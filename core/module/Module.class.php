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
class ms_Module
{

    protected $info;
    protected $config;
    protected $lang;
    protected $mManager;

    function __construct($config, $lang, $modulemanager)
    {
        $this->config = $config;
        $this->lang = $lang;
        $this->mManager = $modulemanager;

    }
    
    //called after the module was created
    public function init() {
        
    }

    public function getHeader()
    {
        
    }

    public function getFooter()
    {
        
    }

    /*
     * Events thrown by viewer:
        onStartup:  after loading the modules;
        onShutdown: after all regular output;
    
     * Events thrown by standard modules
        HTMLframe Module:
            onHead   after outputting the <HEAD> tag 
            onHtml   after outputting the <HTML> tag;
            onBody   after outputting the <BODY> tag; (normally the same as getHeader())
        style Module:
            onStyle<name_of_the_style> if trigger_style in config is setted to true;
        legende Module:
            onAfterLegend   after the legend has been outputted
     */

    public function onEvent($e)
    {
        $e = ucfirst($e);
        $modname = "on$e";
        $ret = '';
        if (method_exists($this, $modname))
        {
            $ret = $this->$modname();
        }
        return $ret;

    }
    
    public function setInfo($info) {
        $reload = false;
        if(isset($this->info)) {
            $reload = true;
        }
        $this->info = $info;
        if($reload) {
            $this->onEvent('infoReloaded');
        }
        else {
            $this->onEvent('infoLoaded');
        }
        
    }

}
