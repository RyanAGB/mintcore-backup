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
	var row_ctr = <?=$sched_row_ctr == '0' ?'1':$sched_row_ctr+1?>;
	var sort_ctr = $('#sort_cnt').val();
	$('#add_payment_sched').click(function(){
		
		var full = 0;
		var fulladd = $('#full').val();
		var payment_name 	= $('#payment_name').val();
		var payment_type 	= $('#payment_type').val();
		var str_payment_type = $('#payment_type').val() == 'P'?'Percentage':'Amount';
		var payment_value 	= $('#payment_value').val();
		var date_year 		= $('#e_year').val();
		var date_month 		= $('#e_month').val() < 10 ? '0' + $('#e_month').val() : $('#e_month').val();
		var date_day 		= $('#e_day').val() < 10 ? '0' + $('#e_day').val() : $('#e_day').val();	
		var payment_date = date_year + '-' + date_month + '-' + date_day;		
		
		if(payment_type == 'A'){
		$('#percent').val(payment_value); }	
		
		if(sort_ctr == 1 && payment_type == 'P' && payment_value > 100)
		{
			alert('Total percentage exceeds 100%');
			return false;
		}
		else if(fullPercent(sort_ctr))
		{
			alert('Total percentage exceeds 100%');
			return false;
		}
		else if(fullDate(date_year,date_month,date_day))
		{
			alert('Payment Date already exists.');
			return false;
		}
		else if(fullName(payment_name,sort_ctr))
		{
			alert('Payment Name already exists.');
			return false;
		}
		else
		{
			if(payment_name != '' && payment_type != '' && payment_value != '' )
			{
				if(str_payment_type == 'Percentage'){	
			full = parseInt(payment_value)+($('#full').val()*1);
			$('#full').val(full);} 
			
				str_table ='<tr id ="row_'+row_ctr+'">';
				  str_table +='<td><input name="payment_name[]" type="hidden" id="payment_name" value="'+payment_name+'" />' +payment_name+ '</td>';
				  str_table +='<td><input name="payment_type[]" type="hidden" id="payment_type" value="'+payment_type+'" />' +str_payment_type+ '</td>';
				  str_table +='<td><input name="payment_value[]" type="hidden" id="payment_value" value="'+payment_value+'" />' +payment_value+ '</td>';
				  str_table +='<td><input name="sort_order[]" type="text" id="sort_order" value="'+sort_ctr+'" /></td>';
				  str_table +='<td><input name="payment_date[]" type="hidden" id="payment_date" value="'+payment_date+'" />' +payment_date+ '</td>';
				  str_table +='<td class="action"><a href="#" class="remove" returnId="'+row_ctr+'" onclick="removeRow('+row_ctr+'); return false;" >Remove</a></td>';             
				str_table +='</tr>';
			sort_ctr++;
				$('#tbl_payment_sched tbody').append(str_table);
				
			$('#payment_name').val('');
			$('#payment_value').val('');
			}
			else
			{
				alert('Some required fields are missing.');
			}
		}
		return false;
	});
	
	//initialize the tab action 
	$('.remove').click(function(){
		<?php if(canDeleteScheme($_REQUEST['id'])){?>
			removeRow($(this).attr("returnId"));
			return false;
		<?php }else{?>
			alert('Cannot Delete Payment Scheme . Currently there are Payment Associated.');
		<?php }?>
		
	});
	

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
			$("form").submit();
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
		if($('#full').val() < 100)
		{
			alert("Percentage must total 100%");
		}
		else
		{
			clearTabs();
			$('#add_new').addClass('active');
			$('#action').val('save');
			$('#view').val('add');
			$("form").submit();
		}
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
		$('#rows').val($('#page_rows').val());
		$('#page').val(1);
		updateList();
	});
	
});	

function removeRow(id)
{
	var valId = id;
	percent = 0;
	
	$('#row_' + valId).remove();
	return false;
}

function fullPercent(srt)
{
  if(srt!=1)
  {
	var ctr = 0;
	var cnt = 0;
	var down = $('#percent').val();
		
		   		$.each($("input[name*=payment_value]"), function(x, p) {
					ctr += parseInt(p.value);
					cnt++;
				});

		ctr = ctr - down;
		//alert(ctr);
		if(ctr > 100)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	return false;
}

function notFullPercent()
{
	var ctr = 0;
	var cnt = 0;
	var down = $('#percent').val();
		
		   		$.each($("input[name*=payment_value]"), function(x, p) {
					ctr += parseInt(p.value);
					cnt++;
				});

		ctr = ctr - down;
		//alert(ctr);
		if(ctr < 100)
		{
			return true;
		}
		else
		{
			return false;
		}
	return false;
}

function fullDate(date_year,date_month,date_day)
{
	var ctr = '';
	var det = '';

		  $.each($("input[name*=payment_date]"), function(y, z) {
				ctr = z.value;
				var dat = ctr.split("-");
				if(date_year == dat[0] && date_month == dat[1] && date_day == dat[2])
				{
					det = "true";
				}
			});

		//alert(det);
		if(det != '')
		{
			return true;
		}
		else
		{
			return false;
		}
}

function fullName(name,ctr)
{
	 if(ctr!=1)
  {
	var nam = '';
		  $.each($("input[name*=payment_name]"), function(q, w) {
			if(q==ctr-1)
			{
				if(w.value == name)
				{
					nam = "true";
				}
			}
		  });

		//alert(nam);
		if(nam != '')
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	return false;
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

	if($('#f_term_id').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#f_term_id').val();
	}
	
	if($('#rows').val() != '' && $('#rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#rows').val() + param;
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}

	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_payment_scheme.php?param=1' + param, null);
}

	$(function()
	{
		
		// initialise the "Select date" link
		$('#date-pick')
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
					$('#date-pick').dpSetSelected(d.asString());
					date_end = d.asString();
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
				echo '$("#date-pick").dpSetSelected(new Date('.$e_year.','. $e_month .','.$e_day.').asString());';
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
<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Scheme List"><span>Scheme List</span></a></li>
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
           <legend><strong>Discount Information</strong></legend>
            <label>Scheme Name:</label>
            <span ><input class="txt_200 {required:true}" title="Discount Name field is required" name="name" type="text" value="<?=$name?>" id="name" />
            </span><br class="hid" />
            <label>Surcharge:</label>
            <span ><input class="txt_200 {required:true}" title="Surcharge Name field is required" name="surcharge" type="text" value="<?=$surcharge?>" id="surcharge" />
            </span><br class="hid" />
            <label>School Year</label>
            <span >
            <select name="term_id" id="term_id" class="txt_200 {required:true}" title="Term field is required" >
                    <?=generateSchoolTerms($term_id)?>
            </select>  
            </span><br class="hid" />
            <label>Publish:</label>
            <span>
                <select name="publish" id="publish" class="txt_150 {required:true}" title="Publish field is required" >
                    <option value="" selected="selected">Select</option>
                    <option value="Y" <?=$publish== "Y"?'selected = "selected"':''?> >Yes</option>
                    <option value="N" <?=$publish== "N"?'selected = "selected"':''?> >No</option>
                </select>    
            </span><br class="hid" />             
    	</fieldset>
        <fieldset>
           <legend><strong>Payment Schedule</strong></legend>
            <span class="clear"></span>
			<label>Caption:</label>
            <span ><input name="payment_name" type="text" class="txt_200" id="payment_name" value="" />
            </span><br class="hid" />
            <label>Type</label>
            <span >
            <select name="payment_type" class="txt_100" id="payment_type" >
              <option value="P">Percentage</option>
              <option value="A">Amount</option>
            </select>  
            </span><br class="hid" />
			<label>Value:</label>
            <span ><input name="payment_value" type="text" class="txt_200" id="payment_value" value="" />
            </span><br class="hid" />            
            <label>Payment Date</label>
            <span >
            <select name="e_month" id="e_month" class="txt_85 {required:true}" title="End Date Month field is required">
                <option value=""></option>
                <?=generateMonth($e_month)?>
            </select>
            
            <select name="e_day" id="e_day" class="txt_45 {required:true}" title="End Date Day field is required">
                <option value="" selected="selected"></option>
                <?=generateDay($e_day)?>                                                   
            </select>
            
            <select name="e_year" id="e_year" class="txt_60 {required:true}" title="End Date Year field is required">
                <option value="" selected="selected"></option>
                <?=generateYearForSchoolYear($e_year)?>                 
            </select>&nbsp;<a href="noJs.html" id="date-pick"><img src="images/425irjds.gif" border="0" /></a>            
        	</span><br class="hid" />
            <label><a href="#" class="button" title="Save" id="add_payment_sched"><span>Add Schedule</span></a></label>
        </fieldset>
        <label>&nbsp;</label>
        <table class="listview" id="tbl_payment_sched">     
        <thead> 
          <tr>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="name">Caption</a></th>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="value">Type</a></th>
              <th class="col_350">Value</th>
              <th class="col_100">Sort Order</th>
              <th class="col_250">Date of Payment</th>
			  <th class="col_150">Action</th>              
          </tr>
        </thead>
        <tbody>
        <?=$payment_sched?>
        </tbody>          
        </table>
        
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
<input type="hidden" name="f_term_id" id="f_term_id" value="<?=$f_term_id==''?CURRENT_TERM_ID:$f_term_id?>" />
<input type="hidden" name="rows" id="rows" value="<?=$rows?>" />
<input type="hidden" name="page" id="page" value="<?=$page?>" />
<input type="hidden" name="percent" id="percent" value="<?=$percent?>" />
<input type="hidden" name="full" id="full" value="<?=$full?>" />
<input type="hidden" name="sort_cnt" id="sort_cnt" value="<?=$sort_cnt==''?1:$sort_cnt?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>