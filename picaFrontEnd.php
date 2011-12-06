<?php
/*
 * ********************************************************* */
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
/*             This class is useful for showing Entire Front end of PICA Photo Gallery 				  */
class pica_contusPicagallery {
	
	
    function picaEffectgallery($arguments= array(), $wid) {
         
  			global $wpdb; global $wp_rewrite;  	
    		$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
				if ($_SERVER["SERVER_PORT"] != "80")
				{
				    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
				} 
				else 
				{
				    $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
				}
   			  if($wp_rewrite->using_permalinks()){
		   				   	
		   				 		
		   				 		$isPermaLink = 1;
		   	   }    	
		   	  else{
		   				   		
		   				   		$isPermaLink = 0;
		   	   }
		$picaKeySet =  get_option('get_pica_title_key');
	    $licenseKey = strlen(trim($picaKeySet['title']));	
		$pluginFoulderName   = dirname(plugin_basename(__FILE__));
        $siteUrl =  $site_url = get_bloginfo('url');
        $pathToPluginDir = $siteUrl.'/wp-content/plugins/'.$pluginFoulderName;	 
       
        $imgfoulder = $site_url.'/wp-content/plugins/'.$pluginFoulderName.'/images/icon.png';
        $uploadDir = wp_upload_dir();
        $photosUploadedDir = $path = $uploadDir['baseurl'] . '/pica-photo-gallery'; //photos were uploaded in this DIR
	    $settingsTableCols =  "`pica-feat-cols`,`pica-feat-rows`,`pica-feat-photo-width`,`pica-feat-photo-height`,`pica-feat-vspace`,`pica-feat-hspace`,`pica-alb-cols`,`pica-alb-rows`,`pica-alb-photo-width`,`pica-alb-photo-Height`,`pica-alb-vspace`,`pica-alb-hspace`,`pica-general-share-pho`,`pica-general-fac-com`,`pica-general-download`,`pica-general-show-alb`,`pica_facebook_api`,`pica-photo-tumb-w`,`pica-photo-tumb-h`,`pica-photo-vspace`,`pica-photo-hspace`,`pica-photo-gene-w`,`pica-photo-gene-h`";
        
        $picaSetting = $wpdb->get_row("SELECT $settingsTableCols  FROM " . $wpdb->prefix . "pica_settings_menu", ARRAY_A); // Get Values from pica_settings table  which is set at backend setting tab. 
      
     
        $picaPhotosTable = $wpdb->prefix ."picaphotos";
        $picaAlbumsTable = $wpdb->prefix ."picaalbum" ;
       		
 // ********************************** SHOW SINGLE PHOTO FROM ALBUM  ***************************************************************
			
		if( isset( $_REQUEST['pid'] ) || isset($_REQUEST['pld'])  )
		{
		
		    $rightArrow =  dirname($uploadDir['baseurl']).'/plugins/'.$pluginFoulderName.'/pica_Carousel with autoscrolling_files/right-arrow.png'; //display right navigation button 
	        $leftArrow =  dirname($uploadDir['baseurl']).'/plugins/'.$pluginFoulderName.'/pica_Carousel with autoscrolling_files/left-arrow.png';   //display left navigation button  
		
		    $limit = $photoSno = $photoId = filter_input(INPUT_GET , 'pid') ; //$_REQUEST['pid']; 
		    $albId = filter_input(INPUT_GET , 'aid');
			$goBack  = $pageURL;
	 		$goBack  = explode('&pid', $goBack); // if any one change url then goto album page
	 		$limit--;
	 	    $sql = "SELECT   macPhoto_id, macPhoto_name , macPhoto_tname ,macPhoto_image , macPhoto_desc  FROM " . $wpdb->prefix . "picaphotos  WHERE  is_delete =0 and macPhoto_status = 'ON'
		            AND  macAlbum_id = $albId AND  macPhoto_sorting = $limit LIMIT  1";
		
		
		 	$picaAlbumPhotosList = $wpdb->get_results($sql); // Getting album photos
		    $numOfFetched = count($picaAlbumPhotosList);
		  // echo '<pre>'; print_r($picaAlbumPhotosList);	  exit;
	      
	     if($numOfFetched){ //if min(1) then show else dont show     
		
		 	  $sql = "SELECT count(*)  as total  , macAlbum_name FROM $picaPhotosTable as p  , $picaAlbumsTable as a  WHERE p.`macAlbum_id` = $albId and a.`macAlbum_id` = $albId  AND p.is_delete = 0 AND macPhoto_status = 'ON' ";
			  $picaAlbName = $wpdb->get_results( $sql ); 
		      $totalPhotos = $picaAlbName[0]->total;
	     	  $albumNameis = $picaAlbName[0]->macAlbum_name ; 
	      
		       $url = $goBack[0].'&pid=';
		  		if($photoId <= 1 )	
		  		{
		  			$leftArrowId = 0;
		  		}
		  		else{
		  			$leftArrowId = $photoId - 1;
		  		}	
		  		if($totalPhotos <= $photoId )
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
			$breadCrumb = 'style = "text-decoration: none;"';	
		  echo  "<div class='pica-showsinglebread'>
		  				<a href =$goBack[0] >".  $albumNameis .'</a>'." > <span class = 'pica-showbreadalbname'> $subname </span> ><span style='color:black;'> Photo $photoSno of $totalPhotos </span> ";
		  echo "</div>";		  
		
		  $photourl = $site_url.'/wp-content/uploads/pica-photo-gallery/'.$picaAlbumPhotosList[0]->macPhoto_tname;
		  $imgName = $site_url.'/wp-content/plugins/'.$pluginFoulderName.'/picadownload.php?imgname='.$picaAlbumPhotosList[0]->macPhoto_tname;
		  $facebookimg =  $site_url.'/wp-content/plugins/'.$pluginFoulderName.'/images/facebookshare4.jpg';
		  $downloadimg =  $site_url.'/wp-content/plugins/'.$pluginFoulderName.'/images/picaDownload.png';
		  $zoomimg =  $site_url.'/wp-content/plugins/'.$pluginFoulderName.'/images/picazoom.png';
  		  $macapi = trim($picaSetting['pica_facebook_api']); //kranthi FB APIid '135895656513411';
		  $downloadImg = $imgName = $pathToPluginDir.'/picadownload.php?imgname='.$picaAlbumPhotosList[0]->macPhoto_tname;
				
		  $img_render = $path.'/'.$originalPhotoName;
	
		  $fbUrl = 'http://www.facebook.com/dialog/feed?app_id='.$macapi.'&description='.$ldescr.'&picture='.$img_render.'&link='.urlencode($pageURL).'&name='.$descr.'&message=Comments&redirect_uri='.urlencode($pageURL);
     	  $photonameis = $path.'/'.$originalPhotoName;
     	$zoomInOutDirPath = $site_url.'/wp-content/plugins/'.$pluginFoulderName.'/zoominout/';
	    	$singeTdWidth = $picaSetting['pica-photo-gene-w'];
	    	$photoDescriWidth = ($singeTdWidth).'px'; 
	    	$singeTdWidth12 = $singeTdWidth+50;
	    	$singeTdWidth12 .= 'px';
	    	$singeTdWidth .= 'px';
	    	if( $_GET['pid'] > 1)
	    	{
	    		 $photoDescriStyle = "style='width:$photoDescriWidth;margin-left:45px'";
	    	}
	    	else 
	    	{
	    		 $photoDescriStyle = "style='width:$photoDescriWidth'";
	    	}
	        if(!$picaSetting['pica-general-fac-com']) {

			 $singlePhotoDivStyle = "style='vertical-align: middle;'" ;
			 $siglePhotoOptionsStyle = '';
			 $siglePhotoAppthaLable = 'style = left:130px';
		?>
				<style>
				#content{
						margin:0px;
				}
				#primary{
								display: none; 
						}
				</style> 
	   <?php }else{
			 		$singlePhotoDivStyle = "style='vertical-align: middle;width:$singeTdWidth;'" ;
			 		$siglePhotoOptionsStyle = "style='width:$singeTdWidth12'";
			 		$siglePhotoAppthaLable = 'style = left:4px';
		} 
	    	
	    	
	    	
	    	
     	    	echo ' <div '.$siglePhotoOptionsStyle.'  class="get-pica-border clearfix " >';
  
		        if(1) { // for show Full screen
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
			  } 	
	 	echo '</div>';
	 	
	    echo '<div id="iviewer">
		        <div class="loader"></div>
		        <div class="viewer"></div>
		        <ul class="controls">
		            <li style="list-style: none;" class="close"></li>
		            <li style="list-style: none;" class="zoomin"></li>
		            <li style="list-style: none;" class="zoomout"></li>
		        </ul>
	    	</div>
	     '; 
	    	
	    ?>
	    
		<script type="text/javascript" src="<?php echo $zoomInOutDirPath; ?>js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo $zoomInOutDirPath; ?>js/jqueryui.js"></script>
		<script type="text/javascript" src="<?php echo $zoomInOutDirPath; ?>js/jquery.iviewer.js"></script>
		<script type="text/javascript" src="<?php echo $zoomInOutDirPath; ?>js/main.js"></script>
		<script type="text/javascript" src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script> 
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $zoomInOutDirPath; ?>zoomstyle.css" /> 
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo $zoomInOutDirPath; ?>colorbox1.css" />
		
		 		
		
		<table  id="pica-single-img-view"  cellpadding="0" cellspacing="0" style="border: none;margin-bottom: 0px"  >
			<tbody>
				<tr>
					<td  style="width:46px; padding: 0px;margin: 0px;cursor:pointer;border-top:none;background:none;vertical-align: middle; " class="picaphotoArrow"  >
						<?php	if($leftArrowId){  // show left arrow ?>  	
									
									<img src="<?php echo $leftArrow; ?>" onclick="showsliding('<?php echo $url ; ?>' , '<?php echo $photoSno; ?>','<?php echo $leftArrowId; ?>' , 'left' ); " />
									 
						<?php   }  ?>
					
					</td>
					
					 <td  style=" vertical-align: middle; padding: 0px 0px 0px 5px;margin: 0px;border-top:none;background:none;text-align: center;"  >
					 				
						  <div  <?php echo $singlePhotoDivStyle ?> >
						 <?php if(!$licenseKey){ //if licencekey is given at backend then dont disply ?>  
						  <div  style="position:relative">
							  <span  <?php echo $siglePhotoAppthaLable; ?> id = "picaAppthaLabelOnPhoto" >
							       <a target="_blank" href="http://www.apptha.com/shop/checkout/cart/">
							  			<img src="http://www.platoon.in/images/1.png">
							 	   </a>
							   </span>
						   </div>
						  <?php } ?>
								    <img  style="text-align: center ;padding: 0px;margin: 0px;-moz-box-shadow: 2px 2px 8px #888;-webkit-box-shadow: 2px 2px 8px #888;"  src="<?php echo  $photosUploadedDir.'/'.$originalPhotoName ; ?>" alt="<?php echo $photoName; ?>"  />
								 
						  </div>
					</td>
					 <td style="width:46px;padding: 0px 0px 0px 5px;margin: 0px;cursor:pointer;border-top:none;background:none;vertical-align: middle;" class="picaphotoArrow"  >
							
							<?php 	if($rightArrowId){ // show right arrow  ?>      
							 	
							 		<img src="<?php echo $rightArrow; ?>" onclick="showsliding('<?php echo $url ; ?>' , '<?php echo $photoSno; ?>','<?php echo $rightArrowId ?>','right' )"  />
							 	 
							<?php 	} //if for photosno right arrow ?>	
					 </td>
				</tr>
			</tbody>
		</table>

		<div <?php echo $photoDescriStyle; ?>  class = "photoDescription" ><?php echo ucfirst($ldescr); ?>  </div>
	

<?php   
		}//if($imgId) end hear
	else{
			
			echo "<script> gotoalbpage('$goBack[0]'); </script>" ;
		}	

 } // if is end hear
 
// ********************************** SHOW PHOTOS IN SELECTED ALBUM  ***************************************************************
		 
 	else if(isset( $_REQUEST['aid'] )){  

 
			$albId =  $_REQUEST['aid'];
			$sql = "SELECT macAlbum_name , macPhoto_id , macPhoto_image ,  macPhoto_name  FROM $picaPhotosTable  as p JOIN  $picaAlbumsTable  USING(macAlbum_id)   WHERE p.macAlbum_id =".$albId. " AND macPhoto_status='ON' AND p.is_delete =0 ORDER BY macPhoto_sorting ASC";
			$picaAlbumPhotosList = $wpdb->get_results($sql); // Getting album photos 
	       	$picaAlbumName = $picaAlbumPhotosList[0]->macAlbum_name; //ablum name	
			
	       	$goBack = get_page_link();  //for breadcrumb taken url to go alb page 
	       	$breadCrumb = 'style = "text-decoration: none;"';//showing breadcrumb link	
		    echo      " <a $breadCrumb href =$goBack >Albums </a><span style= 'color :black;' >></span>   
		    			<span class='albphoto-context-current' >$picaAlbumName</span><br/> " ;
			$numberOfPhotos =  count($picaAlbumPhotosList);
			$pv = $picaSetting['pica-photo-vspace'].'px'; // for vertical space bet photos
			$ph = $picaSetting['pica-photo-hspace'].'px'; // for horiza   space bet photos
			$PhotoThumbH = $picaSetting['pica-photo-tumb-h'].'px';
			$photoThumbW = $picaSetting['pica-photo-tumb-w'].'px';	   		 
		    $style = "style = 'margin:$pv $ph;height:$PhotoThumbH;width:$photoThumbW ' ";
		    
		    $pv = $picaSetting['pica-photo-tumb-w'].'px';
	  	    $titleStyle = "style = 'width:$pv;' ";
	        echo "<div id='albumpagelist' style = 'margin-bottom:20px;'>";	
	      					 
			 for($i = 0 ; $i < $numberOfPhotos ; $i++){    // for showing  photos in album page
				      	
						      		
				    	  $macPhotoName = $picaAlbumPhotosList[$i]->macPhoto_image; 
				    	  $macPhotoId = $picaAlbumPhotosList[$i]->macPhoto_id;
				    	  
				    	  $macPhotoName = explode('.', $macPhotoName);
				    	  $titleData = substr($picaAlbumPhotosList[$i]-> macPhoto_name , 0 , 100);
				    	  $titleData = ucfirst($titleData); // convert first Char as upper
				   		  $jsmouseOver = 'onmouseover = "showPhotoTitleStyle('.$i.')"';
				   		  $jsmouseOut =  'onmouseout =  "dontshowPhotoTitleStyle('.$i.')"';
				   		  //$jsmouseout $jsmouseover
				   		  $img = $path.'/'.$macPhotoId."_photothumb.".$macPhotoName[1];  //_photothumb
									
							echo  "<div $style class='showAlbPhotos '  >
							       <a  onclick=showsinglephotopage('".$pageURL."','".$macPhotoId."','".($i+1)."')  > 
					               <img style='max-width:100% !important;' $jsmouseOut $jsmouseOver  src ='".$img."' /> </a>
							       <div id='albumname$i' $titleStyle class = 'picaPhotoTitle'  >$titleData</div> 
							</div>"; // $titledata	
			}//for loop end
			
			echo "</div>";			
			
		}//main if end
			
		 // ********************************** SHOW FEATURED PHOTOS AND ALBUMS IN FIRST PAGE  ***************************************************************
		 	
		else{   // for shoing featured photos and albums
		       
			
				 $fc = intval($picaSetting['pica-feat-cols']); //for showing number of featured photos get C and R val from backend
		         $fr = intval( $picaSetting['pica-feat-rows']);
		         $numOfPhotos = $fc * $fr; 					   //we have to show this number of photos as featured photos 
		         $limitValue = $numOfPhotos * 3 ;              //get photos tripul from db
		         $randVal = rand(0 , 20);					   //change order dynamicaly 	
		         if($randVal % 2 == 0)
		         {
		         	$orderBy = 'ASC';						   // if it is even number then asc order
		         }
		         else{
		         	$orderBy = 'DESC';
		         }
			     												   //Required coloums form pica_photos_table     
			     $photoTabCol = 'macPhoto_id , macPhoto_sorting , macPhoto_id , p.macAlbum_id  , macAlbum_name , `macPhoto_image` ';
		 	     $sql = "select   $photoTabCol  FROM $picaPhotosTable as p
						LEFT JOIN
						$picaAlbumsTable as a
						ON (p.macAlbum_id  = a.macAlbum_id )
						WHERE 
						macPhoto_status = 'ON' AND p.is_delete = 0 AND a.is_delete = 0 AND macFeaturedCover = 1 AND macAlbum_status = 'ON' ORDER BY 1 $orderBy LIMIT 0 , $limitValue  
				       ";
	
		         $getFeaturedPhotos = $tableStatusOnPhotos = $wpdb->get_results( $sql ); // It get the Result of $sql query and stored in assigend variable
		         $countOfPhotos = count($getFeaturedPhotos);	
	       
	          if($countOfPhotos){    //if min(1) then do else u will get Fatal Error

		           $isSpliteFeaturedPho = $countOfPhotos - $numOfPhotos; //For getting our requried photos from list of photos 
		       		
				 	 if($isSpliteFeaturedPho > 0) // if our num of featured photos < feached records Then only cut array
				 	 {
				 	 	$getFeaturedPhotos = array_slice($getFeaturedPhotos , 0 ,$numOfPhotos);    //seleted num of photos given by user at backed as col and row vals
				 	 }
				 	 else{
				 	 		$numOfPhotos = $countOfPhotos;
				 	 }	   
		
		  	  shuffle($getFeaturedPhotos); // change order of array eleme
	    	  shuffle($getFeaturedPhotos); 
?> 

<script type="text/javascript"> // for albums sliding
    
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

		

		 	
<!--   ********************************* STARTING FEATURED PHOTOS SHOWING   ***************************************  -->
	
<?php   
				echo '<div id="pica_show_featured" class="pica_show_featured_photos" >';
		       
				$fv = $picaSetting['pica-feat-vspace'].'px';
				$fh = $picaSetting['pica-feat-hspace'].'px';
				$featureImgH = $picaSetting['pica-feat-photo-height'].'px';	
				$featureImgW = $picaSetting['pica-feat-photo-width'].'px';	
			    $style = "style = 'margin:$fv $fh ;widht:$featureImgW;height:$featureImgH;'"; 
			    $imgStyle = 'style="opacity:1.0;"';  
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
			    	
	    	   $col = 0;
		 		for($i = 0 ; $i <  $numOfPhotos ; $i++ , $col++ )  // for showing featured photos in front page
		 	   {   
			      	if($col >= $fc)  // for showing number of photos per row
			      	{
			      		$col = 0;
			      		echo "<div style = 'clear:both;'></div>";
			      	}
		 	   		$serialNo =  $getFeaturedPhotos[$i]->macPhoto_sorting;
		 	   		$serialNo++;
			       
		 	  		$albName = ucfirst($getFeaturedPhotos[$i]->macAlbum_name);   	
			      	
			     	$showAlbId  = $getFeaturedPhotos[$i]->macAlbum_id;
			     	$photoIdIs  = explode('_', $getFeaturedPhotos[$i]->macPhoto_image);
			     	$photoIdIs = $photoIdIs[0];
			     	$photoExten = explode('.', $getFeaturedPhotos[$i]->macPhoto_image); //Get img extension .jpg  , .png  ...
					$onclickEvent = "onclick='showalbpage($isPermaLink,$showAlbId,$serialNo)'"; 	// if u click on photo it go to big photo show page
					$onClickEvent2 = "onclick='showalbpage($isPermaLink,$showAlbId)'"; 
					//onclick = "showalbpage(\''.$isPermaLink.'\''.$showAlbId.')" 	
			     	echo "<div $style  class='pica-show-image'  onmouseover='showalbumname($i);' onmouseout='dontshowalbumname($i);'  >";
			     	echo "<a href='javascript:void(0)' $onclickEvent  >
					      <img  style='max-width:100% !important;' src =".$path.'/'.$photoIdIs.'_featuredtumb.'.$photoExten[1]." />
					      </a>";
			     	echo "<div id=$i  $albNameStyle $onClickEvent2 class='pica-show-albumname'>";
			     	echo '<a style="cursor:pointer"  >'.$albName.'</a></div></div>'; // if u click on <a> tag it show all phtos in that album
			     		 
		      } 
		      echo '</div>';
      
		}//if for show featured images only         END of showing Featured Photos
		
   //********************************* STARTING ALBUMS  SHOWING IN SLIDE SHOW  ***************************************  		     
    
       if($picaSetting['pica-general-show-alb'])   // if show album option is enable then only it display
       {   
   				
	   	 echo '  <div class = "featuredPhotosStyle" style = "margin :10px 0px 0px 0px;padding-top:3%;"> Albums </div> '; 
	   	 $sql = "select A.macAlbum_id ,	macAlbum_name,	macAlbum_image,	macAlbum_status,	macAlbum_date ,  count(*) as  total from $picaAlbumsTable AS A RIGHT JOIN $picaPhotosTable  AS P  ON P.macAlbum_id = A.macAlbum_id AND macAlbum_status='ON'   WHERE macAlbum_status = 'ON' AND P.is_delete = 0 AND A.is_delete = 0   GROUP BY A.macAlbum_id ORDER BY 1 DESC ";
	   	 $getAlbumPhotos =  $wpdb->get_results($sql);
	     $numberOfAlbums =  count($getAlbumPhotos);
	     $albumPhotoWidht =  $picaSetting['pica-alb-photo-width'];
   			// echo ($picaSetting['pica-alb-photo-width']*$numberOfAlbums)+100;	
   	?>   
		   	<div id="wrap"  >	
		   	<ul id="mycarousel" class="jcarousel-skin-tango" style="width: <?php echo ($picaSetting['pica-alb-photo-width']*$numberOfAlbums)+200;?>px !important;" >
		   

   	<?php
 	
	 	$av = $picaSetting['pica-alb-vspace'].'px';
		$ah = $picaSetting['pica-alb-hspace'].'px';
        $style = "style = 'margin:$av $ah' ";
	    $imgStyle = 'style = "opacity:1.0;" ';
	   	if($numberOfAlbums)
	   	{
	     		 for($i = 0 ; $i < $numberOfAlbums ; $i++) // for showing albums in front page
			    { 
			      	
					    	$macAlbImgName = $getAlbumPhotos[$i]->macAlbum_image;
					    	$macAlbImgName = trim($macAlbImgName); 
						    if($macAlbImgName == '')
						    {
						    	 $albimg = $site_url.'/wp-content/plugins/'.$pluginFoulderName.'/uploads/star.jpg' ;
						    	 $aw =  $picaSetting['pica-alb-photo-width'].'px';
						    	 $ah =  $picaSetting['pica-alb-photo-Height'].'px';
						    	
						    }
						    else{
						    	$macAlbImgName = explode('_', $macAlbImgName);
						   		$macAlbImgName[1] = explode('.', $macAlbImgName[1]);
						    	$albimg =  $path.'/'.$macAlbImgName[0].'_albumthumb.'.$macAlbImgName[1][1];
						    	
						    }	  
						 
						  $albumNameWidth = "style = 'overflow:hidden ;' ";
						  $macAlbName     = ucfirst($getAlbumPhotos[$i]->macAlbum_name);
				   		  $showAlbId      = $getAlbumPhotos[$i]->macAlbum_id;   
				   		  $postDate       = $getAlbumPhotos[$i]->macAlbum_date;
				   		  $postDate       = date('M d,  Y', strtotime($postDate));
				   		  $numOfPhotos = $getAlbumPhotos[$i]->total;
				   		    echo  "<li $style class='pica-show-alb-image' onclick = showalbpage($isPermaLink,$showAlbId); >";
				     		echo  "<a  style='cursor:pointer;' > 
				     				<img  $imgStyle src =".$albimg." /> ";
				     		echo  "<div $albumNameWidth ><b>"; echo  ''.$macAlbName.' </b> </div>';
				     		echo  "<div  >"; echo  ''. $postDate.'  </div>';
				     		echo  "<div >"; echo  'photos: '. $numOfPhotos.'  </div>';
				     		      
				     		echo  '</a></li>';
			     }//for end hear
			     
				echo ' </ul>  </div>';
	   	}//if counnt end hear	    
  }//if end
      
   	} //cloase else condition hear
   
	  echo '<div style="clear: both;"> </div>'; 
	  if($_GET['pid'])
	  {
	  	$singeTdWidth = $singeTdWidth + 90;
	  	$singeTdWidth .= 'px'; 
	  	$appthaLable = "style = 'width:$singeTdWidth'";
	  }
	  if(!$licenseKey && !$_GET['pid']){  ?> 
	   							<div <?php echo $appthaLable; ?> id="apptha-site-generator">
								<a href="http://www.apptha.com/" title="Apptha" target="_new" >Powered by Apptha.</a>
							    </div>
	   <?php   } //key if is end 	
	 // ********************************************    STOP  **************************************************************
 		
  }// End of the function
}// End of the class
?>