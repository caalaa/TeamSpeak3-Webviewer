<?php

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

/**
 * Returns the language of the browser
 * @deprecated gettext implementation
 * @return string 
 */
function getLang()
{
    $data = $_SERVER['HTTP_USER_AGENT'];
    $lang = '';

    if (preg_match("/en-US/", $data)) $lang = 'en';
    else if (preg_match("/de-DE/", $data)) $lang = 'de';

    if ($lang == '') $lang = 'en';

    return $lang;
}

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

    if (!file_put_contents("pw.xml", $password)) ;
    return false;
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
        if ($module != '..' && $module != '.' && !moduleIsAbstract($module))
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

// Returns all imagepacks in an array
function getImagePacks()
{
    $packs = array();

    $dir = opendir("../images");

    while ($imagepack = readdir($dir))
    {
        if ($imagepack != '..' && $imagepack != '.' && $imagepack != 'serverimages')
                $packs[] = $imagepack;
    }

    return $packs;
}

// Returns all styles in an array
function getStyles()
{
    $styles = array();

    $dir = opendir("../styles");

    while ($style = readdir($dir))
    {
        if ($style != ".." && $style != "." && file_exists("../styles/$style/$style.css"))
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
    if (!file_exists("../config/" . $config))
    {
        return throwAlert($lang->not_exist);
    }
    else
    {
        $config = simplexml_load_file("../config/" . $config);

        if ((string) $config->host == "" || (string) $config->host == NULL || (string) $config->queryport == "" || (string) $config->queryport == NULL || (string) $config->vserverport == "" || (string) $config->vserverport == NULL)
        {
            return throwAlert(__e('Not all necessary information is given in the configfile to flush the cache.'));
        }
        else
        {
            $path = "../cache/" . (string) $config->host . (string) $config->queryport . "/" . (string) $config->vserverport . "/";


            // query
            $dir = opendir($path . "query");
            while ($file = readdir($dir))
            {
                if ($file != ".." && $file != "." && $file != "time")
                        unlink($path . "query/" . $file);
            }

            // query/time
            $dir = opendir($path . "query/time");
            while ($file = readdir($dir))
            {
                if ($file != ".." && $file != ".")
                        unlink($path . "query/time/" . $file);
            }

            // server/images
            $dir = opendir($path . "server/images");
            while ($file = readdir($dir))
            {
                if ($file != ".." && $file != "." && $file != "time")
                        unlink($path . "server/images/" . $file);
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
            $html .= throwAlert(__('The necessary function') . ' ' . $value . ' ' . __('is not available on your webspace. Please contact your service provider'));
        }
    }

    return $html;
}

/**
 * Echos gettext message
 * @since 0.9
 * @param type $message 
 */
function _e($message)
{
    echo(_($message));
}

/**
 * Throws alert
 * @param type $message
 * @return string 
 */
function throwAlert($message)
{
    $html = '<div class="alert" style="margin-bottom:5px;">
            <div class="ui-widget">
                    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
                            <p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' . $message. '</p>

                    </div>
            </div>
            </div>';
    return $html;
}

/**
 * Throws warning
 * @param type $message
 * @return string 
 */
function throwWarning($message)
{
    $html = '<div class="alert" style="margin-bottom:5px;">
            <div class="ui-widget">
				<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>' . $message. '</p>
				</div>
			</div>
                        </div>';
    return $html;
}

?>
