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

dbconn(false);

loggedinorreturn();
parked();

stdhead($tracker_lang['upload_torrent']);

if (!isset($_GET['type'])) {

begin_frame("�������� ��������� ������");
?>
<div align=center>
<form name="upload" action="upload.php" method="GET">
<table border="1" cellspacing="0" cellpadding="5">
<?

$s = "<select name=\"type\">\n<option value=\"0\">(".$tracker_lang['choose'].")</option>\n";

$cats = genrelist();
foreach ($cats as $row)
	$s .= "<option value=\"" . $row["id"] . "\">" . htmlspecialchars($row["name"]) . "</option>\n";

$s .= "</select>\n";
echo $s;

?>
<tr><td align="center" colspan="2" style="border:0;"><input type="submit" class=btn value="�����" /></td></tr>
</table>
</form>

<?php
end_frame();
stdfoot();
die();
}
elseif (!is_valid_id($_GET["type"])) {			stdmsg($tracker_lang['error'],$tracker_lang['invalid_id']); stdfoot();   exit;}

$type = $_GET['type'];

if (get_user_class() < UC_USER)
{
  stdmsg($tracker_lang['error'], $tracker_lang['access_denied']);
  stdfoot();
  exit;
}

if (strlen($CURUSER['passkey']) != 32) {
$CURUSER['passkey'] = md5($CURUSER['username'].get_date_time().$CURUSER['passhash']);
sql_query("UPDATE users SET passkey='$CURUSER[passkey]' WHERE id=$CURUSER[id]");
}

$descrrow = sql_query("SELECT id FROM descr_types WHERE category = ".intval($type));
$descrtype = @mysql_result($descrrow,0);
?>
<script type="text/javascript" language="javascript">
function kinopoiskparser()
{
windop = window.open("parser.php","mywin","height=600,width=400,resizable=no,scrollbars=yes");
}

function tagcounter(checked)
{
  var i=document.forms['upload'].elements['tagcount'].value;
 if (checked) {
 document.forms['upload'].elements['tagcount'].value = parseInt(i)+1;
 return i;
 }
 else {
 document.forms['upload'].elements['tagcount'].value = parseInt(i)-1;
 return '';

}
}
</script>
<form name="upload" enctype="multipart/form-data" action="takeupload.php" method="post" >
<input type="hidden" name="MAX_FILE_SIZE" value="<?=$max_torrent_size?>" />
<input type="hidden" name="reltype" value="<?=$descrtype?>" />
<table border="1" cellspacing="0" cellpadding="5">
<tr><td class="colhead" colspan="2"><?print $tracker_lang['upload_torrent'].(($descrtype == 1)?' [<a href="javascript:kinopoiskparser(\'parser\');">��������� �����, ��������� ������ Kinopoisk.ru</a>]':'')?></td></tr>
<?
//tr($tracker_lang['announce_url'], $announce_urls[0], 1);
tr($tracker_lang['torrent_file'], "<input type=file name=tfile size=80>\n", 1);
tr($tracker_lang['torrent_name']."<font color=\"red\">*</font>", "<input type=\"text\" name=\"name\" size=\"80\" /><br />(".$tracker_lang['taken_from_torrent'].")\n", 1);
tr($tracker_lang['images'], $tracker_lang['max_file_size'].": 500kb<br />".$tracker_lang['avialable_formats'].": .jpg .png .gif<br /><b>".$tracker_lang['image']." (������):</b>&nbsp&nbsp<input type=file name=image0 size=80><br /><b>".$tracker_lang['image']." (����):</b>&nbsp&nbsp<input type=file name=image1 size=80><hr/><div align=\"center\"><b>��� �� ������ ������� URL ��������</a></div><b>".$tracker_lang['image']." (������):</b>&nbsp&nbsp<input type=\"text\" size=\"60\" name=\"img0\"><br/><b>".$tracker_lang['image']." (����):</b>&nbsp&nbsp<input type=\"text\" size=\"60\" name=\"img1\">\n", 1);

$descrtarray = mysql_query("SELECT * FROM descr_details WHERE typeid = ".intval($descrtype)." ORDER BY sort ASC");

while ($dd = mysql_fetch_array($descrtarray)) {
  if ($dd['input'] == 'text')
  tr($dd['name'].(($dd['required'] == 'yes')?"<font color=\"red\">*</font>":""),'<input type="text" size="'.$dd['size'].'" name="val['.$dd['id'].']"><hr/>'.$dd['description'],1);

  elseif ($dd['input'] == 'option') {
  unset($optdescr);
  $optvalues = explode(",",$dd['mask']);
  if (!empty($dd['description'])) $optdescr = explode(",",$dd['description']);
$s = "<select name=\"val[" .$dd['id']. "]\">\n<option value=\"\">(".$tracker_lang['choose'].")</option>\n";


foreach($optvalues as $i => $opt) {

	$s .= "<option value=\"".$opt."\">" . htmlspecialchars((is_array($optdescr)?$optdescr[$i]:$opt)) . "</option>\n";

}

$s .= "</select>\n";
tr($dd['name'].(($dd['required'] == 'yes')?"<font color=\"red\">*</font>":""),$s,1,1);
  }

  elseif ($dd['input'] == 'links') {
   tr($dd['name'].(($dd['required'] == 'yes')?"<font color=\"red\">*</font>":""),"<textarea name=\"val[".$dd['id']."]\" rows=\"3\" cols=\"60\" wrap=\"soft\"></textarea><hr/>".$dd['description']."\n",1);
  }

  elseif ($dd['input'] == 'bbcode') {
  print("<tr><td class=rowhead style='padding: 3px'>".$dd['name'].(($dd['required'] == 'yes')?"<font color=\"red\">*</font>":"")."</td><td>");
  textbbcode("upload","val[".$dd['id']."]");
  print("<hr/>".$dd['description']."</td></tr>\n");
  }

}

$s = "<table width=\"100%\"><tr><td>������� �����: <input type=\"text\" name=\"tagcount\" value=\"0\" readonly/></td></tr>";
$tags = taggenrelist($type);
if (!$tags)
$s .= "��� ����� ��� ������ ���������. �� ������ �������� �����������.";
else
  {

   foreach ($tags as $row) {


   $tag = htmlspecialchars($row["name"]);
   $s .= "<tr><td><input type=\"checkbox\" name=\"tags[]\" value=\"$tag\" onClick=\"this.name='tags['+tagcounter(this.checked)+']';\"> $tag</td></tr>";


   }
  }
$s .= "</table>\n";
tr("���� (�����)", $s, 1);

if(get_user_class() >= UC_ADMINISTRATOR)
	tr($tracker_lang['golden'], "<input type=checkbox name=free value=yes> ".$tracker_lang['golden_descr'], 1);

if (get_user_class() >= UC_MODERATOR)
    tr("������", "<input type=\"checkbox\" name=\"sticky\" value=\"yes\">���������� ���� ������� (������ �������)", 1);
    tr("����� ��� ��������", "<input type=\"checkbox\" name=\"nofile\" value=\"yes\">���� ����� ��� �������� ; ������: <input type=\"text\" name=\"nofilesize\" size=\"20\" /> ��", 1);
    tr("<font color=\"red\">�����</font>", "<input type=\"checkbox\" name=\"annonce\" value=\"yes\">��� �����-���� ����� ������. ������ ��������� �� �����������,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�� ����������� �������� ����� ����� �������������!", 1);
    tr("ID ����<br />������ $FORUMNAME<br/><br/><font color=\"red\">������ �����������!</font><br/><br/><a href=\"$FORUMURL/index.php?act=Search\">������ �����<br/>�� ������</a>", "<input type=\"text\" name=\"topic\" size=\"8\" disabled /><input type=\"checkbox\" onclick=\"document.upload.topic.disabled = false;\" /> - ��������, ����� ������ ID ����.<hr />������: $FORUMURL/index.php?showtopic=<b>45270</b> | <font color=\"red\">45270</font> - ��� ID ����<hr /><b>������ ������� ������������, ����� ���� � ������� <u>��� ����</u> �� ������.</b><br />������ ����� �������� � WIKI ������ ������, �������� � ����� ����� �������� � ������������ � ��������� ������.<br />���� ���� ������, �� ���� ��������� �������������.\n",1);

?>
<tr><td align="center" colspan="2"><input type="hidden" name="type" value="<?=$type?>"><input type="submit" class=btn value="<?=$tracker_lang['upload'];?>" /></td></tr>
</table>
</form>
<?

stdfoot();

?>