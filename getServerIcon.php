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
require_once('bootstrap.php');
error_reporting(-1);

$_GET['id'] = intval($_GET['id']);
if ($_GET['id'] < 0) $_GET['id'] = 4294967296 + $_GET['id'];


$cachefile = CACHE_DIR .'/'. $_GET['id'];
$config['imagepack'] = !isset($config['imagepack']) || trim($config['imagepack']) == '' ? 'standard' : $config['imagepack'];
$standardIconsPath = s_root . "images/" . $config['imagepack'] . "/";
$isStandardIcon = false;

if (in_array($_GET['id'], array("100", "200", "300", "500", "600")))
{
    $isStandardIcon = true;
    $standardIconPath = $standardIconsPath . "group_" . (string) $_GET['id'] . $config['image_type'];
}

// Check if standard group icon exists
if (($isStandardIcon && !file_exists($standardIconPath)) || (int) $_GET['id'] == 0)
{
    exit;
} elseif($isStandardIcon) {
    $img = file_get_contents($standardIconPath);
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
