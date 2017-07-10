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


$page_title = 'Manage Enrollment Date';
$pagination = 'Utility  > Manage Enrollment Date';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$syear             		= $_REQUEST['s_year'];
$smonth            		= $_REQUEST['s_month'];
$sday             		= $_REQUEST['s_day'];
$sbdate 				= array($syear, $smonth, $sday);
$start_date 			= implode("-", $sbdate);


$eyear             		= $_REQUEST['e_year'];
$emonth            		= $_REQUEST['e_month'];
$eday             		= $_REQUEST['e_day'];
$ebdate 				= array($eyear, $emonth, $eday);
$end_date	 			= implode("-", $ebdate);

$dyear             		= $_REQUEST['d_year'];
$dmonth            		= $_REQUEST['d_month'];
$dday             		= $_REQUEST['d_day'];
$dbdate 				= array($dyear, $dmonth, $dday);
$drop_add_date	 		= implode("-", $dbdate);

$school_year_id			= $_REQUEST['school_year_id'];
$term_id				= $_REQUEST['term_id'];
$reserve_days			= $_REQUEST['reserve_days'];
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
	if($reserve_days == '' || $school_year_id == '' || $term_id == '' || count($course_id) == 0)
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (validateDate(valDateStartAddZero($start_date), valDateEndAddZero($end_date)))
	{
		$err_msg = 'Start date should not be less than or equal to End date.';
	}
	else
	{
		foreach($course_id as $course)
		{
			if (checkDateOfEnrollmentExist($start_date, $end_date, $school_year_id, $term_id, $course))
			{
				$err_msg = 'Record already exist.';
			}
			else
			{
				$sql = "INSERT INTO tbl_enrollment_date
						(
							start_date, 
							end_date,
							school_year_id, 
							term_id, 
							reserve_days,
							drop_add_date,
							course_id,
							date_created, 
							created_by,
							date_modified,
							modified_by
						) 
						VALUES 
						(
							".GetSQLValueString($start_date,"date").",  
							".GetSQLValueString($end_date,"date").",
							".GetSQLValueString($school_year_id,"int").",  
							".GetSQLValueString($term_id,"int").",  
							".GetSQLValueString($reserve_days,"int").",
							".GetSQLValueString($drop_add_date,"text").",  	
							".GetSQLValueString($course,"int").",  	
							".time().",
							".USER_ID.", 
							".time().",
							".USER_ID."
						)";
				mysql_query ($sql);
			}
		}
		echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_date_enrollment\';</script>';		
	}
}
else if($action == 'update')
{
	if($reserve_days == '' || $school_year_id == '' || $term_id == '' || $course_id == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (validateDate(valDateStartAddZero($start_date), valDateEndAddZero($end_date), $id))
	{
		$err_msg = 'Start date should not be less than or equal to End date.';
	}
	else
	{
		foreach($course_id as $course)
		{
				if (checkDateOfEnrollmentExist($start_date, $end_date, $school_year_id, $term_id, $course, $id))
				{
					$err_msg = getCourseName($course).'Record already exist.';
				}
				else
				{
			if(storedModifiedLogs(tbl_enrollment_date, $id))
			{
				$sql = "UPDATE tbl_enrollment_date SET 
						start_date =".GetSQLValueString($start_date,"text").",
						end_date =".GetSQLValueString($end_date,"text").",
						school_year_id =".GetSQLValueString($school_year_id,"text").",
						term_id =".GetSQLValueString($term_id,"text").",
						reserve_days =".GetSQLValueString($reserve_days,"text").",
						course_id =".GetSQLValueString($course,"int").",		
						drop_add_date =".GetSQLValueString($drop_add_date,"text").",		 
						date_modified = ".time() .",
						modified_by = ".USER_ID." 
						WHERE id=" .$id;
					
				if(mysql_query ($sql))
				{
					echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_date_enrollment\';</script>';
				}
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
		$sql = "DELETE FROM tbl_enrollment_date WHERE id=" .$item;
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
		$sql = "UPDATE tbl_enrollment_date SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		storedModifiedLogs(tbl_enrollmentdate, $id);
		$sql = "UPDATE tbl_enrollment_date SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_enrollment_date where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	
	$start_date 	= explode("-", $row['start_date']);
	$end_date	 	= explode("-", $row['end_date']);
	$drop_add_date 	= explode("-", $row['drop_add_date']);

	$s_year			= $start_date[0] != $s_year ? $start_date[0] : $s_year;
	$s_month		= $start_date[1] != $s_month ? $start_date[1] : $s_month;
	$s_day			= $start_date[2] != $s_day ? $start_date[2] : $s_day;
	$e_year			= $end_date[0] != $e_year ? $end_date[0] : $e_year;
	$e_month		= $end_date[1] != $e_month ? $end_date[1] : $e_month;
	$e_day			= $end_date[2] != $e_day ? $end_date[2] : $e_day;
	
	$d_year			= $drop_add_date[0] != $d_year ? $drop_add_date[0] : $d_year;
	$d_month		= $drop_add_date[1] != $d_month ? $drop_add_date[1] : $d_month;
	$d_day			= $drop_add_date[2] != $d_day ? $drop_add_date[2] : $d_day;

	$school_year_id		= $row['school_year_id'] != $school_year_id ? $row['school_year_id'] : $school_year_id;
	$term_id		= $row['term_id'] != $term_id ? $row['term_id'] : $term_id;	
	$reserve_days	= $row['reserve_days'] != $reserve_days ? $row['reserve_days'] : $reserve_days;
	$course_id		= $row['course_id'] != $course_id ? $row['course_id'] : $course_id;
	$course_name_display= getCourseName($row['course_id']) != $course_name_display ? getCourseName($row['course_id']) : $course_name_display;
	
}
else if($view == 'add')
{
	
	$start_date				= $_REQUEST['start_date'];
	$end_date				= $_REQUEST['end_date'];
	$drop_add_date			= $_REQUEST['drop_add_date'];
	$reserve_days			= $_REQUEST['reserve_days'];
	$course_id				= $_REQUEST['course_id'];
	$course_name_display	= $_REQUEST['course_name_display'];
	$school_year_id			= $_REQUEST['school_year_id'];
	$term_id				= $_REQUEST['term_id'];


}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_date_enrollment.php';
?>