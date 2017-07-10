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
			$('#period_list').addClass('active');
			$('#view').val('list');
			$('#action').val('list');			
			updateList();
			return false;
		}
	});
	
	$('#period_list').click(function(){
		clearTabs();
		$('#period_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
		validator.resetForm();
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
		$('#period_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
		validator.resetForm();
	});	
	
});	

function validateDatePicker(date1,date2)
{
	if(date1!= '' && date2!= '')
	{
		if(date1> date2)
		{
			alert("Start date should not be less than or equal to End date.");
			return false;
		}
	}
}

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
	if($('#filter_termId').val()!='' && $('#filter_termId').val() != undefined)
	{
		param = param + '&term_id=' + $('#filter_termId').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
	$('#box_container').load('ajax_components/ajax_com_school_period.php?list_rows=10' + param, null);
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
					startDate:'2005-31-01',
					endDate:'<?=date('Y')+3?>-31-01'
					
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
			$('#start_of_sub_day option[value=' + selectedDate.getDate() + ']').attr('selected', 'selected');
			$('#start_of_sub_month option[value=' + (selectedDate.getMonth() + 1) + ']').attr('selected', 'selected');
			$('#start_of_sub_year option[value=' + (selectedDate.getFullYear()) + ']').attr('selected', 'selected');
		}
		// listen for when the selects are changed and update the picker
		$('#start_of_sub_day, #start_of_sub_month, #start_of_sub_year')
			.bind(
				'change',
				function()
				{
					var d = new Date(
								$('#start_of_sub_year').val(),
								$('#start_of_sub_month').val()-1,
								$('#start_of_sub_day').val()
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
		$('#start_of_sub_day').trigger('change');
		
		<?php
			// INITIALIZE THE DATE IN EDIT MODE
			// Dates are with '0' simply multiply it to remove it	
	
				$start_of_sub_day = $start_of_sub_day* 1;
				
				$start_of_sub_month = ($start_of_sub_month * 1) - 1;
				
				$start_of_sub_year = $start_of_sub_year;	
					
				// [-] Date fields
				
			if($start_of_sub_year != '')
			{
				echo '$("#date-pick2").dpSetSelected(new Date('.$start_of_sub_year.','. $start_of_sub_month .','.$start_of_sub_day.').asString());';
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
					startDate:'2005-01-01',
					endDate:'<?=date('Y')+3?>-31-01'
					
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
			$('#end_of_sub_day option[value=' + selectedDate.getDate() + ']').attr('selected', 'selected');
			$('#end_of_sub_month option[value=' + (selectedDate.getMonth() + 1) + ']').attr('selected', 'selected');
			$('#end_of_sub_year option[value=' + (selectedDate.getFullYear()) + ']').attr('selected', 'selected');
		}
		// listen for when the selects are changed and update the picker
		$('#end_of_sub_day, #end_of_sub_month, #end_of_sub_year')
			.bind(
				'change',
				function()
				{
					var d = new Date(
								$('#end_of_sub_year').val(),
								$('#end_of_sub_month').val()-1,
								$('#end_of_sub_day').val()
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
		$('#end_of_sub_day').trigger('change');
		
		<?php
			// INITIALIZE THE DATE IN EDIT MODE
			// Dates are with '0' simply multiply it to remove it	
	
				$end_of_sub_day = $end_of_sub_day* 1;
				
				$end_of_sub_month = ($end_of_sub_month * 1) - 1;
				
				$end_of_sub_year = $end_of_sub_year;	
					
				// [-] Date fields
				
			if($end_of_sub_year != '')
			{
				echo '$("#date-pick3").dpSetSelected(new Date('.$end_of_sub_year.','. $end_of_sub_month .','.$end_of_sub_day.').asString());';
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
<li id="period_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Period List"><span>Period List</span></a></li>
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
           <legend><strong>Period Information</strong></legend>
            <label>Period Name:</label>
            <span>
            <input class="txt_250 {required:true}" title="Period Name field is required" name="period_name" type="text" value="<?=$period_name?>" id="period_name" />
    		</span><br class="hid" />
            <label>Select School Term:</label>
            <span>
			    <select name="term_id" id="term_id" class="txt_200 {required:true}" title="Select School Term field is required">
                    <option value="" selected="selected">Select School Year Term</option>
                    <?=generateSchoolTerms($term_id)?>
                </select>
    		</span><br class="hid" />            

            <label>Start of Submission:</label>
            <span >
                <select name="start_of_sub_month" id="start_of_sub_month" class="txt_100 {required:true}" title="Start Date Month field is required">
                    <option value="" selected="selected"></option>
                    <?=generateMonth($start_of_sub_month)?>
                </select>
                <select name="start_of_sub_day" id="start_of_sub_day" class="txt_50 {required:true}" title=" Start Date Day field is required">
                    <option value="" selected="selected"></option>
                    <?=generateDay($start_of_sub_day)?>                                                                        
                </select>
                <select name="start_of_sub_year" id="start_of_sub_year" class="txt_70 {required:true}" title="Start Date Year field is required">
                    <option value="" selected="selected"></option>
                    <?=generateYearForSchoolYear($start_of_sub_year)?>                     
                </select>&nbsp;<a href="noJs.html" id="date-pick2"><img src="images/425irjds.gif" border="0" /></a>
            </span><br class="hid" />
            
            <label>End of Submission:</label>
        <span >
                <select name="end_of_sub_month" id="end_of_sub_month" class="txt_100 {required:true}" title="End Date Month field is required">
                    <option value=""></option>
                    <?=generateMonth($end_of_sub_month)?>
                </select>
                <select name="end_of_sub_day" id="end_of_sub_day" class="txt_50 {required:true}" title="End Date Day field is required">
                    <option value="" selected="selected"></option>
                    <?=generateDay($end_of_sub_day)?>                                                                        
                </select>
                <select name="end_of_sub_year" id="end_of_sub_year" class="txt_70 {required:true}" title="End Date Year field is required">
                    <option value="" selected="selected"></option>
                    <?=generateYearForSchoolYear($end_of_sub_year)?>                                                    
            </select>&nbsp;<a href="noJs.html" id="date-pick3"><img src="images/425irjds.gif" border="0" /></a></span><br class="hid" />
            <span class="clear"></span>
        </fieldset>
        <fieldset>
           <legend><strong>System Information</strong></legend>
            <label>Percentage:</label>
            <span>
                <input class="txt_50 {required:true}" title="Percentage field is required" name="percentage" type="text" value="<?=$percentage?>" id="percentage" /> notes: do not put a percent sign.
            </span><br class="hid" />
            <label>Sort Order:</label>
            <span>
                
                <select name="period_order" id="period_order" class="txt_70 {required:true}" title="Period Order field is required">
                  <option value="" selected="selected" ></option>
                  <option value="1" <?=$period_order=='1'?'selected="selected"':''?>>1</option> 
                  <option value="2" <?=$period_order=='2'?'selected="selected"':''?>>2</option> 
                  <option value="3" <?=$period_order=='3'?'selected="selected"':''?>>3</option> 
                  <option value="4" <?=$period_order=='4'?'selected="selected"':''?>>4</option>                                                  
                </select>  
            </span><br class="hid" />
			<label>Set as Current Period:</label>
            <span>
                
                <select name="is_current" id="is_current" class="txt_70 {required:true}" title="Is Current field is required">
                  <option value="" selected="selected"></option>
                  <option value="N" <?=$is_current=='N'?'selected="selected"':''?>>No</option> 
                  <option value="Y" <?=$is_current=='Y'?'selected="selected"':''?>>Yes</option> 
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
<input type="hidden" name="filter_termId" id="filter_termId" value="<?=$_REQUEST['filter_termId']==''?CURRENT_TERM_ID:$_REQUEST['filter_termId']?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>