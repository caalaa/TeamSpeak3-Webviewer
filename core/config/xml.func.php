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
 *  along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
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
 * Parses a Text-Config-File and returns its values as an array
 * @deprecated new xml configfiles
 * @param type $file
 * @return boolean 
 */
function parseConfigFileText($file)
{
    if (!file_exists($file)) return false;

    $array = array();
    $fp = fopen($file, "r");
    while ($row = fgets($fp))
    {
        $row = trim($row);
        if (preg_match('#^([A-Za-z0-9_]+)\s+=\s+(.+?)(//.*)?$#D', $row, $arr))
        {
            $arr[2] = trim($arr[2]);
            $arr[1] = trim($arr[1]);
            switch ($arr[2])
            {
                case 'none':
                    $arr[2] = NULL;
                    break;
                case 'false':
                    $arr[2] = false;
                    break;
                case 'true':
                    $arr[2] = true;
            }
            $array[(string) $arr[1]] = $arr[2];
        }
    }
    fclose($fp);
    return $array;
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

/**
 * Parses a text or a xml languagefile
 * @deprecated new gettext l10n
 * @param type $file
 * @param type $xml
 * @return type 
 */
function parseLanguageFile($file, $xml=false)
{
    if (!$xml) return parseLanguageFileText($file);
    else return parseConfigFileXML($file);
}

/**
 * Parses a text-language-file and returns its values as an array
 * @deprecated new gettext l10n
 * @param type $file
 * @return type 
 */
function parseLanguageFileText($file)
{
    if (!file_exists($file)) return false;

    $array = array();
    $fp = fopen($file, "r");
    while ($row = fgets($fp))
    {
        $row = trim($row);
        if (preg_match('#^([A-Za-z0-9_\s\t]+?)\s+=\s+(.*)(//.*)?$#', $row, $arr))
        {
            $arr[2] = trim($arr[2]);
            $arr[1] = trim($arr[1]);
            $array[(string) $arr[1]] = $arr[2];
        }
    }
    fclose($fp);
    return $array;
}

/**
 * Parses a xml-language-file and returns its values as an array
 * @deprecated new gettext l10n
 * @param type $file
 * @return type 
 */
function parseLanguageFileXML($file)
{
    $xml = simplexml_load_file($file);
    $config = array();

    foreach ($xml->children() as $key => $value)
    {
        (string) $value = (string) $value;
        $config[$key] = $value;
    }
    return $config;
}

?>
