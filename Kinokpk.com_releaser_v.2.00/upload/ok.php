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

dbconn();

if (!mkglobal("type"))
	die();

if ($type == "signup" && mkglobal("email")) {
	if (!validemail($email))
		stderr($tracker_lang['error'], "��� �� ������ �� �������� email �����.");
	stdhead($tracker_lang['signup_successful']);
        stdmsg($tracker_lang['signup_successful'],($use_email_act ? sprintf($tracker_lang['confirmation_mail_sent'], htmlspecialchars($email)) : sprintf($tracker_lang['thanks_for_registering'], $SITENAME)));
	stdfoot();
}
elseif ($type == "sysop") {
		stdhead($tracker_lang['sysop_activated']);
	if (isset($CURUSER))
		stdmsg($tracker_lang['sysop_activated'],sprintf($tracker_lang['sysop_account_activated'], $DEFAULTBASEURL));
	else
		print("<p>Your account has been activated! However, it appears that you could not be logged in automatically. A possible reason is that you disabled cookies in your browser. You have to enable cookies to use your account. Please do that and then <a href=\"login.php\">log in</a> and try again.</p>\n");

		stdfoot();
	}
elseif ($type == "confirmed") {
	stdhead($tracker_lang['account_activated']);
	stdmsg($tracker_lang['account_activated'], $tracker_lang['this_account_activated']);
	stdfoot();
}
elseif ($type == "confirm") {
	if (isset($CURUSER)) {
		stdhead("������������� �����������");
		print("<h1>��� ������� ������� �����������!</h1>\n");
		print("<p>��� ������� ������ �����������! �� ������������� �����. ������ �� ������ <a href=\"$DEFAULTBASEURL/\"><b>������� �� �������</b></a> � ������ ������������ ��� �������.</p>\n");
		print("<p>������� ��� ������ ������������ $SITENAME �� ����������� ��� ��������� <a href=\"rules.php\"><b>�������</b></a> � <a href=\"faq.php\"><b>����</b></a>.</p>\n");
		stdfoot();
	}
	else {
		stdhead("Signup confirmation");
		print("<h1>Account successfully confirmed!</h1>\n");
		print("<p>Your account has been activated! However, it appears that you could not be logged in automatically. A possible reason is that you disabled cookies in your browser. You have to enable cookies to use your account. Please do that and then <a href=\"login.php\">log in</a> and try again.</p>\n");
		stdfoot();
	}
}
else
	die();

?>