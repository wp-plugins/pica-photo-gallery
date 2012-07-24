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

require_once('../../../wp-load.php');

$dbtoken = md5(DB_NAME);
$token = trim($_REQUEST["token"]);

if($dbtoken != $token ){
    die("You are not authorized to access this file");
}

require_once( dirname(__FILE__) . '/macDirectory.php');
	$maceditId = $_REQUEST['macEdit'];
	$site_url = get_bloginfo('url');
	$uploadDir = wp_upload_dir();
	$path = $uploadDir['basedir'].'/pica-photo-gallery';

	 if($_REQUEST['macdeleteId'] != '')
	 {
	    $macPhoto_id = $_REQUEST['macdeleteId'];
	    $photoImg    = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "picaphotos WHERE macPhoto_id='$macPhoto_id' ");
	                   $wpdb->query("UPDATE  " . $wpdb->prefix . "picaphotos SET is_delete = 1 WHERE macPhoto_id='$macPhoto_id'");
	                   $wpdb->query("UPDATE  " . $wpdb->prefix . "picaphotos SET macPhoto_sorting = macPhoto_sorting - 1 WHERE macPhoto_id > '$macPhoto_id'");
	   // $path = "$path/"; unlink($path . $photoImg); $extense = explode('.', $photoImg); unlink($path . $macPhoto_id . '.' . $extense[1]);
	 }
	  else if(($_REQUEST['macPhoto_desc']) != '')
	 {
	     $macPhoto_desc = strip_tags($_REQUEST['macPhoto_desc']);
	     $macPhoto_id   = $_REQUEST['macPhoto_id'];
	     $sql = $wpdb->query("UPDATE " . $wpdb->prefix . "picaphotos SET `macPhoto_desc` = '$macPhoto_desc' WHERE `macPhoto_id` = '$macPhoto_id'");
	 	 echo $macPhoto_desc;
	 }
	  else if($_REQUEST['macdelAlbum'] != '')
	 {
	        $macAlbum_id = $_REQUEST['macdelAlbum'];
	        $alumImg = $wpdb->get_var("SELECT macAlbum_image FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$macAlbum_id' ");
	        $delete = $wpdb->query("DELETE FROM " . $wpdb->prefix . "macalbum WHERE macAlbum_id='$macAlbum_id'");
	        $path1 = "$path/";
	        unlink($path1.$alumImg);
	        $extense = explode('.', $alumImg);
	        unlink($path1.$macAlbum_id.'alb.'.$extense[1]);
	        //Photos respect to album deleted
	        $photos  =$wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "picaphotos WHERE macAlbum_id='$macAlbum_id' ");
	
	        foreach ($photos as $albPhotos)
	        {
	
	        $macPhoto_id = $albPhotos->macPhoto_id;
	        $photoImg    = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "picaphotos WHERE macPhoto_id='$macPhoto_id' ");
	        $deletePhoto  = $wpdb->get_results("DELETE FROM " . $wpdb->prefix . "picaphotos WHERE macPhoto_id='$macPhoto_id'");
	        $path1 = "$path/";
	            unlink($path1 . $photoImg);
	            $extense = explode('.', $photoImg);
	            unlink($path1 . $macPhoto_id . '.' . $extense[1]);
	        }
	 }
	  else if($_REQUEST['macedit_phtid'] != '')
	 {
	    	$macedit_name = strip_tags($_REQUEST['macedit_name']);
	    	$macedit_name = preg_replace("/[^a-zA-Z0-9\/_-\s]/", '', $macedit_name);
	        $macedit_desc = strip_tags($_REQUEST['macedit_desc']);
	        $macedit_id   = $_REQUEST['macedit_phtid'];
	      
	        $sql = $wpdb->query("UPDATE " . $wpdb->prefix . "picaphotos SET `macPhoto_name` = '$macedit_name', `macPhoto_desc` = '$macedit_desc' WHERE `macPhoto_id` = '$macedit_id'");
	        
	        echo "success";
	 }
?>