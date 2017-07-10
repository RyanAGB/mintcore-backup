<?php
include_once("../config.php");
include_once("../includes/functions.php");

error_reporting(0);

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp'])){
	header('Location: ../forbid.html');
}else{
	$student_id = $_REQUEST['studentid'];
	$course_id = $_REQUEST['courseid'];
	$curriculum_id = $_REQUEST['curriculumid'];
?>

<script type="text/javascript">

function selectSubject(subject_id, subject_code, subject_name){
	document.getElementById("student_subjectid").value = subject_id;
	document.getElementById("student_subjectcode").value = subject_code;
	document.getElementById("student_subjectname").value = subject_name;
	updateEntry();
	$('#select_subject_dialog').dialog("close");
}

</script>

<div id="lookup_content">
	<table class="classic" style="width:600px;">
		<tr style="border-bottom:solid 2px #000000;">
			<th style="width:70px;weight:bold;">&nbsp;</th>
			<th style="width:120px;weight:bold;">Code</th>
			<th style="weight:bold;">Subject Name</th>
			<th style="width:50px;"></th>
		</tr>
	<?php
	$sql = "SELECT tbl_course.id AS 'course_id', tbl_course.course_code, tbl_course.course_name, 
					tbl_curriculum.id AS 'curriculum_id', 
					tbl_curriculum_subject.id AS 'curriculum_subject_id', 
					tbl_curriculum_subject.year_level, tbl_curriculum_subject.term, 
					tbl_curriculum_subject.subject_id AS 'id', 
					tbl_subject.subject_code, tbl_subject.subject_name 
				FROM tbl_course LEFT JOIN tbl_curriculum ON tbl_course.id=tbl_curriculum.course_id 
				LEFT JOIN tbl_curriculum_subject ON tbl_curriculum.id=tbl_curriculum_subject.curriculum_id 
				LEFT JOIN tbl_subject ON tbl_curriculum_subject.subject_id=tbl_subject.id 
				WHERE tbl_curriculum.id=".$curriculum_id."  
				ORDER BY tbl_curriculum_subject.year_level, tbl_curriculum_subject.term, tbl_subject.subject_name ASC";
		$result = mysql_query($sql);
		$subjectYear = 0;
		$subjectTerm = 0;
		while($row = mysql_fetch_array($result)){
             
			if($subjectYear!=$row["year_level"] || $subjectTerm!=$row["term"]){
				?>
		<tr>
			<td colspan="4" style="font-weight:bold;text-align:center;" ><br/><?=getNumberAsRank_2_1($row["year_level"])." Year &nbsp / &nbsp ".getNumberAsRank_2_1($row["term"])." Term"?></td>
		</tr>
				<?php
				$subjectYear = $row["year_level"];
				$subjectTerm = $row["term"];
				if($x%2==0){
					$x++;
				}
			}
			?>
			
		<tr class="<?=($x%2==0)?"":"highlight";?>">
			<td>
				<a href="#" class="button" onclick='selectSubject("<?=$row["id"]?>","<?=$row["subject_code"]?>","<?=$row["subject_name"]?>")'><div style="border:solid #000000 1px; background-color:#cccccc; font-weight:bold; width:60px; height:20px; display:table-cell; vertical-align:middle;text-align:center;">Select</div></a>
			</td>
			<td><?="(".$row["id"].") ".$row["subject_code"]?></td>
			<td><?=$row["subject_name"]?></td>
			<td></td>
		</tr>
			<?php
			$x++;   
		}  
		?>
	
	</table>
</div>
<?php
}
?>