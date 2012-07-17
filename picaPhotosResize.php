<?php
 /***********************************************************/
/**
 * @name          : PICA Photo Gallery.
 * @version	      : 1.0
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

        $dbtoken = md5(DB_NAME.DB_PASSWORD);
        $token = trim($_REQUEST["token"]);
       //Checking for admin access
        if(!(int)$albumId && !isset($_REQUEST['photoThumbGenerate'] ) && $dbtoken != $token){
            wp_die( __( 'Direct access not permitted.' ) );
        }

	function resizeNewThumbniles($width , $height , $k ,$uploaddir) //this fun use to resize photos
	{ 
			
			global  $wpdb;  $image = new SimpleImage();
			
			 $picaPhotosList = $wpdb->get_results(" select macPhoto_id , macPhoto_tname , macPhoto_image  from " . $wpdb->prefix . "picaphotos ORDER BY macPhoto_id DESC  ", ARRAY_A);
			 $twidth =  $width; //user given w and h values
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
		    $filePath =$uploaddir.$thumbfile;
			$filePath = str_replace(" ","%20",$filePath);
			  
			 $image->load($image_info);  //sending upload img not any thumbs  for get information ab img
			 $imgW = $image->getWidth();  // source img widht
		     $imgH = $image->getHeight(); // source img height
			      	
			 if($thumbType == '_singlethumb')
			 {		
			 			if($imgW >= $twidth && $imgH >= $theight)
			 			{
			 				 $image->resize($twidth,$theight); //reze to w and h 
			 			} 
			  			else if($imgW > $twidth)
				    	{
				    		
				    		$image->resizeToWidth($twidth);  //resize to widht only
		                   
				    	}
				
				       else if( $imgH > $theight) {
				       		
				       	     $image->resizeToHeight($theight); //resize to height only
		                    
				        } 
			 }
			 else{
			 	
			 		 $image->resize($twidth,$theight); //reze to w and h 
			 }	         
				 		if (file_exists($filePath)) {
							
					    		unlink( $filePath);  // it delete the prev image in dir
					    	
					    } 
						 $filePath = str_replace("%20"," ",$filePath);
					 	 $image->save($filePath);   // saving img in pica-photo-gallery foulder in upload dir
					
					} 
				
	}  // function is end hear
  
  if(isset($_REQUEST['photoThumbGenerate']) )
  {  
	   $image = new SimpleImage();
	   $resizetype =  $_REQUEST['photoResize']; 
	   $width =  intval($_REQUEST['width']); 
	   $height = intval($_REQUEST['height']);
	  	$thumb1 = strrpos($resizetype , "feat" , 0);  //it is for featured images
	    $thumb2 = strrpos($resizetype , "alb" , 0);     // it is for album img
	 	$thumb3 = strrpos($resizetype , "photo-tumb" , 0);     // it is for photos 
	    $thumb4 = strrpos($resizetype , "photo-gene" , 0);    // it is for single photo
	   
	   	for($k = 1 ; $k< 5 ; $k++ ){ //we have 4 types of resize so calling in loop
	  
	   		$thumb = "thumb$k";
	   		 
	   		if($$thumb)
	   		{
	   			  	resizeNewThumbniles($width ,$height, $k ,$uploaddir);  //img going to resize
	   		}
	   	}
	    echo 'Photo resize success';
  }
   

if($albumId !='')  //while upload img that time it user to resize imgs
{

		$file = $uploaddir . basename($_FILES['uploadfile']['name']);
		$size=$_FILES['uploadfile']['size'];

                //Restrict image extension in file upload.
                $typeinfo = explode(".",$_FILES['uploadfile']['name']);
                $type =  strtolower($typeinfo[count($typeinfo)-1]);
                $allowExt  = array("jpg","jpeg","png","gif");
                if(!in_array($type,$allowExt)){
                    echo "Not an allowed extension";
                    unlink($_FILES['uploadfile']['tmp_name']);
                    exit;
                }

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
		      $album_image = $macimage = $_FILES['uploadfile']['name'];
		      $macname = explode('.',$macimage);
		      $storing_macname = addslashes($macname[0]);
	 	      $uploadDb =  $wpdb->query("INSERT INTO ". $wpdb->prefix."picaphotos (`macAlbum_id`,`macPhoto_tname` , `macPhoto_name`, `macPhoto_desc`, `macPhoto_image`, `macPhoto_status`, `macPhoto_sorting`,`macPhoto_date` )
		      							 VALUES ('$albumId','$macimage','$storing_macname', '', '$macimage', 'ON', '',NOW())");
		       $lastid = $wpdb->insert_id;
		       $album_image =  $macimage; //$wpdb->get_var("select macPhoto_image from " . $wpdb->prefix . "picaphotos WHERE macPhoto_id='$lastid'");
		       if ( strlen($album_image) < 50 ) //if img name is low then we take extension of img like .jpg , .png
		       {
		       	
		       	$filenameext = explode('.',$album_image);	
		       }
		       else{  // if img name in big then its sloution to take extension of img
		       		$kk = strrev($album_image);
		       		$filenameext = $album_image_ext = explode('.',$album_image);
		     		$filenameext[(int) $filenameextcount - 1] = strrev($album_image_ext[0]);
		       	
		       }
        	   $filenameextcount = count($filenameext);
      		   $picasettings = $wpdb->get_results(" select * from " . $wpdb->prefix . "pica_settings_menu ", ARRAY_N);
                
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
                // resize  of single view photo  
	             if($imgW > $twidth  && $imgH >= $theight) //if given img x&y are < original img then do resize(w,h) 
	             {
	             	 $image->resize($twidth ,$theight  );
	                     
	             	
	             }   
	             else if($imgW > $twidth ) //if only given Widht is < original widht then only 
	             {
	             	$image->resizeToWidth($twidth);
	             }
	             else if( $imgH > $theight  ) // if only given height is < original then only
	             {
	             	  $image->resizeToHeight($theight);
	             	
	             }
	             $image->save($uploaddir . $thumbfile); // after resize img store in pica-photo-gallry foulder in uploads
                
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
                       								as x WHERE is_delete = 0 AND macPhoto_status = 'ON'
                       		  )
                       	" ;
			$sqlQ = "UPDATE " . $wpdb->prefix . "picaphotos SET   macPhoto_image='$newthumbfile', `macPhoto_sorting`= ($sortval - 1)  WHERE macPhoto_id=$lastid";
		    $wpdb->query($sqlQ);
             
	}
	else
	{
	        echo "error ".$_FILES['uploadfile']['error']." --- ".$_FILES['uploadfile']['tmp_name']." %%% ".$file."($size)";
	}
} //if ended hear
?>