<?php

session_name("tswv");
session_start();

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

define("PROJECTPATH", realpath("../") . "/l10n");
define("ENCODING", "UTF-8");

require_once 'core/utils.php';
require_once 'core/htmlbuilder.php';
require_once 'core/xml.php';
require_once '../libraries/php-gettext/gettext.inc';
require_once '../core/tsv.func.php';

// Outputs the header
echo(file_get_contents("html/header.html"));


// START NON OUTPUT FUCTIONS \\
// Unsets $_SESSION['config'] and $_SESSION['config_xml']
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "return" && isset($_SESSION['validated']) && $_SESSION['validated'] == true)
{
    unset($_SESSION['config']);
    unset($_SESSION['config_xml']);
}

// Sets Locale
if (isset($_SESSION['lang']) && $_SESSION['lang'] != "")
{
    $lang = $_SESSION['lang'];

    T_setlocale(LC_MESSAGES, $lang);

    $domain = "ms-tsv-install";

    if ($newPath == NULL) T_bindtextdomain($domain, PROJECTPATH);
    else T_bindtextdomain($domain, $newPath);

    T_textdomain($domain);
    T_bind_textdomain_codeset($domain, "UTF-8");
}

// Sets Language
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "setlang" && isset($_GET['lang']))
{
    $lang = $_GET['lang'];

    T_setlocale(LC_MESSAGES, $lang);

    $domain = "ms-tsv-install";

    if ($newPath == NULL) T_bindtextdomain($domain, PROJECTPATH);
    else T_bindtextdomain($domain, $newPath);

    T_textdomain($domain);
    T_bind_textdomain_codeset($domain, "UTF-8");

    $_SESSION['lang'] = $_GET['lang'];
}

// Sets password
if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'setpw' && isset($_POST['password']))
{
    // Set Password
    $result = setPassword($_POST['password']);

    if ($result == TRUE)
    {
        echo(throwAlert(__('The password could not be safed. Be sure that writing permissions are set to pw.xml')));
        require_once 'html/set_password.php';
        exit;
    }

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

    require_once 'html/select_config.php';
    exit;
}

// Deletes caches
if (isset($_SESSION['validated']) && $_SESSION['validated'] == true && isset($_REQUEST['action']) && $_REQUEST['action'] == "delete" && isset($_REQUEST['config']))
{
    echo(deleteConfigfile($_REQUEST['config']));

    $data = createConfigHtml();

    require_once 'html/select_config.php';
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

        echo(_("The password you provided was not correct. Please try again"));
    }
}

// STOPS NON OUTPUT FUNCTIONS \\
// If the language has not been setted
if (!isset($_SESSION['lang']))
{
    require_once '../core/tsv.func.php';
    require_once 'html/select_language.php';

    exit;
}

// If the password has not been setted
if (!passwordSetted())
{
    require_once 'html/set_password.php';
    exit;
}

// If password is setted but has not been entered yet
if (passwordSetted() && !isset($_SESSION['validated']) || $_SESSION['validated'] != true)
{
    require_once 'html/enter_pw.php';
    exit;
}

// If no configfile is selected
if (!isset($_SESSION['config']) || $_SESSION['config'] == "")
{
    $data = createConfigHtml();

    // Check Functions
    $data['err_warn'] = checkFunctions();

    require_once 'html/select_config.php';
    exit;
}

// If password is setted and has been entered and Configfile and Language is setted and Configfile should be written
if (passwordSetted() && $_SESSION['validated'] == true && isset($_SESSION['config']) && isset($_SESSION['lang']) && isset($_REQUEST['action']) && $_REQUEST['action'] == "submit")
{
    str_replace(".xml", "", $_SESSION['config_xml']);

    $xml = simplexml_load_string($_SESSION['config_xml']);

    $necessary_vars = array("serveradress", "queryport", "serverport", "login_needed", "servericons", "imagepack", "style", "arrows", "caching", "language");
    $vars_unavailable = false;

    // START VAR CHECKING \\
    // Check if necessary vars are full
    foreach ($necessary_vars as $var)
    {
        if (empty($_POST[$var]) || $_POST[$var] == NULL || $_POST[$var] == "")
        {
            $vars_unavailable = true;
            echo throwAlert($var . " " . __('is not set. Please check if you filled out all blanks.'));
        }
    }

    if ($vars_unavailable)
    {
        $data = createEditHtml();

        require_once 'html/config.php';
        exit;
    }
    // END VAR CHECKING \\

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
    $xml->style = $_POST['style'];
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
                0775, true);
    }


    $imagepath = "../cache/" . $_POST['serveradress'] . $_POST['queryport'] . "/" . $_POST['serverport'] . "/server/images";
    if (!is_dir($imagepath))
    {
        mkdir($imagepath, 0775, true);
    }


    if (!file_exists("../config/" . $_SESSION['config'] . ".xml"))
    {
        echo (throwAlert(__('Configfile is not writable. Please check if the required permissions are given to write the file. We recomment setting the file to CHMOD 775')));
        exit;
    }

    $data = createEditHtml();

    echo(throwWarning(__('Configfile successfully saved.')));

    require_once 'html/config.php';

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

    require_once 'html/config.php';
    exit;
}
?>
