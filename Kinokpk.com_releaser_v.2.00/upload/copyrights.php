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

gzip();

dbconn();

//loggedinorreturn();

stdhead("�������");

begin_main_frame();

?>

<? begin_frame("��� ����������������"); ?>
<ul>
��������������! ����������, ������������� �� ������ �������, ������������� ������������� ��� �������� ������������� � ��������������� ����� � �� ����� ���� ���������/���������� �� ������ ���������. �� �������� �����, �� �������-���������, �� ����� ������ ���������� ��� ����������� ���� �� ����� ����� ������� �������������� �� ����� ������������� ���������� ������� �����. ����� �� ����, ��, ��� ������������, ��� ����� ������������� ������ � �������������� �������� �� ����� ��������� �������������. ������ ������� ��������� ����� ��������� � ������������ ������������� ����������, ���������� �� �����.
��� ������, �������������� �� FTP � HTTP �������� ����� ������ �������� � �������� ��������. �� ������, �������������� � ������� Torrent-���� ��������������� ����� ���������� �����. ���� ��� ���������� �����, �� �� ������ ���������� ������������ ����� � HD �������� � ����������������.
���� �� ��������� ���������������� ������ � �� ������, ����� ��������� ����� ��� �������� �� �����, �������� ������������� �����. ���� ����� ������� ��������� � ��������� ������ �������.<br /><br />
<center>
������� ��� ��������� �� <b>TBDev YSE PRE RC 6</b>. �������� ������ ������ �������� �������������� ������� Kinokpk.com<br/>
��������� ������ ������� �������� ����������� � ��������������� � <a target="_blank" href="http://dev.kinokpk.com">������ ������������ �������� Kinokpk.com</a></center>
<? end_frame(); ?>

<?
end_main_frame();
stdfoot(); ?>