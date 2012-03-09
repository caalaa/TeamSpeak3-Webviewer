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
class htmlframe extends ms_Module
{

    public function onHtmlStartup()
    {
        if ($this->config['ajaxEnabled'] !== true)
        {
            $html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
                    "http://www.w3.org/TR/html4/loose.dtd">
                    <html>' . $this->mManager->triggerEvent("html") . '
                    <head>
                    <title>' . $_SERVER['SERVER_NAME'] . '</title>
                    
                    <!-- Meta Information -->
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                    <meta name="description" content="devMX Webviewer">
                    
                    <meta name="software" content="TeamSpeak3 Webviewer">
                    <meta name="version" content="' . version . '">
                    <meta name="author" content="devMX">
                    <meta name="url" content="http://devmx.de">

                    <!-- End Meta Information -->
                    ' . $this->mManager->triggerEvent("Head") . '</head>
                    <body>
                    <div class="devmx-webviewer">' . $this->mManager->triggerEvent("Body");
            return $html;
        }
        else
        {
            return '<div class="devmx-webviewer">' . $this->mManager->triggerEvent("Head") . $this->mManager->triggerEvent("Body");
        }
    }

    public function onHtmlShutdown()
    {
        if ($this->config['ajaxEnabled'] !== true)
        {
            return "</div></body></html>";
        }
    }

}
