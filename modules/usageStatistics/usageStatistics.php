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

class usageStatistics extends ms_Module
{
    /**
     * Loads necessary modules 
     */
    function init()
    {
        $this->mManager->loadModule("jQuery");
    }
    
    /**
     * Returns script code to end of the viewer
     * @return string script
     */
    function getFooter()
    {
        return '<script type="text/javascript" src="'.s_http.'modules/usageStatistics/usageStatistics.js"></script>';
    }
}
?>
