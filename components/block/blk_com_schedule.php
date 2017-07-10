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
	$('#template_dialog').dialog({
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
	
	// Dialog Template Link
	$('#dialog_template').click(function(){
		var param = $(this).attr("returnComp");
		$('#template_dialog').load('lookup/lookup_com_schedule_template.php?comp='+param, null);
		$('#template_dialog').dialog('open');
		return false;
	});
	
	//Student Dialog Link
	$('#student_dialog').dialog({
		autoOpen: false,
		width: 700,
		height: 600,
		bgiframe: true,
		modal: true,
		buttons: {
			"Close": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	$('#dialog_student_link').click(function(){
		var param = $(this).attr("returnId");
		var param2 = $(this).attr("returnComp");
		var param3 = $(this).attr("returnTerm");
		$('#student_dialog').load('viewer/viewer_com_schedule_student_list_2_1.php?id='+param+'&comp='+param2+'&filter_schoolterm='+param3, null);
		$('#student_dialog').dialog('open');
		return false;
	});
	
	//Subject Dialog Link
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
	
	$('#dialog_subject_link').click(function(){
		var param = $(this).attr("returnComp");
		$('#subject_dialog').load('lookup/lookup_com_schedule_subject.php?comp='+param, null);
		$('#subject_dialog').dialog('open');
		return false;
	});
	
	$('#el_subject_dialog').dialog({
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
	
	
	$('#dialog_el_subject_link').click(function(){
		var param = $(this).attr("returnComp");
		$('#el_subject_dialog').load('lookup/lookup_com_schedule_el_subject.php?comp='+param, null);
		$('#el_subject_dialog').dialog('open');
		return false;
	});

	//Room Dialog Link		
	$('#room_dialog').dialog({
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
	
	
	$('#dialog_room_link').click(function(){
		var param = $(this).attr("returnComp");
		$('#room_dialog').load('lookup/lookup_com_schedule_room.php?comp='+param, null);
		$('#room_dialog').dialog('open');
		return false;
	});

	//Professor Dialog Link	
	$('#prof_dialog').dialog({
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
	
	
	$('#dialog_prof_link').click(function(){
		var param = $(this).attr("returnComp");
		$('#prof_dialog').load('lookup/lookup_com_schedule_prof.php?comp='+param, null);
		$('#prof_dialog').dialog('open');
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
	
	$('#room_list').click(function(){
		validator.resetForm();
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
	
	var row_ctr = <?=$sched_row_ctr == '0' ?'1':$sched_row_ctr+1?>;
	
	$('#add_sched').click(function(){

		if($('#chkTBATIME').attr("checked")){
			str_table ='<tr id ="row_'+row_ctr+'">';
			str_table +='<td><input name="days[]" type="hidden" id="days" value="(TBA)" />(TBA)</td>';
			str_table +='<td><input name="start[]" type="hidden" id="start" value="00:00" />00:00</td>';
			str_table +='<td><input name="end[]" type="hidden" id="end" value="00:00" />00:00</td>';
			str_table +='<td class="action"><a href="#" class="remove" returnId="'+row_ctr+'" onclick="removeRow('+row_ctr+'); return false;" >Remove</a></td>';
			str_table +='</tr>';
			$('#tbl_sched tbody').append(str_table);
		}else{
			var start		 	= $('#time_from').val();
			var end			 	= $('#time_to').val();

			if(!validatecheck() || start == '' || end == '' ){
				alert('Some required fields are missing.');
				return false;
			}else{
				if(validateTime()){
					for(var x=0;x<=6;x++){			
						if($('#'+x).attr("checked")){
							//alert($('#'+x).val()+start+end);
							str_table ='<tr id ="row_'+row_ctr+'">';
							str_table +='<td><input name="days[]" type="hidden" id="days" value="'+$('#'+x).val()+'" />' +$('#'+x).val()+ '</td>';
							str_table +='<td><input name="start[]" type="hidden" id="start" value="'+start+'" />' +start+ '</td>';
							str_table +='<td><input name="end[]" type="hidden" id="end" value="'+end+'" />' +end+ '</td>';
							str_table +='<td class="action"><a href="#" class="remove" returnId="'+row_ctr+'" onclick="removeRow('+row_ctr+'); return false;" >Remove</a></td>';             
							str_table +='</tr>';
		
							$('#tbl_sched tbody').append(str_table);
						}
					}
				}else{
					alert('Conflict Schedule Found');
				}
			}
		}
		return false;
	});
		
	// Initialize the list
	<?php
	if($view != 'add' && $view != 'edit'){
		echo 'updateList();'; 
	}
	?>

	$('#cancel').click(function(){
		validator.resetForm();
		clearTabs();
		$('#room_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	
	
	$('.remove').click(function(){
		removeRow($(this).attr('returnId'));
	});	
	
	$('#page_rows').change(function(){
		$('#page').val(1);
		updateList();
	});	
	
	$("#time_from,#time_to")
		.bind(
				'change',
				function()
				{
					if($("#time_from").val() != '' && $("#time_to").val() != '')
					{
						if($("#time_from").val() >= $("#time_to").val())
						{
							alert("End time should not be less than or equal to Start time.");
							$("#time_from, #time_to").val('');
						}
					}
				}
			);
	
});	
function validatecheck(){
	var y=0;
	for(var x=0;x<=7;x++){
		if($('#'+x).attr("checked")){
			y++;
		}
	}
	
	if(y==0){
		return false;
	}else{
		return true;
	}
}
function validateTime(){
	if($("#time_from").val() != '' && $("#time_to").val() != ''){
		if($("#time_from").val() >= $("#time_to").val()){
			$("#time_from, #time_to").val('');
			return false;
		}else{
			return true;
		}
	}else{
		return false;
	}
}
function removeRow(id){
	var valId = id;
	percent = 0;
	
	$('#row_' + valId).remove();
	return false;
}
function clearTabs(){
	$('ul.tabs li').attr('class',''); // clear all active
}

function updateList(pageNum){
	var param = '';
	
	$('#page').val(pageNum)
	if($('#page').val() != '' && $('#page').val() != undefined){
		param = param + '&pageNum=' + $('#page').val();
	}
	
	if($('#filter_field').val() != '' && $('#filter_field').val() != undefined && $('#filter_order').val() != '' && $('#filter_order').val() != undefined ){
		param =  param + '&fieldName=' + $('#filter_field').val() + '&orderBy=' + $('#filter_order').val();
		
	}
		
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined ){
		param = param + '&list_rows=' + $('#page_rows').val();
	}

	if($('#filter_schoolterm').val() != '' ){
		param = param + '&filter_schoolterm=' + $('#filter_schoolterm').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined ){
		param = param + '&comp=' + $('#comp').val() + param;
	}
			
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_schedule.php?param=1' + param, null);
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

<div class="error"></div>

<h2><?=$page_title?></h2>
<ul class="tabs">
<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Schedule List"><span>Schedule List</span></a></li>
<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit Item"><span>Edit Item</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<?php
	if($view == 'list'){
		if(isset($_SESSION[CORE_U_CODE]['pageRows'])&&($_SESSION[CORE_U_CODE]['pageRows']!='')){
			$p_row = $_SESSION[CORE_U_CODE]['pageRows'];
		}else if($_SESSION[CORE_U_CODE]['default_record']!=''){
			$p_row = $_SESSION[CORE_U_CODE]['default_record'];
		}else{
			$p_row = DEFAULT_RECORD;
		}	
		
		if($_SESSION[CORE_U_CODE]['sy_filter'] !=''){
			$sy = $_SESSION[CORE_U_CODE]['sy_filter']; 
		}else{
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
          <option value="20"<?=$p_row==20 ? 'selected=selected':''?>>20</option>
          <option value="50"<?=$p_row==50 ? 'selected=selected':''?>>50</option>
          <option value="100"<?=$p_row==100 ? 'selected=selected':''?>>100</option>
          <option value="200"<?=$p_row==200 ? 'selected=selected':''?>>200</option>            
          <option value="300"<?=$p_row==300 ? 'selected=selected':''?>>300</option>            
          <option value="500"<?=$p_row==500 ? 'selected=selected':''?>>500</option>            
        </select>
    </div>
	
<?php
	} // end of page rows
?>
<div class="box" id="box_container">
<?php
	if($view == 'edit' || $view == 'add'){
?>
	<div class="formview">
    
        <fieldset>
           <legend><strong>Schedule Information</strong></legend>
          	<label><a href="#" class="button" id="dialog_template" returnComp="<?=$_REQUEST['comp']?>"><span>Copy from Template</span></a><br class="hid" /></label>
            <label>&nbsp;</label>
			<label>School Year</label>
            <span >
				<select name="term_id" id="term_id" class="txt_300 {required:true}" title="Section Number field is required" >
                    <?=generateSchoolTerms($term_id)?>
				</select>  
            </span><br class="hid" />
            <label>Section Number</label>
            <span ><input class="txt_100 {required:true}" title="Section Number field is required" name="section_no" type="text" value="<?=$section_no?>" id="section_no" />
            </span><br class="hid" />
            <label>Slots</label>
            <div style="float:left">
				<input class="txt_70 {required:true}" title="Slot field is required" name="number_of_student" type="text" value="<?=$number_of_student?>" id="number_of_student" />   
			</div>
			<div style="width:200px; float:left;">
		<?php
		if($view=='edit'){
			?>
				<label><a href="#" class="button" id="dialog_student_link" returnId="<?=$id;?>" returncomp="<?=$_REQUEST['comp']?>" returnTerm="<?=$term_id;?>"><span>List of Students</span></a><br class="hid" /></label>
		<?php
		}
		?>
			</div>
			<label>Subject</label>
            <span>
            <div style=" float:left">
              <input class="txt" name="subject_id" type="hidden" value="<?=$subject_id?>" id="subject_id" readonly="readonly" />
              <input class="txt_250 {required:true}" title="Subject field is required" name="subject_display" type="text" value="<?=$subject_id!=''?getSubjName($subject_id):$subject_display?>" id="subject_display" readonly="readonly" />
            </div>
            <div style="width:50px; float:left"> <a href="#" class="lookup_button" id="dialog_subject_link" returncomp="<?=$_REQUEST['comp']?>"><span>...</span></a><br class="hid" />
            </div>
            </span><br class="hid" />
			<div id="el_subj" <?=$el_subject_id!=''?'style="display:block"':'style="display:none"' ?>>
				  <label>Elective Subject</label>
					<span>
					<div style=" float:left">
						<input class="txt" name="el_subject_id" type="hidden" value="<?=$el_subject_id?>" id="el_subject_id" readonly="readonly" />
						<input class="txt_250" title="Subject field is required" name="el_subject_display" type="text" value="<?=$el_subject_id!=''?getSubjName($el_subject_id):$el_subject_display?>" id="el_subject_display" readonly="readonly" />
					</div>
					<div style="width:50px; float:left"> <a href="#" class="lookup_button" id="dialog_el_subject_link" returncomp="<?=$_REQUEST['comp']?>"><span>...</span></a><br class="hid" />
					</div>
					</span><br class="hid" />
			 </div>
            <label>Room</label>
            <span>
            <div style=" float:left">
				<input class="txt" name="room_id" type="hidden" value="<?=$room_id?>" id="room_id" readonly="readonly" />
				<input class="txt_250" title="Room field is required" name="room_display" type="text" value="<?=$room_id!=''?getRoomNo($room_id):$room_display?>" id="room_display" readonly="readonly" />
            </div>
            <div style="width:50px; float:left"> <a href="#" class="lookup_button" id="dialog_room_link" returncomp="<?=$_REQUEST['comp']?>"><span>...</span></a><br class="hid" />
            </div>
            </span><br class="hid" />
            <label>Professor</label>
            <span>
            <div style=" float:left">
				<input class="txt" name="employee_id" type="hidden" value="<?=$employee_id?>" id="employee_id" readonly="readonly" />
				<input class="txt_250" title="Professor field is required" name="employee_display" type="text" value="<?=$employee_id!=''?getProfessorFullName($employee_id):$employee_display?>" id="employee_display" readonly="readonly" />
            </div>
            <div style="width:50px; float:left"> <a href="#" class="lookup_button" id="dialog_prof_link" returncomp="<?=$_REQUEST['comp']?>"><span>...</span></a><br class="hid" />
            </div>
            </span>
		</fieldset>
        <fieldset>
           <legend><strong>Schedule Days</strong></legend>
           <label></label>
<br class="hid" />            
</p>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><label> <span >
              <input name="monday"  type="checkbox" value="monday" <?=$monday=='Y'?'checked="checked"':''?> id="0" />
              &nbsp;Monday</span><br class="hid " />
              </label>
                <label> <span >
                <input name="tuesday" type="checkbox" value="tuesday" <?=$tuesday=='Y'?'checked="checked"':''?> id="1"/>
                  &nbsp;Tuesday</span><br class="hid" />
                </label>
                <label> <span >
                <input name="wednesday" type="checkbox" value="wednesday" <?=$wednesday=='Y'?'checked="checked"':''?> id="2"/>
                  &nbsp;Wednesday</span><br class="hid" />
                </label>
                <label> <span >
                <input name="thursday" type="checkbox" value="thursday" <?=$thursday=='Y'?'checked="checked"':''?> id="3"/>
                  &nbsp;Thursday</span><br class="hid" />
                </label>
            </td>
            <td valign="top"><label> <span >
				<input name="friday" type="checkbox" value="friday" <?=$friday=='Y'?'checked="checked"':''?> id="4"/>
				&nbsp;Friday</span><br class="hid" />
				</label>
                <label> <span >
                <input name="saturday" type="checkbox" value="saturday" <?=$saturday=='Y'?'checked="checked"':''?> id="5"/>
                  &nbsp;Saturday</span><br class="hid" />
                </label>
                <label> <span >
                <input name="sunday" type="checkbox" value="sunday" <?=$sunday=='Y'?'checked="checked"':''?> id="6"/>
                  &nbsp;Sunday</span><br class="hid" />
                </label>
				<label> <span >
				<input name="chkTBATIME" type="checkbox" value="TBATIME" id="chkTBATIME"/>
					&nbsp; (TBA)</span><br class="hid" />
				</label>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td><label>Start Time</label>
                <span >
                <select name="time_from" id="time_from" class="txt_100" >
                  <option value="" selected="selected">Select</option>
                  <?=generateTime($time_from,'1',SCHOOL_OPEN_TIME,SCHOOL_CLOSE_TIME)?>
                </select>
                </span><br class="hid" />
            </td>
            <td valign="top"><label>End Time</label>
                <span >
                <select name="time_to" id="time_to" class="txt_100" >
                  <option value="" selected="selected">Select</option>
                  <?=generateTime($time_to,'1',SCHOOL_OPEN_TIME,SCHOOL_CLOSE_TIME)?>
                </select>
                </span><br class="hid" />
            </td>
          </tr>
        </table>
            <span class="clear"></span> 
            <label>&nbsp;</label>
             <a href="#" class="button" title="Add" id="add_sched"><span>Add Schedule</span></a>
        <span class="clear"></span>
        </fieldset>
		<fieldset>
			<legend><strong>Remarks</strong></legend>
			<textarea name="remarks" rows="4" cols="100" style="height:55px;" maxlength="200" ><?=$remarks?></textarea>
		</fieldset>
		
    
    <label>&nbsp;</label>
        <table class="listview" id="tbl_sched">     
        <thead> 
          <tr>
              <th class="col_250">Days</a></th>
              <th class="col_150">Start Time</a></th>
              <th class="col_150">End Time</th>
			  <th class="col_150">Action</th>              
          </tr>
        </thead>
        <tbody>
        <?=$sched?>
        </tbody>          
        </table>
    
        <p class="button_container">
			<input type="hidden" name="id" id="id" value="<?=$id?>" />
<?php if($view == 'add'){?>
			<a href="#" class="button" title="Save" id="save"><span>Save</span></a>
<?php }else if($view == 'edit'){?>
			<a href="#" class="button" title="Update" id="update"><span>Update</span></a>
<?php }?>
		<a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>
        </p>
<!-- TEMPLATE LIST LOOK UP-->
        <div id="template_dialog" title="Template List">
            Loading...
        </div><!-- #dialog -->

		<!-- STUDENT LIST LOOK UP-->
		<div id="student_dialog" title="Student List">
			Loading...
		</div><!-- #dialog-->
		
        <!-- SUBJECT LIST LOOK UP-->
        <div id="subject_dialog" title="Subject List">
            Loading...
        </div><!-- #dialog -->
        
         <!-- ELEC SUBJECT LIST LOOK UP-->
        <div id="el_subject_dialog" title="Subject List">
            Loading...
        </div><!-- #dialog -->
        
        <!-- ROOM LIST LOOK UP-->
        <div id="room_dialog" title="Room List">
            Loading...
        </div><!-- #dialog -->
        
        <!-- PROFESSOR LIST LOOK UP-->
        <div id="prof_dialog" title="Professor List">
            Loading...
        </div><!-- #dialog -->

    </div><!-- /.formview -->
<?php
	}
?>
<p id="formbottom"></p>
</div>
<input type="hidden" name="filter_field" id="filter_field" value="<?=$filter_field?>" />
<input type="hidden" name="filter_order" id="filter_order" value="<?=$filter_order?>" />
<input type="hidden" name="sy_filter" id="sy_filter" value="<?=$sy_filter?>" />
<input type="hidden" name="page" id="page" value="<?=$page?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>