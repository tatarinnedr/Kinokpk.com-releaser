<?

function end_chmod($dir, $chm) {
	if (file_exists($dir) && intval($chm)) {
		#chmod($dir, "0".$chm."");
		$pdir = decoct(fileperms($dir));
		$per = substr($pdir, -3);
		if ($per != $chm) return "".$dir." �� ����� ������ ���������� ��� ������ �� �������.<br />���������� ������ �������� CHMOD - ".$chm."";
	}
}

?>