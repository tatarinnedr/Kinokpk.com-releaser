<?php
if (!defined('BLOCK_FILE')) {
 Header("Location: ../index.php");
 exit;
}
$blocktitle = "���� ������";

$content = cloud();
$content .='<br/><div align="center">[<a href="alltags.php">������� ����</a>]</div>'

?>