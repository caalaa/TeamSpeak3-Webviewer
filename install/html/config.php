<?php
/**
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
 */
?>

<!-- Navigation -->
<span style="position: absolute; right: 10px; top: 10px;" class="topnav" >
    <a href="index.php?action=return" alt="" class="button"><?php __e('Back to configfiles') ?></a>
    <a href="index.php?action=logout" alt="" class="button"><?php __e('Logout') ?></a>
    <a href="http://devmx.de/en/software/teamspeak3-webviewer/dokumentation" title="<?php __e('I need help!') ?>" target="_blank" alt="" class="button"><span class="ui-icon ui-icon-info">&nbsp;</span></a>
</span>
<div id="config">
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
                <table class="config" cellspacing="0">
                    <tr>
                        <td><?php __e('Serveradress') ?></td>
                        <td><input type="text" value="<?php echo($data['serveradress_value']) ?>" name="serveradress" /></td>
                        <td class="option-descr"><?php __e('The adress with which you are connecting to the server (Can be an IP-Adress or hostname).') ?></td>
                    </tr>
                    <tr>
                        <td><?php __e('Queryport') ?></td>
                        <td><input type="text" value="<?php echo($data['queryport_value']) ?>" name="queryport" /></td>
                        <td class="option-descr"><?php __e('The Queryport of your server (Default: 10011).') ?></td>
                    </tr>
                    <tr>
                        <td><?php __e('Serverport') ?></td>
                        <td><input type="text" value="<?php echo($data['serverport_value']) ?>" name="serverport" /></td>
                        <td class="option-descr"><?php __e('The port through which you are connecting to your server via the client (Default: 9987).') ?></td>
                    </tr>
                    <tr>
                        <td><?php __e('Login required') ?></td>
                        <td>
                            <input id="login-needed-true" type="radio" name="login_needed" value="true" <?php if ($data['login_needed'] != "false"): ?>checked="checked"<?php endif; ?>> <?php __e('Yes') ?><br>
                            <input id="login-needed-false" type="radio" name="login_needed" value="false" <?php if ($data['login_needed'] == "false"): ?>checked="checked"<?php endif; ?>><?php __e('No') ?>
                        </td>
                        <td class="option-descr"><?php __e('If a login is required that the viewer can get the needed information (Default: yes).') ?></td>
                    </tr>
                    <tr id="config-username">
                        <td><?php __e('Username') ?></td>
                        <td><input type="text" value="<?php echo($data['username_value']) ?>" name="username" /></td>    
                        <td class="option-descr"><?php __e('The username of the query-user.') ?></td>
                    </tr>
                    <tr id="config-password">
                        <td><?php __e('Password') ?></td>
                        <td><input type="text" value="<?php echo($data['password_value']) ?>" name="password" /></td>
                        <td class="option-descr"><?php __e('Password corresponding to the username above.') ?></td>
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
                    <table class="config" cellspacing="0">
                        <tr>
                            <td><div>
                                    <p><?php __e('enabled modules:') ?></p>
                                    <ul id="sort1" class="sortable">
                                        <?php foreach ($data['enabledModules'] as $mod) : ?>
                                            <li id="<?php echo $mod; ?>" class="module-active"><span class="module-edit" onclick="javascript: openModuleConfig('<?php echo $mod; ?>');"><?php echo $mod ?></span></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div></td>
                            <td><div>
                                    <p><?php __e('disabled modules:') ?></p>
                                    <ul id="sort2" class="sortable">
                                        <?php foreach ($data['disabledModules'] as $mod) : ?>
                                            <li id="<?php echo $mod; ?>" class="module-inactive"><span class="module-edit" onclick="javascript: openModuleConfig('<?php echo $mod ?>');"><?php echo $mod ?></span></li>
                                        <?php endforeach; ?>
                                    </ul>
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
                <table class="config style" cellspacing="0">
                    <tr id="servericons-config">
                        <td><?php __e('Download servericons automatically') ?></td>
                        <td>
                            <input id="servericons-true" type="radio" name="servericons" value="true" <?php if ($data['downloadIcons'] == "true" || $data['downloadIcons'] == '') : ?>checked="checked"><?php endif; ?><span> <?php __e('Enabled') ?></span><br>
                            <input id="servericons-false" type="radio" name="servericons" value="false" <?php if ($data['downloadIcons'] == "false") : ?>checked="checked"><?php endif; ?><span> <?php __e('Disabled') ?></span>
                        </td>
                        <td class="option-descr"><?php __e('If you set this on true, custom icons will be downloaded automatically.') ?></td>
                    </tr>
                    <tr id="imagepack-config">
                        <td><?php __e('Imagepack for icons') ?></td>
                        <td><?php echo($data['imagepack_html']) ?></td>
                        <td class="option-descr"><?php __e('If you disable the automatic icon download the imagepack selected here will be used.') ?></td>
                    </tr>
                    <tr id="stylesheet-config">
                        <td><?php __e('Stylesheet') ?></td>
                        <td><?php echo($data['style_html']) ?></td>
                        <td class="option-descr"><?php __e('The stylesheet which should be used for the viewer') ?></td>
                    </tr>
                    <tr id="arrow-config">
                        <td><?php __e('Display arrows') ?></td>
                        <td><?php echo($data['arrow_html']) ?></td>
                        <td class="option-descr"><?php __e('If you enable this, the viewer will show arrows next to the channel as in the client.') ?></td>
                    </tr>
                    <tr id="filter-config">
                        <td><?php __e('Display filter') ?></td>
                        <td><input type="radio" name="display-filter" value="standard" <?php if ($data['display-filter'] == "standard"): ?> checked="checked"<?php endif; ?>> <?php __e('Standard') ?><br>
                            <input type="radio" name="display-filter" value="clientsonly" <?php if ($data['display-filter'] == "clientsonly"): ?> checked="checked"<?php endif; ?>> <?php __e('Clientsonly') ?><br>
                            <input type="radio" name="display-filter" value="channelclientsonly" <?php if ($data['display-filter'] == "channelclientsonly"): ?> checked="checked" <?php endif; ?>> <?php __e('Channelclientsonly') ?></td>
                        <td class="option-descr">
                            <span><?php __e('standard: shows the viewer like in the TeamSpeak3 Client') ?></span><br>
                            <span><?php __e('clientsonly: shows only clients') ?></span><br>
                            <span><?php __e('channelclientsonly: shows only channels with clients inside') ?></span>
                        </td>
                    </tr>
                    <tr id="show-image-config">
                        <td><?php __e('Show images') ?></td>
                        <td>
                            <input type="radio" name="show_icons" value="true" <?php if ($data['show-images'] == "true") : ?> checked="checked"<?php endif; ?>> <?php __e('Yes') ?><br>
                            <input type="radio" name="show_icons" value="false" <?php if ($data['show-images'] == "false") : ?> checked="checked"<?php endif; ?>> <?php __e('No') ?><br>
                        </td>
                        <td class="option-descr"><?php __e('If you disable this, the webviewer will not show any images on the right side') ?></td>
                    </tr>
                    <tr id="show-country-icons-config">
                        <td><?php __e('Show country icons') ?></td>
                        <td>
                            <input type="radio" name="show_country_icons" value="true" <?php if ($data['show_country_icons'] == "true") : ?> checked="checked"<?php endif; ?>> <?php __e('Yes') ?><br>
                            <input type="radio" name="show_country_icons" value="false" <?php if ($data['show_country_icons'] == "false") : ?> checked="checked"<?php endif; ?>> <?php __e('No') ?>
                        </td>
                        <td class="option-descr"><?php __e('If country icons should be displayed as in the TeamSpeak3 Client')?></td>
                    </tr>
                    <tr id="show-hierarchy-icons">
                        <td><?php __e('Show hierarchry icons')?></td>
                        <td>
                            <input type="radio" name="show_hierarchy_icons" value="true" <?php if($data['show_hierarchy_icons'] == "true") :?> checked="checked"<?php endif;?>> <?php __e('Yes')?><br>
                            <input type="radio" name="show_hierarchy_icons" value="false" <?php if($data['show_hierarchy_icons'] == "false") :?> checked="checked"<?php endif;?>> <?php __e('No')?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Caching -->
            <div id="tab4">
                <table class="config" cellspacing="0">
                    <tr>
                        <td><?php __e('Enable caching?') ?>
                        <td>
                            <?php echo($data['caching_html']) ?>
                        </td>
                        <td class="option-descr"><?php __e('If this on true the viewer will buffer the data it received. It is strongly recommended to enable caching due to stability reasons.') ?></td>
                    </tr>
                    <tr>
                        <td><?php __e('Standard cachetime') ?></td>
                        <td>
                            <?php echo($data['standard_caching_html']) ?>
                        </td>
                        <td class="option-descr"><?php __e('The time in seconds the viewer should buffer the serverdata (Recommended: 60).') ?></td>
                    </tr>
                </table>
            </div>

            <!-- Stuff -->
            <div id="tab5">
                <table class="config language" cellspacing="0">
                    <tr>
                        <td><?php __e('Language') ?></td>
                        <td><?php echo($data['language_html']) ?></td>
                        <td class="option-descr"><?php __e('Language of the viewer') ?></td>
                    </tr>
                    <tr>
                        <td><?php __e('Provide usage statistics') ?></td>
                        <td>
                            <input type="radio" name="usage-statistics" value="true" <?php if ($data['config']->usage_stats == "true"): ?>checked="checked" <?php endif; ?>> <?php __e('Yes') ?><br>
                            <input type="radio" name="usage-statistics" value="false" <?php if ($data['config']->usage_stats != "true") : ?>checked="checked" <?php endif; ?>> <?php __e('No') ?>
                        </td>
                        <td class="option-descr"><?php __e('If enabled the url to your webviewer will be submitted to devMX') ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Submit-Button -->
        <br>
        <input type="submit" value="<?php __e('Save configfile') ?>" />
    </form>
    <iframe style="display:none" id="module-config" allowTransparency="true" frameborder="0" scrolling="0"></iframe>

</div>
<span id="last">&nbsp;</span>
