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

?>

<script type="text/javascript" src="<?php echo $site_url; ?>/wp-content/plugins/<?php echo $folder; ?>/js/macGallery.js"></script>

<table cellspacing="0" cellpadding="0" border="1" class="mac_groupgallery">
<tr>
<th class="checkall"><input type="checkbox"  name="checkAll" id="checkAll" class="checkall" onclick="javascript:check_all('all_action', this)"></th>
<th class="name">Gallery Name</th>
<th class="on">Status</th>
<th class="albumid">Gallery Id</th>

</tr>
<?php
$i=0;
$viewSetting = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "macsettings");
$count_result = mysql_query("SELECT * FROM " . $wpdb->prefix . "macgallery");
$site_url = get_bloginfo('url');
$limit =20;
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

$res = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "macgallery ORDER BY macGallery_id DESC $w" );
$album ='';
 $uploadDir = wp_upload_dir();
            $path = $uploadDir['baseurl'].'/pica-photo-gallery';
foreach($res as $results)
{
	$site_url = get_bloginfo('url');
	
   $album .= "<tr>
  <td class='checkall'>";
  
  $album .= "<input type='checkbox' class='checkSing' name='checkList[]' class='others' value='$results->macGallery_id' ></td>";
  


	         $album .="<td class='macName'>
                    <div id='albName_".$results->macGallery_id."'>".$results->macGallery_name."</div>
                    <div class='delView'><a onClick=galNameform($results->macGallery_id) title='Edit' style='cursor:pointer;'>Quick Edit</a></div><span id='showGaledit_$results->macGallery_id'></span>";
                    
        
         $album .="</div>
 </td>";

        if($results->macGallery_status == 'ON')
        {
           $album .= "<td><div name='status_bind' id='galstatus_bind_$results->macGallery_id'  style='text-align:left'><img src='$site_url/wp-content/plugins/$folder/images/tick.png' width='16' height='16' onclick=macGallery_status('OFF',$results->macGallery_id) style='cursor:pointer'  /></div></td>";
        }
        else
        {
           $album .= "<td><div name='status_bind' id='galstatus_bind_$results->macGallery_id'  style='text-align:left'><img src='$site_url/wp-content/plugins/$folder/images/publish_x.png' width='16' height='16' onclick=macGallery_status('ON',$results->macGallery_id) style='cursor:pointer' /></div></td>";
        }
           $album .="<td style='text-align:left'>$results->macGallery_id</td></tr>";

           
 $i++;
}
$album .='</table>';
$pagelist = pageList($_REQUEST['pages'], $pages);
if($count > $limit)
{
$album .='<div align="right">'. $pagelist.'<span><a href="'.$site_url.'/wp-admin/upload.php?page=macAlbum&pages=viewAll">View All</a></span></div>';
}
echo $album;
?>