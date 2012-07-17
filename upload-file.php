<?php
 /***********************************************************/
/**
 * @name          : Mac Doc Photogallery.
 * @version	      : 2.5
 * @package       : apptha
 * @subpackage    : mac-doc-photogallery
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 2 or later; see LICENSE.txt
 * @abstract      : The core file of calling Mac Photo Gallery.
 * @Creation Date : June 20 2011
 * @Modified Date : September 30 2011
 * */

/*
 ***********************************************************/

/* Upload the photos to the album */

class SimpleImage {
   var $image;
   var $image_type;

   function load($filename) {
      $filename = str_replace("%20"," ",$filename);
     
      $image_info = getimagesize($filename);
       $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
        
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
         chmod($filename,777);
     
      }
   }
   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }
   }
   function getWidth() {
   
  		$width = imagesx($this->image);
  		return $width;
   }
   function getHeight() {
      return imagesy($this->image);
   }
   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
         $this->resize($width,$height);
   }
   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight()* $ratio;
      $this->resize($width,$height);
   }
   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
   function resize($width,$height) {
   	//$width = 144; 	$height = 144;
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }
} // class is end hear

require_once( dirname(__FILE__) . '/macDirectory.php');
global $wpdb;
$albumId = $_REQUEST['albumId'];
$uploadDir = wp_upload_dir();
$path = $uploadDir['basedir'].'/pica-photo-gallery';
$uploaddir = "$path/";
 
function resizeNewThumbniles($width , $height , $k ,$uploaddir){
	
	global  $wpdb;  $image = new SimpleImage();
	
	 $picaPhotosList = $wpdb->get_results(" select macPhoto_id , macPhoto_tname , macPhoto_image  from " . $wpdb->prefix . "picaphotos ORDER BY macPhoto_id DESC  ", ARRAY_A);
	 $twidth = $width;
	 $theight = $height;
switch($k){
	  			
	  			case 1 :    $thumbType = '_featuredtumb';
	  					break;
	  			case 2 : 	 $thumbType = '_albumthumb';
	  					break;
	  			case 3 :	 $thumbType = '_photothumb'; 
	  					break;
	  			case 4 : 	  $thumbType = '_singlethumb';
	  					break;
	  		}
		
	foreach($picaPhotosList as $key => $value ){
	$imgname =	$value['macPhoto_tname'];// get name of imgage
	$imgexten = $value['macPhoto_image'];
	$imgtype = explode('.', $imgexten);   // get type like .jpeg , .png  ...
	  $image_info = $uploaddir.$imgname; // full path of image 
	   $imgid = $value['macPhoto_id'];
	   $thumbfile = $imgid.$thumbType.'.'.$imgtype[1];
	// echo  $filePath = $uploaddir . $thumbfile;
	  $filePath =$uploaddir.$thumbfile;
	  
	$filePath = str_replace(" ","%20",$filePath);
	  
	 $image->load($image_info);  //sending upload img not any thumbs  for get information ab img
	  $imgW = $image->getWidth();
      $imgH = $image->getHeight();
	      	
	 		if($twidth && $theight)
	 		{ 
	 			//echo '<script>alert('.$imgW.');</script>';
	 			if($imgW >= $twidth && $imgH >= $theight)
	 			{
	 				 $image->resize($twidth,$theight);
	 			} 
	  			else if($imgW >= $imgH && $imgW >= $twidth)
		    	{
		    		
		    		$image->resizeToWidth($twidth);
                   
		    	}
		
		       else if($imgH >= $imgW && $imgH >= $theight) {
		       		
		       	     $image->resizeToHeight($theight);
                    
		        }  
	 		}
	 		else if($twidth){
	 				
	 			$image->resizeToWidth($twidth);
	 		}
	 		else if($theight){
	 			
	 			$image->resizeToHeight($theight);
	 		}	
	   
			if (file_exists($filePath)) {
				
		    		unlink( $filePath);  // it delete the prev image in dir
		    	
		    } 
			else{
			
			}
			 $filePath = str_replace("%20"," ",$filePath);
			
			  $image->save($filePath); 
			
			} 
		
	}  // function is end hear
  
  if(isset($_REQUEST['photoThumbGenerate']) )
  {  
  	  $image = new SimpleImage();
  //	print_r($uploadDir);
  $resizetype =  $_REQUEST['photoResize']; 
   $width =  intval($_REQUEST['width']); 
   $height = intval($_REQUEST['height']);

  	 $thumb1 = strrpos($resizetype , "feat" , 0);  //it is for featured images
    $thumb2 = strrpos($resizetype , "alb" , 0);     // it is for album img
 	$thumb3 = strrpos($resizetype , "photo-tumb" , 0);     // it is for photos 
    $thumb4 = strrpos($resizetype , "photo-gene" , 0);    // it is for single photo
   
   	for($k = 1 ; $k< 5 ; $k++ ){
  
   		$thumb = "thumb$k";
   		 
   		if($$thumb)
   		{
   			  	resizeNewThumbniles($width ,$height, $k ,$uploaddir);
   		}
   		
   		
   	}
    echo 'Photo resize success';
  }
   

if($albumId !='')
{

		$file = $uploaddir . basename($_FILES['uploadfile']['name']);
		$size=$_FILES['uploadfile']['size'];
		
		if($size>10485760)
		{
			echo "error file size > 1 MB";
			unlink($_FILES['uploadfile']['tmp_name']);
			exit;
		}
		  $image = new SimpleImage();
		   $image->load($_FILES['uploadfile']['tmp_name']);
		   //print_r($_FILES);

 //if(photoThumbGenerate)
if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file) && $albumId !='0')
{
     $macimage = $_FILES['uploadfile']['name'];
     $macname = explode('.',$macimage);
     $storing_macname = addslashes($macname[0]);
	 
     	
     $uploadDb =  $wpdb->query("INSERT INTO ". $wpdb->prefix."picaphotos (`macAlbum_id`,`macPhoto_tname` , `macPhoto_name`, `macPhoto_desc`, `macPhoto_image`, `macPhoto_status`, `macPhoto_sorting`,`macPhoto_date` )
       VALUES ('$albumId','$macimage','$storing_macname', '', '$macimage', 'ON', '',NOW())");
       $lastid = $wpdb->insert_id;
       $album_image = $wpdb->get_var("select macPhoto_image from " . $wpdb->prefix . "picaphotos WHERE macPhoto_id='$lastid'");
       $filenameext = explode('.',$album_image);
    $filenameextcount = count($filenameext);
       $picasettings = $wpdb->get_results(" select * from " . $wpdb->prefix . "pica_settings_menu ", ARRAY_N);
      // $macSetting = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix."pica_settings_menu" , ARRAY_N);
               
                $bigfile = $lastid . "." . $filenameext[(int) $filenameextcount - 1];
                $path = $uploaddir.$album_image;
                define(contus, "$uploaddir/");
                $macSetting = $wpdb->get_row("SELECT * FROM ". $wpdb->prefix."macsettings" , ARRAY_N);

                $thumbfile = $lastid . "_singlethumb." . $filenameext[(int) $filenameextcount - 1];     // for showing single image with big size
                $image->load($_FILES['uploadfile']['tmp_name']); 
		        $twidth = $picasettings[0][22];
		        $theight = $picasettings[0][23];
				/*$image->resize($twidth,$theight);
                $image->save($uploaddir . $thumbfile);*/
		        $imgW = $image->getWidth();
                $imgH = $image->getHeight();
		    if($imgW >= $twidth || $imgH >= $theight )
		    {

		    	if($imgW >= $imgH && $imgW >= $twidth)
		    	{
		    		 $image->resizeToWidth($twidth);
                     $image->save($uploaddir . $thumbfile);
		    	}
		
		       else if($imgH >= $imgW && $imgH >= $theight) {
		       	     $image->resizeToHeight($theight);
                     $image->save($uploaddir . $thumbfile);
		        }  
		    }
		       else {
		       	     $image->resize($imgW,$imgH);
                     $image->save($uploaddir . $thumbfile);
		            }
                
                 // End of Single Thumb
                
                 $image->load($_FILES['uploadfile']['tmp_name']); 
                 $newthumbfile = $lastid . "_featuredtumb." . $filenameext[(int) $filenameextcount - 1]; //for feature images in front page
	             $twidth = $picasettings[0][3];
	             $theight = $picasettings[0][4];
	             $image->resize($twidth,$theight);
                 $image->save($uploaddir . $newthumbfile);
 
		            
		            // End of Featured
                 
                
				$image->load($_FILES['uploadfile']['tmp_name']);
                $thumbfile = $lastid . "_albumthumb." . $filenameext[(int) $filenameextcount - 1];   //for album img in front page  
		        $twidth = $picasettings[0][9];
                $theight = $picasettings[0][10];
           		$image->resize($twidth,$theight);
                $image->save($uploaddir . $thumbfile);
                
                
		            
		            // End of albumthumb
                
                $image->load($_FILES['uploadfile']['tmp_name']); 
                $thumbfile = $lastid . "_photothumb." . $filenameext[(int) $filenameextcount - 1];     // for showing single image with big size
		        $twidth = $picasettings[0][18];
		        $theight = $picasettings[0][19];
		  		$image->resize($twidth,$theight);
                $image->save($uploaddir . $thumbfile);
                 
		            
		            // End of Photo thumb
              
			$exten = $filenameext[(int) $filenameextcount - 1];
				
			$sortval =  " ( SELECT COUNT(*) FROM (
                         								SELECT  * FROM  " . $wpdb->prefix . "picaphotos WHERE `macAlbum_id` = $albumId 
                       								) 
                       								as x WHERE is_delete = 0 
                       		  )
                       	" ;
			$sqlQ = "UPDATE " . $wpdb->prefix . "picaphotos SET   macPhoto_image='$newthumbfile', `macPhoto_sorting`= ($sortval - 1)  WHERE macPhoto_id=$lastid";
		
				
           $upd = $wpdb->query($sqlQ);
             
}
else
{
        echo "error ".$_FILES['uploadfile']['error']." --- ".$_FILES['uploadfile']['tmp_name']." %%% ".$file."($size)";
}
}
?>