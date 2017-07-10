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
		updateList();
		$("form").submit();
	});	
		
	$('#page_rows').change(function(){
		updateList();
	});		
	
});	

function clearTabs()
{
	pageNum$('ul.tabs li').attr('class',''); // clear all active
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
	
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#page_rows').val();
	}
		

	if($('#schoolterm_id').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#schoolterm_id').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
	
	if($('#prof_id').val() != '')
	{
		param = param + '&prof_id=' + $('#prof_id').val();
		param = param + '&comp=' + $('#comp').val();
		$('#box_container').html(loading);
		$('#box_container').load('ajax_components/ajax_com_professor_encode_grade_list.php?list_rows=10' + param, null);
	}
	else
	{
		$('#box_container').html(loading);
		$('#box_container').load('ajax_components/ajax_com_professor_encode_grade.php?param=1' + param, null);
	}
	
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

<h2><?=$page_title?></h2>

<form name="coreForm" id="coreForm" method="post" action="" class="col50" enctype="multipart/form-data">

<div class="box" id="box_container">
<p id="formbottom"></p>
</div>

<input type="hidden" name="schoolterm_id" id="schoolterm_id" value="<?php if($schoolterm_id!=''){echo $schoolterm_id; }else if($_REQUEST['filter_schoolterm']!=''){ echo $_REQUEST['filter_schoolterm'];}else{ echo CURRENT_TERM_ID; } ?>" />
<input type="hidden" name="filter_field" id="filter_field" value="<?=$filter_field?>" />
<input type="hidden" name="filter_order" id="filter_order" value="<?=$filter_order?>" />
<input type="hidden" name="page" id="page" value="<?=$page?>" />
<input type="hidden" name="rows" id="rows" value="<?=$rows?>" />
<input type="hidden" name="filter_dept" id="filter_dept" value="<?=$filter_dept?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />
<input type="hidden" name="prof_id" id="prof_id" value="<?=$prof_id?>" />
<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>