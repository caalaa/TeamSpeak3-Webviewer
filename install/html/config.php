<div id="config">
    <script type="text/javascript">
        $(document).ready(function()
        {
            $('#tabs').tabs();
        });
    </script>   
    <form action="index.php?action=submit" method="post" >
        <div id="tabs">
            <ul>
                <li><a href="#tab1"><?php _e('Mainsettings')?></a></li>
                <li><a href="#tab2"><?php _e('Modules')?></a></li>
                <li><a href="#tab3"><?php _e('Style')?></a></li>
                <li><a href="#tab4"><?php _e('Caching')?></a></li>
                <li><a href="#tab5"><?php _e('Misc')?></a></li>
            </ul>

            <!-- Serverinformation -->
            <div id="tab1">
                <table style="margin:0">
                    <tr>
                        <td title="<?php _e('The adress with which you are connecting to the server (Can be an IP-Adress or hostname).')?>"><?php _e('Serveradress')?></td>
                        <td><input type="text" value="<?php echo($data['serveradress_value'])?>" name="serveradress" /></td>
                    </tr>
                    <tr>
                        <td title="<?php _e('The Queryport of your server (Default: 10011).')?>"><?php _e('Queryport')?></td>
                        <td><input type="text" value="<?php echo($data['queryport_value'])?>" name="queryport" /></td>
                    </tr>
                    <tr>
                        <td title="<?php _e('The port through which you are connecting to your server via the client (Default: 9987).')?>"><?php _e('Serverport')?></td>
                        <td><input type="text" value="<?php echo($data['serverport_value'])?>" name="serverport" /></td>
                    </tr>
                    <tr>
                        <td title="<?php _e('If a login is required that the viewer can get the needed information (Default: yes).')?>"><?php _e('Login required?')?></td>
                        <td>
                            <?php echo($data['login_html']);?>
                        </td>
                    </tr>
                    <tr>
                        <td title="<?php _e('The username of the query-user.')?>"><?php _e('Username')?></td>
                        <td><input type="text" value="<?php echo($data['username_value'])?>" name="username" /></td>                
                    </tr>
                    <tr>
                        <td title="<?php _e('Password corresponding to the username above.')?>"><?php _e('Password')?></td>
                        <td><input type="text" value="<?php echo($data['password_value'])?>" name="password" /></td>
                    </tr>
                </table>
            </div>

            <!-- Module -->
            <div id="tab2">
                <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
                    <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                        <?php _e('Click on a module to edit the modules configfile. Drag and sort the modules in the order you want them displayed')?></p>
                </div>
                <div>
                    <table style="margin:0;">
                        <tr>
                            <td><div>
                                    <p><?php _e('enabled modules:')?></p>
                                    <p><?php echo($data['mod_sort_enabled'])?></p>
                                </div></td>
                            <td><div>
                                    <p><?php _e('disabled modules:')?></p>
                                    <p><?php echo($data['mod_sort_disabled'])?></p>
                                </div></td>
                        </tr>
                    </table>
                </div>
                <input id="modules_hidden" type="hidden" name="module[]" value="" />
            </div>

            <!-- Style -->
            <div id="tab3">
                <div>
                    <p title="<?php _e('If you set this on true, custom icons will be downloaded automatically.')?>"><?php _e('Download servericons automatically?')?></p>
                    <fieldset><?php echo($data['servericons_radio'])?></fieldset>
                </div>
                <div>
                    <p title="<?php _e('If the setting above is FALSE, the viewer will use the imagepack provided here.')?>"><?php _e('Group-Icons Imagepackage')?></p>
                    <fieldset><?php echo($data['imagepack_html'])?></fieldset>
                </div>
                <div>
                    <p title="<?php _e('The stylesheet which should be used for the viewer')?>"><?php _e('Stylesheet')?></p>
                    <fieldset><?php echo($data['style_html'])?></fieldset>
                </div>
                <div>
                    <p title="<?php _e('If you set that on TRUE, the viewer will show arrows next to the channel similar as in the client.')?>"><?php _e('Show arrows?')?></p>
                    <fieldset><?php echo($data['arrow_html'])?></fieldset>
                </div>
            </div>

            <!-- Caching -->
            <div id="tab4">
                <table style="margin:0">
                    <tr>
                        <td title="<?php _e('If this on true the viewer will buffer the data it received. It is strongly recommended to enable caching due to stability reasons.')?>"><?php _e('Enable caching?')?>
                        <td>
                            <?php echo($data['caching_html'])?>
                        </td>
                    </tr>
                    <tr>
                        <td title="<?php _e('The time in seconds the viewer should buffer the serverdata (Recommended: 60).')?>"><?php _e('Standard cachetime')?></td>
                        <td>
                            <?php echo($data['standard_caching_html'])?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Stuff -->
            <div id="tab5">
                <table style="margin:0">
                    <tr>
                        <td title="<?php _e('Language of the viewer')?>"><?php _e('Language')?></td>
                        <td><?php echo($data['language_html'])?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Submit-Button -->
        <br>
        <div class="ui-state-highlight ui-corner-all" style="margin-top: 0px; padding: 0 .7em; font-size: 10px; margin-bottom: 10px;"> 
            <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em; "></span>
                <?php _e('Tip: Hover over the labels to get more information about the requested data.')?></p>
        </div>
        <input type="submit" value="<?php _e('Save configfile')?>" /> <input type="button" onclick="javascript: redirect();" value="<?php _e('Back to configfiles')?>" />
    </form>
</div>