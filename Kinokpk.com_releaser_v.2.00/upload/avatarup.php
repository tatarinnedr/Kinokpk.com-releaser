<?
require "include/bittorrent.php";
dbconn();
loggedinorreturn();
stdhead();
echo '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="block" width="100%" align="center" valign="middle" ><strong>�������� �������</strong></td></tr></table>';

    $path="./avatars";
    $max_image_width = 120;
    $max_image_height = 120;
    $maxfilesize = 60 * 1024;
    $size = @GetImageSize($_FILES['avatar']['tmp_name']);
    if (!$size) die ("Trying to upload a shell? Access denied!");

    if(!file_exists($path)) die("<div class=\"validation-advice\">����������, �������� ����� <font color=black>".$path."</font> � <a href=?>��������� ������� ��������� ����</a>.</div>");

if(empty($_FILES['avatar']['tmp_name']))
echo "<br><form id=test method=post enctype=multipart/form-data><div class=\"form-row\"><div class=\"field-widget\"><label for=\"avatar\">�������� �������</label> : <input type=file name=avatar id=avatar class=\"emtyavatar validate-img validate-img-size\" title=\"�������� ��������\"></div></div>
<input type=submit value=��������� ></form><br><br><center><font color=green>���������: ������� ������ ���� �������� �� ������ ".round($maxfilesize/1024,2)." ��������<br>� p������� �� ������ ".$max_image_width."�".$max_image_height." ��������</font></center> ";
elseif (($size[0] > $max_image_width ) || ($size[1] > $max_image_height))
echo "<br><div class=\"validation-advice\">������ ������ ������� ".$size[0]."�".$size[1]." ��������� ������ �� ����� ".$max_image_width."�".$max_image_height."  ��������</div> <a href=?> ��������� �������?</a></font></b>";
elseif ($_FILES['avatar']['size'] > $maxfilesize) {
echo $_FILES['avatar']['size'];
echo "<br><div class=\"validation-advice\">������ ����� ������� ��������� ".round($maxfilesize/1024,2)." ��������!</div> <a href=?> ��������� �������?</a></font></b>";
}else{
if(!copy($_FILES['avatar']['tmp_name'],$path.chr(47).$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.'))))
die("<b><font color=red>���� �� ��� ��������! ���������� <a href=?>��������� �������</a>!</font></b>");
else
$pathav = "$DEFAULTBASEURL/avatars/".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.'));
sql_query("UPDATE users SET avatar = '".$pathav."' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);
/*sql_query("UPDATE ipb_member_extra SET avatar_type = 'url' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);
sql_query("UPDATE ipb_member_extra SET avatar_size = '".$size[0]."x".$size[1]."' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);
sql_query("UPDATE ipb_member_extra SET avatar_location = '".$pathav."' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);
*/
echo "<center><b><br>���� ������� ���� ������� �������� �� ������!</font></b></center><hr>�������� �����: <b>".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.'))."</b><br>������ �����: <b>".round($_FILES['avatar']['size']/1024,2)." ��.</b><hr><center>������ ������������� �������� � ������� ������������</b></center>";// ��� �� ��������, ��� � �� <a href=\"".$DEFAULTBASEURL."/forums/\">������</a></b></center> ";

}
stdfoot();
?>
<script type="text/javascript">
                        function formCallback(result, form) {
                            window.status = "�������� ���������� ����� '" + form.id + "': ��������� = " + result;
                        }

                        var valid = new Validation('test', {immediate : true, onFormValidate : formCallback});
                Validation.addAllThese([
                            ['emtyavatar', '��� ����������� �� ������ ������� ������!', function(v) {
                return !Validation.get('IsEmpty').test(v);
            }],
                            ['validate-img', '����������� ���� �� �������� ��������', function (v) {
     return Validation.get('IsEmpty').test(v) ||  /^(.+)\.(jpg|jpeg|png|gif)$/.test(v);
                            }]



                        ]);
                    </script>