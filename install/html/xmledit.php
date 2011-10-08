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
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>TeamSpeak3 Webviewer - Installation</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!-- Jquery -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>

        <!-- Jquery UI -->
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="../../libraries/fluent/css/fluent.css" type="text/css" />
        <link rel="stylesheet" href="../../libraries/codemirror/lib/codemirror.css" type="text/css">
        <script src="../../libraries/codemirror/lib/codemirror.js" type="text/javascript"></script>
        <script src="../../libraries/codemirror/mode/xml/xml.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../libraries/codemirror/mode/xml/xml.css" type="text/css">

        <!-- Style -->
        <link rel="stylesheet" href="../css/xmledit.css" type="text/css">
        
        <script type="text/javascript">
            $(document).ready(function(){
                $("input:submit").button();
                $("#tabs").tabs({fx: { height: "toggle", duration: "slow" }});
                $(".warning, .info, .alert").delay(500).fadeIn(500);
            });
        </script>
    </head>
    <body>
        <?php echo($msERRWAR); ?>
        <div id="xmledit">
            <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
                <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                    <?php __e('You can edit the global and the local configfile of the module here. If you have finished just press the button. The local configfile overrides all configparameters set in the global one. Set \'none\' in the local config, if you want to use the parameter of the global config') ?></p>
            </div>

            <div id="tabs">
                <ul>
                    <li><a href="#global"><?php __e('Global config') ?></a></li>
                    <li><a href="#local"><?php __e('Local config') ?></a></li>
                </ul>
                <div id="global">
                    <form method="POST" action="xmledit.php?action=submit&type=global&module=<?php echo($module) ?>">
                        <textarea id="global-config" name="xml"><?php echo($globalConfig) ?></textarea>
                        <p><input type="submit" value="<?php __e('save configfile') ?>"/></p>
                    </form>
                </div>
                <div id="local">
                    <form method="POST" action="xmledit.php?action=submit&type=local&module=<?php echo($module) ?>">
                        <textarea id="local-config" name="xml"><?php echo($localConfig) ?></textarea>
                        <p><input type="submit" value="<?php __e('save configfile') ?>"/></p>
                    </form>
                </div>
            </div>

            <script type="text/javascript">  
                
                var globalEditor = CodeMirror.fromTextArea(document.getElementById("global-config"), {mode: {name: "xml", alignCDATA: true},   lineNumbers: true });
                var localEditor = CodeMirror.fromTextArea(document.getElementById("local-config"), {mode: {name: "xml", alignCDATA: true},   lineNumbers: true });
                
                $( "#tabs" ).bind( "tabsshow", function(event, ui) {
                    globalEditor.refresh();
                    localEditor.refresh();
                });             
            </script>
        </div>
    </body>
</html>