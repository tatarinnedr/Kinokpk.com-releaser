<?

require_once("include/bittorrent.php");

        //////////////////// Array ////////////////////


        $arrSystem['Windows 3.1'] = "Windows 3.1";
        $arrSystem['Win16'] = "Windows 3.1";
        $arrSystem['16bit'] = "Windows 3.1";
        $arrSystem['Win32'] = "Windows 95";
        $arrSystem['32bit'] = "Windows 95";
        $arrSystem['Win 32'] = "Windows 95";
        $arrSystem['Win95'] = "Windows 95";
        $arrSystem['Windows 95/NT'] = "Windows 95";
        $arrSystem['Win98'] = "Windows 98";
        $arrSystem['Windows 95'] = "Windows 95";
        $arrSystem['Windows 98'] = "Windows 98";
        $arrSystem['Windows NT 5.0'] = "Windows 2000";
        $arrSystem['Windows NT 5.1'] = "Windows XP";
        $arrSystem['Windows NT'] = "Windows NT";
        $arrSystem['WinNT'] = "Windows NT";
        $arrSystem['Windows ME'] = "Windows ME";
        $arrSystem['Windows CE'] = "Windows CE";
        $arrSystem['Windows'] = "Windows 95";
        $arrSystem['Mac_68000'] = "Macintosh";
        $arrSystem['Mac_PowerPC'] = "Macintosh";
        $arrSystem['Mac_68K'] = "Macintosh";
        $arrSystem['Mac_PPC'] = "Macintosh";
        $arrSystem['Macintosh'] = "Macintosh";
        $arrSystem['IRIX'] = "Unix";
        $arrSystem['SunOS'] = "Unix";
        $arrSystem['AIX'] = "Unix";
        $arrSystem['Linux'] = "Unix";
        $arrSystem['HP-UX'] = "Unix";
        $arrSystem['SCO_SV'] = "Unix";
        $arrSystem['FreeBSD'] = "Unix";
        $arrSystem['BSD/OS'] = "Unix";
        $arrSystem['OS/2'] = "OS/2";
        $arrSystem['WebTV/1.0'] = "WebTV/1.0";
        $arrSystem['WebTV/1.2'] = "WebTV/1.2";

        $arrBrowser['Lynx'] = "Lynx";
        $arrBrowser['libwww-perl'] = "Lynx";
        $arrBrowser['ia_archiver'] = "Crawler";
        $arrBrowser['ArchitextSpider'] = "Crawler";
        $arrBrowser['Lycos_Spider_(T-Rex)'] = "Crawler";
        $arrBrowser['Scooter'] = "Crawler";
        $arrBrowser['InfoSeek'] = "Crawler";
        $arrBrowser['AltaVista'] = "Crawler";
        $arrBrowser['Eule-Robot'] = "Crawler";
        $arrBrowser['SwissSearch'] = "Crawler";
        $arrBrowser['Checkbot'] = "Crawler";
        $arrBrowser['Crescent Internet ToolPak'] = "Crawler";
        $arrBrowser['Slurp'] = "Crawler";
        $arrBrowser['WiseWire-Widow'] = "Crawler";
        $arrBrowser['NetAttache'] = "Crawler";
        $arrBrowser['Web21 CustomCrawl'] = "Crawler";
        $arrBrowser['CheckUrl'] = "Crawler";
        $arrBrowser['LinkLint-checkonly'] = "Crawler";
        $arrBrowser['Namecrawler'] = "Crawler";
        $arrBrowser['ZyBorg'] = "Crawler";
        $arrBrowser['Googlebot'] = "Crawler";
        $arrBrowser['WebCrawler'] = "Crawler";
        $arrBrowser['WebCopier'] = "Crawler";
        $arrBrowser['JBH Agent 2.0'] = "Crawler";

        ///////////// Array end ///////////////////->


dbconn();
loggedinorreturn();

stdhead("��� ������������");
if (get_user_class() < UC_MODERATOR)
{
stdmsg(������, "������ ��������!", error);
stdfoot();
die();
}


  $search = unesc($_GET["search"]);
  $search = htmlspecialchars($search);

  $search_cat = unesc($_GET["cat"]);
  $search_cat = intval($search_cat);

   switch ($search_cat){

     default:
     $sql_r = "username";
     $check1 = "checked";
     break;

     case 1:
     $sql_r = "username";
     $check1 = "checked";
     break;

     case 2:
     $sql_r = "url";
     $check2 = "checked";
     break;

     case 3:
     $check3 = "checked";
     break;

   }

  $secs = 1 * 300;//����� ������� (5 ��������� �����)
  $dt = time() - $secs;

  if($search_cat != 3){
     if($search)
    $searchs = "  WHERE $sql_r LIKE '%" . sqlwildcardesc($search) . "%' ";
   } else {
    $searchs = "  WHERE uid LIKE '%" . sqlwildcardesc("-1") . "%' ";
   }

  $res = sql_query("SELECT COUNT(*) FROM sessions $searchs WHERE time > $dt");
  $row = mysql_fetch_array($res);
  $count = $row[0];
  $per_list = 50;

  list($pagertop, $pagerbottom, $limit) = pager($per_list, $count, "online.php?");
  $spy_res = sql_query("SELECT url, uid, username, class, ip, useragent FROM sessions $searchs WHERE time > $dt ORDER BY uid ASC $limit");

  echo "<table  class=\"embedded\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\"><tr><td class=\"colhead\" align=\"center\" colspan=\"3\">��� ��������� ������������ (���������� �� ��������� 5 �����)</td></tr>";

  echo "<tr><td width=\"100%\" colspan=\"3\">";

  echo "<table class=\"embedded\" border=\"0\" cellpadding=\"0\" cellspacing=\"10\">"
      ."<form method=\"get\" action=\"online.php\">"
      ."<tr>"
      ."<td class=\"embedded\">"
      ."<input type=\"text\" name=\"search\" size=\"40\"  value=\"".htmlspecialchars($search)."\" />"
      ."&nbsp;&nbsp;&nbsp;�� �����:"
      ."<input name=\"cat\" type=\"radio\" value=\"1\" ".$check1.">"
      ."&nbsp;&nbsp;&nbsp;�� ������:"
      ."<input name=\"cat\" type=\"radio\" value=\"2\" ".$check2.">"
      ."&nbsp;&nbsp;&nbsp;���������:"
      ."<input name=\"cat\" type=\"radio\" value=\"3\" ".$check3.">"
      ."</td></tr>"
      ."<tr>"
      ."<td class=\"embedded\"><p align=\"center\"><input class=\"btn\" type=\"submit\" value=\"����� � �������������\" /><br></p></td>"
      ."</form>"
      ."</tr>"
      ."</table>";

  echo "</td></tr>";

  echo "<script language=\"javascript\" type=\"text/javascript\" src=\"js/show_hide.js\"></script>"
      ."<tr><td  class=\"colhead\" align=\"center\">������������</td>"
      ."<td class=\"colhead\" align=\"center\">������</td>"
      ."<td class=\"colhead\" align=\"center\">�������������</td></tr>";

   if($per_list < $count){
  echo "<tr><td class=\"index\" colspan=\"3\">"
       .$pagertop."</td></tr>";}


   if (isset($searchs) && $count < 1) {
      print("<tr><td class=\"index\" colspan=\"3\">".$tracker_lang['nothing_found']."</td></tr>\n");
      }



$i=20;

while(list($spy_url, $user_id, $user_name, $user_class, $user_ip, $user_agent, $user_time) = mysql_fetch_array($spy_res)){

$i++;
$spy_urlse =  basename($spy_url);
$res_list =  explode(".php", $spy_urlse);


$brawser = getBrowser($arrBrowser,$user_agent);
$read = "";
if($CURUSER['id'] == $user_id)
{
$read = "<font color=\"red\">(�� �����)</font>";
}

$slep = "<span style=\"cursor: pointer;\" onclick=\"javascript: show_hide('s$i')\">"
       ."<img title=\"�ac�����\" tooltip=\"�ac�����\" src=\"pic/plus.gif\" id=\"pics$i\" border=\"0\"></span>"
       ."<span id=\"ss$i\" style=\"display: none;\"><br>"
       ."������� - ".$brawser['browser']." V.".$brawser['version']."<br>"
       ."�� - ".getSystem($arrSystem,$user_agent)."<br>"
       ."IP - <a target='_blank' href=\"http://www.dnsstuff.com/tools/whois.ch?ip=".$user_ip."\">". $user_ip."</a><br>"
       ."</span>";

if($user_class != -1){
        echo "<tr><td><a target='_blank' href=\"userdetails.php?id=".$user_id."\">".get_user_class_color($user_class, $user_name)."</a> $slep</td>";
        echo "<td><b>".get_user_class_name($user_class)."</b></td><td>";
}else{
        echo "<tr><td><a target='_blank' href=\"http://www.dnsstuff.com/tools/whois.ch?ip=".$user_ip."\">�����</a> $slep</td>";
        echo "<td>".$user_ip."</td><td>";
        }
        echo "<a target='_blank' href=\"".$spy_url."\">".Spy_lang($res_list[0].".php")."</a> ".$read;
        echo "</td></tr>";



}
         if($per_list < $count){
        echo "<tr><td class=\"index\" colspan=\"3\">"
             .$pagerbottom."</td></tr>"; }
        echo "</table>";

 stdfoot();





  /////////////////////////////////////////////
  /////////////////////////////////////////////
  /////////// -- Functions -- /////////////////
  /////////////////////////////////////////////
  //\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\//

        function getSystem($arrSystem,$userAgent)
        {
                $system = 'Other';
                foreach($arrSystem as $key => $value)
                {
                        if (strpos($userAgent, $key) !== false)
                        {
                                $system = $value;
                                break;
                        }
                }
                return $system;
        }


        function getBrowser($arrBrowser,$userAgent)
        {
                $version = "";
                $browser = 'Other';
                if (($pos = strpos($userAgent, 'Opera')) !== false)
                {
                        $browser = 'Opera';
                        $pos += 6;
                        if ((($posEnd = strpos($userAgent, ';', $pos)) !== false) || (($posEnd = strpos($userAgent, ' ', $pos)) !== false))
                                $version = trim(substr($userAgent, $pos, $posEnd - $pos));
                }
                elseif (($pos = strpos($userAgent, 'MSIE')) !== false)
                {
                        $browser = 'Internet Explorer';
                        $posEnd = strpos($userAgent, ';', $pos);
                        if ($posEnd !== false)
                        {
                                $pos += 4;
                                $version = trim(substr($userAgent, $pos, $posEnd - $pos));
                        }
                }
                elseif (((strpos($userAgent, 'Gecko')) !== false) && ((strpos($userAgent, 'Netscape')) === false))
                {
                        $browser = 'Mozila';
                        if (($pos = strpos($userAgent, 'rv:')) !== false)
                        {
                                $posEnd = strpos($userAgent, ')', $pos);
                                if ($posEnd !== false)
                                {
                                        $pos += 3;
                                        $version = trim(substr($userAgent, $pos, $posEnd - $pos));
                                }
                        }
                }
                elseif ((strpos($userAgent, ' I;') !== false) || (strpos($userAgent, ' U;') !== false) || (strpos($userAgent, ' U ;') !== false) || (strpos($userAgent, ' I)') !== false) || (strpos($userAgent, ' U)') !== false))
                {
                        $browser = 'Netscape Navigator';
                        if (($pos = strpos($userAgent, 'Netscape6')) !== false)
                        {
                                $pos += 10;
                                $version = trim(substr($userAgent, $pos, strlen($userAgent) - $pos));
                        }
                        else
                        {
                                if (($pos = strpos($userAgent, 'Mozilla/')) !== false)
                                {
                                        if (($posEnd = strpos($userAgent, ' ', $pos)) !== false)
                                        {
                                                $pos += 8;
                                                $version = trim(substr($userAgent, $pos, $posEnd - $pos));
                                        }
                                }
                        }
                }
                else
                {
                        foreach($arrBrowser as $key => $value)
                        {
                                if (strpos($userAgent, $key) !== false)
                                {
                                        $browser = $value;
                                        break;
                                }
                        }
                }
                $userAgentArr['browser'] = $browser;
                $userAgentArr['version'] = $version;
                return $userAgentArr;
        }



function Spy_lang($op){
 switch ($op) {

        default:
        return "�������� �� ��������";
        break;
        case 'adduser.php':
        $sd = "���������� �����";
        break;
         case 'admincp.php':
        $sd = "������ ������";
        break;
         case 'anatomy.php':
        $sd = "�������� ������";
        break;
         case 'bans.php':
        $sd = "����";
        break;
         case 'bookmark.php':
        $sd = "������� � ��������";
        break;
         case 'bookmarks.php':
        $sd = "��������";
        break;
        case 'browse.php':
        $sd = "�������";
        break;
        case 'category.php':
        $sd = "���������";
        break;
        case 'comment.php':
        $sd = "�����������";
        break;
        case 'contact.php':
        $sd = "�����";
        break;
        case 'delacctadmin.php':
        $sd = "�������� �����";
        break;
        case 'delacct.php':
        $sd = "�������� �����";
        break;
        case 'details.php':
        $sd = "������ ��������";
        break;
        case 'docleanup.php':
        $sd = "������� �������";
        break;
        case 'download.php':
        $sd = "��������� �������";
        break;
        case 'edit.php':
        $sd = "�������������� ��������";
        break;
        case 'faq.php':
        $sd = "����";
        break;
        case 'findnotconnectable.php':
        $sd = "����� �� NAT";
        break;
        case 'formats.php':
        $sd = "������� ������";
        break;
        case 'forums.php':
        $sd = "�����";
        break;
        case 'friends.php':
        $sd = "������";
        break;
        case 'getrss.php':
        $sd = "RSS Feed";
        break;
        case 'rss.php':
        $sd = "RSS Feed";
        break;
        case 'index.php':
        $sd = "������� �����";
        break;
        case 'indexadd.php':
        $sd = "����� �����";
        break;
        case 'indexdelete.php':
        $sd = "�������� ������";
        break;
        case 'indexedit.php':
        $sd = "�������������� ������";
        break;
        case 'invite.php':
        $sd = "�����������";
        break;
        case 'inviteadd.php':
        $sd = "�����������";
        break;
        case 'ipcheck.php':
        $sd = "�������� �� IP";
        break;
        case 'links.php':
        $sd = "������";
        break;
        case 'log.php':
        $sd = "���� �����";
        break;
        case 'login.php':
        $sd = "�����������";
        break;
        case 'logout.php':
        $sd = "����� � �����";
        break;
        case 'makepoll.php':
        $sd = "�������� ������";
        break;
        case 'message.php':
        $sd = "������ ����";
        break;
        case 'my.php':
        $sd = "������ ������";
        break;
        case 'mybonus.php':
        $sd = "��� ������";
        break;
        case 'myinvite.php':
        $sd = "��� �������";
        break;
        case 'mysimpaty.php':
        $sd = "��� ��������";
        break;
        case 'mytorrents.php':
        $sd = "��� �������";
        break;
        case 'news.php':
        $sd = "���������� ��������";
        break;
        case 'nowarn.php':
        $sd = "������ ��������������";
        break;
        case 'offers.php':
        $sd = "�����������";
        break;
        case 'online.php':
        $sd = "��� ������";
        break;
        case 'polloverview.php':
        $sd = "����� �������";
        break;
        case 'polls.php':
        $sd = "������";
        break;
        case 'recover.php':
        $sd = "�������������� ������";
        break;
        case 'requests.php':
        $sd = "�������";
        break;
        case 'restoreclass.php':
        $sd = "�������������� ������";
        break;
        case 'rules.php':
        $sd = "������� �����";
        break;
        case 'setclass.php':
        $sd = "����� ������";
        break;
        case 'simpaty.php':
        $sd = "������� �������";
        break;
        case 'sitestat.php':
        $sd = "���������� �����";
        break;
        case 'stats.php':
        $sd = "���������� �����";
        break;
        case 'smilies.php':
        $sd = "������";
        break;
        case 'staff.php':
        $sd = "�������������";
        break;
        case 'staffmess.php':
        $sd = "�������� ��";
        break;
        case 'subnet.php':
        $sd = "������";
        break;
        case 'tags.php':
        $sd = "bb-����";
        break;
        case 'testip.php':
        $sd = "�������� IP";
        break;
        case 'testport.php':
        $sd = "���� ������";
        break;
        case 'thanks.php':
        $sd = "����������";
        break;
        case 'topten.php':
        $sd = "��� 10";
        break;
        case 'unco.php':
        $sd = "�������. �����";
        break;
        case 'upload.php':
        $sd = "�������� ��������";
        break;
        case 'uploaders.php':
        $sd = "������ ����������";
        break;
        case 'userdetails.php':
        $sd = "������� �����";
        break;
        case 'userhistory.php':
        $sd = "������� ������";
        break;
        case 'users.php':
        $sd = "������ �������������";
        break;
        case 'usersearch.php':
        $sd = "����� �������������";
        break;
        case 'videoformats.php':
        $sd = "������� �����";
        break;
        case 'viewoffers.php':
        $sd = "�����������";
        break;
        case 'viewrequests.php':
        $sd = "�������";
        break;
        case 'votesview.php':
        $sd = "�����������";
        break;
        case 'warned.php':
        $sd = "��������������� �����";
        break;
        case "spyline.php":
        $sd = "�����";
        break;
        break;
        case "signup.php":
        $sd = "�������� �����������";
        break;
        case "allnews.php":
        $sd = "��� ������� �����";
        break;
        //��� �� �������?
        default:
           $sd = "������ ����� ��� n/a";
 }

  return $sd;
}

?>