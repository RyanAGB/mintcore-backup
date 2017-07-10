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
	$('#subject_list').click(function(){
	//alert('AAAAAAAAAAAAAA');
		clearTabs();
		$('#subject_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	
	
	$('#enroll_block').click(function(){
	//alert('AAAAAAAAAAAAAA');
		clearTabs();
		$('#enroll_block').addClass('active');
		$('#view').val('block');
		//$('#action').val('list');
		updateList();
		//$("form").submit();
	});	
	
	// Initialize the list	
	
	<?php
	if($view != 'add' && $view != 'edit')
	{
		echo 'updateList();'; 
	}
	?>

	
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
	<?php
		/*if(checkIfStudentIsReserve(USER_STUDENT_ID) || checkIfStudentIsEnrolled(USER_STUDENT_ID))
		{*/
	?>
			$('#box_container').load('ajax_components/ajax_com_st_reserve_subject.php?list_rows=10' + param, null);
	<?php
		/*}
		else
		{
	?>
		$('#box_container').load('ajax_components/ajax_com_st_select_schedule.php?list_rows=10' + param, null);		
	<?php
		}*/
	?>
}

</script>
<?php
/*if(checkIfStudentReservationIsExpired())
{
?>   
    <p class="alert">
        <span class="txt"><span class="icon"></span><strong>Alert:</strong> Your reservation has expired. Please select your new schedule.</span>
        <a href="#" class="close" title="Close"><span class="bg"></span>Close</a>
    </p>
<?php
}*/
?>

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
<li id="subject_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Per Subject"><span>Assessment</span></a></li>
<!--<li id="enroll_block" <?=$view=='block'?'class="active"':''?>><a href="#" title="Enroll Block"><span>Enroll Block</span></a></li>!-->
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="col50" enctype="multipart/form-data">
<div class="box" id="box_container">

<p id="formbottom"></p>

</div>


<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="sched" id="sched" value="<?=$sched?>" />
<input type="hidden" name="term" id="term" value="<?=CURRENT_TERM_ID?>" />
<input type="hidden" name="cid" id="cid" value="<?=USER_CURRICULUM_ID?>" />
<input type="hidden" name="stdid" id="stdid" value="<?=USER_STUDENT_ID?>" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>