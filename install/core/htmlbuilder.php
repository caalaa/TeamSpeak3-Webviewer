<?php

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
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

function createConfigHtml()
{
    $html = array();

    if (count(getConfigFiles("../config")) == 0)
    {
        $html['selector'] = '-';
        return $html;
    }

    $files = array();
    $files = getConfigFiles("../config");

    foreach ($files as $file)
    {
        $html['selector'] .= '<p><button onclick="javascript: setconfig(\'' . $file . '\')">' . $file . '</button></p>';
    }
    return $html;

}

function createEditHtml()
{
    $html = array();

    $lang = simplexml_load_file("i18n/" . $_SESSION['lang'] . '.i18n.xml');

    $config = $_SESSION['config'];
    $configfile = simplexml_load_string($_SESSION['config_xml']);



    $html['serveradress_value'] = $configfile->host;
    $html['queryport_value'] = $configfile->queryport;
    $html['serverport_value'] = $configfile->vserverport;
    $html['downloadport_value'] = $configfile->downloadport;

    // Login
    if ((bool) $configfile->login_needed)
    {
        $html['login_html'] = '<input type="radio" name="login_needed" value="true" checked="checked"> ' . (string) $lang->yes . '<br>
            <input type="radio" name="login_needed" value="false"> ' . (string) $lang->no;
    }
    else
    {
        $html['login_html'] = '<input type="radio" name="login_needed" value="true"> ' . (string) $lang->yes . '<br>
            <input type="radio" name="login_needed" value="false" checked="checked"> ' . (string) $lang->no;
    }

    $html['username_value'] = (string) $configfile->username;
    $html['password_value'] = (string) $configfile->password;

    // Modules
    $modules = getModules();

    natcasesort($modules);

    foreach ($modules as $module)
    {
        $xml = getXmlFile("../modules/$module/$module.xml");

        $description = $xml->info->{'description_'.$_SESSION['lang']};

        $configmods = explode(",", $configfile->modules);
        if (in_array($module, $configmods))
            $html['module_html'] .= '<p><input type="checkbox" name="module[]" checked="checked" value="' . $module . '"/><a href="core/xmledit.php?module=' . $module . '" id="tt" class="color" title="' . $description . '">' . $module . '</a></p>';
        else
            $html['module_html'] .= '<p><input type="checkbox" name="module[]" value="' . $module . '"/><a href="core/xmledit.php?module=' . $module . '" id="tt" class="color" title="' . $description . '">' . $module . '</a></p>';
    }

    // Servericons
    if ($configfile->use_serverimages == "true" || (string) $configfile->use_serverimages == '')
    {
        $html['servericons_radio'] = '<input type="radio" name="servericons" value="true" checked="checked"> ' . (string) $lang->yes . '<br>
            <input type="radio" name="servericons" value="false"> ' . (string) $lang->no;
    }
    else
    {
        $html['servericons_radio'] = '<input type="radio" name="servericons" value="true"> ' . (string) $lang->yes . '<br>
            <input type="radio" name="servericons" value="false"  checked="checked"> ' . (string) $lang->no;
    }

    // Imagepack
    $imagepacks = getImagePacks();

    natcasesort($imagepacks);

    foreach ($imagepacks as $pack)
    {
        if ((string) $configfile->imagepack == $pack)
            $html['imagepack_html'] .= '<input type="radio" name="imagepack" value="' . $pack . '" checked="checked"> ' . $pack . '<br>';
        else
            $html['imagepack_html'] .= '<input type="radio" name="imagepack" value="' . $pack . '"> ' . $pack . '<br>';
    }

    // Arrows
    if ($configfile->show_arrows == "true" || $configfile->show_arrows == '')
    {
        $html['arrow_html'] = '<input type="radio" name="arrows" value="true" checked="checked"> ' . (string) $lang->yes . '<br>
            <input type="radio" name="arrows" value="false"  > ' . (string) $lang->no;
    }
    else
    {
        $html['arrow_html'] = '<input type="radio" name="arrows" value="true" > ' . (string) $lang->yes . '<br>
            <input type="radio" name="arrows" value="false" checked="checked"> ' . (string) $lang->no;
    }

    // Caching

    if ($configfile->enable_caching == "true" || $configfile->enable_caching = '')
    {
        $html['caching_html'] = '<input type="radio" name="caching" value="true" checked="checked"> ' . (string) $lang->yes . '<br>
            <input type="radio" name="caching" value="false"  > ' . (string) $lang->no;
    }
    else
    {
        $html['caching_html'] = '<input type="radio" name="caching" value="true" > ' . (string) $lang->yes . '<br>
            <input type="radio" name="caching" value="false" checked="checked" > ' . (string) $lang->no;
    }

    // Standard Cachetime
    $html['standard_caching_html'] = '<input type="text" name="standard_caching" value="' . (string) $configfile->standard_cachetime . '" />';

    // Language
    if ((string) $configfile->language == "de" || ( (string) $configfile->language == '' && $_SESSION['lang'] == 'de'))
    {
        $html['language_html'] = '<input type="radio" name="language" value="de" checked="checked" > ' . (string) $lang->german . '<br>
            <input type="radio" name="language" value="en"  > ' . (string) $lang->english;
    }
    else
    {
        $html['language_html'] = '<input type="radio" name="language" value="de" > ' . (string) $lang->german . '<br>
            <input type="radio" name="language" value="en" checked="checked" > ' . (string) $lang->english;
    }

    return $html;

}

function getLanguageFile($loc)
{
    $lang = $_SESSION['lang'];

    if (!$loc)
    {
        if ($lang == "en")
            return simplexml_load_file("i18n/en.i18n.xml");
        else
            return simplexml_load_file("i18n/de.i18n.xml");
    }
    else
    {
        if ($lang == "en")
            return simplexml_load_file("../i18n/en.i18n.xml");
        else
            return simplexml_load_file("../i18n/de.i18n.xml");
    }

}

?>
