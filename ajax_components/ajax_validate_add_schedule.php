<?php
include_once("../config.php");
include_once('../includes/functions.php');
include_once("../includes/common.php");	
$id = $_REQUEST['id'];
$sched_add = $_REQUEST['selected'];
$schedule_id = array();
$schedule_id[] = $sched_add;

$sql = "SELECT * FROM tbl_student_schedule 
		WHERE enrollment_status = 'A' AND student_id=".$id." AND term_id=".CURRENT_TERM_ID;
$query = mysql_query($sql);
	
while($row = mysql_fetch_array($query)){
	$schedule_id[]=$row['schedule_id'];
}
	
$conflict = 'false';

foreach($schedule_id as $needle){
	if($needle != ''){
		foreach($schedule_id as $mixed_array){
			if($needle != $mixed_array && $mixed_array!= ''){
				//echo $needle.'sss'.$mixed_array;
				if(checkScheduleForConflict($mixed_array, $needle)){
					$conflict = 'true';
					break;
				}
			}
		}
	}
		
	if($conflict == 'true'){
		break;
	}
}
echo $conflict;
?>