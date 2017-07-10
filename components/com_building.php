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


$page_title = 'Manage Building';
$pagination = 'Utility  > Manage Building';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];
$comp 	= $_REQUEST['comp'];

$building_code		= $_REQUEST['building_code'];
$building_name		= $_REQUEST['building_name'];
$address			= $_REQUEST['address'];
$publish			= $_REQUEST['publish'];

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

if($action == 'save')
{
	if($building_code == '' || $building_name == '' || $address =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfBuildingExist($building_code))
	{
		$err_msg = 'Building code already exist.';
	}
	else if(checkIfBuildingNameExist($building_name))
	{
		$err_msg = 'Building name already exist.';
	}
	else
	{
		$sql = "INSERT INTO tbl_building 
				(
					building_code, 
					building_name,
					address, 
					publish,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($building_code,"text").",  
					".GetSQLValueString($building_name,"text").", 
					".GetSQLValueString($address,"text").",
					".GetSQLValueString($publish,"text").",  	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_building\';</script>';
		}
	}	
}
else if($action == 'update')
{
	if(!checkBuildingEditable($id))
	{				
		$err_msg ='Cannot edit '.getBuildingName($id).' Building. Currently there are room associated.';
	}
	else if($building_code == '' || $building_name == '' || $address =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfBuildingExist($building_code,$id))
	{
		$err_msg = 'Building code already exist.';
	}	
	else if(checkIfBuildingNameExist($building_name,$id))
	{
		$err_msg = 'Building name already exist.';
	}
	else
	{
		if(storedModifiedLogs(tbl_building, $id))
		{
			$sql = "UPDATE tbl_building SET 
					building_code =".GetSQLValueString($building_code,"text").",
					building_name  =".GetSQLValueString($building_name,"text").",				
					address =".GetSQLValueString($address,"text").",
					publish =".GetSQLValueString($publish,"text").",				 
					date_modified = ".time() .",
					modified_by = ".USER_ID." 
					WHERE id = " .$id;
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_building\';</script>';
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
			$sql_room= "SELECT * FROM tbl_room WHERE building_id =" .$item;
			$qry_room = mysql_query($sql_room);
			$ctr = mysql_num_rows($qry_room);

			if($ctr > 0 )
			{				
				$err_msg ='Cannot delete '.getBuildingName($item).'. Currently there are room associated.';
			}
			else
			{
				$sql = "DELETE FROM tbl_building WHERE id=" .$item;
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
		storedModifiedLogs(tbl_building, $id);
		$sql = "UPDATE tbl_building SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
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
			if(!checkBuildingEditable($id))
			{
				$err_msg = 'Cannot Unpublish '.getBuildingName($item).'. Currently there are schedule associated.';
			}
			else
			{
				storedModifiedLogs(tbl_building, $id);
				$sql = "UPDATE tbl_building SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_building where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	

	$building_code		= $row['building_code'] != $building_code ? $row['building_code'] : $building_code;
	$building_name		= $row['building_name'] != $building_name ? $row['building_name'] : $building_name;
	$address			= $row['address'] != $address ? $row['address'] : $address;
	$publish			= $row['publish'] != $publish ? $row['publish'] : $publish;


}
else if($view == 'add')
{
	
	$building_code		= $_REQUEST['building_code'];
	$building_name		= $_REQUEST['building_name'];
	$address			= $_REQUEST['address'];
	$publish			= $_REQUEST['publish'];

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_building.php';
?>