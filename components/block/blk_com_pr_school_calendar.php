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

	var validator = $("#coreForm").validate({
		errorLabelContainer: $("div.error")
	});

	$('#filter_schoolterm').change(function(){
		$('#f_term_id').val($(this).val());
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
			clearTabs();
			$('#room_list').addClass('active');
			$('#view').val('list');
			$('#action').val('list');			
			alert('No item was selected.');
			updateList();
			return false;
		}
	});
	
	$('#cal_news_and_event_list').click(function(){
		clearTabs();
		$('#cal_news_and_event_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
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
		$('#room_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	
	
	$('#page_rows').change(function(){
		updateList();
	});
		
	
	
	
});	

function clearTabs()
{
	$('ul.tabs li').attr('class',''); // clear all active
}

function updateList(pageNum, sched_id)
{
	var param = '';
	if(pageNum != '' && pageNum != undefined)
	{
		param = param + '&pageNum=' + pageNum;
	}
	
	if($('#f_term_id').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#f_term_id').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}

	param = param + '&sched_id=' + sched_id;	
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_pr_school_calendar.php?list_rows=10' + param, null);
}

	function validateDatePicker(date1,date2)
	{
		if(date1!= '' && date2!= '')
		{
			if(date1 > date2)
			{
				alert("Start date should not be less than or equal to End date.");
				
			}
		}
	}
	
var date_start = '';
var date_end = '';
$(function()
	{
		
		// initialise the "Select date" link
		$('#date-pick2')
			.datePicker(
				// associate the link with a date picker
				{
					createButton:false,
					startDate:'1947-31-01',
					endDate:'2020-31-01'
					
				}
			).bind(
				// when the link is clicked display the date picker
				'click',
				function()
				{
					updateSelects($(this).dpGetSelected()[0]);
					$(this).dpDisplay();
					return false;
				}
			).bind(
				// when a date is selected update the SELECTs
				'dateSelected',
				function(e, selectedDate, $td, state)
				{
					updateSelects(selectedDate);
					date_start = selectedDate;
					validateDatePicker(date_start,date_end);
				}
			).bind(
				'dpClosed',
				function(e, selected)
				{
					updateSelects(selected[0]);
				}
			);
			
		var updateSelects = function (selectedDate)
		{
			var selectedDate = new Date(selectedDate);
			$('#s_day option[value=' + selectedDate.getDate() + ']').attr('selected', 'selected');
			$('#s_month option[value=' + (selectedDate.getMonth() + 1) + ']').attr('selected', 'selected');
			$('#s_year option[value=' + (selectedDate.getFullYear()) + ']').attr('selected', 'selected');
		}
		// listen for when the selects are changed and update the picker
		$('#s_day, #s_month, #s_year')
			.bind(
				'change',
				function()
				{
					var d = new Date(
								$('#s_year').val(),
								$('#s_month').val()-1,
								$('#s_day').val()
							);
					$('#date-pick2').dpSetSelected(d.asString());
					date_start = d.asString();
					validateDatePicker(date_start,date_end);
				}
			);
		
		// default the position of the selects to today
		var today = new Date();
		updateSelects(today.getTime());
		
		// and update the datePicker to reflect it...
		$('#s_day').trigger('change');
		
		<?php
			// INITIALIZE THE DATE IN EDIT MODE
			// Dates are with '0' simply multiply it to remove it	
	
				$s_day = $s_day* 1;
				
				$s_month = ($s_month * 1) - 1;
				
				$s_year = $s_year;	
					
				// [-] Date fields
				
			if($s_year != '')
			{
				echo '$("#date-pick2").dpSetSelected(new Date('.$s_year.','. $s_month .','.$s_day.').asString());';
			}
			
		?>
		 
	});
	
	$(function()
	{
		
		// initialise the "Select date" link
		$('#date-pick3')
			.datePicker(
				// associate the link with a date picker
				{
					createButton:false,
					startDate:'1947-01-01',
					endDate:'2020-31-01'
					
				}
			).bind(
				// when the link is clicked display the date picker
				'click',
				function()
				{
					updateSelects($(this).dpGetSelected()[0]);
					$(this).dpDisplay();
					return false;
				}
			).bind(
				// when a date is selected update the SELECTs
				'dateSelected',
				function(e, selectedDate, $td, state)
				{
					updateSelects(selectedDate);
					date_end = selectedDate;
					validateDatePicker(date_start,date_end);
				}
			).bind(
				'dpClosed',
				function(e, selected)
				{
					updateSelects(selected[0]);
				}
			);
			
		var updateSelects = function (selectedDate)
		{
			var selectedDate = new Date(selectedDate);
			$('#e_day option[value=' + selectedDate.getDate() + ']').attr('selected', 'selected');
			$('#e_month option[value=' + (selectedDate.getMonth() + 1) + ']').attr('selected', 'selected');
			$('#e_year option[value=' + (selectedDate.getFullYear()) + ']').attr('selected', 'selected');
		}
		// listen for when the selects are changed and update the picker
		$('#e_day, #e_month, #e_year')
			.bind(
				'change',
				function()
				{
					var d = new Date(
								$('#e_year').val(),
								$('#e_month').val()-1,
								$('#e_day').val()
							);
					$('#date-pick3').dpSetSelected(d.asString());
					date_end = d.asString();
					validateDatePicker(date_start,date_end);
				}
			);
		
		// default the position of the selects to today
		var today = new Date();
		updateSelects(today.getTime());
		
		// and update the datePicker to reflect it...
		$('#e_day').trigger('change');
		
		<?php
			// INITIALIZE THE DATE IN EDIT MODE
			// Dates are with '0' simply multiply it to remove it	
	
				$e_day = $e_day* 1;
				
				$e_month = ($e_month * 1) - 1;
				
				$e_year = $e_year;	
					
				// [-] Date fields
				
			if($e_year != '')
			{
				echo '$("#date-pick3").dpSetSelected(new Date('.$e_year.','. $e_month .','.$e_day.').asString());';
			}
			
		?>
		 
	});


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
    <!--
    <li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="List"><span>List</span></a></li>

    <li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
    
    <li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Add Remarks"><span>Add Remarks</span></a></li>
	-->
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<?php
if($view =='list')
{
?>
<div class="filter">
    Select School Term&nbsp;&nbsp;
    <select name="filter_schoolterm" id="filter_schoolterm" class="txt_200">
    	<?=generateSchoolTerms($filter_schoolterm)?>
    </select>
</div>
<?php
}
?>
<div class="box" id="box_container">
<?php
	if($view == 'edit' || $view == 'add')
	{
		
?>
	<div class="formview">
    
        <fieldset>
        
           <legend><strong>School Calendar Information</strong></legend>
            <label>Title:</label>
            <span>
            <input class="txt_250 {required:true}" title="Title field is required" name="title" type="text" value="<?=$title?>" id="title" />
    		</span><br class="hid" />
            
            <label>Description:</label>
            <span>
            <textarea name="description" cols="50" rows="6" class="txt_250 {required:true}" id="description" title="Description field is required"><?=$description?></textarea>
    		</span><br class="hid" />
            
            <label>Date from:</label>
            <span>
            
            <select name="s_month"  id="s_month" class="txt_100">
				<?=generateMonth($s_month)?>
            </select>
           	<select name="s_day" id="s_day" class="txt_50">
                    <option value="" selected="selected"></option>
                    <?=generateDay($s_day)?>                                                                        
            </select>
            <select name="s_year" id="s_year" class="txt_70">
                <?=generateYearForSchoolYear($s_year)?>
            </select>
            <a href="noJs.html" id="date-pick2"><img src="images/425irjds.gif" border="0" /></a>
            </span><br class="hid" />
            
            <label>Date to:</label>
            <span>
            <select name="e_month"  id="e_month" class="txt_100">
				<?=generateMonth($e_month)?>
            </select>
         
            <select name="e_day" id="e_day" class="txt_50">
                    <option value="" selected="selected"></option>
                    <?=generateDay($e_day)?>                                                                        
                </select>
            <select name="e_year" id="e_year" class="txt_70">
                <?=generateYearForSchoolYear($e_year)?>
            </select>
            <a href="noJs.html" id="date-pick3"><img src="images/425irjds.gif" border="0" /></a>
            </span><br class="hid" />
            
            <span class="clear"></span>

			
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
<?php
	}
?>
<p id="formbottom"></p>
</div>

    
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="f_term_id" id="f_term_id" value="<?=CURRENT_TERM_ID?>" />
<input type="hidden" name="schedule_id" id="schedule_id" value="<?=$schedule_id?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />
<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>