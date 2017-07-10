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

	var validator = $("#coreForm").validate({
		errorLabelContainer: $("div.error")
	});

	//initialize the tab action
	$('#room_list').click(function(){
		clearTabs();
		$('#room_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	
	
	$('#upload').click(function(){
		
		if($('#import').val() != '')
		{
			clearTabs();
			$('#map').addClass('active');
			$('#view').val('map');
			$('#action').val('uplod');
			$("form").submit();
		}
		else
		{
			alert('No file selected');
			clearTabs();
			$('#import').addClass('active');
			//updateList();
			return false;
		}
	});	
	
	$('#import_fin').click(function(){
		clearTabs();
		$('#room_list').addClass('active');
		$('#action').val('import_fin');
		$('#view').val('room_list');
		$("form").submit();
		updateList();
	});	
	// Initialize the list
	<?php
	if($view != 'map')
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
	if(pageNum != '' && pageNum != undefined)
	{
		param = param + '&pageNum=' + pageNum;
	}

	if($('#filter_field').val() != '' && $('#filter_field').val() != undefined && $('#filter_order').val() != '' && $('#filter_order').val() != undefined )
	{
		param =  param + '&fieldName=' + $('#filter_field').val() + '&orderBy=' + $('#filter_order').val();
		
	}
		
	$('#box_container').load('ajax_components/ajax_com_import_grade.php' + param, null);
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )
	{
		param = '?list_rows=' + $('#page_rows').val() + param;
	}
	else
	{
		param = '?list_rows=10';
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
		
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_import_grade.php' + param, null);
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
<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Import"><span>Import</span></a></li>
<li id="map" <?=$view=='map'?'class="active"':''?>><a href="#" title="Import Map"><span>Import Map</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">

<div class="box" id="box_container">
 <?php
	if($view == 'map')
	{
		if($num != 3)
			{
				echo '<div id="message_container"><h4>Number of CSV data did not match number of required Fields</h4></div>';
	?>
    			<div class="formview">
    			<div class="fieldsetContainer50">
                 <p class="button_container">
            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>
        		</p>
                </div>
                </div>
        <?php
			}
			else
			{
		?>

	<div class="formview">
    <div class="fieldsetContainer50">
        <fieldset>
           <legend><strong>Import Map</strong></legend>
            <?php
				for($ctr=1;$ctr<=$num;$ctr++){
			?>
            <label>Table Field for Coulumn<?=$ctr?></label>
            <span >
           
                <select name="field_<?=$ctr?>" id="field_<?=$ctr?>" class="txt_150" >
    
                    <option value="" >-Select Field-</option>
                    <option value="student_id" >Student Number</option>
                    <option value="subject_id" >Subject Code</option>
                    <option value="final_grade" >Final Grade</option>
        
                </select>   
            <?php
				}
			?> 
            </span><br class="hid" /> 
          
            <span class="clear"></span>
        </fieldset>
    
        
        <p class="button_container">
        <input type="hidden" name="num" id="num" value="<?=$num?>" />
           <input type="hidden" name="uploadfile" id="uploadfile" value="<?=$uploadfile?>" />
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
         <?php if($forbid == ''){ ?>
                <a href="#" class="button" title="Import" id="import_fin"><span>Import</span></a>
          <?php } ?>
            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>
        </p>
        
    </div><!-- /.formview --></div>
<?php
}
	}
?>
<p id="formbottom"></p>
</div>
<input type="hidden" name="import" id="import" value="<?=$import?>" />
<input type="hidden" name="pageNum" id="pageNum" value="<?=$pageNum?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>