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
	$('#course_dialog').dialog({
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
	$('#course_link').click(function(){
		var param = $(this).attr("returnComp");
		$('#course_dialog').load('lookup/lookup_com_curriculum.php?comp='+param, null);
		$('#course_dialog').dialog('open');
		return false;
	});
	
});	
$(function(){

	// Dialog			
	$('#subject_dialog').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Select": function() { 
				getselected(); 
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('#subject_link').click(function(){
		$('#subject_dialog').load('lookup/lookup_com_curriculumsubject.php', null);
		$('#subject_dialog').dialog('open');
		return false;
	});
	
});	
$(function(){

	// Dialog			
	$('#preReq_dialog').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
		"Select": function() { 
				getselected(); 
			}, 
			"Close": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('#preReq_link').click(function(){
		var yir = document.getElementById("year_level").value;
		var term = document.getElementById("term").value;
		var id = $(this).attr("returnId");
		$('#preReq_dialog').load('lookup/lookup_com_preReq.php?id='+id+'&yir='+yir+'&term='+term, null);
		$('#preReq_dialog').dialog('open');
		return false;
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
			updateList();
			return false;
		}
	});
	
	$('#add_subject').click(function(){
		if(checkbox_checker())
		{
			validator.resetForm();
			clearTabs();
			$('#add_subject').addClass('active');
			$('#view').val('add_subject');
			$("form").submit();
		}
		else
		{
			validator.resetForm();
			alert('No item was selected.');
			clearTabs();
			$('#room_list').addClass('active');
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
	
	$('#save_subject').click(function(){
		clearTabs();
		$('#add_subject').addClass('active');
		$('#action').val('save_subject');
		$('#view').val('add_subject');
		$("form").submit();
	});	
	
	$('#save_elective').click(function(){
		clearTabs();
		$('#add_elective').addClass('active');
		$('#action').val('save_elective');
		$('#view').val('add_elective');
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
	if($view != 'add' && $view != 'edit' && $view != 'add_subject' && $view != 'add_elective')
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
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_curriculum.php?list_rows=10' + param, null);
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
<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Curriculum List"><span>Curriculum List</span></a></li>
<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add</span></a></li>
<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit Item"><span>Edit</span></a></li>
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
<?php
	if($view == 'edit' || $view == 'add')
	{
?>
	<div class="formview">
    
        <fieldset>
           <legend><strong>Curriculum Information</strong></legend>
            <label>Curriculum Code:</label>
            <span ><input class="txt_100 {required:true}" title="Curriculum Code field is required" name="curriculum_code" type="text" value="<?=$curriculum_code?>" id="curriculum_code" />
            </span><br class="hid" />
            <label>No. of Year Level:</label>
            <span >
            <select name="no_of_years" id="no_of_years" class="txt_100 {required:true}" title="No. of Year Level is required">
                  <option value="" selected="selected" >Select</option>
                  <option value="1" <?=$no_of_years=='1'?'selected="selected"':''?>>1</option> 
                  <option value="2" <?=$no_of_years=='2'?'selected="selected"':''?>>2</option> 
                  <option value="3" <?=$no_of_years=='3'?'selected="selected"':''?>>3</option> 
                  <option value="4" <?=$no_of_years=='4'?'selected="selected"':''?>>4</option>
                  <option value="5" <?=$no_of_years=='5'?'selected="selected"':''?>>5</option>                           
            </select>
            </span><br class="hid" />
            <label>Term per Year:</label>
            <span >
            <select name="term_per_year" id="term_per_year" class="txt_100 {required:true}" title="Term per Year field is required">
                  <option value="" selected="selected" >Select</option>
                  <option value="1" <?=$term_per_year=='1'?'selected="selected"':''?>>1</option> 
                  <option value="2" <?=$term_per_year=='2'?'selected="selected"':''?>>2</option> 
                  <option value="3" <?=$term_per_year=='3'?'selected="selected"':''?>>3</option> 
                  <option value="4" <?=$term_per_year=='4'?'selected="selected"':''?>>4</option>                                                  
            </select>            
            </span><br class="hid" />
            <br class="hid" />
            <label>Course Name:</label>
            <span>
            <div style=" float:left">
            <input class="txt" name="course_id" type="hidden" value="<?=$course_id?>" id="course_id" readonly="readonly" />
            <input class="txt_250 {required:true}" title="Course Name field is required" name="course_name_display" type="text" value="<?=$course_name_display?>" id="course_name_display" readonly="readonly" />
            </div>
            <div style="width:50px; float:left">
            <a href="#" class="lookup_button" id="course_link" returnComp="<?=$_REQUEST['comp']?>"><span>...</span></a><br class="hid" />
            </div>
        	</span><br class="hid" /> 
            </span><br class="hid" />           

            <p>
            
            <!-- LIST LOOK UP-->
            <div id="course_dialog" title="Course List">
                Loading...
            </div><!-- #dialog -->
    
            <span class="clear"></span>
        </fieldset>
        <fieldset>
           <legend><strong>System Information</strong></legend>
            <label>Current:</label>
            <span >
                <select name="is_current" id="is_current" class="txt_150 {required:true}" title="Is Current field is required" >
                    <option value="" selected="selected">Select</option>
                    <option value="Y" <?=$is_current== "Y"?'selected = "selected"':''?> >Yes</option>
                    <option value="N" <?=$is_current== "N"?'selected = "selected"':''?> >No</option>
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
	$filter_field = $_REQUEST['filter_field'];
	$filter_order = $_REQUEST['filter_order'];
?>

<p id="formbottom"></p>
</div>
<input type="hidden" name="filter_field" id="filter_field" value="<?=$filter_field?>" />
<input type="hidden" name="filter_order" id="filter_order" value="<?=$filter_order?>" />
<input type="hidden" name="page" id="page" value="<?=$page?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>