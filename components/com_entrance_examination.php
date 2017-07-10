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


$page_title = 'Manage Examination Date';
$pagination = 'Date Enrollment > Manage Examination Date';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$syear             		= $_REQUEST['s_year'];
$smonth            		= $_REQUEST['s_month'];
$sday             		= $_REQUEST['s_day'];
$sbdate 				= array($syear, $smonth, $sday);
$date 					= implode("-", $sbdate);

$school_year_id			= $_REQUEST['school_year_id'];
$term_id				= $_REQUEST['term_id'];
$course_id				= $_REQUEST['course_id'];
$course_name_display	= $_REQUEST['course_name_display'];

$sy_filter = $_REQUEST['sy_filter'];
$filter_field 	= $_REQUEST['filter_field'];
$filter_order 	= $_REQUEST['filter_order'];
$page = $_REQUEST['page'];

	if($_SESSION[CORE_U_CODE]['current_comp'] != $comp || $_SESSION[CORE_U_CODE]['pageNum'] != $page || $_SESSION[CORE_U_CODE]['fieldName'] != $filter_field || $_SESSION[CORE_U_CODE]['orderBy'] != $filter_order || $_SESSION[CORE_U_CODE]['sy_filter'] != $sy_filter)
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
		if($sy_filter != '')
		{
			$_SESSION[CORE_U_CODE]['sy_filter'] = $sy_filter;
		}
			$_SESSION[CORE_U_CODE]['current_comp'] = $comp;
	}

if($action == 'save')
{
	if($school_year_id == '' || $term_id == '' || count($course_id) == 0)
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{
		foreach($course_id as $course)
		{
			if (checkDateOfExaminationExist($school_year_id, $date, $term_id, $course))
			{
				$err_msg = 'Record already exist.';
			}
			else if (checkDateIsValid($date, $term_id))
			{
				$err_msg = 'Invalid Date. Please enter date ';
			}
			else
			{
				$sql = "INSERT INTO tbl_exam_date
						(
							entrance_date, 
							school_year_id, 
							term_id, 
							course_id,
							date_created, 
							created_by,
							date_modified,
							modified_by
						) 
						VALUES 
						(
							".GetSQLValueString($date,"date").", 
							".GetSQLValueString($school_year_id,"int").",  
							".GetSQLValueString($term_id,"int").",  
							".GetSQLValueString($course,"int").",  	
							".time().",
							".USER_ID.", 
							".time().",
							".USER_ID."
						)";
				mysql_query ($sql);
			}
		}
		
		echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_entrance_examination\';</script>';		
		
	}
}
else if($action == 'update')
{
	if($school_year_id == '' || $term_id == '' || $course_id == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	foreach($course_id as $course)
	{
		if (checkDateOfExaminationExist($school_year_id, $date, $term_id, $course, $id))
		{
			$err_msg = 'Record already exist.';
		}
		else
		{
		if(storedModifiedLogs(tbl_exam_date, $id))
		{
			$sql = "UPDATE tbl_exam_date SET 
					entrance_date =".GetSQLValueString($date,"text").",
					school_year_id =".GetSQLValueString($school_year_id,"text").",
					term_id =".GetSQLValueString($term_id,"text").",
					course_id =".GetSQLValueString($course,"int").",				 
					date_modified = ".time() .",
					modified_by = ".USER_ID." 
					WHERE id=" .$id;
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_entrance_examination\';</script>';
			}
		}
	}
   }
}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		$sql = "DELETE FROM tbl_exam_date WHERE id=" .$item;
		mysql_query ($sql);
	}

	if(count($arr_str) > 0)
	{
		echo '<script language="javascript">alert("'.implode("\n",$arr_str).'");</script>';
	}

}
else if($action == 'publish')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		storedModifiedLogs(tbl_enrollmentdate, $id);
		$sql = "UPDATE tbl_exam_date SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		storedModifiedLogs(tbl_enrollmentdate, $id);
		$sql = "UPDATE tbl_exam_date SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_exam_date where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	
	$date 	= explode("-", $row['entrance_date']);
	
	$s_year			= $date[0] != $s_year ? $date[0] : $s_year;
	$s_month		= $date[1] != $s_month ? $date[1] : $s_month;
	$s_day			= $date[2] != $s_day ? $date[2] : $s_day;

	$school_year_id		= $row['school_year_id'] != $school_year_id ? $row['school_year_id'] : $school_year_id;
	$term_id		= $row['term_id'] != $term_id ? $row['term_id'] : $term_id;	
	$course_id		= $row['course_id'] != $course_id ? $row['course_id'] : $course_id;
	$course_name_display= getCourseName($row['course_id']) != $course_name_display ? getCourseName($row['course_id']) : $course_name_display;
	
}
else if($view == 'add')
{
	
	$date				= $_REQUEST['date'];
	$course_id				= $_REQUEST['course_id'];
	$course_name_display	= $_REQUEST['course_name_display'];
	$term_id				= $_REQUEST['term_id'];
	$school_year_id			= $_REQUEST['school_year_id'];

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_entrance_examination.php';
?>