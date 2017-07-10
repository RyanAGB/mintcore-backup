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
	$('#period').click(function(){
		if($('#syId').val() != '')
		{
			clearTabs();
			$('#period').addClass('active');
			$('#view').val('period');
			$('#box_container').html(loading);
			$("form").submit();
		}
		else
		{
			alert('No section was selected. Please click on the manage gradesheet icon under the action column.');
			clearTabs();
			$('#list').addClass('active');
			updateList();
			return false;
		}
	});
	
	$('#add_period').click(function(){
		if($('#syId').val() != '' )
		{
			clearTabs();
			$('#add_period').addClass('active');
			$('#view').val('add_period');
			$("form").submit();
		}
		else
		{
			alert('No section was selected. Please click on the manage gradesheet icon under the action column.');
			clearTabs();
			$('#list').addClass('active');
			updateList();
			return false;
		}
	});
	
	$('#list').click(function(){
		clearTabs();
		$('#list').addClass('active');
		$('#view').val('list');
		updateList();
		$("form").submit();
	});	

	$('#save').click(function(){
		clearTabs();
		$('#add_new').addClass('active');
		$('#action').val('save');
		$('#view').val('add_period');
		$("form").submit();
	});	
	
	$('#update').click(function(){
		clearTabs();
		$('#edit_item').addClass('active');
		$('#action').val('update');
		$('#view').val('edit');
		$("form").submit();
	});

	$('#filter_schoolterm').change(function(){
		updateList();
		//alert(document.getElementById('filter_schoolterm').value);
	});

	// Initialize the list
	<?php
	if($view != 'period' && $view != 'add_period' && $view != 'edit')
	{
		echo 'updateList();'; 
	 }
	?>

	$('#cancel').click(function(){
		clearTabs();
		$('#list').addClass('active');
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
	if($('#filter_schoolterm').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#filter_schoolterm').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}

	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_pr_gradesheet.php?list_rows=10' + param, null);
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
<li id="list" <?=$view=='list'?'class="active"':''?>><a href="#" title="School Terms"><span>Section List</span></a></li>
<li id="period" <?=$view=='period'?'class="active"':''?>><a href="#" title="Manage Period"><span>Manage Gradesheet</span></a></li>
<li id="add_period" <?=$view=='add_period'?'class="active"':''?><?=$view=='edit'?'class="active"':''?>><a href="#" title="Add Period"><span>Add/Edit Items</span></a></li>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<?php
	if($view == 'list')
	{
?>
<div class="filter">
    Select School Term&nbsp;&nbsp;
    <select name="filter_schoolterm" id="filter_schoolterm" class="txt_200">
    	<?=generateSchoolTerms($filter_schoolterm)?>
    </select>
</div>
<?php
	}
?>
<div class="box" id="box_container">
<?php
	if($view == 'period')
	{
?>
	
		<h3>
            Grade Sheet Set-Up under Section <?=$schedule_id?> [ <?=getSYandTerm(CURRENT_TERM_ID)?> ]
        </h3>
        
        <table class="listview">      
          <tr>
              <th class="col_20"><input type="checkbox" name="id_all" id="id_all" value="" /></th>
              <th class="col_150"><a href="#list" onclick="sortList('period_name');">Period Name</a></th>
              <th class="col_350"><a href="#list" onclick="sortList('start_of_submission');">Label</a></th>
			  <th class="col_350"><a href="#list" onclick="sortList('percentage');">Percentage</a></th>
              <th class="col_150">Action</th>
          </tr>
		  <?=$period_list?>         
        </table>
<?php
	}
	else if($view == 'add_period' || $view == 'edit')
	{
	
?>

	<div class="formview">
		
		<h3>
            Section <?=$schedule_id?>
        </h3>
        
        <fieldset>
           <legend><strong>Grade Sheet Information</strong></legend>
           <label>Period:</label>
            <span><select class="txt_150" name="school_yr_period_id" id="school_yr_period_id">
            <option value="">Select</option> 
           <?=generatePeriod($syId,$school_yr_period_id)?>
            </select>
            <label>Label:</label>
            <span>
            <input class="txt_250" name="label" type="text" value="<?=$label?>" id="label" />
    		</span><br class="hid" />
			<label>Percentage:</label>
            <span>
            <input class="txt_250" name="percentage" type="text" value="<?=$percentage?>" id="percentage" />
    		</span><br class="hid" />	
			
        </fieldset>

        <p class="button_container">
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
            <?php
            if($view == 'add_period')
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
	//}	
?>
<p id="formbottom"></p>
</div>
<input type="hidden" name="termId" id="termId" value="<?=$termId?>" />
<input type="hidden" name="syId" id="syId" value="<?=$syId?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>