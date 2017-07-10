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
	/*$('#save').click(function(){
	alert(importS);
		if(document.getElementById("imports").value != '' && $('#importF').val() != '')
		{
			clearTabs();
			$('#edit_item').addClass('active');
			$('#view').val('edit');
			$("form").submit();
		}
		else
		{
			alert('No File is set to map');
			clearTabs();
			$('#room_list').addClass('active');
			updateList();
			return false;
		}
	});*/
	
	$('#edit_item').click(function(){
		var im = document.getElementById("imports").value
		var imF = document.getElementById("importfile").value
		
		if(im == '' && imF == '')
		{
			alert('Set-up files first');
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
		updateList();
		$("form").submit();
	});	

	
	$('#update').click(function(){
		clearTabs();
		$('#room_list').addClass('active');
		$('#action').val('update');
		$('#view').val('list');
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

	$('#box_container').load('ajax_components/ajax_com_import.php?list_rows=10' + param, null);
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
<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Import"><span>Import</span></a></li>
<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Map file"><span>Mapping</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<div class="box" id="box_container">
<?php
	if($view == 'edit')
	{
?>
	<div class="formview">
        <fieldset>
           <legend><strong>Import Information</strong></legend>
            <?php
			if($sub == 'subject')	{
				for($ctr=1;$ctr<=$num;$ctr++){
			?>
            <label>Table Field for Coulumn<?=$ctr?></label>
            <span >
           
                <select name="field_<?=$ctr?>" id="field_<?=$ctr?>" class="txt_150" >
    
                    <option value="subject_code" >Subject Code</option>
                    <option value="subject_name" >Subject Name</option>
                    <option value="subject_type" >Subject Type</option>
                    <option value="department_id" >Department</option>
        
                </select>   
            <?php
				}
			}
			?> 
            <?php
			if($sub == 'course')	{
				for($ctr=1;$ctr<=$num;$ctr++){
			?>
            <label>Table Field for Coulumn<?=$ctr?></label>
            <span >
           
                <select name="field_<?=$ctr?>" id="field_<?=$ctr?>" class="txt_150" >
    
                    <option value="course_code" >Course Code</option>
                    <option value="course_name" >Course Name</option>
                    <option value="description" >Description</option>
                    <option value="college_id" >College</option>
        
                </select>   
            <?php
				}
			}
			?> 
            </span><br class="hid" /> 
          
            <span class="clear"></span>
        </fieldset>
    
        
        <p class="button_container">
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
         
                <a href="#" class="button" title="Update" id="update"><span>Import</span></a>
          
            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>
        </p>
        
    </div><!-- /.formview -->
<?php
	}
?>
<p id="formbottom"></p>
</div>
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="num" id="num" value="<?=$num?>" />
<input type="hidden" name="sub" id="sub" value="<?=$sub?>" />
<input type="hidden" name="upload" id="upload" value="<?=$uploadfile?>" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>