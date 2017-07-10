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
	
	//initialize the tab action
	$('#add_new').click(function(){
		clearTabs();
		$('#add_new').addClass('active');
		$('#view').val('add');
		$("form").submit();
	});
	
	$('#edit_item').click(function(){
		
			clearTabs();
			$('#edit_item').addClass('active');
			$('#view').val('edit');
			$("form").submit();
		
	});
	
	$('#room_list').click(function(){
		clearTabs();
		$('#room_list').addClass('active');
		$('#view').val('list');
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
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}

	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_system_settings.php?list_rows=10' + param, null);
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
<ul class="tabs">
	<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="System Settings"><span>System Settings</span></a></li>
    <!--
    <li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
    -->
    <li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit System Settings"><span>Edit System Settings</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<div class="box" id="box_container">
<?php
	if($view == 'edit' || $view == 'add')
	{
?>
	<div class="formview">
    
        <fieldset>
           <legend><strong>System General Settings</strong></legend>
            <label>Account Activation:</label>
            <span >
            <select name="activation_by" id="activation_by" class="txt_200" >
                <option value="D" <?php if ($activation_by=="D"){ ?>selected="selected"<?php } ?>>Disable</option>
                <option value="N" <?php if ($activation_by=="N"){ ?>selected="selected"<?php } ?>>None</option>
                <option value="A" <?php if ($activation_by=="A"){ ?>selected="selected"<?php } ?>>By Admin</option>
            </select>
            </span><br class="hid" />
            
            <label>Password Minimum Length:</label>
            <span class="small_input"><input class="txt_50" name="password_min" type="text" value="<?=$password_min?>" id="password_min" /> 
            </span><br class="hid" />
            <label>Password Maximum Length:</label>
            <span class="small_input"><input class="txt_50" name="password_max" type="text" value="<?=$password_max?>" id="password_max" />
            </span><br class="hid" />
            
            <label>Password Complexity:</label>
          	<span>
            <select name="password_complexity" id="password_complexity" class="txt_200" >
                <option value="NR" <?php if ($password_complexity=="NR"){ ?>selected="selected"<?php } ?>>No requirements</option>
                <option value="MC" <?php if ($password_complexity=="MC"){ ?>selected="selected"<?php } ?>>Must be mixed case</option>
                <option value="LN" <?php if ($password_complexity=="LN"){ ?>selected="selected"<?php } ?>>Must contain letters and numbers</option>
                <option value="WS" <?php if ($password_complexity=="WS"){ ?>selected="selected"<?php } ?>>Must contain symbols</option>
            </select>
            </span><br class="hid" /> 
            
            <label>Default number of Record:</label>
            <span class="small_input"><input class="txt_50" name="default_record" type="text" value="<?=$default_record?>" id="default_record" />
            </span><br class="hid" />
            
             <label>Notifications:</label>
            <span >
            <select name="notification" id="notification" class="txt_100" >
                 <option value="OFF" <?php if ($notification=="OFF"){ ?>selected="selected"<?php } ?>>Off</option>
                <option value="ON" <?php if ($notification=="ON"){ ?>selected="selected"<?php } ?>>On</option>
            </select>
            </span><br class="hid" />
            
            <span class="clear"></span>
        </fieldset>
        <fieldset>
           <legend><strong>System General Options</strong></legend>
 
            <label>Maximum Login Attempts:</label>
          	<span><input class="txt_100" name="max_login_attempt" type="text" value="<?=$max_login_attempt?>" id="max_login_attempt" />
            </span><br class="hid" /> 
            
            <label>Total Failed Login:</label>
          	<span><input class="txt_100" name="total_failed_login" type="text" value="<?=$total_failed_login?>" id="total_failed_login" />
            </span><br class="hid" />  
            
            <label>Time To Re-login:</label>
          	<span><input class="txt_100" name="time_to_relogin" type="text" value="<?=$time_to_relogin?>" id="time_to_relogin" />
            </span><br class="hid" />
            
            <label>Simultaneous Login:</label>
            <span >
            <select name="allowed_sim_login" id="allowed_sim_login" class="txt_100" >
                 <option value="N" <?php if ($allowed_sim_login=="N"){ ?>selected="selected"<?php } ?>>No</option>
                <option value="Y" <?php if ($allowed_sim_login=="Y"){ ?>selected="selected"<?php } ?>>Yes</option>
            </select>
            </span><br class="hid" />
            
            <label>Enable Online Enrollment:</label>
            <span >
            <select name="enable_enrollment" id="enable_enrollment" class="txt_100" >
                <option value="N" <?php if ($enable_enrollment=="N"){ ?>selected="selected"<?php } ?>>No</option>
                <option value="Y" <?php if ($enable_enrollment=="Y"){ ?>selected="selected"<?php } ?>>Yes</option>
            </select>
            </span><br class="hid" />
            
            <label>Set Whole System:</label>
            <span >
            <select name="set_system" id="set_system" class="txt_100" >
                <option value="OFF" <?php if ($set_system=="OFF"){ ?>selected="selected"<?php } ?>>Offline</option>
                <option value="ON" <?php if ($set_system=="ON"){ ?>selected="selected"<?php } ?>>Online</option>
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
?>
<p id="formbottom"></p>
</div>
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>