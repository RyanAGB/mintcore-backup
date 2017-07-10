<?php
include_once("../config.php");
include_once("../includes/functions.php");
include_once("../includes/common.php");

error_reporting(0);

$remove_student_action = "";
$remove_student_action_remarks = "";
$student_schedule_id = "";
$term_id = "";
$schedule_id = "";
$student_id = "";
$student_number = "";
$student_lastname = "";
$student_firstname = "";
$student_middlename = "";
$student_courseid = "";
$student_coursecode = "";
$student_coursename = "";
$student_subjectid = "";
$student_subjectcode = "";
$student_subjectname = "";
$student_curriculumid = "";

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp'])){
	header('Location: ../forbid.html');
}else{	
	$remove_student_action = $_REQUEST["remove_student_action"];
	$student_schedule_id = $_REQUEST["student_schedule_id"];
	$term_id = $_REQUEST["term_id"];
	$schedule_id = $_REQUEST["schedule_id"];
	$student_id = $_REQUEST["student_id"];
	$student_subjectid = $_REQUEST["subject_id"];
	$student_courseid = $_REQUEST["course_id"];
	$student_curriculumid = $_REQUEST["curriculum_id"];
				
	$sql = "SELECT id, student_number, lastname, firstname, middlename 
			FROM tbl_student WHERE id=".$student_id;
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$student_number = $row["student_number"];
	$student_lastname = $row["lastname"];
	$student_firstname = $row["firstname"];
	$student_middlename = $row["middlename"];
	
	$sql = "SELECT id, course_code, course_name 
			FROM tbl_course WHERE id=".$student_courseid;
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$student_coursecode = $row["course_code"];
	$student_coursename = $row["course_name"];
	
	$sql = "SELECT id, subject_code, subject_name 
			FROM tbl_subject WHERE id=".$student_subjectid;
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$student_subjectcode = $row["subject_code"];
	$student_subjectname = $row["subject_name"];
	
	if($remove_student_action=="REMOVESAVE"){
		
		$scheduleSQL = "SELECT * FROM tbl_schedule WHERE id=".$schedule_id." AND term_id=".$term_id;
		$scheduleResult = mysql_query($scheduleSQL);
		$scheduleResultRow = mysql_fetch_array($scheduleResult);
		if(mysql_num_rows($scheduleResult)>0){
			$sql = "DELETE FROM tbl_student_schedule where id=".GetSQLValueString($student_schedule_id,"int");
			if(mysql_query($sql)){
			
				//Update class schedule Slot
				if($scheduleResultRow["number_of_reserved"]>0){
					$number_of_student = $scheduleResultRow["number_of_student"];
					$number_of_reserved = $scheduleResultRow["number_of_reserved"]-1;
					$number_of_available = $number_of_student - $number_of_reserved;
					
					$sql = "UPDATE tbl_schedule 
							SET 
								number_of_reserved=".$number_of_reserved.", 
								number_of_available=".$number_of_available." 
							WHERE id=".$schedule_id;
					mysql_query($sql);
				}
				
				$remove_student_action_remarks = "SUCCESSFUL";
			}
		}
	}
		
?>

<script type="text/javascript">

function removeStudentFromClass(){
	$('#remove_student_dialog').load('viewer/viewer_com_schedule_student_list_2_1_remove.php?comp=<?=$_REQUEST['comp']?>&student_schedule_id=<?=$student_schedule_id?>&term_id=<?=$term_id?>&schedule_id=<?=$schedule_id?>&student_id=<?=$student_id?>&subject_id=<?=$student_subjectid?>&course_id=<?=$student_courseid?>&curriculum_id=<?=$student_curriculumid?>&remove_student_action=REMOVESAVE', null);
}

function doNoRemoveStudentFromClass(){
	$('#remove_student_dialog').dialog('close');
}


function updateRemoveEntry(){
	document.getElementById("tdRemove_StudentNo").innerHTML = document.getElementById("removestudent_number").value;
	document.getElementById("tdRemove_StudentName").innerHTML = document.getElementById("removestudent_lastname").value + ", " + document.getElementById("removestudent_firstname").value + " " + document.getElementById("removestudent_middlename").value;
	document.getElementById("tdRemove_Course").innerHTML = "(" + document.getElementById("removestudent_courseid").value + ") " + document.getElementById("removestudent_coursecode").value + " - " + document.getElementById("removestudent_coursename").value;
	document.getElementById("tdRemove_Curriculum").innerHTML = "(" + document.getElementById("removestudent_curriculumid").value + ")";
	document.getElementById("tdRemove_Subject").innerHTML = "(" + document.getElementById("removestudent_subjectid").value + ") " + document.getElementById("removestudent_subjectcode").value + " - " + document.getElementById("removestudent_subjectname").value;
}

$(document).ready(function(){
	updateRemoveEntry();
	<?php
	if($remove_student_action=="REMOVESAVE"){
		if($remove_student_action_remarks=="SUCCESSFUL"){
			?>
			alert("Student is now removed in class.");
			$('#remove_student_dialog').dialog('close');
			updateStudentList_2_1();
			<?php
		}
		?>

		<?php
	}
	?>
});

</script>

<div id="lookup_content">
	<p style="font-weight:bold; font-size:16px;">Are you sure, you want to remove student from class?</p>
	<table class="classic" style="width:550px;">
		<tr>
			<th style="width:70px;">&nbsp;</th>
			<th>&nbsp;</th>
			<th style="width:70px;">&nbsp;</th>
		</tr>
		<tr>
			<td>Student No.</td>
			<td id="tdRemove_StudentNo"></td>
			<td></td>
		</tr>
		<tr>
			<td>Name</td>
			<td id="tdRemove_StudentName"></td>
			<td></td>
		</tr>
		<tr>
			<td>Course</td>
			<td id="tdRemove_Course"></td>
			<td></td>
		</tr>
		<tr>
			<td>Curriculum</td>
			<td id="tdRemove_Curriculum"></td>
			<td></td>
		</tr>
		<tr>
			<td>Subject</td>
			<td id="tdRemove_Subject"></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<a href="#" class="button" onclick="removeStudentFromClass()" ><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:100px; height:20px; display:table-cell; vertical-align:middle;">YES</div></a>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="#" class="button" onclick="doNoRemoveStudentFromClass()" ><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:100px; height:20px; display:table-cell; vertical-align:middle;">NO</div></a>
			</td>
			<td></td>
		</tr>
	</table>
</div>
<input type="hidden" id="term_id" value="<?=$term_id?>"/>
<input type="hidden" id="schedule_id" value="<?=$schedule_id?>"/>
<input type="hidden" id="removestudent_id" value="<?=$student_id?>"/>
<input type="hidden" id="removestudent_number" value="<?=$student_number?>"/>
<input type="hidden" id="removestudent_lastname" value="<?=$student_lastname?>"/>
<input type="hidden" id="removestudent_firstname" value="<?=$student_firstname?>"/>
<input type="hidden" id="removestudent_middlename" value="<?=$student_middlename?>"/>
<input type="hidden" id="removestudent_courseid" value="<?=$student_courseid?>"/>
<input type="hidden" id="removestudent_coursecode" value="<?=$student_coursecode?>"/>
<input type="hidden" id="removestudent_coursename" value="<?=$student_coursename?>"/>
<input type="hidden" id="removestudent_subjectid" value="<?=$student_subjectid?>"/>
<input type="hidden" id="removestudent_subjectcode" value="<?=$student_subjectcode?>"/>
<input type="hidden" id="removestudent_subjectname" value="<?=$student_subjectname?>"/>
<input type="hidden" id="removestudent_curriculumid" value="<?=$student_curriculumid?>"/>

<?php
}
?>
