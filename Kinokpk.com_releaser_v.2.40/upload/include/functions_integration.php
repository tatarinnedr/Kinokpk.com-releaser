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

if (!defined("IN_TRACKER")) die('Direct access to this file not allowed.');

function forumconn($close = TRUE){
	global $fmysql_host, $fmysql_user, $fmysql_pass, $fmysql_db, $fmysql_charset;

	if ($close) {
		@mysql_close();
	}
	// connecting to IPB DB

	$fdb = mysql_connect($fmysql_host, $fmysql_user, $fmysql_pass)
	or die ('Not connected : ' . mysql_error());
	mysql_select_db ($fmysql_db, $fdb);
	mysql_set_charset($fmysql_charset);
	//connection opened
}

function relconn($close = TRUE){
	global $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $mysql_charset;
	// closing IPB DB connection
	if ($close){
		@mysql_close();
	}
	// connection closed
	$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
	or die ('Not connected : ' . mysql_error());
	mysql_select_db ($mysql_db, $db);
	mysql_set_charset($mysql_charset);
}

function generate_password_salt($len=5)
{
	$salt = '';

	for ( $i = 0; $i < $len; $i++ )
	{
		$num   = rand(33, 126);

		if ( $num == '92' )
		{
			$num = 93;
		}

		$salt .= chr( $num );
	}

	return $salt;
}

function generate_compiled_passhash($sol, $wantpassword)
{
	return md5( md5( $sol ) . $wantpassword );
}

function generate_auto_log_in_key($len=60)
{
	$pass = generate_password_salt( 60 );

	return md5($pass);
}

function do_ipb_thanks()
{
	global $torrentid,$userid,$CACHEARRAY,$fmysql_db,$fmysql_host,$fmysql_pass,$fmysql_user,$fmysql_db,$fprefix,$mysql_db,$mysql_host,$mysql_pass,$mysql_user;
	// IPB THANKS INTEGRATION
	$topicid = sql_query("SELECT topic_id FROM torrents WHERE id = ".$torrentid) or die(mysql_error());
	$topicid = mysql_result($topicid,0);

	if ($topicid != 0) {

		$ipbuser = sql_query("SELECT username FROM users WHERE id=".$userid) or die(mysql_error());
		$ipbuser = mysql_result($ipbuser,0);


		// connecting to IPB DB
		forumconn();

		$check = sql_query("SELECT id FROM ".$fprefix."members WHERE name='".$ipbuser."'") or die(mysql_error());

		if(!@mysql_result($check,0)) $ipbid = 0; else $ipbid=mysql_result($check,0);

		if ($ipbid != 0)
		{
			$postid = sql_query("SELECT topic_firstpost FROM ".$fprefix."topics WHERE tid=".$topicid) or die(mysql_error());
			$postid = mysql_result($postid,0);
			$postthanks = sql_query("SELECT post_thanks FROM ".$fprefix."posts WHERE pid=".$postid) or die(mysql_error());
			$postthanks = mysql_result($postthanks,0);

			if (strpos($postthanks,strval($ipbid)) === false) {
				if (is_null($postthanks))
				sql_query("UPDATE ".$fprefix."posts SET post_thanks = '".strval($ipbid)."' WHERE pid=".$postid) or die(mysql_error());
				else
				sql_query("UPDATE ".$fprefix."posts SET post_thanks = '".$postthanks.",".strval($ipbid)."' WHERE pid=".$postid) or die(mysql_error());
			}
		}


		// closing IPB DB connection
		relconn();
		// connection closed
	}
	//////////////////////////////////////////////////////////
}

function ipb_login($username)
{
	global $CACHEARRAY, $fprefix;
	 
	if ($CACHEARRAY['use_integration']) {
		forumconn();
		$userrow = sql_query("SELECT id, member_login_key, members_display_name, mgroup FROM {$fprefix}members WHERE name = ".sqlesc($username));
		list($id,$passhash, $dispname, $group) = mysql_fetch_array($userrow);
		if (!$id) return '';
		 
		sql_query("UPDATE {$fprefix}members SET member_login_key_expire = ".(time()+604800)." WHERE id=$id");
		$session_id = md5(uniqid(microtime()));
		 
		sql_query("INSERT INTO {$fprefix}sessions (ip_address, member_name, member_id, running_time, member_group, login_type, id, browser, location, location_1_id, location_2_id, location_3_id) VALUES ('".getip()."','{$dispname}',$id,".time().",$group,0,'$session_id','".substr($_SERVER['HTTP_USER_AGENT'],0,50)."','idx,0,',0,0,0)");


		$s = ("<iframe frameborder=\"0\" src=\"{$CACHEARRAY['forumurl']}/releaser_setcookie.php?m=$id&p=$passhash&s=$session_id&c={$CACHEARRAY['ipb_cookie_prefix']}\" width=\"0\" height=\"0\"></iframe>");
		 
		relconn();
		return $s;
	} else return '';

}

function ipb_logout($username)
{
	global $CACHEARRAY;

	if ($CACHEARRAY['use_integration']) {
		$s = ("<iframe frameborder=\"0\" src=\"{$CACHEARRAY['forumurl']}/releaser_setcookie.php?unset\" width=\"0\" height=\"0\"></iframe>");
		return $s;
	} else return '';


}
 
function register_ipb_user($wantusername,$password, $email, $gender, $year, $month, $day, $aim, $icq, $website, $yahoo, $msn, $time = 0, $relconn = true)

{
	global $CACHEARRAY, $fprefix;

	if ($CACHEARRAY['use_integration']) {
		// REGISTERING IPB USER /////////////////////////////////////////////////////////////////////////////////////////////////

		// connecting to IPB DB
		forumconn();
		//connection opened

		$ip = getip();

		if (!$time) $time = time();

		$salt = generate_password_salt();

		$passhash  =  generate_compiled_passhash( $salt, md5($password) );
		$gs = generate_auto_log_in_key();
		/////END OF PASSWORD GENERATOR/////
		/*function insert_db($table_name, $arr){
		 sql_query("INSERT INTO ".$prefix.$table_name.$arr."");
		 */
		////register////

		$first = sql_query("INSERT INTO ".$fprefix."members_converge (converge_email,converge_joined,converge_pass_hash,converge_pass_salt)
            VALUES (" .implode(",", array_map("sqlesc", array($email,$time,$passhash,$salt))).")") or die(mysql_error());

		$idf = mysql_insert_id();

		$second = sql_query("INSERT INTO ".$fprefix."members (id,name,email,mgroup,joined,ip_address,members_display_name,members_l_display_name,members_l_username,member_login_key,bday_day,bday_month,bday_year)
            VALUES (" .implode(",", array_map("sqlesc", array($idf,$wantusername,$email,$CACHEARRAY['defuserclass'],$time,$ip,$wantusername,$wantusername,$wantusername,$gs,$day,$month,$year))).")");

		$icqint = intval($icq);
		$third = sql_query("INSERT INTO ".$fprefix."member_extra (id,notes,links,bio,ta_size,photo_type,photo_location,photo_dimensions,aim_name,icq_number,website,yahoo,interests,msnname,vdirs,location,signature,avatar_location,avatar_size,avatar_type) VALUES (".sqlesc($idf).", NULL, NULL, NULL, NULL, '', '', '', ".sqlesc($aim).", ".sqlesc($icqint).", ".sqlesc($website).", ".sqlesc($yahoo).", '', ".sqlesc($msn).", '', '', '', '', '', 'local')");

		if ($gender == 1) $forumgender = 'male';
		if ($gender == 2) $forumgender = 'female';
		if ($gender == 3) $forumgender = '';

		$fourth = sql_query("INSERT INTO ".$fprefix."profile_portal (pp_member_id,pp_gender) VALUES (".$idf.",'".$forumgender."')");

		// updating forum caches
		$statcache = sql_query("SELECT cs_value FROM ".$fprefix."cache_store WHERE cs_key = 'stats'");
		$statcache = mysql_result($statcache,0);
		$statcache = unserialize($statcache);
		$statcache['mem_count']++;
		$statcache['last_mem_name'] = $wantusername;
		$statcache['last_mem_id'] = $idf;
		$statcache = serialize($statcache);
		sql_query("UPDATE ".$fprefix."cache_store SET cs_value='".$statcache."' WHERE cs_key='stats'");
	 // closing IPB DB connection
		if ($relconn) relconn();
		// connection closed

		return array ('id' => $idf, 'email' => $email, 'bday_day' => $day, 'bday_month' => $month, 'bday_year' => $year);

		//////////END IPB REGISTRATION! //////////////////////////////////////////////////////////////////////////////////////
	} else return false;
}

function ipb_bdate($date)
{
	return array('year' => substr($date,0,4), 'month' => substr($date,5,7), 'day' => substr($date,8,10));
}

function delete_ipb_user ($name){
	global $CACHEARRAY, $fprefix;

	if ($CACHEARRAY['use_integration']) {
		forumconn();
		$useridrow = sql_query("SELECT id FROM {$fprefix}members WHERE name=".sqlesc($name));
		$userid = @mysql_result($useridrow,0);
		if (!$userid) { relconn(); return false; }

		sql_query('DELETE FROM '.$fprefix.'contacts WHERE member_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'dnames_change WHERE dname_member_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'members WHERE id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'members_converge WHERE converge_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'member_extra WHERE id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'message_topics WHERE mt_owner_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'pfields_content WHERE member_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'profile_comments WHERE comment_for_member_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'profile_friends WHERE friends_member_id ='.$userid);
		sql_query('DELETE FROM '.$fprefix.'profile_portal WHERE pp_member_id='.$userid);
		sql_query('DELETE FROM '.$fprefix.'warn_logs WHERE wlog_mid='.$userid);
		relconn();
		return true;
	}
}

?>