<?php

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

/**
 * Replaces a html file with its values
 * @deprecated gettext implementation
 * @param type $file
 * @param type $vals
 * @param type $loc
 * @return html code of the replaced file
 */
function replaceValues($file, $vals=NULL, $loc=FALSE)
{
    $lang = (array) getLanguageFile($loc);


    $data = array();

    if ($vals != NULL)
    {
        (array) $data = array_merge((array) $lang, (array) $vals);
    }
    else
    {
        $data = $lang;
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
        $html['selector'] .= '<p><fieldset><button style="width:200px;" onclick="javascript: setconfig(\'' . $file . '\')">' . $file . ' ('._('edit').')</button><span onclick="javascript: showViewer(\'' . $file . '\');"style="padding:4px; margin-left:15px; cursor: pointer;" class="ui-state-highlight ui-corner-all">' . _('Show Viewer') . '</span><span onclick="javascript: flushCache(\'' . $file .'.xml\');"style="padding:4px; margin-left:10px; cursor: pointer;" class="ui-state-highlight ui-corner-all">' . _('Flush cache') . '</span><span onclick="javascript: deleteConfig(\'' . $file .'.xml\');"style="padding:4px; margin-left:10px; cursor: pointer;" class="ui-state-highlight ui-corner-all">' . _('delete configfile') . '</span></fieldset></p>';
    }
    return $html;
}

/**
 * Returns the $data for the config-editing
 * @todo include directly in file
 * @todo Adapt Language-section to new i18n
 * @return string 
 */
function createEditHtml()
{
    $html = array();

    $config = $_SESSION['config'];
    $configfile = simplexml_load_string($_SESSION['config_xml']);



    $html['serveradress_value'] = $configfile->host;
    $html['queryport_value'] = $configfile->queryport;
    $html['serverport_value'] = $configfile->vserverport;


    // Login
    if ($configfile->login_needed == "true" || $configfile->login_needed == '')
    {
        $html['login_html'] = '<input type="radio" name="login_needed" value="true" checked="checked"> ' . _('Yes') . '<br>
            <input type="radio" name="login_needed" value="false"> ' . _('No');
    }
    else
    {
        $html['login_html'] = '<input type="radio" name="login_needed" value="true"> ' . _('Yes') . '<br>
            <input type="radio" name="login_needed" value="false" checked="checked"> ' . _('No');
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

        $xml = getXmlFile("../modules/$module/$module.xml");
        $description = $xml->info->{'description_' . $_SESSION['lang']};
        $mod_sort_enabled .= '<li id="' . $module . '" class="ui-state-highlight"><a href="core/xmledit.php?module=' . $module . '" class="color" >' . $module . '</a><span title="'.$description.'" style="float:right; margin-top:auto; margin-bottom:auto;" class="ui-icon ui-icon-info" id="tt"></span></li>';
    }

    // Disabled Modules
    foreach ($modules as $module)
    {
        $xml = getXmlFile("../modules/$module/$module.xml");
        $description = $xml->info->{'description_' . $_SESSION['lang']};
        $mod_sort_disabled .= '<li id="' . $module . '" class="ui-state-default"><a href="core/xmledit.php?module=' . $module . '" class="color">' . $module . '</a><span title="'.$description.'" style="float:right; margin-top:auto; margin-bottom:auto;" class="ui-icon ui-icon-info" id="tt"></span></li>';
    }

    $mod_sort_enabled .= '</ul>';
    $mod_sort_disabled .= '</ul>';

    $html['mod_sort_enabled'] = $mod_sort_enabled;
    $html['mod_sort_disabled'] = $mod_sort_disabled;

    // Servericons
    if ($configfile->use_serverimages == "true" || (string) $configfile->use_serverimages == '')
    {
        $html['servericons_radio'] = '<input type="radio" name="servericons" value="true" checked="checked"> ' . _('Enabled') . '<br>
            <input type="radio" name="servericons" value="false"> ' . _('Disabled');
    }
    else
    {
        $html['servericons_radio'] = '<input type="radio" name="servericons" value="true"> ' . _('Enabled') . '<br>
            <input type="radio" name="servericons" value="false"  checked="checked"> ' . _('Disabled');
    }

    // Imagepack
    $imagepacks = getImagePacks();

    natcasesort($imagepacks);

    $html['imagepack_html'] = '';

    foreach ($imagepacks as $pack)
    {
        if ((string) $configfile->imagepack == $pack)
                $html['imagepack_html'] .= '<input type="radio" name="imagepack" value="' . $pack . '" checked="checked"> ' . $pack . '<br>';
        else
                $html['imagepack_html'] .= '<input type="radio" name="imagepack" value="' . $pack . '"> ' . $pack . '<br>';
    }
    
    // Style
    $styles = getStyles();
    
    $html['style_html'] = '';
    
    foreach($styles as $style)
    {
        if((string)$configfile->style == $style)
                $html['style_html'] .= '<input type="radio" name="style" value="' . $style . '" checked="checked"> ' . $style . '<br>';
        else
                $html['style_html'] .= '<input type="radio" name="style" value="' . $style . '"> ' . $style . '<br>';
    }

    // Arrows
    if ($configfile->show_arrows == "true" || $configfile->show_arrows == '')
    {
        $html['arrow_html'] = '<input type="radio" name="arrows" value="true" checked="checked"> ' . _('Enabled') . '<br>
            <input type="radio" name="arrows" value="false"  > ' . _('Disabled');
    }
    else
    {
        $html['arrow_html'] = '<input type="radio" name="arrows" value="true" > ' . _('Enabled') . '<br>
            <input type="radio" name="arrows" value="false" checked="checked"> ' . _('Disabled');
    }

    // Caching
    if ($configfile->enable_caching == "true" || $configfile->enable_caching = '')
    {
        $html['caching_html'] = '<input type="radio" name="caching" value="true" checked="checked"> ' . _('Yes') . '<br>
            <input type="radio" name="caching" value="false"  > ' . _('No');
    }
    else
    {
        $html['caching_html'] = '<input type="radio" name="caching" value="true" > ' . _('Yes') . '<br>
            <input type="radio" name="caching" value="false" checked="checked" > ' . _('No');
    }

    // Standard Cachetime
    $html['standard_caching_html'] = '<input type="text" name="standard_caching" value="' . (string) $configfile->standard_cachetime . '" />';

    // Language
    if ((string) $configfile->language == "de" || ( (string) $configfile->language == '' && $_SESSION['lang'] == 'de'))
    {
        $html['language_html'] = '<input type="radio" name="language" value="de" checked="checked" > ' . _('German') . '<br>
            <input type="radio" name="language" value="en"  > ' . _('English');
    }
    else
    {
        $html['language_html'] = '<input type="radio" name="language" value="de" > ' . _('German') . '<br>
            <input type="radio" name="language" value="en" checked="checked" > ' . _('English');
    }

    return $html;
}

/**
 * Returns the language file as an XML-Object
 * @deprecated gettext implementation
 * @param type $loc
 * @return type 
 */
function getLanguageFile($loc)
{
    $lang = $_SESSION['lang'];

    if (!$loc)
    {
        if ($lang == "en") return simplexml_load_file("i18n/en.i18n.xml");
        else return simplexml_load_file("i18n/de.i18n.xml");
    }
    else
    {
        if ($lang == "en") return simplexml_load_file("../i18n/en.i18n.xml");
        else return simplexml_load_file("../i18n/de.i18n.xml");
    }
}

?>
