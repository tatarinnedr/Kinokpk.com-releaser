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

require_once("include/bittorrent.php");


function bark($msg) {
	stdhead($tracker_lang['error']);
	stdmsg($tracker_lang['error'], $msg);
	stdfoot();
	exit;
}

dbconn();

if (!is_valid_id($_POST["id"])) 			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);

$id = (int) $_POST["id"];

loggedinorreturn();

$res = sql_query("SELECT name,owner,seeders,images,tags FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
stderr($tracker_lang['error'],"������ �������� �� ����������.");

if (get_user_class() < UC_MODERATOR)
bark("�� �� ���������! ��� ����� ����� ���������?\n");

$rt = (int) $_POST["reasontype"];

if ( $rt < 1 || $rt > 5)
bark("�������� ������� $rt.");

$reason = $_POST["reason"];

if ($rt == 1)
$reasonstr = "�������: 0 ���������, 0 �������� = 0 �����";
elseif ($rt == 2)
$reasonstr = "�������" . ($reason[0] ? (": " . trim($reason[0])) : "!");
elseif ($rt == 3)
$reasonstr = "Nuked" . ($reason[1] ? (": " . trim($reason[1])) : "!");
elseif ($rt == 4)
{
	if (!$reason[2])
	bark("�� �� �������� ���� ������, ������� ���� ������� �������.");
	$reasonstr = "��������� ������: " . trim($reason[2]);
}
else
{
	if (!$reason[3])
	bark("�� �� �������� �������, ������ �������� �������.");
	$reasonstr = trim($reason[3]);
}

deletetorrent($id);

if ($row['images']) {
	$images = explode(',',$row['images']);
	foreach ($images as $image) {
		$image = ROOT_PATH."torrents/images/$image";
		$del = unlink($image);
	}
}

$delimgarray = glob(ROOT_PATH."cache/thumbnail/{$id}[0-".($CACHEARRAY['max_images']-1)."][a-z]*.*");
if ($delimgarray)
foreach ($delimgarray as $img) {
	@unlink($img);
}
$tags = explode(",", $row["tags"]);

foreach ($tags as $tag) {
	@sql_query("UPDATE tags SET howmuch=howmuch-1 WHERE name LIKE ".sqlesc($tag)) or sqlerr(__FILE__, __LINE__);
}

if (!defined("CACHE_REQUIRED")){
	require_once(ROOT_PATH . 'classes/cache/cache.class.php');
	require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
	define("CACHE_REQUIRED",1);
}
$cache=new Cache();
$cache->addDriver('file', new FileCacheDriver());

$clearcache = array('block-indextorrents','browse-normal','browse-tags','browse-cat');

foreach ($clearcache as $cachevalue)
$cache->clearGroupCache($cachevalue);


write_log("������� $id ($row[name]) ��� ������ ������������� $CURUSER[username] ($reasonstr)\n","F25B61","torrent");

stdhead("������� ������!");

if (isset($_POST["returnto"]))
$ret = "<a href=\"" . htmlspecialchars($_POST["returnto"]) . "\">�����</a>";
else
$ret = "<a href=\"{$CACHEARRAY['defaultbaseurl']}/\">�� �������</a>";

?>
<h2>������� ������!</h2>
<p><?= $ret ?></p>
<?

stdfoot();

?>