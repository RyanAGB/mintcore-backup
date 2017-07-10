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





$page_title = 'System Settings Management';

$pagination = 'Settings  > System Settings Management';



$view = $view==''?'list':$view; // initialize action



$sql_settings_id = "SELECT * FROM tbl_system_settings";

$qry_settings_id = mysql_query($sql_settings_id);

$row_setting_id = mysql_fetch_array($qry_settings_id);



$row_setting_id['id'];



$table = 'tbl_system_settings';

$id	= $row_setting_id['id'];

$temp 	= $_REQUEST['temp'];





$activation_by			= $_REQUEST['activation_by'];

$max_login_attempt		= $_REQUEST['max_login_attempt'];

$total_failed_login		= $_REQUEST['total_failed_login'];

$time_to_relogin		= $_REQUEST['time_to_relogin'];

$password_min			= $_REQUEST['password_min'];

$password_max			= $_REQUEST['password_max'];

$password_complexity	= $_REQUEST['password_complexity'];

$allowed_sim_login		= $_REQUEST['allowed_sim_login'];

$enable_enrollment		= $_REQUEST['enable_enrollment'];

$set_system				= $_REQUEST['set_system'];

$default_record			= $_REQUEST['default_record'];

$notification			= $_REQUEST['notification'];



if($action == 'update')

{

	if($activation_by == '' || $max_login_attempt == '' || $total_failed_login =='' || $time_to_relogin == '' || $password_min == '' || $password_max =='' || $password_complexity == '' || $allowed_sim_login == '' || $enable_enrollment == '' || $set_system == ''|| $default_record == '')

	{

		$err_msg = 'Some of the required fields are missing.';

	}

	else

	{

		$_SESSION[CORE_U_CODE]['default_record'] = $default_record;

		$_SESSION[CORE_U_CODE]['system_settings']['notification'] = $notification;

		if(storedModifiedLogs($table, $id))

		{

			

			$sql = "UPDATE tbl_system_settings SET 

					activation_by =".GetSQLValueString($activation_by,"text").",

					max_login_attempt  =".GetSQLValueString($max_login_attempt,"int").",				

					total_failed_login =".GetSQLValueString($total_failed_login,"int").",

					time_to_relogin =".GetSQLValueString($time_to_relogin,"text").",	

					password_min =".GetSQLValueString($password_min,"int").",

					password_max =".GetSQLValueString($password_max,"int").",

					password_complexity =".GetSQLValueString($password_complexity,"text").",

					allowed_sim_login =".GetSQLValueString($allowed_sim_login,"text").",

					enable_enrollment =".GetSQLValueString($enable_enrollment,"text").",

					set_system =".GetSQLValueString($set_system,"text").",

					default_record =".GetSQLValueString($default_record,"int").",

					notification =".GetSQLValueString($notification,"text").",

					modified_by =".USER_ID.",			 

					date_modified = ".time()." 

					WHERE id=" .$id;

			if(mysql_query ($sql))

			{

				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_system_settings\';</script>';

			}

		}

	}

}



if($view == 'edit' || $view == 'list')

{

	$sql = "SELECT * FROM tbl_system_settings WHERE id = " . $id;

	$query = mysql_query ($sql);

	$row = mysql_fetch_array($query);

	



	$activation_by		= $row['activation_by'] != $activation_by ? $row['activation_by'] : $activation_by;

	$max_login_attempt		= $row['max_login_attempt'] != $max_login_attempt ? $row['max_login_attempt'] : $max_login_attempt;

	$total_failed_login		= $row['total_failed_login'] != $total_failed_login ? $row['total_failed_login'] : $total_failed_login;

	$time_to_relogin		= $row['time_to_relogin'] != $time_to_relogin ? $row['time_to_relogin'] : $time_to_relogin;

	$password_min			= $row['password_min'] != $password_min ? $row['password_min'] : $password_min;

	$password_max			= $row['password_max'] != $password_max ? $row['password_max'] : $password_max;

	$password_complexity	= $row['password_complexity'] != $password_complexity ? $row['password_complexity'] : $password_complexity;

	$allowed_sim_login		= $row['allowed_sim_login'] != $allowed_sim_login ? $row['allowed_sim_login'] : $allowed_sim_login;

	$enable_enrollment		= $row['enable_enrollment'] != $enable_enrollment ? $row['enable_enrollment'] : $enable_enrollment;

	$set_system				= $row['set_system'] != $set_system ? $row['set_system'] : $set_system;

	$default_record			= $row['default_record'] != $default_record ? $row['default_record'] : $default_record;

	$notification			= $row['notification'] != $notification ? $row['notification'] : $notification;

	



}

else if($view == 'add')

{

	

	$activation_by			= $_REQUEST['activation_by'];

	$max_login_attempt		= $_REQUEST['max_login_attempt'];

	$total_failed_login		= $_REQUEST['total_failed_login'];

	$time_to_relogin		= $_REQUEST['time_to_relogin'];

	$password_min			= $_REQUEST['password_min'];

	$password_max			= $_REQUEST['password_max'];

	$password_complexity	= $_REQUEST['password_complexity'];

	$allowed_sim_login		= $_REQUEST['allowed_sim_login'];

	$set_system				= $_REQUEST['set_system'];

	$default_record			= $_REQUEST['default_record'];

	$notification			= $_REQUEST['notification'];



}



// component block, will be included in the template page

$content_template = 'components/block/blk_com_system_settings.php';

?>