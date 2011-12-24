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
$ajax = true;
$ajaxConfig = $_GET['config'];

require_once 'TSViewer.php';

// If javascript should be sent
if (isset($_GET['json']) && $_GET['json'] == "false")
{
    $createScript;

    header('Content-type: text/javascript');

    foreach ($ajaxScriptOutput['src'] as $s)
    {
        $createScript .= "document.write('<script type=\"text/javascript\" src=\"" . $s . "\"><\/script>');\r\n";
    }

    echo($createScript);
}
// If json should be sent
else if (isset($_GET['json']) && $_GET['json'] == "true")
{
    header('Content-type: application/json');

    echo($_GET['callback'] . '(' . json_encode(array("html" => $ajaxHtmlOutput, "script" => $ajaxScriptOutput), JSON_HEX_QUOT) . ')');
}
?>