<?php
if (!defined('BLOCK_FILE')) {
	header("Location: ../index.php");
	exit;
}
getlang('blocks');
global  $tracker_lang;
$content .= "<table border=\"1\" width=\"100%\"><tr><td align=\"center\"><a href=\"viewcensoredtorrents.php\">".$tracker_lang['view_all']."</a></div><hr><table border=\"1\" class=\"main\" width=\"100%\">";

if (!defined("CACHE_REQUIRED")){
	require_once(ROOT_PATH . 'classes/cache/cache.class.php');
	require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
}
$cache=new Cache();
$cache->addDriver('file', new FileCacheDriver());

$ctorrents = $cache->get('block-cen', 'query');

if ($ctorrents===false) {
	$ctorrentsrow=sql_query("SELECT * FROM censoredtorrents ORDER BY id DESC LIMIT 3");
	$ctorrents = array();
	while ($ctres = mysql_fetch_array($ctorrentsrow))
	$ctorrents[] = $ctres;

	$time = time();
	$cache->set('block-cen', 'query', $ctorrents);
	 
}

if ($ctorrents)
foreach ($ctorrents as $ct) {

	if (strlen($ct['reason']) > 500) $reason = format_comment(substr($ct['reason'],0,500)."..."); else $reason = format_comment($ct['reason']);

	$content .= "<tr><td><b>".$ct['name']."</b><br>".$reason."</tr>";
}

$content .= "</table>";
$content .= "<td width=\"200\">".$tracker_lang['warning']."</td></tr></table>";


$blocktitle = "<font color=\"red\">".$tracker_lang['banned_releases']."</font>";
?>