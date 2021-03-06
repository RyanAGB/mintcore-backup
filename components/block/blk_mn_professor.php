<?php
/* #############################################################
*	
*					 -- DO NOT REMOVED --
*
* 	  			THIS CODE IS CREATED FOR CORE SIS.
*	  USING IT WITHOUT THE PROPER PERMISSION FROM EGLOBALMD
*			 IS PROHIBITED BUT LIMITED FROM OTHER 
*				  OPEN SOURCE THAT ARE USED.
*
*	------------------------------------------------------------
*	
*	CREATED BY: JBG
*	DATE CREATED: 01 JANUARY 2010
*	FOR ANY ISSUE AND BUG FIXES VISIT http://www.eglobalmd.com
*	OR E-mail support@eglobalmd.com
*
*  ############################################################ */

if(!isset($_REQUEST['comp']))
{
include_once("../../config.php");
include_once("../../includes/functions.php");
include_once("../../includes/common.php");
}
	
if(USER_IS_LOGGED != '1')
	{
		header('Location: ../../index.php');
	}
	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../../forbid.html');
	}

?>

<script type="text/javascript">
	
$(document).ready(function(){ 
	
	// Initialize the list

	$('#search').click(function(){
		clearTabs();
		//$('#edit_item').addClass('active');
		$('#action').val('search');
		//$('#view').val('edit');
		$("form").submit();
	});				
	
});	

function clearTabs()
{
	$('ul.tabs li').attr('class',''); // clear all active
}


</script>

<?php

if($err_msg != '')
{
?>
    <p class="alert">
        <span class="txt"><span class="icon"></span><strong>Alert:</strong> <?=$err_msg?></span>
        <a href="#" class="close" title="Close"><span class="bg"></span>Close</a>
    </p>
<?php
}
?>

<h2><?=$page_title?></h2>
<ul class="tabs">
</ul>

<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<div class="box" id="box_container">

	<div class="formview">
    
        <fieldset>
           <legend><strong>Professor Information</strong></legend>     
          	<label>Employee Code</label>
                <span >
                <input class="txt_200" name="emp_id_number" type="text" value="<?=$emp_id_number?>" id="emp_id_number" />
                </span><br class="hid" />
            <span class="clear"></span>
            <label>Lastname</label>
                <span >
                <input class="txt_300" name="lastname" type="text" value="<?=$lastname?>" id="lastname" />
                </span><br class="hid" />
                <label>Firstname</label>
                <span >
                <input class="txt_300" name="firstname" type="text" value="<?=$firstname?>" id="firstname" />
                </span><br class="hid" /> 
                <label>Middlename</label>
                <span >
                <input class="txt_300" name="middlename" type="text" value="<?=$middlename?>" id="middlename" />
                </span><br class="hid" />
                <label>Filter Way</label>
            <span >
                <select name="filter" id="filter"  class="txt_300">
                   <option value="0">Exact Match</option>
                    <option value="1">First Character Match</option>
                </select>   
            </span><br class="hid" />
        </fieldset>
        
    
        
        <p class="button_container">
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
                <a href="#" class="button" title="Search" id="search"><span>Search</span></a>
           <!-- <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>!-->
        </p>
        
    </div><!-- /.formview -->

<p id="formbottom"></p>
</div>
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>

