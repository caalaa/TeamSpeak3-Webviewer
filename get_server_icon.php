<?php
$_GET['id'] = intval($_GET['id']);
if($_GET['id'] < 0)
    $_GET['id'] = 4294967296 + $_GET['id'];


include "utils.func.php";

define( 'msBASEDIR', dirname( $_SERVER['PHP_SELF'] ) );
$config_name = isset( $_GET['config'] ) ? $_GET['config'] : '';
str_replace( '/', '', $config_name );
str_replace( '.', '', $config_name );
$paths[] = "config/" . $config_name . ".xml";
$paths[] = 'config/' . $config_name . ".conf";
$paths[] = 'config/config.xml';
$paths[] = 'config/config.conf';
$paths[] = 'viewer.conf';
foreach ( $paths as $path ) {
    if ( file_exists( $path ) ) {
        if ( substr( $path, -4 ) == ".xml" ) {
            $config = parseConfigFile( $path , true);
        }
        else {
           $config = parseConfigFile($path, false);
        }
        break;
    }
}
$cachefile = "./cache/".$config['host'].$config['queryport']."/".$config['vserverport']."/server/images/".$_GET['id'];

if(file_exists($cachefile))
    $img = file_get_contents ($cachefile);
else {

include "./TSQuery.class.php";

    $query = new TSQuery($config['host'], $config['queryport']);
    $query->use_by_port($config['vserverport']);
    
    $img =  $query->download("/icon_".$_GET['id'],0);
    $query->quit();
    $file = fopen($cachefile,"wb");
    fwrite($file, $img);
    fclose($file);
}

header("Content-Type: image/".$config['image_type']);
echo $img;
?>
