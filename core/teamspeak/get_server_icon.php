<?php

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
$standardIconsPath = s_root . "images/" . $config['imagepack'] . "/";


// Check if standard group icons are used
switch ((int) $_GET['id'])
{
    // Channeladmin
    case 100:
        copy($standardIconsPath . "group_100" . $config['image_type'], $cachefile);
        break;

    // Operator
    case 200:
        copy($standardIconsPath . "group_200" . $config['image_type'], $cachefile);
        break;

    // Superadmin
    case 300:
        copy($standardIconsPath . "group_300" . $config['image_type'], $cachefile);
        break;

    // Superadmin Query
    case 500:
        copy($standardIconsPath . "group_500" . $config['image_type'], $cachefile);
        break;

    // Voice
    case 600:
        copy($standardIconsPath . "group_600" . $config['image_type'], $cachefile);
        break;
}


if (file_exists($cachefile)) $img = file_get_contents($cachefile);
else
{

    include s_root . "core/teamspeak/TSQuery.class.php";

    $query = new TSQuery($config['host'], $config['queryport']);
    $query->use_by_port($config['vserverport']);

    $img = $query->download("/icon_" . $_GET['id'], 0);
    $query->quit();
    $file = fopen($cachefile, "wb");
    fwrite($file, $img);
    fclose($file);
}

header("Content-Type: image/" . $config['image_type']);
echo $img;
?>
