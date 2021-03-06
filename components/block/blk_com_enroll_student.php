<?php

if(!isset($_REQUEST['comp'])){
	include_once("../../config.php");
	include_once("../../includes/functions.php");
	include_once("../../includes/common.php");
}
	
if(USER_IS_LOGGED != '1'){
	header('Location: ../../index.php');
}else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])){
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
			"Ok": function() { 
				$(this).dialog("close"); 
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('#dialog_link').click(function(){
		$('#dialog').load('lookup/lookup_com_college.php', null);
		$('#dialog').dialog('open');
		return false;
	});
	
});		

$(document).ready(function(){  
			
	//initialize the tab action
	/*$('#enroll').click(function(){
		if($('#student_id').val() != '')
		{
			clearTabs();
			$('#enroll').addClass('active');
			$('#view').val('enroll');
			$('#action').val('enroll');
			var param = $('#student_id').val();
			var param2 = $('#comp').val();
			$('#box_container').html(loading);
			$('#box_container').load('ajax_components/ajax_com_enroll_student_sched.php?student_id='+param+'&comp='+param2, null);			
			schedList();
			$("form").submit();
		}
		else
		{
			alert('No student was selected.');
			clearTabs();
			$('#list').addClass('active');
			$('#view').val('list');
			$('#action').val('list');
			$('#student_id').val('');
			updateList();
			return false;
		}
	});*/
	
	$('#enroll_block').click(function(){
		if($('#student_id').val() != ''){
		
			if($('#enrol').val() == 'enrolled'){
				alert('Student already enrolled.');
				return false;
			}else{
				clearTabs();
				$('#enroll_block').addClass('active');
				$('#view').val('block');
				$('#action').val('block');
				var param = $('#student_id').val();
				var param2 = $('#comp').val();
				$('#box_container').html(loading);
				$('#box_container').load('ajax_components/ajax_com_enroll_student_block.php?student_id='+param+'&comp='+param2, null);			
				blockList();
				$("form").submit();
			
			}
		}else{
			alert('No student was selected.');
			clearTabs();
			$('#list').addClass('active');
			$('#view').val('list');
			$('#action').val('list');
			$('#student_id').val('');
			$('#enrol').val(''); 
			updateList();
			return false;
		}
	});
	
	$('#list').click(function(){
	//alert('me');
		clearTabs();
		$('#list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		$('#student_id').val('');
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
	if($view == 'enroll'){
		echo 'schedList();'; 
	}else if($view == 'block'){
		echo 'blockList();';
	}else{
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
		
	$('#page_rows').change(function(){
		$('#page').val(1);
		updateList();
	});	
	
});	

function clearTabs(){
	$('ul.tabs li').attr('class',''); // clear all active
}

function updateList(pageNum){
	var param = '';
	
	$('#page').val(pageNum);
	if($('#page').val() != '' && $('#page').val() != undefined){
		param = param + '&pageNum=' + $('#page').val();
	}
	
	if($('#filter_field').val() != '' && $('#filter_field').val() != undefined && $('#filter_order').val() != '' && $('#filter_order').val() != undefined ){
		param =  param + '&fieldName=' + $('#filter_field').val() + '&orderBy=' + $('#filter_order').val();
		
	}
		
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined ){
		param = param + '&list_rows=' + $('#page_rows').val() + param;
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined ){
		param = param + '&comp=' + $('#comp').val() + param;
	}
	
	$('#box_container').html(loading);
	
	<?php
	if(isset($student_id) && $student_id!=''){
		?>
		clearTabs();
		$('#enroll').addClass('active');
		param = param + '&student_id=' + <?=$student_id?>;
		$('#box_container').load('ajax_components/ajax_com_enroll_student_sched.php?param=1' + param, null);
	<?php
	}else{
		?>
		$('#box_container').load('ajax_components/ajax_com_enroll_student.php?param=1' + param, null);
	<?php
	}
	?>
}

function schedList(pageNum){
	var param = '';
	$('#page').val(pageNum);
	if($('#page').val() != '' && $('#page').val() != undefined){
		param = param + '&pageNum=' + $('#page').val();
	}
	
	if($('#filter_field').val() != '' && $('#filter_field').val() != undefined && $('#filter_order').val() != '' && $('#filter_order').val() != undefined ){
		param =  param + '&fieldName=' + $('#filter_field').val() + '&orderBy=' + $('#filter_order').val();
		
	}
		
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined ){
		param = param + '&list_rows=' + $('#page_rows').val() + param;
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined ){
		param = param + '&comp=' + $('#comp').val();
	}

	<?php
	if(isset($student_id) && $student_id!=''){
	?>
		param = '&student_id=' + <?=$student_id?> + '&comp=' + $('#comp').val();
	<?php
	}
	?>
	//alert (param);
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_enroll_student_sched.php?param=1' + param, null);
}

function blockList(pageNum){
	var param = '';
	$('#enrol').val('block');
	$('#page').val(pageNum);
	if($('#page').val() != '' && $('#page').val() != undefined){
		param = param + '&pageNum=' + $('#page').val();
	}
	
	if($('#filter_field').val() != '' && $('#filter_field').val() != undefined && $('#filter_order').val() != '' && $('#filter_order').val() != undefined ){
		param =  param + '&fieldName=' + $('#filter_field').val() + '&orderBy=' + $('#filter_order').val();
		
	}
		
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined ){
		param = param + '&list_rows=' + $('#page_rows').val() + param;
	}
	<?php
	if(isset($_REQUEST['comp'])){
	?>
		param = '&comp=' + <?=$_REQUEST['comp']?>;
	<?php
	}
	?>
	<?php
	if(isset($student_id) && $student_id != ''){
	?>
		param = '&student_id=' + <?=$student_id?>;
	<?php
	}
	?>
	//alert(param);
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_enroll_student_block.php?param=1' + param, null);
}
</script>

<?php
if($err_msg != ''){
?>
    <p class="alert">
        <span class="txt"><span class="icon"></span><strong>Alert:</strong> <?=$err_msg?></span>
        <a href="#" class="close" title="Close"><span class="bg"></span>Close</a>
    </p>
<?php
}
?>

<h2><?=$page_title?></h2>
<ul class="tabs">
<li id="list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Student List"><span>Student List</span></a></li>
<li id="enroll" <?=$view=='enroll'?'class="active"':''?>><a href="#" title="Enroll"><span>Enroll</span></a></li>
<li id="enroll_block" <?=$view=='block'?'class="active"':''?>><a href="#" title="Enroll Block"><span>Enroll Block</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="col50" enctype="multipart/form-data">
<?php
if($view == 'list'&&$action=='list'){
	if(isset($_SESSION[CORE_U_CODE]['pageRows'])&&($_SESSION[CORE_U_CODE]['pageRows']!='')){
		$p_row = $_SESSION[CORE_U_CODE]['pageRows'];
	}else if($_SESSION[CORE_U_CODE]['default_record']!=''){
		$p_row = $_SESSION[CORE_U_CODE]['default_record'];
	}else{
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
}
?>
	<div class="box" id="box_container">
		<div class="formview">

		</div><!-- /.formview-->
	</div>
    <input type="hidden" name="filter_field" id="filter_field" value="<?=$filter_field?>" />
    <input type="hidden" name="filter_order" id="filter_order" value="<?=$filter_order?>" />
    <input type="hidden" name="page" id="page" value="<?=$page?>" />
	<input type="hidden" name="temp" id="temp" value="" />
    <input type="hidden" name="student_id" id="student_id" value="<?=$student_id?>" />
    <input type="hidden" name="misc_id" id="misc_id" value="<?=$misc_id?>" />
    <input type="hidden" name="sched" id="sched" value="<?=$sched?>" />
    <input type="hidden" name="add_sub" id="add_sub" value="<?=$add_sub?>" />
    <input type="hidden" name="dropped" id="dropped" value="<?=$dropped?>" />
    <input type="hidden" name="scheme" id="scheme" value="<?=$scheme?>" />
    <input type="hidden" name="fees_ID" id="fees_ID" value="<?=$fees_ID?>" />
    <input type="hidden" name="ch_sub" id="ch_sub" value="<?=$ch_sub?>" />
    <input type="hidden" name="term" id="term" value="<?=CURRENT_TERM_ID?>" />
	<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
	<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />
	<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>