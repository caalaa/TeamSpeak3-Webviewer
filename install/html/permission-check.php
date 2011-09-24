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
<div id="permission-check" class="ui-state-highlight ui-corner-all">
    <p style="font-weight: bold;"><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span><?php __e("Checking if all necessary directories are writable:") ?></p>
    <br>
    <table width="100%">
        <tr>
            <th></th>
            <th><?php __e("Directory"); ?></th>
            <th><?php __e("Status"); ?></th>
        </tr>
        <?php $dirs = checkPermissions(array("", "../config", "../cache")); ?>

        <?php
        foreach ($dirs as $key => $value)
        {
            // OK
            if ($value == true)
            {
                ?>

                <tr>
                    <td><img src="img/ok.png" alt="" /></td>
                    <td><?php echo($key) ?></td>
                    <td><?php __e("OK"); ?></td>
                </tr>

                <?php
            }

            // Failed
            else
            {
                ?>
                <tr>
                    <td><img src="img/failure.png" alt="" /></td>
                    <td><?php echo($key) ?></td>
                    <td><?php __e("FAILED"); ?></td>
                </tr>
            <?php }
        } ?>
                
    </table>

    <div class="ui-state-error ui-corner-all" id="permission-warning">
        <p style="padding: 3px;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><?php __e("If the status of one of those directories is FAILED, please make this directory writable manually! Otherwise the viewer may not work.") ?></p>
    </div>
</div>
