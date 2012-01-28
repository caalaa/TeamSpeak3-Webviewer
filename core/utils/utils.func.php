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
 * Throws alert
 * @param type $message
 * @param type $code error code
 * @return string 
 */
function throwAlert($message, $code=NULL, $display=false)
{
    if ($display == true)
    {
        $html = '<div class="alert" style="margin-bottom:5px;">';
    }
    else
    {
        $html = '<div class="alert" style="margin-bottom:5px; display:none;">';
    }

    $html .= '<div class="ui-widget">
                    <div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">';

    if ($code != NULL) $html .= '<p><a href="http://devmx.de/en/software/teamspeak3-webviewer/faq-2" target="_blank">' . __('Error') . ' ' . $code . '</a></p>';

    $html .= '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' . $message . '</p>                       
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
function throwWarning($message, $display=false)
{
    if ($display == true)
    {
        $html = '<div class="warning" style="margin-bottom:5px;">';
    }
    else
    {
        $html = '<div class="warning" style="margin-bottom:5px; display:none;">';
    }


    $html .= '<div class="ui-widget">
				<div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>' . $message . '</p>
				</div>
			</div>
                        </div>';
    return $html;
}

/**
 * Throws info
 * @since 0.9
 * @param type $message
 * @return string 
 */
function throwInfo($message, $display=false)
{
    if ($display == true)
    {
        $html = '<div class="info" style="margin-bottom:5px;">';
    }
    else
    {
        $html = '<div class="info" style="margin-bottom:5px; display:none;">';
    }


    $hml .= '<div class="ui-widget">
				<div class="ui-state-default ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>' . $message . '</p>
				</div>
			</div>
                        </div>';
    return $html;
}

?>