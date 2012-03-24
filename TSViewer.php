<?php

/**
 *  This file is part of devMX TeamSpeak3 Webviewer.
 *  Copyright (C) 2011 - 2012 Max Rath and Maximilian Narr
 *
 *  devMX TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  TeamSpeak3 Webviewer is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with devMX TeamSpeak3 Webviewer.  If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * Events thrown by the viewer:
 * onStartup (no html) after loading the modules specified in the config
 * onCacheFlushed (no html) when the viewers cache gets flushed
 * onInfoLoaded (no html) when the data was loaded from the server
 * onHtmlStartup (html) when the html output is started. the return of all events after this event is included into the final html
 * onServerRendered (html) when the vServer heading was rendered
 * onInServer (html) inside the vServer heading (atm a special hook for the about module)
 * onHtmlShutdown (html) after the viewer is rendered
 * onShutdown (no html) the last event triggered for final tidy up 
 */
// Start Session
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

// Include all required classes
require_once s_root . 'core/teamspeak.inc';
require_once s_root . 'core/module.inc';
require_once s_root . 'core/tsv.inc';
require_once s_root . 'core/i18n.inc';
require_once s_root . 'core/utils.inc';
require_once s_root . "libraries/php-gettext/gettext.inc";


$output = '';

try
{
    $query = new TSQuery($config['host'], $config['queryport']);
}
catch (Exception $ex)
{
    $msERRWAR = throwAlert($ex->getMessage(), null, true);

    require_once s_root . 'html/error/error.php';
    exit;
}

$mManager = new ms_ModuleManager($config, $config_name, debug);
$mManager->loadModule($config['modules']);

// Load usageStatistics if set in the configfile
if ($config['usage_stats'])
{
    $mManager->loadModule("usageStatistics");
}

// Flush caches | Caching
if (isset($_GET['flush_cache']) && isset($config['enable_cache_flushing']) && $config['enable_cache_flushing'] === true)
{
    $mManager->triggerEvent('CacheFlush');
}
elseif (isset($_GET['fc']) && isset($config['enable_cache_flushing']) && $config['enable_cache_flushing'] === true)
{
    $mManager->triggerEvent('CacheFlush');
}

$mManager->triggerEvent('Startup');





try
{
    if ($config['login_needed'])
    {
        ts3_check($query->login($config['username'], $config['password']), 'login');
    }

    ts3_check($query->use_by_port($config['vserverport']), 'use');

    $query->send_cmd("clientupdate client_nickname=" . $query->ts3query_escape($config['client_name']));

    $serverinfo = $query->serverinfo();
    ts3_check($serverinfo, 'serverinfo');

    $channellist = $query->channellist("-voice -flags -icon -limits");
    ts3_check($channellist, 'channellist');

    $clientlist = $query->clientlist("-away -voice -groups -info -times -icon -country");
    ts3_check($clientlist, 'clientlist');

    $servergroups = $query->servergrouplist();
    ts3_check($servergroups, 'servergroups');

    $channelgroups = $query->channelgrouplist();
    ts3_check($channelgroups, 'channelgroups');

    if ($config['need_clientinfo'])
    {
        foreach ($clientlist['return'] as $key => $toFetch)
        {
            $fetched = $query->clientinfo($toFetch['clid']);
            ts3_check($fetched, 'clientinfo');
            $clientlist['return'][$key] = array_merge($clientlist['return'][$key], $fetched);
        }
    }
}
catch (Exception $ex)
{
    $msERRWAR = throwAlert($ex->getMessage(), null, true);
    $query->quit();

    require_once s_root . 'html/error/error.php';
    exit;
}
$query->quit();



foreach ($channellist['return'] as $key => $channel)
{
    $channellist_obj[$key] = new TSChannel($channellist['return'], $channel['cid']);
}
$info = Array(
    'serverinfo' => $serverinfo['return'],
    'channellist' => $channellist_obj,
    'clientlist' => $clientlist['return'],
    'servergroups' => $servergroups['return'],
    'channelgroups' => $channelgroups['return']
);

$mManager->setInfo($info);


//load modules

$output .= $mManager->triggerEvent('HtmlStartup');

$output .= $mManager->getHeaders();

//render the server
$output .= render_server($serverinfo['return']);
$output .= $mManager->triggerEvent("serverRendered");

// render the channels
switch ($config["filter"])
{
    case "clientsonly":
        $output .= render_channellist($channellist_obj, $clientlist['return'], $servergroups['return'], $channelgroups['return'], true, false);
        break;

    case "channelclientsonly":
        $output .= render_channellist($channellist_obj, $clientlist['return'], $servergroups['return'], $channelgroups['return'], false, true);
        break;

    case "standard":
        $output .= render_channellist($channellist_obj, $clientlist['return'], $servergroups['return'], $channelgroups['return'], false, false);
        break;

    default:
        $output .= render_channellist($channellist_obj, $clientlist['return'], $servergroups['return'], $channelgroups['return'], false, false);
        break;
}



$output .= $mManager->getFooters();
$output .= "</div>";
$output .= $mManager->triggerEvent('HtmlShutdown');
$mManager->triggerEvent('Shutdown', array($output));

// Check if ajax mode is enabled
if (isset($ajax) && $ajax)
{
    $ajaxScriptOutput = $mManager->loadModule("js")->ajaxJS;
    $ajaxHtmlOutput = $output;
}
// Normal mode
else
{
    echo $output;
}

$duration = microtime(true) - $start;

//echo $duration;