<?

require_once("include/bittorrent.php");
define("IN_CONTACT",true);
dbconn(false);

stdhead("����� ��� �����");

?>
<br />
<form method="post" action="sendeail.php">
<?
require_once("include/bittorrent.php");
$ipi = getenv("REMOTE_ADDR");
$httpagenti = getenv ("HTTP_USER_AGENT");
?>
  <input type="hidden" name="ip" value="<?php echo $ipi ?>" />
  <input type="hidden" name="httpagent" value="<?php echo $httpagenti ?>" />

<div align="center">
  <table border="0" cellspacing="0" cellpadding="3" style="border-collapse: collapse">
    <tr>
      <td class="colhead" colspan="2" align="center">����� ��� ����� � ��������������</td>
    </tr>
    <tr>
      <td>&nbsp;&nbsp;���� ���:&nbsp;</td>
      <? if ($CURUSER) { ?>
      <td><input type="text" value="<?php echo $CURUSER[username] ?>" size="40" disabled/></td>
    </tr>
    <tr>
      <td>&nbsp;&nbsp;��� Email:&nbsp;</td>
      <td><input type="text" value="<?php echo $CURUSER[email] ?>" size="40" disabled/></td>
    </tr>
   <? } else {  ?>
     <td><input type="text" name="visitor" size="40" /></td>
    </tr>
    <tr>
      <td>&nbsp;&nbsp;��� Email:&nbsp;</td>
      <td><input type="text" name="visitormail" size="40" /></td>
    </tr>
  <?  } ?>
    <tr>
      <td>&nbsp;&nbsp;���� ���������:&nbsp;</td>
      <td><select size="1" name="subj">
      <option selected>�������� � ����������</option>
      <option>���� �� �����</option>
      <option>������� � ��������</option>
      <option>�����������</option>
      <option>���� ����� ����������!</option>
      <option>��� ������� �������, ��� ������</option>
      <option>������</option>
      </select>
      </td>
    </tr>
    <tr>
      <td colspan="2">
      <center><font size="3"><b>���� ���������</b>:</font></center>
  <textarea name="notes" rows="8" cols="80"></textarea></td>
    </tr>
    <?
// use_captcha
if (!$CURUSER){

require_once('include/recaptchalib.php');
print '<tr><td colspan="2" align="center">�� �������?</td></tr>';
print '<tr><td colspan="2" align="center">'.recaptcha_get_html($re_publickey).'</td></tr>';
}
?>
    <tr>
      <td colspan="2" align="center">
  <input type="submit" value="���������"/><input type="reset" value="��������" />
  </td>
    </tr>
  </table>
</div>
  <? if ($CURUSER) { ?>
  <input type="hidden" name="visitor" value="<?php echo $CURUSER[username] ?>" />
  <input type="hidden" name="visitormail" value="<?php echo $CURUSER[email] ?>" />
  <? } ?>
</form>
<?
stdfoot();
?>