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
        $split_title = $wpdb->get_var("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name='get_title_key'");
        $get_title = unserialize($split_title);
        $strDomainName = $site_url;
        preg_match("/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
        preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder[2], $matches);
        $customerurl = $matches['domain'];
        $customerurl = str_replace("www.", "", $customerurl);
        $customerurl = str_replace(".", "D", $customerurl);
        $customerurl = strtoupper($customerurl);
        $get_key     = macgal_generate($customerurl);
    $macSet   = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macsettings");
    $mac_album_count = $wpdb->get_var("SELECT count(*) FROM " . $wpdb->prefix . "macalbum");
    
    if (isset($_REQUEST['doaction_gallery']))
     {
        if (isset($_REQUEST['action_gallery']) == 'delete')
         {
            for ($i = 0; $i < count($_POST['checkList']); $i++)
            {
                $macGallery_id = $_POST['checkList'][$i];
                
                $delete = $wpdb->query("DELETE FROM " . $wpdb->prefix . "macgallery WHERE macGallery_id='$macGallery_id'");
                //define(upload, "$path/");
            }
            $msg = 'Gallery/s Deleted Successfully';
        }
    }
?>
    <link rel='stylesheet' href='<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder ?>/css/style.css' type='text/css' />
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/macGallery.js"></script>

    <script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/main.js" ></script>
    <script type="text/javascript">

        var site_url = '<?php echo $site_url; ?>';
        var url = '<?php echo $site_url; ?>';
        var mac_folder = '<?php echo $folder; ?>';
        var pages  = '<?php echo $_REQUEST['pages']; ?>';
        var get_title = '<?php echo $get_title['title'];?>';
        var title_value = '<?php echo $get_key ?>';
        var dragdr = jQuery.noConflict();
         dragdr(document).ready(function(dragdr) {
        	 macGallery(pages)
         })
    </script>
    <script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/jquery-pack.js'; ?>" type="text/javascript"></script>
<link href="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/css/facebox_admin.css';?>" media="screen" rel="stylesheet" type="text/css" />
<script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/facebox_admin.js'; ?>" type="text/javascript"></script>
<script src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/js/jquery.colorbox.js';?>"></script>

<script type="text/javascript">

        function check_all(frm, chAll)
        {
            var i=0;
            comfList = document.forms[frm].elements['checkList[]'];
            checkAll = (chAll.checked)?true:false; // what to do? Check all or uncheck all.
            // Is it an array
            if (comfList.length) {
                if (checkAll) {
                    for (i = 0; i < comfList.length; i++) {
                        comfList[i].checked = true;
                    }
                }
                else {
                    for (i = 0; i < comfList.length; i++) {
                        comfList[i].checked = false;
                    }
                }
            }
            else {
                /* This will take care of the situation when your
    checkbox/dropdown list (checkList[] element here) is dependent on
                a condition and only a single check box came in a list.
                 */
                if (checkAll) {
                    comfList.checked = true;
                }
                else {
                    comfList.checked = false;
                }
            }

            return;
        }
        var dragdr = jQuery.noConflict();
        jQuery(function(){
            dragdr("#macAlbum_submit").click(function() {
                // Made it a local variable by using "var"
                var macAlbum_name = document.getElementById("macAlbum_name").value;
                if(macAlbum_name == ""){
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
    </script>

    <div class="wrap nosubsub"><div id="icon-upload" class="icon32"><br /></div>
        
         <h3 style="float:left;width:200px;padding-top: 10px">Add New Gallery</h3>
         <?php
if (isset($_REQUEST['macGallery_submit']))
        {
        
            $macGallery_name = filter_input(INPUT_POST, 'macGallery_name');
            $sql = $wpdb->query("INSERT INTO " . $wpdb->prefix . "macgallery
           (`macGallery_name`, `macGallery_status`)
           VALUES('$macGallery_name', 'ON')");
                
       echo '<div class="mac-error_msg">Gallery Created successfully</div>';
    }
   
$options = get_option('get_title_key');
if ( !is_array($options) )
{
  $options = array('title'=>'', 'show'=>'', 'excerpt'=>'','exclude'=>'');
}
if(isset($_POST['submit_license']))
    {
       $options['title'] = strip_tags(stripslashes($_POST['get_license']));

       update_option('get_title_key', $options);
    }

           if($get_title['title'] != $get_key)
        {
        ?>
    <p><a href="#mydiv" rel="facebox"><img src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/images/licence.png'?>" align="right"></a>
 <a href="http://www.apptha.com/Wordpress" target="_blank"><img src="<?php echo $site_url . '/wp-content/plugins/'.$folder.'/images/buynow.png'?>" align="right" style="padding-right:5px;"></a>
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
<div id="oops" style="display:none">
<p><strong>Oops! you will not be able to create more than one album with the free version.</strong></p>
<p>However you can play with the default album</p>
<ul>
    <li> - You can add n number of photos to the default album</li>
    <li> - You can rename the default photo album</li>
    <li> - You can use widgets to show the photos from the default album</li>
</ul>
<p>Please purchase the <a href="http://www.apptha.com/Wordpress" target="_blank">license key</a> to use complete features of this plugin.</p>
</div>
<?php } //else { ?>
 <div class="clear"></div>
 <?php if ($msg) {
 ?>
            <div  class="updated below-h2">
                <p><?php echo $msg; ?></p>
            </div>
<?php } ?>
 <div name="form_album" name="left_content" class="left_column">
            <form name="macAlbum" method="POST" id="macAlbum" enctype="multipart/form-data" onsubmit="return validateGallery();" ><div class="form-wrap">

                    <div class="form-macAlbum">
                        <label for="macGallery_name">Gallery Name</label>
                        <input name="macGallery_name" id="macGallery_name" type="text" value="" size="40" aria-required="true" />
                        <div id="error_alb" style="color:red"></div>
                        <p><?php _e('The gallery name is how it appears on your site.'); ?></p>
                        <div id="error_glry" style="color:red"></div>
                    </div>

                    

                <p class="submit"><a href="#oops" rel="oops">
<input type="submit" class="button" name="macGallery_submit" id="macGallery_submit" value="<?php echo 'Add new gallery'; ?>" /></a></p>
            </div></form>
    </div>
<?php //} ?>
<script type="text/javascript">
function validateGallery()
{
	   var Galleryvalue = document.getElementById("macGallery_name").value;
	   Galleryvalue=Galleryvalue.trim();
	   if(Galleryvalue == ""){
		   document.getElementById("error_glry").innerHTML ='Please enter gallery name';
 	   return false;
	   }

}
</script>
    <div name="right_content" class="right_column">
                       <form name="all_action"  action="" method="POST" onSubmit="return deleteAlbums();" ><div class="alignleft actions">
                           <?php if($get_title['title'] == $get_key) {?>
                        <select name="action_gallery" id="action_gallery">
                            <option value="" selected="selected"><?php _e('Bulk Actions'); ?></option>
                            <option value="delete"><?php _e('Delete'); ?></option>
                        </select>
                        <input type="submit" value="<?php esc_attr_e('Apply'); ?>" name="doaction_gallery" id="doaction_gallery" class="button-secondary action" />
                         <?php }?>  <?php wp_nonce_field('bulk-tags'); ?>
            </div>
            <div id="bind_macGallery" name="right_content" ></div>
            <script type="text/javascript">
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