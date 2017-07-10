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



$page_title = 'Manage Discount';
$pagination = 'Billing  > Manage Discount';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$name				= $_REQUEST['name'];
$term_id			= $_REQUEST['term_id'];
$value				= $_REQUEST['value'];
$publish			= $_REQUEST['publish'];

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
	if($name == '' || $value == '' || $term_id == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (checkIfDiscountIsExist($name, $term_id, $id))
	{
		$err_msg = 'Discount is already exist';
	}
	else
	{
		$sql = "INSERT INTO tbl_discount 
				( 
					name,
					term_id,
					value, 
					publish,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($name,"text").", 
					".GetSQLValueString($term_id,"text").", 
					".GetSQLValueString($value,"text").",
					".GetSQLValueString($publish,"text").",  	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
				
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_school_discount\';</script>';
		}
	}	
}
else if($action == 'update')
{
	if($name == '' || $value =='' || $term_id =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (checkIfDiscountIsExist($name, $term_id, $id))
	{
		$err_msg = 'Discount is already exist';
	}
	else if(!checkDiscountEditable($id))
	{
		$err_msg = 'Cannot be edit. Discount already used';
	}
	else
	{
		if(storedModifiedLogs(tbl_discount, $id))
		{
			$sql = "UPDATE tbl_discount SET 
					name  =".GetSQLValueString($name,"text").",
					term_id  =".GetSQLValueString($term_id,"text").",				
					value =".GetSQLValueString($value,"text").",
					publish =".GetSQLValueString($publish,"text").",				 
					date_modified = ".time() .",
					modified_by = ".USER_ID." 
					WHERE id = " .$id;
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_school_discount\';</script>';
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
			$sql_discount= "SELECT * FROM tbl_student_payment WHERE discount_id = " .$item;
			$qry_discount = mysql_query($sql_discount);
			$ctr = mysql_num_rows($qry_discount);
			if($ctr > 0)
			{
				$err_msg = 'Cannot delete discount. Currently there are payment associated.';
			}
			else
			{
				$sql = "DELETE FROM tbl_discount WHERE id=" .$item;
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
		storedModifiedLogs(tbl_discount, $id);
		$sql = "UPDATE tbl_discount SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
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
			if(!checkDiscountEditable($item))
			{
				$err_msg = 'Cannot Unpublish discount. Currently there are payment associated.';
			}
			else
			{
				storedModifiedLogs(tbl_discount, $id);
				$sql = "UPDATE tbl_discount SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_discount WHERE id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	


	$name		= $row['name'] != $name ? $row['name'] : $name;
	$term_id	= $row['term_id'] != $term_id ? $row['term_id'] : $term_id;
	$value		= $row['value'] != $value ? $row['value'] : $value;
	$publish	= $row['publish'] != $publish ? $row['publish'] : $publish;


}
else if($view == 'add')
{
	
	$name				= $_REQUEST['name'];
	$term_id			= $_REQUEST['term_id'];
	$value				= $_REQUEST['value'];
	$publish			= $_REQUEST['publish'];

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_school_discount.php';
?>