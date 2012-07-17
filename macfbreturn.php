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

class macfb
{
   function fbmacreturn()
   {
     require_once( dirname(__FILE__) . '/macDirectory.php');
     global $wpdb;
        $site_url = get_bloginfo('url');
        $returnfbid =$wpdb->get_var("SELECT ID FROM " . $wpdb->prefix . "posts WHERE post_content= '[fbmaccomments]' AND post_status='publish'"); //Return page id
        $macphid = $_REQUEST['macphid']; // photo id
        $macDis = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "picaphotos WHERE  macPhoto_id='$macphid' and macPhoto_status='ON'"); //select photo
        $mafex  = explode('.',$macDis->macPhoto_image);  //getting original image  extension from the thumb image
        $macorgimg = explode('_',$mafex[0]); //getting original image from the thumb image
          
        $div  = '<div id="fb-root"></div>';
        $div .= "<h3>$macDis->macPhoto_name</h3>";
        $div .=  '<img src="'.$site_url.'/wp-content/plugins/' . dirname(plugin_basename(__FILE__)) . '/uploads/'.$macorgimg[0].'.'.$mafex[1].'" />';
        $div .= '<div id="facebook" align="center">';
        $div .= '<div id="fbcomments">
                   <fb:comments canpost="true" candelete="false" numposts="10" width="750" xid="photo'.'.'.$macphid.'"
                    href="'.$site_url.'/?page_id='.$returnfbid.'&macphid='.$macphid.'" url="'.$site_url.'/?page_id='.$returnfbid.'&macphid='.$macphid.'"
                    title="'.$macDis->macPhoto_name.'"  publish_feed="true">
                   </fb:comments></div>';
        $div .= '</div>';
        echo $div;
      }
}
?>