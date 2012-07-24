<?php
/*
 ***********************************************************/
/**
 * @name          : PICA Photo Gallery.
 * @version	      : 1.3
 * @package       : apptha
 * @subpackage    : PICA Photo Gallery.
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 1 or later; see LICENSE.txt
 * @abstract      : The core file of calling picaPluginRoot.
 * @Creation Date : November 20 2011
 * @Modified Date : July 17 2012
 * */

/*
 ***********************************************************/
require_once( dirname(__FILE__) . '/macDirectory.php');

$timg = $imgname =  $_REQUEST['imgname'];
$pluginName =  'pica-photo-gallery'; //$_REQUEST['pluginname'];
$file = dirname(dirname(dirname(__FILE__)))."/uploads/".$pluginName."/".$timg;
$allowedExtensions = array("jpg", "jpeg", "png", "gif");
$folder = dirname(plugin_basename(__FILE__));
$filepart = explode(".",$timg);
$fileExt = '';
$allowedExtensions = array("jpg", "jpeg", "png", "gif");
 if(preg_grep( "/$filepart[1]/i" , $allowedExtensions )){
 	$fileExt = true;
 }
if(file_exists($file) && (count($filepart) === 2) && $fileExt == '1'){
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