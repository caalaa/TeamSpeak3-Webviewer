<?php

/* Author    : Maximilian Narr
 * Homepage  : http://maxesstuff.de
 * Email     : maxe@maxesstuff.de
 */

session_name("tswv");
session_start();

if (!$_SESSION['validated'])
    die("No Access");

require_once '../core/htmlbuilder.php';
require_once 'utils.php';

// Outputs header
echo(file_get_contents("../html/header_xmledit.html"));

$module = $_GET['module'];

// If File should be saved
if ($_REQUEST['action'] == "submit" && isset($_REQUEST['module']))
{
    $handle = fopen("../../modules/$module/$module.xml", "w");
    fwrite($handle, str_replace('\\"', '"', $_POST['xml']));
    fclose($handle);
    
    if($_SESSION['lang'] == "en")
        echo(throwWarning ("Configfile successfully saved!"));
    else
        echo(throwWarning ("Konfigurationsdatei erfolgreich gespeichert!"));
}

$xml = simplexml_load_file("../../modules/$module/$module.xml")->asXML();

$html = array();
$html['code'] = $xml;
$html['module_edit'] = $module;
$html['xml_script'] = 'var editor = CodeMirror.fromTextArea(document.getElementById("code"), {mode: {name: "xml", alignCDATA: true}});';

$out = replaceValues("../html/xmledit.html", $html, true);

// Outputs Editor
echo($out);

// Outputs Footer
echo(file_get_contents("../html/footer.html"));
?>
