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

define ('IN_ANNOUNCE', true);
require_once('./include/core_announce.php');

dbconn(false);

$r = "d" . benc_str("files") . "d";

$fields = "info_hash, times_completed, seeders, leechers";

if (!isset($_GET["info_hash"]))
	$query = "SELECT $fields FROM torrents ORDER BY info_hash";
else {
$info_hash = stripslashes($_GET["info_hash"]);
if (get_magic_quotes_gpc())
$hash = bin2hex($info_hash);
else
$hash = bin2hex($_GET["info_hash"]);
if (strlen($info_hash) != 20)
err("Invalid info-hash (".strlen($info_hash).")");

$query = "SELECT $fields FROM torrents WHERE info_hash = " . sqlesc($hash);
}

$res = mysql_query($query) or err(mysql_error());

while ($row = mysql_fetch_assoc($res)) {
	$r .= "20:" . pack("H*", ($row["info_hash"])) . "d" .
		benc_str("complete") . "i" . $row["seeders"] . "e" .
		benc_str("downloaded") . "i" . $row["times_completed"] . "e" .
		benc_str("incomplete") . "i" . $row["leechers"] . "e" .
		"e";
}

$r .= "ee";

header("Content-Type: text/plain");
header("Pragma: no-cache");
print($r);

?>