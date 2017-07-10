<?php
include_once("../config.php");
include_once("../includes/functions.php");

error_reporting(0);

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp'])){
	header('Location: ../forbid.html');
}else{
	$id = $_REQUEST['id'];
	$filter_schoolterm = $_REQUEST['filter_schoolterm'];
	$filterfield = $_REQUEST['filterfield'];
	$filtervalue = $_REQUEST['filtervalue'];
	$filterfield2 = $_REQUEST['filterfield2'];
	$filtervalue2 = $_REQUEST['filtervalue2'];
	$filter = $_REQUEST['filter']!=''?$_REQUEST['filter']:'E';
?>
<script type="text/javascript">
$(function(){
	$('.selector').click(function() {
		//var id = $(this).attr("val");		
		var valTxt = $(this).attr("returnTxt");
		var valId = $(this).attr("returnId");
		$('#room_id').attr("value", valId);
		$('#room_display').attr("value", valTxt);
		$('#dialog').dialog('close');			
	});
		
	$('#print').click(function() {
		var w=window.open();
		w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
		w.document.write($('#lookup_content').html());
		w.document.close();
		w.focus();
		w.print();
		//w.close()
		return false;
	});
		
	$('#pdf').click(function() {
		var w=window.open ("pdf_reports/rep108.php?id="+<?=$id?>+"&trm="+<?=$filter_schoolterm?>+"&met=schedule list");
		//&course="+$('#filtercourse').val()+"&year="+$('#filteryear').val() 
		return false;
	});
		
	$('#email').click(function() {
		if(confirm("Are you sure you want to email this file to this student?")){
			$.ajax({
				type: "POST",
				data: "id="+<?=$id?>+"&trm="+<?=$filter_schoolterm?>+"&met=schedule&email=1",
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
		}else{
			return false;
		}
	});
		
	$('#filtercourse').change(function(){
		$.ajax({
			type: "POST",
			data: "mod=updateYearForSched&id=" + $('#filtercourse').val() +"&ad=T",
			url: "ajax_components/ajax_com_year_field_updater.php",
			success: function(msg){
				if (msg != ''){
					$("#filteryear").html(msg);
				}
			}
		});	

		updateList();	
	});
		
	$('#filteryear').change(function(){
		updateList();
	});
		
	$('#filter').change(function(){
		updateList();
	});
});
	
function updateList(){
	var param = '';
	/*
	if($('#filtercourse').val() != ''){
		param = param + '&filterfield=b.course_id&filtervalue=' + $('#filtercourse').val();
	}
	
	if($('#filteryear').val() != ''){
		param = param + '&filterfield2=b.year_level&filtervalue2=' + $('#filteryear').val();
	}
	*/
	if($('#filter').val() != ''){
		param = param + '&filter=' + $('#filter').val();
	}
	param = param + '&comp='+ $('#comp').val()+'&id='+<?=$id?>+'&filter_schoolterm='+<?=$filter_schoolterm?>;
	// alert(param);
	$('#dialog').load('viewer/viewer_com_schedule_student_list.php?listrow=10'+ param, null);
}
</script>

<?php	
	/*
	if(isset($filterfield)){
		$arr_sql[] =  $filterfield.'='.$filtervalue;
	}	
	
	if(isset($filterfield2)){
		$arr_sql[] =  $filterfield2.'='.$filtervalue2;
	}	
	
	if(count($arr_sql) > 0){
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : ' AND ' .$arr_sql = implode(' AND ', $arr_sql);
	}
	*/
	
	if($filter == 'R'){
		$sqldet = "SELECT * FROM tbl_schedule WHERE id = $id";
		$resultdet = mysql_query($sqldet);
		$row = mysql_fetch_array($resultdet);
		$sql = "SELECT * FROM tbl_student_reserve_subject a,tbl_student b 
				WHERE a.student_id=b.id 
				AND a.schedule_id =  " . $id . " AND a.term_id = ".$filter_schoolterm;
				//$sqlcondition." ORDER BY b.lastname ASC" ;	
	}else{
		$sqldet = "SELECT * FROM tbl_schedule WHERE id = $id";
		$resultdet = mysql_query($sqldet);
		$row = mysql_fetch_array($resultdet);
		$sql = "SELECT * FROM tbl_student_schedule a,tbl_student b 
				WHERE a.student_id=b.id 
				AND a.schedule_id =  " . $id . " AND a.term_id = ".$filter_schoolterm; 
				//$sqlcondition ;	
	}
										
	$result = mysql_query($sql);
	$ctr = mysql_num_rows($result);	
?>

<!--Filter By: Course<select name="filtercourse" id="filtercourse" class="txt_200" >
	<option value="" >-Select-</option>
    <?//=generateCourse($filtervalue)?>  
    </select>
    <input type="hidden" name="comp" id="comp" value="<?//=$_REQUEST['comp']?>" />
    <label>&nbsp;</label>
            
    Year<select name="filteryear" id="filteryear" class="txt_100" >
			<option value="" >-Select-</option>
            <?//=generateYearLevelByCourse($filtervalue,$filtervalue2)?>
		</select>!-->
                
<select name="filter" id="filter" class="txt_200" >
	<option value="E" <?=$filter=='E'?'selected="selected"':''?>>Enrolled</option>
	<option value="R" <?=$filter=='R'?'selected="selected"':''?> >Reserved</option>
</select>
            
<label>&nbsp;</label>
<div id="lookup_content">
	<div id="printable">
		<div class="body-container">
			<div class="header">
	<?php
	/*if( $filterfield!=''&&$filterfield2!=''){*/ 
	?>
				<table width="100%">
					<tr>
						<td colspan="3" align="center" class="bold">CLASS LIST | <?=getSchoolTerm($filter_schoolterm)?> | SY <?=getSchoolYearStartEndByTerm($filter_schoolterm)?></td>
					</tr>
					<tr>
						<td class="bold" valign="top">Subject Code:</td>
						<td><?=getSubjCode($row["subject_id"])?></td>
						<td valign="top" class="bold">Class Schedule:</td>
					</tr>
					<tr>
						<td class="bold">Subject Title:</span></td>
						<td><?=getSubjName($row["subject_id"])?></td>
						<td><?=getScheduleDays($id)?></td>
						<!--<td class="bold" valign="top">Department:</td>
						<td><?=getStudentCollegeName($filtervalue)?></td>!-->
					</tr>
					<tr>
						<td class="bold" valign="top">Instructor:</td>
						<td><?=getEmployeeFullNameBySchedId($id)?></td>
						<td><?=getRoomNo($row["room_id"])?></td>
						<!--<td class="bold">Room:</span></td>
						<td><?=getRoomNo($row["room_id"])?></td>!-->
					</tr>   
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan=3 class="title-action">
    <?php
	if($ctr > 0){
	?>
							<a class="viewer_email" href="#" id="email" title="email"></a>
							<a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
							<a class="viewer_print" href="#" id="print" title="print"></a>
    <?php
	}?>
						</td>
					</tr>
					<tr>
						<td colspan=3>&nbsp;</td>
					</tr>
				</table>
 
			</div>
			<div class="content-container">
			<div class="content-wrapper-withoutBorder">
				<table class="classic">      
					<tr>
						<th class="col1_50">&nbsp;</th>
						<th class="col1_150">Student No.</th>
						<th class="col1_150">Student Name</th>
						<th class="col1_150">Course</th>
					</tr>
	<?php
	$x = 1;
		
	if($ctr > 0){
		while($row = mysql_fetch_array($result)){ 
			?>
					<tr class="<?=($x%2==0)?"":"highlight";?>">
						<td><?=$x?></td>
						<td><?=$row["student_number"]?></td> 
						<td><?=$row["lastname"].' , '.$row["firstname"]." ".$row["middlename"]?></td> 
						<td><?=getCourseCode($row["course_id"])?></td>
					</tr>
			<?php 
            $x++;          
        }
	}else{
		?>
					<tr> 
						<td colspan="7">No record found</td>                                
					</tr>        
		<?php
	}
	?>
				</table> 
	<?php
    /*
	}else{
		?>
				<div style="font-family:Arial; font-size:12px; padding-top:10px;">
					<label>Please select course.</label>
				</div>
		<?php
	}
	*/
	?>
			</div> 
		</div>
	</div>
</div><!-- #lookup_content -->
<?php
}
?>