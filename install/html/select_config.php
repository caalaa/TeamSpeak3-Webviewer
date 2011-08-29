<!-- Logout Button -->
<a style="position:absolute; right: 10px; top: 10px;" href="index.php?action=logout" class="button"><?php __e('Logout') ?></a>
<div id="selection">
    <script type="text/javascript">
        $('#selector').button();
    </script>
    <?php if (isset($data['err_warn'])) echo($data['err_warn']) ?>
    <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
            <?php __e('Please select one of those existing configfiles:') ?></p>
    </div> 
    <div id="selector">
        <?php echo($data['selector']) ?>
    </div>
    <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
            <?php __e('or create a new configfile (If no configfile is available please name the new one "default")') ?></p>
    </div>
    <form method="POST" action="index.php?action=new_config">
        <p><?php __e('Name of the new file') ?> <input type="text" name="configname" /></p>
        <p><input type="submit" value="<?php __e('create file') ?>" /></p>
    </form>
</div>
