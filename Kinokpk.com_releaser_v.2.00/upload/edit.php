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

if (!mkglobal("id"))
	die();

$id = 0 + $id;
if (!$id)
	die();

dbconn();

loggedinorreturn();

$res = sql_query("SELECT * FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
	die();

stdhead("�������������� �������� \"" . $row["name"] . "\"");

?>
<script type="text/javascript" language="javascript">
function openwindow(location)
{
 window.open("<?=$DEFAULTBASEURL?>/"+location+".php?id=<?=$id?>","mywindow","width=250,height=250");
}

function tagcounter(checked)
{
  var i=document.forms['edit'].elements['tagcount'].value;
 if (checked) {
 document.forms['edit'].elements['tagcount'].value = parseInt(i)+1;
 return i;
 }
 else {
 document.forms['edit'].elements['tagcount'].value = parseInt(i)-1;
 return '';

}
}
</script>
<?php

if (($row["filename"] == 'nofile') && (get_user_class() == UC_UPLOADER)) $tedit = 1; else $tedit = 0;

if (!isset($CURUSER) || ($CURUSER["id"] != $row["owner"] && get_user_class() < UC_MODERATOR && !$tedit)) {
	stdmsg($tracker_lang['error'],"�� �� ������ ������������� ���� �������.");
} else {
	print("<form name=\"edit\" method=post action=takeedit.php enctype=multipart/form-data>\n");
	print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
	if (isset($_GET["returnto"]))
		print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_GET["returnto"]) . "\" />\n");
	print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
	print("<tr><td class=\"colhead\" colspan=\"2\">������������� �������</td></tr>");
	tr($tracker_lang['torrent_file'], "<input type=file name=tfile size=80>\n", 1);
	tr($tracker_lang['torrent_name']."<font color=\"red\">*</font>", "<input type=\"text\" name=\"name\" value=\"" . $row["name"] . "\" size=\"80\" />", 1);

        tr($tracker_lang['images'], "<input type=radio name=img1action value='keep' checked>�������� �������� �1&nbsp&nbsp"."<input type=radio name=img1action value='delete'>������� �������� �1&nbsp&nbsp"."<input type=radio name=img1action value='update'>�������� �������� �1<br /><b>�������� �1:</b>&nbsp&nbsp<input type=file name=image0 size=80> <br /><br /> <input type=radio name=img2action value='keep' checked>�������� �������� �2&nbsp&nbsp"."<input type=radio name=img2action value='delete'>������� �������� �2&nbsp&nbsp"."<input type=radio name=img2action value='update'>�������� �������� �2<br /><b>�������� �2:</b>&nbsp&nbsp<input type=file name=image1 size=80>", 1);
if ((strpos($row["ori_descr"], "<") === false) || (strpos($row["ori_descr"], "&lt;") !== false))
  $c = "";
else
  $c = " checked";
$detid = sql_query("SELECT descr_torrents.id, descr_torrents.value, descr_details.name, descr_details.input, descr_details.required, descr_details.size, descr_details.description, descr_details.mask FROM descr_torrents LEFT JOIN descr_details ON descr_details.id = descr_torrents.typeid WHERE descr_torrents.torrent = ".$id." ORDER BY descr_details.sort ASC");

while ($did = mysql_fetch_array($detid))  {
  if ($did['input'] == 'bbcode'){
    print("<tr><td class=rowhead style='padding: 3px'>".$did['name'].(($did['required'] == 'yes')?"<font color=\"red\">*</font>":"")."</td><td>");
  textbbcode("edit","val[".$did['id']."]",$did['value']);
  print("</td></tr>\n");
  }

  elseif ($did['input'] == 'text')
  tr($did['name'].(($did['required'] == 'yes')?"<font color=\"red\">*</font>":""),'<input type="text" size="'.$did['size'].'" name="val['.$did['id'].']" value="'.$did['value'].'"><hr/>'.$did['description'],1,1);

  elseif ($did['input'] == 'links')
  tr($did['name'].(($dd['required'] == 'yes')?"<font color=\"red\">*</font>":""),"<textarea name=\"val[".$did['id']."]\" rows=\"3\" cols=\"60\" wrap=\"soft\">".$did['value']."</textarea><hr/>".$did['description'],1,1);

  elseif ($did['input'] == 'option') {
      unset($optdescr);
      $optvalues = explode(",",$did['mask']);
    if (!empty($did['description'])) $optdescr = explode(",",$did['description']);
$s = "<select name=\"val[".$did['id']."]\">\n<option value=\"\">(".$tracker_lang['choose'].")</option>\n";


foreach($optvalues as $i => $opt) {

	$s .= "<option ".(($opt == $did['value'])?"selected":"")." value=\"".$opt."\">" . htmlspecialchars((is_array($optdescr)?$optdescr[$i]:$opt)) . "</option>\n";

}

$s .= "</select>\n";
tr($did['name'].(($dd['required'] == 'yes')?"<font color=\"red\">*</font>":""),$s,1,1);
}
}

	$s = "<select name=\"type\">\n";

	$cats = genrelist();
	foreach ($cats as $subrow) {
		$s .= "<option value=\"" . $subrow["id"] . "\"";
		if ($subrow["id"] == $row["category"])
			$s .= " selected=\"selected\"";
		$s .= ">" . htmlspecialchars($subrow["name"]) . "</option>\n";
	}

	$s .= "</select>\n";
	tr("���", $s, 1);

$oldtags = explode(",",$row['tags']);
$tagcount = count($oldtags);
$s = "<input type=\"hidden\" name=\"oldtags\" value=\"".$row['tags']."\"><table width=\"100%\"><tr><td>������� �����: <input type=\"text\" name=\"tagcount\" value=\"$tagcount\" readonly/></td></tr>";
$tags = taggenrelist($row['category']);
if (!$tags)
$s .= "��� ����� ��� ������ ���������.";
else
  {

   foreach ($tags as $tagrow) {


   $tag = htmlspecialchars($tagrow["name"]);
   $s .= "<tr><td><input type=\"checkbox\" name=\"tags[]\" onClick=\"this.name='tags['+tagcounter(this.checked)+']';\" value=\"$tag\"".(in_array($tag,$oldtags)?" checked":"")."> $tag</td></tr>";


   }
  }
$s .= "</table>\n";
tr("���� (�����)", $s, 1);

tr("�������", "<input type=\"checkbox\" name=\"visible\"" . (($row["visible"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" /> ������� �� �������<br /><table border=0 cellspacing=0 cellpadding=0 width=420><tr><td class=embedded>�������� ��������, ��� ������� ������������� ������ ������ ����� ��������� ��������� � ������������� ���������� ���� ������� (������ ���������) ����� �� ����� ���������� ��������� �����. ����������� ���� ������������� ��� ��������� �������. ����� ������ ��� ��������� �������� (��������) ���-����� ����� ���� ����������� � �������, ��� ������ �� ��-���������.</td></tr></table>", 1);
	if((get_user_class() >= UC_MODERATOR) || $tedit)
        tr("\"��������\"", "<input type=\"checkbox\" name=\"upd\" value=\"yes\" />������� ������ �� �������", 1);
	if(get_user_class() >= UC_ADMINISTRATOR)
		tr("�������", "<input type=\"checkbox\" name=\"banned\"" . (($row["banned"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" />", 1);
}
    if((get_user_class() >= UC_ADMINISTRATOR) || $tedit)
        tr("������� �������", "<input type=\"checkbox\" name=\"free\"" . (($row["free"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"1\" /> ������� ������� (��������� ������ �������, ������ �� �����������)", 1);
    if(get_user_class() >= UC_ADMINISTRATOR)
        tr("������", "<input type=\"checkbox\" name=\"sticky\"" . (($row["sticky"] == "yes") ? " checked=\"checked\"" : "" ) . " value=\"yes\" /> ���������� ���� ������� (������ �������)", 1);
           if ($row['filename'] != 'nofile') $word = ''; else $word = 'checked=\"checked\"';
           $nofsize = $row['size'] / 1024 / 1024;
          tr("����� ��� ��������", "<input type=\"checkbox\" name=\"nofile\" ".$word." value=\"yes\">����� ��� �������� ; ������ (��) <input type=\"text\" name=\"nofilesize\" value=\"" . $nofsize . "\" size=\"20\" />", 1);

if (get_user_class() >= UC_UPLOADER)
          tr("������ �� ����������������","<a href=\"javascript:openwindow('takean')\">��������������� / ������������ ���������� ������</a> (��������� ����� ������)",1,1);
if (get_user_class() >= UC_MODERATOR)
           tr("ID ����<br />������ $FORUMNAME<br/><br/><font color=\"red\">������ �����������!</font><br/><br/>������� ID: <font color=\"red\"><b>".$row['topic_id']."</b></font>", "<input type=\"text\" name=\"topic\" size=\"8\" disabled /><input type=\"checkbox\" onclick=\"document.edit.topic.disabled = false;\" /> - ��������, ����� ������ ID ����. <input type=\"text\" size=\"7\" name=\"source\"/> - �������� ������ (���.,������ - ���-�� ���������)<hr />������: $FORUMURL/index.php?showtopic=<b>45270</b> | <font color=\"red\">45270</font> - ��� ID ����<hr /><b>������ ������� ������������, ����� ���� � ������� ����� ������������������/������������ �� ������.</b><br />������ ����� �������� � WIKI ������ ������, �������� � ����� ����� �������� � ������������ � ��������� ������.<br />���� ���� ������, �������� ���������� ������������.\n",1);
          tr("��������!", "<font color='red'><b>��� ������� �������� ������� \"<i>+HTTP,+FTP,+ed2k,�� ������� �����</i> \" �� �������� ������ � ������������ � ����� ���������������</b></font>", 1);

        print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"��������������\" style=\"height: 25px; width: 100px\"> <input type=reset value=\"�������� ���������\" style=\"height: 25px; width: 100px\"></td></tr>\n");
	print("</table>\n");
	print("</form>\n");
	if(get_user_class() >= UC_MODERATOR) {
	print("<p>\n");
	print("<form method=\"post\" action=\"delete.php\">\n");
  print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
  print("<tr><td class=embedded style='background-color: #F5F4EA;padding-bottom: 5px' colspan=\"2\"><b>������� �������</b> �������:</td></tr>");
  print("<td><input name=\"reasontype\" type=\"radio\" value=\"1\">&nbsp;������� </td><td> 0 ���������, 0 �������� = 0 ����������</td></tr>\n");
  print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"2\">&nbsp;��������</td><td><input type=\"text\" size=\"40\" name=\"reason[]\"></td></tr>\n");
  print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"3\">&nbsp;Nuked</td><td><input type=\"text\" size=\"40\" name=\"reason[]\"></td></tr>\n");
  print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"4\">&nbsp;�������</td><td><input type=\"text\" size=\"40\" name=\"reason[]\">(�����������)</td></tr>");
  print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"5\" checked>&nbsp;������:</td><td><input type=\"text\" size=\"40\" name=\"reason[]\">(�����������)</td></tr>\n");
	print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
	if (isset($_GET["returnto"]))
		print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_GET["returnto"]) . "\" />\n");
  print("<td colspan=\"2\" align=\"center\"><input type=submit value='�������' style='height: 25px'></td></tr>\n");
  print("</table>");
	print("</form>\n");
	print("</p>\n");
}
stdfoot();

?>