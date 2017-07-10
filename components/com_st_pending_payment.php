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


$page_title = 'Pending Payments';
$pagination = 'Billing > Pending Payments';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$student_id = $_REQUEST['student_id'];

$term_id					= $_REQUEST['term'];
$units						= $_REQUEST['units'];
$subject_id					= $_REQUEST['subject_id'];
$schedule_id_dispalay		= $_REQUEST['schedule_id_dispalay'];


if($action == 'save')
{
	
	if($schedule_id == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{
		$ctr = 0 ;
		foreach($schedule_id as $schedID){
			if($schedID != '')
			{
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
						".GetSQLValueString($schedID,"int").",  
						".GetSQLValueString($student_id,"int").",  
						".GetSQLValueString($term_id,"int").", 
						".GetSQLValueString($units[$ctr],"int").", 
						".GetSQLValueString($subject_id[$ctr],"int").", 
						".time().",
						".USER_ID.",		
						".time()."
					)";

				if(mysql_query ($sql))
				{

					storeStudentFee($student_id,getSchedSubjectId($schedID),$units[$ctr]);

				}
			}
			$ctr ++;
		}

		$sql = "INSERT INTO tbl_student_enrollment_status 
			(
				student_id, 
				term_id,
				enrollment_status
			) 
			VALUES 
			(
				".GetSQLValueString($student_id,"int").",  
				".GetSQLValueString($term_id,"int").",  
				".GetSQLValueString('R',"text")."
			)";	
		if(mysql_query($sql))
		{						
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_st_pending_payment\';</script>';
		}			
		
	}	
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_st_pending_payment.php';
?>