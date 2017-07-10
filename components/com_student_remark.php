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

if($action == 'update')
{
	$sql = "INSERT INTO tbl_student_remarks 
				(
					student_id, 
					term_id,
					school_year_id,
					professor_id,
					description,
					date_created,
					created_by
				) 
				VALUES 
				(
					".GetSQLValueString($id,"text").",
					".CURRENT_TERM_ID.",
					".CURRENT_SY_ID.",
					".ADMIN_EMP_ID.", 
					".GetSQLValueString($description,"text").",		
					".time().",
					".USER_ID."
				)";

		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_student_remark\';</script>';
		}	
}
else if($view == 'add')
{
	
	$description	=	$_REQUEST['description'];
	$subject_id		=	$_REQUEST['subject_id'];
	$id	=	$_REQUEST['id'];

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_student_remark.php';
?>