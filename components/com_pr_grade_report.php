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
$sched_id	= $_REQUEST['sched_id'];
$sheet_id	= $_REQUEST['sheet_id'];
$grade	= $_REQUEST['grade'];

if($action == 'save')
{
	foreach($student_id as $student)
	{	$ctr = 0;
		foreach($sheet_id as $sheet)
		{
			if(getStudentGradeInGradeSheet($sched_id,$sheet,$student) == '')
			{
				$sql = "INSERT INTO tbl_professor_gradesheet
					(	
						student_id,
						schedule_id,
						professor_id,
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
						".GetSQLValueString(encrypt($_REQUEST['grade_'.$student][$ctr]),"text").",
						".GetSQLValueString($sheet,"int").",
						".time().",
						".USER_ID.", 
						".time().",
						".USER_ID."						
					)";
				
					if(mysql_query ($sql))
					{
						
					}
				
			}
			else
			{
				$sql = "UPDATE tbl_professor_gradesheet 
						SET
							grade = ".GetSQLValueString(encrypt($_REQUEST['grade_'.$student][$ctr]),"text")."
						WHERE 
							schedule_id = " . $sched_id . " AND 
							student_id = " . $student . " AND 
							sheet_id = " . $sheet;
				
				if(mysql_query ($sql))
				{
					
				}					
			}
			$ctr++;
		}
		
	}	

	echo '<script language="javascript">window.location =\'index.php?comp=com_pr_encode_grade\';</script>';
}
else if($action == 'save_lock')
{
	
	foreach($student_id as $student)
	{	$ctr = 0;
		foreach($sheet_id as $sheet)
		{
			if(getStudentGradeInGradeSheet($sched_id,$sheet,$student) == '')
			{
				$sql = "INSERT INTO tbl_professor_gradesheet
					(	
						student_id,
						schedule_id,
						professor_id,
						grade,
						sheet_id,
						is_locked,
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
						".GetSQLValueString(encrypt($_REQUEST['grade_'.$student][$ctr]),"text").",
						".GetSQLValueString($sheet,"int").",
						".GetSQLValueString('Y',"int").",
						".time().",
						".USER_ID.", 
						".time().",
						".USER_ID."						
					)";
				
					if(mysql_query ($sql))
					{
						
					}
				
			}
			else
			{
				$sql = "UPDATE tbl_professor_gradesheet 
						SET	
							is_locked = ".GetSQLValueString('Y',"text").",
							grade = ".GetSQLValueString(encrypt($_REQUEST['grade_'.$student][$ctr]),"text")."
						WHERE 
							schedule_id = " . $sched_id . " AND 
							student_id = " . $student . " AND 
							sheet_id = " . $sheet;
				
				if(mysql_query ($sql))
				{
					
				}					
			}
			$ctr++;
		}
		
	}		
	
	$sql = "INSERT INTO tbl_professor_gradesheet
		(	
			student_id,
			term_id,
			subject_id,
			schedule_id,
			period_id,
			grade,
			date_created,
			created_by,
																			 
		) 
		VALUES 
		(
			".GetSQLValueString($student,"text").",
			".GetSQLValueString($sched_id,"text").",
			".GetSQLValueString(USER_EMP_ID,"int").",
			".GetSQLValueString(encrypt($_REQUEST['grade_'.$student][$ctr]),"text").",
			".GetSQLValueString($sheet,"int").",
			".GetSQLValueString('Y',"int").",
			".time().",
			".USER_ID.", 
			
		)";
	
		if(mysql_query ($sql))
		{
			
		}
					
	echo '<script language="javascript">window.location =\'index.php?comp=com_pr_encode_grade\';</script>';		
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_pr_grade_report.php';
?>
