<div id="selection" style="width: 600px !important;">
    <script type="text/javascript">
        $('#selector').button();
    </script>
    <?php echo($data['err_warn'])?>
    <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
        <?php _e('Please select one of those existing configfiles:')?></p>
    </div> 
    <div id="selector">
        <?php echo($data['selector'])?>
    </div>
    <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
        <?php _e('or create a new configfile (If no configfile is available please name the new one "default")')?></p>
    </div>
    <form method="POST" action="index.php?action=new_config">
        <table style="margin: 0">
            <tr>
                <td><?php _e('Name of the new file')?></td>
                <td><input type="text" name="configname" /></td>
            </tr>
            <tr>
                <td><input type="submit" value="<?php _e('create file')?>" /></td>
            </tr>
        </table>
    </form>
</div>
