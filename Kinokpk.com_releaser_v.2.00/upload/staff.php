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
loggedinorreturn();
stdhead("�������������");
begin_main_frame();
begin_frame("");
?>


<?
$act = $_GET["act"];
if (!$act) {
// Get current datetime
$dt = gmtime() - 300;
$dt = sqlesc(get_date_time($dt));
// Search User Database for Moderators and above and display in alphabetical order
$res = sql_query("SELECT * FROM users WHERE class>=".UC_UPLOADER." AND status='confirmed' ORDER BY username" ) or sqlerr(__FILE__, __LINE__);

while ($arr = mysql_fetch_assoc($res))
{

$staff_table[$arr['class']]=$staff_table[$arr['class']].
"<td class=embedded><a class=altlink href=userdetails.php?id=".$arr['id']."><b>".
get_user_class_color($arr['class'],$arr['username'])."</b></a></td><td class=embedded> ".("'".$arr['last_access']."'">$dt?"<img src=".$pic_base_url."button_online.gif border=0 alt=\"online\">":"<img src=".$pic_base_url."button_offline.gif border=0 alt=\"offline\">" )."</td>".
"<td class=embedded><a href=message.php?action=sendmessage&amp;receiver=".$arr['id'].">".
"<img src=".$pic_base_url."button_pm.gif border=0></a></td>".
" ";



// Show 3 staff per row, separated by an empty column
++ $col[$arr['class']];
if ($col[$arr['class']]<=2)
$staff_table[$arr['class']]=$staff_table[$arr['class']]."<td class=embedded>&nbsp;</td>";
else
{
$staff_table[$arr['class']]=$staff_table[$arr['class']]."</tr><tr height=15>";
$col[$arr['class']]=0;
}
}
begin_frame("�������������");
?>

<table width=100% cellspacing=0>
<tr>
<tr><td class=embedded colspan=11>�������, �� ������� ���� ������ � �������� ��� FAQ, ����� ��������� ��� ��������.</td></tr>
<!-- Define table column widths -->
<td class=embedded width="125">&nbsp;</td>
<td class=embedded width="25">&nbsp;</td>
<td class=embedded width="35">&nbsp;</td>
<td class=embedded width="85">&nbsp;</td>
<td class=embedded width="125">&nbsp;</td>
<td class=embedded width="25">&nbsp;</td>
<td class=embedded width="35">&nbsp;</td>
<td class=embedded width="85">&nbsp;</td>
<td class=embedded width="125">&nbsp;</td>
<td class=embedded width="25">&nbsp;</td>
<td class=embedded width="35">&nbsp;</td>
</tr>
<tr><td class=embedded colspan=11><b>���������� �������</b></td></tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>
<tr height=15>
<?=$staff_table[UC_SYSOP]?>
</tr>
<tr><td class=embedded colspan=11>&nbsp;</td></tr>
<tr><td class=embedded colspan=11><b>��������������</b></td></tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>
<tr height=15>
<?=$staff_table[UC_ADMINISTRATOR]?>
</tr>
<tr><td class=embedded colspan=11>&nbsp;</td></tr>
<tr><td class=embedded colspan=11><b>����������</b></td></tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>
<tr height=15>
<?=$staff_table[UC_MODERATOR]?>
</tr>
<tr><td class=embedded colspan=11>&nbsp;</td></tr>
<tr><td class=embedded colspan=11><b>���������</b></td></tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>
<tr height=15>
<?=$staff_table[UC_UPLOADER]?>
</tr>
</table>
<?
end_frame();
}
?>

<? if (get_user_class() >= UC_SYSOP) { ?>
<? begin_frame("����������� ���������<font color=#FF0000> - ����� ���. ���������������.</font>"); ?>
<table width=100% cellspacing=10 align=center>
<tr>
<td class=embedded align="center" colspan="4"><center><form method=get action=siteonoff.php>
<input type=submit value="���������� ����������� / ���������� ����� � �������� ������� " style='height: 20px; width: 400px'></form></center></td>
</tr>
<tr>
<td class=embedded><form method=get action=spam.php><input type=submit value="�� ������������� :)" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=category.php><input type=submit value="���������" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=stampadmin.php><input type=submit value="������ � ������" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=descrtypesadmin.php><input type=submit value="���� ��������" style='height: 20px; width: 100px'></form></td>
</tr>
<tr>
<td class=embedded><form method=get action=mysqlstats.php><input type=submit value="���������� MySQL" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=banemailadmin.php><input type=submit value="��� �������" style='height: 20px; width: 100px'></form></td>

</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_ADMINISTRATOR) { ?>
<? begin_frame("����������� ���������<font color=#009900> - ����� ���������������.</font>"); ?>
<table width=100% cellspacing=10 align=center>
<tr>
<td class=embedded><form method=get action=unco.php><input type=submit value="�������. �����" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=delacctadmin.php><input type=submit value="������� �����" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=agentban.php><input type=submit value="��� ��������" style='height: 20px; width: 100px' disabled></form></td>
<td class=embedded><form method=get action=bans.php><input type=submit value="����" style='height: 20px; width: 100px'></form></td>
</tr>
<tr>
<td class=embedded><form method=get action=topten.php><input type=submit value="Top 10" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=findnotconnectable.php><input type=submit value="����� �� NAT" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=email.php><input type=submit value="�������� E-mail" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=staffmess.php><input type=submit value="������� ��" style='height: 20px; width: 100px'></form></td>
</tr>
<tr>
<td class=embedded><form method=get action=pollsadmin.php><input type=submit value="������" style='height: 20px; width: 100px'></form></td>
</tr>
</table>
<? end_frame();
}

if (get_user_class() >= UC_MODERATOR) { ?>
<? begin_frame("�������� ��������� - <font color=#004E98>����� �����������.</font>"); ?>


<table width=100% cellspacing=3>
<tr>
<? if (get_user_class() >= UC_MODERATOR) { ?>
</tr>
<tr>
<td class=embedded><a class=altlink href=staff.php?act=users>������������ � ��������� ���� 0.20</a></td>
<td class=embedded>�������� ���� ������������� � ��������� ���� ��� 0.20</td>
</tr>
<tr>
<td class=embedded><a class=altlink href=staff.php?act=banned>����������� ������������</a></td>
<td class=embedded>�������� ���� ����������� �������������</td>
</tr>
<tr>
<td class=embedded><a class=altlink href=staff.php?act=last>����� ������������</a></td>
<td class=embedded>100 ����� ����� �������������</td>
</tr>
<tr>
<td class=embedded><a class=altlink href=log.php>��� �����</a></td>
<td class=embedded>�������� ��� ���� ������/�������/���</td>
</tr>
</table>

<? end_frame(); ?>
<br />
<? begin_frame("���������� � �������� - <font color=#004E98>����� �����������.</font>"); ?>

<br />
<table width=100% cellspacing=3>
<tr>
<td class=embedded></td>

</tr>

</table>
<table width=100% cellspacing=10 align=center>
<tr>
<td class=embedded><form method=get action=warned.php><input type=submit value="�������. �����" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=adduser.php><input type=submit value="�������� �����" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=recover.php><input type=submit value="������. �����" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=uploaders.php><input type=submit value="���������" style='height: 20px; width: 100px'></form></td>
</tr>
<tr>
<td class=embedded><form method=get action=users.php><input type=submit value="������ ������" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=tags.php><input type=submit value="����" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=smilies.php><input type=submit value="������" style='height: 20px; width: 100px'></form></td>
</tr>
<tr>
<td class=embedded><form method=get action=stats.php><input type=submit value="����������" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=testip.php><input type=submit value="�������� IP" style='height: 20px; width: 100px'></form></td>
<td class=embedded><form method=get action=reports.php><input type=submit value="������" style='height: 20px; width: 100px' disabled></form></td>
<td class=embedded><form method=get action=ipcheck.php><input type=submit value="��������� IP" style='height: 20px; width: 100px'></form></td>
</tr>
</table>
<br />

<? end_frame(); ?>

<? begin_frame("������ ������������ - <font color=#004E98>����� �����������.</font>"); ?>


<table width=100% cellspacing=3>
<tr>
<td class=embedded>
<form method=get action="users.php">
�����: <input type=text size=30 name=search>
<select name=class>
<option value='-'>(��������)</option>
<option value=0>������������</option>
<option value=1>������� ������������</option>
<option value=2>VIP</option>
<option value=3>����������</option>
<option value=4>���������</option>
<option value=5>�������������</option>
<option value=6>��������</option>
</select>
<input type=submit value='������'>
</form>
</td>
</tr>
<tr><td class=embedded><li><a href="usersearch.php">���������������� �����</li></a></td></tr>
</table>

<? end_frame(); ?>
<br />
<? if ($act == "users") {
begin_frame("������������ � ��������� ���� 0.20");

echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>������������</td><td class=colhead>�������</td><td class=colhead>IP</td><td class=colhead>���������������</td><td class=colhead>��������� ��� ��� �� �������</td><td class=colhead>������</td><td class=colhead>������</td></tr>";


$result = sql_query ("SELECT * FROM users WHERE uploaded / downloaded <= 0.20 AND enabled = 'yes' ORDER BY downloaded DESC ");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td colspan=7>��������, ������� �� ����������!</td></tr>";}
echo "</table>";
end_frame(); }?>

<? if ($act == "last") {
begin_frame("��������� ������������");

echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>������������</td><td class=colhead>�������</td><td class=colhead>IP</td><td class=colhead>���������������</td><td class=colhead>���������&nbsp;���&nbsp;���&nbsp;��&nbsp;�������</td><td class=colhead>������</td><td class=colhead>������</td></tr>";

$result = sql_query ("SELECT * FROM users WHERE enabled = 'yes' AND status = 'confirmed' ORDER BY added DESC limit 100");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
else {
$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
}
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td>Sorry, no records were found!</td></tr>";}
echo "</table>";
end_frame(); }?>


<? if ($act == "banned") {
begin_frame("��������� ������������");

echo '<table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">';
echo "<tr><td class=colhead align=left>������������</td><td class=colhead>�������</td><td class=colhead>IP</td><td class=colhead>���������������</td><td class=colhead>��������� ��� ���</td><td class=colhead>������</td><td class=colhead>������</td></tr>";
$result = sql_query ("SELECT * FROM users WHERE enabled = 'no' ORDER BY last_access DESC ");
if ($row = mysql_fetch_array($result)) {
do {
if ($row["uploaded"] == "0") { $ratio = "inf"; }
elseif ($row["downloaded"] == "0") { $ratio = "inf"; }
else {
$ratio = number_format($row["uploaded"] / $row["downloaded"], 3);
$ratio = "<font color=" . get_ratio_color($ratio) . ">$ratio</font>";
}
echo "<tr><td><a href=userdetails.php?id=".$row["id"]."><b>".$row["username"]."</b></a></td><td><strong>".$ratio."</strong></td><td>".$row["ip"]."</td><td>".$row["added"]."</td><td>".$row["last_access"]."</td><td>".mksize($row["downloaded"])."</td><td>".mksize($row["uploaded"])."</td></tr>";


} while($row = mysql_fetch_array($result));
} else {print "<tr><td colspan=7>��������, ������� �� ����������!</td></tr>";}
echo "</table>";
end_frame(); } }



}
if (get_user_class() >= UC_USER) {

if (!$act) {
$dt = gmtime() - 180;
$dt = sqlesc(get_date_time($dt));
// LIST ALL FIRSTLINE SUPPORTERS
// Search User Database for Firstline Support and display in alphabetical order
$res = sql_query("SELECT * FROM users WHERE support='yes' AND status='confirmed' ORDER BY username LIMIT 10") or sqlerr(__FILE__, __LINE__);
while ($arr = mysql_fetch_assoc($res))
{
$land = sql_query("SELECT name,flagpic FROM countries WHERE id=$arr[country]") or sqlerr(__FILE__, __LINE__);
$arr2 = mysql_fetch_assoc($land);
$firstline .= "<tr height=15><td class=embedded><a class=altlink href=userdetails.php?id=".$arr['id'].">".$arr['username']."</a></td>
<td class=embedded> ".("'".$arr['last_access']."'">$dt?"<img src=".$pic_base_url."button_online.gif border=0 alt=\"online\">":"<img src=".$pic_base_url."button_offline.gif border=0 alt=\"offline\">" )."</td>".
"<td class=embedded><a href=message.php?action=sendmessage&amp;receiver=".$arr['id'].">"."<img src=".$pic_base_url."button_pm.gif border=0></a></td>".
"<td class=embedded><img src=".$pic_base_url."/flag/$arr2[flagpic] title=$arr2[name] border=0 width=19 height=12></td>".
"<td class=embedded>".$arr['supportfor']."</td></tr>\n";
}

begin_frame("������ ����� ���������");
?>

<table width=100% cellspacing=0>
<tr>
<td class=embedded colspan=11>����� ������� ����� �������� ���� �������������. ������ ��� ��� �����������, �������� ���� ����� � ���� �� ������ ���.
���������� � ��� ���������.<br /><br /><br /></td></tr>
<!-- Define table column widths -->
<tr>
<td class=embedded width="30"><b>������������&nbsp;</b></td>
<td class=embedded width="5"><b>�������&nbsp;</b></td>
<td class=embedded width="5"><b>�������&nbsp;</b></td>
<td class=embedded width="85"><b>����&nbsp;</b></td>
<td class=embedded width="200"><b>��������� ���&nbsp;</b></td>
</tr>


<tr>
<tr><td class=embedded colspan=11><hr color="#4040c0" size=1></td></tr>

<?=$firstline?>

</tr>
</table>
<?
end_frame();
}

?>
<?
end_frame();
end_main_frame();
stdfoot();
}
?>