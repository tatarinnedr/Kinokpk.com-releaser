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

require "include/bittorrent.php";
dbconn(false);

$res = sql_query("SELECT tags,image1,id,name,size,category,added FROM torrents WHERE visible='yes' ORDER BY added DESC LIMIT 5") or sqlerr(__FILE__, __LINE__);


$SITENAME = iconv("CP1251", "UTF-8",$SITENAME);
try {
	include('classes/rssatom/rssatom.php');
	$feeds=new FeedGenerator;
	$feeds->setGenerator(new RSSGenerator); # or AtomGenerator
	$feeds->setAuthor($ADMINEMAIL." (Site Admin)");
	$feeds->setTitle($SITENAME);
	$feeds->setChannelLink($DEFAULTBASEURL."/rss.php");
	$feeds->setLink($DEFAULTBASEURL);
	$feeds->setDescription($SITENAME.iconv("CP1251", "UTF-8"," - новости RSS 2.0"));
	$feeds->setID($DEFAULTBASEURL."/rss.php");
	
while ($row = mysql_fetch_row($res)){
list($tagsres,$img,$id,$name,$size,$cat,$added,$catname) = $row;

    $detid = sql_query("SELECT descr_torrents.value, descr_details.name, descr_details.description, descr_details.input FROM descr_torrents LEFT JOIN descr_details ON descr_details.id = descr_torrents.typeid WHERE descr_torrents.torrent = ".$id." AND descr_details.mainpage = 'yes' ORDER BY descr_details.sort ASC");
    $descr = '<table width="100%" border="0">';
    $descr .= '<tr><td valign="top"><b>Постер:</b></td><td align="center"><img src="'.$DEFAULTBASEURL.'/thumbnail.php?image='.$img.'&amp;for=rss"></td></tr>';
    $tags = '';
        foreach(explode(",", $tagsres) as $tag)
                $tags .= "<a href=\"$DEFAULTBASEURL/browse.php?tag=".$tag."\">".$tag."</a>, ";

                if ($tags)
                $tags = substr($tags, 0, -2);

    $descr .= "<tr><td valign=\"top\"><b>Жанр:</b></td><td>".$tags."</td></tr>";
while ($did = mysql_fetch_array($detid))  {
   if ($did['value'] != '') $descr .= "<tr><td valign=\"top\"><b>".$did['name'].":</b></td><td>".format_comment($did['value'])."</td></tr>";
  }
   $descr .="</table>";
   
   $descr = str_replace('<div style="position: static;" class="news-wrap"><div class="news-head folded clickable">','',$descr);
   $descr = str_replace('</div><div style="display: none;" class="news-body">','',$descr);
   $descr = str_replace('</div></div>','',$descr);
   
	$feeds->addItem(new FeedItem($DEFAULTBASEURL."/details.php?id=$id&amp;hit=1", iconv("CP1251", "UTF-8",$name), $DEFAULTBASEURL."/details.php?id=$id&amp;hit=1", iconv("CP1251", "UTF-8",$descr)));

}


	$feeds->display();
}
catch(FeedGeneratorException $e){
	echo 'Error: '.$e->getMessage();
}
?>