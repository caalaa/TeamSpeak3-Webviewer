<?php

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

class about extends ms_Module
{

    function __construct($config, $info, $lang, $mManager)
    {
        parent::__construct($config, $info, $lang, $mManager);

        // Load jQuery
        $this->mManager->loadModule("jQuery");

        // Load jQueryUI
        $this->mManager->loadModule("jQueryUI");

        // Insert Code
        $this->mManager->loadModule("js")->loadJS('$(document).ready(function(){
            
                $(".servername").before(\'<p class="ui-state-highlight" style="padding:5px; font-size:10px; margin-bottom:5px; float:right;" target="_blank">Powered by <a href="http://en.maxesstuff.de/software/teamspeak3-webviewer">Maxesstuff Webviewer</a></p>\');
                        });',
                "text");
    }

}

?>
