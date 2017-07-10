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


$page_title = 'Manage College';
$pagination = 'Utility  > Manage College';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$college_name			= $_REQUEST['college_name'];
$college_code			= $_REQUEST['college_code'];
$emp_id					= $_REQUEST['emp_id'];	
$emp_id_display			= $_REQUEST['emp_id_display'];	
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
	if($college_name == '' || $college_code == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfCollegeExist($college_name))
	{
		$err_msg = 'College name already exist.';
	}
	else if(checkIfCollegeCodeExist($college_code))
	{
		$err_msg = 'College code already exist.';
	}
	else
	{
		$sql = "INSERT INTO tbl_college 
				(
					college_name, 
					college_code,
					emp_id, 
					publish,
					date_created,
					created_by,
					date_modified
				) 
				VALUES 
				(
					".GetSQLValueString($college_name,"text").",
					".GetSQLValueString($college_code,"text").",  
					".GetSQLValueString($emp_id,"int").", 
					".GetSQLValueString($publish,"text").",  	
					".time().",
					".USER_ID.",		
					".time()."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_college\';</script>';
		}
	}	
}
else if($action == 'update')
{
	
	if(!checkCollegeEditable($id))
	{
		$err_msg = 'Cannot edit College of '.getCollegeName($id).'. Currently there are course associated.';
	}
	else if($college_name == '' || $college_code == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfCollegeExist($college_name,$id))
	{
		$err_msg = 'College name already exist.';
	}
	else if(checkIfCollegeCodeExist($college_code,$id))
	{
		$err_msg = 'College code already exist.';
	}
	else
	{
		if (storedModifiedLogs(tbl_college, $id)) {
			$sql = "UPDATE tbl_college SET 
					college_name  =".GetSQLValueString($college_name,"text").",	
					college_code  =".GetSQLValueString($college_code,"text").",				
					emp_id =".GetSQLValueString($emp_id,"int").",	
					publish =".GetSQLValueString($publish,"text").",				 
					date_modified = ".time() .",
					modified_by = ".USER_ID ."	
					WHERE id=" .$id;
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_college\';</script>';
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
			$sql_course= "SELECT * FROM tbl_course WHERE college_id = " .$item;
			$qry_course = mysql_query($sql_course);
			$ctr = mysql_num_rows($qry_course);
			if($ctr > 0)
			{
				$err_msg = 'Cannot delete College of '.getCollegeName($item).'. Currently there are course associated.';
			}
			else
			{
				$sql = "DELETE FROM tbl_college WHERE id=" .$item;
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
		$sql = "UPDATE tbl_college SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
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
			if(!checkCollegeEditable($id))
			{
				$err_msg = 'Cannot unpublish College of '.getCollegeName($item).'. Currently there are course associated.';
			}
			else
			{
				storedModifiedLogs(tbl_college, $item);
				$sql = "UPDATE tbl_college SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}
}



if($view == 'edit')
{
	 $sql = "SELECT * FROM tbl_college where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	
	
	$college_name		= $row['college_name'] != $college_name ? $row['college_name'] : $college_name;
	$college_code		= $row['college_code'] != $college_code ? $row['college_code'] : $college_code;
	$emp_id				= $row['emp_id'] != $emp_id ? $row['emp_id'] : $emp_id;
	$emp_id_display		= getProfessorFullName($row['emp_id']) != $emp_id_display ? getProfessorFullName($row['emp_id']) : $emp_id_display;	

	$publish			= $row['publish'] != $publish ? $row['publish'] : $publish;

}
else if($view == 'add')
{
	
	$college_name			= $_REQUEST['college_name'];
	$college_code			= $_REQUEST['college_code'];
	$emp_id					= $_REQUEST['emp_id'];
	$emp_id_display			= $_REQUEST['emp_id_display'];	
	$publish				= $_REQUEST['publish'];
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_college.php';
?>