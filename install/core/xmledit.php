<?php

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

session_name("tswv");
session_start();

define("PROJECTPATH", realpath("./../") . "/l18n");
define("ENCODING", "UTF-8");


if (!$_SESSION['validated']) die("No Access");

require_once '../../libraries/php-gettext/gettext.inc';
require_once '../../core/i18n/i18n.func.php';
require_once '../core/htmlbuilder.php';
require_once 'utils.php';

// l18n
$lang = $_SESSION['lang'];

setlocale(LC_MESSAGES, $lang . ".utf8", $lang . ".UTF8", $lang . ".utf-8", $lang . "UTF-8", $lang);

$domain = "ms-tsv-install";

bindtextdomain($domain, PROJECTPATH);
textdomain($domain);
bind_textdomain_codeset($domain, ENCODING);


$module = $_GET['module'];

$xml = simplexml_load_string($_SESSION['config_xml']);

// Errors and warnings
$msERRWAR = "";

// If File should be saved
if ($_REQUEST['action'] == "submit" && isset($_REQUEST['module']))
{
    // global config
    if ($_REQUEST['type'] == 'global')
    {
        $handle = fopen("../../modules/$module/$module.xml", "w");
        fwrite($handle, str_replace('\\"', '"', $_REQUEST['xml']));
        fclose($handle);

        $msERRWAR .= throwWarning((__('Configfile successfully saved!')));
    }
    // local config
    else if ($_REQUEST['type'] == 'local')
    {
        foreach ($xml->module as $mod)
        {
            foreach ($mod->attributes() as $key => $value)
            {
                if ((string) $key == "name" && (string) $value == $module)
                {
                    $newXML = simplexml_load_string(str_replace('\\"', '"', $_REQUEST['xml']));

                    $dom = dom_import_simplexml($mod);
                    $dom->parentNode->removeChild($dom);

                    $newChild = $xml->addChild('module');
                    $newChild->addAttribute('name', $module);

                    foreach ($newXML as $key => $value)
                    {
                        $newChild->addChild($key, $value);
                    }

                    $dom = new DOMDocument('1.0');
                    $dom->preserveWhiteSpace = false;
                    $dom->formatOutput = true;
                    $dom->loadXML($xml->asXML());
                    $xml = simplexml_load_string($dom->saveXML());
                }
            }
        }
        $msERRWAR .= throwWarning((__('The changes have been added to the queue. They will be saved if you save the configfile of the viewer for the next time.')));
        $_SESSION['config_xml'] = $xml->asXML();
    }
}

// Local config
$localConfig = "";

foreach ($xml->module as $mod)
{
    foreach ($mod->attributes() as $key => $value)
    {
        if ((string) $key == "name" && (string) $value == $module)
        {
            $localConfig = $mod->asXML();
            break 2;
        }
    }
}

// Global config
$globalConfig = simplexml_load_file("../../modules/$module/$module.xml")->asXML();

require_once '../html/xmledit.php';
?>
