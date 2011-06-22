<?php

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

session_name("tswv");
session_start();

define("PROJECTPATH", realpath("./../") . "/l18n");
define("ENCODING", "UTF-8");


if (!$_SESSION['validated']) die("No Access");

require_once '../../libraries/php-gettext/gettext.inc';
require_once '../core/htmlbuilder.php';
require_once 'utils.php';

// l18n
$lang = $_SESSION['lang'];

setlocale(LC_MESSAGES, $lang . ".utf8", $lang . ".UTF8", $lang . ".utf-8",
        $lang . "UTF-8", $lang);

$domain = "ms-tsv-install";

bindtextdomain($domain, PROJECTPATH);
textdomain($domain);
bind_textdomain_codeset($domain, ENCODING);

// Outputs header
echo(file_get_contents("../html/header_xmledit.html"));

$module = $_GET['module'];

// If File should be saved
if ($_REQUEST['action'] == "submit" && isset($_REQUEST['module']))
{
    $handle = fopen("../../modules/$module/$module.xml", "w");
    fwrite($handle, str_replace('\\"', '"', $_POST['xml']));
    fclose($handle);

    echo(throwWarning(_('Configfile successfully saved!')));
}

$xml = simplexml_load_file("../../modules/$module/$module.xml")->asXML();

$html = array();
$html['code'] = $xml;
$html['module_edit'] = $module;
$html['xml_script'] = 'var editor = CodeMirror.fromTextArea(document.getElementById("code"), {mode: {name: "xml", alignCDATA: true}});';

require_once '../html/xmledit.php';

// Outputs Editor
echo($out);

// Outputs Footer
echo(file_get_contents("../html/footer.html"));
?>
