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



$page_title = 'Manage Fee';
$pagination = 'Billing  > Manage Fee';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$term_id						= $_REQUEST['term'];
$fee_name						= $_REQUEST['fee_name'];
$amount							= $_REQUEST['amount'];
$fee_type						= $_REQUEST['fee_type'];
$school_year_level				= $_REQUEST['school_year_level'];
$school_year_level_id_display	= $_REQUEST['school_year_level_id_display'];	
$publish						= $_REQUEST['publish'];

$sy_filter = $_REQUEST['term_id'];
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

	if($term_id == '' || $fee_name == '' || $amount == '' || $fee_type == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfFeesIsInUsed($term_id, $fee_type))
	{
		$err_msg = 'Cannot add fees anymore.';
	}
	else if(checkIfLecLabIsUsed($term_id, $fee_type))
	{
		$err_msg = 'Lecture and Laboratory Fees already exist.';
	}
	else if(!is_numeric($amount))
	{
		$err_msg = 'Please enter a valid Amount.';
	}
	else 
	{
		$sql = "INSERT INTO tbl_school_fee 
				(
					term_id,
					fee_name,
					amount, 
					fee_type,
					school_year_level,
					publish,
					date_created,
					created_by,				
					date_modified
				) 
				VALUES 
				(
					".GetSQLValueString($term_id,"int").", 
					".GetSQLValueString($fee_name,"text").", 
					".GetSQLValueString($amount,"double").",
					".GetSQLValueString($fee_type,"text").",  
					".GetSQLValueString($school_year_level,"text").", 	
					".GetSQLValueString($publish,"text").",  
					".time().", 
					".USER_ID.", 
					".time()."
				)";
				
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_fee\';</script>';
		}
		else
		{
			die(mysql_error());
		}

	}	
}
else if($action == 'update')
{
	if($term_id == '' || $fee_name == '' || $amount == '' || $fee_type =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfLecLabIsUsed($term_id, $fee_type, $id))
	{
		$err_msg = 'Lecture and Laboratory Fees already exist.';
	}
	else if(checkFeeEditable($id,$term_id))
	{
		$err_msg = 'Cannot edit Fees. Currently there are payment associated.';
	}
	else
	{
		if (storedModifiedLogs('tbl_school_fee', $id)) 
		{
			$sql = "UPDATE tbl_school_fee SET 
					term_id =".GetSQLValueString($term_id,"int").", 
					fee_name  =".GetSQLValueString($fee_name,"text").", 			
					amount =".GetSQLValueString($amount,"double").",
					fee_type =".GetSQLValueString($fee_type,"text").",
					school_year_level =".GetSQLValueString($school_year_level,"text").",	
					publish =".GetSQLValueString($publish,"text").",	
					date_modified = ".time() .",	
					modified_by = ".USER_ID . "
					WHERE id=" .$id;	
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_fee\';</script>';
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
			$sql_fee= "SELECT * FROM tbl_student_fees WHERE fee_id = " .$item;
			$qry_fee = mysql_query($sql_fee);
			$ctr = mysql_num_rows($qry_fee);
			
			/*$sql_fee2= "SELECT term_id FROM tbl_school_fee WHERE id = " .$item;
			$qry_fee2 = mysql_query($sql_fee2);
			$row = mysql_fetch_array($qry_fee2);*/
			
			if($ctr > 0 /*|| $row['term_id']==CURRENT_TERM_ID*/)
			{
				$err_msg = 'Cannot delete Fees. Currently there are payment associated.';
			}
			else
			{
				$sql = "DELETE FROM tbl_school_fee WHERE id=" .$item;
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
		storedModifiedLogs('tbl_school_fee', $item);
		$sql = "UPDATE tbl_school_fee SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
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
			if(checkFeeEditable($item))
			{
				$err_msg = 'Cannot Unpublish Fees. Currently there are payment associated.';
			}
			else
			{
				storedModifiedLogs('tbl_school_fee', $item);
				$sql = "UPDATE tbl_school_fee SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_school_fee where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	
	$skulyr = array();
	$skulyr = explode(",",$row["school_year_level"]);
	foreach ($skulyr as $val) {
		if ($val !="") {
			$school_year_level_id_display		.= getSchoolYr($val) != $school_year_level_id_display ? getSchoolYr($val) : $school_year_level_id_display;	
			$school_year_level_id_display 		.= ", ";
		
		
		}
	
	}

	$term_id				= $row['term_id'] != $term_id ? $row['term_id'] : $term_id;
	$fee_name				= $row['fee_name'] != $fee_name ? $row['fee_name'] : $fee_name;
	$amount					= $row['amount'] != $amount ? $row['amount'] : $amount;
	$fee_type				= $row['fee_type'] != $fee_type ? $row['fee_type'] : $fee_type;
	$school_year_level		= $row['school_year_level'] != $school_year_level ? $row['school_year_level'] : $school_year_level;
	$publish				= $row['publish'] != $publish ? $row['publish'] : $publish;
	
}
else if($view == 'add')
{
	
	$term_id						= $_REQUEST['term'];
	$fee_name						= $_REQUEST['fee_name'];
	$amount							= $_REQUEST['amount'];
	$fee_type						= $_REQUEST['fee_type'];
	$school_year_level				= $_REQUEST['school_year_level'];
	$school_year_level_id_display	= $_REQUEST['school_year_level_id_display'];
	$publish						= $_REQUEST['publish'];
	
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_fee.php';
?>