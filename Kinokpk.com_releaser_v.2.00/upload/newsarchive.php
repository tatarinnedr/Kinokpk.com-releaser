<?
require_once("include/bittorrent.php");
dbconn();
//loggedinorreturn();

stdhead("��� ������� ".$SITENAME."");

  $count = get_row_count("news");
  $perpage = 20; //������� �������� �� ��������

  list($pagertop, $pagerbottom, $limit) = pager($perpage, $count, $_SERVER["PHP_SELF"] . "?");
  $resource = sql_query("SELECT news.* , COUNT(newscomments.id) FROM news LEFT JOIN newscomments ON newscomments.news = news.id GROUP BY news.id ORDER BY news.added DESC $limit");

print ("<table border='0' cellspacing='0' width='100%' cellpadding='5'>
        <tr><td class='colhead' align='center'><b>����� �������� &quot;".$SITENAME."&quot;</b></td></tr>
        <tr><td>".$pagertop."</td></tr>");

if ($count)
{

   while(list($id, $userid, $added, $body, $subject,$comments) = mysql_fetch_array($resource))
   {

     $date = date("d.m.Y",strtotime($added));

     print("<tr><td>");
     print("<table border='0' cellspacing='0' width='100%' cellpadding='5'>
            <tr><td class='colhead'>".$subject."");
     print("</td></tr><tr><td>".format_comment($body)."</td></tr>");
     print("</td></tr>");
     print("<tr><td style='background-color: #F9F9F9'>

            <div style='float:left;'><b>���������</b>: ".$added." <b>������������:</b> ".$comments." [<a href=\"newsoverview.php?id=".$id."\">��������������</a>]</div>");

     if (get_user_class() >= UC_ADMINISTRATOR)
     {
     print("<div style='float:right;'>
            <font class=\"small\">
            [<a class='altlink' href=\"news.php?action=edit&newsid=".$id."&returnto=".urlencode($_SERVER['PHP_SELF'])."\">�������������</a>]
            [<a class='altlink' onClick=\"return confirm('������� ��� �������?')\" href=\"news.php?action=delete&newsid=".$id."&returnto=".urlencode($_SERVER['PHP_SELF'])."\">�������</a>]
            </font></div>");
     }
     print("</td></tr></table>");

   }  
}
else
{
print("<tr><td><center><h3>��������, �� �������� ���...</h3></center></td></tr>");
}

print ("<tr><td>".$pagerbottom."</td></tr></table>");

stdfoot();
?>