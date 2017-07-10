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
		$('#dialog').load('lookup/lookup_com_date_enrollment.php?comp='+param, null);
		$('#dialog').dialog('open');
		return false;
	});
	
});	
$(document).ready(function(){  
	
	var validator = $("#coreForm").validate({
		errorLabelContainer: $("div.error")
	});

	$('#filter_schoolterm').change(function(){
		$('#sy_filter').val($('#filter_schoolterm').val());
		updateList();
	});
	//initialize the tab action
	$('#school_year_id').change(function(){
		  updateField($(this).val());
	});
	
	$('#add_new').click(function(){
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
		$('#page').val(1);
		updateList();
	});	

});	
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

	if($('#filter_schoolterm').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#filter_schoolterm').val();
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
	$('#box_container').load('ajax_components/ajax_com_entrance_examination.php?param=1' + param, null);
}
		
function updateField(schoolYear)
{
		$.ajax({
		type: "POST",
		data: "mod=updateField&id=" + schoolYear,
		url: "ajax_components/ajax_com_fee_field_updater.php",
		success: function(msg){
			if (msg != ''){
				$("#term_id").html(msg);
			}
		}
		});	
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
<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Room List"><span>Enrollment Date List</span></a></li>
<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit Item"><span>Edit Item</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">

<?php
	if($view == 'list')
	{
		if($_SESSION[CORE_U_CODE]['pageRows']!='')
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
           <legend><strong>Examination Date Information</strong></legend>
    <label>Examination Date:</label>
            <span >
                <select name="s_month" id="s_month" class="txt_100 {required:true}" title="Start Date Month field is required">
                    <option value="" selected="selected"></option>
                    <?=generateMonth($s_month)?>
                </select>
                <select name="s_day" id="s_day" class="txt_50 {required:true}" title=" Start Date Day field is required">
                    <option value="" selected="selected"></option>
                    <?=generateDay($s_day)?>                                                                        
                </select>
                <select name="s_year" id="s_year" class="txt_70 {required:true}" title="Start Date Year field is required">
                    <option value="" selected="selected"></option>
                    <?=generateYearForSchoolYear($s_year)?>                                                    
                </select>&nbsp;<a href="noJs.html" id="date-pick2"><img src="images/425irjds.gif" border="0" /></a>
            </span><br class="hid" />
            <label></label>
        <br class="hid" />
            <label>School Year:</label>
            <span > <select name="school_year_id" id="school_year_id" class="txt_150 {required:true}" title="School Year field is required">
                  <option value="" selected="selected">Select</option>
                  <?=generateSchoolYrWithoutPast($school_year_id)?>                                                    
                </select>
            </span><br class="hid" />
            <label>School Term:</label>
            <span > <select name="term_id" id="term_id" class="txt_150 {required:true}" title="School Term field is required">
                  <option value="" selected="selected">Select</option>
                  <?=generateSchoolTermsWithoutPastBYSY($school_year_id,$term_id)?>                                                    
                </select>                
            </span><br class="hid" />
            <label></label>
<br class="hid" />
            <!-- LIST LOOK UP-->
            <div id="dialog" title="Course List">
                Loading...
            </div><!-- #dialog -->
    
            <span class="clear"></span>
        </fieldset>
        <fieldset>
           <legend><strong>System Information</strong></legend>
          
            <div id="course_container">
            
            	<?php
				if($course_id != '')
				{
				?>
                    <div id="course_item_<?=$prereq?>">
                        <input name="course_id[]" type="hidden" value="<?=$course_id?>" id="course_id" readonly="readonly" />
                        <label><a href="#" title="remove this course" onclick="$('#course_item_<?=$prereq?>').html(''); $('#course_item_<?=$course_id?>').css('display','none'); return false;" ><img src="images/icon_negative.png" border="0"/></a>&nbsp;&nbsp;<?='('.getCourseCode($course_id).') '.getCourseName($course_id)?></label>
                    </div>
            	<?php
				}
				?>
            
			</div>
            <label>&nbsp;</label>
            <a href="#" class="button" title="Add Course" id="dialog_link" returnId="<?=$id?>" returnComp="<?=$_REQUEST['comp']?>"><span>Add Course</span></a>
        	</span><br class="hid" /> 
            </span><br class="hid" />           

            <p>
            
         
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
<input type="hidden" name="page" id="page" value="<?=$page?>" />
<input type="hidden" name="sy_filter" id="sy_filter" value="<?=$sy_filter?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>