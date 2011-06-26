<?php
require_once 'utils.func.php';
require_once 'libraries/php-gettext/gettext.inc';

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
                height: 500px;
                padding: 5px;
                padding-right: 10px;
            }

            #facebook
            {
                float:left;
                margin-right: 5px;
                width: 310px;
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
                        <p><?php _e('Find us on facebook') ?></p>
                        <iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fmaxesstuff&amp;width=292&amp;colorscheme=light&amp;show_faces=true&amp;stream=true&amp;header=true&amp;height=427" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:427px;"></iframe>
                    </div>
                    <p class="header"><?php _e('Welcome to the Maxesstuff TeamSpeak3 Webviewer') ?></p>
                    <br>
                    <fieldset>
                        <span class="lang"><a href="?lang=en_US"><?php _e('English') ?></a></span>
                        <span class="lang"><a href="?lang=de_DE"><?php _e('German') ?></a></span>
                    </fieldset>
                    <br>
                    <p><?php echo($data['set_status']) ?></p>
                    <p></p>
                    <p><?php _e('The following configfiles are available') ?></p>
                    <p><?php echo($data['configs']) ?></p>
                    <br>
                    <p><?php _e('If you need help, you can take a look for our Livesupport or our FAQ') ?></p>
                    <p><a href="http://support.maxesstuff.de/chat.php">Livesupport</a></p>
                    <p><a href="http://de.maxesstuff.de/teamspeak3-webviewer/faq">FAQ</a></p>
                </div>
            </div>
        </div>
        <br>
    </body>
</html>
