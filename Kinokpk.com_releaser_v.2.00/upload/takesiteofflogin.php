<?
require_once("include/bittorrent.php");
dbconn();

if (!mkglobal("username:password"))
    die();

function bark($text)
{
  print("<title>������!</title>");
  print("<table width='100%' height='100%' style='border: 8px ridge #FF0000'><tr><td align='center'>");
  print("<center><h1 style='color: #CC3300;'>������:</h1><h2>$text</h2></center>");
  print("<center><INPUT TYPE='button' VALUE='�����' onClick=\"history.go(-1)\"></center>");
  print("</td></tr></table>");
  die;
}

if (!$_POST['username'] or !$_POST['password'])
   bark("�� �� ������� ��� ������������ �(���) ������!");

$res = sql_query("SELECT id, passhash, secret, enabled, status FROM users WHERE username = " . sqlesc($username));
$row = mysql_fetch_array($res);

if (!$row)
    bark("�� �� ���������������� � �������!");

if ($row["status"] == 'pending')
    bark("�� ��� �� ������������ ���� �������! ����������� ��� ������� � ���������� �����.");

if ($row["passhash"] != md5($row["secret"] . $password . $row["secret"]))
    bark("��� ������������ ��� ������ �������!");

if ($row["enabled"] == "no")
    bark("���� ������� ��������.");

$peers = sql_query("SELECT COUNT(id) FROM peers WHERE userid = $row[id]");
$num = mysql_fetch_row($peers);
$ip = getip();
if ($num[0] > 0 && $row[ip] != $ip && $row[ip])
    bark("���� ������������ �� ������ ������ �������. ���� ����������!");

logincookie($row["id"], $row["passhash"]);
header("Refresh: 0; url='$DEFAULTBASEURL'");
?>