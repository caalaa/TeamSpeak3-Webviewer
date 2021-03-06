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
class js_login extends ms_Module
{
    
    protected $jsModule;
    protected $styleModule;
    
    public function init() {
        
        $this->mManager->loadModule('jQuery');
        $this->mManager->loadModule('jQueryUI');
        $this->jsModule = $this->mManager->loadModule('js');
        $this->styleModule = $this->mManager->loadModule('style');
    }
    
    public function onStartup()
    {
  
        setL10n($this->config['language'], "teamspeak3-webviewer");
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

        
        $this->jsModule->loadJS(s_http . 'modules/js_login/ts3_connect.js');

        $prompt_serverpass = __('Please insert the serverpassword');
        $prompt_nickname = __('Please insert a nickname of your choice');

        if (isset($this->config['connect_host']) && $this->config['connect_host'] != $this->config['host'])
            $this->config['host'] = $this->config['connect_host'];

        $this->jsModule->loadJS("jQuery(document).on('ready', function() {
                        jQuery('.servername').click(function() {
                                ts3_connect('" . $this->config['host'] . "','" . $this->config['vserverport'] . "'," . $pass_n . ",'" . $serverpassword. "'," . $prompt . ", '" . $prompt_serverpass . "','" . $prompt_nickname . "');
                                })
                        });", 'text');

        $this->styleModule->loadStyle(s_http . "modules/js_login/js_login.css");
    }
}

?>
