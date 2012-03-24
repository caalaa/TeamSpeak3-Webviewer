<?php
session_name('ms_ts3Viewer');
session_start();

// Defines Current Version
define('version', "1.3.2");

// **************************************************************** \\
// STARTING EDITABLE CONTENT                                        \\
// **************************************************************** \\

define('msBASEDIR', dirname(__FILE__) . "/");
define('s_root', dirname(__FILE__) . "/");
define('l10nDir', msBASEDIR . "l10n");
define('CACHE_DIR', msBASEDIR . 'cache');

// Debug flag causes printing more detailed information in ms_ModuleManager and TSQuery.class.php
define('debug', true);

// Define Standardname of the Webviewer
define('clientNickname', 'devMX TeamSpeak3 Webviewer' . version);

// Enter here the HTTP-Path of your viewer (with ending slash)
// Geben Sie hier den HTTP-Pfad zum Viewer ein (mit Schrägstrich am Ende)
// Example/ Beispiel: $serveradress = "http://yourdomain.com/software/viewer/ts3viewer/";
// It it strongly recommended to set the path, else the viewer may nor work properly
// Wir empfehlen dringend, diesesn Pfad zu setzen, da der Viewer sonst eventuell nicht funktionieren wird
$serveradress = "";

// **************************************************************** \\
// END EDITABLE CONTENT                                             \\
// **************************************************************** \\
// If s_http is not defined or empty, $_SERVER['HTTP_REFERER'] will be used (not 100% secure)
// http://php.net/manual/de/reserved.variables.server.php
if (!isset($serveradress) || $serveradress == "")
{
    if ((int) $_SERVER['SERVER_PORT'] == 80 || (int) $_SERVER['SERVER_PORT'] == 443)
    {
        $url = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
    }
    else
    {
        $url = $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['PHP_SELF'];
    }

    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === '' || $_SERVER['HTTPS'] === "off")
    {
        $url = "http://" . $url;
    }
    else
    {
        $url = "https://" . $url;
    }

    // Replace file names
    $url = str_replace("index.php", "", $url);
    $url = str_replace("TSViewer.php", "", $url);
    $url = str_replace("ajax.php", "", $url);

    define("s_http", $url);
}
else
{
    define("s_http", $serveradress);
}

// Enable Ajax-mode
$ajaxEnabled = false;


// Check if debugging mode should be enabled
if (debug)
{
    error_reporting(E_ALL);
}
else
{
    error_reporting(E_ERROR);
}

$start = microtime(true);


require_once s_root . "core/utils.inc";
require_once s_root . 'core/config.inc';

unregister_globals('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES', '_SESSION');

$config_name = isset($_GET['config']) ? $_GET['config'] : 'config';

// Check if ajax Mode should be used
if (isset($ajaxConfig) && $ajaxConfig != "")
{
    $config_name = $ajaxConfig;
    $ajaxEnabled = true;
}

str_replace('/', '', $config_name);
str_replace('.', '', $config_name);
$configPath = s_root . "config/" . $config_name . ".xml";

$config_available = false;

// Checks if configfile exists and loads it if it exists
if (file_exists($configPath))
{
    if (substr($configPath, -4) == ".xml")
    {
        $config = parseConfigFile($configPath, true);
    }
    $config_available = true;
}


// WELCOME SCREEN START \\
// If no configfile is available
if ($config_available == false)
{
    require_once s_root . 'install/core/xml.php';
    require_once s_root . "html/welcome/welcome.php";
    exit;
}
// WELCOME SCREEN END \\
//postparsing of configfile 
foreach ($config as $key => $value)
{
    if (preg_match("/^servergrp_images_/", $key))
    {
        $temp = explode('_', $key);
        $temp = array_pop($temp);
        $config['servergrp_images'][$temp] = $value;
    }
    if (preg_match("/^channelgrp_images_/", $key))
    {
        $temp = explode('_', $key);
        $temp = array_pop($temp);
        $config['channelgrp_images'][$temp] = $value;
    }
}

$config['modules'] = explode(',', $config['modules']);

if ($config['sort_method'] == "tsclient")
{
    $config['need_clientinfo'] = true;
}
else
{
    $config['need_clientinfo'] = false;
}

$config['cache_dir'] = CACHE_DIR;
$config['config_name'] = $config_name;

// Checks if the language as been submitted over the URL
if (isset($_GET['lang']))
{
    $lang = str_replace(".", "", $_GET['lang']);
    $lang = str_replace("/", "", $lang);
    $config['language'] = $lang;
}

// Writes language and pathes into the sesssion
$_SESSION['language'] = $config['language'];
$_SESSION['s_root'] = s_root;
$_SESSION['s_http'] = s_http;


$config['image_type2'] = $config['image_type'];
if (isset($_GET['config']))
{
    $config['serverimages'] = s_http . "getServerIcon.php?config=" . $_GET['config'] . "&amp;id=";
}
else
{
    $config['serverimages'] = s_http . "getServerIcon.php?id=";
}


$config['image_type'] = '.' . $config['image_type'];
$config['client_name'] = sprintf("%s %s", clientNickname, version);
$_SESSION['client_name'] = $config['client_name'];


// Write ajax mode settings to config
if ($ajaxEnabled)
{
    $config['ajaxEnabled'] = true;
}
else
{
    $config['ajaxEnabled'] = false;
}

$_SESSION['viewerConfig'] = $config;

require_once s_root . 'core/teamspeak.inc';
require_once s_root . 'core/module.inc';
require_once s_root . 'core/tsv.inc';
require_once s_root . 'core/i18n.inc';
require_once s_root . 'core/utils.inc';
require_once s_root . "libraries/php-gettext/gettext.inc";

?>
