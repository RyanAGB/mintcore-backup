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


$page_title = 'Manage Subject';
$pagination = 'Utility  > Manage Subject';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$dis_id	= $_REQUEST['dis_id'];

$subject_code			= $_REQUEST['subject_code'];
$subject_name			= $_REQUEST['subject_name'];
$subject_type			= $_REQUEST['subject_type'];
$department_id			= $_REQUEST['department_id'];
$department_name_display= $_REQUEST['department_name_display'];
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
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_subject\';</script>';
		}
	}	
}
else if($action == 'update')
{
	/*if(!checkSubjectEditable($id))
	{			
		$err_msg ='Cannot Edit '.getSubjName($item).'. Currently there are Curriculum and Schedule Associated.';
	}
	else*/ if($subject_code == '' || $subject_name == '' || $subject_type == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfSubjectCodeExist($subject_code, $id))
	{
		$err_msg = 'Subject Code already exists.';
	}
	else
	{
		if(storedModifiedLogs(tbl_subject, $id))
		{
		$sql = "UPDATE tbl_subject SET 
				subject_name =".GetSQLValueString($subject_name,"text").",
				subject_code =".GetSQLValueString($subject_code,"text").",
				department_id =".GetSQLValueString($department_id,"int").",	
				subject_type =".GetSQLValueString($subject_type,"text").",  
				publish =".GetSQLValueString($publish,"text").",				 
				date_modified = ".time() ." ,
				modified_by = ".USER_ID."
				WHERE id=" .$id;
			
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_subject\';</script>';
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
			/*$sql_sched= "SELECT * FROM tbl_curriculum_subject WHERE subject_id = " .$item;
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);
			
			if($ctr > 0 )
			{			
				$err_msg ='Cannot delete '.getSubjName($item).'. Currently there are curriculum associated.';
			}
			else
			{*/
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
else if($action == 'dissolve')
{
	
		if ($dis_id != '')
		{
			
				$sql = "DELETE FROM tbl_subject WHERE id=" .$dis_id;
				
				if(mysql_query ($sql))
				{
					$sql_reserve = "DELETE FROM tbl_student_reserve_subject WHERE subject_id=" .$dis_id;

					$qry_reserve = mysql_query($sql_reserve);	
					
		
					$sql_st_sched = "DELETE FROM tbl_student_schedule WHERE subject_id=" .$dis_id;
		
					$qry_st_sched = mysql_query($sql_st_sched);
						
					
					$sql_fee = "DELETE FROM tbl_student_fees WHERE subject_id=" .$dis_id;
		
					$qry_fee = mysql_query($sql_fee);	
				}
			
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



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_subject where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	
	$subject_type		= $row['subject_type'] != $subject_type ? $row['subject_type'] : $subject_type;
	$subject_code		= $row['subject_code'] != $subject_code ? $row['subject_code'] : $subject_code;
	$subject_name		= $row['subject_name'] != $subject_name ? $row['subject_name'] : $subject_name;
	$department_id		= $row['department_id'] != $department_id ? $row['department_id'] : $department_id;
	$department_name_display= getDeptName($row['department_id']) != $department_name_display ? getDeptName($row['department_id']) : $department_name_display;
	$publish				= $row['publish'] != $publish ? $row['publish'] : $publish;


}
else if($view == 'add')
{
	
	$subject_code			= $_REQUEST['subject_code'];
	$subject_name			= $_REQUEST['subject_name'];
	$subject_type			= $_REQUEST['subject_type'];
	$department_id			= $_REQUEST['department_id'];
	$department_name_display= $_REQUEST['department_name_display'];
	$publish				= $_REQUEST['publish'];



}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_subject.php';
?>