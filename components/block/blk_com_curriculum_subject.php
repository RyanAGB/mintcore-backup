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
	$('#subject_dialog').dialog({
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
	$('#dialog_subject_link').click(function(){
		var param = $(this).attr("returnComp");
		$('#subject_dialog').load('lookup/lookup_com_curriculum_subject.php?comp='+param, null);
		$('#subject_dialog').dialog('open');
		return false;
	});
	
	// Dialog			
	$('#preReq_dialog').dialog({
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
	$('#preReq_link').click(function(){
		var param = $(this).attr("returnComp");
		$('#preReq_dialog').load('lookup/lookup_com_curriculum_subject_prereq.php?comp='+param, null);
		$('#preReq_dialog').dialog('open');
		return false;
	});	
	
	// Dialog			
	$('#coReq_dialog').dialog({
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
	$('#coReq_link').click(function(){
		var param = $(this).attr("returnComp");
		$('#coReq_dialog').load('lookup/lookup_com_curriculum_subject_coreq.php?comp='+param, null);
		$('#coReq_dialog').dialog('open');
		return false;
	});	

});	
$(document).ready(function(){  

	var validator = $("#coreForm").validate({
		errorLabelContainer: $("div.error")
	});
	
	if($('#curriculum_id').val()!='')
	{
		  updateField($('#curriculum_id').val(),'year_level','year_level');
  		  updateField($('#curriculum_id').val(),'term','term');
	}
	
	//initialize the tab action
	$('#curriculum_id').change(function(){
		  updateField($(this).val(),'year_level','year_level');
  		  updateField($(this).val(),'term','term');
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
			alert('No item was selected.');
			updateList();
			return false;
		}
	});
	
	$('#curriculum_list').click(function(){
		clearTabs();
		$('#curriculum_list').addClass('active');
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
	
	if($('#fcourse').val()!='' && $('#fcurr').val()!='' && $('#view').val()=='list')
	{
		$.ajax({
					type: "POST",
					data: "mod=update&id=" + $('#fcourse').val(),
					url: "ajax_components/ajax_com_curr_field_updater.php",
					success: function(msg){
						if (msg != ''){
						//alert(msg);
							$("#filter_curr").html(msg);
							updateList();
						}
					}
					});	
	}
	
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
	
	$('#filter_curr').change(function(){
		$('#fcurr').val($('#filter_curr').val());
		$('#fcourse').val($('#filter_course').val());
		updateList();
	});
	
	$('#filter_course').change(function(){
					$.ajax({
					type: "POST",
					data: "mod=select&id=" + $('#filter_course').val(),
					url: "ajax_components/ajax_com_curr_field_updater.php",
					success: function(msg){
						if (msg != ''){
						//alert(msg);
							$("#filter_curr").html(msg);
						}
					}
					});	
		$('#fcourse').val($('#filter_course').val());
		$('#fcurr').val($('#filter_curr').val());	
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
	
	if($('#fcourse').val() != '' && $('#fcourse').val() != undefined)
	{
		param =  param + '&filterCourse=' + $('#fcourse').val();
		
	}
	if($('#fcurr').val() != '' && $('#fcurr').val() != undefined)
	{
		param =  param + '&filterCurr=' + $('#fcurr').val();
		
	}
		
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#page_rows').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
//alert(param);		
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_curriculum_subject.php?param=1' + param, null);
}
		
function updateField(curriculum_id,filter_type,field_obj)
{
		$.ajax({
		type: "POST",
		data: "mod=updateField&filter=" + filter_type +"&id=" + curriculum_id,
		url: "ajax_components/ajax_com_curriculum_subject_field_updater.php",
		success: function(msg){
			if (msg != ''){
				
				$("#"+field_obj).html(msg);
			}
		}
		});	
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
<li id="curriculum_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Curriculum List"><span>Subject List</span></a></li>
<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add Curriculum Subject"><span>Add</span></a></li>
<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit Curriculum Subject"><span>Edit</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<?php
	if($view == 'list')
	{
	?>
    <div class="filter">
    Filter by Course &nbsp;&nbsp;
		 <select name="filter_course" id="filter_course" class="txt_300">
          <option value="" selected="selected">Select</option>
                <?=generateCourse($fcourse)?>
         </select>
         Curriculum &nbsp;&nbsp;
		 <select name="filter_curr" id="filter_curr" class="txt_200">
          <option value="" selected="selected">Select</option> 
          <?=$fcurr!=''?generateCurriculumByCourse($fcurr,$fcourse):''?>
         </select>
    </div>
	<?php	
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
           <legend><strong>Curriculum Subject Information</strong></legend>
            <label>Curriculum:</label>
            <span ><select name="curriculum_id" id="curriculum_id" class="txt_300 {required:true}" title="Curriculum field is required" >
                    <option value="" selected="selected">Select Curriculum</option>
                    <?=generateCurriculum($fcurr)?>
                </select>
            </span><br class="hid" />
            <label>Year level:</label>
            <span ><select name="year_level" id="year_level" class="txt_150 {required:true}" title="Year Level field is required" >
                    <option value="" selected="selected">Select Year</option>
                    <?=generateYearByCurriculum($year_level,$curriculum_id)?>
                </select>
            </span><br class="hid" />
            <label>Term:</label>
            <span ><select name="term" id="term" class="txt_150 {required:true}" title="Term field is required" >
                    <option value="" selected="selected">Select Term</option>
                    <?=generateTermByCurriculum($term,$curriculum_id)?>
                </select>
            </span><br class="hid" />
            <label>Units:</label>
            <span ><input class="txt_100 {required:true}" title="Units field is required" name="units" type="text" value="<?=$units?>" id="units" />
            </span><br class="hid" />
            <br class="hid" />
            <label>Subject Name:</label>
            <span>
            <div style=" float:left">
            <input class="txt" name="subject_id" type="hidden" value="<?=$subject_id?>" id="subject_id" readonly="readonly" />
            <input class="txt_250 {required:true}" title="Subject Name field is required" name="subject_name_display" type="text" value="<?=$subject_name_display==''?getSubjName($subject_id):$subject_name_display?>" id="subject_name_display" readonly="readonly" />
            </div>
            <div style="width:50px; float:left">
            <a href="#" class="lookup_button" id="dialog_subject_link" returnComp="<?=$_REQUEST['comp']?>"><span>...</span></a><br class="hid" />
            </div>
        	</span><br class="hid" /> 
            <p>      

    
    		 <label>Subject Type:</label>
            <span >
                <select name="subject_category" id="subject_category" class="txt_150 {required:true}" title="Subject Type field is required" >
					<option value="" selected="selected">Select</option>
                    <option value="R" <?=$subject_category== "R"?'selected = "selected"':''?> >Regular Subject</option>
                    <option value="E" <?=$subject_category== "E"?'selected = "selected"':''?> >Elective Subject</option>
                    <option value="EO" <?=$subject_category== "EO"?'selected = "selected"':''?> >Elective Subject Option</option>                    
                </select>    
            </span><br class="hid" /> 
    		</p>
            <span class="clear"></span>
        </fieldset>
                    
            <!-- LIST LOOK UP-->
            <div id="subject_dialog" title="Subject List">
                Loading...
            </div><!-- #dialog -->
            
        <fieldset>
           <legend><strong>Pre-requisite Information</strong></legend>
            <br class="hid" />
            <label>Pre-requisite:</label>
            <span>
			
            <div id="prereq_container">
            
            	<?php
				if(count(getSubjectPreReqInArr($id)) > 0)
				{
					foreach(getSubjectPreReqInArr($id) as $prereq)
					{
				?>
                    <div id="prerequisite_item_<?=$prereq?>">
                        <input class="txt" name="prerequisite[]" type="hidden" value="<?=$prereq?>" id="prerequisite" readonly="readonly" />
                        <label><a href="#" title="remove this subject" onclick="$('#prerequisite_item_<?=$prereq?>').html(''); $('#prerequisite_item_<?=$prereq?>').css('display','none'); return false;" ><img src="images/icon_negative.png" border="0"/></a>&nbsp;&nbsp;<?=$prereq!=''?'('.getSubjCode($prereq).') '.getSubjName($prereq):''?></label>
                    </div>
            	<?php
					}
				}
				?>
            
			</div>
			
            <label>&nbsp;</label>
            <a href="#" class="button" id="preReq_link" returnId="<?=$id?>" returnComp="<?=$_REQUEST['comp']?>"><span>Add Prerequisite</span></a>
        	</span><br class="hid" /> 
            </span><br class="hid" />           

            <p>
            
            <!-- LIST LOOK UP-->
            <div id="preReq_dialog" title="Pre-requisite List">
                Loading...
            </div><!-- #dialog -->
    
            <span class="clear"></span>
            
            
        </fieldset>
        
        <fieldset>
           <legend><strong>Co-requisite Information</strong></legend>
            <br class="hid" />
            <label>Co-requisite:</label>
            <span>
			
            <div id="coreq_container">
            
            	<?php
				if(count(getSubjectCoReqInArr($id)) > 0)
				{
					foreach(getSubjectCoReqInArr($id) as $coreq)
					{
				?>
                    <div id="corequisite_item_<?=$coreq?>">
                        <input class="txt" name="corequisite[]" type="hidden" value="<?=$coreq?>" id="corequisite" readonly="readonly" />
                        <label><a href="#" title="remove this subject" onclick="$('#corequisite_item_<?=$coreq?>').html(''); $('#corequisite_item_<?=$coreq?>').css('display','none'); return false;" ><img src="images/icon_negative.png" border="0"/></a>&nbsp;&nbsp;<?=$coreq!=''?'('.getSubjCode($coreq).') '.getSubjName($coreq):''?></label>
                    </div>
            	<?php
					}
				}
				?>
            
			</div>
			
            <label>&nbsp;</label>
            <a href="#" class="button" id="coReq_link" returnId="<?=$id?>" returnComp="<?=$_REQUEST['comp']?>"><span>Add Co-requisite</span></a>
        	</span><br class="hid" /> 
            </span><br class="hid" />           

            <p>
            
            <!-- LIST LOOK UP-->
            <div id="coReq_dialog" title="Co-requisite List">
                Loading...
            </div><!-- #dialog -->
    
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
<input type="hidden" name="fcourse" id="fcourse" value="<?=$fcourse?>" />
<input type="hidden" name="fcurr" id="fcurr" value="<?=$fcurr?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>