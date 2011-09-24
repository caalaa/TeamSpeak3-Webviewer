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
require_once s_root . 'core/utils.inc';
require_once s_root . 'libraries/php-gettext/gettext.inc';
require_once s_root . 'install/core/xml.php';
require_once s_root . 'core/tsv.inc';
require_once s_root . 'core/i18n.inc';

$lang = "en_US";
$newlang = '';

$utils = new tsvUtils();

//L10N
setL10n($lang, "ms-tsv-welcome");

if (isset($_GET['lang']) && $_GET['lang'] != "")
{
    $lang = $_GET['lang'];
    $newlang = '?action=setlang&lang=' . $lang;
    setL10n($lang, "ms-tsv-welcome");
}
else if (isset($_GET['action']) && $_GET['action'] == "showtrans") :
    ?>

        <?php $languages = $utils->getLanguages(); ?>
    <div id="lang-credits">
    <?php foreach ($languages as $langCode => $langOptions) : ?>
            <div id="lang<?php echo($langCode); ?>">
                <p><?php __e('Translators') ?>: <?php echo($langOptions['lang']); ?> (<?php echo($langOptions['version']); ?>)</p>
                <ul>
                    <?php foreach ($langOptions['authors'] as $author => $homepage) : ?>
                        <li><a href="<?php echo($homepage) ?>"><?php echo($author) ?></a></li>
        <?php endforeach; ?>
                </ul>
            </div>   
    <?php endforeach; ?>
    </div>
    <?php
    exit;
endif;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>devMX TeamSpeak3 Webviewer</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="icon" href="<?php echo s_http; ?>html/welcome/tools.png" type="image/png">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
        <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/redmond/jquery-ui.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.js"></script>
        <link href="<?php echo(s_http) ?>html/welcome/style.css" rel="stylesheet" type="text/css">

        <!-- Colorbox -->
        <link href="<?php echo(s_http) ?>libraries/colorbox/example1/colorbox.css" rel="stylesheet" type="text/css">
        <script src="<?php echo(s_http) ?>libraries/colorbox/colorbox/jquery.colorbox-min.js" type="text/javascript"></script>
        <script>
            $(document).ready(function(){
                $("#lang-link").colorbox();
                $("#facebook").colorbox({width:"550px", height:"630px", iframe:true});
            });
        </script>
    </head>
    <body>
        <div class="ui-widget">
            <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
                <div id="wrapper">
                    <div id="content">
                        <div id="navigation">
                            <span class="nav-element red"><a id="facebook" href="http://www.facebook.com/plugins/likebox.php?href=http://www.facebook.com/maxesstuff&width=500&colorscheme=light&show_faces=true&border_color=000000&stream=true&header=true&height=590"><?php __e('Become fan at facebook'); ?></a></span>
                            <span class="nav-element orange"><a href="<?php echo(s_http . 'install/index.php' . $newlang) ?>"><?php __e('Installation and Configuration') ?></a></span>
                            <span class="nav-element orange"><a target="_blank" href="http://en.devmx.de/emailsupport"><?php __e('Support') ?></a></span>
                            <span class="nav-element orange"><a target="_blank" href="http://en.devmx.de/software/teamspeak3-webviewer/installation"><?php __e('Documentation') ?></a></span>
                        </div>
                        <div id="logo"><img style="margin-left: -175px;" src="<?php echo s_http; ?>html/welcome/logo.png" alt="" /></div>
                        <div><p class="header"><?php __e('Welcome to the devMX TeamSpeak3 Webviewer') ?></p></div>
                        <fieldset id="languages" class="orange">
                            <?php
                            $languages = $utils->getLanguages();
                            foreach ($languages as $langCode => $langOptions)
                            {
                                ?>              
                                <p class="lang" style="float:left; margin-right: 10px;"><a href="?lang=<?php echo($langCode); ?>"><?php echo($langOptions['lang']) ?></a></p>
                            <?php } ?>
                            <p><a style="float:left; margin-right: 20px;" title="<?php __e('show Translators'); ?>" id="lang-link" href="?action=showtrans" class="ui-icon ui-icon-info">&nbsp;</a></p>
                        </fieldset>
                        <br>
                        <p><?php
                            if (count(getConfigFiles(s_root . 'config')) == 0)
                            {
                                __e('Apparently you didn\'t set up the Viewer yet. Please run the')
                                ?> <a href="<?php echo(s_http . 'install/index.php&set_lang=' . $lang) ?>"><?php __e('Installscript') ?></a><?php
                        }
                        else
                        {
                            __e('You can see a list of your config files below. If you want to add more, run the');
                                ?> <a href="<?php echo(s_http . 'install/index.php' . $newlang) ?>"><?php __e('Installscript') ?></a><?php } ?></p>
                        <p></p>
                        <p><?php __e('The following configfiles are available') ?></p>
                        <p><?php
                            if (count(getConfigFiles(s_root . 'config')) == 0)
                            {
                                __e('No configfile available');
                            }
                            else
                            {
                                ?>
                            <ul class="green" id="configs" style="list-style-image:url('<?php echo(s_http . 'html/welcome/tools.png'); ?>');"><?php
                            $configfiles = getConfigFiles(s_root . 'config');
                            foreach ($configfiles as $file)
                            {
                                ?><li><a href="<?php echo(s_http . 'TSViewer.php?config=' . $file) ?>"><?php echo($file) ?></a></li><?php } ?></ul>   
                            <?php } ?></p>

                        <p id="version"><?php __e('Version:'); ?> <?php echo version; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </body>
</html>
