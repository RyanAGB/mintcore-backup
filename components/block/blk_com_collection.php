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
	/*else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../../forbid.html');
	}*/

?>

<script type="text/javascript">
	
$(document).ready(function(){  
	
	$('#f_month,#f_day,#f_year,#t_month,#t_day,#t_year').change(function(){
		updateList();
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
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )
	{
		param = '?list_rows=' + $('#page_rows').val();
	}
	else
	{
		param = '?list_rows=10';
	}
	
	if(pageNum != '' && pageNum != undefined)
	{
		param = param + '&pageNum=' + pageNum;
	}
	
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val();
	}
	
	var date1 = $('#f_year').val()+"-"+$('#f_month').val()+"-"+$('#f_day').val();
	var date2 = $('#t_year').val()+"-"+$('#t_month').val()+"-"+$('#t_day').val();
	
		param = param + '&f_date=' +date1;
		
		param = param + '&t_date=' +date2;
	
		
	//alert(param);
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_collection.php' + param, null);
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
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">

<!--<div class="filter">
    Select School Term&nbsp;&nbsp;
    <select name="filter_schoolterm" id="filter_schoolterm" class="txt_200">
    	<?=generateSchoolTerms($filter_schoolterm)?>
    </select>
</div>

<?php
	if($view == 'list')
	{
		/*if(isset($_SESSION[CORE_U_CODE]['pageRows'])&&($_SESSION[CORE_U_CODE]['pageRows']!=''))
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
		}	*/
		
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
    </div>  !-->
    
    <div class="filter">
    Date From&nbsp;&nbsp;
   <select name="f_month" id="f_month" class="txt_100 {required:true}" title="Start Date Month field is required">
                    <option value="" selected="selected"></option>
                    <?=generateMonth(date('m'))?>
                </select>
                <select name="f_day" id="f_day" class="txt_50 {required:true}" title=" Start Date Day field is required">
                    <option value="" selected="selected"></option>
                    <?=generateDay(date('d'))?>                                                                        
                </select>
                <select name="f_year" id="f_year" class="txt_70 {required:true}" title="Start Date Year field is required">
                    <option value="" selected="selected"></option>
                    <?=generateYearForSchoolYear(date('Y'))?>                                                    
                </select>
                
    To &nbsp;&nbsp;
   <select name="t_month" id="t_month" class="txt_100 {required:true}" title="Start Date Month field is required">
                    <option value="" selected="selected"></option>
                    <?=generateMonth(date('m'))?>
                </select>
                <select name="t_day" id="t_day" class="txt_50 {required:true}" title=" Start Date Day field is required">
                    <option value="" selected="selected"></option>
                    <?=generateDay(date('d'))?>                                                                        
                </select>
                <select name="t_year" id="t_year" class="txt_70 {required:true}" title="Start Date Year field is required">
                    <option value="" selected="selected"></option>
                    <?=generateYearForSchoolYear(date('Y'))?>                                                    
                </select>            
</div>
<?php
	} // end of page rows
?>

<div class="box" id="box_container">
<?php
	if($view == 'edit')
	{
?>
	<div class="formview">
		
   
        <p class="button_container">
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
            <?php if ($lock != '1'){ ?>
                <a href="#" class="button" title="Save" id="update"><span>Save</span></a>
			
                <?php } ?>
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
<input type="hidden" name="cnt_stud" id="cnt_stud" value="<?=mysql_num_rows($result)?>" />
<input type="hidden" name="cnt_sheet" id="cnt_sheet" value="<?=mysql_num_rows($result_per)?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>