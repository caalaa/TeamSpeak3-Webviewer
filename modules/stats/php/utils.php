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
 * Checks if a new entry is needed
 * @param type $configfile
 * @return type true if needed, else false
 */
function needNewEntry($configfile)
{
    $fileDir = cacheDir . "stats/$configfile.xml";
    if (!file_exists($fileDir))
    {
        if (!is_dir(cacheDir . "stats"))
        {
            mkdir(cacheDir . "stats", 0775);
        }

        file_put_contents($fileDir, file_get_contents(s_root . "modules/stats/cache/template.xml"));
        return true;
    }

    $xml = simplexml_load_file($fileDir);

    if ($xml->updated == '' || time() - $xml->updated >= 500)
    {
        return true;
    }
    return false;
}

/**
 * Adds a new entry to the caching-file
 * @param type $clients_online
 * @param type $configfile 
 */
function addEntry($clients_online, $configfile)
{
    $fileDir = cacheDir . "stats/$configfile.xml";

    $xml = simplexml_load_file($fileDir);

    $xml->updated = (string) time();

    $entry = $xml->addChild('entry');
    $entry->addChild('clients', $clients_online);
    $entry->addChild('timestamp', (string) time());

    $handle = fopen($fileDir, "w");
    fwrite($handle, $xml->asXML());
    fclose($handle);
}

/**
 * Returns javascript array for the clienthistory
 * @param type $name
 * @param type $xml
 * @param string $locale
 * @return string 
 */
function createJS($name, $xml, $locale)
{
    $js = $name . '=[';

    $values = array();

    if ($locale == NULL) $locale = "de_DE.UTF-8";

    setlocale(LC_TIME, $locale);

    foreach ($xml->entry as $entry)
    {
        $timestamp = $entry->timestamp;
        $values[] = '[\'' . strftime("%Y-%m-%d %H:%M", (int) $timestamp) . '\',' . $entry->clients . ']';
    }


    $values = array_reverse($values);
    $values = array_slice($values, 0, 10);


    $js .= implode(",", $values) . '];';

    return $js;
}

/**
 * Returns the minimum client count
 * @return type int
 */
function getMinClients($xml)
{
    $entries = array();

    foreach ($xml->entry as $entry)
    {
        $entries[] = (int) $entry->clients;
    }

    $entries = array_reverse($entries);
    $entries = array_unique($entries);
    $entries = array_slice($entries, 0, 10);
    $entries = preg_grep("/[123456789](.*)/", $entries);
    natcasesort($entries);

    foreach ($entries as $value)
    {
        if ((int) $value != (int) 0) return $value;
    }
}

/**
 * Returns the plotoptions
 * @param type $config
 * @return string 
 */
function createPlotOptions($config)
{
    $js = '';

    $tab = "false";

    if ($config['x_formatString'] == NULL) $config['x_formatString'] = "%#H:%M";

    if ($config['y_formatString'] == NULL) $config['y_formatString'] = "%d";

    if ($config['l_style'] == NULL) $config['l_style'] = "filledCircle";

    if ($config['use_tab']) $tab = "true";
    else $tab = "false";

    $js .= '    var plotoptions = {
        "title": "' . __('User history') . '", 
        "x_formatString":"' . $config['x_formatString'] . '", 
        "y_formatString": "' . $config['y_formatString'] . '", 
        "style": "' . $config['l_style'] . '", 
        "tab": ' . $tab . ',
        "min": ' . $config['min'] . '
    };';

    return $js;
}

?>
