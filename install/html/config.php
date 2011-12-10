<?php /**
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
 */ ?>
<!-- Navigation -->
<span style="position: absolute; right: 10px; top: 10px;" >
    <a href="index.php?action=return" alt="" class="button"><?php __e('Back to configfiles') ?></a>
    <a href="index.php?action=logout" alt="" class="button"><?php __e('Logout') ?></a>
    <a href="http://devmx.de/en/software/teamspeak3-webviewer/dokumentation" target="_blank" alt="" class="button"><span class="ui-icon ui-icon-info">&nbsp;</span></a>
</span>

<div id="config">
    <script type="text/javascript">
        $(document).ready(function()
        {
            $('#tabs').tabs({fx: { height: "toggle", duration: "slow" }});
        });
    </script>   
    <form action="index.php?action=submit" method="post" >
        <div id="tabs">
            <ul>
                <li><a href="#tab1"><?php __e('Mainsettings') ?></a></li>
                <li><a href="#tab2"><?php __e('Modules') ?></a></li>
                <li><a href="#tab3"><?php __e('Style') ?></a></li>
                <li><a href="#tab4"><?php __e('Caching') ?></a></li>
                <li><a href="#tab5"><?php __e('Misc') ?></a></li>
            </ul>

            <!-- Serverinformation -->
            <div id="tab1">
                <table style="margin:0">
                    <tr>
                        <td title="<?php __e('The adress with which you are connecting to the server (Can be an IP-Adress or hostname).') ?>"><?php __e('Serveradress') ?></td>
                        <td><input type="text" value="<?php echo($data['serveradress_value']) ?>" name="serveradress" /></td>
                    </tr>
                    <tr>
                        <td title="<?php __e('The Queryport of your server (Default: 10011).') ?>"><?php __e('Queryport') ?></td>
                        <td><input type="text" value="<?php echo($data['queryport_value']) ?>" name="queryport" /></td>
                    </tr>
                    <tr>
                        <td title="<?php __e('The port through which you are connecting to your server via the client (Default: 9987).') ?>"><?php __e('Serverport') ?></td>
                        <td><input type="text" value="<?php echo($data['serverport_value']) ?>" name="serverport" /></td>
                    </tr>
                    <tr>
                        <td title="<?php __e('If a login is required that the viewer can get the needed information (Default: yes).') ?>"><?php __e('Login required?') ?></td>
                        <td>
                            <?php echo($data['login_html']); ?>
                        </td>
                    </tr>
                    <tr id="config-username">
                        <td title="<?php __e('The username of the query-user.') ?>"><?php __e('Username') ?></td>
                        <td><input type="text" value="<?php echo($data['username_value']) ?>" name="username" /></td>                
                    </tr>
                    <tr id="config-password">
                        <td title="<?php __e('Password corresponding to the username above.') ?>"><?php __e('Password') ?></td>
                        <td><input type="text" value="<?php echo($data['password_value']) ?>" name="password" /></td>
                    </tr>
                </table>
            </div>

            <!-- Module -->
            <div id="tab2">
                <div class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0 .7em; margin-bottom: 10px;"> 
                    <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>
                        <?php __e('Click on a module to edit the modules configfile. Drag and sort the modules in the order you want them displayed') ?></p>
                </div>
                <div>
                    <table style="margin:0;">
                        <tr>
                            <td><div>
                                    <p><?php __e('enabled modules:') ?></p>
                                    <p><?php echo($data['mod_sort_enabled']) ?></p>
                                </div></td>
                            <td><div>
                                    <p><?php __e('disabled modules:') ?></p>
                                    <p><?php echo($data['mod_sort_disabled']) ?></p>
                                </div></td>                      
                        </tr>
                        <tr>
                            <td><span class="button" onclick="javascript: enableAllModules();"><?php __e('Enable all'); ?></span></td>
                            <td><span class="button" onclick="javascript: disableAllModules();"><?php __e('Disable all'); ?></span></td>
                        </tr>
                    </table>
                </div>
                <input id="modules_hidden" type="hidden" name="module[]" value="" />
            </div>

            <!-- Style -->
            <div id="tab3">
                <div id="servericons-config">
                    <p title="<?php __e('If you set this on true, custom icons will be downloaded automatically.') ?>"><?php __e('download servericons automatically') ?></p>
                    <fieldset><?php echo($data['servericons_radio']) ?></fieldset>
                </div>
                <div id="imagepack-config">
                    <p title="<?php __e('If the setting above is FALSE, the viewer will use the imagepack provided here.') ?>"><?php __e('Group-Icons Imagepackage') ?></p>
                    <fieldset><?php echo($data['imagepack_html']) ?></fieldset>
                </div>
                <div id="stylesheet-config">
                    <p title="<?php __e('The stylesheet which should be used for the viewer') ?>"><?php __e('Stylesheet') ?></p>
                    <fieldset><?php echo($data['style_html']) ?></fieldset>
                </div>
                <div id="arrow-config">
                    <p title="<?php __e('If you set that on TRUE, the viewer will show arrows next to the channel similar as in the client.') ?>"><?php __e('display arrows') ?></p>
                    <fieldset><?php echo($data['arrow_html']) ?></fieldset>
                </div>
                <div id="filter-config">
                    <p title="<?php __e('standard -> shows the viewer like in TS Client; clientonly -> shows only clients; channelclientsonly -> shows only channels with clients inside') ?>"><?php __e('Display filter') ?></p>
                    <fieldset>
                        <input type="radio" name="display-filter" value="standard" <?php if($data['display-filter'] == "standard"): ?> checked="checked"<?php endif; ?>><span> <?php __e('Standard')?></span><br>
                        <input type="radio" name="display-filter" value="clientsonly" <?php if($data['display-filter'] == "clientsonly"): ?> checked="checked"<?php endif; ?>><span> <?php __e('Clients only')?></span><br>
                        <input type="radio" name="display-filter" value="channelclientsonly" <?php if($data['display-filter'] == "channelclientsonly"): ?> checked="checked" <?php endif;?>><span> <?php __e('Channels with clients inside only')?></span>
                    </fieldset>
                </div>
            </div>

            <!-- Caching -->
            <div id="tab4">
                <table style="margin:0">
                    <tr>
                        <td title="<?php __e('If this on true the viewer will buffer the data it received. It is strongly recommended to enable caching due to stability reasons.') ?>"><?php __e('Enable caching?') ?>
                        <td>
                            <?php echo($data['caching_html']) ?>
                        </td>
                    </tr>
                    <tr>
                        <td title="<?php __e('The time in seconds the viewer should buffer the serverdata (Recommended: 60).') ?>"><?php __e('Standard cachetime') ?></td>
                        <td>
                            <?php echo($data['standard_caching_html']) ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Stuff -->
            <div id="tab5">
                <table style="margin:0">
                    <tr>
                        <td title="<?php __e('Language of the viewer') ?>"><?php __e('Language') ?></td>
                        <td><?php echo($data['language_html']) ?></td>
                    </tr>
                    <tr>
                        <td title="<?php __e('If enabled the url to your webviewer will be submitted to devMX') ?>"><?php __e('Provide usage statistics') ?></td>
                        <td>
                            <input type="radio" name="usage-statistics" value="true" <?php if ($data['config']->usage_stats == "true"): ?>checked="checked" <?php endif; ?>> <?php __e('Yes') ?><br>
                            <input type="radio" name="usage-statistics" value="false" <?php if ($data['config']->usage_stats != "true") : ?>checked="checked" <?php endif; ?>> <?php __e('No') ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Submit-Button -->
        <br>
        <div class="ui-state-highlight ui-corner-all" style="margin-top: 0px; padding: 0 .7em; font-size: 10px; margin-bottom: 10px;"> 
            <p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em; "></span>
                <?php __e('Tip: Hover over the labels to get more information about the requested data.') ?></p>
        </div>
        <input type="submit" value="<?php __e('Save configfile') ?>" />
    </form>
    <iframe style="display:none" id="module-config" allowTransparency="true" frameborder="0" scrolling="0"></iframe>
</div>