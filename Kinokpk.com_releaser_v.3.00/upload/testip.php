<?php
/**
 * Test that ip was banned
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";
dbconn();
getlang('testip');
loggedinorreturn();
if (get_user_class() < UC_MODERATOR) stderr($tracker_lang['error'], $tracker_lang['permission_denied']);

if ($_SERVER["REQUEST_METHOD"] == "POST")
$ip = htmlspecialchars(trim((string)$_POST["ip"]));
else
$ip = htmlspecialchars(trim((string)$_GET["ip"]));
if ($ip)
{
	$res = sql_query("SELECT mask FROM bans");
	while (list($mask) = mysql_fetch_array($res))
	$maskres[] = $mask;
	$ipsniff = new IPAddressSubnetSniffer($maskres);
	if (!$ipsniff->ip_is_allowed($ip) )
	stderr($tracker_lang['result'], "".$tracker_lang['ip_address']." <b>$ip</b> ".$tracker_lang['not_banned']."");
	else
	{
		stderr($tracker_lang['result'], "".$tracker_lang['ip_address']." <b>$ip ".$tracker_lang['banned']."</b>");
	}
}
stdhead($tracker_lang['check_ip']);

?>
<h1><?=$tracker_lang['check_ip']?></h1>
<form method=post action=testip.php>
<table border=1 cellspacing=0 cellpadding=5>
	<tr>
		<td class=rowhead><?=$tracker_lang['ip_address']?></td>
		<td><input type=text name=ip></td>
	</tr>
	<tr>
		<td colspan=2 align=center><input type=submit class=btn value='OK'></td>
	</tr>
	</form>
</table>

<?
stdfoot();
?>