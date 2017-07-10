<?php

if(!isset($_REQUEST['comp'])){
	include_once("../config.php");
	include_once("../includes/functions.php");
	include_once("../includes/common.php");
}

if(USER_IS_LOGGED != '1'){
	echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main
}else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])){
	header('Location: ../forbid.html');
}

$page_title = 'Manage Schedule';
$pagination = 'Schedule  > Manage Schedule';
$view = $view==''?'list':$view; // initialize action
$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];
$section_no			= $_REQUEST['section_no'];
$subject_id			= $_REQUEST['subject_id'];
$room_id			= $_REQUEST['room_id'];
$employee_id		= $_REQUEST['employee_id'];
$term_id			= $_REQUEST['term_id'];
$number_of_student	= $_REQUEST['number_of_student'];
$days				= $_REQUEST['days'];
$start				= $_REQUEST['start'];
$end				= $_REQUEST['end'];
$el_subject_id		= $_REQUEST['el_subject_id'];

$sScheduledDay = "";
$sScheduledStartTime = "";
$sScheduledEndTime = "";

$remarks = $_REQUEST['remarks'];

if(count($days)>0){
	$cnt = 0;
	foreach($days as $day){
		if(in_array('monday',$days)){
			$monday = 'Y';
			$monday_time_from	= $start[$cnt];
			$monday_end_from	= $end[$cnt];
			$sScheduledDay = 'monday';
			$sScheduledStartTime = $start[$cnt];
			$sScheduledEndTime = $end[$cnt];
		}else{
			$monday = 'N';
			$monday_time_from	= 0;
			$monday_end_from	= 0;
		}	

		if(in_array('tuesday',$days)){
			$tuesday = 'Y';
			$tuesday_time_from	= $start[$cnt];
			$tuesday_end_from	= $end[$cnt];
			$sScheduledDay = 'tuesday';
			$sScheduledStartTime = $start[$cnt];
			$sScheduledEndTime = $end[$cnt];
		}else{
			$tuesday = 'N';
			$tuesday_time_from	= 0;
			$tuesday_end_from	= 0;
		}
  
		if(in_array('wednesday',$days)){
			$wednesday = 'Y';
			$wednesday_time_from = $start[$cnt];
			$wednesday_end_from	= $end[$cnt];
			$sScheduledDay = 'wednesday';
			$sScheduledStartTime = $start[$cnt];
			$sScheduledEndTime = $end[$cnt];
		}else{
			$wednesday = 'N';
			$wednesday_time_from	= 0;
			$wednesday_end_from	= 0;
		}
  
		if(in_array('thursday',$days)){
			$thursday = 'Y';
			$thursday_time_from	= $start[$cnt];
			$thursday_end_from	= $end[$cnt];
			$sScheduledDay = 'thursday';
			$sScheduledStartTime = $start[$cnt];
			$sScheduledEndTime = $end[$cnt];
		}else{
			$thursday = 'N';
			$thursday_time_from	= 0;
			$thursday_end_from	= 0;
		}

		if(in_array('friday',$days)){
			$friday = 'Y';
			$friday_time_from	= $start[$cnt];
			$friday_end_from	= $end[$cnt];
			$sScheduledDay = 'friday';
			$sScheduledStartTime = $start[$cnt];
			$sScheduledEndTime = $end[$cnt];
		}else{
			$friday = 'N';
			$friday_time_from	= 0;
			$friday_end_from	= 0;
		}
 
		if(in_array('saturday',$days)){
			$saturday = 'Y';
			$saturday_time_from	= $start[$cnt];
			$saturday_end_from	= $end[$cnt];
			$sScheduledDay = 'saturday';
			$sScheduledStartTime = $start[$cnt];
			$sScheduledEndTime = $end[$cnt];
		}else{
			$saturday = 'N';
			$saturday_time_from	= 0;
			$saturday_end_from	= 0;
		}

		if(in_array('sunday',$days)){
			$sunday = 'Y';
			$sunday_time_from	= $start[$cnt];
			$sunday_end_from	= $end[$cnt];
			$sScheduledDay = 'sunday';
			$sScheduledStartTime = $start[$cnt];
			$sScheduledEndTime = $end[$cnt];
		}else{
			$sunday = 'N';
			$sunday_time_from	= 0;
			$sunday_end_from	= 0;
		}
		
		if(in_array('(TBA)',$days)){
			$tbaTime = 'Y';
			$tbaTime_start = $start[$cnt];
			$tbaTime_end = $end[$cnt];
			$sScheduledDay = "(TBA)";
			$sScheduledStartTime = $start[$cnt];
			$sScheduledEndTime = $end[$cnt];
		}else{
			$tbaTime = 'N';
			$tbaTime_start = 0;
			$tbaTime_end = 0;
		}
  
		$cnt++;
	}
}

$publish = $_REQUEST['publish'];
$sy_filter = $_REQUEST['sy_filter'];
$filter_field = $_REQUEST['filter_field'];
$filter_order = $_REQUEST['filter_order'];
$page = $_REQUEST['page'];

if($_SESSION[CORE_U_CODE]['current_comp'] != $comp || $_SESSION[CORE_U_CODE]['pageNum'] != $page || $_SESSION[CORE_U_CODE]['fieldName'] != $filter_field || $_SESSION[CORE_U_CODE]['orderBy'] != $filter_order || $_SESSION[CORE_U_CODE]['sy_filter'] != $sy_filter){
	if($page != ''){
		$_SESSION[CORE_U_CODE]['pageNum'] = isset($page)&&$page!='' ? $page : '1';
	}

	if($filter_field != '' || $filter_order != ''){
		$_SESSION[CORE_U_CODE]['fieldName'] = $filter_field;
		$_SESSION[CORE_U_CODE]['orderBy'] = $filter_order;
	}

	if($sy_filter != ''){
		$_SESSION[CORE_U_CODE]['sy_filter'] = $sy_filter;
	}

	$_SESSION[CORE_U_CODE]['current_comp'] = $comp;
}

if($action == 'save'){
	if($term_id==''){
		$err_msg = "Please enter School Year Term.";
	
	}else if($section_no==''){
		$err_msg = "Please enter Section Number.";
	
	}else if($subject_id==''){
		$err_msg = "Please enter Subject.";
	
	}else if($room_id==''){
		$err_msg = "Please enter Classroom.";
	
	}else if($employee_id==''){
		$err_msg = "Please Professor";
	}
	
CHECK_ROOM_AVAILABILITY_ONNEW:
	$occupiedScheduleDetail = getScheduleDetailFromRoomOccupied_2_1($term_id,$room_id,$sScheduledDay,$sScheduledStartTime,$sScheduledEndTime,"");
	$occupiedRoomDetail = getRoomDetail_2_1($occupiedScheduleDetail['room_id']);
	if($err_msg==""){
		if($occupiedScheduleDetail['id']!=0){
			if($occupiedRoomDetail['room_no']!="(TBA)"){
				if(hasCommonSubject_2_1($subject_id)==true && hasCommonSubject_2_1($occupiedScheduleDetail['subject_id'])==true){
					goto CHECK_PROFESSOR_AVAILABILITY_ONNEW;
				}else{
					if(isElectiveSubject_2_1($subject_id)==true || isElectiveSubject_2_1($occupiedScheduleDetail['subject_id'])==true){
						goto CHECK_PROFESSOR_AVAILABILITY_ONNEW;
					}else{
						$err_msg = "Room is already occupied on ".$sScheduledDay." ".$sScheduledStartTime." to ".$sScheduledEndTime.".";
					}
				}
			}else{
				goto CHECK_PROFESSOR_AVAILABILITY_ONNEW;
			}
		}else{
			goto CHECK_PROFESSOR_AVAILABILITY_ONNEW;
		}
	}

CHECK_PROFESSOR_AVAILABILITY_ONNEW:
	if($err_msg==""){
		$professorDetail = getEmployeeDetail_2_1($employee_id);
		if($professorDetail['lastname']!="(TBA)"){
			if(isProfAvailable_2_1($term_id,$employee_id,$sScheduledDay,$sScheduledStartTime,$sScheduledEndTime,"")==true){
			goto SAVE_NEW_SCHEDULE;
			}else{
				if(hasCommonSubject_2_1($subject_id)==true && hasCommonSubject_2_1($occupiedScheduleDetail['subject_id'])==true){
					goto SAVE_NEW_SCHEDULE;
				}else{
					if(isElectiveSubject_2_1($subject_id)==true || isElectiveSubject_2_1($occupiedScheduleDetail['subject_id'])==true){
						goto SAVE_NEW_SCHEDULE;
					}else{
						$err_msg = "Professor is not available on ".$sScheduledDay." ".$sScheduledStartTime." to ".$sScheduledEndTime.".";
					}
				}
			}
		}else{
			goto SAVE_NEW_SCHEDULE;
		}
	}

SAVE_NEW_SCHEDULE:
	if($err_msg==""){
		$sql = "INSERT INTO tbl_schedule (
					section_no, 
					subject_id,
					room_id, 
					employee_id,
					term_id,
					elective_of,
					number_of_student,
					number_of_available,
					monday, monday_time_from, monday_time_to,
					tuesday, tuesday_time_from, tuesday_time_to,
					wednesday, wednesday_time_from, wednesday_time_to,
					thursday, thursday_time_from, thursday_time_to,
					friday, friday_time_from, friday_time_to,
					saturday, saturday_time_from, saturday_time_to,
					sunday, sunday_time_from, sunday_time_to,
					date_created, 
					created_by,
					date_modified,
					modified_by,
					remarks
				) VALUES (
					".GetSQLValueString($section_no,"text").",  
					".GetSQLValueString($subject_id,"int").", 
					".GetSQLValueString($room_id,"int").",
					".GetSQLValueString($employee_id,"int").", 
					".GetSQLValueString($term_id,"int").",
					".GetSQLValueString($el_subject_id,"int").",
					".GetSQLValueString($number_of_student,"int").", 
					".GetSQLValueString($number_of_student,"int").", 
					".GetSQLValueString($monday,"text").",".GetSQLValueString($monday_time_from,"text").",".GetSQLValueString($monday_end_from,"text").",
					".GetSQLValueString($tuesday,"text").",".GetSQLValueString($tuesday_time_from,"text").",".GetSQLValueString($tuesday_end_from,"text").",
					".GetSQLValueString($wednesday,"text").",".GetSQLValueString($wednesday_time_from,"text").",".GetSQLValueString($wednesday_end_from,"text").",
					".GetSQLValueString($thursday,"text").",".GetSQLValueString($thursday_time_from,"text").",".GetSQLValueString($thursday_end_from,"text").", 
					".GetSQLValueString($friday,"text").",".GetSQLValueString($friday_time_from,"text").",".GetSQLValueString($friday_end_from,"text").",
					".GetSQLValueString($saturday,"text").",".GetSQLValueString($saturday_time_from,"text").",".GetSQLValueString($saturday_end_from,"text").",
					".GetSQLValueString($sunday,"text").",".GetSQLValueString($sunday_time_from,"text").",".GetSQLValueString($sunday_end_from,"text").", 	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID.",
					".GetSQLValueString($remarks,"text")."
				)";

		if(mysql_query ($sql)){
			echo '<script language="javascript">alert("Successfully Added!");//window.location =\'index.php?comp=com_schedule\';</script>';
		}
	}
	
}else if($action == 'update'){		
	if($term_id==''){
		$err_msg = "Please enter School Year Term.";
	
	}else if($section_no==''){
		$err_msg = "Please enter Section Number.";
	
	}else if($subject_id==''){
		$err_msg = "Please enter Subject.";
	
	}else if($room_id==''){
		$err_msg = "Please enter Classroom.";
	
	}else if($employee_id==''){
		$err_msg = "Please Professor";
	
	}else if(isSubjectChanged_2_1($id,$subject_id)){
		if(isScheduleHasStudents_2_1($term_id,$id)){
			$err_msg = "Cannot Edit Subject. Currently there are student/s associated.";
		}else{
			goto CHECK_ROOM_AVAILABILITY_ONEDIT;
		}
	}

CHECK_ROOM_AVAILABILITY_ONEDIT:		
	$occupiedScheduleDetail = getScheduleDetailFromRoomOccupied_2_1($term_id,$room_id,$sScheduledDay,$sScheduledStartTime,$sScheduledEndTime,$id);
	$occupiedRoomDetail = getRoomDetail_2_1($occupiedScheduleDetail['room_id']);
	if($err_msg==""){
		if($occupiedScheduleDetail['id']!=0){
			if($occupiedRoomDetail['room_no']!="(TBA)"){
				if(hasCommonSubject_2_1($subject_id)==true && hasCommonSubject_2_1($occupiedScheduleDetail['subject_id'])==true){
					goto CHECK_PROFESSOR_AVAILABILITY_ONEDIT;
				}else{
					if(isElectiveSubject_2_1($subject_id)==true || isElectiveSubject_2_1($occupiedScheduleDetail['subject_id'])==true){
						goto CHECK_PROFESSOR_AVAILABILITY_ONEDIT;
					}else{
						$err_msg = "Room is already occupied on ".$sScheduledDay." ".$sScheduledStartTime." to ".$sScheduledEndTime.".";
					}
				}
			}else{
				goto CHECK_PROFESSOR_AVAILABILITY_ONEDIT;
			}
		}else{
			goto CHECK_PROFESSOR_AVAILABILITY_ONEDIT;
		}
	}

CHECK_PROFESSOR_AVAILABILITY_ONEDIT:
	if($err_msg==""){
		$professorDetail = getEmployeeDetail_2_1($employee_id);
		if($professorDetail['lastname']!="(TBA)"){
			if(isProfAvailable_2_1($term_id,$employee_id,$sScheduledDay,$sScheduledStartTime,$sScheduledEndTime,$id)==true){
				goto UPDATE_SCHEDULE;
			}else{
				if(hasCommonSubject_2_1($subject_id)==true && hasCommonSubject_2_1($occupiedScheduleDetail['subject_id'])==true){
					goto UPDATE_SCHEDULE;
				}else{
					if(isElectiveSubject_2_1($subject_id)==true || isElectiveSubject_2_1($occupiedScheduleDetail['subject_id'])==true){
						goto UPDATE_SCHEDULE;
					}else{
						$err_msg = "Professor is not available on ".$sScheduledDay." ".$sScheduledStartTime." to ".$sScheduledEndTime.".";
					}
				}
			}
		}else{
			goto UPDATE_SCHEDULE;
		}
	}

UPDATE_SCHEDULE:
	if($err_msg==""){
		if(storedModifiedLogs(tbl_schedule, $id)){
			$sql = "UPDATE tbl_schedule SET 
						section_no =".GetSQLValueString($section_no,"text").",
						subject_id =".GetSQLValueString($subject_id,"int").",
						room_id =".GetSQLValueString($room_id,"int").",
						employee_id =".GetSQLValueString($employee_id,"int").",
						term_id =".GetSQLValueString($term_id,"int").",
						elective_of =".GetSQLValueString($el_subject_id,"int").",
						number_of_student =".GetSQLValueString($number_of_student,"int").",
						number_of_available =".GetSQLValueString($number_of_student,"int").",
						monday =".GetSQLValueString($monday,"text").",
						monday_time_from =".GetSQLValueString($monday_time_from,"text").",
						monday_time_to =".GetSQLValueString($monday_end_from,"text").",
						tuesday =".GetSQLValueString($tuesday,"text").",
						tuesday_time_from =".GetSQLValueString($tuesday_time_from,"text").",
						tuesday_time_to =".GetSQLValueString($tuesday_end_from,"text").",
						wednesday =".GetSQLValueString($wednesday,"text").",
						wednesday_time_from =".GetSQLValueString($wednesday_time_from,"text").",
						wednesday_time_to =".GetSQLValueString($wednesday_end_from,"text").",
						thursday =".GetSQLValueString($thursday,"text").",
						thursday_time_from =".GetSQLValueString($thursday_time_from,"text").",
						thursday_time_to =".GetSQLValueString($thursday_end_from,"text").",
						friday =".GetSQLValueString($friday,"text").",
						friday_time_from =".GetSQLValueString($friday_time_from,"text").",
						friday_time_to =".GetSQLValueString($friday_end_from,"text").",
						saturday =".GetSQLValueString($saturday,"text").",
						saturday_time_from =".GetSQLValueString($saturday_time_from,"text").",
						saturday_time_to =".GetSQLValueString($saturday_end_from,"text").",
						sunday =".GetSQLValueString($sunday,"text").",
						sunday_time_from =".GetSQLValueString($sunday_time_from,"text").",
						sunday_time_to =".GetSQLValueString($sunday_end_from,"text").",
						date_modified = ".time() .",
						modified_by = ".USER_ID.",
						remarks = ".GetSQLValueString($remarks,"text")." 
					WHERE id = " .$id;

			if(mysql_query ($sql)){
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_schedule\';</script>';
			}
		}
	}
}else if($action == 'delete'){
	$selected_item = explode(',',$temp);

	foreach($selected_item as $item){
		if ($item != ''){
			$sql_reserve = "SELECT * FROM tbl_student_reserve_subject WHERE schedule_id=" .$item;
			$qry_reserve = mysql_query($sql_reserve);
			$ctr_reserve = mysql_num_rows($qry_reserve);
			$sql_st_sched = "SELECT * FROM tbl_student_schedule WHERE schedule_id=" .$item;
			$qry_st_sched = mysql_query($sql_st_sched);
			$ctr_st_sched = mysql_num_rows($qry_st_sched);
			
			if($ctr_reserve > 0){
				$err_msg = 'Cannot delete '.getSectionNo($item).'. Currently there are student associated.';
			}else if($ctr_st_sched > 0){
				$err_msg = 'Cannot delete '.getSectionNo($item).'. Currently there are student associated.';
			}else{
				$sql = "DELETE FROM tbl_schedule WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}

	if(count($arr_str) > 0){
		echo '<script language="javascript">alert("'.implode("\n",$arr_str).'");</script>';
	}
}else if($action == 'publish'){
	$selected_item = explode(',',$temp);

	foreach($selected_item as $item){
		storedModifiedLogs(tbl_schedule, $id);
		$sql = "UPDATE tbl_schedule SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
	}

}else if($action == 'unpublished'){
	$selected_item = explode(',',$temp);

	foreach($selected_item as $item){
		if ($item != ''){
			$sql_reserve = "SELECT * FROM tbl_student_reserve_subject WHERE id=" .$item." AND term_id = ".CURRENT_TERM_ID;
			$qry_reserve = mysql_query($sql_reserve);
			$ctr_reserve = mysql_num_rows($qry_reserve);

			if($ctr_reserve > 0){
				$err_msg = 'Cannot unpublish section '.getSectionNo($item).'. Currently there are student associated.';
			}else if(!checkScheduleEditable($item)){
				$err_msg = 'Cannot unpublish section '.getSectionNo($item).'. Currently there are student associated.';
			}else{
				storedModifiedLogs(tbl_schedule, $id);
				$sql = "UPDATE tbl_schedule SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}
}


LOAD_INTERFACE:

if($view == 'edit'){
	$sql = "SELECT * FROM tbl_schedule WHERE id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	$section_no			= $row['section_no'] != $section_no ? $row['section_no'] : $section_no;
	$subject_id			= $row['subject_id'] != $subject_id ? $row['subject_id'] : $subject_id;
	$room_id			= $row['room_id'] != $room_id ? $row['room_id'] : $room_id;
	$employee_id		= $row['employee_id'] != $employee_id ? $row['employee_id'] : $employee_id;
	$term_id			= $row['term_id'] != $term_id ? $row['term_id'] : $term_id;
	$el_subject_id		= $row['elective_of'] != $el_subject_id ? $row['elective_of'] : $el_subject_id;
	$number_of_student	= $row['number_of_student'] != $number_of_student ? $row['number_of_student'] : $number_of_student;
	$publish			= $row['publish'] != $publish ? $row['publish'] : $publish;												
	$remarks			= $row['remarks'] != $remarks ? $row['remarks'] : $remarks;
	$day = array();
	$start = array();
	$end = array();
	
	$isDayTBA = true;

	if($row['monday']=='Y'){
		$day[] = 'monday';
		$start[]	= $row['monday_time_from'];
		$end[]	= $row['monday_time_to'];
		$isDayTBA = false;
  	}

  	if($row['tuesday']=='Y'){
		$day[] = 'tuesday';
		$start[]	= $row['tuesday_time_from'];
		$end[]	= $row['tuesday_time_to'];
		$isDayTBA = false;
  	}
	
	if($row['wednesday']=='Y'){
		$day[] = 'wednesday';
		$start[]	= $row['wednesday_time_from'];
		$end[]	= $row['wednesday_time_to'];
		$isDayTBA = false;
  	}
	
	if($row['thursday']=='Y'){
		$day[] = 'thursday';
		$start[]	= $row['thursday_time_from'];
		$end[]	= $row['thursday_time_to'];
		$isDayTBA = false;
  	}
	
	if($row['friday']=='Y'){
		$day[] = 'friday';
		$start[]	= $row['friday_time_from'];
		$end[]	= $row['friday_time_to'];
		$isDayTBA = false;
  	}
	
	if($row['saturday']=='Y'){
		$day[] = 'saturday';
		$start[]	= $row['saturday_time_from'];
		$end[]	= $row['saturday_time_to'];
		$isDayTBA = false;
  	}
	
	if($row['sunday']=='Y'){
		$day[] = 'sunday';
		$start[]	= $row['sunday_time_from'];
		$end[]	= $row['sunday_time_to'];
		$isDayTBA = false;
  	}

	$ctr = 0;

	if(count($day)>0){

		foreach($day as $days){
			$arr_str[] ='<tr id="row_'.$ctr.'">';
			$arr_str[] ='<td><input name="days[]" type="hidden" id="days" value="'.$day[$ctr].'" />' .$day[$ctr]. '</td>';
			$arr_str[] ='<td><input name="start['.$ctr.']" type="hidden" id="start" value="'.$start[$ctr].'" />' .$start[$ctr]. '</td>';
			$arr_str[] ='<td><input name="end['.$ctr.']" type="hidden" id="end" value="'.$end[$ctr].'" />' .$end[$ctr]. '</td>';
			$arr_str[] ='<td class="action"><a href="#" class="remove" returnId="'.$ctr.'">Remove</a></td>';             
			$arr_str[] ='</tr>';

			$ctr ++;
		}
		$sched = implode('',$arr_str);
	}else{
		if($isDayTBA==true){
			$arr_str[] ='<tr id="row_'.($ctr+1).'">';
			$arr_str[] ='<td><input name="days[]" type="hidden" id="days" value="(TBA)" />(TBA)</td>';
			$arr_str[] ='<td><input name="start['.($ctr+1).']" type="hidden" id="start" value="00:00" />00:00</td>';
			$arr_str[] ='<td><input name="end['.($ctr+1).']" type="hidden" id="end" value="00:00" />00:00</td>';
			$arr_str[] ='<td class="action"><a href="#" class="remove" returnId="'.($ctr+1).'">Remove</a></td>';             
			$arr_str[] ='</tr>';
		}
		$sched = implode('',$arr_str);
	}
}else if($view == 'add'){
	$section_no			= $_REQUEST['section_no'];
	$subject_id			= $_REQUEST['subject_id'];
	$room_id			= $_REQUEST['room_id'];
	$employee_id		= $_REQUEST['employee_id'];
	$term_id			= $_REQUEST['term_id'];
	$el_subject_id		= $_REQUEST['el_subject_id'];
	$number_of_student	= $_REQUEST['number_of_student'];
	$monday				= $_REQUEST['monday'] == 'Y'?'Y':'N';
	$tuesday			= $_REQUEST['tuesday'] == 'Y'?'Y':'N';
	$wednesday			= $_REQUEST['wednesday'] == 'Y'?'Y':'N';
	$thursday			= $_REQUEST['thursday'] == 'Y'?'Y':'N';
	$friday				= $_REQUEST['friday'] == 'Y'?'Y':'N';
	$saturday			= $_REQUEST['saturday'] == 'Y'?'Y':'N';
	$sunday				= $_REQUEST['sunday'] == 'Y'?'Y':'N';
	$time_from			= $_REQUEST['time_from'];
	$time_to			= $_REQUEST['time_to'];
	$publish			= $_REQUEST['publish'];
	$start				= $_REQUEST['start'];
	$end 				= $_REQUEST['end'];
	$day 				= $_REQUEST['days'];

	$ctr = 0;

	if(count($_REQUEST['days'])>0){
		foreach($_REQUEST['days'] as $days){
			$arr_str[] ='<tr id="row_'.$ctr.'">';
			$arr_str[] ='<td><input name="days[]" type="hidden" id="days" value="'.$day[$ctr].'" />' .$day[$ctr]. '</td>';
			$arr_str[] ='<td><input name="start['.$ctr.']" type="hidden" id="start" value="'.$start[$ctr].'" />' .$start[$ctr]. '</td>';
			$arr_str[] ='<td><input name="end['.$ctr.']" type="hidden" id="end" value="'.$end[$ctr].'" />' .$end[$ctr]. '</td>';
			$arr_str[] ='<td class="action"><a href="#" class="remove" returnId="'.$ctr.'">Remove</a></td>';             
			$arr_str[] ='</tr>';

			$ctr ++;
		}

		$sched = implode('',$arr_str);
	}
}


// component block, will be included in the template page
$content_template = 'components/block/blk_com_schedule.php';

?>