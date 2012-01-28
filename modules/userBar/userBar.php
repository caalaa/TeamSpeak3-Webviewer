<?php

/**
 * This file is part of TeamSpeak3 Webviewer.
 *
 * TeamSpeak3 Webviewer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TeamSpeak3 Webviewer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TeamSpeak3 Webviewer. If not, see http://www.gnu.org/licenses/.
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
        setL10n($this->config['language'], "ms-tsv-userBar");


        $this->max_clients = $this->info['serverinfo']['virtualserver_maxclients'];
        $this->clients_online = $this->info['serverinfo']['virtualserver_clientsonline'] - $this->info['serverinfo']['virtualserver_queryclientsonline'];

        $per_cent = $this->clients_online / $this->max_clients * 100;
        $this->jsModule->loadJS('jQuery(document).on("ready", function() {
                                                   jQuery("#userBar").progressbar({
                                                   value: ' . $per_cent . '
                                                   });
                                                  });', "text");

        $this->styleModule->loadStyle(s_http . 'modules/userBar/userBar.css');
    }

    public function getHeader()
    {
        setL10n($this->config['language'], "ms-tsv-userBar");
        return('<div class="devmx-webviewer-userBar"><p>' . $this->clients_online . ' ' . __('of') . ' ' . $this->max_clients . ' ' . __('clients are currently online') . '</p>
                <div id="userBar"></div></div>');
    }

}

?>
