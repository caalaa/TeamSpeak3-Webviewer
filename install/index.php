<?php

session_name("tswv");
session_start();

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

require_once 'core/utils.php';
require_once 'core/htmlbuilder.php';
require_once 'core/xml.php';

// Outputs the header
echo(file_get_contents("html/header.html"));


// START NON OUTPUT FUCTIONS \\
// Unsets $_SESSION['config'] and $_SESSION['config_xml']
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "return" && isset($_SESSION['validated']) && $_SESSION['validated'] == true)
{
    unset($_SESSION['config']);
    unset($_SESSION['config_xml']);
}

// Sets Language
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "setlang" && isset($_GET['lang']))
{
    $_SESSION['lang'] = $_GET['lang'];
}

// Sets password
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'setpw' && isset($_POST['password']))
{
    // Set Password
    setPassword($_POST['password']);
    $_SESSION['validated'] = true;
}

// Creates new config-file
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "new_config")
{
    $_SESSION['config'] = $_REQUEST['configname'];
    $_SESSION['config_xml'] = simplexml_load_file("../config/template.xml")->asXML();
}

// Sets Configfile
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "set_config" && isset($_GET['configname']))
{
    $_SESSION['config'] = $_GET['configname'];
    $_SESSION['config_xml'] = simplexml_load_file("../config/" . $_SESSION['config'] . ".xml")->asXML();
}

// Flushes caches
if (isset($_SESSION['validated']) && $_SESSION['validated'] == true && isset($_REQUEST['action']) && $_REQUEST['action'] == 'fc' && isset($_REQUEST['config']))
{
    $data = createConfigHtml();

    echo(flushCache($_REQUEST['config']));
    echo(replaceValues("html/select_config.html", $data));
    exit;
}

// Deletes caches
if (isset($_SESSION['validated']) && $_SESSION['validated'] == true && isset($_REQUEST['action']) && $_REQUEST['action'] == "delete" && isset($_REQUEST['config']))
{
    echo(deleteConfigfile($_REQUEST['config']));
    
    $data = createConfigHtml();
    echo(replaceValues("html/select_config.html", $data));
    exit;
}

// If password needs to be validated
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'validate' && isset($_POST['password']) && passwordSetted())
{

    $pw = file_get_contents("pw.xml");

    if (sha1(md5($_POST['password'])) == $pw)
    {
        // If password is right
        $_SESSION['validated'] = true;
    }
    else
    {
        // If password is wrong
        $_SESSION['validated'] = false;

        if ($_SESSION['lang'] == "en")
                echo(throwAlert("The Password you provided was not correct!"));
        else
                echo(throwAlert("Das Passwort ist nich korrekt. Bitte versuchen Sie es erneut!"));
    }
}

// STOPS NON OUTPUT FUNCTIONS \\
// If the language has not been setted
if (!isset($_SESSION['lang']))
{
    echo(file_get_contents("html/select_language.html"));
    exit;
}

// If the password has not been setted
if (!passwordSetted())
{
    echo(replaceValues("html/set_password.html"));
    exit;
}

// If password is setted but has not been entered yet
if (passwordSetted() && !isset($_SESSION['validated']) || $_SESSION['validated'] != true)
{
    echo(replaceValues("html/enter_pw.html"));
    exit;
}

// If no configfile is selected
if (!isset($_SESSION['config']) || $_SESSION['config'] == "")
{
    $data = createConfigHtml();
    
    // Check Functions
    $data['err_warn'] = checkFunctions();
    
    echo(replaceValues("html/select_config.html", $data));
    exit;
}

// If password is setted and has been entered and Configfile and Language is setted and Configfile should be written
if (passwordSetted() && $_SESSION['validated'] == true && isset($_SESSION['config']) && isset($_SESSION['lang']) && isset($_REQUEST['action']) && $_REQUEST['action'] == "submit")
{
    str_replace(".xml", "", $_SESSION['config_xml']);

    $xml = simplexml_load_string($_SESSION['config_xml']);

    $xml->host = $_POST['serveradress'];
    $xml->queryport = $_POST['queryport'];
    $xml->vserverport = $_POST['serverport'];
    $xml->login_needed = $_POST['login_needed'];
    $xml->username = $_POST['username'];
    $xml->password = $_POST['password'];

    if (empty($_POST['module']))
    {
        $pre = "htmlframe,style";
    }
    else
    {
        $pre = "htmlframe,style,";
    }

    $xml->modules = $pre . implode(",", $_POST['module']);

    $xml->use_serverimages = $_POST['servericons'];
    $xml->imagepack = $_POST['imagepack'];
    $xml->show_arrows = $_POST['arrows'];
    $xml->enable_caching = $_POST['caching'];
    $xml->standard_cachetime = $_POST['standard_caching'];
    $xml->cachetime_channellist_voice_flags_icon_limits = $_POST['standard_caching'];
    $xml->cachetime_serverinfo = $_POST['standard_caching'];
    $xml->cachetime_clientlist_away_voice_groups_info_times = $_POST['standard_caching'];
    $xml->cachetime_servergrouplist = $_POST['standard_caching'];
    $xml->cachetime_channelgrouplist = $_POST['standard_caching'];

    $xml->language = $_POST['language'];



    saveXmlFile("../config/" . $_SESSION['config'] . ".xml", $xml);

    if (!is_dir("../cache/" . $_POST['serveradress'] . $_POST['queryport'] . "/" . $_POST['serverport'] . "/query/time/"))
    {
        mkdir("../cache/" . $_POST['serveradress'] . $_POST['queryport'] . "/" . $_POST['serverport'] . "/query/time/",
                0777, true);
    }


    $imagepath = "../cache/" . $_POST['serveradress'] . $_POST['queryport'] . "/" . $_POST['serverport'] . "/server/images";
    if (!is_dir($imagepath))
    {
        mkdir($imagepath, 0777, true);
    }


    if (!file_exists("../config/" . $_SESSION['config'] . ".xml"))
    {
        if ($_SESSION['lang'] == "en")
                echo(throwAlert("Configfile not writeable! Please set chmod for the 'config' directory to 775"));
        else
                echo(throwAlert("Konfigdatei kann nicht geschrieben werden! Bitte setzen Sie die Berechtigungen für das Verzeichnis 'Config' auf 775!"));
        exit;
    }

    $data = createEditHtml();

    if ($_SESSION['lang'] == "en")
            echo(throwWarning("Configfile successfully written!"));
    else echo(throwWarning("Configfile erfolgreich gespeichert!"));

    echo(replaceValues("html/config.html", $data));

    echo('<script type="text/javascript">
            $(document).ready(function(){
                setTimeout(redirect(), 3000);
            });
          </script>');

    exit;
}

// If password is setted and has been entered and Configfile and Language is setted
if (passwordSetted() && $_SESSION['validated'] == true && isset($_SESSION['config']) && isset($_SESSION['lang']))
{
    $data = createEditHtml();

    echo(replaceValues("html/config.html", $data));
    exit;
}
?>
