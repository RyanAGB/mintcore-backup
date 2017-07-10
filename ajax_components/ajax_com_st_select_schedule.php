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


	include_once("../config.php");
	include_once('../includes/functions.php');	
	include_once('../includes/common.php');		
	
	if(USER_IS_LOGGED != '1')
	{
		header('Location: ../index.php');
	}
	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}
?>
<script type="text/javascript">
$(function(){

	// Dialog			
	$('#dialog').dialog({
		autoOpen: false,
		width: 820,
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
	$('.curSub').click(function(){
		var id = $(this).attr("returnId");
		var subjectType = $(this).attr("returnSubjectType");
		var subjectUnit = $(this).attr("returnSubjectUnit");	
		var com = $('#comp').val();	
		var cid = document.getElementById("cid").value;

		//alert('ids'+iD);
		$('#dialog').load('lookup/lookup_com_st_select_schedule.php?id='+id+'&cid='+cid+'&subjectType='+subjectType+'&subjectUnit='+subjectUnit+'&comp='+com, null);
		$('#dialog').dialog('open');
		return false;
	});
	
	$('.delete').click(function(){
		var ObjId = $(this).attr("returnId");

		var subject_unit = $(this).attr("returnSubjectUnit");
		var total_select_units = $('#enrolled_unit').val();
		var total_max_units = $('#max_unit').val();
		
		if($('#schedule_id_'+ObjId).val() != '')
		{
			$.ajax({
				type: "POST",
				data: "mod=reverse&id=" + ObjId,
				url: "ajax_components/ajax_com_slot_updater.php",
				success: function(msg){
					if (msg != ''){
						//alert(msg);
					}
				}
				});
				
			$('#span_select_units').html(parseInt(total_select_units) - parseInt(subject_unit));
			$('#enrolled_unit').val(parseInt(total_select_units) - parseInt(subject_unit));
		}
		
		$('#schedule_id_'+ObjId).attr("value", '');
		//$('#elective_of_'+ObjId).val('');
		//$('#subject_id_'+ObjId).val('');
		$('#units_'+ObjId).val('');
		//$('#subject_type_'+ObjId).val('');
		$('#from_'+ObjId).html('');
		$('#to_'+ObjId).html('');
		$('#day_'+ObjId).html('');
		$('#froms_'+ObjId).attr('value','');
		$('#tos_'+ObjId).attr('value','');
		$('#days_'+ObjId).attr('value','');


		return false;
	});	
		
	$('#save').click(function(){
	
		var needle = '';
		$.each($("input[name*=schedule_id]"), function(index, value) { 
			var mixed_array = '';
			if($(this).val() != '')
			{
				if(needle != '')
				{
					needle = needle + ',';
				}
				needle = needle + $(this).val();
			} // end if of first each
		}); // first each			
		
		if(needle != '')
		{
			$.ajax({
					type: "POST",
					data: "selected=" + needle ,
					url: "ajax_components/ajax_validate_schedule.php",
					success: function(msg){
							if (msg == 'false')
							{
								
								  $.ajax({
									type: "POST",
									data: "id="+<?=USER_STUDENT_ID?>+"&selected=" + needle,
									url: "ajax_components/ajax_validate_corequisite.php",
									success: function(msg){
											if (msg == '')
											{
											//alert(msg);
												if(confirm('Are you sure you want to complete the reservation? This will reflect to student assessment.'))
												{
													$('#action').val('save');
													//$('#enrolled').val('0');
													$("form").submit();
												}
												else
												{
													return false;
												}
											}
											else
											{
												
												$('#action').val('save');
													//$('#enrolled').val('0');
													$("form").submit();
												//alert(msg);
												/*var corq = msg.split(',');
												var non = '';
												for(var x=0;x<corq.length;x++)
												{
													if (document.getElementById('tr_'+corq[x]))
													{
													$('#tr_'+corq[x]).attr('class','highlight_error');
													}
													else
													{
														non = corq[x];
													}
												}
												
												if(non!='')
												{
													alert('Co-Requisites not available.');
												}
												else
												{
													alert('Co-Requisites not selected.');
												}*/
											}
										} // success function
									});	 // success				
							
								}
							else
							{
								alert('Conflict schedule found');
							}
						} // success function
			});	 // success				
			
				
		}
		else
		{
			alert('No schedule is selected');
		}	

	});
	
	$('#print').click(function() {
			var w=window.open();
			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
			w.document.write($('#print_div').html());
			w.document.close();
			w.focus();
			w.print();
			//w.close()
			return false;
		});
		
		$('#pdf').click(function() {
			var w=window.open ("pdf_reports/rep108.php?id="+<?=USER_STUDENT_ID?>+"&trm="+<?=CURRENT_TERM_ID?>+"&met=reservation"); 
			return false;
		});
		
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file to this student?"))
			{
				$.ajax({
					type: "POST",
					data: "id="+<?=USER_STUDENT_ID?>+"&trm="+<?=CURRENT_TERM_ID?>+"&met=reservation&email=1",
					url: "pdf_reports/rep108.php",
					success: function(msg){
						if (msg != ''){
							alert('Sending document by email failed.');
							return false;
						}else{
							alert('Email successfully sent.');
							return false;
						}
					}
					});	
					
			}
			else
			{
				return false;
			}
		
		});
});		
</script>

<script type="text/javascript">	
	function checkConflictSched(Day,id)
	{
			var cnt = 0;
			
		for(var i=0;i<Day.length;i++)
		{
			var days = Day[i].split('-');
			
		   $.each($("input[name*=ids]"), function(index, value) { 
		   		
				var obId = $(this).val();
				var arrDay = $('#days_'+obId).val().split("/");
				
				if(id != obId)
				{
					for(var x=0;x<arrDay.length;x++)
					{
						var days2 = arrDay[x].split('-');
					
					 if((days[1] == days2[1] && days[1] == days2[2]) || (days[2] == days[1] && days[2] == days2[2]))
					 {
					 	if(days[0]==days2[0])
						{
					 		cnt++;
						}
					 }
					 else
					 {
							if((days[1] >= days2[1] && days[1] < days2[2]) || (days[2] > days2[1] && days[2] <= days2[2]))
							{
									if(days[0]==days2[0])
									{
										cnt++;
									}
							}
							
					 }
					 
		 		}
			}
		});
	}
	
			if(cnt == 0)
			{
				return true;
			}
			else
			{
				return false;
			}
	}
	
</script>
<?php
if(checkIfEnrollmentIsOpenForCourse(USER_COURSE_ID))
{ // ENROLLMENT IS STILL OPEN

	$arr_sql = array();
	$arr_subject = getStudentSubjectForEnrollmentInArr(USER_STUDENT_ID);	
	
	if (count($arr_subject) > 0 )
	{
		$x = 1;
		
		$sql = "SELECT * FROM tbl_student WHERE id = ".USER_STUDENT_ID;						
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
	?>
    <div id="print_div">
<div id="printable">
<div class="body-container">
<div class="header">
<div class="headerForm">
	<table class="classic_borderless">
      <tr>
        <td valign="top" style='font-weight:bold'>Student Number:</td>
        <td><?=$row['student_number']?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Student Name:</td>
        <td><?=$row['lastname']. ", " . $row['firstname'] ." " . $row['middlename']?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php
                $birthday = explode ('-',$row['birth_date']);		
				$birth_year = $birthday['0'];
				$birth_day = $birthday['1'];
				$birth_month = $birthday['2'];

				?>
      <tr>
        <td style='font-weight:bold' valign="top">Date of Birth:</td>
        <td><?=date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year))?></td>
        <td><span style="font-weight:bold">Sex:</span></td>
        <td><?=$row['gender']=='F'?'Female':'Male'?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Department:</td>
        <td><?=getStudentCollegeName($row['course_id'])?></td>
        <td><span style="font-weight:bold">Curriculum:</span></td>
        <td><?=getCurriculumCode($row['curriculum_id'])?></td>
      </tr>
      <?php
	  if(checkIfStudentReservationIsExpiredByStudId($student_id))
    	{
	  ?>
      <tr>
        <td style='font-weight:bold' valign="top">Enrollment Remarks:</td>
        <td colspan="3">The enrollment reservation of this student has expired.</td>
      </tr>
      <?php
	  }
	  ?>     
    </table>  
    </div> 
</div>
<div class="content-container">

<div class="content-wrapper-wholeBorder">

<table class="listview">   
  <tr>
    <th class="col_50">Code</th>
		  <th class="col_300">Subject Name</th>
		  <th class="col_50">Units</th>
		  <th class="col_100">Schedule</th>
		  <th class="col_50">Action</th>
	  </tr>
	<?php
			
		foreach($arr_subject as $subject_id) 
		{
			$sql = "SELECT 
						  cur.curriculum_id, 
						  cur.subject_id, 
						  cur.year_level, 
						  cur.subject_category, 
						  cur.term, 
						  cur.units, 
						  sub.id, 
						  sub.subject_code, 
						  sub.subject_name
					  FROM 
						  tbl_curriculum_subject cur LEFT JOIN 
						  tbl_subject sub ON cur.subject_id = sub.id
					  WHERE 
						  cur.subject_id = " .$subject_id ." AND 
						  cur.subject_category <>'EO'"
						 ;
			
			$result = mysql_query($sql);   
			$row = mysql_fetch_array($result); 
			$ctr_subject = mysql_num_rows($result);
			
			if($ctr_subject > 0)
			{	   
				$sql_sched = "SELECT * 
							FROM 
								tbl_student_reserve_subject 
							WHERE 
								student_id = ". USER_STUDENT_ID ." AND 
								subject_id = " . $subject_id;
								
				$query_sched = mysql_query($sql_sched);
				$row_sched = mysql_fetch_array($query_sched);
		
	?>
  		<tr class="<?=($x%2==0)?"":"highlight";?>" id="tr_<?=$row['id']?>">
			<td id="code_<?=$row['id']?>"><?=$row["subject_code"]?></td> 
			<td id="name_<?=$row['id']?>"><?=$row["subject_name"]?></td>
			<td id="units_<?=$row['id']?>"><?=$row["units"]?></td>
			<td id="day_<?=$row['id']?>"></td>
			<td class="action">

				<input type="hidden" name="schedule_id[]" id="schedule_id_<?=$row['id']?>" value="" >   
                <input type="hidden" name="days_<?=$row['id']?>" id="days_<?=$row['id']?>" value="<?=getSepScheduleDays($row_sched['schedule_id'])?>" > 
                        <input type="hidden" name="ids[]" id="ids_<?=$row['id']?>" value="<?=$row['id']?>" >
				<input type="hidden" name="units[]" id="units_<?=$row['id']?>" value="<?=$row["units"]?>" >
				<input type="hidden" name="subject_id[]" id="subject_id_<?=$row['id']?>" value="<?=$row["subject_id"]?>" >
                <input type="hidden" name="subject_type[]" id="subject_type_<?=$row['id']?>" value="<?=$row['subject_category']?>" >                  
                <input type="hidden" name="elective_of[]" id="elective_of_<?=$row['id']?>" value="<?=$row['subject_category'] == 'R'?'':$row['id']?>" >                
			  <ul>
				<li><a class="curSub" href="#" title="Select Schedule" returnId="<?=$row['subject_id']?>" returnSubjectType="<?=$row['subject_category']?>" returnSubjectUnit="<?=$row['units']?>"></a></li>
                    <li><a class="delete" href="#" title="Remove Schedule" returnId="<?=$row['subject_id']?>" returnSubjectType="<?=$row['subject_category']?>" returnSubjectUnit="<?=$row['units']?>"></a></li>                   
				</ul>
			</td>
		</tr>
	<?php  
			} 
		$x++;        
	   }
	}
	else 
	{
		echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';
	}
	?>
    <tr>
    	<td colspan="5">Select Payment Scheme
        	<select name="scheme_id" class="txt_150" id="scheme_id">

                  <option value="">Select</option>

                        <?=generateScheme($payment_scheme_id)?>

              </select>       
        </td>
    
    </tr>
</table>  
                 
<div class="fieldsetContainer50">      
    <label>Total Selected Units: <span id="span_select_units"><?=getStudentReservedUnit(USER_STUDENT_ID)?></span></label>      
    <label>Total allowed Units: <?=getStudentMaxEnrolledUnit(USER_STUDENT_ID)?></label>
    <label>&nbsp;</label>
</div>

<div class="btn_container">
  <p class="button_container">
        <input type="hidden" name="max_unit" id="max_unit" value="<?=getStudentMaxEnrolledUnit(USER_STUDENT_ID)?>" >
        <input type="hidden" name="enrolled_unit" id="enrolled_unit" value="0" >  
		<input type="hidden" name="term" id="term" value="<?=CURRENT_TERM_ID?>" />
		<input type="hidden" name="cid" id="cid" value="<?=USER_CURRICULUM_ID?>" />
		<input type="hidden" name="stdid" id="stdid" value="<?=USER_STUDENT_ID?>" />
	<a href="#" class="button" title="Save" id="save"><span>Reserve Now</span></a>
  </p>
</div>
       </div>
   </div> 
   </div> 
   </div> 
<p id="formbottom"></p>
<div id="dialog" title="Curriculum Subjects">
    Loading...
</div><!-- #dialog -->
<?php
}
else
{//ENROLLMENT IS CLOSED
	echo '<div id="message_container"><h4>Enrollment for your Course is Closed.<br />If you have any question please contact the Administrator.<h4></div><p id="formbottom"></p>';
}
?>