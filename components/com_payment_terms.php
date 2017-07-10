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

if(USER_IS_LOGGED != '1')
{
	echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main
}


$page_title = 'Manage Payment Terms';
$pagination = 'Billing  > Payment Terms';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$name				= $_REQUEST['name'];
$publish			= $_REQUEST['publish'];


if($action == 'save')
{
	if($name == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{
		$sql = "INSERT INTO tbl_payment_term 
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
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_payment_terms\';</script>';
		}
	}	
}
else if($action == 'update')
{
	if($name == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else
	{
		if(storedModifiedLogs(tbl_payment_term, $id))
		{
			$sql = "UPDATE tbl_payment_term SET 
					name =".GetSQLValueString($name,"text").",
					publish =".GetSQLValueString($publish,"text").",				 
					date_modified = ".time() .",
					modified_by = ".USER_ID." 
					WHERE id = " .$id;
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_payment_terms\';</script>';
			}
		}
	}
}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		$sql = "DELETE FROM tbl_payment_term WHERE id=" .$item;
		mysql_query ($sql);
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
		$sql = "UPDATE tbl_payment_term SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		storedModifiedLogs(tbl_building, $id);
		$sql = "UPDATE tbl_payment_term SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
	}
}



if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_payment_term where id = " . $id;
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
$content_template = 'components/block/blk_com_payment_terms.php';
?>