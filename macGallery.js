/*
 ***********************************************************/
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


/*
 ***********************************************************/

//For Showing the list of Album in adjacent

function macAlbum(pages)
{

if(pages == '')
{
    pages = 1;
}

if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
alert(xmlhttp.responseText);
    	document.getElementById('bind_macAlbum').innerHTML =  xmlhttp.responseText;
        imagePreview();
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/picaAblumsTable.php?pages='+pages,true);
xmlhttp.send();
 }

function macGallery(pages)
{

if(pages == '')
{
    pages = 1;
}

if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('bind_macGallery').innerHTML = xmlhttp.responseText ;
        imagePreview();
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macgallerylist.php?pages='+pages,true);
xmlhttp.send();
 }

 //showing the name edit form
function albumNameform(macAlbum_id)
{
     
 if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('showAlbumedit_'+macAlbum_id).style.display="block";
        document.getElementById('showAlbumedit_'+macAlbum_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macAlbumname_id='+macAlbum_id,true);
xmlhttp.send();

}

//Update the album name
function updAlbname(macAlbum_id)
{

var macAlbum_id = macAlbum_id;
var macAlbum_name   = document.getElementById('macedit_name_'+macAlbum_id).value;
var macAlbum_desc   = document.getElementById('macAlbum_desc_'+macAlbum_id).value;

  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
      document.getElementById('albName_'+macAlbum_id).innerHTML = macAlbum_name;
      document.getElementById('displayAlbum_'+macAlbum_id).innerHTML = macAlbum_desc;
      document.getElementById('showAlbumedit_'+macAlbum_id).style.display="none";
    }
  }
 
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macAlbum_id='+macAlbum_id+'&macAlbum_name='+macAlbum_name+'&macAlbum_desc='+macAlbum_desc,true);
xmlhttp.send();
}
//updating gallery
//showing the name edit form
function galNameform(macGallery_id)
{

 if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('showGaledit_'+macGallery_id).style.display="block";
        document.getElementById('showGaledit_'+macGallery_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macGallery_id='+macGallery_id,true);
xmlhttp.send();

}

//Update the Galery name
function updGalname(macGallery_id)
{
var macGallery_id = macGallery_id;
var macGal_name   = document.getElementById('macgaledit_name_'+macGallery_id).value;
 // alert(macGal_name);

  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
    	//macGallery_name = xmlhttp.responseText;

      document.getElementById('macgaledit_name_'+macGallery_id).innerHTML = macGal_name;

      document.getElementById('showGaledit_'+macGallery_id).style.display="none";
      document.forms["all_action"].submit();
    }
  }
 //alert(macGallery_id+'----------'+macGal_name);
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macGallery_id='+macGallery_id+'&macGallery_name='+macGal_name,true);
xmlhttp.send();
}

//For Changing Mac Album Page id

function albumPageid(macAlbum_id)
{
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('showPageedit_'+macAlbum_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macAlbumpage_id='+macAlbum_id,true);
xmlhttp.send();
}

//For Updating Mac Album Page id

function updPageid(macAlbum_id)
{

var macAlbum_pageid = document.getElementById('macedit_pageid_'+macAlbum_id).value;

 if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
      document.getElementById('macPageid_'+macAlbum_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macAlbum_pageid='+macAlbum_pageid+'&macAlbum_id='+macAlbum_id,true);
xmlhttp.send();
}

//For changing the Album Status
 function macAlbum_status(status,macAlbum_id)
 {
   
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
    	//alert(xmlhttp.responseText);
       document.getElementById('status_bind_'+macAlbum_id).innerHTML = xmlhttp.responseText;
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?status='+status+'&albid='+macAlbum_id,true);
xmlhttp.send();
 }

// View Album description Update
function albumtoggle(macAlbum_id) {
	var albumele = document.getElementById("albumtoggleText_"+macAlbum_id);
	var albumtext = document.getElementById("albumdisplayText_"+macAlbum_id);
	if(albumele.style.display == "block") {
    		albumele.style.display = "none";
		albumtext.innerHTML = "Edit";
  	}
	else {
		albumele.style.display = "block";
		albumtext.innerHTML = "hide";
	}
}

function macAlbumdesc_updt(macAlbum_id)
{
var macAlbum_desc = document.getElementById('macAlbum_desc_'+macAlbum_id).value;
    if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
      document.getElementById('displayAlbum_'+macAlbum_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macAlbum_desc='+macAlbum_desc+'&macAlbum_id='+macAlbum_id,true);
xmlhttp.send();
}
 //For changing the gallery status

 function macGallery_status(status,macGallery_id)
 {

  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
       document.getElementById('galstatus_bind_'+macGallery_id).innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macgalajax.php?status='+status+'&galid='+macGallery_id,true);
xmlhttp.send();
 }

function macdeleteAlbum(macAlbum_id)
{
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        window.location = self.location;
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macphtajax.php?macdelAlbum='+macAlbum_id,true);
xmlhttp.send();
}


//For Showing the Multiple Photo Upload in Adjacent
function macPhotos(numFilesQueued,albid)
{
    var show_pht = document.getElementById('bind_value').value;
    document.getElementById('bind_value').value = Number(show_pht)+Number(numFilesQueued);
    var rst_pht = document.getElementById('bind_value').value;
   
   if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('bind_macPhotos').innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macPhotos.php?queue='+rst_pht+'&albid='+albid,true);
xmlhttp.send(); //picaUploadPhoList.php

 }
//For Delete from the upload images
function macdelAjax(macPhoto_id)
{
     if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('remve_macPhotos_'+macPhoto_id).innerHTML += xmlhttp.responseText;
        
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macDelid='+macPhoto_id,true);
xmlhttp.send();
}
//For showing the name,description box adjacent to photos

 function maceditPhotos(rst)
{
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('edit_macPhotos').innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macphtajax.php?macEdit='+rst,true);
xmlhttp.send();
 }

//Update Name and Description

function upd_disphoto(queue,albid)
{

    for(i=1;i<=queue;i++)
    {
     var macedit_phtid = document.getElementById("macedit_id_"+i).value;
     var macedit_name  = document.getElementById("macedit_name_"+i).value;
     var macedit_desc  = document.getElementById("macedit_desc_"+i).value;
dragdr = jQuery.noConflict();
dragdr.ajax({
    method:"GET",
       url: site_url+'/wp-content/plugins/pica-photo-gallery/macphtajax.php',
       data : "macedit_phtid="+macedit_phtid+"&macedit_name="+macedit_name+"&macedit_desc="+macedit_desc,
       asynchronous:false,
       error: function(html){
    	   //alert(xmlhttp.responseText);
            },
      success: function(html){
    	      // alert(xmlhttp.responseText);
          window.location = site_url+'/wp-admin/admin.php?page=picaPhotos&action=viewPhotos&albid='+albid;
           }
       });
  }
alert('Updated sucessfully');
  //window.location = site_url+'/wp-admin/admin.php?page=picaPhotos&action=viewPhotos&albid='+albid;
}

//Mac Individual Photo Delete after uploaded
 function macdeletePhoto(macdeleteId)
 {
var agree=confirm("Are you sure you want to delete ?");
if (agree)
{
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('photo_delete_'+macdeleteId).style.visibility = 'hidden';
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macphtajax.php?macdeleteId='+macdeleteId,true);
xmlhttp.send();
 }
 }


 function macdesEdit(macPhoto_id)
 {

  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
        document.getElementById('edit_macDesc').innerHTML = xmlhttp.responseText
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macphtajax.php?macphotoDesc_id='+macPhoto_id,true);
xmlhttp.send();
 }
 if(typeof String.prototype.trim !== 'function') {
	  String.prototype.trim = function() {
	    return this.replace(/^\s+|\s+$/g, ''); 
	  }
	}

 //Update the Photo name
function updPhotoname(macPhotos_id)
{
var macPhoto_name = document.getElementById('macPhoto_name_'+macPhotos_id).value;
macPhoto_name = macPhoto_name.trim();
	
	if(macPhoto_name == ''){
	alert('not empty');
	document.getElementById('macPhoto_name_'+macPhotos_id).focus();
	return false;
	}
 if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
       
      document.getElementById('macPhotos_'+macPhotos_id).innerHTML = xmlhttp.responseText;
      document.getElementById('showPhotosedit_'+macPhotos_id).style.display = 'none';
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?macPhoto_name='+macPhoto_name+'&macPhotos_id='+macPhotos_id,true);
xmlhttp.send();
}
// View Photo description Update
function phototoggle(macPhoto_id) {
	var ele = document.getElementById("toggleText"+macPhoto_id);
     	var text = document.getElementById("displayText_"+macPhoto_id);
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		text.innerHTML = "Edit";
  	}
	else {
		ele.style.display = "block";
		
	}
}

function macdesc_updt(macPhoto_id)
{
var macPhoto_desc = document.getElementById('macPhoto_desc_'+macPhoto_id).value;
var ele = document.getElementById("toggleText"+macPhoto_id);
	
    if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {
      document.getElementById('display_txt_'+macPhoto_id).innerHTML = xmlhttp.responseText
      ele.style.display = "none";
    }
  }
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macphtajax.php?macPhoto_desc='+macPhoto_desc+'&macPhoto_id='+macPhoto_id,true);

xmlhttp.send();
}
function macFeatured_status(addCover,albumId,macPhoto_id , flag)  //falg is for featured img select
{
		
			    if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
			   xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			  xmlhttp.onreadystatechange=function()
			  {
			    if (xmlhttp.readyState==4)
			    {   
			    	 document.getElementById('albumFeatured_bind_'+macPhoto_id).innerHTML = xmlhttp.responseText
			    	//alert(xmlhttp.responseText);
			      //window.location = self.location;
			    }
			  }
 // alert('addcover = '+addCover+'  album id = '+ albumId+' mac photo id=  '+macPhoto_id+ '  flag = '+  flag );
			 if( flag ) 
				{  
				 xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?featuredCover='+addCover+'&albumId='+albumId+'&macCovered_id='+macPhoto_id+'&isFlag='+addCover,true);
				} 
			 else
			 { 
				 xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?albumCover='+addCover+'&albumId='+albumId+'&macCovered_id='+macPhoto_id,true);
			  }

xmlhttp.send();
}
function macAlbcover_status(addCover,albumId,macPhoto_id , flag)  //falg is for featured img select
{
		
			    if (window.XMLHttpRequest)
			{// code for IE7+, Firefox, Chrome, Opera, Safari
			   xmlhttp=new XMLHttpRequest();
			}
			else
			{// code for IE6, IE5
			    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			  xmlhttp.onreadystatechange=function()
			  {
			    if (xmlhttp.readyState==4)
			    {    //alert(xmlhttp.responseText);
			    	 document.getElementById('albumCover_bind_'+macPhoto_id).innerHTML = xmlhttp.responseText;
			    	//
			        window.location = self.location;
			    }
			  }
 // alert('addcover = '+addCover+'  album id = '+ albumId+' mac photo id=  '+macPhoto_id+ '  flag = '+  flag );
			 
				 xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?albumCover='+addCover+'&albumId='+albumId+'&macCovered_id='+macPhoto_id+'&changeAlbCover='+1,true);
		

xmlhttp.send();
}



//Photos Status Changing
function macPhoto_status(status,macPhoto_id , albId , sortingvalue)
{
  if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
   xmlhttp=new XMLHttpRequest();
}
else
{// code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4)
    {  //alert( xmlhttp.responseText);
       document.getElementById('photoStatus_bind_'+macPhoto_id).innerHTML = xmlhttp.responseText
       window.location = self.location;
    }
  }
 // alert('/macalbajax.php?status='+status+'&macPhoto_id='+macPhoto_id+'&albIdIs='+albId+'&sortingValue='+sortingvalue);
 
xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/macalbajax.php?status='+status+'&macPhoto_id='+macPhoto_id+'&albIdIs='+albId+'&sortingValue='+sortingvalue,true);
xmlhttp.send();
}
function fbcomments(pid,title,siteurl) {

if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
   httpxml=new XMLHttpRequest();
//alert(xmlhttp);
  }
httpxml.onreadystatechange=function()
  {
  if (httpxml.readyState==4 && httpxml.status==200)
    {
  
    var fbComments = httpxml.responseText;
    document.getElementById("facebook").innerHTML = fbComments;
     getfacebook();
     return false;
    }
  }
httpxml.open("GET",site_url+"/wp-content/plugins/"+mac_folder+"/macfbcomment.php?pid="+pid+'&phtName='+title+'&site_url='+siteurl+'&appId='+appId,true);
httpxml.send();
}
function CancelAlbum(macAlbum_id)
{
    document.getElementById('showAlbumedit_'+macAlbum_id).style.display="none";
}

function CancelGalllery(macGallery_id)
{
    document.getElementById('showGaledit_'+macGallery_id).style.display="none";
}
function showphotosreziemes()
{
	document.getElementById('photoResizeMess').style.display = 'block';
	
	
}
  // resize photos
function photos_regenerate(flagname , resizetype , width , height ,  pageURL)
{
	var site_url = document.getElementById('mysiteurl').value;
	var mac_folder = document.getElementById('pliginfoulder').value;
	document.getElementById('photoResizeMess').style.display = 'block';
	

	 if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	   xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	  xmlhttp.onreadystatechange=function()
	  {
	    if (xmlhttp.readyState==4)
	    {
	     alert('Photos Resized Succesfully');  
	   document.getElementById('photoResizeMess').style.display = 'none';
	     window.location = pageURL;
	   
	    }
	  }
	xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/picaPhotosResize.php?photoThumbGenerate='+flagname+'&photoResize='+resizetype+'&width='+width+'&height='+height,true);
	xmlhttp.send();
	
	
}
function photos_download(photoimgname , pluginname , site_url , flag )
{
    if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	   xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	  xmlhttp.onreadystatechange=function()
	  {
	    if (xmlhttp.readyState==4)
	    {
	     //alert(xmlhttp.responseText);  
	    //  document.getElementById('macPhotos_'+macPhotos_id).innerHTML = xmlhttp.responseText;
	    //  document.getElementById('showPhotosedit_'+macPhotos_id).style.display = 'none';
	    }
	  }
	xmlhttp.open("GET",site_url+'/wp-content/plugins/'+pluginname+'/picadownload.php?imgname='+photoimgname+'&flag='+flag+'&pluginname='+pluginname,true);
	xmlhttp.send();
	
	
}
function changesortingorderinalbum(order , site_url , mac_folder  )
{
	
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	   xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	  xmlhttp.onreadystatechange=function()
	  {
	    if (xmlhttp.readyState==4)
	    {
	    	//alert(xmlhttp.responseText);
	    	//document.getElementById('bind_macAlbum').innerHTML =  xmlhttp.responseText;
	        //imagePreview();
	    }
	  }
	 alert(site_url+'/wp-content/plugins/'+mac_folder+'/process-sortable.php?'+order);
	
	xmlhttp.open("GET",site_url+'/wp-content/plugins/'+mac_folder+'/process-sortable.php?'+order,true);
	xmlhttp.send();
 }

function isNumber (o) {
	  return ! isNaN (o-0);
}

function facebookApiId(apiid)
{
	 var idvalue = document.getElementById(apiid).value;
	 idvalue = idvalue.trim();
	
	 if(idvalue != '' )
	 {
			 //alert(idvalue);
			 if(isNumber(idvalue))
			 {
				
			 }
			 else{
				 document.getElementById('facebookerrormsg').innerHTML = 'Enter Numbers' ;   
				 return false;
			 }
			 
			

	 }		 
	 document.getElementById('facebookerrormsg').innerHTML = '';
	 document.getElementById('picaformnotSumit').value = 0;
	 return true;
	 
}
function checkIsNumber(evt , myid ) {   // settings textbox numbers validation
  evt = (evt) ? evt : window.event
  var charCode = (evt.which) ? evt.which : evt.keyCode
          
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {

      if(myid)
      {
      	 document.getElementById('facebookerrormsg').innerHTML = 'Enter Only Numbers';
      	 document.getElementById('picaformnotSumit').value = 1;
   		
      }
      
      return false;
  }
  else{
	        	if(myid)
	            {
	            	 document.getElementById('facebookerrormsg').innerHTML = '';
	            	 document.getElementById('picaformnotSumit').value = 0;
	         		
	            }
	        	 status = ""
	     	     return true

      }
 
     
}
var flagForMse = 1;
function checkIsEmpty(getid){

	  var textboxval =  document.getElementById(getid).value;
	   textboxval = textboxval.trim();
	  var isshow ;
	 var notSubmit = 1;
	 
	  if(textboxval == resizeval && flagForMse ){
		  //alert(resizeval);
		  document.getElementById('PhotosResizeMsg').style.display = 'none';
	  }
	  else{
		  
		  flagForMse = 0;
	  }
	  if(textboxval == '' ){ 
	    	  
		  		isshow = 'block';
		  		document.getElementById('picaformnotSumit').value = notSubmit;
		  		 document.getElementById(getid+'-error-msg').style.display = isshow;
		  		 document.getElementById(getid).focus();
		  		return false;
	  }
	  else if(textboxval == 0 ){ 
  	  
	  		isshow = 'block';
	  		document.getElementById('picaformnotSumit').value = notSubmit;
	  		 document.getElementById(getid+'-error-msg').style.display = isshow;
	  		 document.getElementById(getid+'-error-msg').value = '';
	  		 document.getElementById(getid).focus();
	  		document.getElementById(getid+'-error-msg').innerHTML = 'Please Enter >0';
	  		return false;
}
	  else{
  	  		isshow = 'none';
  	  		notSubmit = 0;
  	  		document.getElementById('picaformnotSumit').value = notSubmit;
  	  		document.getElementById(getid+'-error-msg').style.display = isshow;
 	  }
	 
}

 function mac_settings_validation()
 {
          // Made it a local variable by using "var"
     var issubmit = parseInt(document.getElementById('picaformnotSumit').value);
     if(issubmit){
						return false;
      }
     else
         	return true;
          
          
 }
     
    var resizeval;
     function showClickMessage(textboxid){

       resizeval =  document.getElementById(textboxid).value;	 
  	   document.getElementById('PhotosResizeMsg').style.display = 'block';

     }



     function showsliding(url ,photonum ,photoid , flag ){
	    	photoid = parseInt(photoid);
	    	photonum = parseInt(photonum);
	    //	alert('photo id '+photoid+'  ---- ph noumb'+photonum);
	        if(flag == 'left'){
	    	photonum = photonum-1;
	    	
	        }
	        
	        if(flag == 'right'){
	        	photonum = photonum+1;
	        	
	        }
	    	
			window.location = url+photoid+'#content';
			
		
		}
		function gotoalbpage(url)
		{
			window.location = url;
		}

		function showsinglephotopage(url , phid , photonum)
		{
		
			url = url+'&pid='+photonum;
			window.location.href = url;
		}
		function showPhotoTitleStyle(idis){
			//alert(document.getElementById('albumname'+idis).className);
			document.getElementById('albumname'+idis).className = "picaPhotoTitleHover";
			
		}
		function dontshowPhotoTitleStyle(idis)
		{
			document.getElementById('albumname'+idis).className = 'picaPhotoTitle ';
			
		}
		function showalbumname(myid){
			
			document.getElementById(myid).style.display = 'block';

}//end of showalbumname		
function dontshowalbumname(myid){
	
		document.getElementById(myid).style.display = 'none';
}



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

				function deleteImages(){
					if(document.getElementById('action_photos').selectedIndex == 3)
					{
						var answer = confirm('Are you sure to delete photo/s ?');
						if (answer){
							return true;
						}
						else{
							return false;
						}
					}
					else if(document.getElementById('action_photos').selectedIndex == 0)
					{
					return false;
					}

				}
				function checkemptyaction(formnameis){

					if(!document.getElementById('action_photos').selectedIndex){
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
				function displaysubaction(){
					 var showsubact = document.getElementById('action_photos').selectedIndex;
					
					if(showsubact == 1 || showsubact == 2 ){
						document.getElementById('ApplybutErroMsg').innerHTML = '';		
						document.getElementById('subaction_photos').style.display = 'block'; 
					}
					else{
						// alert(showsubact+'---'+document.getElementById('subaction_photos').style.display);
						 document.getElementById('ApplybutErroMsg').innerHTML = '';
						}
				}
				function photosNameform(macPhotos_id) {
					 

			        document.getElementById('showPhotosedit_'+macPhotos_id).style.display = 'block';
			   
				}
				
				function checkallPhotos(frm,chkall)
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
		
				function displaySelectedAlbum(albid,pageurl){
					//alert(albid+'---------'+pageurl);
					window.location = pageurl+'albid='+albid;
					
					
				}
				function showalbpage(ispermaurl , albid , phid , flag , widgets )
			   	{
					  //window.location =
					
					if(ispermaurl){  
							if(widgets)
								{
								url = widgets;
								}
							else{		
						 	url = self.location+'?aid=';
							}
						   
					}
					else{
						if(widgets)
						{
						url = widgets;
						}
						else{		
							url = self.location+'&aid=';
						}
					}
					
			   			   		if(phid){ // go to album photos
						   			window.location.href = url+albid+'&pid='+phid;
						   		}
						   		else{
						   			window.location.href = url+albid;
						   		}
			   		}//function end
  