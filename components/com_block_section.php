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



$page_title = 'Manage Block Section';
$pagination = 'Curriculum Set Up  > Manage Block Section';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$block_name				= $_REQUEST['block_name'];
$course_id				= $_REQUEST['course_id'];
$course_name_display	= $_REQUEST['course_name_display'];
$publish				= $_REQUEST['publish'];
$school_year_id 		= $_REQUEST['school_year_id'];
$term_id				= $_REQUEST['term_id'];	
$bid					= $_REQUEST['bid'];	
$num					= $_REQUEST['num'];	
$year_level				= $_REQUEST['year_level'];
$arrID = array();

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
	if($block_name == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}

	else
	{
		$sql = "INSERT INTO tbl_block_section
				(
					block_name,
					course_id,
					term_id,
					year_level,
					publish,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($block_name,"text").",  
					".GetSQLValueString($course_id,"int").",  
					".GetSQLValueString($term_id,"int").",
					".GetSQLValueString($year_level,"int").",
					".GetSQLValueString($publish,"text").",  	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_block_section\';</script>';
		}
	}	
}
else if($action == 'update')
{
	if($block_name == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	/*else if(!checkBlockEditable($id))
	{
		$err_msg = 'Cannot edit Block Section. Currently there are student associated.';
	}*/
	else
	{
		if(storedModifiedLogs(tbl_block_section, $id))
		{
		$sql = "UPDATE tbl_block_section SET 
				block_name =".GetSQLValueString($block_name,"text").",
				course_id =".GetSQLValueString($course_id,"int").",
				term_id =".GetSQLValueString($term_id,"int").",			
				publish =".GetSQLValueString($publish,"text").",				 
				date_modified = ".time() ." ,
				modified_by = ".USER_ID."
				WHERE id=" .$id;
		
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_block_section\';</script>';
			}
		}
	}
}
else if($action == 'saveblocksubject')
{
	/*for($ctr=1;$ctr<=$num;$ctr++){
		
		if (isset($_REQUEST["chk_".$ctr])) {
			$arrID[] = $_REQUEST["chk_".$ctr];
		}
	}
		if(count($arrID) > 0){
			
			$sql = "SELECT * FROM tbl_block_section sec,tbl_block_subject sub WHERE sec.id = sub.block_section_id AND sec.id = ".$bid." AND sec.term_id = ".CURRENT_TERM_ID;
			$query = mysql_query($sql);
			$ctr = mysql_num_rows($query);
			
			if(checkIfSubjectScheduleExist($arrID))
			{
				$err_msg = 'Subject Duplicates in Selected Schedules.';
			}
			/*else if(!checkBlockEditable($bid))
			{
				$err_msg = 'Cannot edit block subjects. Currently there are student associated';
			}
			else
			{
			$sqldel ="DELETE FROM tbl_block_subject WHERE block_section_id = " . $bid;
			$query = mysql_query($sqldel);
			
			foreach($arrID as $item){
			 $sql = "INSERT INTO tbl_block_subject
					(
						block_section_id, 
						schedule_id,
						date_created, 
						created_by,
						date_modified,
						modified_by
					) 
					VALUES 
					(
						".GetSQLValueString($bid,"int").",  
						".GetSQLValueString($item,"int").",
						".time().",
						".USER_ID.", 
						".time().",
						".USER_ID."
					)";
			
				if(mysql_query ($sql)){
					echo '<script language="javascript">window.location =\'index.php?comp=com_block_section\';</script>';	
					}
			}
		}
	}*/
	
	if ($_REQUEST["id"]!='') {
		
		$block_ids = explode(',',$_REQUEST["id"]);
	
	foreach($block_ids as $ids)
	{
	$sql = "INSERT INTO tbl_block_subject
					(
						block_section_id, 
						schedule_id,
						date_created, 
						created_by,
						date_modified,
						modified_by
					) 
					VALUES 
					(
						".GetSQLValueString($bid,"int").",  
						".GetSQLValueString($ids,"int").",
						".time().",
						".USER_ID.", 
						".time().",
						".USER_ID."
					)";
			
				if(mysql_query ($sql)){
					echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_block_section\';</script>';
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
			$sql_sched= "SELECT * FROM tbl_student_enrollment_status WHERE block_id = " .$item;
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);
			
			if($ctr > 0)
			{
				$err_msg = 'Cannot delete Block Section. Currently there are student associated.';
			}
			else
			{
				$sql = "DELETE FROM tbl_block_section WHERE id=" .$item;
				mysql_query ($sql);	
				$sql_sub = "DELETE FROM tbl_block_subject WHERE block_section_id = ".$item;
				mysql_query($sql_sub);
			}
		}
	}

}
else if($action == 'delete_sub')
{
	
		if ($_REQUEST['id'] != '')
		{
			$sql_sched= "SELECT * FROM tbl_student_schedule WHERE schedule_id = " .$_REQUEST['id'];
			$qry_sched = mysql_query($sql_sched);
			$ctr = mysql_num_rows($qry_sched);
			
			if($ctr > 0)
			{
				$err_msg = 'Cannot delete Section. Currently there are student associated.';
			}
			else
			{	
				$sql_sub = "DELETE FROM tbl_block_subject WHERE block_section_id = ".$bid." AND schedule_id = " .$_REQUEST['id'];
				mysql_query($sql_sub);
			}
		}

}
else if($action == 'publish')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		if(storedModifiedLogs(tbl_block_section, $item)){
		$sql = "UPDATE tbl_block_section SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
		}
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		if ($item != '')
		{
			if(!checkBlockEditable($item))
			{
				$err_msg = 'Cannot unpublish Block Section. Currently there are student associated.';
			}
			else
			{
				if(storedModifiedLogs(tbl_block_section, $id)){
				$sql = "UPDATE tbl_block_section SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
				}
			}
		}
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_block_section where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	

	$block_name				= $row['block_name'] != $block_name ? $row['block_name'] : $block_name;
	$course_id				= $row['course_id'] != $course_id ? $row['course_id'] : $course_id;
	$term_per_year			= $row['term_per_year'] != $term_per_year ? $row['term_per_year'] : $term_per_year;

	$course_name_display	= getCourseName($row['course_id']) != $course_name_display ? getCourseName($row['course_id']) : $course_name_display;
	$year_level				= $row['year_level'] != $year_level ? $row['year_level'] : $year_level;
	$school_year_id			= $row['school_year_id'] != $school_year_id ? $row['school_year_id'] : $school_year_id;
	$term_id				= $row['term_id'] != $term_id ? $row['term_id'] : $term_id;
	$publish				= $row['publish'] != $publish ? $row['publish'] : $publish;
}
else if($view == 'add')
{
	$block_name				= $_REQUEST['block_name'];
	$course_id				= $_REQUEST['course_id'];
	$course_name_display	= $_REQUEST['course_name_display'];
	$year_level				= $_REQUEST['year_level'];
	$school_year_id 		= $_REQUEST['school_year_id'];
	$term_id				= $_REQUEST['term_id'];	
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_block_section.php';
?>