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

gzip();

dbconn();

//loggedinorreturn();

stdhead("�������");

begin_main_frame();

?>

<? begin_frame("����� ������� - <font color=#004E98>�� ������������ - ���!</font>"); ?>
<ul>
<li>������ ������� �� �������� ���������� � ����������� ��� ���������� ����� ��� ���������� �������������� <?=$DEFAULTBASEURL?> ������ �� <i>�������� ������������</i> �� <i>����������</i> (�������������� � �������� - ��� ����, ��� ������� ��������������� - ��������� �� ������ ����������). ���� ��� �� �������� ��� ������� � �� ������ ��� ���� ������ ������� - �� ������ ������ ������� ���� ����������� ���� � ������ ��� ���, ��� ��� ��������.</li>
<li><b>����� ������������� - ����� ��� ������������� ��������!</b> � ���� ������� ��� ���������� - <?=$SITENAME?> �������� ������� ���������� �������� / ���������, � ��� �������� ������������ ������������� ����������� �������.</li>
<li><a name="warning"></a>�������� ���������� ������ ������� �������� �������������� (<img src="pic/warned.gif"> ). �� ������ �������� ������� <b>5 (����)</b> ��������������. ����� 6 �������������� ��� ������������� ����� �������. �� �������������� ����� ���������� ��������� (���������� ���� �������).</li>
<li>� ������ ����������� ��������� ������, ���������� ����� ������� - ������ � ������� � ��� IP ������ ����� ������.</li>
<li>�� ������������� ��� ���� ��������� ��������� - �� ��������� ��������� ���� ���� ������ � ��� ����� ��������� �������� ������ ����.</li>
<li>������� �� <?=$SITENAME?> ������ �� ������������ <b>������� �����</b>. ������ ����� �� ������ ������������ ������ � ������ ������� �������������. � ������ ���������� ������� ��������� ���������, ����������� <i>��������</i> ��� <i>����������� ���������</i>.
<li>�� ���� ����� ������������ � ����������, ������������ �� ��������� ������ ������ �������. �� ����� ������ ��� �������� �������� � ����� ������� � ����������� � ������ �����. ����� ������� ������ �������� �� ������ �������� � �������� ��������� � <a href="<?=$FORUMURL?>/index.php?showtopic=44987&view=getnewpost">���� ���� �� ������ <?=$FORUMNAME?></a>, ��� ������� �������� ������������� ������ � <b>������� ���������</b>, ��������� ������� ������ ���������.
<li>����������� ��� ����������� ������ <b>�������</b> email �����. �� ����� ������ ������ �������� ��� � ����� ������� ������������. �� �� ����������� ��� ���������� �������� ������ <u>Mail.ru</u> ��� <u>hotmail.com</u> ��-�� ������������ �� ��� ����������� �������� ����-�����. ���� �� ����� �� ��������� ������ � �������������� � �����������/���������������, �������� <a href="faq.php">����</a> ��� ����������� ������ �������� ������. � ���� ������� �� �������� ���, ��� ��� �������� �����, ����� ��� ����� ������ ���� ������������ ����������, �� ����� ������������ �� � ����� ������ ����� � ������� �� ����� �������� ������� �����. �� ����������� ��� ����� ������� �������������� ������ ��� ����� (����� <a href="http://www.icq.com">ICQ</a>, ��� � <a href="http://www.skype.com">Skype</a> ��� � <a href="http://www.msn.com">MSN</a>) � ���� � ��� ������, ���� ��������� ����� �������������.<br />
����� ������� �������� ������, �� ������� 120% ������ ��� ������ - <a target="_blank" href="http://gmail.com">GMail.com</a>.  </li>

<? end_frame(); ?>
<? begin_frame("������� ������� - <font color=#004E98>������ �� ������!</font>"); ?>
<ul>
<li>������ � ������ ������ �������� ����� �� ��������� - ��� ������ �������� �� ��������� �������� ��������� ��-�� ������� �������� ��� ������������. �� ��� �� ������, ��� �� �� ����� �������� �� �����.</li>
<li>�� ���������, �� ������ ���� <b>����� ������������ �� 4 ��������� �������� <u>(VIP - ������������)</u></b>. ���� ��� ����� ��������� �������, �� ������ ��������� � ��������������� �������� � ������ �� ���������������.
<li>������������� ����� ��������� � ��������� � ������������� � �� �������� ������������ ����������� ������������ ������ "�� ���������". �� �������� ��� �� ��� �� ������ ��������� � ������ �������������, ��������� � � �������������.</li>
<li>����������� ����������� "������/�����" ����� 1, �� ���� ������� ������ - ������� � �����. ���������� ������������ ���, ���� ������ ������ � ����������� ����� �� ����� ��������, ��� ������, � �� ����� ������: �� ���������� ��� ���������� ������ ��� ����� ������! � ������, ���� �� �������� ������������ ����� (���������� ������ �����) �� �������, ���������� ����������� �� �������, ���� ���� ��� ������� ���� 1 - ������� �� ������� ����-��, ������ �� ������� ���. ���� ������� ����� ��������� ����� � ������� <b>�������</b></li>
<li>���� � ��� ��������� ����� �������� �� ����������� (������ ����������� ���������� ���� ����������, �� �� ������ ����������� � �������, �������� ����� ������� ����� ������), �������� ���� <a href="faq.php">����</a> - �� ������ � ��� ��� ����������� ��� ����������. � ������ ������������� �����-���� �������� �� ���������� �������, ������� ���������� �� � �������������, � � ����������, ��������� ������� ������ ��������� ��� ������������ � ������.</li>
<? end_frame(); ?>
<? begin_frame("������� ��������������� �������"); ?>
<? begin_frame(); ?>
<b>�����������, ���������� ���������� �������� �/��� ����������������, ���������� � ������� - ������ �� ��������, � �� �� ������ ����!</b>
<? end_frame(); ?>
<ul>
<li>������� ������������ ��������� ������� ��� ���� �����: (1) ��������� ���� <i>��������</i> � <i>�������������</i> ����������, (2) ������ ������������ ��� <i>���������� ����������� ������</i> ������������ ������,(3) �������� <i>���������� ����������</i>, ����������� � �������.
<li>�������� ������� ����� ����� ������������� �������� � ������������.
<li>��������� ����� � ����.
<li>��������� ������ �� �����-�������.
</li>
<? end_frame(); ?>
<? begin_frame("������������ � �������� - <font color=#004E98>������������ ������� ��������� �������������� ��������</font>"); ?>
<ul>
<li>��������� ������� .gif, .jpg � .png.</li>
<li>������������� ���������: <b><?=$avatar_max_width;?> X <?=$avatar_max_height;?> ��������</b> � ������ � �� �������� ����� 60 K�.</li>
<li>�� ����������� �������������� ��������� (� ������: ����������� � ������������ ���������, ���������, ������������ ����������, ������� � �����������). ������������? �������� <a href="staff.php">�������������</a>.</li>
<? end_frame(); ?>

<? if (get_user_class() >= UC_USER) { ?>

<? begin_frame("������� �������� - <font color=#004E98>��������� ��� <i>����</i> �������</font>"); ?>
<ul>
<li>��� ������ ������ ������������ [����� HTTP, FTP, eDonkey] (�.�. �� ������ ���������� � ���� � ������������ ����� ��� �������), ���� �� �� ������ ���������� ����� ����� ������ ������, �������� ��������������� ����������� � ����� ������.</li>
<li>�� ������ ��������� ������, ����������� �� �����! ������������ �� ����� ������ �� ��������������!</li>
<li>�� ����������� �������������� �� ��������� ������ �� 100 ����/���. ��������, <a target="_blank" href="http://megaupload.com">Megaupload.com</a> , <a target="_blank" href="http://narod.yandex.ru/disk/">�����.���� (Yandex)</a> | <a target="_blank" href="http://rapidshare.com">Rapidshare.com</a> - <i>���� ������ ������ ������ ����� 100 ��</i></li>
<li>�� ����������� ��������� ��������� �������� ������. �� ������ ����� ���������� � ����� � ������, ��������� <a target="_blank" href="http://google.ru">Google.ru</a>.</li>
<li>������� � ������� �����������! ����� <i>������ ����������</i></li>
<li>�� ��������� ������� � ������ � "�������������" ����������. �� ������� ���� ��� � �����!</li>
<li>�� ��������� �������� ������� ��� �����, ������� ������� ��� ������� - <b>��� ������� ��� �������!</b></li>
</ul>
<br />
<ul>
������� �� ��������� :)
<? end_frame(); ?>

<? begin_frame("������� �������� - <font color=#004E98>���� ����-�� �� �� ����� �� �����</font>"); ?>
<ul>
<li>��������� ������� ��������� �������</li>
<li>���� ������ �� �������� �� � ���� ���������, ��� ��������� - <b>������</b></li>
<li>�������� �������� ���������� � ������ (��������, ������ �� ��������/���� � �.�.)</li>
<li>�� �������, ������ ������� �������� ������� ��� ���� (������!, �� ��������, � 28.09.2034 � �.�.)</li>
<li>�������, ��� ������ - ��� ������. <font color="green">�� ��������� - �� ��� ������</font>. <font color="red">�� ����� ������ � ����� ����������, �����������, �������������, � ����� �� �����. <br /><br />
�� ���������� ���� "��������������� � ��������" "��������, � ���� ���� ����� ����������!!!" � "�� ��� ����� �����, ��� �������� ��� ��������" - ��� ��������.</font></li>
<? end_frame(); ?>

<? begin_frame("������� ����������� - <font color=#004E98>���� ������� ����-�� ����������</font>"); ?>
<ul>
<li>��������� ������� ��������� �����������, ���� ����������� �� ����� - ��� ��������� <b>������</b></li>
<li>�������, ��� ����������� - ��� �����������. <font color="green">�� ���������� - �� ������</font>. <font color="red">�� ����� ������ � ����� ����������, �����������, �������������, � ����� �� �����. <br /><br />
�� ���������� ���� "��������������� � �����������" "��������, � ���� ��� ����������!!!" � "�� ��� ����� �����, ��� �������� ��� �������" - ��� ��������.</font></li>
<? end_frame(); ?>

<? } if (get_user_class() >= UC_MODERATOR) { ?>

<? begin_frame("������ �� $SITENAME"); ?>
<br />
<table border="0" cellspacing="3" cellpadding="0">
<tr>
	<td class="embedded" bgcolor="#F5F4EA" valign="top">&nbsp;<b><?=get_user_class_color(0,"������������");?></b></td>
	<td class="embedded" width="5">&nbsp;</td>
	<td class="embedded">�������, ���������� ������������ �������</td></tr>
<tr>
	<td class="embedded" bgcolor="#F5F4EA" valign="top">&nbsp; <b><?=get_user_class_color(1,"������� ������������");?></b></td>
	<td class="embedded" width="5">&nbsp;</td>
	<td class="embedded">������ ������������� ����������� (� ��������) ��� ������ � �������������, ��� ������� ������� �� ����� 4 ������ � ����� ������� 1.05 � ����. ��������� ����� ������� ��������� ���� ������ �� ���������� ��������������� ���������� �������.</td>

<tr>
	<td class="embedded" bgcolor="#F5F4EA" valign="top">&nbsp; <b><?=get_user_class_color(2,"VIP");?></b></td>
	<td class="embedded" width="5">&nbsp;</td>
	<td class="embedded">��� "���������" ������� ;)</td>
</tr>
<tr>
<td class="embedded" align="center"><h2>������� <?=$DEFAULTBASEURL;?></h2></td>
</tr>
<tr>
	<td class="embedded" bgcolor="#F5F4EA" valign="top">&nbsp; <b><?=get_user_class_color(3,"��������");?></b></td>
	<td class="embedded" width="5">&nbsp;</td>
	<td class="embedded">�������, ������� ������� ����������� ������</td>
</tr>
<tr>
	<td class="embedded" bgcolor="#F5F4EA" valign="top">&nbsp; <b><?=get_user_class_color(4,"���������");?></b></td>
	<td class="embedded" width="5">&nbsp;</td>
	<td class="embedded">����������� �������������� � ����� ������� �����������.</td>
</tr>
<tr>
	<td class="embedded" bgcolor="#F5F4EA" valign="top">&nbsp; <b><?=get_user_class_color(5,"�������������");?></b></td>
	<td class="embedded" width="5">&nbsp;</td>
	<td class="embedded">���� ���������������� ����������.</td>
</tr>
<tr>
	<td class="embedded" bgcolor="#F5F4EA" valign="top">&nbsp; <b><?=get_user_class_color(6,"��������/��������");?></b></td>
	<td class="embedded" width="5">&nbsp;</td>
	<td class="embedded">���������� � ���.�������������� �������</td>
</tr>
</table>
<br />
<?
	end_frame();
  if (get_user_class() >= UC_MODERATOR) {
	begin_frame("������� �������������");
?>
<ul>

<li>�� ������� ������� <b>���</b>!
<li>������ ������������! ����� ������������(��) ���� �� ������������.</li>
<li>������ ��� ��������� �������, �������� ���/�� �� � ���� ��� �������, ���������� �� ������������� ���� �� 2 ������.</li>
<li><b>������</b> ���������� ������� (� ���� �����������) ������ �� �������� / ������������ ������������.</li>
<br />

<?
	end_frame();
	begin_frame("����������� ����������� - <font color=#004E98>����� ��� ���������� ��� ����������?</font>");
?>
<ul>
<li>�� ������ ������� � ������������� ������.</li>
<li>�� ������ ������� � ������������� ������� �������������.</li>
<li>�� ������ ��������� �������������.</li>
<li>�� ������ ������������� ������ VIP'��.</li>
<li>�� ������ ������ ������ ���������� � �������������.</li>
<li>�� ������ ��������� ���������� � ������������� (��� ������ ����������� � ���������������).</li>
<li>�� ������ ��������� ������ ������-��� �� ��� ������ ��� ��� �����������. ;)</li>
<li>� ����� ������ ���������� ��������� <a href="staff.php" class="altlink">�������������</a> (������ ������� ����).</li>

<? end_frame();
}?>

<p align="right"><font size="1" color="#004E98"><b>������� ������� �������������� 28.03.2008 19:33 ��������������� ZonD80</b></font></p>
<p align="right"><font size="1" color="#004E98"><b>��������� ���������� 20.07.2008 11:56 ��������������� ZonD80</b></font></p>

<? }
end_main_frame();
stdfoot(); ?>