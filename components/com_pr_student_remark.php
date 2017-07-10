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



$page_title = 'Student Remark';
$pagination = 'Student > Student Remarks';

$view = $view==''?'list':$view; // initialize action

$id	=	$_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$subject_id	=	$_REQUEST['subject_id'];
$description	=	$_REQUEST['description'];



if($action == 'update')
{
	$sql = "INSERT INTO tbl_student_remarks 
				(
					student_id, 
					term_id,
					school_year_id,
					professor_id,
					subject_id,
					description,
					date_created,
					created_by
				) 
				VALUES 
				(
					".GetSQLValueString($id,"text").",
					".CURRENT_TERM_ID.",
					".CURRENT_SY_ID.",
					".USER_EMP_ID.",
					".GetSQLValueString($subject_id,"text").", 
					".GetSQLValueString($description,"text").",		
					".time().",
					".USER_ID."
				)";
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_pr_student_remark\';</script>';
		}	
}
else if($view == 'add')
{
	
	$description	=	$_REQUEST['description'];
	$subject_id		=	$_REQUEST['subject_id'];

}


// component block, will be included in the template page
$content_template = 'components/block/blk_com_pr_student_remark.php';
?>