<?

  require "include/bittorrent.php";
  dbconn(false);

  loggedinorreturn();

// delete items older than a week
  $secs = 7 * 86400;
  stdhead("����");
  $type = htmlspecialchars($_GET["type"]);
   if(!$type || $type == 'simp') $type = "tracker";
 	print("<p align=center>"  .
		($type == tracker || !$type ? "<b>������</b>" : "<a href=log.php?type=tracker>������</a>") . " | " .
 		($type == bans ? "<b>����</b>" : "<a href=log.php?type=bans>����</a>") . " | " .
 		($type == release ? "<b>������</b>" : "<a href=log.php?type=release>������</a>") . " | " .
 		($type == exchange ? "<b>��������</b>" : "<a href=log.php?type=exchange>��������</a>") . " | " .
		($type == torrent ? "<b>��������</b>" : "<a href=log.php?type=torrent>��������</a>") . " | " .
		($type == error ? "<b>������</b>" : "<a href=log.php?type=error>������</a>") . "</p>\n");

   if (($type == 'speed' || $type == 'error') && $CURUSER['class'] < 4) {
	stdmsg("������","������ � ���� ������ ������.");
	stdfoot();
	die();
}

  sql_query("DELETE FROM sitelog WHERE " . gmtime() . " - UNIX_TIMESTAMP(added) > $secs") or sqlerr(__FILE__, __LINE__);
  $limit = ($type == 'announce' ? "LIMIT 1000" : "");
  $res = sql_query("SELECT txt, added, color FROM `sitelog` WHERE type = ".sqlesc($type)." ORDER BY `added` DESC $limit") or sqlerr(__FILE__, __LINE__);
  print("<h1>����</h1>\n");
  if (mysql_num_rows($res) == 0)
    print("<b>��� ���� ������</b>\n");
  else
  {
    print("<table border=1 cellspacing=0 cellpadding=5>\n");
    print("<tr><td class=colhead align=left>����</td><td class=colhead align=left>�����</td><td class=colhead align=left>�������</td></tr>\n");
    while ($arr = mysql_fetch_assoc($res))
    {
      $date = substr($arr['added'], 0, strpos($arr['added'], " "));
      $time = substr($arr['added'], strpos($arr['added'], " ") + 1);
      print("<tr style=\"background-color: $arr[color]\"><td>$date</td><td>$time</td><td align=left>$arr[txt]</td></tr>\n");
    }
    print("</table>");
  }
  stdfoot();
?>