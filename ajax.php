<?php

$ajax = true;
$ajaxConfig = $_GET['config'];

require_once 'TSViewer.php';

header('Content-type: application/json');
echo($_GET['callback'].'('.json_encode(array("html" => $ajaxHtmlOutput), JSON_HEX_QUOT).')');
?>