<?php
if (!defined('BLOCK_FILE')) {
 Header("Location: ../index.php");
 exit;
}

global $CURUSER, $tracker_lang, $FORUMURL, $FORUMNAME;

if ($CURUSER)  $content = "<a class=\"menu\" href=\"my.php\">&nbsp;��������� ��������</a>"
           ."<a class=\"menu\" href=\"userdetails.php?id=".$CURUSER["id"]."\">&nbsp;".$tracker_lang['profile']."</a>"
           ."<a class=\"menu\" href=\"mybonus.php\">&nbsp;".$tracker_lang['my_bonus']."</a>"
           ."<a class=\"menu\" href=\"mywarned.php\">&nbsp;��� ��������������</a>"
           ."<a class=\"menu\" href=\"invite.php\">&nbsp;".$tracker_lang['invite']."</a>"
           ."<a class=\"menu\" href=\"users.php\">&nbsp;".$tracker_lang['users']."</a>"
           ."<a class=\"menu\" href=\"friends.php\">&nbsp;".$tracker_lang['personal_lists']."</a>"
           ."<a class=\"menu\" href=\"subnet.php\">&nbsp;".$tracker_lang['neighbours']."</a>"
           ."<a class=\"menu\" href=\"mytorrents.php\">&nbsp;".$tracker_lang['my_torrents']."</a>"
           ."<a class=\"menu\" href=\"message.php\">&nbsp;".$tracker_lang['inbox']." ��</a>"
           ."<a class=\"menu\" href=\"message.php?action=viewmailbox&box=-1\">&nbsp;".$tracker_lang['outbox']." ��</a>"
           ."<a class=\"menu\" href=\"logout.php\">&nbsp;".$tracker_lang['logout']."!</a>"
           ."<a class=\"menu\" href=\"index.php\">&nbsp;".$tracker_lang['homepage']."</a>"
           ."<a class=\"menu\" href=\"viewrequests.php\">&nbsp;".$tracker_lang['requests']."</a>"
           ."<a class=\"menu\" href=\"viewoffers.php\">&nbsp;".$tracker_lang['offers']."</a>"
           ."<a class=\"menu\" href=\"$FORUMURL/index.php\">&nbsp;".$tracker_lang['forum']." $FORUMNAME</a>"
		       ."<a class=\"menu\" href=\"testport.php\">&nbsp;��������� NAT</a>"
           ."<a class=\"menu\" href=\"log.php\">&nbsp;".$tracker_lang['log']."</a>"
           ."<a class=\"menu\" href=\"topten.php\">&nbsp;".$tracker_lang['topten']."</a>"
           ."<a class=\"menu\" href=\"bookmarks.php\">&nbsp;".$tracker_lang['bookmarks']."</a>"
           ."<a class=\"menu\" href=\"rules.php\">&nbsp;".$tracker_lang['rules']."</a>"
           ."<a class=\"menu\" href=\"faq.php\">&nbsp;".$tracker_lang['faq']."</a>"
           ."<a class=\"menu\" href=\"formats.php\">&nbsp;".$tracker_lang['formats']."</a>";
           else
           $content = "<center>
<a href=\"login.php\"><font size=\"3\"><b><u>�����</u></b></font></a><br /><br />
�� ������ ������������ ����� � ������ ������ $FORUMNAME ��� �����������.<br /><hr><br />
� ��� ��� ��������?<br />
<a href=\"signup.php\"><u>�����������������</u></a> ����� ������!</center><br /><br />"

?>