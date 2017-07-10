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



$page_title = 'Manage Period';
$pagination = 'Utility  > Manage Period';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];
$term_id = $_REQUEST['term_id'];

$period_name			= $_REQUEST['period_name'];
$percentage				= $_REQUEST['percentage'];	
$period_order			= $_REQUEST['period_order'];	
$is_current				= $_REQUEST['is_current'];

$start_of_sub_year      = $_REQUEST['start_of_sub_year'];
$start_of_sub_month     = $_REQUEST['start_of_sub_month'];
$start_of_sub_day       = $_REQUEST['start_of_sub_day'];

$start_of_sub			= array($start_of_sub_year, $start_of_sub_month, $start_of_sub_day);
$start_of_submission	= implode("-", $start_of_sub);

$end_of_sub_year      	= $_REQUEST['end_of_sub_year'];
$end_of_sub_month     	= $_REQUEST['end_of_sub_month'];
$end_of_sub_day       	= $_REQUEST['end_of_sub_day'];

$end_of_sub		 		= array($end_of_sub_year, $end_of_sub_month, $end_of_sub_day);
$end_of_submission		= implode("-", $end_of_sub);

$sy_filter = $_REQUEST['filter_termId'];
$filter_field = $_REQUEST['filter_field'];
$filter_order = $_REQUEST['filter_order'];

	if($_SESSION[CORE_U_CODE]['current_comp'] != $comp || $_SESSION[CORE_U_CODE]['fieldName'] != $filter_field || $_SESSION[CORE_U_CODE]['orderBy'] != $filter_order || $_SESSION[CORE_U_CODE]['sy_filter'] != $sy_filter)
	{
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

	if($period_name == '' || $percentage == '' || $period_order == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (checkPeriodNameExist($period_name, $start_of_submission, $end_of_submission, getSchoolYearIdByTermId($term_id), $term_id,  $id))
	{
		$err_msg = 'Period already exist.';
	}
	else if (checkTotalPeriodPercentage(getSchoolYearIdByTermId($term_id),$term_id,$percentage))
	{
		$err_msg =  getSYandTerm($term_id) . ' already exceeded the 100%. ';
	}
	else if (validateDate(valDateStartAddZero($start_of_submission), valDateEndAddZero($end_of_submission)))
	{
		$err_msg = 'Start date should not be less than or equal to End date.';
	}
	else if(checkTotalPeriodPerTerm($term_id))
	{
		$err_msg =  'Periods already exceeded the number per term. ';
	}
	else 
	{
		if($is_current=='Y')
		{
			setAllNotCurrent($term_id);
		}	
		$sql = "INSERT INTO tbl_school_year_period
				(
					school_year_id,
					term_id,
					period_name, 
					start_of_submission, 
					end_of_submission,
					percentage,
					period_order,
					is_current,
					date_created,
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString(getSchoolYearIdByTermId($term_id),"text").",  
					".GetSQLValueString($term_id,"text").",  
					".GetSQLValueString($period_name,"text").",  
					".GetSQLValueString($start_of_submission,"text").", 
					".GetSQLValueString($end_of_submission,"text").", 
					".GetSQLValueString($percentage,"int").", 
					".GetSQLValueString($period_order,"int").", 
					".GetSQLValueString($is_current,"text").",  	
					".time().",
					".USER_ID.",		
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_school_period\';</script>';
		}
	}	
}
else if($action == 'update')
{
	$sdate = $start_of_sub_year. $start_of_sub_month. $start_of_sub_day;
	$edate = $end_of_sub_year. $end_of_sub_month. $end_of_sub_day;

	if($period_name == '' || $percentage == '' || $period_order == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (checkPeriodNameExist($period_name, $start_of_submission, $end_of_submission, getSchoolYearIdByTermId($term_id), $term_id,  $id))
	{
		$err_msg = 'Period already exist.';
	}
	else if (checkTotalPeriodPercentage(getSchoolYearIdByTermId($term_id),$term_id,$percentage,$id))
	{
		$err_msg =  getSYandTerm($term_id) . ' already exceeded the 100%. ';
	}
	else if (validateDate(valDateStartAddZero($start_of_submission), valDateEndAddZero($end_of_submission), $id))
	{
		$err_msg = 'Start date should not be less than or equal to End date.';
	}
	else
	{
		if (storedModifiedLogs(tbl_school_year_period, $id)) {
			$sql = "UPDATE tbl_school_year_period SET 
					school_year_id = ".GetSQLValueString(getSchoolYearIdByTermId($term_id),"text").",
					term_id = ".GetSQLValueString($term_id,"text").",
					period_name  =".GetSQLValueString($period_name,"text").",	
					start_of_submission =".GetSQLValueString($start_of_submission,"text").", 
					end_of_submission =".GetSQLValueString($end_of_submission,"text").",	
					percentage =".GetSQLValueString($percentage,"int").",
					period_order =".GetSQLValueString($period_order,"int").", 
					is_current =".GetSQLValueString($is_current,"text").",				 
					date_modified = ".time() .",
					modified_by = ".USER_ID ."	
					WHERE id=" .$id;
				
			if(mysql_query ($sql) or die (mysql_error()))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_school_period\';</script>';
			}
		}	
		
	}
}
else if($action == 'publish')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		$sql = "UPDATE tbl_school_year_period SET 
			is_current =".GetSQLValueString('N',"text").",  
			date_modified = ".time() .",
			modified_by = ".USER_ID ." 
			WHERE term_id = " . getPeriodTermId($item)."
			";
		mysql_query ($sql);	
		
		storedModifiedLogs(tbl_school_year_period, $item);
		$sql = "UPDATE tbl_school_year_period SET is_current = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
		
		$_SESSION[CORE_U_CODE]['current_period_info']['current_period_id'] = $item;
	}
}

else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{	
		$sql = "UPDATE tbl_school_year_period SET 
			is_current =".GetSQLValueString('N',"text").",  
			date_modified = ".time() .",
			modified_by = ".USER_ID ." 
			WHERE term_id = " . getPeriodTermId($item)."
			";
		mysql_query ($sql);
	
		storedModifiedLogs(tbl_school_year_period, $item);
		$sql = "UPDATE tbl_school_year_period SET is_current = 'N', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
		
		//$_SESSION[CORE_U_CODE]['current_period_info']['current_period_id'] = $item;
	}
}

if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_school_year_period where id = $id";
	$query = mysql_query ($sql);
	$ctr = mysql_num_rows($query);
	$row = mysql_fetch_array ($query);
	
	$term_id = $row["term_id"];
	$start_day = explode("-", $row["start_of_submission"]);
	$start_of_sub_year = $start_day[0];
	$start_of_sub_month = $start_day[1];
	$start_of_sub_day = $start_day[2];
	
	$end_day = explode("-", $row["end_of_submission"]);
	$end_of_sub_year = $end_day[0];
	$end_of_sub_month = $end_day[1];
	$end_of_sub_day = $end_day[2];
	
	$start_of_sub			= array($start_of_sub_year, $start_of_sub_month, $start_of_sub_day);
	$start_of_submission	= implode("-", $start_of_sub);

	$end_of_sub		 		= array($end_of_sub_year, $end_of_sub_month, $end_of_sub_day);
	$end_of_submission		= implode("-", $end_of_sub);
	
	$period_name			= $row['period_name'] != $period_name ? $row['period_name'] : $period_name;
	$start_of_submission	= $row['start_of_submission'] != $start_of_submission ? $row['start_of_submission'] : $start_of_submission;
	$end_of_submission		= $row['end_of_submission'] != $end_of_submission ? $row['end_of_submission'] : $end_of_submission;
	$percentage				= $row['percentage'] != $percentage ? $row['percentage'] : $percentage;
	$period_order			= $row['period_order'] != $period_order ? $row['period_order'] : $period_order;
	$is_current				= $row['is_current'] != $is_current ? $row['is_current'] : $is_current;

}
else if($view == 'add')
{
	
	$term_id			= $_REQUEST['term_id'];
	$period_name			= $_REQUEST['period_name'];
	$percentage				= $_REQUEST['percentage'];	
	$period_order			= $_REQUEST['period_order'];	
	$publish				= $_REQUEST['publish'];

	$start_of_sub_year      = $_REQUEST['start_of_sub_year'];
	$start_of_sub_month     = $_REQUEST['start_of_sub_month'];
	$start_of_sub_day       = $_REQUEST['start_of_sub_day'];

	$start_of_sub			= array($start_of_sub_year, $start_of_sub_month, $start_of_sub_day);
	$start_of_submission	= implode("-", $start_of_sub);

	$end_of_sub_year      	= $_REQUEST['end_of_sub_year'];
	$end_of_sub_month     	= $_REQUEST['end_of_sub_month'];
	$end_of_sub_day       	= $_REQUEST['end_of_sub_day'];

	$end_of_sub		 		= array($end_of_sub_year, $end_of_sub_month, $end_of_sub_day);
	$end_of_submission		= implode("-", $end_of_sub);
	
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_school_period.php';
?>