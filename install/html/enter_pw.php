<div id="password">
    <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
        <?php _e('Please enter the password for the interface:')?></p>
    </div>
    <form class="jqform" method="post" action="index.php?action=validate">
        <input type="password" name="password" />
        <input type="submit" value="<?php _e('Submit')?>" />
    </form>
</div>