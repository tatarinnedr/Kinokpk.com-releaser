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

require_once("include/bittorrent.php");

dbconn();

if ($CURUSER)
	stderr($tracker_lang['error'], "�� ��� ����� �� $SITENAME!");

stdhead("����");

unset($returnto);
if (!empty($_GET["returnto"])) {
	$returnto = $_GET["returnto"];
	if (!$_GET["nowarn"]) {
		$error = "<tr><td colspan=\"2\"><div class=\"error\">� ��������� ��������, ������� �� ��������� ���������� <b>�������� ������ �������� � �������</b>.<br />����� ��������� ����� �� ������ �������������� �� ���������� ��������.</div></td></tr>";
		//print("<h1>�� ��������������!</h1>\n");
		//print("<p><b>������:</b> ��������, ������� �� ��������� ����������, �������� ������ �����������������.</p>\n");
	}
}

?>
<div align="center">
<form method="post" action="takelogin.php">
<p><b>��������</b>: ��� ��������� ����� ������������� cookies.</p>
<p><font color="green">�� ������ ����� �� ����� ������� � ������� �� ������ <?=$FORUMNAME?>. ��������������� ������ �� ����!</font> </p>
<table border="0" cellpadding="5" width="100%">
<?
if (isset($error)) {
	echo $error;
}
?>
<tr><td class="rowhead">������������:</td><td align="left"><input type="text" size="40" name="username" style="width: 200px; border: 1px solid gray" /></td></tr>
<tr><td class="rowhead">������:</td><td align="left"><input type="password" size="40" name="password" style="width: 200px; border: 1px solid gray" /></td></tr>
<tr><td colspan="2" align="center"><input type="submit" value="�����" class="btn"></td></tr>
</table>
<?

if (isset($returnto))
	print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars_uni($returnto) . "\" />\n");

?>
</form>
<p>���� �� ������ ������ ��� �� �� ������ ����� - ����������� ��������������� ������ <a href="recover.php">�������������� �������</a></p>
<p>��� �� ���������������� ? �� ������ <a href="signup.php">������������������</a> ����� ������!</p>
</div>
<?

stdfoot();

?>