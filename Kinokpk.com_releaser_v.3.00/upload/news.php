<?php
/**
 * News viewver & admincp
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";

dbconn();
loggedinorreturn();

if (get_user_class() < UC_ADMINISTRATOR)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);

$action = (string)$_GET["action"];

//   Delete News Item    //////////////////////////////////////////////////////

if ($action == 'delete')
{
	$newsid = (int)$_GET["newsid"];
	if (!is_valid_id($newsid))
	stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

	$returnto = makesafe($_GET["returnto"]);

	sql_query("DELETE FROM news WHERE id=$newsid") or sqlerr(__FILE__, __LINE__);
	sql_query("DELETE FROM newscomments WHERE news=$newsid") or sqlerr(__FILE__, __LINE__);
	sql_query("DELETE FROM notifs WHERE type='newscomments' AND checkid=$newsid") or sqlerr(__FILE__, __LINE__);

	$CACHE->clearGroupCache("block-news");
	if ($returnto != "")
	safe_redirect($returnto);
	else
	$warning = "������� <b>�������</b> �������";
}

elseif ($action == 'add')
{

	$subject = htmlspecialchars((string)$_POST["subject"]);
	if (!$subject)
	stderr($tracker_lang['error'],"���� ������� �� ����� ���� ������!");

	$body = ((string)$_POST["body"]);
	if (!$body)
	stderr($tracker_lang['error'],"���� ������� �� ����� ���� ������!");

	$added = time();

	sql_query("INSERT INTO news (userid, added, body, subject) VALUES (".
	$CURUSER['id'] . ", $added, " . sqlesc($body) . ", " . sqlesc($subject) . ")") or sqlerr(__FILE__, __LINE__);

	$CACHE->clearGroupCache("block-news");
	$warning = "������� <b>������� ���������</b>";

}

elseif ($action == 'edit')
{

	$newsid = (int)$_GET["newsid"];

	if (!is_valid_id($newsid))
	stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$body = (string)$_POST['body'];
		$subject = htmlspecialchars((string)$_POST['subject']);

		if (!$subject)
		stderr($tracker_lang['error'],"���� ������� �� ����� ���� ������!");

		if (!$body)
		stderr($tracker_lang['error'], "���� ������� �� ����� ���� ������!");

		$body = sqlesc(($body));

		$subject = sqlesc($subject);

		$editedat = sqlesc(time());

		sql_query("UPDATE news SET body=$body, subject=$subject WHERE id=$newsid") or sqlerr(__FILE__, __LINE__);

		$CACHE->clearGroupCache("block-news");

		$returnto = makesafe($_POST['returnto']);

		if ($returnto != "")
		safe_redirect($returnto);
		else
		$warning = "������� <b>�������</b> ���������������";
	}
	else
	{
		$res = sql_query("SELECT * FROM news WHERE id=$newsid") or sqlerr(__FILE__, __LINE__);

		if (mysql_num_rows($res) != 1)
		stderr($tracker_lang['error'],$tracker_lang['invalid_id']);

		$arr = mysql_fetch_array($res);
		$returnto = makesafe($_GET['returnto']);
		stdhead("�������������� �������");
		print("<form method=post name=news action=news.php?action=edit&newsid=$newsid>\n");
		print("<table border=1 cellspacing=0 cellpadding=5>\n");
		print("<tr><td class=colhead>�������������� �������<input type=hidden name=returnto value=$returnto></td></tr>\n");
		print("<tr><td>����: <input type=text name=subject maxlength=70 size=50 value=\"" . makesafe($arr["subject"]) . "\"/></td></tr>");
		print("<tr><td style='padding: 0px'>");
		print textbbcode("body",$arr["body"]);
		//<textarea name=body cols=145 rows=5 style='border: 0px'>" . htmlspecialchars($arr["body"]) .
		print("</textarea></td></tr>\n");
		print("<tr><td align=center><input type=submit value='���������������'></td></tr>\n");
		print("</table>\n");
		print("</form>\n");
		stdfoot();
		die;
	}
}

//   Other Actions and followup    ////////////////////////////////////////////

stdhead("�������");
if ($warning)
print("<p><font size=-3>($warning)</font></p>");
print("<form method=post name=news action=news.php?action=add>\n");
print("<table border=1 cellspacing=0 cellpadding=5>\n");
print("<tr><td class=colhead>�������� �������</td></tr>\n");
print("<tr><td>����: <input type=text name=subject maxlength=40 size=50 value=\"" . makesafe($arr["subject"]) . "\"/></td></tr>");
print("<tr><td style='padding: 0px'>");
print textbbcode("body",$arr["body"]);
//<textarea name=body cols=145 rows=5 style='border: 0px'>
print("</textarea></td></tr>\n");
print("<tr><td align=center><input type=submit value='��������' class=btn></td></tr>\n");
print("</table></form><br /><br />\n");

stdfoot();
?>