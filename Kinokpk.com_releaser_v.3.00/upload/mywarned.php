<?
require "include/bittorrent.php";
dbconn();
loggedinorreturn();

stderr('��������','������ ����� �������� ���������');
$STOIMOST = 30*1024*1024*1024;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	headers(true);
	if (empty($_POST["id"])) {
		stdmsg($tracker_lang['error'], "�� �� ������� ���-�� ��������������!");
		die();
	}
	$id = (int) $_POST["id"];
	if (!is_valid_id($id))
	{
		stdmsg($tracker_lang['error'], $tracker_lang['access_denied']);
		die();
	}

	if ($CURUSER["uploaded"] < ($id*$STOIMOST))
	{
		stdmsg($tracker_lang['error'], "� ��� ������������ �������!");
		die();
	}

	$modcomment = sqlesc(gmdate("Y-m-d") . " - ������������ ������� " .$id. " �������������� �� ".mksize($id*$STOIMOST)."\n " . $CURUSER['modcomment']);
	if (!sql_query("UPDATE users SET num_warned = num_warned - $id, uploaded = uploaded - ($id*$STOIMOST), modcomment = ".$modcomment." WHERE id = ".sqlesc($CURUSER["id"])))
	{
		stdmsg($tracker_lang['error'], "�� ���� �������� ��������������!");
		die();
	}
	$zalet = mksize($id*$STOIMOST);
	stdmsg($tracker_lang['success'], "$id ��������������(�) �������� �� $zalet �������!");
	//break;
	die();

}
else
{
	stdhead("��� ��������������");
	?>
<script type="text/javascript">
function send(){

   var frm = document.mywarned;
    var bonus_type = '';

    for (var i=0; i < frm.elements.length;i++) {
        var elmnt = frm.elements[i];
        if (elmnt.type=='radio') {
            if(elmnt.checked == true){ bonus_type = elmnt.value; break;}
        }
    }

      (function($){
   $("#ajax").empty();
   $("#ajax").append('<div align="center"><img src="pic/loading.gif" border="0"/></div>');
    $.post("mywarned.php", { ajax: 1, id: bonus_type }, function(data){
   $("#ajax").empty();
   $("#ajax").append(data);
});
})(jQuery);
}
</script>
<div id="ajax">
<table class="embedded" width="100%" border="1" cellspacing="0"
	cellpadding="5">
	<?

	$myupl = mksize($CURUSER[uploaded]);
	for($i = 1; $i <= 5; $i++)
	{    $id = $i;
	$upl = mksize($STOIMOST*$i);
	$img.="<img src=\"pic/star_warned.gif\" alt=\"������� ��������������\" title=\"������� ��������������\">";
	$descr ="�������� ".$i." ��������������(�)";

	if ($CURUSER["num_warned"]>=$i)
	{$distup = enable; $chec = checked;}
	else
	{$distup = disabled; $chec = "";}


	$output .= "<tr><td><b>$img</b><br />$descr</td><td><center>$upl&nbsp;/&nbsp;$myupl</center></td><td><center><input type=\"radio\" name=\"warned_id\" value=\"$id\" $chec $distup /></center></td></tr>\n";
	}
	?>
	<tr align="center">
		<td class="colhead" colspan="3">��� �������������� <?=$CURUSER["num_warned"];?>,
		������ � ������� <?=mksize($CURUSER["uploaded"]);?></td>
	</tr>
	<tr align="center">
		<td class="colhead">���-�� ��������������</td>
		<td class="colhead">���������</td>
		<td class="colhead">�����</td>
	</tr>
	<form action="mywarned.php" name="mywarned" method="post"><?=$output;?>
	<tr align="right">
		<td colspan="3"><input type="submit" onClick="send(); return false;"
			value="��������" /></td>
	</tr>
	</form>
</table>
</div>
	<?
	stdfoot();
}
?>