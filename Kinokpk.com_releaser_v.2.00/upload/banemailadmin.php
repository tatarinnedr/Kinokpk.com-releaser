<?
require "include/bittorrent.php";
dbconn(false);
loggedinorreturn();
if (get_user_class() < UC_SYSOP)
stderr($tracker_lang['error'], $tracker_lang['access_denied']);


$remove = $_GET['remove'] + 0;
if ($remove)
{
        mysql_query("DELETE FROM bannedemails WHERE id = '$remove'") or sqlerr(__FILE__, __LINE__);
        write_log("��� $remove ��� ���� ������������� $CURUSER[username]");
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
        $email = trim($_POST["email"]);
        $comment = trim($_POST["comment"]);
        if (!$email || !$comment)
        stderr("Error", "Missing form data.");
        mysql_query("INSERT INTO bannedemails (added, addedby, comment, email) VALUES(".sqlesc(get_date_time()).", $CURUSER[id], ".sqlesc($comment).", ".sqlesc($email).")") or sqlerr(__FILE__, __LINE__);
        header("Location: $_SERVER[REQUEST_URI]");
        die;
}

ob_start("ob_gzhandler");

$res = mysql_query("SELECT * FROM bannedemails ORDER BY added DESC") or sqlerr(__FILE__, __LINE__);

stdhead("��� �������");

print("<h1>������ �����</h1>\n");

if (mysql_num_rows($res) == 0)
print("<p align=center><b>�����</b></p>\n");
else
{
        print("<table border=1 cellspacing=0 cellpadding=5>\n");
        print("<tr><td class=colhead>���������</td><td class=colhead align=left>Email</td>".
        "<td class=colhead align=left>���</td><td class=colhead align=left>����������</td><td class=colhead>�����</td></tr>\n");

        while ($arr = mysql_fetch_assoc($res))
        {
                $r2 = mysql_query("SELECT username FROM users WHERE id = $arr[addedby]") or sqlerr(__FILE__, __LINE__);
                $a2 = mysql_fetch_assoc($r2);
                print("<tr><td>$arr[added]</td><td align=left>$arr[email]</td><td align=left><a href=userdetails.php?id=$arr[addedby]>$a2[username]".
                "</a></td><td align=left>$arr[comment]</td><td><a href=banemailadmin.php?remove=$arr[id]>����� ���</a></td></tr>\n");
        }
        print("</table>\n");
}

print("<h2>��������</h2>\n");
print("<table border=1 cellspacing=0 cellpadding=5>\n");
print("<form method=\"post\" action=\"banemailadmin.php\">\n");
print("<tr><td class=rowhead>Email</td><td><input type=\"text\" name=\"email\" size=\"40\"></td>\n");
print("<tr><td class=rowhead>����������</td><td><input type=\"text\" name=\"comment\" size=\"40\"></td>\n");
print("<tr><td colspan=2>����������� *@email.com ����� �������� ���� ������</td></tr>\n");
print("<tr><td colspan=2><input type=\"submit\" value=\"��������\" class=\"btn\"></td></tr>\n");
print("</form>\n</table>\n");

stdfoot();

?>