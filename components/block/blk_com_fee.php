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
$(function(){

	// Dialog			
	$('#dialog').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Select":function() { 
				getselected();
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('#dialog_link').click(function(){
		var param = $(this).attr("returnComp");
		$('#dialog').load('lookup/lookup_com_fee.php?comp='+param, null);
		$('#dialog').dialog('open');
		return false;
	});
	
});	

$(document).ready(function(){  

	var validator = $("#coreForm").validate({
		errorLabelContainer: $("div.error")
	});
	
	$('#school_year_id').change(function(){
		  updateField($(this).val());
	});
	
	$('#filter_schoolterm').change(function(){
		$('#term_id').val($(this).val());
		updateList();
	});	
	
	//initialize the tab action
	$('#add_new').click(function(){
		validator.resetForm();
		clearTabs();
		$('#add_new').addClass('active');
		$('#view').val('add');
		$("form").submit();
	});
	
	$('#edit_item').click(function(){
		if(checkbox_checker())
		{
			validator.resetForm();
			clearTabs();
			$('#edit_item').addClass('active');
			$('#view').val('edit');
			$("form").submit();
		}
		else
		{
			validator.resetForm();
			$('#view').val('list');
			$('#action').val('list');
			alert('No item was selected.');

			updateList();
			return false;
		}
	});
	
	$('#fee_list').click(function(){
		clearTabs();
		$('#fee_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	

	$('#save').click(function(){
		clearTabs();
		$('#add_new').addClass('active');
		$('#action').val('save');
		$('#view').val('add');
		$("form").submit();
	});	
	
	$('#update').click(function(){
		clearTabs();
		$('#edit_item').addClass('active');
		$('#action').val('update');
		$('#view').val('edit');
		$("form").submit();
	});		
	// Initialize the list
	<?php
	if($view != 'add' && $view != 'edit')
	{
		echo 'updateList();'; 
	}
	?>

	$('#cancel').click(function(){
		clearTabs();
		$('#fee_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	
	
	$('#page_rows').change(function(){
		$('#page').val(1);
		updateList();
	});	
	
});	

function clearTabs()
{
	$('ul.tabs li').attr('class',''); // clear all active
}

function updateList(pageNum)
{
	var param = '';
	
	$('#page').val(pageNum);
	if($('#page').val() != '' && $('#page').val() != undefined)
	{
		param = param + '&pageNum=' + $('#page').val();
	}
	
	if($('#filter_field').val() != '' && $('#filter_field').val() != undefined && $('#filter_order').val() != '' && $('#filter_order').val() != undefined )
	{
		param =  param + '&fieldName=' + $('#filter_field').val() + '&orderBy=' + $('#filter_order').val();
		
	}

	if($('#term_id').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#term_id').val();
	}
	
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#page_rows').val() + param;
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
	

	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_fee.php?param=1' + param, null);
}
		
function updateField(schoolYear)
{
		$.ajax({
		type: "POST",
		data: "mod=updateField&id=" + schoolYear,
		url: "ajax_components/ajax_com_date_enrollment_field_updater.php",
		success: function(msg){
			if (msg != ''){
				$("#term_id").html(msg);
			}
		}
		});	
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

<div class="error"></div>

<h2><?=$page_title?></h2>
<ul class="tabs">
<li id="fee_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Building List"><span>Fee List</span></a></li>
<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit Item"><span>Edit Item</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">

<?php

	if($view == 'list')
	{
		if(isset($_SESSION[CORE_U_CODE]['pageRows'])&&($_SESSION[CORE_U_CODE]['pageRows']!=''))
		{
			$p_row = $_SESSION[CORE_U_CODE]['pageRows'];
		}
		else if($_SESSION[CORE_U_CODE]['default_record']!='')
		{
			$p_row = $_SESSION[CORE_U_CODE]['default_record'];
		}
		else
		{
			$p_row = DEFAULT_RECORD;
		}	
		
		if($_SESSION[CORE_U_CODE]['sy_filter'] !='')
		{
			$sy = $_SESSION[CORE_U_CODE]['sy_filter']; 
		}
		else
		{
			$sy = $sy_filter; 
		}
		
?>

<div class="filter">
    Select School Term&nbsp;&nbsp;
    <select name="filter_schoolterm" id="filter_schoolterm" class="txt_200">
    	<?=generateSchoolTerms($sy)?>
    </select>
</div>
     <div id="pageRows">
        <span>show</span>
        <select name="page_rows" id="page_rows">
        <option value="<?=$_SESSION[CORE_U_CODE]['default_record']!='' ? $_SESSION[CORE_U_CODE]['default_record']:DEFAULT_RECORD?>"<?=$p_row==DEFAULT_RECORD||$p_row==$_SESSION[CORE_U_CODE]['default_record'] ? 'selected=selected':''?>>Default</option>
          <option value="10"<?=$p_row==10 ? 'selected=selected':''?>>10</option>
          <option value="20"<?=$p_row==20 ? 'selected=selected':''?>>20</option>
          <option value="50"<?=$p_row==50 ? 'selected=selected':''?>>50</option>
          <option value="100"<?=$p_row==100 ? 'selected=selected':''?>>100</option>
          <option value="150"<?=$p_row==150 ? 'selected=selected':''?>>150</option>            
        </select>
    </div>  
<?php
	} // end of page rows 
?>

<div class="box" id="box_container">
<?php
	if($view == 'edit' || $view == 'add')
	{
?>
	<div class="formview">
    
        <fieldset>
           <legend><strong>Fee Information</strong></legend>
            <label>School Year</label>
            <span >
            <select name="term" id="term" class="txt_200 {required:true}" title="Term field is required" >
                <option value="" selected="selected">Select</option>
                <?=generateSchoolTermsForFees($term_id)?>
            </select>  
            </span><br class="hid" />
            <label>Fee Name:</label>
            <span class="small_input"><input class="txt_200 {required:true}" title="Fee Name field is required" name="fee_name" type="text" value="<?=$fee_name?>" id="fee_name" />
            </span><br class="hid" />
            <label>Amount</label>
            <span><input class="txt_150 {required:true}" title="Amount field is required" name="amount" type="text" value="<?=$amount?>" id="amount" />
            (note:do not put extra characters)
            </span><br class="hid" />
    		<label>Fee Type:</label>
            <span>
    		<select name="fee_type" id="fee_type" class="txt_150 {required:true}" title="Fee Type field is required" >
				 <option value="" selected="selected">Select</option>
                 <option value="perunitlec" <?=$fee_type== "perunitlec"?'selected = "selected"':''?> >Per Unit(Lec)</option>
                 <option value="perunitlab" <?=$fee_type== "perunitlab"?'selected = "selected"':''?> >Per Unit(Lab)</option>
				 <option value="room" <?=$fee_type== "room"?'selected = "selected"':''?> >Room Additional Fee</option>
				 <option value="subject" <?=$fee_type== "subject"?'selected = "selected"':''?> >Subject Additional Fee</option>
				 <option value="mc" <?=$fee_type== "mc"?'selected = "selected"':''?> >Miscellaneous</option> 
            </select>
            </span>
            <br class="hid" />
            
            </span><br class="hid" />           

            <p>
            
            <!-- LIST LOOK UP-->
            <div id="dialog" title="School Year Level List">
                Loading...
            </div><!-- #dialog -->
            
            <span class="clear"></span>
        </fieldset>
        <fieldset>
           <legend><strong>System Information</strong></legend>
			<label>School Year Level:</label>
            <span>
            <div style=" float:left">
            <input class="txt" name="school_year_level" type="hidden" value="<?=$school_year_level?>" id="school_year_level" readonly="readonly" />
            <input class="txt_250 {required:true}" title="School Year Level field is required" name="school_year_level_id_display" type="text" value="<?=$school_year_level_id_display?>" id="school_year_level_id_display" readonly="readonly" />
            </div>
            <div style="width:50px; float:left">
            <a href="#" class="lookup_button" id="dialog_link" returnComp="<?=$_REQUEST['comp']?>"><span>...</span></a><br class="hid" />
            </div>
        	</span><br class="hid" />            
            <label>Publish:</label>
            <span >
                <select name="publish" id="publish" class="txt_150 {required:true}" title="Publish field is required" >
                    <option value="" selected="selected">Select</option>
                    <option value="Y" <?=$publish== "Y"?'selected = "selected"':''?> >Yes</option>
                    <option value="N" <?=$publish== "N"?'selected = "selected"':''?> >No</option>
                </select>   
            </span><br class="hid" /> 
          
            <span class="clear"></span>
        </fieldset>
    
        <p class="button_container">
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
            <?php
            if($view == 'add')
            {
            ?>
                <a href="#" class="button" title="Save" id="save"><span>Save</span></a>
            <?php
            }
            else if($view == 'edit')
            {
            ?>
                <a href="#" class="button" title="Update" id="update"><span>Update</span></a>
            <?php
            }
            ?>
            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>
        </p>
        
    </div><!-- /.formview -->
<?php
	}
?>
<p id="formbottom"></p>
</div>
<input type="hidden" name="filter_field" id="filter_field" value="<?=$filter_field?>" />
<input type="hidden" name="filter_order" id="filter_order" value="<?=$filter_order?>" />
<input type="hidden" name="term_id" id="term_id" value="<?=$term_id==''?CURRENT_TERM_ID:$term_id?>" />
<input type="hidden" name="page" id="page" value="<?=$page?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>