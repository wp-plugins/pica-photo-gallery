<?php
class picaShowAlbums{
	
	function __construct(){
		
		//echo "This is kranthi";
	}
	
	
	function showPicaAlbum($arguments= array()){
		
		
				 // ********************************** SHOW PHOTOS IN SELECTED ALBUM  ***************************************************************
		
	
			global $wpdb;
			$albId =  $tempurl[1];
			$picaAlbumName = $wpdb->get_var("SELECT macAlbum_name  FROM " . $wpdb->prefix . "picaalbum  WHERE macAlbum_id	 =".$albId. " "  ); // Getting album name	
			$picaAlbumPhotosList = $wpdb->get_results("SELECT macPhoto_id , macPhoto_image , macPhoto_desc  FROM " . $wpdb->prefix . "picaphotos  WHERE pica_seo_name =".$albId. " AND  is_delete =0"  ); // Getting album photos 
	       		
			echo "<pre>";       		print_r($picaAlbumName);       		echo "<pre>";	exit;
	       	$goback = get_page_link();	
		    echo     "<a href =$goback >". $picaAlbumName.'</a>'.">
		    			<span class='albphoto-context-current' >$picaAlbumName</span><br/> " ;
						 $numberofphotos =  count($picaAlbumPhotosList);
				    for($i = 0 ; $i < $numberofphotos ; $i++){    // for showing  photos in album page
				      	
						      		
				    	  $macPhoto_name = $picaAlbumPhotosList[$i]->macPhoto_image; 
				    	  $macPhoto_id = $picaAlbumPhotosList[$i]->macPhoto_id;
				    	  
				    	  $macPhoto_name = explode('.', $macPhoto_name);
				    	  //print_r($macPhoto_name);
				   		  //$macPhoto_name[1] = explode('.', $macPhoto_name[1]);
				   		  
				   		  
				   		  $titledata = $picaAlbumPhotosList[$i]->macPhoto_desc;
				   		 $img = $path.'/'.$macPhoto_id."_photothumb.".$macPhoto_name[1];  //_photothumb
				   		 
				     		echo  "<div style='float:left' ><a href='javascript:void(0)' title='$titledata' > <img style='padding:2px;' src =".$img."> </a></br>
				     				 $titledata
				     		</div>";
				      }
				
		
			
		
		
	}
}

?>