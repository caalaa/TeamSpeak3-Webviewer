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
class infoDialog extends ms_Module
{

    protected $jsModule;
    protected $styleModule;

    function init()
    {
        $this->config['usefor'] = explode(',', $this->config['usefor']);
        $this->mManager->loadModule('jQueryUI');
        $this->jsModule = $this->mManager->loadModule('js');
        $this->styleModule = $this->mManager->loadModule('style');
    }

    function onStartup()
    {
        $this->jsModule->loadJS(s_http . 'modules/infoDialog/utils.js');
        $this->styleModule->loadStyle(s_http . 'modules/infoDialog/infoDialog.css', 'file');
    }

    function onInfoLoaded()
    {


        $_SESSION['infoDialog']['info']['clientlist'] = $this->info['clientlist'];
        $_SESSION['infoDialog']['info']['servergroups'] = $this->info['servergroups'];
        $_SESSION['infoDialog']['info']['channelgroups'] = $this->info['channelgroups'];
        $dialog_conf = "{
				autoOpen: false,
				title: ms_title,
                                resizeable: false,
				position: [ms_pos.x+20,ms_pos.y+20],";

        // Reading sizes from config files, else use standard values

        $width = 400;
        $height = 230;

        if (isset($this->config['height']))
        {
            $dialog_conf .= "height: " . $this->config['height'] . ",";
            $height = $this->config['height'];
        }
        else $dialog_conf.= "height: $height,";


        if (isset($this->config['width']))
        {
            $dialog_conf .= "width: " . $this->config['width'];
            $width = $this->config['width'];
        }
        else $dialog_conf .= "width: $width,";


        $dialog_conf = rtrim($dialog_conf, ",");
        $dialog_conf .= "}";

        // Make more config-files possible
        $configfile = '';

        if (isset($_GET['config'])) $configfile = $_GET['config'];


        if (in_array('clients', $this->config['usefor']))
        {
            $this->jsModule->loadJS("jQuery(document).on('ready', function() {
								var ms_dialogs = new Array();
                                                                
                                                                jQuery('.devmx-webviewer').append('<div id=\"dialog\" style=\"overflow:hidden;\"><\/div>');

								jQuery('.client').hover(function() {
									var ms_akt_html;
									var ms_title;
                                                                        var ms_id;
									ms_client = this;
                                                                        ms_id = jQuery(this).attr('id');
						
                                                                        ms_title = '" . __('loading...') . "';
                                                                        ms_pos = ms_getPosition(ms_client);
                                                                        ms_dialogs[ms_id] = jQuery('#dialog').html('<img  style=\" margin-left: 50%; margin-right:50%; margin-top: 25px;\" src=\"" . s_http . "modules/infoDialog/img/ajax-loader.gif\" alt=\"\"><\/img>').dialog(" . $dialog_conf . ");
                                                                                
                                                                        ms_dialogs[ms_id].dialog('open');

                                                                        jQuery.ajax({
                                                                            url: '" . s_http . "modules/infoDialog/getHTML.php?type=client&id=' + ms_id + '&title=true&config=" . $configfile . "',
                                                                            crossDomain: true,
                                                                            dataType: 'jsonp',
                                                                            success: function(data) 
                                                                                {
                                                                                    ms_title = data.country + data.name;
                                                                                }

                                                                        });  
                                                                        
                                                                        jQuery.ajax({
                                                                            url: '" . s_http . "modules/infoDialog/getHTML.php?type=client&id=' + ms_id + '&config=" . $configfile . "',
                                                                            crossDomain: true,
                                                                            dataType: 'jsonp',
                                                                            success: function(data) 
                                                                                {
                                                                                    ms_dialogs[ms_id].dialog('option', 'title', ms_title);
                                                                                    jQuery('#dialog').html(data.html);
                                                                                }

                                                                        });					
								}, " . ($this->config['close_by_mouseout'] ? "function() { ms_dialogs[jQuery(this).attr('id')].dialog('close');}" : "function(){}") . ");
										
							});", 'text');
        }
    }

}