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
	$('#period').click(function(){
		if($('#syId').val() != '' && $('#termId').val() != '')
		{
			validator.resetForm();
			clearTabs();
			$('#period').addClass('active');
			$('#action').val('period');
			$('#view').val('period');
			$("form").submit();
		}
		else
		{
			validator.resetForm();
			alert('No term was selected. Please click on the manage period icon under the action column.');
			clearTabs();
			$('#list').addClass('active');
			$('#action').val('list');
			updateList();
			return false;
		}
	});
	
	$('#add_period').click(function(){
		if($('#syId').val() != '' && $('#termId').val() != '')
		{
			validator.resetForm();
			clearTabs();
			$('#add_period').addClass('active');
			$('#action').val('add_period');
			$('#view').val('add_period');
			$("form").submit();
		}
		else
		{
			validator.resetForm();
			alert('No term was selected. Please click on the add period icon under the action column.');
			clearTabs();
			$('#list').addClass('active');
			$('#action').val('list');
			updateList();
			return false;
		}
	});
	
	$('.edit').click(function(){
		$('#add_period').addClass('active');
		$('#period_id').val($(this).attr('returnId'));
		$('#action').val('edit');
		$('#view').val('edit');
		$("form").submit();
	});
	
	$('#list').click(function(){
		clearTabs();
		$('#list').addClass('active');
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

	$('.xmark').click(function(){
			if(confirm('Are you sure you want to set this as current period?'))
			{
				clearTabs();
				$('#period').addClass('active');
				$('#view').val('period');		
				$('#id').val($(this).attr("returnMarkId"));
				$('#action').val('set');
				$("form").submit();
			}
			else
			{
				return false;
			}
	});
	
	$('.checkmark').click(function(){
			alert('You cannot unset this period.');
			return false;
			
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
	if($view != 'period' && $view != 'add_period' && $view != 'edit')
	{
		echo 'updateList();'; 
	}
	?>

	$('#cancel').click(function(){
		clearTabs();
		$('#list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	
	
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
	$('#box_container').load('ajax_components/ajax_com_school_terms.php?list_rows=10' + param, null);
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
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<div class="box" id="box_container">

<p id="formbottom"></p>
</div>
<input type="hidden" name="filter_field" id="filter_field" value="<?=$filter_field?>" />
<input type="hidden" name="filter_order" id="filter_order" value="<?=$filter_order?>" />
<input type="hidden" name="termId" id="termId" value="<?=$termId?>" />
<input type="hidden" name="syId" id="syId" value="<?=$syId?>" />
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<input type="hidden" name="period_id" id="period_id" value="<?=$period_id?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>
<!-- LIST LOOK UP-->
<div id="dialog_grd" title="Student Grades">
    Loading...
</div><!-- #dialog -->