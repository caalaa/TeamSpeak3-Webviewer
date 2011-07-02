<?php

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

class about extends ms_Module
{
    
    protected $sent = FALSE;
    
    function init()
    {
        $this->mManager->loadModule("jQueryUI");
    }
    
    function getText() {
        return '<span class="ui-state-highlight" style="padding:5px; font-size:10px; margin-bottom:5px; float:right;">Powered by <a href="http://en.maxesstuff.de/software/teamspeak3-webviewer"  target="_blank">Maxesstuff Webviewer</a></span>';
    }
    
    function onBeforeTabs() {
        
            return $this->getText();
        
    }
    
    function getFooter() {
        if(!$this->mManager->moduleIsLoaded('infoTab')) {
            return $this->getText();
        }
    }

}

?>
