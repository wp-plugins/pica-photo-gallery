<?php
/*
 ***********************************************************/
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
require_once( dirname(__FILE__) . '/macDirectory.php');

class macManage {


    function macManage() {

        if ($_REQUEST['action'] == 'editAlbum') {
            updateAlbum();
        } else {
            controller();
        }
    }
}
class simpleimage {

    var $image;
    var $image_type;

    function loads($filename) {

        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
        if ($permissions != null) {
            chmod($filename, $permissions);
        }
    }

    function output($image_type=IMAGETYPE_JPEG) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image);
        }
    }

    function getWidth() {
        return imagesx($this->image);
    }

    function getHeight() {
        return imagesy($this->image);
    }

    function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    /* resizing an image (crop an image) */

    function resize($width, $height) {
        $imgwidth = $this->getWidth();
        $imgheight = $this->getHeight();
        $source_aspect_ratio = $imgwidth / $imgheight;
        $desired_aspect_ratio = $width / $height;

        /* Triggered when source image is wider */
        if ($source_aspect_ratio > $desired_aspect_ratio) {
            $temp_height = $height;
            $temp_width = (int) ( $height * $source_aspect_ratio );
        } else { /* Triggered otherwise (i.e. source image is similar or taller) */
            $temp_width = $width;
            $temp_height = (int) ( $width / $source_aspect_ratio );
        }

        /* Resize the image into a temporary image */
        $temp_gdim = imagecreatetruecolor($temp_width, $temp_height);
        imagecopyresampled($temp_gdim, $this->image, 0, 0, 0, 0, $temp_width, $temp_height, $imgwidth, $imgheight);

        /* Copy cropped region from temporary image into the desired image */
        $x0 = ( $temp_width - $width ) / 2;
        $y0 = ( $temp_height - $height ) / 2;

        $desired_gdim = imagecreatetruecolor($width, $height);
        imagecopy($desired_gdim, $temp_gdim, 0, 0, $x0, $y0, $width, $height);
        $this->image = $desired_gdim;
    }

}

function controller() {
        global $wpdb, $site_url, $folder;
        $site_url = get_bloginfo('url');
        $folder   = dirname(plugin_basename(__FILE__));
        $pageURL  = $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        $split_title = $wpdb->get_var("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name='get_pica_title_key'");
        $get_title = unserialize($split_title);
        $strDomainName = $site_url;
        preg_match("/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
        preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder[2], $matches);
        $customerurl = $matches['domain'];
        $customerurl = str_replace("www.", "", $customerurl);
        $customerurl = str_replace(".", "D", $customerurl);
        $customerurl = strtoupper($customerurl);
        $get_key     = pica_macgal_generate($customerurl);
  
    $mac_album_count = 1;
    
    if (isset($_REQUEST['doaction_album']))
     {
        if (isset($_REQUEST['action_album']) == 'delete')
         {
            for ($i = 0; $i < count($_POST['checkList']); $i++)
            {
                $macAlbum_id = $_POST['checkList'][$i];
                
               $wpdb->query("UPDATE  " . $wpdb->prefix . "picaalbum  SET is_delete = 1 WHERE  macAlbum_id= $macAlbum_id" );
       
            }
            $msg = 'Album/s Deleted Successfully';
        }
    }
?>
    <link rel='stylesheet' href='<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder ?>/css/style.css' type='text/css' />
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/macGallery.js"></script>
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/mac_preview.js" ></script>
    <script type="text/javascript">

        var site_url = '<?php echo $site_url; ?>';
        var url = '<?php echo $site_url; ?>';
        var mac_folder = '<?php echo $folder; ?>';
        var pages  = '<?php echo $_REQUEST['pages']; ?>';
        var get_title = '<?php echo $get_title['title'];?>';
        var title_value = '<?php echo $get_key ?>';
        var dragdr = jQuery.noConflict();
         dragdr(document).ready(function(dragdr) {
       // macAlbum(pages)
         });
    </script>
    <script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/jquery-pack.js'; ?>" type="text/javascript"></script>
<link href="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/css/facebox_admin.css';?>" media="screen" rel="stylesheet" type="text/css" />
<script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/facebox_admin.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/jquery.colorbox.js';?>"></script>
<script type="text/javascript">
var apptha2= jQuery.noConflict();
apptha2(document).ready(function(apptha2) {
  apptha2('a[rel*=facebox]').facebox()
})
    
</script>
<script type="text/javascript">


        function checkAll78(frm,chkall)
        {
          
            var j=0;
            comfList123 = document.forms[frm].elements['checkList[]'];
            checkAll = (chkall.checked)?true:false; // what to do? Check all or uncheck all.

            // Is it an array
            if (comfList123.length) {
                if (checkAll) {
                    for (j = 0; j < comfList123.length; j++) {
                        comfList123[j].checked = true;
                    }
                }
                else {
                    for (j = 0; j < comfList123.length; j++) {
                        comfList123[j].checked = false;
                    }
                }
            }
            else {
                /* This will take care of the situation when your
    checkbox/dropdown list (checkList[] element here) is dependent on
    a condition and only a single check box came in a list.
                 */
                if (checkAll) {
                    comfList123.checked = true;
                }
                else {
                    comfList123.checked = false;
                }
            }

            return;
        }
     var dragdr = jQuery.noConflict();
        jQuery(function(){
            dragdr("#macAlbum_submit").click(function() {
                // Made it a local variable by using "var"
                var macAlbum_name = document.getElementById("macAlbum_name").value;
                var macGallery_name = document.getElementById("macGallery_name").value;
                if(macGallery_name == "0"){
                    document.getElementById("error_glry").innerHTML = 'Please Select Any Gallery ';
                    return false;
                }
                else if(macAlbum_name == ""){
                    document.getElementById("error_alb").innerHTML = 'Please Enter the Album ';
                    return false;
                }
                else if(get_title != title_value && <?php echo $mac_album_count?> != 0 )
                    {
                       /* dragdr(document).ready(function($) {
                        dragdr('a[rel*=oops]').facebox();
                         });
                       */
                    }

            });
        });


        function vaildateAlbumFields(){
             alert('error');
             return false;
        var albumName = document.getElementById("macAlbum_name").value;
        albumName = albumName.trim();
        if(albumName == ''){
        	 document.getElementById("error_alb_name").innerHTML = 'Please Enter the Album Name ';
        	 document.getElementById("macAlbum_name").focus();
        	 return false;
            }
        else{
        	document.getElementById("error_alb_name").innerHTML = '';
            }
        return true;
            
     }
    </script>

    <div class="wrap nosubsub"><div id="icon-upload" class="icon32"><br /></div>
       
        
         <h3 style="float:left;width:200px;padding-top: 10px">Add New Album</h3>
         <?php
      
         
if (isset($_POST['macAlbum_submit']))
        {
            $uploadDir = wp_upload_dir();
            $path = $uploadDir['basedir'].'/pica-photo-gallery';
        
          if($get_title['title'] == $get_key || $mac_album_count >= 1)
          {
          $macAlbum_name        = filter_input(INPUT_POST, 'macAlbum_name');
          $macAlbum_description = filter_input(INPUT_POST, 'macAlbum_description');
            $current_image        = $_FILES['macAlbum_image']['name'];
            $macGallery_id =  1; filter_input(INPUT_POST, 'macGallery_name');
            $get_albname =  $wpdb->get_var("SELECT macAlbum_name FROM " . $wpdb->prefix . "picaalbum WHERE macAlbum_name like '%$macAlbum_name%' AND is_delete = 0 ");
            //print_r($get_albname);
           
            if(!$get_albname)
            {
            $sql1 = "INSERT INTO " . $wpdb->prefix . "picaalbum
                    (`macAlbum_name`, `macAlbum_description`,`macAlbum_image`,`macAlbum_status`,`macAlbum_date`,`macGallery_id` , `is_delete`) VALUES
                    ('$macAlbum_name', '$macAlbum_description','',               'ON',             NOW(),        $macGallery_id  , 0)";
            $sql = $wpdb->query($sql1);
    
            }
            else
            {
                echo "<script> alert('Album name already exist');</script>";
            }
         
      }
      else
      {
       echo '<div class="mac-error_msg">Album Created successfully</div>';
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

         if($get_title['title'] != $get_key)
        {
        ?>
      <p><a href="#mydiv" rel="facebox"><img src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/images/licence.png'?>" align="right"></a>
 <a href="http://www.apptha.com/shop/checkout/cart/add/product/68" target="_blank"><img src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/images/buynow.png'?>" align="right" style="padding-right:5px;"></a>
</p> 
 

<div id="mydiv" style="display:none;width:500px;background:#fff;">
<form method="POST" action=""  onSubmit="return validateKey()">
    <h2 align="center">License Key</h2>
   <div align="center"><input type="text" name="get_license" id="get_license" size="58" value="" />
   <input type="submit" name="submit_license" id="submit_license" value="Save"/></div>
</form>
</div>

<script>
   
    function validateKey()
           {
        	   var Licencevalue = document.getElementById("get_license").value;
                   if(Licencevalue == "" || Licencevalue !="<?php echo $get_key ?>"){
            	   alert('Please enter valid license key');
            	   return false;
        	   }
                   else
                       {
                            alert('Valid License key is entered successfully');
            	           return true;
                       }

           }
</script>

<?php } //else { ?>
 <div class="clear"></div>
 <?php if ($msg) {
 ?>
            <div  class="updated below-h2">
                <p><?php echo $msg; ?></p>
            </div>
<?php } ?>
 <div name="form_album" name="left_content" class="left_column">
            <form name="macAlbum" method="POST" id="macAlbum" enctype="multipart/form-data"  ><div class="form-wrap">

                    <div class="form-macAlbum">
                        <label for="macAlbum_name"  style="cursor: none;">Album Name</label>
                        <input name="macAlbum_name" id="macAlbum_name" type="text" value="" size="40" aria-required="true" />
                        <div id="error_alb_name" style="color:red"></div>
                        <p><?php _e('The album name is how it appears on your site.'); ?></p>
                        
                    </div>

                    <div class="form-macAlbum">
                        <label for="macAlbum_description" style="cursor: none;" >Album Description</label>
                        <textarea name="macAlbum_description" id="macAlbum_description" rows="5" cols="30"></textarea>
                        <p><?php _e('The description is for the album.'); ?></p>
                    </div>
                    <!-- Gallery functionality written by Ishak -->
                 <!--    <div class="form-macAlbum">
                    <label for="macAlbum_image">Select Gallery</label>


						<select name="macGallery_name" id="macGallery_name">
							<option value="0">-- Select Gallery Here --</option>

							<?php
							$albRst = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "picagallery");
							foreach ($albRst as $albRsts) {
							?>
							<option value="<?php echo $albRsts->macGallery_id; ?>"><?php echo $albRsts->macGallery_name; ?></option>

							<?php
							}
							?>
						</select>

                    <p><?php _e('Select any gallery for the album.'); ?></p>
                    <div id="error_glry" style="color:red"></div>
                </div>
				 -->
    <!--  <p class="submit"><a href="#oops" rel="oops">
    	<input type="submit" class="button" name="macAlbum_submit" id="macAlbum_submit" value="<?php echo 'Add new Album'; ?>" /></a>
    </p>
     -->
    
<p class="submit"><input type="submit" onclick="return vaildateAlbumFields1()" class="button" name="macAlbum_submit" id="macAlbum_submit1" value="<?php echo 'Add new Album'; ?>" /></a></p>

            </div></form>
    </div>
<?php //} ?>
    <div class="right_column">
                       <form name="all_action" id="all_action" action="" method="POST" onSubmit="return deleteAlbums();" ><div class="alignleft actions">
                          
                        <select name="action_album" id="action_album">
                            <option value="" selected="selected"><?php _e('Bulk Actions'); ?></option>
                            <option value="delete"><?php _e('Delete'); ?></option>
                           
                        </select>
                        <input type="submit" value="<?php esc_attr_e('Apply'); ?>" name="doaction_album" id="doaction_album" class="button-secondary action"  onclick="return checkemptyaction('all_action')" />
                        <span id="ApplybutErroMsg" style="color: red;" ></span>	
                        <?php wp_nonce_field('bulk-tags'); ?>
            </div>
<script type="text/javascript">
function checkemptyaction(formnameis){

	if(!document.getElementById('action_album').selectedIndex){
		document.getElementById('ApplybutErroMsg').innerHTML = 'Please select Action';
		
		return false;
	}
	
	chks = document.getElementsByName('checkList[]');
	//alert(chks.length);
	var hasChecked = false;
	for (var i = 0; i < chks.length; i++)
	{
		if (chks[i].checked)
		{
		hasChecked = true;
		break;
		}
	}
	if(!hasChecked)
	 { 
		document.getElementById('ApplybutErroMsg').innerHTML = 'Please Select Check box'; 
		
		 return false;
	 }	 
	else
	{  document.getElementById('ApplybutErroMsg').innerHTML = '';
	}	
	return true;
}

</script>
          <?php  
               require_once( dirname(__FILE__).'/macalblist.php');?>
            <script type="text/javascript">
            function vaildateAlbumFields1(){

            	 var albumName = document.getElementById("macAlbum_name").value;
                 albumName = albumName.trim();
                 if(albumName == ''){
                	 document.getElementById("macAlbum_name").value = '';
                 	 document.getElementById("error_alb_name").innerHTML = 'Please Enter the Album Name ';
                 	 document.getElementById("macAlbum_name").focus();
                 	 return false;
                     }
                 else{
                 	document.getElementById("error_alb_name").innerHTML = '';
                     }
                 return true;
                 
             }
				function deleteAlbums(){
					if(document.getElementById('action_album').selectedIndex == 1)
					{
						var album_delete= confirm('Are you sure to delete album/s ?');
						if (album_delete){
							return true;
						}
						else{
							return false;
						}
					}
					else if(document.getElementById('action_album').selectedIndex == 0)
					{
					return false;
					}

				}
				</script>
				</form> 
    </div>

</div>

<?php
   }
?>