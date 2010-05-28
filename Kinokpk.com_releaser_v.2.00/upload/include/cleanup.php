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

# IMPORTANT: Do not edit below unless you know what you are doing!
if(!defined('IN_TRACKER'))
  die('Hacking attempt!');

function docleanup() {
	global $pm_delete_sys_days, $pm_delete_user_days, $torrent_dir, $signup_timeout, $max_dead_torrent_time, $use_ttl, $autoclean_interval, $points_per_cleanup, $ttl_days, $tracker_lang, $rootpath;

	@set_time_limit(0);
	@ignore_user_abort(1);

	do {
		$res = sql_query("SELECT id FROM torrents") or sqlerr(__FILE__,__LINE__);
		$ar = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			$ar[$id] = 1;
		}

		if (!count($ar))
			break;

		$dp = @opendir($rootpath.$torrent_dir);
		if (!$dp)
			break;

		$ar2 = array();
		while (($file = @readdir($dp)) !== false) {
			if (!preg_match('/^(\d+)\.torrent$/', $file, $m))
				continue;
			$id = $m[1];
			$ar2[$id] = 1;
			if (isset($ar[$id]) && $ar[$id])
				continue;
			$ff = $rootpath.$torrent_dir.'/'.$file;
			@unlink($ff);
		}
		@closedir($dp);

		if (!count($ar2))
			break;

		$delids = array();
		foreach (array_keys($ar) as $k) {
			if (isset($ar2[$k]) && $ar2[$k])
				continue;
			$delids[] = $k;
			unset($ar[$k]);
		}
		if (count($delids))
			sql_query("DELETE FROM torrents WHERE id IN (" . join(",", $delids) . ")") or sqlerr(__FILE__,__LINE__);

		$res = sql_query("SELECT torrent FROM peers GROUP BY torrent") or sqlerr(__FILE__,__LINE__);
		$delids = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			if (isset($ar[$id]) && $ar[$id])
				continue;
			$delids[] = $id;
		}
		if (count($delids))
			sql_query("DELETE FROM peers WHERE torrent IN (" . join(",", $delids) . ")") or sqlerr(__FILE__,__LINE__);

		$res = sql_query("SELECT torrent FROM files GROUP BY torrent") or sqlerr(__FILE__,__LINE__);
		$delids = array();
		while ($row = mysql_fetch_array($res)) {
			$id = $row[0];
			if ($ar[$id])
				continue;
			$delids[] = $id;
		}
		if (count($delids))
			sql_query("DELETE FROM files WHERE torrent IN (" . join(", ", $delids) . ")") or sqlerr(__FILE__,__LINE__);
	} while (0);

	$deadtime = deadtime();
	sql_query("DELETE FROM peers WHERE last_action < FROM_UNIXTIME($deadtime)") or sqlerr(__FILE__,__LINE__);

	$deadtime = deadtime();
	sql_query("UPDATE snatched SET seeder = 'no' WHERE seeder = 'yes' AND last_action < FROM_UNIXTIME($deadtime)");

	$deadtime -= $max_dead_torrent_time;

  //sql_query("UPDATE torrents SET visible='no' WHERE visible='yes' AND last_action < FROM_UNIXTIME($deadtime) AND filename <> 'nofile'") or sqlerr(__FILE__,__LINE__);

	$torrents = array();
	$res = sql_query('SELECT torrent, seeder, COUNT(*) AS c FROM peers GROUP BY torrent, seeder');
	while ($row = mysql_fetch_array($res)) {
		if ($row['seeder'] == 'yes')
			$key = 'seeders';
		else
			$key = 'leechers';
		$torrents[$row['torrent']][$key] = $row['c'];
	}

	$res = sql_query('SELECT torrent, COUNT(*) AS c FROM comments GROUP BY torrent');
	while ($row = mysql_fetch_array($res)) 
		$torrents[$row['torrent']]['comments'] = $row['c'];

	$fields = explode(':', 'comments:leechers:seeders');
	$res = sql_query('SELECT id, seeders, leechers, comments FROM torrents');
	while ($row = mysql_fetch_array($res)) {
		$id = $row['id'];
		$torr = $torrents[$id];
		foreach ($fields as $field) {
			if (!isset($torr[$field]))
				$torr[$field] = 0;
		}
		$update = array();
		foreach ($fields as $field) {
			if ($torr[$field] != $row[$field])
				$update[] = $field . ' = ' . $torr[$field];
		}
		if (count($update))
			sql_query("UPDATE torrents SET " . implode(",", $update) . " WHERE id = $id");
	}

	/*	//delete inactive user accounts
		$secs = 31*86400;
		$dt = sqlesc(get_date_time(gmtime() - $secs));
		$maxclass = UC_POWER_USER;
		$res = sql_query("SELECT id FROM users WHERE parked='no' AND status='confirmed' AND class <= $maxclass AND last_access < $dt AND last_access <> '0000-00-00 00:00:00'") or sqlerr(__FILE__,__LINE__);
		while ($arr = mysql_fetch_assoc($res)) {
			sql_query("DELETE FROM users WHERE id = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM messages WHERE receiver = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM friends WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM friends WHERE friendid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM blocks WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM blocks WHERE blockid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM bookmarks WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM invites WHERE inviter = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM peers WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM readtorrents WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM simpaty WHERE fromuserid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM addedrequests WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM checkcomm WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM offervotes WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
		}

       //delete parked user accounts
       $secs = 175*86400; // change the time to fit your needs
       $dt = sqlesc(get_date_time(gmtime() - $secs));
       $maxclass = UC_POWER_USER;
       $res = sql_query("SELECT id FROM users WHERE parked='yes' AND status='confirmed' AND class <= $maxclass AND last_access < $dt");
       if (mysql_num_rows($res) > 0) {
       	while ($arr = mysql_fetch_array($res)) {
			sql_query("DELETE FROM users WHERE id = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM messages WHERE receiver = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM friends WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM friends WHERE friendid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM blocks WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM blocks WHERE blockid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM bookmarks WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM invites WHERE inviter = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM peers WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM readtorrents WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM simpaty WHERE fromuserid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM addedrequests WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM checkcomm WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM offervotes WHERE userid = ".sqlesc($arr["id"])) or sqlerr(__FILE__,__LINE__);
		}
	}
       */
       
//������� ��������� ���������� ��������� ������ n ����
$secs_system = $pm_delete_sys_days*86400; // ���������� ����
$dt_system = sqlesc(get_date_time(gmtime() - $secs_system)); // ������� ����� ���������� ����
sql_query("DELETE FROM messages WHERE archived = 'no' AND unread = 'no' AND added < $dt_system") or sqlerr(__FILE__, __LINE__);
//������� ��� ���������� ��������� ������ n ����
$secs_all = $pm_delete_user_days*86400; // ���������� ����
$dt_all = sqlesc(get_date_time(gmtime() - $secs_all)); // ������� ����� ���������� ����
sql_query("DELETE FROM messages WHERE unread = 'no' AND archived = 'no' AND added < $dt_all") or sqlerr(__FILE__, __LINE__);


	// delete unconfirmed users if timeout.
	$deadtime = TIMENOW - $signup_timeout;
	$res = sql_query("SELECT id FROM users WHERE status = 'pending' AND added < FROM_UNIXTIME($deadtime) AND last_login < FROM_UNIXTIME($deadtime) AND last_access < FROM_UNIXTIME($deadtime)") or sqlerr(__FILE__,__LINE__);
	if (mysql_num_rows($res) > 0) {
		while ($arr = mysql_fetch_array($res)) {
			sql_query("DELETE FROM users WHERE id = ".sqlesc($arr["id"]));

		}
	}
//���������� ��������������� ������������� (� ��� � ���� 5 �����)
        $res = sql_query("SELECT id, username, modcomment FROM users WHERE num_warned > 4 AND enabled = 'yes' ") or sqlerr(__FILE__,__LINE__);
        $num = mysql_num_rows($res);
        while ($arr = mysql_fetch_assoc($res)) {
         $modcom = sqlesc(date("Y-m-d") . " - �������� �������� (5 � ����� ��������������) " . "\n". $arr[modcomment]);
        sql_query("UPDATE users SET enabled = 'no' WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
        sql_query("UPDATE users SET modcomment = $modcom WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);
        write_log("������������ $arr[username] ��� �������� �������� (5 � ����� ��������������)","CCCCCC","tracker");
        }
        write_log("��������� $num ������������� (5 � ����� ��������������)","","admin");
        
	// Update seed bonus
	sql_query("UPDATE users SET bonus = bonus + $points_per_cleanup WHERE users.id IN (SELECT userid FROM peers WHERE seeder = 'yes')") or sqlerr(__FILE__,__LINE__);

	//remove expired warnings
	$now = sqlesc(get_date_time());
	$modcomment = sqlesc(date("Y-m-d") . " - �������������� ����� �������� �� ��������.\n");
	$msg = sqlesc("���� �������������� ����� �� ��������. ������������ ������ �� �������� �������������� � ��������� ��������.\n");
	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster) SELECT 0, id, $now, $msg, 0 FROM users WHERE warned='yes' AND warneduntil < NOW() AND warneduntil <> '0000-00-00 00:00:00'") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE users SET warned='no', warneduntil = '0000-00-00 00:00:00', modcomment = CONCAT($modcomment, modcomment) WHERE warned='yes' AND warneduntil < NOW() AND warneduntil <> '0000-00-00 00:00:00'") or sqlerr(__FILE__,__LINE__);

	// promote power users
	$limit = 25*1024*1024*1024;
	$minratio = 1.05;
	$maxdt = sqlesc(get_date_time(gmtime() - 86400*28));
	$now = sqlesc(get_date_time());
	$msg = sqlesc("���� ������������, �� ���� ����-�������� �� ����� [b]������� ����������[/b].");
	$subject = sqlesc("�� ���� ��������");
	$modcomment = sqlesc(date("Y-m-d") . " - ������� �� ������ \"".$tracker_lang["class_power_user"]."\" ��������.\n");
	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, id, $now, $msg, 0, $subject FROM users WHERE class = 0 AND uploaded >= $limit AND uploaded / downloaded >= $minratio AND added < $maxdt") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE users SET class = ".UC_POWER_USER.", modcomment = CONCAT($modcomment, modcomment) WHERE class = ".UC_USER." AND uploaded >= $limit AND uploaded / downloaded >= $minratio AND added < $maxdt") or sqlerr(__FILE__,__LINE__);

	// demote power users
	$minratio = 0.95;
	$now = sqlesc(get_date_time());
	$msg = sqlesc("�� ���� ����-�������� � ����� [b]������� ������������[/b] �� ����� [b]������������[/b] ������-��� ��� ������� ���� ���� [b]{$minratio}[/b].");
	$subject = sqlesc("�� ���� ��������");
	$modcomment = sqlesc(date("Y-m-d") . " - ������� �� ������ \"".$tracker_lang["class_user"]."\" ��������.\n");
	sql_query("INSERT INTO messages (sender, receiver, added, msg, poster, subject) SELECT 0, id, $now, $msg, 0, $subject FROM users WHERE class = 1 AND uploaded / downloaded < $minratio") or sqlerr(__FILE__,__LINE__);
	sql_query("UPDATE users SET class = ".UC_USER.", modcomment = CONCAT($modcomment, modcomment) WHERE class = ".UC_POWER_USER." AND uploaded / downloaded < $minratio") or sqlerr(__FILE__,__LINE__);

	// delete old torrents
	if ($use_ttl) {
		$dt = sqlesc(get_date_time(gmtime() - ($ttl_days * 86400)));
		$res = sql_query("SELECT id, name FROM torrents WHERE added < $dt") or sqlerr(__FILE__,__LINE__);
	while ($arr = mysql_fetch_array($res))
	{
		@unlink($rootpath.$torrent_dir.'/'.$arr['id'].'.torrent');
			sql_query("DELETE FROM torrents WHERE id=$arr[id]") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM snatched WHERE torrentid=$arr[id]") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM peers WHERE torrent=$arr[id]") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM comments WHERE torrent=$arr[id]") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM files WHERE torrent=$arr[id]") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM ratings WHERE torrent=$arr[id]") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM checkcomm WHERE checkid=$arr[id] AND torrent = 1") or sqlerr(__FILE__,__LINE__);
			sql_query("DELETE FROM bookmarks WHERE id=$arr[id]") or sqlerr(__FILE__,__LINE__);
			write_log("������� $arr[id] ($arr[name]) ��� ������ �������� (������ ��� $ttl_days ����)","","torrent");
		}
	}

	$secs = 1 * 3600;
	$dt = time() - $secs;
	sql_query("DELETE FROM sessions WHERE time < $dt") or sqlerr(__FILE__,__LINE__);
	
// UPDATE CACHES:
    
    $res=sql_query("(SELECT COUNT(*) FROM users) UNION ALL
     (SELECT COUNT(*) FROM users WHERE status='pending') UNION ALL
      (SELECT COUNT(*) FROM users WHERE gender='1') UNION ALL
       (SELECT COUNT(*) FROM users WHERE gender='2') UNION ALL
        (SELECT COUNT(*) FROM torrents) UNION ALL
         (SELECT COUNT(*) FROM torrents WHERE filename = 'nofile') UNION ALL
          (SELECT COUNT(*) FROM torrents WHERE visible='no') UNION ALL
           (SELECT COUNT(*) FROM torrents WHERE filename = 'nofile') UNION ALL
            (SELECT COUNT(*) FROM torrents WHERE visible='no') UNION ALL
             (SELECT COUNT(*) FROM users WHERE warned = 'yes') UNION ALL
              (SELECT COUNT(*) FROM users WHERE enabled = 'no') UNION ALL
               (SELECT COUNT(*) FROM users WHERE class = ".UC_UPLOADER.") UNION ALL
                (SELECT COUNT(*) FROM users WHERE class = ".UC_VIP.")");

$params = array('users','users_pending','males','females','torrents','torrents_nofile','torrents_dead','users_warned','users_warned','users_disabled','uploaders','vips');
$i=0;
while (list($value) = mysql_fetch_array($res)){
  $block_online[$params[$i]] = $value;
  $i++;
}

 if (!defined("CACHE_REQUIRED")){
 	require_once($rootpath . 'classes/cache/cache.class.php');
	require_once($rootpath .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
  }
  		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());
		
 $cache->set('block-stats', 'queries', $block_online);




}
//GENERATE SITEMAP
require_once($rootpath . "include/createsitemap.php");
?>