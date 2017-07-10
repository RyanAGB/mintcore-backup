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


$page_title = 'Manage Elective Subject';
$pagination = 'Utility  > Manage Elective Subject';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$subject_id			= $_REQUEST['subject_id'];
$subject_name_display= $_REQUEST['subject_name_display'];

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

/*if($action == 'save')
{
	if($subject_code == '' || $subject_name == '' || $subject_type == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfSubjectCodeExist($subject_code, $id))
	{
		$err_msg = 'Subject Code already exists.';
	}
	else
	{
		$sql = "INSERT INTO tbl_subject
				(
					subject_code, 
					subject_name,
					department_id,
					subject_type,
					publish,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($subject_code,"text").",  
					".GetSQLValueString($subject_name,"text").",
					".GetSQLValueString($department_id,"int").", 
					".GetSQLValueString($subject_type,"text").",  
					".GetSQLValueString($publish,"text").",  	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_electives\';</script>';
		}
	}	
}
else*/ if($action == 'update')
{
	if($subject_id == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{
		$subj = explode(',',$subject_id);
		
		foreach($subj as $s)
		{
			$sqld = "DELETE FROM tbl_subject_elective WHERE elec_subj_id=".$id;
			$queryd = mysql_query($sqld);
			
			$sql = "INSERT INTO tbl_subject_elective
				(
					subject_id,
					elec_subj_id,
					subject_name, 
					subject_code,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				( 
					".GetSQLValueString($s,"text").",
					".GetSQLValueString($id,"text").",
					".GetSQLValueString(getSubjName($s),"text").",
					".GetSQLValueString(getSubjCode($s),"text").",
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_electives\';</script>';
			}
		}

	}
}
/*else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		if ($item != '')
		{
			/*$sql_sched= "SELECT * FROM tbl_curriculum_subject WHERE subject_id = " .$item;
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);
			
			if($ctr > 0 )
			{			
				$err_msg ='Cannot delete '.getSubjName($item).'. Currently there are curriculum associated.';
			}
			else
			{
				$sql = "DELETE FROM tbl_subject WHERE id=" .$item;
				mysql_query ($sql);
			//}
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
		storedModifiedLogs(tbl_subject, $id);
		$sql = "UPDATE tbl_subject SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
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
			if(!checkSubjectEditable($item))
			{			
				$err_msg ='Cannot edit '.getSubjName($item).'. Currently there are curriculum and schedule associated.';
			}
			else
			{
				storedModifiedLogs(tbl_subject, $id);
				$sql = "UPDATE tbl_subject SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}
}
*/

if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_subject_elective WHERE elec_subj_id=".$id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	
	$subject_id		= $row['subject_id'] != $subject_id ? $row['subject_id'] : $subject_id;
	$subject_name_display	= getSubjName($row['subject_id']) != $subject_name_display ? getSubjName($row['subject_id']) : $subject_name_display;

}
else if($view == 'add')
{
	
	$subject_id				= $_REQUEST['subject_id'];
	$subject_name_display	= $_REQUEST['subject_name_display'];

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_electives.php';
?>