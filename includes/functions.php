<?php

/*Version 2.1 start -----------------------------------*/

function isElectiveSubject_2_1($subject_id){
	$electives = array(270,281,282,284,290,291,295,312,318,319,
					   320,325,326,329,343,346,358,359,367,460,
					   469,470,473,474,475,476,477,478,526,555);
	if(in_array($subject_id,$electives)){
		return true;
	}else{
		return false;
	}
}

function isProfAvailable_2_1($term_id,$employee_id,$day,$startTime,$endTime,$schedule_id=""){
	
	if($startTime=="00:00" && $endTime=="00:00"){
		return true;
	}
	
	$iRowCount = 0;
	$iNoConflict = 0;
	
	if($schedule_id==""){	
		$sql = "SELECT id, term_id, employee_id, ".$day.", ".$day."_time_from, ".$day."_time_to FROM tbl_schedule 
				WHERE
					term_id=".GetSQLValueString($term_id,"int")." AND 
					employee_id=".GetSQLValueString($employee_id,"int")." AND 
					".$day."='Y'";
	}else{
		$sql = "SELECT id, term_id, employee_id, ".$day.", ".$day."_time_from, ".$day."_time_to FROM tbl_schedule 
				WHERE 
					term_id=".GetSQLValueString($term_id,"int")." AND 
					employee_id=".GetSQLValueString($employee_id,"int")." AND 
					".$day."='Y' AND 
					id<>".GetSQLValueString($schedule_id,"int");
	}
	
	$query = mysql_query($sql);
	$iRowCount = mysql_num_rows($query);
	if($iRowCount>0){
		
		$arStartTime = explode(":",$startTime);
		$iStartTimeHr = intval($arStartTime[0]);
		$iStartTimeMin = intval($arStartTime[1]);
		
		$arEndTime = explode(":",$endTime);
		$iEndTimeHr = intval($arEndTime[0]);
		$iEndTimeMin = intval($arEndTime[1]);

		while($row = mysql_fetch_array($query)){
			$arProfStartTime = explode(":",$row[$day.'_time_from']);
			$iProfStartTimeHr = $arProfStartTime[0];
			$iProfStartTimeMin = $arProfStartTime[1];
			
			$arProfEndTime = explode(":",$row[$day.'_time_to']);
			$iProfEndTimeHr = $arProfEndTime[0];
			$iProfEndTimeMin = $arProfEndTime[1];
			
			if(($iStartTimeHr+($iStartTimeMin/60))<($iProfStartTimeHr+($iProfStartTimeMin/60)) && 
				($iEndTimeHr+($iEndTimeMin/60))<=($iProfStartTimeHr+($iProfStartTimeMin/60))){
					$iNoConflict = $iNoConflict + 1;
			}
			
			if(($iStartTimeHr+($iStartTimeMin/60))>=($iProfEndTimeHr+($iProfEndTimeMin/60)) &&
				($iEndTimeHr+($iEndTimeMin/60))>($iProfEndTimeHr+($iProfEndTimeMin/60))){
					$iNoConflict = $iNoConflict + 1;
			}
			
		}

		if($iNoConflict<$iRowCount){
			return false;
		}else{
			return true;
		}
	}else{
		return true;
	}
}

function isRoomOccupied_2_1($term_id,$room_id,$day,$startTime,$endTime,$schedule_id=""){
	
	if($startTime=="00:00" && $endTime=="00:00"){
		return 0;
	}
	
	$iRowCount = 0;
	$iNoConflict = 0;
	
	if($schedule_id==""){	
		$sql = "SELECT id, term_id, room_id, ".$day.", ".$day."_time_from, ".$day."_time_to FROM tbl_schedule
				WHERE
					term_id=".GetSQLValueString($term_id,"int")." AND 
					room_id=".GetSQLValueString($room_id,"int")." AND 
					".$day."='Y'";
	}else{
		$sql = "SELECT id, term_id, room_id, ".$day.", ".$day."_time_from, ".$day."_time_to FROM tbl_schedule 
				WHERE 
					term_id=".GetSQLValueString($term_id,"int")." AND 
					room_id=".GetSQLValueString($room_id,"int")." AND 
					".$day."='Y' AND 
					id<>".GetSQLValueString($schedule_id,"int");
	}
	
	$query = mysql_query($sql);
	$iRowCount = mysql_num_rows($query);
	if($iRowCount>0){
		
		$arStartTime = explode(":",$startTime);
		$iStartTimeHr = intval($arStartTime[0]);
		$iStartTimeMin = intval($arStartTime[1]);
		
		$arEndTime = explode(":",$endTime);
		$iEndTimeHr = intval($arEndTime[0]);
		$iEndTimeMin = intval($arEndTime[1]);

		while($row = mysql_fetch_array($query)){
			$arRoomStartTime = explode(":",$row[$day.'_time_from']);
			$iRoomStartTimeHr = $arRoomStartTime[0];
			$iRoomStartTimeMin = $arRoomStartTime[1];
			
			$arRoomEndTime = explode(":",$row[$day.'_time_to']);
			$iRoomEndTimeHr = $arRoomEndTime[0];
			$iRoomEndTimeMin = $arRoomEndTime[1];
			
			if(($iStartTimeHr+($iStartTimeMin/60))<($iRoomStartTimeHr+($iRoomStartTimeMin/60)) && 
				($iEndTimeHr+($iEndTimeMin/60))<=($iRoomStartTimeHr+($iRoomStartTimeMin/60))){
					$iNoConflict = $iNoConflict + 1;
			}
			
			if(($iStartTimeHr+($iStartTimeMin/60))>=($iRoomEndTimeHr+($iRoomEndTimeMin/60)) &&
				($iEndTimeHr+($iEndTimeMin/60))>($iRoomEndTimeHr+($iRoomEndTimeMin/60))){
					$iNoConflict = $iNoConflict + 1;
			}
			
		}
		
		if($iNoConflict<$iRowCount){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}

function isScheduleHasStudents_2_1($term_id,$schedule_id){
	$sql = "SELECT id, student_id, term_id, schedule_id FROM tbl_student_schedule 
			WHERE
				term_id=".GetSQLValueString($term_id,"int")." AND 
				schedule_id=".GetSQLValueString($schedule_id,"int");
	
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0){
		return true;
	}else{
		return false;
	}
}

function isSectionNumberExists_2_1($section_no,$term_id,$schedule_id=""){
	if($schedule_id==""){
		$sql = "SELECT id, term_id, section_no FROM tbl_schedule 
				WHERE 
					section_no = ".GetSQLValueString($section_no,"text")." AND 
					term_id = ".GetSQLValueString($term_id,"int");
	}else{
		$sql = "SELECT id, term_id, schedule_id, section_no FROM tbl_schedule
				WHERE
					section_no = ".GetSQLValueString($section_no,"text")." AND 
					term_id = ".GetSQLValueString($term_id,"int")." AND 
					id<>".GetSQLValueString($schedule_id,"int");
	}

	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0){
		return true;
	}else{
		return false;
	}
}

function isSubjectChanged_2_1($schedule_id,$newSubject_id){
	$sql = "SELECT id, subject_id FROM tbl_schedule 
			WHERE 
				id=".GetSQLValueString($schedule_id);
	
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0){
		while($row = mysql_fetch_array($query)){
			if($row['subject_id']!=$newSubject_id){
				return true;
			}else{
				return false;
			}
		}
	}else{
		return false;
	}
}

function getRoomDetail_2_1($room_id){
	$roomDetail['id'] = 0;
	$roomDetail['building_id'] = 0;
	$roomDetail['room_no'] = '';
	$roomDetail['room_type'] = '';
	$roomDetail['has_custom_availability'] = '';
	$roomDetail['publish'] = '';
	$roomDetail['date_created'] = '';
	$roomDetail['created_by'] = '';
	$roomDetail['date_modified'] = '';
	$roomDetail['modified_by'] = '';
	$roomDetail['modified_logs'] = '';
	
	$sql = "SELECT id, building_id, 
				room_no, room_type, has_custom_availability, publish, 
				date_created, created_by, date_modified, modified_by, modified_logs 
			FROM tbl_room 
			WHERE id=".GetSQLValueString($room_id)."";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0){
		$row = mysql_fetch_array($query);
		
		$roomDetail['id'] = $row['id'];
		$roomDetail['building_id'] = $row['building_id'];
		$roomDetail['room_no'] = $row['room_no'];
		$roomDetail['room_type'] = $row['room_type'];
		$roomDetail['has_custom_availability'] = $row['has_custom_availability'];
		$roomDetail['publish'] = $row['publish'];
		$roomDetail['date_created'] = $row['date_created'];
		$roomDetail['created_by'] = $row['created_by'];
		$roomDetail['date_modified'] = $row['date_modified'];
		$roomDetail['modified_by'] = $row['modified_by'];
		$roomDetail['modified_logs'] = $row['modified_logs'];
		
		return $roomDetail;
	}else{
		return $roomDetail;
	}
}

function getScheduleDetail_2_1($schedule_id){
	$scheduleDetail['id'] = 0;
	$scheduleDetail['section_no'] = '';
	$scheduleDetail['subject_id'] = '';
	$scheduleDetail['room_id'] = '';
	$scheduleDetail['employee_id'] = '';
	$scheduleDetail['term_id'] = '';
	$scheduleDetail['elective_of'] = '';
	$scheduleDetail['number_of_student'] = '';
	$scheduleDetail['number_of_reserve'] = '';
	$scheduleDetail['number_of_available'] = '';
	$scheduleDetail['monday'] = '';
	$scheduleDetail['monday_time_from'] = '';
	$scheduleDetail['monday_time_to'] = '';
	$scheduleDetail['tuesday'] = '';
	$scheduleDetail['tuesday_time_from'] = '';
	$scheduleDetail['tuesday_time_to'] = '';
	$scheduleDetail['wednesday'] = '';
	$scheduleDetail['wednesday_time_from'] = '';
	$scheduleDetail['wednesday_time_to'] = '';
	$scheduleDetail['thursday'] = '';
	$scheduleDetail['thursday_time_from'] = '';
	$scheduleDetail['friday'] = '';
	$scheduleDetail['friday_time_from'] = '';
	$scheduleDetail['friday_time_to'] = '';
	$scheduleDetail['saturday'] = '';
	$scheduleDetail['saturday_time_from'] = '';
	$scheduleDetail['saturday_time_to'] = '';
	$scheduleDetail['sunday'] = '';
	$scheduleDetail['sunday_time_from'] = '';
	$scheduleDetail['sunday_time_to'] = '';
	$scheduleDetail['publish'] = '';
	$scheduleDetail['date_created'] = '';
	$scheduleDetail['created_by'] = '';
	$scheduleDetail['date_modified'] = '';
	$scheduleDetail['modified_by'] = '';
	$scheduleDetail['modified_logs'] = '';
	
	$sql = "SELECT id, section_no, subject_id, room_id, employee_id, term_id, 
				elective_of, number_of_student, number_of_reserved, number_of_available, 
				monday, monday_time_from, monday_time_to, 
				tuesday, tuesday_time_from, tuesday_time_to, 
				wednesday, wednesday_time_from, wednesday_time_to, 
				thursday, thursday_time_from, thursday_time_to, 
				friday, friday_time_from, friday_time_to, 
				saturday, saturday_time_from, saturday_time_to, 
				sunday, sunday_time_from, sunday_time_to, 
				publish, 
				date_created, created_by, date_modified, modified_by, modified_logs 
			FROM tbl_schedule 
			WHERE id=".GetSQLValueString($schedule_id)."";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0){
		$row = mysql_fetch_array($query);
		
		$scheduleDetail['id'] = $row['id'];
		$scheduleDetail['section_no'] = $row['section_no'];
		$scheduleDetail['subject_id'] = $row['subject_id'];
		$scheduleDetail['room_id'] = $row['room_id'];
		$scheduleDetail['employee_id'] = $row['employee_id'];
		$scheduleDetail['term_id'] = $row['term_id'];
		$scheduleDetail['elective_of'] = $row['elective_of'];
		$scheduleDetail['number_of_student'] = $row['number_of_student'];
		$scheduleDetail['number_of_reserve'] = $row['number_of_reserve'];
		$scheduleDetail['number_of_available'] = $row['number_of_available'];
		$scheduleDetail['monday'] = $row['monday'];
		$scheduleDetail['monday_time_from'] = $row['monday_time_from'];
		$scheduleDetail['monday_time_to'] = $row['monday_time_to'];
		$scheduleDetail['tuesday'] = $row['tuesday'];
		$scheduleDetail['tuesday_time_from'] = $row['tuesday_time_from'];
		$scheduleDetail['tuesday_time_to'] = $row['tuesday_time_to'];
		$scheduleDetail['wednesday'] = $row['wednesday'];
		$scheduleDetail['wednesday_time_from'] = $row['wednesday_time_from'];
		$scheduleDetail['wednesday_time_to'] = $row['wednesday_time_to'];
		$scheduleDetail['thursday'] = $row['thursday'];
		$scheduleDetail['thursday_time_from'] = $row['thursday_time_from'];
		$scheduleDetail['friday'] = $row['friday'];
		$scheduleDetail['friday_time_from'] = $row['friday_time_from'];
		$scheduleDetail['friday_time_to'] = $row['friday_time_to'];
		$scheduleDetail['saturday'] = $row['saturday'];
		$scheduleDetail['saturday_time_from'] = $row['saturday_time_from'];
		$scheduleDetail['saturday_time_to'] = $row['saturday_time_to'];
		$scheduleDetail['sunday'] = $row['sunday'];
		$scheduleDetail['sunday_time_from'] = $row['sunday_time_from'];
		$scheduleDetail['sunday_time_to'] = $row['sunday_time_to'];
		$scheduleDetail['publish'] = $row['publish'];
		$scheduleDetail['date_created'] = $row['date_created'];
		$scheduleDetail['created_by'] = $row['created_by'];
		$scheduleDetail['date_modified'] = $row['date_modified'];
		$scheduleDetail['modified_by'] = $row['modified_by'];
		$scheduleDetail['modified_logs'] = $row['modified_logs'];
		
		return $scheduleDetail;
	}else{
		return $scheduleDetail;
	}
}

function getScheduleDetailFromRoomOccupied_2_1($term_id,$room_id,$day,$startTime,$endTime,$schedule_id=""){
	
	if($startTime=="00:00" && $endTime=="00:00"){
		return getScheduleDetail_2_1(0);
	}
	
	$iRowCount = 0;
	$iNoConflict = 0;
	
	if($schedule_id==""){	
		$sql = "SELECT id, section_no, subject_id, room_id, employee_id, term_id, elective_of, 
					number_of_student, number_of_reserved, number_of_available, 
					".$day.", ".$day."_time_from, ".$day."_time_to FROM tbl_schedule
				WHERE
					term_id=".GetSQLValueString($term_id,"int")." AND 
					room_id=".GetSQLValueString($room_id,"int")." AND 
					".$day."='Y'";
	}else{
		$sql = "SELECT id, section_no, subject_id, room_id, employee_id, term_id, elective_of, 
					number_of_student, number_of_reserved, number_of_available, 
					".$day.", ".$day."_time_from, ".$day."_time_to FROM tbl_schedule 
				WHERE 
					term_id=".GetSQLValueString($term_id,"int")." AND 
					room_id=".GetSQLValueString($room_id,"int")." AND 
					".$day."='Y' AND 
					id<>".GetSQLValueString($schedule_id,"int");
	}
	
	$query = mysql_query($sql);
	$iRowCount = mysql_num_rows($query);
	if($iRowCount>0){
		
		$arStartTime = explode(":",$startTime);
		$iStartTimeHr = intval($arStartTime[0]);
		$iStartTimeMin = intval($arStartTime[1]);
		
		$arEndTime = explode(":",$endTime);
		$iEndTimeHr = intval($arEndTime[0]);
		$iEndTimeMin = intval($arEndTime[1]);

		while($row = mysql_fetch_array($query)){
			$arRoomStartTime = explode(":",$row[$day.'_time_from']);
			$iRoomStartTimeHr = $arRoomStartTime[0];
			$iRoomStartTimeMin = $arRoomStartTime[1];
			
			$arRoomEndTime = explode(":",$row[$day.'_time_to']);
			$iRoomEndTimeHr = $arRoomEndTime[0];
			$iRoomEndTimeMin = $arRoomEndTime[1];
			
			if(($iStartTimeHr+($iStartTimeMin/60))<($iRoomStartTimeHr+($iRoomStartTimeMin/60)) && 
				($iEndTimeHr+($iEndTimeMin/60))<=($iRoomStartTimeHr+($iRoomStartTimeMin/60))){
				
				$iNoConflict = $iNoConflict + 1;
				
			}else{
				if(($iStartTimeHr+($iStartTimeMin/60))>=($iRoomEndTimeHr+($iRoomEndTimeMin/60)) &&
					($iEndTimeHr+($iEndTimeMin/60))>($iRoomEndTimeHr+($iRoomEndTimeMin/60))){
					$iNoConflict = $iNoConflict + 1;
				}else{
					return getScheduleDetail_2_1($row['id']);
				}
			}
		}
		return getScheduleDetail_2_1(0);
	}else{
		return getScheduleDetail_2_1(0);
	}
}

function getSubjectDetail_2_1($subject_id){
	$subjectDetail['id'] = 0;
	$subjectDetail['subject_code'] = '';
	$subjectDetail['subject_name'] = '';
	$subjectDetail['department_id'] = 0;
	$subjectDetail['publish'] = '';
	$subjectDetail['subject_type'] = '';
	$subjectDetail['date_created'] = '';
	$subjectDetail['created_by'] = '';
	$subjectDetail['date_modified'] = '';
	$subjectDetail['modified_by'] = '';
	$subjectDetail['modified_logs'] = '';
	$sql = "SELECT id, subject_code, subject_name, department_id, 
				publish, subject_type, 
				date_created, created_by, date_modified, modified_by, modified_logs 
			FROM tbl_subject 
			WHERE id=".GetSQLValueString($subject_id)."";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0){
		$row = mysql_fetch_array($query);
		$subjectDetail['id'] = $row['id'];
		$subjectDetail['subject_code'] = $row['subject_code'];
		$subjectDetail['subject_name'] = $row['subject_name'];
		$subjectDetail['department_id'] = $row['department_id'];
		$subjectDetail['publish'] = $row['publish'];
		$subjectDetail['subject_type'] = $row['subject_type'];
		$subjectDetail['date_created'] = $row['date_created'];
		$subjectDetail['created_by'] = $row['created_by'];
		$subjectDetail['date_modified'] = $row['date_modified'];
		$subjectDetail['modified_by'] = $row['modified_by'];
		$subjectDetail['modified_logs'] = $row['modified_logs'];
		return $subjectDetail;
	}else{
		return $subjectDetail;
	}
}

function getEmployeeDetail_2_1($employee_id){
	$employeeDetail['id'] = 0;
	$employeeDetail['user_id'] = 0;
	$employeeDetail['employee_type'] = 0;
	$employeeDetail['department_id'] = 0;
	$employeeDetail['emp_id_number'] = 0;
	$employeeDetail['firstname'] = '';
	$employeeDetail['middlename'] = '';
	$employeeDetail['lastname'] = '';
	$employeeDetail['suffix'] = '';
	$employeeDetail['email'] = '';
	$employeeDetail['birth_date'] = '';
	$employeeDetail['birth_place'] = '';
	$employeeDetail['gender'] = '';
	$employeeDetail['citizenship'] = '';
	$employeeDetail['civil_status'] = '';
	$employeeDetail['religion'] = '';
	$employeeDetail['present_address'] = '';
	$employeeDetail['present_address_zip'] = '';
	$employeeDetail['permanent_address'] = '';
	$employeeDetail['permanent_address_zip'] = '';
	$employeeDetail['tel_number'] = '';
	$employeeDetail['mobile_number'] = '';
	$employeeDetail['ice_fullname'] = '';
	$employeeDetail['ice_address'] = '';
	$employeeDetail['ice_tel_number'] = '';
	$employeeDetail['has_custom_availability'] = '';
	$employeeDetail['suspended_flg'] = '';
	$employeeDetail['admin_flg'] = '';
	$employeeDetail['circ_flg'] = '';
	$employeeDetail['circ_mbr_flg'] = '';
	$employeeDetail['catalog_flg'] = '';
	$employeeDetail['reports_flg'] = '';
	$employeeDetail['date_created'] = '';
	$employeeDetail['created_by'] = '';
	$employeeDetail['date_modified'] = '';
	$employeeDetail['modified_by'] = '';
	$employeeDetail['modified_logs'] = '';
	
	$sql = "SELECT id, user_id, employee_type, department_id, emp_id_number, 
				firstname, middlename, lastname, suffix, email, birth_date, birth_place, gender, citizenship, civil_status, religion, 
				present_address, present_address_zip, permanent_address, permanent_address_zip, tel_number, mobile_number,
				ice_fullname, ice_address, ice_tel_number, has_custom_availability,
				suspended_flg, admin_flg, circ_flg, circ_mbr_flg, catalog_flg, reports_flg,
				date_created, created_by, date_modified, modified_by, modified_logs 
			FROM tbl_employee 
			WHERE id=".GetSQLValueString($employee_id)."";
	$query = mysql_query($sql);
	if(mysql_num_rows($query)>0){
		$row = mysql_fetch_array($query);
		$employeeDetail['id'] = $row['id'];
		$employeeDetail['user_id'] = $row['user_id'];
		$employeeDetail['employee_type'] = $row['employee_type'];
		$employeeDetail['department_id'] = $row['department_id'];
		$employeeDetail['emp_id_number'] = $row['emp_id_number'];
		$employeeDetail['firstname'] = $row['firstname'];
		$employeeDetail['middlename'] = $row['middlename'];
		$employeeDetail['lastname'] = $row['lastname'];
		$employeeDetail['suffix'] = $row['suffix'];
		$employeeDetail['email'] = $row['email'];
		$employeeDetail['birth_date'] = $row['birth_date'];
		$employeeDetail['birth_place'] = $row['birth_place'];
		$employeeDetail['gender'] = $row['gender'];
		$employeeDetail['citizenship'] = $row['citizenship'];
		$employeeDetail['civil_status'] = $row['civil_status'];
		$employeeDetail['religion'] = $row['religion'];
		$employeeDetail['present_address'] = $row['present_address'];
		$employeeDetail['present_address_zip'] = $row['present_address_zip'];
		$employeeDetail['permanent_address'] = $row['permanent_address'];
		$employeeDetail['permanent_address_zip'] = $row['permanent_address_zip'];
		$employeeDetail['tel_number'] = $row['tel_number'];
		$employeeDetail['mobile_number'] = $row['mobile_number'];
		$employeeDetail['ice_fullname'] = $row['ice_fullname'];
		$employeeDetail['ice_address'] = $row['ice_address'];
		$employeeDetail['ice_tel_number'] = $row['ice_tel_number'];
		$employeeDetail['has_custom_availability'] = $row['has_custom_availability'];
		$employeeDetail['suspended_flg'] = $row['suspended_flg'];
		$employeeDetail['admin_flg'] = $row['admin_flg'];
		$employeeDetail['circ_flg'] = $row['circ_flg'];
		$employeeDetail['circ_mbr_flg'] = $row['circ_mbr_flg'];
		$employeeDetail['catalog_flg'] = $row['catalog_flg'];
		$employeeDetail['reports_flg'] = $row['reports_flg'];
		$employeeDetail['date_created'] = $row['date_created'];
		$employeeDetail['created_by'] = $row['created_by'];
		$employeeDetail['date_modified'] = $row['date_modified'];
		$employeeDetail['modified_by'] = $row['modified_by'];
		$employeeDetail['modified_logs'] = $row['modified_logs'];
		return $employeeDetail;
	}else{
		return $employeeDetail;
	}
}

function hasCommonSubject_2_1($subject_id){
	/*
	ID	Code		Name
	- - - - - - - - - - - - - - - - - - - -
	246	GENSCI 1	Physical Science
	251	CS 5		Computer Organization and Assembly Language
	255	GENSCI 2	Biological Science
	262	CS 11		Data Communication & Networking
	268	CS 15		Advanced Database Management System
	274	CS 14		System Analysis and Design
	301	IT 5		Computer Organization and Assembly Language
	309	IT 12		System Analysis and Design
	310	IT 13		Advanced Database Management System
	311	IT 14		Networking Design & Management
	524	NATSCI 1	Physical Science
	525	NATSCI 2	Biological Science
	553	IT 262		Object-Oriented Programming Design and Development
	554	CS 29		Object-Oriented Programming Design and Development
	- - - - - - - - - - - - - - - - - - - -
	*/
	
	$commonSubject_id = 0;
	
	switch($subject_id){
		case 246:	$commonSubject_id = 524;break;
		case 251:	$commonSubject_id = 301;break;
		case 255:	$commonSubject_id = 255;break;
		case 262:	$commonSubject_id = 311;break;
		case 268:	$commonSubject_id = 310;break;
		case 274:	$commonSubject_id = 309;break;
		case 301:	$commonSubject_id = 251;break;
		case 309:	$commonSubject_id = 274;break;
		case 310:	$commonSubject_id = 268;break;
		case 311:	$commonSubject_id = 262;break;
		case 524:	$commonSubject_id = 246;break;
		case 525:	$commonSubject_id = 255;break;
		case 553:	$commonSubject_id = 554;break;
		case 554:	$commonSubject_id = 553;break;
	}

	if($commonSubject_id>0){
		return true;
	}else{
		return false;
	}	
}

function getStudentSubjectForEnrollmentInArr_2_1($student_id){
		$arr_subject = array('Id','Code','Name','Units','AvailableClass');
		$sqlSubjectsToEnroll = "SELECT curr.subject_id, sub.subject_code, sub.subject_name, 
									curr.year_level, curr.term, curr.units, sched.classes, gr.final_grade, gr.grade_remarks 
								FROM tbl_curriculum_subject as curr 
								LEFT JOIN tbl_subject as sub ON curr.subject_id=sub.id 
								LEFT JOIN (SELECT subject_id,term_id,count(subject_id) as 'classes' FROM tbl_schedule GROUP BY subject_id, term_id HAVING term_id=".CURRENT_TERM_ID.") as sched ON curr.subject_id=sched.subject_id 
								LEFT JOIN (SELECT student_id, subject_id, final_grade, remarks as 'grade_remarks' FROM tbl_student_final_grade WHERE student_id=".$student_id.") as gr ON curr.subject_id=gr.subject_id
								WHERE curr.curriculum_id=".getStudentCurriculumID($student_id)." AND sched.classes>0 AND (gr.grade_remarks NOT LIKE 'P' OR gr.grade_remarks IS NULL) 
								ORDER BY curr.year_level, curr.term, sub.subject_code ASC";
		$query = mysql_query($sqlSubjectsToEnroll);
		while($row = mysql_fetch_array($query)){
			$arr_subject[] = $row['subject_id'];
		}
		return $arr_subject;
	}

function getCourses_2_1($selected_id){
	$arr_str = array();
	$sql = "SELECT id,course_code,course_name FROM tbl_course WHERE publish='Y' ORDER BY course_name ASC";
	$query = mysql_query($sql);
	while($row = mysql_fetch_array($query)){
		if($row['id'] == $selected_id){
			$selected = 'selected="selected"';
		}else{
			$selected = '';
		}

		$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.'('.$row['course_code'].') '.$row['course_name'].'</option>';
	}
	return implode('',$arr_str);
}

function getNumberAsRank_2_1($num){
	$rank;
	switch($num){
		case  1:  $rank = "First";break;
		case  2:  $rank = "Second";break;
		case  3:  $rank = "Third";break;
		case  4:  $rank = "Fourth";break;
		case  5:  $rank = "Fifth";break;
		case  6:  $rank = "Sixth";break;
		case  7:  $rank = "Seventh";break;
		case  8:  $rank = "Eight";break;
		case  9:  $rank = "Nineth";break;
		case 10:  $rank = "Tenth";break;
	}
	return $rank;
}
	
/*Version 2.1 finish ----------------------------------*/

/* [+] ALL THE GENERATE SELECT BOX FUNCTION */

	function generateStudentNumber($course,$inc){		
			$sql = "SELECT * FROM tbl_student";
			$query = mysql_query($sql);
			if(mysql_num_rows($query)>0){
				$num = (mysql_num_rows($query)*1)-1;
				$x=0;
				$arr = array();

				while($row = mysql_fetch_array($query)){
					if($x==$num){
						list($fst,$snd,$trd,$last_num) = explode('-',$row['student_number']);
					}
					
					list($fst,$snd,$trd,$last_num2) = explode('-',$row['student_number']);
					$arr[] = $last_num2;
					$x++;
				}
			}else{
				$arr[] = $last_num = 0;
			}

			$term = getSemesterByWord(CURRENT_TERM_ID);
			$year = substr((string)CURRENT_SY_START,-2);
			
			asort($arr);
			foreach ($arr as $val){
				$last_num = $val;
			}

			$last = sprintf("%04d", ($last_num*1)+($inc*1));
			
			//63 = philippine code

			if(checkStudentIDExist('63-'.$year.$term.'-'.getStudentCollegeCode($course).'-'.$last)){
				for($x=0;$x='ok';$x++){
					$last = $last+1;
					if(checkStudentIDExist('63-'.$year.$term.'-'.getStudentCollegeCode($course).'-'.$last)){
						$x++;	
					}else{
						$x='ok';
						return '63-'.$year.$term.'-'.getStudentCollegeCode($course).'-'.$last;	
					}
				}
			}else{
				return '63-'.$year.$term.'-'.getStudentCollegeCode($course).'-'.$last;
			}
	}
	
	function generateStudentNumberForNextTerm($sy,$course,$inc){		
			$sql = "SELECT * FROM tbl_student ORDER BY student_number";
			$query = mysql_query($sql);
			if(mysql_num_rows($query)>0){
				$num = (mysql_num_rows($query)*1)-1;
				$x=0;
				$arr = array();

				while($row = mysql_fetch_array($query)){
					if($x==$num){
						list($fst,$snd,$trd,$last_num) = explode('-',$row['student_number']);
					}
					
					list($fst,$snd,$trd,$last_num2) = explode('-',$row['student_number']);
					$arr[] = $last_num2;
					$x++;
				}
			}else{
				$last_num = 0;
			}

			$term = getSemesterByWord($sy);

			$year = substr((string)getSyStart($sy),-2);
			
			asort($arr);
			foreach ($arr as $val){
				$last_num = $val;
			}

			$last = sprintf("%04d", ($last_num*1)+($inc*1));
			
			//63 = philippine code

			if(checkStudentIDExist('63-'.$year.$term.'-'.getStudentCollegeCode($course).'-'.$last)){
				for($x=0;$x='ok';$x++){
					$last = $last+1;
					if(checkStudentIDExist('63-'.$year.$term.'-'.getStudentCollegeCode($course).'-'.$last)){
						$x++;	
					}else{
						$x='ok';
						return '63-'.$year.$term.'-'.getStudentCollegeCode($course).'-'.$last;	
					}
				}//
			}else{
				return '63-'.$year.$term.'-'.getStudentCollegeCode($course).'-'.$last;
			}
	}

	function generateEmployeeNumber($name,$inc){		
			$sql = "SELECT * FROM tbl_employee";
			$query = mysql_query($sql);
			if(mysql_num_rows($query)>0){
				$num = (mysql_num_rows($query)*1)-1;
				$x=0;
				$arr = array();

				while($row = mysql_fetch_array($query)){
					list($nm,$last_num2) = explode('_',$row['emp_id_number']);
					
					$arr[] = $last_num2;
					$x++;
				}
			}else{
				$last_num2 = 0;
			}
			
			asort($arr);
			foreach ($arr as $val){
				$last_num = $val;
			}

			$last = sprintf("%04d", ($last_num*1)+($inc*1));
			
			$sPattern = '/\s*/m'; 
			$sReplace = '';
			$name =  preg_replace( $sPattern, $sReplace, $name );

			if(checkEmpIDExist($name.'_'.$last)){
				for($x=0;$x='ok';$x++){
					$last = $last+1;
					if(checkEmpIDExist($name.'_'.$last)){
						$x++;	
					}else{
						$x='ok';
						return $name.'_'.$last;	
					}
				}
			}else{
				return $name.'_'.$last;
			}
	}
	
	function generateSpecialNumber($length = 5, $letters ='1234567890'){		
		$arr_str = array();
		$lettersLength = strlen($letters)-1; 

		for($i = 0 ; $i < $length ; $i++){ 
			$arr_str[] = $letters[rand(0,$lettersLength)]; 
		}

		$num =  implode('',$arr_str);
		$yir = CURRENT_SY_END;
		
		return $num.' s. '.$yir;
	}

	function generateDay($selected_item){
		$arr_str = array();

		for($ctr = 1; $ctr <=31; $ctr ++){
			$val = $ctr < 10 ? "" . $ctr : $ctr;

			if($val == $selected_item){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$val.'" '.$selected.' >'.$val.'</option>';
		}
		return implode('',$arr_str);
	}

	function generateYear($selected_item){
		$arr_str = array();
		$curr_yr = date('Y') - 5;

		for($ctr = $curr_yr ; $ctr >= 1946; $ctr --){
			if($ctr == $selected_item*1){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$ctr.'" '.$selected.' >'.$ctr.'</option>';
		}

		return implode('',$arr_str);
	}
	
	function generateYearUntilNow($selected_item){
		$arr_str = array();
		$curr_yr = date('Y');

		for($ctr = $curr_yr ; $ctr >= 1990; $ctr --){
			if($ctr == $selected_item){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$ctr.'" '.$selected.' >'.$ctr.'</option>';
		}

		return implode('',$arr_str);
	}

	function generateYearForSchoolYear($selected_item){
		$arr_str = array();
		$curr_yr = date('Y') + 5;

		for($ctr = $curr_yr ; $ctr >= 2005; $ctr --){
			if($ctr == $selected_item){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$ctr.'" '.$selected.' >'.$ctr.'</option>';
		}

		return implode('',$arr_str);
	}

	function generateMonth($selected_item){
		$arr_str = array();

		for($ctr = 1 ; $ctr <= 12; $ctr ++){
			if($ctr == $selected_item){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$ctr.'" '.$selected.' >'.getMonthName($ctr).'</option>';
		}
		return implode('',$arr_str);
	}

	function generateReports($class){
		$arr_str = array();

		$sql = "SELECT * FROM tbl_school_report WHERE classification = '".$class."'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['report_name'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateReligion($selected_id){
		$arr_str = array();

		$sql = "SELECT * FROM tbl_religion";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['title'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateCountry($selected_id){
		$arr_str = array();

		$sql = "SELECT * FROM tbl_country";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['title'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateCitizenship($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_citizenship";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['title'].'</option>';
		}
		return implode('',$arr_str);
	}

	function getSyStart($term){
		if($term!=''){
			$sql = "SELECT * FROM tbl_school_year_term WHERE id=".$term;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			
			$sqls = "SELECT * FROM tbl_school_year WHERE id =".$row['school_year_id'];
			$querys = mysql_query($sqls);
			$rows = mysql_fetch_array($querys);
			
			return $rows['start_year'];
		}
	}

	function getCitizenship($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_citizenship WHERE id=$id";						
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);

			return $row['title'];
		}
	}

	function generateLastSchool($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_school_list";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['school_name'].'</option>';
		}

			//$arr_str[] = '<option value="others" '.$selected.' >Others</option>';
		return implode('',$arr_str);
	}

	function generateLastSchoolCode($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_school_attended";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['school_code'].'</option>';
		}
			//$arr_str[] = '<option value="others" '.$selected.' >Others</option>';
		return implode('',$arr_str);
	}

	function generateGrossIncome($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_gross_income";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['title'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateBuilding($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_building WHERE publish='Y'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['building_name'].' ('.$row['building_code'].')'.'</option>';
		}
		return implode('',$arr_str);
	}

	function generateYearLevel($year_level){
		$arr_str = array();

		for($ctr = 1;$ctr<=$year_level;$ctr++){
			if($ctr == $year_level){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$ctr.'" '.$selected.' >'.getYearLevel($ctr).'</option>';
		}

		return implode('',$arr_str);
	}

	function generateExamDate($selected_id,$term){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_exam_date WHERE term_id = ".$term;						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if(strtotime($row['entrance_date'])>=strtotime(date('Y-m-d'))){
				if($row['id'] == $selected_id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.getMonthName($date[1]).' '.$date[2].', '.$date[0]. '</option>';
			}
		}
		return implode('',$arr_str);
	}

	function generateYearLevelByCourse($course_id,$year){
		$arr_str = array();
		$sql = "SELECT no_of_years FROM tbl_curriculum WHERE is_current = 'Y' AND course_id =" .$course_id;						
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$years = $row['no_of_years'];
		for($ctr=1; $ctr<=$years; $ctr++){
			if($ctr == 1){
				$yir = '1st Year';
			}
			
			if($ctr == 2){
				$yir = '2nd Year';
			}
			
			if($ctr == 3){
				$yir = '3rd Year';
			}
			
			if($ctr == 4 ){
				$yir = '4th Year';
			}

			if($year==$ctr){
				$select = 'selected=selected';
			}else{
				$select = '';
			}

			$arr_str[] = '<option value="'.$ctr.'" '.$select.' >'.$yir.'</option>';
		}

		return implode('',$arr_str);
	}

	function generateCollege($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_college WHERE publish='Y'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['college_name'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateDepartment($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_department WHERE publish='Y'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['department_name'].' ('.$row['department_code'].')'.'</option>';
		}
		return implode('',$arr_str);
	}

	function generateTempSched($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_schedule_template WHERE publish='Y'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['template_name'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateSubject($id, $selected_id=''){
		$arr_str = array();
		if(isset($id) && $id!='' && $id=='all'){
			$sql = "SELECT * FROM tbl_subject WHERE publish='Y'";						
			$query = mysql_query($sql);
			while($row = mysql_fetch_array($query)){
				if($row['id'] == $selected_id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['subject_name'].' ('.$row['subject_code'].')'.'</option>';

			}
			return implode('',$arr_str);
		}else{
			if(isset($id) && $id!=''){
				$sql = "SELECT * FROM tbl_subject WHERE publish='Y' AND department_id = ".$id;						
				$query = mysql_query($sql);
				while($row = mysql_fetch_array($query)){
					if($row['id'] == $selected_id){
						$selected = 'selected="selected"';
					}else{
						$selected = '';
					}
				$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['subject_name'].' ('.$row['subject_code'].')'.'</option>';
				}
				return implode('',$arr_str);
			}
		}
	}

	function generateStudentSubject($id){
		$arr_str = array();
		if(isset($id) && $id!=''){
			$sql = "SELECT * FROM tbl_student_schedule WHERE student_id=".$id;						
			$query = mysql_query($sql);
			while($row = mysql_fetch_array($query)){
				$arr_str[] = '<option value="'.$row['subject_id'].'" '.$selected.' >'.getSubjName($row['subject_id']).' ('.getSubjCode($row['subject_id']).')'.'</option>';
			}
			return implode('',$arr_str);
		}
	}

	function generateEmployeeType($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_access WHERE access_type IN ('A','E','C') ORDER BY access_name";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			if($row['access_type'] == 'C') $txt = ' (Custom Access)';
			else $txt = '';
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['access_name'].$txt .'</option>';
		}
		return implode('',$arr_str);
	}

	function generateUserRole($user_role_id){
		$arr_str = array();
		$sql = "SELECT * FROM user_role WHERE user_role_id <> 2";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['user_role_id'] == $user_role_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$row['user_role_id'].'" '.$selected.' >'.$row['role_name'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateCourse($course_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_course WHERE publish='Y'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $course_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['course_name'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateLibClassfy($class){
		$arr_str = array();
		$sql = "SELECT * FROM ob_mbr_classify_dm";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['code'] == $class){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$row['code'].'" '.$selected.' >'.$row['description'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateSchoolYr($id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_school_year";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['start_year'].'-'.$row['end_year'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateSchoolTerms($id){
		$arr_str = array();
		if($id == ''){
			$id = CURRENT_TERM_ID;
		}
		$sql = "SELECT * FROM tbl_school_year";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			$sql_term = "SELECT * FROM tbl_school_year_term WHERE school_year_id = ".$row['id'];						
			$query_term  = mysql_query($sql_term);
			while($row_term  = mysql_fetch_array($query_term )){
				if($row_term ['id'] == $id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$arr_str[] = '<option value="'.$row_term ['id'].'" '.$selected.' >'.$row['start_year'].' - '.$row['end_year'].' ( '.$row_term ['school_term'] .' )</option>';
			}
		}
		return implode('',$arr_str);
	}

	function generateSchoolTermsForApplicants($id){
		$arr_str = array();
		if($id == ''){
			$id = CURRENT_TERM_ID+1;
		}
		$sql = "SELECT * FROM tbl_school_year";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			$sql_term = "SELECT * FROM tbl_school_year_term WHERE school_year_id = ".$row['id'];						
			$query_term  = mysql_query($sql_term);
			while($row_term  = mysql_fetch_array($query_term )){
				if($row_term ['id'] == $id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$arr_str[] = '<option value="'.$row_term ['id'].'" '.$selected.' >'.$row['start_year'].' - '.$row['end_year'].' ( '.$row_term ['school_term'] .' )</option>';
			}
		}
		return implode('',$arr_str);
	}

	function generateSchoolTermsForFees($id){
		$arr_str = array();
		$sql_term = "SELECT * FROM tbl_school_year_term";						
		$query_term  = mysql_query($sql_term);
		while($row_term  = mysql_fetch_array($query_term )){
			if($row_term ['id'] == $id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row_term ['id'].'" '.$selected.' >'.getSYandterm($row_term['id']).'</option>';
		}
		return implode('',$arr_str);
	}

	function generateSchoolTermsApplicant($id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_school_year";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			$sql_term = "SELECT * FROM tbl_school_year_term WHERE school_year_id = ".$row['id'];						
			$query_term  = mysql_query($sql_term);
			while($row_term  = mysql_fetch_array($query_term )){
				if($row_term ['id'] == $id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}

				if($row['start_year'] >= CURRENT_SY_START && $row_term['id'] > CURRENT_TERM_ID){
						$arr_str[] = '<option value="'.$row_term ['id'].'" '.$selected.' >'.$row['start_year'].' - '.$row['end_year'].' ( '.$row_term ['school_term'] .' )</option>';
				}
			}
		}
		return implode('',$arr_str);
	}

	function generateSchoolTermsBYSY($school_year_id,$selected_id=''){
		$arr_str = array();
		$sql= "SELECT * FROM tbl_school_year_term WHERE school_year_id = ".$school_year_id;						
		$query  = mysql_query($sql);
		while($row  = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row ['id'].'" '.$selected.' >'.$row['school_term'].'</option>';
		}
		return implode('',$arr_str);
	}	

	function generateSchoolYrWithoutPast($id){
		$arr_str = array();
		$sqlcur= "SELECT * FROM tbl_school_year WHERE is_current_sy = 'Y'";						
		$querycur  = mysql_query($sqlcur);
		$rowcur = mysql_fetch_array($querycur);
		$sql = "SELECT * FROM tbl_school_year";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			if($rowcur['start_year'] <= $row['start_year']){
				$arr_str[] = '<option value="'.$row['id'].'" '.$selected.'>'.$row['start_year'].'-'.$row['end_year'].'</option>';
			}
		}
		return implode('',$arr_str);
	}

	function generateSchoolTermsWithoutPastBYSY($school_year_id,$selected_id=''){
		$arr_str = array();
		$sqlcur= "SELECT * FROM tbl_school_year_term WHERE is_current = 'Y' 
					AND school_year_id = ".$school_year_id;						
		$querycur  = mysql_query($sqlcur);
		$rowcur = mysql_fetch_array($querycur);
	
		$sql= "SELECT * FROM tbl_school_year_term WHERE school_year_id = ".$school_year_id;						
		$query  = mysql_query($sql);

		while($row  = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			if($row['id'] >= $rowcur['id']){
				$arr_str[] = '<option value="'.$row ['id'].'" '.$selected.' >'.$row['school_term'].'</option>';
			}
		}
		return implode('',$arr_str);
	}

	function generateBibliography($id){
		$arr_str = array();
		$sql = "SELECT * FROM ob_biblio";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row ['bibid'] == $id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row ['bibid'].'" '.$selected.' >'.$row['title'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generatePeriod($syId,$school_yr_period_id=''){
		$arr_str = array();
		$sql_sec = "SELECT * FROM tbl_schedule WHERE id = $syId";
		$res_sec = mysql_query($sql_sec);
		$row_sec = mysql_fetch_array($res_sec);
		$sql = "SELECT * FROM tbl_school_year_period WHERE term_id = ".$row_sec['term_id'];
		$query = mysql_query ($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $school_yr_period_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['period_name'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generatePeriodPerTerm($id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_school_year_period WHERE term_id = ".CURRENT_TERM_ID;
		$query = mysql_query ($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['period_name'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateCurriculumByCourse($curriculum_id,$course_id){
		$arr_str = array();
		if($course_id != ''){
			$sql = "SELECT * FROM curriculum WHERE publish='Y' AND course_id = $course_id";						
			$query = mysql_query($sql);
			while($row = mysql_fetch_array($query)){
				if($row['curriculum_id'] == $curriculum_id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$arr_str[] = '<option value="'.$row['curriculum_id'].'" '.$selected.' >'.$row['curriculum_code'].'</option>';
			}
		}
		return implode('',$arr_str);
	}

	function generateCurriculum($curriculum_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_curriculum";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $curriculum_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['curriculum_code'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateYearByCurriculum($year,$id){
		$arr_str = array();
		$sql = "SELECT no_of_years FROM tbl_curriculum WHERE id='$id'";						
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$num = $row['no_of_years'];
		for($cnt=1; $cnt<=$num; $cnt++){		
			if($cnt == $year){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$cnt.'" '.$selected.' >'.$cnt.'</option>';
		}
		return implode('',$arr_str);
	}

	function generateTermByCurriculum($term,$id){
		$arr_str = array();
		$sql = "SELECT term_per_year FROM tbl_curriculum WHERE id='$id'";						
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$num = $row['term_per_year'];
		for($cnt=1; $cnt<=$num; $cnt++){
			if($cnt == $term){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$cnt.'" '.$selected.' >'.$cnt.'</option>';
		}

		if(isset($id)){
			$arr_str[] = '<option value="'.($num+1).'" '.$selected.' >Summer'.$num.'</option>';
		}

		return implode('',$arr_str);
	}

	function generateTime($selected_time,$interval = '1',$from = '01:00',$to = '24:45'){
		/* Interval 0 = 0min; 1 = 15mins; 2 = 30mins; 3 = 45mins */
		$from_str = str_replace(':','',$from);
		$to_str = str_replace(':','',$to);		
		$arr_str = array();
		switch ($interval){
			case '0':
				$int = 0;
				$min =	0;
				break;
			case '1':
				$int = 3;
				$min =	15;
				break;
			case '2':
				$int = 1;
				$min =	30;
				break;
			case '3':
				$int = 1;
				$min =	45;
				break;
		}
		$inc_min = 0;
		for($ctr = 1; $ctr<=24; $ctr++){
			$str_hr = $ctr < 10?'0'.$ctr:$ctr;
			for($int_ctr = 0;$int_ctr<=$int;$int_ctr++){
				$inc_min = $inc_min;
				$inc_min = $inc_min>=60?'0':$inc_min;
				$str_min = $inc_min < 10?'0'.$inc_min:$inc_min;
				$str_time = $str_hr . ':'. $str_min;
				if($str_time == $selected_time){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}

				$temp_time = $str_hr . $str_min;
				if($from_str <= $temp_time  && $to_str >= $temp_time){
					$arr_str[] = '<option value="'.$str_time.'" '.$selected.' >'.$str_time.'</option>';	
				}

				$inc_min = $inc_min + $min;			
			}
		}
		return implode('',$arr_str);
	}

	function generateRoomTimeTable($room_id, $interval = '1',$from = '01:00',$to = '24:45'){
		/* Interval 0 = 0min; 1 = 15mins; 2 = 30mins; 3 = 45mins */
		$from_str = str_replace(':','',$from);
		$to_str = str_replace(':','',$to);		
		$arr_str = array();
		switch ($interval){
			case '0':
				$int = 0;
				$min =	0;
				break;
			case '1':
				$int = 3;
				$min =	15;
				break;
			case '2':
				$int = 1;
				$min =	30;
				break;
			case '3':
				$int = 1;
				$min =	45;
				break;
		}
		$inc_min = 0;
		$sql = "SELECT * FROM tbl_room WHERE id = $room_id" ;
		$query = mysql_query ($sql);
		$row = mysql_fetch_array($query);	
		$has_custom_availability = $row['has_custom_availability'];
		$day_arr = array('M','T','W','TH','F','S','SU');

		for($ctr = 1; $ctr<=24; $ctr++){
			$str_hr = $ctr < 10?'0'.$ctr:$ctr;
			for($int_ctr = 0;$int_ctr<=$int;$int_ctr++){
				$inc_min = $inc_min;
				$inc_min = $inc_min>=60?'0':$inc_min;
				$str_min = $inc_min < 10?'0'.$inc_min:$inc_min;
				$str_time = $str_hr . ':'. $str_min;

				if($str_time == $selected_time){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}

				$temp_time = $str_hr . $str_min;
	
				if($from_str <= $temp_time  && $to_str >= $temp_time  ){	
					$selected_arr = array();
					if($has_custom_availability == 'Y'){
						$sql = "SELECT * FROM tbl_room_availability WHERE room_id = $room_id AND from_time = '$str_time'" ;
						$query = mysql_query ($sql);
						while($row = mysql_fetch_array($query)){
							$selected_arr[] = $row['day_available'];
						}
					}else{
						$selected_arr =  array('M','T','W','TH','F','S','SU');
					}
					$arr_str[] = '<tr class="highlight">';
					$arr_str[] = '<td><input type="checkbox" value="'.$str_time.'" class="chktime" />&nbsp;&nbsp;'.$str_time.'</td>';

					foreach ($day_arr as $day){
						if(in_array($day,$selected_arr)){
							$class = 'checkmark';
							$checked = 'checked="checked"';
						}else{
							$class = 'xmark';
							$checked = '';
						}
						$arr_str[] = '<td>';
						$arr_str[] = '<input name="'.$day.'[]" id="'.$day . $str_hr . $str_min.'" type="checkbox" value="'.$str_time.'" '.$checked.' objDay="'.$day.'" objTime="'.$str_time.'" style="display:none;"/>';
						$arr_str[] = '<a href="#" name="flag" class="'.$class .'" returnObj="'.$day . $str_hr . $str_min.'" returnTime="'.$str_time.'" returnDay="'.$day.'"></a>';
						$arr_str[] = '</td>';
					}
					$arr_str[] = '</tr>';
					unset($selected_arr);
				}
				$inc_min = $inc_min + $min;			
			}
		}
		return implode('',$arr_str);
	}

	function generateProfTimeTable($prof_id, $interval = '1',$from = '01:00',$to = '24:45'){
		/* Interval 0 = 0min; 1 = 15mins; 2 = 30mins; 3 = 45mins */
		$from_str = str_replace(':','',$from);
		$to_str = str_replace(':','',$to);		
		$arr_str = array();
		switch ($interval){
			case '0':
				$int = 0;
				$min =	0;
				break;
			case '1':
				$int = 3;
				$min =	15;
				break;
			case '2':
				$int = 1;
				$min =	30;
				break;
			case '3':
				$int = 1;
				$min =	45;
				break;
		}
		$inc_min = 0;
		$sql = "SELECT * FROM tbl_employee WHERE id = $prof_id" ;
		$query = mysql_query ($sql);
		$row = mysql_fetch_array($query);	
		$has_custom_availability = $row['has_custom_availability'];
		$day_arr = array('M','T','W','TH','F','S','SU');

		for($ctr = 1; $ctr<=24; $ctr++){
			$str_hr = $ctr < 10?'0'.$ctr:$ctr;
			for($int_ctr = 0;$int_ctr<=$int;$int_ctr++){
				$inc_min = $inc_min;
				$inc_min = $inc_min>=60?'0':$inc_min;
				$str_min = $inc_min < 10?'0'.$inc_min:$inc_min;
				$str_time = $str_hr . ':'. $str_min;
				if($str_time == $selected_time){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$temp_time = $str_hr . $str_min;
				if($from_str <= $temp_time  && $to_str >= $temp_time  ){	
					$selected_arr = array();
					if($has_custom_availability == 'Y'){
						$sql = "SELECT * FROM tbl_employee_availability WHERE employee_id = $prof_id AND from_time = '$str_time'" ;
						$query = mysql_query ($sql);
						while($row = mysql_fetch_array($query)){
							$selected_arr[] = $row['day_available'];
						}
					}else{
						$selected_arr =  array('M','T','W','TH','F','S','SU');
					}
					$arr_str[] = '<tr class="highlight">';
					$arr_str[] = '<td><input type="checkbox" value="'.$str_time.'" class="chktime" />&nbsp;&nbsp;'.$str_time.'</td>';

					foreach ($day_arr as $day){
						if(in_array($day,$selected_arr)){
							$class = 'checkmark';
							$checked = 'checked="checked"';
						}else{
							$class = 'xmark';
							$checked = '';
						}
						$arr_str[] = '<td>';
						$arr_str[] = '<input name="'.$day.'[]" id="'.$day . $str_hr . $str_min.'" type="checkbox" value="'.$str_time.'" '.$checked.' objDay="'.$day.'" objTime="'.$str_time.'" style="display:none;"/>';
						$arr_str[] = '<a href="#" name="flag" class="'.$class .'" returnObj="'.$day . $str_hr . $str_min.'" returnTime="'.$str_time.'" returnDay="'.$day.'"></a>';
						$arr_str[] = '</td>';
					}
					$arr_str[] = '</tr>';
					unset($selected_arr);
				}
				$inc_min = $inc_min + $min;			
			}
		}
		return implode('',$arr_str);
	}

	function generatePaymentMethod($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_payment_method";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['name'].'</option>';
		}
		return implode('',$arr_str);
	}
	
	function generatePaymentType($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_payment_types";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['name'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generatePaymentTerm($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_payment_term";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['name'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateSystemLogs($access){
		$arr_str = array();
		if($access == '1'){
			$sql = "SELECT * FROM tbl_system_logs WHERE for_user_id = '".USER_ID."' OR for_admin = 'Y' ORDER BY id desc";						
			$query = mysql_query($sql);
			while($row = mysql_fetch_array($query)){
				$arr_str[] = getUserFullNameFromUserId($row['user_id']) . ' ( '.  date("F  d , Y h:i" ,$row['date_created']) .' )' . '<br />'. $row['message'];		
			}
		}else if($access == '2'){
			$sql = "SELECT * FROM tbl_system_logs WHERE (for_user_id = '".USER_ID."' OR user_id  = '".USER_ID."') AND (for_self = 'Y' OR for_prof = 'Y' )ORDER BY id desc";						
			$query = mysql_query($sql);
			while($row = mysql_fetch_array($query)){
				$arr_str[] = getUserFullNameFromUserId($row['user_id']) . ' ( '.  date("F  d , Y h:i" ,$row['date_created']) .' )' . '<br />'. $row['message'];		
			}
		}else if($access == '6'){
			$sql = "SELECT * FROM tbl_system_logs WHERE (for_user_id = '".USER_ID."' OR user_id  = '".USER_ID."') AND (for_self = 'Y' OR for_student = 'Y' )ORDER BY id desc";						
			$query = mysql_query($sql);
			while($row = mysql_fetch_array($query)){
				$arr_str[] = getUserFullNameFromUserId($row['user_id']) . ' ( '.  date("F  d , Y h:i" ,$row['date_created']) .' )' . '<br />'. $row['message'];		
			}
		}					
		return implode('<br /><br />',$arr_str);
	}

	function generateCheckNo($id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_student_payment WHERE check_no <> 0 
					AND bank <> 'none' AND is_bounced <> 'Y' AND is_refund <> 'Y' 
					AND student_id = ".$id. " AND term_id = ".CURRENT_TERM_ID;						
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
			$arr_str[] = '<option value="'.$row['id'].'" >'.$row['check_no'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateDiscount($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_discount WHERE term_id = ".CURRENT_TERM_ID." AND publish ='Y'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.$row['name'].' ('.$row['value'].'%)</option>';
		}
		return implode('',$arr_str);
	}

	function generateScheme($selected_id,$term=''){
		$arr_str = array();
		$term==''?$term=CURRENT_TERM_ID:$term=$term;
		$sql = "SELECT * FROM tbl_payment_scheme WHERE term_id = ".$term." AND publish ='Y'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			$sqldet = "SELECT * FROM tbl_payment_scheme_details WHERE scheme_id=".$row['id'];
			$querydet = mysql_query($sqldet);
			$rowdet = mysql_num_rows($querydet);

			if($rowdet > 0){
				if($row['id'] == $selected_id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$arr_str[] = '<option value='.$row['id'].' '.$selected.' >'.$row['name'].'</option>';
			}
		}
		// return implode('',$arr_str);
	}
	
	function generateAllScheme($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_payment_scheme WHERE publish ='Y' ORDER BY term_id";						
		$query = mysql_query($sql);
		$term ='';

		while($row = mysql_fetch_array($query)){
			$sqldet = "SELECT * FROM tbl_payment_scheme_details WHERE scheme_id=".$row['id'];
			$querydet = mysql_query($sqldet);
			$rowdet = mysql_num_rows($querydet);

			if($rowdet > 0){
				if($row['id'] == $selected_id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$sqlterm = "SELECT * FROM tbl_school_year WHERE id=".getSchoolYearIdByTermId($row['term_id']);						

				$queryterm = mysql_query($sqlterm);
				$rowterm = mysql_fetch_array($queryterm);
			
				if($term!=$row['term_id']){	
					$arr_str[] = '<option value="" disabled="disabled" style="background-color:#efefef; color:#000;" font-weight:bold;>('.$rowterm['start_year'].'-'.$rowterm['end_year'].'-'.getSchoolTerm($row['term_id']).')</option>';
				}

				$arr_str[] = '<option value='.$row['id'].' '.$selected.' >'.$row['name'].'</option>';
				$term = $row['term_id'];
			}
		}
		return implode('',$arr_str);
	}
	
	function generateFees($selected_id){
		$arr_str = array();
		$sql = "SELECT DISTINCT term_id FROM tbl_school_fee WHERE publish ='Y'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['id'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$i = getSchoolYearIdByTermId($row['term_id']);
			$sqlterm = "SELECT * FROM tbl_school_year WHERE id=".getSchoolYearIdByTermId($row['term_id']);						
			$queryterm = mysql_query($sqlterm);
			$rowterm = mysql_fetch_array($queryterm);
			
			if($i!=$y){
				//$arr_str[] = '<option value='.$row['term_id'].' '.$selected.' >'.$rowterm['start_year'].'-'.$rowterm['end_year'].'('.getSchoolTerm($row['term_id']).')'.'</option>';
				$arr_str[] = '<option value='.$row['term_id'].' '.$selected.' >'.$rowterm['start_year'].'-'.$rowterm['end_year'].'</option>';
				$y = $i;
			}
		}
		return implode('',$arr_str);
	}

	function generateStudentReportFields($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_report_fields WHERE category = 'S' AND publish ='Y'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['field_name'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}

			$arr_str[] = '<option value="'.$row['field_name'].'" '.$selected.' >'.$row['field_title'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateEmployeeReportFields($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_report_fields WHERE category = 'E' AND publish ='Y'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['field_name'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['field_name'].'" '.$selected.' >'.$row['field_title'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateLibraryReportFields($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_report_fields WHERE category = 'L' AND publish ='Y' AND table_name = 'ob_biblio_copy'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['field_name'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['field_name'].'" '.$selected.' >'.$row['field_title'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateLibraryOverdueReportFields($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_report_fields WHERE category = 'L' AND publish ='Y'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['field_name'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['field_name'].'" '.$selected.' >'.$row['field_title'].'</option>';
		}
		return implode('',$arr_str);
	}

	function generateLibraryCheckOutReportFields($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_report_fields WHERE category = 'L' AND publish ='Y'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['field_name']!='days_late'){
				if($row['field_name'] == $selected_id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$arr_str[] = '<option value="'.$row['field_name'].'" '.$selected.' >'.$row['field_title'].'</option>';
			}
		}
		return implode('',$arr_str);
	}

	function generateLibraryBalanceReportFields($selected_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_report_fields WHERE category = 'L' AND publish ='Y'  AND table_name = 'ob_member_account'";						
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if($row['field_name'] == $selected_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$arr_str[] = '<option value="'.$row['field_name'].'" '.$selected.' >'.$row['field_title'].'</option>';
		}
		return implode('',$arr_str);
	}			

/*  [-] ALL THE GENERATE SELECT BOX FUNCTION */
/**********************************************************************/

/**********************************************************************/
/* [+] ALL THE GET VALUE FUNCTION */

	function isValidEmail($email){
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $email);
	}

	function getMonthName($m=0){ 		
		switch($m){
		  case 1:return "January";break;
		  case 2:return "February";break;
		  case 3:return "March";break;
		  case 4:return "April";break;
		  case 5:return "May";break;
		  case 6:return "June";break;
		  case 7:return "July";break;
		  case 8:return "August";break;
		  case 9:return "September";break;
		  case 10:return "October";break;
		  case 11:return "November";break;		
		  case 12:return "December";break;		
		}
	} 

	function getMainComponent($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_components 
					WHERE id = ".$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			if($row['parent_id']=='0'){
				echo $row['unique_friendly_title'];
			}else{
				getMainComponent($row['parent_id']);
			}
		}
	}

	function getMainComponent2($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_components 
					WHERE id = ".$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['unique_friendly_title'];
		}
	}

	function getProfessorUserID($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_employee 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['user_id'];
		}
	}

	function getProfessorFullName($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_employee 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['lastname'].', '.$row['firstname'].' '.$row['middlename'];
		}
	}

	function getProfessorInitial($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_employee 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['firstname'][0].'. '.$row['lastname'];
		}
	}
	
	function getUserInitial($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_employee 
					WHERE user_id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['firstname'][0].'. '.$row['lastname'];
		}
	}

	function getEmployeeFullName($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_employee 
					WHERE user_id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['lastname'].', '.$row['firstname'].' '.$row['middlename'];
		}
	}

	function getEmployeeFullNameByUserId($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_employee 
					WHERE user_id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['lastname'].', '.$row['firstname'].' '.$row['middlename'];
		}
	}

	function getEmployeeNumber($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_employee 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['emp_id_number'];
		}
	}

	function getEmployeeDeparmentId($id){
		if(isset($id)){
			$sql = "SELECT department_id FROM tbl_employee 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['department_id'];
		}
	}

	function getUserFullNameFromUserId($id){
		if(isset($id)){
			$sql_student = "SELECT *
					FROM tbl_student 
					WHERE user_id =" .$id;
			$query_student = mysql_query($sql_student);	
			$ctr_student = mysql_num_rows($query_student);	
			if($ctr_student > 0){
				$row_student = mysql_fetch_array($query_student);
				return $row_student['lastname'].', '.$row_student['firstname'].' '.$row_student['middlename'];
			}

			$sql_employee = "SELECT *
					FROM tbl_employee 
					WHERE user_id =" .$id;
			$query_employee = mysql_query($sql_employee);	
			$ctr_employee = mysql_num_rows($query_employee);	
			if($ctr_employee > 0){
				$row_employee = mysql_fetch_array($query_employee);
				return $row_employee['lastname'].', '.$row_employee['firstname'].' '.$row_employee['middlename'];
			}

			$sql_parent = "SELECT *
					FROM tbl_parent 
					WHERE user_id =" .$id;
			$query_parent = mysql_query($sql_parent);	
			$ctr_parent = mysql_num_rows($query_parent);	
			if($ctr_parent > 0){
				$row_parent = mysql_fetch_array($query_parent);
				return $row_parent['name'];
			}
		}
	}

	function getUserFullNameFromId($id){
		if(isset($id)){
			$sql_student = "SELECT *
					FROM tbl_student 
					WHERE id =" .$id;
			$query_student = mysql_query($sql_student);	
			$ctr_student = mysql_num_rows($query_student);	
			if($ctr_student > 0){
				$row_student = mysql_fetch_array($query_student);
				return $row_student['lastname'].', '.$row_student['firstname'].' '.$row_student['middlename'];
			}

			$sql_employee = "SELECT *
					FROM tbl_employee 
					WHERE id =" .$id;
			$query_employee = mysql_query($sql_employee);	
			$ctr_employee = mysql_num_rows($query_employee);	
			if($ctr_employee > 0){
				$row_employee = mysql_fetch_array($query_employee);
				return $row_employee['lastname'].', '.$row_employee['firstname'].' '.$row_employee['middlename'];
			}

			$sql_parent = "SELECT *
					FROM tbl_parent 
					WHERE id =" .$id;
			$query_parent = mysql_query($sql_parent);	
			$ctr_parent = mysql_num_rows($query_parent);	
			if($ctr_parent > 0){
				$row_parent = mysql_fetch_array($query_parent);
				return $row_parent['name'];
			}
		}
	}

	function getNextBalanceDate($id,$totalFee,$term,$stud=''){
		$sql = "SELECT *
				FROM tbl_payment_scheme_details
				WHERE scheme_id = ".$id." ORDER BY sort_order";
		$result = mysql_query($sql);
		$x=0;
		$fee=0;
		if($stud!=''){
			$total = 0;
			$sqls = "SELECT *
					FROM tbl_student_payment
					WHERE student_id = ".$stud." AND term_id=".$term;
			$results = mysql_query($sqls);
			while($rows = mysql_fetch_array($results)){
					$total+=$rows['amount'];
			}
		}
		$cnt = 0;

		while($row = mysql_fetch_array($result)){
			$fee = ($row['payment_value']/100)*$totalFee;
			if(strtotime(date('Y-m-d'))>=strtotime($row['payment_date']."+3 days")&&$fee<$total&&$fee>0){
				$date = $row['payment_date'];
				$fees += 0;
				$total = $total - $fee;
				//$totalb += $total - $fee;
			}else if(strtotime(date('Y-m-d'))>=strtotime($row['payment_date']."+3 days")&&$fee>=$total&&$fee>0){
				$date = $row['payment_date'];
				$fees += ($fee - $total);
				$total= 0;
			}
			/*else if(strtotime(date('Y-m-d'))>=strtotime($row['payment_date'])&&$total==0){
				$date = $row['payment_date'];
				$fees += $fee;
				$total=0;
			}*/

			if(strtotime(date('Y-m-d'))<=strtotime($row['payment_date']."+3 days")&&$fee>$total&&$cnt==0){
				$date = $row['payment_date'];
				$fees += $fee;
				$cnt++;
			}
				
			if($total>0&&strtotime(date('Y-m-d'))<=strtotime($row['payment_date']."+3 days")){
				$fees = $fee-$total;	
			}
		}
		return array($date,$fees);
	}

	function getBalanceBySort($id,$totalFee,$date,$stud=''){
		$sql = "SELECT *
				FROM tbl_payment_scheme_details
				WHERE scheme_id = ".$id." ORDER BY sort_order";
		$result = mysql_query($sql);
		$x=0;
		$fee=0;
		while($row = mysql_fetch_array($result)){
			if(strtotime($date)>=strtotime($row['payment_date'])&&$row['payment_type']=="A"&&$row['sort_order']==1){
				$fee += $row['payment_value'];
				$totalFee = $totalFee - $fee;
			}else if(strtotime($date)>=strtotime($row['payment_date'])&&$row['payment_type']=="A"){
				$fee += $row['payment_value'];
			}else if(strtotime($date)>=strtotime($row['payment_date'])&&$row['payment_type']=="P"){
				$fee += ($row['payment_value']/100)*$totalFee;
			}
			/*else if(strtotime($date)< strtotime($row['payment_date'])&&$row['sort_order']==1){
				if($row['payment_type']=='P'){
					$fee += ($row['payment_value']/100)*$totalFee;
				}else{
					$fee += $row['payment_value'];
					$totalFee = $totalFee - $fee;
				}
			}*/
		}

		if($stud!=''){
			$total = 0;
			$sql = "SELECT *
					FROM tbl_student_payment
					WHERE student_id = ".$stud." AND term_id=".CURRENT_TERM_ID;
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($result)){
				$total+=$row['amount'];
			}
			$fee=$fee-$total;
		}

		if($fee>0){
			return $fee;
		}else{
			return 0;
		}
	}

	function getLearnerfee(){
		$sql = 'SELECT * FROM tbl_school_fee WHERE term_id='.CURRENT_TERM_ID;
		$query = mysql_query($sql);
		if(mysql_num_rows($query)>0){
			while($row=mysql_fetch_array($query)){
				if($row['fee_name']=="Learner's Enhancement Programs"){
					$id = $row['amount'];	
				}
			}
			return $id;
		}
		return 0;
	}
	
	//TEMPORARY GETTING FEES FOR MBM
	function getAdditionalFees($id){
		$sql = 'SELECT * FROM tbl_student WHERE id='.$id;
		$query = mysql_query($sql);
		$row=mysql_fetch_array($query);
		
		if(mysql_num_rows($query)>0){
			$sql2 = "SELECT * FROM tbl_school_other_fee WHERE course_id=".$row['course_id'];
			$query2 = mysql_query($sql2);
			while($row2 = mysql_fetch_array($query2)){
				$yr = explode(',',$row2['school_year_level']);
				
				if(in_array(getStudentNextYearLevel($id),$yr)){
					$sql = "INSERT INTO tbl_student_other_fees 
						(
							student_id,
							term_id,
							fee_id,
							amount,
							quantity
						) 
						VALUES 
						(
							".GetSQLValueString($id,"int").", 
							".GetSQLValueString(CURRENT_TERM_ID,"int").",  
							".GetSQLValueString($row2['id'],"int").",
							".GetSQLValueString($row2['amount'],"text").",
							".GetSQLValueString('1',"text")."
						)";	
					mysql_query($sql);
				}	
			}
		}
	}

	function getAddRefund($id,$stud,$amt){
		if(isset($stud)){
			$sql = "INSERT INTO tbl_student_payment
				(
					student_id, 
					term_id,
					subject_id,
					amount, 
					is_refund,
					date_created,
					created_by
				) 
				VALUES 
				(
					".GetSQLValueString($stud,"text").",  
					".CURRENT_TERM_ID.", 
					".GetSQLValueString($id,"text").",
					".GetSQLValueString($amt,"text").",
					"."'Y'".",
					".time().", 
					".USER_ID."
				)";
		}
	}

	function getGraduationDate($id){
		if(isset($id)&&$id!=''){
			$sql = 'SELECT * FROM tbl_student_graduate_status WHERE student_id='.$id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			
			if(mysql_num_rows($query)>0){
				return date("F d, Y", $row['date_graduated']);
			}else{
				return 'N/A';
			}
			
		}else{
			return 'N/A';
		}
	}

	function getStudentLastSchool($id){
		if(isset($id)&&$id!=''){
			$ret = '';
			$sql = "SELECT * FROM tbl_student WHERE id=".$id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);

			if($row['grade_school']!=''){
				$ret = $row['grade_school'];
			}

			if($row['high_school']!=''){
				$ret = $row['high_school'];
			}

			if($row['college_school']!=''){
				$ret = $row['college_school'];
			}
			return $ret;
		}
	}
	
	function GetStudentScheme($id,$term){
		$term==""?$term=CURRENT_TERM_ID:'';
		$sql = "SELECT * FROM tbl_student_enrollment_status WHERE term_id = ".$term." AND student_id=".$id;
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);

		if(mysql_num_rows($query)>0){
			return $row['scheme_id'];
		}else{
			return '';
		}
	}

	function GetSchemeForSurcharge($id){
		$sql = "SELECT * FROM tbl_student_enrollment_status WHERE term_id = ".CURRENT_TERM_ID." AND student_id=".$id;
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		if(mysql_num_rows($query)>0){
			$sql2 = "SELECT * FROM tbl_payment_scheme WHERE id=".$row['scheme_id'];
			$query2 = mysql_query($sql2);
			$row2 = mysql_fetch_array($query2);
			//return $row2['surcharge'];
			$sqls2 = "SELECT * FROM tbl_payment_scheme WHERE term_id=".$row2['term_id'];
			$querys2 = mysql_query($sqls2);
			$cnt2 = 0;
			while($rows2 = mysql_fetch_array($querys2)){
				if($row['scheme_id']==$rows2['id']){
					$count2 = $cnt2;
				}
				$cnt2++;
			}
			
			$sql3 = "SELECT * FROM tbl_student_fees WHERE student_id=".$id." AND term_id=".CURRENT_TERM_ID;
			$query3 = mysql_query($sql3);
			$row3 = mysql_fetch_array($query3);
			
			$sql4 = "SELECT * FROM tbl_school_fee WHERE id=".$row3['fee_id'];
			$query4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($query4);
			
			$sqls1 = "SELECT * FROM tbl_payment_scheme WHERE term_id=".$row4['term_id'];
			$querys1 = mysql_query($sqls1);
			$cnt1 = 0;
				
			while($rows1 = mysql_fetch_array($querys1)){
				if($cnt1==$count2){
					$surg = $rows1['surcharge'];
				}
				$cnt1++;
			}
			return $surg;
		}else{
			return 0;
		}
	}
	
	function GetSchemeForSurcharge2($id,$term){
		$term=$term!=''?$term:CURRENT_TERM_ID;
		$sql = "SELECT * FROM tbl_student_enrollment_status WHERE term_id = ".$term." AND student_id=".$id;
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		if(mysql_num_rows($query)>0){
			$sql2 = "SELECT * FROM tbl_payment_scheme WHERE id=".$row['scheme_id'];
			$query2 = mysql_query($sql2);
			$row2 = mysql_fetch_array($query2);
			
			//return $row2['surcharge'];
			$sqls2 = "SELECT * FROM tbl_payment_scheme WHERE term_id=".$row2['term_id'];
			$querys2 = mysql_query($sqls2);
			$cnt2 = 0;
			
			while($rows2 = mysql_fetch_array($querys2)){
				if($row['scheme_id']==$rows2['id']){
					$count2 = $cnt2;
				}
				$cnt2++;
			}
			
			$sql3 = "SELECT * FROM tbl_student_fees WHERE student_id=".$id." AND term_id=".CURRENT_TERM_ID;
			$query3 = mysql_query($sql3);
			$row3 = mysql_fetch_array($query3);
			
			$sql4 = "SELECT * FROM tbl_school_fee WHERE id=".$row3['fee_id'];
			$query4 = mysql_query($sql4);
			$row4 = mysql_fetch_array($query4);
			
			$sqls1 = "SELECT * FROM tbl_payment_scheme WHERE term_id=".$row4['term_id'];
			$querys1 = mysql_query($sqls1);
			$cnt1 = 0;
				
			while($rows1 = mysql_fetch_array($querys1)){
				if($cnt1==$count2){
					$surg = $rows1['surcharge'];
				}
				$cnt1++;
			}
			return $surg;
		}else{
			return 0;
		}
	}
	
	function GetSchemeForSurchargePerTerm($id){
		$sql = "SELECT * FROM tbl_student_fees WHERE term_id = ".CURRENT_TERM_ID." AND student_id=".$id;
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
			
		if(mysql_num_rows($query)>0){
			$sql2 = "SELECT * FROM tbl_school_fee WHERE id=".$row['fee_id'];
			$query2 = mysql_query($sql2);
			$row2 = mysql_fetch_array($query2);
			
			//return $row2['surcharge'];
			$sqls2 = "SELECT * FROM tbl_payment_scheme WHERE term_id=".$row2['term_id'];
			$querys2 = mysql_query($sqls2);
			$cnt2 = 0;
			
			$scs='';
			
			while($rows2 = mysql_fetch_array($querys2)){
				//$scs!=''?$scs.= $rows2['surcharge'].",":$scs= $rows2['surcharge'];
				$scs[]= $rows2['surcharge'];
			}
			return $scs;
		}else{
			return 0;
		}
	}
	
	function GetSchemeForCurrentSurcharge($id){
		if($id!=''){
			//return $row2['surcharge'];
			$sqls2 = "SELECT * FROM tbl_payment_scheme WHERE term_id=".$id;
			$querys2 = mysql_query($sqls2);
			$cnt2 = 0;
			
			$scs='';
			
			while($rows2 = mysql_fetch_array($querys2)){
				//$scs!=''?$scs.= $rows2['surcharge'].",":$scs= $rows2['surcharge'];
				$scs[]= $rows2['surcharge'];
			}
			return $scs;
		}else{
			return 0;
		}
	}
	
	function getStudentLastSchoolYears($id){
		if(isset($id)&&$id!=''){
			$ret = '';
			$sql = "SELECT * FROM tbl_student WHERE id=".$id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);

			if($row['grade_school_years']!='0/0-0/0'){
				$ret = $row['grade_school_years'];
			}

			if($row['high_school_years']!='0/0-0/0'){
				$ret = $row['high_school_years'];
			}

			if($row['college_school_years']!='0/0-0/0'){
				$ret = $row['college_school_years'];
			}

			$yir = explode('-',$ret);
			$yir1 = explode('/',$yir[0]);
			$yir2 = explode('/',$yir[1]);

			return getMonthName($yir2[0]).' '.$yir2[1];
		}
	}

	function getStudentPaymentSchemeID($id){
		$sql = "SELECT * FROM tbl_student_payment WHERE student_id=".$id." AND term_id=".CURRENT_TERM_ID;
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);

		return $row['payment_scheme_id'];
	}

	function getExamDate($id){
		if($id!=''){
			$sql = 'SELECT * FROM tbl_exam_date WHERE id='.$id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$date = explode('-',$row['entrance_date']);
			$exam = getMonthName($date[1]).' '.$date[2].', '.$date[0].' ('.$row['time_from'].'-'.$row['time_to'].')';
		}
		return $exam;
	}

	function getStudentUserID($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_student 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			return $row['user_id'];
		}
	}

	function getUserEmail($id){
		if(isset($id)){		
			$sql = "SELECT *
					FROM tbl_user 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			if($row['access_id']==6){
				$sqls = "SELECT *
						FROM tbl_student 
						WHERE user_id = ".$row['id'];
				$querys = mysql_query($sqls);		
				$rows = mysql_fetch_array($querys);
				return $rows['email'];
			}else if($row['access_id']==7){
				$sqls = "SELECT *
						FROM tbl_parent 
						WHERE user_id = ".$row['id'];
				$querys = mysql_query($sqls);		
				$rows = mysql_fetch_array($querys);
				return $rows['email'];
			}else{
				$sqls = "SELECT *
						FROM tbl_employee 
						WHERE user_id =".$row['id'];
				$querys = mysql_query($sqls);		
				$rows = mysql_fetch_array($querys);
				return $rows['email'];
			}
		}
	}

	function getCountryName($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_country 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['title'];
		}
	}

	function getEmployeeUsernamebyId($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_user 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['username'];
		}
	}

	function getStudentFullName($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_student 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['lastname'].', '.$row['firstname'].' '.$row['middlename'];
		}
	}
	
	function getOtherFullName($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_other_payments 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['lastname'].', '.$row['firstname'];
		}
	}	

	function getStudentRequirements($admission_type){
		if(isset($admission_type)){
			$sql = "SELECT * FROM tbl_requirements WHERE admission='".$admission_type."'";
			$query = mysql_query($sql);
			$arr = array();
			while($row = mysql_fetch_array($query)){	
				$arr[] = $row['requirement'];
			}
			return $arr;
		}
	}
	
	function getStudentPassedRequirements($id,$add){
		if(isset($id)){
			$str_arr = array();
            $str_arr[] = '<legend><strong>Requirements:</strong></legend>';
			$sql = "SELECT * FROM tbl_requirements WHERE admission='".$add."'";
			$query = mysql_query($sql);
			$x=0;
			while($row = mysql_fetch_array($query)){
				$sqlstud = "SELECT requirement_id FROM tbl_student_requirements WHERE student_id=".$id." AND requirement_id = ".$row['id'];
				$querystud = mysql_query($sqlstud);
			
				if(mysql_num_rows($querystud)>0){
					$chk = 'checked="checked"';	
				}else{
					$chk='';	
				}
				
				$str_arr[] = '<label><input name="chk[]" type="checkbox" '.$chk.' value="'.$row['id'].'" id="chk'.$x.'"/>
				'.$row['requirement'].'</label>';
				$x++;
			}
			echo implode('',$str_arr);
		}
	}

	function getStudentAdmissionType($admission_type){
		if(isset($admission_type)){
			if($admission_type=='F'){
				return 'Freshmen';
			}else if($admission_type=='T'){
				return 'Transferee';
			}else if($admission_type=='R'){
				return 'Returnee';
			}else if($admission_type=='C'){
				return 'Cross-Enrolee';
			}else if($admission_type=='CG'){
				return 'College Graduate';
			}else if($admission_type=='SC'){
				return 'Short Course';
			}
		}
	}

	function getStudentFullNameByUser($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_student 
					WHERE user_id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['lastname'].', '.$row['firstname'].' '.$row['middlename'];
		}
	}

	function getParentFullName($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_parent 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['lastname'].', '.$row['firstname'].' '.$row['middlename'];
		}
	}

	function getStudentNumber($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_student 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['student_number'];
		}
	}

	function getStudentIdByNumber($number){
		if(isset($number)){
			$sql = "SELECT *
					FROM tbl_student 
					WHERE student_number = $number";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['id'];
		}
	}

	function getEmployeeIdByNumber($number){
		if(isset($number)){
			$sql = "SELECT *
					FROM tbl_employee
					WHERE emp_id_number = $number";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['id'];
		}
	}

	

	/*GET THE COURSENAME USING STUDENT_ID*/
	function getStudentCourse($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_student as student, tbl_course as course
					WHERE student.course_id = course.id AND student.id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['course_name'];
		}
	}

	function getStudentCourseCode($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_student as student, tbl_course as course
					WHERE student.course_id = course.id AND student.id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['course_code'];
		}
	}

	function getStudentCourseName($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_student as student, tbl_course as course
					WHERE student.course_id = course.id AND student.id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['course_name'];
		}
	}

	function getStudentCourseId($id){
		if(isset($id)){
			$sql = "SELECT course.id as cid
					FROM tbl_student as student, tbl_course as course
					WHERE student.course_id = course.id AND student.id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['cid'];
		}
	}

	function updateStudentYearLevel(){
		$sql = "SELECT * FROM tbl_student";
		$query = mysql_query($sql);
		
		while($row = mysql_fetch_array($query)){
			$yr = getStudentYearLevel($row['id']);
			$up = "UPDATE tbl_student SET year_level=".$yr." WHERE id=".$row['id'];
			mysql_query($up);
		}
	}

	function getStudentYearLevel($id){
		if(isset($id)){
			/*$sql = "SELECT count(id) as ctr_lvl
					FROM tbl_student_enrollment_status 
					WHERE student_id = $id AND enrollment_status = 'E'";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return floor($row['ctr_lvl']/getNumberOfTerm()) + 1;*/
			
			$sqlstud = "SELECT * FROM tbl_student WHERE id= $id";
			$querystud = mysql_query($sqlstud);
			$rowstud = mysql_fetch_array($querystud);
			$sql = "SELECT *
					FROM tbl_student_enrollment_status 
					WHERE student_id = $id AND enrollment_status = 'E' ORDER BY term_id";
			$query = mysql_query($sql);
			$yr_rows = mysql_num_rows($query);
				
			if($yr_rows>0){
				$yr = 0;
				$c=0;
				$s='';
				while($row = mysql_fetch_array($query)){
					$sql2 = "SELECT * FROM tbl_school_year_term WHERE id=".$row['term_id'];
					$query2 = mysql_query($sql2);
					$row2 = mysql_fetch_array($query2);
					
					if(strtolower($row2['school_term'])!='summer'){
						$yr++;
					}
				}
				return round($yr/getNumberOfTerm());
			}else{
				if($rowstud['admission_type']=='F'){
					return 1;
				}else{
					return $rowstud['year_level']!=''?$rowstud['year_level']:1;
				}
			}
		}
	}

	function getStudentNextYearLevel($id){
		if(isset($id)){
			$sqlstud = "SELECT * FROM tbl_student WHERE id= $id";
			$querystud = mysql_query($sqlstud);
			$rowstud = mysql_fetch_array($querystud);
			$sql = "SELECT *
					FROM tbl_student_enrollment_status 
					WHERE student_id = $id AND enrollment_status = 'E'";
			$query = mysql_query($sql);
			$yr_rows = mysql_num_rows($query);
			$yr=0;
				
			if($yr_rows>0){
				while($row = mysql_fetch_array($query)){
					$sql2 = "SELECT * FROM tbl_school_year_term WHERE id=".$row['term_id'];
					$query2 = mysql_query($sql2);
					$row2 = mysql_fetch_array($query2);
					
					if(strtolower($row2['school_term'])!='summer'){
						$yr++;
					}
				}
							
				if(strtolower($row2['school_term'])!='summer'){
					//$yr_rows++;
					$yr++;
				}
				return round($yr/getNumberOfTerm());
			
			}else{
				if($rowstud['admission_type']=='F'){
					return 1;
				}else{
					return $rowstud['year_level']!=''?$rowstud['year_level']:1;
				}
			}
		}
	}
	
	function getStudentCurrentSemInCurriculum($id){
		if(isset($id)){
			$sql = "SELECT count(id) as ctr_lvl
					FROM tbl_student_enrollment_status 
					WHERE student_id = $id AND enrollment_status = 'E'";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			if($row['ctr_lvl']==1){
				return 1;
			}else{
				return $row['ctr_lvl']%getNumberOfTerm()==0?'1':'2';
			}
		}
	}

	function getElectiveSubject($id){
		if($id!=''){
			$sql = "SELECT * FROM tbl_subject_elective WHERE subject_id = ".$id;
			$query = @mysql_query($sql);
			$row = @mysql_fetch_array($query);
			
			if(@mysql_num_rows($query)>0){
				if($row['subject_id']!=''){
					return $row['subject_id'];
				}else{
					return 0;	
				}
			}else{
				return 0;	
			}
		}
	}
	
	function checkIfElective($id){
		$sql = "SELECT * FROM tbl_curriculum_subject WHERE subject_id=".$id." AND subject_category='E' OR subject_category='EO'";
		$query = mysql_query($sql);
		
		if(mysql_num_rows($query)>0){
			return 1;	
		}else{
			return 0;	
		}
	}

	function getStudentReservedUnit($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT sum(units) as units_ctr
					FROM tbl_student_reserve_subject 
					WHERE student_id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['units_ctr']==''?'0':$row['units_ctr'];
		}	
	}

	function getStudentMaxEnrolledUnit($id){
		if(isset($id) && $id != ''){
			$curr = getStudentCurriculumID($id);
			$yr_level = getStudentYearLevel($id);
			$next_term = getStudentNextSemInCurriculum($id);
			
			if($next_term==1&&$yr_level>1){
				$yr_level++;
			}else if($next_term==1&&$yr_level==1){
				$sql = "SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status = 'E' AND student_id=".$id;
				$query = mysql_query($sql);
				$rows = mysql_num_rows($query);
				
				if($rows>0){
					$yr_level++;
				}
			}

			/*$term = getSemesterByWord(CURRENT_TERM_ID);
			$sql = "SELECT year_level
					FROM tbl_student 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['year_level'];
			*/

			/* [+] TEMP SOLUTION ** PLEASE CHANGE ME ASAP 
			$sql = "SELECT 
						sum(units) as total_units
					FROM 
						tbl_curriculum_subject 
					WHERE 
						curriculum_id =".$curr." AND 
						year_level = ".$yr_level." AND 
						term = ".getStudentCurrentSemInCurriculum($id)
					;
					
			$sql = "SELECT 
						sum(units) as total_units
					FROM 
						tbl_curriculum_subject 
					WHERE 
						curriculum_id =".$curr." AND 
						year_level = ".$yr_level." AND 
						term = ".$term
					;*/
					
			$sql = "SELECT 
						sum(units) as total_units
					FROM 
						tbl_curriculum_subject 
					WHERE 
						curriculum_id =".$curr." AND 
						year_level = ".$yr_level." AND 
						term = ".$next_term;

			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			return $row['total_units'];

			/* [-] TEMP SOLUTION ** PLEASE CHANGE ME ASAP m*/			
		}
	}

	function getCurrentStudentMaxEnrolledUnit($id){
		if(isset($id) && $id != ''){
			$curr = getStudentCurriculumID($id);
			$yr_level = getStudentYearLevel($id);

			/*
			$sql = "SELECT year_level
					FROM tbl_student 
					WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			return $row['year_level'];
			*/

			/* [+] TEMP SOLUTION ** PLEASE CHANGE ME ASAP */
			$sql = "SELECT 
						sum(units) as total_units
					FROM 
						tbl_curriculum_subject 
					WHERE 
						curriculum_id =".$curr." AND 
						year_level = ".$yr_level." AND 
						term = ".getStudentSemInCurriculum($id)
					;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['total_units'];

			/* [-] TEMP SOLUTION ** PLEASE CHANGE ME ASAP m*/			
		}
	}	

	function getStudentSemInCurriculum($id){
		if(isset($id)){
			$sql = "SELECT count(id) as ctr_lvl
					FROM tbl_student_enrollment_status 
					WHERE student_id = $id AND enrollment_status = 'E'";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			if($row['ctr_lvl']<=getNumberOfTerm()){
				return 1;
			}else{
				return $row['ctr_lvl']/getNumberOfTerm()%getNumberOfTerm()?1:getNumberOfTerm();
			}
		}
	}

	function getStudentCurrentYearLevel($id){
		if(isset($id)){
			$sql = "SELECT count(id) as ctr_lvl
					FROM tbl_student_enrollment_status 
					WHERE student_id = $id AND enrollment_status = 'E'";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			if($row['ctr_lvl']==1){
				return 1;
			}else{
				return $row['ctr_lvl']/getNumberOfTerm()%getNumberOfTerm()?1:getNumberOfTerm();
			}
		}
	}

	function getStudentEnrolledTerm($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT count(term_id) as term_ctr
					FROM tbl_student_enrollment_status 
					WHERE student_id = $id AND 
					enrollment_status = 'E'";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['term_ctr'];
		}	
	}

	function getEmployeeDeptName($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_employee as emp, tbl_department as dept
					WHERE emp.department_id = dept.id AND emp.id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			return $row['department_name'];
		}
	}

	function getEmployeeFullNameBySchedId($id){
		if(isset($id)){
			$sql = "SELECT *
					FROM tbl_employee as emp, tbl_schedule as sched
					WHERE emp.id = sched.employee_id AND sched.id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['lastname'].', '.$row['firstname'].' '.$row['middlename'];
		}
	}

	function getDeptName($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_department WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['department_name'];
		}
	}

	function getPaymentMethod($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_payment_method WHERE id = " .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['name'];
		}
	}

	function getPaymentMethodCheque(){
		$sql = "SELECT * FROM tbl_payment_method WHERE name LIKE '%Check%'";
		$query = mysql_query($sql);		
		$row = mysql_fetch_array($query);
		return $row['id'];
	}

	function getPaymentTerm($id){
		if(isset($id)){
			if($id == 'P'){
				return 'Partial';
			}else{
				return 'Full';
			}
		}
	}

	function getSchemeByStatus($id){
		$sql = "SELECT scheme_id FROM tbl_student_enrollment_status WHERE student_id= ".$id;
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$scheme_id = $row['scheme_id'];
		return $scheme_id;
	}

	function getPaymentSchemeId($id){
		if(isset($id)){
			$sql = "SELECT scheme_id FROM tbl_payment_scheme_details WHERE id= ".$id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$scheme_id = $row['scheme_id'];
			return $scheme_id;
		}
	}

	function canDeleteScheme($id){
		if(isset($id)){
			$sql_sched= "SELECT * FROM tbl_student_payment pay, tbl_payment_scheme_details scheme
						WHERE pay.payment_scheme_id=scheme.id AND scheme.scheme_id = " .$id;
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);

			if($ctr > 0 ){			
				return true;
			}else{
				return false;
			}
		}
	}

	function getPaymentSchemeFirstDue($id){
		if(isset($id)){
			$sql_stud = "SELECT payment_scheme FROM weberp.0_debtors_master WHERE debtor_no= ".$id;
			$query_stud = mysql_query($sql_stud);
			$row_stud = mysql_fetch_array($query_stud);
			$scheme_id = $row_stud['payment_scheme'];
			$sql = "SELECT * FROM tbl_payment_scheme_details 
					WHERE sort_order = 2 AND scheme_id= ".$scheme_id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$due = $row['payment_date'];
			return $due;
		}
	}

	function getSYandTerm($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_school_year_term as sy_term, tbl_school_year as sy WHERE sy.id = sy_term.school_year_id AND sy_term.id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['start_year'].' - '.$row['end_year']. ' ( '.$row['school_term'].' )';
		}
	}

	function getNumberSubjectEnrolled($id,$term){
		$total = 0;
		$sql = "SELECT * FROM tbl_student_schedule WHERE subject_id=".$id." AND term_id=".$term;
		$query = mysql_query($sql);
		
		if(mysql_num_rows($query)>0){
			while($row = mysql_fetch_array($query)){
				$sql2 = "SELECT * FROM tbl_student_enrollment_status WHERE student_id = ".$row['student_id']." AND term_id=".$term;
				$query2 = mysql_query($sql2);
				
				if(mysql_num_rows($query2)>0){
					$row2 = mysql_fetch_array($query2);
					
					if($row2['enrollment_status']=='E'){
						$total++;
					}
				}
			}
		}
		return $total;
	}
	
	function getNumberScheduleEnrolled($id,$term){
		$total = 0;
		
		$sql = "SELECT * FROM tbl_student_schedule WHERE schedule_id=".$id." AND term_id=".$term;
		$query = mysql_query($sql);
		
		if(mysql_num_rows($query)>0){
			while($row = mysql_fetch_array($query)){
				$sql2 = "SELECT * FROM tbl_student_enrollment_status WHERE student_id = ".$row['student_id']." AND term_id=".$term;
				$query2 = mysql_query($sql2);
				
				if(mysql_num_rows($query2)>0){
					$row2 = mysql_fetch_array($query2);
					if($row2['enrollment_status']=='E'){
						$total++;
					}
				}
			}
		}
		return $total;
	}

	function getNumberOfTerm(){
		$sql = "SELECT * FROM tbl_school_year WHERE is_current_sy='Y'";
		$query = mysql_query($sql);		
		$row = mysql_fetch_array($query);
		return $row['number_of_term'];
	}

	function getEmpType($id){
		if(isset($id) && $id !=''){
			$sql = "SELECT * FROM tbl_access WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['access_name'];
		}
	}

	function getCollegeName($id){
		if(isset($id) && $id !=''){
			$sql = "SELECT * FROM tbl_college WHERE id=$id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['college_name'];
		}
	}	

	function getElecSubjName($id){
		if(isset($id) && $id !=''){
			$sql = "SELECT subject_id FROM tbl_elec_subject WHERE elec_subject_id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['subject_id'];
		}
	}

	function getStudSubjElecName($sID,$id){
		$sql = "SELECT * FROM tbl_student_schedule WHERE student_id=".$id." AND subject_id=".$sID;
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		
		if(mysql_num_rows($query)>0){
			if($row['elective_of']!=''){
				$sqlE = "SELECT * FROM tbl_subject WHERE id=".$row['elective_of'];
				$queryE = mysql_query($sqlE);
				$rowE = mysql_fetch_array($queryE);
				
				return "(".$rowE['subject_name'].")";
			}else{
				return "";	
			}
		}else{
			return "";	
		}
	}
	
	function getSubjElecName($sID,$id){
		$sql = "SELECT * FROM tbl_schedule WHERE employee_id=".$id." AND subject_id=".$sID;
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		
		if(mysql_num_rows($query)>0){
				$sqlE = "SELECT * FROM tbl_student_schedule WHERE subject_id=".$row['subject_id']. " AND schedule_id=".$row['id'];
				$queryE = mysql_query($sqlE);
				$rowE = mysql_fetch_array($queryE);
				
				if($rowE['elective_of']!=''){
					return "(".getSubjName($rowE['elective_of']).")";
				}else{
					return '';	
				}
		}else{
			return "";	
		}
	}

	function getSubjName($id){
		if(isset($id) && $id !=''){
			$sql = "SELECT * FROM tbl_subject WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['subject_name'];	
		}
	}

	function getStudentCollegeName($id){
		if(isset($id) && $id !=''){
			$sql = "SELECT * FROM tbl_course a,tbl_college b WHERE a.college_id=b.id AND a.id = ".$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['college_name'];
		}
	}	

	function getStudentCollegeCode($id){
		if(isset($id) && $id !=''){
			$sql = "SELECT * FROM tbl_course a,tbl_college b WHERE a.college_id=b.id AND a.id = ".$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['college_code'];
		}
	}	

	function getTermIdBySchedId($sched_id){
		if(isset($sched_id) && $sched_id !=''){
			$sql = "SELECT term_id FROM tbl_schedule WHERE id = $sched_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['term_id'];
		}
	}	

	function getPeriodTermId($period_id){
		if(isset($period_id) && $period_id !=''){
			$sql = "SELECT term_id FROM tbl_school_year_period WHERE id = $period_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['term_id'];
		}
	}		

	function getPeriodCount($term){
		$term ==''?$term=CURRENT_SCHOOL_YEAR_ID:'';

		if(isset($term) && $term !=''){
			$sql = "SELECT term_id FROM tbl_school_year WHERE id = $term";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['number_of_period'];
		}
	}	

	function getSubjNameBySchedId($sched_id){
		if(isset($sched_id) && $sched_id !=''){
			$sql = "SELECT subject_id FROM tbl_schedule WHERE id = $sched_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return getSubjName($row['subject_id']);
		}
	}	

	function getSubjCode($id){
		if(isset($id) && $id !=''){
			$sql = "SELECT subject_code FROM tbl_subject WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['subject_code'];
		}
	}

	function getSubjCodeBySchedId($sched_id){
		if(isset($sched_id) && $sched_id !=''){
			$sql = "SELECT subject_id FROM tbl_schedule WHERE id = $sched_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return getSubjCode($row['subject_id']);
		}
	}	

	function getSubjType($id){
		if(isset($id) && $id !=''){
			$sql = "SELECT subject_type FROM tbl_subject WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['subject_type'];
		}
	}

	function getSubjIdBySchedule($id){
		if(isset($id) && $id !=''){
			$sql = "SELECT * FROM tbl_schedule WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['subject_id'];
		}
	}

	function getSubjUnit($id){
		if(isset($id) && $id !=''){
			$sql = "SELECT * FROM tbl_curriculum_subject WHERE subject_id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['units'];
		}
	}

	function getSchedSubjectId($sched_id){
		if(isset($sched_id)){
			$sql = "SELECT subject_id FROM tbl_schedule WHERE id = $sched_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['subject_id'];
		}
	}

	function getSchedFromTime($sched_id){
		if(isset($sched_id)){
			$sql = "SELECT time_from FROM tbl_schedule WHERE id = $sched_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['time_from'];
		}
	}

	function getSchedToTime($sched_id){
		if(isset($sched_id)){
			$sql = "SELECT time_to FROM tbl_schedule WHERE id = $sched_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['time_to'];
		}
	}

	function getSchedProf($sched_id){
		if(isset($sched_id)){
			$sql = "SELECT employee_id FROM tbl_schedule WHERE id = $sched_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['employee_id'];
		}
	}

	function getSchedRoom($sched_id){
		if(isset($sched_id)){
			$sql = "SELECT room_id FROM tbl_schedule WHERE id = $sched_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['room_id'];
		}
	}

	function getRoomNo($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_room WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['room_no'];
		}
	}
	
	function getSubjRoom($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_schedule WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			$sql = "SELECT * FROM tbl_room WHERE id = ".$row['room_id'];
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['room_no'];
		}
	}

	function getBuildingName($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_building WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['building_name'];
		}
	}

	function getRoleName($user_role_id){
		if(isset($user_role_id)){
			$sql = "SELECT * FROM user_role WHERE user_role_id=" .$user_role_id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['role_name'];
		}
	}

	function getSchoolTerm($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_school_year_term WHERE id=" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['school_term'];
		}
	}

	function getCurriculumCode($id){
		if(isset($id)){
			$sql = "SELECT curriculum_code FROM tbl_curriculum WHERE id=" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['curriculum_code'];
		}
	}

	function getCurriculumCourse($id){
		if(isset($id)){
			$sql = "SELECT course_id FROM tbl_curriculum WHERE id=" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['course_id'];
		}
	}

	function getCurriculumByCourseId($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_curriculum WHERE is_current='Y' AND course_id=" .$id;
			$query = mysql_query($sql);		
			$row = @mysql_fetch_array($query);
			return $row['id'];
		}
	}	

	function getStudentEnrolledBySchedule($id,$term){
		if($id != ''){
			$sql = "SELECT * FROM tbl_student_schedule 
					WHERE 
						term_id = ".$term." AND
						schedule_id = ".$id;
			$query = mysql_query($sql);		
			$ctr = array();

			if(mysql_num_rows($query)>0){
				while($row = mysql_fetch_array($query)){
					$ctr[]=$row['student_id'];
				}
				return $ctr;
			}		
		}
	}
	
	function CheckIfStudentEnrolledBySubject($id,$term,$sub){
		if($id != ''){
			$sql = "SELECT * FROM tbl_student_schedule 
					WHERE 
						term_id = ".$term." AND
						subject_id = ".$sub." AND student_id=".$id;
			$query = mysql_query($sql);		
			$ctr = array();

			if(mysql_num_rows($query)>0){
				return true;
			}else{
				return false;
			}
		}
	}
	
	function CheckIfStudentFinishEnrolledBySubject($id,$sub){
		if($id != ''){
			$sql = "SELECT * FROM tbl_student_schedule 
					WHERE subject_id = ".$sub." AND student_id=".$id;
			$query = mysql_query($sql);		
			$ctr = array();

			if(mysql_num_rows($query)>0){
				return true;
			}else{
				return false;
			}
		}
	}

	function getCurrentEnrolledTotalUnits($id){
		$sql = 'SELECT * FROM tbl_student_schedule WHERE term_id='.CURRENT_TERM_ID.' AND student_id='.$id;
		$query = mysql_query($sql);
		$total = 0;
		
		if(mysql_num_rows($query)>0){
			while($row=mysql_fetch_array($query)){
				$total += $row['units'];
			}
			return $total;
		}else{
			return $total;	
		}
	}
	
	function getStudentTotalUnitsEnrolled($id){
		$sql = 'SELECT * FROM tbl_student_schedule WHERE student_id='.$id;
		$query = mysql_query($sql);
		$total = 0;
		
		if(mysql_num_rows($query)>0){
			while($row=mysql_fetch_array($query)){
				if(!checkIfstudentFailedSubject($id,$row['subject_id'])){
					$total += $row['units'];
				}
			}
			return $total;
		}else{
			return $total;	
		}
	}
	
	function getEnrolledDate($id,$term){
		$sql = "SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status='E' AND student_id=".$id." AND term_id=".$term;
		$query = mysql_query($sql);	
		
		if(mysql_num_rows($query)>0){
			$row = mysql_fetch_array($query);
			return $row['date_enrolled'];
		}else{
			return "";	
		}
	}
	
	function getEnrolledTotalUnits($id,$stud_id){
		$sql = 'SELECT * FROM tbl_student_schedule WHERE enrollment_status="A" AND term_id='.$id.' AND student_id='.$stud_id;
		$query = mysql_query($sql);
		$total = 0;
		
		if(mysql_num_rows($query)>0){
			while($row=mysql_fetch_array($query)){
				$total += $row['units'];
			}
			return $total;
		}else{
			return $total;	
		}
	}

	function countCheckoutBibliography($id,$copy){
		if($id !=''){
			$sql = "SELECT *
					FROM ob_biblio_status_hist 
					WHERE  status_cd = 'out'
					AND bibid = ".$id." AND copyid=".$copy;
			$query = mysql_query($sql);		
			$ctr = mysql_num_rows($query);
			return $ctr;		
		}
	}

	function countStudentEnrolledPerCourse($course_id,$term_id){
		if($course_id !='' && $term_id != ''){
			$sql = "SELECT * FROM 
						tbl_student_enrollment_status stat, 
						tbl_student stud 
					WHERE 
						stat.student_id = stud.id AND 
						stat.enrollment_status = 'E' AND 
						stud.course_id = $course_id AND 
						stat.term_id = ".$term_id;
			$query = mysql_query($sql);		
			$ctr = mysql_num_rows($query);
			return $ctr;		
		}
	}

	function countStudentEnrolledPerCoursePerYear($course_id,$term_id,$year_lvl){
		if($course_id !='' && $term_id != '' && $year_lvl !=''){
			$stud_count = 0;
			
			$sql = "SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status='E' AND term_id=".$term_id;
			$query = mysql_query($sql);
			
			while($row = mysql_fetch_array($query)){
				$sql2 = "SELECT * FROM tbl_student WHERE id=".$row['student_id'];
				$query2 = mysql_query($sql2);
				$row2 = mysql_fetch_array($query2);
				
				if($course_id == $row2['course_id'] && getStudentYearLevel($row2['id'])==$year_lvl){
					$stud_count++;
				}
			}

			/*$sql = "SELECT curr_subj.subject_id FROM 
						tbl_curriculum_subject curr_subj, 
						tbl_curriculum curr 
					WHERE 
						curr.id = curr_subj.curriculum_id AND 
						curr.course_id = $course_id AND 
						curr_subj.year_level = $year_lvl
						";
			$query = mysql_query($sql);		
			while($row = mysql_fetch_array($query)){
				$sql_stud = "SELECT * FROM 
								tbl_student_schedule sched,
								tbl_student stud
							WHERE 
								sched.student_id = stud.id AND
								stud.course_id = $course_id AND
								sched.term_id = $term_id AND 
								sched.subject_id = " . $row['subject_id']
								;
				$query_stud = mysql_query($sql_stud);
				$ctr_stud = mysql_num_rows($query_stud);
				while($row_stud = mysql_fetch_array($query_stud)){
					if(!in_array($row_stud['student_id'],$stud_arr) && $ctr_stud > 0){
						$stud_arr[] = $row_stud['student_id'];
					}
				}
			}*/
			return $stud_count;		
		}
	}	

	function getBibliographyTitle($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT * FROM ob_biblio WHERE bibid=" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['title'];
		}
	}

	function getCurriculumByCurriculumSubjectId($subject_id){
		if(isset($subject_id) && $subject_id != ''){
			$sql = "SELECT curriculum_id FROM tbl_curriculum_subject WHERE id=" .$subject_id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['curriculum_id'];
		}
	}	

	function getSchedDays($id){
		if(isset($id)){
			$sql = "SELECT TRIM(CONCAT(IF(monday='Y','M',''), ' ', 
										IF(tuesday='Y','T',''), ' ', 
										IF(wednesday='Y','W',''), ' ', 
										IF(thursday='Y','Th',''),' ', 
										IF(friday='Y','F',''), ' ', 
										IF(saturday='Y','S',''), ' ', 
										IF(sunday='Y','Sun',''))) 
					AS days FROM tbl_schedule WHERE subject_id =" .$id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['days'];
		}
	}

	function getYearLevel($year_level){ 		
		if($year_level == '1'){
			return "First Year";
		}else if($year_level == '2'){
			return "Second Year";
		}else if($year_level == '3'){
			return "Third Year";
		}else if($year_level == '4'){
			return "Fourth Year";
		}else if($year_level == '5'){
			return "Fifth Year";
		}
	} 

	function getSemesterInWord($term){ 
		if(isset($term) && $term != ''){
			if($term == 1) 		$school_term =  'First Term';
			else if($term == 2) $school_term =  'Second Term';
			else if($term == 3) $school_term =  'Third Term';
			else if($term == 4) $school_term =  'Fourth Term';	

			return $school_term;
		}
	}

	function getSemesterByWord($term){ 
		if(isset($term) && $term != ''){
			$sql = "SELECT * FROM tbl_school_year_term WHERE id=".$term;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);

			if($row['school_term'] == 'First Term'||$row['school_term'] == '1st Term') 		$school_term =  1;
			else if($row['school_term'] == 'Second Term'||$row['school_term'] == '2nd Term') $school_term =  2;
			else if($row['school_term'] == 'Third Term'||$row['school_term'] == '3rd Term') $school_term =  3;
			else if($row['school_term'] == 'Fourth Term'||$row['school_term'] == '4th Term') $school_term =  4;	

			return $school_term;
		}
	}

	function getSchoolYr($id){
		if(isset($id)){
			$idss=explode(",", $id);

			foreach($idss as $ids){
				list($cnt, $cnt2) = explode("-", $ids);	

				if($cnt == 1){
					$yrname = "1st Year";
				}else if($cnt == 2){
					$yrname = "2nd Year";
				}else if($cnt == 3){
					$yrname = "3rd Year";
				}else if($cnt == 4){
					$yrname = "4th Year";
				}else if($cnt == 5){
					$yrname = "5th Year";
				} 

				if($cnt2 == 1){
					$yrpername = "1st Period";
				}else if($cnt2 == 2){
					$yrpername = "2nd Period";
				}else if($cnt2 == 3){
					$yrpername = "3rd Period";
				}else if($cnt2 == 4){
					$yrpername = "4th Period";
				}else if($cnt2 == 5){
					$yrpername = "5th Period";
				}
			}
			return $yrname." ".$yrpername;
		}
	}

	function getFeeTypeName($fee_type){
		if(isset($fee_type) && $fee_type!= ''){

			if($fee_type=='perunitlec') $fee_title = "Per Unit(Lec)";
			else if($fee_type=='perunitlab') $fee_title = "Per Unit(Lab)";
			else if($fee_type=='room') $fee_title = "Room Additional Fee";
			else if($fee_type=='subject') $fee_title = "Subject Additional Fee";
			else if($fee_type=='mc') $fee_title = "Miscellaneous Fee"; 	

			return $fee_title;
		}
	}

	function getFeeTypeId($fee_type,$term=''){
		$term!=''?$term=$term:$term=CURRENT_TERM_ID;
		if(isset($fee_type) && $fee_type != ''){
			$sql = "SELECT id FROM tbl_school_fee WHERE 
					fee_type = '$fee_type' AND 
					term_id=".$term;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['id'];
		}
	}

	function getFeeName($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT fee_name FROM tbl_school_fee WHERE id = ". $id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['fee_name'];
		}
	}

	function getFeeType($fee_type){
		if(isset($fee_type) && $fee_type != ''){
			$sql = "SELECT fee_type FROM tbl_school_fee WHERE 
					fee_type = '$fee_type' AND
					school_year_id = ".CURRENT_SY_ID." AND 
					term_id=".CURRENT_TERM_ID;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['fee_type'];
		}
	}

	function getFeeAmount($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT amount FROM tbl_school_fee WHERE 
					id = ".$id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['amount'];
		}
	}

	function getFeeAmountMC($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT amount FROM tbl_school_fee WHERE 
					id = ".$id." AND
					school_year_id = ".CURRENT_SY_ID." AND 
					term_id=".CURRENT_TERM_ID;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['amount'];
		}
	}

	function getSchoolYearStudentStart($id){
		if(isset($id) && $id != ''){
			$sql = 'SELECT * FROM tbl_student_enrollment_status WHERE student_id='.$id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			
			if(mysql_num_rows($query)>0){
				return getSchoolYearStartEndByTerm($row['term_id']);
			}
		}
	}

	function getSchoolYearStartEnd($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT start_year,end_year FROM tbl_school_year WHERE id = $id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['start_year']."-".$row['end_year'];
		}
	}

	function getSchoolYearStartEndByTerm($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT start_year,end_year FROM tbl_school_year WHERE id = ".getSchoolYearIdByTermId($id);
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['start_year']."-".$row['end_year'];
		}
	}

	function getSchoolYearIdByTermId($term_id){
		if(isset($term_id) && $term_id != ''){
			$sql = "SELECT school_year_id FROM tbl_school_year_term WHERE id = $term_id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['school_year_id'];
		}
	}

	function getCarriedBalances($id,$term_id){
		$carry = array();
		if($id!=''){
			/*
			$term = getPreviousTermByYear($term_id);
			$sqlterm = "SELECT * FROM tbl_school_year_term";
			$queryterm = mysql_query($sqlterm);
			
			if(mysql_num_rows($queryterm)>0){
				while($rowterm = mysql_fetch_array($queryterm)){
					$sqlen = "SELECT enrollment_status FROM tbl_student_enrollment_status 
					WHERE student_id = $id AND term_id = " . $rowterm['id'];
					$queryen = mysql_query($sqlen);
					$rowen = @mysql_fetch_array($queryen);

					if($rowen['enrollment_status'] == 'E' && $rowterm['id']!=CURRENT_TERM_ID){				
						//TOTAL LEC PAYMENT
						$sql_lec = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlec' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$id." AND s.term_id =" .$rowterm['id'];
						$qry_lec = mysql_query($sql_lec);
						$row_lec = mysql_fetch_array($qry_lec);
						$sub_lec_total = 0;
						//$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$student_id);           
						$sub_lec_total = $row_lec['sum(s.quantity)']*$row_lec['amount'];

						//TOTAL LAB PAYMENT
						$sql_lab = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlab' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$id." AND s.term_id =" .$rowterm['id'];
						$qry_lab = mysql_query($sql_lab);
						$row_lab = mysql_fetch_array($qry_lab);
						$sub_lab_total = $row_lab['sum(s.quantity)']*$row_lab['amount'];
			
						//TOTAL MISC PAYMENT
						$sql_misc = "SELECT f.fee_type,s.*,sum(s.amount) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$id." AND s.term_id =" .$rowterm['id'];
						$qry_misc = mysql_query($sql_misc);
						$row_misc = mysql_fetch_array($qry_misc);
						$sub_mis_total = $row_misc['sum(s.amount)'];
			
						//TOTAL LEC AND LAB = LEC + LAB
						$sub_total =  $sub_lec_total + $sub_lab_total + $sub_mis_total;

						//TOTAL UNITS = TUITION FEE - TOTAL OTHER PAYMENT
						$total_lec_lab = $sub_total - $sub_mis_total;

						$sql_payment = "SELECT * FROM tbl_student_payment WHERE term_id = ".$rowterm['id']." AND student_id = ".$id;
						$query_payment = mysql_query ($sql_payment);

						while($row_pay = @mysql_fetch_array($query_payment)){
							if($row_pay['is_bounced'] != 'N' && $row_pay['is_refund'] != 'N'){
								$total_payment += $row_pay['amount']; 
							}
						}

						$surcharge = GetSchemeForSurcharge($id)*getEnrolledTotalUnits($rowterm['id'],$id);
				
						$sqldis = 'SELECT * FROM tbl_student WHERE id='.$id;
						$querydis = mysql_query($sqldis);
						$rowdis = mysql_fetch_array($querydis);
				
						if($rowdis['scholarship_type']=='A'){
							$discount = ($sub_total+$surcharge)-5000;
							$discount = ($discount*$rowdis['scholarship'])/100;
						}else{
							$discount = $sub_lec_total+$surcharge;
							$discount = ($discount*$rowdis['scholarship'])/100;
						}

						$totalfee += ($sub_total+$surcharge) - $discount;
						//$totalfee = $totalfee - $total_payment;	
					}
					
					//print_r($carry);
				}
			}
		
			if($totalfee>$total_payment){
				return $totalfee - $total_payment;
			}else{
				return 0;	
			}
			
			*/
			return 0;
		}
	}

	function getCarriedDebits($id,$term_id){
		$carry = array();

		if(isset($id)){
			$term = getPreviousTermByYear($term_id);
			$sqlterm = "SELECT * FROM tbl_school_year_term";
			$queryterm = mysql_query($sqlterm);
			
			if(mysql_num_rows($queryterm)>0){
				while($rowterm = mysql_fetch_array($queryterm)){
					$sqlen = "SELECT enrollment_status FROM tbl_student_enrollment_status 
							WHERE student_id = $id AND term_id = " . $rowterm['id'];
					$queryen = mysql_query($sqlen);
					$rowen = @mysql_fetch_array($queryen);

					if($rowen['enrollment_status'] == 'E'){

						/* TOTAL LEC PAYMENT */
						$sql_lec = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlec' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$id." AND s.term_id =" .$rowterm['id'];
						$qry_lec = mysql_query($sql_lec);
						$row_lec = mysql_fetch_array($qry_lec);
						$sub_lec_total = 0;

						//$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$student_id);           
						$sub_lec_total = $row_lec['sum(s.quantity)']*$row_lec['amount'];

						/* TOTAL LAB PAYMENT */
						$sql_lab = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlab' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$id." AND s.term_id =" .$rowterm['id'];
						$qry_lab = mysql_query($sql_lab);
						$row_lab = mysql_fetch_array($qry_lab);
						$sub_lab_total = $row_lab['sum(s.quantity)']*$row_lab['amount'];
						
						/* TOTAL MISC PAYMENT */
						$sql_misc = "SELECT f.fee_type,s.*,sum(s.amount) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$id." AND s.term_id =" .$rowterm['id'];
						$qry_misc = mysql_query($sql_misc);
						$row_misc = mysql_fetch_array($qry_misc);
						$sub_mis_total = $row_misc['sum(s.amount)'];
			
						/*TOTAL LEC AND LAB = LEC + LAB*/
						$sub_total =  $sub_lec_total + $sub_lab_total + $sub_mis_total;

						/*TOTAL UNITS = TUITION FEE - TOTAL OTHER PAYMENT*/
						$total_lec_lab = $sub_total - $sub_mis_total;
						$sql_payment = "SELECT * FROM tbl_student_payment WHERE term_id = ".$rowterm['id']." AND student_id = ".$id;
						$query_payment = mysql_query ($sql_payment);

						while($row_pay = @mysql_fetch_array($query_payment)){
							if($row_pay['is_bounced'] != 'N' && $row_pay['is_refund'] != 'N'){
								$total_payment += $row_pay['amount']; 
							}
							
							if($row_pay['is_refund'] != 'N'){
								$total_ref += $row_pay['amount']; 
							}
						}
						
						$surcharge = GetSchemeForSurcharge($id)*getEnrolledTotalUnits($rowterm['id'],$id);
			
						$sqldis = 'SELECT * FROM tbl_student WHERE id='.$id;
						$querydis = mysql_query($sqldis);
						$rowdis = mysql_fetch_array($querydis);
			
						if($rowdis['scholarship_type']=='A'){
							$discount = ($sub_total+$surcharge)-5000;
							$discount = ($discount*$rowdis['scholarship'])/100;
						}else{
							$discount = $sub_lec_total+$surcharge;
							$discount = ($discount*$rowdis['scholarship'])/100;
						}
						$totalfee += ($sub_total+$surcharge) - $discount;
						//$totalfee = $totalfee-$total_payment;	
					}
				}
			}

			//print_r($carry);
			if($total_payment>$totalfee){
				return ($total_payment-$totalfee)+$total_ref;
			}else if($total_ref > 0){
				return $total_ref;	
			}else{
				return 0;
			}
		}
	}

	function getPreviousTerm($id){
		$sql = "SELECT * FROM tbl_school_year_term";
		$query = mysql_query($sql);
		$fin = 'false';

		while($row = mysql_fetch_array($query)){
			if($row['id']==CURRENT_TERM_ID){
				$fin = 'true';
			}

			if($fin != 'true'){
				$prev_term = $row['id'];
			}
		}
		return $prev_term;
	}

	function getPreviousTermByYear($id){
		$sql = "SELECT * FROM tbl_school_year_term";
		$query = mysql_query($sql);
		$fin = 'false';

		while($row = mysql_fetch_array($query)){
			if($row['id']==$id){
				$fin = 'true';
			}

			if($fin != 'true'){
				$prev_term = $row['id'];
			}
		}
		return $prev_term;
	}

	function getStudentCurriculumID($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT curriculum_id FROM tbl_student WHERE id = $id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['curriculum_id'];
		}
	}

	function getStudentNextSemInCurriculum($id){
		if(isset($id)){
			$sql = "SELECT count(id) as ctr_lvl
					FROM tbl_student_enrollment_status 
					WHERE student_id = $id AND enrollment_status = 'E'";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			$cnt = $row['ctr_lvl'];
		
			if($cnt==1){
				return 2;
			}else{
				return $cnt%getNumberOfTerm()==0?'1':'2';
			}
		}
	}

	function getCourseName($id){
		if(isset($id)){
			$sql = "SELECT course_name FROM tbl_course WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['course_name'];
		}
	}

	function getCourseCode($id){
		if(isset($id)){
			$sql = "SELECT course_code FROM tbl_course WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['course_code'];
		}
	}

	function getSectionNo($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_schedule WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['section_no'];
		}
	}

	function getScheduleDays($id){
		if(isset($id) && $id != ''){
			$arr_str = array();
			$sql = "SELECT * FROM tbl_schedule WHERE id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			
			if($row['monday']=='Y'){
				$arr_str[] = 'M('.getScheduleTimeConverted($row['monday_time_from'],$row['monday_time_to']).')';
			
			}else if($row['tuesday']=='Y'){
				$arr_str[] = 'T('.getScheduleTimeConverted($row['tuesday_time_from'],$row['tuesday_time_to']).')';
			
			}else if($row['wednesday']=='Y'){
				$arr_str[] = 'W('.getScheduleTimeConverted($row['wednesday_time_from'],$row['wednesday_time_to']).')';
			
			}else if($row['thursday']=='Y'){
				$arr_str[] = 'Th('.getScheduleTimeConverted($row['thursday_time_from'],$row['thursday_time_to']).')';
			
			}else if($row['friday']=='Y'){
				$arr_str[] = 'F('.getScheduleTimeConverted($row['friday_time_from'],$row['friday_time_to']).')';
			
			}else if($row['saturday']== 'Y'){
				$arr_str[] = 'S('.getScheduleTimeConverted($row['saturday_time_from'],$row['saturday_time_to']).')';
			
			}else if($row['sunday']=='Y'){
				$arr_str[] = 'Su('.getScheduleTimeConverted($row['sunday_time_from'],$row['sunday_time_to']).')';
			}
		
			if(count($arr_str)>0){
				return implode(' ', $arr_str);
			}else{
				return '-';	
			}
		}
	}

	function getSepScheduleDays($id){
		if(isset($id) && $id != ''){
			$arr_str = array();
			$sql = "SELECT * FROM tbl_schedule WHERE id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			$row['monday']	 	== 'Y'?$arr_str[] = 'M-'.$row['monday_time_from'].'-'.$row['monday_time_to'] :'';
			$row['tuesday'] 	== 'Y'?$arr_str[] = 'T-'.$row['tuesday_time_from'].'-'.$row['tuesday_time_to'] :'';
			$row['wednesday'] 	== 'Y'?$arr_str[] = 'W-'.$row['wednesday_time_from'].'-'.$row['wednesday_time_to'] :'';
			$row['thursday'] 	== 'Y'?$arr_str[] = 'Th-'.$row['thursday_time_from'].'-'.$row['thursday_time_to'] :'';
			$row['friday'] 		== 'Y'?$arr_str[] = 'F-'.$row['friday_time_from'].'-'.$row['friday_time_to'] :'';
			$row['saturday'] 	== 'Y'?$arr_str[] = 'S-'.$row['saturday_time_from'].'-'.$row['saturday_time_to'] :'';
			$row['sunday'] 		== 'Y'?$arr_str[] = 'Su-'.$row['sunday_time_from'].'-'.$row['sunday_time_to'] :'';

			return implode('/', $arr_str);
		}
	}

	function getSepScheduleTempDays($id){
		if(isset($id) && $id != ''){
			$arr_str = array();
			$sql = "SELECT * FROM tbl_schedule_template_subjects WHERE id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			$row['monday']	 	== 'Y'?$arr_str[] = 'M-'.$row['monday_time_from'].'-'.$row['monday_time_to'] :'';
			$row['tuesday'] 	== 'Y'?$arr_str[] = 'T-'.$row['tuesday_time_from'].'-'.$row['tuesday_time_to'] :'';
			$row['wednesday'] 	== 'Y'?$arr_str[] = 'W-'.$row['wednesday_time_from'].'-'.$row['wednesday_time_to'] :'';
			$row['thursday'] 	== 'Y'?$arr_str[] = 'Th-'.$row['thursday_time_from'].'-'.$row['thursday_time_to'] :'';
			$row['friday'] 		== 'Y'?$arr_str[] = 'F-'.$row['friday_time_from'].'-'.$row['friday_time_to'] :'';
			$row['saturday'] 	== 'Y'?$arr_str[] = 'S-'.$row['saturday_time_from'].'-'.$row['saturday_time_to'] :'';
			$row['sunday'] 		== 'Y'?$arr_str[] = 'Su-'.$row['sunday_time_from'].'-'.$row['sunday_time_to'] :'';

			return implode('/', $arr_str);
		}
	}

	function getSepScheduleTimeFr($id){
		if(isset($id) && $id != ''){
			$arr_str = array();
			$sql = "SELECT * FROM tbl_schedule WHERE id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			$row['monday']	 	== 'Y'?$arr_str[0] = $row['monday_time_from']:'';
			$row['tuesday'] 	== 'Y'?$arr_str[1] = $row['tuesday_time_from']:'';
			$row['wednesday'] 	== 'Y'?$arr_str[2] = $row['wednesday_time_from']:'';
			$row['thursday'] 	== 'Y'?$arr_str[3] = $row['thursday_time_from']:'';
			$row['friday'] 		== 'Y'?$arr_str[4] = $row['friday_time_from']:'';
			$row['saturday'] 	== 'Y'?$arr_str[5] = $row['saturday_time_from']:'';
			$row['sunday'] 		== 'Y'?$arr_str[6] = $row['sunday_time_from']:'';

			return implode(' ', $arr_str);
		}
	}

	function getSepScheduleTimeTo($id){
		if(isset($id) && $id != ''){
			$arr_str = array();
			$sql = "SELECT * FROM tbl_schedule WHERE id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			$row['monday']	 	== 'Y'?$arr_str[0] = $row['monday_time_to']:'';
			$row['tuesday'] 	== 'Y'?$arr_str[1] = $row['tuesday_time_to']:'';
			$row['wednesday'] 	== 'Y'?$arr_str[2] = $row['wednesday_time_to']:'';
			$row['thursday'] 	== 'Y'?$arr_str[3] = $row['thursday_time_to']:'';
			$row['friday'] 		== 'Y'?$arr_str[4] = $row['friday_time_to']:'';
			$row['saturday'] 	== 'Y'?$arr_str[5] = $row['saturday_time_to']:'';
			$row['sunday'] 		== 'Y'?$arr_str[6] = $row['sunday_time_to']:'';

			return implode(' ', $arr_str);
		}
	}

	function getScheduleDaysTemplate($id){
		if(isset($id) && $id != ''){
			$arr_str = array();
			$sql = "SELECT * FROM tbl_schedule_template_subjects WHERE id =" .$id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			$row['monday']	 	== 'Y'?$arr_str[] = 'M('.$row['monday_time_from'].'-'.$row['monday_time_to'].')' :'';
			$row['tuesday'] 	== 'Y'?$arr_str[] = 'T('.$row['tuesday_time_from'].'-'.$row['tuesday_time_to'].')' :'';
			$row['wednesday'] 	== 'Y'?$arr_str[] = 'W('.$row['wednesday_time_from'].'-'.$row['wednesday_time_to'].')' :'';
			$row['thursday'] 	== 'Y'?$arr_str[] = 'Th('.$row['thursday_time_from'].'-'.$row['thursday_time_to'].')' :'';
			$row['friday'] 		== 'Y'?$arr_str[] = 'F('.$row['friday_time_from'].'-'.$row['friday_time_to'].')' :'';
			$row['saturday'] 	== 'Y'?$arr_str[] = 'S('.$row['saturday_time_from'].'-'.$row['saturday_time_to'].')' :'';
			$row['sunday'] 		== 'Y'?$arr_str[] = 'Su('.$row['sunday_time_from'].'-'.$row['sunday_time_to'].')' :'';

			return implode(' ', $arr_str);
		}
	}

	function getScheduleTime($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT * FROM tbl_schedule WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['time_from'].'-'.$row['time_to'];
		}
	}

	function getScheduleTimeConverted($time_fr,$time_to){
		if($time_fr!='' && $time_to!=''){
			$ts = explode(':',$time_fr);
			$t1 = $ts[0]>12?$ts[0]-12:$ts[0];
			$ts2 = explode(':',$time_to);
			$t2 = $ts2[0]>12?$ts2[0]-12:$ts2[0];
			return $t1.':'.$ts[1].' - '.$t2.':'.$ts2[1];
		}
	}

	function getSubjectIdByCurriculumSubjectId($curriculum_subject_id){
		if(isset($curriculum_subject_id) && $curriculum_subject_id != ''){
			$sql = "SELECT * FROM tbl_curriculum_subject WHERE id = $curriculum_subject_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['subject_id'];
		}
	}

	function getPrereqOfSubject($curriculum_subject_id){
		if(isset($curriculum_subject_id) && $curriculum_subject_id != ''){
			$arr_prereq = array();
			$sql = "SELECT * FROM tbl_curriculum_subject_prereq WHERE curriculum_subject_id = $curriculum_subject_id";
			$query = mysql_query($sql);		

			while ($row = mysql_fetch_array($query)){
				$arr_prereq[] = getSubjCode($row['prereq_subject_id']);
			}
			return count($arr_prereq) > 0? implode(',',$arr_prereq):'';
		}
	}

	function getArrPrereqByCurriculumSubjectId($curriculum_subject_id){
		if(isset($curriculum_subject_id) && $curriculum_subject_id != ''){
			$arr_prereq = array();
			$sql = "SELECT * FROM tbl_curriculum_subject_prereq WHERE curriculum_subject_id = $curriculum_subject_id";
			$query = mysql_query($sql);		

			while ($row = mysql_fetch_array($query)){
				$arr_prereq[] = $row['prereq_subject_id'];
			}
			return $arr_prereq;
		}
	}

	function checkIfStudentIsCredited($id){
		if(isset($id) && $id != ''){
			$arr_prereq = array();
			$sql = "SELECT * FROM tbl_credited_student WHERE student_id = $id";
			$query = mysql_query($sql);		
			
			if(@mysql_num_rows($query)>0){
				return true;
			}else{
				return false;
			}
		}
	}	

	function checkIfSubjectHasPrereq($curriculum_subject_id){
		if(isset($curriculum_subject_id) && $curriculum_subject_id != ''){
			$arr_prereq = array();
			$sql = "SELECT * FROM tbl_curriculum_subject_prereq WHERE curriculum_subject_id = $curriculum_subject_id";
			$query = mysql_query($sql);		
			$ctr = mysql_num_rows($query);
			return $ctr;
		}
	}

	function checkSubjectPrereqIfPass($student_id,$curriculum_subject_id){
		$arr_prereq = getArrPrereqByCurriculumSubjectId($curriculum_subject_id);

		foreach( $arr_prereq as $prereq){
			$sql_finish = "SELECT * FROM 
								tbl_student_final_grade 
							WHERE 
								student_id = $student_id AND 
								remarks='P' AND subject_id = ".$prereq;
			$query_finish = mysql_query($sql_finish);	
			$ctr_finish = mysql_num_rows($query_finish);

			if($ctr_finish == 0){
				//return false;
				//echo 'this is me';
			}
		}
		return true;			
	}

	function getLastTerm($id){
		if(isset($id)){
			$sqls = "SELECT * FROM tbl_student_schedule WHERE student_id = ".$id." ORDER BY term_id";
			$querys = mysql_query($sqls);
			while($rows=mysql_fetch_array($querys)){
				$last = $row['term_id'];
			}

			$sql = "SELECT * FROM tbl_school_year_period WHERE id=".$last." ORDER BY period_order" ;
			$query = mysql_query($sql);
			while($row=mysql_fetch_array($query)){
				$last_m = $row['end_of_submission'];
			}

			$sql_last = "SELECT * FROM tbl_school_year_term a,tbl_school_year b WHERE a.school_year_id=b.id AND a.id = ".$last;
			$query_last = mysql_query($sql_last);
			$row_last = mysql_fetch_array($query_last);
			$mnth = explode('-',$last_m);

			return getMonth($mnth).' '.$row_last['end_year'];
		}
	}
	
	function checkIfSubjectIsFinished($student_id,$subject_id){
		if($student_id!=''){
			$sql_finish = "SELECT * FROM tbl_student_final_grade 
							WHERE student_id = $student_id AND subject_id = ".$subject_id;
			$query_finish = mysql_query($sql_finish);	
			$row_finish = @mysql_fetch_array($query_finish);	
			$ctr_finish = @mysql_num_rows($query_finish);	

			if($ctr_finish>0 && $row_finish['final_grade']=''){
				$sql = "SELECT * FROM tbl_student_grade WHERE subject_id = $subject_id AND student_id=$student_id";
				$query = mysql_query($sql);	
				$grade = 0;	
		
				while($row = @mysql_fetch_array($query)){
					if($row['is_altered'] == 'Y'){
						if($row['altered_grade'] == ''){
							$grade = decrypt($row['grade'])*1;
						}else{
							$grade = decrypt($row['altered_grade'])*1;
						}
					}else{
						$grade =  decrypt($row['grade'])*1;
					}
	
					$final_grade +=  ($grade * (getPeriodPercentage($row['period_id']) / 100));
					$grade = 0;	
					
					if($grade<50){
						return false;	
					}else{
						return true;	
					}
				}
			}else if($row_finish['final_grade']<50){
				return false;
			}else{
				return true;	
			}
		}
	}

	function getStudentSubjectForEnrollmentInArr($student_id){
		$arr_subject = array();
		$sql = "SELECT sub.*,cur.id as curriculum_subject_id
				FROM tbl_curriculum_subject cur LEFT JOIN tbl_subject sub ON cur.subject_id = sub.id
				WHERE curriculum_id = " .getStudentCurriculumID($student_id)." ORDER BY sub.subject_name ASC";

		//subject_category <>'EO' AND
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
			$sql_finish = "SELECT * FROM tbl_student_final_grade 
							WHERE 
								student_id = $student_id AND 
								remarks='P' AND subject_id = ".$row ['id'];
			$query_finish = mysql_query($sql_finish);	
			$row_finish = @mysql_fetch_array($query_finish);	
			$ctr_finish = @mysql_num_rows($query_finish);								

			/*DISABLE IF NEEDED
			if(checkIfSubjectHasPrereq($row['curriculum_subject_id']) == 0 && $ctr_finish == 0 ){
				$arr_subject[] = $row['id'];
			}

			if((checkIfSubjectHasPrereq($row['curriculum_subject_id']) != 0 && checkSubjectPrereqIfPass($student_id,$row['curriculum_subject_id']) === true) && $ctr_finish == 0 ){
			 	$arr_subject[] = $row['id'];
			}*/
			
			$sqlsched = "SELECT * FROM tbl_schedule WHERE subject_id = ".$row ['id']." AND term_id=".CURRENT_TERM_ID;
			
			$querysched = mysql_query($sqlsched);			
			$sched = @mysql_num_rows($querysched);
			
			if($ctr_finish == 0 && $sched > 0){
			 	$arr_subject[] = $row['id'];
			}

			//$arr_subject[] = $row['id'];
		}
		return $arr_subject;
	}

	function setStudentSubjectForEnrollmentInArr($student_id,$term_id){
		$arr_subject = array();
		$sql = "SELECT sub.*,cur.id as curriculum_subject_id
				FROM tbl_curriculum_subject cur LEFT JOIN tbl_subject sub ON cur.subject_id = sub.id
				WHERE
					subject_category <>'EO' AND
					curriculum_id = " .getStudentCurriculumID($student_id);
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
			$sql_finish = "SELECT * FROM tbl_student_final_grade 
							WHERE 
								student_id = $student_id AND 
								remarks='P' AND subject_id = ".$row ['id'];
			$query_finish = mysql_query($sql_finish);	
			$row_finish = @mysql_fetch_array($query_finish);	
			$ctr_finish = @mysql_num_rows($query_finish);								

			/*DISABLE IF NEEDED
			if(checkIfSubjectHasPrereq($row['curriculum_subject_id']) == 0 && $ctr_finish == 0 ){
				$arr_subject[] = $row['id'];
			}

			if((checkIfSubjectHasPrereq($row['curriculum_subject_id']) != 0 && checkSubjectPrereqIfPass($student_id,$row['curriculum_subject_id']) === true) && $ctr_finish == 0 ){
			 	$arr_subject[] = $row['id'];
			}*/
			
			$sqlsched = "SELECT * FROM tbl_schedule WHERE subject_id = ".$row ['id']." AND term_id=".CURRENT_TERM_ID;
			$querysched = mysql_query($sqlsched);
			$sched = @mysql_num_rows($querysched);
			
			if($ctr_finish == 0 && $sched > 0){
				$arr_subject[] = $row['id'];
			}
			//$arr_subject[] = $row['id'];
		}
		return $arr_subject;
	}

	//---------------

	function getCoreqOfSubject($curriculum_subject_id){
		if(isset($curriculum_subject_id) && $curriculum_subject_id != ''){
			$arr_coreq = array();
			$sql = "SELECT * FROM tbl_curriculum_subject_coreq WHERE curriculum_subject_id = $curriculum_subject_id";
			$query = mysql_query($sql);		

			while ($row = mysql_fetch_array($query)){
				$arr_coreq[] = getSubjCode($row['coreq_subject_id']);
			}
			return count($arr_coreq) > 0? implode(',',$arr_coreq):'';
		}
	}

	function getArrCoreqByCurriculumSubjectId($curriculum_subject_id){
		if(isset($curriculum_subject_id) && $curriculum_subject_id != ''){
			$arr_coreq = array();
			$sql = "SELECT * FROM tbl_curriculum_subject_coreq WHERE curriculum_subject_id = $curriculum_subject_id";
			$query = mysql_query($sql);		

			while ($row = mysql_fetch_array($query)){
				$arr_coreq[] = $row['coreq_subject_id'];
			}
			return $arr_coreq;
		}
	}

	function checkIfSubjectHasCoreq($curriculum_subject_id){
		if(isset($curriculum_subject_id) && $curriculum_subject_id != ''){
			$arr_prereq = array();
			$sql = "SELECT * FROM tbl_curriculum_subject_coreq WHERE curriculum_subject_id = $curriculum_subject_id";
			$query = mysql_query($sql);		
			$ctr = mysql_num_rows($query);
			return $ctr;
		}
	}	

	function checkIfSubjectIsCoreq($subject_id){
		if(isset($subject_id) && $subject_id != ''){
			$arr_coreq = array();
			$sql = "SELECT * FROM tbl_curriculum_subject_coreq WHERE coreq_subject_id = $subject_id";
			$query = mysql_query($sql);		

			if(mysql_num_rows($query)>0){
				while($ctr = mysql_fetch_array($query)){
					$arr_coreq[] = $ctr['curriculum_subject_id'];
				}
				return $arr_coreq;
			}else{
				return '';
			}
		}
	}	

	function checkSubjectCoreqIfPass($student_id,$curriculum_subject_id){
		$arr_coreq = getArrCoreqByCurriculumSubjectId($curriculum_subject_id);

		foreach( $arr_coreq as $coreq){
			$sql_finish = "SELECT * FROM tbl_student_final_grade 
							WHERE 
								student_id = $student_id AND 
								remarks='P' AND subject_id = ".$coreq;
			$query_finish = mysql_query($sql_finish);	
			$ctr_finish = mysql_num_rows($query_finish);

			if($ctr_finish == 0){
				return false;
			}
		}
		return true;			
	}

	//---------------

	function getBlockSubjetcsSchedule($id){
		if(isset($id) && $id != ''){
			$arr_block = array();
			$sql = "SELECT * FROM tbl_block_subject WHERE block_section_id = $id";
			$query = mysql_query($sql);		

			while ($row = mysql_fetch_array($query)){
				$sqls = "SELECT * FROM tbl_schedule WHERE id = ".$row['schedule_id'];
				$querys = mysql_query($sqls);
				$rows = mysql_fetch_array($querys);
				$arr_block[] = $rows['subject_id'];
			}
			return $arr_block;
		}
	}

	function checkIfstudentFinishedSubject($student_id,$subject_id,$cur_sub){
		$arr_subject = array();
		$sql_finish = "SELECT * FROM tbl_student_final_grade 
						WHERE 
							student_id = $student_id AND 
							remarks='P' AND subject_id = ".$subject_id;
		$query_finish = mysql_query($sql_finish);	
		$row_finish = mysql_fetch_array($query_finish);	
		$ctr_finish = mysql_num_rows($query_finish);								

		if($ctr_finish == 0 ){
			$arr_subject[] = $row['id'];
		}

		if(checkIfSubjectHasPrereq($cur_sub) != 0 && checkSubjectPrereqIfPass($student_id,$cur_sub) === false){
		 	$arr_subject[] = $row['id'];
		}

		if(count($arr_subject) > 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkIfstudentFailedSubject($student_id,$subject_id){
		$arr_pass = 0;
		$sql_finish = "SELECT * FROM tbl_student_final_grade 
					WHERE 
						student_id = $student_id 
						AND subject_id = ".$subject_id;
		$query_finish = mysql_query($sql_finish);	

		while($row_finish = mysql_fetch_array($query_finish)){
			if(checkIfGradeIsPass(decrypt($row_finish['final_grade']))==='N'){
				$arr_pass++;
			}
		}								

		if($arr_pass > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkStudentEnrolledSubjectBlock($b_section,$student_id){
		//validate if student finished and passed subject
		$sub_arr = array();
		$pass_arr = array();

		$sql = "SELECT b.*,c.id as cur_sub_id FROM tbl_block_subject a 
				LEFT JOIN tbl_schedule b ON a.schedule_id = b.id 
				LEFT JOIN tbl_curriculum_subject c ON b.subject_id=c.subject_id 
				WHERE a.block_section_id = ".$b_section." 
				AND c.curriculum_id = ".getStudentCurriculumID($student_id);
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
			$sql_finish = "SELECT * FROM tbl_student_final_grade 
							WHERE 
								student_id = $student_id AND 
								remarks='P' AND subject_id = ".$row ['subject_id'];
			$query_finish = mysql_query($sql_finish);	
			$ctr_finish = mysql_num_rows($query_finish);

			while($row_finish = mysql_fetch_array($query_finish)){								
				$sub_arr[] = $row_finish['subject_id'];
			}
		}

		if(count($sub_arr) > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkStudentUnenrollSubjectPreqBlock($b_section,$student_id){
		//validate if student has unfinish prereq
		$pass_arr = array();
		$sql = "SELECT b.*,c.id as cur_sub_id FROM tbl_block_subject a 
				LEFT JOIN tbl_schedule b ON a.schedule_id = b.id 
				LEFT JOIN tbl_curriculum_subject c ON b.subject_id=c.subject_id 
				WHERE a.block_section_id = ".$b_section." 
				AND c.curriculum_id = ".getStudentCurriculumID($student_id);
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
			if(checkIfSubjectHasPrereq($row['cur_sub_id']) > 0 && checkSubjectPrereqIfPass($student_id,$row['cur_sub_id']) === false){
				$pass_arr[] = $row['id'];
			}
		}

		if(count($pass_arr) > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkStudentCoReqSubjectInBlock($b_section,$student_id){
		//validate if student has unfinish coreq
		$pass_arr = array();

		$sql = "SELECT b.*,c.id as cur_sub_id FROM tbl_block_subject a 
				LEFT JOIN tbl_schedule b ON a.schedule_id = b.id 
				LEFT JOIN tbl_curriculum_subject c ON b.subject_id=c.subject_id 
				WHERE a.block_section_id = ".$b_section." 
				AND c.curriculum_id = ".getStudentCurriculumID($student_id);
		$query = mysql_query($sql);
		$subs = getBlockSubjetcsSchedule($b_section);

		while($row = mysql_fetch_array($query)){
			if(checkIfSubjectHasCoreq($row['cur_sub_id']) > 0 && checkSubjectCoreqIfPass($student_id,$row['cur_sub_id']) === false){
				$coreq = getArrCoreqByCurriculumSubjectId($row['cur_sub_id']);

				foreach($coreq as $co){
					if(!in_array($co,$subs)){
						$pass_arr[] = $co;
					}
				}
			}else if(checkIfSubjectIsCoreq($row['cur_sub_id']) > 0){				
				foreach(checkIfSubjectIsCoreq($row['cur_sub_id']) as $cor){
					$sqls = "SELECT * FROM tbl_schedule WHERE subject_id=".$cor;
					$querys = mysql_query($sqls);
					$rows = mysql_fetch_array($querys);
					
					if(!in_array($cor,$subs)){
						$pass_arr[] = $cor;
					}
				}
			}
		}

		if(count($pass_arr) > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkSlotAvailableInBlock($b_section,$student_id){
		//computeAllSlotByStudentSched(CURRENT_TERM_ID);
		$slt_arr = array();
		$sql = "SELECT b.*,c.id as cur_sub_id FROM tbl_block_subject a 
				LEFT JOIN tbl_schedule b ON a.schedule_id = b.id 
				LEFT JOIN tbl_curriculum_subject c ON b.subject_id=c.subject_id 
				WHERE a.block_section_id = ".$b_section." 
				AND c.curriculum_id = ".getStudentCurriculumID($student_id);
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
			$avail = $row['number_of_student']-$row['number_of_reserved'];
			if($avail <= 0){
				$slt_arr[] = $row['id'];
			}
		}

		if(count($slt_arr) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function checkSlotAvailableInBlock2($b_section,$student_id){
		//computeAllSlotByStudentSched(CURRENT_TERM_ID);
		$slt_arr = array();
		$sql = "SELECT b.* FROM tbl_block_subject a 
				LEFT JOIN tbl_schedule b ON a.schedule_id = b.id 
				WHERE a.block_section_id = ".$b_section;
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
			$avail = $row['number_of_student']-$row['number_of_reserved'];

			if($avail <= 0){
				$slt_arr[] = $row['id'];
			}
		}

		if(count($slt_arr) > 0){
			return true;
		}else{
			return false;
		}
	}
	
	function checkSlotAvailableBySched($section,$id,$term){
		$slt_arr = array();
		$sql = "SELECT * FROM tbl_schedule 
				WHERE id = ".$section." 
				AND subject_id = ".$id." AND term_id=".$term;
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$avail = $row['number_of_student']-$row['number_of_reserved'];

		if($avail > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkEmptyGrades($id){
		$sql_grade = "SELECT student_id,schedule_id FROM tbl_student_schedule 
					WHERE term_id = ".CURRENT_TERM_ID." AND schedule_id = ".$id;						
        $query_grade = mysql_query($sql_grade);
		$arr = array();

		while($row_grade = mysql_fetch_array($query_grade)){
			$sql_period = "SELECT * FROM tbl_school_year_period WHERE 
							term_id=".CURRENT_TERM_ID." AND 
							start_of_submission < '" .  date("Y-m-d") . "'
							ORDER BY period_order";						
			$query_period = mysql_query($sql_period);

			while($row_period = mysql_fetch_array($query_period)){
				$sql = "SELECT * FROM tbl_student_grade 
						WHERE student_id = ".$row_grade["student_id"]." AND
							schedule_id = ".$row_grade["schedule_id"]." AND 
							period_id = ".$row_period["id"]." AND term_id = ".CURRENT_TERM_ID;
				$query = mysql_query($sql);

				if(mysql_num_rows($query) == 0){
					$arr[] = $row_grade['schedule_id'];
				}
			}
		}

		if(in_array($id,$arr)){
			return true;
		}else{
			return false;
		}
	}
	
	function checkIfSubjectINCByTerm($id,$sched,$term){
		if(isset($id)){
			$cnt = 0;
			$cnt0 = 0;
			$sql_period = "SELECT * FROM tbl_school_year_period WHERE term_id=".$term." ORDER BY period_order"; 
			$query_period = mysql_query($sql_period);
			$cntP = mysql_num_rows($query_period);
			
			while($row_period = mysql_fetch_array($query_period)){
				$sql = "SELECT * FROM tbl_student_grade WHERE schedule_id = ".$sched." AND  period_id = ".$row_period['id']." AND student_id=".$id;
				$query = mysql_query($sql);		
				$row = mysql_fetch_array($query);

				if($row['is_altered'] == 'Y'){
					if($row['altered_grade'] != ''){
					$cnt++;
					}else{
						$cnt0++;
					}
				}else{
					if($row['grade'] != ''){
						$cnt++;
					}else{
						$cnt0++;
					}
				}		
			}
			
			if($cnt0==$cntP){
				return false;
			}else if($cnt!=$cntP){
				return true;
			}else{
				return false;
			}

		}
	}
	
	function checkIfSubjectINCBySubj($id,$subj){
		if(isset($id)){
			$cnt = 0;
			$cnt0 = 0;
			
			//$sql_period = "SELECT * FROM tbl_school_year_period WHERE term_id=".CURRENT_TERM_ID." ORDER BY period_order"; 
			//$query_period = mysql_query($sql_period);
			
			$cntP = 3;//mysql_num_rows($query_period);
			$sql = "SELECT * FROM tbl_student_grade WHERE subject_id = ".$subj." AND student_id=".$id;
			$query = mysql_query($sql);		

			while($row = mysql_fetch_array($query)){
				if($row['is_altered'] == 'Y'){
					if($row['altered_grade'] > 0){
						$cnt++;
					}else{
						$cnt0++;
					}
				}else{
					if($row['grade'] > 0){
						$cnt++;
					}else{
						$cnt0++;
					}
				}
			}
			
			if($cntP==$cnt){
				return false;
			}/*else if($cnt!=$cnt0){
				return true;
			}else if($cnt0>0){
				return true;
			}*/else{
				return false;
			}
		}
	}
	
	function checkINCByTerm($id,$term,$subj){
		if(isset($id)){
			$cnt=0;
			$sql_period = "SELECT * FROM tbl_school_year_period WHERE term_id=15 ORDER BY period_order"; 
			$query_period = mysql_query($sql_period);
			$cntP = mysql_num_rows($query_period);
			
			while($row_period = mysql_fetch_array($query_period)){
				$sql = "SELECT * FROM tbl_student_grade WHERE subject_id = ".$subj." AND student_id=".$id." AND period_id=".$row_period['id'];
				$query = mysql_query($sql);	
				$row = mysql_fetch_array($query);
				
				if($row['is_altered'] == 'Y'){
					if(decrypt($row['altered_grade']) >0){
						$cnt++;
					}
				}else{
					if(decrypt($row['grade']) >0){
						$cnt++;
					}				
				}
			}
			//echo $cnt.'aaa'.$cntP;
			if($cnt >= $cntP){
				return false;
			}else{
				return true;
			}
		}
	}

	function checkIfSubjectDroppedByTerm($id,$sched,$term){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_student_schedule 
					 WHERE schedule_id = ".$sched." 
					 AND student_id = ".$id." AND term_id=".$term." AND enrollment_status='D' OR enrollment_status='DR'";
			$query = mysql_query($sql);

			if(mysql_num_rows($query) > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	function getSubjectDropped($id,$sched,$term){
		if(isset($id)){
			$sql = "SELECT enrollment_status FROM tbl_student_schedule 
					 WHERE schedule_id = ".$sched." 
					 AND student_id = ".$id." AND term_id=".$term;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['enrollment_status'];
		}
	}
	
	function checkIfSubjectDropped($id,$sched){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_student_schedule 
					 WHERE enrollment_status='D' AND schedule_id = ".$sched." 
					 AND student_id = ".$id." AND term_id=".CURRENT_TERM_ID;
			$query = mysql_query($sql);

			if(mysql_num_rows($query) > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfCanDropped($id){
		if(isset($id)){
			$sql = "SELECT start_of_submission FROM tbl_school_year_period 
					 WHERE term_id = ".$id." 
					 AND period_order=1 ";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);

			if(strtotime($row['start_of_submission']) > time()){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfSubjectCanDropped($id,$sched){
		if(isset($id)){
			$ast = 0;
			$sql = "SELECT * FROM tbl_student_grade 
					 WHERE schedule_id = ".$sched." 
					 AND student_id = ".$id." AND term_id=".CURRENT_TERM_ID;
			$query = mysql_query($sql);
			
			if(mysql_num_rows($query) > 0){
				while($row=mysql_fetch_array($query)){
					if(is_numeric($row['grade'])){
						$ast++;
					}else if(is_numeric($row['altered_grade'])){
						$ast++;
					}
				}

				if($ast > 0){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
	}

	function getStudentGradePerPeriod($sched_id,$id,$student_id,$is_altered='Y'){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_student_grade WHERE schedule_id = $sched_id AND  period_id = $id AND student_id=$student_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			if($row['is_altered'] == 'Y'){
				if($row['altered_grade'] == ''){
					//return substr(decrypt($row['grade']),0,4);
					return decrypt($row['grade'])*1;
				}else{
					//return  substr(decrypt($row['altered_grade']),0,4);
					return decrypt($row['altered_grade'])*1;
				}
			}else{
				//return substr(decrypt($row['grade']),0,4);
				return decrypt($row['grade'])*1;
			}		
		}
	}

	function getStudentGradeInGradeSheet($sched_id,$id,$student_id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_professor_gradesheet WHERE schedule_id = $sched_id AND  sheet_id = $id AND student_id=$student_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return decrypt($row['grade'])*1;
		}
	}	

	function checkTotalPeriodPerTerm($term_id){
		if(isset($term_id)){
			$sql = "SELECT * FROM tbl_school_year_term WHERE id=".$term_id;
			$query = mysql_query ($sql);
			$row = mysql_fetch_array($query);

			$sqly = "SELECT * FROM tbl_school_year WHERE id=".$row['school_year_id'];
			$queryy = mysql_query ($sqly);
			$rowy = mysql_fetch_array($queryy);
			$periods = $rowy['number_of_period'];

			$sqlper = "SELECT * FROM tbl_school_year_period where term_id = $term_id";
			$queryper = mysql_query ($sqlper);
			$count = mysql_num_rows($queryper);

			if($count > 0 && $count == $periods){
				return true;
			}else{
				return false;
			}
		}
	}

	function setAllNotCurrent($term){
		if(isset($term)){
			$sql = "UPDATE tbl_school_year_period SET is_current = 'N' WHERE term_id = $term";
			$query = mysql_query($sql);		
		}
	}

	function countPeriodPerTerm($term_id){
		$ctr = 0;

		if(isset($term_id) || $term_id != ''){
			$sql = "SELECT * FROM tbl_school_year_period WHERE term_id = $term_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			$ctr = mysql_num_rows($query);
			return $ctr;	
		}
	}

	function getPeriodName($id){
		if(isset($id) || $id != ''){
			$sql = "SELECT period_name FROM tbl_school_year_period WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['period_name'];
		}
	}

	function getPeriodPercentage($id){
		if(isset($id)){
			$sql = "SELECT percentage FROM tbl_school_year_period WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['percentage'];
		}
	}

	function getGradeSubmissionPerPeriod($period_id){
		if(isset($period_id) || $period_id != ''){
			$sql = "SELECT start_of_submission, end_of_submission FROM tbl_school_year_period 
					WHERE id = $period_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['start_of_submission'] . ' to ' . $row['end_of_submission'];
		}
	}

	function getSheetPercentage($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT percentage FROM tbl_gradesheet WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['percentage'];
		}
	}

	function computeStudentFinalGradePerPeriod($student_id,$schedule_id,$period_id){
		$final_grade = 0;
		if($student_id != '' && $schedule_id != '' && $period_id != ''){
			$sql = "SELECT * FROM tbl_professor_gradesheet 
					WHERE schedule_id = $schedule_id AND student_id = $student_id AND period_id = $period_id";
			$query = mysql_query($sql);	
			$grade = 0;	

			while($row = mysql_fetch_array($query)){
				$grade =  decrypt($row['grade']);
				$final_grade = $final_grade +  ($grade * (getSheetPercentage($row['sheet_id']) / 100));
				$grade = 0;		
			}	
		}
		return $final_grade*1;
	}

	function getStudentFinalGradePerPeriod($student_id,$schedule_id,$period_id){
		if($student_id != '' && $schedule_id != '' && $period_id != ''){
			$sql = "SELECT * FROM tbl_student_grade 
					WHERE schedule_id = $schedule_id AND 
						student_id=$student_id AND 
						period_id=$period_id";
			$query = mysql_query($sql);	
			$row = mysql_fetch_array($query);

			if($row['is_altered'] == 'Y'){
				return decrypt($row['altered_grade'])*1;
			}else{
				return decrypt($row['grade'])*1;
			}
		}
	}

	function getStudentNotAlteredFinalGradePerPeriod($student_id,$schedule_id,$period_id){
		if($student_id != '' && $schedule_id != '' && $period_id != ''){
			$sql = "SELECT * FROM tbl_student_grade 
					WHERE 
						schedule_id = $schedule_id AND 
						student_id=$student_id AND 
						period_id=$period_id";
			$query = mysql_query($sql);	
			$row = mysql_fetch_array($query);
			return decrypt($row['grade'])*1;
		}
	}

	function getStudentAlteredFinalGradePerPeriod($student_id,$schedule_id,$period_id){
		if($student_id != '' && $schedule_id != '' && $period_id != ''){
			$sql = "SELECT * FROM tbl_student_grade 
					WHERE 
						schedule_id = $schedule_id AND 
						student_id=$student_id AND 
						period_id=$period_id";
			$query = mysql_query($sql);	
			$row = mysql_fetch_array($query);
			return decrypt($row['altered_grade'])*1;
		}
	}

	function getStudentAverage($term,$id){
		$sql = "SELECT * FROM tbl_student_schedule
					WHERE term_id = ".$term." AND student_id = ".$id;
		$query = mysql_query($sql);
		
		$ave = 0;
		$cnt = 0;
		
		while($row = mysql_fetch_array($query)){
		
			//TEMPORARY PE ids
			if($row['schedule_id'] != 238 || $row['schedule_id'] != 247 || $row['schedule_id'] != 257 || $row['schedule_id'] != 266){
				$grade = getStudentFinalGrade($id,$row['schedule_id'],$term);
				if($grade!=''){
					$ave += $grade;
					$cnt++;
				}
			}
			
		}
		
		if($ave>0){
			$final_ave = $ave/$cnt;
		}
		
		if($final_ave>0){
			return $final_ave;
		}else{
			return 0;
		}
	}
	
	function getStudentIfDeansLister($term,$id){
		$sql = "SELECT * FROM tbl_student_schedule
					WHERE term_id = ".$term." AND student_id = ".$id;
		$query = mysql_query($sql);
		
		$ave = 0;
		$cnt = 0;
		$dean = 0;
		
		while($row = mysql_fetch_array($query)){
			//PE ids
			if($row['schedule_id'] != 238 || $row['schedule_id'] != 247 || $row['schedule_id'] != 257 || $row['schedule_id'] != 266){
				$ave += getStudentFinalGrade($id,$row['schedule_id'],$term);
				$cnt++;
			}
			
			if($ave >= 86){
				$dean++;
			}
		}
		
		if($cnt == $dean){
			if($ave > 0){
				$final_ave = $ave/$cnt;
			}
			
			if($final_ave >= 89){
				return $final_ave;
			}else{
				return 0;
			}
		}
	}

	function getStudentFinalGrade($student_id,$schedule_id,$term_id){
		$final_grade = 0;

		if($student_id != '' && $schedule_id != '' && $term_id != ''){
			$sql = "SELECT * FROM tbl_student_grade WHERE schedule_id = $schedule_id AND student_id=$student_id AND term_id=$term_id";
			$query = mysql_query($sql);	
			$grade = 0;	

			while($row = @mysql_fetch_array($query)){
				if($row['is_altered'] == 'Y'){
					if($row['altered_grade'] == ''){
						$grade = decrypt($row['grade'])*1;
					}else{
						$grade = decrypt($row['altered_grade'])*1;
					}
				}else{
					$grade =  decrypt($row['grade'])*1;
				}
				$final_grade +=  ($grade * (getPeriodPercentage($row['period_id']) / 100));
				$grade = 0;		
			}
		}
		
		if($final_grade > 100){
			return 100;
		}else if($final_grade < 40&&$final_grade>0){
			return 40;
		}else{
			return $final_grade*1;
		}
	}

	function getStudentFinalGrade2($student_id,$schedule_id,$term_id){
		$final_grade = 0;

		if($student_id != '' && $schedule_id != '' && $term_id != ''){
			$sql = "SELECT * FROM tbl_schedule WHERE id = ".$schedule_id;
			$query = mysql_query($sql);	
			$row = @mysql_fetch_array($query);
		
			$sql2 = "SELECT * FROM tbl_student_final_grade WHERE subject_id = ".$row['subject_id']." AND student_id=".$student_id." AND term_id=".$term_id;
			$query2 = mysql_query($sql2);
			$row2 = @mysql_fetch_array($query2);

			if(mysql_num_rows($query2)>0){
				if($row2['final_grade'] != ''){
						$grade = substr(decrypt($row2['final_grade']),0,4);
				}else{
					$grade = 0;
				}
			}else{
				$grade =  0;
			}
		}
		return $grade*1;
	}
	
	function getStudentFinalGradeBySubject($student_id,$sub){
		$final_grade = 0;

		if($student_id != '' && $sub != ''){
			$sql2 = "SELECT * FROM tbl_student_final_grade WHERE subject_id = ".$sub." AND student_id=".$student_id;
			$query2 = mysql_query($sql2);
			$row2 = @mysql_fetch_array($query2);

			if(mysql_num_rows($query2)>0){
				if($row2['final_grade'] != ''){
						$grade = decrypt($row2['final_grade'])*1;
				}else{
					$grade = 0;
				}
			}else{
				$grade =  0;
			}
		}
		return $grade;
	}
	
	function getStudentFinalGradeBySubject2($student_id,$sub){
		$final_grade = 0;

		if($student_id != '' && $sub != ''){
			$sqls = 'SELECT * FROM tbl_student_schedule WHERE subject_id= $sub AND student_id=$student_id';
			$querys = mysql_query($sqls);
			$rows = mysql_fetch_array($querys);

			$sql = "SELECT * FROM tbl_student_grade WHERE subject_id = ".$sub." AND student_id=".$student_id;
			$query = mysql_query($sql);	
			$grade = 0;	

			while($row = mysql_fetch_array($query)){
				if($row['is_altered'] == 'Y'){
					if($row['altered_grade'] != ''){
						$grade = decrypt($row['altered_grade'])*1;
					}else{
						$grade = decrypt($row['grade'])*1;
					}
				}else{
					$grade =  decrypt($row['grade'])*1;
				}
				$final_grade +=  ($grade * (getPeriodPercentage($row['period_id']) / 100));
				$grade = 0;		
			}
		}
		
		if($final_grade > 100){
			return 100;
		}else if($final_grade < 40&&$final_grade>0){
			return 40;
		}else{
			return $final_grade*1;
		}
	}
		
	function getGradeConversionId($grade,$term){
		if($grade != ''){
			if($term>10){
				$s="is_current='Y'";
			}else{
				$s="is_current='N'";
			}
			$sql = "SELECT id FROM tbl_grade_conversion 
					WHERE ".$s." AND '".round($grade)."' BETWEEN floor_grade AND ceiling_grade";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['id'];
		}
	}

	function getGradeConversionById($id){
		if($id != ''){			
			$sql = "SELECT * FROM tbl_grade_conversion 
					WHERE id=".$id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);

			if(mysql_num_rows($query) > 0){
				return $row['grade_code']>3?5:$row['grade_code']; 
			}else{
				return 0;
			}
		}
	}

	function getGradeConversionGrade($grade){
		if($grade != ''){			
			$sql = "SELECT * FROM tbl_grade_conversion 
					WHERE is_current='Y' AND '".round($grade)."' BETWEEN floor_grade AND ceiling_grade";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);

			if(mysql_num_rows($query) < 1){
				$sql_con = "SELECT * FROM tbl_grade_conversion ORDER BY ceiling_grade";
				$query_con = mysql_query($sql_con);
				$num = mysql_num_rows($query_con);
				$x = 0;

				while($row_con = mysql_fetch_array($query_con)){
					if($x==0 && $grade < $row_con['floor_grade']){
						return $row_con['grade_code']; 
					}else if($x==$num && $grade > $row_con['ceiling_grade']){
						return $row_con['grade_code']; 
					}
					$x++;
				}
			}else{
				return $row['grade_code'];
			}
		}
	}

	function getGradeConversionGrade2($stud,$sched,$term){
		if($stud != ''){			
			$sql = "SELECT * FROM tbl_schedule WHERE id = ".$sched." AND term_id=".$term;
			$query = mysql_query($sql);	
			$row = @mysql_fetch_array($query);
			$sql2 = "SELECT * FROM tbl_student_final_grade WHERE subject_id = ".$row['subject_id']." AND student_id=".$stud." AND term_id=".$term;
			$query2 = mysql_query($sql2);
			$row2 = @mysql_fetch_array($query2);

			if(mysql_num_rows($query2) > 0){
				$grade = decrypt($row2['final_grade']);
				
				if($grade>100){
					$grade = 100;
				}else if($grade<40&&$grade>0){
					$grade = 40;
				}

				$sql = "SELECT * FROM tbl_grade_conversion 
					WHERE id=".$row2['grade_conversion_id'];
				$query = mysql_query($sql);
				$row = mysql_fetch_array($query);
		
				if(mysql_num_rows($query) > 0){
					return $row['grade_code']; 
				}else{
					if($grade > 40){
						$sql = "SELECT * FROM tbl_grade_conversion 
								WHERE is_current='Y' AND '".$grade."' BETWEEN floor_grade AND ceiling_grade";
						$query = mysql_query($sql);
						$row = mysql_fetch_array($query);
						return $row['grade_code'];
					}else{
						return 5.0;
					}
				}
			}else{
				$grade = getStudentFinalGrade($stud,$sched,$term);
				if($grade > 40){
					$sql = "SELECT * FROM tbl_grade_conversion 
							WHERE is_current='Y' AND '".$grade."' BETWEEN floor_grade AND ceiling_grade";
					$query = mysql_query($sql);
					$row = mysql_fetch_array($query);
					return $row['grade_code'];
				}else{
					return 5.0;
				}
			}
		}
	}
	
	function getGradeConversionGradeByConvId($stud,$id,$term){
		if($stud != ''){			
			$sql2 = "SELECT * FROM tbl_student_final_grade WHERE id = ".$id." AND student_id=".$stud." AND term_id=".$term;
			$query2 = mysql_query($sql2);
			$row2 = @mysql_fetch_array($query2);

			if(mysql_num_rows($query2) > 0){
				$grade = decrypt($row2['final_grade']);
				
				if($grade>100){
					$grade = 100;
				}else if($grade<40){
					$grade = 40;
				}

				$sql = "SELECT * FROM tbl_grade_conversion 
						WHERE id=".$row2['grade_conversion_id'];
				$query = mysql_query($sql);
				$row = mysql_fetch_array($query);
		
				if(mysql_num_rows($query) > 0){
					return $row['grade_code']; 
				}else{
					if($grade > 40){
						$sql = "SELECT * FROM tbl_grade_conversion 
								WHERE is_current='Y' AND '".$grade."' BETWEEN floor_grade AND ceiling_grade";
						$query = mysql_query($sql);
						$row = mysql_fetch_array($query);
						return $row['grade_code'];
					}else{
						return 5.0;
					}
				}
			}else{
				return 0.0;
			}
		}
	}
	
	function getAverageConversion($grade,$stud,$term){
		if($stud != ''){			
			$sql2 = "SELECT * FROM tbl_student_final_grade WHERE student_id=".$stud." AND term_id=".$term;
			$query2 = mysql_query($sql2);
			$row2 = @mysql_fetch_array($query2);

			if(mysql_num_rows($query2) > 0){
				$sql3 = "SELECT * FROM tbl_grade_conversion 
						WHERE id=".$row2['grade_conversion_id'];
				$query3 = mysql_query($sql3);
				$row3 = mysql_fetch_array($query3);
				
				if(mysql_num_rows($query3) > 0){
					if($row3['is_current']=='Y'){
						$sql = "SELECT * FROM tbl_grade_conversion 
								WHERE is_current='Y' AND '".round($grade)."' BETWEEN floor_grade AND ceiling_grade";
						$query = mysql_query($sql);
						$row = mysql_fetch_array($query);
			
						if(mysql_num_rows($query) < 1){
							$sql_con = "SELECT * FROM tbl_grade_conversion ORDER BY ceiling_grade";
							$query_con = mysql_query($sql_con);
							$num = mysql_num_rows($query_con);
							$x = 0;
			
							while($row_con = mysql_fetch_array($query_con)){
								if($x==0 && $grade < $row_con['floor_grade']){
									return $row_con['grade_code']; 
								}else if($x==$num && $grade > $row_con['ceiling_grade']){
									return $row_con['grade_code']; 
								}
								$x++;
							}
						}
						return $row['grade_code']; 
					}else{
						$sql = "SELECT * FROM tbl_grade_conversion 
								WHERE is_current='N' AND '".round($grade)."' BETWEEN floor_grade AND ceiling_grade";
						$query = mysql_query($sql);
						$row = mysql_fetch_array($query);
			
						if(mysql_num_rows($query) < 1){
							$sql_con = "SELECT * FROM tbl_grade_conversion ORDER BY ceiling_grade";
							$query_con = mysql_query($sql_con);
							$num = mysql_num_rows($query_con);
							$x = 0;
			
							while($row_con = mysql_fetch_array($query_con)){
								if($x==0 && $grade < $row_con['floor_grade']){
									return $row_con['grade_code']; 
								}else if($x==$num && $grade > $row_con['ceiling_grade']){
									return $row_con['grade_code']; 
								}
								$x++;
							}
						}
						return $row['grade_code']; 
					}
				}
			}
		}
	}

	function covertTimeTo12($time){
		$new_time = date("g:i A", strtotime($time));
		return $new_time;
	}

	function changeDateFormat($date){ /*CHANGE THE TIME FORMAT FRO YYYYMMDD TO MM DD YYYY*/
		$arr_date = explode("-", $date);
		return date("F d, Y", mktime(0, 0, 0, $arr_date[1], $arr_date[2], $arr_date[0])); 
	}

	function activation_by($activation_by){
		if($activation_by == 'D'){
			return $act_by = "Disable";
		}else if($activation_by == 'N'){
			return $act_by = "None";
		}else if($activation_by == 'A'){
			return $act_by = "By Admin";
		}
	}

	function passComplexity($password_complexity){
		if($password_complexity == 'NR'){
			return $complex = "No requirements";
		}else if($password_complexity == 'MC'){
			return $complex = "Must be mixed case";
		}else if($password_complexity == 'LN'){
			return $complex = "Must contain letters and numbers";
		}else if($password_complexity == 'WS'){
			return $complex = "Must contain symbols";
		}
	}
	
	function getAnnualIncome($id){
		if(isset($id)){
			$sql = "SELECT * FROM tbl_gross_income WHERE id = $id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['title'];
		}
	}

	function getAccessId($employee_type_id){
		if(isset($employee_type_id)){
			$sql = "SELECT * FROM tbl_access WHERE id = $employee_type_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['id'];
		}
	}

	function getPerUnitType($fee_type){
		if($fee_type == 'perunitlec'){
			return "Lec";
		}else if($fee_type == 'perunitlab'){
			return "Lab";
		}
	}

	function checkStudentDueFeeExist($stud_id,$id){
		$sql = "SELECT * FROM ob_member_account WHERE student_id=".$stud_id." AND transid=".$id;
		$query = mysql_query($sql);

		if(mysql_num_rows($query) > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkIfLibraryPayment($id){
		$sql = "SELECT * FROM tbl_payment_types WHERE id=".$id;
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);

		if($row['is_library']=='Y'){
			return true;
		}else{
			return false;
		}
	}

	function getStudentLibraryDueFee($stud_id){
		if(isset($stud_id) && $stud_id !=''){
			$sql_out = "SELECT c.bibid, c.copyid, m.id, c.barcode_nmbr,
							b.title, b.author, c.status_begin_dt, d.description,
							c.due_back_dt, m.lib_card_number member_bcode,m.course_id,m.student_number,
							concat(m.lastname, ', ', m.firstname) name
						FROM ob_biblio b, ob_biblio_copy c, tbl_student m, ob_collection_dm d
						WHERE b.bibid = c.bibid
							AND c.student_id = m.id
							AND c.status_cd = 'out'	
							AND b.collection_cd=d.code and id =".$stud_id;
			$query_out = mysql_query($sql_out);

			if(mysql_num_rows($query_out) > 0){
				while($row_out = mysql_fetch_array($query_out)){
					$sql = "SELECT c.bibid, c.copyid, m.id, c.barcode_nmbr, b.collection_cd,
								b.title, b.author, c.status_begin_dt, d.description,
								c.due_back_dt, m.lib_card_number member_bcode,m.course_id,m.student_number,
								concat(m.lastname, ', ', m.firstname) name,
								floor(to_days(now())-to_days(c.due_back_dt)) days_late
							FROM ob_biblio b, ob_biblio_copy c, tbl_student m, ob_collection_dm d
							WHERE b.bibid = c.bibid
								AND c.student_id = m.id
								AND c.status_cd = 'out'
								AND b.collection_cd=d.code 
								AND c.bibid=".$row_out['bibid']. " 
								AND m.id=".$stud_id;
					$query = mysql_query($sql);

					while($row = mysql_fetch_array($query)){
						if($row['days_late'] > 0){
							$sql_due = "SELECT * FROM ob_collection_dm WHERE code=".$row['collection_cd'];
							$query_due = mysql_query($sql_due);
							$row_due = mysql_fetch_array($query_due);
							$total = $row_due['daily_late_fee'] * $row['days_late'];

							if(!checkStudentDueFeeExist($stud_id,$row_out['bibid'])){
								if($total != 0){
									$sql_in = "INSERT INTO ob_member_account 
												(
													student_id,
													transid,
													create_dt, 
													create_userid,
													transaction_type_cd,
													amount,
													description
												)
												VALUES 
												(
													".$stud_id.", 
													".$row_out['bibid'].", 
													'".date("Y-m-d H:s:i")."',
													".USER_ID.",  
													".'"+c"'.", 	
													".$total.",  
													".'"Overdue Charge"'."
												)";
									$query_in = mysql_query($sql_in);
								}
							}else{
								if($total != 0){
									$sql_in = "UPDATE ob_member_account 
												SET amount = ".$total."
												WHERE student_id = ".$stud_id." AND
													transid = ".$row_out['bibid'];
									$query_in = mysql_query($sql_in);
								}
							}
						}
					}
				}
			}
		}
	}

	function getStudentLibraryBalance($id){
		if(isset($id) && $id !=''){
			$total = 0;
			$sql = "SELECT * FROM ob_member_account WHERE transaction_type_cd='+c' AND student_id=".$id;
			$query = mysql_query($sql);

			while($row = mysql_fetch_array($query)){
				$total += $row['amount'];
			}
			return $total;
		}
	}

	function getLibraryDueFee($stud_id){
		if(isset($stud_id) && $stud_id !=''){
			$sql_out = "SELECT c.bibid, c.copyid, m.id, c.barcode_nmbr,
							b.title, b.author, c.status_begin_dt, d.description,
							c.due_back_dt, m.lib_card_number member_bcode,m.course_id,m.student_number,
							concat(m.lastname, ', ', m.firstname) name
						FROM ob_biblio b, ob_biblio_copy c, tbl_student m, ob_collection_dm d
						WHERE b.bibid = c.bibid
							AND c.student_id = m.id
							AND c.status_cd = 'out'	
							AND b.collection_cd=d.code and id =".$stud_id;
			$query_out = mysql_query($sql_out);

			if(mysql_num_rows($query_out) > 0){
				while($row_out = mysql_fetch_array($query_out)) {
					$sql = "SELECT c.bibid, c.copyid, m.id, c.barcode_nmbr, b.collection_cd,
								b.title, b.author, c.status_begin_dt, d.description,
								c.due_back_dt, m.lib_card_number member_bcode,m.course_id,m.student_number,
								concat(m.lastname, ', ', m.firstname) name,
								floor(to_days(now())-to_days(c.due_back_dt)) days_late
							FROM ob_biblio b, ob_biblio_copy c, tbl_student m, ob_collection_dm d
							WHERE b.bibid = c.bibid
								AND c.student_id = m.id
								AND c.status_cd = 'out'
								AND b.collection_cd=d.code 
								AND c.bibid=".$row_out['bibid']. " 
								AND m.id=".$stud_id;
					$query = mysql_query($sql);

					while($row = mysql_fetch_array($query)){
						if($row['days_late'] > 0){
							$sql_due = "SELECT * FROM ob_collection_dm WHERE code=".$row['collection_cd'];
							$query_due = mysql_query($sql_due);
							$row_due = mysql_fetch_array($query_due);
							$total = $row_due['daily_late_fee'] * $row['days_late'];
						}
					}
				}
			}
		}
		return $total;
	}

	function getStudentTotalDue($id,$term){
		if(isset($id) && $id !=''){
			/* TOTAL FEE */
			$sql = "SELECT * FROM tbl_student_fees WHERE is_removed <> 'Y' AND student_id = ".$id." AND term_id =" .$term;
			$qry = mysql_query($sql);

			while($row = mysql_fetch_array($qry)){           
				$total += $row['quantity']*$row['amount'];
				
				if($row['subject_id']!=''){
					$sub_lec_total+=$row['quantity']*$row['amount'];	
				}
			}
			
			
			/* TOTAL OTHER FEE */
			$sql_other = "SELECT sum(amount) FROM tbl_student_other_fees WHERE student_id = ".$id." AND term_id =" .$term;
			$qry_other = mysql_query($sql_other);
			$row_other = mysql_fetch_array($qry_other);
			$sub_other_total = $row_other['sum(amount)'];

			/*TOTAL*/
			$sub_total =  $total+$sub_other_total;
			
			//SURCHARGE
			$surcharge = GetSchemeForSurcharge($id)*getEnrolledTotalUnits($term,$id);
			
			//DISCOUNT
			$sqldis = 'SELECT * FROM tbl_student WHERE id='.$id;
			$querydis = mysql_query($sqldis);
			$rowdis = mysql_fetch_array($querydis);
			
			if($rowdis['scholarship_type']=='A'){
				$discount = ($sub_total+$surcharge)-5000;
				$discount = ($discount*$rowdis['scholarship'])/100;
			}else{
				$discount = $sub_lec_total+$surcharge;
				$discount = ($discount*$rowdis['scholarship'])/100;
			}

			//CARRIED BALANCE
			
			/*
			if(getCarriedBalances($id,CURRENT_TERM_ID)>0){
				$sub_total += getCarriedBalances($id,CURRENT_TERM_ID);	
			}

			if(getCarriedDebits($id,CURRENT_TERM_ID)>0){
				$sub_total += getCarriedDebits($id,CURRENT_TERM_ID);	
			}
			*/

			$totalfee = ($sub_total+$surcharge) - $discount;
  		}
		return $totalfee;
	}
	
	function getPaymentSchemeTotal($term){
		if($term!=''){
			$sql = 'SELECT * FROM tbl_payment_scheme WHERE term_id='.$term;
			$query = mysql_query($sql);
			
			$r=0;
			while($row = mysql_fetch_array($query)){
				$sql2 = 'SELECT * FROM tbl_payment_scheme_details WHERE scheme_id='.$row['id'];
				$query2 = mysql_query($sql2);
				$a = mysql_num_rows($query2);
				
				if($a>$r){
					$r = $a;
					$id = $row['id'];
				}
			}
			return $id;
		}
	}

	function getStudentTotalFee($id,$term=CURRENT_TERM_ID){
		if(isset($id) && $id !=''){

			/*
			TOTAL AMOUNT 
			$sql = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND publish =  'Y'";
			$result = mysql_query($sql);
			$sub_total = 0;

			while($row = mysql_fetch_array($result)){
				$total = getStudentAmountFeeByFeeId($row['id'],$id);           
				$sub_total += $total;
			}
			*/

			/*
			TOTAL OTHER PAYMENT 
			$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND publish =  'Y'";
			$result_fee_other = mysql_query($sql_fee_other);
			$row_fee_other = mysql_fetch_array($result_fee_other);
			$sub_mis_total = 0;
			$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$id);           
			$sub_mis_total += $mis_total;
			*/

			/*
			TOTAL LEC PAYMENT 
			$sql_lec = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND fee_type = 'perunitlec' AND publish =  'Y'";
			$qry_lec = mysql_query($sql_lec);
			$row_lec = mysql_fetch_array($qry_lec);
			$sub_lec_total = 0;
			$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$id);           
			$sub_lec_total += $lec_total;
			*/

			/*
			TOTAL LAB PAYMENT 
			$sql_lab = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND fee_type = 'perunitlab' AND publish =  'Y'";
			$qry_lab = mysql_query($sql_lab);
			$row_lab = mysql_fetch_array($qry_lab);
			$sub_lab_total = 0;
			$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$id);           
			$sub_lab_total += $lab_total;
			*/

			/* TOTAL LEC PAYMENT */
			$sql_lec = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlec' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$id." AND s.term_id =" .$term;
			$qry_lec = mysql_query($sql_lec);
			$row_lec = mysql_fetch_array($qry_lec);
			$sub_lec_total = 0;
			//$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$student_id);           
			$sub_lec_total = $row_lec['sum(s.quantity)']*$row_lec['amount'];

			/* TOTAL LAB PAYMENT */
			$sql_lab = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlab' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$id." AND s.term_id =" .$term;
			$qry_lab = mysql_query($sql_lab);
			$row_lab = mysql_fetch_array($qry_lab);
			$sub_lab_total = $row_lab['sum(s.quantity)']*$row_lab['amount'];
			
			/* TOTAL MISC PAYMENT */
			$sql_misc = "SELECT f.fee_type,s.*,sum(s.amount) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$id." AND s.term_id =" .$term;
			$qry_misc = mysql_query($sql_misc);
			$row_misc = mysql_fetch_array($qry_misc);
			$sub_mis_total = $row_misc['sum(s.amount)'];
		
			/*
			TOTAL OTHER FEE 
			$sql_other = "SELECT sum(amount) FROM tbl_student_other_fees WHERE student_id = ".$id." AND term_id =" .$term;
			$qry_other = mysql_query($sql_other);
			$row_other = mysql_fetch_array($qry_other);
			$sub_other_total = $row_other['sum(amount)'];
			*/
			

			/*TOTAL LEC AND LAB = LEC + LAB*/
			$sub_total =  $sub_lec_total + $sub_lab_total + $sub_mis_total;//+$sub_other_total;

			/*TOTAL UNITS = TUITION FEE - TOTAL OTHER PAYMENT*/
			$total_lec_lab = $sub_total - $sub_mis_total;
			$sql_payment = "SELECT * FROM tbl_student_payment WHERE term_id = ".$term." AND student_id = ".$id;
			$query_payment = mysql_query ($sql_payment);

			while($row_pay = @mysql_fetch_array($query_payment)){				
				if($row_pay['is_bounced'] != 'N' && $row_pay['is_refund'] != 'N'){
					$total_payment += $row_pay['amount']; 
				}
			}
			
			//SURCHARGE
			$surcharge = GetSchemeForSurcharge($id)*getEnrolledTotalUnits($term,$id);
			
			//DISCOUNT
			$sqldis = 'SELECT * FROM tbl_student WHERE id='.$id;
			$querydis = mysql_query($sqldis);
			$rowdis = mysql_fetch_array($querydis);
			
			$sqlt = 'SELECT * FROM tbl_school_year_term WHERE id='.$term;
			$queryt = mysql_query($sqlt);
			$rowt = mysql_fetch_array($queryt);
			$t = strtolower($rowt['school_term'])!='summer'?5000:0;
			
			if($rowdis['scholarship_type']=='A'){
				getStudentYearLevel($id)==4?$t=0:'';
				$discount = ($sub_total+$surcharge)-$t;
				$discount = ($discount*$rowdis['scholarship'])/100;
			}else{
				$discount = $sub_lec_total+$surcharge;
				$discount = ($discount*$rowdis['scholarship'])/100;
			}

			//CARRIED BALANCE
			/*
			if(getCarriedBalances($id,CURRENT_TERM_ID)>0){
				$sub_total += getCarriedBalances($id,CURRENT_TERM_ID);	
			}

			if(getCarriedDebits($id,CURRENT_TERM_ID)>0){
				$sub_total += getCarriedDebits($id,CURRENT_TERM_ID);	
			}
			*/
			$totalfee = ($sub_total+$surcharge) - $discount;
  		}
		return $totalfee;
	}

	function getStudentAmountFeeByFeeId($fee_id,$student_id){
		if(isset($fee_id)){
			$sql = "SELECT sum(amount) as total_fee FROM tbl_student_fees 
					WHERE 
						fee_id = $fee_id AND 
						student_id = $student_id AND
						is_removed = 'N'";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['total_fee'];
		}
	}

	function getStudentAmountFeeByFeeIdCompByUnit($fee_id,$student_id){
		if(isset($fee_id)){
			$sql = "SELECT sum(amount) as total_amount, sum(quantity) as total_quantity FROM tbl_student_fees 
						WHERE 
							fee_id = $fee_id AND 
							student_id = $student_id AND
							is_removed = 'N'";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			$total_fee = $row['total_amount'];
			return $total_fee;
		}
	}

	function getFeeUnit($id,$student_id){
		if(isset($id)){
			$total_units = 0;
			$sql = "SELECT * FROM tbl_student_fees 
					WHERE 
						fee_id = $id AND 
						student_id = $student_id AND
						is_removed = 'N'";
			$query = mysql_query($sql);		

			while($row = mysql_fetch_array($query)){
				$total_units += $row['quantity'];
			}
			return $total_units!=''?$total_units:'0.00';
		}
	}

	function getStudentTotalFeeLecLab($fee_id,$student_id){
		if(isset($fee_id)){
			$sql = "SELECT sum(quantity) as total_unit FROM tbl_student_fees 
					WHERE 
						fee_id = $fee_id AND 
						student_id = $student_id AND
						is_removed = 'N'";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			$sql_per_unit = "SELECT * FROM tbl_school_fee WHERE id = $fee_id";
			$query_per_unit = mysql_query($sql_per_unit);		
			$row_per_unit = mysql_fetch_array($query_per_unit);
			$total_unit = $row['total_unit'] * $row_per_unit['amount'];
			return $total_unit;
		}
	}

	function getStudentDiscount($discount_id,$student_id,$total_lec_lab){
		if(isset($discount_id)){
			$sql = "SELECT * FROM tbl_student_payment 
					WHERE 
						discount_id = $discount_id AND 
						student_id = $student_id";
			$query = mysql_query($sql);		
			$row = @mysql_fetch_array($query);
			$sql_discount = "SELECT * FROM tbl_discount WHERE id=" .$row['discount_id'];
			$qry_discount = mysql_query($sql_discount);
			$row_discount = @mysql_fetch_array($qry_discount);
			$total_discounted = $total_lec_lab / 100 * $row_discount['value'];
			return $total_discounted;
		}
	}

	function getDiscountValue($discount_id){
		if(isset($discount_id)){
			$sql_discount = "SELECT * FROM tbl_discount WHERE id=" .$discount_id;
			$qry_discount = mysql_query($sql_discount);
			$row_discount = mysql_fetch_array($qry_discount);
			return $row_discount['value'];
		}
	}

	function getDiscountName($discount_id){
		if(isset($discount_id)){
			$sql_discount = "SELECT * FROM tbl_discount WHERE id=" .$discount_id;
			$qry_discount = mysql_query($sql_discount);
			$row_discount = mysql_fetch_array($qry_discount);
			return $row_discount['name'];
		}
	}

	function getStudentOtherFeeByFeeId($fee_id,$student_id){
		if(isset($fee_id)){
			$sql = "SELECT sum(school_fee.amount) as total_other_fee
					FROM tbl_school_fee as school_fee, tbl_student_fees as student_fee
					WHERE student_fee.fee_id = school_fee.id
						AND school_fee.id <> $fee_id
						AND (school_fee.fee_type <> 'perunitlec' AND school_fee.fee_type <> 'perunitlab') 
						AND school_fee.term_id=".CURRENT_TERM_ID." 
						AND school_fee.publish =  'Y'
						AND student_fee.is_removed = 'N'
						AND student_fee.student_id =" .$student_id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['total_other_fee'];
		}
	}

	function getDroppingAddingEndDate($course,$term){
		if($term!=''){
			$sql = "SELECT * FROM tbl_enrollment_date 
					WHERE 
							course_id = " .$course.
							" AND term_id = ".$term;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);

			if($row['drop_add_date']!=''){
				return $row['drop_add_date'];
			}else{
				return date('Y-m-d'); 
			}
		}
	}

	function getStudentReservationDay($student_id){
		if(isset($student_id)){
			$sql = "SELECT reserve_days FROM tbl_enrollment_date 
					WHERE 
						course_id = " .getStudentCourseId($student_id);
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['reserve_days'];
		}
	}

	/* THIS IS TO GET THE TOTAL LEC/LAB UNIT */
	function getTotalLecLabUnit($fee_type, $student_id){
		$sql = "SELECT * FROM tbl_student_reserve_subject as res_sub, tbl_schedule as sched, tbl_subject as sub WHERE res_sub.schedule_id = sched.id AND res_sub.schedule_id = sub.id AND sub.subject_type = '".$fee_type."' AND res_sub.student_id =".$student_id; 
		$result = mysql_query($sql);
		$arrTotalLecLabUnits = array();

		if(mysql_num_rows($result) > 0 ){
			$ctr = 0;

			while($row = mysql_fetch_array($result)){ 
				$arrTotalLecLabUnits[] = getSubjUnit($row["subject_id"]);
			}
		}
		return $arrTotalLecLabUnits = array_sum($arrTotalLecLabUnits);
	}

	function getRefundAmountBySubjectId($id,$student_id){
		if($id != '' && $student_id !=''){
			$sql = "SELECT * FROM tbl_student_fees 
					WHERE is_removed = 'Y' AND student_id = ".$student_id." AND subject_id = ".$id;
			$query =mysql_query($sql);
			$row = mysql_fetch_array($query);
			$total = ($row['amount']*$row['quantity']); 
		}
		return $total;
	}

	function getTotalRefundAmount($id,$term){
		if($id != ''){
			$sql = "SELECT * FROM tbl_student_fees
					WHERE student_id = ".$id." AND term_id = ".$term;
			$query =mysql_query($sql);

			while($row = mysql_fetch_array($query)){
				if($row['is_removed']=='Y'){
					$trefund += ($row['amount']*$row['quantity']);
				}
				$total += ($row['amount']*$row['quantity']); 
			}
		}
		$total = $total + getCarriedBalances($id,$term);
		//
		$sql = "SELECT * FROM tbl_student_payment WHERE is_refund <> 'Y' AND is_bounced <> 'Y' AND student_id = ".$id." AND term_id = ".$term;
		$query = mysql_query($sql);
		
		while($row = mysql_fetch_array($query))
		{
			if($row['discount_id']!=''&&$row['discount_id']!=0)
			{
				
				$sqld = "SELECT * FROM tbl_discount WHERE id = ".$row['discount_id'];
				$queryd = mysql_query($sqld);
				
				$rowd = mysql_fetch_array($queryd);
				
				$disc = $rowd['value']/100; 
				
				$disct = ($total-$trefund)*$disc;
				
			}
			$totalpay += $row['amount'];
			
		}

		if($totalpay > ($total-$trefund)-$disct)
		{
		
			$refund = abs((($total-$trefund)-$disct)-$totalpay);
		
		}
		else
		{
		
			$refund = 0;
		
		}
		
		return $refund;
	}

	function checkIfStudentPaidFull($id){
		if($id != ''){
			$sql = "SELECT * FROM tbl_student_payment WHERE student_id = ".$id.
					" AND is_bounced <> 'Y' AND is_refund <> 'Y' 
					AND term_id=".CURRENT_TERM_ID;
			$query =mysql_query($sql);
			$row = @mysql_fetch_array($query);
			$paid = @mysql_num_rows($query);
		}

		if($row['payment_term']=='F'){
			return true;
		}else{
			return false;
		}
	}

	function checkIfStudentRefunded($id,$student_id){
		if($id != ''){		
			$sub="";	
			$sqlsub = "SELECT * FROM tbl_student_payment 
						WHERE student_id = ".$student_id.
						" AND is_refund = 'Y' 
						AND term_id=".CURRENT_TERM_ID;
			$querysub =mysql_query($sqlsub);

			while($rowsub = mysql_fetch_array($querysub)){
				if($sub!=""){
					$sub.=",";
				}
				$sub .= $rowsub['subject_id'];
			}
			$subj = explode(",",$sub);
		}

		if(in_array($id,$subj)){
			return true;
		}else{
			return false;
		}
	}

	function getStudentGuardianByStudentId($student_id){
		if(isset($student_id)){
			$sql = "SELECT * FROM tbl_parent WHERE student_id = $student_id";
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			return $row['firstname'].' '.$row['lastname'];
		}
	}	

	function getSubjectPreReqInArr($curriculum_subject_id){
		if(isset($curriculum_subject_id) && $curriculum_subject_id != ''){
			$arr_str = array();
			$sql = "SELECT * FROM tbl_curriculum_subject_prereq WHERE curriculum_subject_id = $curriculum_subject_id";
			$query = mysql_query($sql);		

			while($row = mysql_fetch_array($query)){
				$arr_str[] = $row['prereq_subject_id'];
			}
			return $arr_str;
		}	
	}

	function getSubjectCoReqInArr($curriculum_subject_id){
		if(isset($curriculum_subject_id) && $curriculum_subject_id != ''){
			$arr_str = array();
			$sql = "SELECT * FROM tbl_curriculum_subject_coreq WHERE curriculum_subject_id = $curriculum_subject_id";
			$query = mysql_query($sql);		

			while($row = mysql_fetch_array($query)){
				$arr_str[] = $row['coreq_subject_id'];
			}
			return $arr_str;
		}
	}

/* [-] ALL THE GET VALUE FUNCTION */

/**********************************************************************/

/* [+] ALL SYSTEM FUNCTION */

	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""){
		$theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;
		switch ($theType) {
			case "text":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "''";
				break;    
			case "long":
			case "int":
				$theValue = ($theValue != "") ? intval($theValue) : "NULL";
				break;
			case "double":
				$theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
				break;
			case "date":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
				break;
			case "defined":
				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
				break;
		}
		return $theValue;
	}

	function generatePassword($length = 8, $letters = '1234567890qwertyuiopasdfghjklzxcvbnmABCDEFGHIJKLMNOPQRSTUVWXYZ'){ 
		$pass = ''; 
		$lettersLength = strlen($letters)-1; 
		for($i = 0 ; $i < $length ; $i++){ 
			$pass .= $letters[rand(0,$lettersLength)]; 
		} 
		return $pass; 
	}  

	function generateRandomString($length = 20, $letters = '1234567890qwertyuiopasdfghjklzxcvbnmABCDEFGHIJKLMNOPQRSTUVWXYZ'){ 
		$arr_str = array();
		$lettersLength = strlen($letters)-1; 

		for($i = 0 ; $i < $length ; $i++){ 
			$arr_str[] = $letters[rand(0,$lettersLength)]; 
		} 
		return implode('',$arr_str);
	}

	function generateSaltString($length = 10, $letters ='1234567890qwertyuiopasdfghjklzxcvbnmABCDEFGHIJKLMNOPQRSTUVWXYZ'){ 
		$arr_str = array();
		$lettersLength = strlen($letters)-1; 

		for($i = 0 ; $i < $length ; $i++){ 
			$arr_str[] = $letters[rand(0,$lettersLength)]; 
		} 
		return implode('',$arr_str);
	} 

	function getUserIP(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){   /*check ip from share internet*/
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){   /*to check ip is pass from proxy*/
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	function userNoAccess(){
	}

	function throwUserLogs(){
		killCurrentSession();
	}

	function killCurrentSession(){
		session_unset();
		session_destroy();
		session_regenerate_id(true);
	}	

	function getUserSalt($username){
		if($username != '' && isset($username)){
			$sql = "SELECT * FROM tbl_user WHERE username= ".GetSQLValueString($username,"text");
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);

			return $row['salt'];
		}
	}
	
	function storedModifiedLogs($table, $id){
		if($table != '' && $id != ''){
			$sql = "SELECT * FROM $table WHERE id = " . GetSQLValueString($id ,"int");
			$query = mysql_query($sql);
			if($row = mysql_fetch_array($query)){
				if($row['modified_logs'] != ''){
					$logs_str = $row['modified_logs'] . ';' . $row['date_modified'] . ',' .$row['modified_by'];
				}else{
					$logs_str =$row['date_modified'] . ',' .$row['modified_by'];
				}

				$sql = "UPDATE $table SET 
							modified_logs = ".GetSQLValueString($logs_str  ,"text") . "
						WHERE id = " . GetSQLValueString($id ,"int");

				if(mysql_query ($sql)){
					return true;
				}else{
					return false;
				}
			}
		}
		return false;
	}

	function storedSessionLogs(){
		if(SYS_ALLOWED_SIM_LOGIN == 'N'){
			$sql = "DELETE FROM tbl_session_logs WHERE user_id = ".$_SESSION[CORE_U_CODE]['user_credentials']['user_id'];
			mysql_query ($sql);
		}			

		$sql = "INSERT INTO tbl_session_logs 
				(
					session_id,
					user_id,
					ip_connected, 
					date_logged
				) 
				VALUES 
				(
					".GetSQLValueString($_SESSION[CORE_U_CODE]['logged_info']['log_session_id'] ,"text").", 
					".GetSQLValueString($_SESSION[CORE_U_CODE]['user_credentials']['user_id'] ,"int").", 
					".GetSQLValueString($_SESSION[CORE_U_CODE]['logged_info']['log_ip_address'] ,"text").", 
					".GetSQLValueString($_SESSION[CORE_U_CODE]['logged_info']['log_timestamp'] ,"int") ."
				)";
		mysql_query ($sql);

		$sql = "INSERT INTO tbl_system_logs 
				(
					user_id,
					message,
					for_admin,
					for_self,
					for_prof,
					for_student,
					date_created
				) 
				VALUES 
				(
					".GetSQLValueString($_SESSION[CORE_U_CODE]['user_credentials']['user_id'] ,"int").", 
					".GetSQLValueString(' successfully logged in the system' ,"text").", 
					".GetSQLValueString('Y' ,"text").", 
					".GetSQLValueString('Y' ,"text").", 
					".GetSQLValueString('N' ,"text").", 
					".GetSQLValueString('N' ,"text").", 
					".time()."
				)";
		mysql_query ($sql);			
	}

	function updateSystemLogForLogout(){
		$sql = "INSERT INTO tbl_system_logs 
				(
					user_id,
					message,
					for_admin,
					for_self,
					for_prof,
					for_student,
					date_created
				) 
				VALUES 
				(
					".GetSQLValueString($_SESSION[CORE_U_CODE]['user_credentials']['user_id'] ,"int").", 
					".GetSQLValueString(' successfully logged out from the system' ,"text").", 
					".GetSQLValueString('Y' ,"text").", 
					".GetSQLValueString('Y' ,"text").", 
					".GetSQLValueString('N' ,"text").", 
					".GetSQLValueString('N' ,"text").", 
					".time()."
				)";
		mysql_query ($sql);	
	}

	function storeSystemLogs($template,$param,$for_user = '' ,$self = 'N', $admin = 'N',$prof = 'N',$stud = 'N'){
		$message = vsprintf($template,$param);
		
		$sql = "INSERT INTO tbl_system_logs 
				(
					user_id,
					for_user_id,
					message,
					for_admin,
					for_self,
					for_prof,
					for_student,
					date_created
				) 
				VALUES 
				(
					".GetSQLValueString(USER_ID ,"int").", 
					".GetSQLValueString($for_user ,"int").", 
					".GetSQLValueString($message ,"text").", 
					".GetSQLValueString($admin ,"text").", 
					".GetSQLValueString($self ,"text").", 
					".GetSQLValueString($prof ,"text").", 
					".GetSQLValueString($stud ,"text").", 
					".time()."
				)";
		mysql_query ($sql);
	}

	function notification($user,$template,$param,$from){
		if($_SESSION[CORE_U_CODE]['system_settings']['notification']=='ON'){
			$message = vsprintf($template,$param);
			$msg = "System Notifications: \n";
			$msg .= "================================================================================= \n";

			if(USER_ID==6){
				$msg .= getStudentFullNameByUser($from).$message;
			}else{
				$msg .= getEmployeeFullNameByUserId($from).$message;
			}

			if(getUserEmail($user)!=''){
				@mail(getUserEmail($user), SCHOOL_NAME.' Notification', $msg);
				//echo getUserEmail($user);
			}
		}	
	}

	function updateUserLastLogin(){
		$sql = "UPDATE tbl_user SET 
					last_login = ".GetSQLValueString($_SESSION[CORE_U_CODE]['logged_info']['log_timestamp'] ,"int") . "
				WHERE id = " . GetSQLValueString($_SESSION[CORE_U_CODE]['user_credentials']['user_id'] ,"int");
		if(mysql_query ($sql)){

		}
	}

	function getUserAll(){	
		$ids = array();
		$sql = "SELECT * FROM tbl_user WHERE blocked=0";
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
			$ids[] = $row['id'];
		}
		return $ids;
	}

	function getUserAdmin(){	
		$ids = array();
		$sql = "SELECT * FROM tbl_user WHERE access_id=1";
		$query = mysql_query($sql);
		
		while($row = mysql_fetch_array($query)){
			$ids[] = $row['id'];
		}
		return $ids;
	}

	function getUserFullName($id){	
		$sql = "SELECT * FROM tbl_user WHERE id=".$id;
		$query = mysql_query($sql);

		$row = mysql_fetch_array($query);

		if($row['access_id']==6){
			$name = getStudentFullNameByUser($row['id']);
		}else{
			$name = getEmployeeFullNameByUserId($row['id']);
		}
		return $name;
	}

	function getStudentUser($id){	
		$sql = "SELECT * FROM tbl_student WHERE id=".$id;
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		return $row['user_id'];
	}

	function getParentUser($id){	
		$sql = "SELECT * FROM tbl_parent WHERE student_id=".$id;
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		return $row['user_id'];
	}

	function getUserAccessRole($user_id){

	}

	function getUserAccess($access_id){
		if($access_id != '' && isset($access_id)){
			$sql = "SELECT * FROM tbl_access WHERE id= ".GetSQLValueString($access_id,"text");
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['access_type'];
		}	
	}

	function checkIfUserCanAccessComp($comp_id){
		$sql = "SELECT COUNT(id) FROM tbl_access_role WHERE (can_view = 'Y' OR can_edit = 'Y' OR can_add = 'Y') AND component_id = $comp_id AND access_id = " . $_SESSION[CORE_U_CODE]['user_credentials']['access_id'] .";";
		$query = mysql_query($sql);

		if(mysql_result($query, 0, 0) > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkIfUserCanEditComp($comp_id){
		$sql = "SELECT * FROM tbl_access_role WHERE can_edit = 'N' AND can_add = 'N' AND component_id = $comp_id";
		$query = mysql_query($sql);

		if(mysql_num_rows($query) > 0){
			return true;
		}else{
			return false;
		}
	}

	function getSubItems($comp_id){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_components WHERE parent_id = $comp_id AND published = 'Y' ORDER BY sort_order";
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
			if(checkIfUserCanAccessComp($row['id']) === true){
				$parent_item =  array('id'			=>	$row['id'],
									'parent_id'		=>	$row['parent_id'],
									'name'			=>	$row['unique_friendly_title'],
									'caption'		=>	$row['title'],
									'sort_order'	=>	$row['sort_order']
								);

				if(!in_array($row['unique_friendly_title'],$_SESSION[CORE_U_CODE]['access_components'])){
					$_SESSION[CORE_U_CODE]['access_components'][] = $row['unique_friendly_title'];
				}

				if(checkIfUserCanEditComp($row['id'])){
					if(!in_array($row['unique_friendly_title'],$_SESSION[CORE_U_CODE]['can_edit_comp'])){
						$_SESSION[CORE_U_CODE]['can_edit_comp'][] = $row['unique_friendly_title'];
					}
				}

				if(count(getSubItems($row['id'])) > 0){					
					$arr_str[$row['unique_friendly_title']] = array_merge($parent_item,array('subItems' => getSubItems($row['id'])));
				}else{
					$arr_str[$row['unique_friendly_title']] = $parent_item;
				}
			}
		}
		return $arr_str;
	}	

	function checkIfStudentGradeExistPerPeriod($sched_id,$student_id,$period_id){
		if($sched_id != '' && $student_id != '' && $period_id !=''){
			$sql = "SELECT * FROM tbl_student_grade 
					WHERE schedule_id = $sched_id AND  period_id = $period_id AND student_id=$student_id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$ctr = mysql_num_rows($query);

			if($ctr > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfStudentFinalGradeExistPerTerm($subject_id,$term_id,$student_id){
		if($subject_id != '' && $student_id != '' && $term_id !=''){
			$sql = "SELECT * FROM tbl_student_final_grade 
					WHERE subject_id = $subject_id AND  term_id = $term_id AND student_id=$student_id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$ctr = mysql_num_rows($query);

			if($ctr > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfGradeSheetIslockedPerPeriodPerStud($sched_id,$student_id,$period_id){

		if(isset($sched_id) && $sched_id != '' && $student_id != ''){
			$sql = "SELECT is_locked FROM tbl_student_grade 
					WHERE schedule_id = $sched_id AND  period_id = $period_id AND student_id=$student_id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);

			if($row['is_locked'] == 'Y'){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfGradeSheetIslocked($sched_id,$period_id){
		if($sched_id != '' && $period_id != ''){
			$sql = "SELECT submission_is_locked FROM tbl_grade_submission 
					WHERE schedule_id = $sched_id AND professor_id = " . USER_EMP_ID . " AND period_id = " . $period_id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);

			if($row['submission_is_locked'] == 'Y'){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfAllPeriodExistPerStud($sched_id,$student_id){
		if($sched_id != '' && $student_id != ''){
			$term_id = getTermIdBySchedId($sched_id);
			$sql = "SELECT * FROM tbl_student_grade 
					WHERE schedule_id = $sched_id 
					AND student_id = $student_id 
					AND term_id = $term_id ";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$ctr = mysql_num_rows($query);

			if(countPeriodPerTerm($term_id) == $ctr){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfGradeIsEncodedPerPeriod($sched_id,$period_id){
		if($sched_id != '' && $period_id != ''){
			$sql = "SELECT * FROM tbl_grade_submission 
					WHERE schedule_id = $sched_id AND period_id = " . $period_id;
			$query = mysql_query($sql);
			$ctr = mysql_num_rows($query);

			if($ctr > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfGradeIsPass($grade){
		if($grade != ''){
			$sql = "SELECT is_grade_passing FROM tbl_grade_conversion 
					WHERE is_current='Y' AND '".round($grade)."' BETWEEN floor_grade AND ceiling_grade";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
		
			if(mysql_num_rows($query)>0){
				return $row['is_grade_passing'];
			}else{
				return 'N';
			}
		}
	}

	function checkIfStudentIsEnroll($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT enrollment_status FROM tbl_student_enrollment_status 
					WHERE student_id = $id AND term_id = " . CURRENT_TERM_ID;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);

			if($row['enrollment_status'] == 'E'){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfStudentDropAllSubjects($id){
		if(isset($id) && $id != ''){
			$sql = "SELECT * FROM tbl_student_schedule 
					WHERE student_id = ".$id." AND term_id = " . CURRENT_TERM_ID;
			$query = mysql_query($sql);
			$total = mysql_num_rows($query);
			$ctr = 0;

			while($row = mysql_fetch_array($query)){
				if($row['enrollment_status'] == 'D'){
					$ctr++;
				}
			}
		}

		if($ctr == $total){
			return true;
		}else{
			return false;
		}
	}

	function checkIfStudentIsEnrollByTerm($id,$term){
		if(isset($id) && $id != ''){
			$sql = "SELECT enrollment_status FROM tbl_student_enrollment_status 
					WHERE student_id = $id AND term_id = " . $term;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);

			if($row['enrollment_status'] == 'E'){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfScheduleIsInBlock($id,$block_id){
		if(isset($id) && $id != ''){
			$sql = "SELECT * FROM tbl_block_subject 
					WHERE schedule_id = $id AND block_section_id = " . $block_id;
			$query = mysql_query($sql);
			$ctr = mysql_num_rows($query);

			if($ctr > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfUserSessionIsActive(){
		$sql = "SELECT * FROM tbl_session_logs WHERE 
					session_id = ".GetSQLValueString($_SESSION[CORE_U_CODE]['logged_info']['log_session_id'],"text") ." AND
					user_id = ".GetSQLValueString($_SESSION[CORE_U_CODE]['user_credentials']['user_id'],"text");
		$query = mysql_query($sql);
		$ctr = mysql_num_rows($query);

		if($ctr > 0){
			return true;
		}else{
			return false;
		}
	}

	function setSessionData($username){
		if($username != '' && isset($username)){
			$sql = "SELECT * FROM tbl_user WHERE username= ".GetSQLValueString($username,"text");
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);	
							
			$_SESSION[CORE_U_CODE]['logged_info'] 	= array(
														'log_session_id'	=> session_id(),
														'log_status' 		=> 1,
														'log_timestamp' 	=> time(),
														'log_ip_address'	=> getUserIP()
													);
		
			$_SESSION[CORE_U_CODE]['user_credentials'] 	= array(
														'user_id' 			=> $row['id'],
														'username' 			=> $row['username'],
														'access_id'			=> $row['access_id'],
														'blocked'			=> $row['blocked'],
														'failed_logs'		=> $row['failed_logs'],
														'no_of_block_times'	=> $row['no_of_block_times'],
														'verification_code'	=> $row['verification_code'],
														'salt'				=> $row['salt'],
														'last_login'		=> $row['last_login'],
													);
		
			$sql_logs = "SELECT * FROM tbl_session_logs WHERE user_id= ".GetSQLValueString($row['id'],"int");
	
			$query_logs = mysql_query($sql_logs);
			$row_logs = mysql_fetch_array($query_logs);				
		
			$_SESSION[CORE_U_CODE]['user_credentials']['last_ip_connected'] 	=  $row_logs['ip_connected'];			
		

			if(in_array(getUserAccess($row['access_id']),array('A','E','C'))){
				$sql = "SELECT * FROM tbl_employee WHERE user_id = " . $row['id'];
				$query = mysql_query($sql);
				$row = mysql_fetch_array($query);

				$_SESSION[CORE_U_CODE]['user_info'] = array(
														professor_id => $row['id'],
														department_id => $row['department_id'],
														position_id => $row['position_id'],
														emp_id_number => $row['emp_id_number'],
														date_hired => $row['date_hired'],
														date_resigned => $row['date_resigned'],
														firstname => $row['firstname'],
														middlename => $row['middlename'],
														lastname => $row['lastname'],
														suffix => $row['suffix'],
														email => $row['email'],
														birth_date => $row['birth_date'],
														birth_place => $row['birth_place'],
														gender => $row['gender'],
														citizenship => $row['citizenship'],
														civil_status => $row['civil_status'],
														religion => $row['religion'],
														present_address => $row['present_address'],
														present_address_zip => $row['present_address_zip'],
														permanent_address => $row['permanent_address'],
														permanent_address_zip => $row['permanent_address_zip'],
														tel_number => $row['tel_number'],
														mobile_number => $row['mobile_number'],
														ice_fullname => $row['ice_fullname'],
														ice_address => $row['ice_address'],
														ice_tel_number => $row['ice_tel_number']
														);	
		 
				$_SESSION[CORE_U_CODE]['library'] = array(
														//username => $staff->getUsername(),
														userid => $row['user_id'],
														//token => $token,
														loginAttempts => 0,
														hasSuspendAuth => $row['suspended_flg'],
														hasAdminAuth => $row['admin_flg'],
														hasCircAuth => $row['circ_flg'],
														hasCircMbrAuth => $row['circ_mbr_flg'],
														hasCatalogAuth => $row['catalog_flg'],
														hasReportsAuth => $row['reports_flg']
														);
		
			}else if(getUserAccess($row['access_id']) == 'S'){
				$sql = "SELECT * FROM tbl_student WHERE user_id = " . $row['id'];
				$query = mysql_query($sql);
				$row = mysql_fetch_array($query);
				$_SESSION[CORE_U_CODE]['user_info'] = array(
														student_id => $row['id'],
														student_number => $row['student_number'],
														course_id => $row['course_id'],
														curriculum_id => $row['curriculum_id'],
														year_level => $row['year_level'],
														firstname => $row['firstname'],
														middlename => $row['middlename'],
														lastname => $row['lastname'],
														suffix => $row['suffix'],
														email => $row['email'],
														birth_date => $row['birth_date'],
														birth_place => $row['birth_place'],
														gender => $row['gender'],
														citizenship => $row['citizenship'],
														civil_status => $row['civil_status'],
														religion => $row['religion'],
														father_name => $row['father_name'],
														father_occupation => $row['father_occupation'],
														mother_name => $row['mother_name'],
														mother_occupation => $row['mother_occupation'],
														guardian_name => $row['guardian_name'],
														annual_family_income => $row['annual_family_income'],
														present_address => $row['present_address'],
														present_address_zip => $row['present_address_zip'],
														permanent_address => $row['permanent_address'],
														permanent_address_zip => $row['permanent_address_zip'],
														tel_number => $row['tel_number'],
														mobile_number => $row['mobile_number'],
														last_school => $row['last_school'],
														last_school_address => $row['last_school_address'],
														last_school_type => $row['last_school_type'],
														last_school_code => $row['last_school_code'],
														ice_fullname => $row['ice_fullname'],
														ice_address => $row['ice_address'],
														ice_tel_number => $row['ice_tel_number']
														);				
			}else if(getUserAccess($row['access_id']) == 'P'){
				$sql = "SELECT * FROM tbl_parent WHERE user_id = " . $row['id'];
				$query = mysql_query($sql);
				$row = mysql_fetch_array($query);
				$_SESSION[CORE_U_CODE]['user_info'] = array(
														guardian_id => $row['id'],
														student_id => $row['student_id'],
														firstname => $row['firstname'],
														lastname => $row['lastname'],
														email => $row['email']
														);				

			}else{
				throwUserLogs();
			}

			$sql = "SELECT * FROM tbl_components WHERE parent_id = 0 AND published = 'Y' ORDER BY sort_order";
			$query = mysql_query($sql);

			$_SESSION[CORE_U_CODE]['access_components'] = array();
			$_SESSION[CORE_U_CODE]['can_edit_comp'] = array();

			

			while($row = mysql_fetch_array($query)){
				if(checkIfUserCanAccessComp($row['id'])){
					$parent_item =  array(
										'id'			=>	$row['id'],
										'parent_id'		=>	$row['parent_id'],
										'name'			=>	$row['unique_friendly_title'],
										'caption'		=>	$row['title'],
										'sort_order'	=>	$row['sort_order']
										);
			
					if(count(getSubItems($row['id'])) > 0){						
						if(checkIfUserCanAccessComp($row['id'])){
							if(!in_array($row['unique_friendly_title'],$_SESSION[CORE_U_CODE]['access_components'])){
								$_SESSION[CORE_U_CODE]['access_components'][] = $row['unique_friendly_title'];		
							}

							if(checkIfUserCanEditComp($row['id'])){
								if(!in_array($row['unique_friendly_title'],$_SESSION[CORE_U_CODE]['can_edit_comp'])){
									$_SESSION[CORE_U_CODE]['can_edit_comp'][] = $row['unique_friendly_title'];
								}
							}
				
							$_SESSION[CORE_U_CODE]['user_menuItems'][$row['unique_friendly_title']] = array_merge($parent_item,array('subItems' => getSubItems($row['id'])));
						}
					}else{
						$_SESSION[CORE_U_CODE]['user_menuItems'][$row['unique_friendly_title']] = $parent_item;
					}
				}
			}			
		}
	}

	//print_r($_SESSION[CORE_U_CODE]['can_edit_comp']);

	/*
	function getCanAccessComponents($access_id){
		echo $sql = "SELECT * FROM tbl_access_role WHERE access_id =".$access_id;
		$query = mysql_query($sql);
		$_SESSION[CORE_U_CODE]['access_components'] = array();

		while($row = mysql_fetch_array($query)){
			echo $sqlacc = "SELECT * FROM tbl_components WHERE id =".$row['component_id'];
			$query = mysql_query($sqlacc);
			$rowacc = mysql_fetch_array($queryacc);
			$_SESSION[CORE_U_CODE]['access_components'][] = $rowacc['unique_friendly_title'];
		}
	}
	*/

	function setSystemSessionData(){
			$sql = "SELECT 
						school_name,
						school_address,
						school_city,
						school_postal,
						school_tel,
						school_fax,
						school_sys_email,
						school_sys_url,
						school_open_time,
						school_close_time
					FROM 
						tbl_school_settings";
							
			$query = mysql_query($sql);
			$row = @mysql_fetch_array($query);	
		
			$_SESSION[CORE_U_CODE]['school_settings'] 	= array(
															'school_name'		=> $row['school_name'],
															'school_address' 	=> $row['school_address'],
															'school_city' 		=> $row['school_city'],
															'school_postal' 	=> $row['school_postal'],
															'school_tel' 		=> $row['school_tel'],
															'school_fax' 		=> $row['school_fax'],
															'school_sys_email' 	=> $row['school_sys_email'],
															'school_sys_url' 	=> $row['school_sys_url'],
															'school_open_time' 	=> $row['school_open_time'],
															'school_close_time'	=> $row['school_close_time']);	
													
			$sql = "SELECT * FROM tbl_system_settings";
			$query = mysql_query($sql);
			$row = @mysql_fetch_array($query);	

			echo mysql_error();				

			$_SESSION[CORE_U_CODE]['system_settings'] 	= array(
															'activation_by'			=> $row['activation_by'],
															'max_login_attempt' 	=> $row['max_login_attempt'],
															'total_failed_login' 	=> $row['total_failed_login'],
															'time_to_relogin' 		=> $row['time_to_relogin'],
															'password_min' 			=> $row['password_min'],
															'password_max' 			=> $row['password_max'],
															'password_complexity' 	=> $row['password_complexity'],
															'allowed_sim_login' 	=> $row['allowed_sim_login'],
															'set_system' 			=> $row['set_system'],
															'default_record' 		=> $row['default_record'],
															'notification' 			=> $row['notification']);	

			$sql = "SELECT * FROM tbl_school_year WHERE is_current_sy = 'Y'";
			$query = mysql_query($sql);
			$row = @mysql_fetch_array($query);	
													
			$_SESSION[CORE_U_CODE]['current_sy_info'] 	= array(
															'current_sy_id'				=> $row['id'],
															'current_sy_start' 			=> $row['start_year'],
															'current_sy_end' 			=> $row['end_year'],
															'current_sy_number_of_term'	=> $row['number_of_term'],
															'current_sy_number_of_period'=> $row['number_of_period']);			
		
			$sql = "SELECT * FROM tbl_school_year_term WHERE is_current = 'Y' AND school_year_id = " . $row['id'];
			$query = mysql_query($sql);
			$row = @mysql_fetch_array($query);	
													
			$_SESSION[CORE_U_CODE]['current_term_info'] 	= array(
																'current_term_id'		=> $row['id'],
																'current_term_sy_id' 	=> $row['school_year_id'],);
		
			$sql = "SELECT * FROM tbl_school_year_period WHERE is_current = 'Y' AND term_id = " . $row['id'];
			$query = mysql_query($sql);
			$row = @mysql_fetch_array($query);	
													
			$_SESSION[CORE_U_CODE]['current_period_info'] 	= array(
																'current_period_id' 		=> $row['id'],
																'current_period_sy_id' 		=> $row['school_year_id'],
																'current_period_term_id'	=> $row['term_id'],
																'current_period_name'		=> $row['period_name'],
																'current_period_sub_start'	=> $row['start_of_submission'],
																'current_period_sub_end'	=> $row['end_of_submission'],
																'current_period_percentage'	=> $row['percentage'],
																'current_period_sub_locked'	=> $row['submission_is_locked'],);			
	}

	function userAuthentication(){
		if($_SESSION[CORE_U_CODE]['logged_info']['log_status'] === 1){
			if(!checkIfUserSessionIsActive()){
				session_unset();
				session_destroy();
				session_regenerate_id(true);
				echo '<script language="javascript">alert(\'Session expired. Please try to login again.\');window.location =\'index.php\';</script>';
			}else{
				define('USER_IS_LOGGED', 1);
			}
		}else{
			define('USER_IS_LOGGED', 0);	
		}
	}

	function setStudentSearch($course_id,$student_number,$lastname,$firstname,$middlename,$filter){												
			$_SESSION[CORE_U_CODE]['student_search'] 	= array(
															'course_id'		=> $course_id,
															'student_number'=> $student_number,
															'lastname' 		=> $lastname,
															'firstname' 	=> $firstname,
															'middlename' 	=> $middlename,
															'filter'		=> $filter
															);	
	}

	function incrementFormatedTime($time,$inc = '1'){												
		if(isset($time) && $time != ''){
			$time_arr = explode(':',$time);
			$inc_time = $time_arr[0] + $inc;

			if($inc_time <10)
				$inc_time  = "0".$inc_time;

			return $inc_time .':'.$time_arr[1];
		}
	}

	function clearStudentFilter(){												
		foreach($_SESSION[CORE_U_CODE]['student_search'] as $key => $val){
			unset($_SESSION[CORE_U_CODE]['student_search'][$key]);
		}
		unset($_SESSION[CORE_U_CODE]['student_search']);
	}

	function setProfSearch($emp_id_number,$lastname,$firstname,$middlename,$filter){												
		$_SESSION[CORE_U_CODE]['prof_search'] 	= array(
														'emp_id_number'	=> $emp_id_number,
														'lastname' 		=> $lastname,
														'firstname' 	=> $firstname,
														'middlename' 	=> $middlename,
														'filter'		=> $filter
														);	
	}

	function clearProfFilter(){												
		foreach($_SESSION[CORE_U_CODE]['prof_search'] as $key => $val){
			unset($_SESSION[CORE_U_CODE]['prof_search'][$key]);
		}
		unset($_SESSION[CORE_U_CODE]['prof_search']);
	}

	function setStudentBasicSearch($student_no_bsc){												
		$_SESSION[CORE_U_CODE]['student_basic_search'] 	= array(
																'student_no_bsc'=> $student_no_bsc
																);	
	}

	function clearStudentBasicFilter(){												
		foreach($_SESSION[CORE_U_CODE]['student_basic_search'] as $key => $val){
			unset($_SESSION[CORE_U_CODE]['student_basic_search'][$key]);
		}

		unset($_SESSION[CORE_U_CODE]['student_basic_search']);
	}

	function getDefaultStudentFeeTerm($student_id){
		$sql = "SELECT * FROM tbl_student_fees WHERE subject_id>0 AND student_id = ".$student_id;
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		
		$sqlf = "SELECT * FROM tbl_school_fee WHERE fee_type='perunitlec' AND amount=".$row['amount'];
		$queryf =mysql_query($sqlf);
		$rowf = mysql_fetch_array($queryf);
		
		if(mysql_num_rows($queryf)>0){
			return $rowf['term_id'];
		}else{
			return CURRENT_TERM_ID;
		}
	}

	function storeStudentFee($student_id,$term){
		$term!=''?$term=$term:$term=CURRENT_TERM_ID;
		$sql_del = "DELETE FROM tbl_student_fees WHERE 
					student_id = $student_id AND 
					term_id=".CURRENT_TERM_ID;
		$result_del = mysql_query($sql_del);

		$sql ="SELECT * FROM tbl_student_reserve_subject 
			WHERE 
				student_id=".$student_id ." AND 
				term_id = " . CURRENT_TERM_ID;	
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
			/* generate subject fees */
			if(getSubjType($row['subject_id']) == 'Lec'){
				$fee_id = getFeeTypeId('perunitlec',$term);
			}else if(getSubjType($row['subject_id']) == 'Lab'){
				$fee_id = getFeeTypeId('perunitlab',$term);
			}

			$sql = "INSERT INTO tbl_student_fees 
					(
						student_id, 
						school_year_id,
						term_id,
						subject_id,
						fee_id,
						amount,
						quantity
					)
					VALUES 
					(
						".GetSQLValueString($student_id,"int").",  
						".GetSQLValueString(CURRENT_SY_ID,"int").",  
						".GetSQLValueString(CURRENT_TERM_ID,"int").",  
						".GetSQLValueString($row['subject_id'],"int").",
						".GetSQLValueString($fee_id,"int").",
						".GetSQLValueString(getFeeAmount($fee_id),"text").",
						".GetSQLValueString($row['units'],"text")."
					)";	
			mysql_query($sql);
		}
	}

	function storeStudentAddSubjectFee($student_id,$sched){
		$sqldel = "SELECT * FROM tbl_schedule WHERE id = ".$sched."
					AND term_id =".CURRENT_TERM_ID;
		$query= mysql_query($sqldel);

		while($row = mysql_fetch_array($query)){
			/* generate subject fees */

			if(getSubjType($row['subject_id']) == 'Lec'){
				$fee_id = getFeeTypeId('perunitlec');
			}else if(getSubjType($row['subject_id']) == 'Lab'){
				$fee_id = getFeeTypeId('perunitlab');
			}

			$sql = "INSERT INTO tbl_student_fees 
					(
						student_id, 
						school_year_id,
						term_id,
						subject_id,
						fee_id,
						amount,
						quantity
					) 
					VALUES 
					(
						".GetSQLValueString($student_id,"int").",  
						".GetSQLValueString(CURRENT_SY_ID,"int").",  
						".GetSQLValueString(CURRENT_TERM_ID,"int").",  
						".GetSQLValueString($row['subject_id'],"int").",
						".GetSQLValueString($fee_id,"int").",
						".GetSQLValueString(getFeeAmount($fee_id),"text").",
						".GetSQLValueString(getSubjUnit($row["subject_id"]),"text")."
					)";	
			mysql_query($sql);
 		}
	}

	function storeStudentOtherFee($student_id,$term){
		$term!=''?$term=$term:$term=CURRENT_TERM_ID;
		$sql2 = "SELECT * FROM tbl_school_year_term WHERE id=".CURRENT_TERM_ID;
		$query2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($query2);
					
		if(strtolower($row2['school_term'])=='summer'){
			$term=CURRENT_TERM_ID;
		}

		$sql = "SELECT id FROM tbl_school_fee WHERE 
				fee_type = 'mc' AND 
				term_id=".$term;
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
			$sql = "INSERT INTO tbl_student_fees 
					(
						student_id, 
						school_year_id,
						term_id,
						fee_id,
						amount,
						quantity
					)
					VALUES 
					(
						".GetSQLValueString($student_id,"int").",  
						".GetSQLValueString(CURRENT_SY_ID,"int").",  
						".GetSQLValueString(CURRENT_TERM_ID,"int").",  
						".GetSQLValueString($row['id'],"int").",
						".GetSQLValueString(getFeeAmount($row['id']),"text").",
						".GetSQLValueString('1',"text")."
					)";	
			mysql_query($sql);					
		}		
	}

	function storeDefaultScheme($term,$id,$bid){
		$sql_sch = "SELECT * FROM tbl_payment_scheme
					WHERE term_id = ".$term;
		$query_sch = mysql_query($sql_sch);
		$row_sch = mysql_fetch_array($query_sch);
		
		$sql = "INSERT INTO tbl_student_enrollment_status 
				(	
					student_id, 
					term_id,
					scheme_id,
					enrollment_status,
					block_id,
					days_of_reservation,
					date_reserved,
					expiration_date
				) 
				VALUES 
				(
					".GetSQLValueString($id,"int").",  
					".GetSQLValueString($term,"int").",
					".GetSQLValueString($row_sch['id'],"int").",  
					".GetSQLValueString('R',"text").",
					".$bid.",
					0, 
					".GetSQLValueString(time(),"int").", 
					0 
				)";	
		mysql_query($sql);
	}

	function deductSlot($schedule_id){
		$sql_count = "SELECT * FROM tbl_schedule WHERE id = ".$schedule_id;
		$result_count = mysql_query($sql_count);
		$row = mysql_fetch_array($result_count);
		$number_of_student = $row['number_of_student'];
		$number_of_reserved = $row['number_of_reserved'];
		$reserved = $number_of_reserved + 1;
		$available = $number_of_student - $reserved;

		$sql = "UPDATE tbl_schedule 
				SET 
					number_of_reserved = ".$reserved.",
					number_of_available = ".$available."
				WHERE id = ".$schedule_id;
		mysql_query($sql);						
	}

	function reverseSlot($schedule_id){
		$sql_count = "SELECT * FROM tbl_schedule WHERE id = ".$schedule_id;
		$result_count = mysql_query($sql_count);
		$row = mysql_fetch_array($result_count);
		$number_of_student = $row['number_of_student'];
		$number_of_reserved = $row['number_of_reserved'];
		$reserved = $number_of_reserved - 1;
		$available = $number_of_student - $reserved;

		$sql = "UPDATE tbl_schedule 
				SET 
					number_of_reserved = ".$reserved.",
					number_of_available = ".$available."
				WHERE id = ".$schedule_id;
		mysql_query($sql);						
	}

	function computeSlot($schedule_id){
		$sql = "SELECT * FROM tbl_schedule WHERE id = $schedule_id";
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$student = $row['number_of_student'];
		$reserved = $row['number_of_reserved'];
		$avail = abs($student - $reserved);

		$sql = "UPDATE tbl_schedule SET number_of_available = ".$avail." ,number_of_reserved= ".$reserved."
				WHERE id = ".$schedule_id;	
		mysql_query($sql);						
	}
	
	function computeAllSlotByStudentSched($term_id=CURRENT_TERM_ID){
		$sql = "SELECT * FROM tbl_schedule WHERE term_id = ".$term_id;
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
		
			/*$sqls2 = "SELECT * FROM tbl_student_reserve_subject WHERE schedule_id=".$row['id']." AND term_id=".$term_id;
			$querys2 = mysql_query($sqls2);
			
			$all_rec2 = mysql_num_rows($querys2);*/
			
			$sqls = "SELECT * FROM tbl_student_schedule WHERE schedule_id=".$row['id']." AND term_id=".$term_id;
			$querys = mysql_query($sqls);
			
			$all_rec = mysql_num_rows($querys);
			
			if($all_rec+$all_rec2 > $row['number_of_student']){
				$reserved = $row['number_of_student'];
				$avail=0;
			}else{
				$reserved = $all_rec+$all_rec2;
				$avail = abs($row['number_of_student'] - $reserved);
			}
			//$student = $row['number_of_student'];

			//$reserved = $row['number_of_reserved'];

			//$avail = abs($student - $reserved);

			$sql = "UPDATE tbl_schedule SET number_of_available = ".$avail.", number_of_reserved = ".$reserved." 
					WHERE id = ".$row['id'];	
			
			mysql_query($sql);
		}					
	}

	function generateOR(){
		$sql = "SELECT or_no FROM tbl_student_payment ORDER BY or_no";
		$query = mysql_query($sql);
		$num = mysql_num_rows($query);
		$c = 1;
		
		while($row = mysql_fetch_array($query)){
			if($num == $c){
				$last = ($row['or_no']*1)+1;
			}
			$c++;
		}
		return $last;
	}

	function generateCalendarDays(){	
		$arr_str = array();
		$sql = "SELECT * FROM tbl_school_calendar WHERE publish = 'Y' AND schedule_id = '0'";
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query)){
			$from = explode("-",$row['date_from']);
			$to = explode("-",$row['date_to']);	
			$from_day = $from[2] * 1;
			$from_month = $from[1] * 1;	
			$to_day = $to[2] * 1;
			$to_month = $to[1] * 1;						

			if($to_day < from_day ){
				for($ctr=$from_day;$ctr=31;$ctr++){
					$arr_str[] = "[".$from_month.", ".$ctr.",'ev']";
				}	

				for($ctr=1;$ctr<=$to_day;$ctr++){
					$arr_str[] = "[".$from_month.", ".$ctr.",'ev']";
				}		
			}else{
				for($ctr=$from_day;$ctr<=$to_day;$ctr++){
					$arr_str[] = "[".$from_month.", ".$ctr.",'ev']";
				}
			}
		}

		if(ACCESS_ID == '6'){
			$sql = "SELECT * FROM tbl_school_calendar calendar, tbl_student_schedule stud_sched WHERE calendar.publish = 'Y' AND calendar.schedule_id <> '0' AND stud_sched.schedule_id = calendar.schedule_id AND stud_sched.student_id =" .USER_STUDENT_ID;
			$query = mysql_query($sql);
		}else if(ACCESS_ID == '7'){
			$sql = "SELECT * FROM tbl_school_calendar calendar, tbl_student_schedule stud_sched WHERE calendar.publish = 'Y' AND calendar.schedule_id <> '0' AND stud_sched.schedule_id = calendar.schedule_id AND stud_sched.student_id =" .STUDENT_ID;
			$query = mysql_query($sql);
		}else if(ACCESS_ID == '2'){
			$sql = "SELECT * FROM tbl_school_calendar calendar, tbl_schedule sched WHERE calendar.publish = 'Y' AND calendar.schedule_id <> '0' AND sched.id = calendar.schedule_id AND sched.employee_id =" .USER_EMP_ID;
			$query = mysql_query($sql);
		}else if(ACCESS_ID == '5'){
			$sql = "SELECT * FROM tbl_school_calendar WHERE publish = 'Y' AND schedule_id = '0'";
			$query = mysql_query($sql);
		}else{
			$sql = "SELECT * FROM tbl_school_calendar WHERE publish = 'Y' AND schedule_id <> '0'";
			$query = mysql_query($sql);
		}

		while($row = mysql_fetch_array($query)){
			$from = explode("-",$row['date_from']);
			$to = explode("-",$row['date_to']);	
			$from_day = $from[2] * 1;
			$from_month = $from[1] * 1;	
			$to_day = $to[2] * 1;
			$to_month = $to[1] * 1;						

			if($to_day < from_day ){
				for($ctr=$from_day;$ctr=31;$ctr++){
					$arr_str[] = "[".$from_month.", ".$ctr.",'ev']";
				}	

				for($ctr=1;$ctr<=$to_day;$ctr++){
					$arr_str[] = "[".$from_month.", ".$ctr.",'ev']";
				}		
			}else{
				for($ctr=$from_day;$ctr<=$to_day;$ctr++){
					$arr_str[] = "[".$from_month.", ".$ctr.",'ev']";
				}
			}
		}
		return "[".implode(",",$arr_str)."]";
	}

	function getSchedIdCalendar($date){
		if($date != ''){
			$sched = array();
			$sql = "SELECT * FROM tbl_school_calendar WHERE publish = 'Y' AND schedule_id = '0'";
			$query = mysql_query($sql);

			while($row = mysql_fetch_array($query)){
				$sched[] = $row['schedule_id'];
			}

			if(ACCESS_ID == '6'){
					$sqlsch = "SELECT * FROM tbl_school_calendar calendar, tbl_student_schedule stud_sched WHERE calendar.publish = 'Y' AND calendar.schedule_id <> '0' AND stud_sched.schedule_id = calendar.schedule_id AND stud_sched.student_id =" .USER_STUDENT_ID;
					$querysch = mysql_query($sqlsch);
			}else if(ACCESS_ID == '7'){
				$sqlsch = "SELECT * FROM tbl_school_calendar calendar, tbl_student_schedule stud_sched WHERE calendar.publish = 'Y' AND calendar.schedule_id <> '0' AND stud_sched.schedule_id = calendar.schedule_id AND stud_sched.student_id =" .STUDENT_ID;
				$querysch = mysql_query($sqlsch);
			}else if(ACCESS_ID == '2'){
				$sqlsch = "SELECT * FROM tbl_school_calendar calendar, tbl_schedule sched WHERE calendar.publish = 'Y' AND calendar.schedule_id <> '0' AND sched.id = calendar.schedule_id AND sched.employee_id =" .USER_EMP_ID;
				$querysch = mysql_query($sqlsch);
			}else if(ACCESS_ID == '5'){
				$sqlsch = "SELECT * FROM tbl_school_calendar WHERE publish = 'Y' AND schedule_id = '0'";
				$querysch = mysql_query($sqlsch);
			}else{
				$sqlsch = "SELECT * FROM tbl_school_calendar WHERE publish = 'Y' AND schedule_id <> '0'";
				$querysch = mysql_query($sqlsch);
			}

			while($rowsch = mysql_fetch_array($querysch)){
				$sched[] = $rowsch['schedule_id'];
			}

			//print_r($sched);
			return $sched;
		}
	}

	function checkExpirationOfAllReservation(){
		$sql="SELECT * FROM tbl_student_enrollment_status 
				WHERE 
					enrollment_status = 'R' AND 
					expiration_date <= '" .mktime(0, 0, 0, date("m") , date("d"), date("Y")). "' AND 
					term_id = " . CURRENT_TERM_ID;
		$query = mysql_query($sql);
		$ctr = @mysql_num_rows($query);

		if($ctr > 0){
			while($row = mysql_fetch_array($query)){

			//TEMPORARY DISABLED
/*
				//[+] Re-credit Slot
				$sql_slot = "SELECT schedule_id FROM tbl_student_reserve_subject WHERE 
								student_id = ".$row['student_id']." AND 
								term_id = ".$row['term_id'];
								$query_slot = mysql_query($sql_slot);	
				
				while($row_slot = mysql_fetch_array($query_slot)){
					reverseSlot($row_slot['schedule_id']);
				}
				// [-] Re-credit Slot 

				// [+] Clear reservation 
				$sqldel = "DELETE FROM tbl_student_reserve_subject WHERE 
								student_id= ".$row['student_id']." AND 
								term_id =".$row['term_id'];
								$resdel= mysql_query($sqldel);		
				// [-] Clear reservation 

				// [+] Clear Fees 
				$sqldel = "DELETE FROM tbl_student_fees WHERE 
								student_id= ".$row['student_id']." AND 
								term_id =".$row['term_id'];
							$resdel= mysql_query($sqldel);		
				// [-] Clear Fees 				
			
				$sql_exp = "UPDATE tbl_student_enrollment_status SET 
								enrollment_status = 'EXP'
							WHERE id = " . $row['id'];
				mysql_query($sql_exp);

				//Accounting clear
				//deleteOrdAndTrans($row['student_id'],$row['term_id']);
*/
			}
		}
	}

	function getTimeToRelogin(){
		$sql = "SELECT * FROM tbl_system_settings";
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$time_to_relogin = $row['time_to_relogin'];	
		return $time_to_relogin;
	}

	function getTimeToReloginWithStr(){
		$sql = "SELECT * FROM tbl_system_settings";
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$time_to_relogin = $row['time_to_relogin'];	
		return $time_to_relogin . ' minutes';
	}	

	function getRemainingMinutesTologin($username){
		$sql = "SELECT * FROM tbl_user 
				WHERE username= ".GetSQLValueString($username,"text");
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$last_failed_login = $row['last_failed_login'];
		return getTimeToRelogin() - ((date('i',time()) * 1) - (date('i',$last_failed_login) * 1));	
	}

	function checkIfUserCanLogin($username){
		$sql = "SELECT * FROM tbl_user WHERE username= ".GetSQLValueString($username,"text");
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$last_failed_login = $row['last_failed_login'];

		if($last_failed_login != '' && $last_failed_login != '0'){
			$remaining_min = (date('i',time()) * 1) - (date('i',$last_failed_login) * 1);
			if($remaining_min >= getTimeToRelogin()){
				return true;
			}else{
				$sql = "UPDATE tbl_user 
						SET last_failed_login  = ''		
						WHERE username= ".GetSQLValueString($username,"text");	
				mysql_query ($sql);
				return false;
			}
		}else{
			return true;
		}
	}

	function clearUserFailedLoginHistory($username){
		$sql = "UPDATE tbl_user SET 
					failed_logs  = '0',
					last_failed_login = '0',
					no_of_block_times	= '0'
				WHERE username= ".GetSQLValueString($username,"text");	
		mysql_query ($sql);
	}

	function storeLoginFailedLoginAttemp($username){
		$sql = "SELECT * FROM tbl_user 
				WHERE username= ".GetSQLValueString($username,"text");
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$failed_logs_ctr = $row['failed_logs'] + 1;
		$no_of_block_times_ctr = $row['no_of_block_times'] + 1;

		$sql = "UPDATE tbl_user SET 
					failed_logs  = ".$failed_logs_ctr ."		
				WHERE username= ".GetSQLValueString($username,"text");	
		mysql_query ($sql);

		$sql = "SELECT * FROM tbl_system_settings";				
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$max_login_attempt = $row['max_login_attempt'];	
		$total_failed_login = $row['total_failed_login'];	
		
		if($failed_logs_ctr == $max_login_attempt){
			$sql = "UPDATE tbl_user SET 
						no_of_block_times  = ".$no_of_block_times_ctr .",
						failed_logs  = 0,
						last_failed_login = ". time()."		
					WHERE username= ".GetSQLValueString($username,"text");	
			mysql_query ($sql);

			if($no_of_block_times_ctr >= $total_failed_login){
				$sql = "UPDATE tbl_user SET 
						blocked  = 1		
					WHERE username= ".GetSQLValueString($username,"text");	

				mysql_query ($sql);

				echo '<script language="javascript">alert("Your account has been blocked, due to consecutive failed login attempts.\nPlease contact the administrator.");</script>';

			}

		}else{
			return false;
		}		
	}
/* [-] ALL SYSTEM FUNCTION */

/* [+] ALL VALIDATOR FUNCTION */
	function checkIfEnrollmentIsOpenForCourse($course_id){
		if(isset($course_id) && $course_id != ''){
			$sql = "SELECT * FROM tbl_enrollment_date 
					WHERE 
						('" . date("Y-m-d") . "' BETWEEN start_date AND end_date) AND  
						school_year_id = '".CURRENT_SY_ID."' AND 
						term_id = '".CURRENT_TERM_ID."' AND  
						course_id = " .$course_id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			if(mysql_num_rows($query) > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfSubmissionGradeIsOpenPerPeriod($period_id){
		if(isset($period_id) && $period_id != ''){
			$sql = "SELECT * FROM tbl_school_year_period 
					WHERE 
						('" . date("Y-m-d") . "' BETWEEN start_of_submission AND end_of_submission) AND  
						id = " .$period_id;
			$query = mysql_query($sql);		
			$row = mysql_fetch_array($query);
			if(mysql_num_rows($query) > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfStudentIsEnrolled(){
		$sql = "SELECT count(*) FROM tbl_student_enrollment_status 
				WHERE 
					enrollment_status = 'E' AND
					term_id = " . CURRENT_TERM_ID . " AND
					student_id= " .USER_STUDENT_ID;
		$query = mysql_query($sql);
		if(mysql_result($query, 0, 0) > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkIfStudentIsReserve($id){
		$sql = "SELECT count(*) FROM tbl_student_enrollment_status 
				WHERE 
					enrollment_status = 'R' AND
					term_id = " . CURRENT_TERM_ID . " AND
					student_id= " .$id;
		$query = mysql_query($sql);
		if(mysql_result($query, 0, 0) > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkIfStudentIsReserveByStudId($id){
		if(isset($id) && $id != ''){	
			$sql = "SELECT count(*) FROM tbl_student_enrollment_status 
					WHERE 
						term_id = " . CURRENT_TERM_ID . " AND
						student_id= " .$id;
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfStudentReservationIsExpired(){
		$sql = "SELECT count(*) FROM tbl_student_enrollment_status 
				WHERE 
					enrollment_status = 'EXP' AND
					term_id = " . CURRENT_TERM_ID . " AND
					student_id= " .USER_STUDENT_ID;
		$query = mysql_query($sql);
		if(@mysql_result($query, 0, 0) > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkIfStudentReservationIsExpiredByStudId($id){
		$sql = "SELECT count(*) FROM tbl_student_enrollment_status 
				WHERE 
					enrollment_status = 'EXP' AND
					term_id = " . CURRENT_TERM_ID . " AND
					student_id= " .$id;
		$query = mysql_query($sql);
		if(@mysql_result($query, 0, 0) > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkIfSchoolFeeIsComplete($id){
		$sql = "SELECT * FROM tbl_school_fee WHERE publish = 'Y' AND term_id = ".CURRENT_TERM_ID;
		$query = mysql_query($sql);
		while($row = @mysql_fetch_array($query)){
			if($row['fee_type']=='perunitlec'){
				$lec = $row['id'];
			}

			if($row['fee_type']=='perunitlab'){
				$lab = $row['id'];
			}
		}
			
		if($lec == '' && $lab == ''){
			return true;
		}else{
			return false;
		}
	}

	function checkIfStudentApplicantSchoolYear($school_id,$term_id){
		$yir = getSchoolYearStartEnd($school_id);
		if($yir <= CURRENT_SY_START.'-'.CURRENT_SY_END && $term_id <= CURRENT_TERM_ID){
			return true;
		}else{
			return false;
		}
	}

	function checkIfSchoolYearExist($start,$end, $id = ''){
		if($start != '' && $end != '' && $id == ''){
			$sql = "SELECT count(*) FROM tbl_school_year 
					WHERE 
						(start_year <= $start AND end_year > $start ) OR 
						(start_year <= $end AND end_year > $end )";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}
		}else{
			$sql = "SELECT count(*) FROM tbl_school_year 
					WHERE 
						((start_year <= $start AND end_year > $start ) OR (start_year <= $end AND end_year > $end ))
						AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}
		}	
	}

	function checkIfSYPeriodIsComplete($id){
		$sql = "SELECT * FROM tbl_school_year WHERE id = $id";
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$ctr_period = $row['number_of_period'];
		$ctr_term = $row['number_of_term'];
		$sqlp = "SELECT * FROM tbl_school_year_period WHERE school_year_id= " .$id;
		$queryp = mysql_query($sqlp);
		$periods = mysql_result($query, 0, 0);
		$per = 0;
		$ctr = 0;
		$peri = 0;

		while($rowp = mysql_fetch_array($queryp)){
			if($per == 0 || $peri == $rowp['term_id']){
				$ctr++;
				$peri = $rowp['term_id'];
			}
			$per++;
		}

		if($ctr == $ctr_period){
			return true;
		}else{
			return false;
		}
	}

	function checkStudentGradesIsComplete($id){
		$grd = array();
		$sql_sched = "SELECT * FROM tbl_schedule schedule WHERE term_id =".$id;
		$result_sched = mysql_query($sql_sched);

		while($row_sched = @mysql_fetch_array($result_sched)){
			$subj = getSubjIdBySchedule($row_sched['id']);
			$sql_grade = "SELECT student_id,schedule_id FROM tbl_student_schedule 
							WHERE term_id = ".$id." AND schedule_id = ".$row_sched['id'];						
			$query_grade = mysql_query($sql_grade);

			while($row_grade = @mysql_fetch_array($query_grade)){
				$sql_period = "SELECT * FROM tbl_school_year_period 
								WHERE 
									term_id=".$id." AND 
									start_of_submission < '" .  date("Y-m-d") . "'
								ORDER BY period_order";						
				$query_period = mysql_query($sql_period);
				while($row_period = @mysql_fetch_array($query_period)){
					$sql_emp = "SELECT * FROM tbl_student_grade 
								WHERE student_id = ".$row_grade["student_id"]." AND
									schedule_id = ".$row_grade["schedule_id"]." AND 
									period_id = ".$row_period["id"]." AND term_id = ".$id;
					$query_emp = mysql_query($sql_emp);
					$row_emp = @mysql_fetch_array($query_emp);

					if(mysql_num_rows($query_emp) == 0){
						$grd[]=$row_grade["schedule_id"];
					}
				}
			}
		}	

		if(count($grd) > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkIfSYIfActivePeriodIsSet($id){
		$sql = "SELECT * FROM tbl_school_year WHERE id = $id";
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$ctr_period = $row['number_of_period'];
		$sql = "SELECT count(*) FROM tbl_school_year_period WHERE school_year_id= " .$id;
		$query = mysql_query($sql);

		if(mysql_result($query, 0, 0) == $ctr_period){
			return true;
		}else{
			return false;
		}
	}	

	function checkStudentHasRecord($id){
		if($id != ''){
			$ctr_sked 		="";
			$ctr_grade		="";
			$ctr_reserve	="";
			$sql_sked 		= "SELECT * FROM tbl_student_schedule WHERE student_id = " . $id;
			$sql_grade 		= "SELECT * FROM tbl_student_grade WHERE student_id = " . $id;
			$sql_reserve	= "SELECT * FROM tbl_student_reserve_subject WHERE student_id = " . $id;
			$qry_sked		= mysql_query($sql_sked);
			$qry_grade		= mysql_query($sql_grade);
			$qry_reserve	= mysql_query($sql_reserve);
			$ctr_sked		= mysql_num_rows($qry_sked);
			$ctr_grade		= mysql_num_rows($qry_grade);
			$ctr_reserve	= mysql_num_rows($qry_reserve);

			if (($ctr_sked > 0 ) || ($ctr_grade > 0) || ($ctr_reserve > 0)){
				return true;
			}else{
				return false;
			}
		}	
	}

	function checkEmployeeHasRecord($id){
		if($id != ''){
			$ctr_student_grade 		="";
			$ctr_sked				="";
			$ctr_department			="";
			$ctr_employee_avail		="";
			$ctr_college			="";
			$sql_student_grade 		= "SELECT * FROM tbl_grade_submission WHERE professor_id = " . $id;
			$sql_sked				= "SELECT * FROM tbl_schedule WHERE employee_id = " . $id;
			$sql_department			= "SELECT * FROM tbl_department WHERE employee_id = " . $id;
			$sql_employee_avail		= "SELECT * FROM tbl_employee_availability WHERE employee_id = " . $id;
			$sql_college			= "SELECT * FROM tbl_college WHERE emp_id = ". $id;
			$sql_employee			= "SELECT * FROM tbl_employee WHERE created_by = ". $id . " OR modified_by = ". $id;	
			$sql_student			= "SELECT * FROM tbl_student WHERE created_by = ". $id . " OR modified_by = ". $id;	
			$sql_payment			= "SELECT * FROM tbl_student_payment WHERE created_by = ". $id . " OR modified_by = ". $id;						
			$qry_student_grade		= mysql_query($sql_student_grade);
			$qry_sked				= mysql_query($sql_sked);
			$qry_department			= mysql_query($sql_department);
			$qry_employee_avail		= mysql_query($sql_employee_avail);
			$qry_college			= mysql_query($sql_college);
			$qry_employee			= mysql_query($sql_employee);	
			$qry_student			= mysql_query($sql_student);	
			$qry_payment			= mysql_query($sql_payment);						
			$ctr_student_grade		= mysql_num_rows($qry_student_grade);
			$ctr_sked				= mysql_num_rows($qry_sked);
			$ctr_department			= mysql_num_rows($qry_department);
			$ctr_employee_avail		= mysql_num_rows($qry_employee_avail);
			$ctr_college			= mysql_num_rows($qry_college);
			$ctr_employee			= mysql_num_rows($qry_employee);
			$ctr_student			= mysql_num_rows($qry_student);	
			$ctr_payment			= mysql_num_rows($qry_payment);					

			if (($ctr_student_grade > 0 ) || ($ctr_sked > 0) || ($ctr_department > 0) || ($ctr_employee_avail > 0) || ($ctr_college > 0) || ($ctr_employee > 0) || ($ctr_student > 0)  || ($ctr_payment > 0) ){
				return true;
			}else{
				return false;
			}
		}	
	}

	function checkIfSYIsAlreadyUsed($id){
		if(isset($id) && $id!=''){
			$sql_term = "SELECT * FROM tbl_school_year_term WHERE school_year_id = $id";
			$query_term = mysql_query($sql_term);
			while($row_term = mysql_fetch_array($query_term)){
				$sql = "SELECT count(*) FROM tbl_school_year_period WHERE term_id = " . $row_term['id'];
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}	

				$sql = "SELECT count(*) FROM tbl_schedule WHERE term_id = " . $row_term['id'];
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}	

				$sql = "SELECT count(*) FROM tbl_student_enrollment_status WHERE term_id = " . $row_term['id'];
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}	

				$sql = "SELECT count(*) FROM tbl_student_final_grade WHERE term_id = " . $row_term['id'];
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}

				$sql = "SELECT count(*) FROM tbl_student_grade WHERE term_id = " . $row_term['id'];
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}										

				$sql = "SELECT count(*) FROM tbl_student_payment WHERE term_id = " . $row_term['id'];
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}	
			
				$sql = "SELECT count(*) FROM tbl_student_reserve_subject WHERE term_id = " . $row_term['id'];
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}

				$sql = "SELECT count(*) FROM tbl_student_schedule WHERE term_id = " . $row_term['id'];
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}	
			}
			return false;					
		}
	}

	function checkIfSchoolYearCanDoAction($id){
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_school_year_period 
					WHERE school_year_id = $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return false;
			}else{
				return true;
			}
		}	
	}

	function checkIfBuildingExist($building_code, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_building 
					WHERE building_code = ".GetSQLValueString($building_code,"text") ."AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($building_code) && $building_code!=''){
				$sql = "SELECT count(*) FROM tbl_building 
						WHERE building_code = ".GetSQLValueString($building_code,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfBuildingNameExist($building_name, $id=""){
		/* IF $id exist check for edit validation */

		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_building 
					WHERE building_name = ".GetSQLValueString($building_name,"text") ."AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($building_name) && $building_name!=''){
				$sql = "SELECT count(*) FROM tbl_building 
						WHERE building_name = ".GetSQLValueString($building_name,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfCollegeExist($college_name, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_college 
					WHERE college_name = ".GetSQLValueString($college_name,"text") ."AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($college_name) && $college_name!=''){
				$sql = "SELECT count(*) FROM tbl_college 
						WHERE college_name = ".GetSQLValueString($college_name,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfCollegeCodeExist($college_code, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_college 
					WHERE college_code = ".GetSQLValueString($college_code,"text") ."AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($college_name) && $college_name!=''){
				$sql = "SELECT count(*) FROM tbl_college 
						WHERE college_code = ".GetSQLValueString($college_code,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfCourseCodeExist($course_code, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_course 
					WHERE course_code = ".GetSQLValueString($course_code,"text") ."AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($course_code) && $course_code!=''){
				$sql = "SELECT count(*) FROM tbl_course 
						WHERE course_code = ".GetSQLValueString($course_code,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfCurriculumCourseCodeExist($curriculum_code, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_curriculum 
					WHERE curriculum_code = ".GetSQLValueString($curriculum_code,"text") ."AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($curriculum_code) && $curriculum_code!=''){
				$sql = "SELECT count(*) FROM tbl_curriculum 
						WHERE curriculum_code = ".GetSQLValueString($curriculum_code,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfPaySchemeNameExist($name, $term_id, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_payment_scheme 
					WHERE 
						name = ".GetSQLValueString($name,"text") .
						"AND term_id = ".$term_id.
						"AND id <> $id";
			$query = mysql_query($sql);
			if(@mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($name) && $name!=''){
				$sql = "SELECT count(*) FROM tbl_payment_scheme 
						WHERE 
							name = ".GetSQLValueString($name,"text").
							"AND term_id = ".$term_id;
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfSchemePaymentNameExist($payment_name, $id=""){
		$sql_ps = "SELECT * FROM tbl_payment_scheme as pay_scheme, tbl_payment_scheme_details as pay_scheme_det
					WHERE pay_scheme.id = pay_scheme_det.scheme_id
					AND pay_scheme_det.scheme_id = $id";
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_payment_scheme_details 
					WHERE payment_name = ".GetSQLValueString($payment_name,"text") ."AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($name) && $name!=''){
				$sql = "SELECT count(*) FROM tbl_payment_scheme 
						WHERE name = ".GetSQLValueString($name,"text")."AND term_id <> $term_id";
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function getStudentPaymentScheme($id,$amount){
		$sql = "SELECT * FROM tbl_payment_scheme_details WHERE id = ".$id;
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)){
			if($row['payment_type']=='P'){
				$fee = ($amount * $row['payment_value']) / 100;
			}
		}
		return $fee;
	}

	function checkPaymentIfCheque($id){	
		$sqlUp = "SELECT * FROM tbl_student_payment 
				WHERE check_no <> 0 AND bank <> 'none' AND is_bounced <> 'Y' 
				AND is_refund <> 'Y' AND student_id = ".$id." 
				AND term_id =".CURRENT_TERM_ID;
		$queryUp = mysql_query($sqlUp);
		if(mysql_num_rows($queryUp)){
			return true;
		}else{
			return false;
		}
	}

	function checkIfCurriculumSubjectExist($subject_id, $curriculum_id, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			 $sql = "SELECT count(*) FROM tbl_curriculum_subject 
					WHERE 
						subject_id = ".GetSQLValueString($subject_id,"int") . 
						" AND curriculum_id = ".GetSQLValueString($curriculum_id,"int").
						" AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if((isset($subject_id) && $subject_id!='') ||(isset($curriculum_id) && $curriculum_id!='')){
				$sql = "SELECT count(*) FROM tbl_curriculum_subject 
						WHERE subject_id = ".GetSQLValueString($subject_id,"int") .
						" AND curriculum_id = ".GetSQLValueString($curriculum_id,"int");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkCurriculumHasCurrent(){
		$sql = "SELECT * FROM tbl_curriculum WHERE is_current = 'Y'";
		$query = mysql_query($sql);
		if(mysql_num_rows($query) > 0){
			return true;
		}else{
			return false;
		}
	}

	function checkCurriculumSubjectIsSet(){
		$sql = "SELECT * FROM tbl_curriculum WHERE is_current = 'Y'";
		$query = mysql_query($sql);
		$row = @mysql_fetch_array($query);
		$ctr = 1;
		$cnt = 1;
		$arr = array();
		for($ctr=1;$ctr<=$row['no_of_years'];$ctr++){
			for($cnt=1;$cnt<=$row['term_per_year'];$cnt++){
				$arr[] = $ctr.'-'.$cnt;
			}
		}

		$sqlSub = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = ".$row['id'];
		$querySub = mysql_query($sqlSub);
		$ctr1 = 1;
		$cnt1 = 1;
		$chk = array();

		while($rowSub=@mysql_fetch_array($querySub)){
			for($ctr1=1;$ctr1<=$row['no_of_years'];$ctr1++){
				for($cnt1=1;$cnt1<=$row['term_per_year'];$cnt1++){
					if($rowSub['year_level']==$ctr1&&$rowSub['term']==$cnt1){
						if(!in_array($rowSub['year_level'].'-'.$rowSub['term'],$chk)){
							$chk[]=$rowSub['year_level'].'-'.$rowSub['term'];
						}
					}
				}
			}
		}
		//print_r($arr);
		//print_r($chk);
		if(count($arr)==count($chk)){
			return true;
		}else{
			return false;
		}
	}

	
	/*
	function checkIfPaymentValueIs100($payment_value, $payment_type){
		foreach ($payment_type as $pay_type){
			echo $pay_type;
			if($pay_type == 'P'){
				$total_payment = 0; 
				foreach($payment_value as $pay_value){  	
					echo $pay_value;	
					$p_value = $pay_value;
					$total_payment = $total_payment + $p_value;  	
				}

				if($total_payment >= '101'){
					return true;
				}else{
					return false;
				}
			}		
		}
	}
	*/

	function checkIfPaymentValueIs100($payment_value, $payment_type){
		$total = 0;
		while ((list($key1, $val1) = each($payment_type)) && (list($key2, $val2) = each($payment_value))){
			if($val1 == 'P'){	
				$x = array($val2);
				foreach ($x as $value){
					$total = $total + $value;
				}			 
			}
		}

		if($total >= '101'){
			return true;
		}else{
			return false;
		}
	}

	function checkIfPaymentValueNot100($payment_value, $payment_type){
		$total = 0;
		while ((list($key1, $val1) = each($payment_type)) && (list($key2, $val2) = each($payment_value))){
			if($val1 == 'P'){	
				$x = array($val2);
				foreach ($x as $value){
					$total = $total + $value;
				}			 
			}
		}

		if($total < '100'){
			return true;
		}else{
			return false;
		}
	}

	function checkIfPaymentNameIsExist($payment_name){
		$result = array_unique(array_diff_assoc($payment_name,array_unique($payment_name))); 
		while ((list($key1, $val1) = each($result))){
			$x = $val1;
		}

		if($x != ''){
			return true;
		}else{
			return false;
		}
	}

	function checkIfPaymentDateIsExist($payment_date){
		$result = array_unique(array_diff_assoc($payment_date,array_unique($payment_date))); 
		while ((list($key1, $val1) = each($result))){
			$x = $val1;
		}

		if($x != ''){
			return true;
		}else{
			return false;
		}
	}

	function checkIfCurriculumIsAlreadyUsed($curriculum_id){
		/* IF $id exist check for edit validation */
		if(isset($curriculum_id) && $curriculum_id!=''){
			$sql = "SELECT count(*) FROM tbl_student 
					WHERE curriculum_id = ".GetSQLValueString($curriculum_id,"int");
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}
	}

	function checkIfScheduleExist($subject_id, $section_no, $term_id,  $id=""){

		/* IF $id exist check for edit validation */

		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_schedule 
					WHERE 
						section_no = ".GetSQLValueString($section_no,"text").
						" AND term_id = ".GetSQLValueString($term_id,"int").
						" AND id <> $id";

			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if((isset($subject_id) && $subject_id!='') ||(isset($section_no) && $section_no!='') || (isset($term_id) && $term_id!='') ){
				$sql = "SELECT count(*) FROM tbl_schedule
						WHERE 
							section_no = ".GetSQLValueString($section_no,"text").
							" AND subject_id = ".GetSQLValueString($subject_id,"int").
							" AND term_id = ".GetSQLValueString($term_id,"int");

				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkBlockSubjectExist($id){
		if($id != ''){
			$sqlblok='SELECT * FROM tbl_block_subject WHERE block_section_id = '.$id;
			$query = mysql_query($sqlblok);
			$row = mysql_num_rows($query);
			if($row > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfScheduleTemplateExist($template_name,  $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_schedule_template
					WHERE template_name = ".GetSQLValueString($template_name,"text")." AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if((isset($template_name) && $template_name!='')){
				$sql = "SELECT count(*) FROM tbl_schedule_template
						WHERE template_name = ".GetSQLValueString($template_name,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfSubjectScheduleExist($arrID){
		/* IF subject duplicates validation */
		$arr = array();
		foreach($arrID as $ids){
			$sql = "SELECT subject_id FROM tbl_schedule WHERE id = " .$ids;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$arr[]=$row['subject_id'];
		}

		$uniques = array_unique($arr);
		$dups = array_diff_assoc($arr, $uniques);
		if(count($dups) > 0){
			return true;
		}else{
			return false;
		}		
	}

	function checkIfScheduleIsInReserve($section_no, $term_id,  $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_schedule as sched, tbl_student_reserve_subject as stud_reserve
					WHERE
						sched.id = stud_reserve.schedule_id 
						AND sched.term_id = ".GetSQLValueString($term_id,"int").
						" AND sched.id = $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}			
		}
	}

	function checkIfScheduleIsInStudentSched($section_no, $term_id,  $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM 
						tbl_schedule as sched, 
						tbl_student_schedule as stud_sched
					WHERE
						sched.id = stud_sched.schedule_id AND 
						sched.term_id = ".GetSQLValueString($term_id,"int")." AND
						stud_sched.schedule_id = $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}			
		}
	}

	function checkGradeConversionExist($floor_grade, $ceiling_grade, $grade_code, $is_grade_passing, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			 $sql = "SELECT count(*) FROM tbl_grade_conversion
					WHERE 
						floor_grade = ".GetSQLValueString($floor_grade,"text") . 
						" AND ceiling_grade = ".GetSQLValueString($ceiling_grade,"text").
						" AND grade_code = ".GetSQLValueString($grade_code,"text").
						" AND is_grade_passing = ".GetSQLValueString($is_grade_passing,"text").
						" AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if((isset($floor_grade) && $floor_grade!='') ||(isset($ceiling_grade) && $ceiling_grade!='') || (isset($grade_code) && $grade_code!='') || (isset($is_grade_passing) && $is_grade_passing!='')){
				$sql = "SELECT count(*) FROM tbl_grade_conversion
						WHERE 
							floor_grade = ".GetSQLValueString($floor_grade,"text") .
							" AND ceiling_grade = ".GetSQLValueString($ceiling_grade,"text").
							" AND grade_code = ".GetSQLValueString($grade_code,"text") .
							" AND is_grade_passing = ".GetSQLValueString($is_grade_passing,"text") .
							" AND letter = ".GetSQLValueString($letter,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkDateOfEnrollmentExist($start_date, $end_date, $school_year_id, $term_id, $course_id,  $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			 $sql = "SELECT count(*) FROM tbl_enrollment_date
					WHERE 
						start_date = ".GetSQLValueString($start_date,"date") . 
						" AND end_date = ".GetSQLValueString($end_date,"date").
						" AND school_year_id = ".GetSQLValueString($school_year_id,"int").
						" AND term_id = ".GetSQLValueString($term_id,"int").
						" AND course_id = ".GetSQLValueString($course_id,"int").
						" AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if((isset($start_date) && $start_date!='') ||(isset($end_date) && $end_date!='') || (isset($school_year_id) && $school_year_id!='') ||
				(isset($term_id ) && $term_id !='') ||(isset($course_id) && $course_id!='')){

				$sql = "SELECT count(*) FROM tbl_enrollment_date
						WHERE 
							start_date = ".GetSQLValueString($start_date,"date") .
							" AND end_date = ".GetSQLValueString($end_date,"date").
							" AND school_year_id = ".GetSQLValueString($school_year_id,"int") .
							" AND term_id = ".GetSQLValueString($term_id,"int") .
							" AND course_id = ".GetSQLValueString($course_id,"int");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkDateOfExaminationExist($school_year_id, $date, $term_id, $course_id,  $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			 $sql = "SELECT count(*) FROM tbl_exam_date
					WHERE entrance_date = ".GetSQLValueString($start_date,"date") . 
					" AND school_year_id = ".GetSQLValueString($school_year_id,"int").
					" AND term_id = ".GetSQLValueString($term_id,"int").
					" AND course_id = ".GetSQLValueString($course_id,"int").
					" AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if((isset($date) && $date!='')||(isset($term_id ) && $term_id !='') ||(isset($course_id) && $course_id!='')){
				$sql = "SELECT count(*) FROM tbl_exam_date
						WHERE entrance_date = ".GetSQLValueString($date,"date") .
						" AND school_year_id = ".GetSQLValueString($school_year_id,"int") .
						" AND term_id = ".GetSQLValueString($term_id,"int") .
						" AND course_id = ".GetSQLValueString($course_id,"int");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkDateIsValid($date, $term_id){
		if($date < date('Y-m-d') && $term_id < CURRENT_TERM_ID){
			return  true;
		}else{
			return false;
		}
	}

	function checkPeriodNameExist($period_name, $start_of_submission, $end_of_submission, $school_year_id, $term_id,  $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			 $sql = "SELECT count(*) FROM tbl_school_year_period
					WHERE period_name = ".GetSQLValueString($period_name,"text") . 
					" AND start_of_submission = ".GetSQLValueString($start_of_submission,"date").
					" AND end_of_submission = ".GetSQLValueString($end_of_submission,"date").
					" AND term_id = ".GetSQLValueString($term_id,"int").
					" AND school_year_id = ".GetSQLValueString($school_year_id,"int").
					" AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if((isset($period_name) && $period_name!='') ||(isset($start_of_submission) && $start_of_submission!='') || 
			(isset($end_of_submission) && $end_of_submission!='') || (isset($term_id ) && $term_id !='') ||(isset($school_year_id) && $school_year_id!='')){
				
				$sql = "SELECT count(*) FROM tbl_school_year_period
						WHERE period_name = ".GetSQLValueString($period_name,"text") .
						" AND start_of_submission = ".GetSQLValueString($start_of_submission,"date").
						" AND end_of_submission = ".GetSQLValueString($end_of_submission,"date") .
						" AND term_id = ".GetSQLValueString($term_id,"int") .
						" AND school_year_id = ".GetSQLValueString($school_year_id,"int");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfCourseNameExist($course_name, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_course 
					WHERE course_name = ".GetSQLValueString($course_name,"text")."AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($course_name) && $course_name!=''){
				$sql = "SELECT count(*) FROM tbl_course 
						WHERE course_name = ".GetSQLValueString($course_name,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfDeptCodeExist($department_code, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_department 
					WHERE department_code = ".GetSQLValueString($department_code,"text")."AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($department_code) && $department_code!=''){
				$sql = "SELECT count(*) FROM tbl_department 
						WHERE department_code = ".GetSQLValueString($department_code,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfDiscountIsExist($name, $term_id, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_discount 
					WHERE name = ".GetSQLValueString($name,"text").
					" AND term_id = ".GetSQLValueString($term_id,"int").
					" AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if((isset($name) && $name!='') && (isset($term_id) && $term_id!='' )){
				$sql = "SELECT count(*) FROM tbl_discount 
						WHERE name = ".GetSQLValueString($name,"text").
						" AND term_id = ".GetSQLValueString($term_id,"int");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfDiscountIsUsed($name, $term_id, $id=""){
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_discount as discount, tbl_student_payment as stud_payment 
					WHERE discount.id = stud_payment.discount_id 
					AND discount.name = ".GetSQLValueString($name,"text").
					" AND discount.term_id = ".GetSQLValueString($term_id,"int").
					" AND discount.id = " .$id;
			$query = mysql_query($sql);	
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkIfDeptNameExist($department_name, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_department 
					WHERE department_name = ".GetSQLValueString($department_name,"text")."AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($department_name) && $department_name!=''){
				$sql = "SELECT count(*) FROM tbl_department 
						WHERE department_name = ".GetSQLValueString($department_name,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfLecLabIsUsed($term_id, $fee_type, $id = ''){
		if(isset($id) && $id != ''){
			$sql = "SELECT * FROM tbl_school_fee 
					WHERE term_id =".GetSQLValueString($term_id,"int")."
					AND (fee_type = 'perunitlec' 
					OR fee_type = 'perunitlab') 
					AND fee_type =".GetSQLValueString($fee_type,"text")." 
					AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_num_rows($query) > 0){
				return true;
			}else{
				return false;
			}
		}else{
			if(isset($term_id) && $term_id!=''){
				$sql = "SELECT * FROM tbl_school_fee 
						WHERE term_id =".GetSQLValueString($term_id,"int")."
						AND (fee_type = 'perunitlec' 
						OR fee_type = 'perunitlab') 
						AND fee_type =".GetSQLValueString($fee_type,"text");
				$query = mysql_query($sql);
				if(mysql_num_rows($query) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfFeesIsInUsed($term_id, $fee_type, $id = ''){
		if(isset($id) && $id != ''){
			$sql_fee= "SELECT * FROM tbl_student_fees WHERE fee_id =" .$id;
			$qry_fee = mysql_query($sql_fee);
			if(mysql_num_rows($qry_fee) > 0){
				return true;
			}else{
				return false;
			}
		}else{
			if(isset($term_id) && $term_id!=''){
				$sql_fee= "SELECT * FROM tbl_student_fees as stud_fee, tbl_school_fee as school_fee  
							WHERE school_fee.id = stud_fee.fee_id 
							AND school_fee.term_id =" .$term_id;
				$qry_fee = mysql_query($sql_fee);
				if(mysql_num_rows($qry_fee) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfRoomNumberExist($room_no, $building_id, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_room 
					WHERE room_no = ".GetSQLValueString($room_no,"text") .
					" AND building_id = ".GetSQLValueString($building_id,"text") ." 
					AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($room_no) && $room_no!=''){
				$sql = "SELECT count(*) FROM tbl_room 
						WHERE room_no = ".GetSQLValueString($room_no,"text").
						" AND building_id = ".GetSQLValueString($building_id,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkEmpIDExist($emp_id_number, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_employee 
					WHERE emp_id_number = ".GetSQLValueString($emp_id_number,"text")."AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($emp_id_number) && $emp_id_number!=''){
				$sql = "SELECT count(*) FROM tbl_employee 
						WHERE emp_id_number = ".GetSQLValueString($emp_id_number,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkStudentIDExist($student_number, $id=""){
		/* IF $id exist check for edit validation */
	
		//FOR MINT FORMAT
		$c = 0;
		list($fst,$snd,$trd,$last_num) = explode('-',$student_number);
		
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_student
					WHERE student_number = ".GetSQLValueString($student_number,"text")."AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				$sql = "SELECT student_number FROM tbl_student WHERE  id <> $id";
				$query = mysql_query($sql);
				while($row = mysql_fetch_array($query)){
					list($fst,$snd,$trd,$last_num2) = explode('-',$row['student_number']);
					if($last_num2 == $last_num){
						$c++;
					}
				}
				
				if($c > 0){
					return true;
				}else{
					return false;
				}
			}		
		}else{
			if(isset($student_number) && $student_number!=''){
				$sql = "SELECT count(*) FROM tbl_student
						WHERE student_number = ".GetSQLValueString($student_number,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					$sql = "SELECT student_number FROM tbl_student";
					$query = mysql_query($sql);
					while($row = mysql_fetch_array($query)){
						list($fst,$snd,$trd,$last_num2) = explode('-',$row['student_number']);
						if($last_num2 == $last_num){
							$c++;
						}
					}
					
					if($c > 0){
						return true;
					}else{
						return false;
					}
				}
			}	
		}
		
		/*
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_student
					WHERE student_number = ".GetSQLValueString($student_number,"text")."AND id <> $id";
			$query = mysql_query($sql);

			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if(isset($student_number) && $student_number!=''){
				$sql = "SELECT count(*) FROM tbl_student
						WHERE student_number = ".GetSQLValueString($student_number,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
		*/
	}

	function checkIfSubjectCodeExist($subject_code, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_subject 
					WHERE subject_code = ".GetSQLValueString($subject_code,"text")." AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if((isset($subject_code) && $subject_code!='') || (isset($subject_name) && $subject_name!='')){
				$sql = "SELECT count(*) FROM tbl_subject 
						WHERE subject_code = ".GetSQLValueString($subject_code,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfStudentEmailExist($email, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_student 
					WHERE email = ".GetSQLValueString($email,"text")." AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if((isset($email) && $email!='') || (isset($email) && $email!='')){
				$sql = "SELECT count(*) FROM tbl_student WHERE email = ".GetSQLValueString($email,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfParentEmailExist($guardian_email, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_parent 
					WHERE email = ".GetSQLValueString($guardian_email,"text")." AND student_id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if((isset($guardian_email) && $guardian_email!='') || (isset($guardian_email) && $guardian_email!='')){
				$sql = "SELECT count(*) FROM tbl_parent 
						WHERE email = ".GetSQLValueString($guardian_email,"text");
				$query = mysql_query($sql);
				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfParentEmailEqualStudentEmail($guardian_email, $student_email){
		/* IF parent_email = student_email validation */
		if($guardian_email == $student_email){
			return true;
		}else{
			return false;
		}
	}

	function validateEmail($email){
		if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email)){
			return false;
		}else{
			return true;
		}
	}

	function validateDate($from, $to, $id =""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id ==''){
			if(isset($from) && $from !=''){
				if($from > $to){
					return true;
				}else{
					return false;
				}
			}	
		}else{
			if(isset($from) && $from!=''){
				if($from > $to){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function valDateStartAddZero($start_date){
		$s_day	 = explode("-", $start_date);
		if($s_day[2] == '1' || $s_day[2] == '2' || $s_day[2] == '3' || $s_day[2] == '4' || $s_day[2] == '5' || $s_day[2] == '6' || $s_day[2] == '7' || $s_day[2] == '8' || $s_day[2] == '9'){
			$sday 		="0".$s_day[2];
		}else{
			$sday 		=$s_day[2];
		}

		if($s_day[1] == '1' || $s_day[1] == '2' || $s_day[1] == '3' || $s_day[1] == '4' || $s_day[1] == '5' || $s_day[1] == '6' || $s_day[1] == '7' || $s_day[1] == '8' || $s_day[1] == '9'){
			$smonth 	="0".$s_day[1];	
		}else{
			$smonth 	=$s_day[1];
		}

		$syear 		= $s_day[0];
		$sbdate  	= array($syear, $smonth, $sday);
		return $startdate = implode("-", $sbdate);
	}

	function valDateEndAddZero($end_date){
		$e_day	 = explode("-", $end_date);

		if($e_day[2] == '1' || $e_day[2] == '2' || $e_day[2] == '3' || $e_day[2] == '4' || $e_day[2] == '5' || $e_day[2] == '6' || $e_day[2] == '7' || $e_day[2] == '8' || $e_day[2] == '9'){
			$eday 		="0".$e_day[2];
		}else{
			$eday 		=$e_day[2];
		}

		if($e_day[1] == '1' || $e_day[1] == '2' || $e_day[1] == '3' || $e_day[1] == '4' || $e_day[1] == '5' || $e_day[1] == '6' || $e_day[1] == '7' || $e_day[1] == '8' || $e_day[1] == '9'){	
			$emonth 	="0".$e_day[1];
		}else{
			$emonth 	=$e_day[1];
		}

		$eyear 		= $e_day[0];
		$ebdate  	= array($eyear, $emonth, $eday);
		return $enddate = implode("-", $ebdate);
	}

	function checkIfEmployeeEmailExist($email, $id=""){
		/* IF $id exist check for edit validation */
		if(isset($id) && $id!=''){
			$sql = "SELECT count(*) FROM tbl_employee 
					WHERE email = ".GetSQLValueString($email,"text")." AND id <> $id";
			$query = mysql_query($sql);
			if(mysql_result($query, 0, 0) > 0){
				return true;
			}else{
				return false;
			}		
		}else{
			if((isset($email) && $email!='') || (isset($email) && $email!='')){
				$sql = "SELECT count(*) FROM tbl_employee 
						WHERE email = ".GetSQLValueString($email,"text");
				$query = mysql_query($sql);

				if(mysql_result($query, 0, 0) > 0){
					return true;
				}else{
					return false;
				}
			}	
		}
	}

	function checkEmployeeTypeByTitleExist($title){
		if(isset($title)){
			$sql = "SELECT * FROM tbl_employee_type WHERE type_title = '".$title."'";
			$query = mysql_query($sql);		
			if(mysql_num_rows($query) > 0){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkCanEditGradesheet($id){
		$sql = "SELECT * FROM tbl_professor_gradesheet"; 
		$query = mysql_query($sql);
		$arr = array();
		while($row = mysql_fetch_array($query)){
			$arr[] = $row['sheet_id'];
		}

		if(in_array($id, $arr)){
			return true;
		}else{
			return false;
		}
	}

	function checkTotalGradesheetPercentage($school_yr_period_id,$syId,$percentage,$id=''){
		/* IF percentage exceed validation */
		if(isset($school_yr_period_id) && $school_yr_period_id !='' && $id==''){
			$sql = "SELECT percentage FROM tbl_gradesheet 
					WHERE school_yr_period_id = ".$school_yr_period_id." 
					AND schedule_id = ".$syId;
			$query = mysql_query($sql);
			while($row = mysql_fetch_array($query)){
				$tal += $row['percentage']; 
			}
			$total = $tal + $percentage;
			if($total > 100){
				return true;
			}else{
				return false;
			}		
		}else if(isset($id) && $id!='' && $school_yr_period_id!=''){
			$sql = "SELECT percentage FROM tbl_gradesheet 
					WHERE school_yr_period_id = ".$school_yr_period_id." 
					AND schedule_id = ".$syId." AND id <>" .$id;
			$query = mysql_query($sql);
			while($row = mysql_fetch_array($query)){
				$tal += $row['percentage']; 
			}
			$total = $tal + $percentage;
			if($total > 100){
				return true;
			}else{
				return false;
			}		
		}
	}

	function checkTotalPeriodPercentage($syId,$termId,$percentage,$id=''){
		/* IF percentage exceed validation */
		if(isset($termId) && $termId !='' && $id==''){
			$sql = "SELECT * FROM tbl_school_year_period where school_year_id = $syId AND term_id = $termId";
			$query = mysql_query($sql);
			while($row = mysql_fetch_array($query)){
				$tal += $row['percentage']; 
			}
			$total = $tal + $percentage;

			if($total > 100){
				return true;
			}else{
				return false;
			}		
		}else if(isset($id) && $id!='' && $termId!=''){
			$sql = "SELECT * FROM tbl_school_year_period where school_year_id = $syId 
					AND term_id = $termId AND id <>" .$id;
			$query = mysql_query($sql);

			while($row = mysql_fetch_array($query)){
				$tal += $row['percentage']; 
			}
			$total = $tal + $percentage;

			if($total > 100){
				return true;
			}else{
				return false;
			}
		}
	}

	function checkFullPayment($id,$amount){
		/* IF payment exceed validation */
		if(isset($id) && $id !=''){
			/* TOTAL AMOUNT */
			$sql = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND publish =  'Y'";
			$result = mysql_query($sql);
			$sub_total = 0;

			while($row = mysql_fetch_array($result)){
				$total = getStudentAmountFeeByFeeId($row['id'],$id);           
				$sub_total += $total;
			}

			/* TOTAL OTHER PAYMENT */
			$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND publish =  'Y'";
			$result_fee_other = mysql_query($sql_fee_other);
			$row_fee_other = mysql_fetch_array($result_fee_other);
			$sub_mis_total = 0;
			$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$id);           
			$sub_mis_total += $mis_total;

			/* TOTAL LEC PAYMENT */
			$sql_lec = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND fee_type = 'perunitlec' AND publish =  'Y'";
			$qry_lec = mysql_query($sql_lec);
			$row_lec = mysql_fetch_array($qry_lec);
			$sub_lec_total = 0;
			$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$id);           
			$sub_lec_total += $lec_total;

			/* TOTAL LAB PAYMENT */
			$sql_lab = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND fee_type = 'perunitlab' AND publish =  'Y'";
			$qry_lab = mysql_query($sql_lab);
			$row_lab = mysql_fetch_array($qry_lab);
			$sub_lab_total = 0;
			$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$id);           
			$sub_lab_total += $lab_total;

			/*TOTAL LEC AND LAB = LEC + LAB*/
			$sub_total =  $sub_lec_total + $sub_lab_total + $sub_mis_total;

			/*TOTAL UNITS = TUITION FEE - TOTAL OTHER PAYMENT*/
			$total_lec_lab = $sub_total - $sub_mis_total;

			$sql_payment = "SELECT * FROM tbl_student_payment WHERE term_id = ".CURRENT_TERM_ID." AND is_bounced <> 'Y' AND is_refund <> 'Y' AND student_id = ".$id;
			$query_payment = mysql_query ($sql_payment);
			while($row_pay = @mysql_fetch_array($query_payment)){
				if($row_pay['discount_id'] != ''){
					$discount = $row_pay['discount_id'];
				}

				if($row_pay['is_bounced'] == 'N'){
					$total_payment += $row_pay['amount']; 
				}
			}

			if($discount != ''){
				$sql_discount = "SELECT * FROM tbl_discount WHERE term_id = ".CURRENT_TERM_ID." AND publish = 'Y' AND id=" .$discount;
				$qry_discount = mysql_query($sql_discount);
				$row_discount = mysql_fetch_array($qry_discount);
				$discount = $row_discount['value'];

				/*TOTAL DISCOUNT = TOTAL UNIT LEC/LAB / 100%  x DISCOUNT*/
				$total_discounted = $total_lec_lab / 100 * $discount;
			}else{
				$total_discounted = 0;
			}

			$totalfee = $sub_total - $total_discounted;
			$totalpay = $total_payment + $amount;

			if($totalpay > $totalfee){
				return true;
			}else{		
				return false;
			}
  		}
	}

	function checkPaymentIfPartialOrFull($id,$amount){
		/* IF payment exceed validation */
		if(isset($id) && $id !=''){
			/* TOTAL AMOUNT */
			$sql = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND publish =  'Y'";
			$result = mysql_query($sql);
			$sub_total = 0;
			while($row = mysql_fetch_array($result)){
				$total = getStudentAmountFeeByFeeId($row['id'],$id);           
				$sub_total += $total;
			}

			/* TOTAL OTHER PAYMENT */
			$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND publish =  'Y'";
			$result_fee_other = mysql_query($sql_fee_other);
			$row_fee_other = mysql_fetch_array($result_fee_other);
			$sub_mis_total = 0;
			$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$id);           
			$sub_mis_total += $mis_total;

			/* TOTAL LEC PAYMENT */
			$sql_lec = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND fee_type = 'perunitlec' AND publish =  'Y'";
			$qry_lec = mysql_query($sql_lec);
			$row_lec = mysql_fetch_array($qry_lec);
			$sub_lec_total = 0;
			$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$id);           
			$sub_lec_total += $lec_total;

			/* TOTAL LAB PAYMENT */
			$sql_lab = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND fee_type = 'perunitlab' AND publish =  'Y'";
			$qry_lab = mysql_query($sql_lab);
			$row_lab = mysql_fetch_array($qry_lab);
			$sub_lab_total = 0;
			$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$id);           
			$sub_lab_total += $lab_total;

			/*TOTAL LEC AND LAB = LEC + LAB*/
			$sub_total =  $sub_lec_total + $sub_lab_total + $sub_mis_total;

			/*TOTAL UNITS = TUITION FEE - TOTAL OTHER PAYMENT*/
			$total_lec_lab = $sub_total - $sub_mis_total;
			$sql_payment = "SELECT * FROM tbl_student_payment WHERE term_id = ".CURRENT_TERM_ID." AND student_id = ".$id;
			$query_payment = mysql_query ($sql_payment);

			while($row_pay = @mysql_fetch_array($query_payment)){
				if($row_pay['discount_id'] != ''){
					$discount = $row_pay['discount_id'];
				}

				if($row_pay['is_bounced'] == 'N'){
					$total_payment += $row_pay['amount']; 
				}
			}

			if($discount != ''){
				$sql_discount = "SELECT * FROM tbl_discount WHERE term_id = ".CURRENT_TERM_ID." AND publish = 'Y' AND id=" .$discount;
				$qry_discount = mysql_query($sql_discount);
				$row_discount = mysql_fetch_array($qry_discount);
				$discount = $row_discount['value'];

				/*TOTAL DISCOUNT = TOTAL UNIT LEC/LAB / 100%  x DISCOUNT*/
				$total_discounted = $total_lec_lab / 100 * $discount;
			}else{
				$total_discounted = 0;
			}

			$totalfee = $sub_total - $total_discounted;
			$totalpay = $total_payment + $amount;

			if($totalpay == $totalfee){
				return 'F';
			}else{		
				return 'P';
			}
  		}
	}

	function checkIfPaymentAlreadyFull($id){
		$sql = "SELECT * FROM tbl_student_payment WHERE student_id = ".$id." AND term_id=".CURRENT_TERM_ID;
		$query = mysql_query($sql);
		$paid = array();
		
		while($row = mysql_fetch_array($query)){
			if($row['is_bounced']=='N' && $row['is_refund']=='N'){
				$paid[] = $row['payment_term'];
			}
		}

		if(in_array('F',$paid)){
			return true;
		}else{
			return false;
		}
	}

	function checkBalanceCarried($id,$prev_term){
		/* IF payment exceed validation */
		if(isset($id) && $id !=''){
			/* TOTAL AMOUNT */
			$sql = "SELECT * FROM tbl_school_fee WHERE term_id = ".$prev_term." AND publish =  'Y'";
			$result = mysql_query($sql);
			$sub_total = 0;

			while($row = mysql_fetch_array($result)){
				$total = getStudentAmountFeeByFeeId($row['id'],$id);           
				$sub_total += $total;
			}

			/* TOTAL OTHER PAYMENT */
			$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE term_id = ".$prev_term." AND publish =  'Y'";
			$result_fee_other = mysql_query($sql_fee_other);
			$row_fee_other = mysql_fetch_array($result_fee_other);
			$sub_mis_total = 0;
			$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$id);           
			$sub_mis_total += $mis_total;

			/* TOTAL LEC PAYMENT */
			$sql_lec = "SELECT * FROM tbl_school_fee WHERE term_id = ".$prev_term." AND fee_type = 'perunitlec' AND publish =  'Y'";
			$qry_lec = mysql_query($sql_lec);
			$row_lec = mysql_fetch_array($qry_lec);
			$sub_lec_total = 0;
			$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$id);           
			$sub_lec_total += $lec_total;

			/* TOTAL LAB PAYMENT */
			$sql_lab = "SELECT * FROM tbl_school_fee WHERE term_id = ".$prev_term." AND fee_type = 'perunitlab' AND publish =  'Y'";
			$qry_lab = mysql_query($sql_lab);
			$row_lab = mysql_fetch_array($qry_lab);
			$sub_lab_total = 0;
			$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$id);           
			$sub_lab_total += $lab_total;

			/*TOTAL LEC AND LAB = LEC + LAB*/
			$sub_total =  $sub_lec_total + $sub_lab_total + $sub_mis_total;

			/*TOTAL UNITS = TUITION FEE - TOTAL OTHER PAYMENT*/
			$total_lec_lab = $sub_total - $sub_mis_total;
			$sql_payment = "SELECT * FROM tbl_student_payment WHERE term_id = ".$prev_term." AND student_id = ".$id;
			$query_payment = mysql_query ($sql_payment);

			while($row_pay = @mysql_fetch_array($query_payment)){
				if($row_pay['discount_id'] != ''){
					$discount = $row_pay['discount_id'];
				}

				if($row_pay['is_bounced'] == 'N'){
					$total_payment += $row_pay['amount']; 
				}
			}

			if($discount != ''){
				$sql_discount = "SELECT * FROM tbl_discount WHERE term_id = ".$prev_term." AND publish = 'Y' AND id=" .$discount;
				$qry_discount = mysql_query($sql_discount);
				$row_discount = mysql_fetch_array($qry_discount);
				$discount = $row_discount['value'];

				/*TOTAL DISCOUNT = TOTAL UNIT LEC/LAB / 100%  x DISCOUNT*/
				$total_discounted = $total_lec_lab / 100 * $discount;
			}else{
				$total_discounted = 0;
			}

			echo $sub_total.'SSS'.$total_discounted;
			$totalfee = $sub_total - $total_discounted;
			$balance = $totalfee - $total_payment;
			return $balance;
  		}
	}

	function checkIfProfIsAvail($employee_id, $term_id, $day, $start, $end, $id =""){			
		/* IF $id exist check for edit validation */
		if(isset($id) && $id ==''){	
			if((isset($employee_id) && $employee_id!='')){
				$ctr = count($day);
				for($x=0;$x<=$ctr;$x++){	
					$sql2 = "SELECT * FROM tbl_schedule WHERE employee_id =" . $employee_id." AND term_id=".$term_id." AND ";				
					$sql = "SELECT * FROM tbl_schedule 
							WHERE 
								((('".$start[$x]."' BETWEEN ".$day[$x]."_time_from AND ".$day[$x]."_time_to ) AND ".$day[$x]."_time_to <> '".$start[$x]."' ) OR 
								('".$end[$x]."' BETWEEN ".$day[$x]."_time_from AND ".$day[$x]."_time_to ))
							AND term_id =" . $term_id . " AND employee_id =" . $employee_id. 
							" AND ".$day[$x]." ='Y'";				
					$query = mysql_query($sql);
					$row = mysql_fetch_array($query);

					if(mysql_result($query, 0, 0) > 0){
						while($row = mysql_fetch_array($query)){
							$sql2 = "SELECT * FROM tbl_schedule WHERE employee_id =" . $employee_id." AND term_id=".$term_id." AND ".$day[$x]."_time_from=".$start[$x]." AND ".$day[$x]."_time_to=".$end[$x];
							$query2 = mysql_query($sql2);
							$row2 = mysql_fetch_array($query2);
						
							if($row['room_id']==$row2['room_id']){
								return true;
							}else{
								return false;	
							}
						}
					}else{
						return false;
					}	
				}
			}
		}else{
			if((isset($employee_id) && $employee_id!='')){
				$ctr = count($day);
				for($x=0;$x<=$ctr;$x++){
					$sql = "SELECT * FROM tbl_schedule 
							WHERE 
								((('".$start[$x]."' BETWEEN ".$day[$x]."_time_from AND ".$day[$x]."_time_to ) AND ".$day[$x]."_time_to <> '".$start[$x]."' ) OR 
								('".$end[$x]."' BETWEEN ".$day[$x]."_time_from AND ".$day[$x]."_time_to ))
							AND term_id =" . $term_id . " AND id <> " . $id . "  AND employee_id =" .$employee_id. " AND ".$day[$x]." ='Y'";		
					$query = mysql_query($sql);
					$row = mysql_fetch_array($query);

					if(mysql_result($query, 0, 0) > 0){
						while($row = mysql_fetch_array($query)){
							$sql2 = "SELECT * FROM tbl_schedule WHERE employee_id =" . $employee_id." AND term_id=".$term_id." AND ".$day[$x]."_time_from=".$start[$x]." AND ".$day[$x]."_time_to=".$end[$x];
							$query2 = mysql_query($sql2);
							$row2 = mysql_fetch_array($query2);
						
							if($row['room_id']==$row2['room_id']){
								return true;
							}else{
								return false;	
							}
						}
					}else{
						return false;
					}
				}
			}	
		}
	}

	function checkIfRoomIsAvail($room_id, $term_id, $days, $start, $end, $subject_id, $id =""){		
		/* IF $id exist check for edit validation */
		if(isset($id) && $id ==''){
			if(isset($room_id) && $room_id !=''){								
				$ctr = 0;

				for($x=0;$x<=$ctr;$x++){					
					$sql = "SELECT * FROM tbl_schedule 
							WHERE 
								((('".$start[$x]."' BETWEEN ".$days[$x]."_time_from 
							AND ".$days[$x]."_time_to ) AND ".$days[$x]."_time_to <> '".$start[$x]."' ) 
							OR ('".$end[$x]."' BETWEEN ".$days[$x]."_time_from 
							AND ".$days[$x]."_time_to ) AND ".$days[$x]."_time_to = '".$end[$x]."') AND term_id =" . $term_id . " 
							AND room_id =" . $room_id. " AND ".$days[$x]." ='Y'";						
					$query = mysql_query($sql);
					if(mysql_result($query, 0, 0) > 0){
						while($row = mysql_fetch_array($query)){
							$sql2 = "SELECT * FROM tbl_schedule WHERE room_id =" . $room_id." AND term_id=".$term_id." AND ".$days[$x]."_time_from=".$start[$x]." AND ".$days[$x]."_time_to=".$end[$x];
							$query2 = mysql_query($sql2);
							$row2 = mysql_fetch_array($query2);
					
							if($row['employee_id']==$row2['employee_id']){
								return true;
							}else{
								return false;	
							}
						}
					}else{
						return false;
					}
				}
			}	
		}else{
			if((isset($room_id) && $room_id!='')){
				$ctr = 0;
				//added line - AND ".$days[$x]."_time_to = '".$end[$x]."'(by MKT)
				for($x=0;$x<=$ctr;$x++){		
					$sql = "SELECT * FROM tbl_schedule 
							WHERE 
								((('".$start[$x]."' BETWEEN ".$day[$x]."_time_from 
							AND ".$day[$x]."_time_to ) AND ".$day[$x]."_time_to <> '".$start[$x]."' ) 
							OR ('".$end[$x]."' BETWEEN ".$day[$x]."_time_from 
							AND ".$day[$x]."_time_to )AND ".$days[$x]."_time_to = '".$end[$x]."')	AND term_id =" . $term_id . " 
							AND id <> " . $id . " AND room_id =" . $room_id. " AND ".$day[$x]." ='Y'";		
					$query = mysql_query($sql);
					if(@mysql_result($query, 0, 0) > 0){
						while($row = mysql_fetch_array($query)){
							$sql2 = "SELECT * FROM tbl_schedule WHERE room_id =" . $room_id." AND term_id=".$term_id." AND ".$days[$x]."_time_from=".$start[$x]." AND ".$days[$x]."_time_to=".$end[$x];
							$query2 = mysql_query($sql2);
							$row2 = mysql_fetch_array($query2);
					
							if($row['employee_id']==$row2['employee_id']){
								return true;
							}else{
								return false;	
							}
						}
					}else{
						return false;
					}
				}
			}	
		}
	}

	function checkIfProfIsAvailByAvailability($employee_id, $term_id, $day, $start, $end, $id =""){			
		/* IF $id exist check for edit validation */
		if(isset($id) && $id ==''){	
			if((isset($employee_id) && $employee_id!='')){
				$ctr = 0;
				$sqla = "SELECT * FROM tbl_employee_availability WHERE employee_id = ".$employee_id;
				$querya = mysql_query($sqla);
				if(mysql_num_rows($querya) > 0){
					for($x=0;$x<=$ctr;$x++){
						$dayw = getDayInCode($days[$x]);
						$sqlr = "SELECT count(*) FROM tbl_employee_availability 
								WHERE 
									((('".$start[$x]."' BETWEEN from_time
								AND to_time ) AND to_time <> '".$start[$x]."' ) 
								OR ('".$end[$x]."' BETWEEN from_time 
								AND to_time )) AND employee_id =" . $employee_id. " 
								AND day_available ='".$dayw."'";						
						$queryr = mysql_query($sqlr);
						if(mysql_result($queryr, 0, 0) > 0){
							return false;
						}else{
							return true;
						}
					}
				}else{
					return false;
				}
			}
		}else{
			if((isset($employee_id) && $employee_id!='')){
				$ctr = count($day);
				$sqla = "SELECT * FROM tbl_employee_availability 
						WHERE employee_id = ".$employee_id;
				$querya = mysql_query($sqla);
				if(mysql_num_rows($querya) > 0){
					for($x=0;$x<=$ctr;$x++){
						$dayw = getDayInCode($days[$x]);
						$sqlr = "SELECT count(*) FROM tbl_room_availability 
								WHERE 
									((('".$start[$x]."' BETWEEN from_time
								AND to_time ) AND to_time <> '".$start[$x]."' ) 
								OR ('".$end[$x]."' BETWEEN from_time 
								AND to_time )) AND employee_id =" . $employee_id. " 
								AND day_available ='".$dayw."'";						
						$queryr = mysql_query($sqlr);
						if(mysql_result($queryr, 0, 0) > 0){
							return false;
						}else{
							return true;
						}
					}
				}else{
					return false;
				}
			}	
		}
	}

	function checkIfRoomIsAvailByAvailability($room_id, $term_id, $days, $start, $end, $id =""){		
		/* IF $id exist check for edit validation */
		if(isset($id) && $id ==''){
			if(isset($room_id) && $room_id !=''){								
				$sqla = "SELECT * FROM tbl_room_availability 
						WHERE room_id = ".$room_id;
				$querya = mysql_query($sqla);
				$ctr = mysql_num_rows($querya);
				if($ctr > 0){
					for($x=0;$x<=$ctr;$x++){
						$dayw = getDayInCode($days[$x]);
						$sqlr = "SELECT * FROM tbl_room_availability 
								WHERE 
									((('".$start[$x]."' BETWEEN from_time
								AND to_time ) AND to_time <> '".$start[$x]."' ) 
								OR ('".$end[$x]."' BETWEEN from_time 
								AND to_time )) AND room_id =" . $room_id. " 
								AND day_available ='".$dayw."'";						
						$queryr = mysql_query($sqlr);
				
						if(mysql_num_rows($queryr) > 0){
							return false;
						}else{
							return true;
						}
					}
				}else{
					return false;
				}
			}	
		}else{
			if((isset($room_id) && $room_id!='')){
				$ctr = 0;
				$sqla = "SELECT * FROM tbl_room_availability WHERE room_id = ".$room_id;
				$querya = mysql_query($sqla);

				if(mysql_num_rows($querya) > 0){
					for($x=0;$x<=$ctr;$x++){
						$dayw = getDayInCode($days[$x]);
						$sqlr = "SELECT * FROM tbl_room_availability 
								WHERE 
									((('".$start[$x]."' BETWEEN ".$days[$x]."_time_from 
								AND ".$days[$x]."_time_to ) AND ".$days[$x]."_time_to <> '".$start[$x]."' ) 
								OR ('".$end[$x]."' BETWEEN ".$days[$x]."_time_from 
								AND ".$days[$x]."_time_to )) AND room_id =" . $room_id. " 
								AND day_available ='".$dayw."'";						
						$queryr = mysql_query($sqlr);

						if(mysql_num_rows($queryr) > 0){
							return false;
						}else{
							return true;
						}
					}
				}else{
					return false;
				}
			}	
		}
	}

	function conflictRed($arr){	
		$conflict = '';
		foreach($arr as $needle){
			if(needle != ''){
				foreach($arr_sched_id as $mixed_array){
					if($needle != $mixed_array && $mixed_array!= ''){
						if(checkScheduleForConflict($needle, $mixed_array)){
							$conflict = $needle;
							break;
						}
					}
				}
			}

			if($conflict != ''){
				break;
			}
		}
		return $conflict;
	}
	
	function checkStudentChangeScheduleConfilct($schedule_id,$student_id){
		$sql1 = "SELECT * FROM tbl_student_schedule WHERE student_id = ".$student_id." AND term_id=".CURRENT_TERM_ID." AND schedule_id<>".$schedule_id;
		$query1 = mysql_query($sql1);
		while($row1 = mysql_fetch_array($query1)){
			$monday == 'Y' 		? $arr_str[]  = "monday-".$monday_time_from.'-'.$monday_end_from:'';
			$tuesday == 'Y' 	? $arr_str[]  = "tuesday-".$tuesday_time_from.'-'.$tuesday_end_from:'';
			$wednesday == 'Y' 	? $arr_str[]  = "wednesday-".$wednesday_time_from.'-'.$wednesday_end_from:'';
			$thursday == 'Y' 	? $arr_str[]  = "thursday-".$thursday_time_from.'-'.$thursday_end_from:'';
			$friday == 'Y' 		? $arr_str[]  = "friday-".$friday_time_from.'-'.$friday_end_from:'';
			$saturday == 'Y' 	? $arr_str[]  = "saturday-".$saturday_time_from.'-'.$saturday_end_from:'';
			$sunday == 'Y' 		? $arr_str[]  = "sunday-".$sunday_time_from.'-'.$sunday_end_from:'';
			$sql = "SELECT * FROM tbl_schedule WHERE id = ".$schedule_id;
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$arr_str_2 = array();
			$row['monday'] == 'Y' 		? $arr_str2[]  = "monday-".$row['monday_time_from'].'-'.$row['monday_time_to']:'';
			$row['tuesday'] == 'Y' 		? $arr_str2[]  = "tuesday-".$row['tuesday_time_from'].'-'.$row['tuesday_time_to']:'';
			$row['wednesday'] == 'Y' 	? $arr_str2[]  = "wednesday-".$row['wednesday_time_from'].'-'.$row['wednesday_time_to']:'';
			$row['thursday'] == 'Y' 	? $arr_str2[]  = "thursday-".$row['thursday_time_from'].'-'.$row['thursday_time_to']:'';
			$row['friday'] == 'Y' 		? $arr_str2[]  = "friday-".$row['friday_time_from'].'-'.$row['friday_time_to']:'';
			$row['saturday'] == 'Y' 	? $arr_str2[]  = "saturday-".$row['saturday_time_from'].'-'.$row['saturday_time_to']:'';
			$row['sunday'] == 'Y' 		? $arr_str2[]  = "sunday-".$row['sunday_time_from'].'-'.$row['sunday_time_to']:'';
			$ctr = 0;
			$cnt = 0;
			foreach($arr_str as $day){
				//echo $day;
				//foreach($arr_str2 as $day2){
		
				$days = explode('-',$day);
				$days2 = explode('-',$arr_str2);
				$str_time_from 	= str_replace(':','',$days[1]) * 1;
				$str_time_to 	= str_replace(':','',$days[2]) * 1;
				$str_time_from_2 	= str_replace(':','',$arr_str2[1]) * 1;
				$str_time_to_2 	= str_replace(':','',$arr_str2[2]) * 1;				 
						
				if(($str_time_from == $str_time_from_2 && $str_time_from == $str_time_to_2) || ($str_time_to == $str_time_from && $str_time_to == $str_time_to_2)){
					if($days[0]==$arr_str2[0]){
						return true;
					}else{
						return false;
					}
				 }else{
					if(($str_time_from >= $str_time_to && $str_time_from < $str_time_to_2) || ($str_time_to > $str_time_from_2 && $str_time_to <= $str_time_to_2)){
						if($days[0]==$arr_str2[0]){
							return true;
						}else{
							return false;
						}
					}
				}

				$cnt++;
				//}
				return false;
				$ctr++;
			}
			return false;
		}
	}

	function checkStudentScheduleForConflict($days, $start, $end, $schedule_id,$term){
		if($schedule_id != ''){
			if(count($days)>0){
				$cnt = 0;
				foreach($days as $day){
					if(in_array('monday',$days)){
						$monday = 'Y';
						$monday_time_from	= $start[$cnt];
						$monday_end_from	= $end[$cnt];
					}else{
						$monday = 'N';
						$monday_time_from	= 0;
						$monday_end_from	= 0;
					}
	
					if(in_array('tuesday',$days)){
						$tuesday = 'Y';
						$tuesday_time_from	= $start[$cnt];
						$tuesday_end_from	= $end[$cnt];
					}else{
						$tuesday = 'N';
						$tuesday_time_from	= 0;
						$tuesday_end_from	= 0;
					}

					if(in_array('wednesday',$days)){
						$wednesday = 'Y';
						$wednesday_time_from	= $start[$cnt];
						$wednesday_end_from	= $end[$cnt];
					}else{
						$wednesday = 'N';
						$wednesday_time_from	= 0;
						$wednesday_end_from	= 0;
					}
						
					if(in_array('thursday',$days)){
						$thursday = 'Y';
						$thursday_time_from	= $start[$cnt];
						$thursday_end_from	= $end[$cnt];
					}else{
						$thursday = 'N';
						$thursday_time_from	= 0;
						$thursday_end_from	= 0;
					}
			
					if(in_array('friday',$days)){
						$friday = 'Y';
						$friday_time_from	= $start[$cnt];
						$friday_end_from	= $end[$cnt];
					}else{
						$friday = 'N';
						$friday_time_from	= 0;
						$friday_end_from	= 0;
					}
			
					if(in_array('saturday',$days)){
						$saturday = 'Y';
						$saturday_time_from	= $start[$cnt];
						$saturday_end_from	= $end[$cnt];
					}else{
						$saturday = 'N';
						$saturday_time_from	= 0;
						$saturday_end_from	= 0;
					}
		
					if(in_array('sunday',$days)){
						$sunday = 'Y';
						$sunday_time_from	= $start[$cnt];
						$sunday_end_from	= $end[$cnt];
					}else{
						$sunday = 'N';
						$sunday_time_from	= 0;
						$sunday_end_from	= 0;
					}
					$cnt++;
				}
			}


			$sqls = "SELECT DISTINCT student_id FROM tbl_student_schedule WHERE schedule_id = ".$schedule_id." AND term_id=".$term;
			$querys = mysql_query($sqls);
			$arr_str = array();
			while($rows = mysql_fetch_array($querys)){
				$sql1 = "SELECT * FROM tbl_student_schedule WHERE student_id = ".$rows['student_id']." AND term_id=".$term." AND schedule_id<>".$schedule_id;
				$query1 = mysql_query($sql1);
				while($row1 = mysql_fetch_array($query1)){
					$monday == 'Y' 		? $arr_str[]  = "monday-".$monday_time_from.'-'.$monday_end_from:'';
					$tuesday == 'Y' 	? $arr_str[]  = "tuesday-".$tuesday_time_from.'-'.$tuesday_end_from:'';
					$wednesday == 'Y' 	? $arr_str[]  = "wednesday-".$wednesday_time_from.'-'.$wednesday_end_from:'';
					$thursday == 'Y' 	? $arr_str[]  = "thursday-".$thursday_time_from.'-'.$thursday_end_from:'';
					$friday == 'Y' 		? $arr_str[]  = "friday-".$friday_time_from.'-'.$friday_end_from:'';
					$saturday == 'Y' 	? $arr_str[]  = "saturday-".$saturday_time_from.'-'.$saturday_end_from:'';
					$sunday == 'Y' 		? $arr_str[]  = "sunday-".$sunday_time_from.'-'.$sunday_end_from:'';
					$sql = "SELECT * FROM tbl_schedule WHERE id = ".$row1['schedule_id'];
					$query = mysql_query($sql);
					$row = mysql_fetch_array($query);
					$arr_str_2 = array();
				
					$row['monday'] == 'Y' 		? $arr_str2[]  = "monday-".$row['monday_time_from'].'-'.$row['monday_time_to']:'';
					$row['tuesday'] == 'Y' 		? $arr_str2[]  = "tuesday-".$row['tuesday_time_from'].'-'.$row['tuesday_time_to']:'';
					$row['wednesday'] == 'Y' 	? $arr_str2[]  = "wednesday-".$row['wednesday_time_from'].'-'.$row['wednesday_time_to']:'';
					$row['thursday'] == 'Y' 	? $arr_str2[]  = "thursday-".$row['thursday_time_from'].'-'.$row['thursday_time_to']:'';
					$row['friday'] == 'Y' 		? $arr_str2[]  = "friday-".$row['friday_time_from'].'-'.$row['friday_time_to']:'';
					$row['saturday'] == 'Y' 	? $arr_str2[]  = "saturday-".$row['saturday_time_from'].'-'.$row['saturday_time_to']:'';
					$row['sunday'] == 'Y' 		? $arr_str2[]  = "sunday-".$row['sunday_time_from'].'-'.$row['sunday_time_to']:'';
					$ctr = 0;
					$cnt = 0;
		
					foreach($arr_str as $day){
						//echo $day;
						foreach($arr_str2 as $day2){
							$days = explode('-',$day);
							$days2 = explode('-',$day2);
							$str_time_from 	= str_replace(':','',$days[1]) * 1;
							$str_time_to 	= str_replace(':','',$days[2]) * 1;
							$str_time_from_2 	= str_replace(':','',$days2[1]) * 1;
							$str_time_to_2 	= str_replace(':','',$days2[2]) * 1;				 

							if(($str_time_from == $str_time_from_2 && $str_time_from == $str_time_to_2) || ($str_time_to == $str_time_from && $str_time_to == $str_time_to_2)){
								if($days[0]==$days2[0]){
									return true;
								}else{
									return false;
								}
							}else{
								if(($str_time_from >= $str_time_to && $str_time_from < $str_time_to_2) || ($str_time_to > $str_time_from_2 && $str_time_to <= $str_time_to_2)){
									if($days[0]==$days2[0]){
										return true;
									}else{
										return false;
									}
								}
							}
							$cnt++;
						}
						return false;
						$ctr++;
					}
					return false;
				}
			}
		}
	}	

	function checkScheduleForConflict($schedule_id_selected, $schedule_id){
		if($schedule_id_selected != '' &&  $schedule_id != ''){
			$sql = "SELECT * FROM tbl_schedule WHERE id = $schedule_id_selected";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$arr_str = array();
			$row['monday'] == 'Y' 		? $arr_str[]  = "monday-".$row['monday_time_from'].'-'.$row['monday_time_to']:'';
			$row['tuesday'] == 'Y' 		? $arr_str[]  = "tuesday-".$row['tuesday_time_from'].'-'.$row['tuesday_time_to']:'';
			$row['wednesday'] == 'Y' 	? $arr_str[]  = "wednesday-".$row['wednesday_time_from'].'-'.$row['wednesday_time_to']:'';
			$row['thursday'] == 'Y' 	? $arr_str[]  = "thursday-".$row['thursday_time_from'].'-'.$row['thursday_time_to']:'';
			$row['friday'] == 'Y' 		? $arr_str[]  = "friday-".$row['friday_time_from'].'-'.$row['friday_time_to']:'';
			$row['saturday'] == 'Y' 	? $arr_str[]  = "saturday-".$row['saturday_time_from'].'-'.$row['saturday_time_to']:'';
			$row['sunday'] == 'Y' 		? $arr_str[]  = "sunday-".$row['sunday_time_from'].'-'.$row['sunday_time_to']:'';
			$sql = "SELECT * FROM tbl_schedule WHERE id = $schedule_id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$arr_str_2 = array();
			$row['monday'] == 'Y' 		? $arr_str2[]  = "monday-".$row['monday_time_from'].'-'.$row['monday_time_to']:'';
			$row['tuesday'] == 'Y' 		? $arr_str2[]  = "tuesday-".$row['tuesday_time_from'].'-'.$row['tuesday_time_to']:'';
			$row['wednesday'] == 'Y' 	? $arr_str2[]  = "wednesday-".$row['wednesday_time_from'].'-'.$row['wednesday_time_to']:'';
			$row['thursday'] == 'Y' 	? $arr_str2[]  = "thursday-".$row['thursday_time_from'].'-'.$row['thursday_time_to']:'';
			$row['friday'] == 'Y' 		? $arr_str2[]  = "friday-".$row['friday_time_from'].'-'.$row['friday_time_to']:'';
			$row['saturday'] == 'Y' 	? $arr_str2[]  = "saturday-".$row['saturday_time_from'].'-'.$row['saturday_time_to']:'';
			$row['sunday'] == 'Y' 		? $arr_str2[]  = "sunday-".$row['sunday_time_from'].'-'.$row['sunday_time_to']:'';
			$ctr = 0;
			$cnt = 0;

			foreach($arr_str as $day){
				foreach($arr_str2 as $day2){
					$days = explode('-',$day);
					$days2 = explode('-',$day2);
					$str_time_from 	= str_replace(':','',$days[1]) * 1;
					$str_time_to 	= str_replace(':','',$days[2]) * 1;
					$str_time_from_2 	= str_replace(':','',$days2[1]) * 1;
					$str_time_to_2 	= str_replace(':','',$days2[2]) * 1;				 

					if(($str_time_from == $str_time_from_2 && $str_time_from == $str_time_to_2) || ($str_time_to == $str_time_from && $str_time_to == $str_time_to_2)){
						if($days[0]==$days2[0]){
							return true;
						}else{
							return false;
						}
					}else{
						if(($str_time_from >= $str_time_to && $str_time_from < $str_time_to_2) || ($str_time_to > $str_time_from_2 && $str_time_to <= $str_time_to_2)){
							if($days[0]==$days2[0]){
								return true;
							}else{
								return false;
							}
						}
					}
					$cnt++;
				}
				return false;
				$ctr++;
			}
			return false;
		}
	}	

	function checkScheduleIfConflict($schedule_id_selected, $schedule_id){
		$ret = '';
		$ctr = 0;
		if($schedule_id_selected != '' &&  $schedule_id != ''){
			$sql = "SELECT * FROM tbl_schedule WHERE id = $schedule_id_selected";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$str_time_from 	= str_replace(':','',$row['time_from']) * 1;
			$str_time_to 	= str_replace(':','',$row['time_to']) * 1;
			$arr_str = array();
			$row['monday'] == 'Y' 		? $arr_str[]  = "monday" 	:'';
			$row['tuesday'] == 'Y' 		? $arr_str[]  = "tuesday" 	:'';
			$row['wednesday'] == 'Y' 	? $arr_str[]  = "wednesday" :'';
			$row['thursday'] == 'Y' 	? $arr_str[]  = "thursday" 	:'';
			$row['friday'] == 'Y' 		? $arr_str[]  = "friday" 	:'';
			$row['saturday'] == 'Y' 	? $arr_str[]  = "saturday" 	:'';
			$row['sunday'] == 'Y' 		? $arr_str[]  = "sunday" 	:'';

			$sql = "SELECT * FROM tbl_schedule WHERE id = $schedule_id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$str_time_from_2 	= str_replace(':','',$row['time_from']) * 1;
			$str_time_to_2 	= str_replace(':','',$row['time_to']) * 1;
			$arr_str_2 = array();
			$row['monday'] == 'Y' 		? $arr_str_2[]  = "monday" 	:'';
			$row['tuesday'] == 'Y' 		? $arr_str_2[]  = "tuesday" 	:'';
			$row['wednesday'] == 'Y' 	? $arr_str_2[]  = "wednesday" :'';
			$row['thursday'] == 'Y' 	? $arr_str_2[]  = "thursday" 	:'';
			$row['friday'] == 'Y' 		? $arr_str_2[]  = "friday" 	:'';
			$row['saturday'] == 'Y' 	? $arr_str_2[]  = "saturday" 	:'';
			$row['sunday'] == 'Y' 		? $arr_str_2[]  = "sunday" 	:'';

			if($str_time_from == $str_time_from_2 && $str_time_from == $str_time_to){
				$ctr++;
			}else{
				if($str_time_from >= $str_time_from_2 && $str_time_from < $str_time_to_2 ){
					foreach($arr_str as $day){
						if(in_array($day,$arr_str_2)){
							$ctr++;
						}
					}
				}
			}

			if($str_time_to == $str_time_from_2 && $str_time_to == $str_time_to_2){
				$ctr++;
			}else{
				if($str_time_to >= $str_time_from_2 && $str_time_to < $str_time_to_2 ){
					foreach($arr_str as $day){
						if(in_array($day,$arr_str_2)){
							$ctr++;
						}
					}
				}
			}
		}

		if($ctr > 0){
			return true;
		}else{
			return false;
		}
	}

/* [-] ALL VALIDATOR FUNCTION */





/* [+] PUT HERE ALL FUNCTION WITH HTML */

	function getDayInCode($day){
		if($day=='monday'){
			return 'M';
		}else if($day=='tuesday'){
			return 'T';
		}else if($day=='wednesday'){
			return 'W';
		}else if($day=='thursday'){
			return 'TH';
		}else if($day=='friday'){
			return 'F';
		}else if($day=='saturday'){
			return 'S';
		}else if($day=='sunday'){
			return 'SU';
		}
	}

	function getTotalTuitionFeeOfStudent($student_id,$term_id){
		$sql = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id= $term_id";
		$result = mysql_query($sql); 
		$sub_total = 0;
		while($row = mysql_fetch_array($result)){
			$sub_total += getStudentTotalFeeLecLab($row['id'],$student_id );
		}
		return  $sub_total;  
	}

	function getTotalMiscellaneousFeeOfStudent($student_id,$term_id){
		$sql = "SELECT sum(school_fee.amount) as total_other_fee
				FROM tbl_school_fee as school_fee, tbl_student_fees as student_fee
				WHERE student_fee.fee_id = school_fee.id
				AND (school_fee.fee_type <> 'perunitlec' AND school_fee.fee_type <> 'perunitlab')
				AND school_fee.term_id=".$term_id." 
				AND school_fee.publish =  'Y'
				AND student_fee.student_id =" .$student_id;
		$query = mysql_query($sql);		
		$row = mysql_fetch_array($query);
		return $row['total_other_fee'];
	}

	function getTotalPaymentOfStudent($student_id,$term_id){
		$sql = "SELECT sum(amount) as payment FROM tbl_student_payment 
				WHERE term_id = $term_id AND  
					is_bounced <> 'Y' AND is_refund <> 'Y' AND 
					student_id =" .$student_id;
		$query = mysql_query($sql);		
		$row = @mysql_fetch_array($query);

		/*$sql_ref = "SELECT sum(amount) as refund
			FROM 
				tbl_student_payment 
			WHERE 
				term_id = $term_id AND  
				is_bounced <> 'Y' AND is_refund = 'Y' AND 
				student_id =" .$student_id;
		$query_ref = mysql_query($sql_ref);		
		$row_ref = mysql_fetch_array($query_ref);*/

		return $row['payment'];
	}

	function getStudentTotalDiscount($student_id,$term_id){
		if($student_id != '' && $term_id){
			$sql = "SELECT * FROM tbl_student_payment WHERE discount_id = $discount_id AND student_id = $student_id";
			$query = mysql_query($sql);		
			$row = @mysql_fetch_array($query);
			$sql_discount = "SELECT * FROM tbl_discount WHERE id=" .$row['discount_id'];
			$qry_discount = mysql_query($sql_discount);
			$row_discount = @mysql_fetch_array($qry_discount);
			$total_discounted = $total_lec_lab / 100 * $row_discount['value'];
			return $total_discounted;
		}
	}

	function displayStudentAssessment($student_id,$term_id){
		$arr_str = array();
		$arr_str[] = '<table class="classic_withoutWidth" width="100%">';
		$arr_str[] = '<tr>';
		$arr_str[] = '<th colspan="3">ASSESSMENT OF FEES</th>';
		$arr_str[] = '</tr>';
		$arr_str[] = '<tr>';
		$arr_str[] = '<th class="col1_150">Fees</th>';
		$arr_str[] = '<th class="col1_150">Amount</th>';
		$arr_str[] = '<th class="col1_150">Total</th>';
		$arr_str[] = '</tr>';
		$x = 1;
		$sql = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id= $term_id";
		$result = mysql_query($sql); 
		$sub_total = 0;
		while($row = mysql_fetch_array($result)){
			$total = getStudentTotalFeeLecLab($row['id'],$student_id );
			$class = ($x%2==0)?"":"highlight";
			$total_formated = $total==''?'0.00':number_format($total, 2, ".", ",");
			$arr_str[] = '<tr class="'.$class.'">';
			$arr_str[] = '<td>'.$row['fee_name'].'</td>';
			$arr_str[] = '<td>'.getFeeAmount($row['id']).'</td>';
			$arr_str[] = '<td>';
			$arr_str[] = '<div align="right">';
			$arr_str[] = 'Php '.$total_formated;
			$arr_str[] = '</div></td>';
			$arr_str[] = '</tr>';
			$x++;
		}
   
		$arr_str[] = '<tr>';
		$arr_str[] = '<td>&nbsp;</td>';
		$arr_str[] = '<td><strong>Total</strong></td>';
		$arr_str[] = '<td>';
		$arr_str[] = '<div align="right">';
		$arr_str[] = 'Php' . number_format(getTotalTuitionFeeOfStudent($student_id,$term_id), 2, ".", ",");
		$arr_str[] = '</div></td>';
		$arr_str[] = '</tr>';                
		$arr_str[] = '</table>';

		return implode('',$arr_str);
	}

	function displayStudentBalanceDetails($student_id,$term_id){
		$arr_str = array();
		$sub_total = getTotalTuitionFeeOfStudent($student_id,$term_id);
		$total_charges = ($sub_total - $total_discounted)-($total_payment);
		$total_lec_lab = $sub_total - getTotalMiscellaneousFeeOfStudent($student_id,$term_id);
		$total_discounted = getStudentDiscount($row_payment['discount_id'], $student_id, $total_lec_lab); 
		$total_payment = getTotalPaymentOfStudent($student_id,$term_id);
		$total_charges = $sub_total - $total;
		$total_rem_bal = $sub_total - getTotalPaymentOfStudent($student_id,$term_id);	
		$arr_str[] = '<table class="classic_borderless" width="100%">';
		$arr_str[] = '<tr>';
		$arr_str[] = '<td>Total Tuition Fee Amount:</td>';
		$arr_str[] = '<td align="right">Php ' . number_format($sub_total, 2, ".", ",") . '</td>';
		$arr_str[] = '</tr>';
		$arr_str[] = '<tr>';
		$arr_str[] = '<td>Balance Carried Forward:</td>';
		$arr_str[] = '<td><div align="right">Php 0.00</div></td>';
		$arr_str[] = '</tr>';
		$arr_str[] = '<tr>';
		$arr_str[] = '<td>Current Charges:</td>';
		$arr_str[] = '<td align="right">Php '.number_format($sub_total, 2, ".", ",").'</td>';
		$arr_str[] = '</tr>';
		$arr_str[] = '<tr>';
		$arr_str[] = '<td><strong>Total Current Charges:</strong></td>';
		$arr_str[] = '<td align="right">Php '.number_format($sub_total, 2, ".", ",").'</td>';
		$arr_str[] = '</tr>';

		if($row_payment['discount_id'] != '0'){
			$arr_str[] = '<tr>';
			$arr_str[] = '<td>Student Discount ('. getDiscountValue($row_payment['discount_id']) .'%)</td>';
			$arr_str[] = '<td align="right">Php ' . number_format($total_discounted, 2, ".", ",").'</td>';
			$arr_str[] = '</tr>';
		}else{
			$arr_str[] = '<tr>';
			$arr_str[] = '<td>Student Discount</td>';
			$arr_str[] = '<td><div align="right">Php 0.00</div></td>';
			$arr_str[] = '</tr>';
		}

		$arr_str[] = '<tr>';
		$arr_str[] = '<td colspan="2" class="bottom"></td>';
		$arr_str[] = '</tr>';
		$arr_str[] = '<tr>';
		$arr_str[] = '<td><strong>Total Charges:</strong></td>';
		$arr_str[] = '<td align="right"><strong>Php '.number_format($total_charges , 2, ".", ",").'</strong></td>';
		$arr_str[] = '</tr>';
		$arr_str[] = '<tr>';
		$arr_str[] = '<td><strong>Total Payment:</strong></td>';
		$arr_str[] = '<td align="right"><strong>Php '.number_format($total_payment, 2, ".", ",").'</strong></td>';
		$arr_str[] = '<input type="hidden" name="sub_total" id="sub_total" value="'.$row_total_payment['amount'].'" />';
		$arr_str[] = '</tr>';
		$arr_str[] = '<tr>';
		$arr_str[] = '<td><strong>Total Remaining Balance:</strong></td>';
		$arr_str[] = '<td align="right"><strong>Php '.number_format($total_rem_bal, 2, ".", ",") .'</strong></td>';
		$arr_str[] = '</tr>';                               
		$arr_str[] = '</table>';
		return implode('',$arr_str);	
	}

	function displayComponentList($access_id = ''){
		$arr_str = array();
		$arr_str[] = '<table class="listview">';
		$arr_str[] = '<thead>';
		$arr_str[] = '<tr>';
		$arr_str[] = '<th class="col_150">Main Menu</th>';
		$arr_str[] = '<th class="col_150">Sub Menu</th>';              
		$arr_str[] = '<th class="col_150">Component Name</th>';
		$arr_str[] = '<th class="col_50">View</th>';
		$arr_str[] = '<th class="col_50">Add/Edit</th>';    
		$arr_str[] = '<th class="col_50">Show in Dashboard</th>';              
		$arr_str[] = '</tr>';
		$arr_str[] = '</thead>';
		$arr_str[] = ' <tbody>';
		$sql = "SELECT * FROM tbl_components WHERE user_type = 'A' AND parent_id = 0";
		$query = mysql_query($sql);				
		while($row = mysql_fetch_array($query)){ 
			if(checkIfCompIfInAccess($access_id,$row["id"]) > 0){
				$comp_parent = $row["id"];
			}else{
				$comp_parent = '';
			}

			$arr_str[] = '<tr class="highlight">';
			$arr_str[] = '<td>'.$row["title"].'</td>';
			$arr_str[] = '<td><input name="comp_item[]" id="comp_item_'.$row["id"].'" type="hidden" value="'.$comp_parent.'" />
							<input name="view_access[]" id="view_access_'.$row["id"].'" type="hidden" value="'.$row["id"].'" />
							<input name="add_access[]" id="add_access_'.$row["id"].'" type="hidden" value="'.$row["id"].'" />
						</td>';
			$arr_str[] = '<td>&nbsp;</td>';				
			$arr_str[] = '<td>&nbsp;</td>';
			$arr_str[] = '<td>&nbsp;</td>';
			$arr_str[] = '<td>&nbsp;</td>';
			$arr_str[] = '</tr>';
			$arr_str[] = generateComponentChild($row['id'],$access_id);
		}
		$arr_str[] = '</tbody>';          
		$arr_str[] = '</table>';   
		return implode('',$arr_str);	   
	}

	function generateComponentChild($comp_id,$access_id = ''){
		$arr_str = array();
		$sql = "SELECT * FROM tbl_components WHERE user_type = 'A' AND parent_id = $comp_id AND published = 'Y' ORDER BY sort_order";
		$query = mysql_query($sql);
		while($row = mysql_fetch_array($query)){
			if(checkIfCompIfInAccess($access_id,$row["id"]) > 0){
				$comp_parent = $row["id"];
			}else{
				$comp_parent = '';
			}

			if(checkIfMenuHasChild($row['id']) > 0){	
				$arr_str[] = '<tr class="highlight">';
				$arr_str[] = '<td><input name="comp_item[]" id="comp_item_'.$row["id"].'" class="subParent" returnParentId="'.$comp_id.'"type="hidden" value="'.$comp_parent.'" />
								<input name="view_access[]" id="view_access_'.$row["id"].'" returnParentId="'.$comp_id.'" type="hidden" value="'.$row["id"].'" />
								<input name="add_access[]" id="add_access_'.$row["id"].'" returnParentId="'.$comp_id.'" type="hidden" value="'.$row["id"].'" /></td>';
				$arr_str[] = '<td>'.$row["title"].'</td>';
				$arr_str[] = '<td>&nbsp;</td>';				
				$arr_str[] = '<td>&nbsp;</td>';
				$arr_str[] = '<td>&nbsp;</td>';
				$arr_str[] = '<td>&nbsp;</td>';
				$arr_str[] = '</tr>';
				$arr_str[] = generateComponentChild($row['id'],$access_id);
			}else{
				if($access_id != ''){
					$allow_view = checkIfAccessIfViewIsAllow($access_id,$row["id"]) == 'Y'?'checked="checked"':'';
					$allow_add = checkIfAccessIfAddIsAllow($access_id,$row["id"]) == 'Y'?'checked="checked"':''	;	
					$allow_dash = checkIfAccessIfShowDashboardIsAllow($access_id,$row["id"]) == 'Y'?'checked="checked"':''	;		
				}else{
					$allow_view = '';
					$allow_add = '';	
				}		
				$arr_str[] = '<tr class="">';
				$arr_str[] = '<td><input name="comp_item[]" id="comp_item_'.$row["id"].'" returnParentId="'.$comp_id.'" type="hidden" value="'.$comp_parent.'" />&nbsp;</td>';
				$arr_str[] = '<td>&nbsp;</td>';
				$arr_str[] = '<td>'.$row["title"].'</td>';				
				$arr_str[] = '<td><input name="view_access[]" id="view_access_'.$row["id"].'" returnParentId="'.$comp_id.'" class="access_view" type="checkbox" value="'.$row["id"].'" '.$allow_view.'/></td>';
				$arr_str[] = '<td><input name="add_access[]" id="add_access_'.$row["id"].'"returnParentId="'.$comp_id.'"  class="access_add" type="checkbox" value="'.$row["id"].'" '.$allow_add.' /></td>';
				$arr_str[] = '<td><input name="dash_[]" id="dash_'.$row["id"].'"returnParentId="'.$comp_id.'"  class="access_add" type="checkbox" value="'.$row["id"].'" '.$allow_dash.' onclick="countCheck(this.value);" /></td>';
				$arr_str[] = '</tr>';
			}	
		}
	return implode('',$arr_str);	  
	}

	function checkIfMenuHasChild($comp_id){
		$sql = "SELECT * FROM tbl_components WHERE user_type = 'A' AND parent_id = $comp_id AND published = 'Y' ORDER BY sort_order";
		$query = mysql_query($sql);
		$ctr = mysql_num_rows($query);
		return $ctr;
	}

	function checkIfCompIfInAccess($access_id,$comp_id){
		if($access_id != ''){
			$sql = "SELECT * FROM tbl_access_role WHERE access_id = $access_id AND component_id = $comp_id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			$ctr = mysql_num_rows($query);
			return $ctr;
		}
	}

	function checkIfAccessIfViewIsAllow($access_id,$comp_id){
		if($access_id != ''){
			$sql = "SELECT can_view FROM tbl_access_role WHERE access_id = $access_id AND component_id = $comp_id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['can_view'];
		}
	}

	function checkIfAccessIfAddIsAllow($access_id,$comp_id){
		if($access_id != ''){
			$sql = "SELECT can_add FROM tbl_access_role WHERE access_id = $access_id AND component_id = $comp_id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['can_add'];
		}
	}

	function checkIfAccessIfShowDashboardIsAllow($access_id,$comp_id){
		if($access_id != ''){
			$sql = "SELECT show_dashboard FROM tbl_access_role WHERE access_id = $access_id AND component_id = $comp_id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			return $row['show_dashboard'];
		}
	}

	function getCanEditComponentName($id){
		if($id != ''){
			$sql = "SELECT * FROM tbl_components WHERE id = $id";
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
		}
		return $row['unique_friendly_title'];
	}

	function getStudentNewYrLevel($student_id){
		$yr_lvl = 1;
	
		//MODIFIED BY MKT
		$sql = "SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status = 'E' AND student_id = ".$student_id;
		$query = mysql_query($sql);
		$rows = mysql_num_rows($query);
	
		if($rows>0){
			$yr_lvl = round($rows/getNumberOfTerm());
		}

		/*
		$sql = "SELECT * 
				FROM 
					tbl_student_reserve_subject 
				WHERE 
					student_id =" .$student_id;
		$query = mysql_query($sql);
		
		while($row = mysql_fetch_array($query)){
			$sql_yrlvl = "SELECT * FROM 
									tbl_curriculum_subject 
							WHERE 
									subject_id= ". $row['subject_id']." AND 
									curriculum_id = ".getStudentCurriculumID($student_id);
			$query_yrlvl = mysql_query($sql_yrlvl);	
			$row_yrlvl = mysql_fetch_array($query_yrlvl);	

			if($row_yrlvl['year_level'] > $yr_lvl ){
				$yr_lvl = $row_yrlvl['year_level'];
			}
		}*/
		return $yr_lvl;
	}

/* [-] PUT HERE ALL FUNCTION WITH HTML */



/* [+] FUNCTIONS FOR EDITABLE RECORDS */

	function checkBuildingEditable($id){
		if($id != ''){
			$sql_room= "SELECT * FROM tbl_room WHERE building_id =" .$id;
			$qry_room = mysql_query($sql_room);
			$arr = array();

			while($row = mysql_fetch_array($qry_room)){
				$sql_sched= "SELECT * FROM tbl_schedule WHERE room_id = " .$row['id']. " AND term_id=" .CURRENT_TERM_ID;
				$qry_sched = mysql_query($sql_sched);
				$ctr = mysql_num_rows($qry_sched);

				if($ctr > 0){
					$arr[] = $row['id'];
				}
			}
		}

		if(count($arr) == 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkRoomEditable($id){
		if($id != ''){
			$sql_sched= "SELECT * FROM tbl_schedule WHERE room_id = " .$id. " AND term_id=" .CURRENT_TERM_ID;
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);
			$cnt = 0;
			
			if($ctr > 0){
				$cnt++;
			}
		}

		if($cnt == 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkCourseEditable($id){
		if($id != ''){
			$sql_sched = "SELECT * FROM tbl_student stud,tbl_enrollment_date date WHERE stud.course_id = date.course_id AND stud.course_id =" .$id." AND date.term_id =".CURRENT_TERM_ID;
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);
			$cnt = 0;

			if($ctr > 0){
				$cnt++;
			}
		}

		if($cnt == 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkCollegeEditable($id){
		if($id != ''){
			$sql_sched = "SELECT * FROM tbl_course WHERE college_id =" .$id;
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);
			$cnt = 0;
				
			if($ctr > 0){
				$cnt++;
			}
		}
	
		if($cnt == 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkDepartmentEditable($id){
		if($id != ''){
			$sql_sched = "SELECT * FROM tbl_subject sub,tbl_schedule sched
						WHERE sub.id = sched.subject_id AND sched.term_id = ".CURRENT_TERM_ID.
						" AND sub.department_id =" .$id;
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);
			$cnt = 0;

			if($ctr > 0){
				$cnt++;
			}
		}
		
		if($cnt == 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkSubjectEditable($id){
		if($id != ''){
			$sql_sched = "SELECT * FROM tbl_subject sub,tbl_schedule sched
						WHERE sub.id = sched.subject_id AND sched.term_id = ".CURRENT_TERM_ID.
						" AND sub.id =" .$id;
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);
			$cnt = 0;

			if($ctr > 0){
				$cnt++;
			}
		}

		if($cnt == 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkCurriculumEditable($id){
		if($id != ''){
			$sql_student = "SELECT * FROM tbl_student WHERE curriculum_id = " .$id;
			$qry_student = mysql_query($sql_student);
			$ctr = mysql_num_rows($qry_student);
		}

		if($ctr > 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkFeeEditable($id,$term_id){
		if($id != ''){
			$sql_student = "SELECT * FROM tbl_school_fee WHERE term_id = ".$term_id." AND id=" .$id;
			$qry_student = mysql_query($sql_student);
			$ctr = mysql_num_rows($qry_student);
		}

		if($ctr == 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkPaymentMethodEditable($id){
		if($id != ''){
			$sql_student = "SELECT * FROM tbl_student_payment WHERE payment_method = ".$id." AND term_id = ".CURRENT_TERM_ID;
			$qry_student = mysql_query($sql_student);
			$ctr = mysql_num_rows($qry_student);
		}

		if($ctr == 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkPaymentTypeEditable($id){
		if($id != ''){
			$sql_student = "SELECT * FROM tbl_other_payments WHERE type_id = ".$id." AND term_id = ".CURRENT_TERM_ID;
			$qry_student = mysql_query($sql_student);
			$ctr = mysql_num_rows($qry_student);
		}

		if($ctr == 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkDiscountEditable($id){
		if($id != ''){
			$sql_student = "SELECT * FROM tbl_student_payment WHERE discount_id = ".$id." AND term_id = ".CURRENT_TERM_ID;
			$qry_student = mysql_query($sql_student);
			$ctr = mysql_num_rows($qry_student);
		}

		if($ctr == 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkSchemeEditable($id,$term_id){
		if($id != ''){
			$sql_sched= "SELECT * FROM tbl_student_payment pay, tbl_payment_scheme scheme 	
						WHERE pay.payment_scheme_id=scheme.id AND scheme.id = " .$id.
						" AND scheme.term_id = ".$term_id;
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);
		}

		if($ctr == 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkScheduleEditable($id){
		if($id != ''){
			$sql_sched= "SELECT * FROM tbl_student_schedule WHERE schedule_id = " .$id." AND term_id = ".CURRENT_TERM_ID;
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);
		}

		if($ctr == 0 ){
			return true;
		}else{
			return false;
		}
	}

	function checkBlockEditable($id){
		if($id != ''){
			$sql_sched= "SELECT * FROM tbl_student_enrollment_status WHERE block_id = " .$id." AND term_id = ".CURRENT_TERM_ID;
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);
		}

		if($ctr == 0 ){
			return true;
		}else{
			return false;
		}
	}

/* [-] FUNCTIONS FOR EDITABLE RECORDS */

/* ENCRYPTION FUNCTION */

	function bytexor($a,$b,$l){
		$c="";
		for($i=0;$i<$l;$i++){
			$c.=$a{$i}^$b{$i};
		}
		return($c);
	}

	function binmd5($val){
		return(pack("H*",md5($val)));
	}

	function decrypt($msg){
		$key = CORE_U_CODE;
		$sifra="";
		$key1=binmd5($key);

		while($msg){
			$m=substr($msg,0,16);
			$msg=substr($msg,16);
			$sifra.=$m=bytexor($m,$key1,16);
			$key1=binmd5($key.$key1.$m);
		}
		
		return($sifra);
	}

	function encrypt($msg) {
		$key = CORE_U_CODE;
		$sifra="";
		$key1=binmd5($key);

		while($msg){
			$m=substr($msg,0,16);
			$msg=substr($msg,16);
			$sifra.=bytexor($m,$key1,16);
			$key1=binmd5($key.$key1.$m);
		}

		return(addslashes($sifra));
	}
  

?>