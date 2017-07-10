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


$page_title = 'School Details Management';
$pagination = 'School Settings  > School Details Management';

$view = $view==''?'list':$view; // initialize action

$sql_settings_id = "SELECT * FROM tbl_school_settings";
$qry_settings_id = mysql_query($sql_settings_id);
$row_setting_id = mysql_fetch_array($qry_settings_id);

$row_setting_id['id'];

$table = 'tbl_school_settings';
$id	= $row_setting_id['id'];
$temp 	= $_REQUEST['temp'];


$school_name		= $_REQUEST['school_name'];
$school_address		= $_REQUEST['school_address'];
$school_city		= $_REQUEST['school_city'];
$school_postal		= $_REQUEST['school_postal'];
$school_tel			= $_REQUEST['school_tel'];
$school_fax			= $_REQUEST['school_fax'];

$school_logo		= $_REQUEST['school_logo'];


if(isset($_FILES['school_logo']['tmp_name']) && $_FILES['school_logo']['tmp_name'] != '')
{
	
	$imageSize 		= getimagesize($_FILES['school_logo']['tmp_name']);	
	$width 			= $imageSize[0];
	$height 		= $imageSize[1];
	$imgData 		= addslashes(file_get_contents($_FILES['school_logo']['tmp_name']));

}
$school_sys_url		= $_REQUEST['school_sys_url'];
$school_sys_email	= $_REQUEST['school_sys_email'];
$school_open_time	= $_REQUEST['school_open_time'];
$school_close_time	= $_REQUEST['school_close_time'];


if($action == 'update')
{
	if($school_name == '' || $school_address == '' || $school_city =='' || $school_postal == '' || $school_tel == '' || $school_fax =='' || $school_sys_email == '' || $school_sys_url == '' || $school_open_time == '' || $school_close_time == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if($height > '55')
	{
		$err_msg = 'Error in Height. The required size is 550x55';
	}
	else if($width > '550')
	{
		$err_msg = 'Error in Width. The required size is 550x55';
	}
	else
	{
	
		if(storedModifiedLogs($table, $id))
		{
			if($_FILES['school_logo']['tmp_name'] !='')
			{
				$str_updates = "school_logo ='".$imgData."', ";
			}
			
			$sql = "UPDATE tbl_school_settings SET 
					school_name =".GetSQLValueString($school_name,"text").",
					school_address  =".GetSQLValueString($school_address,"text").",				
					school_city =".GetSQLValueString($school_city,"text").",
					school_postal =".GetSQLValueString($school_postal,"text").",	
					school_tel =".GetSQLValueString($school_tel,"text").",
					school_fax =".GetSQLValueString($school_fax,"text").",
					".$str_updates ."
					school_sys_url =".GetSQLValueString($school_sys_url,"text").",
					school_sys_email =".GetSQLValueString($school_sys_email,"text").",
					school_open_time =".GetSQLValueString($school_open_time,"text").",
					school_close_time =".GetSQLValueString($school_close_time,"text").",
					modified_by =".USER_ID.",			 
					date_modified = ".time() ." 
					WHERE id=" .$id;	
					
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully updated the school details.\nYou need to logout to reflect the changes.");window.location =\'index.php?comp=com_school_details\';</script>';
			}
			
		}
	}
}

if($view == 'edit' || $view == 'list')
{
	$sql = "SELECT * FROM tbl_school_settings WHERE id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);

	$school_name		= $row['school_name'] != $school_name ? $row['school_name'] : $school_name;
	$school_address		= $row['school_address'] != $school_address ? $row['school_address'] : $school_address;
	$school_city		= $row['school_city'] != $school_city ? $row['school_city'] : $school_city;
	$school_postal		= $row['school_postal'] != $school_postal ? $row['school_postal'] : $school_postal;
	$school_tel			= $row['school_tel'] != $school_tel ? $row['school_tel'] : $school_tel;
	$school_fax			= $row['school_fax'] != $school_fax ? $row['school_fax'] : $school_fax;
	$school_logo		= $row['school_logo'] != $school_logo ? $row['school_logo'] : $school_logo;
	$school_sys_url		= $row['school_sys_url'] != $school_sys_url ? $row['school_sys_url'] : $school_sys_url;
	$school_sys_email	= $row['school_sys_email'] != $school_sys_email ? $row['school_sys_email'] : $school_sys_email;
	$school_open_time	= $row['school_open_time'] != $school_open_time ? $row['school_open_time'] : $school_open_time;
	$school_close_time	= $row['school_close_time'] != $school_close_time ? $row['school_close_time'] : $school_close_time;

}
else if($view == 'add')
{
	
	$school_name		= $_REQUEST['school_name'];
	$school_address		= $_REQUEST['school_address'];
	$school_city		= $_REQUEST['school_city'];
	$school_postal		= $_REQUEST['school_postal'];
	$school_tel			= $_REQUEST['school_tel'];
	$school_fax			= $_REQUEST['school_fax'];
	$school_logo		= $_REQUEST['school_logo'];
	$school_sys_url		= $_REQUEST['school_sys_url'];
	$school_sys_email	= $_REQUEST['school_sys_email'];
	$school_open_time	= $_REQUEST['school_open_time'];
	$school_close_time	= $_REQUEST['school_close_time'];

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_school_details.php';
?>