<?php
require_once s_root . 'core/utils.inc';
require_once s_root . 'libraries/php-gettext/gettext.inc';
require_once s_root . 'install/core/xml.php';
require_once s_root . 'core/tsv.inc';
require_once s_root . 'core/i18n.inc';

$lang = "en_US";
if (isset($_GET['lang']) && $_GET['lang'] != "") $lang = $_GET['lang'];

//L10N
setL10n($lang, "ms-tsv-welcome");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Maxesstuff TeamSpeak3 Webviewer</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
        <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/redmond/jquery-ui.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.js"></script>

        <style type="text/css">
            div
            {
                margin-left: auto;
                margin-right: auto;
            }
            .header
            {
                font-size: 26px;
                text-align: center;
                margin-bottom: 8px;
            }

            p
            {
                font-size: 14px;
            }

            .ui-widget
            {
                width: 1000px;
                height: 1000px;
            }

            #wrapper
            {
                height: 600px;
                padding: 5px;
                padding-right: 10px;
            }

            #facebook
            {
                float:left;
                margin-right: 5px;
                width: 310px;
                height: 100%;
            }

            iframe
            {
                background-color: white;
            }

            span .lang
            {
                margin-left: 12px;
                font-size: 14px;
            }

            ul
            {
                font-size: 18px;
                list-style-position: inside;
            }
        </style>
    </head>
    <body>
        <div class="ui-widget">
            <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em;"> 
                <div id="wrapper">
                    <div id="facebook">
                        <p><?php __e('Find us on facebook') ?></p>
                        <iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fmaxesstuff&amp;width=292&amp;colorscheme=light&amp;show_faces=true&amp;stream=true&amp;header=true&amp;height=427" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:427px;"></iframe>
                    </div>
                    <div id="content">
                        <p class="header"><?php __e('Welcome to the Maxesstuff TeamSpeak3 Webviewer') ?></p>
                        <fieldset>
                            <?php $languages = tsv_getLanguages(); ?>
                            <?php foreach ($languages as $langCode => $langOptions)
                            { ?>              
                                <span class="lang" style="float:left; margin-right: 30px;"><p><a href="?lang=<?php echo($langCode); ?>"><?php echo($langOptions['lang']) ?></a></p>
                                    <p style="font-size: 8px;"><span> <?php __e('by') ?> <a href="<?php echo($langOptions['url']) ?>" target="_blank"><?php echo($langOptions['author']); ?></a></span>
                                        <br><span><?php __e('Version'); ?>: <?php echo($langOptions['version']) ?></span></p></span>
                            <?php } ?>
                        </fieldset>
                        <br>
                        <p><?php
                            if (count(getConfigFiles(s_root . 'config')) == 0)
                            {
                                __e('Apparently you didn\'t set up the Viewer yet. Please run the')
                                ?> <a href="<?php echo(s_http . 'install/index.php') ?>"><?php __e('Installscript') ?></a><?php
                        }
                        else
                        {
                            __e('You can see a list of your config files below. If you want to add more, run the');
                                ?> <a href="<?php echo(s_http . 'install/index.php') ?>"><?php __e('Installscript') ?></a><?php } ?></p>
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
                            <ul style="list-style-image:url('<?php echo(s_http . 'html/welcome/tools.png'); ?>');"><?php
                            $configfiles = getConfigFiles(s_root . 'config');
                            foreach ($configfiles as $file)
                            {
                                    ?><li><a href="<?php echo(s_http . 'TSViewer.php?config=' . $file) ?>"><?php echo($file) ?></a></li><?php } ?></ul>   
                        <?php } ?></p>
                        <br>
                        <p><?php __e('If you need help, you can take a look for our Livesupport or our FAQ') ?></p>
                        <p><a href="http://support.maxesstuff.de/chat.php">Livesupport</a></p>
                        <p><a href="http://de.maxesstuff.de/teamspeak3-webviewer/faq">FAQ</a></p>
                    </div>
                </div>
            </div>
        </div>
        <br>
    </body>
</html>
