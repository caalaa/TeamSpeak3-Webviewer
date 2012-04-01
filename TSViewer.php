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
require_once('bootstrap.php');
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

if($output !== '') {
    if (isset($ajax) && $ajax)
    {
        $ajaxScriptOutput = $mManager->loadModule("js")->ajaxJS;
        $ajaxHtmlOutput = $output;
    }
    else {
        echo $output;
    }
    return;
}



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