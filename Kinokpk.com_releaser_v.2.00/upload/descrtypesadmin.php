<?php
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
require_once "include/bittorrent.php";

function bark($msg) {
	genbark($msg, $tracker_lang['error']);
}

dbconn();

if (get_user_class() < UC_SYSOP) bark("Access denied. You're not SYSOP.");

  
if ((!isset($_GET['class'])) && (!isset($_GET['faq']))) {
    stdhead("������� ������������ ��������");
  print('<table width="100%" border="1"><tr><td class="colhead"><h1>�������� ���</h1></td><td class="colhead" width="100px" aling="right"><div align="center"><a href="?faq"><font color="red">FAQ</font></a></div></td></tr></table><table width="100%" border="1"><tr><td><a href="?class=type">���� ��������</a></td><td><a href="?class=details">������ ����� ��������</a></td></tr></table>');
  stdfoot();
  die;
} elseif (isset($_GET['faq'])) {
  stdhead("���������� ���������� ������� ������������ ��������");
  print("<div aling=\"center\"><h1>������� ��� ������ (������� ������������ ��������)</h1></div>");
  print nl2br('� �������� ��� ���������� �������� ������� ���������� ��� ���� option.
  ���� ��� ���� ������ ��������� ��� ���� option, �� ��� ����� �������, ���������� ��������� ����� ����� �������, ��������:
  ����� <b>� ���� �����</b>: DVDrip,HDTVrip �� ��������
  <select><option value="DVDrip">DVDrip</option><option value="HDTVrip">HDTVrip</option></select>
  
  ���� �� ��� ���������� ������ ����������� � ������ ��������, ������� ��� ���������, �� ��� ����� �������, �������� �������� �������������� ��� ������ �����:
  �����: DVDrip,HDTVrip
  ��������: ������ ���-��, ������ ���-��
  ����:
  <select><option value="DVDrip">������ ���-��</option><option value="HDTVrip">������ ���-��</option></select>
  
  <div aling="center"><b>��������!</b></div>
  
  ���� ��� ���������� ���������� �������������� ������������ ��� ������������ ��������,��:
  <b>����� - ������������� � BB�����
  �������� - ������������� � HTML</b>');
  stdfoot();
  die;
  
}
elseif (!isset($_GET['action']) && ($_GET['class'] == 'type')) {
    stdhead("������� ������������ ����� ��������");
  $res = sql_query("SELECT * FROM descr_types ORDER BY id ASC");
  print("<table width=\"100%\" border=\"0\"><tr><td><a href=\"?class=type&action=add\">�������� ��� ��������</a></td><td><a href=\"descrtypesadmin.php\">� ������ �������� ����������������� ����� ��������</a></td></tr></table>");
  print('<table width="100%" border="1"><tr><td class="colhead">ID</td><td class="colhead">��������</td><td class="colhead">ID ���������</td><td class="colhead">���/��</td></tr>');
  while ($row = mysql_fetch_array($res)) {
  print("<tr><td>".$row['id']."</td><td>".$row['type']."</td><td>".$row['category']."</td><td><a href=\"?class=type&action=edit&id=".$row['id']."\">E</a> | <a onClick=\"return confirm('�� �������?')\" href=\"?class=type&action=delete&id=".$row['id']."\">D</a></td></tr>");
}
 print("</table>");
 stdfoot();
 die;
}

elseif (!isset($_GET['action']) && ($_GET['class'] == 'details')) {
    stdhead("������� ������� ������������ ����� ��������");
  print("<div algin=\"center\"><h1>������ ����� ��������</h1></div>");
  print("<table width=\"100%\" border=\"0\"><tr><td><a href=\"?class=details&action=add\">�������� ����������</a></td><td><a href=\"descrtypesadmin.php\">� ������ �������� ����������������� ����� ��������</a></td></tr></table>");
  $detarray = sql_query("SELECT descr_details.*, descr_types.type FROM descr_details LEFT JOIN descr_types ON descr_details.typeid = descr_types.id ORDER BY descr_details.typeid,descr_details.sort ASC");
  print("<table width=\"100%\" border=\"1\"><tr><td class=\"colhead\">ID</td><td class=\"colhead\">��������</td><td class=\"colhead\">�������</td><td class=\"colhead\">��������</td><td class=\"colhead\">��������</td><td class=\"colhead\">���</td><td class=\"colhead\">������ ����</td><td class=\"colhead\">��������?</td><td class=\"colhead\">���������� ����</td><td class=\"colhead\">�����</td><td class=\"colhead\">����� �� �������</td><td class=\"colhead\">� ������</td><td class=\"colhead\">�����</td><td class=\"colhead\">���/��</td></tr><form name=\"saveids\" action=\"?class=details&action=saveids\" method=\"post\">");
  while($detail = mysql_fetch_array($detarray)) {
    print("<tr><td>".$detail['id']."</td><td>".$detail['type']."</td><td><input type=\"text\" name=\"sort[".$detail['id']."]\" size=\"4\" value=\"".$detail['sort']."\"></td><td>".$detail['name']."</td><td>".(($detail['description'] == '')?"---":$detail['description'])."</td><td>".$detail['input']."</td><td>".$detail['size']."</td><td>".$detail['isnumeric']."</td><td>".$detail['required']."</td><td>".(!empty($detail['mask'])?$detail['mask']:"---")."</td><td>".$detail['mainpage']."</td><td>".$detail['search']."</td><td>".$detail['hide']."</td><td><a href=\"?class=details&action=edit&id=".$detail['id']."\">E</a> | <a onClick=\"return confirm('�� �������?')\" href=\"?class=details&action=delete&id=".$detail['id']."\">D</a></td></tr>");
  }
  print("</table><input type=\"submit\" class=\"btn\" value=\"��������� ������� �����������\"></form>");
  stdfoot();
}

elseif (($_GET['action'] == 'saveids') && ($_GET['class'] == 'details')) {
if (is_array($_POST['sort'])) {

    foreach ($_POST['sort'] as $id => $s) {

    sql_query("UPDATE descr_details SET sort = ".intval($s)."  WHERE id = " . $id);
  }
                      header("Location: descrtypesadmin.php?class=details");
                exit();
}
 else bark("Missing form data");
}

elseif (($_GET['action'] == 'add') && ($_GET['class'] == 'type')) {
      stdhead("���������� ������������� ����");
  print("<table width=\"100%\"><form action=\"?class=type&action=saveadd\" enctype=\"multipart/form-data\" name=\"savearray\" method=\"post\"><tr><td class=\"colhead\">�������� ���� ��������</td></tr><tr><td><input type=\"text\" name=\"type\" size=\"80\"></td></tr><tr><td class=\"colhead\">�������� ���������� <input type=\"text\" name=\"category\"></td></tr></table>
  <input type=\"submit\" class=\"btn\" value=\"��������\"></form>");
stdfoot();
}

elseif (($_GET['action'] == 'add') && ($_GET['class'] == 'details')) {
      stdhead("���������� ������ ����");
  $res = sql_query("SELECT * FROM descr_types ORDER BY id ASC");
  while ($row = mysql_fetch_array($res)){
    $ids[] = $row['id'];
    $types[] = $row['type'];
  }
  print("<div align=\"center\"><b>���������� ������ ���� ��������</b></div><br/><table width=\"100%\" border=\"1\"><form action=\"?class=details&action=saveadd\" enctype=\"multipart/form-data\" name=\"savearray\" method=\"post\"><tr><td>�������� ������ ��� ��������:</td><td><input type=\"text\" name=\"name\" size=\"40\"></td></tr>");

  $s = "<select name=\"typeid\">\n";

foreach($ids as $opt => $desc) {

	$s .= "<option value=\"".$desc."\">" . $types[$opt] . "</option>\n";

}

$s .= "</select>\n";
print("<tr><td>��������:</td><td>$s</td></tr>");
print('<tr><td>����������:</td><td><input type="text" size="3" name="sort"></td></tr>');
print('<tr><td>����� (BBcodes) :</td><td><textarea name="mask" rows="10" cols="60" wrap="off"></textarea></td></tr>');
print('<tr><td>�������� (HTML) :</td><td><textarea name="description" rows="10" cols="60" wrap="off"></textarea></td></tr>');
print('<tr><td>���:</td><td><select name="input">
<option value="text">�����</option>
<option value="bbcode">�����</option>
<option value="option">�����</option>
<option value="links">������</option>
</select></td></tr>');
print('<tr><td>������ (������ ��� ������, size) :</td><td><input type="text" size="2" name="size"></td></tr>');
print('<tr><td>�������� ���� (�������� �� is_numeric) :</td><td><input type="checkbox" name="isnumeric" value="yes"></td></tr>');
print('<tr><td>����������� ��� ���������� (�������� �� !empty) :</td><td><input type="checkbox" name="required" value="yes"></td></tr>');
print('<tr><td>������ �� ������ (�������� $CURUSER) :</td><td><input type="checkbox" name="hide" value="yes"></td></tr>');
print('<tr><td>���������� � ������ (����� � 2.0) :</td><td><input type="checkbox" name="search" value="yes"></td></tr>');
print('<tr><td>������������ � ����� ������� �� ������� :</td><td><input type="checkbox" name="mainpage" value="yes"></td></tr>');
print("</table><input type=\"submit\" class=\"btn\" value=\"��������\"></form>");
stdfoot();
}

elseif (($_GET['action'] == 'delete') && ($_GET['class'] == 'type')) {
  if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
  sql_query("DELETE FROM descr_types WHERE id = ".$_GET['id']);
  sql_query("DELETE FROM descr_details WHERE typeid = ".$_GET['id']);
                        header("Location: descrtypesadmin.php?class=type");
                exit();

}

elseif (($_GET['action'] == 'delete') && ($_GET['class'] == 'details')) {
  if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
  sql_query("DELETE FROM descr_details WHERE id = ".$_GET['id']);
  @sql_query("DELETE FROM descr_torrents WHERE typeid = ".$_GET['id']);
                        header("Location: descrtypesadmin.php?class=details");
                exit();

}

elseif (($_GET['action'] == 'edit') && ($_GET['class'] == 'type')) {
    if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");

    $typearray = sql_query("SELECT * FROM descr_types WHERE id=".$_GET['id']);
    list($id,$type,$category) = mysql_fetch_array($typearray);

                        stdhead("�������������� ���� ��������");
  print("<table width=\"100%\"><form name=\"save\" enctype=\"multipart/form-data\" action=\"?class=type&action=saveedit\" method=\"post\"><tr><td class=\"colhead\">�������� ���� ��������</td></tr><tr><td><input type=\"text\" name=\"type\" size=\"80\" value=\"$type\"></td></tr><tr><td class=\"colhead\">�������� ���������� <input type=\"text\" name=\"category\" value=\"$category\"></table>
  <input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\">
  <input type=\"submit\" class=\"btn\" value=\"���������������\"></form>");
stdfoot();
}

elseif (($_GET['action'] == 'edit') && ($_GET['class'] == 'details')) {
    if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");

    stdhead("�������������� ������� ���� ��������");
    $res = sql_query("SELECT * FROM descr_types ORDER BY id ASC");
  while ($row = mysql_fetch_array($res)){
    $ids[] = $row['id'];
    $types[] = $row['type'];
  }
  $detarray = sql_query("SELECT * FROM descr_details WHERE id=".$_GET['id']);
  $detail = mysql_fetch_array($detarray);
  print("<div align=\"center\"><b>�������������� ������ ���� ��������</b></div><br/><table width=\"100%\" border=\"1\"><form action=\"?class=details&action=saveedit\" enctype=\"multipart/form-data\" name=\"save\" method=\"post\"><tr><td>�������� ������ ��� ��������:</td><td><input type=\"text\" name=\"name\" size=\"40\" value=\"".$detail['name']."\"></td></tr>");

  $s = "<select name=\"typeid\">\n";

foreach($ids as $opt => $desc) {

	$s .= "<option ".(($desc==$detail['typeid'])?"selected":"")." value=\"".$desc."\">" . $types[$opt] . "</option>\n";

}

$s .= "</select>\n";
print("<tr><td>��������:</td><td>$s</td></tr>");
print('<tr><td>����������:</td><td><input type="text" size="3" name="sort" value="'.$detail['sort'].'"></td></tr>');
print('<tr><td>�����:</td><td><textarea name="mask" rows="10" cols="60" wrap="off">'.$detail['mask'].'</textarea></td></tr>');
print('<tr><td>��������:</td><td><textarea name="description" rows="10" cols="60" wrap="off">'.$detail['description'].'</textarea></td></tr>');
print('<tr><td>���:</td><td><select name="input">
<option '.(($detail['input'] == 'text')?"selected":"").' value="text">�����</option>
<option '.(($detail['input'] == 'bbcode')?"selected":"").' value="bbcode">�����</option>
<option '.(($detail['input'] == 'option')?"selected":"").' value="option">�����</option>
<option '.(($detail['input'] == 'links')?"selected":"").' value="links">������</option>
</select></td></tr>');
print('<tr><td>������ (������ ��� ������):</td><td><input type="text" size="2" name="size" value="'.$detail['size'].'"></td></tr>');
print('<tr><td>�������� ����:</td><td><input type="checkbox" name="isnumeric" value="yes" '.(($detail['isnumeric'] == 'yes')?"checked":"").'></td></tr>');
print('<tr><td>����������� ��� ����������:</td><td><input type="checkbox" name="required" value="yes" '.(($detail['required'] == 'yes')?"checked":"").'></td></tr>');
print('<tr><td>������ �� ������:</td><td><input type="checkbox" name="hide" value="yes" '.(($detail['hide'] == 'yes')?"checked":"").'></td></tr>');
print('<tr><td>���������� � ������:</td><td><input type="checkbox" name="search" value="yes" '.(($detail['search'] == 'yes')?"checked":"").'></td></tr>');
print('<tr><td>������������ � ����� ������� �� �������:</td><td><input type="checkbox" name="mainpage" value="yes" '.(($detail['mainpage'] == 'yes')?"checked":"").'></td></tr>');
print("</table><input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\"><input type=\"submit\" class=\"btn\" value=\"���������������\"></form>");
stdfoot();
}

elseif (($_GET['action'] == 'saveedit') && ($_GET['class'] == 'type')) {

  sql_query("UPDATE descr_types SET type='".$_POST['type']."', category = ".intval($_POST['category'])." WHERE id=".intval($_POST['id']));
                        header("Location: descrtypesadmin.php?class=type");
                exit();
  }
  
elseif (($_GET['action'] == 'saveedit') && ($_GET['class'] == 'details')) {
  if ($_POST['size'] == '') $size = 0; else $size=$_POST['size'];
 if ($_POST['isnumeric'] == 'yes') $isnumeric='yes'; else $isnumeric = 'no';
 if ($_POST['required'] == 'yes') $required = 'yes'; else $required = 'no';
 if ($_POST['search'] == 'yes') $search = 'yes'; else $search = 'no';
 if ($_POST['hide'] == 'yes') $hide = 'yes'; else $hide='no';
  if ($_POST['mainpage'] == 'yes') $mainpage = 'yes'; else $mainpage='no';
  sql_query("UPDATE descr_details SET typeid=".$_POST['typeid'].", sort=".intval($_POST['sort']).", name='".$_POST['name']."',description='".$_POST['description']."',input='".$_POST['input']."', size='".$size."', isnumeric='".$isnumeric."', required='".$required."', mask='".$_POST['mask']."', search='".$search."', hide='".$hide."', mainpage='".$mainpage."' WHERE id=".intval($_POST['id']));
                        header("Location: descrtypesadmin.php?class=details");
                exit();
  }
  
elseif (($_GET['action'] == 'saveadd') && ($_GET['class'] == 'type')) {

  sql_query("INSERT INTO descr_types (type) VALUES ('".$_POST['type']."')");
                      header("Location: descrtypesadmin.php?class=type");
                exit();
}

elseif (($_GET['action'] == 'saveadd') && ($_GET['class'] == 'details')) {
 if ($_POST['size'] == '') $size = 0; else $size=$_POST['size'];
 if ($_POST['isnumeric'] == 'yes') $isnumeric='yes'; else $isnumeric = 'no';
 if ($_POST['required'] == 'yes') $required = 'yes'; else $required = 'no';
 if ($_POST['search'] == 'yes') $search = 'yes'; else $search = 'no';
 if ($_POST['hide'] == 'yes') $hide = 'yes'; else $hide='no';
 if ($_POST['mainpage'] == 'yes') $mainpage = 'yes'; else $mainpage='no';
 
  sql_query("INSERT INTO descr_details (typeid,sort,name,description,input,size,isnumeric,required,mask,search,hide,mainpage) VALUES (".$_POST['typeid'].",".intval($_POST['sort']).",'".($_POST['name'])."','".$_POST['description']."','".$_POST['input']."',$size,'$isnumeric','$required','".$_POST['mask']."','$search','$hide','$mainpage')");
                      header("Location: descrtypesadmin.php?class=details");
                exit();
}
