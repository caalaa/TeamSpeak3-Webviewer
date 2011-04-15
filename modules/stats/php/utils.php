<?php

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

// Checks if data.xml needs a new entry
function needNewEntry()
{
    $xml = simplexml_load_file("modules/stats/cache/data.xml");

    if ($xml->updated == "" | time() - $xml->updated >= 500)
    {
        return true;
    }
    return false;

}

// Adds an entry to the DOM
function addEntry($clients_online)
{
    $xml = simplexml_load_file("modules/stats/cache/data.xml");

    $xml->updated = (string) time();

    $entry = $xml->addChild('entry');
    $entry->addChild('clients', $clients_online);
    $entry->addChild('timestamp', (string) time());

    $handle = fopen("modules/stats/cache/data.xml", "w");
    fwrite($handle, $xml->asXML());
    fclose($handle);

}

// Returns a javascript 'array' of the clienthistory
function createJS($name, $xml)
{
    $js = $name.'=[';

    $values = array();
    
    setlocale(LC_ALL, "de_DE.UTF8");
    
    foreach ($xml->entry as $entry)
    {
        $timestamp = $entry->timestamp;
        $values[] = '[\'' . strftime("%Y-%m-%d %H:%M", (int)$timestamp) . '\',' . $entry->clients . ']';
    }

    array_reverse($values);

    $values = array_slice($values, 0, 5);
    
    $js .= implode(",", $values) . '];';

    return $js;
}

?>
