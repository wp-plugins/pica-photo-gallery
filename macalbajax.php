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
?>
<?php
require_once('../../../wp-load.php');

$dbtoken = md5(DB_NAME);
$token = trim($_REQUEST["token"]);

if($dbtoken != $token ){
    die("You are not authorized to access this file");
}
?>
<?php require_once( dirname(__FILE__) . '/macDirectory.php');
$site_url = get_bloginfo('url');
$folder   = dirname(plugin_basename(__FILE__));

// Album Status Change
if($_REQUEST['albid'] != '')  //for change status of albums in albums tab.
{
	$mac_albId   = $_REQUEST['albid'];
	$mac_albStat = $_REQUEST['status'];
	if($_REQUEST['status'] == 'ON')
	{
		$alumImg = $wpdb->query("UPDATE " . $wpdb->prefix . "picaalbum SET macAlbum_status='ON' WHERE macAlbum_id='$mac_albId'");
		echo "<img src='$site_url/wp-content/plugins/$folder/images/tick.png' style='cursor:pointer' width='16' height='16' onclick=macAlbum_status('OFF',$mac_albId)  />";
	}
	else
	{
		$alumImg = $wpdb->query("UPDATE " . $wpdb->prefix . "picaalbum SET macAlbum_status='OFF' WHERE macAlbum_id='$mac_albId'");
		echo "<img src='$site_url/wp-content/plugins/$folder/uploads/pica_deactive.png' style='cursor:pointer' width='16' height='16' onclick=macAlbum_status('ON',$mac_albId)  />";
	}

}
// Photos status change respect to album
else if($_REQUEST['macPhoto_id'] != '')
{
	$macPhoto_id   = $_REQUEST['macPhoto_id'];
	$mac_photoStat = $_REQUEST['status'];
	$albId 		   = $_REQUEST['albIdIs'];
	$sortingId     = $_REQUEST['sortingValue'];
	$picaPhotoTable = $wpdb->prefix . "picaphotos";
	if($_REQUEST['status'] == 'ON')
	{
		$photoImg = $wpdb->query("UPDATE $picaPhotoTable SET macPhoto_status='ON' , macPhoto_sorting = macPhoto_sorting + 1 WHERE macPhoto_id='$macPhoto_id'");
		 $sql = "UPDATE $picaPhotoTable SET macPhoto_sorting = macPhoto_sorting + 1 
		                WHERE  macPhoto_sorting > $sortingId AND macPhoto_id != $macPhoto_id AND is_delete = 0";
		
		$wpdb->query($sql);
		echo "<img src='$site_url/wp-content/plugins/$folder/images/tick.png' style='cursor:pointer' width='16' height='16' onclick=macPhoto_status('OFF',$macPhoto_id)  />";
	}
	else   // if u click on status off then it exe
	{
		$photoImg = $wpdb->query("UPDATE $picaPhotoTable SET macPhoto_status='OFF' WHERE macPhoto_id='$macPhoto_id'");

		 $sql = "UPDATE $picaPhotoTable SET macPhoto_sorting = macPhoto_sorting - 1 
		                WHERE  macPhoto_sorting >= $sortingId AND is_delete = 0";
		
		$wpdb->query($sql);
		
		// $wpdb->query("UPDATE " . $wpdb->prefix . "picaphotos SET  macPhoto_sorting  = macPhoto_sorting - 1 WHERE macPhoto_sorting IS NOT NULL AND `macAlbum_id` = 1 AND `macPhoto_id` >$macPhoto_id AND is_delete = 0");
		                
		echo "<img src='$site_url/wp-content/plugins/$folder/uploads/pica_deactive.png' style='cursor:pointer' width='16' height='16'  onclick=macPhoto_status('ON',$macPhoto_id) />";
		
	}

}
else if($_REQUEST['macDelid'] != '')
{
	$macPhoto_id = $_REQUEST['macDelid'];
	$photoImg    = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "picaphotos WHERE macPhoto_id='$macPhoto_id' ");
	$uploadDir = wp_upload_dir();
	$path = $uploadDir['baseurl'];
	$path = "$path/";
	unlink($path . $photoImg);
	$extense = explode('.', $photoImg);
	unlink($path . $macPhoto_id . '.' .$extense[1]);
	$deletePhoto = $wpdb->get_results("DELETE FROM " . $wpdb->prefix . "picaphotos WHERE macPhoto_id='$macPhoto_id'");
	echo '';

}
//   For photo edit form
else if($_REQUEST['macPhotoname_id'] != '')
{
	$macPhoto_id = $_REQUEST['macPhotoname_id'];
	$div = '<form name="macPhotoform" method="POST"><td style="margin:0 10px;border:none"><input type="text" name="macPhoto_name_'.$macPhoto_id.'" id="macPhoto_name_'.$macPhoto_id.'" ></td>';
	$div .= '<td colspan="2" style="padding-top:10px;text-align:center;border:none"><input type="submit" style = "cursor:pointer;"name="updatePhoto_name" value="Update" onclick="updPhotoname('.$macPhoto_id.')";></td></form/>' ;
	echo $div;
}

// Add as album cover from the photos
else if ($_REQUEST['macCovered_id'] != '')
{
	$macPhotoid  = $_REQUEST['macCovered_id'];
	$albumCover  = $_REQUEST['albumCover'];
	$albumId     = $_REQUEST['albumId'];
	$flag     = $_REQUEST['featuredCover'];
	$isflage = 	$_REQUEST['isFlag'];
	$changeAlbCover  = $_REQUEST['changeAlbCover'];

	if(isset(  $changeAlbCover) )  //in photos table set the album cover option
	{

		$albumCoveroff = $wpdb->query("UPDATE " . $wpdb->prefix . "picaphotos SET macAlbum_cover='OFF' WHERE macPhoto_id !='$macPhotoid' and macAlbum_id='$albumId'");
		$albumCover    = $wpdb->query("UPDATE " . $wpdb->prefix . "picaphotos SET macAlbum_cover='ON' WHERE macPhoto_id='$macPhotoid' and macAlbum_id='$albumId'");
		$photoImg      = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "picaphotos WHERE macPhoto_id='$macPhotoid' ");
		$addtoAlbum    = $wpdb->query("UPDATE " . $wpdb->prefix . "picaalbum SET macAlbum_image='$photoImg' WHERE macAlbum_id='$albumId'");

		if($albumCover == 'ON')
		{
			echo "<img src='$site_url/wp-content/plugins/$folder/images/active.png' style='cursor:pointer' width='16' height='16' onclick=macAlbcover_status('OFF',$albumId,$macPhotoid) />";

		}
		else{
			echo "<img src='$site_url/wp-content/plugins/$folder/images/deactive.png' style='cursor:pointer' width='16' height='16' onclick=macAlbcover_status('ON',$albumId,$macPhotoid) />";

		}
			
	}

	else if(isset($flag)){     // FOR FEATURED IMAGE

		$wpdb->query("UPDATE " . $wpdb->prefix . "picaphotos SET macFeaturedCover = $flag  WHERE macPhoto_id='$macPhotoid' and macAlbum_id='$albumId'");
		if($flag == 0)
		{
		 echo "<img src='$site_url/wp-content/plugins/$folder/uploads/pica_deactive.png' style='cursor:pointer' width='16' height='16'
		  onclick= macFeatured_status('1',$albumId,$macPhotoid,1) />";

		}
		else{
		 echo "<img src='$site_url/wp-content/plugins/$folder/images/tick.png' style='cursor:pointer' width='16' height='16'
		  onclick= macFeatured_status('0',$albumId,$macPhotoid,1)  />";


		}


	}
	else if($albumCover == 'ON')
	{
		$albumCover    = $wpdb->query("UPDATE " . $wpdb->prefix . "picaphotos SET macAlbum_cover='ON' WHERE macPhoto_id='$macPhotoid' and macAlbum_id='$albumId'");
		$albumCoveroff = $wpdb->query("UPDATE " . $wpdb->prefix . "picaphotos SET macAlbum_cover='OFF' WHERE macPhoto_id !='$macPhotoid' and macAlbum_id='$albumId'");
		$photoImg      = $wpdb->get_var("SELECT macPhoto_image FROM " . $wpdb->prefix . "picaphotos WHERE macPhoto_id='$macPhotoid' ");
		$addtoAlbum    = $wpdb->query("UPDATE " . $wpdb->prefix . "picaalbum SET macAlbum_image='$photoImg' WHERE macAlbum_id='$albumId'");
		echo "<img src='$site_url/wp-content/plugins/$folder/images/active.png' style='cursor:pointer' width='16' height='16'  />";
	}


}

// update photo name
else if($_REQUEST['macPhoto_name'] != '')
{
	$macPhoto_id =$_REQUEST['macPhotos_id'];
	$macPhoto_name =  addslashes($_REQUEST['macPhoto_name']);
	$sql = $wpdb->get_results("UPDATE " . $wpdb->prefix . "picaphotos SET `macPhoto_name` = '$macPhoto_name' WHERE `macPhoto_id` = $macPhoto_id");
	echo $macPhoto_name;
}

//Album name edit form
else if($_REQUEST['macAlbumname_id'] != '')
{
	$macAlbum_id = $_REQUEST['macAlbumname_id'];
	$fet_res = $wpdb->get_row("SELECT * FROM  " . $wpdb->prefix . "picaalbum WHERE macAlbum_id='$macAlbum_id'");
	$div = '<form name="macUptform" method="POST">
    <div style="margin:0;padding:0;border:none"><input type="text"
           name="macedit_name_'.$macAlbum_id.'" id="macedit_name_'.$macAlbum_id.'" size="15" value="'.$fet_res->macAlbum_name.'" ></div>';

	$div .= '<div><textarea name="macAlbum_desc_'.$macAlbum_id.'"  id="macAlbum_desc_'.$macAlbum_id.'" rows="6" cols="27" >'.$fet_res->macAlbum_description.'</textarea></div>';
	$div .='<input type="button"  name="updateMac_name" value="Update" onclick="updAlbname('.$macAlbum_id.')" class="button-secondary action";>
             <input type="button" onclick="CancelAlbum('.$macAlbum_id.')"   value="Cancel" class="button-secondary action">
            </div>';
	$div .= '</form/>' ;
	echo $div;
}

else if($_REQUEST['macGallery_id'] != '')
{
	$macGallery_id = $_REQUEST['macGallery_id'];
	$fet_res = $wpdb->get_row("SELECT * FROM  " . $wpdb->prefix . "picagallery WHERE macGallery_id='$_id'");
	$div = '<form name="macGalform" method="POST">
    <div style="margin:0;padding:0;border:none"><input type="text"
           name="macgaledit_name_'.$macGallery_id.'" id="macgaledit_name_'.$macGallery_id.'" size="15" value="'.$fet_res->macGallery_name.'" ></div>';
	$div .='<input type="button"  name="updateGal_name" value="Update" onclick="return updGalname('.$macGallery_id.')" class="button-secondary action";>
             <input type="button" onclick="CancelGalllery('.$macGallery_id.')"   value="Cancel" class="button-secondary action">
            </div>';
	$div .= '</form/>' ;
	echo $div;

	$macGal_name =  addslashes($_REQUEST['macGallery_name']) ;
	$macGallery_id   = $_REQUEST ['macGallery_id'];
	$sql = $wpdb->query("UPDATE " . $wpdb->prefix . "picagallery SET `macGallery_name` = '" .$macGal_name. "' WHERE `macGallery_id` = ".$macGallery_id);
	echo $macGal_name;
}
//  Album description update
else if($_REQUEST['macGallery_id'] != '')
{
	$macGal_name =  addslashes($_REQUEST['macGallery_name']) ;

	$macGallery_id   = $_REQUEST['macGallery_id'];
	$sql = $wpdb->query("UPDATE " . $wpdb->prefix . "picagallery SET `macGallery_name` = '$macGal_name' WHERE `macGallery_id` = '$macGallery_id'");
	echo $macGal_name;
}


else if($_REQUEST['macAlbum_id'] != '' )
{
	$macAlbum_id =   $_GET['macAlbum_id'];
	$macAlbum_name = $_GET['macAlbum_name'];
	$macAlbum_desc = $_GET['macAlbum_desc'];
	$sql = $wpdb->get_results("UPDATE " . $wpdb->prefix . "picaalbum SET `macAlbum_name`='$macAlbum_name',`macAlbum_description` ='$macAlbum_desc'
    							 WHERE `macAlbum_id` = '$macAlbum_id'");

}

//  Album description update
else
{
	$macAlbum_desc =  addslashes($_REQUEST['macAlbum_desc']) ;
	$macAlbum_id   = $_REQUEST['macAlbum_id'];
	$sql = $wpdb->query("UPDATE " . $wpdb->prefix . "picaalbum SET `macAlbum_description` = '$macAlbum_desc' WHERE `macAlbum_id` = '$macAlbum_id'");
	echo $macAlbum_desc;
}

?>