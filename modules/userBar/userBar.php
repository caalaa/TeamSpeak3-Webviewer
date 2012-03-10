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
class userBar extends ms_Module
{

    public $max_clients = "";
    public $clients_online = "";
    protected $jsModule;
    protected $styleModule;

    public function init()
    {

        $this->mManager->loadModule("jQueryUI");
        $this->jsModule = $this->mManager->loadModule('js');
        $this->styleModule = $this->mManager->loadModule('style');
    }

    function onInfoLoaded()
    {


        //L10N
        setL10n($this->config['language'], "teamspeak3-webviewer");


        $this->max_clients = $this->info['serverinfo']['virtualserver_maxclients'];
        $this->clients_online = $this->info['serverinfo']['virtualserver_clientsonline'] - $this->info['serverinfo']['virtualserver_queryclientsonline'];

        $per_cent = $this->clients_online / $this->max_clients * 100;

        $this->jsModule->loadJSVar("userBar", array("perCent" => $per_cent));
        $this->jsModule->loadJS(s_http . "modules/userBar/userBar.js");
        $this->styleModule->loadStyle(s_http . 'modules/userBar/userBar.css');
    }

    public function getHeader()
    {
        setL10n($this->config['language'], "teamspeak3-webviewer");
        return('<div class="devmx-webviewer-userBar"><p>' . sprintf("%s of %s are currently online.", $this->clients_online, $this->max_clients) . '</p>
                <div id="userBar"></div></div>');
    }

}

?>
