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



$page_title = 'Manage School Calendar';
$pagination = 'School Settings  > Manage School Calendar';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];


$title				= $_REQUEST['title'];
$description		= $_REQUEST['description'];

$s_year             = $_REQUEST['s_year'];
$s_month            = $_REQUEST['s_month'];
$s_day             	= $_REQUEST['s_day'];
$start_date 		= array($s_year, $s_month, $s_day);
$date_from 			= implode("-", $start_date);
		

$e_year            	= $_REQUEST['e_year'];
$e_month           	= $_REQUEST['e_month'];
$e_day         		= $_REQUEST['e_day'];
$end_date 			= array($e_year, $e_month, $e_day);
$date_to	 		= implode("-", $end_date);

$publish			= $_REQUEST['publish'];

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
	if($title == '' || $description == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (validateDate(valDateStartAddZero($date_from), valDateEndAddZero($date_to)))
	{
		$err_msg = 'Start date should not be less than or equal to End date.';
	}
	else
	{
		$sql = "INSERT INTO tbl_school_calendar 
				(
					title,
					description, 
					date_from,
					date_to, 
					publish,
					date_created,
					created_by,
					date_modified
				) 
				VALUES 
				(
					".GetSQLValueString($title,"text").",
					".GetSQLValueString($description,"text").",  
					".GetSQLValueString($date_from,"text").",
					".GetSQLValueString($date_to,"text").", 
					".GetSQLValueString($publish,"text").",  	
					".time().",
					".USER_ID.",		
					".time()."
				)";

		if(mysql_query ($sql))
		{
			// [+] STORE SYSTEM LOGS
			$param = array($title);
			storeSystemLogs(MSG_ADMIN_CALENDAR_FOR_ALL,$param,'','Y','Y');				
			// [-] STORE SYSTEM LOGS	
			
			$alls = getUserAll();
			foreach($alls as $all)
			{
				notification($all,MSG_ADMIN_CALENDAR_FOR_ALL,$param,USER_ID);					
			}
			
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_school_calendar\';</script>';
		}
	}	
}
else if($action == 'update')
{
	if($title == '' || $description == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (validateDate(valDateStartAddZero($date_from), valDateEndAddZero($date_to), $id))
	{
		$err_msg = 'Start date should not be less than or equal to End date.';
	}
	else
	{
		if (storedModifiedLogs(tbl_school_calendar, $id)) {
			 $sql = "UPDATE tbl_school_calendar SET 
					title  =".GetSQLValueString($title,"text").",
					description  =".GetSQLValueString($description,"text").",
					date_from  =".GetSQLValueString($date_from,"text").",				
					date_to =".GetSQLValueString($date_to,"text").",	
					publish =".GetSQLValueString($publish,"text").",				 
					date_modified = ".time() .",
					modified_by = ".USER_ID ."	
					WHERE id=" .$id;
				
			if(mysql_query ($sql))
			{
				// [+] STORE SYSTEM LOGS
				storeSystemLogs(MSG_MODIFIED_ADMIN_CALENDAR_FOR_ALL);			
				// [-] STORE SYSTEM LOGS	
				
				$alls = getUserAll();
				foreach($alls as $all)
				{
					//notification($all,MSG_MODIFIED_ADMIN_CALENDAR_FOR_ALL,$param,USER_ID);					
				}
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_school_calendar\';</script>';
			}
		}	
		
	}
}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		$sql = "DELETE FROM tbl_school_calendar WHERE id=" .$item;
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
		storedModifiedLogs(tbl_school_calendar, $item);
		$sql = "UPDATE tbl_school_calendar SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
		
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		storedModifiedLogs(tbl_school_calendar, $item);
		$sql = "UPDATE tbl_school_calendar SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
		
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_school_calendar WHERE id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	
	
	$title			= $row['title'] != $title ? $row['title'] : $title;
	$date_from		= $row['date_from'] != $date_from ? $row['date_from'] : $date_from;
	$date_to		= $row['date_to'] != $date_to ? $row['date_to'] : $date_to;
	$description	= $row['description'] != $description ? $row['description'] : $description;
	
	$publish		= $row['publish'] != $publish ? $row['publish'] : $publish;


	// [+] Start Date fields

	$exp_start_date = explode('-',$date_from);
	
	// Dates are with '0' simply multiply it to remove it	
	
	$s_day = $exp_start_date['2'] * 1;
	
	$s_month = ($exp_start_date['1'] * 1);
	
	$s_year = $exp_start_date['0'];	
		
	// [-] Start Date fields
	
	// [+] End Date fields

	$exp_end_date = explode('-',$date_to);
	
	// Dates are with '0' simply multiply it to remove it	
	
	$e_day = $exp_end_date['2'] * 1;
	
	$e_month = ($exp_end_date['1'] * 1);
	
	$e_year = $exp_end_date['0'];	
		
	// [-] End Date fields

}
else if($view == 'add')
{
	
	$title				= $_REQUEST['title'];
	$description		= $_REQUEST['description'];
	$date_from			= $_REQUEST['date_from'];
	$date_to			= $_REQUEST['date_to'];
	$publish			= $_REQUEST['publish'];
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_school_calendar.php';
?>