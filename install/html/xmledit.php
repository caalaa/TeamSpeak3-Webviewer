<link rel="stylesheet" href="../../libraries/codemirror/lib/codemirror.css" type="text/css">
<script src="../../libraries/codemirror/lib/codemirror.js" type="text/javascript"></script>
<script src="../../libraries/codemirror/mode/xml/xml.js" type="text/javascript"></script>
<link rel="stylesheet" href="../../libraries/codemirror/mode/xml/xml.css" type="text/css">
<style type="text/css">
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
    });
</script>

<div id="xmledit">
    <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
        <?php _e('You can edit the configfile of the module here. If you have finished just press the button')?></p>
    </div>
    <form method="POST" action="xmledit.php?action=submit&module=<?php echo($html['module_ed'])?>">
        <textarea id="code" name="xml"><?php echo($html['code'])?></textarea>
        <p><input type="submit" value="<?php _e('save configfile')?>"/></p>
    </form>
    <script type="text/javascript">
            <?php echo($html['xml_script'])?>
    </script>
</div>