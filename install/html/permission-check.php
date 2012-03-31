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
<?php $dirs = checkPermissions(array("pw.xml", "../config", "../cache")); ?>
<?php $funcs = checkFunctions(array("fsockopen")); ?>

<div id="permission-check" class="ui-state-highlight ui-corner-all">
    <p style="font-weight: bold;"><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span><?php __e("Checking if all necessary directories are writable and needed functions are available:") ?></p>
    <br>
    <table width="100%" cellspacing="5">
        <tr>
            <th></th>
            <th><?php __e("Directory"); ?></th>
            <th><?php __e("Status"); ?></th>
        </tr>


        <?php
        foreach ($dirs as $key => $value) :

            // OK
            if ($value == true) :
                ?>

                <tr>
                    <td><img src="img/ok.png" alt="" /></td>
                    <td><?php echo($key) ?></td>
                    <td><?php __e("OK"); ?></td>
                </tr>

                <?php
            // Failed
            else:
                ?>
                <tr>
                    <td><img src="img/failure.png" alt="" /></td>
                    <td><?php echo($key) ?></td>
                    <td><?php __e("FAILED"); ?></td>
                </tr>
            <?php
            endif;
        endforeach;
        ?>

    </table>
    <table width="100%" cellspacing="5">
        <tr>
            <th></th>
            <th><?php __e("Function"); ?></th>
            <th><?php __e("Status"); ?></th>
        </tr>


        <?php
        foreach ($funcs as $key => $value) :

            // OK
            if ($value == true) :
                ?>

                <tr>
                    <td><img src="img/ok.png" alt="" /></td>
                    <td><?php echo($key) ?></td>
                    <td><?php __e("OK"); ?></td>
                </tr>

                <?php
            // Failed
            else:
                ?>
                <tr>
                    <td><img src="img/failure.png" alt="" /></td>
                    <td><?php echo($key) ?></td>
                    <td><?php __e("FAILED"); ?></td>
                </tr>
            <?php
            endif;
        endforeach;
        ?>

    </table>

    <div class="ui-state-error ui-corner-all" id="permission-warning">       
        <ul>
            <li><div class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></div><span><?php __e("If the status of one of those directories is FAILED, please make this directory writable manually! Otherwise the viewer may not work.") ?></span></li>
            <li><div class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></div><span><?php __e("If the status of one of those functions is FAILED, make this function available on your webspace. You may need to contact your service provider. If a function is missing, the TeamSpeak3 Webviewer will NOT work.") ?></span></li>
        </ul>
    </div>
</div>
