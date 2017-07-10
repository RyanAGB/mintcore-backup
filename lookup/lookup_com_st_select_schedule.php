<?php
include_once("../config.php");
include_once("../includes/functions.php");
include_once("../includes/common.php");

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp'])){
	header('Location: ../forbid.html');
}else{
	$cid = $_REQUEST['cid'];
	$id  = $_REQUEST['id'];
	$order = $_REQUEST['ord'];
	$subjectType  = $_REQUEST['subjectType'];
	$subjectUnit  = $_REQUEST['subjectUnit'];

	if($subjectType == 'R'){
		$sql = "SELECT sched.id, sched.section_no, sched.subject_id, sched.room_id, sched.term_id,sched.employee_id,sched.elective_of,
					sched.monday, sched.tuesday, sched.wednesday, sched.thursday, 
					sched.friday, sched.saturday, sched.sunday,
					sub.subject_code, sub.subject_name,
					rum.room_no, sched.number_of_student,
					sched.number_of_available 
				FROM tbl_schedule sched
				LEFT JOIN tbl_subject sub
				ON sub.id = sched.subject_id
				LEFT JOIN tbl_room rum
				ON sched.room_id = rum.id
				WHERE sched.term_id = '".CURRENT_TERM_ID."'
				AND sched.subject_id = " . $id; 
	$result = mysql_query($sql);
	?>

<script type="text/javascript">
$(function(){
	$('.selector').click(function(){
		//var id = $(this).attr("val");		
		var valTxt = $(this).attr("returnTxt");
		var valName = $(this).attr("returnName");
		var valSubjId = $(this).attr("returnSubjId");
		var valId = $(this).attr("returnId");
		var ObjId = $(this).attr("returnObjId");
		var valDay = $(this).attr("returnDay");
		var valSepDay = $(this).attr("returnSepDay").split('/');
		var access = $('#access').val();
		var subject_unit = <?=$subjectUnit?>;
		//var order = <?=$order?>;
		var total_select_units = $('#enrolled_unit').val();
		var total_max_units = $('#max_unit').val();
		var inc_units = parseInt(total_select_units) + parseInt(subject_unit);		
		
		if(valSepDay!=''){
			if(checkConflictSched(valSepDay,ObjId)){
				if(access==1){
					$.ajax({
						type: "POST",
						data: "mod=compute&id=" + valId,
						url: "ajax_components/ajax_com_slot_updater.php",
						success: function(msg){
							if (msg != ''){
							//alert(msg);
							}
						}
					});

					if($('#schedule_id_'+ObjId).val() == ''){
						$('#span_select_units').html(inc_units);
						$('#enrolled_unit').val(inc_units);
					}

					$('#schedule_id_'+ObjId).attr("value", valId);
					$('#schedule_id_display').attr("value", valTxt);
					$('#subject_id_'+ObjId).val(valSubjId);
					$('#day_'+ObjId).html(valDay);
					$('#days_'+ObjId).val($(this).attr("returnSepDay"));

					if($('#tr_'+ObjId).attr('class')=='highlight_error'){
						$('#tr_'+ObjId).attr('class','');
					}
					$('#dialog').dialog('close');
				}else{
					/*if(inc_units > total_max_units && $('#schedule_id_'+ObjId).val() == '')
				{
				alert('You are only allowed to enroll maximum of ' + total_max_units + ' units.');
			}else{*/	
					$.ajax({
						type: "POST",
						data: "mod=compute&id=" + valId,
						url: "ajax_components/ajax_com_slot_updater.php",

						success: function(msg){
							if (msg != ''){
								//alert(msg); 
							}
						}
					});

					if($('#schedule_id_'+ObjId).val() == ''){
						$('#span_select_units').html(inc_units);
						$('#enrolled_unit').val(inc_units);
					}
					$('#schedule_id_'+ObjId).attr("value", valId);
					$('#schedule_id_display').attr("value", valTxt);
					$('#subject_id_'+ObjId).val(valSubjId);
					$('#day_'+ObjId).html(valDay);
					$('#days_'+ObjId).val($(this).attr("returnSepDay"));
			//}
					if($('#tr_'+ObjId).attr('class')=='highlight_error'){
						$('#tr_'+ObjId).attr('class','');
					}
					$('#dialog').dialog('close');
				}
			}else{
				alert('This time slot conflicts with your existing schedule.');
				return false;
			}
		}else{
			$.ajax({
				type: "POST",
				data: "mod=compute&id=" + valId,
				url: "ajax_components/ajax_com_slot_updater.php",
				
				success: function(msg){
					if (msg != ''){
						//alert(msg);
					}
				}
			});

			if($('#schedule_id_'+ObjId).val() == ''){
				$('#span_select_units').html(inc_units);
				$('#enrolled_unit').val(inc_units);
			}
			$('#schedule_id_'+ObjId).attr("value", valId);
			$('#schedule_id_display').attr("value", valTxt);
			$('#subject_id_'+ObjId).val(valSubjId);
			$('#day_'+ObjId).html(valDay);
			$('#days_'+ObjId).val($(this).attr("returnSepDay"));

			if($('#tr_'+ObjId).attr('class')=='highlight_error'){
				$('#tr_'+ObjId).attr('class','');
			}
			$('#dialog').dialog('close');
		}
	});
});

$(document).ready(function(){ 
	$.each($("input[name*=schedred]"), function(index, value){ 
		var obId = $(this).val();
		var arrDay = $('#dy_'+obId).val().split("/");

		if(!checkConflictSched(arrDay,obId)){
			$('#trl_'+obId).attr('class','highlight_error');
		}
	});
});
</script>

<div id="lookup_content">
	<table class="fieldsetList" style="width:800px;">      
		<tr>
			<th class="col1_70">&nbsp;</th>
			<th class="col1_50"><a href="#">Section</a></th>
			<th class="col1_50"><a href="#">Code</a></th>  
			<th class="col1_150"><a href="#">Subject Name</a></th> 
			<th class="col1_150"><a href="#">Professor</a></th>                           
			<th class="col1_50"><a href="#">Schedule</a></th>                 
			<th class="col1_100"><a href="#">Room</a></th> 
			<th class="col1_50"><a href="#">Slot</a></th> 
		</tr>
<?php
	$x = 1;

	while($row = mysql_fetch_array($result)){ 
        ?>

		<tr id="trl_<?=$row['id']?>" class="<?=($x%2==0)?"":"highlight";?>">
			<td>
		<?php
		if($row["number_of_available"]>0){
			?>
				<a href="#" name="id" id="id_<?=$row['id']?>" class="selector" returnObjId="<?=$id?>"  returnId="<?=$row['id']?>" returnTxt="<?=$row['subject_code']?>" returnName="<?=$row['subject_name']?>" returnSubjId="<?=$row['subject_id']?>" returnDay="<?=getScheduleDays($row['id'])?>" returnSepDay="<?=getSepScheduleDays($row['id'])?>">Select</a>
		<?php
		}else{
			echo '&nbsp;&nbsp;&nbsp;';
		}
		?>
			</td>
			<td><?="(".$row["id"].")".$row["section_no"]?></td>
			<td><?="(".$row["subject_id"].")".$row["subject_code"]?></td>
			<td><?=$row["subject_name"]?></td>
			<td><?=$row["employee_id"]!=''?getProfessorFullName($row["employee_id"]):'-'?></td>
			<td><?=getScheduleDays($row['id'])?></td>
			<td><?=$row["room_no"]!=''?$row["room_no"]:'-'?></td>
			<td><?=$row["number_of_available"].'/'.$row["number_of_student"]?></td>
		</tr>
		<input type="hidden" name="schedred[]" id="schedred_<?=$row['id']?>" value="<?=$row['id']?>">
		<input type="hidden" name="dy_<?=$row['id']?>" id="dy_<?=$row['id']?>" value="<?=getSepScheduleDays($row['id'])?>">
		<?php 
		$x++;       
	}
	?>
		<input type="hidden" id="access" name="access" value="<?=ACCESS_ID?>" />
	</table> 
</div> <!-- #lookup_content -->   


	<?php
	}else if($subjectType == 'E'){
		/*$sql = "SELECT 
					sched.id, sched.section_no, sched.subject_id, sched.room_id, sched.term_id,sched.employee_id,
					sched.monday, sched.tuesday, sched.wednesday, sched.thursday, 
					sched.friday, sched.saturday, sched.sunday, sched.time_from, sched.time_to,
					sub.subject_code, sub.subject_name,
					rum.room_no, sched.number_of_available, sched.number_of_student
				FROM 
					tbl_schedule sched 
				LEFT JOIN tbl_subject sub ON sub.id = sched.subject_id
				LEFT JOIN tbl_room rum ON sched.room_id = rum.id 
				LEFT JOIN tbl_curriculum_subject cur ON cur.subject_id = sub.id
				WHERE 
					sched.term_id = '1' AND 
					cur.subject_category = 'EO'"
				;*/
		$sql = "SELECT sched.id, sched.section_no, sched.subject_id, sched.room_id, sched.term_id,sched.employee_id,sched.elective_of,
					sched.monday, sched.tuesday, sched.wednesday, sched.thursday, 
					sched.friday, sched.saturday, sched.sunday,
					sub.subject_code, sub.subject_name,
					rum.room_no, sched.number_of_student,
					sched.number_of_available 
				FROM tbl_schedule sched
				LEFT JOIN tbl_subject sub
				ON sub.id = sched.subject_id
				LEFT JOIN tbl_room rum
				ON sched.room_id = rum.id
				WHERE sched.term_id = '".CURRENT_TERM_ID."'
				AND sched.subject_id = " . $id; 
		$result = mysql_query($sql);
		?>

<script type="text/javascript">
$(function(){
	$('.selector').click(function(){
		//var id = $(this).attr("val");		
		var valTxt = $(this).attr("returnTxt");
		var valName = $(this).attr("returnName");
		var valSubjId = $(this).attr("returnSubjId");
		var valId = $(this).attr("returnId");
		var ObjId = $(this).attr("returnObjId");
		var valFrom = $(this).attr("returnFrom");
		var valTo = $(this).attr("returnTo");
		var valDay = $(this).attr("returnDay");
		var valDays = valDay.split(" ");
		var valTos = valTo.split(":").join("");
		var valFroms = valFrom.split(':').join('');
		var subject_unit = <?=$subjectUnit?>;
		var total_select_units = $('#enrolled_unit').val();
		var total_max_units = $('#max_unit').val();
		var inc_units = parseInt(total_select_units) + parseInt(subject_unit);		

		if(checkConflictSched(valTo,valFrom,valDay)){
			/*if(inc_units > total_max_units){
				alert('You are only allowed to enroll maximum of ' + total_max_units + ' units.');
			}else{	*/

			if($('#schedule_id_'+ObjId).val() == ''){
				$('#span_select_units').html(inc_units);
				$('#enrolled_unit').val(inc_units);
			}
			$('#schedule_id_'+ObjId).attr("value", valId);
			$('#schedule_id_display').attr("value", valTxt);
			$('#code_'+ObjId).html(valTxt);
			$('#name_'+ObjId).html(valName);
			$('#elective_of_'+ObjId).val($(this).attr("returnEl"));
			$('#subject_id_'+ObjId).val(valSubjId);
			$('#from_'+ObjId).html(valFrom);
			$('#to_'+ObjId).html(valTo);
			$('#day_'+ObjId).html(valDay);
		//}
			$('#dialog').dialog('close');
		}else{
			alert('Conflict Schedule');
			return false;
		}
	});
});
</script>

<div id="lookup_content">
	<table class="fieldsetList" style="width:800px;">      
		<tr>
			<th class="col1_70">&nbsp;</th>
			<th class="col1_50"><a href="#">Section</a></th> 
			<th class="col1_50"><a href="#">Code</a></th>  
			<th class="col1_150"><a href="#">Subject Name</a></th>  
			<th class="col1_150"><a href="#">Professor</a></th>                           
			<th class="col1_50"><a href="#">Schedule</a></th>                   
			<th class="col1_100"><a href="#">Room</a></th> 
			<th class="col1_50"><a href="#">Slot</a></th> 
		</tr>

        <?php
        $x = 1;
        while($row = mysql_fetch_array($result)){ 
			?>
        
		<tr class="<?=($x%2==0)?"":"highlight";?>">
			<td>
			<?php
			if($row["number_of_available"]>0){
				?>
				<a href="#" name="id" id="id_<?=$row['id']?>" class="selector" returnObjId="<?=$id?>"  returnId="<?=$row['id']?>" returnTxt="<?=$row['subject_code']?>" returnName="<?=$row['subject_name']?>" returnSubjId="<?=$row['subject_id']?>" returnFrom="<?=$row['time_from']?>" returnTo="<?=$row['time_to']?>" returnDay="<?=getScheduleDays($row['id'])?>" returnEL="<?=$row['elective_of']?>">Select</a>
			<?php
			}else{
				echo '&nbsp;&nbsp;&nbsp;';
			}
			?>
			</td>
			<td><?=$row["section_no"]?></td>
			<td><?=$row["subject_code"]?></td>
			<td><?=$row['elective_of']!=""?$row["subject_name"].'('.getSubjName($row['elective_of']).')':$row["subject_name"]?></td>
			<td><?=getProfessorFullName($row["employee_id"])?></td>                
			<td><?=getScheduleDays($row['id'])?></td>
			<td><?=$row["room_no"]?></td>
			<td><?=$row["number_of_available"].'/'.$row["number_of_student"]?></td>
		</tr>
		<input type="hidden" name="sched[]" id="sched_<?=$row['id']?>" value="<?=$row['id']?>">

			<?php 
			$x++;          
        }
		?>
	</table> 
</div> <!-- #lookup_content -->    

	<?php
	}
}
?>   

