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
<div id="password">
    <!-- Permission Check -->
    <?php require_once 'html/permission-check.php'; ?>
    
    <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
        <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
            <?php __e('Please insert a password for the interface once: '); ?></p>
    </div>

    <form class="jqform" action="index.php?action=setpw" method="post">
        <input type="password" name="password" />
        <input type="submit" value="<?php __e('Set password'); ?>" />
    </form>
</div>