<?php

class js_login extends ms_Module
{
    
    protected $jsModule;
    protected $styleModule;
    
    public function init() {
        
        $this->mManager->loadModule('jQuery');
        $this->jsModule = $this->mManager->loadModule('js');
        $this->styleModule = $this->mManager->loadModule('style');
    }
    
    function onStartup()
    {
  
        setL10n($this->config['language'], "ms-tsv-js_login");
        $prompt = bool2text($this->config['prompt_username']);
        $pass_n = bool2text($this->config['prompt_pass']);
        $serverpassword = $this->config['server_password'];

        if ($serverpassword != null && $serverpassword != "")
        {
            $pass_n = "false";
        }
        else
        {
            $serverpassword = 0;
        }

        
        $this->jsModule->loadJS(s_http . '/modules/js_login/ts3_connect.js');

        $prompt_serverpass = __('Please insert the serverpassword');
        $prompt_nickname = __('Please insert a nickname of your choice');

        if (isset($this->config['connect_host']) && $this->config['connect_host'] != $this->config['host'])
            $this->config['host'] = $this->config['connect_host'];

        $this->jsModule->loadJS("$(document).ready( function() {
                        $('.servername').click(function() {
                                ts3_connect('" . $this->config['host'] . "','" . $this->config['vserverport'] . "'," . $pass_n . "," . $serverpassword. "," . $prompt . ", '" . $prompt_serverpass . "','" . $prompt_nickname . "');
                                })
                        });", 'text');


        $this->styleModule->loadStyle('.servername {
                        cursor: pointer
                }', 'text');
    }

}

?>
