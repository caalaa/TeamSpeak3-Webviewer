<?php

// Start Session
session_name('ms_ts3Viewer');
session_start();

// Defines Current Version
define('version', "0.9");

// **************************************************************** \\
// STARTING EDITABLE CONTENT                                        \\
// **************************************************************** \\

define('msBASEDIR', dirname(__FILE__) . "/");
define('s_root', dirname(__FILE__) . "/");
define('l10nDir', msBASEDIR . "l10n");

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
    $url = $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
    if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === '' || $_SERVER['HTTPS'] === "off")
    {
        $url = "http://" . $url;
    }
    else
    {
        $url = "https://" . $url;
    }
    $url = str_replace("index.php", "", $url);
    $url = str_replace("TSViewer.php", "", $url);
    define("s_http", $url);
}
else
{
    define("s_http", $serveradress);
}

//Debug flag causes printing more detailed information in ms_ModuleManager and TSQuery.class.php
$debug = true;
if ($debug) error_reporting(E_ALL);
else
{
    error_reporting(E_ERROR);
}

$start = microtime(true);

require_once("utils.func.php");

unregister_globals('_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV',
        '_FILES', '_SESSION');

$config_name = isset($_GET['config']) ? $_GET['config'] : '';
str_replace('/', '', $config_name);
str_replace('.', '', $config_name);
$paths[] = s_root . "config/" . $config_name . ".xml";
$paths[] = s_root . 'config/config.xml';

$config_available = false;
foreach ($paths as $path)
{
    if (file_exists($path))
    {
        if (substr($path, -4) == ".xml")
        {
            $config = parseConfigFile($path, true);
        }
        else
        {
            $config = parseConfigFile($path, false);
        }
        $config_available = true;
        break;
    }
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
// 
//postparsing of configfile 
foreach ($config as $key => $value)
{
    if (preg_match("/^cachetime_/", $key))
    {
        $temp = explode('_', $key, 2);
        $temp[1] = str_replace('_', ' -', $temp[1]);
        $config['cachetime'][$temp[1]] = $value;
    }
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
$config['imagepath'] = s_http . "images/serverimages/";
if ($config['use_serverimages'] == true)
{
    if (isset($_GET['config']))
    {
        $config['serverimages'] = s_http . "get_server_icon.php?config=" . $_GET['config'] . "&id=";
    }
    else
    {
        $config['serverimages'] = s_http . "get_server_icon.php?id=";
    }
}
else
{
    $config['serverimages'] = s_http . "images/" . $config['imagepack'] . "/";
}

$config['image_type'] = '.' . $config['image_type'];
$config['client_name'] = "Maxesstuff TS3 Webviewer";


//get all needed classes
require_once(s_root . "TSQuery.class.php");
require_once(s_root . "TSChannel.class.php");
require_once(s_root . "Module.class.php");
require_once(s_root . "ModuleManager.class.php");
require_once(s_root . "libraries/php-gettext/gettext.inc");


$output = '';
try
{
    $query = new TSQuery($config['host'], $config['queryport']);
}
catch (Exception $e)
{
    die($e->getMessage());
}



// Flush caches | Caching
if (isset($_GET['flush_cache']) && isset($config['enable_cache_flushing']) && $config['enable_cache_flushing'] === true)
{
    $query->set_caching(true, 0);
}
elseif (isset($_GET['fc']) && isset($config['enable_cache_flushing']) && $config['enable_cache_flushing'] === true)
{
    $query->set_caching(true, 0);
}
else
{
    $query->set_caching($config['enable_caching'],
            $config['standard_cachetime'], $config['cachetime']);
}


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

$clientlist = $query->clientlist("-away -voice -groups -info -times");
ts3_check($clientlist, 'clientlist');

$servergroups = $query->servergrouplist();
ts3_check($servergroups, 'servergroups');

$channelgroups = $query->channelgrouplist();
ts3_check($channelgroups, 'channelgroups');

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


//load modules
$mManager = new ms_ModuleManager($info, $config, $debug);
$mManager->loadModule($config['modules']);
$output .= $mManager->triggerEvent('Startup');
$output .= $mManager->getHeaders();

//render the server
$output .= render_server($serverinfo['return'], $config['imagepath']);
$output .= $mManager->triggerEvent("serverRendered");

// render the channels
$output .= render_channellist($channellist_obj, $clientlist['return'],
        $servergroups['return'], $channelgroups['return']);



$output .= $mManager->getFooters();
$output .= "</div>";
$output .= $mManager->triggerEvent('Shutdown');

// Output the TS3 Viewer
// START HTML-Tidy-Up \\
if (function_exists("tidy_parse_string"))
{
    $config = array('indent' => TRUE,
        'output-xhtml' => TRUE,
        'wrap' => 200);

    $tidy = tidy_parse_string($output, $config, "UTF-8");
    $tidy->cleanRepair();
    var_dump($tidy);
    $output = $tidy;
}

// END HTML-Tidy-Up \\

echo $output;

$duration = microtime(true) - $start;

//echo $duration;
//** Rendering Functions **\\
function render_server($serverinfo, $imagepath)
{
    global $config, $mManager;
    return "<div class=\"server\">\r\n<p  class=\"servername\">" . $mManager->triggerEvent("InServer") . " <span>" . '<span class="serverimage image">&nbsp;</span>' . escape_name($serverinfo['virtualserver_name']) . "</span></p>\r\n";
}

function render_client($clientinfo, $servergrouplist, $channelgrouplist)
{
    global $config;

    if ($clientinfo['client_type'] == 1) return '';

    $rendered = '<div class="client" id="' . $config['prefix'] . 'client_' . htmlspecialchars($clientinfo['clid'],
                    ENT_QUOTES) . '"><p class="client-content" id="' . $config['prefix'] . "client_" . htmlspecialchars($clientinfo['clid'],
                    ENT_QUOTES) . '">';

    foreach (get_servergroup_images($clientinfo, $servergrouplist,
            $config['use_serverimages'], $config['servergrp_images']) as $image)
    {
        if ($image == 0) continue;
        $rendered .= '<span class="img_r group-image" style="background: url(\'' . $config['serverimages'] . $image . '\') no-repeat transparent;">&nbsp;</span>';
    }
    $channelgroup_icon = get_channelgroup_image($clientinfo, $channelgrouplist,
            $config['use_serverimages'], $config['channelgrp_images']);
    if ($channelgroup_icon != 0)
    {
        $rendered .= '<span class="img_r group-image" style="background: url(\'' . $config['serverimages'] . $channelgroup_icon . '\') no-repeat transparent;">&nbsp;</span>';
    }
    $rendered .= '<span class="clientimage ' . get_client_image($clientinfo) . '">&nbsp;</span>' . escape_name($clientinfo['client_nickname']);
    $rendered .= "\r\n</div></p>";
    return $rendered;
}

// Renders channels
function render_channel_start($channel, $clientlist)
{
    global $config;
    $output = '';
    $channel['channel_name'] = (parse_spacer($channel) === false ? $channel['channel_name'] : parse_spacer($channel));

    if (!is_array($channel['channel_name']))
    {
        $channelimage = 'normal-channel';

        if ($channel['channel_maxclients'] != -1 && $channel['channel_maxclients'] <= $channel['total_clients'])
        {
            $channelimage = 'full';
        }
        elseif ($channel['channel_flag_password'] == 1)
        {
            $channelimage = 'locked';
        }

        if (($channel->has_childs() || $channel->has_clients($clientlist)) && $config['show_arrows'])
        {
            $output .= '<div class="channel channel_arr" id="' . $config['prefix'] . "channel_" . htmlspecialchars($channel['cid'],
                            ENT_QUOTES) . "\">\r\n";
        }
        else
        {
            $output .= '<div class="channel channel_norm" id="' . $config['prefix'] . "channel_" . htmlspecialchars($channel['cid'],
                            ENT_QUOTES) . "\">\r\n";
        }
        $output .= '<p class="chan_content">';

        // If channel has a channel icon
        if ($channel['channel_icon_id'] != 0 && $config['use_serverimages'] == true)
        {
            $output .= '<span class="img_r group-image" style="background: url(\'' . $config['serverimages'] . $channel['channel_icon_id'] . '\') no-repeat transparent;">&nbsp;</span>';
        }

        // If channel is moderated
        if ($channel['channel_needed_talk_power'] > 0)
        {
            $output .= '<span class="channel-perm-image moderated img_r">&nbsp;</span>';
        }

        // If channel has password
        if ($channel['channel_flag_password'] == '1')
        {
            $output .= '<span class="channel-perm-image password img_r">&nbsp;</span>';
        }

        // If arrow needs to be displayed
        if (($channel->has_childs() || $channel->has_clients($clientlist)) && $config['show_arrows'])
        {
            $output .= '<span class="img_l arrow arrow-normal"></span>';
        }

        $output .= '<span class="channelimage ' . $channelimage . '">&nbsp;</span>' . escape_name($channel['channel_name']);
        $output .= "</p>\r\n";
    }
    else
    {
        if (($channel->has_childs() || $channel->has_clients($clientlist)) && $config['show_arrows'])
        {
            $output .= '<div class="spacer spacer_arr" id="' . $config['prefix'] . "channel_" . htmlspecialchars($channel['cid'],
                            ENT_QUOTES) . '">';
        }
        else
        {
            $output .= '<div class="spacer spacer_norm" id="' . $config['prefix'] . "channel_" . htmlspecialchars($channel['cid'],
                            ENT_QUOTES) . '">';
        }
        if ($channel['channel_name']['is_special_spacer'])
        {
            switch ($channel['channel_name']['spacer_name'])
            {
                case '---':
                    $output .= '<p class="bs spacer_con">';
                    break;
                case '...':
                    $output .= '<p class="punkt spacer_con">';
                    break;
                case '-.-':
                    $output .= '<p class="bspunkt spacer_con">';
                    break;
                case '___':
                    $output .= '<p class="linie spacer_con">';
                    break;
                case '-..':
                    $output .= '<p class="bsdpunkt spacer_con">';
                    break;
            }
            if (($channel->has_childs() || $channel->has_clients($clientlist)) && $config['show_arrows'])
            {
                $output .= '<img alt="" class="img_l arrow" src="' . $config['imagepath'] . 'arrow_normal' . $config['image_type'] . '"/>';
            }
            $output .= '&nbsp';

            $output .= '</p>';
        }
        else
        {
            switch ($channel['channel_name']['spacer_alignment'])
            {

                case 'r':
                    $output .= '<p class="left spacer_con">';
                    break;
                case 'c':
                    $output .= '<p class="center spacer_con">';
                    break;
                case 'l':
                case '*':
                    $output .= '<p class="left spacer_con">';
            }
            if (($channel->has_childs() || $channel->has_clients($clientlist)) && $config['show_arrows'])
            {
                $output .= '<img alt="" class="img_l arrow" src="' . $config['imagepath'] . 'arrow_normal' . $config['image_type'] . '"/>';
            }

            $output .= ( $channel['channel_name']['spacer_alignment'] == '*' ? str_repeat(escape_name($channel['channel_name']['spacer_name']),
                                    200) : escape_name($channel['channel_name']['spacer_name'])) . "</p>\r\n";
        }
    }

    return $output;
}

// Renders the Channels
function render_channellist($channellist, $clientlist, $servergroups,
        $channelgroups)
{
    static $is_rendered;

    global $config;

    $output = '';
    $clients_to_render = Array();

    foreach ($channellist as $channel)
    {
        $clients_to_render = Array();
        if (@in_array($channel['cid'], $is_rendered)) continue;

        $is_rendered[] = $channel['cid'];
        $output .= render_channel_start($channel, $clientlist);
        foreach ($clientlist as $client)
        {
            if ($client['cid'] == $channel['cid'])
            {
                $clients_to_render[] = $client;
            }
        }

        $clients_to_render = sort_clients($clients_to_render);
        foreach ($clients_to_render as $client)
        {
            $output .= render_client($client, $servergroups, $channelgroups);
        }


        if ($channel->has_childs())
        {
            $output .= render_channellist($channel->get_childs(), $clientlist,
                    $servergroups, $channelgroups);
        }

        $output .= "</div>\r\n";
    }

    return $output;
}