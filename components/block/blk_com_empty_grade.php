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
	else if(in_array($comp,$_SESSION[CORE_U_CODE]['can_edit_comp']))
	{
		$canEdit='N';
	}else{
		$canEdit='Y';
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
			clearTabs();
			$('#room_list').addClass('active');
			$('#view').val('list');
			$('#action').val('list');			
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
		param = param + '&list_rows=' + $('#page_rows').val() + param;
	}
	
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
	
	if($('#canEdit').val() != '' && $('#canEdit').val() != undefined )
	{
		param = param + '&canEdit=' + $('#canEdit').val() + param;
	}
		
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_empty_grade.php?param=1' + param, null);
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
<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Building List"><span>Schedule List</span></a></li>
<!--<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit Item"><span>Edit Item</span></a></li>!-->
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<?php
	if($view == 'list')
	{
		if(isset($_SESSION[CORE_U_CODE]['pageRows'])&&($_SESSION[CORE_U_CODE]['pageRows']!=''))
		{
			$p_row = $_SESSION[CORE_U_CODE]['pageRows'];
		}
		else if(isset($_SESSION[CORE_U_CODE]['default_record']))
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

<p id="formbottom"></p>
</div>
<input type="hidden" name="filter_field" id="filter_field" value="<?=$filter_field?>" />
<input type="hidden" name="filter_order" id="filter_order" value="<?=$filter_order?>" />
<input type="hidden" name="page" id="page" value="<?=$page?>" />
<input type="hidden" name="canEdit" id="canEdit" value="<?=$canEdit?>" />
<input type="hidden" name="ctr" id="ctr" value="<?=$ctr?>" />
<input type="hidden" name="sched" id="sched" value="<?=$sched?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>
