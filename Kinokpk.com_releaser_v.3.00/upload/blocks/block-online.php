<?php
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../index.php");
	exit;
}
global $CURUSER, $CACHEARRAY;
$a = mysql_fetch_array(sql_query("SELECT id, username FROM users WHERE id = (SELECT MAX(id) FROM users WHERE users.confirmed=1)"));
if ($CURUSER)
$latestuser = "<a href='userdetails.php?id=" . $a["id"] . "' class='online'>" . $a["username"] . "</a>";
else
$latestuser = $a['username'];
$title_who = array();
$gues = array();
$dt = sqlesc(time() - 300);
$result = sql_query("SELECT DISTINCT s.uid, s.username, s.class, s.ip FROM sessions AS s WHERE s.time > $dt ORDER BY s.class DESC");
while ($row = mysql_fetch_array($result)) {
	$uid = $row["uid"];
	$uname = $row["username"];
	$class = $row["class"];
	$ip = $row["ip"];
	$uname_new = $uname;
	if (!empty($uname) && ($uname_new != $uname_old)) {
		$title_who[] = "<a href='userdetails.php?id=".$uid."' class='online'>".get_user_class_color($class, $uname)."</a>";
	}
	if (($uname_new != $uname_old) && ($class >= UC_MODERATOR)) {
		$staff++;
	} elseif((!empty($uname) and $uname_new != $uname_old)) {
		$users++;
	}
	if ($uid <= 0 && !in_array("$ip",$gues)) {
		$guests++;
		$gues[] = "$ip";
	}
	if (empty($uname)) {
		continue;
	} else {
		$who_online .= $title_who;
	}
	$uname_old = $uname;
}
$total = $staff + $users + $guests;
if ($staff == "")  $staff = 0;
if ($guests == "") $guests = 0;
if ($users == "")  $users = 0;
if ($total == "")  $total = 0;
$content .= "<table border='0' width='100%'>
             <tr valign='middle'>
             <td align='left' class='embedded' style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'><b>���������: </b> $latestuser</td></tr>";
if (count($title_who)) {
	$content .= "<tr valign='middle'>
                    <td align='left' class='embedded' style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'>
                    <b>��� ������: </b><br />".@implode(", ", $title_who)."</td></tr>";
} else {
	$content .= "<tr valign='middle'>
                    <td align='left' class='embedded' style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'>
                    <b>��� ������: </b><br />��� ������������� �� ��������� 10 �����.</td></tr>";
}
$content .= "<tr valign='middle'>
            <td align='left' class='embedded' style='padding:5px; border: 1px solid #266C8A; background-color: #FFFFFF'>
            <b>� ����: </b><br />";
$content .= "<img src='pic/info/admin.gif' alt='��������������' align='middle' width='16' height='16' />&nbsp;<font color='red'>������: $staff</font>&nbsp;";
$content .= "<img src='pic/info/member.gif' alt='������������' align='middle' width='16' height='16' />&nbsp;������������: $users&nbsp;<br />";
$content .= "<img src='pic/info/guest.gif' alt='�����' align='middle' width='16' height='16' />&nbsp;�����: $guests&nbsp;";
$content .= "<img src='pic/info/group.gif' alt='�����' align='middle' width='16' height='16' />&nbsp;�����: $total</td></tr></table>";
?>