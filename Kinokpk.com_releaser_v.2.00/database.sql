-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- ����: localhost
-- ����� ��������: ��� 17 2008 �., 00:11
-- ������ �������: 5.0.18
-- ������ PHP: 5.1.6
-- 
-- ��: `releaser200`
-- 

-- --------------------------------------------------------

-- 
-- ��������� ������� `addedrequests`
-- 

CREATE TABLE `addedrequests` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `requestid` int(10) unsigned NOT NULL default '0',
  `userid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pollid` (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `addedrequests`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `bannedemails`
-- 

CREATE TABLE `bannedemails` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `addedby` int(10) unsigned NOT NULL default '0',
  `comment` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `bannedemails`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `bans`
-- 

CREATE TABLE `bans` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `mask` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `bans`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `blocks`
-- 

CREATE TABLE `blocks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `blockid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userfriend` (`userid`,`blockid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `blocks`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `bonus`
-- 

CREATE TABLE `bonus` (
  `id` int(5) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `points` decimal(5,2) NOT NULL default '0.00',
  `description` text NOT NULL,
  `type` varchar(10) NOT NULL default 'traffic',
  `quanity` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `bonus`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `bookmarks`
-- 

CREATE TABLE `bookmarks` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `torrentid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `bookmarks`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `cache_stats`
-- 

CREATE TABLE `cache_stats` (
  `cache_name` varchar(255) NOT NULL,
  `cache_value` mediumtext,
  PRIMARY KEY  (`cache_name`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- ���� ������ ������� `cache_stats`
-- 

INSERT INTO `cache_stats` VALUES ('siteonline', 'a:4:{s:5:"onoff";s:1:"1";s:6:"reason";s:4:"test";s:5:"class";s:1:"6";s:10:"class_name";s:21:"������ ��� ����������";}');
INSERT INTO `cache_stats` VALUES ('bans_lastupdate', '1');
INSERT INTO `cache_stats` VALUES ('lastcleantime', '1');
INSERT INTO `cache_stats` VALUES ('censoredtorrents_lastupdate', '1');
INSERT INTO `cache_stats` VALUES ('requests_lastupdate', '1');
INSERT INTO `cache_stats` VALUES ('polls_lastupdate', '1');
INSERT INTO `cache_stats` VALUES ('news_lastupdate', '1');
INSERT INTO `cache_stats` VALUES ('torrents_lastupdate', '1');

-- --------------------------------------------------------

-- 
-- ��������� ������� `categories`
-- 

CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=3 ;

-- 
-- ���� ������ ������� `categories`
-- 

INSERT INTO `categories` VALUES (1, 10, '������', '1.png');
INSERT INTO `categories` VALUES (2, 20, '�������', '2.png');

-- --------------------------------------------------------

-- 
-- ��������� ������� `censoredtorrents`
-- 

CREATE TABLE `censoredtorrents` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `name` (`name`,`reason`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `censoredtorrents`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `checkcomm`
-- 

CREATE TABLE `checkcomm` (
  `id` int(11) NOT NULL auto_increment,
  `checkid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `offer` tinyint(4) NOT NULL default '0',
  `torrent` tinyint(4) NOT NULL default '0',
  `req` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `checkcomm`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `comments`
-- 

CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `torrent` int(10) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `ori_text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` datetime NOT NULL default '0000-00-00 00:00:00',
  `request` varchar(11) NOT NULL default '0',
  `offer` varchar(11) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `post_id` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `torrent` (`torrent`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `comments`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `countries`
-- 

CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `flagpic` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=103 ;

-- 
-- ���� ������ ������� `countries`
-- 

INSERT INTO `countries` VALUES (87, 'Antigua Barbuda', 'antiguabarbuda.gif');
INSERT INTO `countries` VALUES (33, 'Belize', 'belize.gif');
INSERT INTO `countries` VALUES (59, 'Burkina Faso', 'burkinafaso.gif');
INSERT INTO `countries` VALUES (10, 'Denmark', 'denmark.gif');
INSERT INTO `countries` VALUES (91, 'Senegal', 'senegal.gif');
INSERT INTO `countries` VALUES (76, 'Trinidad & Tobago', 'trinidadandtobago.gif');
INSERT INTO `countries` VALUES (20, '���������', 'australia.gif');
INSERT INTO `countries` VALUES (36, '�������', 'austria.gif');
INSERT INTO `countries` VALUES (27, '�������', 'albania.gif');
INSERT INTO `countries` VALUES (34, '�����', 'algeria.gif');
INSERT INTO `countries` VALUES (12, '������', 'uk.gif');
INSERT INTO `countries` VALUES (35, '������', 'angola.gif');
INSERT INTO `countries` VALUES (66, '������', 'andorra.gif');
INSERT INTO `countries` VALUES (19, '���������', 'argentina.gif');
INSERT INTO `countries` VALUES (53, '����������', 'afghanistan.gif');
INSERT INTO `countries` VALUES (80, '������', 'bahamas.gif');
INSERT INTO `countries` VALUES (83, '��������', 'barbados.gif');
INSERT INTO `countries` VALUES (16, '�������', 'belgium.gif');
INSERT INTO `countries` VALUES (84, '���������', 'bangladesh.gif');
INSERT INTO `countries` VALUES (101, '��������', 'bulgaria.gif');
INSERT INTO `countries` VALUES (65, '������', 'bosniaherzegovina.gif');
INSERT INTO `countries` VALUES (18, '��������', 'brazil.gif');
INSERT INTO `countries` VALUES (74, '�������', 'vanuatu.gif');
INSERT INTO `countries` VALUES (72, '�������', 'hungary.gif');
INSERT INTO `countries` VALUES (71, '���������', 'venezuela.gif');
INSERT INTO `countries` VALUES (75, '�������', 'vietnam.gif');
INSERT INTO `countries` VALUES (7, '��������', 'germany.gif');
INSERT INTO `countries` VALUES (77, '��������', 'honduras.gif');
INSERT INTO `countries` VALUES (32, '���� ����', 'hongkong.gif');
INSERT INTO `countries` VALUES (41, '������', 'greece.gif');
INSERT INTO `countries` VALUES (42, '���������', 'guatemala.gif');
INSERT INTO `countries` VALUES (40, '������������� ����������', 'dominicanrep.gif');
INSERT INTO `countries` VALUES (100, '�����', 'egypt.gif');
INSERT INTO `countries` VALUES (43, '�������', 'israel.gif');
INSERT INTO `countries` VALUES (26, '�����', 'india.gif');
INSERT INTO `countries` VALUES (13, '��������', 'ireland.gif');
INSERT INTO `countries` VALUES (61, '��������', 'iceland.gif');
INSERT INTO `countries` VALUES (102, '���� �� ������', 'jollyroger.gif');
INSERT INTO `countries` VALUES (22, '�������', 'spain.gif');
INSERT INTO `countries` VALUES (9, '������', 'italy.gif');
INSERT INTO `countries` VALUES (82, '��������', 'cambodia.gif');
INSERT INTO `countries` VALUES (5, '������', 'canada.gif');
INSERT INTO `countries` VALUES (78, '���������', 'kyrgyzstan.gif');
INSERT INTO `countries` VALUES (57, '��������', 'kiribati.gif');
INSERT INTO `countries` VALUES (8, '�����', 'china.gif');
INSERT INTO `countries` VALUES (52, '�����', 'congo.gif');
INSERT INTO `countries` VALUES (96, '��������', 'colombia.gif');
INSERT INTO `countries` VALUES (99, '����� ����', 'costarica.gif');
INSERT INTO `countries` VALUES (51, '����', 'cuba.gif');
INSERT INTO `countries` VALUES (85, '����', 'laos.gif');
INSERT INTO `countries` VALUES (98, '������', 'latvia.gif');
INSERT INTO `countries` VALUES (97, '�������', 'lebanon.gif');
INSERT INTO `countries` VALUES (67, '�����', 'lithuania.gif');
INSERT INTO `countries` VALUES (31, '����������', 'luxembourg.gif');
INSERT INTO `countries` VALUES (68, '���������', 'macedonia.gif');
INSERT INTO `countries` VALUES (39, '��������', 'malaysia.gif');
INSERT INTO `countries` VALUES (24, '�������', 'mexico.gif');
INSERT INTO `countries` VALUES (62, '�����', 'nauru.gif');
INSERT INTO `countries` VALUES (60, '�������', 'nigeria.gif');
INSERT INTO `countries` VALUES (69, '������������� �������', 'nethantilles.gif');
INSERT INTO `countries` VALUES (15, '����������', 'netherlands.gif');
INSERT INTO `countries` VALUES (21, '����� ��������', 'newzealand.gif');
INSERT INTO `countries` VALUES (11, '��������', 'norway.gif');
INSERT INTO `countries` VALUES (44, '��������', 'pakistan.gif');
INSERT INTO `countries` VALUES (88, '��������', 'paraguay.gif');
INSERT INTO `countries` VALUES (81, '����', 'peru.gif');
INSERT INTO `countries` VALUES (14, '������', 'poland.gif');
INSERT INTO `countries` VALUES (23, '����������', 'portugal.gif');
INSERT INTO `countries` VALUES (49, '������ ����', 'puertorico.gif');
INSERT INTO `countries` VALUES (3, '������', 'russia.gif');
INSERT INTO `countries` VALUES (73, '�������', 'romania.gif');
INSERT INTO `countries` VALUES (93, '�������� �����', 'northkorea.gif');
INSERT INTO `countries` VALUES (47, '����������� �������', 'seychelles.gif');
INSERT INTO `countries` VALUES (46, '������', 'serbia.gif');
INSERT INTO `countries` VALUES (25, '��������', 'singapore.gif');
INSERT INTO `countries` VALUES (63, '��������', 'slovenia.gif');
INSERT INTO `countries` VALUES (90, '����', 'ussr.gif');
INSERT INTO `countries` VALUES (2, '���', 'usa.gif');
INSERT INTO `countries` VALUES (48, '�������', 'taiwan.gif');
INSERT INTO `countries` VALUES (89, '�������', 'thailand.gif');
INSERT INTO `countries` VALUES (92, '����', 'togo.gif');
INSERT INTO `countries` VALUES (64, '������������', 'turkmenistan.gif');
INSERT INTO `countries` VALUES (54, '������', 'turkey.gif');
INSERT INTO `countries` VALUES (55, '����������', 'uzbekistan.gif');
INSERT INTO `countries` VALUES (70, '�������', 'ukraine.gif');
INSERT INTO `countries` VALUES (86, '�������', 'uruguay.gif');
INSERT INTO `countries` VALUES (58, '���������', 'philippines.gif');
INSERT INTO `countries` VALUES (4, '���������', 'finland.gif');
INSERT INTO `countries` VALUES (6, '�������', 'france.gif');
INSERT INTO `countries` VALUES (94, '��������', 'croatia.gif');
INSERT INTO `countries` VALUES (45, '�����', 'czechrep.gif');
INSERT INTO `countries` VALUES (50, '����', 'chile.gif');
INSERT INTO `countries` VALUES (56, '���������', 'switzerland.gif');
INSERT INTO `countries` VALUES (1, '������', 'sweden.gif');
INSERT INTO `countries` VALUES (79, '�������', 'ecuador.gif');
INSERT INTO `countries` VALUES (95, '�������', 'estonia.gif');
INSERT INTO `countries` VALUES (37, '���������', 'yugoslavia.gif');
INSERT INTO `countries` VALUES (28, '����� ������', 'southafrica.gif');
INSERT INTO `countries` VALUES (29, '����� �����', 'southkorea.gif');
INSERT INTO `countries` VALUES (38, '����� �����', 'westernsamoa.gif');
INSERT INTO `countries` VALUES (30, '������', 'jamaica.gif');
INSERT INTO `countries` VALUES (17, '������', 'japan.gif');

-- --------------------------------------------------------

-- 
-- ��������� ������� `descr_details`
-- 

CREATE TABLE `descr_details` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `typeid` int(10) default NULL,
  `sort` int(3) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `description` text NOT NULL,
  `input` enum('text','bbcode','option','links') default 'text',
  `size` int(3) NOT NULL default '40',
  `isnumeric` enum('yes','no') default 'no',
  `required` enum('yes','no') NOT NULL default 'no',
  `mask` text,
  `search` enum('yes','no') NOT NULL default 'no',
  `hide` enum('yes','no') default 'no',
  `mainpage` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=39 ;

-- 
-- ���� ������ ������� `descr_details`
-- 

INSERT INTO `descr_details` VALUES (1, 1, 1, '������������ ��������', '������������ (�����������) �������� ������, ���� ��� �� �������, �� ���� �� �����������.', 'text', 80, 'no', 'no', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (2, 1, 2, '��� ������', '��� ������ ������ �� ������.', 'text', 4, 'yes', 'yes', '', 'yes', 'no', 'yes');
INSERT INTO `descr_details` VALUES (3, 1, 3, '��������', '�������� ������', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (4, 1, 4, '� �����', '������, ������������ � ������', 'text', 100, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (5, 1, 5, '��� ��������', '����� ������������/������ ������ (������).', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (6, 1, 6, '�����������������', '����������������� ������ � ������� �:��:��', 'text', 6, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (7, 1, 7, '�������', '����, �� ������� ��������� �����.', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (8, 1, 8, '��������', '�������� (������ �� ���), �� <a target="_blank" href="http://www.kinofilms.com.ua">Kinofilms.com.ua</a>', 'text', 100, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (9, 1, 9, '������� IMDB', '������� ����� IMDB � �������: ������� (���-�� �������). ������: 9.0/10 (366) <br/><a target="_blank" href="http://www.imdb.com">������� �� IMDb</a>', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (10, 1, 10, '������� ����������', '������� ����������� ����� ���������.�� � �������: ������� (���-�� �������). ������: 5.143 (12)<br/><a target="_blank" href="http://www.kinopoisk.ru">������� �� Kinopoisk</a>', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (11, 1, 11, '������� ����<br /><a target="_blank" href="mpaafaq.php">��������� ���</a>', 'G - ��� ���������� �����������,PG - ������������� ����������� ���������,PG-13 - ����� �� 13 ��� �������� �� ���������,R - ����� �� 17 ��� ����������� ����������� ���������,NC-17 - ����� �� 17 ��� �������� ��������', 'option', 0, 'no', 'no', '[img][siteurl]/pic/mpaa/G.gif[/img] G - ��� ���������� �����������,[img][siteurl]/pic/mpaa/PG.gif[/img] PG - ������������� ����������� ���������,[img][siteurl]/pic/mpaa/PG-13.gif[/img] PG-13 - ����� �� 13 ��� �������� �� ���������,[img][siteurl]/pic/mpaa/R.gif[/img] R - ����� �� 17 ��� ����������� ����������� ���������,[img][siteurl]/pic/mpaa/NC-17.gif[/img] NC-17 - ����� �� 17 ��� �������� ��������', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (12, 1, 13, 'HTTP ������', 'HTTP ������ �� ��������������', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (13, 1, 14, 'FTP ������', 'FTP ������ �� ��������������', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (14, 1, 15, '�����', '���������� � �����: ����������, �����, ������� ������, �������, ', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (15, 1, 16, '�����', '���������� �� �����: ���������� �������, �����, ������� �������������, �������.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (16, 1, 17, '������ �����', '��������, AVI', 'text', 4, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (17, 1, 18, '�������� ���������', '', 'option', 0, 'no', 'yes', 'SATRip,DVDrip,CamRip,TeleSync,TeleCine,TVrip,HDTVrip,DVDscr,WorkPoint', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (18, 1, 12, '�������� ������', '', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (19, 2, 1, '������������ ��������', '������������ (�����������) �������� �������, ���� ��� �� �������, �� ���� �� �����������.', 'text', 80, 'no', 'no', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (20, 2, 2, '��� ������ ������', '��� ������ ������ ������', 'text', 4, 'yes', 'yes', '', 'yes', 'no', 'yes');
INSERT INTO `descr_details` VALUES (21, 2, 3, '��� ��������� ������', '��� ��������� ������ �������, ���� ������ ������� �� ���������, �� ����������� "�� ��������". ', 'text', 11, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (22, 2, 4, '�����', '����� ������.', 'text', 2, 'yes', 'yes', '', 'yes', 'no', 'yes');
INSERT INTO `descr_details` VALUES (23, 2, 5, '�������', '�������(�) �������.', 'text', 40, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (24, 2, 6, '� �����', '������, ����������� � �������.', 'text', 100, 'no', 'yes', '', 'yes', 'no', 'yes');
INSERT INTO `descr_details` VALUES (25, 2, 7, '��� ��������', '����� ������������/������ ������� (������).', 'text', 40, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (26, 2, 9, '���������� �����, ��������� ������', '���������� �����, ��������� ��� ���������� � ������ ������.', 'text', 2, 'yes', 'yes', '', 'yes', 'no', 'yes');
INSERT INTO `descr_details` VALUES (27, 2, 10, '�������', '����, �� ������� �������� ������. ���� ������ �� �������� �����, ����������� "��������".', 'text', 40, 'no', 'yes', '', 'yes', 'no', 'yes');
INSERT INTO `descr_details` VALUES (28, 2, 11, '��������', '�������� (������ �� ���), �� <a target="_blank" href="http://www.kinofilms.com.ua">Kinofilms.com.ua</a>', 'text', 100, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (29, 2, 12, '������� IMDB', '������� ����� IMDB � �������: ������� (���-�� �������). ������: 9.0/10 (366) <br/><a target="_blank" href="http://www.imdb.com">������� �� IMDb</a>', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (30, 2, 13, '������� ����������', '������� ����������� ����� ���������.�� � �������: ������� (���-�� �������). ������: 5.143 (12)<br/><a target="_blank" href="http://www.kinopoisk.ru">������� �� Kinopoisk</a>', 'text', 40, 'no', 'no', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (31, 2, 14, '������� ����<br /><a target="_blank" href="mpaafaq.php">��������� ���</a>', 'G - ��� ���������� �����������,PG - ������������� ����������� ���������,PG-13 - ����� �� 13 ��� �������� �� ���������,R - ����� �� 17 ��� ����������� ����������� ���������,NC-17 - ����� �� 17 ��� �������� ��������', 'option', 0, 'no', 'no', '[img][siteurl]/pic/mpaa/G.gif[/img] G - ��� ���������� �����������,[img][siteurl]/pic/mpaa/PG.gif[/img] PG - ������������� ����������� ���������,[img][siteurl]/pic/mpaa/PG-13.gif[/img] PG-13 - ����� �� 13 ��� �������� �� ���������,[img][siteurl]/pic/mpaa/R.gif[/img] R - ����� �� 17 ��� ����������� ����������� ���������,[img][siteurl]/pic/mpaa/NC-17.gif[/img] NC-17 - ����� �� 17 ��� �������� ��������', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (32, 2, 15, '�������� �������', '�������� ������� � ����� � ���������, ����������� ������������ ��� <b>spolier</b> ��� ������� ������� ������� ������.', 'bbcode', 0, 'no', 'yes', '', 'no', 'no', 'yes');
INSERT INTO `descr_details` VALUES (33, 2, 15, 'HTTP ������', 'HTTP ������ �� ��������������', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (34, 2, 16, 'FTP ������', 'FTP ������ �� ��������������', 'links', 0, 'no', 'no', '', 'no', 'yes', 'no');
INSERT INTO `descr_details` VALUES (35, 2, 17, '�����', '���������� � �����: ����������, �����, ������� ������, �������, ', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (36, 2, 18, '�����', '	���������� �� �����: ���������� �������, �����, ������� �������������, �������.', 'text', 80, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (37, 2, 19, '������ �����', '������ ������, �������� AVI.', 'text', 4, 'no', 'yes', '', 'no', 'no', 'no');
INSERT INTO `descr_details` VALUES (38, 2, 20, '�������� ���������', '', 'option', 0, 'no', 'yes', 'SATRip,DVDrip,CamRip,TeleSync,TeleCine,TVrip,HDTVrip,DVDscr,WorkPoint', 'no', 'no', 'no');

-- --------------------------------------------------------

-- 
-- ��������� ������� `descr_torrents`
-- 

CREATE TABLE `descr_torrents` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) NOT NULL,
  `typeid` varchar(30) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `torrent` (`torrent`,`typeid`),
  FULLTEXT KEY `value` (`value`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `descr_torrents`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `descr_types`
-- 

CREATE TABLE `descr_types` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` varchar(30) default NULL,
  `category` int(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=3 ;

-- 
-- ���� ������ ������� `descr_types`
-- 

INSERT INTO `descr_types` VALUES (1, '������', 1);
INSERT INTO `descr_types` VALUES (2, '�������', 2);

-- --------------------------------------------------------

-- 
-- ��������� ������� `faq`
-- 

CREATE TABLE `faq` (
  `id` int(10) NOT NULL auto_increment,
  `type` set('categ','item') NOT NULL default 'item',
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `flag` tinyint(1) NOT NULL default '1',
  `categ` int(10) NOT NULL default '0',
  `order` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=75 ;

-- 
-- ���� ������ ������� `faq`
-- 

INSERT INTO `faq` VALUES (1, 'categ', '� �����', '', 1, 0, 1);
INSERT INTO `faq` VALUES (2, 'categ', '���������� ��� �������������', '', 1, 0, 3);
INSERT INTO `faq` VALUES (3, 'categ', '����������', '', 1, 0, 4);
INSERT INTO `faq` VALUES (4, 'categ', '�������', '', 1, 0, 2);
INSERT INTO `faq` VALUES (5, 'categ', '�������', '', 1, 0, 5);
INSERT INTO `faq` VALUES (6, 'categ', '��� � ���� ��������� ���� �������� ��������?', '', 1, 0, 6);
INSERT INTO `faq` VALUES (7, 'categ', '��� ��������� ���������� ������. ��� ��� ������?', '', 1, 0, 7);
INSERT INTO `faq` VALUES (8, 'categ', '������ � �� ���� ������������? ���� �������������?', '', 1, 0, 8);
INSERT INTO `faq` VALUES (9, 'categ', '���� ������ �� ��� ������ ����� ���..? ', '', 1, 0, 9);
INSERT INTO `faq` VALUES (10, 'item', '��� ����� ������� (bittorrent)? ��� ��������� �����?', 'Check out <a class=altlink href="http://www.btfaq.com/">Brian''s BitTorrent FAQ and Guide</a>', 1, 1, 1);
INSERT INTO `faq` VALUES (11, 'item', '�� ��� ����������� ������ �� �������������?', '�� ����� ���������� ���������� ������ ��� ���������� � ����� ������� ������. �� ������ ������ ������ ���� �� ������ ��������.', 1, 1, 2);
INSERT INTO `faq` VALUES (12, 'item', '��� � ���� ������� ��������� ����� ������?', '�� ������ ����� �� �� <a href="http://dev.kinokpk.com" class=altlink_white>Dev.Kinokpk.com</a>.', 1, 1, 3);
INSERT INTO `faq` VALUES (13, 'item', '� ��������������� �������, �� �� ������� ������ � �������������� �� e-mail!', '�������������� <a class=altlink href=delacct.php>���� ������</a>, ����� ������� ������� � �������������������.\r\n�������� ��������, ���� � ������ ��� ������������� �� e-mail �� ������, ��, ��������, �� ������ ��� ��� ���� �� ������. ���������� ������������ ������ e-mail �����.', 1, 2, 1);
INSERT INTO `faq` VALUES (14, 'item', '� ����� ��� ������ �������� ��� ������! �� ����� �� �� �������� �� ���?', '����������, �������������� <a class=altlink href=recover.php>���� ������</a>, ����� ������ ����������� ���� ������� ��� �� E-mail.', 1, 2, 2);
INSERT INTO `faq` VALUES (15, 'item', '�� ����� �� �� ������������� ��� ������� ������?', '�� �� ��������������� ��������. ����������, �������� �����. (�������������� <a href=delacct.php class=altlink>���� ������</a>), ����� ������� ���������� �������.', 1, 2, 3);
INSERT INTO `faq` VALUES (16, 'item', '��� �� �� �� ������� ��� �������������� �������?', '�� ������ ������� ��� ����, ��������� <a href=delacct.php class=altlink>��� �����</a>.', 1, 2, 4);
INSERT INTO `faq` VALUES (17, 'item', '� ��� ����� ��� ������� (ratio)?', '���� ��������� <b>BitTorrent</b> ����������� � ���, ��� <b>������</b> ������������ <b>�� ������ ���������, �� � ������������ ������� ������ ������������� ��, ��� ��� ����� �������</b>. ���� �� �� ������� ��� ������� ����� ����, ��� ������������ �������� ����������� � �� ��, �� ������ ��������� ������� �� ������ ������ �������� ���� ����������� �������� �������.\r\n���� �� ����� 1, �� �� ������ ����� �������, ������� �������.<br>\r\n�� ������� ������������� ������� �� ������ ������� � ����� �������� � ����� <a class=altlink href=my.php>�������</a>.<br>\r\n<br>\r\n������� � ����� ������ ����. ���� � ��������� ��������� � � ���������� ��� ��������� ��������� � �������, ���, ��� ��� ������ ����������� ���������� ����� ��������������. ��� ������� ������ ���� �� ���� 1.<br>\r\n<br>\r\n�� �������� ������� � ����� ��������, ������ ��������� ����������� � ������, �� ������ ������� ��� �������:\r\n<br>������ (---), ����������, ��� ��� ������� �� �������� ��� ����� 0.<br> ������ (INF), ����������, ��� �� ������ ������ � ������� �������� �� �������� �.�. �� ����� �������������. \r\n', 1, 2, 5);
INSERT INTO `faq` VALUES (18, 'item', '������ ��� IP ������������ �� �������� � ��������?', '������ �� � ���������� ����� �������� ��� IP � email. ������� ������������ �� ����� ������ ��� ����������.', 1, 2, 6);
INSERT INTO `faq` VALUES (19, 'item', '��������! � �� ���� ����� (������������)!?', '������ ��� �������� ��������� ��-�� ������ Internet Explorer. �������� ��� ���� Internet Explorer`� � �������� Internet Options � ������ ����������. �������� �� ������ Delete Cookies. ��� ������ ������.\r\n', 1, 2, 7);
INSERT INTO `faq` VALUES (20, 'item', '��� IP-����� ������������. ��� ��� �������, ����� ���� ������������?', '��� �� ����� ������ ������. �� ��� ��� ����� - ��� ���������, ��� �� ����� (������������) � ������� IP- �������, ����� ��������� ����� �������-������ (��������� ����� ��������/�������). ����� �����, ���� ���� ��� IP ���������� � ������� ������, ���������� ��� ������� �����������, � ���������� ��������� �������������', 1, 2, 8);
INSERT INTO `faq` VALUES (21, 'item', '������ ��������, ��� � �� ���� ������������? (� ����� ���� ��� ���� �������� �����)(� � ��� ��� ���� ������������?)', '������ ���������, ��� � ��� ������� (firewall) ��� NAT, � �� �� ������ ��������� �����������.\r\n<br>\r\n<br>\r\n��� ��������, ��� ������ ��������� �� ������ ������������ � ���, ���� ������ �� � ���. �������� �����, ��� ��� ����, ��� � ��������� ������� �� ���� ����������� ���� � ������, ���� ���-���� �� ��� �� ������� ����.\r\n<br>\r\n<br>\r\n��� ������� ������ �������� �������� �����, ������������ ��� �������� ���������� (����� ��, ��� � � ���������� ������ �������) � �������� �/��� ��������� ��� NAT ������. (���������� � ������������ � ������ ������� ��� �� ����� �������������). ��� �� �� ������ ����� ������ ���������� �� ������� <a class=altlink href="http://portforward.com/">PortForward</a>).', 1, 2, 9);
INSERT INTO `faq` VALUES (22, 'item', '����� ������ ������������� ������������ �� ����� �������?', '<table cellspacing=3 cellpadding=0>\r\n<tr>\r\n<td class=embedded width=100 bgcolor="#F5F4EA">&nbsp; <b><font color="#000000">������������</b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>����������� ����� ������������. ������ �����.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><font color=''#996699''>�����������</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>������� ������������. ����� ������������ NFO-�����, � ����� ������ �� 10 ��������� ������������.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><img src="pic/star.gif" alt="Star"></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>����� ������������ ������������</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded valign=top bgcolor="#F5F4EA">&nbsp; <b><font color=''green''>VIP</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded valign=top>������� ������������. �� �������� ��������������� ��������� � �����.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><font color="orange">�������</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>������������ � ������� �������.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded valign=top bgcolor="#F5F4EA">&nbsp; <b><font color="purple">���������</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded valign=top>������������� ������������� �������. ����� ������������� � ������� ������� � �����������, �������� � �������� � �����, �������� ��������������.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><font color="red">�������������</color></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>����� ���, ��� ������. ��������� ���� �������������, ��� ������ � �������������� ���� ������.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><font color="#0F6CEE">SysOp</color></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>���������� �������.</td>\r\n</tr>\r\n</table>', 1, 2, 10);
INSERT INTO `faq` VALUES (23, 'item', '��� �������� ��� ������?', '<table cellspacing=3 cellpadding=0>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA" valign=top width=100>&nbsp; <b><font color="#996699">�����������</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded valign=top>������ ���� ���������������, ��� �������, 4 ������, ��������� �� ����� 25�� � ����� ������� ����� 1.05.<br>\r\n��� ���������� ������������ ����������, ����� ������������� ������ � �����. �������� ��������������� ��������� ��� ������� �������� �� ������� ����� 0,95.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><img src="pic/star.gif" alt="Star"></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>���� ������������� ������ ������, �� �������� ���� ������������� ����</a></td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA" valign=top>&nbsp; <b><font color=''green''>VIP</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded valign=top>�������� �� ������ ������� ����� ��������.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><font color="orange">�������</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>�� ������ ��������� ���������� � ���������� � ���� ������ ������ ��� ���������� ��������� �������: �� ���������������� ����� 8 ������, �� ��������� ����� 25 ��, ��� ������� ���� ������� 1,05.</td>\r\n</tr>\r\n<tr>\r\n<td class=embedded bgcolor="#F5F4EA">&nbsp; <b><font color="purple">���������</font></b></td>\r\n<td class=embedded width=5>&nbsp;</td>\r\n<td class=embedded>����������� ��������������. �� ����� ������� ���, �� ���� ��� ��������.</td>\r\n</tr>\r\n</table>', 1, 2, 11);
INSERT INTO `faq` VALUES (25, 'item', '������ ��� �������� �� ����� ������������������?', '������ �������� ����� �������������. ��������, ���������� � ������� ����� 28 ����, ������������� ���������, ��� ��� ����� ��������� �����. (� ��� ��� ������� �������������� ����, ��� �������, �� ����������� ��� �� ����!)\r\n', 1, 2, 12);
INSERT INTO `faq` VALUES (26, 'item', '��� ��� �������� ������ � ���� �������?', '��� ������ ������� ��������, ������� ��� �����������, � ���������� ��� <a class=altlink href=rules.php>�������</a>. ����� ��� ���������� ����� �����, ����� ���������� ��, �������� <a class=altlink href=bitbucket-upload.php>BitBucket</a>, <a class="altlink" href="http://photobucket.com/">Photobucket</a>,\r\n<a class="altlink" href="http://uploadit.org/">Upload-It!</a> ���\r\n<a class="altlink" href="http://www.imageshack.us/">ImageShack</a>).\r\n����� ���� ��� �� ���������� ���� ��������, ��� ���� ������ ����������� URL, ������� ��� ������ ��� ���������� ����� � ���� ������ � <a class="altlink" href="usercp.php">�������</a>.', 1, 2, 13);
INSERT INTO `faq` VALUES (27, 'item', '�������� ����� ������������� ������� ������������ ����������.', '<ul>\r\n<li>���� - �����. (��� "������� ���")</li>\r\n<li>������ ���������� � �� ��������. ������ ������������ ���������� ������ ��������, ���� ������ �� ���������� �����. (������������ ������� ���� �������������� ������� ���������� �������� �� �������������.)</li>\r\n<li>�� ����������� ������/����������� ������. ���� �� ������ ������������ ����������������� ������, ����������� � �� ���� ����� � ����.</li>\r\n</ul>', 1, 3, 1);
INSERT INTO `faq` VALUES (28, 'item', '�������� ������.', '<ul>\r\n<li>���� �������, ������� �� ����������/�������, �� ��������� � ������ ����� ������� ������ ���������, ��� �������� �������� �������.</li>\r\n<li>���������, ��� �� ��������� ������� ��� ������, � ������ ������� "event=completed".</li>\r\n<li>���� ������ ����, � ����� �� ����������� �������. ���� ��� ������� �� ����, ��� �� ������� �� �������, ���������� ��������� �������������.</li>\r\n</ul>', 1, 3, 2);
INSERT INTO `faq` VALUES (29, 'item', '����� �� ������������ ����� �������-�������?', '��. �� ������ ������ ������ ��������� ���������� ���������, ��� ������������� ������ �������-�������. (����� ���������� �������) ��� �� ����� �� ����������� <b>�� ������������</b> ��������� �������:br>\r\n<ul>\r\n<li>BitTorrent++</li>\r\n<li>Nova Torrent</li>\r\n<li>TorrentStorm</li>\r\n</ul>\r\n��� ������� ������� ������������ ������/��������� �������-������. ���� �� �� �����������, �������� ��������, ����� � ������� �������� ����� ����������� ���� ����� ���������� ��������, ��� �������� �������.<br>\r\n<br>\r\n��� ��, �� ������������� ������������ ������� �����(alpha) ��� ����(beta) ������.', 1, 3, 3);
INSERT INTO `faq` VALUES (30, 'item', '������ �������, ������� � ��������/������, ������������ ��������� ��� � ���� �������?', '���� �� ��������� �������� (�������� ���������� ������������ ����������, ��� ��������� �������) ��� ������ �������� ������ �����������, � �� ������������� ���, ��� ����� ����� ����� "peer_id", ����� ������� ���� ������� ����� ��������, ��� �����(������) �������. � �� ������� �������� ������ ��� ������� � �� ������� "event=completed" ��� "event=stopped", � ����� ���������� ��� ��������� ����� � ������ ����� �������� ���������. �� ��������� �� ��� ��������, � �������� ����� ���� ��������.', 1, 3, 4);
INSERT INTO `faq` VALUES (31, 'item', '� �������� ��� ������� �������. ������ � ���� ������� �� ��� ��� ������������?', '��������� �������, �������� TorrentStorm � Nova Torrent �� ���������� ������� ��������� � ����������� ��� ������ ��������. � ����� ������� ������ ����� ����� ��������� �� ������ �������, � ���������� ��� �� ���������� ��� �������� ��� ��������� �����. �� ��������� ��������, ����� ��������� ����� ������� ���-���� �������� �� ������ ����� �������� ���������.', 1, 3, 5);
INSERT INTO `faq` VALUES (32, 'item', '������ ������ � ���� �������� ������������ ��������, ������� � ������� �� �����!?', '����� ����������� ������-������ ������ ���������� passkey ��� ��������� ������������. �������� ���-�� �����/����� ��� �������. ����������� ������� ��� � ���� ������� ���� ����� ���������� �����. ������, ��� ����� ����� ������� ��� �������� ���������� ��� �������� ��������.', 1, 3, 6);
INSERT INTO `faq` VALUES (33, 'item', '��������� IP (���� �� � ��������� � ������ �����������)?', '��, ������ ������������ ��������� ������ � ������ IP ��� ������ �����������. ������� ������������� � ������������� � ��� ������, ����� �� �������� �������, � ������ � ���� ������ IP �����. ����� �������, ���� �� ������ ���������/��������� � ���������� � � ���������� � ��������� ���� � ��� �� �������, ��� ���������� ������������ �� ���� � ���������� �, ��������� �������, � ����� ��������� �� �� ����� � ��������� � (2 ���������� ������������ ������ ��� �������, ����������� �� ���������� ���. ������� - ��������� ��� ���� �� ������ �� ����������). ��� �� ����� ���������������� ������, ����� �� ���������� ������.\r\n', 1, 3, 7);
INSERT INTO `faq` VALUES (34, 'item', '��� NAT/ICS ����� ��������� �������?', '� ������ ������������� NAT ��� ���������� ��������� ������ ��������� ��� �������-�������� �� ������ �����������, � ������� NAT ������� � �������. (����������� ��������� �������� ������� �� ����� ������� FAQ`�, ������� ���������� � ������������ � ������ ������� �/��� �� ����� ������������). ����� � ����� ��� ����������� ��������������� ������� �� ������ ����������. ��� ��������� ������������ �������� �� ���� ����� � ����. �� ������ ��������� � ������� �� NAT`�� ������������� ��������������� �� �����.', 1, 3, 8);
INSERT INTO `faq` VALUES (73, 'item', '������ ��������, ��� ���������� �����������, ���� � �� ��������� NAT/Firewall?', '��� ������ ���������� ��������������� ������������ ������� ����������� ������ ��������� IP, ������, ��� ����������, ����� ������ ������� ��������� HTTP_X_FORWARDED_FOR. ���� ������ ������ ���������� ����� �� ������ - ���������� ���������: ������ �������������� IP ������, ��� ��� �����������. � ����� �� ��������� ����� �� ������, �� �������� ���������� � ����� ��������, ��� �� ����������, ������ �� �� �� NAT/firewall, ������ �� ����� ���� �� ������������ � ������-�������, �� �����, ������� �� ������� � ���� �������. �.�. ������ ������ �� ��������� �� ������� �����, ������������� ���������� �� ����� �����������, � ������ ����� ������, ��� �� �� �����/�������', 1, 7, 3);
INSERT INTO `faq` VALUES (36, 'item', '������ � �� ���� ���������?', '������ ����������� ������������ (<font color="orange"><b>��������</b></font>) ����� ����� �������� ��������.', 1, 4, 1);
INSERT INTO `faq` VALUES (37, 'item', '��� ��� ���� �������, ����� ����� <font color="orange">���������</font> ?', '��� ���������� �������� �� � ��������������� �������� <a class=altlink href=staff.php>�������������</a>. ����� ����, ��� ������������� ����������� � ���, �� ������ ������� � ����� ���������.\r\n\r\n<br><br><b>���������� � ����������:</b>\r\n<li>����� �������� ������� ����� 25 ��/�</li>\r\n<li>�� ������ ���� ������ ���������� ������� ��� ������� 24 ���� ��� �� ��������� 2-� ���������.</li>\r\n</ul>\r\n', 1, 4, 2);
INSERT INTO `faq` VALUES (38, 'item', '���� �� � ��������� ���� �������� �� ������ ��������?', '���. � ��� ��������, � ������������ ����������� ������������� ����������. ������ ������������������ ������������ ����� ����� ������������ ������ ������. ���������� ����� ��������� �� ������ �������� ������� � ����, ��� ������������, ������� ������� �������-���� (�� ������ �������), �� ������ ����������� � ����� ��������.<br>\r\n<br>\r\n(������ �� ������ ������������ ��������� ��������� ��� ��� ������������������. �� � ����� ������ ������ ������� �������-����, ���������� ��� �� ������ ������� � ������� ���).\r\n', 1, 4, 3);
INSERT INTO `faq` VALUES (39, 'item', '��� ��� ������������ �����, ������� � ��������?', '������ ����� ����� �� ������ � ���� <a class=altlink href=formatss.php?form=mov>��������</a>.', 1, 5, 1);
INSERT INTO `faq` VALUES (40, 'item', '� ������ �����, �� �� �������, ��� �������� ��� CAM/TS/TC/SCR ?', '���������� <a class=altlink href=formatss.php?form=mov>�����</a>.', 1, 5, 2);
INSERT INTO `faq` VALUES (41, 'item', '������ �������, ������ ��� ������ ��������, ����� �����!?', '�� ��� ����� ���� ��������� ������:<br>\r\n(<b>1</b>) ������� �� �������������� <a class=altlink href=rules.php>��������</a>.<br>\r\n(<b>2</b>) �������� ��� ������� ���, �.�. ����������, ��� ��� ��� ������ �����. ������ ����� �� ����� ������ �� ������.<br>\r\n(<b>3</b>) �������� ������������� ��������� �� ��������� 28 ����.', 1, 5, 3);
INSERT INTO `faq` VALUES (42, 'item', '��� ����� ���������� ����������/�������, ���� �������� ��� � ������ � ��� ������� (��-�� �����, ��� ��-�� ����� �������, � �.�.)?', '�������� *.torrent ����. ����� ��� ������ ������� ���� ��������� - �������� ���� � ��� ������������ ������. ���������� ������������ ������.\r\n', 1, 5, 4);
INSERT INTO `faq` VALUES (43, 'item', '������ ��� �������� ������ ��������������� �� 99%?', '� ��� ��� ������� ���������� ������� ���������� ������, � ������ �������� ����� �������������, � ������� ���� �����, ������� � ��� �� �������, ��� ������� � ��������. ������� �������� ������ ����� ��������������� � ��� ������, ����� �� ���������� �������� ����� ��������� ���������. ��������� ��������, � � ������ (�� ��� �� ����� :) ) ������� ������ �������� ��� ����������� �����. ��� �� ����� ���� ����� ��������� ��� ������������� ��������� ��������.', 1, 5, 5);
INSERT INTO `faq` VALUES (44, 'item', '��� ������ ��������� "a piece has failed an hash check"?', '�������-������� ��������� �������� ������ �� �����������. ����� ����� �������� � �������� ��� ������������� ����������� ������. ��� ���������� ����������� � ����, ��� ��� �� ������������.<br>\r\n<br>\r\n� ��������� �������� ���� ����������� ������������� ������������ �������������, ������� ��������� ��� ����� � ��������. ���� �� ������, ����� � ���������� �� �� ��������� ������ �� ����� ������������, ��� ���������� �������������� ������ ������� � ����� �������.', 1, 5, 6);
INSERT INTO `faq` VALUES (45, 'item', '������ �������� 100��. ��� � ��� ������� 120��?', '�������� ���������� �����. ���� ��� ������ ������� ����� � ��������, �� ������������ �. ����� �������, ����� ���������� ���������� ����� ���� ������, ��� ������ ��������.', 1, 5, 7);
INSERT INTO `faq` VALUES (46, 'item', '������ ��� �������� ������ "���������! ����� �������� ����� xxx �����"?', '����� �� ������� ���������� <b>�����</b> �������, ��������� ������������ ������ ��������� �����-�� ���������� ������� ������ ��� ��� ������ ��� �������. ��� �������� ������ ������������� � ������ ���������, � ������������� � ��������� ����������� ��������� �������.<br>\r\n<br>\r\nThis applies to new users as well, so opening a new account will not help. Note also that this\r\nworks at tracker level, you will be able to grab the .torrent file itself at any time.<br>\r\n<br>\r\n<!--The delay applies only to leeching, not to seeding. If you got the files from any other source and\r\nwish to seed them you may do so at any time irrespectively of your ratio or total uploaded.<br>-->\r\nN.B. Due to some users exploiting the ''no-delay-for-seeders'' policy we had to change it. The delay\r\nnow applies to both seeding and leeching. So if you are subject to a delay and get the files from\r\nsome other source you will not be able to seed them until the delay has elapsed.', 0, 5, 8);
INSERT INTO `faq` VALUES (47, 'item', '������ ����������� ������ "rejected by tracker - Port xxxx is blacklisted', '��� �������-������ ������� �������, ��� �� ���������� ����� �� ��������� ��� �������� (6881-6889), ��� ����� ��������� � ������� p2p �����������.<br>\r\n<br>\r\n��� �������-������ ������� �������, ��� �� ���������� ����� �� ��������� ��� �������� (6881-6889), ��� ����� ��������� � ������� p2p �����������. <br>\r\n<br>\r\n�����, ������� ������ ����������� �� ������ ���������� � ������ ����, �� �� ����, ��� ��� ��������� ��������� ������ ��:<br>\r\n<br>\r\n<table cellspacing=3 cellpadding=0>\r\n  <tr>\r\n    <td class=embedded width="80">Direct Connect</td>\r\n    <td class=embedded width="80" bgcolor="#F5F4EA"><div align="center">411 - 413</div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class=embedded width="80">Kazaa</td>\r\n    <td class=embedded width="80" bgcolor="#F5F4EA"><div align="center">1214</div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class=embedded width="80">eDonkey</td>\r\n    <td class=embedded width="80" bgcolor="#F5F4EA"><div align="center">4662</div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class=embedded width="80">Gnutella</td>\r\n    <td class=embedded width="80" bgcolor="#F5F4EA"><div align="center">6346 - 6347</div></td>\r\n  </tr>\r\n  <tr>\r\n    <td class=embedded width="80">BitTorrent</td>\r\n    <td class=embedded width="80" bgcolor="#F5F4EA"><div align="center">6881 - 6889</div></td>\r\n </tr>\r\n</table>\r\n<br>\r\n����� ������������ ��� ������ �� ������ ��������� ��� ������ �� ������������� ������ ������ (���������� � ��������� 49152-65535).  �������, ��� ����� �������, ��� Azureus ���������� ���� ���� �� ��� ��������, � �� �����, ��� ����������� ������ ���������� ������ ����� �� ������ �������. ��� ����� ������������ ��� ���������� ����� ��������������, � �� ����� �������� � ��������! ��� ������ ��������� ����� �� ��������� �� ����� ����������� ������������ ������ �������, � �� ������ ������� ��� ���� �������� ��� �������� �������� ����� ���� � ������� �����������. ��� ������ ��� �� ������ ����������� � ������ �������������, � ������� � ���������� ����������� ����������� �����.<br>\r\n<br>\r\n�� ����������� � ��� � �� ������, ����� ����� ��� ���������� �������. ��� ������ ������� ������, ������� ���������� ������������, ��� ������ ������ � ����������� ��������� � ������� �����.\r\n', 1, 5, 9);
INSERT INTO `faq` VALUES (48, 'item', '��� ����� "IOError - [Errno13] Permission denied"?', '���� �� ������ ������ ������ ��� �������� - ������������� ���������, ��� ������ ������. ���������� ������ ������.<br>\r\n<br>\r\nIOError �������� ������ ����-������, � ��� ������ ����� �������(����������) � �� �������. ��� �����������, ����� ������ �� ��������� �������� �� ����� ������� ��������� �����. �������� ��������� ������� - ���������� ������������ 2 �������: ��� ����� �����������, ��������, ���� �� ������� ������, �� �� ����� ���� �� �� ��������, � ��������� �������� � ����, ����� �� ��������� ������ ����� �������, �� ������ �� ��� ��������� �����, ������ �� ����� �������� � ��� ������, � ���������� ��� ��� ������.<br>\r\n<br>\r\n�������� ������ ������ - ��� ��������� FAT-������� ����� �������� �������, ��� ����� �������� � ��������������� ����������� ������. �������������� ����� ����������� ����� ������. (��� ����� ��������� ���� �� ����������� Windows 9x - ������� ������������ ������ FAT, ��� � ����� NT/2000/XP ���� �������������� ������ � FAT. NTFS ����� ������� �������� �������, � �� ������ ��������� � ����� �������).', 1, 5, 10);
INSERT INTO `faq` VALUES (49, 'item', '��� ����� "TTL" �� ���������?', '��� ����� ����� ����������� ��������. ���������� ����� ����� ����� ������� ����� ����� � ������� (��, ���� ���� �� ����� ��������!). �������, ��� ��� ������������ ��������, ������� ����� ���� ����� � ����� �����, ���� �� ���������.', 1, 5, 11);
INSERT INTO `faq` VALUES (50, 'item', '�� �������������� �� ����� ��������', '��������, ���� � ��� ��������� ��������. ����� ������� � ������ ������� ����� � �������� ��������, ������� ����� ����� ��������� ���, � ��� ����� � ���.<br>\r\n<br>\r\n��������� ����� ��� ���������� ��������� �������� � �������� ����� ��������, ������ � ���� ������ SLR ��������� ������ ������, � �� ������ ������ � ������������ ���������. (������ � ���� ������ � ��� �� ����� ����������� ��������� ��� �����, ��� ���� �� �� ������� ������ ���� � ������ ������. ��� ��� �������: ���������� ������������� ����� ����� 2�� ����������.', 1, 6, 1);
INSERT INTO `faq` VALUES (51, 'item', '���������� ���� �������� �������', '�������� ������� ����� ��������� ����������� �� �������� �������� � ���� �������:<br>\r\n<ul>\r\n      <li>������� ��������� ����� ��������� � ����, ����� �������� ���, ��� �� ������. ��� �������� ��� ���� � � � ��������� ���� � ��� �� ����, � � �������� ������ � � ������� ���������, ����� � ����� ��������� ���������� ����. ����� ������� ������� �������� ������� ���� � ������� �������� ��������.</li>\r\n      <li>������, ����� � �������� ���-�� � �, �� ������ ������� �, ��� ��������� ������ ���� ������� ��������. (��� ���������� �������������(acknowledgements) - ACKs - ��� ���� �� ����� ��������� "��������!"). ���� � �� ���� ��������� ����� ����� �, ����� �� (�) ������������ ������� ��� (�) � ����� �����. ���� � ������ �� ������ �������� ����� ��������� ���, ��� ������������� (ACKs) ����� �������������. ����� ������� ������� �� ������ �������� ������� � �������� �������� ��������.</li>\r\n</ul>\r\n\r\n���������� ���������� �� ����������, ���������� ����� ����� 2�� ��������. �������� ������� ������ ���� ����������� �������, ��� ������� ACKs �������� ��� ��������. <b>��������� ������� - ��� ���������� ���� �������� ������� �� 80% �� ������������ ���������.</b> ������ ��� ����� ������������ ����� ������ ���������, ������� ����� ��������� ��������� ������ � ����� ������. (�������, ��� ����������� ������ �������� ������� ������������ ����������� �� ����� ��������(ratio)). <br>\r\n<br>\r\n��������� ������� (����. Azureus) ������������ ����� �������� �������, ������ (����. Shad0w`s) ��������� ���������� ������ �������. ������� ��� ������, � ������������� � �������� �������, � �� �����, ��� �� ����������� ���� ����� ��� ����-�� ��� (�������, ��� � �.�.).', 1, 6, 2);
INSERT INTO `faq` VALUES (52, 'item', '���������� ���������� ������������� ����������', '��������� ������������ ������� (����� ��� Windows 9x) �� ����� ������ ������������ ������� ���������� ����������, � ���� ����� ���������. ��� ��, ��������� �������� ������� (�������� ����� ������� NAT �/��� ������� � ������ ��-���� ������������) ����� ������� �������� ��� �������� ����� ������� ����� ����������. �� ���������� ������� ���������������� ��������, ������ �� ������ ����������� ����������, ����������������� ����, ���������� ������������� ���������� � �������� �� 60 �� 100. ���������� ���������, ��� ��� �������� �����������, �.�. ���� � ��� �������� ��������� ��������, ���������� ���������� ����� ����� ����� ����� ���������� ������� �� ���.', 1, 6, 3);
INSERT INTO `faq` VALUES (53, 'item', '���������� ���������� ������������� ������', '� ����� ��� �� �� �� �����, � ��� ������� ����? ���. ����������� ���������� ���������� �������� ����������� ���������� ����������, � �������� ��� ������ ������������ �/��� � ������� ���������. ����������� ������� ������������� ����������� ���������� ����������, ������� �� �������. ��������� �������� ����� ��������� ������ ������� ����, ��� ���������� ������������� ����������, � ��������������� ������� �� �������� ������ (�����������) �����������.', 1, 6, 4);
INSERT INTO `faq` VALUES (54, 'item', '��������� �������', '��� ������� ����, ������ ��������� ��������� � ������ ������� �������� � ����, ��� �� ������. ����� �� ������ ��������� ��������� ����� ����, � ��� ������ ���������� ������ ����������, � ��� ����� ������������ ���. ��� ������� � ����, ��� � ������ �������� �������� ����� ���������� ������, ��������, ���� �� ��������� �� � ����� ��� � ����� ����� ����������� �������. �������� �������� ������ ����������� ��� ������ � ��� �������� ��������� ������ ��� �������.', 1, 6, 5);
INSERT INTO `faq` VALUES (55, 'item', '������ �������� ��� �������� �����������, ����� � ����� ���-��?', '���� �������� �������� ����� �������� �������� (������� �� ������ ����������, ������ � �.�. � �.�.). ���� �� �������� �������� �������� ��� ������� � ����, ��� ��� ����� ����� �������� �� ���������, � ������������� ������� ����� ����� ���������. ������, �� ������ ���������� �������� �������� � ����� �������. � ��� �� �� ������ ������������ ������ ��������� ��� ����������� �������� ������ ����������� ����������, �������� ��� ������ <a class=altlink href="redirector.php?url=http://www.netlimiter.com/">NetLimiter</a>.<br>\r\n<br>\r\n(������� ���� ������ ��� �������, �� �� ����� ������� ����� ����������� ��� ���� ����� ��������, ���������� ����-���� �� http/ftp, � �.�.)', 1, 6, 6);
INSERT INTO `faq` VALUES (56, 'item', '��� ����� ������ (proxy)?', '����� �������, ��� ��� ���������. ����� �� ������� �� ���������, ������-������ �������� ��� ������, � �������������� ��� �� ����, � �������� �� ������ ������������. ������ ��������� ������� ������ (������������ ������ �� �����������):<br>\r\n<br>\r\n\r\n\r\n<table cellspacing=3 cellpadding=0>\r\n <tr>\r\n    <td class=embedded valign="top" bgcolor="#F5F4EA" width="100">&nbsp;����������</td>\r\n    <td class=embedded width="10">&nbsp;</td>\r\n    <td class=embedded valign="top">���������� ������ �� ������� �������� ��������. ��� �������� ���� ��������������� ��������������� �������� � 80�� ����� �� ������. (������ ������������ ��� ������� �� ��������� ������.)</td>\r\n </tr>\r\n <tr>\r\n    <td class=embedded valign="top" bgcolor="#F5F4EA">&nbsp;�����/����������</td>\r\n    <td class=embedded width="10">&nbsp;</td>\r\n    <td class=embedded valign="top">�� ������ ��������� ���� �������, ��� �� ������������ ��.</td>\r\n </tr>\r\n <tr>\r\n    <td class=embedded valign="top" bgcolor="#F5F4EA">&nbsp; ���������</td>\r\n    <td class=embedded width="10">&nbsp;</td>\r\n    <td class=embedded valign="top">������ ��� ������ �� �������� ������ �� ������� �� ������ (��������� HTTP_X_FORWARDED_FOR �� ����������; � ������ �� ����� ��� IP.)</td>\r\n </tr>\r\n <tr>\r\n    <td class=embedded valign="top" bgcolor="#F5F4EA">&nbsp; ����� ���������</td>\r\n    <td class=embedded width="10">&nbsp;</td>\r\n    <td class=embedded valign="top">������ �� �������� �� ������ �� ���������� � �������, �� ���������� � ������ (��������� HTTP_X_FORWARDED_FOR, HTTP_VIA � HTTP_PROXY_CONNECTION �� ����������; ������ �� ����� ��� IP � �� ����� ��� �� ����������� ������).</td>\r\n </tr>\r\n <tr>\r\n    <td class=embedded valign="top" bgcolor="#F5F4EA">&nbsp;Public</td>\r\n    <td class=embedded width="10">&nbsp;</td>\r\n    <td class=embedded valign="top">(Self explanatory)</td>\r\n </tr>\r\n</table>\r\n<br>\r\n���������� ������ ����� ����, � ����� � �� ���� ����������. � ���� ������� ��������� ������ ����� ��������� ������� �����������.\r\n', 1, 7, 1);
INSERT INTO `faq` VALUES (57, 'item', '��� ����������, ��� � ���� �� ������?', '���������� <a href=http://proxyjudge.org class="altlink">ProxyJudge</a>. �� ������ ��� HTTP ���������, ������� ������� ������ �� ���. ����� ������ - ��� HTTP_CLIENT_IP, HTTP_X_FORWARDED_FOR � REMOTE_ADDR.<br>\r\n', 1, 7, 2);
INSERT INTO `faq` VALUES (58, 'item', '����� �� ������ ������ ����� ����������?', '���� ��� ��������� ��������� ������ HTTP ������� ����� 80� ����, ��� ��������� ����������� ������-�����, ����� ���������� ���-�� ����� ����� <a href=http://www.socks.permeo.com>socks</a>. ������ ������ ������� ������ �� ������� ������� FAQ.', 1, 7, 4);
INSERT INTO `faq` VALUES (74, 'item', '� ��� �������� ���� ������ <img src="pic/freedownload.gif" border="0"> ����� �������� � ������?', '���� ������ ��������, ��� ������� "����������", �� ���� ���� �� ������ ��� ������, �� � ��� ����� ��������� ������ ���������� ����������� ����������. ��� ��� �� �������� �� ���� �������� �� ����� �������� � ���������� ����������.', 1, 2, 14);
INSERT INTO `faq` VALUES (59, 'item', '��� �������, ���� ��� �������-������ ����������� ������?', '����� �� ������������ ������ ��� Internet Explorer`a, �� ���������� ������������ ������ ��� ����� http-�������� (������� ������� Microsoft, � �� �� ��� �� IE �������� ������ ������������ �������). � ������ �������, ���� �� ����������� ������ ������� (Opera/Mozilla/Firefox � �.�.) � ������������ � ��� ������ - ��� ��������� ����� ����������� ������ �� ���� �������. �� �� ����� ������� �������, ����������� ����������� ������ ������ ��� ����.', 1, 7, 5);
INSERT INTO `faq` VALUES (60, 'item', '������ � �� ���� ������������������ �� ��� ������?', '� ��� ����� ������� - �� �� ��������� ��������� ����� �������� �� ��� ������.', 1, 7, 6);
INSERT INTO `faq` VALUES (61, 'item', '��������� �� ��� ��������� � ������ �������-������?', '��� ������ ���� �������� ���������� ��� ������ �������. ������ ������� ����� ���� ��������� ��� ���������, � ����� �������� ���������� �� ������ ������ (�������� 6868 ��� 6969). �� ������������� �� ����������� ��������� � ������ ��������.', 1, 7, 7);
INSERT INTO `faq` VALUES (62, 'item', '����� ��� IP ������ � ������ ������?', '��� ���� ��������� ��� IP, ������������� � ���� ������ <a class=altlink href="http://methlabs.org/">PeerGuardian</a>\r\n��� ��, ��� � ������ ���������� �������������. ��� �������� �� ������ Apache/PHP � ������������ �� ���� ������� ������, ������� ��������� <i>������</i> � ���� �������. ���� �� ��������� �������������� ����������, ��� ���������� ���������� ������� ping/traceroute. ���� ��� �� ��������, ������ ������� �� � ����.<br>\r\n<br>\r\n���� ��-���� ������� � ����, � ��� IP ����� ������������� ���������� � ���� ������ PG, �� ������� ��� � ���, ���� �� ��������� ��� �� ����. ��� �� � ����� ��������. ��� ���������� �������� ���� IP ���������� � �������������� ������ ���� ������.', 1, 8, 1);
INSERT INTO `faq` VALUES (63, 'item', '��� ��������� ��������� ����� ������ �����', '(������ �����, ������������, ��� ��� ��������� ������ ���. ���� ������ �������� DNS-�������, �/��� ��������� (��� ����������) �������� � ����� �����/�����������). \r\n<br>\r\n������, ���� ��� ������, �� ����� �� ������ ��� ������. ��� ���������� �������� � ����� ����������� (��� ������������ � �������).<br>\r\n<br>\r\n�������, ��� �� ������ �� ����� ����������, ��� �� ������������, ������ ��� ������ �� ������ ��������� ��������� �� ��� ������ �������� ����������.', 1, 8, 2);
INSERT INTO `faq` VALUES (64, 'item', 'Alternate port (81)', 'Some of our torrents use ports other than the usual HTTP port 80. This may cause problems for some users,\r\nfor instance those behind some firewall or proxy configurations.\r\n\r\nYou can easily solve this by editing the .torrent file yourself with any torrent editor, e.g.\r\n<a href="http://sourceforge.net/projects/burst/" class="altlink">MakeTorrent</a>,\r\nand replacing the announce url bit-torrent.kiev.ua:81 with bit-torrent.kiev.ua:80 or just templateshares.net.<br>\r\n<br>\r\nEditing the .torrent with Notepad is not recommended. It may look like a text file, but it is in fact\r\na bencoded file. If for some reason you must use a plain text editor, change the announce url to\r\nbit-torrent.kiev.ua:80, not bit-torrent.kiev.ua. (If you''re thinking about changing the number before the\r\nannounce url instead, you know too much to be reading this.)', 0, 8, 3);
INSERT INTO `faq` VALUES (65, 'item', '� ������ ����������� ���:', '���� �� �� ����� ������� ����� �������� �����, �������� �� <a class="altlink" href="http://forum.pdaprime.ru/index.php?showtopic=44987">�����</a>, ���� �������� � �������� � <a href="staff.php">�������������</a> ����� �� ��� ����� <a href="contact.php">����� ������� �����</a>. �� ����� ������� ����������� ��� ������, ���� ������ ��������� ���������� �������� ��������:\r\n<ul>\r\n<li>�� ����� ������� ����������� ��� ������, ���� ������ ��������� ���������� �������� ��������.</li>\r\n<li>����� ���, ��� �������� ������, �������� ������ ���� (����������� �� ����� ����� ������). ����� ����� ����������, �� �������� ������� � FAQ, ����� ���� ������� � ���� �����.</li>\r\n<li>�������� ��� ������ ���. �� ��������������� ������� ���� "������ �� ��������!". �������� ������ ��������, ��� ����� ��� �� �������� ������������ � ���, ��� �� ������ �������, ��� ������ ����� �� ��������� �������.\r\n����� ������ �� �����������? ����� � ��� ��? ����� ��������� � ����� ����? ����� ��������� ��������� �� ������ �� ��������, ���� ��������? � ������ ���������� � ��� �������� ��������?\r\n��� ������ ������������ �� ��� ����������, ��� ����� ��� ����� ��� ������, � ������ ����� ������ �� ��, ��� ��� ������ �� ��������� ��� ������.</li>\r\n<li><b>� ��� ����� ��������: ������ �������. ���������� ������ ����� �����������, � �� ����� ��� ������� � ������ ������ �� �������� ��� ������.</b>', 1, 9, 1);
INSERT INTO `faq` VALUES (67, 'item', '��� ����� ������� � ����� �� �����?', '<b>�������</b> - ��� ��� ������, ������������ ����������-�������� ��� ����, ����� ������ �������, � ������ �������� ������.<br>\r\n<br>\r\n������������� ������� ��������� ��� ����������� �� ����, ����� ���������� ������������� ���������� ���� IP-����� ������ ����� ���������� � ��������� � ������� ������������ ��, ��� �������� ����������-������ �������.<br>\r\n<br>\r\n������� ������������ � �������-���� ��� ���������� ��� � �������. ��� ������, ��� ������ ��� ������� �������� �������, ���� ������� ����� ������� ����� ��������� ���-���� �� ������ �����. � ��� � ���� ������� ����� ������� ���������� �� ����� ��������.<br>\r\n������������ ������� � Announce-URL � ����� ����: http://merdox.ints.ru/announce.php?passkey=<passkey>, ��� <passkey> - ����� �� 32 ��������� ���� � �������� ����. �j����� ����� ������� �������� ������ ����������-�������, �������, ����� ������� �� ��� ����� (����������� ��� ��� ���������).\r\n', 1, 5, 13);
INSERT INTO `faq` VALUES (68, 'item', 'Why do i get a "Unknown Passkey" error? ', 'You will get this error, firstly if you are not registered on our tracker, or if you havent downloaded the torrent to use from our webpage, when you were logged in. In this case, just register or log in and redownload the torrent.\r\n\r\nThere is a chance to get this error also, at the first time you download anything as a new user, or at the first download after you reset your passkey. The reason is simply that the tracker reviews the changes in the passkeys every few minutes and not instantly. For that reason just leave the torrent running for a few minutes, and you will get eventually an OK message from the tracker.', 0, 5, 14);
INSERT INTO `faq` VALUES (69, 'item', 'When do i need to reset my passkey? ', '<ul><li> If your passkey has been leeched and other user(s) uses it to download torrents using your account. In this case, you will see torrents stated in your account that you are not leeching or seeding .</li>\r\n<li> When your clients hangs up or your connection is terminated without pressing the stop button of your client. In this case, in your account you will see that you are still leeching/seeding the torrents even that your client has been closed. Normally these "ghost peers" will be cleaned automatically within 30 minutes, but if you want to resume your downloads and the tracker denied that due to the fact that you "already are downloading the same torrents - Connection limit error" then you should reset your passkey and redownload the torrent, then resume it. </li></ul>', 0, 5, 15);
INSERT INTO `faq` VALUES (70, 'item', '��� ����� DHT � ������ ������ ���� ���������?', 'DHT ������ ���� ��������� � ����� �������, DHT ����� ���� �������� ������������� ����������� ����������� ������ �������� � ����� ��������� ��� ���������. �����, ������������ DHT ����� ������� �� ����� �������. ���������� ���� ���������� ���������, ����� ���������, ��� ������������� ���������; ������ 30 ����� ������ ��������� ���� ����������.', 1, 5, 15);
INSERT INTO `faq` VALUES (71, 'item', '������������� BT-�������:', '<b>������������������ �������:</b><br>\r\nAzureus<br>\r\nBitTornado<br>\r\n<br>\r\n<b>������� ��� Win:</b><br>\r\n�Torrent<br>\r\nABC<br>\r\n<br>\r\n<b>������� ��� Mac:</b><br>\r\nTomato Torrent<br>\r\nBitRocket (lastest version)<br>\r\nrtorrent<br>\r\n<br>\r\n<b>������ ��� Linux:</b><br>\r\nrtorrent<br>\r\nktorrent<br>\r\ndeluge<br>', 1, 1, 4);

-- --------------------------------------------------------

-- 
-- ��������� ������� `files`
-- 

CREATE TABLE `files` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) unsigned NOT NULL default '0',
  `filename` varchar(255) NOT NULL default '',
  `size` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `torrent` (`torrent`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `files`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `friends`
-- 

CREATE TABLE `friends` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `friendid` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `userfriend` (`userid`,`friendid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `friends`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `invites`
-- 

CREATE TABLE `invites` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `inviter` int(10) unsigned NOT NULL default '0',
  `inviteid` int(10) NOT NULL default '0',
  `invite` varchar(32) NOT NULL default '',
  `time_invited` datetime NOT NULL default '0000-00-00 00:00:00',
  `confirmed` char(3) NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `inviter` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `invites`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `messages`
-- 

CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sender` int(10) unsigned NOT NULL default '0',
  `receiver` int(10) unsigned NOT NULL default '0',
  `added` datetime default NULL,
  `subject` varchar(255) NOT NULL default '',
  `msg` text,
  `unread` enum('yes','no') NOT NULL default 'yes',
  `poster` int(10) unsigned NOT NULL default '0',
  `location` tinyint(1) NOT NULL default '1',
  `saved` enum('no','yes') NOT NULL default 'no',
  `archived` enum('yes','no') NOT NULL default 'no',
  `spamid` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `receiver` (`receiver`),
  KEY `sender` (`sender`),
  KEY `poster` (`poster`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `messages`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `news`
-- 

CREATE TABLE `news` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `body` text NOT NULL,
  `subject` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `news`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `newscomments`
-- 

CREATE TABLE `newscomments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `news` int(10) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `ori_text` text NOT NULL,
  `editedby` int(10) unsigned NOT NULL default '0',
  `editedat` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `news` (`news`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `newscomments`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `notconnectablepmlog`
-- 

CREATE TABLE `notconnectablepmlog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `date` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `notconnectablepmlog`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `orbital_blocks`
-- 

CREATE TABLE `orbital_blocks` (
  `bid` int(10) NOT NULL auto_increment,
  `bkey` varchar(15) NOT NULL default '',
  `title` varchar(60) NOT NULL default '',
  `content` text NOT NULL,
  `bposition` char(1) NOT NULL default '',
  `weight` int(10) NOT NULL default '1',
  `active` int(1) NOT NULL default '1',
  `time` varchar(14) NOT NULL default '0',
  `blockfile` varchar(255) NOT NULL default '',
  `view` int(1) NOT NULL default '0',
  `expire` varchar(14) NOT NULL default '0',
  `action` char(1) NOT NULL default '',
  `which` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`bid`),
  KEY `title` (`title`),
  KEY `weight` (`weight`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=24 ;

-- 
-- ���� ������ ������� `orbital_blocks`
-- 

INSERT INTO `orbital_blocks` VALUES (1, '', '�������������', '<table border="0"><tr>\r\n<td class="block"><a href="admincp.php">�������</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="online.php">�� �� ������?!</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="newsarchive.php">������������� �������</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="users.php">������ �������������</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="viewreport.php">������ �� ��������</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="staffmess.php">�������� ��</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="ipcheck.php">�������� �� IP</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="admincp.php?op=iUsers">������� ������ � ���� ������</a></td>\r\n</tr><tr>\r\n<td class="block"><a href="clearcache.php">�������� ���</a></td>\r\n</tr>\r\n</table>', 'l', 3, 1, '', '', 2, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (8, '', '����������', '', 'd', 2, 1, '', 'block-stats.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (9, '', '������, ������� ����� ���������', '', 'c', 1, 1, '', 'block-helpseed.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (10, '', '����������� � ��������', '<p align="jsutify">������������� ������� ����� - ������������ ������� � ����������, ������� ������ � ���� ����� �������� � ������������ ��� ��������������, ��������� ��� ����� ����� ���������� �����. ������������ ������ �������� ����� - �� ���������� ��� �� �����, �� ���� �������� ������ � ���������, ����������� ���� ������� ��� ����� ����� � 1, � �� ������ ������ ������������ � ���������. � �� ��������, ��� �� ��� �� �������������! (�����)</p>', 'c', 2, 0, '', '', 0, '0', 'd', 'rules,');
INSERT INTO `orbital_blocks` VALUES (2, '', '�������', '', 'c', 3, 1, '', 'block-news.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (3, '', '������������', '', 'd', 1, 1, '', 'block-online.php', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (4, '', '�����', '', 'l', 4, 1, '', 'block-search.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (5, '', '�����', '', 'c', 4, 1, '', 'block-polls.php', 1, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (6, '', '����� ������', '', 'c', 5, 0, '', 'block-releases.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (7, '', '���� ��� �� ������ ��������?)', '', 'c', 6, 0, '', 'block-forum.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (11, '', '�������� �������', '', 'c', 7, 1, '', 'block-server_load.php', 2, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (12, '', '�������� �� �������', '', 'c', 8, 1, '', 'block-indextorrents.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (13, '', '�������������', '<center><a href="javascript:void(0)" title="SMS.������� � ����� ��������� ������" onClick="javascript:window.open(''http://smskopilka.ru/?info&id=36066'', ''smskopilka'',''width=400,height=480,status=no,toolbar=no, menubar=no,scrollbars=yes,resizable=yes'');">\r\n<img src="http://img.smskopilka.ru/common/digits/target2/36/36066-101.gif" border="0" alt="SMS.�������"></a><br>\r\nWebMoney:<br>\r\nR153898361884\r\nZ113282224168<br><hr>\r\n������� �������!</center> ', 'l', 5, 1, '', '', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (14, '', '�������� ?', '<center>\r\n<a href="contact.php"><font color="red"><u>�������� ������!</u></font></a>\r\n</center><br>\r\n<i>..� ������������<br>\r\n..� ������<br>\r\n..� ����������</i>', 'l', 6, 1, '', '', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (15, '', 'Vote 4 us!', 'none', 'l', 7, 1, '', '', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (23, '', '������ �����', '', 'l', 1, 1, '', 'block-cloud.php', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (16, '', '������', 'none', 'l', 8, 1, '', '', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (17, '', '�������', '', 'l', 9, 1, '', 'block-req.php', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (18, '', '������� ?', 'none', 'l', 10, 1, '', '', 0, '0', 'd', 'all');
INSERT INTO `orbital_blocks` VALUES (19, '', '����', '', 'l', 2, 1, '', 'block-login.php', 0, '0', 'd', 'ihome,');
INSERT INTO `orbital_blocks` VALUES (22, '', '����������� ������', '', 'd', 3, 1, '', 'block-cen.php', 0, '0', 'd', 'all');

-- --------------------------------------------------------

-- 
-- ��������� ������� `peers`
-- 

CREATE TABLE `peers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `torrent` int(10) unsigned NOT NULL default '0',
  `peer_id` varchar(20) NOT NULL default '',
  `ip` varchar(64) NOT NULL default '',
  `port` smallint(5) unsigned NOT NULL default '0',
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `uploadoffset` bigint(20) unsigned NOT NULL default '0',
  `downloadoffset` bigint(20) unsigned NOT NULL default '0',
  `to_go` bigint(20) unsigned NOT NULL default '0',
  `seeder` enum('yes','no') NOT NULL default 'no',
  `started` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_action` datetime NOT NULL default '0000-00-00 00:00:00',
  `prev_action` datetime NOT NULL default '0000-00-00 00:00:00',
  `connectable` enum('yes','no') NOT NULL default 'yes',
  `userid` int(10) unsigned NOT NULL default '0',
  `agent` varchar(60) NOT NULL default '',
  `finishedat` int(10) unsigned NOT NULL default '0',
  `passkey` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `torrent_peer_id` (`torrent`,`peer_id`),
  KEY `torrent` (`torrent`),
  KEY `torrent_seeder` (`torrent`,`seeder`),
  KEY `last_action` (`last_action`),
  KEY `connectable` (`connectable`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `peers`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `polls`
-- 

CREATE TABLE `polls` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `question` varchar(255) NOT NULL,
  `start` int(10) NOT NULL,
  `exp` int(10) default NULL,
  `public` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `polls`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `polls_structure`
-- 

CREATE TABLE `polls_structure` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pollid` int(10) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `value` (`value`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `polls_structure`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `polls_votes`
-- 

CREATE TABLE `polls_votes` (
  `vid` int(10) unsigned NOT NULL auto_increment,
  `sid` int(10) NOT NULL default '0',
  `user` int(10) NOT NULL,
  `pid` int(10) NOT NULL,
  PRIMARY KEY  (`vid`),
  UNIQUE KEY `sid` (`sid`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `polls_votes`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `ratings`
-- 

CREATE TABLE `ratings` (
  `id` int(6) NOT NULL auto_increment,
  `torrent` int(10) NOT NULL default '0',
  `user` int(6) NOT NULL default '0',
  `rating` int(1) NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `ratings`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `readtorrents`
-- 

CREATE TABLE `readtorrents` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `torrentid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `read` (`userid`,`torrentid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `readtorrents`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `report`
-- 

CREATE TABLE `report` (
  `id` int(11) NOT NULL auto_increment,
  `torrentid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `motive` varchar(255) NOT NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `report`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `requests`
-- 

CREATE TABLE `requests` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `request` varchar(225) default NULL,
  `descr` text NOT NULL,
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `hits` int(10) unsigned NOT NULL default '0',
  `uploaded` enum('yes','no') NOT NULL default 'no',
  `filled` varchar(200) NOT NULL default '',
  `torrentid` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `cat` int(10) unsigned NOT NULL default '0',
  `filledby` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `requests`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `sessions`
-- 

CREATE TABLE `sessions` (
  `sid` varchar(32) NOT NULL default '',
  `uid` int(10) NOT NULL default '0',
  `username` varchar(40) NOT NULL default '',
  `class` tinyint(4) NOT NULL default '0',
  `ip` varchar(40) NOT NULL default '',
  `time` bigint(30) NOT NULL default '0',
  `url` varchar(150) NOT NULL default '',
  `useragent` text,
  PRIMARY KEY  (`sid`),
  KEY `time` (`time`),
  KEY `uid` (`uid`),
  KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

-- 
-- ���� ������ ������� `sessions`
-- 

INSERT INTO `sessions` VALUES ('cb437926985bda184ab1bfb4e7d3e3f4', -1, '', -1, '127.0.0.1', 1229461823, '/', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.4) Gecko/2008102920 Firefox/3.0.4');

-- --------------------------------------------------------

-- 
-- ��������� ������� `simpaty`
-- 

CREATE TABLE `simpaty` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `touserid` int(10) unsigned NOT NULL default '0',
  `fromuserid` int(10) unsigned NOT NULL default '0',
  `fromusername` varchar(40) NOT NULL default '',
  `bad` tinyint(1) unsigned NOT NULL default '0',
  `good` tinyint(1) unsigned NOT NULL default '0',
  `type` varchar(60) NOT NULL default '',
  `respect_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `touserid` (`touserid`),
  KEY `fromuserid` (`fromuserid`),
  KEY `fromusername` (`fromusername`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `simpaty`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `sitelog`
-- 

CREATE TABLE `sitelog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `added` datetime default NULL,
  `color` varchar(11) NOT NULL default 'transparent',
  `txt` text,
  `type` varchar(8) NOT NULL default 'tracker',
  PRIMARY KEY  (`id`),
  KEY `added` (`added`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=2 ;

-- 
-- ���� ������ ������� `sitelog`
-- 

INSERT INTO `sitelog` VALUES (1, '2008-12-17 00:05:35', '', '��������� 0 ������������� (5 � ����� ��������������)', 'admin');

-- --------------------------------------------------------

-- 
-- ��������� ������� `snatched`
-- 

CREATE TABLE `snatched` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) default '0',
  `torrent` int(10) unsigned NOT NULL default '0',
  `port` smallint(5) unsigned NOT NULL default '0',
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `to_go` bigint(20) unsigned NOT NULL default '0',
  `seeder` enum('yes','no') NOT NULL default 'no',
  `last_action` datetime NOT NULL default '0000-00-00 00:00:00',
  `startdat` datetime NOT NULL default '0000-00-00 00:00:00',
  `completedat` datetime NOT NULL default '0000-00-00 00:00:00',
  `connectable` enum('yes','no') NOT NULL default 'yes',
  `finished` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  KEY `snatch` (`torrent`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `snatched`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `stamps`
-- 

CREATE TABLE `stamps` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `sort` int(10) NOT NULL default '0',
  `class` tinyint(3) NOT NULL default '0',
  `image` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `image` (`image`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `stamps`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `stylesheets`
-- 

CREATE TABLE `stylesheets` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uri` varchar(255) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=2 ;

-- 
-- ���� ������ ������� `stylesheets`
-- 

INSERT INTO `stylesheets` VALUES (1, 'kinokpk', 'Kinokpk');

-- --------------------------------------------------------

-- 
-- ��������� ������� `tags`
-- 

CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category` int(10) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `howmuch` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=17 ;

-- 
-- ���� ������ ������� `tags`
-- 

INSERT INTO `tags` VALUES (1, 1, '�������', 0);
INSERT INTO `tags` VALUES (2, 1, '�������', 0);
INSERT INTO `tags` VALUES (3, 1, '��������', 0);
INSERT INTO `tags` VALUES (4, 1, '�������� � �����', 0);
INSERT INTO `tags` VALUES (5, 1, '����� � ���������', 0);
INSERT INTO `tags` VALUES (6, 1, '���������� � �������', 0);
INSERT INTO `tags` VALUES (7, 1, '����������� ������', 0);
INSERT INTO `tags` VALUES (8, 1, '������� � �����������', 0);
INSERT INTO `tags` VALUES (9, 1, '�������������� � ������-�������������� ������', 0);
INSERT INTO `tags` VALUES (10, 1, '��������� ����', 0);
INSERT INTO `tags` VALUES (11, 1, '����', 0);
INSERT INTO `tags` VALUES (12, 1, '��������', 0);
INSERT INTO `tags` VALUES (13, 1, '������������', 0);
INSERT INTO `tags` VALUES (14, 1, '���������', 0);
INSERT INTO `tags` VALUES (15, 2, '���������� � �������', 0);
INSERT INTO `tags` VALUES (16, 2, '�������', 0);

-- --------------------------------------------------------

-- 
-- ��������� ������� `thanks`
-- 

CREATE TABLE `thanks` (
  `id` int(11) NOT NULL auto_increment,
  `torrentid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `torrentid` (`torrentid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `thanks`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `torrents`
-- 

CREATE TABLE `torrents` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `info_hash` varbinary(40) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `save_as` varchar(255) NOT NULL default '',
  `search_text` text NOT NULL,
  `descr_type` int(10) NOT NULL,
  `image1` text NOT NULL,
  `image2` text NOT NULL,
  `category` int(10) unsigned NOT NULL default '0',
  `size` bigint(20) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `type` enum('single','multi') NOT NULL default 'single',
  `numfiles` int(10) unsigned NOT NULL default '0',
  `comments` int(10) unsigned NOT NULL default '0',
  `views` int(10) unsigned NOT NULL default '0',
  `hits` int(10) unsigned NOT NULL default '0',
  `times_completed` int(10) unsigned NOT NULL default '0',
  `leechers` int(10) unsigned NOT NULL default '0',
  `seeders` int(10) unsigned NOT NULL default '0',
  `last_action` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_reseed` datetime NOT NULL default '0000-00-00 00:00:00',
  `visible` enum('yes','no') NOT NULL default 'yes',
  `banned` enum('yes','no') NOT NULL default 'no',
  `owner` int(10) unsigned NOT NULL default '0',
  `orig_owner` int(10) unsigned NOT NULL default '0',
  `numratings` int(10) unsigned NOT NULL default '0',
  `ratingsum` int(10) unsigned NOT NULL default '0',
  `free` enum('yes','no') default 'no',
  `sticky` enum('yes','no') NOT NULL default 'no',
  `moderated` enum('yes','no') NOT NULL default 'no',
  `moderatedby` int(10) unsigned default '0',
  `topic_id` int(10) NOT NULL default '0',
  `online` varchar(255) NOT NULL,
  `tags` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `info_hash` (`info_hash`),
  KEY `owner` (`owner`),
  KEY `visible` (`visible`),
  KEY `category_visible` (`category`,`visible`),
  FULLTEXT KEY `ft_search` (`search_text`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `torrents`
-- 


-- --------------------------------------------------------

-- 
-- ��������� ������� `users`
-- 

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(40) NOT NULL default '',
  `old_password` varchar(40) NOT NULL default '',
  `passhash` varchar(32) NOT NULL default '',
  `secret` varchar(20) NOT NULL default '',
  `email` varchar(80) NOT NULL default '',
  `status` enum('pending','confirmed') NOT NULL default 'pending',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_login` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_access` datetime NOT NULL default '0000-00-00 00:00:00',
  `editsecret` varchar(20) NOT NULL default '',
  `privacy` enum('strong','normal','low') NOT NULL default 'normal',
  `stylesheet` int(10) default '1',
  `info` text,
  `acceptpms` enum('yes','friends','no') NOT NULL default 'yes',
  `ip` varchar(15) NOT NULL default '',
  `class` tinyint(3) unsigned NOT NULL default '0',
  `override_class` tinyint(3) unsigned NOT NULL default '255',
  `support` enum('no','yes') NOT NULL default 'no',
  `supportfor` text,
  `avatar` varchar(100) NOT NULL default '',
  `icq` varchar(255) NOT NULL default '',
  `msn` varchar(255) NOT NULL default '',
  `aim` varchar(255) NOT NULL default '',
  `yahoo` varchar(255) NOT NULL default '',
  `skype` varchar(255) NOT NULL default '',
  `mirc` varchar(255) NOT NULL default '',
  `website` varchar(50) NOT NULL default '',
  `uploaded` bigint(20) unsigned NOT NULL default '0',
  `downloaded` bigint(20) unsigned NOT NULL default '0',
  `bonus` decimal(5,2) NOT NULL default '0.00',
  `title` varchar(30) NOT NULL default '',
  `country` int(10) unsigned NOT NULL default '0',
  `notifs` varchar(100) NOT NULL default '',
  `modcomment` text,
  `enabled` enum('yes','no') NOT NULL default 'yes',
  `dis_reason` text NOT NULL,
  `parked` enum('yes','no') NOT NULL default 'no',
  `avatars` enum('yes','no') NOT NULL default 'yes',
  `donor` enum('yes','no') NOT NULL default 'no',
  `simpaty` int(10) unsigned NOT NULL default '0',
  `warned` enum('yes','no') NOT NULL default 'no',
  `warneduntil` datetime NOT NULL default '0000-00-00 00:00:00',
  `torrentsperpage` int(3) unsigned NOT NULL default '0',
  `deletepms` enum('yes','no') NOT NULL default 'yes',
  `savepms` enum('yes','no') NOT NULL default 'no',
  `gender` enum('1','2','3') NOT NULL default '1',
  `birthday` date default '0000-00-00',
  `passkey` varchar(32) NOT NULL default '',
  `language` varchar(255) default NULL,
  `invites` int(10) NOT NULL default '0',
  `invitedby` int(10) NOT NULL default '0',
  `invitedroot` int(10) NOT NULL default '0',
  `passkey_ip` varchar(15) NOT NULL default '',
  `num_warned` int(2) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `status_added` (`status`,`added`),
  KEY `ip` (`ip`),
  KEY `uploaded` (`uploaded`),
  KEY `downloaded` (`downloaded`),
  KEY `country` (`country`),
  KEY `last_access` (`last_access`),
  KEY `enabled` (`enabled`),
  KEY `warned` (`warned`),
  KEY `user` (`id`,`status`,`enabled`),
  FULLTEXT KEY `endis_reason` (`dis_reason`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- 
-- ���� ������ ������� `users`
-- 

