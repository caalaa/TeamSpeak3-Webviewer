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
 * Returns all available languages as an array
 * @since 0.9
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
            require $path . "/" . $file . "/" . "lang.php";

            $languages[$file] = $l10_lang;
        }
    }
    return $languages;
}

/**
 * Returns an Alert/ Error
 * @todo add Code
 * @param type $message
 * @return type 
 */
function tsv_throwAlert($message)
{
    return;
}

/**
 * Returns a Warning
 * @todo add Code
 * @param type $message
 * @return type 
 */
function tsv_throwWarning($message)
{
    return;
}

?>
