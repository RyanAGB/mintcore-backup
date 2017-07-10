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

$page_title = 'Manage School Terms';
$pagination = 'School Setup  > Manage School Terms';

$view = $view==''?'list':$view; // initialize action

$id		= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];
$syId 	= $_REQUEST['syId'];
$termId = $_REQUEST['termId'];
$period_id = $_REQUEST['period_id'];	

$period_name			= $_REQUEST['period_name'];
$percentage				= $_REQUEST['percentage'];	
$period_order			= $_REQUEST['period_order'];	
$is_current				= $_REQUEST['is_current'];

$start_of_sub_year      = $_REQUEST['start_of_sub_year'];
$start_of_sub_month     = $_REQUEST['start_of_sub_month'];
$start_of_sub_day       = $_REQUEST['start_of_sub_day'];

$start_of_sub			= array($start_of_sub_year, $start_of_sub_month, $start_of_sub_day);
$start_of_submission	= implode("-", $start_of_sub);

$end_of_sub_year      	= $_REQUEST['end_of_sub_year'];
$end_of_sub_month     	= $_REQUEST['end_of_sub_month'];
$end_of_sub_day       	= $_REQUEST['end_of_sub_day'];

$end_of_sub		 		= array($end_of_sub_year, $end_of_sub_month, $end_of_sub_day);
$end_of_submission		= implode("-", $end_of_sub);

if($action == 'save')
{

	if($period_name == '' || $percentage == '' || $period_order == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (checkPeriodNameExist($period_name, $start_of_submission, $end_of_submission, $syId, $termId,  $id))
	{
		$err_msg = 'Period already exist.';
	}
	else if (checkTotalPeriodPercentage($syId,$termId,$percentage))
	{
		$err_msg = 'Percentage exceeds 100%.';
	}
	else
	{
		$sql = "INSERT INTO tbl_school_year_period
				(
					school_year_id,
					term_id,
					period_name, 
					start_of_submission, 
					end_of_submission,
					percentage,
					period_order,
					is_current,
					date_created,
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($syId,"text").",  
					".GetSQLValueString($termId,"text").",  
					".GetSQLValueString($period_name,"text").",  
					".GetSQLValueString($start_of_submission,"text").", 
					".GetSQLValueString($end_of_submission,"text").", 
					".GetSQLValueString($percentage,"int").", 
					".GetSQLValueString($period_order,"int").", 
					".GetSQLValueString($is_current,"text").",  	
					".time().",
					".USER_ID.",		
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_school_terms\';</script>';
		}
	}	
}
else if($action == 'update')
{
	if($period_name == '' || $percentage == '' || $period_order == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if ($start_of_submission > $end_of_submission)
	{
		$err_msg = 'Start of submission date should not be less than or equal to End of submission date.';
	}
	else if (checkPeriodNameExist($period_name, $start_of_submission, $end_of_submission, $syId, $termId,  $id))
	{
		$err_msg = 'Period already exist.';
	}
	else if (checkTotalPeriodPercentage($syId,$termId,$percentage,$period_id))
	{
		$err_msg = 'Percentage exceeds 100%.';
	}
	else
	{
		if (storedModifiedLogs(tbl_school_year_period, $period_id)) {
			 $sql = "UPDATE tbl_school_year_period SET 
					period_name  =".GetSQLValueString($period_name,"text").",	
					start_of_submission =".GetSQLValueString($start_of_submission,"text").", 
					end_of_submission =".GetSQLValueString($end_of_submission,"text").",	
					percentage =".GetSQLValueString($percentage,"int").",
					period_order =".GetSQLValueString($period_order,"int").", 
					is_current =".GetSQLValueString($is_current,"text").",				 
					date_modified = ".time() .",
					modified_by = ".USER_ID ."	
					WHERE id=" .$period_id;
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_school_terms\';</script>';
			}
		}	
		
	}
}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		$sql = "DELETE FROM tbl_school_year_period WHERE id=" .$item;
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
		if(checkStudentGradesIsComplete(CURRENT_TERM_ID))
		{
			//$err_msg = "Cannot set this Term. Current Term contains students without grades. Complete Grades First.";
			$view = 'pub_grade';
		}
        else
        {

		$sql = "UPDATE tbl_school_year_term SET 
			is_current =".GetSQLValueString('N',"text");
		mysql_query ($sql);	

		
		storedModifiedLogs(tbl_school_year_term, $item);
		$sql = "UPDATE tbl_school_year_term SET is_current = 'Y' WHERE id=" .$item;
		mysql_query ($sql);
		
		echo '<script language="javascript">alert("Successfully Changed Term. You need to Log-off to refresh the system.");window.location =\'index.php?comp=com_school_terms\';</script>';
		}
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		$sql_unset_syr = "SELECT * FROM tbl_school_year_term WHERE is_current = 'Y' AND id =" .$item;
		$qry_unset_syr = mysql_query($sql_unset_syr);
		$ctr_unset_syr = mysql_num_rows($qry_unset_syr);
		
		if($ctr_unset_syr > 0)
		{
			$err_msg = 'Cannot unset SY: '.getSYandTerm($item).'.';
		}
		else
		{
			storedModifiedLogs(tbl_school_year_term, $item);
			$sql = "UPDATE tbl_school_year_term SET is_current = 'N' WHERE id=" .$item;
			mysql_query ($sql);
		}
		
	}
}

else if($action == 'set')
{
	$sql = "UPDATE tbl_school_year_period SET 
		is_current =".GetSQLValueString('N',"text");
	mysql_query ($sql);	
	
	storedModifiedLogs(tbl_school_year_period, $id);
	$sql = "UPDATE tbl_school_year_period SET is_current = 'Y' WHERE id=" .$id;
	mysql_query ($sql);

}
else if($action == 'unset')
{
	
	$sql_unset_period = "SELECT * FROM tbl_school_year_period WHERE is_current = 'Y' AND id =" .$id;
	$qry_unset_period = mysql_query($sql_unset_period);
	$ctr_unset_period = mysql_num_rows($qry_unset_period);
	
	if($ctr_unset_period > 0)
	{
		$err_msg = 'Cannot unset period: '.getPeriodName($id).'.';
	}
	else
	{
		storedModifiedLogs(tbl_school_year_period, $id);
		$sql = "UPDATE tbl_school_year_period SET is_current = 'N' WHERE id=" .$id;
		mysql_query ($sql);
	}

}


if($view == 'edit')
{
	
	$sql = "SELECT * FROM tbl_school_year_period where id = $period_id";
	$query = mysql_query ($sql);
	$ctr = mysql_num_rows($query);
	$row = mysql_fetch_array ($query);
	
	$start_day = explode("-", $row["start_of_submission"]);
	$start_of_sub_year = $start_day[0];
	$start_of_sub_month = $start_day[1];
	$start_of_sub_day = $start_day[2];
	$end_day = explode("-", $row["end_of_submission"]);
	$end_of_sub_year = $end_day[0];
	$end_of_sub_month = $end_day[1];
	$end_of_sub_day = $end_day[2];
	
	$start_of_sub			= array($start_of_sub_year, $start_of_sub_month, $start_of_sub_day);
	$start_of_submission	= implode("-", $start_of_sub);

	$end_of_sub		 		= array($end_of_sub_year, $end_of_sub_month, $end_of_sub_day);
	$end_of_submission		= implode("-", $end_of_sub);

	
	$period_name			= $row['period_name'] != $period_name ? $row['period_name'] : $period_name;
	$start_of_submission	= $row['start_of_submission'] != $start_of_submission ? $row['start_of_submission'] : $start_of_submission;
	$end_of_submission		= $row['end_of_submission'] != $end_of_submission ? $row['end_of_submission'] : $end_of_submission;
	$percentage				= $row['percentage'] != $percentage ? $row['percentage'] : $percentage;
	$period_order			= $row['period_order'] != $period_order ? $row['period_order'] : $period_order;
	$is_current				= $row['is_current'] != $is_current ? $row['is_current'] : $is_current;

}
else if($view == 'period')
{
	$str_arr = array();
	
	$sql_sy = "SELECT * FROM tbl_school_year where id = $syId";
	$query_sy = mysql_query ($sql_sy);
	$row_sy = mysql_fetch_array ($query_sy);	
	
	$start_year = $row_sy['start_year'];
	$end_year = $row_sy['end_year'];	
	
	$sql = "SELECT * FROM tbl_school_year_period where school_year_id = $syId AND term_id = $termId";
	$query = mysql_query ($sql);
	$ctr = mysql_num_rows($query);
	if($ctr > 0)
	{
		while($row = mysql_fetch_array($query))
		{
			  $str_arr[] = '<tr>';
				  $str_arr[] = '<td class="col_200">'.$row['period_name'].'</td>';
				  $str_arr[] = '<td class="col_350">'.$row['start_of_submission'].'</td>';
				  $str_arr[] = '<td class="col_350">'.$row['end_of_submission'].'</td>';
				  $str_arr[] = '<td class="col_350">'.$row['percentage'].'</td>';
				  
				  $str_arr[] = '<td class="col_150">
								<ul>
								<li>';
								if($row['is_current']=='Y')
								{

				  $str_arr[] = '<a class="checkmark" href="#" returnMarkId="'.$row['id'].'" title="click to unset period"></a>';
								}
								else
								{
								
				  $str_arr[] = '<a class="xmark" href="#" returnMarkId="'.$row['id'].'" title="click to set period"></a>';
								
								}
								
				  $str_arr[] =	'</li>
								</ul>
								</td>';
								
				  $str_arr[] = '<td class="col_150">
									<ul>
									
										<li><a class="edit" returnId="'.$row['id'].'"></a></li>
										<li><a class="delete" href="#" onclick="javascript:lnk_deleteItem(\'id_'.$row['id'].'\');" title="delete"></a></li>
									</ul>
								</td>';
			  $str_arr[] = '</tr>';
		}
	}
	else
	{
			  $str_arr[] = '<tr>';
				  $str_arr[] = '<td colspan="6" align="center">No period found under this school year term.</td>';
			  $str_arr[] = '</tr>';
	}	
	$period_list = implode('',$str_arr);
}
else if($view == 'add_period')
{
	
	$str_arr = array();
	
	$sql = "SELECT * FROM tbl_school_year where id = $syId";
	$query = mysql_query ($sql);
	$row = mysql_fetch_array ($query);
	
	$number_of_period = $row['number_of_period'];
	$start_year = $row['start_year'];
	$end_year = $row['end_year'];		
	
	$sql = "SELECT * FROM tbl_school_year_period where school_year_id = $syId AND term_id = $termId";
	$query = mysql_query ($sql);
	$ctr = mysql_num_rows($query);
	
	
	
	$period_name			= $_REQUEST['period_name'];
	$percentage				= $_REQUEST['percentage'];	
	$period_order			= $_REQUEST['period_order'];	
	$publish				= $_REQUEST['publish'];

	$start_of_sub_year      = $_REQUEST['start_of_sub_year'];
	$start_of_sub_month     = $_REQUEST['start_of_sub_month'];
	$start_of_sub_day       = $_REQUEST['start_of_sub_day'];

	$start_of_sub			= array($start_of_sub_year, $start_of_sub_month, $start_of_sub_day);
	$start_of_submission	= implode("-", $start_of_sub);

	$end_of_sub_year      	= $_REQUEST['end_of_sub_year'];
	$end_of_sub_month     	= $_REQUEST['end_of_sub_month'];
	$end_of_sub_day       	= $_REQUEST['end_of_sub_day'];

	$end_of_sub		 		= array($end_of_sub_year, $end_of_sub_month, $end_of_sub_day);
	$end_of_submission		= implode("-", $end_of_sub);
	
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_school_terms.php';
?>

