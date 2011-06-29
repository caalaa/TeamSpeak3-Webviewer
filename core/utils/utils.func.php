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
 * Unregisters globals thanks to bohwaz (http://php.net)
 * @return type 
 */
function unregister_globals()
{
    if (!ini_get('register_globals'))
    {
        return false;
    }

    foreach (func_get_args() as $name)
    {
        foreach ($GLOBALS[$name] as $key => $value)
        {
            if (isset($GLOBALS[$key])) unset($GLOBALS[$key]);
        }
    }
}

/**
 * Simple bool to text converter
 * @param type var
 * @return type 
 */
function bool2text($var)
{
    if ($var)
    {
        return 'true';
    }
    else
    {
        return 'false';
    }
}

/**
 * Replaces {} in the Code with the given data
 * @deprecated new MVC
 * @param type $file
 * @param type $values
 * @param type $languagefile
 * @return type 
 */
function replaceValues($file, $values, $languagefile)
{
    $data = array();
    $lang = simplexml_load_file($languagefile);

    if ($values != NULL)
    {
        (array) $data = array_merge((array) $lang, (array) $values);
    }
    else
    {
        $data = (array) $lang;
    }

    $html = file_get_contents($file);

    $matches = array();
    preg_match_all("/{.*?}/", $html, $matches);

    foreach ($matches[0] as $match)
    {
        $match_raw = $match;
        $match_norm = preg_replace("/[{}]/", "", strtolower($match_raw));

        $html = str_replace($match_raw, (string) $data[$match_norm], $html);
    }
    return $html;
}

?>