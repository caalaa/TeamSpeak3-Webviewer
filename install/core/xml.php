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
// Returns an XML-File as Simple-XML-Object
function getXmlFile($path)
{
    if (file_exists($path)) return simplexml_load_file($path);

    return false;
}

// Saves an XML-File
function saveXmlFile($path, $data)
{
    if (file_exists($path)) unlink($path);

    file_put_contents($path, $data->asXML());
    return true;
}

// Gets all config-files
function getConfigFiles($dir)
{
    $handler = opendir($dir);
    $files = array();

    while ($file = readdir($handler))
    {
        if ($file != "." && $file != ".." && $file != 'template.xml') $files[] = str_replace(".xml", "", $file);
    }

    return $files;
}

/**
 * Adds the config parameters of the module config files
 * @todo update mechanism
 * @since 1.0
 * @param type $xml 
 * @return type $xml xml with config options
 */
function addModuleConfigParameter($xml)
{
    $modules = getModules();

    foreach ($modules as $modName)
    {
        $configPath = "../modules/$modName/$modName.xml";

        // Check if module has a configfile
        if (!file_exists($configPath)) continue;

        $module = simplexml_load_file($configPath);

        $available = false;

        // Check if config for the module already exists
        foreach ($xml->module as $mod)
        {
            foreach ($mod->attributes() as $key => $value)
            {
                if ($key == "name" && $value == $modName) $available = true;
            }
        }

        if ($available) continue;

        $newSection = $xml->addChild('module');
        $newSection->addAttribute('name', $modName);

        foreach ($module as $option => $value)
        {
            if ((string) $option !== "info")
            {
                $newSection->addChild($option, $value);
            }
        }
    }

    // Tidy Up
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML());

    return simplexml_load_string($dom->saveXML());
}

?>
