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

require_once ("include/bittorrent.php");

gzip();

// Connect to DB & check login
dbconn();
loggedinorreturn();
parked();

// Define constants
define('PM_DELETED',0); // Message was deleted
define('PM_INBOX',1); // Message located in Inbox for reciever
define('PM_SENTBOX',-1); // GET value for sent box

// Determine action
$action = (string) $_GET['action'];
if (!$action)
{
        $action = (string) $_POST['action'];
        if (!$action)
        {
                $action = 'viewmailbox';
        }
}

// ������ �������� ��������� �����
if ($action == "viewmailbox") {
        // Get Mailbox Number
        $mailbox = (int) $_GET['box'];
        if (!$mailbox)
        {
                $mailbox = PM_INBOX;
        }
                if ($mailbox == PM_INBOX)
                {
                        $mailbox_name = $tracker_lang['inbox'];
                }
                else
                {
                        $mailbox_name = $tracker_lang['outbox'];
                }

        // Start Page

        stdhead($mailbox_name); ?>
        <script type="text/javascript">
function checkAll(oForm, cbName, checked)
{
for (var i=0; i < oForm[cbName].length; i++) oForm[cbName][i].checked = checked;
}
</script>
        <script language="javascript" type="text/javascript" src="js/functions.js"></script>
        <H1><?=$mailbox_name?></H1>
        <DIV align="right"><FORM action="message.php" method="get">
        <INPUT type="hidden" name="action" value="viewmailbox"><?=$tracker_lang['go_to'];?>: <SELECT name="box">
        <OPTION value="1"<?=($mailbox == PM_INBOX ? " selected" : "")?>><?=$tracker_lang['inbox'];?></OPTION>
        <OPTION value="-1"<?=($mailbox == PM_SENTBOX ? " selected" : "")?>><?=$tracker_lang['outbox'];?></OPTION>
        </SELECT> <INPUT type="submit" value="<?=$tracker_lang['go_go_go'];?>"></FORM>
        </DIV>
        <TABLE border="0" cellpadding="4" cellspacing="0" width="100%">
        <FORM action="message.php" method="post" name="form1">
        <INPUT type="hidden" name="action" value="moveordel">
        <TR>
        <TD width="2%" class="colhead">&nbsp;&nbsp;</TD>
        <TD width="41%" class="colhead"><?=$tracker_lang['subject'];?></TD>
        <?
        if ($mailbox == PM_INBOX )
                print ("<TD width=\"35%\" class=\"colhead\">".$tracker_lang['sender']."</TD>");
        else
                print ("<TD width=\"35%\" class=\"colhead\">".$tracker_lang['receiver']."</TD>");
        ?>
        <TD width="10%" class="colhead"><?=$tracker_lang['date'];?></TD>
        <TD width="10%" class="colhead">� ������</TD>
        <TD width="2%" class="colhead"><INPUT type="checkbox" title="<?=$tracker_lang['mark_all'];?>" value="<?=$tracker_lang['mark_all'];?>" onClick="checkAll(this.form,'messages[]',this.checked)"></TD>
        </TR>
        <? if ($mailbox != PM_SENTBOX) {
                $res = sql_query("SELECT m.*, u.username AS sender_username, s.id AS sfid, r.id AS rfid FROM messages m LEFT JOIN users u ON m.sender = u.id LEFT JOIN friends r ON r.userid = {$CURUSER["id"]} AND r.friendid = m.receiver LEFT JOIN friends s ON s.userid = {$CURUSER["id"]} AND s.friendid = m.sender WHERE receiver=" . sqlesc($CURUSER['id']) . " AND location=" . sqlesc($mailbox) . " ORDER BY id DESC") or sqlerr(__FILE__,__LINE__);
        } else {
                $res = sql_query("SELECT m.*, u.username AS receiver_username, s.id AS sfid, r.id AS rfid FROM messages m LEFT JOIN users u ON m.receiver = u.id LEFT JOIN friends r ON r.userid = {$CURUSER["id"]} AND r.friendid = m.receiver LEFT JOIN friends s ON s.userid = {$CURUSER["id"]} AND s.friendid = m.sender WHERE sender=" . sqlesc($CURUSER['id']) . " AND saved='yes' ORDER BY id DESC") or sqlerr(__FILE__,__LINE__);
        }
        if (mysql_num_rows($res) == 0) {
                echo("<TD colspan=\"6\" align=\"center\">".$tracker_lang['no_messages'].".</TD>\n");
        }
        else
        {
                while ($row = mysql_fetch_assoc($res))
                {
                        // Get Sender Username
                        if ($row['sender'] != 0) {
                                $username = "<A href=\"userdetails.php?id=" . $row['sender'] . "\">" . $row["sender_username"] . "</A>";
                                $id = $row['sender'];
                                $friend = $row['sfid'];
                                if ($friend && $CURUSER['id'] != $row['sender']) {
                                        $username .= "&nbsp;<a href=friends.php?action=delete&type=friend&targetid=$id>[������� �� ������]</a>";
                                }
                                elseif ($CURUSER['id'] != $row['sender']) {
                                        $username .= "&nbsp;<a href=friends.php?action=add&type=friend&targetid=$id>[�������� � ������]</a>";
                                }
                        }
                        else {
                                $username = $tracker_lang['from_system'];
                        }
                        // Get Receiver Username
                        if ($row['receiver'] != 0) {
                                $receiver = "<A href=\"userdetails.php?id=" . $row['receiver'] . "\">" . $row["receiver_username"] . "</A>";
                                $id_r = $row['receiver'];
                                $friend = $row['rfid'];
                                if ($friend && $CURUSER['id'] != $row['receiver']) {
                                        $receiver .= "&nbsp;<a href=friends.php?action=delete&type=friend&targetid=$id_r>[������� �� ������]</a>";
                                }
                                elseif ($CURUSER['id'] != $row['receiver']) {
                                        $receiver .= "&nbsp;<a href=friends.php?action=add&type=friend&targetid=$id_r>[�������� � ������]</a>";
                                }
                        }
                        else {
                                $receiver = $tracker_lang['from_system'];
                        }
                        $subject = htmlspecialchars($row['subject']);
                        if (strlen($subject) <= 0) {
                                $subject = $tracker_lang['no_subject'];
                        }
                        if ($row['unread'] == 'yes' && $mailbox != PM_SENTBOX) {
                                echo("<TR>\n<TD ><IMG src=\"pic/pn_inboxnew.gif\" alt=\"".$tracker_lang['mail_unread']."\"></TD>\n");
                        }
                        else {
                                echo("<TR>\n<TD><IMG src=\"pic/pn_inbox.gif\" alt=\"".$tracker_lang['mail_read']."\"></TD>\n");
                        }
                        echo("<TD><A href=\"message.php?action=viewmessage&amp;id=" . $row['id'] . "\">" . $subject . "</A></TD>\n");
                        if ($mailbox != PM_SENTBOX) {
                            echo("<TD>$username</TD>\n");
                        }
                        else {
                            echo("<TD>$receiver</TD>\n");
                        }
                        echo("<TD>" . display_date_time(strtotime($row['added']), $CURUSER["tzoffset"]) . "</TD>\n");
                        if ($row['archived'] == 'yes') $archived = "<font color=\"red\">��</font>"; else $archived = "���";
                        echo("<TD>" . $archived . "</TD>\n");
                        echo("<TD><INPUT type=\"checkbox\" name=\"messages[]\" title=\"".$tracker_lang['mark']."\" value=\"" . $row['id'] . "\" id=\"checkbox_tbl_" . $row['id'] . "\"></TD>\n</TR>\n");
                }
        }
        ?>
        <tr class="colhead">
        <td colspan="6" align="right" class="colhead">
        <input type="hidden" name="box" value="<?=$mailbox?>">
        <input type="submit" name="delete" title="<?=$tracker_lang['delete_marked_messages'];?>" value="<?=$tracker_lang['delete'];?>" onClick="return confirm('<?=$tracker_lang['sure_mark_delete'];?>')">
        <input type="submit" name="markread" title="<?=$tracker_lang['mark_as_read'];?>" value="<?=$tracker_lang['mark_read'];?>" onClick="return confirm('<?=$tracker_lang['sure_mark_read'];?>')">
        <input type="submit" name="archive" title="������������" value="������������" onClick="return confirm('������������ ��������� ���������? (��� �� ����� ������� �������� �������������)')">
        <input type="submit" name="unarchive" title="���������������" value="���������������" onClick="return confirm('��������������� ��������� ���������? (��� ����� ������� �������� �������������)')"></form>

        </td>
        </tr>
        </form>
        </table>
        <div align="left"><img src="pic/pn_inboxnew.gif" alt="�������������" /> <?=$tracker_lang['mail_unread_desc'];?><br />
        <img src="pic/pn_inbox.gif" alt="�����������" /> <?=$tracker_lang['mail_read_desc'];?></div>
        <?
        stdfoot();
}
// ����� �������� ��������� �����


// ������ �������� ���� ���������
elseif ($action == "viewmessage") {
  if (!is_valid_id($_GET["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
        $pm_id = $_GET['id'];

        // Get the message
        if (get_user_class() != UC_SYSOP) {
        $res = sql_query('SELECT messages.*,users.username FROM messages LEFT JOIN users ON messages.spamid = users.id WHERE messages.id=' . sqlesc($pm_id) . ' AND (messages.receiver=' . sqlesc($CURUSER['id']) . ' OR (messages.sender=' . sqlesc($CURUSER['id']). ' AND messages.saved=\'yes\')) LIMIT 1') or sqlerr(__FILE__,__LINE__);
        if (mysql_num_rows($res) == 0)
        {
                stderr($tracker_lang['error'],"������ ��������� �� ����������.");
        }
        
        } else {
        $res = sql_query('SELECT messages.*, users.username FROM messages LEFT JOIN users ON messages.spamid = users.id WHERE messages.id=' . sqlesc($pm_id));
        if (mysql_num_rows($res) == 0)
        {
                stderr($tracker_lang['error'],"������ ��������� �� ����������.");
        }
        $adminview = 1;
        }
        
        // Prepare for displaying message
        $message = mysql_fetch_assoc($res);
        if ($message['sender'] == $CURUSER['id'])
        {
                // Display to
                $res2 = sql_query("SELECT username FROM users WHERE id=" . sqlesc($message['receiver'])) or sqlerr(__FILE__,__LINE__);
                $sender = mysql_fetch_array($res2);
                $sender = "<A href=\"userdetails.php?id=" . $message['receiver'] . "\">" . $sender[0] . "</A>";
                $reply = "";
                $from = "����";
        }
        else
        {
                $from = "�� ����";
                if ($message['sender'] == 0)
                {
                        $sender = "���������";
                        $reply = "";
                }
                else
                {
                        $res2 = sql_query("SELECT username FROM users WHERE id=" . sqlesc($message['sender'])) or sqlerr(__FILE__,__LINE__);
                        $sender = mysql_fetch_array($res2);
                        $sender = "<A href=\"userdetails.php?id=" . $message['sender'] . "\">" . $sender[0] . "</A>";
                        $reply = " [ <A href=\"message.php?action=sendmessage&amp;receiver=" . $message['sender'] . "&amp;replyto=" . $pm_id . "\">��������</A> ]";
                }
        }
        $body = format_comment($message['msg']);
        $added = display_date_time(strtotime($message['added']), $CURUSER['tzoffset']);
        if (get_user_class() >= UC_MODERATOR && $message['sender'] == $CURUSER['id'])
        {
                $unread = ($message['unread'] == 'yes' ? "<SPAN style=\"color: #FF0000;\"><b>(�����)</b></A>" : "");
        }
        else
        {
                $unread = "";
        }
        $subject = htmlspecialchars($message['subject']).(($message['spamid'] != 0)?" [<font color=\"red\">�������� ��� ����</font>]":"");
        if (strlen($subject) <= 0)
        {
                $subject = "��� ����";
        }
        // Mark message unread
        sql_query("UPDATE messages SET unread='no' WHERE id=" . sqlesc($pm_id) . " AND receiver=" . sqlesc($CURUSER['id']) . " LIMIT 1");
        // Display message
        stdhead("������ ��������� (����: $subject)"); ?>
        <TABLE width="660" border="0" cellpadding="4" cellspacing="0">
        <?php
        if (($message['spamid'] !=0) && $adminview) print("<tr><td><h2>��� ��������� ���� �������� ��� ���� ������������� <a href=\"userdetails.php?id=".$message['spamid']."\">".$message['username']."</a></h2></td><td><a onClick=\"return confirm('�� �������?')\" href=\"message.php?action=delspam&id=".sqlesc($pm_id)."\">����� �������</a></td></tr>");
        ?>
        <TR><TD class="colhead" colspan="2">����: <?=$subject?></TD></TR>
        <TR>
        <TD width="50%" class="colhead"><?=$from?></TD>
        <TD width="50%" class="colhead">���� ��������</TD>
        </TR>
        <TR>
        <TD><?=$sender?></TD>
        <TD><?=$added?>&nbsp;&nbsp;<?=$unread?></TD>
        </TR>
        <TR>
        <TD colspan="2"><?=$body?></TD>
        </TR>
        <TR>
        <TD align="right" colspan=2>
        <?php
        if ($adminview && ($CURUSER['id'] != $message['receiver']) && ($CURUSER['id']  != $message['sender'])) {
          $a_receiver = mysql_query("SELECT username FROM users WHERE id = ".$message['receiver']);
          $a_receiver = mysql_result($a_receiver,0);
          
        print('<font color="red">�� �������������� ��� ��������� �� ���� ��������������!</font> ����������: <a href="userdetails.php?id='.$message['receiver'].'">'.$a_receiver.'</a><br/>');
         }
        print("[ <A onClick=\"return confirm('�� �������?')\" href=\"message.php?action=deletemessage&id=$pm_id\">�������</A> ]$reply [ <A href=\"message.php?action=forward&id=$pm_id\">���������</A> ]".(($message['spamid'] == 0)?"[ <a onClick=\"return confirm('�� �������, ��� ��� ������������� ����?')\" href=\"message.php?action=setspam&id=$pm_id\"><font color=\"red\">��� ����!</font></a> ]":"[�������� ��� ����]"));
 ?>
        </TD></TR>
        </TABLE><?
        stdfoot();
}
// ����� �������� ���� ���������


// ������ �������� ������� ���������
elseif ($action == "sendmessage") {

        $receiver = $_GET["receiver"];
        if (!is_valid_id($receiver))
                stderr($tracker_lang['error'], "�������� ID ����������");
                
        $replyto = $_GET["replyto"];
        if ($replyto && !is_valid_id($replyto))
                stderr($tracker_lang['error'], "�������� ID ���������");

        $auto = $_GET["auto"];
        $std = $_GET["std"];

        if (($auto || $std ) && get_user_class() < UC_MODERATOR)
                stderr($tracker_lang['error'], "������ ��������.");

        $res = sql_query("SELECT * FROM users WHERE id=$receiver") or die(mysql_error());
        $user = mysql_fetch_assoc($res);
        if (!$user)
                stderr($tracker_lang['error'], "������������ � ����� ID �� ����������.");
        if ($auto)
                $body = $pm_std_reply[$auto];
        if ($std)
                $body = $pm_template[$std][1];

        if ($replyto) {
                $res = sql_query("SELECT * FROM messages WHERE id=$replyto") or sqlerr(__FILE__, __LINE__);
                $msga = mysql_fetch_assoc($res);
                if ($msga["receiver"] != $CURUSER["id"])
                        stderr($tracker_lang['error'], "�� ��������� �������� �� �� ���� ���������!");

                $res = sql_query("SELECT username FROM users WHERE id=" . $msga["sender"]) or sqlerr(__FILE__, __LINE__);
                $usra = mysql_fetch_assoc($res);
                $body .= "[quote=$usra[username]]".htmlspecialchars($msga['msg'])."[/quote]";
                // Change
                $subject = "Re: " . htmlspecialchars($msga['subject']);
                // End of Change
        }

        stdhead("������� ���������", false);
        ?>
          <script language="JavaScript">
<!--

required = new Array("subject", "msg");
required_show = new Array("���� ���������", "���������");


function SendForm () {
  var i, j;

for(j=0; j<required.length; j++) {
    for (i=0; i<document.message.length; i++) {
        if (document.message.elements[i].name == required[j] &&
  document.forms[0].elements[i].value == "" ) {
            alert('����������, ������� ' + required_show[j]);
            document.message.elements[i].focus();
            return false;
        }
    }
}

  return true;
}
//-->

</script>
        <table class=main border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
        <form name="message" method="post" action="message.php" onsubmit="return SendForm();">
        <input type=hidden name=action value=takemessage>
        <table class=message cellspacing=0 cellpadding=5>
        <tr><td colspan=2 class=colhead>��������� ��� <a class=altlink_white href=userdetails.php?id=<?=$receiver?>><?=$user["username"]?></a></td></tr>
        <TR>
        <TD colspan="2"><B>����:&nbsp;&nbsp;</B>
        <INPUT name="subject" type="text" size="60" value="<?=$subject?>" maxlength="255"></TD>
        </TR>
        <tr><td<?=$replyto?" colspan=2":""?>>
        <?
        textbbcode("message","msg","$body");
        ?>
        </td></tr>
        <tr>
        <? if ($replyto) { ?>
        <td align=center><input type=checkbox name='delete' value='yes' <?=$CURUSER['deletepms'] == 'yes'?"checked":""?>/>������� ��������� ����� ������
        <input type=hidden name=origmsg value=<?=$replyto?>></td>
        <? } ?>
        <td align=center><input type=checkbox name='save' value='yes' <?=$CURUSER['savepms'] == 'yes'?"checked":""?>/>��������� ��������� � ������������</td></tr><tr>
        <td align="center"><input type="checkbox" name='archive' value='yes'/>������������ ����� ��������</td></tr>
        <tr><td<?=$replyto?" colspan=2":""?> align=center><input type=submit value="�������!" class=btn/></td></tr>
        </table>
        <input type=hidden name=receiver value=<?=$receiver?>>
        </form>
        </div></td></tr></table>
        <?
        stdfoot();
}
// ����� ������� ���������


// ������ ����� ���������� ���������
elseif ($action == 'takemessage') {

        $receiver = $_POST["receiver"];
        $origmsg = $_POST["origmsg"];
        $save = $_POST["save"];
        $archive = $_POST["archive"];
        $returnto = $_POST["returnto"];
        if (!is_valid_id($receiver) || ($origmsg && !is_valid_id($origmsg)))
                stderr($tracker_lang['error'],"�������� ID");
        $msg = trim($_POST["msg"]);
        if (!$msg)
                stderr($tracker_lang['error'],"���������� ������� ���������!");
        $subject = trim($_POST['subject']);
        if (!$subject)
                stderr($tracker_lang['error'],"���������� ������� ���� ���������!");


        $pms = mysql_query("SELECT COUNT(*) FROM messages WHERE (receiver = $receiver AND location=1) OR (sender = $receiver AND saved = 'yes')");
        $pms = mysql_result($pms,0);
        if ($pms>=$pm_max) stderr($tracker_lang['error'], "���� ������ ��������� ���������� ��������, �� �� ������ ��������� ��� ���������.");

        if ($save == 'yes') {
        $pms = mysql_query("SELECT COUNT(*) FROM messages WHERE (receiver = ".$CURUSER['id']." AND location=1) OR (sender = ".$CURUSER['id']." AND saved = 'yes')");
        $pms = mysql_result($pms,0);
        if ($pms>=$pm_max) stderr("���������� ��������� ���������", "��� ���� ������ ��������� ��������, ������������ ���-�� $pm_max. �� �� ������ ��������� ���������, ��� ���������� �������� ���� ������ ���������");
        }

        // Change
        $save = ($save == 'yes') ? "yes" : "no";
        $archive = ($archive == 'yes') ? "yes" : "no";
        // End of Change
        $res = sql_query("SELECT email, acceptpms, notifs, parked, UNIX_TIMESTAMP(last_access) as la FROM users WHERE id=$receiver") or sqlerr(__FILE__, __LINE__);
        $user = mysql_fetch_assoc($res);
        if (!$user)
                stderr($tracker_lang['error'], "��� ������������ � ����� ID $receiver.");
        //Make sure recipient wants this message
        if ($user["parked"] == "yes")
                stderr($tracker_lang['error'], "���� ������� �����������.");
        if (get_user_class() < UC_MODERATOR)
        {
                if ($user["acceptpms"] == "yes")
                {
                        $res2 = sql_query("SELECT * FROM blocks WHERE userid=$receiver AND blockid=" . $CURUSER["id"]) or sqlerr(__FILE__, __LINE__);
                        if (mysql_num_rows($res2) == 1)
                                sttderr("���������", "���� ������������ ������� ��� � ������ ������.");
                }
                elseif ($user["acceptpms"] == "friends")
                {
                        $res2 = sql_query("SELECT * FROM friends WHERE userid=$receiver AND friendid=" . $CURUSER["id"]) or sqlerr(__FILE__, __LINE__);
                        if (mysql_num_rows($res2) != 1)
                                 stderr("���������", "���� ������������ ��������� ��������� ������ �� ������ ����� ������");
                }
                elseif ($user["acceptpms"] == "no")
                                 stderr("���������", "���� ������������ �� ��������� ���������.");
        }
        sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, saved, location, archived) VALUES(" . $CURUSER["id"] . ", " . $CURUSER["id"] . ",
        $receiver, '" . get_date_time() . "', " . sqlesc($msg) . ", " . sqlesc($subject) . ", " . sqlesc($save) . ",  1, " . sqlesc($archive) . ")") or sqlerr(__FILE__, __LINE__);
        $sended_id = mysql_insert_id();
        if (strpos($user['notifs'], '[pm]') !== false) {
                $username = $CURUSER["username"];
                $usremail = $user["email"];
$body = <<<EOD
$username ������ ��� ������ ���������!

�������� �� ������ ����, ����� ��� ���������.

$DEFAULTBASEURL/message.php?action=viewmessage&id=$sended_id

--

$SITENAME
EOD;
                $subj = "�� �������� ����� �� �� $username!"; 
                mail($usremail, $subj, $body, $SITEEMAIL);
        }
        $delete = $_POST["delete"];
        if ($origmsg)
        {
                if ($delete == "yes")
                {
                        // Make sure receiver of $origmsg is current user
                        $res = sql_query("SELECT * FROM messages WHERE id=$origmsg") or sqlerr(__FILE__, __LINE__);
                        if (mysql_num_rows($res) == 1)
                        {
                                $arr = mysql_fetch_assoc($res);
                                if ($arr["receiver"] != $CURUSER["id"])
                                        stderr($tracker_lang['error'],"�� ��������� ������� �� ���� ���������!");
                                if ($arr["saved"] == "no")
                                        sql_query("DELETE FROM messages WHERE id=$origmsg") or sqlerr(__FILE__, __LINE__);
                                elseif ($arr["saved"] == "yes")
                                        sql_query("UPDATE messages SET location = '0' WHERE id=$origmsg") or sqlerr(__FILE__, __LINE__);
                        }
                }
                if (!$returnto)
                        $returnto = "$DEFAULTBASEURL/message.php";
        }
        if ($returnto) {
                header("Location: $returnto");
                die;
        }
        else {
                header ("Refresh: 2; url=message.php");
                stderr($tracker_lang['success'] , "��������� ���� ������� ����������!");
        }


}
// ����� ����� ���������� ���������


//������ �������� ��������
elseif ($action == 'mass_pm') {
        if (get_user_class() < UC_MODERATOR)
                stderr($tracker_lang['error'], $tracker_lang['access_denied']);
        $n_pms = 0 + $_POST['n_pms'];
        $pmees = $_POST['pmees'];
        $auto = $_POST['auto'];

        if ($auto)
                $body=$mm_template[$auto][1];

        stdhead("������� ���������", false);
        ?>
        <table class=main border=0 cellspacing=0 cellpadding=0>
        <tr><td class=embedded><div align=center>
        <form method=post action=<?=$_SERVER['PHP_SELF']?> name=message>
        <input type=hidden name=action value=takemass_pm>
        <? if ($_SERVER["HTTP_REFERER"]) { ?>
        <input type=hidden name=returnto value="<?=htmlspecialchars($_SERVER["HTTP_REFERER"]);?>">
        <? } ?>
        <table border=1 cellspacing=0 cellpadding=5>
        <tr><td class=colhead colspan=2>�������� �������� ��� <?=$n_pms?> ����������<?=($n_pms>1?"���":"��")?></td></tr>
        <TR>
        <TD colspan="2"><B>����:&nbsp;&nbsp;</B>
        <INPUT name="subject" type="text" size="60" maxlength="255"></TD>
        </TR>
        <tr><td colspan="2"><div align="center">
        <?=textbbcode("message","msg","$body");?>
        </div></td></tr>
        <tr><td colspan="2"><div align="center"><b>�����������:&nbsp;&nbsp;</b>
        <input name="comment" type="text" size="70">
        </div></td></tr>
        <tr><td><div align="center"><b>��:&nbsp;&nbsp;</b>
        <?=$CURUSER['username']?>
        <input name="sender" type="radio" value="self" checked>
        &nbsp; ���������
        <input name="sender" type="radio" value="system">
        </div></td>
        <td><div align="center"><b>Take snapshot:</b>&nbsp;<input name="snap" type="checkbox" value="1">
         </div></td></tr>
        <tr><td colspan="2" align=center><input type=submit value="�������!" class=btn>
        </td></tr></table>
        <input type=hidden name=pmees value="<?=$pmees?>">
        <input type=hidden name=n_pms value=<?=$n_pms?>>
        </form><br /><br />
        </div>
        </td>
        </tr>
        </table>
        <?
        stdfoot();

}
//����� �������� ��������


//������ ����� ��������� �� �������� ��������
elseif ($action == 'takemass_pm') {
        if (get_user_class() < UC_MODERATOR)
                stderr($tracker_lang['error'], $tracker_lang['access_denied']);
        $msg = trim($_POST["msg"]);
        if (!$msg)
                stderr($tracker_lang['error'],"���������� ������� ���������.");
        $sender_id = ($_POST['sender'] == 'system' ? 0 : $CURUSER['id']);
        $from_is = unesc($_POST['pmees']);
        // Change
        $subject = trim($_POST['subject']);
        $query = "INSERT INTO messages (sender, receiver, added, msg, subject, location, poster) ". "SELECT $sender_id, u.id, '" . get_date_time(time()) . "', " .
        sqlesc($msg) . ", " . sqlesc($subject) . ", 1, $sender_id " . $from_is;
        // End of Change
        sql_query($query) or sqlerr(__FILE__, __LINE__);
        $n = mysql_affected_rows();
        $n_pms = $_POST['n_pms'];
        $comment = $_POST['comment'];
        $snapshot = $_POST['snap'];
        // add a custom text or stats snapshot to comments in profile
        if ($comment || $snapshot)
        {
                $res = sql_query("SELECT u.id, u.uploaded, u.downloaded, u.modcomment ".$from_is) or sqlerr(__FILE__, __LINE__);
                if (mysql_num_rows($res) > 0)
                {
                        $l = 0;
                        while ($user = mysql_fetch_array($res))
                        {
                                unset($new);
                                $old = $user['modcomment'];
                                if ($comment)
                                        $new = $comment;
                                        if ($snapshot)
                                        {
                                                $new .= ($new?"\n":"") . "MMed, " . date("Y-m-d") . ", " .
                                                "UL: " . mksize($user['uploaded']) . ", " .
                                                "DL: " . mksize($user['downloaded']) . ", " .
                                                "r: " . (($user['downloaded'] > 0)?($user['uploaded']/$user['downloaded']) : 0) . " - " .
                                                ($_POST['sender'] == "system"?"System":$CURUSER['username']);
                                        }
                                        $new .= $old?("\n".$old):$old;
                                        sql_query("UPDATE users SET modcomment = " . sqlesc($new) . " WHERE id = " . $user['id']) or sqlerr(__FILE__, __LINE__);
                                        if (mysql_affected_rows())
                                                $l++;
                        }
                }
        }
        header ("Refresh: 3; url=message.php");
        stderr($tracker_lang['success'], (($n_pms > 1) ? "$n ��������� �� $n_pms ����" : "��������� ����")." ������� ����������!" . ($l ? " $l �����������(��) � ������� " . (($l>1) ? "����" : " ���") . " ��������!" : ""));
}
//����� ����� ��������� �� �������� ��������


//������ �����������, ��������� ��� ������������
elseif ($action == "moveordel") {
            if (!is_valid_id($_POST["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
        $pm_id = $_POST['id'];

        $pm_box = (int) $_POST['box'];
        $pm_messages = $_POST['messages'];
        if ($_POST['move']) {
                if ($pm_id) {
                        // Move a single message
                        @sql_query("UPDATE messages SET location=" . sqlesc($pm_box) . ", saved = 'yes' WHERE id=" . sqlesc($pm_id) . " AND receiver=" . $CURUSER['id'] . " LIMIT 1");
                }
                else {
                        // Move multiple messages
                        @sql_query("UPDATE messages SET location=" . sqlesc($pm_box) . ", saved = 'yes' WHERE id IN (" . implode(", ", array_map("sqlesc", array_map("intval", $pm_messages))) . ') AND receiver=' . $CURUSER['id']);
                }
                // Check if messages were moved
                if (@mysql_affected_rows() == 0) {
                        stderr($tracker_lang['error'], "�� �������� ����������� ���������!");
                }
                header("Location: message.php?action=viewmailbox&box=" . $pm_box);
                exit();
        }
        elseif ($_POST['delete']) {
                if ($pm_id) {
                        // Delete a single message
                        $res = sql_query("SELECT * FROM messages WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
                        $message = mysql_fetch_assoc($res);
                        if ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'no') {
                                sql_query("DELETE FROM messages WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
                        }
                        elseif ($message['sender'] == $CURUSER['id'] && $message['location'] == PM_DELETED) {
                                sql_query("DELETE FROM messages WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
                        }
                        elseif ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'yes') {
                                sql_query("UPDATE messages SET location=0 WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
                        }
                        elseif ($message['sender'] == $CURUSER['id'] && $message['location'] != PM_DELETED) {
                                sql_query("UPDATE messages SET saved='no' WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
                        }
                } else {
                        // Delete multiple messages
                        if (is_array($pm_messages))
                        foreach ($pm_messages as $id) {
                                $res = sql_query("SELECT * FROM messages WHERE id=" . sqlesc((int) $id));
                                $message = mysql_fetch_assoc($res);
                                if ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'no') {
                                        sql_query("DELETE FROM messages WHERE id=" . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
                                }
                                elseif ($message['sender'] == $CURUSER['id'] && $message['location'] == PM_DELETED) {
                                        sql_query("DELETE FROM messages WHERE id=" . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
                                }
                                elseif ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'yes') {
                                        sql_query("UPDATE messages SET location=0 WHERE id=" . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
                                }
                                elseif ($message['sender'] == $CURUSER['id'] && $message['location'] != PM_DELETED) {
                                        sql_query("UPDATE messages SET saved='no' WHERE id=" . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
                                }
                        }
                }
                // Check if messages were moved
                if (@mysql_affected_rows() == 0) {
                        stderr($tracker_lang['error'],"��������� �� ����� ���� �������!");
                }
                else {
                        header("Location: message.php?action=viewmailbox&box=" . $pm_box);
                        exit();
                }
        }
        elseif ($_POST["markread"]) {
                //�������� ���� ���������
                if ($pm_id) {
                        sql_query("UPDATE messages SET unread='no' WHERE id = " . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
                }
                //�������� ��������� ���������
                else {
                		if (is_array($pm_messages))
                        foreach ($pm_messages as $id) {
                                $res = sql_query("SELECT * FROM messages WHERE id=" . sqlesc((int) $id));
                                $message = mysql_fetch_assoc($res);
                                sql_query("UPDATE messages SET unread='no' WHERE id = " . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
                        }
                }
                // ���������, ���� �� �������� ���������
                if (@mysql_affected_rows() == 0) {
                        stderr($tracker_lang['error'], "��������� �� ����� ���� �������� ��� �����������! ");
                }
                else {
                        header("Location: message.php?action=viewmailbox&box=" . $pm_box);
                        exit();
                }
        }
                elseif ($_POST["archive"]) {
                //���������� ���� ���������
                if ($pm_id) {
                        sql_query("UPDATE messages SET archived='yes' WHERE id = " . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
                }
                //���������� ��������� ���������
                else {
                		if (is_array($pm_messages))
                        foreach ($pm_messages as $id) {
                                $res = sql_query("SELECT * FROM messages WHERE id=" . sqlesc((int) $id));
                                $message = mysql_fetch_assoc($res);
                                sql_query("UPDATE messages SET archived='yes' WHERE id = " . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
                        }
                }
                // ���������, ���� �� �������� ���������
                if (@mysql_affected_rows() == 0) {
                        stderr($tracker_lang['error'], "��������� �� ����� ���� ������������! ");
                }
                else {
                        header("Location: message.php?action=viewmailbox&box=" . $pm_box);
                        exit();
                }
        }
                      elseif ($_POST["unarchive"]) {
                //���������� ���� ���������
                if ($pm_id) {
                        sql_query("UPDATE messages SET archived='no' WHERE archived='yes' AND id = " . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
                }
                //���������� ��������� ���������
                else {
                		if (is_array($pm_messages))
                        foreach ($pm_messages as $id) {
                                $res = sql_query("SELECT * FROM messages WHERE id=" . sqlesc((int) $id));
                                $message = mysql_fetch_assoc($res);
                                sql_query("UPDATE messages SET archived='no' WHERE archived='yes' AND id = " . sqlesc((int) $id)) or sqlerr(__FILE__,__LINE__);
                        }
                }
                // ���������, ���� �� �������� ���������
                if (@mysql_affected_rows() == 0) {
                        stderr($tracker_lang['error'], "��������� �� ����� ���� ���������������! ");
                }
                else {
                        header("Location: message.php?action=viewmailbox&box=" . $pm_box);
                        exit();
                }
        }

stderr($tracker_lang['error'],"��� ��������.");
}
//����� �����������, ��������� ��� ������������


//������ ���������
elseif ($action == "forward") {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                // Display form
                if (!is_valid_id($_GET["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
                $pm_id = $_GET['id'];


                // Get the message
                $res = sql_query('SELECT * FROM messages WHERE id=' . sqlesc($pm_id) . ' AND (receiver=' . sqlesc($CURUSER['id']) . ' OR sender=' . sqlesc($CURUSER['id']) . ') LIMIT 1') or sqlerr(__FILE__,__LINE__);

                if (!$res) {
                        stderr($tracker_lang['error'], "� ��� ��� ���������� ���������� ��� ���������.");
                }
                if (mysql_num_rows($res) == 0) {
                        stderr($tracker_lang['error'], "� ��� ��� ���������� ���������� ��� ���������.");
                }
                $message = mysql_fetch_assoc($res);

                // Prepare variables
                $subject = "Fwd: " . htmlspecialchars($message['subject']);
                $from = $message['sender'];
                $orig = $message['receiver'];

                $res = sql_query("SELECT username FROM users WHERE id=" . sqlesc($orig) . " OR id=" . sqlesc($from)) or sqlerr(__FILE__,__LINE__);

                $orig2 = mysql_fetch_assoc($res);
                $orig_name = "<A href=\"userdetails.php?id=" . $from . "\">" . $orig2['username'] . "</A>";
                if ($from == 0) {
                        $from_name = "���������";
                        $from2['username'] = "���������";
                }
                else {
                        $from2 = mysql_fetch_array($res);
                        $from_name = "<A href=\"userdetails.php?id=" . $from . "\">" . $from2['username'] . "</A>";
                }

                $body = "������������ ���������:<br/>[quote=" . $from2['username'] . "]" . format_comment($message['msg']. "[/quote]");

                stdhead($subject);?>

                <FORM action="message.php" method="post">
                <INPUT type="hidden" name="action" value="forward">
                <INPUT type="hidden" name="id" value="<?=$pm_id?>">
                <TABLE border="0" cellpadding="4" cellspacing="0">
                <TR><TD class="colhead" colspan="2"><?=$subject?></TD></TR>
                <TR>
                <TD>����:</TD>
                <TD><INPUT type="text" name="to" value="������� ���" size="83"></TD>
                </TR>
                <TR>
                <TD>������������<BR>�����������:</TD>
                <TD><?=$orig_name?></TD>
                </TR>
                <TR>
                <TD>��:</TD>
                <TD><?=$from_name?></TD>
                </TR>
                <TR>
                <TD>����:</TD>
                <TD><INPUT type="text" name="subject" value="<?=$subject?>" size="83"></TD>
                </TR>
                <TR>
                <TD>���������:</TD>
                <TD><TEXTAREA name="msg" cols="80" rows="8"></TEXTAREA><BR><?=$body?></TD>
                </TR>
                <TR>
                <TD colspan="2" align="center">��������� ��������� <INPUT type="checkbox" name="save" value="1"<?=$CURUSER['savepms'] == 'yes'?" checked":""?>>&nbsp;<INPUT type="submit" value="���������"></TD>
                </TR>
                </TABLE>
                </FORM><?
                stdfoot();
        }

        else {

                // Forward the message
                if (!is_valid_id($_POST["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
                $pm_id = $_POST['id'];


                // Get the message
                $res = sql_query('SELECT * FROM messages WHERE id=' . sqlesc($pm_id) . ' AND (receiver=' . sqlesc($CURUSER['id']) . ' OR sender=' . sqlesc($CURUSER['id']) . ') LIMIT 1') or sqlerr(__FILE__,__LINE__);
                if (!$res) {
                        stderr($tracker_lang['error'], "� ��� ��� ���������� ���������� ��� ���������.");
                }

                if (mysql_num_rows($res) == 0) {
                        stderr($tracker_lang['error'], "� ��� ��� ���������� ���������� ��� ���������.");
                }

                $message = mysql_fetch_assoc($res);
                $subject = (string) $_POST['subject'];
                $username = strip_tags($_POST['to']);

                // Try finding a user with specified name

                $res = sql_query("SELECT id FROM users WHERE LOWER(username)=LOWER(" . sqlesc($username) . ") LIMIT 1");
                if (!$res) {
                        stderr($tracker_lang['error'], "������������, � ����� ������ �� ����������.");
                }
                if (mysql_num_rows($res) == 0) {
                        stderr($tracker_lang['error'], "������������, � ����� ������ �� ����������.");
                }

                $to = mysql_fetch_array($res);
                $to = $to[0];

                // Get Orignal sender's username
                if ($message['sender'] == 0) {
                        $from = "���������";
                }
                else {
                        $res = sql_query("SELECT * FROM users WHERE id=" . sqlesc($message['sender'])) or sqlerr(__FILE__,__LINE__);
                        $from = mysql_fetch_assoc($res);
                        $from = $from['username'];
                }
                $body = (string) $_POST['msg'];
                $body .= "������������ ���������:[quote=" . $from . "]" . $message['msg'] . "[/quote]";
                $save = (int) $_POST['save'];
                if ($save) {
                        $save = 'yes';
                }
                else {
                        $save = 'no';
                }

                //Make sure recipient wants this message
                if (get_user_class() < UC_MODERATOR) {
                        if ($from["acceptpms"] == "yes") {
                                $res2 = sql_query("SELECT * FROM blocks WHERE userid=$to AND blockid=" . $CURUSER["id"]) or sqlerr(__FILE__, __LINE__);
                                if (mysql_num_rows($res2) == 1)
                                        stderr("���������", "���� ������������ ������� ��� � ������ ������.");
                        }
                        elseif ($from["acceptpms"] == "friends") {
                                $res2 = sql_query("SELECT * FROM friends WHERE userid=$to AND friendid=" . $CURUSER["id"]) or sqlerr(__FILE__, __LINE__);
                                if (mysql_num_rows($res2) != 1)
                                        stderr("���������", "���� ������������ ��������� ��������� ������ �� ������ ����� ������.");
                        }

                        elseif ($from["acceptpms"] == "no")
                                stderr("���������", "���� ������������ �� ��������� ���������.");
                }
                
                $pms = mysql_query("SELECT COUNT(*) FROM messages WHERE (receiver = $receiver AND location=1) OR (sender = $receiver AND saved = 'yes')");
                $pms = mysql_result($pms,0);
                if ($pms>=$pm_max) stderr($tracker_lang['error'], "���� ������ ��������� ���������� ��������, �� �� ������ ��������� ��� ���������.");

                sql_query("INSERT INTO messages (poster, sender, receiver, added, subject, msg, location, saved) VALUES(" . $CURUSER["id"] . ", " . $CURUSER["id"] . ", $to, '" . get_date_time() . "', " . sqlesc($subject) . "," . sqlesc($body) . ", " . sqlesc(PM_INBOX) . ", " . sqlesc($save) . ")") or sqlerr(__FILE__, __LINE__);
                        stdmsg("������", "�� ���������.");
        }
}
//����� ���������


//������ �������� ���������
elseif ($action == "deletemessage") {
if (!is_valid_id($_GET["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
        $pm_id = $_GET['id'];


        // Delete message
        $res = sql_query("SELECT * FROM messages WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
        if (!$res) {
                stderr($tracker_lang['error'],"��������� � ����� ID �� ����������.");
        }
        if (mysql_num_rows($res) == 0) {
                stderr($tracker_lang['error'],"��������� � ����� ID �� ����������.");
        }
        $message = mysql_fetch_assoc($res);
        if ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'no') {
                $res2 = sql_query("DELETE FROM messages WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
        }
        elseif ($message['sender'] == $CURUSER['id'] && $message['location'] == PM_DELETED) {
                $res2 = sql_query("DELETE FROM messages WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
        }
        elseif ($message['receiver'] == $CURUSER['id'] && $message['saved'] == 'yes') {
                $res2 = sql_query("UPDATE messages SET location=0 WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
        }
        elseif ($message['sender'] == $CURUSER['id'] && $message['location'] != PM_DELETED) {
                $res2 = sql_query("UPDATE messages SET saved='no' WHERE id=" . sqlesc($pm_id)) or sqlerr(__FILE__,__LINE__);
        }
        if (!$res2) {
                stderr($tracker_lang['error'],"���������� ������� ���������.");
        }
        if (mysql_affected_rows() == 0) {
                stderr($tracker_lang['error'],"���������� ������� ���������.");
        }
        else {
                header("Location: message.php?action=viewmailbox&id=" . $message['location']);
                exit();
        }
        //����� �������� ���������
}
elseif ($action == 'setspam') {
if (!is_valid_id($_GET["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
          $pm_id = $_GET['id'];

        
        sql_query("UPDATE messages SET spamid = ".$CURUSER['id']." WHERE id = ".$pm_id);
                
        $arow = sql_query("SELECT id FROM users WHERE class = '".UC_SYSOP."'");
        
        while (list($admin) = mysql_fetch_array($arow)) {
        sql_query("INSERT INTO messages (poster, sender, receiver, added, msg, subject, location) VALUES(0, 0,
        $admin, '" . get_date_time() . "', '[url=message.php?action=viewmessage&id=".$pm_id."]��� ���������[/url] ���� �������� ������������� [url=userdetails.php?id=".$CURUSER['id']."]".$CURUSER['username']."[/url], ��� ���������� ����', '��������� � �����!', 1)") or sqlerr(__FILE__, __LINE__);
        }
        header("Location: message.php?action=viewmessage&id=".$pm_id);


}
elseif ($action == 'delspam') {
  if (get_user_class() != UC_SYSOP) stderr("Access Denied.","You're not SYOP.");

  if (!is_valid_id($_GET["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
  $pm_id = $_GET['id'];

  sql_query("UPDATE messages SET spamid=0 WHERE id=".$pm_id);
  
      header("Location: message.php?action=viewmessage&id=".$pm_id);

}
//else stderr("Access Denied.","Unknown action");
?>