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



$page_title = 'Manage Course';
$pagination = 'Utility  > Manage Course';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$course_code		= $_REQUEST['course_code'];
$course_name		= $_REQUEST['course_name'];
$description		= $_REQUEST['description'];
$publish			= $_REQUEST['publish'];
$college_id			= $_REQUEST['college_id'];
$filtercol			= $_REQUEST['filtercol'];

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
	if($course_code == '' || $course_name == '' || $description =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfCourseCodeExist($course_code))
	{
		$err_msg = 'Course code already exist.';
	}
	else if(checkIfCourseNameExist($course_name))
	{
		$err_msg = 'Course name already exist.';
	}
	else
	{
		 $sql = "INSERT INTO tbl_course
				(
					course_code, 
					course_name,
					description, 
					college_id,
					publish,
					date_created,
					created_by,				
					date_modified
				) 
				VALUES 
				(
					".GetSQLValueString($course_code,"text").",  
					".GetSQLValueString($course_name,"text").", 
					".GetSQLValueString($description,"text").",
					".GetSQLValueString($college_id,"int").", 					
					".GetSQLValueString($publish,"text").",  
					".time().", 
					".USER_ID.", 
					".time()."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_course\';</script>';
		}
	}	
}
else if($action == 'update')
{
	if(!checkCourseEditable($id))
	{
		$err_msg = 'Cannot edit '.getCourseCode($id).'. Currently there are student associated.';
	}
	else if($course_code == '' || $course_name == '' || $description =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfCourseCodeExist($course_code, $id))
	{
		$err_msg = 'Course code already exist.';
	}
	else if(checkIfCourseNameExist($course_name, $id))
	{
		$err_msg = 'Course name already exist.';
	}
	else
	{
		if (storedModifiedLogs(tbl_course, $id)) {
			$sql = "UPDATE tbl_course SET 
					course_code =".GetSQLValueString($course_code,"text").",
					course_name  =".GetSQLValueString($course_name,"text").",				
					description =".GetSQLValueString($description,"text").",
					publish =".GetSQLValueString($publish,"text").",	
					college_id =".GetSQLValueString($college_id,"int").",	
					date_modified = ".time() .",	
					modified_by = ".USER_ID . "
					WHERE id=" .$id;
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_course\';</script>';
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
			$sql_st_user = "SELECT * FROM tbl_student WHERE course_id =" .$item;
			$qry_st_user = mysql_query($sql_st_user);
			$ctr = @mysql_num_rows($qry_st_user);
			
			$sql_cur = "SELECT * FROM tbl_curriculum WHERE course_id =" .$item;
			$qry_cur = mysql_query($sql_cur);
			$ctr_cur = @mysql_num_rows($qry_cur);
			
			$sql_en = "SELECT * FROM tbl_enrollment_date WHERE course_id =" .$item;
			$qry_en = mysql_query($sql_en);
			$ctr_en = @mysql_num_rows($qry_en);
			
			if($ctr > 0 || $ctr_cur > 0 || $ctr_en > 0)
			{
				$err_msg = 'Cannot delete '.getCourseCode($item).'. Currently there are student associated.';
			}
			else
			{
				$sql = "DELETE FROM tbl_course WHERE id=" .$item;
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
		storedModifiedLogs(tbl_college, $item);
		$sql = "UPDATE tbl_course SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
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
			if(!checkCourseEditable($id))
			{
				$err_msg = 'Cannot unpublish '.getCourseCode($item).'. Currently there are Student associated.';
			}
			else
			{
				storedModifiedLogs(tbl_college, $item);
				$sql = "UPDATE tbl_course SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}
}



if($view == 'edit')
{
	 $sql = "SELECT * FROM tbl_course where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	
	
	$course_code		= $row['course_code'] != $course_code ? $row['course_code'] : $course_code;
	$course_name		= $row['course_name'] != $course_name ? $row['course_name'] : $course_name;
	$description		= $row['description'] != $description ? $row['description'] : $description;
	$publish			= $row['publish'] 	  != $publish ? $row['publish'] : $publish;
	$college_id			= $row['college_id'] != $college_id ? $row['college_id'] : $college_id;

}
else if($view == 'add')
{
	
	$course_code		= $_REQUEST['course_code'];
	$course_name		= $_REQUEST['course_name'];
	$description		= $_REQUEST['description'];
	$publish			= $_REQUEST['publish'];
	$college_id			= $_REQUEST['college_id'];
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_course.php';
?>