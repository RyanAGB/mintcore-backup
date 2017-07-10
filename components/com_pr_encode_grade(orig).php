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


$page_title = 'Manage Encode Grade';
$pagination = 'Grade  > Manage Encode Grade';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$student_id	= $_REQUEST['student_id'];
$subject_id	= $_REQUEST['subject_id'];
$term_id	= $_REQUEST['term_id'];
$period_id = $_REQUEST['period_id'];
$sched_id	= $_REQUEST['sched_id'];
$sheet_id	= $_REQUEST['sheet_id'];
$grade	= $_REQUEST['grade'];

if($action == 'save')
{
	
	$sqlgsheet = 'SELECT * FROM tbl_gradesheet WHERE school_yr_period_id = '.$period_id.' AND schedule_id = '.$sched_id;
	$querygsheet = mysql_query($sqlgsheet);
	
	if(mysql_num_rows($querygsheet)>0)
	{
		$rowgsheet = mysql_fetch_array($querygsheet);
		$sheet = $rowgsheet['id'];
	}
	else
	{
		//FOR GRADESHEET
				
				$sqlsheet = "INSERT INTO tbl_gradesheet
				(
					school_yr_period_id,
					label,
					percentage,
					schedule_id,
					date_created,
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($period_id,"text").",  
					".GetSQLValueString(getPeriodName($period_id),"text").",  
					".GetSQLValueString(100,"int").", 
					".GetSQLValueString($sched_id,"int").",   	
					".time().",
					".USER_ID.",		
					".time().",
					".USER_ID."
				)";
				
			$querysheet = @mysql_query ($sqlsheet);
			$sheet = @mysql_insert_id();	
	}

	foreach($student_id as $student)
	{	$ctr = 0;
		/*foreach($sheet_id as $sheet)
		{
			if(getStudentGradeInGradeSheet($sched_id,$sheet,$student) == '')
			{*/
				
				$sql = "DELETE FROM tbl_professor_gradesheet 
						WHERE 
							schedule_id = " . $sched_id . " AND 
							student_id = " . $student . " AND 
							sheet_id = " . $sheet;
				mysql_query ($sql);		
				
				$sql = "INSERT INTO tbl_professor_gradesheet
					(	
						student_id,
						schedule_id,
						professor_id,
						period_id,
						grade,
						sheet_id,
						date_created,
						created_by,
						date_altered,
						altered_by	 
																						 
					) 
					VALUES 
					(
						".GetSQLValueString($student,"text").",
						".GetSQLValueString($sched_id,"text").",
						".GetSQLValueString(USER_EMP_ID,"int").",
						".GetSQLValueString($period_id,"int").",
						".GetSQLValueString(encrypt($_REQUEST['grade_'.$student][$ctr]),"text").",
						".GetSQLValueString($sheet,"int").",
						".time().",
						".USER_ID.", 
						".time().",
						".USER_ID."						
					)";
					mysql_query ($sql);
				
			/*}
			else
			{
				$sql = "UPDATE tbl_professor_gradesheet 
						SET
							grade = ".GetSQLValueString(encrypt($_REQUEST['grade_'.$student][$ctr]),"text")."
						WHERE 
							schedule_id = " . $sched_id . " AND 
							student_id = " . $student . " AND 
							sheet_id = " . $sheet;
				mysql_query ($sql);					
			}*/
			$ctr++;
		//}

		/*if(!checkIfStudentGradeExistPerPeriod($sched_id,$student,$period_id))
		{*/
			if(computeStudentFinalGradePerPeriod($student,$sched_id,$period_id) > 0)
			{
				$sql = "DELETE FROM tbl_student_grade 
						WHERE 
							schedule_id = " . $sched_id . " AND 
							student_id = " . $student . " AND 
							period_id = " . $period_id;
				mysql_query ($sql);	
				
				$sql = "INSERT INTO tbl_student_grade
					(	
						student_id,
						term_id,
						subject_id,
						schedule_id,
						period_id,
						grade,
						is_locked,
						date_created,
						created_by													 
					) 
					VALUES 
					(
						".GetSQLValueString($student,"text").",
						".GetSQLValueString(getTermIdBySchedId($sched_id),"text").",
						".GetSQLValueString(getSchedSubjectId($sched_id),"int").",
						".GetSQLValueString($sched_id,"text").",
						".GetSQLValueString($period_id,"int").",
						".GetSQLValueString(encrypt(computeStudentFinalGradePerPeriod($student,$sched_id,$period_id)),"text").",
						".GetSQLValueString('N',"text").",
						".time().",
						".USER_ID."
					)";
				mysql_query ($sql) or die (mysql_error());
			}
			
		/*}
		else
		{
			$sql = "UPDATE tbl_student_grade 
					SET	
						grade = ".GetSQLValueString(encrypt(computeStudentFinalGradePerPeriod($student,$sched_id,$period_id)),"text")."
					WHERE 
						schedule_id = " . $sched_id . " AND 
						student_id = " . $student . " AND 
						period_id = " . $period_id;
			mysql_query ($sql);	
		}*/
		
		
	}	

	/*if(!checkIfGradeIsEncodedPerPeriod($sched_id,$period_id))
	{*/
		$sql = "DELETE FROM tbl_grade_submission 
						WHERE 
							schedule_id = " . $sched_id . " AND 
							professor_id = " . USER_EMP_ID . " AND 
							period_id = " . $period_id;
				mysql_query ($sql);	
		
		$sql = "INSERT INTO tbl_grade_submission
		(	
			professor_id,
			term_id,
			period_id,
			schedule_id,
			submission_is_locked,
			locked_date                                                               
		) 
		VALUES 
		(
			".GetSQLValueString(USER_EMP_ID,"int").",
			".GetSQLValueString(getTermIdBySchedId($sched_id),"text").",
			".GetSQLValueString($period_id,"text").",
			".GetSQLValueString($sched_id,"text").",
			".GetSQLValueString('N',"text").",
			".time()."				
		)";
		mysql_query ($sql);

		// [+] STORE SYSTEM LOGS
			$param = array(getSectionNo($sched_id),getSchoolTerm(getTermIdBySchedId($sched_id)),getPeriodName($period_id));
			storeSystemLogs(MSG_PROFESSOR_SUBMIT_GRADESHEET,$param,'','Y','Y');			
		// [-] STORE SYSTEM LOGS	
		
		//notification(getStudentUser($student),MSG_PROFESSOR_SUBMIT_GRADESHEET,$param,getProfessorUserID(getSchedProf($sched_id)));
		//notification(getParentUser($student),MSG_PROFESSOR_SUBMIT_GRADESHEET,$param,getProfessorUserID(getSchedProf($sched_id)));
		
		$admins = getUserAdmin();
		foreach($admins as $admin)
		{
			//notification($admin,MSG_PROFESSOR_SUBMIT_GRADESHEET,$param,getProfessorUserID(getSchedProf($sched_id)));					
		}
	/*}
	else
	{
		$sql = "UPDATE tbl_grade_submission 
				SET	
					submission_is_locked = ".GetSQLValueString('N',"text")."
				WHERE 
					professor_id = " . USER_EMP_ID . " AND 
					period_id = " . $period_id . " AND 
					schedule_id = " . $sched_id;
		
		mysql_query ($sql);	

		// [+] STORE SYSTEM LOGS
			$param = array(getSectionNo($sched_id),getSchoolTerm(getTermIdBySchedId($sched_id)),getPeriodName($period_id));
			storeSystemLogs(MSG_PROFESSOR_MODIFIED_GRADESHEET,$param,'','Y','Y');			
		// [-] STORE SYSTEM LOGS	
		
		//notification(getStudentUser($student),MSG_PROFESSOR_SUBMIT_GRADESHEET,$param,getProfessorUserID(getSchedProf($sched_id)));
		//notification(getParentUser($student),MSG_PROFESSOR_SUBMIT_GRADESHEET,$param,getProfessorUserID(getSchedProf($sched_id)));
		
		$admins = getUserAdmin();
		foreach($admins as $admin)
		{
			//notification($admin,MSG_PROFESSOR_MODIFIED_GRADESHEET,$param,getProfessorUserID(getSchedProf($sched_id)));					
		}
	}*/

	echo '<script language="javascript">alert("The grade has been save successfully!");</script>';	
	echo '<script language="javascript">window.location =\'index.php?comp=com_pr_encode_grade\';</script>';
}
else if($action == 'save_lock')
{
	$sqlgsheet = 'SELECT * FROM tbl_gradesheet WHERE school_yr_period_id = '.$period_id.' AND schedule_id = '.$sched_id;
	$queygsheet = mysql_query($sqlgsheet);
	
	if(mysql_num_rows($queygsheet)>0)
	{
		$rowgsheet = mysql_fetch_array($queygsheet);
		$sheet = $rowgsheet['id'];
	}
	else
	{
		//FOR GRADESHEET
				
				$sqlsheet = "INSERT INTO tbl_gradesheet
				(
					school_yr_period_id,
					label,
					percentage,
					schedule_id,
					date_created,
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($period_id,"text").",  
					".GetSQLValueString(getPeriodName($period_id),"text").",  
					".GetSQLValueString(100,"int").", 
					".GetSQLValueString($sched_id,"int").",   	
					".time().",
					".USER_ID.",		
					".time().",
					".USER_ID."
				)";
				
			$querysheet = mysql_query ($sqlsheet);
			$sheet = mysql_insert_id();	
	}

	foreach($student_id as $student)
	{	
		$ctr = 0;
		/*foreach($sheet_id as $sheet)
		{
			if(getStudentGradeInGradeSheet($sched_id,$sheet,$student) == '')
			{*/
			
			$sql = "DELETE FROM tbl_professor_gradesheet 
						WHERE 
							schedule_id = " . $sched_id . " AND 
							professor_id = " . USER_EMP_ID . " AND 
							sheet_id = " . $sheet;
				mysql_query ($sql);	
				
				$sql = "INSERT INTO tbl_professor_gradesheet
					(	
						student_id,
						schedule_id,
						professor_id,
						period_id,
						grade,
						sheet_id,
						date_created,
						created_by,
						date_altered,
						altered_by	 
																						 
					) 
					VALUES 
					(
						".GetSQLValueString($student,"text").",
						".GetSQLValueString($sched_id,"text").",
						".GetSQLValueString(USER_EMP_ID,"int").",
						".GetSQLValueString($period_id,"int").",
						".GetSQLValueString(encrypt($_REQUEST['grade_'.$student][$ctr]),"text").",
						".GetSQLValueString($sheet,"int").",
						".time().",
						".USER_ID.", 
						".time().",
						".USER_ID."						
					)";
					mysql_query ($sql);
				
			/*}
			else
			{
				$sql = "UPDATE tbl_professor_gradesheet 
						SET	
							grade = ".GetSQLValueString(encrypt($_REQUEST['grade_'.$student][$ctr]),"text")."
						WHERE 
							schedule_id = " . $sched_id . " AND 
							student_id = " . $student . " AND 
							sheet_id = " . $sheet;
				mysql_query ($sql);					
			}*/
			$ctr++;
		//} // 2nd foreach
		
		/*if(!checkIfStudentGradeExistPerPeriod($sched_id,$student,$period_id))
		{*/
		
		$sql = "DELETE FROM tbl_student_grade 
						WHERE 
							schedule_id = " . $sched_id . " AND 
							student_id = " . $student . " AND 
							period_id = " . $period_id;
				mysql_query ($sql);	
		
			$sql = "INSERT INTO tbl_student_grade
				(	
					student_id,
					term_id,
					subject_id,
					schedule_id,
					period_id,
					grade,
					is_locked,
					date_created,
					created_by													 
				) 
				VALUES 
				(
					".GetSQLValueString($student,"text").",
					".GetSQLValueString(getTermIdBySchedId($sched_id),"text").",
					".GetSQLValueString(getSchedSubjectId($sched_id),"int").",
					".GetSQLValueString($sched_id,"text").",
					".GetSQLValueString($period_id,"int").",
					".GetSQLValueString(encrypt(computeStudentFinalGradePerPeriod($student,$sched_id,$period_id)),"text").",
					".GetSQLValueString('N',"text").",
					".time().",
					".USER_ID."
				)";
			mysql_query ($sql);
		/*}
		else
		{
			$sql = "UPDATE tbl_student_grade 
					SET	
						grade = ".GetSQLValueString(encrypt(computeStudentFinalGradePerPeriod($student,$sched_id,$period_id)),"text")."
					WHERE 
						schedule_id = " . $sched_id . " AND 
						student_id = " . $student . " AND 
						period_id = " . $period_id;
			mysql_query ($sql);	
		}	*/			
	
		if(!checkIfGradeIsEncodedPerPeriod($sched_id,$period_id))
		{
			$sql = "INSERT INTO tbl_grade_submission
			(	
				professor_id,
				term_id,
				period_id,
				schedule_id,
				submission_is_locked,
				locked_date                                                               
			) 
			VALUES 
			(
				".GetSQLValueString(USER_EMP_ID,"int").",
				".GetSQLValueString(getTermIdBySchedId($sched_id),"text").",
				".GetSQLValueString($period_id,"text").",
				".GetSQLValueString($sched_id,"text").",
				".GetSQLValueString('Y',"text").",
				".time()."				
			)";
			mysql_query ($sql);
		}
		else
		{
			$sql = "UPDATE tbl_grade_submission 
					SET	
						submission_is_locked = ".GetSQLValueString('Y',"text")."
					WHERE 
						professor_id = " . USER_EMP_ID . " AND 
						period_id = " . $period_id . " AND 
						schedule_id = " . $sched_id;
			
			mysql_query ($sql);			
		}
							
	} // 1st foreach

	// [+] STORE SYSTEM LOGS
		$param = array(getSectionNo($sched_id),getSchoolTerm(getTermIdBySchedId($sched_id)),getPeriodName($period_id));
		storeSystemLogs(MSG_PROFESSOR_LOCKED_GRADESHEET,$param,'Y','Y');			
	// [-] STORE SYSTEM LOGS	
		
		//notification(getStudentUser($student),MSG_PROFESSOR_LOCKED_GRADESHEET,$param,getProfessorUserID(getSchedProf($sched_id)));
		//notification(getParentUser($student),MSG_PROFESSOR_LOCKED_GRADESHEET,$param,getProfessorUserID(getSchedProf($sched_id)));
		
		$admins = getUserAdmin();
		foreach($admins as $admin)
		{
			notification($admin,MSG_PROFESSOR_LOCKED_GRADESHEET,$param,getProfessorUserID(getSchedProf($sched_id)));					
		}
		
	foreach($student_id as $student)
	{
		if(checkIfGradeIsPass(getStudentFinalGrade($student,$sched_id,CURRENT_TERM_ID)) == 'Y')
		{
			$remarks = 'P';
		}
		else
		{
			$remarks = 'F';
		}
		
		if(checkIfAllPeriodExistPerStud($sched_id,$student)  && !checkIfStudentFinalGradeExistPerTerm(getSchedSubjectId($sched_id),getTermIdBySchedId($sched_id),$student))
		{
			
			/*$sql = "DELETE FROM tbl_student_final_grade 
						WHERE 
							schedule_id = " . $sched_id . " AND 
							student_id = " . $student ;
				mysql_query ($sql);	*/
			
			$sql = "INSERT INTO tbl_student_final_grade
			(	
				student_id,
				subject_id,
				term_id,
				final_grade,
				type,
				remarks,
				grade_conversion_id,
				date_created,
				created_by
                                                           
			) 
			VALUES 
			(
				".GetSQLValueString($student,"int").",
				".GetSQLValueString(getSchedSubjectId($sched_id),"int").",
				".GetSQLValueString(getTermIdBySchedId($sched_id),"int").",
				".GetSQLValueString(encrypt(getStudentFinalGrade($student,$sched_id,CURRENT_TERM_ID)),"text").",
				".GetSQLValueString('S',"text").",
				".GetSQLValueString($remarks,"text").",
				".GetSQLValueString(getGradeConversionId(getStudentFinalGrade($student,$sched_id,CURRENT_TERM_ID)),"text").",
				".time().",
				".USER_ID."		
			)";
			mysql_query ($sql) or die(mysql_error());
		}
		else if(checkIfAllPeriodExistPerStud($sched_id,$student))
		{
			$sql = "UPDATE tbl_student_final_grade SET
						final_grade =".GetSQLValueString(encrypt(getStudentFinalGrade($student,$sched_id,CURRENT_TERM_ID)),"text").",
						type  =".GetSQLValueString('S',"text").",				
						remarks = ".GetSQLValueString($remarks,"text").",
						grade_conversion_id = ".GetSQLValueString(getGradeConversionId(getStudentFinalGrade($student,$sched_id,CURRENT_TERM_ID)),"text")."
					WHERE 
						subject_id= ".getSchedSubjectId($sched_id)." AND 
						student_id =" .$student . " AND 
						term_id = " . getTermIdBySchedId($sched_id);
								
			mysql_query ($sql) or die(mysql_error());		
		}
	}
	/*echo '<script language="javascript">alert("The grade has been successfully locked!");</script>';				
	echo '<script language="javascript">window.location =\'index.php?comp=com_pr_encode_grade\';</script>';	*/	
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_pr_encode_grade.php';
?>
