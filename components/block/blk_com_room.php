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
	$("a[name*='flag']").click(function() {
	
		var valTime = $(this).attr("returnTime");
		var valDay = $(this).attr("returnDay");
		var valObj = $(this).attr("returnObj");

		if($(this).attr('class')=='checkmark')
		{
			$('#'+valObj).attr('checked','');	
			$(this).attr("class","xmark");
		}
		else
		{
			$('#'+valObj).attr('checked','checked');	
			$(this).attr("class","checkmark");		
		}		
		
	});

	
	$('.chkday').click(function() {

		if($(this).attr('checked') == false)
		{
			$("a[returnDay='"+$(this).val()+"']").attr("class","xmark");
			$("input[objDay='"+$(this).val()+"']").attr('checked','');
		}
		else
		{
			$("a[returnDay='"+$(this).val()+"']").attr("class","checkmark");
			$("input[objDay='"+$(this).val()+"']").attr('checked','checked');		
		}
	});

	
	$('.chktime').click(function() {

		if($(this).attr('checked') == false)
		{
			$("a[returnTime='"+$(this).val()+"']").attr("class","xmark");
			$("input[objTime='"+$(this).val()+"']").attr('checked','');	
		}
		else
		{
			$("a[returnTime='"+$(this).val()+"']").attr("class","checkmark");	
			$("input[objTime='"+$(this).val()+"']").attr('checked','checked');		
		}
	});

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
			$('#action').val('list');	
			updateList();
			return false;
		}
	});

	$('#availability').click(function(){
		if(checkbox_checker())
		{
			validator.resetForm();
			clearTabs();
			$('#availability').addClass('active');
			$('#view').val('availability');
			$('#box_container').html(loading);
			$("form").submit();
		}
		else
		{
			validator.resetForm();
			alert('No item was selected.');
			$('#action').val('list');	
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

	$('#save_avail').click(function(){
		clearTabs();
		$('#availability').addClass('active');
		$('#action').val('save_avail');
		$('#view').val('availability');
		$("form").submit();
	});		
	
	$('#update').click(function(){
		clearTabs();
		$('#edit_item').addClass('active');
		$('#action').val('update');
		$('#view').val('edit');
		$("form").submit();
	});		
	
	$('#filteroom,#filterbuild').change(function(){
		$('#filteroom2').val($('#filteroom').val());
		$('#filterbuild2').val($('#filterbuild').val());
		updateList();
	});
	// Initialize the list
	<?php
	if($view != 'add' && $view != 'edit' && $view != 'availability')
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
	
	if($('#filteroom2').val() != '' && $('#filterbuild2').val() != '')
		{
			param = param + '&filterfield=building_id&filterbuilding_id=' + $('#filterbuild2').val()+ '&filterroom=room_type&filterroom_type='+$('#filteroom2').val();
		}
		else if($('#filteroom2').val() == '' && $('#filterbuild2').val() != '')
		{
			param = param + '&filterfield=building_id&filterbuilding_id=' + $('#filterbuild2').val();
		}
		
	if($('#filterbuild2').val() != '' && $('#filteroom2').val() != '')
		{
			param = param + '&filterroom=room_type&filterroom_type='+$('#filteroom2').val()+ '&filterfield=building_id&filterbuilding_id=' + $('#filterbuild2').val();
		}
		else if($('#filterbuild2').val() == '' && $('#filteroom2').val() != '')
		{
			param = param + '&filterroom=room_type&filterroom_type='+$('#filteroom2').val();
		}
	
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val();
	}
	if($('#canEdit').val() != '' && $('#canEdit').val() != undefined )
	{
		param = param + '&canEdit=' + $('#canEdit').val();
	}
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#page_rows').val() + param;
	}
//alert(param);
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_room.php?param=1' + param, null);
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
<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Room List"><span>Room List</span></a></li>
<?php 
if($canEdit=='Y')
{
?>
<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit Item"><span>Edit Item</span></a></li>
<li id="availability" <?=$view=='availability'?'class="active"':''?>><a href="#" title="Availability"><span>Availability</span></a></li>
<?php } ?>
</ul>
<form name="coreForm" id="coreForm" method="post" action=""  class="col50" enctype="multipart/form-data">
<?php
	if($view == 'list')
	{
	?>
	
<div class="filter">
    Filter By: Building <select name="filterbuild" id="filterbuild" class="txt_150" >
    
                    <option value="" >-Select-</option>
                     <?=generateBuilding($filterbuild2)?>  
                </select>
                
           	Room Type <select name="filteroom" id="filteroom" class="txt_100" >
                    <option value="" >-Select-</option>
                    <option value="lec" <?=$filteroom2 == 'lec' ? 'selected="selected"' : '';?> >Lec</option>
                    <option value="lab" <?=$filteroom2 == 'lab' ? 'selected="selected"' : '';?> >Lab</option>
                    <option value="field" <?=$filteroom2 == 'field' ? 'selected="selected"' : '';?> >Field</option> 
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
    	<div class="fieldsetContainer50">
            <fieldset>
               <legend><strong>Room Information</strong></legend>
                <label>Room No:</label>
                <span ><input class="txt_100 {required:true}" title="Room No. field is required" name="room_no" type="text" value="<?=$room_no?>" id="room_no" />
                </span><br class="hid" />
                <label>Room Type:</label>
                <span>
                <select name="room_type" id="room_type" class="txt_250 {required:true}" title="Room Type field is required" >
                    <option value="" selected="selected">Select</option>
                    <option value="Lec"  <?=$room_type== "Lec"?'selected="selected"':''?>>Lec</option>
                    <option value="Lab"   <?=$room_type== "Lab"?'selected="selected"':''?>>Lab</option>
                    <option value="Field"  <?=$room_type== "Field"?'selected="selected"':''?>>Field</option>
                </select>
                </span>
                <br class="hid" />
                <label>Building:</label>
                <span>
                    <select name="building_id" id="building_id" class="txt_250 {required:true}" title="Building field is required" >
                        <option value="" selected="selected">Select</option>
                        <?=generateBuilding($building_id)?>
                    </select>    
                </span><br class="hid" />
        
                <span class="clear"></span>
            </fieldset>
        </div>
        <div class="fieldsetContainer50">
            <fieldset>
               <legend><strong>System Information</strong></legend>
                <label>Publish:</label>
                <span >
                    <select name="publish" id="publish" class="txt_150 {required:true}" title="Publish field is required" >
                        <option value="" selected="selected">Select</option>
                        <option value="Y" <?=$publish== "Y"?'selected = "selected"':''?> >Yes</option>
                        <option value="N" <?=$publish== "N"?'selected = "selected"':''?> >No</option>
                    </select>    
                </span><br class="hid" /> 
              
                <span class="clear"></span>
            </fieldset>     
    	</div>
        
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
	else if($view == 'availability')
	{
?>
	<div class="formview">
        <table class="listview">      
          <tr>
	          <th class="col_100"><label>Time</label></th>
              <th class="col_100"><label><input name="" type="checkbox" value="M" class="chkday" />&nbsp;&nbsp;Mon</label></th>
              <th class="col_100"><label><input name="" type="checkbox" value="T" class="chkday" />&nbsp;&nbsp;Tue</label></th>
              <th class="col_100"><label><input name="" type="checkbox" value="W" class="chkday" />&nbsp;&nbsp;Wed</label></th>
              <th class="col_100"><label><input name="" type="checkbox" value="TH" class="chkday" />&nbsp;&nbsp;Thu</label></th>
              <th class="col_100"><label><input name="" type="checkbox" value="F" class="chkday" />&nbsp;&nbsp;Fri</label></th>
              <th class="col_100"><label><input name="" type="checkbox" value="S" class="chkday" />&nbsp;&nbsp;Sat</label></th>
              <th class="col_100"><label><input name="" type="checkbox" value="SU" class="chkday" />&nbsp;&nbsp;Sun</label></th>
          </tr>
          <?=generateRoomTimeTable($id,0,SCHOOL_OPEN_TIME,SCHOOL_CLOSE_TIME)?>                                                                                                                                           
        </table>
        <p class="button_container">
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
            <a href="#" class="button" title="Save" id="save_avail"><span>Save</span></a>
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
<input type="hidden" name="filteroom2" id="filteroom2" value="<?=$filteroom2?>" />
<input type="hidden" name="filterbuild2" id="filterbuild2" value="<?=$filterbuild2?>" />
<input type="hidden" name="page" id="page" value="<?=$page?>" />
<input type="hidden" name="canEdit" id="canEdit" value="<?=$canEdit?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>