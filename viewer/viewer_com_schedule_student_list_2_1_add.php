<?php
include_once("../config.php");
include_once("../includes/functions.php");
include_once("../includes/common.php");

error_reporting(0);

$add_student_action = "";
$add_student_action_remarks = "";
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
	$add_student_action = $_REQUEST["add_student_action"];
	$term_id = $_REQUEST["term_id"];
	$schedule_id = $_REQUEST["schedule_id"];
	
	if($add_student_action=="ADDSAVE"){
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
		
		
		//ADD SUBJECT FOR STUDENT
		$scheduleSQL = "SELECT * FROM tbl_schedule WHERE id=".$schedule_id." AND term_id=".$term_id;
		$scheduleResult = mysql_query($scheduleSQL);
		$scheduleResultRow = mysql_fetch_array($scheduleResult);
		if(mysql_num_rows($scheduleResult)>0){
			$sql = "INSERT INTO tbl_student_schedule 
					(
						student_id,
						term_id,
						schedule_id,
						subject_id,
						units,
						enrollment_status,
						date_created,
						created_by,
						date_modified,
						modified_by
					) 
					VALUES
					(
						".GetSQLValueString($student_id,"int").", 
						".GetSQLValueString($term_id,"int").", 
						".GetSQLValueString($schedule_id,"int").",
						".GetSQLValueString($student_subjectid,"int").", 
						".GetSQLValueString(getSubjUnit($student_subjectid),"int").",
						".GetSQLValueString("A","text").",
						".time().",
						".USER_ID.",		
						".time().",
						".USER_ID."
					)";
			storeStudentAddSubjectFee($student_id,$schedule_id);
		
			if(mysql_query($sql)){		
				if(getSubjType($student_subjectid) == 'Lec'){
					$fee_id = getFeeTypeId('perunitlec');
				}else if(getSubjType($student_subjectid) == 'Lab'){
					$fee_id = getFeeTypeId('perunitlab');
				}
				
				//Update class schedule Slot
				if($scheduleResultRow["number_of_reserved"]<$scheduleResultRow["number_of_student"]){
					$number_of_student = $scheduleResultRow["number_of_student"];
					$number_of_reserved = $scheduleResultRow["number_of_reserved"]+1;
					$number_of_available = $number_of_student - $number_of_reserved;
					
					$sql = "UPDATE tbl_schedule 
							SET 
								number_of_reserved=".$number_of_reserved.", 
								number_of_available=".$number_of_available." 
							WHERE id=".$schedule_id;
					mysql_query($sql);
				}
				
									
				$sqlpay = "SELECT * FROM tbl_student_payment WHERE student_id = ".$student_id;
				$querypay = mysql_query($sqlpay);
				while($row = mysql_fetch_array($querypay)){
					if($row['is_bounced']=='N' && $row['is_refund']=='N' && $row['payment_term']=='F'){
						$sqlup = "UPDATE tbl_student_payment set payment_term='P' 
						WHERE student_id = ".$student_id." AND id=".$row['id'];
						$queryup = mysql_query($sqlup);
					}
				}
				
				// [+] STORE SYSTEM LOGS
				$param = array(	
								getStudentNumber($student_id),
								getSYandTerm($term_id)
							   );
				storeSystemLogs(MSG_ADMIN_ADD_SUBJECT_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
				// [-] STORE SYSTEM LOGS	
				
				//notification(getStudentUser($student_id),MSG_ADMIN_ADD_SUBJECT_FOR_STUDENT,$param,USER_ID);
				//notification(getParentUser($student_id),MSG_ADMIN_ADD_SUBJECT_FOR_STUDENT,$param,USER_ID);
										
				$add_student_action_remarks = "SUCCESSFUL";
			}
		}
	}
?>

<script type="text/javascript">

$(function(){
	$('#search_student_dialog').dialog({
		autoOpen: false,
		width: 650,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Close": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	$('#select_subject_dialog').dialog({
		autoOpen: false,
		width: 650,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Close": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
});


function searchStudent(){
	$('#search_student_dialog').load('viewer/viewer_com_schedule_student_list_2_1_add_searchstudent.php?comp=<?=$_REQUEST['comp']?>&searchtype=&searchvalue=',null);
	$('#search_student_dialog').dialog('open');
}

function selectStudentSubject(){
	if(document.getElementById("student_id").value!=""){
		student_id = document.getElementById("student_id").value;
		student_courseid = document.getElementById("student_courseid").value;
		student_curriculumid = document.getElementById("student_curriculumid").value;
		$('#select_subject_dialog').load('viewer/viewer_com_schedule_student_list_2_1_add_selectsubject.php?comp=<?=$_REQUEST['comp']?>&studentid='+student_id+'&courseid='+student_courseid+'&curriculumid='+student_curriculumid,null);
		$('#select_subject_dialog').dialog('open');
	}else{
		alert("PLEASE SELECT STUDENT!");
	}
}

function enrollStudentToClass(){
	student_id = document.getElementById("student_id").value;
	subject_id = document.getElementById("student_subjectid").value;
	course_id = document.getElementById("student_courseid").value;
	curriculum_id = document.getElementById("student_curriculumid").value;
	
	if(student_id==""){
		alert("Please enter student.");
		return;
	}
	
	if(subject_id==""){
		alert("Please select subject.");
		return;
	}
	
	$('#add_student_dialog').load('viewer/viewer_com_schedule_student_list_2_1_add.php?comp=<?=$_REQUEST['comp']?>&term_id=<?=$term_id?>&schedule_id=<?=$schedule_id?>&add_student_action=ADDSAVE&student_id=' + student_id + '&subject_id=' + subject_id + '&course_id=' + course_id + '&curriculum_id=' + curriculum_id, null);
}


function updateEntry(){
	document.getElementById("tdAdd_StudentNo").innerHTML = document.getElementById("student_number").value;
	document.getElementById("tdAdd_StudentName").innerHTML = document.getElementById("student_lastname").value + ", " + document.getElementById("student_firstname").value + " " + document.getElementById("student_middlename").value;
	document.getElementById("tdAdd_Course").innerHTML = "(" + document.getElementById("student_courseid").value + ") " + document.getElementById("student_coursecode").value + " - " + document.getElementById("student_coursename").value;
	document.getElementById("tdAdd_Curriculum").innerHTML = "(" + document.getElementById("student_curriculumid").value + ")";
	document.getElementById("tdAdd_Subject").innerHTML = "(" + document.getElementById("student_subjectid").value + ") " + document.getElementById("student_subjectcode").value + " - " + document.getElementById("student_subjectname").value;
}

function clearEntry(){
	document.getElementById("student_id").value = "";
	document.getElementById("student_number").value = "";
	document.getElementById("student_lastname").value = "";
	document.getElementById("student_firstname").value = "";
	document.getElementById("student_middlename").value = "";
	document.getElementById("student_courseid").value = "";
	document.getElementById("student_coursecode").value = "";
	document.getElementById("student_coursename").value = "";
	document.getElementById("student_subjectid").value = "";
	document.getElementById("student_subjectcode").value = "";
	document.getElementById("student_subjectname").value = "";
	document.getElementById("student_curriculumid").value = "";
}

$(document).ready(function(){
	updateEntry();
	
	<?php
	if($add_student_action=="ADDSAVE"){
		if($add_student_action_remarks=="SUCCESSFUL"){
			?>
			alert("Student is now enrolled in class.");
			$('#add_student_dialog').dialog("close");
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
	<table class="classic" style="width:550px;">
		<tr>
			<th style="width:70px;">&nbsp;</th>
			<th>&nbsp;</th>
			<th style="width:70px;">&nbsp;</th>
		</tr>
		<tr>
			<td>Student No.</td>
			<td id="tdAdd_StudentNo"></td>
			<td align="center">
				<a href="#" class="button" onclick="searchStudent()" ><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:60px; height:15px; display:table-cell; vertical-align:middle;">SEARCH</div></a>
			</td>
		</tr>
		<tr>
			<td>Name</td>
			<td id="tdAdd_StudentName"></td>
			<td></td>
		</tr>
		<tr>
			<td>Course</td>
			<td id="tdAdd_Course"></td>
			<td></td>
		</tr>
		<tr>
			<td>Curriculum</td>
			<td id="tdAdd_Curriculum"></td>
			<td></td>
		</tr>
		<tr>
			<td>Subject</td>
			<td id="tdAdd_Subject"></td>
			<td align="center">
				<a href="#" class="button" onclick="selectStudentSubject()" ><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:60px; height:15px; display:table-cell; vertical-align:middle;">SELECT</div></a>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<a href="#" class="button" onclick="enrollStudentToClass()" ><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:300px; height:20px; display:table-cell; vertical-align:middle;">ENROLL STUDENT TO CLASS</div></a>
			</td>
			<td></td>
		</tr>
	</table>
</div>
<input type="hidden" id="term_id" value="<?=$term_id?>"/>
<input type="hidden" id="schedule_id" value="<?=$schedule_id?>"/>
<input type="hidden" id="student_id" value="<?=$student_id?>"/>
<input type="hidden" id="student_number" value="<?=$student_number?>"/>
<input type="hidden" id="student_lastname" value="<?=$student_lastname?>"/>
<input type="hidden" id="student_firstname" value="<?=$student_firstname?>"/>
<input type="hidden" id="student_middlename" value="<?=$student_middlename?>"/>
<input type="hidden" id="student_courseid" value="<?=$student_courseid?>"/>
<input type="hidden" id="student_coursecode" value="<?=$student_coursecode?>"/>
<input type="hidden" id="student_coursename" value="<?=$student_coursename?>"/>
<input type="hidden" id="student_subjectid" value="<?=$student_subjectid?>"/>
<input type="hidden" id="student_subjectcode" value="<?=$student_subjectcode?>"/>
<input type="hidden" id="student_subjectname" value="<?=$student_subjectname?>"/>
<input type="hidden" id="student_curriculumid" value="<?=$student_curriculumid?>"/>

<?php
}
?>
