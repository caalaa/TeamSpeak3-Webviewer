<?php
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

