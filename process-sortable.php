<?php
 /***********************************************************/
/**
 * @name          : PICA Photo Gallery.
 * @version	      : 1.2
 * @package       : apptha
 * @subpackage    : PICA Photo Gallery.
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 1 or later; see LICENSE.txt
 * @abstract      : The core file of calling Mac Photo Gallery.
 * @Creation Date : November 20 2011
 * @Modified Date : 
 * */


/*
 ***********************************************************/

require_once( dirname(__FILE__) . '/macDirectory.php'); 

 //            this coding for sorting photos when u drag the sort image in photo table which is beside of checkboxs
	$macPhoto_id = $_REQUEST['macPhoto_id'];
	if(isset($_GET['albid']))
	{
			
	}
	else {
 		$picaPhotoTable = $wpdb->prefix . "picaphotos";
		$totalCount =  count($_GET['listItem']); // giveing desc order of photos
			$totalCount--;
			foreach ($_GET['listItem'] as $position => $item) :
			$nextRid = $preRid = $totalCount;
			$nextRid++;
			--$preRid;
$sql[] =$wpdb->query("UPDATE $picaPhotoTable  
				
	 				 SET `macPhoto_sorting` = 
	 				 (
	 				 	CASE  WHEN ( macPhoto_status = 'ON' )THEN ($totalCount) 
	 				 	ELSE  ( macPhoto_sorting )
	 				 	END 
	 				 )
	 				  WHERE `macPhoto_id` = $item");
				--$totalCount;
				 //macPhoto_sorting  SELECT macPhoto_sorting FROM  $picaPhotoTable WHERE  `macPhoto_id` = 
				
			endforeach;
			
	}	
		
?>
