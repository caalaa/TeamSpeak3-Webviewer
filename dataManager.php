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
session_name('ms_ts3Viewer');
session_start();
if (!isset($_GET['action']) || !isset($_GET['id']))
    exit();
switch ($_GET['action'])
{

    case 'load':
        if(isset($_GET['config'] )) {
            echo $_SESSION['dataManager'][$_GET['config']][$_GET['field']][$_GET['id']];
        }
        else {
            echo $_SESSION['dataManager'][$_GET['field']][$_GET['id']];
        }
        break;
    case 'save':
        if (isset($_GET['data'])) {
            if(isset($_GET['config'])) {
                $_SESSION['dataManager'][$_GET['config']][$_GET['field']][$_GET['id']] = $_GET['data'];
            }
            else {
                $_SESSION['dataManager'][$_GET['field']][$_GET['id']] = $_GET['data'];
            }
        }
            
        echo 'saved';
        break;
    case 'delete':
        if(isset($_GET['config'])) {
            unset($_SESSION['dataManager'][$_GET['config']][$_GET['field']][$_GET['id']]);
        }
        else {
            unset($_SESSION['dataManager'][$_GET['field']][$_GET['id']]);
        }
        echo 'deleted';
}

