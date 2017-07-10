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


$page_title = 'Manage Access Role';
$pagination = 'User  > Manage Access Role';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$access_name		= $_REQUEST['access_name'];

$view_access		= $_REQUEST['view_access'];
$add_access		= $_REQUEST['add_access'];
$dash		= $_REQUEST['dash_'];
$comp_item  = $_REQUEST['comp_item'];

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
	if($access_name == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{
		$sql = "INSERT INTO tbl_access 
				(
					access_name, 
					access_type,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($access_name,"text").",  
					".GetSQLValueString('C',"text").", 
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			$_SESSION[CORE_U_CODE]['can_edit_comp'] = array('');
			$access_id = mysql_insert_id();
			$ctr = 0 ;
			foreach($comp_item as $item)
			{
				$view_access_item = in_array($item,$view_access)?'Y':'N';
				$add_access_item = in_array($item,$add_access)?'Y':'N';
				$dash_access_item = in_array($item,$dash)?'Y':'N';
				
				if($add_access_item == 'N')
					{
					   $_SESSION[CORE_U_CODE]['can_edit_comp'][] = getCanEditComponentName($item);
					}
				//
				$sql = "INSERT INTO tbl_access_role 
						( 
							access_id,
							component_id,
							can_view,
							can_edit, 
							can_add,
							show_dashboard
						) 
						VALUES 
						(
							".GetSQLValueString($access_id,"text").", 
							".GetSQLValueString($item,"text").", 
							".GetSQLValueString($view_access_item,"text").", 
							".GetSQLValueString($add_access_item,"text").", 
							".GetSQLValueString($add_access_item,"text").",
							".GetSQLValueString($dash_access_item,"text")."
						)";
						
				mysql_query ($sql);	
				$ctr ++;
			}		
			echo '<script language="javascript">alert("Successfully added the access role!");window.location =\'index.php?comp=com_access_role\';</script>';
		}
	}	
}
else if($action == 'update')
{
	
	if($access_name == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{
		if(storedModifiedLogs(tbl_access, $id))
		{
			$sql = "UPDATE tbl_access SET 
					access_name =".GetSQLValueString($access_name,"text").",
					access_type  =".GetSQLValueString('C',"text").",				
					date_modified = ".time() .",
					modified_by = ".USER_ID." 
					WHERE id = " .$id;
				
			if(mysql_query ($sql))
			{
				$_SESSION[CORE_U_CODE]['can_edit_comp'] = array();
				$sql = "DELETE FROM tbl_access_role WHERE access_id = " . $id;
				$query = mysql_query($sql);

				$ctr = 0 ;
				foreach($comp_item as $item)
				{					
					$view_access_item = in_array($item,$view_access)?'Y':'N';
					$add_access_item = in_array($item,$add_access)?'Y':'N';
					$dash_access_item = in_array($item,$dash)?'Y':'N';
					
					if($add_access_item == 'N')
					{
					  $_SESSION[CORE_U_CODE]['can_edit_comp'][] = getCanEditComponentName($item);
					}
					
					 $sql = "INSERT INTO tbl_access_role 
							( 
								access_id,
								component_id,
								can_view,
								can_edit, 
								can_add,
								show_dashboard
							) 
							VALUES 
							(
								".GetSQLValueString($id,"text").", 
								".GetSQLValueString($item,"text").", 
								".GetSQLValueString($view_access_item,"text").", 
								".GetSQLValueString($add_access_item,"text").", 
								".GetSQLValueString($add_access_item,"text").",
								".GetSQLValueString($dash_access_item,"text")."
							)";
							
					mysql_query ($sql);	
					$ctr ++;
				}	
										
				echo '<script language="javascript">alert("Successfully updated the access role!");window.location =\'index.php?comp=com_access_role\';</script>';
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
			$sql = "DELETE FROM tbl_access WHERE id=" .$item;
			mysql_query ($sql);
			
			$sql = "DELETE FROM tbl_access_role WHERE access_id = " . $item;
			$query = mysql_query($sql);
		}
	}

	if(count($arr_str) > 0)
	{
		echo '<script language="javascript">alert("'.implode("\n",$arr_str).'");</script>';
	}

}


if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_access where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);

	$access_name		= $row['access_name'] != $access_name ? $row['access_name'] : $access_name;


}
else if($view == 'add')
{
	$access_name		= $_REQUEST['access_name'];
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_access_role.php';
?>