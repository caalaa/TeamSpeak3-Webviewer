<?php

/**
 *  This file is part of TeamSpeak3 Webviewer.
 *
 *  TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
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
 *  along with TeamSpeak3 Webviewer.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Parses either a text or a xml configfile
 * @param type $file
 * @param type $xml
 * @return type 
 */
function parseConfigFile($file, $xml=false)
{
    if (!$xml) return parseConfigFileText($file);
    else return parseConfigFileXML($file);
}

/**
 * Parses XML configfile
 * @param type $file
 * @return type 
 */
function parseConfigFileXML($file)
{
    $xml = simplexml_load_file($file);
    $config = array();

    foreach ($xml->children() as $key => $value)
    {
        switch ($value)
        {
            case "true":
                (boolean) $value = (boolean) TRUE;
                break;
            case "false":
                (boolean) $value = (boolean) FALSE;
                break;
            case "none":
                $value = NULL;
                break;
            default:
                (string) $value = (string) $value;
                break;
        }

        $config[$key] = $value;
    }
    return $config;
}
?>
