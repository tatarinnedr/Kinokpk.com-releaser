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

require_once("include/bittorrent.php");

dbconn();

if ($use_captcha){

require_once('include/recaptchalib.php');
$resp = recaptcha_check_answer ($re_privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
	stderr($tracker_lang['error'], "�� �� ������ �������� �� ������������, ���������� ��� ���.");
}

}

if ($deny_signup && !$allow_invite_signup)
	stderr($tracker_lang['error'], "��������, �� ����������� ��������� ��������������.");

if ($CURUSER)
	stderr($tracker_lang['error'], sprintf($tracker_lang['signup_already_registered'], $SITENAME));

$users = get_row_count("users");
if ($users >= $maxusers)
	stderr($tracker_lang['error'], sprintf($tracker_lang['signup_users_limit'], number_format($maxusers)));

if ($deny_signup && $allow_invite_signup) {
	if (empty($_POST["invite"]))
		stderr("������", "��� ����������� ��� ����� ������ ��� �����������!");
	if (strlen($_POST["invite"]) != 32)
		stderr("������", "�� ����� �� ���������� ��� �����������.");
	list($inviter) = mysql_fetch_row(sql_query("SELECT inviter FROM invites WHERE invite = ".sqlesc($_POST["invite"])));
	if (!$inviter)
		stderr("������", "��� ����������� ��������� ���� �� �������.");
	list($invitedroot) = mysql_fetch_row(sql_query("SELECT invitedroot FROM users WHERE id = $inviter"));
}

function bark($msg) {
	global $tracker_lang;
	stdhead();
	stdmsg($tracker_lang['error'], $msg, 'error');
	stdfoot();
	exit;
}

function validusername($username)
{
	if ($username == "")
	  return false;

	// The following characters are allowed in user names
	$allowedchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_ ".
		"���������������������������������Ũ����������������������";

	for ($i = 0; $i < strlen($username); ++$i)
	  if (strpos($allowedchars, $username[$i]) === false)
	    return false;

	return true;
}


if (!is_numeric($_POST["icq"]) && (intval($_POST["icq"]) > 999999999))
    bark("����, ����� icq ������� ������� , ��� ��� �� ����� (���� - 999999999)");
$icq = $_POST["icq"];

$msn = unesc($_POST["msn"]);
if (strlen($msn) > 30)
    bark("����, ��� msn ������� �������  (���� - 30)");

$aim = unesc($_POST["aim"]);
if (strlen($aim) > 30)
    bark("����, ��� aim ������� �������  (���� - 30)");

$yahoo = unesc($_POST["yahoo"]);
if (strlen($yahoo) > 30)
    bark("����, ��� yahoo ������� �������  (���� - 30)");

$mirc = unesc($_POST["mirc"]);
if (strlen($mirc) > 30)
    bark("����, ��� mirc ������� �������  (���� - 30)");

$skype = unesc($_POST["skype"]);
if (strlen($skype) > 20)
    bark("����, ��� skype ������� �������  (���� - 20)");

    $wantusername = unesc($_POST["wantusername"]);
    $email = unesc($_POST["email"]);
    $gender = unesc($_POST["gender"]);
    $country = unesc($_POST["email"]);
if (empty($wantusername) || empty($_POST['wantpassword']) || empty($email) || !is_valid_id($_POST["gender"]) || !is_valid_id($_POST["country"]))
	bark("��� ���� ����������� ��� ����������.");

if (strlen($wantusername) > 12)
	bark("��������, ��� ������������ ������� ������� (�������� 12 ��������)");

if ($_POST['wantpassword'] != $_POST['passagain'])
	bark("������ �� ���������! ������ �� ��������. ���������� ���.");

if (strlen($_POST['wantpassword']) < 6)
	bark("��������, ������ ������� ������� (������� 6 ��������)");

if (strlen($_POST['wantpassword']) > 40)
	bark("��������, ������ ������� ������� (�������� 40 ��������)");

if ($_POST['wantpassword'] == $wantusername)
	bark("��������, ������ �� ����� ���� �����-�� ��� ��� ������������.");

if (!validemail($email))
	bark("��� �� ������ �� �������� email �����.");

if (!validusername($wantusername))
	bark("�������� ��� ������������.");

if (!is_valid_id($_POST['year']) || !is_valid_id($_POST['month']) || !is_valid_id($_POST['day']))
        stderr($tracker_lang['error'],"������ �� ������� �������� ���� ��������");
        
$year = $_POST['year'];
$month = $_POST['month'];
$day = $_POST['day'];
	$birthday = date("$year.$month.$day");

// make sure user agrees to everything...
if ($_POST["rulesverify"] != "yes" || $_POST["faqverify"] != "yes" || $_POST["ageverify"] != "yes")
	stderr($tracker_lang['error'], "��������, �� �� ��������� ��� ���� ���-�� ����� ������ ����� �����.");

// check if email addy is already in use
$a = (@mysql_fetch_row(@sql_query("SELECT COUNT(*) FROM users WHERE email=".sqlesc($email)))) or die(mysql_error());
if ($a[0] != 0)
	bark("E-mail ����� $email ��� ��������������� � �������.");

check_banned_emails($email);

			if ($use_integration) {
// check IPB email and USER ///////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
mysql_close();
// connecting to IPB DB

$fdb = mysql_connect($fmysql_host, $fmysql_user, $fmysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($fmysql_db, $fdb);
	sql_query("SET NAMES $fmysql_charset");
//connection opened

$a = (@mysql_fetch_row(@sql_query("SELECT COUNT(*) FROM ".$fprefix."members_converge WHERE converge_email=".sqlesc($email)))) or die(mysql_error());
if ($a[0] != 0)
	die("E-mail ����� $email ��� ��������������� �� ������ $FORUMNAME.");
	
$a = (@mysql_fetch_row(@sql_query("SELECT COUNT(*) FROM ".$fprefix."members WHERE name=".sqlesc($wantusername)))) or die(mysql_error());
if ($a[0] != 0)
	die("������������ � ����� $wantusername ��� ��������������� �� ������ $FORUMNAME.");
	
	 // closing IPB DB connection
mysql_close();
 // connection closed
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($mysql_db, $db);
	sql_query("SET NAMES $mysql_charset");
/////////////////////////////////////////////////////////////////
}

$ip = getip();

if (isset($_COOKIE["uid"]) && is_numeric($_COOKIE["uid"]) && $users) {
    $cid = intval($_COOKIE["uid"]);
    $c = sql_query("SELECT enabled FROM users WHERE id = $cid ORDER BY id DESC LIMIT 1");
    $co = @mysql_fetch_row($c);
    if ($co[0] == 'no') {
		sql_query("UPDATE users SET ip = '$ip', last_access = NOW() WHERE id = $cid");
		bark("�� ��� ��������������� �� $DEFAULTBASEURL");
    } else
		bark("�� ��� ��������������� �� $DEFAULTBASEURL");
} else {
    $b = (@mysql_fetch_row(@sql_query("SELECT enabled, id FROM users WHERE ip LIKE '$ip' ORDER BY last_access DESC LIMIT 1")));
    if ($b[0] == 'no') {
		$banned_id = $b[1];
        setcookie("uid", $banned_id, "0x7fffffff", "/");
		bark("�� ��� ��������������� �� $DEFAULTBASEURL");
    }
}

$secret = mksecret();
$wantpasshash = md5($secret . $_POST['wantpassword'] . $secret);
$editsecret = (!$users?"":mksecret());

if ((!$users) || (!$use_email_act == true))
	$status = 'confirmed';
else
	$status = 'pending';

$ret = sql_query("INSERT INTO users (username, passhash, secret, editsecret, gender, country, icq, msn, aim, yahoo, skype, mirc, website, email, status, ". (!$users?"class, ":"") ."added, birthday, language, invitedby, invitedroot) VALUES (" .
		implode(",", array_map("sqlesc", array($wantusername, $wantpasshash, $secret, $editsecret, $gender, $country, $icq, $msn, $aim, $yahoo, $skype, $mirc, $website, $email, $status))).
		", ". (!$users?UC_SYSOP.", ":""). "'". get_date_time() ."', '$birthday', '$default_language', '$inviter', '$invitedroot')");// or sqlerr(__FILE__, __LINE__);

if (!$ret) {
	if (mysql_errno() == 1062)
		bark("������������ $wantusername ��� ��������������� �� $SITENAME!");
	bark("����������� ������. ����� �� ������� mySQL: ".htmlspecialchars(mysql_error()));
}

$id = mysql_insert_id();

			if ($use_integration) {
// REGISTERING IPB USER /////////////////////////////////////////////////////////////////////////////////////////////////

mysql_close();
// connecting to IPB DB

$fdb = mysql_connect($fmysql_host, $fmysql_user, $fmysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($fmysql_db, $fdb);
	sql_query("SET NAMES $mysql_charset");
//connection opened

$time = time();
//$ip_adr =

////PASS GENERATOR////


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

//$sol = generate_password_salt(5);

function generate_compiled_passhash($sol, $wantpassword)
    {
        return md5( md5( $sol ) . $wantpassword );
    }

//    $passhash = md5( md5( $salt ) . $wantpassword );

        function generate_auto_log_in_key($len=60)
    {
        $pass = generate_password_salt( 60 );

        return md5($pass);

    }
$passhash  =  generate_compiled_passhash( $salt, md5($_POST['wantpassword']) );
$gs = generate_auto_log_in_key();
/////END OF PASSWORD GENERATOR/////
/*function insert_db($table_name, $arr){
    sql_query("INSERT INTO ".$prefix.$table_name.$arr."");
*/
////register////

$first = sql_query("INSERT INTO ".$fprefix."members_converge (converge_email,converge_joined,converge_pass_hash,converge_pass_salt)
            VALUES ('$email','$time','$passhash','$salt')");

$idf = mysql_insert_id();

$second = sql_query("INSERT INTO ".$fprefix."members (id,name,email,mgroup,joined,ip_address,members_display_name,members_l_display_name,members_l_username,member_login_key,bday_day,bday_month,bday_year)
            VALUES ('$idf','$wantusername','$email','$defuserclass','$time','$ip','$wantusername','$wantusername','$wantusername','$gs','$day','$month','$year')");

$icqint = intval($icq);
$third = sql_query("INSERT INTO ".$fprefix."member_extra (id,notes,links,bio,ta_size,photo_type,photo_location,photo_dimensions,aim_name,icq_number,website,yahoo,interests,msnname,vdirs,location,signature,avatar_location,avatar_size,avatar_type) VALUES (".$idf.", NULL, NULL, NULL, NULL, '', '', '', '".$aim."', ".$icqint.", '".$website."', '".$yahoo."', '', '".$msn."', '', '', '', '', '', 'local')");

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
mysql_close();
 // connection closed
$db = mysql_connect($mysql_host, $mysql_user, $mysql_pass)
       or die ('Not connected : ' . mysql_error());
mysql_select_db ($mysql_db, $db);
	sql_query("SET NAMES $mysql_charset");

 //////////END IPB REGISTRATION! //////////////////////////////////////////////////////////////////////////////////////
}
sql_query("DELETE FROM invites WHERE invite = ".sqlesc($_POST["invite"]));

write_log("��������������� ����� ������������ $wantusername","FFFFFF","tracker");
			if ($use_integration) {
write_log("��������������� ����� ������������ �� ������ $FORUMNAME  $wantusername","9693FF","tracker");
 }


$psecret = md5($editsecret);

$body = <<<EOD
�� ������������������ �� $SITENAME � ������� ���� ����� ��� �������� ($email).

���� ��� ���� �� ��, ��������� �������������� ��� ������. ������� ������� ����� ��� E-Mail ������ ����� IP ����� {$_SERVER["REMOTE_ADDR"]}. ���������, �� ���������.

��� ������������� ����� �����������, ��� ����� ������ �� ��������� ������:

$DEFAULTBASEURL/confirm.php?id=$id&secret=$psecret

����� ���� ��� �� ��� ��������, �� ������� ������������ ��� �������. ���� �� ����� �� ��������,
 ��� ����� ������� ����� ������ ����� ���� ����. �� ����������� ��� ��������� �������
� ���� ������ ��� �� ������� ������������ $SITENAME.
EOD;

if($use_email_act && $users) {
	if (!sent_mail($email,$SITENAME,$SITEEMAIL,"������������� ����������� �� $SITENAME",$body,false)) {
		stderr($tracker_lang['error'], "���������� ��������� E-Mail. ���������� �����");
	}
} else {
	logincookie($id, $wantpasshash, $default_language);
}
header("Refresh: 0; url=ok.php?type=". (!$users?"sysop":("signup&email=" . urlencode($email))));

?>