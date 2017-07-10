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

	$('#course_dialog').dialog({

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

	$('#course_link').click(function(){

		$("#year_level").html('<option value="" selected="selected">Select</option>');

		var param = $(this).attr("returnComp");

		$('#course_dialog').load('lookup/lookup_com_curriculum.php?comp='+param, null);

		$('#course_dialog').dialog('open');

		return false;

	});

	// Dialog2			
	$('#dialog2').dialog({
		autoOpen: false,
		width: 650,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Ok": function() { 
				getselected(); 
			},
			"Close": function() { 
				$(this).dialog("close");
			} 
		}
	});
	
	// Dialog2 Link
	$('#add_subj').click(function(){
	
		$('#dialog2').load('viewer/viewer_com_add_subject.php?comp='+$('#comp').val()+'&term_id=<?=$_REQUEST['termId']?>&course=<?=$_REQUEST['courseID']?>&blk=<?=$_REQUEST['bid']?>', null);
		$('#dialog2').dialog('open');
		return false;
	});

});	



$(document).ready(function(){  

	

	var validator = $("#coreForm").validate({

		errorLabelContainer: $("div.error")

	});

	

	$('#filter_schoolterm').change(function(){

		clearTabs();

		$('#room_list').addClass('active');

		$('#view').val('list');

		$('#sy_filter').val($('#filter_schoolterm').val());

		updateList();

	});



	$('#period').click(function(){

		if($('#syId').val() != '' && $('#termId').val() != '' && $('#$courseID').val() != '' )

		{

			validator.resetForm();

			clearTabs();

			$('#period').addClass('active');

			$('#view').val('period');

			$("form").submit();

		}

		else

		{

			validator.resetForm();

			alert('No Block section was selected. Please click on the manage block section icon under the action column.');

			clearTabs();

			$('#list').addClass('active');

			updateList();

			return false;

		}

	});

	

	

	//initialize the tab action

	$('#add_new').click(function(){

			validator.resetForm();

			clearTabs();

			$('#add_new').addClass('active');

			$('#view').val('add');

			$("form").submit();		

	});

	

	$('#edit_item').click(function(){

		if(checkbox_checker())

		{

			validator.resetForm();

			clearTabs();

			$('#edit_item').addClass('active');

			$('#view').val('edit');

			$("form").submit();

		}

		else

		{

			validator.resetForm();

			alert('No item was selected.');

			updateList();

			return false;

		}

	});

	

	$('#room_list').click(function(){

		clearTabs();

		$('#room_list').addClass('active');

		$('#view').val('list');

		$('#action').val('list');

		updateList();

		$("form").submit();

	});	



	$('#save').click(function(){

		clearTabs();

		$('#add_new').addClass('active');

		$('#action').val('save');

		$('#view').val('add');

		$("form").submit();

	});	


	$('#saveblocksubject').click(function(){

		var cnt = $('#num').val();

		var needle = '';

		var mixed_array = '';

		

		for(ctr=1;ctr<=cnt;ctr++){

			if ($('#chk_' + ctr).attr("checked")) {

				if (needle != "")

					needle += ",";

				needle += $('#chk_' +ctr).val(); 

				}

		}

		//alert(needle);

		if(needle != '')

			{

				$.ajax({

						type: "POST",

						data: "selected="+needle,

						url: "ajax_components/ajax_validate_block_schedule.php",

						success: function(msg){

								if (msg == 'false')

								{

										clearTabs();

										$('#period').addClass('active');

										$('#action').val('saveblocksubject');

										$('#view').val('period');

										$("form").submit();

								}

								else

								{

									alert('Conflict schedule found');

								}

					}

				});	 // success				

				

			}

			else

			{

				alert('No action was made, please select at least one(1) schedule.');

			}		

        

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

	if($view != 'period' && $view != 'add' && $view != 'edit' && $view != 'add_subject' && $view != 'add_elective')

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

		$('#page').val(1);

		updateList();

	});	

	

});	

function delete_sub(id)
{
	if(confirm("Are you sure you want to delete this record?"))
	{
		$('#id').val(id);
		
		$('#action').val('delete_sub');

		$("form").submit();
	}
}

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

	

	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )

	{

		param = param + '&list_rows=' + $('#page_rows').val() + param;

	}



	if($('#filter_schoolterm').val() != '' && $('#filter_schoolterm').val() != undefined)

	{

		param = param + '&filter_schoolterm=' + $('#filter_schoolterm').val();

	}

	if($('#comp').val() != '' && $('#comp').val() != undefined )

	{

		param = param + '&comp=' + $('#comp').val() + param;

	}

	

	$('#box_container').html(loading);

	$('#box_container').load('ajax_components/ajax_com_block_section.php?param=1' + param, null);

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

<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Curriculum List"><span>Block Section List</span></a></li>

<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add / Edit</span></a></li>

<!--<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit Item"><span>Edit</span></a></li>!-->

<li id="period" <?=$view=='period'?'class="active"':''?>><a href="#" title="Manage Block Section"><span>Manage Block Section</span></a></li>

</ul>

<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">



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

		

		if($_SESSION[CORE_U_CODE]['sy_filter'] !='')

		{

			$sy = $_SESSION[CORE_U_CODE]['sy_filter']; 

		}

		else

		{

			$sy = $sy_filter; 

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

<?php

	} // end of page rows

if($view=='list')

{

?>

<div class="filter">

    Select School Term&nbsp;&nbsp;

    <select name="filter_schoolterm" id="filter_schoolterm" class="txt_200">

    	<?=generateSchoolTerms($sy)?>

    </select>

</div>



<?php

}

?>

<div class="box" id="box_container">

<?php

	if ($view == 'period') 

	{

		$courseid = $_REQUEST['courseID'];

		$bid = $_REQUEST['bid'];

		$termId = $_REQUEST['termId'];
		
		$sql = "SELECT * FROM tbl_block_section where id = " . $bid;
		$query = mysql_query ($sql);
		$row = mysql_fetch_array($query);
		
	
		$block_name				= $row['block_name'] != $block_name ? $row['block_name'] : $block_name;
		$course_id				= $row['course_id'] != $course_id ? $row['course_id'] : $course_id;



		$sql = "SELECT *, c.id as cid FROM tbl_curriculum a 

				JOIN tbl_curriculum_subject b ON a.id = b.curriculum_id 

				JOIN tbl_schedule c ON c.subject_id = b.subject_id 

				WHERE a.course_id = ".$courseid. " AND c.term_id = ".$termId." AND a.is_current ='Y' ORDER BY c.section_no";

/*
		$sql = "SELECT *, c.id as cid FROM tbl_schedule c

				LEFT JOIN tbl_curriculum_subject b ON c.subject_id = b.subject_id 

				LEFT JOIN tbl_curriculum a ON b.curriculum_id = a.id

				WHERE a.course_id = ".$courseid." AND c.term_id = ".$termId." AND a.is_current ='Y' ORDER BY c.section_no";
*/
	

		$result = mysql_query($sql);

		if (mysql_num_rows($result) > 0 )

        {

            $x = 1;

			$ctr = 1;

?>



		<table class="listview">
			<tr>

			<td colspan="7">
            
            <p><strong>Block Name : 
            <?=$block_name?>
            </strong></p>
            
            <p><strong>Course : 
            <?=getCourseName($course_id)?>
            </strong></p>
            
            <p><strong>SchoolYear : 
            <?=getSYandTerm($termId)?>
            </strong></p>
            
            </td>

			</tr>
            
            <tr>

			<th class="col_20"><input type="hidden" name="id_all" id="id_all" value="" /></th>

			<th class="col_100">Section No</th>

			<th class="col_300">Subject</th>

			<th class="col_100">Room</th>

			<th class="col_250">Professor</th>

			<th class="col_40">Slots</th>

			<th class="col_50">Schedule</th>

			</tr>

		<?php	

			while($row = mysql_fetch_array($result))

			{ 

				if(checkIfScheduleIsInBlock($row['cid'],$bid))

				{

					$checked = 'checked="checked"';

				/*}

				else

				{

					$checked = '';

				}*/

		?>

            <tr class="<?=($x%2==0)?"":"highlight";?>">
            <?php 
            // print $bid;
            // print "<pre>";
            // print_r ($row);
            // print "</pre>";	
             ?>

                <td><input type="checkbox" name="chk_<?=$ctr?>" id="chk_<?=$ctr?>" value="<?=$row['cid']?>" <?=$checked?> style="display:none" /><a href="#" onclick="delete_sub(<?=$row["id"]?>)">Delete</a></td>

                <td><?=$row["section_no"]?></td> 

                <td><?=getSubjName($row["subject_id"])?><span><?=$x?></span></td>

                <td><?=getRoomNo($row["room_id"])?></td>

                <td><?=getProfessorFullName($row["employee_id"])?></td>

                <td><?=$row["number_of_student"]?></td>

                <td><?=getScheduleDays($row["id"])?></td>

			</tr>	



		<?php



			$x++;

			$ctr++;

            }	}

		?>
        

        </table>

         <!-- LIST LOOK UP-->
<div id="dialog2" title="Add Subject">
    Loading...
</div><!-- #dialog2 -->

        <div class="btn_container">

        

            <p class="button_container">

            <input type="hidden" name="id" id="id" value="<?=$id?>" />

            <?php

				if($view == 'period') 

				{

            ?>

           		 <!--<a href="#" class="button" title="SaveBlockSubject" id="saveblocksubject"><span>Save</span></a>!--><a href="#" class="button" title="Add subject" id="add_subj" name="add_subj"><span>Add subject</span></a>

            <?php

            	}

            ?>

            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>

            </p>

            

        </div>	        

<?php

	 } else {

		echo '<div id="message_container"><h4>No schedule found</h4></div>';

		}	 

	}		

?>



<?php

	if($view == 'edit' || $view == 'add')

	{

?>

	<div class="formview">

    

        <fieldset>

           <legend><strong>Block Section Information</strong></legend>

            <label>Block Name:</label>

            <span ><input class="txt_100 {required:true}" title="Block Name field is required" name="block_name" type="text" value="<?=$block_name?>" id="block_name" />

            </span><br class="hid" />

            <label>School Year:</label>

            <span > <select name="term_id" id="term_id" class="txt_200 {required:true}" title="Section Number field is required" >

                    <?=generateSchoolTerms($term_id)?>

            </select>

            </span><br class="hid" />

            <label>Course Name:</label>

            <span>

            <div style=" float:left">

            <input class="txt" name="course_id" type="hidden" value="<?=$course_id?>" id="course_id" readonly="readonly" />

            <input class="txt_200 {required:true}" title="Course Name field is required" name="course_name_display" type="text" value="<?=$course_name_display?>" id="course_name_display" readonly="readonly"/>

            </div>

            <div style="width:50px; float:left">

            <a href="#" class="lookup_button" id="course_link" returnComp="<?=$_REQUEST['comp']?>" onkeypress="alertMe()"><span>...</span></a><br class="hid" />

            </div>

        	</span><br class="hid" /> 

            </span><br class="hid" />           



            <p>

            

            <!-- LIST LOOK UP-->

            <div id="course_dialog" title="Course List">

                Loading...

            </div><!-- #dialog -->
            
           

    

            <span class="clear"></span>

            <label>Year Level:</label>

            <span ><select name="year_level" id="year_level" class="txt_150 {required:true}" title="year level field is required" >

                    <option value="" selected="selected">Select</option>

                    <?=generateYearLevelByCourse($course_id,$year_level)?>

            </select>

            </span><br class="hid" />

        </fieldset>

        <fieldset>

           <legend><strong>System Information</strong></legend>

            <label>Publish:</label>

            <span >

                <select name="publish" id="publish" class="txt_150 {required:true}" title="Publish field is required" >

                    <option value="" selected="selected">Select</option>

                    <option value="Y" <?=$publish== "Y"?'selected = "selected"':''?> >Yes</option>

                    <option value="N" <?=$publish== "N"?'selected = "selected"':''?> >No</option>

                </select>    

            </span><br class="hid" /> 

          

            <span class="clear"></span>

        </fieldset>

    

        

        <p class="button_container">

            <input type="hidden" name="id" id="id" value="<?=$id?>" />

            <?php

            if($view == 'add') 

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

?>



<p id="formbottom"></p>

</div>

<input type="hidden" name="filter_field" id="filter_field" value="<?=$filter_field?>" />

<input type="hidden" name="filter_order" id="filter_order" value="<?=$filter_order?>" />

<input type="hidden" name="page" id="page" value="<?=$page?>" />

<input type="hidden" name="sy_filter" id="sy_filter" value="<?=$sy_filter?>" />

<input type="hidden" name="num" id="num" value="<?=mysql_num_rows($result)?>" />

<input type="hidden" name="bid" id="bid" value="<?=$bid?>" />

<input type="hidden" name="courseID" id="courseID" value="<?=$courseid?>" />

<input type="hidden" name="termId" id="termId" value="<?=$termId?>" />

<input type="hidden" name="syId" id="syId" value="<?=$syId?>" />

<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />

<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="temp" id="temp" value="" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />

</form>