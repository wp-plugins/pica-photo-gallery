<?php
require_once( dirname(__FILE__) . '/macDirectory.php');

$timg = $imgname =  $_REQUEST['imgname'];
$pluginName =  'pica-photo-gallery'; //$_REQUEST['pluginname'];
$file = dirname(dirname(dirname(__FILE__)))."/uploads/".$pluginName."/".$timg;
//Restrict direct file access with wildcard charectors ([.],[..],[././],etc.,)
$folder = dirname(plugin_basename(__FILE__));
$filepart = explode(".",$timg);

if(file_exists($file) && (count($filepart) === 2)  ){

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
}
else{
    die("No direct access");
}
?>