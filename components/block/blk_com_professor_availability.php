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
	
	$('#availability').click(function(){
		if(checkbox_checker())
		{
			validator.resetForm();
			clearTabs();
			$('#availability').addClass('active');
			$('#view').val('availability');
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
	
	$('#prof_list').click(function(){
		clearTabs();
		$('#prof_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	
	
	$('#save_avail').click(function(){
		clearTabs();
		$('#add_new').addClass('active');
		$('#action').val('save_avail');
		$('#view').val('availability');
		$("form").submit();
	});		
	
	// Initialize the list
	<?php
	if($view != 'availability')
	{
		echo 'updateList();'; 
	}
	?>

	$('#cancel').click(function(){
		clearTabs();
		$('#prof_list').addClass('active');
		$('#view').val('list');
		updateList();
		$("form").submit();
	});	
	$('#page_rows').change(function(){
		$('#page').val(1);
		updateList();
	});
	
	$('#filterdept').change(function(){
		$('#filter_dept').val($('#filterdept').val());
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
	if($('#filter_dept').val() != '' && $('#filter_dept').val() != undefined )
	{
		param = param + '&filterdept=' + $('#filter_dept').val();
	}
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#page_rows').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}

	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_professor_availability.php?param=1' + param, null);
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
<li id="prof_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Professor List"><span>Professor List</span></a></li>
<li id="availability" <?=$view=='availability'?'class="active"':''?>><a href="#" title="Availability"><span>Availability</span></a></li>
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
    
    <div class="filter">
    Filter By: Department <select name="filterdept" id="filterdept" class="txt_200" >
    
                    <option value="" >-Select-</option>
                     <?=generateDepartment($filter_dept)?>  
                </select>   
</div>
<?php
	} // end of page rows
?>
<div class="box" id="box_container">
<?php
	if($view == 'availability')
	{
?>
	<div style="padding:10px; ">
	<table class="classic_borderless">
      <tr>
        <td style=" font-weight:bold; font-size:12px">Professor Name: &nbsp;</td>
        <td style="font-size:12px"><?=getEmployeeFullName($id)?></td>
      </tr>
      <tr>
        <td style=" font-weight:bold; font-size:12px">Employee Number: &nbsp;</td>
        <td style="font-size:12px"><?=getEmployeeNumber($id)?></td>
      </tr>
      <tr>
        <td style=" font-weight:bold; font-size:12px">Department: &nbsp;</td>
        <td style="font-size:12px"><?=getDeptName(getEmployeeDeparmentId($id))?></td>
      </tr>      
    </table>  
    </div>
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
          <?=generateProfTimeTable($id,0,SCHOOL_OPEN_TIME,SCHOOL_CLOSE_TIME)?>                                                                                                                                           
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
<input type="hidden" name="filter_dept" id="filter_dept" value="<?=$filter_dept?>" />
<input type="hidden" name="page" id="page" value="<?=$page?>" />
<input type="hidden" name="id" id="id" value="<?=$id?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>