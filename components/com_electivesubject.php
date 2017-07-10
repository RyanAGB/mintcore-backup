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

if(USER_IS_LOGGED != '1')
{
	echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main
}


$page_title = 'Manage Elective Subjects';
$pagination = 'Curriculum Set Up  > Manage Elective Subjects';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$curriculum_id			= $_REQUEST['curriculum_id'];
$subject_id				= $_REQUEST['subject_id'];
$subject_name_display	= $_REQUEST['subject_name_display'];

$arr=array();
$arr=explode(",",$subject_id);

if($action == 'save')
{
	if($curriculum_id == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{
		foreach($arr as $y) {
		$sql = "INSERT INTO tbl_electivesubject
				(
					curriculum_id, 
					subject_id,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($curriculum_id,"text").",  
					".GetSQLValueString($y,"int").",   	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_electivesubject\';</script>';
			}
		}
	}	
}
else if($action == 'update')
{
		if($curriculum_id == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{
		if(storedModifiedLogs(tbl_electivesubject, $id))
		{
		$sql = "UPDATE tbl_electivesubject SET 
				curriculum_id =".GetSQLValueString($curriculum_id,"text").",
				subject_id =".GetSQLValueString($subject_id,"int").",					
				date_modified = ".time() ." ,
				modified_by = ".USER_ID."
				WHERE id=" .$id;
			
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_electivesubject\';</script>';
			}
		}
	}
}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		$sql = "DELETE FROM tbl_electivesubject WHERE id=" .$item;
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
		storedModifiedLogs(tbl_electivesubject, $id);
		$sql = "UPDATE tbl_electivesubject SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		storedModifiedLogs(tbl_electivesubject, $id);
		$sql = "UPDATE tbl_electivesubject SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_electivesubject where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	

	$curriculum_id		= $row['curriculum_id'] != $curriculum_id ? $row['curriculum_id'] : $curriculum_id;
	$subject_id			= $row['subject_id'] != $subject_id ? $row['subject_id'] : $subject_id;
	$subject_name_display= getSubjName($row['subject_id']) != $subject_name_display ? getSubjName($row['subject_id']) : $subject_name_display;

}
else if($view == 'add')
{
	
	$curriculum_id			= $_REQUEST['curriculum_id'];
	$subject_id				= $_REQUEST['subject_id'];
	$subject_name_display	= $_REQUEST['subject_name_display'];


}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_electivesubject.php';
?>