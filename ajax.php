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
if (isset($_GET['s']) && $_GET['s'] == "true" && isset($_GET['config']) && isset($_GET['id']) && $_GET['id'] != "")
{
    header('Content-type: text/javascript');
    
    $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
    $url = str_replace("/ajax.php", "", $url);
    $url = str_replace("//", "\/\/", $url);
    
    $id = str_replace("#", "", $_GET['id']);
    
    $config = $_GET['config'];
    $data = <<<EOF
var scriptdata = '
<script type="text/javascript" src="%s/ajax.php?config=%s&json=false"><\/script>
<script type="text/javascript">
var viewerData;
jQuery(document).ready(function(){
    jQuery.ajax(
            {
                url: \'%s/ajax.php?config=%s&json=true\',
                crossDomain: true,
                dataType: "jsonp",
                jsonp: "callback",
                jsonpCallback: "viewerReceived"
            }
    );
});
function viewerReceived (data){
    jQuery(data.script.txt).each(function(index, value){
        var s = document.createElement("script");
        s.type = "text/javascript";
        document.getElementsByTagName("head")[0].appendChild(s);
        s.text = value;
    });
    var s = document.createElement("script");
    s.type = "text/javascript";
    jQuery("#%s").html(data.html);
    document.getElementsByTagName("head")[0].appendChild(s);
    s.text = "jQuery(document).triggerHandler(\'ready\');";
}
<\/script>';
document.write(scriptdata);
EOF;
    $data = sprintf($data, $url, $config, $url, $config, $id);
    $data = preg_replace("/[\\t\\n\\r]/", "", $data);
    echo $data;
}
else if (isset($_GET['config']) && isset($_GET['json']))
{
    $ajax = true;
    $ajaxConfig = $_GET['config'];

    require_once 'TSViewer.php';

    // If javascript should be sent
    if (isset($_GET['json']) && $_GET['json'] == "false")
    {
        $createScript = "";

        header('Content-type: text/javascript');

        foreach ($ajaxScriptOutput['src'] as $s)
        {
            $createScript .= "document.write('<script type=\"text/javascript\" src=\"" . $s . "\"><\/script>');\r\n";
        }
        
        echo($createScript);
    }
    // If json should be sent
    else if (isset($_GET['json']) && $_GET['json'] == "true")
    {
        header('Content-type: application/json');

        echo($_GET['callback'] . '(' . json_encode(array("html" => preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '' ,$ajaxHtmlOutput), "script" => $ajaxScriptOutput)) . ')');
    }
}
?>
