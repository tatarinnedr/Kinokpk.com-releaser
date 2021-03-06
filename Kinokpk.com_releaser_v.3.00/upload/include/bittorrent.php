<?php
/**
 * Script that initialises all stuff
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
/**
 * Constant to deny direct access to inclusion scripts
 * @var boolean
 */
define('IN_TRACKER', true);

// SET PHP ENVIRONMENT
@error_reporting(E_ALL & ~E_NOTICE);
@ini_set('error_reporting', E_ALL & ~E_NOTICE);
@ini_set('display_errors', '1');
@ini_set('display_startup_errors', '0');
@ini_set('ignore_repeated_errors', '1');
@session_start();
date_default_timezone_set('UTC');
/**
 * Full path to releaser sources
 * @var string
 */
define ('ROOT_PATH', str_replace("include","",dirname(__FILE__)));

require_once(ROOT_PATH . 'include/classes.php');
require_once(ROOT_PATH . 'include/functions.php');

// Variables for Start Time
/**
 * Script start time for debug
 * @var float
 */
$tstart = microtime(true); // Start time

require_once(ROOT_PATH . 'include/secrets.php');
require_once(ROOT_PATH . 'classes/cache/cache.class.php');
require_once(ROOT_PATH .  'classes/cache/fileCacheDriver.class.php');
/* @var object general cache object */
$CACHE=new Cache();
$CACHE->addDriver(NULL, new FileCacheDriver());
// TinyMCE security
require_once(ROOT_PATH . 'include/htmLawed.php');
// Ban system
require_once(ROOT_PATH.'classes/bans/ipcheck.class.php');

require_once(ROOT_PATH . 'include/blocks.php');

// IPB Integration functions
require_once(ROOT_PATH . 'include/functions_integration.php');

// IN AJAX MODE?

ajaxcheck();
?>