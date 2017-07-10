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


$page_title = 'Manage Department';
$pagination = 'Utility  > Manage Department';

$view = $view==''?'list':$view; // initialize action 

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$department_code		= $_REQUEST['department_code'];
$department_name		= $_REQUEST['department_name'];
$employee_id			= $_REQUEST['employee_id'];
$department_head_display= $_REQUEST['department_head_display'];
$publish				= $_REQUEST['publish'];

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
	if($department_name == '' || $department_code == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfDeptCodeExist($department_code))
	{
		$err_msg = 'Department code already exist.';
	}
	else if(checkIfDeptNameExist($department_name))
	{
		$err_msg = 'Department name already exist.';
	}
	else
	{
		$sql = "INSERT INTO tbl_department
				(
					department_name, 
					department_code,
					employee_id,
					publish,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($department_name,"text").",  
					".GetSQLValueString($department_code,"text").",
					".GetSQLValueString($employee_id,"int").", 
					".GetSQLValueString($publish,"text").",  	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_department\';</script>';
		}
	}	
}
else if($action == 'update')
{
	if(!checkDepartmentEditable($id))
	{
		$err_msg = 'Cannot edit '.getDeptName($id).' Department. Currently there are subject associated.';
	}
	else if($department_name == '' || $department_code == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfDeptCodeExist($department_code, $id))
	{
		$err_msg = 'Department code already exist.';
	}
	else if(checkIfDeptNameExist($department_name, $id))
	{
		$err_msg = 'Department name already exist.';
	}
	else
	{
		if(storedModifiedLogs(tbl_department, $id))
		{
		$sql = "UPDATE tbl_department SET 
				department_name =".GetSQLValueString($department_name,"text").",
				department_code =".GetSQLValueString($department_code,"text").",
				employee_id =".GetSQLValueString($employee_id,"int").",				
				publish =".GetSQLValueString($publish,"text").",				 
				date_modified = ".time() .",
				modified_by = ".USER_ID." 
				WHERE id=" .$id;
			
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_department\';</script>';
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
			$sql_dept = "SELECT * FROM tbl_subject WHERE department_id =" .$item;
			$qry_dept = mysql_query($sql_dept);
			$ctr = mysql_num_rows($qry_dept);
			if($ctr > 0)
			{
				$err_msg = 'Cannot delete '.getDeptName($item).'. Currently there are subject associated.';
			}
			else
			{
				$sql = "DELETE FROM tbl_department WHERE id=" .$item;
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
		storedModifiedLogs(tbl_department, $id);
		$sql = "UPDATE tbl_department SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
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
			if(!checkDepartmentEditable($id))
			{
				$err_msg = 'Cannot unpublish '.getDeptName($item).'. Currently there are Subject associated.';
			}
			else
			{
				storedModifiedLogs(tbl_department, $id);
				$sql = "UPDATE tbl_department SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_department where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	

	$department_code		= $row['department_code'] != $department_code ? $row['department_code'] : $department_code;
	$department_name		= $row['department_name'] != $department_name ? $row['department_name'] : $department_name;
	$employee_id		= $row['employee_id'] != $employee_id ? $row['employee_id'] : $employee_id;
	$department_head_display= getProfessorFullName($row['employee_id']) != $department_head_display ? getProfessorFullName($row['employee_id']) : $department_head_display;
	$publish				= $row['publish'] != $publish ? $row['publish'] : $publish;


}
else if($view == 'add')
{
	
	$department_code		= $_REQUEST['department_code'];
	$department_name		= $_REQUEST['department_name'];
	$employee_id			= $_REQUEST['employee_id'];
	$department_head_display= $_REQUEST['department_head_display'];
	$publish				= $_REQUEST['publish'];



}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_department.php';
?>