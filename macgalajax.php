<?php
/*
 ***********************************************************/
/**
 * @name          : Mac Doc Photogallery.
 * @version	      : 2.4
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
?>
<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/macGallery.js"></script>
<?php require_once( dirname(__FILE__) . '/macDirectory.php');
$site_url = get_bloginfo('url');
$folder   = dirname(plugin_basename(__FILE__));
// Album Status Change
if($_REQUEST['galid'] != '')
{
    $mac_galid   = $_REQUEST['galid'];
    $mac_galStat = $_REQUEST['status'];
    if($_REQUEST['status'] == 'ON')
    {
       $alumImg = $wpdb->query("UPDATE " . $wpdb->prefix . "picagallery SET macGallery_status='ON' WHERE macGallery_id='$mac_galid'");
       echo "<img src='$site_url/wp-content/plugins/$folder/images/tick.png' style='cursor:pointer' width='16' height='16' onclick=macGallery_status('OFF',$mac_galid)  />";
    }
    else
    {
        $alumImg = $wpdb->query("UPDATE " . $wpdb->prefix . "picagallery SET macGallery_status='OFF' WHERE macGallery_id='$mac_galid'");
        echo "<img src='$site_url/wp-content/plugins/$folder/images/publish_x.png' style='cursor:pointer' width='16' height='16' onclick=macGallery_status('ON',$mac_galid)  />";
    }

}
?>