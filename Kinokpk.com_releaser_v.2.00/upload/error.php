<?php
// CUSTOM ERROR MESSAGES
require_once("include/bittorrent.php");
$errors = array(
400 => '<h1>400 - Bad Request</h><br/>������ �� ����� ������',
401 => '<h1>401 - Unauthorized</h1><br/>������������ ����������',
403 => '<h1>403 - Forbidden</h1><br/>������ ��������',
404 => '<h1>404 - Not Found</h1><br/>������ �������',
);
function bark($msg) {
	genbark($msg, $tracker_lang['error']);
}
$error = intval($_GET['id']);

if (isset($errors["$error"])) bark ($errors["$error"]); else bark("����������� ������ HTTP");