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



$page_title = 'Manage Grade Sheet';
$pagination = 'Grade  > Manage Grade Sheet';

$view = $view==''?'list':$view; // initialize action

$id		= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];
$syId 	= $_REQUEST['syId'];

$label      	=  $_REQUEST['label'];
$percentage    	= $_REQUEST['percentage'];
$school_yr_period_id= $_REQUEST['school_yr_period_id'];

if($action == 'save')
{
	if($label == '' || $percentage == '' || $school_yr_period_id =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkTotalGradesheetPercentage($school_yr_period_id,$syId,$percentage)){
		$err_msg = 'Total Percentage reached 100%.';
	}
	else
	{
		$sql = "INSERT INTO tbl_gradesheet
				(
					school_yr_period_id,
					label,
					percentage,
					schedule_id,
					date_created,
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($school_yr_period_id,"text").",  
					".GetSQLValueString($label,"text").",  
					".GetSQLValueString($percentage,"int").", 
					".GetSQLValueString($syId,"int").",   	
					".time().",
					".USER_ID.",		
					".time().",
					".USER_ID."
				)";
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_pr_gradesheet\';</script>';
			}	
	}
}
else if($action == 'update')
{
	if($label == '' || $percentage == '' || $school_yr_period_id =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkTotalGradesheetPercentage($school_yr_period_id,$syId,$percentage,$id)){
		$err_msg = 'Total Percentage exceed 100%';
	}
	else if(checkCanEditGradesheet($id))
	{
		$err_msg ='Cannot Edit Grade Sheet . Currently there are Student Associated.';
	}
	else
	{
		if (storedModifiedLogs(tbl_gradesheet, $id)) {
			 $sql = "UPDATE tbl_gradesheet SET 
					label  =".GetSQLValueString($label,"text").",	
					percentage =".GetSQLValueString($percentage,"int").",
					date_modified = ".time() .",
					modified_by = ".USER_ID ."	
					WHERE id=" .$id;
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_pr_gradesheet\';</script>';
			}
		}	
		
	}
}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		if(checkCanEditGradesheet($id))
		{
			$err_msg ='Cannot Edit Grade Sheet . Currently there are Student Associated.';
		}
		else
		{
		$sql = "DELETE FROM tbl_gradesheet WHERE id=" .$item;
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
		storedModifiedLogs(tbl_school_year_period, $item);
		$sql = "UPDATE tbl_school_year_period SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
		
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		if(checkCanEditGradesheet($id))
		{
			$err_msg ='Cannot Edit Grade Sheet . Currently there are Student Associated.';
		}
		else
		{
			storedModifiedLogs(tbl_school_year_period, $item);
			$sql = "UPDATE tbl_school_year_period SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
			mysql_query ($sql);
		}
		
	}
}


if($view == 'edit')
{
	$sql_sec = "SELECT * FROM tbl_schedule WHERE id = $syId";
	$res_sec = mysql_query($sql_sec);
	$row_sec = mysql_fetch_array($res_sec);
	$schedule_id = $row_sec['section_no'];
	
	$sql = "SELECT * FROM tbl_gradesheet WHERE id = $id";
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);
	
	$label				= $row['label'] != $label ? $row['label'] : $label;
	$percentage			= $row['percentage'] != $percentage ? $row['percentage'] : $percentage;
	$school_yr_period_id= $row['school_yr_period_id'] != $school_yr_period_id ? $row['school_yr_period_id'] : $school_yr_period_id;

}
else if($view == 'period')
{
	$str_arr = array();
	$sql_sec = "SELECT * FROM tbl_schedule WHERE id = $syId";
	$res_sec = mysql_query($sql_sec);
	$row_sec = mysql_fetch_array($res_sec);
	$schedule_id = $row_sec['section_no'];
	
	$sql_sy = "SELECT * FROM tbl_school_year_period where term_id = ".$row_sec['term_id'];
	$query_sy = mysql_query ($sql_sy);
	
	if (mysql_num_rows($query_sy) > 0 )
        {
            $x = 1;
				while($row_sy = mysql_fetch_array($query_sy))
				{	
				 $sql = "SELECT * FROM tbl_gradesheet where school_yr_period_id = ".$row_sy['id']." AND schedule_id=".$syId;
				$query = mysql_query ($sql);
				//$ctr = 0;
				$dis = 0;
				while($row = mysql_fetch_array($query))
				{
					$dis++;
					$class= $x%2==0?"":"highlight";
					$str_arr[] = '<tr class="'.$class.'">';
					$str_arr[] = '<td ><input type="checkbox" name="id" id=id_'.$row['id'].' value='.$row['id'].' /></td>';
					
					if($dis == 1)
					{
						$str_arr[] = '<td class="col_150">'.$row_sy['period_name'].'</td>';
					}
					else
					{
						$str_arr[] = '<td class="col_150">&nbsp;</td>';
					}
					
					$str_arr[] = '<td class="col_150">'.$row['label'].'</td>';
					$str_arr[] = '<td class="col_350">'.$row['percentage'].'</td>';
					$str_arr[] = '<td class="col_150"><ul>
					<li><a class="edit" href="javascript:doTheAction (\'edit\', \'edit\', \'id_'.$row['id'].'\');" title="edit"></a></li>
					<li><a class="delete" href="#" onclick="javascript:lnk_deleteItem(\'id_'.$row['id'].'\');" title="delete"></a></li>
					</ul></td>';
					$str_arr[] = '</tr>';
				  $x++;
			  }
		}
	}
	else
	{
			  $str_arr[] = '<tr>';
				  $str_arr[] = '<td colspan="6" align="center">No gradesheet set-up found under this section.</td>';
			  $str_arr[] = '</tr>';
	}	
	$period_list = implode('',$str_arr);
}
else if($view == 'add_period')
{
	$sql_sec = "SELECT * FROM tbl_schedule WHERE id = $syId";
	$res_sec = mysql_query($sql_sec);
	$row_sec = mysql_fetch_array($res_sec);
	$schedule_id = $row_sec['section_no'];
	
	$label					= $_REQUEST['label'];
	$percentage				= $_REQUEST['percentage'];
	$school_yr_period_id	= $_REQUEST['school_yr_period_id'];	
	
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_pr_gradesheet.php';
?>