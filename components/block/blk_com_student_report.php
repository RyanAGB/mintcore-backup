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
			"Ok": function() { 
				$(this).dialog("close"); 
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('.dialog_link').click(function(){
		$('#dialog').load('lookup/lookup_com_department.php', null);
		$('#dialog').dialog('open');
		return false;
	});
	
});	
$(document).ready(function(){  
	
	$('#filter_fieldname').change(function(){
	
		changeValueObject($(this).val());
		changeFilterWay($(this).val());
				
	});
	
	// Initialize the list
	<?php
	if($view != 'add' && $view != 'edit')
	{
		echo 'updateList();'; 
	}
	?>
	var filter_ctr = 0;
	$('#btn_addFilter').click(function(){

		var filter_fieldname = $('#filter_fieldname').val();
		var filter_condition = $('#filter_condition').val();
		
		if(getValueObject(filter_fieldname) == 'date_field')
		{
			var filter_value = $('#field_year').val() + '-' + $('#field_month').val() + '-' + $('#field_day').val();
		}
		else
		{
			var filter_value = $('#'+getValueObject(filter_fieldname)).val();
		}
		
		var htmlContents = $('#searchFilter_container').html();
		if(filter_fieldname != '' && filter_condition != '' && filter_value != '' )
		{
			var filter = '<div id="filter_container_'+filter_ctr+'">' +
						 '<a style="color:red" href="#" title="remove this filter" onclick="$(\'#filter_container_' + filter_ctr + '\').html(\'\'); $(\'#filter_container_' + filter_ctr + '\').css(\'display\',\'none\'); return false;" >Remove</a>&nbsp;&nbsp;' +
						 '<input name="fieldname" id="fieldname" type="hidden" value="' + filter_fieldname + '" />' + 
						 '<input name="fieldcondition" id="fieldcondition" type="hidden" value="' + filter_condition + '" />' + 
						 '<input name="fieldvalue" id="fieldvalue" type="hidden" value="' + filter_value + '" />' + 
						 '<strong>' + getFieldTitle(filter_fieldname) + '</strong> ' + 
						 getConditionValue(filter_condition) + ' ' + 
						 '<span id="filtervalue_container_'+filter_ctr+'">' +getValueMasking(getValueObject(filter_fieldname),filter_value,'filtervalue_container_'+filter_ctr) + '</span>'+
						 '</div>';
			
			$('#searchFilter_container').html(htmlContents + filter);
			filter_ctr++;
		}
		else
		{
			alert('Please fill out the filter fields');
		}
		return false;
	});	

	$('#btn_searchNow').click(function(){
		updateList();
		return false;
	});	
		
	$('#cancel').click(function(){
		clearTabs();
		$('#room_list').addClass('active');
		$('#view').val('list');
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

function changeValueObject(fieldname)
{
	//TB: textbox; DB:dropdown; DT:date; RB:radio button; CB:checkbox     
		if(getValueObject(fieldname) == 'text_field')
		{
			hideAllSearchValue();
			$('#text_field_container').css('display','block');
		}
		else if(getValueObject(fieldname) == 'yr_level_field')
		{
			hideAllSearchValue();
			$('#yr_level_field_container').css('display','block');		
		}
		else if(getValueObject(fieldname) == 'gender_field')
		{
			hideAllSearchValue();
			$('#gender_field_container').css('display','block');		
		}
		else if(getValueObject(fieldname) == 'date_field')
		{
			hideAllSearchValue();
			$('#date_field_container').css('display','block');		
		}
		else if(getValueObject(fieldname) == 'curriculum_field')
		{
			hideAllSearchValue();
			$('#curriculum_field_container').css('display','block');		
		}	
		else if(getValueObject(fieldname) == 'course_field')
		{
			hideAllSearchValue();
			$('#course_field_container').css('display','block');		
		}	
		else if(getValueObject(fieldname) == 'civil_status_field')
		{
			hideAllSearchValue();
			$('#civil_status_field_container').css('display','block');		
		}										
	
}

function changeFilterWay(fieldname)
{
	//0:All condition; 1: equal and not equal only; 2:exact match only; 3: comparative >,<,= is accepted; 4: contains and exact
		if(getFilterWay(fieldname) == '0')
		{
			$('#filter_way_container').html('<select name="filter_condition" id="filter_condition" class="txt_200">'+
												'<option value="EQ">Exact Match</option>' +
												'<option value="EX">Excluding</option>' +
												'<option value="LKA">Contains Same Character</option>'+
												'<option value="LKF">First Character Match</option>'+
												'<option value="GT">Greater Than ( > )</option>'+
												'<option value="LT">Less Than ( < )</option>' +
											'</select>');
		}
		else if(getFilterWay(fieldname) == '1')
		{
			$('#filter_way_container').html('<select name="filter_condition" id="filter_condition" class="txt_200">' +
												'<option value="EQ">Exact Match</option>' +
												'<option value="EX">Excluding</option>' +
											'</select>');
		}
		else if(getFilterWay(fieldname) == '2')
		{
			$('#filter_way_container').html('<select name="filter_condition" id="filter_condition" class="txt_200">' +
												'<option value="EQ">Exact Match</option>' +
											'</select>');
		}	
		else if(getFilterWay(fieldname) == '3')
		{
			$('#filter_way_container').html('<select name="filter_condition" id="filter_condition" class="txt_200">' +
												'<option value="EQ">Exact Match</option>' +
												'<option value="EX">Excluding</option>' +
												'<option value="GT">Greater Than ( > )</option>'+
												'<option value="LT">Less Than ( < )</option>' +
											'</select>');
		}		
		else if(getFilterWay(fieldname) == '4')
		{
			$('#filter_way_container').html('<select name="filter_condition" id="filter_condition" class="txt_200">' +
												'<option value="EQ">Exact Match</option>' +
												'<option value="EX">Excluding</option>' +
												'<option value="LKA">Contains Same Character</option>'+
											'</select>');
		}					
}

function getValueObject(fieldname)
{
	//TB: textbox; DB:dropdown; DT:date; RB:radio button; CB:checkbox     
<?php
			
		$sql = "SELECT * FROM tbl_report_fields WHERE category = 'S' AND publish ='Y' ORDER BY value_type";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query))
		{
			
			echo "if(fieldname == '".$row['field_name']."') return '".$row['value_type']."'; \n";
		}
?>

}

function getValueMasking(fieldname,fieldvalue,obj)
{
	$.ajax({
		type: "POST",
		data: "fieldname="+fieldname+"&fieldvalue="+fieldvalue,
		url: "ajax_components/ajax_com_student_report_value_masking.php",
		success: function(msg){
			if (msg != ''){
				$('#' + obj).html(msg);
			}
		}
	});		
}

function getFieldTitle(fieldname)
{
	//TB: textbox; DB:dropdown; DT:date; RB:radio button; CB:checkbox     
<?php
			
		$sql = "SELECT * FROM tbl_report_fields WHERE category = 'S' AND publish ='Y' ORDER BY value_type";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query))
		{
			
			echo "if(fieldname == '".$row['field_name']."') return '".$row['field_title']."'; \n";
		}
?>

}

function getFilterWay(fieldname)
{
//0:All condition; 1: equal and not equal only; 2:exact match only; 3: comparative >,<,= is accepted; 4: contains and exact
<?php
			
		$sql = "SELECT * FROM tbl_report_fields WHERE category = 'S' AND publish ='Y' ORDER BY value_type";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query))
		{
			
			echo "if(fieldname == '".$row['field_name']."') return '".$row['condition']."'; \n";
		}
?>

}

function getConditionValue(cond)
{
	if(cond == 'EQ')
	{
		 return 'equal';
	}
	else if(cond == 'EX')
	{
		return 'not equal to';
	}
	else if(cond == 'LKA')
	{
		return 'containing';	
	}
	else if(cond == 'LKF')
	{
		return 'start\'s with';	
	}
	else if(cond == 'GT')
	{
		return 'greater than';	
	}
	else if(cond == 'LT')
	{
		return 'less than';	
	}
}


function hideAllSearchValue()
{
	$('#text_field_container').css('display','none');
	$('#date_field_container').css('display','none');
	$('#course_field_container').css('display','none');
	$('#yr_level_field_container').css('display','none');
	$('#gender_field_container').css('display','none');
	$('#civil_status_field_container').css('display','none');
	$('#curriculum_field_container').css('display','none');	
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
	
	if($('input[name=fieldname]').val() != ''  && $('input[name=fieldname]').val() != undefined )
	{
			$.each($('input[name=fieldname]'), function(e, textBox) {
				param =  param + '&fieldNameFilter[]=' + textBox.value ;	
			});
			$.each($('input[name=fieldcondition]'), function(e, textBox) {
				param =  param + '&conditionFilter[]=' + textBox.value ;	
			});
			$.each($('input[name=fieldvalue]'), function(e, textBox) {
				param =  param + '&valueFilter[]=' + textBox.value ;	
			});						
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )
	{
		param = '?list_rows=' + $('#page_rows').val() + param;
	}
	else
	{
		param = '?list_rows=10' + param;
	}
	
	
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_student_report.php' + param, null);
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

<h2><?=$page_title?></h2>
<ul class="tabs">
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">

<div class="filter">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="50%" valign="top">
			<table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td>Field Name</td>
                <td style="padding-left:10px; height:30px;">
                    <select name="filter_fieldname" id="filter_fieldname" class="txt_200">
	                    <option value="" selected="selected">Select field name</option>
                        <?=generateStudentReportFields($filter_fieldname)?>
                    </select>        </td>
              </tr>
              <tr>
                <td>Filter Way</td>
                <td style="padding-left:10px; height:40px;" id="filter_way_container">
                    <select name="filter_condition" id="filter_condition" class="txt_200">
						<option>Contains Same Character</option>
                        <option>Exact Match</option>
                        <option>First Character Match</option>
                        <option>Not Equal To</option>
                        <option>Greater Than ( > )</option>
                        <option>Less Than ( < )</option>
                        
                    </select>        </td>
              </tr>              
              <tr>
                <td>Search Value</td>
                <td style="padding-left:10px;" id="search_value_container">
                <div id="text_field_container">
                	<input name="text_field" id="text_field" type="text" class="txt_200"/>
                </div>
                <div id="date_field_container" style="display:none">
                    <select name="field_month" id="field_month" class="txt_100">
                        <?=generateMonth($s_month)?>
                    </select>
                    <select name="field_day" id="field_day" class="txt_50">
                        <?=generateDay($s_day)?>                                                                        
                    </select>
                    <select name="field_year" id="field_year" class="txt_70">
                        <?=generateYear($s_year)?>                                                    
                    </select>
                </div>
                <div id="course_field_container" style="display:none">
                    <select name="course_field" id="course_field" class="txt_250">
                    	<?=generateCourse($course_id)?>
                    </select> 
                </div>   
                <div id="curriculum_field_container" style="display:none">
                    <select name="curriculum_field" id="curriculum_field" class="txt_250">
                    	<?=generateCurriculum($curriculum_field)?>
                    </select> 
                </div>                   
                <div id="yr_level_field_container" style="display:none">
                    <select name="yr_level_field" id="yr_level_field" class="txt_200">
                    	<?=generateYearLevel($year_level)?>
                    </select> 
                </div>                                
                 <div id="gender_field_container" style="display:none">
                    <select name="gender_field" id="gender_field" class="txt_200">
             			<option value="M">Male</option>
						<option value="F">Female</option>
                    </select>
                </div>
                 <div id="civil_status_field_container" style="display:none">
                    <select name="civil_status_field" id="civil_status_field" class="txt_200">
             			<option value="S">Single</option>
						<option value="M">Married</option>
                    </select>
                </div>                                                      
                </td>
              </tr>
            </table>        
        </td>
        <td width="50%" valign="top">
        	<div><strong>Report Filter List</strong></div>
            <div id="searchFilter_container" style="padding:10px;"></div>
        </td>
      </tr>
      <tr>
        <td style="padding-left:80px; height:40px;">
            <div style="width:100px;">
                <a href="#" class="filter_button" id="btn_addFilter"><span>Add Filter</span></a>
            </div>         
        </td>
        <td>
            <div style="width:115px;">
                <a href="#" class="filter_button" id="btn_searchNow"><span>Search Now</span></a>
            </div>         
        </td>
      </tr>
    </table>


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
<div class="box" id="box_container">

<p id="formbottom"></p>
</div>
<input type="hidden" name="filter_field" id="filter_field" value="<?=$filter_field?>" />
<input type="hidden" name="filter_order" id="filter_order" value="<?=$filter_order?>" />
<input type="hidden" name="cnt_stud" id="cnt_stud" value="<?=mysql_num_rows($result)?>" />
<input type="hidden" name="cnt_sheet" id="cnt_sheet" value="<?=mysql_num_rows($result_per)?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>