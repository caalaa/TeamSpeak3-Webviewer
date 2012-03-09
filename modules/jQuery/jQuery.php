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
class jQuery extends ms_Module
{

    function init()
    {
        $fullPath = true;

        // Check if a full path is given in the configfile
        if (!preg_match("/(http(s)?)(.*)(\.)(.*)/", $this->config['jQuery_path'])) $fullPath = false;

        // relative path
        if (!$fullPath)
        {
            $this->mManager->loadModule('js')->loadJS(s_http . "modules/jQuery/" . $this->config['jQuery_path']);
            $this->mManager->loadModule('js')->loadJS("$.noConflict();", 'text');
        }
        
        // absolute path
        else
        {
            $this->mManager->loadModule('js')->loadJS(stripslashes($this->config['jQuery_path']));
            $this->mManager->loadModule('js')->loadJS("$.noConflict();", 'text');
        }
    }

}

?>
