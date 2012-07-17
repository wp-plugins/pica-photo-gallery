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
require_once( dirname(__FILE__) . '/macDirectory.php');
//Pagination
function listPagesNoTitle($args) { //Pagination
	if ($args) {
		$args .= '&echo=0';
	} else {
		$args = 'echo=0';
	}
	$pages = wp_list_pages($args);
	echo $pages;
}

function findStart($limit) { //Pagination
	if (!(isset($_REQUEST['pages'])) || ($_REQUEST['pages'] == "1")) {
		$start = 0;
		$_GET['pages'] = 1;
	} else {
		$start = ($_GET['pages'] - 1) * $limit;
	}
	return $start;
}

/*
 * int findPages (int count, int limit)
 * Returns the number of pages needed based on a count and a limit
 */

function findPages($count, $limit) { //Pagination
	$pages = (($count % $limit) == 0) ? $count / $limit : floor($count / $limit) + 1;
	if ($pages == 1) {
		$pages = '';
	}
	return $pages;
}

/*
 * string pageList (int curpage, int pages)
 * Returns a list of pages in the format of "Ã‚Â« < [pages] > Ã‚Â»"
 * */

function pageList($curpage, $pages) {
	//Pagination
	$page_list = "";
	if ($search != '') {

		$self = '?page=' . macAlbum;
	} else {
		$self = '?page=' . macAlbum;
	}

	/* Print the first and previous page links if necessary */
	if (($curpage != 1) && ($curpage)) {
		$page_list .= "  <a href=\"" . $self . "&pages=1\" title=\"First Page\"><<</a> ";
	}

	if (($curpage - 1) > 0) {
		$page_list .= "<a href=\"" . $self . "&pages=" . ($curpage - 1) . "\" title=\"Previous Page\"><</a> ";
	}

	/* Print the numeric page list; make the current page unlinked and bold */
	for ($i = 1; $i <= $pages; $i++) {
		if ($i == $curpage) {
			$page_list .= "<b>" . $i . "</b>";
		} else {
			$page_list .= "<a href=\"" . $self . "&pages=" . $i . "\" title=\"Page " . $i . "\">" . $i . "</a>";
		}
		$page_list .= " ";
	}

	/* Print the Next and Last page links if necessary */
	if (($curpage + 1) <= $pages) {
		$page_list .= "<a href=\"" . $self . "&pages=" . ($curpage + 1) . "\" title=\"Next Page\">></a> ";
	}

	if (($curpage != $pages) && ($pages != 0)) {
		$page_list .= "<a href=\"" . $self . "&pages=" . $pages . "\" title=\"Last Page\">>></a> ";
	}
	$page_list .= "</td>\n";

	return $page_list;
}

/*
 * string nextPrev (int curpage, int pages)
 * Returns "Previous | Next" string for individual pagination (it's a word!)
 */

function nextPrev($curpage, $pages) { //Pagination
	$next_prev = "";

	if (($curpage - 1) <= 0) {
		$next_prev .= "Previous";
	} else {
		$next_prev .= "<a href=\"" . $_SERVER['PHP_SELF'] . "&pages=" . ($curpage - 1) . "\">Previous</a>";
	}

	$next_prev .= " | ";

	if (($curpage + 1) > $pages) {
		$next_prev .= "Next";
	} else {
		$next_prev .= "<a href=\"" . $_SERVER['PHP_SELF'] . "&pages=" . ($curpage + 1) . "\">Next</a>";
	}
	return $next_prev;
}

//End of Pagination
$folder   = dirname(plugin_basename(__FILE__));
$i=0;
$count_result = mysql_query("SELECT * FROM " . $wpdb->prefix . "picaalbum WHERE is_delete = 0");
$site_url = get_bloginfo('url');
$limit = 20;
$start = findStart($limit);
if ($_REQUEST['pages'] == 'viewAll') {
	$w = '';
}
else if(!isset($_REQUEST['pages']))
{
	$w= '';
}
else {
	$w = "LIMIT " . $start . "," . $limit;
}
	
$count = mysql_num_rows($count_result);
/* Find the number of pages based on $count and $limit */
$pages = findPages($count, $limit);
/* Now we use the LIMIT clause to grab a range of rows */

//echo "<pre>"; print_r($res); echo "<pre>";


$album ='';
$uploadDir = wp_upload_dir();
$path = $uploadDir['baseurl'].'/pica-photo-gallery';
?>

<script
	type="text/javascript"
	src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/jquery-ui-1.7.1.custom.min.js"></script>

<script
	type="text/javascript"
	src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/macGallery.js"></script>
<script type="text/javascript">
QueueCountApptha = 0;

    dragdr(document).ready(function(){
    if(document.getElementById('mac-test-list'))
     {
               dragdr("#mac-test-list").sortable({
                handle : '.handle',
                update : function () {
                    var order =dragdr('#mac-test-list').sortable('serialize');
                     dragdr("#info").load(site_url+"/wp-content/plugins/"+mac_folder+"/process-sortable.php?"+order);

                   }

                });
    }
    });
    </script>
 

<table cellspacing="0" cellpadding="0" border="1" class="mac_gallery">

	<thead style="background-color: #DFDFDF;">
	<!-- <th style="width: 5%;">Sort</th>  -->
		<th style="width: 5%;" class="checkall"><input type="checkbox"
			name="checkAll" id="checkAll" class="checkall"
			onclick="javascript:checkAll78('all_action',this)"></th>
		<th style="width: 12%;" class="image">Album Cover</th>
		<th style="width: 13%;" class="name">Album Name</th>
		<th class="desc">Description</th>
		<th style="width:8%;" class="on">Status</th>
		<th style="width:8%;" class="albumid">Album Id</th>
		<th class="gallery"></th>
		<th class="gallery"></th>
	</thead>
	<tbody id="mac-test-list" class="list:post">
	<?php

	$res = $wpdb->get_results("SELECT * ,macGallery_name FROM " . $wpdb->prefix . "picaalbum ," . $wpdb->prefix . "picagallery  where ". $wpdb->prefix . "picagallery.".macGallery_id."=". $wpdb->prefix . "picaalbum." . macGallery_id . " AND is_delete = 0 ORDER BY macAlbum_id DESC" );

	foreach($res as $results)
	{
		$file_image =  $uploadDir['basedir'] . '/pica-photo-gallery/' .$results->macAlbum_image;
		$site_url = get_bloginfo('url');
		$results->macGallery_id = 1;
		//<td class='mac_sort_arrow'><img src='$site_url/wp-content/plugins/$folder/images/arrow.png' alt='move' width='16' height='16' class='handle' /></td>
		$album .= "<tr  id='listItem_$results->macAlbum_id'>
     	
   <td class='checkall'>";

		$album .= "<input type='checkbox' class='checkSing' name='checkList[]' class='others' value='$results->macAlbum_id' ></td>";
		if(file_exists($file_image) && $results->macAlbum_image != '')
		{
			$album .="<td><a href='javascript:void(0)' id='$path/$results->macAlbum_image' class='preview' >
                  <img src='$path/$results->macAlbum_image' width='40' height='20' /></a></td>";
		}
		else if(!file_exists($file_image)){
			$album .="<td><a href='javascript:void(0)' id='$site_url/wp-content/plugins/$folder/uploads/star.jpg' class='preview'>
             <img src='$site_url/wp-content/plugins/$folder/uploads/star.jpg' width='40' height='20' /></a></td>";
		}
		else
		{
			$album .="<td><a href='javascript:void(0)' id='$site_url/wp-content/plugins/$folder/images/default_star.gif' class='preview'>
             <img src='$site_url/wp-content/plugins/$folder/images/default_star.gif' width='40' height='20' /></a></td>";

		}
		$album .="<td class='macName'>
                    <div id='albName_".$results->macAlbum_id."'>".$results->macAlbum_name."</div>
                    <div class='delView'><a onClick=albumNameform($results->macAlbum_id) title='Edit' style='cursor:pointer;'>Quick Edit</a></div></td>";
		$album .="<td style='width:30%'><div id='displayAlbum_".$results->macAlbum_id."' style='text-align:justify' >".$results->macAlbum_description."</div> <span id='showAlbumedit_$results->macAlbum_id'></span>";
		$album .="</div>
 		</td>";

		if($results->macAlbum_status == 'ON')
		{
			$album .= "<td><div name='status_bind' id='status_bind_$results->macAlbum_id'  style='text-align:left'><img src='$site_url/wp-content/plugins/$folder/images/tick.png' width='16' height='16' onclick=macAlbum_status('OFF',$results->macAlbum_id) style='cursor:pointer'  /></div></td>";
		}
		else
		{
			$album .= "<td><div name='status_bind' id='status_bind_$results->macAlbum_id'  style='text-align:left'><img src='$site_url/wp-content/plugins/$folder/uploads/pica_deactive.png' width='16' height='16' onclick=macAlbum_status('ON',$results->macAlbum_id) style='cursor:pointer' /></div></td>";
		}
		$album .="<td style='text-align:left'>$results->macAlbum_id</td> ";

		$album .="<td><a href='$site_url/wp-admin/admin.php?page=picaPhotos&action=viewPhotos&galid=$results->macGallery_id&albid=$results->macAlbum_id'>View Photos</a>
                    </td>";
		$album .="<td>
           <a href='$site_url/wp-admin/admin.php?page=picaPhotos&galid=$results->macGallery_id&albid=$results->macAlbum_id'>Add Photos</a>
        </td>
    </tr>";
			
		$i++;
	} //foreach end hear
	$album .='</tbody></table>';
	$pagelist = pageList($_REQUEST['pages'], $pages);
	if($count > $limit)
	{
		$album .='<div align="right">'. $pagelist.'<span><a href="'.$site_url.'/wp-admin/upload.php?page=picaAlbum&pages=viewAll">View All</a></span></div></table>';
	}

	echo $album;

	?>