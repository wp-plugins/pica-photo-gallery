<?php
/*
 ***********************************************************/
/**
 * @name          : PICA Photo Gallery.
 * @version	      : 1.3
 * @package       : apptha
 * @subpackage    : PICA Photo Gallery.
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license	      : GNU General Public License version 1 or later; see LICENSE.txt
 * @abstract      : The core file of calling picaPluginRoot.
 * @Creation Date : November 20 2011
 * @Modified Date : July 17 2012
 * */

/*
 ***********************************************************/

/** All DataBase operactions are doing hear for PICA Photo Gallery */
 

function picaGallery_install()
{
	global $wpdb;
	// set tablename settings, albums, photos
	$table_settings		= $wpdb->prefix . 'pica_settings_menu';
	$table_macAlbum		= $wpdb->prefix . 'picaalbum';
	$table_macPhotos	= $wpdb->prefix . 'picaphotos';
	$table_macGallery	= $wpdb->prefix . 'picagallery';
	$sfound = false;
	$afound = false;
	$pfound = false;
	$gfound = false;
	$found = true;
	 
	foreach ($wpdb->get_results("SHOW TABLES;", ARRAY_N) as $row)
	{
		if ($row[0] == $table_settings) $sfound = true;
		if ($row[0] == $table_macAlbum) $afound = true;
		if ($row[0] == $table_macPhotos) $pfound = true;
		if ($row[0] == $table_macGallery) $gfound = true;
	}

	// add charset & collate like wp core
	$charset_collate = '';

	if ( version_compare(mysql_get_server_info(), '4.1.0', '>=') )
	{
		if ( ! empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) )
		$charset_collate .= " COLLATE $wpdb->collate";
	}

	if (!$sfound)
	{
		$sql = " CREATE TABLE IF NOT EXISTS ".$table_settings." (
               		   `sno`  int(2)  NOT NULL DEFAULT '1', 	
					  `pica-feat-cols` int(2) NOT NULL DEFAULT '4',
					  `pica-feat-rows` int(2) NOT NULL DEFAULT '4',
					  `pica-feat-photo-width` int(2) NOT NULL DEFAULT '144',
					  `pica-feat-photo-height` int(2) NOT NULL DEFAULT '144',
					  `pica-feat-vspace` int(2) NOT NULL DEFAULT '6',
					  `pica-feat-hspace` int(2) NOT NULL DEFAULT '6',
					  `pica-alb-cols` int(2) NOT NULL DEFAULT '4',
					  `pica-alb-rows` int(2) NOT NULL DEFAULT '1',
					  `pica-alb-photo-width` int(2) NOT NULL DEFAULT '166',
					  `pica-alb-photo-Height` int(2) NOT NULL DEFAULT '166',
					  `pica-alb-vspace` int(2) NOT NULL DEFAULT '6',
					  `pica-alb-hspace` int(2) NOT NULL DEFAULT '6',
					  `pica-general-share-pho` int(2) NOT NULL DEFAULT '1',
					  `pica-general-fac-com` int(2) NOT NULL DEFAULT '1',
					  `pica-general-download` int(2) NOT NULL DEFAULT '1',
					  `pica-general-show-alb` int(2) NOT NULL DEFAULT '1',
					  `pica_facebook_api` varchar(29) NOT NULL,
					  `pica-photo-tumb-w` int(2) NOT NULL DEFAULT '150',
					  `pica-photo-tumb-h` int(2) NOT NULL DEFAULT '150',
					  `pica-photo-vspace` int(1) NOT NULL DEFAULT '15',
  					  `pica-photo-hspace` int(1) NOT NULL DEFAULT '7',
					  `pica-photo-gene-w` int(2) NOT NULL DEFAULT '600',
					  `pica-photo-gene-h` int(2) NOT NULL DEFAULT '370'
					) ENGINE=InnoDB DEFAULT CHARSET=latin1 ";
			

		$sql1 =  " INSERT INTO ".$table_settings." (`sno` , `pica-feat-cols`, `pica-feat-rows`, `pica-feat-photo-width`, `pica-feat-photo-height`, `pica-feat-vspace`, `pica-feat-hspace`, `pica-alb-cols`, `pica-alb-rows`, `pica-alb-photo-width`, `pica-alb-photo-Height`, `pica-alb-vspace`, `pica-alb-hspace`, `pica-general-share-pho`, `pica-general-fac-com`, `pica-general-download`, `pica-general-show-alb`, `pica_facebook_api`, `pica-photo-tumb-w`, `pica-photo-tumb-h`,`pica-photo-vspace` , `pica-photo-hspace`, `pica-photo-gene-w`, `pica-photo-gene-h`)
			    VALUES (1,4, 4, 144, 144, 6, 6, 4, 1, 166, 166, 6, 6, 1, 1, 1, 1, '', 150, 150 , 15 , 7, 600 , 370)";		    
		$res = $wpdb->query($sql);

		$res = $wpdb->query($sql1);

	}
	 

	if (!$afound)
	{
		$sql = "CREATE TABLE ".$table_macAlbum."  (
		          `macAlbum_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		          `macAlbum_name` varchar(100) NOT NULL,
		          `macAlbum_description` text NOT NULL,
		          `macAlbum_image` varchar(50) NOT NULL,
		          `macAlbum_status` varchar(100) NOT NULL,
		          `macAlbum_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		          `macGallery_id` int(1) NOT NULL DEFAULT '1',
		          `is_delete` int(1) NOT NULL DEFAULT '0'
		          ) $charset_collate;";
		$sql2 = "INSERT INTO $table_macAlbum (`macAlbum_id`, `macAlbum_name`, `macAlbum_description`, `macAlbum_image`, `macAlbum_status`, `macAlbum_date`, `macGallery_id`, `is_delete`) VALUES ('1', 'First Album', 'hello', '', 'ON', CURRENT_TIMESTAMP, '1', '0')";
		$wpdb->query($sql);
		$wpdb->query($sql2);
			
	}
	 

	if (!$gfound)
	{
		$sql = "CREATE TABLE ".$table_macGallery."  (
		          `macGallery_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		          `macGallery_name` varchar(100) NOT NULL,
		          `macGallery_status` varchar(100)
		            ) $charset_collate;";
		$sql2 = "INSERT INTO $table_macGallery (`macGallery_id`, `macGallery_name`, `macGallery_status`) VALUES ('1', 'First Gallery', 'ON')";
		$res = $wpdb->query($sql);
		$res = $wpdb->query($sql2);
	}

	if (!$pfound)
	{

		$sql =  " CREATE TABLE IF NOT EXISTS ".$table_macPhotos." (
			  `macPhoto_id` int(5) NOT NULL AUTO_INCREMENT,
			  `macAlbum_id` int(5) NOT NULL,
			  `macAlbum_cover` varchar(10) NOT NULL,
			  `macFeaturedCover` int(2) NOT NULL DEFAULT '1',
			  `macPhoto_tname` varchar(200) ,
			  `macPhoto_name` varchar(200) NOT NULL,
			  `macPhoto_desc` text NOT NULL,
			  `macPhoto_image` varchar(50) NOT NULL,
			  `macPhoto_status` varchar(10) NOT NULL,
			  `macPhoto_sorting` int(4) NOT NULL,
			  `macPhoto_date` date NOT NULL,
			  `is_active` int(2) NOT NULL DEFAULT '1',
			  `is_delete` int(2) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`macPhoto_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1  " ;
		$res = $wpdb->get_results($sql);
			

	}//if end
	$site_url = get_option('siteurl');  //Getting the site domain path


	$page_found  = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."posts where post_content='[picaGallery]'");
	if (empty($page_found)) {
		$mac_gallery_page    =  "INSERT INTO ".$wpdb->prefix."posts(`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`)
       								 VALUES
				                    (1, NOW(), NOW(), '[picaGallery]', 'Photos', '', 'publish', 'closed', 'open', '', 'pica-gallery', '', '', '2011-01-10 10:42:43',
				                    'NOW()', '','', '$site_url/?page_id=',0, 'page', '', 0)";

		$res_macpage       =  $wpdb->get_results($mac_gallery_page );
		$res_macpage_id    =  $wpdb->get_var("select ID from ".$wpdb->prefix."posts ORDER BY ID DESC LIMIT 0,1");
		$upd_macPage       =  "UPDATE ".$wpdb->prefix."posts SET post_parent='$videoId',guid='$site_url/?page_id=$res_macpage_id' WHERE ID='$res_macpage_id'";
		$rst_updated       =  $wpdb->get_results($upd_macPage);
	}
		
}
function create_pica_folder()  // uploaded photos are stored hear
{
	$structure = dirname(dirname(dirname(__FILE__))).'/uploads/pica-photo-gallery';

	if (is_dir($structure))
	{

	}
	else
	{
		mkdir($structure , 0777);  //if no dir then it create dir
	}
}
?>