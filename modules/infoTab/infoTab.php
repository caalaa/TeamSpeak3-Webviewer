<?php

class infoTab extends ms_Module
{

    private $akt_tab;
    private $nav;
    private $tabs;
    protected $jsModule;

    function init()
    {
        $this->akt_tab = 0;
        $this->nav = "";
        $this->tabs = "";
        $this->have_tabs = false;
        $this->mManager->loadModule('jQueryUI');
        $this->jsModule = $this->mManager->loadModule('js');
    }
    
    function onStartup() {
        $this->jsModule->loadJS('$(document).ready(function() {
                 $( "#mstabs" ).tabs({
                        fx: { height: "toggle", duration: "slow" }
                    });
		});',
                "text");
    }

    function addTab($title, $content)
    {
        $this->have_tabs = true;
        $this->nav .= '<li style="font-size:16px;"><a href="#msInfoTab-' . $this->akt_tab . '">' . $title . '</a></li>' . "\r\n";
        $this->tabs .= '<div style="font-size:14px;" id="msInfoTab-' . $this->akt_tab . '"><p>' . $content . '</div>' . "\r\n";
        $this->akt_tab++;
    }

    function getFooter()
    {
        $output = "";
        
        if ($this->have_tabs)
        {
            $output .= $this->mManager->triggerEvent('beforeTabs');
            $output .= '<br><br>';
            $output .= '<div id="mstabs">';
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

