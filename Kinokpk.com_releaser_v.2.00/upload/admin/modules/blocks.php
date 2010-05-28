<?php

if (!defined('ADMIN_FILE')) die("Illegal File Access");

$prefix = "orbital";

$allowed_modules = array(
	"admincp" => "�������",
	"browse" => "�����",
	"forums" => "�����",
	"staff" => "��������",
	"upload" => "���������",
	"details" => "������",
	"my" => "������ �����.",
	"userdetails" => "�������",
	"viewrequests" => "�������",
	"viewoffers" => "�����������",
	"log" => "������",
	"faq" => "����",
	"rules" => "�������",
	"message" => "�����",
	"recover" => "�������. ������",
	"signup" => "�����������",
	"login" => "����",
	"mybonus" => "��� �����",
	"invite" => "�����������",
	"bookmarks" => "��������"
);

function BlocksNavi() {
	global $admin_file;
	echo "<h2>���������� �������</h2><br />"
	."[ <a href=\"".$admin_file.".php?op=BlocksAdmin\">�������</a>"
	." | <a href=\"".$admin_file.".php?op=BlocksNew\">�������� ����� ����</a>"
	." | <a href=\"".$admin_file.".php?op=BlocksFile\">�������� ����� �������� ����</a>"
	." | <a href=\"".$admin_file.".php?op=BlocksFileEdit\">������������� ����</a> ]";
}

function BlocksAdmin() {
	global $admin_file, $prefix;
	BlocksNavi();
	echo "<p /><table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" width=\"100%\"><tr align=\"center\">"
	."<td class=\"colhead\">�</td><td class=\"colhead\">���������</td><td class=\"colhead\">�������</td><td colspan=\"2\" class=\"colhead\">���������</td><td class=\"colhead\">���</td><td class=\"colhead\">������</td><td class=\"colhead\">��� �����</td><td class=\"colhead\">�������</td></tr>";

	$result = sql_query("SELECT a.bid, a.bkey, a.title, a.bposition, a.weight, a.active, a.blockfile, a.view, a.expire, a.action, b.bid, b.bposition, b.weight, c.bid, c.bposition, c.weight FROM ".$prefix."_blocks AS a LEFT JOIN ".$prefix."_blocks AS b ON (b.bposition = a.bposition AND b.weight = a.weight-1) LEFT JOIN ".$prefix."_blocks AS c ON (c.bposition = a.bposition AND c.weight = a.weight+1) ORDER BY a.bposition, a.weight") or sqlerr(__FILE__,__LINE__);
	while (list($bid, $bkey, $title, $bposition, $weight, $active, $blockfile, $view, $expire, $action, $con1, $bposition1, $weight1, $con2, $bposition2, $weight2) = mysql_fetch_row($result)) {
		if (($expire && $expire < time()) || (!$active && $expire)) {
			if ($action == "d") {
				sql_query("UPDATE ".$prefix."_blocks SET active='0', expire='0' WHERE bid='$bid'");
			} elseif ($action == "r") {
				sql_query("DELETE FROM ".$prefix."_blocks WHERE bid='$bid'");
			}
		}
		$weight_minus = $weight - 1;
		$weight_plus = $weight + 1;
		echo "<tr><td align=\"center\">$bid</td><td>$title</td>";
		if ($bposition == "l") {
			$bposition = "<img src=\"admin/pic/left.gif\" border=\"0\" alt=\"����� ����\" title=\"����� ����\"> �����";
		} elseif ($bposition == "r") {
			$bposition = "������ <img src=\"admin/pic/right.gif\" border=\"0\" alt=\"������ ����\" title=\"������ ����\">";
		} elseif ($bposition == "c") {
			$bposition = "<img src=\"admin/pic/right.gif\" border=\"0\" alt=\"����������� ����\" title=\"����������� ����\">&nbsp;�� ������ ������&nbsp;<img src=\"admin/pic/left.gif\" border=\"0\" alt=\"����������� ����\" title=\"����������� ����\">";
		} elseif ($bposition == "d") {
			$bposition = "<img src=\"admin/pic/right.gif\" border=\"0\" alt=\"����������� ����\" title=\"����������� ����\">&nbsp;�� ������ �����&nbsp;<img src=\"admin/pic/left.gif\" border=\"0\" alt=\"����������� ����\" title=\"����������� ����\">";
		} elseif ($bposition == "b") {
			$bposition = "<img src=\"admin/pic/up.gif\" border=\"0\" alt=\"������\" title=\"������\">&nbsp;������� ������&nbsp;<img src=\"admin/pic/up.gif\" border=\"0\" alt=\"������\" title=\"������\">";
		} elseif ($bposition == "f") {
			$bposition = "<img src=\"admin/pic/down.gif\" border=\"0\" alt=\"������\" title=\"������\">&nbsp;������ ������&nbsp;<img src=\"admin/pic/down.gif\" border=\"0\" alt=\"������\" title=\"������\">";
		}
		echo "<td align=\"center\"><nobr>$bposition</nobr></td><td align=\"center\">$weight</td><td align=\"center\">";
		if ($con1) echo "<a href=\"".$admin_file.".php?op=BlocksOrder&weight=$weight&bidori=$bid&weightrep=$weight_minus&bidrep=$con1\"><img src=\"admin/pic/up.gif\" alt=\"����������� �����\" title=\"����������� �����\" border=\"0\"></a> ";
		if ($con2) echo "<a href=\"".$admin_file.".php?op=BlocksOrder&weight=$weight&bidori=$bid&weightrep=$weight_plus&bidrep=$con2\"><img src=\"admin/pic/down.gif\" alt=\"����������� ����\" title=\"����������� ����\" border=\"0\"></a>";
		echo"</td>";
		if ($bkey == "") {
			$type = "HTML";
			if ($blockfile != "") $type = "����";
		} elseif ($bkey != "") {
			$type = "���������";
		}
		echo "<td align=\"center\">$type</td>";
		$block_act = $active;
		if ($active == 1) {
			$active = "<font color=\"#009900\">���.</font>";
			$change = "title=\"����.\"><img src=\"admin/pic/inactive.gif\" border=\"0\" alt=\"����.\"></a>";
		} elseif ($active == 0) {
			$active = "<font color=\"#FF0000\">����.</font>";
			$change = "title=\"���.\"><img src=\"admin/pic/activate.gif\" border=\"0\" alt=\"���.\"></a>";
		}
		echo "<td align=\"center\">$active</td>";
		if ($view == 0) {
			$who_view = "��� ����������";
		} elseif ($view == 1) {
			$who_view = "������ ������������";
		} elseif ($view == 2) {
			$who_view = "������ ��������������";
		} elseif ($view == 3) {
			$who_view = "������ �������";
		}
		echo "<td align=\"center\"><nobr>$who_view</nobr></td>";
		echo "<td align=\"center\"><a href=\"".$admin_file.".php?op=BlocksEdit&bid=$bid\" title=\"�������������\"><img src=\"admin/pic/edit.gif\" border=\"0\" alt=\"�������������\"></a> <a href=\"".$admin_file.".php?op=BlocksChange&bid=$bid\" $change";
		if ($bkey == "") echo " <a href=\"".$admin_file.".php?op=BlocksDelete&bid=$bid\" OnClick=\"return DelCheck(this, '������� &quot;$title&quot;?');\" title=\"�������\"><img src=\"admin/pic/delete.gif\" border=\"0\" alt=\"�������\"></a>";
		if ($block_act == 0) echo " <a href=\"".$admin_file.".php?op=BlocksShow&bid=$bid\" title=\"��������\"><img src=\"admin/pic/show.gif\" border=\"0\" alt=\"��������\"></a>";
	}
	if (mysql_num_rows($result) == 0)
		echo "<tr><td colspan=\"9\">��� ������.";
	echo "</td></tr></table><center>[ <a href=\"".$admin_file.".php?op=BlocksFixweight\">������������� ������� � ��������� ������</a> ]</center>";

}

function BlocksNew() {
	global $prefix, $admin_file;
	BlocksNavi();
	echo "<h2>�������� ����� ����</h2>"
	."<form action=\"".$admin_file.".php\" method=\"post\">"
	."<table border=\"0\" align=\"center\">"
	."<tr><td>���������:</td><td><input type=\"text\" name=\"title\" size=\"65\" style=\"width:400px\" maxlength=\"60\"></td></tr>"
	."<tr><td>��� �����:</td><td>"
	."<select name=\"blockfile\" style=\"width:400px\">"
	."<option name=\"blockfile\" value=\"\" selected>���</option>";
	$handle = opendir("blocks");
	while ($file = readdir($handle)) {
		if (preg_match("/^block\-(.+)\.php/", $file, $matches)) {
			$found = str_replace("_", " ", $matches[1]);
			if (mysql_num_rows(sql_query("SELECT * FROM ".$prefix."_blocks WHERE blockfile='$file'")) == 0) echo "<option value=\"$file\">$found</option>\n";
		}
	}
	closedir($handle);
	echo "</select></td></tr>"
	."<tr><td>����������:</td><td><textarea name=\"content\" cols=\"65\" rows=\"15\" style=\"width:400px\"></textarea></td></tr>"
	."<tr><td>�������:</td><td><select name=\"bposition\" style=\"width:400px\">"
	."<option name=\"bposition\" value=\"l\">�����</option>"
	."<option name=\"bposition\" value=\"c\">�� ������ ������</option>"
	."<option name=\"bposition\" value=\"d\">�� ������ �����</option>"
	."<option name=\"bposition\" value=\"r\">������</option>"
	."<option name=\"bposition\" value=\"b\">������� ������</option>"
	."<option name=\"bposition\" value=\"f\">������ ������</option>"
	."</select></td></tr>";
	echo "<tr><td>���������� ���� � �������:</td><td align=\"center\"><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\" style=\"width:400px\"><tr>";
	echo "<td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"ihome\"></td><td>�������</td>";
	global $allowed_modules;
	$a = 1;
	foreach ($allowed_modules as $name => $title) {
		$i++;
		$title = ereg_replace("_", " ", $title);
		echo "<td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"".$name."\"></td><td>$title</td>";
		if ($a == 2) {
			echo "</tr><tr>";
			$a = 0;
		} else {
			$a++;
		}
	}
	echo "</tr><tr><td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"all\"></td><td><b>�� ���� �������</b></td><td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"home\"></td><td><b>������ �� �������</b></td><td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"infly\"></td><td><b>��������� ����</b></td></tr></table></td></tr>";
	echo "<tr><td>��������?</td><td><input type=\"radio\" name=\"active\" value=\"1\" checked>�� &nbsp;&nbsp; <input type=\"radio\" name=\"active\" value=\"0\">���</td></tr>"
	."<tr><td>����� ������, � ����:</td><td><input type=\"text\" name=\"expire\" maxlength=\"3\" value=\"0\" size=\"65\" style=\"width:400px\"></td></tr>"
	."<tr><td>����� ���������:</td><td><select name=\"action\" style=\"width:400px\">"
	."<option name=\"action\" value=\"d\">����.</option>"
	."<option name=\"action\" value=\"r\">�������</option></select></td></tr>"
	."<tr><td>��� ��� ����� ������?</td><td><select name=\"view\" style=\"width:400px\">"
	."<option value=\"0\" >��� ����������</option>"
	."<option value=\"1\" >������ ������������</option>"
	."<option value=\"2\" >������ ��������������</option>"
	."<option value=\"3\" >������ �������</option>"
	."</select></td></tr>"
	."<tr><td colspan=\"2\" align=\"center\"><br /><input type=\"hidden\" name=\"op\" value=\"BlocksAdd\"><input type=\"submit\" value=\"������� ����\"></td></tr></table></form>";
}

function BlocksFile() {
	global $admin_file;
	BlocksNavi();
	echo "<h2>�������� ����� �������� ����</h2>"
	."<form action=\"".$admin_file.".php\" method=\"post\">"
	."<table border=\"0\" align=\"center\">"
	."<tr><td>��� �����:</td><td><input type=\"text\" name=\"bf\" size=\"65\" style=\"width:400px\" maxlength=\"200\">"
	."<tr><td>���:</td><td><input type=\"radio\" name=\"flag\" value=\"php\" checked>PHP &nbsp;&nbsp; <input type=\"radio\" name=\"flag\" value=\"html\">HTML</td></tr>"
	."<tr><td colspan=\"2\" align=\"center\"><br /><input type=\"hidden\" name=\"op\" value=\"BlocksbfEdit\">"
	."<input type=\"submit\" value=\"������� ����\"></td></tr></table></form>";
}

function BlocksOrder($weightrep,$weight,$bidrep,$bidori) {
	global $prefix, $admin_file;
	$result = sql_query("UPDATE ".$prefix."_blocks SET weight='$weight' WHERE bid='$bidrep'");
	$result2 = sql_query("UPDATE ".$prefix."_blocks SET weight='$weightrep' WHERE bid='$bidori'");
	Header("Location: ".$admin_file.".php?op=BlocksAdmin");
}

function BlocksAdd($title, $content, $bposition, $active, $blockfile, $view, $expire, $action) {
	global $prefix, $admin_file;
	list($weight) = mysql_fetch_row(sql_query("SELECT weight FROM ".$prefix."_blocks WHERE bposition=".sqlesc($bposition)." ORDER BY weight DESC"));
	$weight++;
	$bkey = "";
	$btime = "";
	if ($blockfile != "") {
		$url = "";
		if ($title == "") {
			$title = str_replace("block-", "", $blockfile);
			$title = str_replace(".php", "", $title);
			$title = str_replace("_", " ", $title);
		}
	}

	if (($content == "") && ($blockfile == "")) {
		stdmsg("������", "���� �� ����� ���� ������!", 'error');
	} else {
		if ($expire == "" || $expire == 0) {
			$expire = 0;
		} else {
			$expire = time() + ($expire * 86400);
		}
		if (isset($_POST['blockwhere'])) {
			$blockwhere = $_POST['blockwhere'];
			$which = "";
			$which = (in_array("all", $blockwhere)) ? "all" : $which;
			$which = (in_array("home", $blockwhere)) ? "home" : $which;
			if ($which == "") {
				while(list($key, $val) = each($blockwhere)) {
					$which .= "{$val},";
				}
			}
		}
		sql_query("INSERT INTO ".$prefix."_blocks VALUES (NULL, ".implode(", ", array_map("sqlesc", array($bkey, $title, $content, $bposition, $weight, $active, $btime, $blockfile, $view, $expire, $action, $which))).")") or sqlerr(__FILE__,__LINE__);
		Header("Location: ".$admin_file.".php?op=BlocksAdmin");
	}
}

function BlocksEdit($bid) {
	global $prefix, $admin_file;
	BlocksNavi();
	$bid = intval($bid);
	list($bkey, $title, $content, $bposition, $weight, $active, $blockfile, $view, $expire, $action, $which) = mysql_fetch_row(sql_query("SELECT bkey, title, content, bposition, weight, active, blockfile, view, expire, action, which FROM ".$prefix."_blocks WHERE bid='$bid'"));
	if ($blockfile != "") {
		$type = "(�������� ����)";
	} else {
		$type = "(HTML ����)";
	}
	echo "<h2>����: $title $type</h2>"
	."<form action=\"".$admin_file.".php\" method=\"post\">"
	."<table border=\"0\" align=\"center\">"
	."<tr><td>���������:</td><td><input type=\"text\" name=\"title\" maxlength=\"50\" size=\"65\" style=\"width:400px\" value=\"$title\"></td></tr>";
	if ($blockfile != "") {
		echo "<tr><td>��� �����:</td><td><select name=\"blockfile\" style=\"width:400px\">";
		$dir = opendir("blocks");
		while ($file = readdir($dir)) {
			if (preg_match("/^block\-(.+)\.php/", $file, $matches)) {
				$found = str_replace("_", " ", $matches[1]);
				$selected = ($blockfile == $file) ? "selected" : "";
				echo "<option value=\"$file\" $selected>".$found."</option>";
			}
		}
		closedir($dir);
	} else {
		echo "<tr><td>����������:</td><td><textarea name=\"content\" cols=\"65\" rows=\"15\" style=\"width:400px\">$content</textarea></td></tr>";
	}
	$oldposition = $bposition;
	echo "<input type=\"hidden\" name=\"oldposition\" value=\"$oldposition\">";
	$sel1 = ($bposition == "l") ? "selected" : "";
	$sel2 = ($bposition == "c") ? "selected" : "";
	$sel3 = ($bposition == "r") ? "selected" : "";
	$sel4 = ($bposition == "d") ? "selected" : "";
	$sel5 = ($bposition == "b") ? "selected" : "";
	$sel6 = ($bposition == "f") ? "selected" : "";
	echo "<tr><td>�������:</td><td><select name=\"bposition\" style=\"width:400px\">"
	."<option name=\"bposition\" value=\"l\" $sel1>�����</option>"
	."<option name=\"bposition\" value=\"c\" $sel2>�� ������ ������</option>"
	."<option name=\"bposition\" value=\"d\" $sel4>�� ������ �����</option>"
	."<option name=\"bposition\" value=\"r\" $sel3>������</option>"
	."<option name=\"bposition\" value=\"b\" $sel5>������� ������</option>"
	."<option name=\"bposition\" value=\"f\" $sel6>������ ������</option>"
	."</select></td></tr>";
	echo "<tr><td>���������� ���� � �������:</td><td align=\"center\"><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\" style=\"width:400px\"><tr>";
	$where_mas = explode(",", $which);
	$cel = ($where_mas[0] == "ihome") ? " checked" : "";
	echo "<td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"ihome\"$cel></td><td>�������</td>";
	global $allowed_modules;
	$a = 1;
	foreach ($allowed_modules as $name => $title) {
		$i++;
		$cel = "";
		foreach ($where_mas as $key => $val) {
			if ($val == $name) $cel = " checked";
		}
		$title = str_replace("_", " ", $title);
		echo "<td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"".$name."\"$cel></td><td>$title</td>";
		if ($a == 2) {
			echo "</tr><tr>";
			$a = 0;
		} else {
			$a++;
		}
	}
	$where_mas = explode(",", $which);
    $cel = "";
    $hel = "";
	switch ($where_mas[0]) {
		case "all":
		$cel = " checked";
		break;
		case "home":
		$hel = " checked";
		break;
		case "infly":
		$fel = " checked";
		break;
	}
	echo "</tr><tr><td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"all\"$cel></td><td><b>�� ���� �������</b></td><td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"home\"$hel></td><td><b>������ �� �������</b></td><td><input type=\"checkbox\" name=\"blockwhere[]\" value=\"infly\"$fel></td><td><b>��������� ����</b></td></tr></table></td></tr>";
	$sel1 = ($active == 1) ? "checked" : "";
	$sel2 = ($active == 0) ? "checked" : "";
	if ($expire != 0) {
		$newexpire = 0;
		$oldexpire = $expire;
		$expire = intval(($expire - time()) / 3600);
		$exp_day = $expire / 24;
		$expire_text = "<input type=\"hidden\" name=\"expire\" value=\"$oldexpire\">��������: $expire ���� (".substr($exp_day,0,5)." ����)";
	} else {
		$newexpire = 1;
		$expire_text = "<input type=\"text\" name=\"expire\" value=\"0\" maxlength=\"3\" size=\"65\" style=\"width:400px\">";
	}
	$selact1 = ($action == "d") ? "selected" : "";
	$selact2 = ($action == "r") ? "selected" : "";
	echo "<tr><td>��������?</td><td><input type=\"radio\" name=\"active\" value=\"1\" $sel1>�� &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"active\" value=\"0\" $sel2>���</td></tr>"
	."<tr><td>����� ������, � ����:</td><td>$expire_text</td></tr>"
	."<tr><td>����� ���������:</td><td><select name=\"action\" style=\"width:400px\">"
	."<option name=\"action\" value=\"d\" $selact1>����.</option>"
	."<option name=\"action\" value=\"r\" $selact2>�������</option></select></td></tr>";
	$sel1 = ($view == 0) ? "selected" : "";
	$sel2 = ($view == 1) ? "selected" : "";
	$sel3 = ($view == 2) ? "selected" : "";
	$sel4 = ($view == 3) ? "selected" : "";
	echo "</td></tr><tr><td>��� ��� ����� ������?</td><td><select name=\"view\" style=\"width:400px\">"
	."<option value=\"0\" $sel1>��� ����������</option>"
	."<option value=\"1\" $sel2>������ ������������</option>"
	."<option value=\"2\" $sel3>������ ��������������</option>"
	."<option value=\"3\" $sel4>������ �������</option>"
	."</select></td></tr></table><br>"
	."<center><input type=\"hidden\" name=\"bid\" value=\"$bid\">"
	."<input type=\"hidden\" name=\"newexpire\" value=\"$newexpire\">"
	."<input type=\"hidden\" name=\"bkey\" value=\"$bkey\">"
	."<input type=\"hidden\" name=\"weight\" value=\"$weight\">"
	."<input type=\"hidden\" name=\"op\" value=\"BlocksEditSave\">"
	."<input type=\"submit\" value=\"���������\"></form></center>";
}

function BlocksEditSave($newexpire, $bid, $bkey, $title, $content, $oldposition, $bposition, $active, $weight, $blockfile, $view, $expire, $action) {
	global $prefix, $db, $admin_file;
	if (isset($_POST['blockwhere'])) {
		$blockwhere = $_POST['blockwhere'];
		$which = "";
		$which = (in_array("all", $blockwhere)) ? "all" : $which;
		$which = (in_array("home", $blockwhere)) ? "home" : $which;
		if ($which == "") {
			print $which;
			while(list($key, $val) = each($blockwhere)) {
				$which .= "{$val},";
			}
		}
		sql_query("UPDATE ".$prefix."_blocks SET which=".sqlesc($which)." WHERE bid=".sqlesc($bid));
	} else {
		sql_query("UPDATE ".$prefix."_blocks SET which='' WHERE bid=".sqlesc($bid));
	}
		if ($oldposition != $bposition) {
			$result5 = sql_query("SELECT bid FROM ".$prefix."_blocks WHERE weight>=".sqlesc($weight)." AND bposition=".sqlesc($bposition));
			$fweight = $weight;
			$oweight = $weight;
			while (list($nbid) = mysql_fetch_row($result5)) {
				$weight++;
				sql_query("UPDATE ".$prefix."_blocks SET weight=".sqlesc($weight)." WHERE bid=".sqlesc($nbid)) or sqlerr(__FILE__,__LINE__);
			}
			$result6 = sql_query("SELECT bid FROM ".$prefix."_blocks WHERE weight>".sqlesc($oweight)." AND bposition=".sqlesc($oldposition)) or sqlerr(__FILE__,__LINE__);
			while (list($obid) = mysql_fetch_row($result6)) {
				sql_query("UPDATE ".$prefix."_blocks SET weight=".sqlesc($oweight)." WHERE bid=".sqlesc($obid));
				$oweight++;
			}
			list($lastw) = mysql_fetch_row(sql_query("SELECT weight FROM ".$prefix."_blocks WHERE bposition=".sqlesc($bposition)." ORDER BY weight DESC LIMIT 0,1"));
			if ($lastw <= $fweight) {
				$lastw++;
				sql_query("UPDATE ".$prefix."_blocks SET title=".sqlesc($title).", content=".sqlesc($content).", bposition=".sqlesc($bposition).", weight=".sqlesc($lastw).", active=".sqlesc($active).", blockfile=".sqlesc($blockfile).", view=".sqlesc($view)." WHERE bid=".sqlesc($bid)) or sqlerr(__FILE__,__LINE__);
			} else {
				sql_query("UPDATE ".$prefix."_blocks SET title=".sqlesc($title).", content=".sqlesc($content).", bposition=".sqlesc($bposition).", weight=".sqlesc($fweight).", active=".sqlesc($active).", blockfile=".sqlesc($blockfile).", view=".sqlesc($view)." WHERE bid=".sqlesc($bid)) or sqlerr(__FILE__,__LINE__);
			}
		} else {
			if ($expire == "") $expire = 0;
			if ($newexpire == 1 && $expire != 0) $expire = time() + ($expire * 86400);
			$result8 = sql_query("UPDATE ".$prefix."_blocks SET bkey=".sqlesc($bkey).", title=".sqlesc($title).", content=".sqlesc($content).", bposition=".sqlesc($bposition).", weight=".sqlesc($weight).", active=".sqlesc($active).", blockfile=".sqlesc($blockfile).", view=".sqlesc($view).", expire=".sqlesc($expire).", action=".sqlesc($action)." WHERE bid=".sqlesc($bid)) or sqlerr(__FILE__,__LINE__);
		}
		Header("Location: ".$admin_file.".php?op=BlocksAdmin");
}

function BlocksShow($bid) {
	global $prefix, $db, $admin_file;
	BlocksNavi();
	list($bid, $bkey, $title, $content, $bposition, $blockfile) = mysql_fetch_row(sql_query("SELECT bid, bkey, title, content, bposition, blockfile FROM ".$prefix."_blocks WHERE bid='$bid'"));
	$bid = intval($bid);
	echo "<p />";
	render_blocks($bposition, $blockfile, $title, $content, $bid, 'c');
	echo "<h4>[ <a href=\"".$admin_file.".php?op=BlocksChange&bid=$bid\">��������</a> | <a href=\"".$admin_file.".php?op=BlocksEdit&bid=$bid\">�������������</a>";
	if ($bkey == "") echo " | <a href=\"".$admin_file.".php?op=BlocksDelete&bid=$bid\" OnClick=\"return DelCheck(this, '������� &quot;$title&quot;?');\">�������</a>";
	echo " | <a href=\"".$admin_file.".php?op=BlocksAdmin\">�������</a> ]</h4>";
}

function BlocksFileEdit() {
	global $prefix, $admin_file;
	BlocksNavi();
	echo "<h2>������������� ����</h2>"
	."<form action=\"".$admin_file.".php\" method=\"post\">"
	."<table border=\"0\" align=\"center\">"
	."<tr><td>��� �����:</td><td>"
	."<select name=\"bf\" style=\"width:400px\">";
	$handle = opendir("blocks");
	while ($file = readdir($handle)) {
		if (preg_match("/^block\-(.+)\.php/", $file, $matches)) {
			$found = str_replace("-", " ", $matches[1]);
			if (mysql_num_rows(sql_query("SELECT * FROM ".$prefix."_blocks WHERE blockfile='$file'")) > 0) echo "<option value=\"$file\">$found</option>\n";
		}
	}
	closedir($handle);
	echo "</select></td></tr>"
	."<tr><td colspan=\"2\" align=\"center\"><input type=\"hidden\" name=\"op\" value=\"BlocksbfEdit\"><input type=\"submit\" value=\"������������� ����\"></td></tr></table></form>";
}

function BlocksChange($bid, $ok=0) {
	global $prefix, $admin_file;
	$bid = intval($bid);
	$row = mysql_fetch_array(sql_query("SELECT active FROM ".$prefix."_blocks WHERE bid='$bid'"));
	$active = intval($row['active']);
	if (($ok) || ($active == 0)) {
		if ($active == 0) {
			$active = 1;
		} elseif ($active == 1) {
			$active = 0;
		}
		$result = sql_query("UPDATE ".$prefix."_blocks SET active='$active' WHERE bid='$bid'");
		Header("Location: ".$admin_file.".php?op=BlocksAdmin");
	} else {
		list($title, $content, $active) = mysql_fetch_row(sql_query("SELECT title, content, active FROM ".$prefix."_blocks WHERE bid='$bid'"));
		if ($active == 0) {
			echo "<center>������������ ���� \"$title\"?<br /><br />";
		} else {
			echo "<center>�������������� ���� \"$title\"?<br /><br />";
		}
		echo "[ <a href=\"".$admin_file.".php?op=BlocksChange&bid=$bid&ok=1\">��</a> | <a href=\"".$admin_file.".php?op=BlocksAdmin\">���</a> ]</center>";
	}
}

function BlocksbfEdit() {
	global $prefix, $db, $admin_file;
	if ($_REQUEST['bf'] != "") {
		$bf = $_REQUEST['bf'];
		if (isset($_POST['flag'])) {
			$flaged = $_POST['flag'];
			$bf = str_replace("block-", "",$bf);
			$bf = str_replace(".php", "",$bf);
			$bf = 'block-'.$bf.'.php';
		} else {
			$bfstr = file_get_contents('blocks/'.$bf);
			if (strpos($bfstr,'BLOCKHTML') === false) {
				$flaged = 'php';
				preg_match("/<\?php.*if.*\(\!defined\(\'BLOCK_FILE\'\)\).*exit;.*?}(.*)\?>/is", $bfstr, $out);
				unset($out[0]);
			} else {
				$flaged = 'html';
				preg_match("/<<<BLOCKHTML(.*)BLOCKHTML;/is", $bfstr, $out);
				unset($out[0]);
			}
		}
		BlocksNavi();
		$permtest = end_chmod("blocks", 777);
		if ($permtest)
			stdmsg("������", $permtest, 'error');
		echo "<h2>����: $bf</h2>"
		."<form action=\"".$admin_file.".php\" method=\"post\">"
		."<table border=\"0\" align=\"center\">"
		."<tr><td>����������:</td><td><textarea wrap=\"virtual\" name=\"blocktext\" cols=\"65\" rows=\"25\" style=\"width:400px\">".$out[1]."</textarea></td></tr>"
		."<tr><td colspan=\"2\" align=\"center\"><br /><input type=\"hidden\" name=\"bf\" value=\"".$bf."\">"
		."<input type=\"hidden\" name=\"flag\" value=\"".$flaged."\">"
		."<input type=\"hidden\" name=\"op\" value=\"BlocksbfSave\">"
		."<input type=\"submit\" value=\"���������\"> <input type=\"button\" value=\"�����\" onClick=\"javascript:history.go(-1)\"></td></tr></table></form>";
	} else {
		Header("Location: ".$admin_file.".php?op=BlocksFile");
	}
}

function BlocksbfSave() {
	global $prefix, $db, $admin_file;
	if (isset($_POST['blocktext'])) {
		if (!empty($_POST['blocktext'])) {
			if (isset($_POST['bf'])) {
				$bf = $_POST['bf'];
				if ($handle = fopen('blocks/'.$bf, 'w')) {
					$htmlB = "";
					$htmlE = "";
					if (isset($_POST['flag'])) {
						$flaged = $_POST['flag'];
						if ($flaged == 'html') {
							$htmlB = "\$content=<<<BLOCKHTML\n";
							$htmlE = "\nBLOCKHTML;\n";
						}
					}
					$str_set = $_POST['blocktext'];
					fwrite($handle, "<?php\n\nif (!defined('BLOCK_FILE')) {\nheader(\"Location: ../index.php\");\nexit;\n}\n\n".$htmlB.$str_set.$htmlE."\r\n?>");
					Header("Location: ".$admin_file.".php?op=BlocksAdmin");
				}
				fclose($handle);
			}
		}
	}
}

switch($op) {
	case "BlocksAdmin":
	BlocksAdmin();
	break;
	
	case "BlocksNew":
	BlocksNew();
	break;
	
	case "BlocksFile":
	BlocksFile();
	break;
	
	case "BlocksFileEdit":
	BlocksFileEdit();
	break;
	
	case "BlocksAdd":
	BlocksAdd($title, $content, $bposition, $active, $blockfile, $view, $expire, $action);
	break;
	
	case "BlocksEdit":
	BlocksEdit($bid);
	break;
	
	case "BlocksEditSave":
	BlocksEditSave($newexpire, $bid, $bkey, $title, $content, $oldposition, $bposition, $active, $weight, $blockfile, $view, $expire, $action);
	break;
	
	case "BlocksChange":
	BlocksChange($bid, $ok, $de);
	break;
	
	case "BlocksDelete":
	$bid = intval($_REQUEST['bid']);
	list($bposition, $weight) = mysql_fetch_row(sql_query("SELECT bposition, weight FROM ".$prefix."_blocks WHERE bid='$bid'"));
	$result = sql_query("SELECT bid FROM ".$prefix."_blocks WHERE weight>'$weight' AND bposition='$bposition'");
	while (list($nbid) = mysql_fetch_row($result)) {
		sql_query("UPDATE ".$prefix."_blocks SET weight='$weight' WHERE bid='$nbid'");
		$weight++;
	}
	sql_query("DELETE FROM ".$prefix."_blocks WHERE bid='$bid'");
	Header("Location: ".$admin_file.".php?op=BlocksAdmin");
	break;
	
	case "BlocksOrder":
	BlocksOrder($weightrep, $weight, $bidrep, $bidori);
	break;
	
	case "BlocksFixweight":
	BlocksFixweight();
	break;
	
	case "BlocksShow":
	BlocksShow($bid);
	break;
	
	case "BlocksbfEdit":
	BlocksbfEdit();
	break;
	
	case "BlocksbfSave":
	BlocksbfSave();
	break;
}
?>