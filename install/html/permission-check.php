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

    <div class="ui-state-error ui-corner-all">
        <p style="padding: 3px;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><?php __e("If the status of one of those directories is FAILED, please make this directory writable manually! Otherwise the viewer may not work.") ?></p>
    </div>
</div>
