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
        <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/redmond/jquery-ui.css" type="text/css" />
        <link rel="stylesheet" href="../../libraries/codemirror/lib/codemirror.css" type="text/css">
        <script src="../../libraries/codemirror/lib/codemirror.js" type="text/javascript"></script>
        <script src="../../libraries/codemirror/mode/xml/xml.js" type="text/javascript"></script>
        <link rel="stylesheet" href="../../libraries/codemirror/mode/xml/xml.css" type="text/css">
        <style type="text/css">
            p, li, input
            {
                font-size: 12px;
            }

            textarea
            {
                height: 400px;
                border: 2px black solid;
            }
            body
            {
                height:600px;
                width:1000px;
            }
        </style>

        <script type="text/javascript">
            $(document).ready(function(){
                $("input:submit").button();
                $("#tabs").tabs({fx: { height: "toggle", duration: "slow" }});
                $(".warning, .info, .alert").delay(500).fadeIn(500);
            });
        </script>
    </head>
    <body>
        <div id="xmledit">
            <?php echo($msERRWAR); ?>
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