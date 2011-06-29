<?php

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

// Checks if data.xml needs a new entry
function needNewEntry($configfile)
{
    if (!file_exists(s_root."modules/stats/cache/$configfile.xml"))
    {
        file_put_contents(s_root."modules/stats/cache/$configfile.xml", file_get_contents(s_root."modules/stats/cache/template.xml"));
        return true;
    }

    $xml = simplexml_load_file(s_root."modules/stats/cache/$configfile.xml");

    if ($xml->updated == '' || time() - $xml->updated >= 500)
    {
        return true;
    }
    return false;

}

// Adds an entry to the DOM
function addEntry($clients_online, $configfile)
{
    $xml = simplexml_load_file(s_root."modules/stats/cache/$configfile.xml");

    $xml->updated = (string) time();

    $entry = $xml->addChild('entry');
    $entry->addChild('clients', $clients_online);
    $entry->addChild('timestamp', (string) time());

    $handle = fopen(s_root."modules/stats/cache/$configfile.xml", "w");
    fwrite($handle, $xml->asXML());
    fclose($handle);

}

// Returns a javascript 'array' of the clienthistory
function createJS($name, $xml, $locale)
{
    $js = $name . '=[';

    $values = array();

    if ($locale == NULL)
        $locale = "de_DE.UTF-8";

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

// returns the Plotoptions
function createPlotOptions($config)
{
    $js = '';

    $tab = "false";

    if ($config['x_formatString'] == NULL)
        $config['x_formatString'] = "%#H:%M";

    if ($config['y_formatString'] == NULL)
        $config['y_formatString'] = "%d";

    if ($config['l_style'] == NULL)
        $config['l_style'] = "filledCircle";

    if ($config['use_tab'])
        $tab = "true";
    else
        $tab = "false";

    $js .= '    var plotoptions = {
        "title": "' . __('User history') . '", 
        "x_formatString":"' . $config['x_formatString'] . '", 
        "y_formatString": "' . $config['y_formatString'] . '", 
        "style": "' . $config['l_style'] . '", 
        "tab": ' . $tab . '
    };';

    return $js;

}

?>
