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


$page_title = 'Manage Room';
$pagination = 'Utility  > Manage Room';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$filter_field 	= $_REQUEST['filter_field'];
$filter_order 	= $_REQUEST['filter_order'];
$filteroom2 = $_REQUEST['filteroom2'];
$filterbuild2 = $_REQUEST['filterbuild2'];
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

$room_no		= $_REQUEST['room_no'];
$room_type		= $_REQUEST['room_type'];
$building_id	= $_REQUEST['building_id'];
$publish		= $_REQUEST['publish'];

// ROOM AVAILABILITY
$selected_room_id = $_REQUEST['selected_room_id']; 
$room_avail_day = $_REQUEST['chkday'];
$from_time 		= $_REQUEST['from_time'];
$to_time 		= $_REQUEST['to_time'];

$monday 		= $_REQUEST['M'];
$tuesday 		= $_REQUEST['T'];
$wednesday 		= $_REQUEST['W'];
$thursday 		= $_REQUEST['TH'];
$friday 		= $_REQUEST['F'];
$saturday 		= $_REQUEST['S'];
$sunday 		= $_REQUEST['SU'];


if($action == 'save')
{
	if($room_no == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfRoomNumberExist($room_no, $building_id))
	{
		$err_msg = 'Room number already exist in this building';
	}
	else
	{
		$sql = "INSERT INTO tbl_room 
				(
					room_no, 
					building_id,
					room_type, 
					publish,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($room_no,"text").",  
					".GetSQLValueString($building_id,"text").", 
					".GetSQLValueString($room_type,"text").",
					".GetSQLValueString($publish,"text").",  	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_room\';</script>';
		}
	}	
}
else if($action == 'update')
{		
	if(!checkRoomEditable($id))
	{			
		$err_msg ='Cannot edit '.getRoomNo($id).' Room. Currently there are schedule associated.';
	}
	else if($room_no == '' )
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfRoomNumberExist($room_no, $building_id, $id))
	{
		$err_msg = 'Room number already exist in this building';
	}
	else
	{
		if(storedModifiedLogs(tbl_room, $id))
		{
		$sql = "UPDATE tbl_room SET 
				room_no =".GetSQLValueString($room_no,"text").",
				building_id =".GetSQLValueString($building_id,"text").",				
				room_type =".GetSQLValueString($room_type,"text").",
				publish =".GetSQLValueString($publish,"text").",				 
				date_modified = ".time() ." ,
				modified_by = ".USER_ID."
				WHERE id=" .$id;
	
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_room\';</script>';
			}
		}
	}
}
else if($action == 'save_avail')
{

	$sql ="DELETE FROM tbl_room_availability WHERE room_id = " . $id;
	if(mysql_query ($sql))
	{
		$sql = "UPDATE tbl_room SET 
						has_custom_availability =".GetSQLValueString('Y',"text")."
						WHERE id=" .$id;			
		if(mysql_query ($sql))
		{
			if(count($monday) > 0)
			{
				foreach($monday as $day)
				{
					$sql = "INSERT INTO tbl_room_availability 
							(
								room_id, 
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
				$sql = "INSERT INTO tbl_room_availability 
						(
							room_id, 
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
				$sql = "INSERT INTO tbl_room_availability 
						(
							room_id, 
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
				$sql = "INSERT INTO tbl_room_availability 
						(
							room_id, 
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
				$sql = "INSERT INTO tbl_room_availability 
						(
							room_id, 
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
				$sql = "INSERT INTO tbl_room_availability 
						(
							room_id, 
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
				$sql = "INSERT INTO tbl_room_availability 
						(
							room_id, 
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
					
		echo '<script language="javascript">window.location =\'index.php?comp=com_room\';</script>';
	}

}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		if ($item != '')
		{
				$sql_sched= "SELECT * FROM tbl_schedule WHERE room_id = " .$item. " AND term_id=" .CURRENT_TERM_ID;
				$qry_sched = mysql_query($sql_sched);
				$ctr = mysql_num_rows($qry_sched);
			
			if($ctr > 0 )
			{			
				$err_msg ='Cannot delete '.getRoomNo($item).'. Currently there are schedule associated.';
			}
			else
			{
				$sql = "DELETE FROM tbl_room WHERE id=" .$item;
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
		storedModifiedLogs(tbl_room, $id);
		$sql = "UPDATE tbl_room SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
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
			if(!checkRoomEditable($id))
			{
				$err_msg = 'Cannot Unpublish '.getRoomNo($item).'. Currently there are schedule associated not in current term.';
			}
			else
			{
				storedModifiedLogs(tbl_room, $id);
				$sql = "UPDATE tbl_room SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
				mysql_query ($sql);
			}
		}
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_room where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	

	$room_no		= $row['room_no'] != $room_no ? $row['room_no'] : $room_no;
	$room_type		= $row['room_type'] != $room_type ? $row['room_type'] : $room_type;
	$building_id	= $row['building_id'] != $building_id ? $row['building_id'] : $building_id;
	$publish			= $row['publish'] != $publish ? $row['publish'] : $publish;


}
else if($view == 'availability')
{
	
	$sql = "SELECT * FROM tbl_room where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	


}
else if($view == 'add')
{
	
	$room_no		= $_REQUEST['room_no'];
	$room_type		= $_REQUEST['room_type'];
	$building_id	= $_REQUEST['building_id'];
	$publish		= $_REQUEST['publish'];


}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_room.php';
?>