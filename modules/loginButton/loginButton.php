<?php

/**
 *  This file is part of TeamSpeak3 Webviewer.
 *
 *  TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
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
 *  along with TeamSpeak3 Webviewer.  If not, see <http://www.gnu.org/licenses/>.
 */
class loginButton extends ms_Module
{

    protected $jsModule;
    protected $styleModule;

    public function init()
    {

        $this->mManager->loadModule('jQueryUI');
        $this->jsModule = $this->mManager->loadModule('js');
        $this->styleModule = $this->mManager->loadModule('style');
    }

    function onStartup()
    {

        $this->jsModule->loadJS(s_http . 'modules/js_login/ts3_connect.js');
        $this->jsModule->loadJS("jQuery(document).on('ready', function() { jQuery('#LoginButton').button(); } );", "text");
        $this->styleModule->loadStyle('#LoginButton
            {
                margin-bottom: 5px;
            }', 'text');

        $this->styleModule->loadStyle(s_http . 'modules/js_login/js_login.css', 'file');
    }

    function getHeader()
    {
        setL10n($this->config['language'], "ms-tsv-loginButton");
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

        if (isset($this->config['connect_host'])) $this->config['host'] = $this->config['connect_host'];
        return "<input id=\"LoginButton\" type=\"button\" onclick=\"ts3_connect('" . $this->config['host'] . "','" . $this->config['vserverport'] . "'," . $pass_n . ",'" . $serverpassword . "'," . $prompt . ", '" . $prompt_serverpass . "', '" . $prompt_nickname . "');\" value=\"" . $this->config['button_text'] . "\" >";
    }

}
