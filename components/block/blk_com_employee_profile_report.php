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
	$('.profile').click(function(){
		var param = $(this).attr("returnId");
		$('#dialog').load('viewer/view_com_employee_profile_report.php?id='+param, null);
		$('#dialog').dialog('open');
		return false;
	});
	
});	

$(document).ready(function(){  

	var validator = $("#coreForm").validate({
		errorLabelContainer: $("div.error")
	});

	//initialize the tab action
	
	$('#edit_item').click(function(){
		
			alert('Search First');
			updateList();
			return false;
	});
	
	$('#room_list').click(function(){
		clearTabs();
		$('#room_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	

	$('#print').click(function(){
	//alert(document.getElementById('con').value);
	if(document.getElementById('canP').value != ''){
	var con = document.getElementById('con').value;
	window.open('ajax_components/print_emp_info.php?print='+con);
	}else{
		alert('Search First');
			updateList();
			return false;
	}
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
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	
	
	$('#page_rows').change(function(){
		updatePage($('#pagenum').val(0),$('#page_rows').val());
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
		
		$('#view').val('edit');
		$('#filter_field').val($(this).attr('returnFilter'));
		$('#filter_order').val(order);
		//updateList();
		//return false;
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
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_employee_profile_report.php?list_rows=10' + param, null);
}

function updatePage(pageNum,pageRow)
{
	$('#view').val('edit');
	$('#pagenum').val(pageNum);
	$('#page_rows').val(pageRow);
	$("form").submit();
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
<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Search"><span>Search</span></a></li>
<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Result"><span>Result</span></a></li>
<li id="print" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Print"><span>Print</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<div class="box" id="box_container">
<?php
	if($view == 'edit')
	{
?>
<div id="pageRows">
        <span>show</span>
        <select name="page_rows" id="page_rows">
          <option value="10" <?php if($_REQUEST['page_rows'] == 10){ ?> selected="selected" <?php  } ?>>10</option>
          <option value="20" <?php if($_REQUEST['page_rows'] == 20){ ?> selected="selected" <?php  } ?>>20</option>
          <option value="50" <?php if($_REQUEST['page_rows'] == 50){ ?> selected="selected" <?php  } ?>>50</option>
          <option value="100" <?php if($_REQUEST['page_rows'] == 100){ ?> selected="selected" <?php  } ?>>100</option>
          <option value="150" <?php if($_REQUEST['page_rows'] == 150){ ?> selected="selected" <?php  } ?>>150</option>          
        </select>
    </div>
	 <?=$fil_list?>
     <?=$norec?>
<?php
	}
?>
<p id="pagin">

        	<?php
            for($x=1;$x<=$last;$x++) {
                if ($_REQUEST['pagenum'] == $x) {
            ?>	
                <a href="#"><?=$x?></a>
            <?php		
                } else {
            ?>
                <a href="#list" onclick="updatePage(<?=$x?>)"><?=$x?></a>
            <?php } 
            } 
            ?>
        
        </p>
</div>
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="canP" id="canP" value="<?=$canP?>" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />
<input type="hidden" name="pagenum" id="pagenum" value="<?=$pagenum?>" />
<input type="hidden" name="filter_field" id="filter_field" value="<?=$field?>" />
<input type="hidden" name="filter_order" id="filter_order" value="<?=$ord?>" />
<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>
<!-- LIST LOOK UP-->
<div id="dialog" title="Employee Profile">
    Loading...
</div><!-- #dialog -->