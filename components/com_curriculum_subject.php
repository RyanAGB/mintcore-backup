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



$page_title = 'Manage Curriculum Subject';
$pagination = 'Curriculum Setup  > Manage Curriculum Subject';

$view = $view==''?'list':$view; // initialize action

$id				= $_REQUEST['id'];
$temp 			= $_REQUEST['temp'];

$curriculum_id		= $_REQUEST['curriculum_id'];
$subject_id			= $_REQUEST['subject_id'];
$subject_category	= $_REQUEST['subject_category'];
$year_level			= $_REQUEST['year_level'];
$term				= $_REQUEST['term'];
$units				= $_REQUEST['units'];
$prerequisite 		= $_REQUEST['prerequisite'];
$prerequisite_name_display 		= $_REQUEST['corequisite_name_display'];
$corequisite 		= $_REQUEST['corequisite'];
$corequisite_name_display 		= $_REQUEST['corequisite_name_display'];	

$filter_field 	= $_REQUEST['filter_field'];
$filter_order 	= $_REQUEST['filter_order'];
$page = $_REQUEST['page'];
$fcourse = $_REQUEST['fcourse'];
$fcurr = $_REQUEST['fcurr'];

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
	if($year_level == '' || $subject_category == '' || $year_level =='' || $term =='' || $units =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	/*else if(checkIfCurriculumIsAlreadyUsed($curriculum_id))
	{
		$err_msg = 'Cannot add new subject, the curriculum is already used in the system.';	
	}*/	
	else if(checkIfCurriculumSubjectExist($subject_id, $curriculum_id, $id)) 
	{
		$err_msg = 'Curriculum subject already exist.';
	}
	else
	{
		$sql = "INSERT INTO tbl_curriculum_subject
				(
					curriculum_id,
					subject_id,
					subject_category,
					year_level,
					term,
					units,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($curriculum_id,"int").",  
					".GetSQLValueString($subject_id,"int").", 
					".GetSQLValueString($subject_category,"text").",
					".GetSQLValueString($year_level,"int").",  	
					".GetSQLValueString($term,"int").",  
					".GetSQLValueString($units,"int").",  
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			$curriculum_subject_id = mysql_insert_id();
			//ADDED FOR ELECTIVE SUBJECT MODULE (MKT)
			if($subject_category=='E' OR $subject_category=='EO')
			{
				$sqle = "INSERT INTO tbl_elec_subject ( elec_subject_id ) VALUES 
					(".GetSQLValueString($subject_id,"text").")";
				
				mysql_query ($sqle);
			}
			//
			if(count($prerequisite) > 0)
			{
				foreach($prerequisite as $prereq)
				{
					$sql = "INSERT INTO tbl_curriculum_subject_prereq
							(
								curriculum_subject_id,
								prereq_subject_id
							) 
							VALUES 
							(
								".GetSQLValueString($curriculum_subject_id,"int").",  
								".GetSQLValueString($prereq,"int")."
							)";
					mysql_query ($sql);
				}
			}
			
			if(count($corequisite) > 0)
			{
				foreach($corequisite as $coreq)
				{
					$sql = "INSERT INTO tbl_curriculum_subject_coreq
							(
								curriculum_subject_id,
								coreq_subject_id
							) 
							VALUES 
							(
								".GetSQLValueString($curriculum_subject_id,"int").",  
								".GetSQLValueString($coreq,"int")."
							)";
					mysql_query ($sql);
				}
			}
			
			echo '<script language="javascript">alert("Curriculum subject has been successfully Added!");window.location =\'index.php?comp=com_curriculum_subject\';</script>';
		}
	}	
}
else if($action == 'update')
{
	if($year_level == '' || $subject_category == '' || $year_level =='' || $term =='' || $units =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	/*else if(checkIfCurriculumIsAlreadyUsed($curriculum_id))
	{
		$err_msg = 'Cannot edit, curriculum subject is already used in the system.';	
	}*/	
	else if(checkIfCurriculumSubjectExist($subject_id, $curriculum_id, $id)) 
	{
		$err_msg = 'Curriculum subject already exist.';
	}
	else
	{
		if(storedModifiedLogs(tbl_curriculum_subject, $id))
		{
			$sql = "UPDATE tbl_curriculum_subject SET 
					curriculum_id =".GetSQLValueString($curriculum_id,"int").", 
					subject_id =".GetSQLValueString($subject_id,"int").", 
					subject_category =".GetSQLValueString($subject_category,"text").",
					year_level =".GetSQLValueString($year_level,"int").", 
					term =".GetSQLValueString($term,"int").",   
					units =".GetSQLValueString($units,"int").",  
					prerequisite =".GetSQLValueString($prerequisite,"text").",  				 
					date_modified = ".time() .",
					modified_by = ".USER_ID." 
					WHERE id = " .$id;
				
			if(mysql_query ($sql))
			{
				$sql = "DELETE FROM tbl_curriculum_subject_prereq WHERE curriculum_subject_id=".GetSQLValueString($id,"int");
				mysql_query ($sql);
				
				//ADDED FOR ELECTIVE SUBJECT MODULE (MKT)
				if($subject_category=='E' OR $subject_category=='EO')
				{
					$sqle = "UPDATE tbl_elec_subject SET 
					elec_subject_id = ".GetSQLValueString($subject_id,"text")."
					WHERE elec_subject_id = " .$id;
					
					mysql_query($sqle);
				}
				//
				
			if(count($prerequisite) > 0)
			{	
				foreach($prerequisite as $prereq)
				{
					$sql = "INSERT INTO tbl_curriculum_subject_prereq
							(
								curriculum_subject_id,
								prereq_subject_id
							) 
							VALUES 
							(
								".GetSQLValueString($id,"int").",  
								".GetSQLValueString($prereq,"int")."
							)";
					mysql_query ($sql);
				}	}		
				
				$sql = "DELETE FROM tbl_curriculum_subject_coreq WHERE curriculum_subject_id=".GetSQLValueString($id,"int");
				mysql_query ($sql);
				
			if(count($corequisite) > 0)
			{
				foreach($corequisite as $coreq)
				{
					$sql = "INSERT INTO tbl_curriculum_subject_coreq
							(
								curriculum_subject_id,
								coreq_subject_id
							) 
							VALUES 
							(
								".GetSQLValueString($id,"int").",  
								".GetSQLValueString($coreq,"int")."
							)";
					mysql_query ($sql);
				}
			}
				
				echo '<script language="javascript">alert("Curriculum subject has been successfully updated!");window.location =\'index.php?comp=com_curriculum_subject\';</script>';
			}
		}
	}
}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		/*if(checkIfCurriculumIsAlreadyUsed(getCurriculumByCurriculumSubjectId($item)))
		{
			$err_msg = 'Cannot delete curriculum subject is already used in the system.';	
		}	
		else
		{*/
			$sql = "DELETE FROM tbl_curriculum_subject WHERE id=" .$item;
			mysql_query ($sql);
		//}
	}

	if(count($arr_str) > 0)
	{
		echo '<script language="javascript">alert("'.implode("\n",$arr_str).'");</script>';
	}

}

if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_curriculum_subject WHERE id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	

	$curriculum_id		= $row['curriculum_id'] != $curriculum_id ? $row['curriculum_id'] : $curriculum_id;
	$subject_id			= $row['subject_id'] != $subject_id ? $row['subject_id'] : $subject_id;
	$subject_category	= $row['subject_category'] != $subject_category ? $row['subject_category'] : $subject_category;
	$year_level			= $row['year_level'] != $year_level ? $row['year_level'] : $year_level;
	$term				= $row['term'] != $term ? $row['term'] : $term;
	$units				= $row['units'] != $units ? $row['units'] : $units;
	$prerequisite		= $row['prerequisite'] != $prerequisite ? $row['prerequisite'] : $prerequisite;	


}
else if($view == 'add')
{
	
	$curriculum_id		= $_REQUEST['curriculum_id'];
	$year_level			= $_REQUEST['subject_id'];
	$subject_category	= $_REQUEST['subject_category'];
	$year_level			= $_REQUEST['year_level'];
	$term				= $_REQUEST['term'];
	$units				= $_REQUEST['units'];
	$prerequisite 		= $_REQUEST['prerequisite'];
	$prerequisite_name_display 		= $_REQUEST['prerequisite_name_display'];
	$corequisite 		= $_REQUEST['corequisite'];
	$corequisite_name_display 		= $_REQUEST['corequisite_name_display'];	
	

}


// component block, will be included in the template page 
$content_template = 'components/block/blk_com_curriculum_subject.php';
?>