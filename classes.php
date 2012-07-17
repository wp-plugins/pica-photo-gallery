<?php
/*
 * ********************************************************* */
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
 * ********************************************************* */
class pica_contusMacgallery {
	
		
			
			function add_image_src(){
 
					  global $post;
					    // get the global variable post
					  // if it exists and is only a single post
					  if ($post){
					 
					    // pattern for recognizing first image in post
					    $pattern = '!<img.*?src="(.*?)"!';
					    preg_match_all($pattern, $post->post_content, $matches);
					 
					    //first image would be the representative image
					    $image_src = 'http://iseofirm.net/ptest/wordpress/env2/wp-content/uploads/pica-photo-gallery/66_singlethumb.jpg';
					    // extract it as Facebook wants it
						   
					    echo '<head><link rel="image_src" href="', $image_src,'" /></head>';
					  }					  
					// 	hooking to the head generation loop my function defined above
					//add_action('wp_head', 'add_image_src');
			}
 
	
    function picaEffectgallery($arguments= array(), $wid) {
         
  			global $wpdb;  	
    		$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
				if ($_SERVER["SERVER_PORT"] != "80")
				{
				    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
				} 
				else 
				{
				    $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
				}
		
		if(isset($_REQUEST['fb_comment_id']))
		{
		$reload = 	explode('&fb_comment_id',$pageURL);
		echo '<script type="text/javascript">	window.location = "'.$reload[0].'" </script>';
		
		}		
		 $pluginFoulderName   = dirname(plugin_basename(__FILE__));
       
        $siteUrl =  $site_url = get_bloginfo('url');
        $pathToPluginDir = $siteUrl.'/wp-content/plugins/'.$pluginFoulderName;	 
        $folder   = dirname(plugin_basename(__FILE__));
        $imgfoulder = $site_url.'/wp-content/plugins/'.$folder.'/images/icon.png';
        $uploadDir = wp_upload_dir();
        $photosUploadedDir = $path = $uploadDir['baseurl'] . '/pica-photo-gallery'; //photos were uploaded in this DIR
	
       
         
        $settingsTableCols =  "`pica-feat-cols`,`pica-feat-rows`,`pica-feat-photo-width`,`pica-feat-photo-height`,`pica-feat-vspace`,`pica-feat-hspace`,`pica-alb-cols`,`pica-alb-rows`,`pica-alb-photo-width`,`pica-alb-photo-Height`,`pica-alb-vspace`,`pica-alb-hspace`,`pica-general-share-pho`,`pica-general-fac-com`,`pica-general-download`,`pica-general-show-alb`,`pica_facebook_api`,`pica-photo-tumb-w`,`pica-photo-tumb-h`,`pica-photo-vspace`,`pica-photo-hspace`,`pica-photo-gene-w`,`pica-photo-gene-h`";
        
        $picaSetting = $wpdb->get_row("SELECT $settingsTableCols  FROM " . $wpdb->prefix . "pica_settings_menu", ARRAY_A); // Get Values from pica_settings table  which is set at backend setting tab. 
      
     
        $table1 = $wpdb->prefix ."picaphotos";
        $table2 = $wpdb->prefix ."picaalbum" ;
        $tempurl = $pageURL;
        
		$tempurl  = explode('?',$tempurl);
		$tempurl  = explode('=',$tempurl[1]);
		
		// ********************************** SHOW SINGLE PHOTO FROM ALBUM  ***************************************************************

		if( isset( $_REQUEST['pid'] ) || isset($_REQUEST['pld'])  ){
		
	    $rightArrow =  dirname($uploadDir['baseurl']).'/plugins/'.$folder.'/pica_Carousel with autoscrolling_files/right-arrow.png';
        $leftArrow =  dirname($uploadDir['baseurl']).'/plugins/'.$folder.'/pica_Carousel with autoscrolling_files/left-arrow.png';
		 
	
		        $photoSno = $PhotoSortingId = $photoId = $_REQUEST['pid']; 
		         $psno = $limit = $photoId = $_REQUEST['pid'];
		       $albId = $_REQUEST['aid'];
		        
		        $photosno = $_REQUEST['photonumberis'];
		
		
		$goback  = $pageURL;
 		$goback  = explode('&pid', $goback);
 		$limit--;
 	   $psql = "SELECT   macPhoto_id, macPhoto_name , macPhoto_tname ,macPhoto_image , macPhoto_desc  FROM " . $wpdb->prefix . "picaphotos  WHERE  is_delete =0 and macPhoto_status = 'ON'
	                   AND  macAlbum_id = $albId AND  macPhoto_sorting = $limit LIMIT  1";
	
	
	 	   $picaAlbumPhotosList = $wpdb->get_results($psql); // Getting album photos
	       $numOffetched = count($picaAlbumPhotosList);
	       
	    if(!$numOffetched) // if in backed img are not sorted then we get through photo id value
	    {  
	    	  
	    	$psql = "SELECT   macPhoto_id, macPhoto_name , macPhoto_tname ,macPhoto_image , macPhoto_desc  FROM " . $wpdb->prefix . "picaphotos  WHERE  is_delete =0 and macPhoto_status = 'ON'
	                   AND  macAlbum_id = $albId  LIMIT  $limit , 1";
	    	$picaAlbumPhotosList = $wpdb->get_results($psql);
 		  $numOffetched = count($picaAlbumPhotosList);
	   
	    }  
 		 								// echo '<pre>'; print_r($picaAlbumPhotosList);	  exit;
	      
	if($numOffetched){     
		
		  $picaAlbName = $wpdb->get_results(  "SELECT count(*)  as total  , macAlbum_name FROM $table1 as p  , $table2 as a  WHERE p.`macAlbum_id` = $albId and a.`macAlbum_id` = $albId  AND p.is_delete = 0"); 
	   
		//echo '<pre>'; print_r($picaAlbName);	  exit;
	      $photoflag = 0;
	        $total = $picaAlbName[0]->total;
	
		        
	       $albname  = $picaAlbName[0]->macAlbum_name ; 
	      
	        $url = $goback[0].'&pid=';
	  		if($photoId <= 1 )	
	  		{
	  			$leftArrowId = 0;
	  		}
	  		else{
	  			$leftArrowId = $photoId - 1;
	  		}	
	  		if($total <= $photoId )
	  		{
	  			$rightArrowId = 0;
	  		}
	  		else{
	  			$rightArrowId = $photoId + 1;
	  		}
	  		
	  		 $imgId  =  $picaAlbumPhotosList[0]->macPhoto_id ;
             $photoName =  $picaAlbumPhotosList[0]->macPhoto_name;
              
             $ldescr =  $picaAlbumPhotosList[0]->macPhoto_desc ;
             $originalPhotoName = $picaAlbumPhotosList[0]->macPhoto_image;
			 $extension = explode('.', $originalPhotoName);
	      	 $originalPhotoName = $imgId.'_singlethumb.'.$extension[1];
		
			  $descr = $photoName;
			  $subname = substr($photoName,0,25);
			  $phtid = $imgId ;
			$breadcreams = 'style = "text-decoration: none;"';	
		 echo  "<div class='pica-showsinglebread'>
		  				<a href =$goback[0] >".  $albname .'</a>'." <span style='color: #000;'> > </span> <span class = 'pica-showbreadalbname' style='color: #3964C2;font-size:18px'> $subname </span> <span style='color: #000;'> > </span><span style='color: #000;'> Photo $photoSno of $total </span> ";
		  echo "</div>";
		
		  $photourl = $site_url.'/wp-content/uploads/pica-photo-gallery/'.$picaAlbumPhotosList[0]->macPhoto_tname;
		  $imgName = $site_url.'/wp-content/plugins/'.$folder.'/picadownload.php?imgname='.$picaAlbumPhotosList[0]->macPhoto_tname;
		  $facebookimg =  $site_url.'/wp-content/plugins/'.$folder.'/images/facebookshare4.jpg';
		  $downloadimg =  $site_url.'/wp-content/plugins/'.$folder.'/images/picaDownload.png';
		  $zoomimg =  $site_url.'/wp-content/plugins/'.$folder.'/images/picazoom.png';
  		  $macapi = trim($picaSetting['pica_facebook_api']); //kranthi FB APIid '135895656513411';
		  $downloadImg = $imgName = $pathToPluginDir.'/picadownload.php?imgname='.$picaAlbumPhotosList[0]->macPhoto_tname;
				
		 $img_render = $path.'/'.$originalPhotoName;
	
		$fbUrl = 'http://www.facebook.com/dialog/feed?app_id='.$macapi.'&description='.$ldescr.'&picture='.$img_render.'&link='.urlencode($pageURL).'&name='.$descr.'&message=Comments&redirect_uri='.urlencode($pageURL);
     	$photonameis = $path.'/'.$originalPhotoName;
     	
     	$this->add_image_src();
		
?> 		
 <div class="get-pica-border clearfix " style="line-height:0.8em;" >
     <?php  
    	  if($picaSetting['pica-general-fac-com']) { // for show Full screen
        $div .='<div style="float:left;padding-right:5px;padding-bottom:2px;">
	    		<div class="picafullscreecss">';
        $div .="<a  href='$photourl' id='go'>"; 
		$div .='<img src="'.$siteUrl.'/wp-content/plugins/'.$pluginFoulderName.'/images/fullscreen.png">';
		$div .="<span>Full Screen</a></span>
		        </div></div>
		        <div class='separator'>&nbsp;</div>";
		echo $div ;
    	}
        if($picaSetting['pica-general-download']) { // for show download

        $div ='<div style="float:left;padding-right:5px">
       			<div class="picafullscreecss">';
		$div .='<a  href = "'.$downloadImg.'"><img src="'.$site_url.'/wp-content/plugins/'.$pluginFoulderName.'/images/download.png">';
		$div .='<span>Download </a></span></div></div><div class="separator">&nbsp;</div>';
		echo $div;
          	
     	 } 
	 
	 if($picaSetting['pica-general-share-pho']) { // for Allow facebook Share 
	 
	    $div ='<div style="float:left;padding-right:5px;"><div class="picafullscreecss">';
		$div .='<a  class="links" title="Facebook Share" href="'.$fbUrl.'" target="_blank" >
		        <img src="'.$siteUrl.'/wp-content/plugins/'.$pluginFoulderName.'/images/share.png">';
		$div .='<span> Share</a><span></div></div><div class="separator">&nbsp;</div>';
		
	     echo $div;
	  } ?>	
	</div>
	    <div id="iviewer">
	        <div class="loader"></div>
	
	        <div class="viewer"></div>
	        <ul class="controls">
	            <li style="list-style: none;" class="close"></li>
	            <li style="list-style: none;" class="zoomin"></li>
	            <li style="list-style: none;" class="zoomout"></li>
	        </ul>
	    </div>
	
		<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/zoominout/js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/zoominout/js/jqueryui.js"></script>
		<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/zoominout/js/jquery.iviewer.js"></script>
		<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/zoominout/js/main.js"></script>
		
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $site_url; ?>/wp-content/plugins/pica-photo-gallery/zoominout/zoomstyle.css" /> 
<link rel="stylesheet" type="text/css" media="all" href="<?php echo $site_url; ?>/wp-content/plugins/pica-photo-gallery/zoominout/colorbox1.css" />
 
 
<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script> 

	
<table class="picaphotoTable" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:1%; width: 40% !important;background:none;" >
			<tbody>
			<tr  >
				<td  style="padding: 0px;margin: 0px;cursor:pointer;border-top:none;background:none; " class="picaphotoArrow" >
					<?php	if($leftArrowId){  // show left arrow ?>  	
					<img src="<?php echo $leftArrow; ?>" onclick="showsliding('<?php echo $url ; ?>' , '<?php echo $photoSno; ?>','<?php echo $leftArrowId; ?>' , 'left' ); " /> 
					<?php   }  ?>
				
				</td>
				
				 <td   style="padding: 0px 0px 0px 5px;margin: 0px;border-top:none;background:none;text-align: center;"  >
				  <div  style="width:<?php echo $picaSetting['pica-photo-gene-w'].'px'; ?>;height: <?php  echo $picaSetting['pica-photo-gene-h'].'px'; ?>  "> 
						     <img  style="text-align: center ;padding: 0px;margin: 0px;-moz-box-shadow: 2px 2px 8px #888;-webkit-box-shadow: 2px 2px 8px #888;"  src="<?php echo  $photosUploadedDir.'/'.$originalPhotoName ; ?>" alt="<?php echo $photoName; ?>"  />
				  </div>
				</td>
				<td style="padding: 0px 0px 0px 5px;margin: 0px;cursor:pointer;border-top:none;background:none;" class="picaphotoArrow"  >
					<?php 	if($rightArrowId){ // show right arrow  ?>      
					 	<img src="<?php echo $rightArrow; ?>" onclick="showsliding('<?php echo $url ; ?>' , '<?php echo $photoSno; ?>','<?php echo $rightArrowId ?>','right' )"  /> 
					<?php 	} //if for photosno right arrow ?>	
				
				</td>
			</tr>
			</tbody>
			</table>

		<div class = "photoDescription" ><?php echo $ldescr; ?>  </div>
	

<?php   // show facebook comments

 	 
		  if( $picaSetting['pica-general-fac-com'])
		  {
		  	   //echo '<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>';
		  	  // echo '<div id="fb-root"></div>';
			  // echo ' <fb:comments  numposts="10" width="500" xid="photo'.'.'.$phtid.'"
              //         href="'.$pageURL.'" title="'.$photoName.'" > 
              //        </fb:comments>';
		  }  //media = "{src = "'.$photonameis.'"}"

		}//if($imgId) end hear
else{
		
		echo "<script> gotoalbpage('$goback[0]'); </script>" ;
	}	

 } // if is end hear
 
		 // ********************************** SHOW PHOTOS IN SELECTED ALBUM  ***************************************************************
		 
 	else if(isset( $_REQUEST['aid'] )){  
			
?>			


		
		<form name="submitalbum" action="" method="post" >
		  <input type="hidden" value="" id="photonumberis" name="photonumberis" /> 
		</form>
<?php 
 
			$albId =  $_REQUEST['aid'];
			$picaAlbumName = $wpdb->get_var("SELECT macAlbum_name  FROM  $table2 WHERE macAlbum_id	 =".$albId. " "  ); // Getting album name	
			$sql = "SELECT macPhoto_id , macPhoto_image ,  macPhoto_name  FROM $table1  WHERE macAlbum_id =".$albId. " AND macPhoto_status='ON' AND is_delete =0 ORDER BY macPhoto_sorting ASC";
			$picaAlbumPhotosList = $wpdb->get_results($sql); // Getting album photos 
	       		
			//echo "<pre>";       		print_r($picaAlbumName);       		echo "<pre>";	exit;
	       	$goback = get_page_link();
	       	$breadcreams = 'style = "text-decoration: none;"';	
		    echo     "<a $breadcreams href =$goback >Albums</a> >
		    			<span class='albphoto-context-current' >$picaAlbumName</span><br/> " ;
						 $numberofphotos =  count($picaAlbumPhotosList);
			
		$pv = $picaSetting['pica-photo-vspace'].'px';
		$ph = $picaSetting['pica-photo-hspace'].'px';
		$PhotoThumbH = $picaSetting['pica-photo-tumb-h'].'px';
	    $style = "style = 'margin:$pv $ph;height:$PhotoThumbH' ";
	    
	    //$style = "style = 'margin:$pv $ph' ";
	    $pv = $picaSetting['pica-photo-tumb-w'].'px';
	  // $titleStyle = "style = 'width:$pv;' ";
           $titleStyle = "style = 'width:98%;' ";
	      echo "<div id='albumpagelist' style = 'margin-bottom:20px;'>";	
	      					 
			 for($i = 0 ; $i < $numberofphotos ; $i++){    // for showing  photos in album page
				      	
						      		
				    	  $macPhoto_name = $picaAlbumPhotosList[$i]->macPhoto_image; 
				    	  $macPhoto_id = $picaAlbumPhotosList[$i]->macPhoto_id;
				    	  
				    	  $macPhoto_name = explode('.', $macPhoto_name);
				    	  //print_r($macPhoto_name);
				   		  //$macPhoto_name[1] = explode('.', $macPhoto_name[1]);
				   		  
				   		  
				   		  $titledata = substr($picaAlbumPhotosList[$i]-> macPhoto_name , 0 , 100);
				   		  $jsmouseover = 'onmouseover = "showPhotoTitleStyle('.$i.')"';
				   		  $jsmouseout =  'onmouseout =  "dontshowPhotoTitleStyle('.$i.')"';
				   		  //$jsmouseout $jsmouseover
				   		 $img = $path.'/'.$macPhoto_id."_photothumb.".$macPhoto_name[1];  //_photothumb
							$wid = ($picaSetting['pica-photo-tumb-w']).'px';	   		 
							$stylewidth = "style='width:$wid' "	;	   		
							echo  "<div $style class='showAlbPhotos '  >";
							echo "<a  onclick=showsinglephotopage('".$pageURL."','".$macPhoto_id."','".($i+1)."')  > 
					<img  $jsmouseout $jsmouseover  src ='".$img."' /> </a>
							<div id='albumname$i' $titleStyle class = 'picaPhotoTitle'  >$titledata</div> 
				</div>"; // $titledata	
			}//for loop end
			
			echo "</div>";			
			
		}//main if end
			
		 // ********************************** SHOW FEATURED PHOTOS AND ALBUMS IN FIRST PAGE  ***************************************************************
		 	
		else{   // for shoing featured photos and albums
		       
			
			$fc = intval($picaSetting['pica-feat-cols']);
		         $fr = intval( $picaSetting['pica-feat-rows']);
		         $num_of_photos = $fc * $fr;
		     
	     $sql = "select macPhoto_id  FROM $table1 as p
					LEFT JOIN
					$table2 as a
					ON (p.macAlbum_id  = a.macAlbum_id )
					WHERE 
					macPhoto_status = 'ON' AND p.is_delete = 0 AND a.is_delete = 0 AND macFeaturedCover = 1 AND macAlbum_status = 'ON' ORDER BY 1 DESC  
			       ";

	         $tableStatusOnPhotos = $wpdb->get_results( $sql ,ARRAY_N);
	       $countOfPhotos = count($tableStatusOnPhotos);
	      //echo "<pre>"; print_r($tableStatusOnPhotos);	      exit;
	       
	          if($countOfPhotos){

		          for($i = 0 ; $i< $countOfPhotos ; $i++)
		         {
		         	$selectedids[$i] = $tableStatusOnPhotos[$i][0];
		         }
	             
		          $isShowFeatured = $countOfPhotos - $num_of_photos; 
		       	 shuffle($selectedids);
			 	 array_unique($selectedids);
				 	 if($isShowFeatured > 0)
				 	 {
				 	 	$selectedids = array_slice($selectedids , 0 ,$num_of_photos);   
				 	 }	     
			 //shuffle($selectedids);
  		     $sql = "select macPhoto_sorting , macPhoto_id , p.macAlbum_id  , macAlbum_name , `macPhoto_image` FROM $table1 as p  LEFT JOIN  $table2  as a    ON (p.macAlbum_id  = a.macAlbum_id )  WHERE macPhoto_id  IN (".implode(",", $selectedids).")  AND  a.is_delete = 0  AND p.is_delete = 0 AND a.macAlbum_status = 'ON' AND p.macPhoto_status = 'ON'  " ;
  	  	     $getFeaturedPhotos = $wpdb->get_results($sql) ;
		 	 $numberofrows =count($getFeaturedPhotos);
	    			
?> 

<script type="text/javascript"> 
    
function mycarousel_initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
        
    });
 
    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });
 
    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};
 
jQuery(document).ready(function() {
    jQuery('#mycarousel').jcarousel({
        auto: 3,
        wrap: 'last',
        initCallback: mycarousel_initCallback
    });
});
 
</script> 

		
		<form name="submitalbum" action="" method="post" >
		  <input type="hidden" value="" id="tablephotonumberis" name="tablephotonumberis" /> 
		</form>

		 	
<!--   ********************************* STARTING FEATURED PHOTOS SHOWING   ***************************************  -->
	
<?php   
				echo '<div id="pica_show_featured" class="pica_show_featured_photos" >';
		       
				$fv = $picaSetting['pica-feat-vspace'].'px';
				$fh = $picaSetting['pica-feat-hspace'].'px';
				$featureImgH = $picaSetting['pica-feat-photo-height'].'px';	
			    $style = "style = 'margin:$fv $fh ;height : $featureImgH; '"; 
			    
			    $fw = $picaSetting['pica-feat-photo-width'].'px';  //if photo width is low alb name must mininmu chars
			    $albNameStyle = '';
			     if($fw <= 50) 
			    {
			    	$albNameStyle = "style:font-size:8px; "; 
			    }
		 	   else if($fw <= 80) 
			    {
			    	
			    	$albNameStyle = "style:font-size:10px; "; 
			    }
			    	
				
	 // <property name="dest_folder" value="../../mac_dock_gallery_apptha_svn/wp-content/plugins/mac_dock_gallery" />
	    $col = 0;
		 		for($i = 0 ; $i <  $numberofrows ; $i++ , $col++ )  // for showing featured photos in front page
		 	   {   
		      	if($col >= $fc)  // for showing number of photos per row
		      	{
		      		$col = 0;
		      		echo "<div style = 'clear:both;'></div>";
		      	}
		 	   		$serialno =  $getFeaturedPhotos[$i]->macPhoto_sorting;
		 	   		$serialno++;
			      
		 	  		 	$albName = ucfirst($getFeaturedPhotos[$i]->macAlbum_name);   	
			      	
			     	$showalbId  = $getFeaturedPhotos[$i]->macAlbum_id;
			     	 $phid  = explode('_', $getFeaturedPhotos[$i]->macPhoto_image);
			     	 $phid = $phid[0];
			     	 $ext = explode('.', $getFeaturedPhotos[$i]->macPhoto_image);
					$onclickEvent = "onclick='showalbpage($showalbId , $serialno , 2 )'"; 		
			     	echo "<div $style  class='pica-show-image'  onmouseover='showalbumname($i);' onmouseout='dontshowalbumname($i);'  >";
			     	echo "<a href='javascript:void(0)' $onclickEvent  >
					      <img   src =".$path.'/'.$phid.'_featuredtumb.'.$ext[1]." />
					      </a>";
			     	echo "<div id=$i  $albNameStyle  class='pica-show-albumname'>";
			     	echo '<a style="cursor:pointer" onclick = "showalbpage(\''.$showalbId.'\')" >'.$albName.'</a></div></div>';
			     		  
		      } 
		      echo '</div>';
      
		}//if for show featured images only
		
   //********************************* STARTING ALBUMS  SHOWING   ***************************************  		     
    
       if($picaSetting['pica-general-show-alb'])   // if show album option is enable then only it display
       {   
   				
   	?>   <div class = 'featuredPhotosStyle' style = "margin :10px 0px 0px 0px;"> Albums </div> 
   	<?php 
   	 $sql = "select A.macAlbum_id ,	macAlbum_name,	macAlbum_image,	macAlbum_status,	macAlbum_date ,  count(*) as  total from $table2 AS A RIGHT JOIN $table1  AS P  ON P.macAlbum_id = A.macAlbum_id AND macAlbum_status='ON'   WHERE macAlbum_status = 'ON' AND P.is_delete = 0 AND A.is_delete = 0   GROUP BY A.macAlbum_id ORDER BY 1 DESC ";
  
   	 $getAlbumPhotos =  $wpdb->get_results($sql);
   	 
   	   $numberofalbums =  count($getAlbumPhotos);
   	$albumPhotoWidht =  $picaSetting['pica-alb-photo-width'];
   				$albumPhotoWidht = ($albumPhotoWidht * 3 )+100;
   				$albumPhotoWidht .= 'px';
   	?>   
   	<div id="wrap"  >	
   	<ul id="mycarousel" class="jcarousel-skin-tango" style="width: <?php echo ($picaSetting['pica-alb-photo-width']*$numberofalbums)+100;?>px !important;" >
   	 	<script>
   	   	
   	function showalbpage(albid , phid , flag)
   	{
   						
   			<?php 
   				global $wp_rewrite;
   			
   				  if($wp_rewrite->using_permalinks()){
   				   	
   				 		$url =  get_page_link();
   				 		$url = $url."?aid=";
   				   }    	
   				   else{
   				   		$url =  get_page_link();
   				   		$url = $url."&aid=";
   				   }
   			?>
   		
   		if(phid){ // go to album photos
   			window.location.href = "<?php echo $url; ?>"+albid+'&pid='+phid;
   		}
   		else{
   			window.location.href = "<?php echo $url; ?>"+albid;
   		}
   	}//function end
   		
   	
   	</script>	
   	<?php
 		
  
	 	$av = $picaSetting['pica-alb-vspace'].'px';
		$ah = $picaSetting['pica-alb-hspace'].'px';
        $style = "style = 'margin:$av $ah' ";
	//echo "<pre>"; print_r($getAlbumPhotos);   
   	if($numberofalbums)
   	{
     		 for($i = 0 ; $i < $numberofalbums ; $i++) // for showing albums in front page
		    { 
		      	
				    	  $macAlb_img = $getAlbumPhotos[$i]->macAlbum_image;
				    	  $macAlb_img = trim($macAlb_img); 
					    if($macAlb_img == '')
					    {
					    	 $albimg = $site_url.'/wp-content/plugins/'.$folder.'/uploads/star.jpg' ;
					    	 $aw =  $picaSetting['pica-alb-photo-width'].'px';
					    	 $ah =  $picaSetting['pica-alb-photo-Height'].'px';
					    	 $imgstyle = "style='width:$aw ; height:$ah ; margin:3% 3%' ";
					    }
					    else{
					    	$macAlb_img = explode('_', $macAlb_img);
					   		$macAlb_img[1] = explode('.', $macAlb_img[1]);
					    	$albimg =  $path.'/'.$macAlb_img[0].'_albumthumb.'.$macAlb_img[1][1];
					    	$imgstyle = 'style =" margin:3% 3%" ';
					    }	  
						$albphwidth  = $picaSetting['pica-alb-photo-width'].'px';
						$albumNameWidth = " style = 'overflow:hidden ;padding-left:5%;' ";

						$macAlb_name = ucfirst($getAlbumPhotos[$i]->macAlbum_name);
			   	
			   		  $showalbId  =  $getAlbumPhotos[$i]->macAlbum_id;   
			   		$postDate  = $getAlbumPhotos[$i]->macAlbum_date;
			   		
			   		 $postDate  = date('M d,  Y', strtotime($postDate));
			   		
			   		  $numOfPhotos = $getAlbumPhotos[$i]->total;
			   		    echo  "<li $style class='pica-show-alb-image' onclick = showalbpage($showalbId); >";
			     		echo  "<a  style='cursor:pointer;' > 
			     				<img  $imgstyle src =".$albimg." /> ";
			     		echo  "<div $albumNameWidth ><b>"; echo  ''.$macAlb_name.' </b> </div>';
			     		echo  "<div style='padding-left:5%;' >"; echo  ''. $postDate.'  </div>';
			     		echo  "<div style='padding-left:5%;'>"; echo  'photos: '. $numOfPhotos.'  </div>';
			     		      
			     		echo  '</a></li>';
		     }//for end hear
		     
			echo ' </ul>  </div>';
   	}//if counnt end hear	    
  }//if end
      
   	} //cloase else condition hear
   ?>
   <div style="clear: both;"> </div>
  <?php 
  $picakeyset =  get_option('get_pica_title_key');
  $key = strlen(trim($picakeyset['title']));
  if(!$key){  ?> 
   <div id="apptha-site-generator">
							<a href="http://www.apptha.com/" title="Apptha" target="_new" >Powered by Apptha.</a>
			</div>
   <?php } //key if is end 	
	 // ********************************************    STOP  **************************************************************
 		
  }// End of the function
}// End of the class
?>