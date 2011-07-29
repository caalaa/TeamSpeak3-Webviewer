<?php

class jQuery extends ms_Module
{

    function init()
    {
        $fullPath = true;

        // Check if a full path is given in the configfile
        if (!preg_match("/(http)(.*)(\.)(.*)/", $this->config['jQuery_path'])) $fullPath = false;

        // relative path
        if (!$fullPath) $this->mManager->loadModule('js')->loadJS(s_http . "modules/jQuery/" . $this->config['jQuery_path']);
        // absolute path
        else $this->mManager->loadModule('js')->loadJS(stripslashes($this->config['jQuery_path']));
    }

}

?>
