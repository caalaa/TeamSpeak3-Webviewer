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
 * Returns the $data array for select_config.php
 * @todo include directly in file
 * @return string 
 */
function createConfigHtml()
{
    $html = array();

    $html['selector'] = '';

    if (count(getConfigFiles("../config")) == 0)
    {
        $html['selector'] = '-';
        return $html;
    }

    $files = array();
    $files = getConfigFiles("../config");

    foreach ($files as $file)
    {
        $html['selector'] .= '<p><fieldset><button style="width:200px;" onclick="javascript: setconfig(\'' . $file . '\')">' . $file . ' (' . __('edit') . ')</button><span onclick="javascript: showViewer(\'' . $file . '\');"style="padding:4px; margin-left:15px; cursor: pointer;" class="ui-state-highlight ui-corner-all">' . __('Show Viewer') . '</span><span onclick="javascript: flushCache(\'' . $file . '.xml\');"style="padding:4px; margin-left:10px; cursor: pointer;" class="ui-state-highlight ui-corner-all">' . __('Flush cache') . '</span><span onclick="javascript: deleteConfig(\'' . $file . '.xml\');"style="padding:4px; margin-left:10px; cursor: pointer;" class="ui-state-highlight ui-corner-all">' . __('delete configfile') . '</span></fieldset></p>';
    }
    return $html;
}

/**
 * Returns the $data for the config-editing
 * @todo include directly in file
 * @return string 
 */
function createEditHtml()
{
    global $utils;

    $html = array();

    $configfile = simplexml_load_string($_SESSION['config_xml']);

    $html['serveradress_value'] = $configfile->host;
    $html['queryport_value'] = $configfile->queryport;
    $html['serverport_value'] = $configfile->vserverport;


    // Login
    if ($configfile->login_needed == "true" || $configfile->login_needed == '')
    {
        $html['login_html'] = '<input type="radio" name="login_needed" value="true" checked="checked"> ' . __('Yes') . '<br>
            <input type="radio" name="login_needed" value="false"> ' . __('No');
    }
    else
    {
        $html['login_html'] = '<input type="radio" name="login_needed" value="true"> ' . __('Yes') . '<br>
            <input type="radio" name="login_needed" value="false" checked="checked"> ' . __('No');
    }

    $html['username_value'] = (string) $configfile->username;
    $html['password_value'] = (string) $configfile->password;

    // Modules
    $modules = getModules();

    $html['module_html'] = '';

    $mod_sort_enabled = '<ul id="sort1" class="sortable">';
    $mod_sort_disabled = '<ul id="sort2" class="sortable">';
    natcasesort($modules);

    $enabled_modules = explode(",", $configfile->modules);
    unset($enabled_modules[array_search("htmlframe", $enabled_modules)]);
    unset($enabled_modules[array_search("style", $enabled_modules)]);

    // Enabled Modules
    foreach ($enabled_modules as $module)
    {
        unset($modules[array_search($module, $modules)]);
        $mod_sort_enabled .= '<li id="' . $module . '" class="ui-state-highlight"><a href="core/xmledit.php?module=' . $module . '" class="color" >' . $module . '</a></li>';
    }

    // Disabled Modules
    foreach ($modules as $module)
    {
        $mod_sort_disabled .= '<li id="' . $module . '" class="ui-state-default"><a href="core/xmledit.php?module=' . $module . '" class="color">' . $module . '</a></li>';
    }

    $mod_sort_enabled .= '</ul>';
    $mod_sort_disabled .= '</ul>';

    $html['mod_sort_enabled'] = $mod_sort_enabled;
    $html['mod_sort_disabled'] = $mod_sort_disabled;

    // Servericons
    if ($configfile->use_serverimages == "true" || (string) $configfile->use_serverimages == '')
    {
        $html['servericons_radio'] = '<input type="radio" name="servericons" value="true" checked="checked"><span> ' . __('Enabled') . '</span><br>
            <input type="radio" name="servericons" value="false"><span> ' . __('Disabled') . '</span>';
    }
    else
    {
        $html['servericons_radio'] = '<input type="radio" name="servericons" value="true"><span> ' . __('Enabled') . '<br>
            <input type="radio" name="servericons" value="false"  checked="checked"><span> ' . __('Disabled') . '</span>';
    }

    // Imagepack
    $imagepacks = getImagePacks();

    natcasesort($imagepacks);

    $html['imagepack_html'] = '';

    foreach ($imagepacks as $pack)
    {
        if ((string) $configfile->imagepack == $pack) $html['imagepack_html'] .= '<input type="radio" name="imagepack" value="' . $pack . '" checked="checked"><span> ' . $pack . '</span><br>';
        else $html['imagepack_html'] .= '<input type="radio" name="imagepack" value="' . $pack . '"><span> ' . $pack . '</span><br>';
    }

    // Style
    $styles = getStyles();

    $html['style_html'] = '';

    foreach ($styles as $style)
    {
        if ((string) $configfile->style == $style) $html['style_html'] .= '<input type="radio" name="style" value="' . $style . '" checked="checked"><span> ' . $style . '</span><br>';
        else $html['style_html'] .= '<input type="radio" name="style" value="' . $style . '"><span> ' . $style . '</span><br>';
    }

    // Arrows
    if ($configfile->show_arrows == "true" || $configfile->show_arrows == '')
    {
        $html['arrow_html'] = '<input type="radio" name="arrows" value="true" checked="checked"><span> ' . __('Enabled') . '<br>
            <input type="radio" name="arrows" value="false"  ><span> ' . __('Disabled') . '</span>';
    }
    else
    {
        $html['arrow_html'] = '<input type="radio" name="arrows" value="true" > ' . __('Enabled') . '<br>
            <input type="radio" name="arrows" value="false" checked="checked"> ' . __('Disabled') . '</span>';
    }

    // Caching
    if ($configfile->enable_caching == "true" || $configfile->enable_caching = '')
    {
        $html['caching_html'] = '<input type="radio" name="caching" value="true" checked="checked"> ' . __('Yes') . '<br>
            <input type="radio" name="caching" value="false"  > ' . __('No');
    }
    else
    {
        $html['caching_html'] = '<input type="radio" name="caching" value="true" > ' . __('Yes') . '<br>
            <input type="radio" name="caching" value="false" checked="checked" > ' . __('No');
    }

    // Standard Cachetime
    $html['standard_caching_html'] = '<input type="text" name="standard_caching" value="' . (string) $configfile->standard_cachetime . '" />';

    // Language
    $html['language_html'] = "";
    $languages = $utils->getLanguages();
    $selected_lang = (string)$_SESSION['lang'];
    
    if(isset($configfile->language) && (string)$configfile->language != "")
    {
        $selected_lang = (string) $configfile->language;
    }

    foreach ($languages as $langCode => $langOptions)
    {
        if ($langCode == $selected_lang) $html['language_html'] .= '<input type="radio" name="language" checked="checked" value="' . $langCode . '">' . $langOptions['lang'] . ' <br>';
        else $html['language_html'] .= '<input type="radio" name="language"  value="' . $langCode . '">' . $langOptions['lang'] . ' <br>';
    }
    return $html;
}

?>
