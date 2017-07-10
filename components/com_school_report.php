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


$page_title = 'Manage Report';
$pagination = 'Utility  > Manage Report';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];
$comp 	= $_REQUEST['comp'];

$report_name		= $_REQUEST['report_name'];
$classification		= $_REQUEST['classification'];

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
	if($report_name == '' || $classification == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{
		$sql = "INSERT INTO tbl_school_report 
				(
					report_name, 
					classification,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($report_name,"text").",  
					".GetSQLValueString($classification,"text").",  	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_school_report\';</script>';
		}
	}	
}
else if($action == 'update')
{
	if($report_name == '' || $classification == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{
		if(storedModifiedLogs(tbl_school_report, $id))
		{
			$sql = "UPDATE tbl_school_report SET 
					report_name =".GetSQLValueString($report_name,"text").",
					classification  =".GetSQLValueString($classification,"text").",
					date_modified = ".time() .",
					modified_by = ".USER_ID." 
					WHERE id = " .$id;
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_school_report\';</script>';
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
			$sql_room= "SELECT * FROM tbl_student_report WHERE report_id =" .$item;
			$qry_room = mysql_query($sql_room);
			$ctr = mysql_num_rows($qry_room);

			if($ctr > 0 )
			{				
				$err_msg ='Cannot delete Report. Currently there are students associated.';
			}
			else
			{
				$sql = "DELETE FROM tbl_school_report WHERE id=" .$item;
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
		storedModifiedLogs(tbl_building, $id);
		$sql = "UPDATE tbl_building SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
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
			if(!checkBuildingEditable($id))
			{
				$err_msg = 'Cannot Unpublish '.getBuildingName($item).'. Currently there are schedule associated.';
			}
			else
			{
				storedModifiedLogs(tbl_building, $id);
				$sql = "UPDATE tbl_building SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_school_report where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	

	$report_name		= $row['report_name'] != $report_name ? $row['report_name'] : $report_name;
	$classification		= $row['classification'] != $classification ? $row['classification'] : $classification;

}
else if($view == 'add')
{
	
	$report_name		= $_REQUEST['report_name'];
	$classification		= $_REQUEST['classification'];

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_school_report.php';
?>