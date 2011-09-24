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
class jQueryUI extends ms_Module
{

    protected $styleModule;
    protected $jsModule;

    function init()
    {
        $this->mManager->loadModule('jQuery');
        $this->jsModule = $this->mManager->loadModule('js');
        $this->styleModule = $this->mManager->loadModule('style');
    }

    function onStartup()
    {

        $cssFullPath = true;
        $jsFullPath = true;
        // Check if a full path is given in the configfile
        if (!preg_match("/(http(s)?)(.*)(\.)(.*)/", $this->config['css_path'])) $cssFullPath = false;

        // Check if a full path is given in the configfile
        if (!preg_match("/(http(s)?)(.*)(\.)(.*)/", $this->config['js_path'])) $jsFullPath = false;

        // CSS
        // relative path
        if (!$cssFullPath) $this->styleModule->loadStyle(s_http . "modules/jQueryUI/" . $this->config['css_path']);
        // absolute path
        else $this->styleModule->loadStyle($this->config['css_path']);


        // JS
        // relative path
        if (!$jsFullPath) $this->jsModule->loadJS(s_http . "modules/jQueryUI/" . $this->config['js_path']);
        // absolute path
        else $this->jsModule->loadJS($this->config['js_path']);
    }

}
