<?php
/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

// Returns an XML-File as Simple-XML-Object
function getXmlFile($path)
{
    if (file_exists($path))
        return simplexml_load_file ($path);

    return false;
}

// Saves an XML-File
function saveXmlFile($path, $data)
{
    if (file_exists($path))
        unlink ($path);

    file_put_contents($path, $data->asXML());
    return true;
}

// Gets all config-files
function getConfigFiles($dir)
{
    $handler = opendir($dir);
    $files = array();

    while($file = readdir($handler))
    {
        if($file != "." && $file != ".." && $file != 'template.xml')
            $files[] = str_replace(".xml", "", $file);
    }

    return $files;
}
?>
