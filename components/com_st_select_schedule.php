<?php
/* #############################################################
*	
*					 -- DO NOT REMOVED --
*
* 	  			THIS CODE IS CREATED FOR CORE SIS.
*	  USING IT WITHOUT THE PROPER PERMISSION FROM EGLOBALMD
*			 IS PROHIBITED BUT LIMITED FROM OTHER 
*				  OPEN SOURCE THAT ARE USED.
*
*	------------------------------------------------------------
*	
*	CREATED BY: JBG
*	DATE CREATED: 01 JANUARY 2010
*	FOR ANY ISSUE AND BUG FIXES VISIT http://www.eglobalmd.com
*	OR E-mail support@eglobalmd.com
*
*  ############################################################ */

if(!isset($_REQUEST['comp']))
{
include_once("../config.php");
include_once("../includes/functions.php");
include_once("../includes/common.php");
}


if(USER_IS_LOGGED != '1')
{
	echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main
}
else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}



$page_title = 'Schedule Reservation';
$pagination = 'Enrollment  > Schedule Reservation';

$view = $view==''?'list':$view; // initialize action 

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$student_id 				= $_REQUEST['stdid'];	
$schedule_id				= $_REQUEST['schedule_id'];	
$subject_type				= $_REQUEST['subject_type'];	
$elective_of				= $_REQUEST['elective_of'];	
$term_id					= $_REQUEST['term'];
$units						= $_REQUEST['units'];
$subject_id					= $_REQUEST['subject_id'];
$scheme_id					= $_REQUEST['scheme_id'];
$schedule_id_dispalay		= $_REQUEST['schedule_id_dispalay'];
$sched 						= $_REQUEST['sched'];


if($action == 'save')
{
	$conflict = false;
	if(count($schedule_id)>1)
	{
		foreach($schedule_id as $sched_needle)
		{
			foreach($schedule_id as $sched_compare)
			{
				if($sched_needle!= $sched_compare)
				{
					if(checkScheduleForConflict($sched_needle, $sched_compare))
					{
						$conflict =  true;
						break;
					}
				}
			}
			if($conflict === true)
			{
				break;
			}
		}
	}
	
	if($schedule_id == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if($conflict == true)
	{
		$err_msg = 'Conflict schedule found.';	
	}
	else
	{
		$ctr = 0 ;
		
		foreach($schedule_id as $schedID){
			if($schedID != '')
			{
				deductSlot($schedID);
				
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
						".GetSQLValueString(USER_STUDENT_ID,"int").",  
						".GetSQLValueString($term_id,"int").", 
						".GetSQLValueString($units[$ctr],"int").", 
						".GetSQLValueString($subject_id[$ctr],"int").", 
						".GetSQLValueString($elective_of[$ctr],"int").", 
						".time().",
						".USER_ID.",		
						".time()."
					)";
					
			
				mysql_query ($sql);
			}
			$ctr ++;
		}
		
		storeStudentFee(USER_STUDENT_ID,$term_id);
		storeStudentOtherFee(USER_STUDENT_ID,$term_id);
		
		$reserv_days = getStudentReservationDay($student_id);
		$exp_date = mktime(0, 0, 0, date("m") , date("d")+ $reserv_days, date("Y"));
		
		if(checkIfStudentReservationIsExpired())
		{
			$sql = "UPDATE tbl_student_enrollment_status SET 
				enrollment_status = ".GetSQLValueString('R',"text").",
				days_of_reservation = ".GetSQLValueString($reserv_days,"int").", 
				scheme_id = ".GetSQLValueString($scheme_id,"int").",
				date_reserved = ".GetSQLValueString(time(),"int").", 
				expiration_date = ".GetSQLValueString($exp_date,"int")." 
				WHERE student_id = " . $student_id;
				
			if(mysql_query($sql))
			{
				// [+] STORE SYSTEM LOGS
					$param = array(	
									getSYandTerm($term_id)
								   );
					storeSystemLogs(MSG_STUDENT_RESERVE_SUBJECT,$param,'','Y','Y','N','N');			
				// [-] STORE SYSTEM LOGS				
				
				//notification(getParentUser($student_id),MSG_STUDENT_RESERVE_SUBJECT,$param,getStudentUser($student_id));
				
				$admins = getUserAdmin();
				foreach($admins as $admin)
				{
					notification($admin,MSG_STUDENT_RESERVE_SUBJECT,$param,getStudentUser($student_id));					
				}
			}
		}
		else
		{
			$sql = "INSERT INTO tbl_student_enrollment_status 
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
					".GetSQLValueString($scheme_id,"int").",  
					".GetSQLValueString('R',"text").",
					".GetSQLValueString($reserv_days,"int").", 
					".GetSQLValueString(time(),"int").", 
					".GetSQLValueString($exp_date,"int")." 
				)";	

			if(mysql_query($sql))
			{
				// [+] STORE SYSTEM LOGS
					$param = array(	
									getSYandTerm($term_id)
								   );
					storeSystemLogs(MSG_STUDENT_RESERVE_SUBJECT,$param,'','Y','Y','N','N');			
				// [-] STORE SYSTEM LOGS	
				
				//notification(getParentUser($student_id),MSG_STUDENT_RESERVE_SUBJECT,$param,getStudentUser($student_id));
				
				$admins = getUserAdmin();
				foreach($admins as $admin)
				{
					notification($admin,MSG_STUDENT_RESERVE_SUBJECT,$param,getStudentUser($student_id));					
				}			
			}
		}
		
		echo '<script language="javascript">window.location =\'index.php?comp=com_st_select_schedule\';</script>';
	}	
}
else if($action == 'enrol_block'){
	
	if(checkStudentEnrolledSubjectBlock($sched,$student_id))
		{
			$err_msg = 'Some Subject in block Aready Enrolled.';
		}
		else if(checkStudentUnenrollSubjectPreqBlock($sched,$student_id))
		{
			$err_msg = 'Some Subject Prerequisite in block is not yet Enrolled.';
		}
		else if(checkSlotAvailableInBlock($sched,$student_id))
		{
			$err_msg = 'Some Subject Slot in block is not Available.';
		}
		else
		{
			$sql = "SELECT * 
						FROM tbl_block_subject a 
						LEFT JOIN tbl_schedule b ON a.schedule_id = b.id 
						LEFT JOIN tbl_curriculum_subject c ON b.subject_id=c.subject_id 
						WHERE a.block_section_id = ".$sched." 
						AND c.curriculum_id = ".USER_CURRICULUM_ID;
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
						".time().",
						".USER_ID.",		
						".time()."
					)";
					
			
				mysql_query ($sql);

			}

		
		storeStudentFee(USER_STUDENT_ID);
		storeStudentOtherFee(USER_STUDENT_ID);
		
		$reserv_days = getStudentReservationDay($student_id);
		$exp_date = mktime(0, 0, 0, date("m") , date("d")+ $reserv_days, date("Y"));
		
		if(checkIfStudentReservationIsExpired())
		{
			$sql = "UPDATE tbl_student_enrollment_status SET 
				enrollment_status = ".GetSQLValueString('R',"text").",
				days_of_reservation = ".GetSQLValueString($reserv_days,"int").", 
				scheme_id = ".GetSQLValueString($scheme_id,"int").",
				date_reserved = ".GetSQLValueString(time(),"int").", 
				block_id = ".GetSQLValueString($sched,"int").", 
				expiration_date = ".GetSQLValueString($exp_date,"int")." 
				WHERE student_id = " . $student_id;
				
			mysql_query($sql);
		}
		else
		{
			$sql = "INSERT INTO tbl_student_enrollment_status 
				(
					student_id, 
					term_id,
					enrollment_status,
					block_id,
					scheme_id,
					days_of_reservation,
					date_reserved,
					expiration_date
				) 
				VALUES 
				(
					".GetSQLValueString($student_id,"int").",  
					".GetSQLValueString($term_id,"int").",  
					".GetSQLValueString('R',"text").",
					".$sched.",
					".GetSQLValueString($scheme_id,"int").",
					".GetSQLValueString($reserv_days,"int").", 
					".GetSQLValueString(time(),"int").", 
					".GetSQLValueString($exp_date,"int")." 
				)";	
	
			if(mysql_query($sql))
			{
				// [+] STORE SYSTEM LOGS
					$param = array(	
									getSYandTerm($term_id)
								   );
					storeSystemLogs(MSG_STUDENT_RESERVE_IN_BLOCK_SUBJECT,$param,'','Y','Y','N','N');			
				// [-] STORE SYSTEM LOGS	

				//notification(getParentUser($student_id),MSG_STUDENT_RESERVE_IN_BLOCK_SUBJECT,$param,getStudentUser($student_id));
				
				$admins = getUserAdmin();
				foreach($admins as $admin)
				{
					notification($admin,MSG_STUDENT_RESERVE_IN_BLOCK_SUBJECT,$param,getStudentUser($student_id));					
				}			
			}
		}
		
		echo '<script language="javascript">window.location =\'index.php?comp=com_st_select_schedule\';</script>';
		}
}
if($view == 'block'){

	$str_arr = array();
	echo $sql='SELECT * FROM tbl_block_section WHERE course_id = '.USER_COURSE_ID.' AND term_id = '.CURRENT_TERM_ID;
	$result = mysql_query($sql);
 
        if (mysql_num_rows($result) > 0 )
        {
            $x = 0;
        
        
       $str_arr[] ='<table class="listview">';      
          $str_arr[] ='<tr>';
              $str_arr[] ='<th class="col_150">Block Name</th>';
              $str_arr[] ='<th class="col_150">Action</th>';
          $str_arr[] ='</tr>';
        
            while($row = mysql_fetch_array($result)) 
            { 
        
            $str_arr[] ='<tr class="'.($x%2==0).'""?"":"highlight"">';
                $str_arr[] ='<td>'.$row["block_name"].'</td>';
                $str_arr[] ='<td class="action">';
                    $str_arr[] ='<ul>';
	                    $str_arr[] ='<li><a class="applicant" href="#" title="View Subjects" returnId='.$row['id'].'></a></li>';
						 $str_arr[] ='<li><a class="publish" href="#" title="Reserve" returnId='.$row['id'].'></a></li>';
                    $str_arr[] ='</ul>';
                $str_arr[] ='</td>';
            $str_arr[] ='</tr>';
          
           }
        }
        else 
        {
                $str_arr[] = '<div id="message_container"><h4>No block records is found</h4></div>';
        }

        $str_arr[] ='</table>';

	$block_list = implode('',$str_arr);
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_st_select_schedule.php';
?>