<?php
if (!defined("ADMIN_FILE")) die("Illegal File Access");

function iUsers($iname, $ipass, $imail) {
	global $admin_file;
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$updateset = array();
		if (!empty($ipass)) {
			$secret = mksecret();
			$hash = md5($secret.$ipass.$secret);
			$updateset[] = "secret = ".sqlesc($secret);
			$updateset[] = "passhash = ".sqlesc($hash);
		}
		if (!empty($imail) && validemail($imail))
			$updateset[] = "email = ".sqlesc($imail);
		if (count($updateset))
			$res = sql_query("UPDATE users SET ".implode(", ", $updateset)." WHERE username = ".sqlesc($iname)) or sqlerr(__FILE__,__LINE__);
		if (mysql_modified_rows() < 1)
			stdmsg("������", "����� ������ ����������� ��������! �������� ������� �������������� ��� ������������.", "error");
		else
			stdmsg("��������� ������������ ������ �������", "��� ������������: ".$iname.(!empty($hash) ? "<br />����� ������: ".$ipass : "").(!empty($imail) ? "<br />����� �����: ".$imail : ""));
	} else {
		echo "<form method=\"post\" action=\"".$admin_file.".php?op=iUsers\">"
		."<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\">"
		."<tr><td class=\"colhead\" colspan=\"2\">����� ������</td></tr>"
		."<tr>"
		."<td><b>������������</b></td>"
		."<td><input name=\"iname\" type=\"text\"></td>"
		."</tr>"
		."<tr>"
		."<td><b>����� ������</b></td>"
		."<td><input name=\"ipass\" type=\"password\"></td>"
		."</tr>"
		."<tr>"
		."<td><b>����� �����</b></td>"
		."<td><input name=\"imail\" type=\"text\"></td>"
		."</tr>"
		."<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" name=\"isub\" value=\"�������\"></td></tr>"
		."</table>"
		."<input type=\"hidden\" name=\"op\" value=\"iUsers\" />"
		."</form>";
	}
}

switch ($op) {
	case "iUsers":
	iUsers($iname, $ipass, $imail);
	break;
}

?>