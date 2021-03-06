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
	$('#subject_dialog').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Ok": function() { 
				$(this).dialog("close"); 
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('#dialog_subject_link').click(function(){
		var param = $(this).attr("returnComp");
		$('#subject_dialog').load('lookup/lookup_com_schedule_subject.php?comp='+param, null);
		$('#subject_dialog').dialog('open');
		return false;
	});

	// Dialog			
	$('#room_dialog').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Ok": function() { 
				$(this).dialog("close"); 
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('#dialog_room_link').click(function(){
		var param = $(this).attr("returnComp");
		$('#room_dialog').load('lookup/lookup_com_schedule_room.php?comp='+param, null);
		$('#room_dialog').dialog('open');
		return false;
	});

	// Dialog			
	$('#prof_dialog').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Ok": function() { 
				$(this).dialog("close"); 
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('#dialog_prof_link').click(function(){
		var param = $(this).attr("returnComp");
		$('#prof_dialog').load('lookup/lookup_com_schedule_prof.php?comp='+param, null);
		$('#prof_dialog').dialog('open');
		return false;
	});	

});	
$(document).ready(function(){  
	
	
	//initialize the tab action
	$('#add_new').click(function(){
		clearTabs();
		$('#add_new').addClass('active');
		$('#view').val('add');
		$("form").submit();
	});
	
	$('#edit_item').click(function(){
		if(checkbox_checker())
		{
			clearTabs();
			$('#add_new').addClass('active');
			$('#view').val('edit');
			$("form").submit();
		}
		else
		{
			alert('No item was selected.');
			updateList();
			return false;
		}
	});
	
	$('#period').click(function(){
		if($('#id').val() != '' )
		{
			validator.resetForm();
			clearTabs();
			$('#period').addClass('active');
			$('#view').val('period');
			$("form").submit();
		}
		else
		{
			validator.resetForm();
			alert('No Template was selected. Please click on the manage template icon under the action column.');
			clearTabs();
			$('#room_list').addClass('active');
			updateList();
			return false;
		}
	});
	
	$('#room_list').click(function(){
		clearTabs();
		$('#room_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	

	$('#save').click(function(){
		if(validateTime())
		{
			clearTabs();
			$('#add_new').addClass('active');
			$('#action').val('save');
			$('#view').val('add');
			$("form").submit();
		}
		else
		{
			alert("End time should not be less than or equal to Start time.");
			$("#time_from, #time_to").val('');
			return false;
		}
	});	
	
	$('#save_temp').click(function(){
		
			clearTabs();
			$('#period').addClass('active');
			$('#action').val('save_temp');
			$('#view').val('period');
			$("form").submit();
		
	});	
	
	$('#update').click(function(){
		if(validateTime())
		{
			clearTabs();
			$('#add_new').addClass('active');
			$('#action').val('update');
			$('#view').val('edit');
			$("form").submit();
		}
		else
		{
			alert("End time should not be less than or equal to Start time.");
			$("#time_from, #time_to").val('');
			return false;
		}
	});		
	// Initialize the list
	<?php
	if($view != 'add' && $view != 'edit' && $view != 'period')
	{
		echo 'updateList();'; 
	}
	?>

	$('#cancel').click(function(){
		clearTabs();
		$('#room_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	
	
	$('#page_rows').change(function(){
		$('#page').val(1);
		updateList();
	});	
	
	$("#time_from,#time_to")
		.bind(
				'change',
				function()
				{
					if($("#time_from").val() != '' && $("#time_to").val() != '')
					{
						if($("#time_from").val() >= $("#time_to").val())
						{
							alert("End time should not be less than or equal to Start time.");
							$("#time_from, #time_to").val('');
						}
					}
				}
			);
	
});	
function validateTime()
{
	if($("#time_from").val() != '' && $("#time_to").val() != '')
	{
		if($("#time_from").val() >= $("#time_to").val())
		{
			$("#time_from, #time_to").val('');
			return false;
		}
		else
		{
			return true;
		}
	}
	else
	{
		return true;
	}
}
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
		
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#page_rows').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}

		
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_schedule_template.php?param=1' + param, null);
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
<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Schedule List"><span>Schedule List</span></a></li>
<li id="add_new" <?=$view=='add'||$view=='edit'?'class="active"':''?>><a href="#" title="Add New"><span>Add / Edit</span></a></li>
<li id="period" <?=$view=='period'?'class="active"':''?>><a href="#" title="Manage Template"><span>Manage Template</span></a></li></ul>
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
		
?>
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
           <legend><strong>Template Information</strong></legend>
          	<label>&nbsp;</label>
             <label>Template Name</label>
            <span ><input class="txt_200 {required:true}" title="Teplate name field is required" name="template_name" type="text" value="<?=$template_name?>" id="template_name" />
            </span><br class="hid" />
            <label>Section Number</label>
            <span ><input class="txt_100 {required:true}" title="Section Number field is required" name="section_no" type="text" value="<?=$section_no?>" id="section_no" />
            </span><br class="hid" />
        </fieldset>
       <fieldset>
           <legend><strong>System Information</strong></legend>
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
        <p id="formbottom"></p>
          
<?php
	}
	if($view=='period')
	{
?>
	<div class="formview">
<?php
		echo $sched;
?>

	 <p class="button_container">
            <input type="hidden" name="num" id="num" value="<?=$num?>" />
            
            <a href="#" class="button" title="Save" id="save_temp"><span>Save</span></a>
            
            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>
        </p>
        </div><!-- /.formview -->
        <p id="formbottom"></p>
<?php
	}
?>


</div>
<input type="hidden" name="filter_field" id="filter_field" value="<?=$filter_field?>" />
<input type="hidden" name="filter_order" id="filter_order" value="<?=$filter_order?>" />
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<input type="hidden" name="page" id="page" value="<?=$page?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>