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



$page_title = 'Professor Encode Grade';
$pagination = 'Professor > Professor Encode Grade';

$view = $view==''?'list':$view; // initialize action

$id			= $_REQUEST['id'];
$temp 		= $_REQUEST['temp'];
$comp 		= $_REQUEST['comp'];
$prof_id	= $_REQUEST['prof_id'];
$sched_id	= $_REQUEST['sched_id'];
$rows 		= $_REQUEST['rows'];

$student_id	= $_REQUEST['student_id'];
$subject_id	= $_REQUEST['subject_id'];
$term_id	= $_REQUEST['term_id'];
$sched_id	= $_REQUEST['sched_id'];
$period_id	= $_REQUEST['period_id'];
$grade		= $_REQUEST['grade'];

$schoolterm_id = $_REQUEST['schoolterm_id'];
$filter_field = $_REQUEST['filter_field'];
$filter_order = $_REQUEST['filter_order'];
$rows = $_REQUEST['rows'];
$page = $_REQUEST['page'];

	if($_SESSION[CORE_U_CODE]['current_comp'] != $comp || $_SESSION[CORE_U_CODE]['pageNum'] != $page || $_SESSION[CORE_U_CODE]['pageRows'] != $rows || $_SESSION[CORE_U_CODE]['fieldName'] != $filter_field || $_SESSION[CORE_U_CODE]['orderBy'] != $filter_order || $_SESSION[CORE_U_CODE]['sy_filter'] != $schoolterm_id)
	{
		if($page != '')
		{
			$_SESSION[CORE_U_CODE]['pageNum'] = isset($page)&&$page!='' ? $page : '1';
		}
		if($rows != '')
		{
			$_SESSION[CORE_U_CODE]['pageRows'] = isset($rows)&&$rows!='' ? $rows : '10';
		}
		if($filter_field != '' || $filter_order != '')
		{
			$_SESSION[CORE_U_CODE]['fieldName'] = $filter_field;
			$_SESSION[CORE_U_CODE]['orderBy'] = $filter_order;
		}
		if($schoolterm_id != '')
		{
			$_SESSION[CORE_U_CODE]['sy_filter'] = $schoolterm_id;
		}
		$_SESSION[CORE_U_CODE]['current_comp'] = $comp;
	}

if($action == 'save')
{
	
	foreach($student_id as $student)
	{	
		$ctr = 0;
		foreach($period_id as $period)
		{
			if(getStudentGradePerPeriod($sched_id,$period,$student) == '')
			{
				$sqld = "DELETE FROM tbl_student_grade 						
						WHERE schedule_id= ".$sched_id." AND student_id =" .$student . " AND period_id = " . $period;
				$queryd = mysql_query($sqld);
				
				$sql = "INSERT INTO tbl_student_grade
				(	
					student_id,
					subject_id,
					term_id,
					schedule_id,
					period_id,
					altered_grade,
					is_altered,
					is_locked,
					date_created, 
					created_by,
					date_altered,
					altered_by	                                                                  
				) 
				VALUES 
				(
					".GetSQLValueString($student,"text").",
					".GetSQLValueString($subject_id,"text").",
					".GetSQLValueString($schoolterm_id,"text").",
					".GetSQLValueString($sched_id,"text").",
					".GetSQLValueString($period,"text").",
					".GetSQLValueString(encrypt($_REQUEST['grade_'.$student][$ctr]),"text").",
					".GetSQLValueString('Y',"text").",	
					".GetSQLValueString('Y',"text").",	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."						
				)";
			
				if(mysql_query ($sql))
				{
				// [+] STORE SYSTEM LOGS
					$param = array(	
									getProfessorFullName((getSchedProf($sched_id))),
									getStudentNumber($student),
									getSectionNo($sched_id),
									getSchoolTerm(getTermIdBySchedId($sched_id)),
									getPeriodName($period)
								   );
					storeSystemLogs(MSG_ADMIN_SUBMIT_STUDENT_GRADE,$param,getProfessorUserID(getSchedProf($sched_id)),'N','Y','Y');	
				// [-] STORE SYSTEM LOGS	
					
					/*notification(getProfessorUserID(getSchedProf($sched_id)),MSG_ADMIN_SUBMIT_STUDENT_GRADE,$param,USER_ID);	
					notification(getStudentUser($student),MSG_ADMIN_SUBMIT_STUDENT_GRADE,$param,USER_ID);
					notification(getParentUser($student),MSG_ADMIN_SUBMIT_STUDENT_GRADE,$param,USER_ID);	*/
				}
				
				}
			else
			{
				$sql = "UPDATE tbl_student_grade SET
						altered_grade =".GetSQLValueString(encrypt($_REQUEST['grade_'.$student][$ctr]),"text").",
						is_altered  =".GetSQLValueString('Y',"text").",				
						date_altered = ".time() .",
						altered_by = ".USER_ID." 						
						WHERE schedule_id= ".$sched_id." AND student_id =" .$student . " AND period_id = " . $period;
				
				if(mysql_query ($sql))
				{
				// [+] STORE SYSTEM LOGS
					$param = array(	
									getProfessorFullName((getSchedProf($sched_id))),
									getStudentNumber($student),
									getSectionNo($sched_id),
									getSchoolTerm(getTermIdBySchedId($sched_id)),
									getPeriodName($period)
								   );
					storeSystemLogs(MSG_ADMIN_MODIFIED_STUDENT_GRADE,$param,getProfessorUserID(getSchedProf($sched_id)),'N','Y','Y');	
				// [-] STORE SYSTEM LOGS		
				
					/*notification(getProfessorUserID(getSchedProf($sched_id)),MSG_ADMIN_MODIFIED_STUDENT_GRADE,$param,USER_ID);	
					notification(getStudentUser($student),MSG_ADMIN_MODIFIED_STUDENT_GRADE,$param,USER_ID);			
					notification(getParentUser($student),MSG_ADMIN_MODIFIED_STUDENT_GRADE,$param,USER_ID);	*/
				}
			}
				
			
			$ctr++;
			
		if(!checkIfGradeIsEncodedPerPeriod($sched_id,$period))
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
				".GetSQLValueString($prof_id,"text").",
				".GetSQLValueString($schoolterm_id,"text").",
				".GetSQLValueString($period,"text").",
				".GetSQLValueString($sched_id,"text").",
				".GetSQLValueString('N',"text").",
				".time()."				
			)";

			if(mysql_query ($sql))
			{
				
			}
		}
		}
		
		if(checkIfGradeIsPass(getStudentFinalGrade($student,$sched_id,$schoolterm_id)) == 'Y')
		{
			$remarks = 'P';
		}
		else
		{
			$remarks = 'F';
		}
		
		
		if(checkIfAllPeriodExistPerStud($sched_id,$student)  && !checkIfStudentFinalGradeExistPerTerm(getSchedSubjectId($sched_id),getTermIdBySchedId($sched_id),$student))
		{
				
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
				".GetSQLValueString(encrypt(getStudentFinalGrade($student,$sched_id,$schoolterm_id)),"text").",
				".GetSQLValueString('S',"text").",
				".GetSQLValueString($remarks,"text").",
				".GetSQLValueString(getGradeConversionId(getStudentFinalGrade($student,$sched_id,$schoolterm_id),$schoolterm_id),"text").",
				".time().",
				".USER_ID."		
			)";
			mysql_query ($sql) ;//or die(mysql_error());
		}
		else if(checkIfAllPeriodExistPerStud($sched_id,$student))
		{

			$sql = "UPDATE tbl_student_final_grade SET
						final_grade =".GetSQLValueString(encrypt(getStudentFinalGrade($student,$sched_id,$schoolterm_id)),"text").",
						type  =".GetSQLValueString('S',"text").",				
						remarks = ".GetSQLValueString($remarks,"text").",
						grade_conversion_id = ".GetSQLValueString(getGradeConversionId(getStudentFinalGrade($student,$sched_id,$schoolterm_id),$schoolterm_id),"text")."
					WHERE 
						subject_id= ".getSchedSubjectId($sched_id)." AND 
						student_id =" .$student . " AND 
						term_id = " . getTermIdBySchedId($sched_id);
								
			mysql_query ($sql); //or die(mysql_error());		
		}
		
	}	
	
	echo '<script language="javascript">alert("The grade has been save successfully!");</script>';
	echo '<script language="javascript">window.location =\'index.php?comp=com_professor_encode_grade&prof_id='.$prof_id.'&filter_schoolterm='.$schoolterm_id.'\';</script>';
}


// component block, will be included in the template page
$content_template = 'components/block/blk_com_professor_encode_grade.php';
?>