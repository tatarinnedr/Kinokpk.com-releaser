<?php
/**
 * Release editing form
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

dbconn();
getlang('upload');

loggedinorreturn();

if (!is_valid_id($_GET['id'])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$id = (int) $_GET['id'];
$tree = make_tree();

$res = sql_query("SELECT id,name,descr,images,category,size,visible,banned,free,sticky,moderatedby,topic_id,online,owner,relgroup,modcomm FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$res = sql_query("SELECT tracker FROM trackers WHERE torrent=$id AND tracker<>'localhost'");
while (list($tracker) = mysql_fetch_array($res)) $trackers[] = $tracker;
if ($trackers) $trackers = implode("\n",$trackers);
stdhead("�������������� ������ \"" . makesafe($row["name"]) . "\"");

?>
<script type="text/javascript" language="javascript">

function openwindow(location)
{
 window.open("<?=$CACHEARRAY['defaultbaseurl']?>/"+location+".php?id=<?=$id?>","mywindow","width=250,height=250");
}
</script>
<?php

if (($row["filename"] == 'nofile') || (get_user_class() == UC_UPLOADER)) $tedit = 1; else $tedit = 0;

if (($CURUSER["id"] != $row["owner"]) && (get_user_class() < UC_MODERATOR) && !$tedit) {
	stdmsg($tracker_lang['error'],"�� �� ������ ������������� ���� �������.");
} else {
	print("<form name=\"edit\" method=post action=takeedit.php enctype=multipart/form-data>\n");
	print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
	if (isset($_GET["returnto"]))
	print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_GET["returnto"]) . "\" />\n");
	print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
	print("<tr><td class=\"colhead\" colspan=\"2\">������������� �������</td></tr>");
	if ((get_user_class() >= UC_MODERATOR) || $tedit) tr($tracker_lang['check'],"<input type=\"checkbox\" name=\"approve\" value=\"1\"".($row['moderatedby']?' checked':'')."> {$tracker_lang['approve']}",1);
	tr($tracker_lang['torrent_file'], "<input type=file name=tfile size=80><br /><input type=\"checkbox\" name=\"multi\" value=\"1\">&nbsp;{$tracker_lang['multitracker_torrent']}<br /><small>{$tracker_lang['multitracker_torrent_notice']}</small>\n", 1);
	if (get_user_class() >= UC_UPLOADER)
	tr($tracker_lang['announce_urls'],"<textarea name=\"trackers\" rows=\"6\" cols=\"60\" wrap=\"off\">$trackers</textarea><br/><input type=\"submit\" name=\"add_trackers\" value=\"{$tracker_lang['add_announce_urls']}\"><br/>{$tracker_lang['announce_urls_notice']}",1);
	tr($tracker_lang['torrent_name']."<font color=\"red\">*</font>", "<input type=\"text\" name=\"name\" value=\"" . strip_tags($row["name"]) . "\" size=\"80\" />", 1);

	$row['images'] = explode(',',$row['images']);
	$imgcontent = '';
	// die(var_dump($images));

	for ($i = 0; $i < $CACHEARRAY['max_images']; $i++) {
		$imgcontent .= "<b>�������� �".($i+1).":&nbsp&nbsp<input type=\"text\" size=\"61\" name=\"img$i\" value=\"{$row['images'][$i]}\"><hr />";
	}
	tr($tracker_lang['images'], $tracker_lang['max_file_size'].": 500kb<br />".$tracker_lang['avialable_formats'].": .jpg .png .gif<br />$imgcontent", 1);

	print '<tr><td align="left"><b>'.$tracker_lang['description'].'</b></td><td>'.textbbcode('descr',$row['descr'],1).'</td></tr>';

	//relgroups
	$rgarrayres = sql_query("SELECT id,name FROM relgroups ORDER BY added DESC");
	while($rgarrayrow = mysql_fetch_assoc($rgarrayres)) {
		$rgarray[$rgarrayrow['id']] = $rgarrayrow['name'];
	}

	if ($rgarray) {
		$rgselect = '<select name="relgroup"><option value="0">('.$tracker_lang['choose'].')</option>';
		foreach ($rgarray as $rgid=>$rgname) $rgselect.='<option value="'.$rgid.'"'.(($row['relgroup']==$rgid)?" selected=\"1\"":'').'>'.$rgname."</option>\n";
		$rgselect.='</select>';
	}
	if ($rgselect)
	tr($tracker_lang['relgroup'],$rgselect,1);
	// relgroups end
	// make main category an childs
	$cats = explode(',',$row['category']);
	$cat= array_shift($cats);
	$cat = get_cur_branch($tree,$cat);
	$childs = get_childs($tree,$cat['parent_id']);
	if ($childs) {
		$chsel='<table width="100%" border="1">';
		foreach($childs as $child)
		if ($cat['id'] != $child['id']) $chsel.="<tr><td><input type=\"checkbox\" name=\"type[]\" value=\"{$child['id']}\"".(in_array($child['id'],$cats)?' checked':'').">&nbsp;{$child['name']}</td></tr>";
		$chsel.="</table>";
	}
	tr ($tracker_lang['main_category'],gen_select_area('type[]',$tree,$cat['id']),1);
	if ($chsel)
	tr ($tracker_lang['subcats'],$chsel,1);

	tr("�������", "<input type=\"checkbox\" name=\"visible\"" . (($row["visible"]) ? " checked=\"checked\"" : "" ) . " value=\"1\" /> ������� �� �������<br /><table border=0 cellspacing=0 cellpadding=0 width=420><tr><td class=embedded>�������� ��������, ��� ������� ������������� ������ ������ ����� ��������� ��������� � ������������� ���������� ���� ������� (������ ���������) ����� �� ����� ���������� ��������� �����. ����������� ���� ������������� ��� ��������� �������. ����� ������ ��� ��������� �������� (��������) ���-����� ����� ���� ����������� � �������, ��� ������ �� ��-���������.</td></tr></table>", 1);
	if((get_user_class() >= UC_MODERATOR) || $tedit)
	tr("\"��������\"", "<input type=\"checkbox\" name=\"upd\" value=\"1\" />������� ������ �� �������", 1);
	if(get_user_class() >= UC_MODERATOR)
	tr("�������", "<input type=\"checkbox\" name=\"banned\"" . (($row["banned"]) ? " checked=\"checked\"" : "" ) . " value=\"1\" />", 1);

if((get_user_class() >= UC_MODERATOR) || $tedit)
tr("������� �������", "<input type=\"checkbox\" name=\"free\"" . (($row["free"]) ? " checked=\"checked\"" : "" ) . " value=\"1\" /> ������� ������� (��������� ������ �������, ������ �� �����������)", 1);
if(get_user_class() >= UC_MODERATOR)   {
	tr("������", "<input type=\"checkbox\" name=\"sticky\"" . (($row["sticky"]) ? " checked=\"checked\"" : "" ) . " value=\"1\" /> ���������� ���� ������� (������ �������)", 1);
	tr("����������� �����������<br /><small>������������� �� ����</small></td>","<textarea cols=60 rows=6 name=modcomm" . ">".htmlspecialchars($row['modcomm'])."</textarea>\n",1);

}
if ($row['filename'] != 'nofile') $word = ''; else $word = 'checked=\"checked\"';
$nofsize = $row['size'] / 1024 / 1024;
tr("����� ��� ��������", "<input type=\"checkbox\" name=\"nofile\" ".$word." value=\"1\">����� ��� �������� ; ������ (��) <input type=\"text\" name=\"nofilesize\" value=\"" . $nofsize . "\" size=\"20\" />", 1);

if (get_user_class() >= UC_UPLOADER)
tr("������ �� ����������������","<a href=\"javascript:openwindow('takean')\">��������������� / ������������ ���������� ������</a> (��������� ����� ������)",1,1);
if ((get_user_class() >= UC_MODERATOR) && $CACHEARRAY['use_integration']) {
	tr("ID ����<br />������ {$CACHEARRAY['forumname']}<br /><br /><font color=\"red\">������ �����������!</font><br /><br />������� ID: <font color=\"red\"><b>".$row['topic_id']."</b></font>", "<input type=\"text\" name=\"topic\" size=\"8\" disabled /><input type=\"checkbox\" onclick=\"document.edit.topic.disabled = false;\" /> - ��������, ����� ������ ID ����. <input type=\"text\" size=\"7\" name=\"source\"/> - �������� ������ (���.,������ - ���-�� ���������)<hr />������: {$CACHEARRAY['forumurl']}/index.php?showtopic=<b>45270</b> | <font color=\"red\">45270</font> - ��� ID ����<hr /><b>������ ������� ������������, ����� ���� � ������� ����� ������������������/������������ �� ������.</b><br />������ ����� �������� � WIKI ������ ������, �������� � ����� ����� �������� � ������������ � ��������� ������.<br />���� ���� ������, �������� ���������� ������������.\n",1);
}

print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"{$tracker_lang['edit']}\" style=\"height: 25px; width: 100px\"> <input type=reset value=\"�������� ���������\" style=\"height: 25px; width: 100px\"></td></tr>\n");
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
}
stdfoot();

?>