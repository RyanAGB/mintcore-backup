<?php
include_once("../config.php");
include_once("../includes/functions.php");

error_reporting(0);

$searchType = "";
$lastnameInitial = "";
$lastname = "";
$firstname = "";

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp'])){
	header('Location: ../forbid.html');
}else{	
	if($_REQUEST['searchtype']){
		$searchType = $_REQUEST['searchtype'];

		if($searchType=="LASTNAME"){
			$lastnameInitial = $_REQUEST['lastnameinitial'];
		}else if($searchType=="STUDENTNAME"){
			$lastname = $_REQUEST['lastname'];
			$firstname = $_REQUEST['firstname'];
		}
	}
?>

<script type="text/javascript">

function searchStudentByLastNameInitial(lastnameInitial){
	$('#search_student_dialog').load('viewer/viewer_com_schedule_student_list_2_1_add_searchstudent.php?comp=<?=$_REQUEST['comp']?>&searchtype=LASTNAME&lastnameinitial='+lastnameInitial, null);
}

function searchStudentByName(lastname, firstname){
	$('#search_student_dialog').load('viewer/viewer_com_schedule_student_list_2_1_add_searchstudent.php?comp=<?=$_REQUEST['comp']?>&searchtype=STUDENTNAME&lastname='+lastname+'&firstname='+firstname, null);
}

function selectStudent(student_id, student_number, student_lastname, student_firstname, student_middlename, student_courseid, student_coursecode, student_coursename, student_curriculumid){
	document.getElementById("student_id").value = student_id;
	document.getElementById("student_number").value = student_number;
	document.getElementById("student_lastname").value = student_lastname;	
	document.getElementById("student_firstname").value = student_firstname;	
	document.getElementById("student_middlename").value = student_middlename;	
	document.getElementById("student_courseid").value = student_courseid;	
	document.getElementById("student_coursecode").value = student_coursecode;	
	document.getElementById("student_coursename").value = student_coursename;	
	document.getElementById("student_curriculumid").value = student_curriculumid;
	document.getElementById("student_subjectid").value = "";
	document.getElementById("student_subjectcode").value = "";
	document.getElementById("student_subjectname").value = "";
	updateEntry();
	$('#search_student_dialog').dialog("close");
}

</script>


<div id="lookup_content">
	<table>
		<tr>
			<td valign="bottom" style="width:420px;">
				<table>
					<tr>
						<td>
							LASTNAME
						</td>
						<td>
							FIRSTNAME
						</td>
					</tr>
					<tr>
						<td>
							<input id="txtStudentLastName" type="text" onkeypress="if(event.keyCode==13){searchStudentByName(document.getElementById('txtStudentLastName').value, document.getElementById('txtStudentFirstName').value)}" style="width:150px; height:20px; font-weight:bold; border:solid 1px;" value="<?=$lastname?>"/>
						</td>
						<td>
							<input id="txtStudentFirstName" type="text" onkeypress="if(event.keyCode==13){searchStudentByName(document.getElementById('txtStudentLastName').value, document.getElementById('txtStudentFirstName').value)}" style="width:150px; height:20px; font-weight:bold; border:solid 1px;" value="<?=$firstname?>"/>
						</td>
						<td style="text-align:center;">
							<a href="#" class="button" onclick="searchStudentByName(document.getElementById('txtStudentLastName').value, document.getElementById('txtStudentFirstName').value)"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:70px; height:20px; display:table-cell; vertical-align:middle;">SEARCH</div></a>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table>
					<tr>
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('A')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">A</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('B')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">B</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('C')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">C</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('D')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">D</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('E')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">E</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('F')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">F</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('G')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">G</div></a></td>						
					</tr>
					<tr>
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('H')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">H</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('I')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">I</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('J')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">J</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('K')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">K</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('L')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">L</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('M')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">M</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('N')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">N</div></a></td>						
					</tr>
					<tr>
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('O')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">O</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('P')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">P</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('Q')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">Q</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('R')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">R</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('S')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">S</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('T')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">T</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('U')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">U</div></a></td>						
					</tr>
					<tr>
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('V')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">V</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('W')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">W</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('X')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">X</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('Y')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">Y</div></a></td>						
						<td style="text-align:center;margin:0px;"><a href="#" class="button" onclick="searchStudentByLastNameInitial('Z')"><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:20px; height:20px; display:table-cell; vertical-align:middle;">Z</div></a></td>						
						<td></td>
						<td></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table class="classic" style="width:600px;">
		<tr>
			<th style="width:70px;weight:bold;"></th>
			<th style="width:100px;weight:bold;">Student No.</th>
			<th style="weight:bold;">Student Name</th>
			<th style="width:100px;weight:bold;">Course</th>
		</tr>
	</table>
	<div style="border-top:solid 1px #000000; border-bottom:solid 1px #000000; width:620px; height:260px; overflow:auto;">
		<table class="classic" style="width:600px;">
	<?php
	$strSQL = "";
	if($searchType=="LASTNAME"){
		$strSQL = "SELECT s.id, s.student_number, 
						s.course_id, c.course_code, c.course_name, 
						s.lastname, s.firstname, s.middlename, 
						s.curriculum_id 
					FROM tbl_student s, tbl_course c 
					WHERE s.course_id=c.id 
					AND s.lastname LIKE '".$lastnameInitial."%' 
					ORDER BY lastname ASC, firstname ASC, middlename ASC";
	}else if($searchType=="STUDENTNAME"){
		$strSQL = "SELECT s.id, s.student_number, 
						s.course_id, c.course_code, c.course_name, 
						s.lastname, s.firstname, s.middlename, 
						s.curriculum_id 
					FROM tbl_student s, tbl_course c 
					WHERE s.course_id=c.id 
					AND s.lastname LIKE '".$lastname."%' 
					AND s.firstname LIKE '".$firstname."%' 
					ORDER BY lastname ASC, firstname ASC, middlename ASC";
	}else{
		$strSQL = "SELECT s.id, s.student_number, 
						s.course_id, c.course_code, c.course_name, 
						s.lastname, s.firstname, s.middlename, 
						s.curriculum_id 
					FROM tbl_student s, tbl_course c 
					WHERE s.course_id=c.id 
					AND 0=1";
	}
	$result = mysql_query($strSQL);
	$ctr = mysql_num_rows($result);
	$iRow = 0;
	if($ctr>0){
		while($row=mysql_fetch_array($result)){
			$iRow = $iRow + 1;
		?>
			<tr class="<?=($iRow%2==0)?"":"highlight"?>">
				<td style="width:70px;">
					<a href="#" class="button" onclick='selectStudent("<?=$row["id"]?>","<?=$row["student_number"]?>","<?=$row["lastname"]?>","<?=$row["firstname"]?>","<?=$row["middlename"]?>","<?=$row["course_id"]?>","<?=$row["course_code"]?>","<?=$row["course_name"]?>","<?=$row["curriculum_id"]?>")'><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:60px; height:20px; display:table-cell; vertical-align:middle;text-align:center;">Select</div></a>
				</td>
				<td style="width:100px;"><?=$row["student_number"]?></td>
				<td><?=$row["lastname"].", ".$row["firstname"]." ".$row["middlename"]?></td>
				<td style="width:100px;" title="<?=$row["course_name"]?>"><?=$row["course_code"]?></td>
			</tr>
		<?php
		}
	}
	?>
		</table>
	</div>
</div>

<?php
}
?>