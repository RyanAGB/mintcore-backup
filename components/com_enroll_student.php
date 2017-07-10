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

$page_title = 'Enroll Student';
$pagination = 'Student > Enroll Student';
$view = $view==''?'list':$view; // initialize action
$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];
$student_id = $_REQUEST['student_id'];
$misc_id = $_REQUEST['misc_id'];

$term_id					= $_REQUEST['term'];
$units						= $_REQUEST['units'];
$subject_id					= $_REQUEST['subject_id'];
$schedule_id_dispalay		= $_REQUEST['schedule_id_dispalay'];
$enrolled					= $_REQUEST['enrolled'];
$schedule_id				= $_REQUEST['schedule_id'];
$sched						= $_REQUEST['sched'];
$dropped					= $_REQUEST['dropped'];
$add_sub					= $_REQUEST['add_sub'];
$scheme						= $_REQUEST['scheme'];
$elective_of				= $_REQUEST['elective_of'];
$fees_ID					= $_REQUEST['fees_ID'];
$ch_sub						= $_REQUEST['ch_sub'];

$filter_field = $_REQUEST['filter_field'];
$filter_order = $_REQUEST['filter_order'];
$page = $_REQUEST['page'];

if($_SESSION[CORE_U_CODE]['current_comp'] != $comp || $_SESSION[CORE_U_CODE]['pageNum'] != $page || $_SESSION[CORE_U_CODE]['fieldName'] != $filter_field || $_SESSION[CORE_U_CODE]['orderBy'] != $filter_order){
	if($page != ''){
		$_SESSION[CORE_U_CODE]['pageNum'] = isset($page)&&$page!='' ? $page : '1';
	}
	
	if($filter_field != '' || $filter_order != ''){
		$_SESSION[CORE_U_CODE]['fieldName'] = $filter_field;
		$_SESSION[CORE_U_CODE]['orderBy'] = $filter_order;
	}
	
	$_SESSION[CORE_U_CODE]['current_comp'] = $comp;
}

if($action == 'save'){
	$conflict = false;
	if(count($schedule_id)>1){
		foreach($schedule_id as $sched_needle){
			foreach($schedule_id as $sched_compare){
				if($sched_needle!= $sched_compare){
					if(checkScheduleForConflict($sched_needle, $sched_compare)){
						$conflict =  true;
						break;
					}
				}
			}
			
			if($conflict){
				break;
			}
		}
	}
	
	if($conflict == true){
		$err_msg = 'This time slot conflicts with your existing schedule.';	
	}	
	/*else if($scheme=='')
	{
		$err_msg = 'Please select a payment scheme';
	}*/
	else if(checkIfSchoolFeeIsComplete($id)){
		$err_msg = 'Incomplete School Fee. Set-up school fees first';
	}else{
		$ctr = 0 ;
		
		$sqldel = "DELETE FROM tbl_student_reserve_subject WHERE 
					student_id= ".$student_id." AND 
					term_id =".$term_id;
					
		$resdel= mysql_query($sqldel);			

		foreach($schedule_id as $schedID){
			if($schedID != ''){
				//deductSlot($schedID);
				$sql = "INSERT INTO tbl_student_reserve_subject 
				(
					schedule_id, 
					student_id, 
					term_id,
					units,
					subject_id,
					elective_of,
					date_created,
					created_by,
					date_modified
				) 
				VALUES 
				(
					".GetSQLValueString($schedID,"int").",  
					".GetSQLValueString($student_id,"int").",  
					".GetSQLValueString($term_id,"int").", 
					".GetSQLValueString($units[$ctr],"int").", 
					".GetSQLValueString($subject_id[$ctr],"int").", 
					".GetSQLValueString($elective_of[$ctr],"int").", 
					".time().",
					".USER_ID.",		
					".time()."
				)";
				mysql_query($sql);
					
					if(getSubjType($subject_id[$ctr]) == 'Lec')
					{
						$fee_id = getFeeTypeId('perunitlec');
					}
					else if(getSubjType($subject_id[$ctr]) == 'Lab')
					{
						$fee_id = getFeeTypeId('perunitlab');
					}
				
			}
			$ctr ++;
	    }	
		
		storeStudentFee($student_id,getDefaultStudentFeeTerm($student_id));
		storeStudentOtherFee($student_id,getDefaultStudentFeeTerm($student_id));
		
		computeAllSlotByStudentSched($term_id);
		
		//$reserv_days = getStudentReservationDay($student_id);
		//$exp_date = mktime(0, 0, 0, date("m") , date("d")+ $reserv_days, date("Y"));	
		
		if(!checkIfStudentIsReserveByStudId($student_id)){
			storeDefaultScheme($term_id,$student_id,0);
			/*$sql = "INSERT INTO tbl_student_enrollment_status 
				(
					student_id, 
					term_id,
					scheme_id,
					enrollment_status,
					days_of_reservation,
					date_reserved,
					expiration_date
				) 
				VALUES 
				(
					".GetSQLValueString($student_id,"int").",  
					".GetSQLValueString($term_id,"int").", 
					".GetSQLValueString($scheme,"int").",  
					".GetSQLValueString('R',"text").",
					".GetSQLValueString($reserv_days,"int").", 
					".GetSQLValueString(time(),"int").", 
					".GetSQLValueString($exp_date,"int")." 
				)";	
			
			if(mysql_query($sql))
			{*/
				
							
				// [+] STORE SYSTEM LOGS
					$param = array(	
									getStudentNumber($student_id),
									getSYandTerm($term_id)
								   );
					storeSystemLogs(MSG_ADMIN_RESERVE_SUBJECT_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
				// [-] STORE SYSTEM LOGS	
				
				/*temporary disabled
				notification(getStudentUser($student_id),MSG_ADMIN_RESERVE_SUBJECT_FOR_STUDENT,$param,USER_ID);
				notification(getParentUser($student_id),MSG_ADMIN_RESERVE_SUBJECT_FOR_STUDENT,$param,USER_ID);*/
										
				echo '<script language="javascript">alert("Successfully Reserved a student!");window.location =\'index.php?comp=com_enroll_student&student_id='.$student_id.'\';</script>';
			//}		   	
		}else if(checkIfStudentReservationIsExpiredByStudId($student_id)){
			 $sql = "UPDATE tbl_student_enrollment_status SET 
						enrollment_status = ".GetSQLValueString('R',"text").",
						scheme_id = ".GetSQLValueString($scheme,"int").",
						days_of_reservation = ".GetSQLValueString($reserv_days,"int").", 
						date_reserved = ".GetSQLValueString(time(),"int").", 
						expiration_date = ".GetSQLValueString($exp_date,"int")." 
						WHERE student_id = " . $student_id;
				
			if(mysql_query($sql)){			
				// [+] STORE SYSTEM LOGS
					$param = array(	
									getStudentNumber($student_id),
									getSYandTerm($term_id)
								   );
					storeSystemLogs(MSG_ADMIN_RESERVE_SUBJECT_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
				// [-] STORE SYSTEM LOGS	
				
				/*temporary disabled
				notification(getStudentUser($student_id),MSG_ADMIN_RESERVE_SUBJECT_FOR_STUDENT,$param,USER_ID);
				notification(getParentUser($student_id),MSG_ADMIN_RESERVE_SUBJECT_FOR_STUDENT,$param,USER_ID);*/
										
				echo '<script language="javascript">alert("Successfully Reserved a student!");window.location =\'index.php?comp=com_enroll_student&student_id='.$student_id.'\';</script>';
			}	
		}else{
			/*$sql = "UPDATE tbl_student_enrollment_status SET 
				scheme_id = ".GetSQLValueString($scheme,"int")." 
				WHERE student_id = " . $student_id;
				
			mysql_query($sql);*/
			
			
			// [+] STORE SYSTEM LOGS
			$param = array(	
							getStudentNumber($student_id),
							getSYandTerm($term_id)
							);
			storeSystemLogs(MSG_ADMIN_MODIFIED_RESERVE_SUBJECT_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
			// [-] STORE SYSTEM LOGS	
				
			/*temporary disabled
			notification(getStudentUser($student_id),MSG_ADMIN_MODIFIED_RESERVE_SUBJECT_FOR_STUDENT,$param,USER_ID);
			notification(getParentUser($student_id),MSG_ADMIN_MODIFIED_RESERVE_SUBJECT_FOR_STUDENT,$param,USER_ID);*/
				
			echo '<script language="javascript">alert("Successfully modified the reserved subject!");window.location =\'index.php?comp=com_enroll_student&student_id='.$student_id.'\';</script>';				
		}
	}
}else if($action == 'enrol_block'){
	
		if(checkStudentEnrolledSubjectBlock($sched,$student_id)){
			$err_msg = 'Some subject(s) from the selected block section are already taken by the student.';
		}
		/*else if(checkStudentUnenrollSubjectPreqBlock($sched,$student_id))
		{
			$err_msg = 'Some Prerequisite subject(s) from the selected block section are not yet Enrolled or Failed.';
		}
		else if(checkStudentCoReqSubjectInBlock($sched,$student_id))
		{
			$err_msg = 'Some Co-requisite subject(s) from the selected block section do not Exists or not yet Enrolled or Failed.';
		}*/
		else if(checkSlotAvailableInBlock($sched,$student_id)){
			$err_msg = 'Some subject(s) slots from the selected block section are already Full.';
		}else{
			$ctr = 0 ;
			/*
			// [+] Re-credit Slot
			$sqlrev = "SELECT schedule_id FROM tbl_student_reserve_subject WHERE 
				student_id = ".$student_id." AND 
				term_id = ".$term_id;
			$queryrev = mysql_query($sqlrev);	
			while($rowrev = mysql_fetch_array($queryrev))
			{
				reverseSlot($rowrev['schedule_id']);
			}
			// [-] Re-credit Slot		
			*/		
			$sqldel = "DELETE FROM tbl_student_reserve_subject WHERE 
						student_id= ".$student_id." AND 
						term_id =".$term_id;
						
			$resdel= mysql_query($sqldel);

			$sql = "SELECT * FROM tbl_block_subject a 
					LEFT JOIN tbl_schedule b ON a.schedule_id = b.id 
					LEFT JOIN tbl_curriculum_subject c ON b.subject_id=c.subject_id 
					WHERE a.block_section_id = ".$sched." 
					AND c.curriculum_id = ".getStudentCurriculumID($student_id);
			$query = mysql_query($sql);
	
			while($row = mysql_fetch_array($query)){
	
				deductSlot($row['schedule_id']);

			 	$sql = "INSERT INTO tbl_student_reserve_subject 
					(
						schedule_id, 
						student_id, 
						term_id, 
						units,
						subject_id,
						elective_of,
						date_created,
						created_by,
						date_modified
					) 
					VALUES 
					(
						".$row['schedule_id'].",  
						".$student_id.",  
						".$term_id.", 
						".$row['units'].", 
						".$row['subject_id'].",
						'".$row['elective_of']."',
						".time().",
						".USER_ID.",		
						".time()."
					)";
					
			
				mysql_query ($sql);

			}

		
			storeStudentFee($student_id,getDefaultStudentFeeTerm($student_id));
			storeStudentOtherFee($student_id,getDefaultStudentFeeTerm($student_id));
			
			//$reserv_days = getStudentReservationDay($student_id);
			//$exp_date = mktime(0, 0, 0, date("m") , date("d")+ $reserv_days, date("Y"));
			
			//computeAllSlotByStudentSched($term_id);
		
			if(!checkIfStudentIsReserveByStudId($student_id)){
				storeDefaultScheme($term_id,$student_id,$sched);
				/*
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
						".GetSQLValueString($student_id,"int").",  
						".GetSQLValueString($term_id,"int").",
						".GetSQLValueString($scheme,"int").",  
						".GetSQLValueString('R',"text").",
						".$sched.",
						".GetSQLValueString($reserv_days,"int").", 
						".GetSQLValueString(time(),"int").", 
						".GetSQLValueString($exp_date,"int")." 
					)";	
				if(mysql_query($sql))
				{	*/	
					// [+] STORE SYSTEM LOGS
					$param = array(	
									getStudentNumber($student_id),
									getSYandTerm($term_id)
								   );
					storeSystemLogs(MSG_ADMIN_RESERVE_IN_BLOCK_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
				// [-] STORE SYSTEM LOGS	
				
				/*temporary disabled
				notification(getStudentUser($student_id),MSG_ADMIN_RESERVE_IN_BLOCK_FOR_STUDENT,$param,USER_ID);
				notification(getParentUser($student_id),MSG_ADMIN_RESERVE_IN_BLOCK_FOR_STUDENT,$param,USER_ID);*/
												
				echo '<script language="javascript">alert("Successfully Enrolled student in a block!");window.location =\'index.php?comp=com_enroll_student&student_id='.$student_id.'\';</script>';
			//}		   	
		}
		else if(checkIfStudentReservationIsExpiredByStudId($student_id))
		{
			$sql = "UPDATE tbl_student_enrollment_status SET 
				enrollment_status = ".GetSQLValueString('R',"text").",
				block_id = ".$sched.",
				days_of_reservation = ".GetSQLValueString($reserv_days,"int").",
				scheme_id = ".GetSQLValueString($scheme,"int").", 
				date_reserved = ".GetSQLValueString(time(),"int").", 
				expiration_date = ".GetSQLValueString($exp_date,"int")." 
				WHERE student_id = " . $student_id;
				
			if(mysql_query($sql)){		
				// [+] STORE SYSTEM LOGS
				$param = array(	
								getStudentNumber($student_id),
								getSYandTerm($term_id)
							   );
				storeSystemLogs(MSG_ADMIN_RESERVE_IN_BLOCK_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
				// [-] STORE SYSTEM LOGS	
				
				/*temporary disabled
				notification(getStudentUser($student_id),MSG_ADMIN_RESERVE_IN_BLOCK_FOR_STUDENT,$param,USER_ID);
				notification(getParentUser($student_id),MSG_ADMIN_RESERVE_IN_BLOCK_FOR_STUDENT,$param,USER_ID);*/
												
				echo '<script language="javascript">alert("Successfully Enrolled student in a block!");window.location =\'index.php?comp=com_enroll_student&student_id='.$student_id.'\';</script>';
			}	
		}else{
			// [+] STORE SYSTEM LOGS
			$param = array(	
							getStudentNumber($student_id),
							getSYandTerm($term_id)
							);
			storeSystemLogs(MSG_ADMIN_MODIFIED_RESERVE_SUBJECT_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
			// [-] STORE SYSTEM LOGS	
				
			/*temporary disabled
			notification(getStudentUser($student_id),MSG_ADMIN_MODIFIED_RESERVE_SUBJECT_FOR_STUDENT,$param,USER_ID);
			notification(getParentUser($student_id),MSG_ADMIN_MODIFIED_RESERVE_SUBJECT_FOR_STUDENT,$param,USER_ID);*/
				
			echo '<script language="javascript">alert("Successfully modified the reservation in a block!");window.location =\'index.php?comp=com_enroll_student&student_id='.$student_id.'\';</script>';				
		}		
	}
}else if($action == 'drop'){
	
	if(checkIfSubjectCanDropped($student_id,$sched)){
		$err_msg = 'You are not allowed to drop subject,final grade was already encoded.';
	}else{
		if($_REQUEST['deduct']!='N'){			
			$sql = "UPDATE tbl_student_fees SET is_removed = 'Y' WHERE subject_id = ".$dropped."
						 AND student_id =".$student_id.
						" AND term_id = ".CURRENT_TERM_ID;
			mysql_query($sql);
		}
		
		$ES = "D";
		$sqlu = "UPDATE tbl_student_schedule SET enrollment_status = '".$ES."' WHERE subject_id = ".$dropped." AND student_id =".$student_id." AND term_id = ".CURRENT_TERM_ID;
		
		if(mysql_query($sqlu)){						
			// [+] STORE SYSTEM LOGS
			$param = array(	
							getStudentNumber($student_id),
							getSYandTerm($term_id)
						   );
			storeSystemLogs(MSG_ADMIN_DROP_SUBJECT_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
			// [-] STORE SYSTEM LOGS	
				
			//notification(getStudentUser($student_id),MSG_ADMIN_DROP_SUBJECT_FOR_STUDENT,$param,USER_ID);
			//notification(getParentUser($student_id),MSG_ADMIN_DROP_SUBJECT_FOR_STUDENT,$param,USER_ID);
				
			echo '<script language="javascript">alert("Successfully Dropped Subject!");window.location =\'index.php?comp=com_enroll_student&student_id='.$student_id.'\';</script>';
		}
	}
}else if($action == 'add_subject'){
	
	if(checkIfSchoolFeeIsComplete($id)){
		$err_msg = 'Incomplete School Fee. Set-up school fees first';
	}else{
		
		$sqldel = "SELECT * FROM tbl_schedule WHERE id = ".$add_sub."
					AND term_id =".$term_id;
					
		$resdel= mysql_query($sqldel);	
		$row = mysql_fetch_array($resdel);	

		if(mysql_num_rows($resdel) > 0){
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
				".GetSQLValueString($add_sub,"int").", 
				".GetSQLValueString($row['subject_id'],"int").", 
				".GetSQLValueString(getSubjUnit($row["subject_id"]),"int").", 
				".GetSQLValueString('A',"text").", 
				".time().",
				".USER_ID.",		
				".time().",
				".USER_ID."
			)";
		
			storeStudentAddSubjectFee($student_id,$add_sub);
			
			if(mysql_query($sql)){		
				if(getSubjType($row['subject_id']) == 'Lec'){
					$fee_id = getFeeTypeId('perunitlec');
				}else if(getSubjType($row['subject_id']) == 'Lab'){
					$fee_id = getFeeTypeId('perunitlab');
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
										
				echo '<script language="javascript">alert("Successfully Added Subject!");window.location =\'index.php?comp=com_enroll_student&student_id='.$student_id.'\';</script>';
			}
		}		   	
	}
}else if($action == 'change_subject'){
	
	if(checkIfSchoolFeeIsComplete($id)){
		$err_msg = 'Incomplete School Fee. Set-up school fees first';
	}else{
		
		$sqldel = "SELECT * FROM tbl_schedule WHERE id = ".$add_sub."
					AND term_id =".$term_id;
					
		$resdel= mysql_query($sqldel);	
		$row = mysql_fetch_array($resdel);	

		if(mysql_num_rows($resdel) > 0){
			$sql = "UPDATE tbl_student_schedule 
					SET
						schedule_id=".GetSQLValueString($add_sub,"int").",
						subject_id=".GetSQLValueString($row['subject_id'],"int")." WHERE student_id = ".$student_id." AND subject_id=".$ch_sub." AND term_id=".CURRENT_TERM_ID;
		
			//storeStudentAddSubjectFee($student_id,$add_sub);
		
			if(mysql_query($sql)){		
				if(getSubjType($row['subject_id']) == 'Lec'){
					$fee_id = getFeeTypeId('perunitlec');
				}else if(getSubjType($row['subject_id']) == 'Lab'){
					$fee_id = getFeeTypeId('perunitlab');
				}
				
				$sqls = "UPDATE tbl_student_fees SET
							subject_id=".GetSQLValueString($row['subject_id'],"int")."
						WHERE student_id = ".$student_id." AND subject_id=".$ch_sub." AND term_id=".CURRENT_TERM_ID;	
			
				mysql_query($sqls);
			
				$sqlc = "INSERT INTO tbl_student_alter_subjects (fee_id,student_id,subject_id,amount,quantity,term_id,status,change_from,date_created) SELECT fee_id,student_id,".$row['id'].",amount,quantity,term_id,'C',".$ch_sub.",".time()." FROM tbl_student_fees WHERE subject_id=".$row['subject_id']." AND student_id=".$student_id." AND term_id=".CURRENT_TERM_ID;
				
				mysql_query($sqlc);
				
				/*// [+] STORE SYSTEM LOGS
				$param = array(	
								getStudentNumber($student_id),
								getSYandTerm($term_id)
								  );
				storeSystemLogs(MSG_ADMIN_ADD_SUBJECT_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
				// [-] STORE SYSTEM LOGS	
				
				notification(getStudentUser($student_id),MSG_ADMIN_ADD_SUBJECT_FOR_STUDENT,$param,USER_ID);
				notification(getParentUser($student_id),MSG_ADMIN_ADD_SUBJECT_FOR_STUDENT,$param,USER_ID);*/
										
				echo '<script language="javascript">alert("Successfully Change Subject!");window.location =\'index.php?comp=com_enroll_student&student_id='.$student_id.'\';</script>';
			}
		}		   	
	}
}else if($action == 'remove'){
	$sql = "UPDATE tbl_student_fees SET 
				is_removed = ".GetSQLValueString('Y',"text")."
				WHERE student_id = " . $student_id ."
				AND term_id = ".CURRENT_TERM_ID." AND id=".$misc_id;
				
	$query = mysql_query($sql);

}else if($action == 'add'){
		
	$sql = "UPDATE tbl_student_fees SET 
				is_removed = ".GetSQLValueString('N',"text")."
			WHERE student_id = " . $student_id ."
			AND term_id = ".CURRENT_TERM_ID." AND id=".$misc_id;
				
	$query = mysql_query($sql);
		
}else if($action == 'remove2'){
		
	$sql = "UPDATE tbl_student_other_fees SET 
				is_removed = ".GetSQLValueString('Y',"text")."
			WHERE student_id = " . $student_id ."
			AND term_id = ".CURRENT_TERM_ID." AND id=".$misc_id;
				
	$query = mysql_query($sql);
		
}else if($action == 'add2'){
	
	$sql = "UPDATE tbl_student_other_fees SET 
				is_removed = ".GetSQLValueString('N',"text")."
			WHERE student_id = " . $student_id ."
			AND term_id = ".CURRENT_TERM_ID." AND id=".$misc_id;
				
	$query = mysql_query($sql);
}
	
	
// component block, will be included in the template page
$content_template = 'components/block/blk_com_enroll_student.php';
?>