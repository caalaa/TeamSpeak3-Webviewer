<?php

/**
 * This file is part of TeamSpeak3 Webviewer.
 *
 * TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TeamSpeak3 Webviewer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TeamSpeak3 Webviewer. If not, see http://www.gnu.org/licenses/.
 */
session_name("ms_ts3Viewer");
session_start();

define('s_root', $_SESSION['s_root']);


$_GET['id'] = intval($_GET['id']);
if ($_GET['id'] < 0) $_GET['id'] = 4294967296 + $_GET['id'];

include s_root . "core/config.inc";

$config_name = isset($_GET['config']) ? $_GET['config'] : '';
str_replace('/', '', $config_name);
str_replace('.', '', $config_name);
$paths[] = s_root . "config/" . $config_name . ".xml";
$paths[] = s_root . 'config/' . $config_name . ".conf";
$paths[] = s_root . 'config/config.xml';
$paths[] = s_root . 'config/config.conf';
$paths[] = s_root . 'viewer.conf';
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
        break;
    }
}


$cachefile = s_root . "cache/" . $config['host'] . $config['queryport'] . "/" . $config['vserverport'] . "/server/images/" . $_GET['id'];
$config['imagepack'] = !isset($config['imagepack']) || trim($config['imagepack']) == '' ? 'standard' : $config['imagepack'];
$standardIconsPath = s_root . "images/" . $config['imagepack'] . "/";

$isStandardIcon = false;

if (in_array((int) $_GET['id'], array(100, 200, 300, 500, 600))) $isStandardIcon = true;

// Check if standard group icon exists
if ((!file_exists($standardIconsPath . "group_" . (string) $_GET['id'] . "." . $config['image_type']) && $isStandardIcon) || (int) $_GET['id'] == 0) exit;

// Check if standard group icons are used switch ((int) $_GET['id'])
switch ((int) $_GET['id'])
{
    // Channeladmin
    case 100:
        $img = file_get_contents($standardIconsPath . "group_100." . $config['image_type']);
        break;

    // Operator
    case 200:
        $img = file_get_contents($standardIconsPath . "group_200." . $config['image_type']);
        break;

    // Superadmin
    case 300:
        $img = file_get_contents($standardIconsPath . "group_300." . $config['image_type']);
        break;

    // Superadmin Query
    case 500:
        $img = file_get_contents($standardIconsPath . "group_500." . $config['image_type']);
        break;

    // Voice
    case 600:
        $img = file_get_contents($standardIconsPath . "group_600." . $config['image_type']);
        break;
}


// If standardicon is used
if (isset($img))
{
    header("Content-Type: image/" . $config['image_type']);
    echo $img;
    exit;
}
// If using automatic icon download is turned off
else if ($config['use_serverimages'] == FALSE)
{
    exit;
}
// If automatic icon download is on
else
{
    // If file is cached
    if (file_exists($cachefile))
    {
        $img = file_get_contents($cachefile);
    }
    // If icon needs to be downloaded
    else
    {
        include s_root . "core/teamspeak/TSQuery.class.php";

        $query = new TSQuery($config['host'], $config['queryport']);

        if ($config['login_needed'])
        {
            $query->login($config['username'], $config['password']);
        }

        $query->use_by_port($config['vserverport']);

        $img = $query->download("/icon_" . $_GET['id'], 0);
        $query->quit();
        $file = fopen($cachefile, "wb");
        fwrite($file, $img);
        fclose($file);
    }

    header("Content-Type: image/png");
    echo $img;
}
?>
