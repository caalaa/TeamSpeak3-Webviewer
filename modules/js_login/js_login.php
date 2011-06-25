<?php

class js_login extends ms_Module
{

    function __construct($info, $config, $lang, $mm)
    {
        parent::__construct($info, $config, $lang, $mm);

        // L10N
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

        $this->mManager->loadModule('jQuery');
        $this->mManager->loadModule('js')->loadJS(s_http . '/modules/js_login/ts3_connect.js');

        $prompt_serverpass = _('Please insert the serverpassword');
        $prompt_nickname = _('Please insert a nickname of your choice');

        if (isset($this->config['connect_host']) && $this->config['connect_host'] != $this->config['host'])
            $this->config['host'] = $this->config['connect_host'];

        $this->mManager->loadModule('js')->loadJS("$(document).ready( function() {
                        $('.servername').click(function() {
                                ts3_connect('" . $this->config['host'] . "','" . $this->config['vserverport'] . "'," . $pass_n . "," . $serverpassword. "," . $prompt . ", '" . $prompt_serverpass . "','" . $prompt_nickname . "');
                                })
                        });", 'text');


        $this->mManager->loadModule('style')->loadStyle('.servername {
                        cursor: pointer
                }', 'text');
    }

}

?>
