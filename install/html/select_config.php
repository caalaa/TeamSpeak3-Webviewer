<?php
/**
* This file is part of TeamSpeak3 Webviewer.
*
* TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* TeamSpeak3 Webviewer is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with TeamSpeak3 Webviewer. If not, see http://www.gnu.org/licenses/.
*/?>

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
