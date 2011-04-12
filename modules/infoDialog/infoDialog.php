<?php

class infoDialog extends ms_Module {
	function __construct($info,$config,$lang,$mm) {
		parent::__construct($info,$config,$lang,$mm);
		 
		
		$this->config['usefor'] = explode(',',$this->config['usefor']);
		$this->mManager->loadModule('jQueryUI');
		$this->mManager->loadModule('js')->loadJS(msBASEDIR.'/modules/infoDialog/utils.js');
		$_SESSION['infoDialog']['info']['clientlist'] = $this->info['clientlist'];
		$dialog_conf = "{
				autoOpen: false,
				title: ms_title,
				position: [ms_pos.x+20,ms_pos.y+20],";
		
                // Reading sizes from config files, else use standard values
                if(isset($this->config['height']))
                    $dialog_conf .= "height: " . $this->config['height'] .",";

		if(isset($this->config['width']))
			$dialog_conf .= "width: ". $this->config['width'];
                else
                    $dialog_conf .= "width: 400";
                

		$dialog_conf = rtrim($dialog_conf,",");
		$dialog_conf .= "}";

                // Make more config-files possible
                $configfile = '';

                if (isset($_GET['config']))
                    $configfile = $_GET['config'];
                
							
		if(in_array('clients',$this->config['usefor'])) {
		$this->mManager->loadModule('js')->loadJS("$(document).ready(function() {
								var ms_dialogs = new Array();
								$('.client').hover(function() {
									var ms_akt_html;
									var ms_title;
                                                                        var ms_id;
									ms_client = this;
                                                                        ms_id = $(this).attr('id');
									if(ms_dialogs[ms_id]) {
										ms_dialogs[ms_id].dialog('open');
									}
									else {
                                                                                $.get('".msBASEDIR."/modules/infoDialog/getHTML.php', {type: 'client', id: ms_id, title: 'true', config: '".$configfile."'}, function(data) {
                                                                                    ms_title = data;
                                                                                });
										 $.get('".msBASEDIR."/modules/infoDialog/getHTML.php',{type: 'client', id: ms_id, config: '".$configfile."'}, function(data){
														
										
                                                                                    //ms_id = $(ms_client).attr('id');
                                                                                    ms_pos = ms_getPosition(ms_client);
                                                                                    ms_dialogs[ms_id] = $(\"<div><\\/div>\").html(data).dialog(".$dialog_conf.")
										} );
										ms_dialogs[ms_id].dialog('open');
									}
								}, ".($this->config['close_by_mouseout'] ? "function() { ms_dialogs[$(this).attr('id')].dialog('close');}" : "function(){}").");
										
							});",'text');
		} //end if usefor_clients
		
	}
}
