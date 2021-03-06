<?php
/**
 * Password recovery
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";

dbconn();
getlang('contact');

if ($CACHEARRAY['use_integration'] && $CACHEARRAY['ipb_password_priority']) safe_redirect(" ".$CACHEARRAY['forumurl']."/index.php?act=Reg&CODE=10");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if ($CACHEARRAY['use_captcha']) {
		require_once(ROOT_PATH.'include/recaptchalib.php');
		$resp = recaptcha_check_answer ($CACHEARRAY['re_privatekey'],
		$_SERVER["REMOTE_ADDR"],
		$_POST["recaptcha_challenge_field"],
		$_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) stderr($tracker_lang['error'], $tracker_lang['test_humanity']);
	}

	$email = trim(htmlspecialchars((string)$_POST["email"]));
	if (!$email || !validemail($email))
	stderr($tracker_lang['error'], "�� ������ ������ email �����");
	$res = sql_query("SELECT * FROM users WHERE email=" . sqlesc($email) . " LIMIT 1") or sqlerr(__FILE__, __LINE__);
	$arr = mysql_fetch_array($res) or stderr($tracker_lang['error'], "Email ����� �� ������ � ���� ������.\n");

	$sec = mksecret();

	sql_query("UPDATE users SET editsecret=" . sqlesc($sec) . " WHERE id=" . $arr["id"]) or sqlerr(__FILE__, __LINE__);
	if (!mysql_affected_rows())
	stderr($tracker_lang['error'], "������ ���� ������. ��������� � ��������������� ������������ ���� ������.");

	$hash = md5($sec . $email . $arr["passhash"] . $sec);

	$body = <<<EOD
��, ��� ���-�� ������, ��������� ����� ������ � �������� ��������� � ���� ������� ($email).

���� ��� ���� �� ��, �������������� ��� ������. ��������� �� ���������.

���� �� ������������� ���� ������, ��������� �� ��������� ������:

{$CACHEARRAY['defaultbaseurl']}/recover.php?confirm&id={$arr["id"]}&secret=$hash


����� ���� ��� �� ��� ��������, ��� ������ ����� ������� � ����� ������ ����� ��������� ��� �� E-Mail.

--
{$CACHEARRAY['sitename']}
EOD;

if (sent_mail($arr['email'], $CACHEARRAY['sitename'], $CACHEARRAY['siteemail'],  "{$CACHEARRAY['defaultbaseurl']} �������������� ������",  wordwrap($body,70))==false) stderr($tracker_lang['error'],"������ ��� �������� ������");

stderr($tracker_lang['success'], "�������������� ������ ���� ����������.\n" .
		" ����� ��������� ����� (������ �����) ��� ������� ������ � ����������� ����������.");
}
elseif(isset($_GET['confirm']))
{

	if (!is_valid_id($_GET["id"]))
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

	$id = (int) $_GET["id"];
	$md5 = $_GET["secret"];

	$res = sql_query("SELECT username, email, passhash, editsecret FROM users WHERE id = $id");
	$arr = mysql_fetch_array($res) or stderr($tracker_lang['error'],"��� ������������ � ����� ID");

	$email = $arr["email"];

	$sec = hash_pad($arr["editsecret"]);
	if (preg_match('/^ *$/s', $sec))
	stderr($tracker_lang['error'],"������ ���������� ���� �������������");
	if ($md5 != md5($sec . $email . $arr["passhash"] . $sec))
	stderr($tracker_lang['error'],"��� ������������� �������");

	// generate new password;
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

	$newpassword = "";
	for ($i = 0; $i < 10; $i++)
	$newpassword .= $chars[mt_rand(0, strlen($chars) - 1)];

	$sec = mksecret();

	$newpasshash = md5($sec . $newpassword . $sec);
	//$username = @mysql_result(sql_query("SELECT username FROM users WHERE id=$id"),0);

	sql_query("UPDATE users SET secret=" . sqlesc($sec) . ", editsecret='', passhash=" . sqlesc($newpasshash) . " WHERE id=$id AND editsecret=" . sqlesc($arr["editsecret"]));

	//if ($username) change_ipb_password($newpassword,$username);

	if (!mysql_affected_rows())
	stderr($tracker_lang['error'], "���������� �������� ������ ������������. ��������� ��������� � ��������������� ������������ ���� ������.");

	$body = <<<EOD
�� ������ ������� �� �������������� ������, �� ������������� ��� ����� ������.

��� ���� ����� ������ ��� ����� ��������:

    ������������: {$arr["username"]}
    ������:       $newpassword

�� ������ ����� �� ���� ���: {$CACHEARRAY['defaultbaseurl']}/login.php

--
{$CACHEARRAY['sitename']}
EOD;

$mail_sent = sent_mail($email,$CACHEARRAY['sitename'],$CACHEARRAY['siteemail'], "{$CACHEARRAY['defaultbaseurl']} ������ ��������", $body);
if (!$mail_sent) stderr($tracker_lang['error'],'Mail not sent, configure smtp/sendmail or contact site admin');
stderr($tracker_lang['success'], "����� ������ �� �������� ���������� �� E-Mail <b>$email</b>.\n" .
    "����� ��������� ����� (������ �����) �� �������� ���� ����� ������.");
}
else
{
	stdhead("�������������� ������");
	?><form method="post" action="recover.php"><table border="1" cellspacing="0" cellpadding="5">	<tr>		<td class="colhead" colspan="2">�������������� ����� ������������ ���		������</td>	</tr>	<tr>		<td colspan="2">����������� ����� ���� ��� ������������� ������<br />		� ���� ������ ����� ���������� ��� �� �����.<br />		<br />		�� ����� ������ ����������� ������.</td>	</tr>	<tr>		<td class="rowhead">����������������� email</td>		<td><input type="text" size="40" name="email"></td>	</tr>	<?php
	if ($CACHEARRAY['use_captcha']) {
		require_once(ROOT_PATH.'include/recaptchalib.php');
		print '<tr><td colspan="2" align="center">'.$tracker_lang['you_people'].'</td></tr>';
		print '<tr><td colspan="2" align="center">'.recaptcha_get_html($CACHEARRAY['re_publickey']).'</td></tr>';
	}
	?>	<tr>		<td colspan="2" align="center"><input type="submit"			value="������������"></td>	</tr></table>	<?
	stdfoot();
}

?>