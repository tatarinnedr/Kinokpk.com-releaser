<?php
/**
 * Retrackers administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";

function bark($msg) {
	stderr($tracker_lang['error'], $msg);
}

dbconn();
getlang('retrackeradmin');
loggedinorreturn();
httpauth();

if (get_user_class() < UC_SYSOP) bark("Access denied. You're not SYSOP.");
if (!isset($_GET['action'])) {
	stdhead($tracker_lang['panel_name']);
	print("<div algin=\"center\"><h1>{$tracker_lang['panel_name']}</h1></div>");
	print("<table width=\"100%\" border=\"0\"><tr><td><a href=\"retrackeradmin.php?action=add\">{$tracker_lang['add_retracker']}</a></td></tr></table>");
	$rtarray = sql_query("SELECT * FROM retrackers ORDER BY sort ASC");
	print("<form name=\"saveids\" action=\"retrackeradmin.php?action=saveids\" method=\"post\"><table width=\"100%\" border=\"1\"><tr><td align=\"center\" colspan=\"5\">{$tracker_lang['panel_notice']}</td></tr><tr><td class=\"colhead\">ID</td><td class=\"colhead\">{$tracker_lang['order']}</td><td class=\"colhead\">{$tracker_lang['announce_url']}</td><td class=\"colhead\">{$tracker_lang['subnet_mask']}</td><td class=\"colhead\">{$tracker_lang['edit_delete']}</td></tr>");
	while($rt = mysql_fetch_array($rtarray)) {
		print("<tr><td>".$rt['id']."</td><td><input type=\"text\" name=\"sort[".$rt['id']."]\" size=\"4\" value=\"".$rt['sort']."\"></td><td>{$rt['announce_url']}</td><td>{$rt['mask']}</td><td><a href=\"retrackeradmin.php?action=edit&id=".$rt['id']."\">E</a> | <a onClick=\"return confirm('{$tracker_lang['are_you_sure']}')\" href=\"retrackeradmin.php?action=delete&id=".$rt['id']."\">D</a></td></tr>");
	}
	print("</table><input type=\"submit\" class=\"btn\" value=\"{$tracker_lang['save_order']}\"></form>");
	stdfoot();
}

elseif ($_GET['action'] == 'saveids') {

	if (is_array($_POST['sort'])) {

		foreach ($_POST['sort'] as $id => $s) {

			sql_query("UPDATE retrackers SET sort = ".(int)$s."  WHERE id = " . (int)$id);
		}
		safe_redirect(" retrackeradmin.php");
		exit();
	}
	else bark("Missing form data");
}

elseif ($_GET['action'] == 'add') {
	stdhead($tracker_lang['add_retracker']);
	print("<form action=\"retrackeradmin.php?action=saveadd\" name=\"savearray\" method=\"post\"><table width=\"100%\"><tr><td class=\"colhead\">{$tracker_lang['announce_url']}</td></tr><tr><td><input type=\"text\" name=\"retracker\" size=\"80\"></td></tr><tr><td class=\"colhead\">{$tracker_lang['subnet_mask']}</td></tr><tr><td><input type=\"text\" name=\"mask\" size=\"15\"></td></tr><tr><td class=\"colhead\">{$tracker_lang['order']}</td></tr><tr><td><input type=\"text\" name=\"sort\" size=\"4\"></td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"{$tracker_lang['add_retracker']}\"></td></tr></table></form>");
	stdfoot();
}

elseif ($_GET['action'] == 'delete') {
	if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");
	sql_query("DELETE FROM retrackers WHERE id = ".(int) $_GET['id']);
	safe_redirect(" retrackeradmin.php");
	exit();

}

elseif ($_GET['action'] == 'edit') {
	if ((!isset($_GET['id'])) || ($_GET['id'] == "") || (!is_numeric($_GET['id']))) bark("Wrong ID");

	$rtarray = sql_query("SELECT * FROM retrackers WHERE id=".(int)$_GET['id']);
	list($id,$sort,$announce_url,$mask) = mysql_fetch_array($rtarray);

	stdhead($tracker_lang['editing_retracker']);
	print("<form name=\"save\" action=\"retrackeradmin.php?action=saveedit\" method=\"post\"><table width=\"100%\"><tr><td class=\"colhead\">{$tracker_lang['announce_url']}</td></tr><tr><td><input type=\"hidden\" name=\"id\" value=\"".$id."\"><input type=\"text\" name=\"retracker\" size=\"80\" value=\"".$announce_url."\"></td></tr><tr><td class=\"colhead\">{$tracker_lang['subnet_mask']}</td></tr><tr><td><input type=\"text\" name=\"mask\" size=\"15\" value=\"$mask\"></td></tr><tr><td class=\"colhead\">{$tracker_lang['order']}</td></tr><tr><td><input type=\"text\" name=\"sort\" size=\"4\" value=\"".$sort."\"></td></tr><tr><td><input type=\"submit\" class=\"btn\" value=\"{$tracker_lang['edit']}\"></td></tr></table></form>");
	stdfoot();
}

elseif ($_GET['action'] == 'saveedit') {
	sql_query("UPDATE retrackers SET announce_url = ".sqlesc(htmlspecialchars((string)$_POST['retracker'])).", mask = ".sqlesc(htmlspecialchars((string)$_POST['mask'])).", sort = ".intval($_POST['sort'])." WHERE id = ".intval($_POST['id']));
	safe_redirect(" retrackeradmin.php");
	exit();
}
elseif ($_GET['action'] == 'saveadd') {

	sql_query("INSERT INTO retrackers (announce_url,sort,mask) VALUES (".sqlesc(htmlspecialchars((string)$_POST['retracker'])).", ".intval($_POST['sort']).", ".sqlesc(htmlspecialchars((string)$_POST['mask'])).")");
	safe_redirect(" retrackeradmin.php");
	exit();
}


