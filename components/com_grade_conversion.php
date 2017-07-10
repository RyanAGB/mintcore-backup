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



$page_title = 'Manage Grade Conversion';
$pagination = 'Utility  > Manage Grade Conversion';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$floor_grade			= $_REQUEST['floor_grade'];
$ceiling_grade			= $_REQUEST['ceiling_grade'];
$grade_code				= $_REQUEST['grade_code'];
$is_grade_passing		= $_REQUEST['is_grade_passing'];
$description			= $_REQUEST['description'];
$publish				= $_REQUEST['publish'];

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
	if($floor_grade == '' || $ceiling_grade == '' || $grade_code == '' || $description == '') 
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (checkGradeConversionExist($floor_grade, $ceiling_grade, $grade_code, $is_grade_passing,  $id))
	{
		$err_msg = 'Record already exist.';
	}
	else if('"'.$floor_grade.'"' != '"'.floatval($floor_grade).'"' || '"'.$ceiling_grade.'"' != '"'.floatval($ceiling_grade).'"')
	{
		$err_msg = "Please enter numbers only.";
	}
	else
	{
		$sql = "INSERT INTO tbl_grade_conversion
				(
					floor_grade, 
					ceiling_grade, 
					grade_code,
					is_grade_passing,
					description,
					date_created,
					created_by,
					date_modified
				) 
				VALUES 
				(
					".GetSQLValueString($floor_grade,"text").",  
					".GetSQLValueString($ceiling_grade,"text").", 
					".GetSQLValueString($grade_code,"text").",  
					".GetSQLValueString($is_grade_passing,"text").", 
					".GetSQLValueString($description,"text").", 	
					".time().",
					".USER_ID.",		
					".time()."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_grade_conversion\';</script>';
		}
	}	
}
else if($action == 'update')
{
	if($floor_grade == '' || $ceiling_grade == '' || $grade_code == '' || $description == '') 
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (checkGradeConversionExist($floor_grade, $ceiling_grade, $grade_code, $is_grade_passing, $id))
	{
		$err_msg = 'Record already exist.';
	}
	else if('"'.$floor_grade.'"' != '"'.floatval($floor_grade).'"' || '"'.$ceiling_grade.'"' != '"'.floatval($ceiling_grade).'"')
	{
		$err_msg = "Please enter numbers only.";
	}
	else
	{
	
		if (storedModifiedLogs(tbl_grade_conversion, $id)) {
			$sql = "UPDATE tbl_grade_conversion SET 
					floor_grade  =".GetSQLValueString($floor_grade,"text").",	
					ceiling_grade =".GetSQLValueString($ceiling_grade,"text").", 
					grade_code =".GetSQLValueString($grade_code,"text").", 
					is_grade_passing =".GetSQLValueString($is_grade_passing,"text").", 
					description =".GetSQLValueString($description,"text").",			 
					date_modified = ".time() .",
					modified_by = ".USER_ID ."	
					WHERE id=" .$id;
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_grade_conversion\';</script>';
			}
		}	
		
	}
}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		$sql = "DELETE FROM tbl_grade_conversion WHERE id=" .$item;
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
		storedModifiedLogs(tbl_grade_conversion, $item);
		$sql = "UPDATE tbl_grade_conversion SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
		
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		storedModifiedLogs(tbl_grade_conversion, $item);
		$sql = "UPDATE tbl_grade_conversion SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
		
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_grade_conversion WHERE id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	
	
	$floor_grade			= $row['floor_grade'] != $floor_grade ? $row['floor_grade'] : $floor_grade;
	$ceiling_grade			= $row['ceiling_grade'] != $ceiling_grade ? $row['ceiling_grade'] : $ceiling_grade;
	$grade_code				= $row['grade_code'] != $grade_code ? $row['grade_code'] : $grade_code;
	$is_grade_passing		= $row['is_grade_passing'] != $is_grade_passing ? $row['is_grade_passing'] : $is_grade_passing;
	$description			= $row['description'] != $description ? $row['description'] : $description;
	$publish				= $row['publish'] != $publish ? $row['publish'] : $publish;

}
else if($view == 'add')
{
	
	$floor_grade			= $_REQUEST['floor_grade'];
	$ceiling_grade			= $_REQUEST['ceiling_grade'];
	$grade_code				= $_REQUEST['grade_code'];
	$is_grade_passing		= $_REQUEST['is_grade_passing'];
	$description			= $_REQUEST['description'];
	$publish				= $_REQUEST['publish'];

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_grade_conversion.php';
?>