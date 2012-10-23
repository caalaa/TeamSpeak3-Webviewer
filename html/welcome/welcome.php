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
require_once s_root . 'core/utils.inc';
require_once s_root . 'libraries/php-gettext/gettext.inc';
require_once s_root . 'install/core/xml.php';
require_once s_root . 'core/tsv.inc';
require_once s_root . 'core/i18n.inc';

$lang = "en_US";
$newlang = '';

$utils = new tsvUtils();

//L10N
setL10n($lang, "teamspeak3-webviewer");

if (isset($_GET['lang']) && $_GET['lang'] != "")
{
    $lang = $_GET['lang'];
    $newlang = '?action=setlang&lang=' . $lang;
    setL10n($lang, "teamspeak3-webviewer");
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>devMX TeamSpeak3 Webviewer</title>
        <meta http-equiv="X-UA-Compatible" content="IE=9" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" href="<?php echo s_http; ?>html/welcome/tools.png" type="image/png">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.js"></script>
        <link href="<?php echo(s_http) ?>libraries/grayified/grayified-jquery-ui-1.0.css" rel="stylesheet" type="text/css">
        <link href="<?php echo(s_http) ?>html/welcome/style.css" rel="stylesheet" type="text/css">
        <link href="<?php echo(s_http) ?>libraries/css/tswv.css" rel="stylesheet" type="text/css">
    </head>
    <body class="tswv-body">
        <div id="wrapper" class="tswv-container tswv-container-60per"> 
            <div id="content">
                <div id="navigation">
                    <span class="nav-element tswv-button-orange"><a class="nav" href="http://devmx.de/en/software/teamspeak3-webviewer/ubersetzen" target="_blank"><?php __e('Help us translating the webviewer') ?></a></span>
                    <span onclick="javascript: openFacebookDialog();" class="nav nav-element tswv-button-orange"><?php __e('Become fan at facebook'); ?></span>
                    <span class="nav-element tswv-button-orange"><a class="nav" href="<?php echo(s_http . 'install/index.php' . htmlspecialchars($newlang)) ?>"><?php __e('Installation and Configuration') ?></a></span>
                    <span class="nav-element tswv-button-orange"><a class="nav" target="_blank" href="http://forum.devmx.de"><?php __e('Support') ?></span></a>
                    <span class="nav-element tswv-button-orange"><a class="nav" target="_blank" href="http://docs.devmx.de/teamspeak3-webviewer/"><?php __e('Documentation') ?></a></span>
                    <span class="nav-element tswv-button-orange"><a class="nav" href="ts3server://devmx.de"><?php __e('TeamSpeak') ?></a></span>
                </div>
                <div id="logo"><img style="margin-left: -175px;" src="<?php echo s_http; ?>html/welcome/logo.png" alt="" /></div>
                <div><p class="header"><?php __e('Welcome to the devMX TeamSpeak3 Webviewer') ?></p></div>
                <br>
                <p><?php __e('You can see a list of your config files below. If you want to add more, run the'); ?> <a href="<?php echo(s_http . 'install/index.php' . $newlang) ?>" class="tswv-link-gray"><?php __e('Installscript') ?></a></p>
                <p><?php __e('The following configfiles are available:') ?></p>
                <?php if (count(getConfigFiles(s_root . 'config')) == 0) : ?>
                    <p class="no-config"><?php __e('You did not create any configurationfiles yet.') ?></p>
                <?php else : ?>
                    <ul id="configs" class="tswv-inner-container tswv-container-450px" style="list-style-image:url('<?php echo(s_http . 'html/welcome/tools.png'); ?>');">
                        <?php
                        $configfiles = getConfigFiles(s_root . 'config');
                        foreach ($configfiles as $file) :
                            ?>
                            <li><a href="<?php echo(s_http . 'TSViewer.php?config=' . $file) ?>" class="tswv-link-gray"><?php echo($file) ?></a> <span class="get-code tswv-link-gray" title="<?php __e('Get code to include this configfile') ?>" onclick="javascript: openLinkDialog('<?php echo($file) ?>');"><?php __e('Get code to include') ?></span></li>
                        <?php endforeach; ?>
                    </ul>   
                <?php endif; ?>
                <div class="tswv-languages">
                <?php
                $languages = $utils->getLanguages();
                foreach ($languages as $langCode => $langOptions) :
                    ?>              
                    <span class="tswv-button-orange lang" style="float:left; margin-right: 10px;"><a href="?lang=<?php echo($langCode); ?>"><img class="flag" src="modules/infoDialog/flags/<?php echo ($langOptions['icon'] . '.png') ?>" alt="" /><?php echo($langOptions['lang']) ?></a></span>
                <?php endforeach; ?>
                </div>
                <p id="version"><?php __e('Version:'); ?> <?php echo (string) version; ?></p>

                <?php
                $versionInfo = $utils->versionCompare();
                if ($versionInfo !== false) :
                    ?>
                    <p id="version-hint"><a class="tswv-button-red" target="_blank" href="<?php echo($versionInfo->url); ?>"><?php echo sprintf(__('Version %s of the TeamSpeak3 Webviewer has been released. Click here to update.'), (string) $versionInfo->version) ?></a></p>
                <?php endif; ?>
            </div>
        </div>
        <div id="hint" class="tswv-button-orange">
            <a href="http://devmx.de" target="_blank"><?php __e('devMX TeamSpeak3 Webviewer') ?></a>
        </div>

        <div style="display: none;">
            <iframe style="width:500px !important; height:100%" allowTransparency="true" frameborder="0" scrolling="0" id="fblink"></iframe>  
            <iframe allowTransparency="true" frameborder="0" scrolling="0" id="langlink"></iframe>
            <div id="code">
                <div id="include-tabs">
                    <ul>
                        <li><a href="#iframeInclude"><?php __e('Iframe'); ?></a></li>
                        <li><a href="#ajaxInclude"><?php __e('Ajax'); ?></a></li>
                    </ul>
                    <div id="iframeInclude">
                        <table>
                            <tr>
                                <td><?php __e('Height') ?>:</td>
                                <td><input class="ui-widget ui-corner-all ui-widget-content ui-textbox" type="text" value="100%" id="code-height" /></td>
                            </tr>
                            <tr>
                                <td><?php __e('Width') ?>:</td>
                                <td><input class="ui-widget ui-corner-all ui-widget-content ui-textbox" type="text" value="100%" id="code-width" /></td>
                            </tr>
                            <tr>
                                <td><?php __e('Language') ?>:</td>
                                <td>
                                    <select id="code-language">
                                        <?php foreach ($languages as $key => $value) : ?>
                                            <option value="<?php echo ($key) ?>"><?php echo ($value['lang']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <textarea class="ui-textbox ui-corner-all ui-widget-content ui-widget" id="code-area"></textarea>
                    </div>
                    <div id="ajaxInclude">
                        <p><?php __e('Id') ?> <input class="ui-widget ui-corner-all ui-widget-content ui-textbox" type="text" value="viewer" id="ajax-id" /></p>
                        <textarea class="ui-textbox ui-corner-all ui-widget-content ui-widget" id="ajax-area"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var s_http='<?php echo(s_http) ?>';
        </script>
        <script src="<?php echo(s_http) ?>html/welcome/welcome.js" type="text/javascript"></script>
    </body>
</html>
