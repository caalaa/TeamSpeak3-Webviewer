<?php

class loginButton extends ms_Module {

    function __construct($info,$config,$lang,$mm) {
        parent::__construct($info,$config,$lang,$mm);
        $this->mManager->loadModule('jQueryUI');
        $this->mManager->loadModule('js')->loadJS(s_http.'/modules/js_login/ts3_connect.js');
        $this->mManager->loadModule('js')->loadJS("$(document).ready(function() { $('#LoginButton').button(); } );","text");
   }

    function getHeader() {
        $prompt = bool2text($this->config['prompt_username']);
        $pass_n = bool2text($this->config['have_server_pass']);

        $prompt_serverpass = $this->lang['prompt_serverpass'];
        $prompt_nickname = $this->lang['prompt_nickname'];

        if(isset($this->config['connect_host']))
                $this->config['host'] = $this->config['connect_host'];
            return "<input id=\"LoginButton\" type=\"button\" onclick=\"ts3_connect('".$this->config['host']."','".$this->config['vserverport']."',".$pass_n.",".$prompt.", '".$prompt_serverpass."', '".$prompt_nickname."');\" value=\"".$this->config['button_text']."\" />";
    }
	
}
