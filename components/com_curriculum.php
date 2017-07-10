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



$page_title = 'Manage Curriculum';
$pagination = 'Curriculum Set Up  > Manage Curriculum';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$curriculum_code		= $_REQUEST['curriculum_code'];
$no_of_years			= $_REQUEST['no_of_years'];
$term_per_year			= $_REQUEST['term_per_year'];
$course_id				= $_REQUEST['course_id'];
$course_name_display	= $_REQUEST['course_name_display'];
$is_current				= $_REQUEST['is_current'];

$arrID = array();
$arrID = explode(",",$subject_id);

$filter_field 	= $_REQUEST['filter_field'];
$filter_order 	= $_REQUEST['filter_order'];
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
	if($no_of_years == '' || $curriculum_code == '' || $term_per_year == '' || $course_id == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (checkIfCurriculumCourseCodeExist($curriculum_code))
	{
		$err_msg = 'Curriculum Code already exist.';
	}
	else
	{
		$sql = "INSERT INTO tbl_curriculum
				(
					curriculum_code,
					term_per_year,
					no_of_years,
					course_id,
					is_current,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($curriculum_code,"text").",  
					".GetSQLValueString($term_per_year,"text").",  
					".GetSQLValueString($no_of_years,"int").",
					".GetSQLValueString($course_id,"int").",
					".GetSQLValueString($is_current,"text").",  	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_curriculum\';</script>';
		}
	}	
}
else if($action == 'update')
{
	/*if(checkCurriculumEditable($id))
	{			
		$err_msg ='Cannot edit curriculum '.getCurriculumCode($id).'. Currently there are student associated.';
	}
	else*/ if($no_of_years == '' || $curriculum_code == '' || $term_per_year == '' || $course_id == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfCurriculumIsAlreadyUsed($curriculum_id))
	{
		$err_msg = 'Cannot add new subject, the curriculum is already used in the system.';	
	}		
	else if (checkIfCurriculumCourseCodeExist($curriculum_code,$id))
	{
		$err_msg = 'Curriculum Code already exist.';
	}
	else
	{
		if(storedModifiedLogs(tbl_curriculum, $id))
		{
		$sql = "UPDATE tbl_curriculum SET 
				curriculum_code =".GetSQLValueString($curriculum_code,"text").",
				term_per_year =".GetSQLValueString($term_per_year,"text").",
				no_of_years =".GetSQLValueString($no_of_years,"int").",
				course_id =".GetSQLValueString($course_id,"int").",				
				is_current =".GetSQLValueString($is_current,"text").",				 
				date_modified = ".time() ." ,
				modified_by = ".USER_ID."
				WHERE id=" .$id;
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_curriculum\';</script>';
			}
		}
	}
}
else if($action == 'save_subject')
{
	if($units == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{	
		foreach($arrID as $item){
		$sql = "INSERT INTO tbl_curriculum_subject
				(
					curriculum_id, 
					year_level,
					term,
					subject_id,
					units,
					subject_category,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($id,"text").",  
					".GetSQLValueString($year_level,"int").",
					".GetSQLValueString($term,"int").",
					".GetSQLValueString($item,"int").",
					".GetSQLValueString($units,"int").", 
					".GetSQLValueString($subject_category,"text").",  	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">window.location =\'index.php?comp=com_curriculum\';</script>';
			}
		}
	}	
}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		if ($item != '')
		{
			$sql_student = "SELECT * FROM tbl_student WHERE curriculum_id = " .$item;
			$qry_student = mysql_query($sql_student);
			$ctr = mysql_num_rows($qry_student);
			
			if($ctr > 0 )
			{			
				$err_msg ='Cannot delete curriculum '.getCurriculumCode($item).'. Currently there are student associated.';
			}
			else
			{
				$sql = "DELETE FROM tbl_curriculum WHERE id=" .$item;
				mysql_query ($sql);
			}	
		}
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
		$sql = "UPDATE tbl_curriculum SET 
			is_current =".GetSQLValueString('N',"text").",  
			date_modified = ".time() .",
			modified_by = ".USER_ID ." 
			WHERE course_id = ".getCurriculumCourse($item)
			;
		mysql_query ($sql);	
		
		storedModifiedLogs(tbl_curriculum, $item);
		$sql = "UPDATE tbl_curriculum SET is_current = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
		
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		if ($item != '')
		{
			$sql_student = "SELECT * FROM tbl_student WHERE curriculum_id = " .$item;
			$qry_student = mysql_query($sql_student);
			$ctr = mysql_num_rows($qry_student);
			
			if($ctr > 0 )
			{			
				$err_msg ='Cannot Unset curriculum '.getCurriculumCode($item).'. Currently there are student associated.';
			}
			else
			{
				storedModifiedLogs(tbl_curriculum, $id);
				$sql = "UPDATE tbl_curriculum SET is_current = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_curriculum where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	
	$curriculum_code	= $row['curriculum_code'] != $curriculum_code ? $row['curriculum_code'] : $curriculum_code;
	$no_of_years		= $row['no_of_years'] != $no_of_years ? $row['no_of_years'] : $no_of_years;
	$term_per_year		= $row['term_per_year'] != $term_per_year ? $row['term_per_year'] : $term_per_year;
	$course_id			= $row['course_id'] != $course_id ? $row['course_id'] : $course_id;
	$course_name_display= getCourseName($row['course_id']) != $course_name_display ? getCourseName($row['course_id']) : $course_name_display;
	$is_current			= $row['is_current'] != $is_current ? $row['is_current'] : $is_current;


}
else if($view == 'add_subject')
{

	$year_level				= $_REQUEST['year_level'];
	$term					= $_REQUEST['term'];
	$units					= $_REQUEST['units'];
	$subject_id				= $_REQUEST['subject_id'];
	$subject_name_display	= $_REQUEST['subject_name_display'];
	$subject_category		= $_REQUEST['subject_category'];

}
else if($view == 'add')
{
	$curriculum_code		= $_REQUEST['curriculum_code'];
	$no_of_years			= $_REQUEST['no_of_years'];
	$term_per_year			= $_REQUEST['term_per_year'];
	$course_id				= $_REQUEST['course_id'];
	$course_name_display	= $_REQUEST['course_name_display'];
	$is_current				= $_REQUEST['is_current'];

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_curriculum.php';
?>