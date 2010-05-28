<?php

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
stdhead("�������� BitTorrent");
?>
<table class=main width=750 border=0 cellspacing=0 cellpadding=0><tr><td class=embedded>
<table width=100% border=1 cellspacing=0 cellpadding=5>
<tr><td class=colhead>�������� �������-�����</td></tr>
<tr><td class=text>

<em>(Updated to reflect the tracker changes. 14-04-2004)</em>

<br /><br />
There seems to be a lot of confusion about how the statistics updates work. The following is a capture of a full
session to see what's going on behind the scenes. ������ �������� � �������� ����� ������� HTTP GET-������. ��������� ����� ������� ������:<br />
<br />
<code>GET /announce.php?<b>passkey</b>=a092924c51e9cac0d76b51457de93c9e&<b>info_hash</b>=c%97%91%C5jG%951%BE%C7M%F9%BFa%03%F2%2C%ED%EE%0F& <b>peer_id</b>=S588-----gqQ8TqDeqaY&<b>port</b>=6882&<b>uploaded</b>=0&<b>downloaded</b>=0&<b>left</b>=753690875&<b>event</b>=started</code><br />
<br />
��������� �� �����:<br />
<br />
� <b>passkey</b> - ��� ������ ������, ������� �������������� ��� ��� ������������ �������<br />
� <b>info_hash</b> - ��� ���������������� �������<br />
� <b>peer_id</b> - ������������� ������� (����� s588 �������� Shad0w's 5.8.8, ��������� ��������� �����)<br />
� <b>port</b> - ���� �� ������� ������ ������� �������� ����������<br />
� <b>uploaded</b>=0 - ������� ������ ����� ����������<br />
� <b>downloaded</b>=0 - ������� ������ ������ ����������<br />
� <b>left</b>=753690875 - ������� �������� ��������<br />
� <b>event=started</b> - ��������� ������� � ��� ��� ������ ������ ����� �������<br />
<br />
�������� ��������, ��� IP ����� ������������ ��� �� ���������� (��� �� ����� ������ ����� ���� �������� �� �������� IP ������).
It's up to the tracker to see it and associate it with the user_id.<br />
(������ ������� �������, � ��� ���������� ������ IP ������ ����� � ������������� �����.)<br />
At this stage the user's profile will be listing this torrent as being leeched.<br />
<br />
&raquo; � ����� ������� ������ ����� ���������� GET-������� �� ������. �� ��������� ������ ������ �� ���:
<br />
<br />
<code> GET /announce.php?<b>passkey</b>=a092924c51e9cac0d76b51457de93c9e&<b>info_hash</b>=c%97%91%C5jG%951%BE%C7M%F9%BFa%03%F2%2C%ED%EE%0F& <b>peer_id</b>=S588-----gqQ8TqDeqaY&<b>port</b>=6882&<b>uploaded</b>=67960832&<b>downloaded</b>=40828928& <b>left</b>=715417851&<b>numwant</b>=0</code><br />
<br />
("numwant" is how the client tells the tracker how many new peers it wants, in this case 0.)
<br />
<br />
As you can see at this stage the user had uploaded approx. 68MB and downloaded approx. 40MB. Whenever the tracker receives
these GETs it updates both the stats relative to the 'currently leeching/seeding' boxes and the total user upload/download stats. These intermediate GETs will be sent either periodically (every 15 min
or so, depends on the client and tracker) or when you force a manual announce in the client.
<br />
<br />
�������, ����� ������ ������������ �� ���������� ��� ���� ������ �� ������:
<br />
<br />
<code> GET /announce.php?<b>passkey</b>=a092924c51e9cac0d76b51457de93c9e&<b>info_hash</b>=c%97%91%C5jG%951%BE%C7M%F9%BFa%03%F2%2C%ED%EE%0F& <b>peer_id</b>=S588-----gqQ8TqDeqaY&<b>port</b>=6882&<b>uploaded</b>=754384896&<b>downloaded</b>=754215163 &<b>left</b>=0&numwant</b>=0&<b>event</b>=completed</code><br />
<br />
Notice the all-important "event=completed". It is at this stage that the torrent will be removed from the user's profile.
If for some reason (tracker down, lost connection, bad client, crash, ...) this last GET doesn't reach
the tracker this torrent will still be seen in the user profile until some tracker timeout occurs. It should be stressed that this message will be sent only when
closing the client properly, not when the download is finished. (The tracker will start listing
a torrent as 'currently seeding' after it receives a GET with left=0). <br />
<br />
There's a further message that causes the torrent to be removed from the user's profile, 
namely "event=stopped". This is usually sent 
when stopping in the middle of a download, e.g. by pressing 'Cancel' in Shad0w's. <br />
<br />
��� ���� �������: ��������� ������� ����� ������� �����/����������. ��� <b>��</b> ���������� �������� ��������� �� ������.
�� ����������� ��� ��� ������� ��������� ��������� ����, ��� ������ �� ����� ��������. (��������� �� Shad0w's 5.8.11 � ABC 2.6.5.)
<br />
</td></tr></table>
</td></tr></table>
<br />
<?
stdfoot();
?>