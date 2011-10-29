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
 * Checks if a password is setted
 * @return type 
 */
function passwordSetted()
{
    if (!file_exists("pw.xml")) return false;

    $file = file_get_contents("pw.xml");

    if ($file == '') return false;

    return true;
}

/**
 * Sets a new password
 * @param type $password
 * @return type 
 */
function setPassword($password)
{
    $password = sha1(md5($password));

    if (!file_put_contents("pw.xml", $password))
    {
        return false;
    }
    return true;
}

/**
 * Returns all modules in an array
 * @return type 
 */
function getModules()
{
    $modules = array();
    $dir = opendir("../modules");

    while ($module = readdir($dir))
    {
        if ($module != '..' && $module != '.' && file_exists("../modules/$module/$module.xml")  && !moduleIsAbstract($module))
        {
            if (file_exists("../modules/$module/$module.php") && file_exists("../modules/$module/$module.xml"))
            {
                $modules[] = $module;
            }
        }
    }

    return $modules;
}

/**
 * Checks if a module is abstract or no
 * @param type $module modulename
 * @return type true if abstract else false
 */
function moduleIsAbstract($module)
{
    $xml = simplexml_load_file("../modules/$module/$module.xml");

    if ($xml->info->abstract == "true") return true;
    return false;
}

/**
 * Returns all available imagepacks as an array
 * @return type array if imagepacks
 */
function getImagePacks()
{
    $packs = array();
    $blacklist = array('..', '.', 'serverimages', 'no.png');

    $dir = opendir("../images");
    
    while ($imagepack = readdir($dir))
    {
        if (!in_array($imagepack, $blacklist))
        {
            $packs[] = $imagepack;
        }
    }

    return $packs;
}

/**
 * Returns all available styles as an array
 * @return type array of styles
 */
function getStyles()
{
    $styles = array();
    $blacklist = array('..', '.', 'template');

    $dir = opendir("../styles");

    while ($style = readdir($dir))
    {
        if (!in_array($style, $blacklist) && file_exists("../styles/$style/$style.css"))
        {
            $styles[] = $style;
        }
    }

    return $styles;
}

/**
 * Flushs the cache of a given configfile
 * @param type $config
 * @return type 
 */
function flushCache($config)
{
    $config .= '.xml';
    
    if (!file_exists("../config/" . $config))
    {
        return throwAlert(__('The configfile does not exist'));
    }
    else
    {
        $config = simplexml_load_file("../config/" . $config );

        if ((string) $config->host == "" || (string) $config->host == NULL || (string) $config->queryport == "" || (string) $config->queryport == NULL || (string) $config->vserverport == "" || (string) $config->vserverport == NULL)
        {
            return throwAlert(__('Not all necessary information is given in the configfile to flush the cache.'));
        }
        else
        {
            $path = "../cache/" . (string) $config->host . (string) $config->queryport . "/" . (string) $config->vserverport . "/";


            // query
            $dir = opendir($path . "query");
            while ($file = readdir($dir))
            {
                if ($file != ".." && $file != "." && $file != "time") unlink($path . "query/" . $file);
            }

            // query/time
            $dir = opendir($path . "query/time");
            while ($file = readdir($dir))
            {
                if ($file != ".." && $file != ".") unlink($path . "query/time/" . $file);
            }

            // server/images
            $dir = opendir($path . "server/images");
            while ($file = readdir($dir))
            {
                if ($file != ".." && $file != "." && $file != "time") unlink($path . "server/images/" . $file);
            }
            return throwWarning(__('Cache flushed.'));
        }
    }
}

/**
 * Deletes a configfile
 * @param type $file
 * @return type 
 */
function deleteConfigfile($file)
{
    $file .= '.xml';
    if (!file_exists("../config/" . $file))
    {
        return throwAlert(__('The configfile you wanted to delete does not exist'));
    }
    else
    {
        unlink("../config/" . $file);
        return throwWarning(__('The configfile has been successfully deleted'));
    }
}

/**
 * Returns warnings if some necessary functions are not available
 * @return warning
 */
function checkFunctions()
{
    $html = '';
    $functions = Array("fsockopen");
    foreach ($functions as $value)
    {
        if (!function_exists($value))
        {
            // Create Warnings
            $html .= throwAlert(__('The necessary function') . ' ' . $value . ' ' . __('is not available on your webspace. Please contact your service provider.'), 25);
        }
    }

    return $html;
}

/**
 * Checks if the directories given are writeable
 * @return array key = directoryname value = true, if writable, else false
 * @since 1.0
 * @param type $directories 
 */
function checkPermissions($directories)
{
    $results = array();

    foreach ($directories as $dir)
    {
        $dir = realpath($dir);
        if (is_writable($dir))
        {
            $results[$dir] = true;
            continue;
        }
        else
        {
            setChmodRecursive($dir, 0775);

            if (is_writable($dir))
            {
                $results[$dir] = true;
                continue;
            }
            else
            {
                setChmodRecursive($dir, 0777);

                if (is_writable($dir))
                {
                    $results[$dir] = true;
                    continue;
                }
            }
        }
        $results[$dir] = false;
    }
    return $results;
}

/**
 * sets chmod recursive to the directory
 * @since 0.9
 * @param type $directory
 * @param type $chmod 
 */
function setChmodRecursive($path, $chmod)
{
    if (is_file($path))
    {
        chmod($path, $chmod);
    }
    elseif (is_dir($path))
    {
        $foldersAndFiles = scandir($path);

        $entries = array_slice($foldersAndFiles, 2);

        foreach ($entries as $entry)
        {
            if (substr($path, -1) !== "/") setChmodRecursive($path . "/" . $entry, $chmod);
            else setChmodRecursive($path . $entry, $chmod);
        }

        chmod($path, $chmod);
    }
}

/**
 * Checks if a variable is null or emtpy
 * @since 1.0
 * @param type $var 
 */
function isNullOrEmtpy($var)
{
    if (!isset($var) || empty($var) || $var == null || $var = "") return true;
    return falsE;
}

?>
