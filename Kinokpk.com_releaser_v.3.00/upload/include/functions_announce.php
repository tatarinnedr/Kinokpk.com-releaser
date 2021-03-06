<?php
/**
 * Annouce functions file
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

# IMPORTANT: Do not edit below unless you know what you are doing!
if(!defined('IN_ANNOUNCE'))
die('Hacking attempt!');
require_once(ROOT_PATH . 'include/secrets.php');
require_once(ROOT_PATH . 'classes/cache/cache.class.php');
require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
$CACHE=new Cache();
$CACHE->addDriver('file', new FileCacheDriver());
require_once(ROOT_PATH.'classes/bans/ipcheck.class.php');

/**
 * Bencoded error message with exit
 * @param string $msg Error message
 * @return void
 */

/**
 * Generates SQL error message sending notification to SYSOP
 * @param string $file File where error begins __FILE__
 * @param string $line Line where error begins __LINE__
 * @return void
 */
function sqlerr($file = '', $line = '') {
	$err = mysql_error();
	$text = ("SQL error, mysql server said: " . $err . ($file != '' && $line != '' ? " file: $file, line: $line" : ""));
	write_log("Announce/scrape SQL ERROR: $text",'sql_errors');
	err($text);
	return;
}

/**
 * Writes event to sitelog
 * @param stirng $text Message to be writed to log
 * @param string $type Type of log record, default 'tracker'
 * @return void
 */
function write_log($text, $type = "tracker") {
	$type = sqlesc($type);
	$text = sqlesc($text);
	$added = time();
	mysql_query("INSERT INTO sitelog (added, txt, type) VALUES($added, $text, $type)") or sqlerr(__FILE__,__LINE__);
	return;
}

function err($msg) {
	benc_resp(array("failure reason" => array(type => "string", value => $msg)));
	exit();
	return;
}
/**
 * Sets charset to database connection.
 * @param string $charset Charset to be set
 * @return void
 */
function my_set_charset($charset) {
	if (!function_exists("mysql_set_charset") || !mysql_set_charset($charset)) mysql_query("SET NAMES $charset");
	return;
}

/**
 * Bencoded response (dictionary)
 * @param string $d Response
 * @return void
 */
function benc_resp($d) {
	benc_resp_raw(benc(array(type => "dictionary", value => $d)));
	return;
}

/**
 * Bencoded response
 * @param string $x String to response
 */
function benc_resp_raw($x) {
	header("Content-Type: text/plain");
	header("Pragma: no-cache");
	print($x);
	return;
}

/**
 * Get all headers emulation
 * @return array Emulated headers
 */
function emu_getallheaders() {
	foreach($_SERVER as $name => $value)
	if(substr($name, 0, 5) == 'HTTP_')
	$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
	return $headers;
}

/**
 * Validates client port
 * @param int $port Port to be validated
 * @return boolean
 */
function portblacklisted($port) {
	if ($port >= 411 && $port <= 413)
	return true;
	if ($port >= 6881 && $port <= 6889)
	return true;
	if ($port == 1214)
	return true;
	if ($port >= 6346 && $port <= 6347)
	return true;
	if ($port == 4662)
	return true;
	if ($port == 6699)
	return true;
	return false;
}

/**
 * Validates client ip
 * @param string $ip Ip to be validated
 * @return boolean
 */
function validip($ip) {
	if (!empty($ip) && $ip == long2ip(ip2long($ip)))
	{
		$reserved_ips = array (
		array('0.0.0.0','2.255.255.255'),
		array('10.0.0.0','10.255.255.255'),
		array('127.0.0.0','127.255.255.255'),
		array('169.254.0.0','169.254.255.255'),
		array('172.16.0.0','172.31.255.255'),
		array('192.0.2.0','192.0.2.255'),
		array('192.168.0.0','192.168.255.255'),
		array('255.255.255.0','255.255.255.255')
		);

		foreach ($reserved_ips as $r)
		{
			$min = ip2long($r[0]);
			$max = ip2long($r[1]);
			if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
		}
		return true;
	}
	else return false;
}

/**
 * Gets client ip
 * @return string Ip address
 */
function getip() {
	if (isset($_SERVER)) {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && validip($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (isset($_SERVER['HTTP_CLIENT_IP']) && validip($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	} else {
		if (getenv('HTTP_X_FORWARDED_FOR') && validip(getenv('HTTP_X_FORWARDED_FOR'))) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('HTTP_CLIENT_IP') && validip(getenv('HTTP_CLIENT_IP'))) {
			$ip = getenv('HTTP_CLIENT_IP');
		} else {
			$ip = getenv('REMOTE_ADDR');
		}
	}

	return $ip;
}

/**
 * Sets up a database connection
 * @return void
 */
function dbconn() {
	global $mysql_host, $mysql_charset, $mysql_user, $mysql_pass, $mysql_db, $CACHEARRAY, $CACHE;
	if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
	{
		err('dbconn: mysql_connect: ' . mysql_error());
	}

	mysql_select_db($mysql_db) or err('dbconn: mysql_select_db: ' + mysql_error());
	my_set_charset($mysql_charset);
	// configcache init

	$CACHEARRAY=$CACHE->get('system','config');

	if ($CACHEARRAY===false) {

		err('cache system error, wait some minutes please');
	}
	mysql_query("SET @@collation_connection = @@collation_database");
	//configcache init end
	register_shutdown_function("mysql_close");
	return;
}

/**
 * Escapes value to make safe sql query
 * @param string $value Value to be escaped
 * @return string Escaped value
 */
function sqlesc($value) {
	// Quote if not a number or a numeric string
	if (!is_numeric($value)) {
		$value = "'" . mysql_real_escape_string($value) . "'";
	}
	return $value;
}

/**
 * Unescapes value. With php 5.3 just returns value
 * @param string $x Value to be unescaped
 * @return string Unescaped value
 */
function unesc($x) {
	return $x;
}

/**
 * Starts gzip comperssion
 * @return void
 */
function gzip() {
	if (@extension_loaded('zlib') && @ini_get('zlib.output_compression') != '1' && @ini_get('output_handler') != 'ob_gzhandler') {
		@ob_start('ob_gzhandler');
	}
	return;
}

/**
 * Checks that user client was not banned. Dies on false
 * @param string $peer_id Peer_id of client
 * @return void
 */
function checkclient($peer_id){
	$agent = $_SERVER['HTTP_USER_AGENT'];
	//die($peer_id);
	//return true;
	//check by headers
	if (function_exists('getallheaders')){
		$headers = getallheaders();
	}else{
		$headers = emu_getallheaders();
	}
	if (isset($headers['Cookie']) || isset($headers['Accept-Language']) || isset($headers['Accept-Charset']))err('�� �� ������ ������������ ���� ������. �������� �� �����.');

	//check by agent
	$banned = array();
	$banned[]= "FUTB";
	$banned[]= "ABC";
	$banned[]= "Opera";
	$banned[]= "Mozilla";
	$banned[]= "Rufus";
	// $banned[]= "Deluge";
	$banned[]= "BinTorrent";
	$banned[]= "TorrentStorm";
	$banned[]= "Burst!";
	$banned[]= "BitBuddy";
	$banned[]= "Shareaza";
	$banned[]= "TurboBT";
	$banned[]= "eXeem";
	$banned[]= "RAZA";
	$banned[]= "AG";
	$banned[]= "MLDonkey";
	$banned[]= "Ares";
	$banned[]= "Red Swoosh";
	$banned[]= "FDM";
	$banned[]= "SHAD0W";


	for($i=0;$i<sizeof($banned);$i++){
		if(strpos($agent, $banned[$i]) !== false) err("��������, ������ ".$banned[$i]." �������� �� ����� �������.");
	}


	if(strpos($agent, "uTorrent") !== false && strpos($agent, "B") !== false) err("����-������ uTorrent ��������� �� ����� �������, ����������� ���������� ������.");

	#    //check by peer_id

	if(substr($peer_id, 0, 6) == "exbc\08") err("������ BitComet 0.56 �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 4) == "FUTB") err("������ FUTB �������� �� ����� �������."); //patched version of BitComet 0.57 (FUTB- Fuck U TorrentBits)
	elseif(substr($peer_id, 1, 2) == 'BC' && substr($peer_id, 5, 2) != 70 && substr($peer_id, 5, 2) != 63 && substr($peer_id, 5, 2) != 77 && substr($peer_id, 5, 2) >= 59/* && substr($peer_id, 5, 2) <= 88*/) err("BitComet ".substr($peer_id, 5, 2)." is banned.");
	elseif(preg_match("/^0P3R4H/", $peer_id)) err("������ Opera �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 7) == "exbc\0L") err("������ BitLord 1.0 �������� �� ����� �������..");
	elseif(substr($peer_id, 0, 7) == "exbcL") err("������ BitLord 1.1 �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 3) == "-TS") err("������ TorrentStorm �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 5) == "Mbrst") err("������ Burst! �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 3) == "-BB") err("������ BitBuddy �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 3) == "-SZ") err("������ Shareaza �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 5) == "turbo") err("������ TurboBT �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 4) == "T03A") err("����� BitTornado ������������� � ��� ������ �������, ����������, �������� ������.");
	elseif(substr($peer_id, 0, 4) == "T03B") err("����� BitTornado ������������� � ��� ������ �������, ����������, �������� ������.");
	elseif(substr($peer_id, 0, 3 ) == "FRS") err("������ Rufus �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 2 ) == "eX") err("������ eXeem �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 8 ) == "-TR0005-") err("������ Transmission/0.5 �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 8 ) == "-TR0006-") err("������ Transmission/0.6 �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 8 ) == "-XX0025-") err("������ Transmission/0.6 �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 1 ) == ",") err ("������ RAZA �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 3 ) == "-AG") err("��� ����������-������ �������� �� ����� �������.");
	elseif(substr($peer_id, 0, 3 ) == "R34") err("������ BTuga/Revolution-3.4 �������� �� ����� �������.");
	elseif(preg_match("/MLDonkey\/([0-9]+).([0-9]+).([0-9]+)*/", $peer_id, $matches)) err("MLDonkey �� �������� ����������-��������.");
	elseif(preg_match("/ed2k_plugin v([0-9]+\\.[0-9]+).*/", $peer_id, $matches)) err("eDonkey �� �������� ����������-��������.");
	elseif(substr($peer_id, 0, 4) == "exbc") err("��� ������ BitComet �������������.");
	elseif(substr($peer_id, 0, 3) == '-FG') err("FlashGet �������� �� ����� �������.");

	elseif(substr($peer_id, 1, 2) == 'UT'){
		$UTVersion = (int) substr($peer_id, 3, 4);
		if($UTVersion<1610)err("uTorrent ������ ���� ".$UTVersion." �������� �� ����� �������.");
		//elseif($UTVersion>1610 AND $UTVersion<1810)err("uTorrent ������ ".$UTVersion." �������� �� ����� �������.");
	}



	//exbcLORD         BitLord 1.1
	//-UT1750            uTorrent 1750
	//-UT1610-           uTorrent 1610
	//-BC0070-\tiB       BitTorrent/3.4.2
	//-TR1110-6lzvmrvc7i06 Transmission/1.11 (5504)
	//-lt0C00            rtorrent/0.8.0/0.12.0
	//T03I                BitTornado/T-0.3.18

	//check by agent and version (not all versions are banned)

}

?>