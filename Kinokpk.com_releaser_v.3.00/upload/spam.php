<?php
/**
 * Private messages admin viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";
dbconn();
loggedinorreturn();
httpauth();

if (get_user_class() < UC_SYSOP)
stderr($tracker_lang['error'],$tracker_lang['access_denied']);

$res2 = sql_query("SELECT SUM(1) FROM messages");
$row = mysql_fetch_array($res2);
$count = $row[0];

if (!$count){
	stderr("��������, �� ��������� �� �������.");
}

$perpage = 50;

list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, "spam.php?" . $addparam);

stdhead("�� �������������");

?>

<form method="post" action="take-delmp.php" name="form1" id="message">

<table border="1" cellspacing="1" cellpadding="1" width="100%">
	<tr>
		<td colspan="5" class=colhead align=center>�������� ��������� (����� <font
			color="red"><?=$count?></font>)</td>
	</tr>
	<tr>
		<td colspan="5">
		<div><?=$pagertop?></div>
		<div style="float: right;"><input type="submit"
			value="������� ���������!" onClick="return confirm('�� �������?')"></div>
		</td>
	</tr>
	<tr>
		<td class=colhead align=center>�����������/����������</td>
		<td class=colhead align=center>ID</td>
		<td class=colhead align=center>����������</td>
		<td class=colhead align=center>����</td>
		<td class=colhead>
		<center><INPUT type="checkbox" title="������� ���" value="������� ���"
			id="toggle-all"></center>
		</td>
	</tr>
	<tr>
	<?
	$res = sql_query("SELECT * FROM messages $where ORDER BY id DESC $limit") or sqlerr(__FILE__, __LINE__);
	while ($arr = mysql_fetch_assoc($res))
	{
		$res2 = sql_query("SELECT username, class FROM users WHERE id=".$arr["receiver"]) or sqlerr(__FILE__, __LINE__);
		$arr2 = mysql_fetch_assoc($res2);

		if($arr["receiver"] == 0 or !$arr["receiver"]){
			$receiver = "<strike><b>����������</b></strike>";
		} else {
			$receiver = "<a href=userdetails.php?id=".$arr["receiver"].">".get_user_class_color($arr2["class"], $arr2["username"])."</a>";
		}

		$res3 = sql_query("SELECT username, class FROM users WHERE id=".$arr["sender"]) or sqlerr(__FILE__, __LINE__);
		$arr3 = mysql_fetch_assoc($res3);

		if($arr["sender"] == 0){
			$sender = "<font color=red><b>���������</b></font>";
		} else {
			$sender = "<a href=userdetails.php?id=".$arr['sender'].">".get_user_class_color($arr3["class"], $arr3["username"])."</a>";
		}
		$msg = format_comment($arr['msg']);
		$added = mkprettytime($arr['added']);

		print("<td align='left'>
        <div style='padding-top:5px; padding-bottom:10px;'>�����������:&nbsp;".$sender."</div>
        <div style='padding-top:10px; padding-bottom:5px;'>����������:&nbsp;".$receiver."</div>
        </td><td align=center><a href=\"message.php?action=viewmessage&id=".$arr["id"]."\">".$arr["id"]."</a></td>
        <td>$msg</td>
        <td align=center>$added</td>");
		print("<TD align=center><INPUT type=\"checkbox\" name=\"delmp[]\" value=\"".$arr['id']."\" id=\"checkbox_tbl_".$arr['id']."\">
          </TD></tr>");
	}
	?>
		<tr>
			<td class=colhead colspan="4"></td>
			<td class=colhead>
			<center><INPUT type="checkbox" title="������� ���"
				value="������� ���" id="toggle-all"></center>
			</td>
		</tr>

		<?
		if ($where && $count){
			?>
		<tr>
			<td colspan="5"><a href="spion.php">��������� � ������ ������
			���������</a></td>
		</tr>
		<?}?>

		<tr>
			<td colspan="5">
			<div><?=$pagerbottom?></div>
			<div style="float: right;"><input type="submit"
				value="������� ���������!" onClick="return confirm('�� �������?')">
			</div>
			</td>
		</tr>

</table>
</form>
<br />
		<?
		stdfoot();
		?>