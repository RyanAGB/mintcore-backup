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
	$('#dialog_grd').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Close": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
});

$(document).ready(function(){  

	var validator = $("#coreForm").validate({
		errorLabelContainer: $("div.error")
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
			alert('No item was selected.');
			clearTabs();
			$('#school_year_list').addClass('active');
			updateList();
			return false;
		}
	});
	
	$('#school_year_list').click(function(){
		clearTabs();
		$('#school_year_list').addClass('active');
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
		if($('#is_current_sy').val() == 'Y')
		{
			if(confirm("Are you sure you want to set this as the Current School Year?"))
			{
				$("form").submit();
			}
		}
		else
		{
			$("form").submit();
		}
	});	
	
	$('#update').click(function(){
		clearTabs();
		$('#edit_item').addClass('active');
		$('#action').val('update');
		$('#view').val('edit');
		if($('#is_current_sy').val() == 'Y')
		{
			if(confirm("Are you sure you want to set this as the Current School Year?"))
			{
				$("form").submit();
			}
		}
		else
		{
			$("form").submit();
		}
	});		
	
	if($('#view').val()=='pub_grade')
	{
		if(confirm('Cannot set this School year. Current School Year contains students without grades. View student grades?'))
			{
				window.location ='index.php?comp=com_empty_grade';
			}
		else
			{
				return false;
			}
	}
	
	// Initialize the list
	<?php
	if($view != 'add' && $view != 'edit' && $view != 'period')
	{
		echo 'updateList();'; 
	}
	?>

	$('#cancel').click(function(){
		clearTabs();
		$('#school_year_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	
	
	$("#start_year,#end_year")
		.bind(
				'change',
				function()
				{
					if($("#start_year").val() != '' && $("#end_year").val() != '')
					{
						if($("#start_year").val() >= $("#end_year").val())
						{
							alert("School year start date should not be less than or equal to school year end date.");
							$("#start_year, #end_year").val('');
						}
					}
				}
			);
	
});	

function clearTabs()
{
	$('ul.tabs li').attr('class',''); // clear all active
}

function updateList(pageNum)
{
	var param = '';
	if(pageNum != '' && pageNum != undefined)
	{
		param = param + '&pageNum=' + pageNum;
	}
	
	if($('#filter_field').val() != '' && $('#filter_field').val() != undefined && $('#filter_order').val() != '' && $('#filter_order').val() != undefined )
	{
		param =  param + '&fieldName=' + $('#filter_field').val() + '&orderBy=' + $('#filter_order').val();
		
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
	
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_school_year.php?list_rows=10' + param, null);
}

function apply()
{
alert('BEEEE');
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
<li id="school_year_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="College List"><span>School Year List</span></a></li>
<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit Item"><span>Edit Item</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<div class="box" id="box_container">
<?php
	if($view == 'edit' || $view == 'add')
	{
?>
	<div class="formview">
    
        <fieldset>
           <legend><strong>School Year Information</strong></legend>
            <label>School Year Start</label>
            <span>
            <select name="start_year" id="start_year" class="txt_150 {required:true}" title="School Year Start field is required" >
                <option value="" selected="selected">Select</option>
                <?=generateYearForSchoolYear($start_year)?>
            </select>
    		</span><br class="hid" />
            <label>School Year End</label>
			<span>
            <select name="end_year" id="end_year" class="txt_150 {required:true}" title="School Year End field is required" >
                <option value="" selected="selected">Select</option>
                <?=generateYearForSchoolYear($end_year)?>
            </select>
    		</span><br class="hid" />
           
            <span class="clear"></span>

        </fieldset>
        <fieldset>
           <legend><strong>System Information</strong></legend>
			<label>Number of Term</label>
			 <span>
             <select name="number_of_term" id="number_of_term" class="txt_150 {required:true}" title="Number of Term field is required" >
                <option value="" selected="selected">Select</option>
                <option value="1" <?=$number_of_term== "1"?'selected="selected"':''?>>1</option>
                <option value="2" <?=$number_of_term== "2"?'selected="selected"':''?>>2</option>
                <option value="3" <?=$number_of_term== "3"?'selected="selected"':''?>>3</option>
				<option value="4" <?=$number_of_term== "4"?'selected="selected"':''?>>4</option>
            </select>
    		</span><br class="hid" />
			<label>Number of Periods per Term</label>
            <span>
    		<select name="number_of_period" id="number_of_period" class="txt_150 {required:true}" title="Number of Period field is required" >
                <option value="" selected="selected">Select</option>
                <option value="1" <?=$number_of_period== "1"?'selected="selected"':''?>>1</option>
                <option value="2" <?=$number_of_period== "2"?'selected="selected"':''?>>2</option>
                <option value="3" <?=$number_of_period== "3"?'selected="selected"':''?>>3</option>
				<option value="4" <?=$number_of_period== "4"?'selected="selected"':''?>>4</option>
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
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>
<!-- LIST LOOK UP-->
<div id="dialog_grd" title="Student Grades">
    Loading...
</div><!-- #dialog -->