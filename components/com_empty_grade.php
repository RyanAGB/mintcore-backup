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


$page_title = 'Manage Empty Grade';
$pagination = 'Professor  > Manage Empty Grade';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];
$comp 	= $_REQUEST['comp'];

$ctr = $_REQUEST['ctr'];
$sched = $_REQUEST['sched'];

$filter_field = $_REQUEST['filter_field'];
$filter_order = $_REQUEST['filter_order'];
$page = $_REQUEST['page'];

	if($_SESSION[CORE_U_CODE]['current_comp'] != $comp || $_SESSION[CORE_U_CODE]['pageNum'] != $page || $_SESSION[CORE_U_CODE]['fieldName'] != $filter_field || $_SESSION[CORE_U_CODE]['orderBy'] != $filter_order)
	{
		if($page != '')
		{
			$_SESSION[CORE_U_CODE]['pageNum'] = isset($page)&&$page!='' ? $page : '1';
		}
		if($filter_field != '' || $filter_order != '')
		{
			$_SESSION[CORE_U_CODE]['fieldName'] = $filter_field;
			$_SESSION[CORE_U_CODE]['orderBy'] = $filter_order;
		}
		$_SESSION[CORE_U_CODE]['current_comp'] = $comp;
	}

if($action == 'save')
{
   if($ctr == 0)
   {
   		$err_msg = 'No Empty Grades need to be filled.';
   }
   else
   {
	$sql_sched = "SELECT schedule.* 
					FROM tbl_schedule schedule, 
						tbl_subject subject,
						tbl_room room,
						tbl_employee employee
					WHERE schedule.subject_id = subject.id AND
						schedule.room_id = room.id AND
						schedule.employee_id = employee.id
					AND term_id =".CURRENT_TERM_ID;
	
	$result_sched = mysql_query($sql_sched);
	while($row_sched = mysql_fetch_array($result_sched))
	{
		$subj = getSubjIdBySchedule($row_sched['id']);
		
		$sql_grade = "SELECT student_id,schedule_id FROM tbl_student_schedule 
							WHERE term_id = ".CURRENT_TERM_ID." AND schedule_id = ".$row_sched['id'];						
        $query_grade = mysql_query($sql_grade);
		
		while($row_grade = mysql_fetch_array($query_grade))
		{
			$sql_period = "SELECT * FROM tbl_school_year_period WHERE 
						term_id=".CURRENT_TERM_ID." AND 
						start_of_submission < '" .  date("Y-m-d") . "'
						ORDER BY period_order";						
			$query_period = mysql_query($sql_period);
			while($row_period = mysql_fetch_array($query_period))
			{
				$sql_emp = "SELECT * FROM tbl_student_grade 
						WHERE student_id = ".$row_grade["student_id"]." AND
						schedule_id = ".$row_grade["schedule_id"]." AND 
						period_id = ".$row_period["id"]." AND term_id = ".CURRENT_TERM_ID;
				$query_emp = mysql_query($sql_emp);
				$row_emp = mysql_fetch_array($query_emp);

				if(mysql_num_rows($query_emp) == 0)
				{
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
						".$row_grade['student_id'].",
						".$subj.",
						".CURRENT_TERM_ID.",
						".$row_grade['schedule_id'].",
						".$row_period['id'].",
						".GetSQLValueString(encrypt('*'),"text").",
						".GetSQLValueString('Y',"text").",	
						".GetSQLValueString('N',"text").",	
						".time().",
						".USER_ID.", 
						".time().",
						".USER_ID."						
					)";
					
				mysql_query($sql);
				
				}
			}
		}
	}	
  }
		echo '<script language="javascript">alert("Successfully Applied * to all empty Grades.");window.location =\'index.php?comp=com_empty_grade\';</script>';
}


// component block, will be included in the template page
$content_template = 'components/block/blk_com_empty_grade.php';
?>