<?
//* Global Setting
$tracker_lang['control'] = 'Global Setting';
$tracker_lang['control'] = 'Kinokpk releaser v.3.00';
$tracker_lang['error'] = 'Error';
$tracker_lang['access_denied'] = 'Access Denied';
$tracker_lang['yes'] = 'Yes';
$tracker_lang['no'] = 'No';
$tracker_lang['site'] = 'Web site address (without /):';
$tracker_lang['site_example'] = 'For example, "http://www.kinokpk.com"';
$tracker_lang['site_name'] = 'Site name (title):';
$tracker_lang['site_name_example'] = 'For example, "Tracker on music"';
$tracker_lang['site_description'] = 'Site Description (meta description):';
$tracker_lang['site_description_example'] = 'For example, "The best music download here"';
$tracker_lang['site_keywords'] = 'Keywords (meta keywords):';
$tracker_lang['site_keywords_example'] = 'For example, "Download, music, tracker"';
$tracker_lang['site_email'] = 'Email, from which the message will be sent to the site:';
$tracker_lang['site_email_example'] = 'For example, "bot@kinokpk.com"';
$tracker_lang['site_adminemail'] = 'Email to contact the administrator:';
$tracker_lang['site_adminemail_example'] = 'For example, "admin@windows.lox"';
$tracker_lang['site_language'] = 'Site language the default (name lang_%language%):';
$tracker_lang['site_multilanguage'] = 'Use multi-language system (off is not recommended):';
$tracker_lang['site_language_example'] = ' Specifies only the first 2 letters of the language (ru, en).';
$tracker_lang['site_themes'] = 'The standard theme for guests and registrants (themes/%name theme%):';
$tracker_lang['site_default_theme'] = ' By default, "kinokpk"';
$tracker_lang['site_copy'] = 'Your copyright for display at the bottom of page:';
$tracker_lang['site_copy_default'] = '*you can use the template <b>{datenow}</b> to display the current year.';
$tracker_lang['site_copy_example'] = 'For example, "&copy; 2008-{datenow} My site"';
$tracker_lang['site_block'] = 'Use a system of blocks (not recommended disable):';
$tracker_lang['site_gzip'] = 'Use gzip compression for pages:';
$tracker_lang['site_bans'] = 'Use the system is ban on IP / Network:';
$tracker_lang['site_smpt'] = 'Type SMTP:';
$tracker_lang['site_binar'] = 'The binary format of peers in anonser:';
$tracker_lang['site_binar_default'] = ' By default, Yes';
//* Setting integration with IPB forum
$tracker_lang['site_integration'] = 'Setting integration with IPB forum';
$tracker_lang['site_use_integration'] = 'Use integration with forum IPB:';
$tracker_lang['ipb_password_priority'] = 'Password from the forum is more important than the password from the tracker:<br /><small>In this case, the passwords do not coincide with the entrance to the tracker user will get an error, otherwise the tracker will automatically change the password on the forum</small>';
$tracker_lang['ibp_wiki'] = 'Type export releases to the forum:<br /><small>* for use export function in the wiki section<br />install and integrate IPB wikimedia with <a target="_blank" href="http://www.ipbwiki.com/">http://www.ipbwiki.com/</a></small>';
$tracker_lang['post_wiki'] = 'In the wiki section';
$tracker_lang['post'] = 'Directly in the post';
$tracker_lang['forum_url'] = 'URL Forum (no /):';
$tracker_lang['forum_url_example'] = 'For example, "http://forum.pdaprime.ru"';
$tracker_lang['forum_name'] = 'Forum Name:';
$tracker_lang['forum_name_example'] = 'For example, "pdaPRIME.ru"';
$tracker_lang['forum_cookie'] = 'Prefix forum cookies:';
$tracker_lang['forum_cookie_default'] = ' By default IPB, empty';
$tracker_lang['forum_id'] = 'Forum ID-basket';
$tracker_lang['forum_user_id'] = 'The class of users after export to the forum:';
$tracker_lang['forum_user_id_default'] = ' By default IPB, "3"';
$tracker_lang['forum_id_other'] = 'ID Forum for the export of other releases:';
$tracker_lang['forum_id_error'] = '* This release, a forum which does not coincide with the name tag, or an error in the determination of the forum.';
$tracker_lang['forum_smilies'] = 'Directory of smilies Forum (no /):';
$tracker_lang['forum_smilies_default'] = ' By default IPB, "default"';
//* Registration settings
$tracker_lang['reg_settings'] = 'Registration settings';
$tracker_lang['reg_deny'] = 'Deny registration:';
$tracker_lang['reg_allow_invite'] = 'Allow registration by invitation:';
$tracker_lang['reg_timezona'] = 'Time zone at registration:';
$tracker_lang['reg_activation'] = 'Use your account activation by e-mail:';
$tracker_lang['reg_captcha'] = 'Use captcha:<br /><small>* You must register at <a target="_blank" href="http://recaptcha.net">ReCaptcha.net</a> and get the private and public keys for use this options</small>';
$tracker_lang['reg_captcha_public'] = 'The Public Key CAPTCHA:';
$tracker_lang['reg_captcha_private'] = 'The Private Key CAPTCHA:';
$tracker_lang['default_notifs'] = 'The standard notification (pop-up window and/or PM):';
$tracker_lang['default_emailnotifs'] = 'Standard notification Email:';
$tracker_lang['type_notifs'] = '* All types of notifications in Kinokpk.com releaser';
$tracker_lang['type_notifs2'] = 'unread,torrents,comments,pollcomments,newscomments,usercomments,reqcomments,rgcomments,pages,pagecomments,friends,users,reports,unchecked ;';
$tracker_lang['type_notifs3'] = 'More  - <a target="_blank" href="mynotifs.php?settings">my notification settings</a>';
//* Settings restrictions
$tracker_lang['setting_restrictions'] = 'Settings restrictions.';
$tracker_lang['max_user'] = 'Maximum quantity of users:';
$tracker_lang['max_user_set'] = ' users, specify 0 to disable limit';
$tracker_lang['max_pm'] = 'Maximum quantity of messages in the Personal box:';
$tracker_lang['messages'] = ' messages';
$tracker_lang['avatar_max_width'] = 'Maximum width of the avatar';
$tracker_lang['avatar_max_height'] = 'Maximum height of an avatar:';
$tracker_lang['pixels'] = ' pixels';
$tracker_lang['deny_close_port'] = 'Deny Connection closed ports:';
$tracker_lang['max_torrent_size'] = 'The maximum size of the torrent file in bytes:';
$tracker_lang['bytes'] = ' bytes';
$tracker_lang['max_images'] = 'Maximum quantity of pictures for release:';
$tracker_lang['max_images_example'] = 'For example, "2"';
$tracker_lang['category_adult'] = 'Categories adult releases:<br /><small>*will be replaced by the default placeholder "Adult release", the user can choose to display these categories in the profile<br /><b>If the categories of more than one, list them separated by commas <u>no spaces</u></b></small>';
$tracker_lang['category_adult_example'] = 'For example, "13,14"';
//* Security Settings
$tracker_lang['security_settings'] = 'Security Settings';
$tracker_lang['security_flood'] = 'Flood-interval in seconds:';
$tracker_lang['seconds'] = ' seconds';
$tracker_lang['check_comments'] = 'Use check last 5 comments (spam):';
$tracker_lang['debug_mode'] = 'Debug mode:';
//* Other settings
$tracker_lang['other_settings'] = 'Other settings';
$tracker_lang['kinopoisk'] = 'Try it automatically get the movie trailer with kinopoisk.ru:<br/><small>*Works only if the description of the release has a link type http://www.kinopoisk.ru/level/1/film/ID_films</small>';
$tracker_lang['releases_browse'] = 'The quantity of releases in the list of releases on this page:<br /><small>*If you change this setting must clear the cache browse</small>';
$tracker_lang['releases'] = ' releases';
$tracker_lang['use_tll'] = 'Use the TTL (auto delete dead torrents):';
$tracker_lang['use_wait'] = 'Use the system limitations leechers Time:';
//* Save changes
$tracker_lang['save_changes'] = 'Save changes';
$tracker_lang['reset'] = 'Reset';
$tracker_lang['not_filled'] = 'Some fields are not filled';
$tracker_lang['not_filled_ipb'] = 'Some of the fields for integration with the forum is not filled';
$tracker_lang['captcha_not_defined'] = 'Private or public key captcha not defined';
$tracker_lang['unknown_action'] = 'Unknown action';
?>