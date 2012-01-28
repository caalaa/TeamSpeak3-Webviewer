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
class infoTab extends ms_Module
{

    private $akt_tab;
    private $nav;
    private $tabs;
    protected $jsModule;
    protected $styleModule;

    function init()
    {
        $this->akt_tab = 0;
        $this->nav = "";
        $this->tabs = "";
        $this->have_tabs = false;
        $this->mManager->loadModule('jQueryUI');
        $this->jsModule = $this->mManager->loadModule('js');
        $this->styleModule =  $this->mManager->loadModule('style');
    }

    function onStartup()
    {
        $this->jsModule->loadJS('jQuery(document).on("ready", function() {
                 jQuery( "#mstabs" ).tabs({
                        fx: { height: "toggle", duration: "slow" }
                    });
		});', "text");
        
        $this->styleModule->loadStyle(s_http . 'modules/infoTab/infoTab.css');
    }

    function addTab($title, $content)
    {
        $this->have_tabs = true;
        $this->nav .= '<li><a href="#msInfoTab-' . $this->akt_tab . '">' . $title . '</a></li>' . "\r\n";
        $this->tabs .= '<div id="msInfoTab-' . $this->akt_tab . '"><p>' . $content . '</div>' . "\r\n";
        $this->akt_tab++;
    }

    function getFooter()
    {
        $output = "";

        if ($this->have_tabs)
        {
            $output .= $this->mManager->triggerEvent('beforeTabs');
            $output .= '<br><br>';
            $output .= '<div class="devmx-webviewer-tabs" id="mstabs">';
            $output .= '<ul>';
            $output .= $this->nav;
            $output .= '</ul>';
            $output .= $this->tabs;
            $output .= '</div>';
            $output .= $this->mManager->triggerEvent('afterTabs');
        }
        return $output;
    }

}

