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
require_once("include/bittorrent.php");

//////////////////// Array ////////////////////
dbconn();
loggedinorreturn();

stdhead("��� ������������");
if (get_user_class() < UC_MODERATOR)
{
	stdmsg($tracker_lang['error'], $tracker_lang['access_denied'], 'error');
	stdfoot();
	die();
}

$secs = 1 * 300;//����� ������� (5 ��������� �����)
$dt = time() - $secs;


$res = sql_query("SELECT SUM(1) FROM sessions $searchs WHERE time > $dt");
$row = mysql_fetch_array($res);
$count = $row[0];
$per_list = 100;

list($pagertop, $pagerbottom, $limit) = pager($per_list, $count, "online.php?");
$spy_res = sql_query("SELECT url, uid, username, class, ip, useragent FROM sessions WHERE time > $dt ORDER BY uid ASC $limit");

echo "<table  class=\"embedded\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\"><tr><td class=\"colhead\" align=\"center\" colspan=\"3\">��� ��������� ������������ (���������� �� ��������� 5 �����)</td></tr>";

echo "<tr><td  class=\"colhead\" align=\"center\">������������</td>"
."<td class=\"colhead\" align=\"center\">������</td>"
."<td class=\"colhead\" align=\"center\">�������������</td></tr>";

if($per_list < $count){
	echo "<tr><td class=\"index\" colspan=\"3\">"
	.$pagertop."</td></tr>";}


	if (isset($searchs) && $count < 1) {
		print("<tr><td class=\"index\" colspan=\"3\">".$tracker_lang['nothing_found']."</td></tr>\n");
	}



	$i=20;

	while(list($spy_url, $user_id, $user_name, $user_class, $user_ip, $user_agent, $user_time) = mysql_fetch_array($spy_res)){

		$i++;
		$spy_urlse =  basename($spy_url);
		$res_list =  explode(".php", $spy_urlse);
		$read = "";
		if($CURUSER['id'] == $user_id)
		{
			$read = "<font color=\"red\">(�� �����)</font>";
		}

		$slep = "<div class=\"sp-wrap\"><div class=\"sp-head folded clickable\"><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td class=bottom width=50%><i>�������</i></td></tr></table></div><div class=\"sp-body\">"
		."User_agent - ".$user_agent."<br />"
		."IP - <a target='_blank' href=\"http://www.dnsstuff.com/tools/whois.ch?ip=".$user_ip."\">". $user_ip."</a></div></div>";

		if($user_class != -1){
			echo "<tr><td><a target='_blank' href=\"userdetails.php?id=".$user_id."\">".get_user_class_color($user_class, $user_name)."</a> $slep</td>";
			echo "<td><b>".get_user_class_name($user_class)."</b></td><td>";
		}else{
			echo "<tr><td><a target='_blank' href=\"http://www.dnsstuff.com/tools/whois.ch?ip=".$user_ip."\">�����</a> $slep</td>";
			echo "<td>".$user_ip."</td><td>";
		}
		echo "<a target='_blank' href=\"".$spy_url."\">$spy_url</a> ".$read;
		echo "</td></tr>";



	}
	if($per_list < $count){
		echo "<tr><td class=\"index\" colspan=\"3\">"
		.$pagerbottom."</td></tr>"; }
		echo "</table>";

		stdfoot();

		?>