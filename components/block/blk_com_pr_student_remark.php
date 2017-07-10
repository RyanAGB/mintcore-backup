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
	$('#edit').click(function(){
		alert($(this).attr('returnID'));
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
	
	if($('#f_term_id').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#f_term_id').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
		
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_pr_student_remark.php?list_rows=10' + param, null);
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
    <!--
    <li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="List"><span>List</span></a></li>

    <li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
    
    <li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Add Remarks"><span>Add Remarks</span></a></li>
	-->
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<?php if($view == 'list')
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
	<div style="padding:10px; ">
	<table class="classic_borderless">
      <tr>
        <td style=" font-weight:bold; font-size:12px">Student Name: &nbsp;</td>
        <td style="font-size:12px"><?=getStudentFullName($id)?></td>
      </tr>
      <tr>
        <td style=" font-weight:bold; font-size:12px">Student Number: &nbsp;</td>
        <td style="font-size:12px"><?=getStudentNumber($id)?></td>
      </tr>
      <tr>
        <td style=" font-weight:bold; font-size:12px">Course: &nbsp;</td>
        <td style="font-size:12px"><?=getStudentCourseName($id)?></td>
      </tr>      
    </table>  
    </div>
	<div class="formview">
    
        <fieldset>
           <legend><strong>Student Remarks</strong></legend>
            <label>Description:</label>
            <span>
            <textarea name="description" cols="60" rows="8" class="txt_250 {required:true}" id="description" title="Description field is required"><?=$description?></textarea>
    		</span><br class="hid" />
    
            <span class="clear"></span>
        </fieldset>   
        
        <p class="button_container">
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
            <input type="hidden" name="subject_id" id="subject_id" value="<?=$subject_id?>" />
            <?php
            if($view == 'edit')
            {
            ?>
                <a href="#" class="button" title="Save" id="update"><span>Save</span></a>
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
<input type="hidden" name="f_term_id" id="f_term_id" value="<?=CURRENT_TERM_ID?>" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />
<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>