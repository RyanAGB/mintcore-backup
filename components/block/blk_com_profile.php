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

	$('#box_container').load('ajax_components/ajax_com_profile.php?list_rows=10' + param, null);
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
	<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="System Settings"><span>Profile</span></a></li>
    <!--
    <li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
    -->
    <li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit System Settings"><span>Edit Profile</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<div class="box" id="box_container">
<?php
	if($view == 'edit' || $view == 'add')
	{
?>
	<div class="formview">
        <fieldset>
           <legend><strong>Account Options</strong></legend>
            <label>Old Password:</label>
          	<span>
            <input class="txt_200" name="old_password" type="password" value="<?=$old_password?>" id="old_password" />
            </span><br class="hid" />
            
            <label>New Password:</label>
            <span >
            <input class="txt_200" name="new_password" type="password" value="<?=$new_password?>" id="new_password" />
            </span><br class="hid" />
            
            <label>Confirm Password:</label>
            <span >
            <input class="txt_200" name="confirm_password" type="password" value="<?=$confirm_password?>" id="confirm_password" />
            </span><br class="hid" />
              
            <span class="clear"></span>
        </fieldset>
        <fieldset>
           <legend><strong>Email Options</strong></legend>
            <label>Old Email Address:</label>
          	<span>
            <input class="txt_200" name="old_email" type="text" value="<?=$old_email?>" id="old_email" />
            </span><br class="hid" />
            
            <label>New Email Address:</label>
            <span >
            <input class="txt_200" name="new_email" type="text" value="<?=$new_email?>" id="new_email" />
            </span><br class="hid" />
            
            <label>Confirm Email Address:</label>
            <span >
            <input class="txt_200" name="confirm_email" type="text" value="<?=$confirm_email?>" id="confirm_email" />
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