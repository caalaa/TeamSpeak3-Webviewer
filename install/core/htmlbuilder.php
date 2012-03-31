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
 * Returns the $data array for select_config.php
 * @return string 
 * @deprecated directly in html file
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
        $html['selector'] .= '<fieldset class="config">';
        $html['selector'] .= '<a href="index.php?action=set_config&configname=' . $file . '"><span class="ui-corner-all ui-state-default">' . $file . ' (' . __('edit') . ')</span></a>';
        $html['selector'] .= '<a href="../index.php?config=' . $file . '" target="_blank"><span class="ui-corner-all ui-state-highlight">' . __('show') . '</span></a>';
        $html['selector'] .= '<a href="index.php?action=fc&config=' . $file . '"><span class="ui-corner-all ui-state-highlight">' . __('flush cache') . '</span></a>';
        $html['selector'] .= '<a href="index.php?action=delete&config=' . $file . '"><span class="ui-corner-all ui-state-highlight">' . __('delete file') . '</span></a>';
        $html['selector'] .= '</fieldset>';
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
    $template = simplexml_load_file("../config/template.xml");

    $html['config'] = $configfile;
    $html['serveradress_value'] = (string) $configfile->host;
    $html['queryport_value'] = (string) $configfile->queryport;
    $html['serverport_value'] = (string) $configfile->vserverport;
    $html['display-filter'] = (string) $configfile->filter;

    $html['login_needed'] = (string) $configfile->login_needed;

    $html['username_value'] = (string) $configfile->username;
    $html['password_value'] = (string) $configfile->password;

    $html['show-images'] = (string) $configfile->show_icons;

    $html['show_hierarchy_icons'] = (string) $configfile->show_hierarchy_icons;

    $html['caching-method'] = (string) $configfile->cache_method;

    // Modules
    $modules = getModules();

    natcasesort($modules);

    $enabled_modules = explode(",", $configfile->modules);
    unset($enabled_modules[array_search("htmlframe", $enabled_modules)]);
    unset($enabled_modules[array_search("style", $enabled_modules)]);
    unset($enabled_modules[array_search("fullCache", $enabled_modules)]);

    $html['enabledModules'] = $enabled_modules;

    // Unset enabled modules
    foreach ($enabled_modules as $module)
    {
        unset($modules[array_search($module, $modules)]);
    }


    // Set disabled modules
    $html['disabledModules'] = $modules;


    // Servericons
    $html['downloadIcons'] = $configfile->use_serverimages;

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
        $html['arrow_html'] = '<input type="radio" name="arrows" value="true" checked="checked"><span> ' . __('Enabled') . '</span><br>
            <input type="radio" name="arrows" value="false"  ><span> ' . __('Disabled') . '<span>';
    }
    else
    {
        $html['arrow_html'] = '<input type="radio" name="arrows" value="true" ><span> ' . __('Enabled') . '<br></span>
            <input type="radio" name="arrows" value="false" checked="checked"><span> ' . __('Disabled') . '</span>';
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
    $selected_lang = (string) $_SESSION['lang'];

    if (isset($configfile->language) && (string) $configfile->language != "")
    {
        $selected_lang = (string) $configfile->language;
    }

    foreach ($languages as $langCode => $langOptions)
    {
        if ($langCode == $selected_lang) $html['language_html'] .= '<input type="radio" name="language" checked="checked" value="' . $langCode . '">' . $langOptions['lang'] . ' <br>';
        else $html['language_html'] .= '<input type="radio" name="language"  value="' . $langCode . '">' . $langOptions['lang'] . ' <br>';
    }

    // Date format
    if (isset($configfile->date_format) && $configfile->date_format != null && $configfile->date_format != "")
    {
        $html['date_format'] = (string) $configfile->date_format;
    }
    else
    {
        $html['date_format'] = (string) $template->date_format;
    }

    // Country icons
    $html['show_country_icons'] = (string) $configfile->show_country_icons;


    return $html;
}

?>
