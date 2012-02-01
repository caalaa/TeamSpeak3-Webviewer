<?php /**
 *  This file is part of devMX TeamSpeak3 Webviewer.
 *  Copyright (C) 2011 - 2012 Max Rath and Maximilian Narr
 *
 *  devMX TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
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
 *  along with devMX TeamSpeak3 Webviewer.  If not, see <http://www.gnu.org/licenses/>.
 */ ?>

<!-- Navigation -->
<span style="position: absolute; right: 10px; top: 10px;" class="topnav" >
    <a href="index.php?action=logout" alt="" class="button"><?php __e('Logout') ?></a>
    <a href="http://devmx.de/en/software/teamspeak3-webviewer/dokumentation" title="<?php __e('I need help!') ?>" target="_blank" alt="" class="button"><span class="ui-icon ui-icon-info">&nbsp;</span></a>
</span>

<div id="config-selection">
    <script type="text/javascript">
        $('#selector').button();
    </script>
    <?php if (isset($data['err_warn'])) echo($data['err_warn']) ?>
    <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
            <?php __e('Please select one of those existing configfiles...') ?></p>
    </div> 
    <div id="selector">
        <?php $files = getConfigFiles("../config"); ?>
        <?php if (count($files) == 0) : ?>
            <p><?php __e('No configfiles created yet. Please create one') ?></p>
        <?php else: ?>
            <div class="config-select">
            <table class="config-select" cellspacing="0" >
                <?php foreach ($files as $file) : ?>
                    <tr>
                        <td><a href="index.php?action=set_config&configname=<?php echo($file) ?>" class="select-button"><span class="select-button config-button"><?php echo($file) ?> (<?php __e('Edit') ?>)</span></a></td>
                        <td><a href="../index.php?config=<?php echo($file) ?>" target="_blank" class="select-button"><span class="select-button config-button"><?php __e('Show') ?></span></a></td>
                        <td><a href="index.php?action=fc&config=<?php echo($file) ?>"class="select-button"><span class="select-button config-button"><?php __e('Flush cache') ?></span></a></td>
                        <td><a href="index.php?action=delete&config=<?php echo($file) ?>" class="select-button"><span class="select-button config-button"><?php __e('Delete configfile') ?></span></a></td>
                    </tr>

                <?php endforeach; ?>
            </table>
            </div>
        <?php endif; ?>
    </div>
    <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
        <p><?php __e('... or create a new configfile') ?></p>
    </div>
    <form method="POST" action="index.php?action=new_config">
        <p><?php __e('Name of the new configfile') ?> <input type="text" name="configname" /></p>
        <p><input type="submit" value="<?php __e('create configfile') ?>" /></p>
    </form>
</div>
