<?php
/*
 Plugin Name: PICA Photo Gallery
 Plugin URI: http://www.apptha.com/category/extension/Wordpress/PICA-Photo-Gallery
 Description: PICA Photo Gallery make it easy for you to organize  your digital photos, then create online albums to share with friends, family & the world. You can show your photos at their best It allow you Manage the stack of photos in categories as featured photos as seen in Picasa Gallery. Photo view with facebook share/download/zoom in/zoom out. Whole Design replicates Picasa design.
Facility to manage list of album under featured photos.
 Version:1.0
 Author: Apptha
 Author URI: http://www.apptha.com
 License: GNU General Public License version 2 or later; see LICENSE.txt
 */

/* The first loading page of the Mac Photo Gallery these contain admin setting too */
require_once("picaFrontEnd.php"); // Front view of the PICA Photo Gallery


    //class for creating widgets for Show albums
    class picaAlbums extends WP_Widget 
{ 
				protected $numOfAlbums;
				
			
				
        function picaAlbums() {
            $widget_ops = array('classname' => 'picaAlbums', 'description' => 'Albums of Your Site ');
            $this->WP_Widget('picaphotos', 'PICA Photo Gallery', $widget_ops);
        }

        function form($instance) {
            $instance = wp_parse_args((array) $instance, array('title' => '','noOfRows' => '', 'noOfCols' => '' , 'photoW' => '' , 'albType' => '')  );
            
            $title      = $instance['title'];
           $this->rows =  $noOfRowVal = $instance['noOfRows'];
          $this->cols =  $noOfColVal = $instance['noOfCols'];
            $this->photoW  =  $photoW  = $instance['photoW'];
            $this->orderBy = $albType  = $instance['albType'];
           $this->numOfAlbums = $this->cols * $this->rows ; 
            
          //  print_r($instance);
    ?>      <p>
	            <label for="<?php echo $this->get_field_id('title'); ?>">Title: 
	            		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
	            </label>
	             <label for="<?php echo $this->get_field_id('noOfCols'); ?>">No Of Albums: 
	            		<input class="widefat" id="<?php echo $this->get_field_id('noOfCols'); ?>" name="<?php echo $this->get_field_name('noOfCols'); ?>" type="text" value="<?php echo attribute_escape($noOfColVal); ?>" />
	            </label>
	          <!--  <label for="<?php echo $this->get_field_id('noOfRows'); ?>">No Of Rows: 
	            		<input class="widefat" id="<?php echo $this->get_field_id('noOfRows'); ?>" name="<?php echo $this->get_field_name('noOfRows'); ?>" type="text" value="<?php echo attribute_escape($noOfRowVal); ?>" />
	            </label>
	            --> 
	            <label for="<?php echo $this->get_field_id('photoW'); ?>">Photo Width: 
	            		<input class="widefat" id="<?php echo $this->get_field_id('photoW'); ?>" name="<?php echo $this->get_field_name('photoW'); ?>"  style="width: 95%;" type="text" value="<?php echo attribute_escape($photoW); ?>" />px
	            </label>
	             <label for="<?php echo $this->get_field_id('albType'); ?>">Select Albums as :
	             <?php if(!$albType){
	             			$selectOne = 'selected = "selected"';
	             		}
	             		else{
	             			$selectTwo = 'selected = "selected"';
	             		}	
	              ?> 
	            		<select class="widefat" id="<?php echo $this->get_field_id('albType'); ?>" name="<?php echo $this->get_field_name('albType'); ?>"  >
	            		
	            				<option value="<?php echo 0; ?>"  <?php echo $selectOne; ?> >Recent</option>
								<option value="<?php echo 1; ?>"  <?php echo $selectTwo; ?> >Random</option>
	            				
	            		</select>
	            	
	            </label>
            </p>
    <?php
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title']    = $new_instance['title'];
            $instance['noOfRows'] = $new_instance['noOfRows'];
            $instance['noOfCols'] = $new_instance['noOfCols'];
            $instance['photoW'] = $new_instance['photoW'];
            $instance['albType'] = $new_instance['albType'];
            return $instance;
        }

function widget($args, $instance) {
            extract($args, EXTR_SKIP);
            echo $before_widget;
            $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
            if (!empty($title))
                echo $before_title . $title . $after_title;
            // WIDGET CODE GOES HERE
            global $wpdb;global $wp_rewrite;  
            $uploadDir = wp_upload_dir();
            $photosUploadedDir = $path = $uploadDir['baseurl'] . '/pica-photo-gallery'; //photos were uploaded in this DIR
	  		$pWidth = $instance['photoW'].'px';
        
            $albTableName =  $wpdb->prefix.'picaalbum';
            $postTable = $wpdb->prefix.'posts';
            $sql = "SELECT macAlbum_id, macAlbum_image ,macAlbum_name FROM  $albTableName  WHERE macAlbum_status = 'ON' AND is_delete = 0  AND `macAlbum_image` != ''  ORDER BY 1 DESC ";
            $albumList = $wpdb->get_results($sql , ARRAY_A );
            $sql =  "SELECT * FROM $postTable WHERE `post_content` = '[picaGallery]' AND `post_status` = 'publish' LIMIT 1 " ;
            $postIdIs = $wpdb->get_var($sql);
            
     
             $this->numOfAlbums = $instance['noOfCols'] ; 
             $numOfAlbs = count($albumList);
             
               //post_content
         		if($wp_rewrite->using_permalinks()){
		   				 		$isPermaLink = 1;
		   				 		$PermaLink = $siteUrl.'/pica-gallery'.'?aid=';
		   	     }    	
		   	  else{
		   				   		$isPermaLink = 0;
		   				     	$PermaLink = $siteUrl.'?page_id='.$postIdIs.'&aid=';
		   	     }
             if($instance['albType']) //if albums type is RANDOM THEN WE SHUFFLE ARRAY
     		{
           		 shuffle($albumList);shuffle($albumList);
     		} 
     		if($this->numOfAlbums < $numOfAlbs)
     		{  
     			$albumList = array_slice($albumList , 0 , $this->numOfAlbums);
     			
     		}	        
     	    $style = "style = 'width:$pWidth;'";
								
     	    echo "<div  >";
     	       	   
            foreach($albumList as $key => $colNames )
            {
            	  
            	$path = $photosUploadedDir.'/'.$colNames['macAlbum_image'];
            	$onclickEvent = "onclick = showalbpage($isPermaLink,$colNames[macAlbum_id],0,0,'$PermaLink')";
            	echo "<img id='pica-widget-alb-photos' $style src='$path' $onclickEvent title='$colNames[macAlbum_name]' />"; 
            	
            }
    		echo "</div>";
                
                
             
   
            echo $after_widget;
        }

    }

    //class for creating widgets for product tag ends
   add_action('widgets_init', create_function('', 'return register_widget("picaAlbums");'));//adding product tag widget


function pica_Sharemacgallery($content)
{
	$content = preg_replace_callback('/\[picaGallery ([^]]*)\y]/i', 'pica_CONTUS_macRender', $content); //PiCA Photo Gallery Page
		
	return $content;
}

function pica_CONTUS_macRender($content,$wid='')
{
	global $wpdb;
	if($wid=='')
	$wid='pirobox_gall';
	$pageClass = new pica_contusPicagallery();
	$returnGallery = $pageClass->picaEffectgallery($content,$wid);
	return  $returnGallery;
}

function picaPage()
{
	add_menu_page('PICA Gallery', 'PICA Gallery', '4', 'picaAlbum', 'show_picaMenu',get_bloginfo('url').'/wp-content/plugins/'.dirname(plugin_basename(__FILE__)).'/images/icon.png');
	add_submenu_page('picaAlbum', 'Albums', 		'Albums',   4, 'picaAlbum',  'show_picaMenu');
	add_submenu_page('picaAlbum', 'Image upload', 'Upload Photos', 'manage_options', 'picaPhotos', 'show_picaMenu');
	add_submenu_page('picaAlbum', 'Mac Settings', 'Settings', 'manage_options', 'picaSettings', 'show_picaMenu');
}

function show_picaMenu()
{
	?>
<h2
	class="nav-tab-wrapper">
	<!--  <a id="picaGallery" href="?page=picaGallery" class="nav-tab">Gallery</a>  -->
	<a id="picaAlbum" href="?page=picaAlbum" class="nav-tab">Albums</a> <a
		id="picaPhotos" href="?page=picaPhotos&albid=0" class="nav-tab"> <?php  if($_GET['action']== 'viewPhotos')
		echo 'View Photos';
		else
		echo 'Upload Photos';
		?> </a>
	<!--  <a id="picatags"   href="?page=picatags" class="nav-tab">Tags</a> -->
	<a id="picaSettings" href="?page=picaSettings" class="nav-tab">Settings</a>
</h2>
<div
	style="background-color: #ECECEC; padding: 10px; margin-top: 10px; border: #ccc 1px solid">
	<strong> Note : </strong>Note : PICA Photo Gallery can be easily inserted to the Post / Page by adding 
	the following code, But use only ones per page or post
<br>[picaGallery] <br>

</div>

<script type="text/javascript">
	          	  document.getElementById("<?php echo $_GET['page']; ?>").className = 'nav-tab nav-tab-active';
				</script>
		<?php
		switch ($_GET['page'])
		{
			//echo ($_GET['page']);
			case 'macGallery' :
				include_once (dirname(__FILE__) . '/macgallerygroup.php'); // admin functions
				$macManage = new macManage();
				break;
			case 'picaAlbum' :
				include_once (dirname(__FILE__) . '/picaAddNewAlbum.php'); // admin functions
				$macManage = new macManage();
				break;
			case 'picaPhotos' :
				include_once (dirname(__FILE__) . '/picaShowPhotosInAlbum.php'); // admin functions
				$macPhotos = new macPhotos();
				break;
			case 'picaSettings' :

				include_once (dirname(__FILE__) . '/picaPluginRoot.php'); // admin functions
				picaSettings1();
				break;
			case 'picatags' :

				include_once (dirname(__FILE__) . '/picaTags.php'); // admin functions
				break;

		}

}
$options = get_option('get_pica_title_key');
if ( !is_array($options) )
{
	$options = array('title'=>'', 'show'=>'', 'excerpt'=>'','exclude'=>'');
		
}
if(isset($_POST['submit_license']))
{   
	
	$options['title'] = strip_tags(stripslashes($_POST['get_license']));

	update_option('get_pica_title_key', $options);
}


// Admin Setting For Mac Photo Gallery

function pica_get_domain($url)
{
	$pieces = parse_url($url);
	$domain = isset($pieces['host']) ? $pieces['host'] : '';
	if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
		return $regs['domain'];
	}
	return false;
}
function picaSettings1() {
	global $wpdb;
	$folder   = dirname(plugin_basename(__FILE__));
	$site_url = get_bloginfo('url');
	define('PLUGINAME',$folder);
	define('SITEURL',$site_url);

	$split_title =  $wpdb->get_var("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name='get_pica_title_key'");
	$get_title = unserialize($split_title);
	$strDomainName = $site_url;
	preg_match("/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
	preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder[2], $matches);
	$customerurl = $matches['domain'];
	$customerurl = str_replace("www.", "", $customerurl);
	$customerurl = str_replace(".", "D", $customerurl);
	$customerurl = strtoupper($customerurl);
	$get_key     = pica_macgal_generate($customerurl);//  esnecil

	if($get_title['title'] != $get_key)
	{  
		?>
<script>
function validateKey()
{
	   var Licencevalue = document.getElementById("get_license").value;
	   if(Licencevalue == ""||Licencevalue !="<?php echo $get_key ?>"){
 	   alert('Please enter valid license key');
 	   return false;
	   }
         else
            {
                alert('Valid License key is entered successfully');
 	           return true;
            }

}
	var url = '<?php echo $site_url; ?>';
	</script>
<link
	href="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/css/facebox_admin.css';?>"
	media="screen" rel="stylesheet" type="text/css" />
<script
	src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/facebox_admin.js'; ?>"
	type="text/javascript"></script>


<script type="text/javascript">
	 var apptha1 = jQuery.noConflict();
	    apptha1(document).ready(function(apptha1) {
	      apptha1('a[rel*=facebox]').facebox()
	    })
	   
	</script>
<p style="padding-right: 30px; padding-bottom: 20px;">
	<a href="#mydiv" rel="facebox"><img
		src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/images/licence.png'?>"
		align="right"> </a> <a href="http://www.apptha.com/shop/checkout/cart/add/product/68/"
		target="_blank"><img
		src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/images/buynow.png'?>"
		align="right" style="padding-right: 15px;"> </a>
</p>

<div id="mydiv" style="display: none">
	<form method="POST" action="" onSubmit="return validateKey()">
		<h2 align="center">License Key</h2>
		<div align="right">
			<input type="text" name="get_license" id="get_license" value='<?php echo $get_key; ?>' size="58" /> <input
				type="submit" name="submit_license" id="submit_license" value="Save" />
		</div>
	</form>
</div>
		<?php } //if is end hear ?>

<link
	rel="stylesheet"
	href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/style.css'; ?>">
<script
	src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/macGallery.js'; ?>"
	type="text/javascript"></script>
<script
	src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/jquery164.js'; ?>"
	type="text/javascript"></script>
		
<div id="error_msg" style="color: red"></div>
		<?php
	
		$isupdated = 0;
		if(isset($_REQUEST['picaSet_upt']))            //  isset($_REQUEST['picaSet_upt']) saving settings tab values after click on update button
		{
			// $settingColumns =  $wpdb->get_results('SHOW COLUMNS  FROM `wp_pica_settings_menu`');
			$settingColumns = array('sno',     'pica-feat-cols',     'pica-feat-rows',     'pica-feat-photo-width',     'pica-feat-photo-height',     'pica-feat-vspace',     'pica-feat-hspace',     'pica-alb-cols',     'pica-alb-rows',     'pica-alb-photo-width',     'pica-alb-photo-Height',     'pica-alb-vspace',     'pica-alb-hspace',     'pica-general-share-pho',     'pica-general-fac-com',     'pica-general-download',     'pica-general-show-alb',     'pica_facebook_api',     'pica-photo-tumb-w',     'pica-photo-tumb-h',  'pica-photo-vspace','pica-photo-hspace',   'pica-photo-gene-w',     'pica-photo-gene-h');
			$uploaddata = array();
			for( $i = 0 ; $i < count($settingColumns) ; $i++ ){

				$uploaddata[$settingColumns[$i]] = trim($_REQUEST[$settingColumns[$i]]);

			}
			$uploaddata['sno'] = 1;
			$tableName = $wpdb->prefix."pica_settings_menu";
			$wpdb->update( $tableName , $uploaddata , array( 'sno' => 1)  );
			$msg = 'Updated Successfully';

		}
		if( isset($_POST['Photos_Resize'] ))      //resize the images  isset($_POST['picaSet_upt_gener'] )
		{
				
			$pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
			if ($_SERVER["SERVER_PORT"] != "80")
			{
				$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
			}
			else
			{
				$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
			}

				
			$tablename = $wpdb->prefix."pica_settings_menu";
			$sqlQ =  "SELECT `pica-feat-photo-width` ,`pica-feat-photo-height` ,`pica-alb-photo-width`,`pica-alb-photo-Height` ,`pica-photo-tumb-w` ,`pica-photo-tumb-h` ,`pica-photo-gene-w` ,`pica-photo-gene-h`  FROM $tablename " ;
			$settingValues =  $wpdb->get_results($sqlQ ,ARRAY_A );
			// echo "<pre>";print_r($settingValues);echo "<pre>";	exit;
			$colNames = array('pica-feat-photo-width','pica-feat-photo-height','pica-alb-photo-width','pica-alb-photo-Height','pica-photo-tumb-w','pica-photo-tumb-h','pica-photo-gene-w','pica-photo-gene-h' );

			$numOfTimes = count($colNames);
			$findValues = array();
			$updateintable = array();  // use to update in table
			for($i = 0 ; $i < $numOfTimes ; $i++ )
			{
				$isstore =  $settingValues[0][$colNames[$i]] - $_REQUEST[$colNames[$i]]."<br/>";
				$isstore = intval($isstore);
				if($isstore){

					$updateintable[$colNames[$i]] = $_REQUEST[$colNames[$i]] ;
					$findValues[] = $i;
						
				}

			}
			$tableName = $wpdb->prefix."pica_settings_menu";
			$wpdb->update( $tableName , $updateintable , array( 'sno' => 1)  );
			$c = count($findValues);

			if($c)
			{	sort($findValues);
			$changeColNames = array();
				
			for($j = 0 ; $j < $c ; $j++ )
			{

				$changeColNames[] = $colNames[$findValues[$j]];
				//$updateintable[$colNames[$findValues[$j]]] = $_REQUEST[$colNames[$j]] ;

			}
				
				
			$allPhotsData = array();
			for ($i = 0 ; $i< $c ; $i++)
			{
				$value = intval($findValues[$i]);


				if($value % 2 == 0){   // if changed value start with even then
						
					if(in_array($value+1 , $findValues)){   // if he change same photo W & H

						$colW = $_REQUEST[$colNames[$value]];  //taking W value
						$colH = $_REQUEST[$colNames[$value+1]]; // Taking HEIGHT VALU

						$allPhotsData[$i] = array('name' => $changeColNames[$i], 'width' => $colW , 'height' => $colH);
						$i++; 	//deleting height value
					}
					else{   // if he chane only Height
						$colW = $_REQUEST[$colNames[$value]];
						$colH =  $settingValues[0][$colNames[$value+1]];
						$allPhotsData[$i] = array('name' => $changeColNames[$i], 'width' => $colW , 'height' => $colH);

					}
				}
				else{  			// if changed value is odd then
						
					if(in_array( ($value-1) , $findValues)){

						$colW = $_REQUEST[$colNames[$value-1]];
						$colH = $_REQUEST[$colNames[$value]];
						$allPhotsData[$i] = array('name' => $changeColNames[$i], 'width' => $colW , 'height' => $colH);

					}
					else{   $colW =  $settingValues[0][$colNames[$value-1]];
					$colH = $_REQUEST[$colNames[$value]];
					$allPhotsData[$i] = array('name' => $changeColNames[$i], 'width' => $colW , 'height' => $colH);
						
					}
						
				}

			}//for end
				
			}//if end

			//echo "<pre>"; print_r($allPhotsData);	echo "<pre>";
				
			echo "<input type='hidden' value='$site_url' name ='mysiteurl' id='mysiteurl' />  ";
			echo "<input type='hidden' value='$folder' name ='pliginfoulder' id='pliginfoulder' />  ";
			if(is_array($allPhotsData))
			{
				foreach($allPhotsData as $key => $values){
						
					echo '<sapn style = "color: green; display :none; padding: 10px; " id="photoResizeMess" > Please Wait Photos Are Resizeing ... </sapn>';
						
					echo "<script type='text/javascript'>
	  	 photos_regenerate('photoResize','$values[name]', '$values[width]' ,'$values[height]' , '$pageURL' );
	  	 </script>";

				}
			}

				
		}// IF END FOR RESIZE THUMBNILS

		$viewSetting = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "pica_settings_menu" , ARRAY_A);
		
		if ($msg) {
			?>
<div class="updated below-h2"
	style="background-color: lightYellow; border-color: #E6DB55;">
	<p>
	<?php echo $msg; ?>
	</p>
</div>
	<?php } ?>
<div class="clear"></div>



<form name="macSet" method="POST"
	onsubmit="return mac_settings_validation();" action="">
	<input type="hidden" value="" id="picaformnotSumit"
		name="picaformnotSumit" />
	<div class="macSettings">

		<table style="margin-right: 10px;">

			<caption class="header">
			<?php _e('Featured Settings','apptha'); ?>
			</caption>

			<tr>
				<td><span>No of Columns</span></td>
				<td><input type="text" name="pica-feat-cols" id="pica-feat-cols"
					maxlength="2" onblur="checkIsEmpty('pica-feat-cols')"
					onKeyPress="return checkIsNumber(event)"
					value="<?php echo $viewSetting['pica-feat-cols']; ?>"> <span
					style="display: none; color: red;" id="pica-feat-cols-error-msg">Please
						Enter Col Value </span>
				</td>
			</tr>
			<tr>
				<td><span>No of Rows</span></td>
				<td><input type="text" name="pica-feat-rows" id="pica-feat-rows"
					maxlength="2" onblur="checkIsEmpty('pica-feat-rows')"
					onKeyPress="return checkIsNumber(event)"
					value="<?php echo $viewSetting['pica-feat-rows']; ?>"> <span
					style="display: none; color: red;" id="pica-feat-rows-error-msg">Please
						Enter Row Value </span>
				</td>
			</tr>


			<tr>
				<td><span>Size Of Photos</span></td>
				<td>Width&nbsp;<input type="text" name="pica-feat-photo-width"
					id="pica-feat-photo-width" size="3" maxlength="3"
					onblur="checkIsEmpty('pica-feat-photo-width')"
					onfocus="showClickMessage(id)"
					onKeyPress="return checkIsNumber(event)"
					value="<?php echo $viewSetting['pica-feat-photo-width'];  ?>">px
					&nbsp;&nbsp; Height&nbsp;&nbsp;<input type="text"
					id="pica-feat-photo-height" name="pica-feat-photo-height"
					onblur="checkIsEmpty('pica-feat-photo-height')"
					onKeyPress="return checkIsNumber(event)"
					onfocus="showClickMessage(id)" size="3" maxlength="3"
					value="<?php echo $viewSetting['pica-feat-photo-height'];  ?>">px <span
					style="display: none; color: red;"
					id="pica-feat-photo-width-error-msg">Please Enter Width Value </span>
					<span style="display: none; color: red;"
					id="pica-feat-photo-height-error-msg">Please Enter Height Value </span>
				</td>

			</tr>
			<tr>
				<td><span>Vertical Space</span></td>
				<td><input type="text" id="pica-feat-vspace" name="pica-feat-vspace"
					maxlength="3" onblur="checkIsEmpty('pica-feat-vspace')"
					onKeyPress="return checkIsNumber(event)"
					value="<?php echo $viewSetting['pica-feat-vspace'];  ?>">px <span
					style="display: none; color: red;" id="pica-feat-vspace-error-msg">Please
						Enter Value </span>
				</td>

			</tr>
			<tr>
				<td><span>Horizontal Space</span></td>
				<td><input type="text" id="pica-feat-hspace" name="pica-feat-hspace"
					maxlength="3" onblur="checkIsEmpty('pica-feat-hspace')"
					onKeyPress="return checkIsNumber(event)"
					value="<?php echo $viewSetting['pica-feat-hspace'];  ?>">px <span
					style="display: none; color: red;" id="pica-feat-hspace-error-msg">Please
						Enter Height Value </span>
				</td>
			</tr>
		</table>
		<table>

			<caption>General Settings</caption>
			<tr>
				<td><span>Share Photos</span></td>
				<td><?php $flag = 1; ?> <input type="radio" value="1"
					name="pica-general-share-pho" id="pica-general-share-pho"
					<?php if($viewSetting['pica-general-share-pho'])  { echo 'checked="checked" '; $flag = 0;}  ?> />
					Enable &nbsp;&nbsp; <input type="radio" value="0"
					name="pica-general-share-pho" id="pica-general-share-pho"
					<?php if($flag)  { echo 'checked="checked" '; } ?> /> Disable</td>
			</tr>
			<tr>
				<td><span>Show widget area in single photo view </span></td>
				<td><?php $flag = 1; ?> <input type="radio" value="1"
					name="pica-general-fac-com" id="pica-general-fac-com"
					<?php if($viewSetting['pica-general-fac-com'])  { echo 'checked="checked" '; $flag = 0;}  ?> />
					Enable &nbsp;&nbsp; <input type="radio" value="0"
					name="pica-general-fac-com" id="pica-general-fac-com"
					<?php if($flag)  { echo 'checked="checked" '; } ?> /> Disable</td>
			</tr>

			<tr>
				<td><span>Downlaod</span></td>
				<td><?php $flag = 1; ?> <input type="radio" value="1"
					name="pica-general-download" id="pica-general-download"
					<?php if($viewSetting['pica-general-download'])  { echo 'checked="checked" '; $flag = 0;}  ?> />
					Enable &nbsp;&nbsp; <input type="radio" value="0"
					name="pica-general-download" id="pica-general-download"
					<?php if($flag)  { echo 'checked="checked" '; } ?> /> Disable</td>
			</tr>
			<tr>
				<td><span>Show Albums in Featured Page </span></td>
				<td><?php $flag = 1; ?> <input type="radio" value="1"
					name="pica-general-show-alb" id="pica-general-show-alb"
					<?php if($viewSetting['pica-general-show-alb'])  { echo 'checked="checked" '; $flag = 0;}  ?> />
					Enable &nbsp;&nbsp; <input type="radio" value="0"
					name="pica-general-show-alb" id="pica-general-show-alb"
					<?php if($flag)  { echo 'checked="checked" '; } ?> /> Disable</td>
			</tr>
			<tr>
				<td><span> Facebook API ID</span></td>
				<td><input type="text" name="pica_facebook_api"
					id="pica_facebook_api"
					value="<?php echo $viewSetting['pica_facebook_api']; ?> "><strong
					style="color: red; font-size: 11px;" id="facebookerrormsg"></strong>
					<br />
					<div style="font-size: 8pt">
						To create new ID, go to <a
							href="https://developers.facebook.com/apps" target="_new">https://developers.facebook.com/apps
						</a><br />
					</div>
				</td>
			</tr>

		</table>
		<table>
			<!-- Albums Settings  -->

			<caption class="header">
			<?php _e('Albums Settings','apptha'); ?>
			</caption>
			<tr>
				<td><span>Size Of Photos</span></td>
				<td>Width&nbsp; <input type="text" id="pica-alb-photo-width"
					name="pica-alb-photo-width" size="3" maxlength="3"
					onblur="checkIsEmpty('pica-alb-photo-width')"
					onKeyPress="return checkIsNumber(event)"
					onfocus="showClickMessage(id)"
					value="<?php echo $viewSetting['pica-alb-photo-width'];  ?>">px
					&nbsp;&nbsp;Height&nbsp;&nbsp; <input type="text"
					id="pica-alb-photo-Height" name="pica-alb-photo-Height" size="3"
					maxlength="3" onblur="checkIsEmpty('pica-alb-photo-Height')"
					onKeyPress="return checkIsNumber(event)"
					onfocus="showClickMessage(id)"
					value="<?php echo $viewSetting['pica-alb-photo-Height'];  ?>">px <span
					style="display: none; color: red;"
					id="pica-alb-photo-width-error-msg">Please Enter Width Value </span>
					<span style="display: none; color: red;"
					id="pica-alb-photo-Height-error-msg">Please Enter Height Value </span>
				</td>

			</tr>
			<tr>
				<td><span>Vertical Space</span></td>
				<td><input type="text" id="pica-alb-vspace" name="pica-alb-vspace"
					maxlength="3" onblur="checkIsEmpty('pica-alb-vspace')"
					onKeyPress="return checkIsNumber(event)"
					value="<?php echo $viewSetting['pica-alb-vspace'];  ?>">px <span
					style="display: none; color: red;" id="pica-alb-vspace-error-msg">Please
						Enter Value </span>
				</td>

			</tr>
			<tr>
				<td><span>Horizontal Space</span></td>
				<td><input type="text" id="pica-alb-hspace" name="pica-alb-hspace"
					maxlength="3" onblur="checkIsEmpty('pica-alb-hspace')"
					onKeyPress="return checkIsNumber(event)"
					value="<?php echo $viewSetting['pica-alb-hspace'];  ?>">px <span
					style="display: none; color: red;" id="pica-alb-hspace-error-msg">Please
						Enter Value </span>
				</td>
			</tr>
		</table>
    	<table>

			<caption>Photo Settings</caption>
			<tr>
				<td><span>Generate Photo Thumbnail Size</span></td>

				<td>Width&nbsp; <input type="text" id="pica-photo-tumb-w"
					name="pica-photo-tumb-w" onblur="checkIsEmpty('pica-photo-tumb-w')"
					onKeyPress="return checkIsNumber(event)"
					onfocus="showClickMessage(id)" size="3" maxlength="3"
					value="<?php echo $viewSetting['pica-photo-tumb-w'];  ?>">px
					&nbsp;&nbsp;Height&nbsp;&nbsp;<input type="text"
					id="pica-photo-tumb-h" name="pica-photo-tumb-h"
					onblur="checkIsEmpty('pica-photo-tumb-h')"
					onKeyPress="return checkIsNumber(event)"
					onfocus="showClickMessage(id)" size="3" maxlength="3"
					value="<?php echo $viewSetting['pica-photo-tumb-h'];  ?>">px <span
					style="display: none; color: red;" id="pica-photo-tumb-w-error-msg">Please
						Enter Width Value </span> <span style="display: none; color: red;"
					id="pica-photo-tumb-h-error-msg">Please Enter Height Value </span>
				</td>

			</tr>
			<tr>
				<td><span>Vertical Space</span></td>
				<td><input type="text" id="pica-photo-vspace"
					name="pica-photo-vspace" maxlength="3"
					onblur="checkIsEmpty('pica-photo-vspace')"
					onkeypress="return checkIsNumber(event)"
					value="<?php echo $viewSetting['pica-photo-vspace'];  ?>">px <span
					style="display: none; color: red;" id="pica-photo-vspace-error-msg">Please
						Enter Value </span>
				</td>

			</tr>
			<tr>
				<td><span>Horizontal Space</span></td>
				<td><input type="text" id="pica-photo-hspace"
					name="pica-photo-hspace" maxlength="3"
					onblur="checkIsEmpty('pica-photo-hspace')"
					onkeypress="return checkIsNumber(event)"
					value="<?php echo $viewSetting['pica-photo-hspace'];  ?>">px <span
					style="display: none; color: red;" id="pica-photo-hspace-error-msg">Please
						Enter Value </span>
				</td>
			</tr>


			<tr>
				<td><span>Generate Large Photo Size</span></td>

				<td>Width&nbsp; <input type="text" id="pica-photo-gene-w"
					onfocus="showClickMessage(id)"
					onblur="checkIsEmpty('pica-photo-gene-w')"
					onKeyPress="return checkIsNumber(event)" name="pica-photo-gene-w"
					size="3" maxlength="4"
					value="<?php echo $viewSetting['pica-photo-gene-w'];  ?>">px
					&nbsp;&nbsp;Height&nbsp;&nbsp;<input type="text"
					id="pica-photo-gene-h" onfocus="showClickMessage(id)"
					onblur="checkIsEmpty('pica-photo-gene-h')"
					onKeyPress="return checkIsNumber(event)" name="pica-photo-gene-h"
					size="3" maxlength="4"
					value="<?php echo $viewSetting['pica-photo-gene-h'];  ?>">px <span
					style="display: none; color: red;" id="pica-photo-gene-w-error-msg">Please
						Enter Width Value </span> <span style="display: none; color: red;"
					id="pica-photo-gene-h-error-msg">Please Enter Height Value </span>
				</td>

			</tr>
		</table>


		<div align="right">
			<input style="margin-right: 35px; margin-top: 10px;"
				class='button-primary' name='Photos Resize' id='Photos Resize'
				type='submit' value='Photos Resize' onclick="showphotosreziemes()" />
			<input style="margin-right: 35px;" class='button-primary'
				name='picaSet_upt' id='picaSet_upt' type='submit'
				value='Update Options'
				onclick="return facebookApiId('pica_facebook_api')" />

		</div>
		<div style="float: right;">

			<input readonly="readonly" size="10"
				style="margin-right: 40px; display: none; background-color: white; border: none; color: green;"
				name='UpdateMsg' id='UpdateMsg' type="text" value='Click Hear'
				onclick="return facebookApiId('pica_facebook_api')" /> <input
				readonly="readonly" size="50"
				style="margin-right: 30px; display: none; background-color: white; border: none; margin-top: 10px; color: green;"
				name='PhotosResizeMsg' id='PhotosResizeMsg' type="text"
				value='Click Here to Photos Resize' onclick="showphotosreziemes()" />

		</div>
	</div>
</form>

<?php
}
//End of Album Setting page


$lookupObj = array();
$chars_str;
$chars_array = array();

function pica_macgal_generate($domain)
{
	$code=pica_macgal_encrypt($domain);
	$code = substr($code,0,25)."CONTUS";
	return $code;
}

function pica_macgal_encrypt($tkey) {

	$message =  "EW-WPGMP0EFIL9XEV8YZAL7KCIUQ6NI5OREH4TSEB3TSRIF2SI1ROTAIDALG-JW";

	for($i=0;$i<strlen($tkey);$i++){
		$key_array[]=$tkey[$i];
	}
	$enc_message = "";
	$kPos = 0;
	$chars_str =  "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
	for($i=0;$i<strlen($chars_str);$i++){
		$chars_array[]=$chars_str[$i];
	}
	for ($i = 0; $i<strlen($message); $i++) {
		$char=substr($message, $i, 1);

		$offset = pica_macgal_getOffset($key_array[$kPos], $char);
		$enc_message .= $chars_array[$offset];
		$kPos++;
		if ($kPos>=count($key_array)) {
			$kPos = 0;
		}
	}

	return $enc_message;
}
function pica_macgal_getOffset($start, $end) {

	$chars_str =  "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
	for($i=0;$i<strlen($chars_str);$i++){
		$chars_array[]=$chars_str[$i];
	}

	for ($i=count($chars_array)-1;$i>=0;$i--) {
		$lookupObj[ord($chars_array[$i])] = $i;

	}

	$sNum = $lookupObj[ord($start)];
	$eNum = $lookupObj[ord($end)];

	$offset = $eNum-$sNum;

	if ($offset<0) {
		$offset = count($chars_array)+($offset);
	}

	return $offset;
}
/* Function to invoke install player plugin */

function pica_macGallery_installFile()
{

	require_once(dirname(__FILE__) . '/picaDataBaseTables.php');
	picaGallery_install();
}

/* Function to uninstall player plugin */

function pica_macGallery_deinstall()
{
	global $wpdb;
	$table_settings		= $wpdb->prefix . 'pica_settings_menu';
	$table_macAlbum		= $wpdb->prefix . 'picaalbum';
	$table_macPhotos		= $wpdb->prefix . 'picaphotos';
	$table_macGallery		= $wpdb->prefix . 'picagallery';

	echo $sql = "DROP TABLE $table_settings , $table_macAlbum	 ,  $table_macPhotos ,$table_macGallery   ";

	$wpdb->query($sql);
		
	$uploadDir = wp_upload_dir();
	$path = $uploadDir['basedir'].'/pica-photo-gallery/';
	if(is_dir($path)){
		chmod($path , 0777);
		$photos =  opendir($path);

		while($content = readdir($photos)  )
		{
			if($content != '.' && $content != '..') {

				$deleteis = $path.$content;
				unlink($deleteis);
			}
		}

	}
}

/* Function to activate player plugin */
function pica_macGallery_deactivate()
{
	global $wpdb;
	$wpdb->query("DELETE FROM " . $wpdb->prefix . "posts WHERE post_content='[picaGallery]'");
	
}
function pica_macGallery_activate() {

	create_pica_folder();
}
register_activation_hook(plugin_basename(dirname(__FILE__)) . '/picaPluginRoot.php', 'pica_macGallery_installFile');
register_activation_hook(__FILE__, 'pica_macGallery_activate');
register_uninstall_hook(__FILE__, 'pica_macGallery_deinstall');   //click on delete plugin option
register_deactivation_hook(__FILE__, 'pica_macGallery_deactivate');//Click on deactive plugin option 
add_shortcode('picaGallery', 'pica_CONTUS_macRender');
add_filter('the_content', 'pica_Sharemacgallery');
add_action('admin_menu', 'picaPage');
/* Album Listing Page */

 function cssJs() {
      $foulder =  dirname(plugin_basename(__FILE__));
      
       $path = $site_url . '/wp-content/plugins/'.$foulder;
        wp_deregister_script('javaScript');
        wp_register_script('javaScript', $path.'/js/jquery164.js');
        wp_enqueue_script('javaScript');
        
        wp_deregister_script('javaScript1');
        wp_register_script('javaScript1', $path.'/js/macGallery.js');
        wp_enqueue_script('javaScript1');
        
        wp_deregister_script('javaScript2');
        wp_register_script('javaScript2', $path.'/js/facebox.js');
        wp_enqueue_script('javaScript2');
     $path = $site_url . '/wp-content/plugins/' . $foulder . '/pica_Carousel%20with%20autoscrolling_files';
     
        wp_deregister_script('javaScript3');
        wp_register_script('javaScript3', $path.'/jquery.jcarousel.min.js');
        wp_enqueue_script('javaScript3');
        
        
 }
  add_action('init', 'cssJs');
function pica_setplayerscripts() {
	$site_url = get_bloginfo('url');
	$path = $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/pica_Carousel%20with%20autoscrolling_files';
	$foulder =  dirname(plugin_basename(__FILE__));
	
	
	
	?>

<html lang="en" class="ie8 ielt9">
<html lang="en">



<link href=" <?php echo $path; ?>/albslide_style.css" rel="stylesheet"
	type="text/css">


<!--
	  jCarousel skin stylesheet
	-->
<link rel="stylesheet" type="text/css"
	href="<?php echo $path; ?>/skin.css">
<!-- for zoomin or zoomout  -->
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
<link rel="stylesheet" type="text/css"
	href="<?php echo $site_url . '/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/css/facebox.css'; ?>" />
	<?php }
	add_action('wp_head', 'pica_setplayerscripts');
	// Run code and init
	?>