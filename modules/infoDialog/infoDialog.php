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
class infoDialog extends ms_Module
{

    protected $jsModule;
    protected $styleModule;
    protected $infoDialogOptions = array();

    function init()
    {
        $this->mManager->loadModule('jQueryUI');
        $this->jsModule = $this->mManager->loadModule('js');
        $this->styleModule = $this->mManager->loadModule('style');
    }

    function onStartup()
    {
        $this->jsModule->loadJS(s_http . 'modules/infoDialog/js/jquery.hoverIntent.min.js');
        $this->jsModule->loadJS(s_http . 'modules/infoDialog/infoDialog.js');
        $this->styleModule->loadStyle(s_http . 'modules/infoDialog/infoDialog.css', 'file');
    }

    function onInfoLoaded()
    {
        $this->l10n();

        $_SESSION['infoDialog']['info']['clientlist'] = $this->info['clientlist'];
        $_SESSION['infoDialog']['info']['servergroups'] = $this->info['servergroups'];
        $_SESSION['infoDialog']['info']['channelgroups'] = $this->info['channelgroups'];

        // Reading sizes from config files, else use standard values
        $width = 400;
        $height = 230;

        
        // Load height
        if (isset($this->config['height']))
        {
            $this->infoDialogOptions['height'] = $this->config['height'];
        }
        else
        {
            $this->infoDialogOptions['height'] = $height;
        }

        // Load width;
        if (isset($this->config['width']))
        {
            $this->infoDialogOptions['width'] = $this->config['width'];
        }
        else
        {
            $this->infoDialogOptions['width'] = $width;
        }

        // Load configfile
        $this->infoDialogOptions['configfile'] = $_GET['config'];
        
        // Load closeByMouseout
        $this->infoDialogOptions['closeOnMouseOut'] = $this->config['close_by_mouseout'];

        // Load hoverDelay
        if (isset($this->config['hoverDelay']))
        {
            $this->infoDialogOptions['hoverDelay'] = $this->config['hover_delay'];
        }
        else
        {
            $this->infoDialogOptions['hoverDelay'] = 200;
        }
        
        $this->jsModule->loadJSVar("infoDialog", $this->infoDialogOptions);
    }

    protected function l10n()
    {
        $this->infoDialogOptions['l10n']['load'] = __('loading...');
    }

}