<?

/*
Project: Kinokpk.com releaser
This file is part of Kinokpk.com releaser.
Kinokpk.com releaser is based on TBDev,
originally by RedBeard of TorrentBits, extensively modified by
Gartenzwerg and Yuna Scatari.
Kinokpk.com releaser is free software;
you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
Kinokpk.com is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with Kinokpk.com releaser; if not, write to the
Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
MA  02111-1307  USA
Do not remove above lines!
*/

require "include/bittorrent.php";
dbconn();

$id = 0 + $_GET["id"];
if (!$id)
	stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$res = sql_query("SELECT username, class, email FROM users WHERE id=$id");
$arr = mysql_fetch_assoc($res) or stderr($tracker_lang['error'], "��� ������ ������������.");
$username = $arr["username"];
if ($arr["class"] < UC_MODERATOR)
	stderr($tracker_lang['error'], $tracker_lang['access_denied']);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$to = $arr["email"];

	$from = substr(trim($_POST["from"]), 0, 80);
	if ($from == "") $from = "��������";

	$from_email = substr(trim($_POST["from_email"]), 0, 80);
	if ($from_email == "") $from_email = $SITEEMAIL;
	if (!strpos($from_email, "@")) stderr($tracker_lang['error'], "�������� e-mail ����� �� ����� �� ������.");

	$from = "$from <$from_email>";

	$subject = substr(trim($_POST["subject"]), 0, 80);
	if ($subject == "") $subject = "(��� ����)";
	$subject = "Fwd: $subject";

	$message = trim($_POST["message"]);
	if ($message == "") stderr($tracker_lang['error'], "�� �� ����� ���������!");

	$message = "��������� ���������� � IP ������ $_SERVER[REMOTE_ADDR] � " . date("Y-m-d H:i:s") . " GMT.\n" .
		"��������: ������� �� ��� ������, �� ��������� ��� e-mail �����.\n" .
		"---------------------------------------------------------------------\n\n" .
		$message . "\n\n" .
		"---------------------------------------------------------------------\n$SITENAME E-Mail ����\n";

	$success = @mail($to, $subject, $message, "From: $from", "-f$SITEEMAIL");

	if ($success)
		stderr($tracker_lang['success'], "E-mail ������� ���������.");
	else
		stderr($tracker_lang['error'], "������ �� ����� ���� ����������. ����������, ���������� �����.");
}

stdhead("E-mail ����");
?>
<table border=1 cellspacing=0 cellpadding=5>
<tr><td class=colhead colspan=2>��������� e-mail ������������ <?=$username;?></td></tr>
<form method=post action=email-gateway.php?id=<?=$id?>>
<tr><td class=rowhead>���� ���</td><td><input type=text name=from size=80></td></tr>
<tr><td class=rowhead>��� e-mail</td><td><input type=text name=from_email size=80></td></tr>
<tr><td class=rowhead>����</td><td><input type=text name=subject size=80></td></tr>
<tr><td class=rowhead>���������</td><td><textarea name=message cols=80 rows=20></textarea></td></tr>
<tr><td colspan=2 align=center><input type=submit value="Send" class=btn></td></tr>
</form>
</table>
<p>
<font class=small><b>��������:</b> ��� IP-����� ����� ������� � ����� ����� ����������, ��� ������������� ������.<br />
��������� ��� �� ����� ��������� e-mail ����� ���� �� �������� ������.</font>
</p>
<? stdfoot(); ?>