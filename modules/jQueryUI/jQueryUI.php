<?php

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
        if (!preg_match("/(http)(.*)(\.)(.*)/", $this->config['css_path'])) $cssFullPath = false;

        // Check if a full path is given in the configfile
        if (!preg_match("/(http)(.*)(\.)(.*)/", $this->config['js_path'])) $jsFullPath = false;

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
