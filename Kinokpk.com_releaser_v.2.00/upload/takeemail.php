<?
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();

if (get_user_class() < UC_SYSOP)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);

$res = sql_query("SELECT email FROM users") or sqlerr(__FILE__,__LINE__);
$counter = mysql_affected_rows();
while ($a = mysql_fetch_assoc($res))
{

$subject = $_POST['subject'];
if (!$subject)
stderr($tracker_lang['error'],"���������, ������� ����!");

$msg = $_POST['msg'];
if (!$msg)
stderr($tracker_lang['error'],"������� ����� ���������!");

$message = <<<EOD

$msg

EOD;
sent_mail($a["email"], $SITENAME, $SITEEMAIL, $subject, $message, false);
}
stdhead("�������� E-mail");
stdmsg("�������..", "�������� ������� ���������. ���������� <b>$counter</b> ���������");
stdfoot();
?>