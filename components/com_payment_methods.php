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



$page_title = 'Manage Payment Methods';
$pagination = 'Billing  > Payment Methods';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$name				= $_REQUEST['name'];
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
	if($name == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{
		$sql = "INSERT INTO tbl_payment_method 
				(
					name,
					publish,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($name,"text").",  
					".GetSQLValueString($publish,"text").",  	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_payment_methods\';</script>';
		}
	}	
}
else if($action == 'update')
{		
	if(!checkPaymentMethodEditable($id))
	{			
		$err_msg = 'Cannot Edit Payment Method. Currently there are payment associated.';
	}
	else if($name == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}	
	else
	{
		if(storedModifiedLogs(tbl_payment_method, $id))
		{
			$sql = "UPDATE tbl_payment_method SET 
					name =".GetSQLValueString($name,"text").",
					publish =".GetSQLValueString($publish,"text").",				 
					date_modified = ".time() .",
					modified_by = ".USER_ID." 
					WHERE id = " .$id;
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_payment_methods\';</script>';
			}
		}
	}
}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		$sql_sched= "SELECT * FROM tbl_student_payment WHERE payment_method = " .$id;
		$qry_sched = mysql_query($sql_sched);
		$ctr = mysql_num_rows($qry_sched);
		
		if($ctr > 0 )
		{			
			$err_msg = 'Cannot Delete Payment Method. Currently there are payment associated.';
		}
		else
		{
			$sql = "DELETE FROM tbl_payment_method WHERE id=" .$item;
			mysql_query ($sql);
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
		storedModifiedLogs(tbl_building, $id);
		$sql = "UPDATE tbl_payment_method SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
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
			if(!checkPaymentMethodEditable($id))
			{
				$err_msg = 'Cannot Unpublish Payment Method. Currently there are payment associated.';
			}
			else
			{
				storedModifiedLogs(tbl_payment_method, $id);
				$sql = "UPDATE tbl_payment_method SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_payment_method where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	

	$name				= $row['name'] != $name ? $row['name'] : $name;
	$publish			= $row['publish'] != $publish ? $row['publish'] : $publish;


}
else if($view == 'add')
{
	
	$name				= $_REQUEST['name'];
	$publish			= $_REQUEST['publish'];


}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_payment_methods.php';
?>