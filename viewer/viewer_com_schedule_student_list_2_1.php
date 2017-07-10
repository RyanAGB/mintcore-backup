<?php
include_once("../config.php");
include_once("../includes/functions.php");

error_reporting(0);

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp'])){
	header('Location: ../forbid.html');
}else{	
	$id = $_REQUEST['id'];	//schedule id
	$filter_schoolterm = $_REQUEST['filter_schoolterm'];	//term id
	
?>

<script type="text/javascript">

//Student Dialog Link
$(function(){
	$('#add_student_dialog').dialog({
		autoOpen: false,
		width: 590,
		height: 300,
		bgiframe: true,
		modal: true,
		buttons: {
			"Close": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	$('#remove_student_dialog').dialog({
		autoOpen: false,
		width: 590,
		height: 300,
		bgiframe: true,
		modal: true,
		buttons: {
			"Close": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
});

function addStudent(term_id, schedule_id){
	$('#add_student_dialog').load('viewer/viewer_com_schedule_student_list_2_1_add.php?comp=<?=$_REQUEST['comp']?>&term_id=' + term_id + '&schedule_id=' + schedule_id + '&add_student_action=ADD',null);
	$('#add_student_dialog').dialog('open');
}

function removeStudent(student_schedule_id, term_id, schedule_id, student_id, subject_id, course_id, curriculum_id){
	$('#remove_student_dialog').load('viewer/viewer_com_schedule_student_list_2_1_remove.php?comp=<?=$_REQUEST['comp']?>&student_schedule_id=' + student_schedule_id + '&term_id=' + term_id + '&schedule_id=' + schedule_id + '&student_id=' + student_id + '&subject_id=' + subject_id + '&course_id=' + course_id + '&curriculum_id=' + curriculum_id + '&remove_student_action=REMOVE',null);
	$('#remove_student_dialog').dialog('open');
}
	
function updateStudentList_2_1(){
	/*
	var param = '';
	if($('#filter').val() != ''){
		param = param + '&filter=' + $('#filter').val();
	}
	param = param + '&comp='+ $('#comp').val()+'&id='+<?=$id?>+'&filter_schoolterm='+<?=$filter_schoolterm?>;
	$('#dialog').load('viewer/viewer_com_schedule_student_list_2_1.php?listrow=10'+ param, null);
	*/
	$('#student_dialog').load('viewer/viewer_com_schedule_student_list_2_1.php?id=<?=$id?>&comp=<?=$_REQUEST["comp"]?>&filter_schoolterm=<?=$filter_schoolterm?>', null);
}
</script>

	<?php	
	$scheduleSQL = "SELECT * FROM tbl_schedule WHERE id = $id";
	$scheduleResult = mysql_query($scheduleSQL);
	$scheduleResultRow = mysql_fetch_array($scheduleResult);
	$schedule_id = $scheduleResultRow["id"];
	$term_id = $scheduleResultRow["term_id"];
	
	$studentsSQL = "SELECT sched.id, 
						sched.student_id, stud.student_number, stud.lastname, stud.firstname, stud.middlename, stud.curriculum_id, 
						stud.course_id, course.course_code, course.course_name, 
						sched.subject_id, subj.subject_code, subj.subject_name 
					FROM tbl_student_schedule sched 
					LEFT JOIN tbl_student stud ON sched.student_id=stud.id 
					LEFT JOIN tbl_course course ON stud.course_id=course.id 
					LEFT JOIN tbl_subject subj ON sched.subject_id=subj.id 
					WHERE sched.schedule_id=".$id." AND enrollment_status='A' 
					ORDER BY lastname ASC, firstname ASC, middlename ASC";
	$studentsResult = mysql_query($studentsSQL);
	$studentsCount = mysql_num_rows($studentsResult);	
	?>
<div id="lookup_content">
	<table class="classic" style="width:650px;">
		<tr>
		<th style="width:15px;">&nbsp;</th>
		<th style="width:60px;">
			<a href="#" class="button" onclick="addStudent('<?=$term_id?>','<?=$schedule_id?>')" ><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:60px; text-align:center;">ADD</div></a>
		</th>
		<th style="width:90px;">Student No.</th>
		<th>Student Name</th>
		<th style="width:80px;">Course</th>
		<th style="width:80px;">Subject</th>
	</tr>
	</table>
</div>
<div id="lookup_content" style="border-top:solid 1px #000000; border-bottom:solid 1px #000000; width:670px; height:450px; overflow:auto;">
<table class="classic" style="width:650px;">
	<?php
	if($studentsCount>0){
		$rowCount = 0;
		while($row=mysql_fetch_array($studentsResult)){
			$rowCount = $rowCount+1;
		?>			
			<tr class="<?=($rowCount%2==0)?"":"highlight"?>">
				<td style="width:15px;text-align:right;" title="<?='StudentId:'.$row["student_id"]?>"><?=$rowCount?></td>
				<td style="width:60px;">
					<a href="#" class="button" onclick="removeStudent('<?=$row["id"]?>','<?=$term_id?>','<?=$schedule_id?>','<?=$row["student_id"]?>','<?=$row["subject_id"]?>','<?=$row["course_id"]?>','<?=$row["curriculum_id"]?>')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:60px; text-align:center;">REMOVE</div></a>
				</td>
				<td style="width:90px;"><?=$row["student_number"]?></td>
				<td><?=$row["lastname"].' , '.$row["firstname"].' '.$row["middlename"]?></td>
				<td style="width:80px;" title="<?=$row["course_name"]?>"><?=$row["course_code"]?></td>
				<td style="width:80px;" title="<?=$row["subject_name"]?>"><?="(".$row["subject_id"].") ".$row["subject_code"]?></td>
			</tr>
		<?php
		} 
	}
	?>
</table>
</div>
<div id="lookup_content">
	<table class="classic" style="width:650px;">
		<tr>
			<td style="width:15px;"></td>
			<td style="width:60px;">
				<a href="#" class="button" onclick="addStudent('<?=$term_id?>','<?=$schedule_id?>')" ><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:60px; text-align:center;">ADD</div></a>
			</td>
			<td></td>
		</tr>
	</table>
</div>
<div id="add_student_dialog" title="Add Student"></div>
<div id="search_student_dialog" title="Search Student"></div>
<div id="select_subject_dialog" title="Select Subject to Enroll for this Class"></div>
<div id="remove_student_dialog" title="Remove Student"></div>
<?php
}
?>