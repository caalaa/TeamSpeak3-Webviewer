<?php

/**
 *  This file is part of devMX TeamSpeak3 Webviewer.
 *  Copyright (C) 2011 - 2012 Max Rath and Maximilian Narr
 *
 *  devMX TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
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
 *  along with devMX TeamSpeak3 Webviewer.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Provides general Utils
 * @since 0.9
 */
class tsvUtils
{

    private $customPath;
    private $versionPath = "http://cdn.devmx.de/tswebviewer/update.xml";

    /**
     * Contruct
     * @since 0.9
     * @param type $customPath set a custom root path of the viewer, if null is set, current one will be used
     */
    function __construct($customPath=NULL)
    {
        if ($customPath == NULL) $this->customPath = realpath("./");
        else $this->customPath = realpath($customPath);
    }

    /**
     * Returns all available languages as an array
     * @since 0.9
     * @param type $customPath
     * @return type 
     * @subpackage php-gettext
     */
    public function getLanguages()
    {
        $languages = array();
        $path = $this->customPath . "/l10n";
        $handler = opendir($path);
        while ($file = readdir($handler))
        {
            if ($file != "." && $file != ".." && preg_match("/([a-z]){2}(_){1}([A-Z]){2}/", $file) >= 1 )
            {
                require $path . "/" . $file . "/" . "lang.php";

                $languages[$file] = $l10_lang;
            }
        }
        uasort($languages, array($this, "languagesort"));

        return $languages;
    }

    /**
     * Checks if a new version of the webviewer is available
     * @since 1.0
     * @return type 
     */
    public function versionCompare()
    {
        if (function_exists("ini_get") && (bool) ini_get("allow_url_fopen"))
        {
            $xml = simplexml_load_string(file_get_contents($this->versionPath));
            
            if(version_compare(version, (string)$xml->version, "<"))
            {
                return $xml;
            }
        }
        return false;
    }

    /**
     * Sorting function for sorting of languages
     * @since 0.9
     * @param type $a
     * @param type $b
     * @return type 
     */
    private function languagesort($a, $b)
    {
        return strnatcmp($a['lang'], $b['lang']);
    }

}

?>
