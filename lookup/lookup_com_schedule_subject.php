<?php
include_once("../config.php");
include_once("../includes/functions.php");
include_once("../includes/common.php");

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp'])){
	header('Location: ../forbid.html');
}else{
	$filterfield = $_REQUEST['filterfield'];
	$filtervalue = $_REQUEST['filtervalue'];
	?>
<script type="text/javascript">
$(function(){
	$('.selector').click(function() {
		//var id = $(this).attr("val");		
		var valTxt = $(this).attr("returnTxt");
		var valId = $(this).attr("returnId");
		var valElec = $(this).attr("retElec");
		$('#subject_id').attr("value", valId);
		$('#subject_display').attr("value", valTxt);
			
		if(valElec==1){
			$('#el_subj').attr('style','block');
		}
		$('#subject_dialog').dialog('close');			
	});

	$('#filtersubject').change(function(){
		updateList();
	});
});

function updateList(){
	var param = '';
		
	if($('#filtersubject').val() != ''){
		param = param + '&filterfield=course_id&filtervalue=' + $('#filtersubject').val();
	}
	param = param + '&comp='+ $('#comp').val();
	//alert(param);
	$('#subject_dialog').load('lookup/lookup_com_schedule_subject.php?listrow=10'+ param, null);
}
</script>

	<?php
	if(isset($filterfield) && $filtervalue!='all'){
		$sqlcon = 'AND '.$filterfield.'='.$filtervalue;
	}
	?>

<div id="lookup_content">
	Filter By: Course
	<select name="filtersubject" id="filtersubject" class="txt_400" >
		<option value="" >-Select-</option>
		<!--<option value="all" <?=$filterfield == 'all' ? 'selected="selected"' : '';?> >All</option>-->
			<?=getCourses_2_1($filtervalue)?>  
	</select>
	<input type="hidden" name="comp" id="comp" value="<?=$_REQUEST['comp']?>" />
	<label>&nbsp;</label>
 
	<?php
	$x = 1;
	if( $filterfield!=''){
		?>
	<table class="fieldsetList">
		<tr>
			<th class="col1_50">&nbsp;</th> 
			<th class="col1_150">Code</th>
			<th class="col1_150">Subject Name</th>
		</tr>
		
		<?php
		//$sql = "SELECT * FROM tbl_subject WHERE 0=1";// publish = 'Y' " . $sqlcon . " ORDER BY `subject_code` ASC";
		$sql = "SELECT tbl_course.id AS 'course_id', tbl_course.course_code, tbl_course.course_name, 
					tbl_curriculum.id AS 'curriculum_id', tbl_curriculum.is_current, 
					tbl_curriculum_subject.id AS 'curriculum_subject_id', 
					tbl_curriculum_subject.year_level, tbl_curriculum_subject.term, 
					tbl_curriculum_subject.subject_id AS 'id', 
					tbl_subject.subject_code, tbl_subject.subject_name 
				FROM tbl_course LEFT JOIN tbl_curriculum ON tbl_course.id=tbl_curriculum.course_id 
				LEFT JOIN tbl_curriculum_subject ON tbl_curriculum.id=tbl_curriculum_subject.curriculum_id 
				LEFT JOIN tbl_subject ON tbl_curriculum_subject.subject_id=tbl_subject.id 
				WHERE tbl_curriculum.is_current='Y' ".$sqlcon." 
				ORDER BY tbl_curriculum_subject.year_level, tbl_curriculum_subject.term, tbl_subject.subject_name ASC";
		$result = mysql_query($sql);

		$subjectYear = 0;
		$subjectTerm = 0;
		while($row = mysql_fetch_array($result)){
             
			if($subjectYear!=$row["year_level"] || $subjectTerm!=$row["term"]){
				?>
		<tr>
			<td colspan="3" style="font-weight:bold;text-align:center;" ><br/><?=getNumberAsRank_2_1($row["year_level"])." Year &nbsp / &nbsp ".getNumberAsRank_2_1($row["term"])." Term"?></td>
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
			<td><a href="#" name="id" id="id_<?=$row['id']?>" class="selector" returnId="<?=$row['id']?>" returnTxt="<?=$row['subject_name']?>" retElec="<?=checkIfElective($row['id'])?>">Select</a></td>
			<td><?="(".$row["id"].") ".$row["subject_code"]?></td>
			<td><?=$row["subject_name"]?></td>
		</tr>
			<?php
			$x++;   
		}  
		?>
	</table> 
    
		<?php 
		if(mysql_num_rows($result) == 0){  
			?>
	<div style="font-family:Arial; font-size:12px; padding-top:10px;">
		<label>No Records Found.</label>
	</div>
		<?php
		}
	}else{
	?>
	<div style="font-family:Arial; font-size:12px; padding-top:10px;">
		<label>Please select department.</label>
	</div>
	<?php
	}
?>
</div> <!-- #lookup_content -->
<?php
}
?>