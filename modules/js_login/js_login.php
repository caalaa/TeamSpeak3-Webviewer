<?php
class js_login extends ms_Module {

    function __construct($info,$config,$lang,$mm) {
        parent::__construct($info,$config,$lang,$mm);

        $prompt = bool2text($this->config['prompt_username']);
        $pass_n = bool2text($this->config['have_server_pass']);

        $this->mManager->loadModule('jQuery');
        $this->mManager->loadModule('js')->loadJS(s_http.'/modules/js_login/ts3_connect.js');

        $prompt_serverpass = $this->lang['prompt_serverpass'];
        $prompt_nickname = $this->lang['prompt_nickname'];

        if(isset($this->config['connect_host']) && $this->config['connect_host'] != $this->config['host'])
                $this->config['host'] = $this->config['connect_host'];
        $this->mManager->loadModule('js')->loadJS("$(document).ready( function() {
                        $('.servername').click(function() {
                                ts3_connect('".$this->config['host']."','".$this->config['vserverport']."',".$pass_n.",".$prompt.", '".$prompt_serverpass."','".$prompt_nickname."');
                                })
                        });",'text');


        $this->mManager->loadModule('style')->loadStyle('.servername {
                        cursor: pointer
                }','text');
    }
}
?>
