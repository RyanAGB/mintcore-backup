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
	
	$('#list').click(function(){
		clearTabs();
		$('#list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		$('#student_id').val('');
		updateList();
		$("form").submit();
	});	

	$('#save').click(function(){
	//alert('xxxxxxxx');
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
	if($view == 'payment')
	{
		echo 'schedList();'; 
	}
	else
	{
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
	
	if($('#rows').val() != '' && $('#rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#rows').val();
	}
	
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
	
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_system_log.php?param=1' + param, null);
	
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
<!--<li id="list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Other Payment"><span>Other Payment</span></a></li>
<li id="payment" <?=$view=='enroll'?'class="active"':''?>><a href="#" title="Enroll"><span>Payment</span></a></li>!-->
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="col50" enctype="multipart/form-data">
<div class="box" id="box_container">
		<div class="formview">
<?php
	if($view == 'list')
	{
?>
     
<?php
	} // end of page rows
?>	
    
        
		
		</div><!-- /.formview -->
	</div>

	<input type="hidden" name="temp" id="temp" value="" />
    <input type="hidden" name="id" id="id" value="<?=$id?>" />
    <input type="hidden" name="page" id="page" value="<?=$page?>" />
    <input type="hidden" name="rows" id="rows" value="<?=$rows?>" />
	<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
	<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />
	<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>