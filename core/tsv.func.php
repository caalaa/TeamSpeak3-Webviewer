<?php

/**
 * Provides basic functions for the webviewer
 */

/**
 * Returns all available languages as an array
 * @param type $customPath
 * @return type 
 * @subpackage php-gettext
 */
function tsv_getLanguages($customPath=NULL)
{
    $languages = array();

    $path = realpath("./l10n");

    if (!empty($customPath)) $path = $customPath;
    $path = realpath($path);

    $handler = opendir($path);

    while ($file = readdir($handler))
    {
        if ($file != "." && $file != "..")
        {
            require $path."/".$file."/"."lang.php";
            
            $languages[$file] = $l10_lang;
        }
    }

    return $languages;
}

?>
