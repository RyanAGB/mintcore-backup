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


$page_title = 'Block / Unblock Access';
$pagination = 'Users > Manage Employee > Block / Unblock Access';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

if($action == 'publish')
{
	$selected_item = explode(',',$temp);

	foreach($selected_item as $item)
	{
		$sql_emp = "SELECT * FROM tbl_employee WHERE id=" .$item;
		$qry_emp = mysql_query($sql_emp);
		$row_emp = mysql_fetch_array($qry_emp);
	
		storedModifiedLogs(tbl_employee, $id);
		$sql = "UPDATE tbl_user SET blocked = '1' WHERE id=" .$row_emp['user_id'];
		if(mysql_query ($sql))
		{
			$sql = "UPDATE tbl_employee SET date_modified = ".time() ." WHERE id=" .$item;
			mysql_query ($sql);
		}
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{	
		$sql_emp = "SELECT * FROM tbl_employee WHERE id=" .$item;
		$qry_emp = mysql_query($sql_emp);
		$row_emp = mysql_fetch_array($qry_emp);
		
		storedModifiedLogs(tbl_employee, $id);
		$sql = "UPDATE tbl_user SET blocked = '0' WHERE id=" .$row_emp['user_id'];
		if(mysql_query ($sql))
		{
			$sql = "UPDATE tbl_employee SET date_modified = ".time() ." WHERE id=" .$item;
			mysql_query ($sql);
		}
	}
}


// component block, will be included in the template page
$content_template = 'components/block/blk_com_user_employee_access.php';
?>