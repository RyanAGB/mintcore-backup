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
	$('#box_container').load('ajax_components/ajax_com_school_details.php?list_rows=10' + param, null);
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
	<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="School Settings"><span>School Settings</span></a></li>
    <!--
    <li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
    -->
    <li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit School Settings"><span>Edit School Settings</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<div class="box" id="box_container">
<?php
	if($view == 'edit' || $view == 'add')
	{
?>
	<div class="formview">
    
        <fieldset>
           <legend><strong>School Information</strong></legend>
            <label>School Name:</label>
            <span ><input class="txt_250" name="school_name" type="text" value="<?=$school_name?>" id="school_name" />
            </span><br class="hid" />
            <label>School Address:</label>
            <span class="small_input"><input class="txt_250" name="school_address" type="text" value="<?=$school_address?>" id="school_address" />
            </span><br class="hid" />
            <label>School City</label>
            <span><input class="txt_250" name="school_city" type="text" value="<?=$school_city?>" id="school_city" />
            </span><br class="hid" />
    		<label>School Postal</label>
            <span><input class="txt_250" name="school_postal" type="text" value="<?=$school_postal?>" id="school_postal" />
            </span><br class="hid" />
            <label>School Telephone</label>
            <span><input class="txt_250" name="school_tel" type="text" value="<?=$school_tel?>" id="school_tel" />
            </span><br class="hid" />
            <label>School Fax</label>
            <span><input class="txt_250" name="school_fax" type="text" value="<?=$school_fax?>" id="school_fax" />
            </span><br class="hid" />
            
            <label>School Open Time</label>
          	<span>
            <select name="school_open_time" id="school_open_time" onchange="checkIfTimeIsValid(this)" class="txt_100" >
                <?=generateTime($school_open_time,'1')?>  
            </select>
            </span><br class="hid" /> 
            <label>School Close Time</label>
          	<span>
            <select name="school_close_time" id="school_close_time" onchange="checkIfTimeIsValid(this)" class="txt_100" >
                <?=generateTime($school_close_time,'1')?>  
            </select>
            </span><br class="hid" /> 
            <span class="clear"></span>
        </fieldset>
        <fieldset>
           <legend><strong>System Information</strong></legend>
                
            </span><br class="hid" /> 
            <label>School Logo:</label>
          	<span>
            <br />
            <?php
			if($school_logo != '')
			{
			?>
            	<img src="includes/getimage.php?id=<?=$id?>" width="275"/>
            <?php
			}
			else
			{
			?>
            	<img src="images/NoPhotoAvailable.jpg"/>
            <?php
			}
			?>
            <br />
            <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
            <input class="txt_250" name="school_logo" type="file" id="school_logo" />
            </span>
            <p>(*maximum size: 550x55)</p>
            <br class="hid" /> 
            <label>System Email Address:</label>
          	<span><input class="txt_250" name="school_sys_email" type="text" value="<?=$school_sys_email?>" id="school_sys_email" />
            </span><br class="hid" />
            <label>System URL:</label>
          	<span><input class="txt_250" name="school_sys_url" type="text" value="<?=$school_sys_url?>" id="school_sys_url" />
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