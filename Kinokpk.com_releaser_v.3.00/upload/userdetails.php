<?php
/**
 * Userdetails
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";

dbconn ();

loggedinorreturn ();

getlang('userdetails');

function bark($msg) {
	global $tracker_lang;
	stdhead ( $tracker_lang ['error'] );
	stdmsg ( $tracker_lang ['error'], $msg );
	stdfoot ();
	exit ();
}

if (! is_valid_id ( $_GET ["id"] ))
bark ( $tracker_lang ['invalid_id'] );

$id = ( int ) $_GET ["id"];
$cats = assoc_cats ();
$r = @sql_query ( "SELECT * FROM users WHERE id=$id" ) or sqlerr ( __FILE__, __LINE__ );
$user = mysql_fetch_array ( $r ) or bark ( "��� ������������ � ����� ID $id." );
if (! $user ["confirmed"])
stderr ( $tracker_lang ['error'], "���� ������������ ��� �� ���������� ���� �� e-mail, ��������, ���� ������� ������ ����� ������" );

$it = sql_query ( "SELECT u.id, u.username, u.class, i.id AS invitedid, i.username AS invitedname, i.class AS invitedclass FROM users AS u LEFT JOIN users AS i ON i.id = u.invitedby WHERE u.invitedroot = $id OR u.invitedby = $id ORDER BY u.invitedby" );
if (mysql_num_rows ( $it ) >= 1) {
	while ( $inviter = mysql_fetch_array ( $it ) )
	$invitetree .= "<a href=\"userdetails.php?id=$inviter[id]\">" . get_user_class_color ( $inviter ["class"], $inviter ["username"] ) . "</a> ��������� <a href=\"userdetails.php?id=$inviter[invitedid]\">" . get_user_class_color ( $inviter ["invitedclass"], $inviter ["invitedname"] ) . "</a><br />";

}

if ($user ["ip"] && (get_user_class () >= UC_MODERATOR || $user ["id"] == $CURUSER ["id"])) {
	$ip = $user ["ip"];
	$dom = @gethostbyaddr ( $user ["ip"] );
	if ($dom == $user ["ip"] || @gethostbyname ( $dom ) != $user ["ip"])
	$addr = $ip;
	else {
		$dom = strtoupper ( $dom );
		$domparts = explode ( ".", $dom );
		$domain = $domparts [count ( $domparts ) - 2];
		if ($domain == "COM" || $domain == "CO" || $domain == "NET" || $domain == "NE" || $domain == "ORG" || $domain == "OR")
		$l = 2;
		else
		$l = 1;
		$addr = "$ip ($dom)";
	}
}

if (! $user [added])
$joindate = 'N/A';
else
$joindate = mkprettytime ( $user ['added'] ) . " (" . get_elapsed_time ( $user ["added"] ) . " " . $tracker_lang ['ago'] . ")";
$lastseen = $user ["last_access"];
if (! $lastseen)
$lastseen = $tracker_lang ['never'];
else {
	$lastseen = mkprettytime ( $lastseen ) . " (" . get_elapsed_time ( $lastseen ) . " " . $tracker_lang ['ago'] . ")";
}
// social activity
$allowed_types = array ('comments', 'pollcomments', 'newscomments', 'usercomments', 'reqcomments', 'rgcomments','pagecomments',  'pages', 'friends','seeding','leeching','downloaded','uploaded');

foreach ($allowed_types as $type) {
	switch ($type) {
		case 'pages' : $addition = " AND pages.class<=".get_user_class(); break;
		case 'friends' : $addition = "(friendid={$id} OR userid={$id}) AND confirmed=1"; break;
		case 'seeding' : $sql_query[] = "(SELECT SUM(1) FROM peers WHERE seeder=1 AND userid=$id) AS seeding"; $noq=true; break;
		case 'leeching' : $sql_query[] = "(SELECT SUM(1) FROM peers WHERE seeder=0 AND userid=$id) AS leeching"; $noq=true; break;
		case 'downloaded' : $sql_query[] = "(SELECT SUM(1) FROM snatched LEFT JOIN torrents ON snatched.torrent=torrents.id WHERE snatched.finished=1 AND userid=$id AND torrents.owner<>$id) AS downloaded"; $noq=true; break;
		case 'uploaded' : $sql_query[] = "(SELECT SUM(1) FROM torrents WHERE owner=$id) AS uploaded"; $noq=true; break;
	}
	if (!$noq) {
		$string = (($type!='friends')?(($type!='pages')?"user":"owner")." = $id":'').$addition;
			
		$sql_query[]="(SELECT SUM(1) FROM $type WHERE $string) AS $type";
	}
	unset($addition);
	unset($string);
}
$sql_query = "SELECT ".implode(', ', $sql_query);

//die($sql_query);
$socialsql = sql_query($sql_query);
$social = mysql_fetch_assoc($socialsql);
foreach ($social as $type => $value) $soctable .= "{$tracker_lang['social_'.$type]}: ".($value?"<a href=\"userhistory.php?id=$id&amp;type=$type\">$value</a>":$tracker_lang['no']).'<br/>';
// social activity end

//if ($user['donated'] > 0)
//  $don = "<img src=pic/starbig.gif>";


$res = sql_query ( "SELECT name, flagpic FROM countries WHERE id = $user[country] LIMIT 1" ) or sqlerr ( __FILE__, __LINE__ );
if (mysql_num_rows ( $res ) == 1) {
	$arr = mysql_fetch_assoc ( $res );
	$country = "<img src=\"pic/flag/$arr[flagpic]\" alt=\"$arr[name]\" style=\"margin-left: 8pt\">";
}

//if ($user["donor"] == "yes") $donor = "<td class=embedded><img src=pic/starbig.gif alt='Donor' style='margin-left: 4pt'></td>";
//if ($user["warned"] == "yes") $warned = "<td class=embedded><img src=pic/warnedbig.gif alt='Warned' style='margin-left: 4pt'></td>";


if ($user ["gender"] == "1")
$gender = "<img src=\"pic/male.gif\" alt=\"������\" title=\"������\">";
elseif ($user ["gender"] == "2")
$gender = "<img src=\"pic/female.gif\" alt=\"�������\" title=\"�������\">";
elseif ($user ["gender"] == "3")
$gender = "N/A";

///////////////// BIRTHDAY MOD /////////////////////
if ($user [birthday] != "0000-00-00") {
	//$current = date("Y-m-d", time());
	$current = date ( "Y-m-d", time () + $CURUSER ['tzoffset'] * 60 );
	list ( $year2, $month2, $day2 ) = explode ( '-', $current );
	$birthday = $user ["birthday"];
	$birthday = date ( "Y-m-d", strtotime ( $birthday ) );
	list ( $year1, $month1, $day1 ) = explode ( '-', $birthday );
	if ($month2 < $month1) {
		$age = $year2 - $year1 - 1;
	}
	if ($month2 == $month1) {
		if ($day2 < $day1) {
			$age = $year2 - $year1 - 1;
		} else {
			$age = $year2 - $year1;
		}
	}
	if ($month2 > $month1) {
		$age = $year2 - $year1;
	}

}
///////////////// BIRTHDAY MOD /////////////////////


stdhead ( "�������� ������� " . $user ["username"] );
$enabled = $user ["enabled"] == 1;

print ( '<table width="100%"><tr><td width="100%" style="vertical-align: top;">' );

begin_main_frame ();

print ( "<tr><td colspan=\"2\" align=\"center\"><p><h1 style=\"margin:0px\">$user[username]" . get_user_icons ( $user, true ) . "</h1>" . (($user ['class'] < UC_ADMINISTRATOR) ? reportarea ( $id, 'users' ) : '') . "</p>\n" );

if (! $enabled)
print ( "<p><b>���� ������� ��������</b> �������: " . $user ['dis_reason'] . "</p>\n" );
elseif ($CURUSER ["id"] != $user ["id"]) {
	$r = sql_query ( "SELECT id FROM friends WHERE (userid=$id AND friendid={$CURUSER['id']}) OR (friendid = $id AND userid={$CURUSER['id']})" ) or sqlerr ( __FILE__, __LINE__ );
	list ( $friend ) = mysql_fetch_array ( $r );
	if ($friend)
	print ( "<p>(<a href=\"friends.php?action=deny&id=$friend\">������ �� ������</a>)<br />(<a href=\"present.php?id=$id\">�������� �������</a>)</p>\n" );
	else {
		print ( "<p>(<a href=\"friends.php?action=add&id=$id\">�������� � ������</a>)</p>\n" );
	}
}
print ( "<p>" . ratearea ( $user ['ratingsum'], $user ['id'], 'users', $CURUSER['id'] ) . "$country</p>" );

print ( '<table width=100% border=1 cellspacing=0 cellpadding=5>
<tr><td class=rowhead width=1%>���������������</td><td align=left width=99%>' . $joindate . '</td></tr>
<tr><td class=rowhead>��������� ��� ��� �� �������</td><td align=left>' . $lastseen . '</td></tr>' );

if (get_user_class () >= UC_MODERATOR)
print ( "<tr><td class=\"rowhead\">Email</td><td align=\"left\"><a href=\"mailto:$user[email]\">$user[email]</a></td></tr>\n" );
if ($addr)
print ( "<tr><td class=\"rowhead\">IP</td><td align=\"left\">$addr</td></tr>\n" );

if (get_user_class () >= UC_MODERATOR)
print ( "<tr><td class=\"rowhead\">�����������</td><td align=left><a href=\"invite.php?id=$id\">" . $user ["invites"] . "</a></td></tr>" );
if ($user ["invitedby"] != 0) {
	$inviter = mysql_fetch_assoc ( sql_query ( "SELECT username FROM users WHERE id = " . sqlesc ( $user ["invitedby"] ) ) );
	print ( "<tr><td class=\"rowhead\">���������</td><td align=\"left\"><a href=\"userdetails.php?id=$user[invitedby]\">$inviter[username]</a></td></tr>" );
}
//}
if ($user ["icq"] || $user ["msn"] || $user ["aim"] || $user ["yahoo"] || $user ["skype"]) {
	?>
<tr>
	<td class=rowhead><b>�����</b></td>
	<td align=left><?
	if ($user ["icq"])
	print ( "<img src=\"http://web.icq.com/whitepages/online?icq=" . ( int ) $user [icq] . "&amp;img=5\" alt=\"icq\" border=\"0\" /> " . ( int ) $user [icq] . " <br />\n" );
	if ($user ["msn"])
	print ( "<img src=\"pic/contact/msn.gif\" alt=\"msn\" border=\"0\" /> " . makesafe ( $user [msn] ) . "<br />\n" );
	if ($user ["aim"])
	print ( "<img src=\"pic/contact/aim.gif\" alt=\"aim\" border=\"0\" /> " . makesafe ( $user [aim] ) . "<br />\n" );
	if ($user ["yahoo"])
	print ( "<img src=\"pic/contact/yahoo.gif\" alt=\"yahoo\" border=\"0\" /> " . makesafe ( $user [yahoo] ) . "<br />\n" );
	if ($user ["skype"])
	print ( "<img src=\"pic/contact/skype.gif\" alt=\"skype\" border=\"0\" /> " . makesafe ( $user [skype] ) . "<br />\n" );
	if ($user ["mirc"])
	print ( "<img src=\"pic/contact/mirc.gif\" alt=\"mirc\" border=\"0\" /> " . makesafe ( $user [mirc] ) . "\n" );
	?></td>
</tr>
	<?
}
if ($user ["website"])
print ( "<tr><td class=\"rowhead\">����</td><td align=\"left\">" . makesafe ( $user [website] ) . "</a></td></tr>\n" );
//if ($user['donated'] > 0 && (get_user_class() >= UC_MODERATOR || $CURUSER["id"] == $user["id"]))
//  print("<tr><td class=rowhead>Donated</td><td align=left>$$user[donated]</td></tr>\n");
if ($user ["avatar"])
print ( "<tr><td class=\"rowhead\">������</td><td align=left><img src=\"" . $user ["avatar"] . "\"></td></tr>\n" );
print ( "<tr><td class=\"rowhead\">�����</td><td align=\"left\"><b>" . get_user_class_color ( $user ["class"], get_user_class_name ( $user ["class"] ) ) . ($user ["title"] != "" ? " / <span style=\"color: purple;\">{$user["title"]}</span>" : "") . "</b></td></tr>\n" );
print ( "<tr><td class=\"rowhead\">���</td><td align=\"left\">$gender</td></tr>\n" );
//��� ��������������
print ( "<tr><td class=\"rowhead\">�������<br />��������������</td><td align=\"left\">" );
for($i = 0; $i < $user ["num_warned"]; $i ++) {
	$img .= "<img src=\"pic/star_warned.gif\" alt=\"������� ��������������\" title=\"������� ��������������\">";
}
if (! $img)
$img = "��� ��������������";
print ( $img . ((($CURUSER ['id'] == $id) && ($CURUSER ['num_warned'] != 0)) ? " <a href=\"mywarned.php\">������ ����������� �� ������</a>" : "") . "</td></tr>\n" );

if ($user ["birthday"] != '0000-00-00') {
	print ( "<tr><td class=\"rowhead\">�������</td><td align=\"left\">" . AgeToStr ( $age ) . "</td></tr>\n" );
	$birthday = date ( "d.m.Y", strtotime ( $birthday ) );
	print ( "<tr><td class=\"rowhead\">���� ��������</td><td align=\"left\">$birthday</td></tr>\n" );

	$month_of_birth = substr ( $user ["birthday"], 5, 2 );
	$day_of_birth = substr ( $user ["birthday"], 8, 2 );
	for($i = 0; $i < count ( $zodiac ); $i ++) {
		if (($month_of_birth == substr ( $zodiac [$i] [2], 3, 2 ))) {
			if ($day_of_birth >= substr ( $zodiac [$i] [2], 0, 2 )) {
				$zodiac_img = $zodiac [$i] [1];
				$zodiac_name = $zodiac [$i] [0];
			} else {
				if ($i == 11) {
					$zodiac_img = $zodiac [0] [1];
					$zodiac_name = $zodiac [0] [0];
				} else {
					$zodiac_img = $zodiac [$i + 1] [1];
					$zodiac_name = $zodiac [$i + 1] [0];
				}
			}
		}

	}

	print ( "<tr><td class=\"rowhead\">���� �������</td><td align=\"left\"><img src=\"pic/zodiac/" . $zodiac_img . "\" alt=\"" . $zodiac_name . "\" title=\"" . $zodiac_name . "\"></td></tr>\n" );

}

print ( "<tr><td class=\"rowhead\">{$tracker_lang['comments_and_social']}</td>" );

print ( "<td align=\"left\">$soctable</td></tr>\n" );


if ($invitetree)
print ( "<tr valign=\"top\"><td colspan=\"2\"><div class=\"sp-wrap\"><div class=\"sp-head folded clickable\">������������</div><div class=\"sp-body\">$invitetree</div></div></td></tr>\n" );

if ($user ["info"])
print ( "<tr valign=\"top\"><td align=\"left\" colspan=\"2\" class=\"text\" bgcolor=\"#F4F4F0\">" . format_comment ( $user ["info"] ) . "</td></tr>\n" );

if ($CURUSER ["id"] != $user ["id"])
$showpmbutton = 1;
elseif ($user ["acceptpms"] == "friends") {
	$r = sql_query ( "SELECT id FROM friends WHERE userid = $user[id] AND friendid = $CURUSER[id]" ) or sqlerr ( __FILE__, __LINE__ );
	$showpmbutton = (mysql_num_rows ( $r ) == 1 ? 1 : 0);
}
if ($showpmbutton)
print ( "<tr><td align=right><b>�����</b></td><td align=center><form method=\"get\" action=\"message.php\">
        <input type=\"hidden\" name=\"receiver\" value=" . $user ["id"] . "> 
        <input type=\"hidden\" name=\"action\" value=\"sendmessage\"> 
        <input type=submit value=\"������� ��\" style=\"height: 23px\"> 
        </form>" . ((get_user_class () >= UC_MODERATOR) ? "<form method=\"get\" action=\"email-gateway.php\">
        <input type=\"hidden\" name=\"id\" value=\"" . $user ["id"] . "\">
        <input type=submit value=\"������� e-mail\" style=\"height: 23px\">
        </form>" : '') . "</td></tr>" );

print ( "</table>\n" );
print ( '</td><td>' );

begin_frame ();
$subres = sql_query ( "SELECT SUM(1) FROM usercomments WHERE userid = $id" );
$subrow = mysql_fetch_array ( $subres );
$count = $subrow [0];

$limited = 10;

if (! $count) {

	print ( "<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">" );
	print ( "<tr><td class=colhead align=\"left\" colspan=\"2\">" );
	print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: ������ ������������</div>" );
	print ( "<div align=\"right\"><a href=userdetails.php?id=$id#comments class=altlink_white>�������� �����������</a></div>" );
	print ( "</td></tr><tr><td align=\"center\">" );
	print ( "������������ ���. <a href=userdetails.php?id=$id#comments>������� ��������?</a>" );
	print ( "</td></tr></table><br />" );

} else {
	list ( $pagertop, $pagerbottom, $limit ) = pager ( $limited, $count, "userdetails.php?id=$id&", array ('lastpagedefault' => 1 ) );

	$subres = sql_query ( "SELECT c.id, c.ip, c.ratingsum, c.text, c.user, c.added, c.editedby, c.editedat, u.avatar, u.warned, " . "u.username, u.title, u.class, u.donor, u.enabled, u.ratingsum AS urating, u.gender, s.time AS last_access, e.username AS editedbyname FROM usercomments AS c LEFT JOIN users AS u ON c.user = u.id LEFT JOIN users AS e ON c.editedby = e.id  LEFT JOIN sessions AS s ON s.uid=u.id WHERE userid = " . "$id GROUP BY (c.id) ORDER BY c.id $limit" ) or sqlerr ( __FILE__, __LINE__ );
	$allrows = array ();
	while ( $subrow = mysql_fetch_array ( $subres ) ) {
		$subrow['subject'] = $user['username'];
		$subrow['link'] = "userdetails.php?id=$id#comm{$subrow['id']}";
		$allrows [] = $subrow;
	}

	print ( "<table id=\"comments-table\" class=main cellspacing=\"0\" cellPadding=\"5\" width=\"100%\" >" );
	print ( "<tr><td class=\"colhead\" align=\"center\">" );
	print ( "<div style=\"float: left; width: auto;\" align=\"left\"> :: ������ ������������</div>" );
	print ( "<div align=\"right\"><a href=\"userdetails.php?id=$id#comments\" class=\"altlink_white\">{$tracker_lang['add_comment']}</a></div>" );
	print ( "</td></tr>" );

	print ( "<tr><td>" );
	print ( $pagertop );
	print ( "</td></tr>" );
	print ( "<tr><td>" );
	commenttable ( $allrows, 'usercomment' );
	print ( "</td></tr>" );
	print ( "<tr><td>" );
	print ( $pagerbottom );
	print ( "</td></tr>" );
	print ( "</table>" );
}


print ( "<table style=\"margin-top: 2px;\" cellpadding=\"5\" width=\"100%\">" );
print ( "<tr><td class=colhead align=\"left\" colspan=\"2\">  <div id=\"comments\"></div><b>:: {$tracker_lang['add_comment']} � ������������ | " . is_i_notified ( $id, 'usercomments' ) . "</b></td></tr>" );
print ( "<tr><td width=\"100%\" align=\"center\" >" );
//print("���� ���: ");
//print("".$CURUSER['username']."<p>");
print ( "<form name=comment method=\"post\" action=\"usercomment.php?action=add\">" );
print ( "<table width=\"100%\"><tr><td align=\"center\">" . textbbcode ( "text") . "</td></tr>" );

print ( "<tr><td  align=\"center\">" );
print ( "<input type=\"hidden\" name=\"uid\" value=\"$id\"/>" );
print ( "<input type=\"submit\" value=\"���������� �����������\" />" );
print ( "</td></tr></table></form>" );
end_frame ();

print ( '</td></tr></table></table>' );
if (get_user_class () >= UC_MODERATOR && $user ["class"] < get_user_class ()) {
	begin_frame ( "�������������� ������������", true );
	print ( "<form method=\"post\" action=\"modtask.php\">\n" );
	print ( "<input type=\"hidden\" name=\"action\" value=\"edituser\">\n" );
	print ( "<input type=\"hidden\" name=\"userid\" value=\"$id\">\n" );
	print ( "<input type=\"hidden\" name=\"returnto\" value=\"userdetails.php?id=$id\">\n" );
	print ( "<table class=\"main\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n" );
	print ( "<tr><td class=\"rowhead\">���������</td><td colspan=\"2\" align=\"left\"><input type=\"text\" size=\"60\" name=\"title\" value=\"" . htmlspecialchars ( $user [title] ) . "\"></td></tr>\n" );
	print ( "<tr><td class=\"rowhead\">������� ������</td><td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"avatar\" value=\"1\"></td></tr>\n" );
	// we do not want mods to be able to change user classes or amount donated...
	if ($CURUSER ["class"] < UC_ADMINISTRATOR)
	print ( "<input type=\"hidden\" name=\"donor\" value=\"$user[donor]\">\n" );
	else {
		print ( "<tr><td class=\"rowhead\">�����</td><td colspan=\"2\" align=\"left\"><input type=\"radio\" name=\"donor\" value=\"1\"" . ($user ["donor"] ? " checked" : "") . ">�� <input type=\"radio\" name=\"donor\" value=\"0\"" . (! $user ["donor"] ? " checked" : "") . ">���</td></tr>\n" );
	}

	if (get_user_class() >= UC_MODERATOR && $user["class"] > get_user_class ())
	print ( "<input type=\"hidden\" name=\"class\" value=\"$user[class]\">\n" );
	else {

		print ( "<tr><td class=\"rowhead\">�����</td><td colspan=\"2\" align=\"left\"><select name=\"class\">\n" );
		if (get_user_class() == UC_SYSOP)
		$maxclass = UC_SYSOP;
		elseif (get_user_class() == UC_MODERATOR)
		$maxclass = UC_POWER_USER;
	 else
	 $maxclass = get_user_class() - 1;
	 for ($i = 0; $i <= $maxclass; ++$i)
	 print ( "<option value=\"$i\"" . ($user ["class"] == $i ? " selected" : "") . ">$prefix" . get_user_class_name ( $i ) . "\n" );
		print ( "</select></td></tr>\n" );
	}
	print ( "<tr><td class=\"rowhead\">�������� ���� ��������</td><td colspan=\"2\" align=\"left\"><input type=\"radio\" name=\"resetb\" value=\"1\">��<input type=\"radio\" name=\"resetb\" value=\"0\" checked>���</td></tr>\n" );
	$modcomment = makesafe ( $user ["modcomment"] );
	$supportfor = makesafe ( $user ["supportfor"] );
	print ( "<tr><td class=rowhead>���������</td><td colspan=2 align=left><input type=radio name=support value=\"1\"" . ($user ["support"] ? " checked" : "") . ">�� <input type=radio name=support value=\"0\"" . (! $user ["support"] ? " checked" : "") . ">���</td></tr>\n" );
	print ( "<tr><td class=rowhead>��������� ���:</td><td colspan=2 align=left><textarea cols=60 rows=6 name=supportfor>$supportfor</textarea></td></tr>\n" );
	print ( "<tr><td class=rowhead>������� ������������</td><td colspan=2 align=left><textarea cols=60 rows=6" . (get_user_class () < UC_SYSOP ? " readonly" : " name=modcomment") . ">$modcomment</textarea></td></tr>\n" );
	$warned = $user ["warned"] == 1;

	print ( "<tr><td class=\"rowhead\"" . (! $warned ? " rowspan=\"2\"" : "") . ">��������������</td>
 	<td align=\"left\" width=\"20%\">" . ($warned ? "<input name=\"warned\" value=\"1\" type=\"radio\" checked>��<input name=\"warned\" value=\"0\" type=\"radio\">���" : "���") . "</td>" );

	if ($warned) {
		$warneduntil = $user ['warneduntil'];
		if (! $warneduntil)
		print ( "<td align=\"center\">�� ������������� ����</td></tr>\n" );
		else {
			print ( "<td align=\"center\">�� " . mkprettytime ( $warneduntil ) );
			print ( " (" . get_elapsed_time ( $warneduntil ) . " ��������)</td></tr>\n" );
		}
	} else {
		print ( "<td>������������ �� <select name=\"warnlength\">\n" );
		print ( "<option value=\"0\">------</option>\n" );
		print ( "<option value=\"1\">1 ������</option>\n" );
		print ( "<option value=\"2\">2 ������</option>\n" );
		print ( "<option value=\"4\">4 ������</option>\n" );
		print ( "<option value=\"8\">8 ������</option>\n" );
		print ( "<option value=\"255\">������������</option>\n" );
		print ( "</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����������� � ��:</td></tr>\n" );
		print ( "<tr><td colspan=\"2\" align=\"left\"><input type=\"text\" size=\"60\" name=\"warnpm\"></td></tr>" );
	}
	if (get_user_class () >= UC_ADMINISTRATOR && $user ["class"] < get_user_class ()) {
		print ( "<tr><td class=\"rowhead\" rowspan=\"2\">�������</td><td colspan=\"2\" align=\"left\"><input name=\"enabled\" value=\"1\" type=\"radio\"" . ($enabled ? " checked" : "") . ">�� <input name=\"enabled\" value=\"0\" type=\"radio\"" . (! $enabled ? " checked" : "") . ">���</td></tr>\n" );
		if ($enabled)
		print ( "<tr><td colspan=\"2\" align=\"left\">������� ����������:&nbsp;<input type=\"text\" name=\"disreason\" size=\"60\" /></td></tr>" );
		else
		print ( "<tr><td colspan=\"2\" align=\"left\">������� ���������:&nbsp;<input type=\"text\" name=\"enareason\" size=\"60\" /></td></tr>" );}
		?>
<script type="text/javascript">

function togglepic(bu, picid, formid)
{
    var pic = document.getElementById(picid);
    var form = document.getElementById(formid);
    
    if(pic.src == bu + "/pic/plus.gif")
    {
        pic.src = bu + "/pic/minus.gif";
        form.value = "minus";
    }else{
        pic.src = bu + "/pic/plus.gif";
        form.value = "plus";
    }
}

</script>
		<?
		print ( "<tr><td class=\"rowhead\">�������� �������</td><td align=\"left\"><img src=\"pic/plus.gif\" id=\"uppic\" onClick=\"togglepic('{$CACHEARRAY['defaultbaseurl']}','uppic','upchange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountup\" size=\"10\" /><td>\n<select name=\"formatup\">\n<option value=\"mb\">MB</option>\n<option value=\"gb\">GB</option></select></td></tr>" );
		print ( "<tr><td class=\"rowhead\">�������� ������</td><td align=\"left\"><img src=\"pic/plus.gif\" id=\"downpic\" onClick=\"togglepic('{$CACHEARRAY['defaultbaseurl']}','downpic','downchange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountdown\" size=\"10\" /><td>\n<select name=\"formatdown\">\n<option value=\"mb\">MB</option>\n<option value=\"gb\">GB</option></select></td></tr>" );
		print ( "<tr><td class=\"rowhead\">�������� �������</td><td align=\"left\"><img src=\"pic/plus.gif\" id=\"ratingpic\" onClick=\"togglepic('{$CACHEARRAY['defaultbaseurl']}','ratingpic','ratingchange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountrating\" size=\"10\" /><td>������ �������� � ������������: {$user['ratingsum']}</td></tr>" );
		print ( "<tr><td class=\"rowhead\">�������� �����</td><td align=\"left\"><img src=\"pic/plus.gif\" id=\"discountpic\" onClick=\"togglepic('{$CACHEARRAY['defaultbaseurl']}','discountpic','discountchange')\" style=\"cursor: pointer;\">&nbsp;<input type=\"text\" name=\"amountdiscount\" size=\"10\" /><td>������ ������ � ������������: {$user['discount']}</td></tr>" );
		print ( "<tr><td class=\"rowhead\">�������� passkey</td><td colspan=\"2\" align=\"left\"><input name=\"resetkey\" value=\"1\" type=\"checkbox\"></td></tr>\n" );
		if ($CURUSER ["class"] < UC_ADMINISTRATOR)
		print ( "<input type=\"hidden\" name=\"deluser\">" );
		else
		print ( "<tr><td class=\"rowhead\">�������</td><td colspan=\"2\" align=\"left\"><input type=\"checkbox\" name=\"deluser\"></td></tr>" );
		print ( "</td></tr>" );
		print ( "<tr><td colspan=\"3\" align=\"center\"><input type=\"submit\" class=\"btn\" value=\"��\"></td></tr>\n" );
		print ( "</table>\n" );
		print ( "<input type=\"hidden\" id=\"ratingchange\" name=\"ratingchange\" value=\"plus\"><input type=\"hidden\" id=\"discountchange\" name=\"discountchange\" value=\"plus\"><input type=\"hidden\" id=\"upchange\" name=\"upchange\" value=\"plus\"><input type=\"hidden\" id=\"downchange\" name=\"downchange\" value=\"plus\">\n" );
		print ( "</form>\n" );
		end_frame ();
}
end_main_frame ();

set_visited('users',$id);
stdfoot ();
?>
