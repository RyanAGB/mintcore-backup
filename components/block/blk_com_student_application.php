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
	$('#dialog').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Close": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('.applicant').click(function(){
		var param = $(this).attr("returnId");
		$('#dialog').load('lookup/lookup_com_student_decline.php?id='+param, null);
		$('#dialog').dialog('open');
		return false;
	});
	
});

$(document).ready(function(){  
	
	//initialize the tab action
	
	$('#room_list').click(function(){
		clearTabs();
		$('#room_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	
	
	$('#add_new').click(function(){
		clearTabs();
		$('#add_new').addClass('active');
		$('#view').val('add');
		updateList();
	});	
	
	$('#save').click(function(){
		/*var chek = $('#cheks').val().split(',');
		var cnt = 0;
		var cnt2 = 0;
		var cnt3 = 0;
		var num = $('#number').val();
		var score = $('#score').val();
		var chk = $('#chk').val();
		for(var x=0;x<=chek.lenght;x++)
		{
			if(num[chek[x]]!='')
			{
				cnt++;
			}
			if(score[chek[x]]!='')
			{
				cnt2++;
			}
			for(var y=0;y<=chk[chek[x]].lenght;y++)
			{
				if(chk[chek[x]][y].attr('checked'))
				{
					cnt3++;
				}
			}
		}
		
		if(cnt==chek.lenght&&cnt2==chek.lenght&&cnt3>0)
		{*/
			clearTabs();
			$('#room_list').addClass('active');
			$('#view').val('app');
			$('#action').val('save');
			$("form").submit();
		/*}
		else
		{
			alert('Some required fields are missing.');
			return false;
		}*/
	});
	// Initialize the list
	<?php
	if($view != 'add' && $view !='app')
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
	$('.sortBy').click(function(){
	
		if($('#filter_order').val() == '' || $('#filter_order').val() == 'DESC')
		{
			var order = 'ASC';
		}
		else
		{
			var order = 'DESC';
		}
		
		$('#view').val('add');
		$('#filter_field').val($(this).attr('returnFilter'));
		$('#filter_order').val(order);
		//updateList();
		//return false;
		$("form").submit();
	});
	
	$('#page_rows').change(function(){
		updateList();
	});
	
	$('#filter_schoolterm').change(function(){
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
	
	if($('#filter_schoolterm').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#filter_schoolterm').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val();
	}
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#page_rows').val();
	}
	
	$('#box_container').html(loading);
	if($('#view').val() != 'add')
	{
	$('#box_container').load('ajax_components/ajax_com_student_application.php?param=1' + param, null);
	}
	else
	{
	 $('#box_container').load('ajax_components/ajax_com_student_decline.php?param=1' + param, null);
	}
	
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
<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Applicant List"><span>Applicant List</span></a></li>
<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Declined Applicants"><span>Declined Applicants</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="col50" enctype="multipart/form-data">

<?php
	if($view == 'list')
	{
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
<div class="filter">
    Select School Term&nbsp;&nbsp;
    <select name="filter_schoolterm" id="filter_schoolterm" class="txt_200">
    	<?=generateSchoolTermsForApplicants($filter_schoolterm)?>
    </select>
</div>
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
	if($view == 'app')
	{
	?>
    <div class="formview">
<?php
		echo $list;
	?>
    </div>
<?php
	}
?>

<p id="formbottom"></p>
</div>
<input type="hidden" name="filter_field" id="filter_field" value="<?=$field?>" />
<input type="hidden" name="filter_order" id="filter_order" value="<?=$ord?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="sid" id="sid" value="<?=$sId?>" />
<input type="hidden" name="cheks" id="cheks" value="<?=$cheks?>" />
<input type="hidden" name="req" id="req" value="<?=$req?>" />

<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>
<!-- LIST LOOK UP-->
<div id="dialog" title="Student Profile">
    Loading...
</div><!-- #dialog -->