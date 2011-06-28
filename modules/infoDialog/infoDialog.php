<?php

class infoDialog extends ms_Module
{

    function __construct($info, $config, $lang, $mm)
    {
        parent::__construct($info, $config, $lang, $mm);


        $this->config['usefor'] = explode(',', $this->config['usefor']);
        $this->mManager->loadModule('jQueryUI');
        $this->mManager->loadModule('js')->loadJS(s_http . 'modules/infoDialog/utils.js');
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
        $height = 300;

        if (isset($this->config['height']))
        {
                $dialog_conf .= "height: " . $this->config['height'] . ",";
                $height = $this->config['height'];
        }
        else $dialog_conf.= "height: 200,";


        if (isset($this->config['width']))
        {
                $dialog_conf .= "width: " . $this->config['width'];
                $width = $this->config['width'];
        }
        else $dialog_conf .= "width: 400,";


        $dialog_conf = rtrim($dialog_conf, ",");
        $dialog_conf .= "}";

        // Make more config-files possible
        $configfile = '';

        if (isset($_GET['config'])) $configfile = $_GET['config'];


        if (in_array('clients', $this->config['usefor']))
        {
            $this->mManager->loadModule('js')->loadJS("$(document).ready(function() {
								var ms_dialogs = new Array();
                                                                
                                                                $('body').append('<div id=\"dialog\" style=\"overflow:hidden;\"></div>');

								$('.client').hover(function() {
									var ms_akt_html;
									var ms_title;
                                                                        var ms_id;
									ms_client = this;
                                                                        ms_id = $(this).attr('id');
						
                                                                        ms_title = '" . __('loading...') . "';
                                                                        ms_pos = ms_getPosition(ms_client);
                                                                        ms_dialogs[ms_id] = $('#dialog').html('<img  style=\" margin-left: 50%; margin-right:50%; margin-top: 25px;\" src=\"" . s_http . "modules/infoDialog/img/ajax-loader.gif\" alt=\"\"></img>').dialog(" . $dialog_conf . ");
                                                                                
                                                                        ms_dialogs[ms_id].dialog('open');
                                                                        $.get('" . s_http . "modules/infoDialog/getHTML.php', {type: 'client', id: ms_id, title: 'true', config: '" . $configfile . "'}, function(data) {
                                                                                ms_title = data;
                                                                        });
                                                                             $.get('" . s_http . "modules/infoDialog/getHTML.php',{type: 'client', id: ms_id, config: '" . $configfile . "'}, function(data){
														
                                                                                ms_dialogs[ms_id].dialog('option', 'title', ms_title);
                                                                                $('#dialog').html(data);
                                                                            } );					
								}, " . ($this->config['close_by_mouseout'] ? "function() { ms_dialogs[$(this).attr('id')].dialog('close');}" : "function(){}") . ");
										
							});",
                    'text');
        }
    }

}