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



$page_title = 'Professor Availability';
$pagination = 'Professor > Professor Availability';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

// PROFESSOR AVAILABILITY

$monday 		= $_REQUEST['M'];
$tuesday 		= $_REQUEST['T'];
$wednesday 		= $_REQUEST['W'];
$thursday 		= $_REQUEST['TH'];
$friday 		= $_REQUEST['F'];
$saturday 		= $_REQUEST['S'];
$sunday 		= $_REQUEST['SU'];
$filter_dept	= $_REQUEST['filter_dept'];
$filter_field = $_REQUEST['filter_field'];
$filter_order = $_REQUEST['filter_order'];
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

if($action == 'save_avail')
{

	$sql ="DELETE FROM tbl_employee_availability WHERE employee_id = " . $id;
	if(mysql_query ($sql))
	{
		$sql = "UPDATE tbl_employee SET 
						has_custom_availability =".GetSQLValueString('Y',"text")."
						WHERE id=" .$id;			
		if(mysql_query ($sql))
		{
			if(count($monday) > 0)
			{
				foreach($monday as $day)
				{
					$sql = "INSERT INTO tbl_employee_availability 
							(
								employee_id, 
								day_available,
								from_time, 
								to_time
							) 
							VALUES 
							(
								".GetSQLValueString($id,"int").",  
								".GetSQLValueString('M',"text").", 
								".GetSQLValueString($day,"text").", 
								".GetSQLValueString(incrementFormatedTime($day),"text")." 	
							)";
					mysql_query ($sql);
				}
			}
		}
		
		if(count($tuesday) > 0)
		{
			foreach($tuesday as $day)
			{
				$sql = "INSERT INTO tbl_employee_availability 
						(
							employee_id, 
							day_available,
							from_time, 
							to_time
						) 
						VALUES 
						(
							".GetSQLValueString($id,"int").",  
							".GetSQLValueString('T',"text").", 
							".GetSQLValueString($day,"text").", 
							".GetSQLValueString(incrementFormatedTime($day),"text")." 	
						)";
				mysql_query ($sql);
			}
		}
		
		if(count($wednesday) > 0)
		{
			foreach($wednesday as $day)
			{
				$sql = "INSERT INTO tbl_employee_availability 
						(
							employee_id, 
							day_available,
							from_time, 
							to_time
						) 
						VALUES 
						(
							".GetSQLValueString($id,"int").",  
							".GetSQLValueString('W',"text").", 
							".GetSQLValueString($day,"text").", 
							".GetSQLValueString(incrementFormatedTime($day),"text")." 	
						)";
				mysql_query ($sql);
			}
		}
		
		if(count($thursday) > 0)
		{
			foreach($thursday as $day)
			{
				$sql = "INSERT INTO tbl_employee_availability 
						(
							employee_id, 
							day_available,
							from_time, 
							to_time
						) 
						VALUES 
						(
							".GetSQLValueString($id,"int").",  
							".GetSQLValueString('TH',"text").", 
							".GetSQLValueString($day,"text").", 
							".GetSQLValueString(incrementFormatedTime($day),"text")." 	
						)";
				mysql_query ($sql);
			}
		}
		
		if(count($friday) > 0)
		{
			foreach($friday as $day)
			{
				$sql = "INSERT INTO tbl_employee_availability 
						(
							employee_id, 
							day_available,
							from_time, 
							to_time
						) 
						VALUES 
						(
							".GetSQLValueString($id,"int").",  
							".GetSQLValueString('F',"text").", 
							".GetSQLValueString($day,"text").", 
							".GetSQLValueString(incrementFormatedTime($day),"text")." 	
						)";
				mysql_query ($sql);
			}	
		}
		if(count($saturday) > 0)
		{
			foreach($saturday as $day)
			{
				$sql = "INSERT INTO tbl_employee_availability 
						(
							employee_id, 
							day_available,
							from_time, 
							to_time
						) 
						VALUES 
						(
							".GetSQLValueString($id,"int").",  
							".GetSQLValueString('S',"text").", 
							".GetSQLValueString($day,"text").", 
							".GetSQLValueString(incrementFormatedTime($day),"text")." 	
						)";
				mysql_query ($sql);
			}	
		}
		
		if(count($sunday) > 0)
		{
			foreach($sunday as $day)
			{
				$sql = "INSERT INTO tbl_employee_availability 
						(
							employee_id, 
							day_available,
							from_time, 
							to_time
						) 
						VALUES 
						(
							".GetSQLValueString($id,"int").",  
							".GetSQLValueString('SU',"text").", 
							".GetSQLValueString($day,"text").", 
							".GetSQLValueString(incrementFormatedTime($day),"text")." 	
						)";
				mysql_query ($sql);
			}	
		}
					
		echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_professor_availability\';</script>';
	}

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_professor_availability.php';
?>