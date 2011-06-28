<?php

class loginButton extends ms_Module
{

    function __construct($info, $config, $lang, $mm)
    {
        parent::__construct($info, $config, $lang, $mm);
        
        //L10N
        setL10n($this->config['language'], "ms-tsv-loginButton");
        
        $this->mManager->loadModule('jQueryUI');
        $this->mManager->loadModule('js')->loadJS(s_http . '/modules/js_login/ts3_connect.js');
        $this->mManager->loadModule('js')->loadJS("$(document).ready(function() { $('#LoginButton').button(); } );", "text");
        $this->mManager->loadModule('style')->loadStyle('#LoginButton
            {
                margin-bottom: 5px;
            }', 'text');
    }

    function getHeader()
    {
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

        $prompt_serverpass = __('Please insert the serverpassword');
        $prompt_nickname = __('Please insert a nickname of your choice');

        if (isset($this->config['connect_host']))
            $this->config['host'] = $this->config['connect_host'];
        return "<input id=\"LoginButton\" type=\"button\" onclick=\"ts3_connect('" . $this->config['host'] . "','" . $this->config['vserverport'] . "'," . $pass_n . "," . $serverpassword . "," . $prompt . ", '" . $prompt_serverpass . "', '" . $prompt_nickname . "');\" value=\"" . $this->config['button_text'] . "\" />";
    }

}
