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

$page_title = 'Manage School Year';
$pagination = 'Utility  > Manage School Year';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];
$comp 	= $_REQUEST['comp'];

$start_year			= $_REQUEST['start_year'];
$end_year			= $_REQUEST['end_year'];	
$number_of_term		= $_REQUEST['number_of_term'];	
$number_of_period	= $_REQUEST['number_of_period'];	
$is_current_sy		= $_REQUEST['is_current_sy'];	


if($action == 'save')
{
	if($start_year == '' || $end_year == '' || $number_of_term == '' || $number_of_period == '' )
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if($start_year >= $end_year )
	{
		$err_msg = 'School year start date should not be less than or equal school year end date.';	
	}
	else if(checkIfSchoolYearExist($start_year,$end_year))
	{
		$err_msg = 'School year date already exist in the system.';		
	}
	else
	{
				
		 $sql = "INSERT INTO tbl_school_year
				(
					start_year, 
					end_year,
					number_of_term,
					number_of_period,
					is_current_sy,
					date_created,
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($start_year,"int").", 
					".GetSQLValueString($end_year,"int").", 
					".GetSQLValueString($number_of_term,"int").", 
					".GetSQLValueString($number_of_period,"int").",  
					".GetSQLValueString('N',"text").",
					".time().",
					".USER_ID.",		
					".time().",
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			$school_year_id = mysql_insert_id();
			$allTerm = ($number_of_term*1)+1;
			for($ctr=1;$ctr<=$allTerm;$ctr++)
			{
				if($ctr == $allTerm) $school_term =  'Summer';
				else if($ctr == 1) $school_term = 'First Term';
				else if($ctr == 2) $school_term =  'Second Term';
				else if($ctr == 3) $school_term =  'Third Term';
				else if($ctr == 4) $school_term =  'Fourth Term';		

				if($ctr == 1) $is_current = 'Y';
				else $is_current = 'N';

				$sql = "INSERT INTO tbl_school_year_term
						(
							school_year_id, 
							school_term,
							is_current
						) 
						VALUES 
						(
							".GetSQLValueString($school_year_id,"int").", 
							".GetSQLValueString($school_term,"text")." ,
							".GetSQLValueString($is_current,"text")." 
						)";
				
				mysql_query ($sql);
			}
			echo '<script language="javascript">alert("School year has been successfully Added!");window.location =\'index.php?comp=com_school_year\';</script>';
		}
	}	
}
else if($action == 'update')
{
	if($start_year == '' || $end_year == '' || $number_of_term == '' || $number_of_period == '' )
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if (checkIfSYIsAlreadyUsed($id))
	{
		$err_msg = 'Cannot edit this School Year. Record is already used in the system.';	
	}	
	else if(checkIfSchoolYearExist($start_year,$end_year,$id))
	{
		$err_msg = 'School year date already exist in the system.';		
	}
	else
	{
		if (storedModifiedLogs(tbl_school_year, $id)) {
		
			 $sql = "UPDATE tbl_school_year SET 
					start_year =".GetSQLValueString($start_year,"int").", 
					end_year =".GetSQLValueString($end_year,"int").", 
					number_of_term =".GetSQLValueString($number_of_term,"int").", 
					number_of_period =".GetSQLValueString($number_of_period,"int").",
					is_current_sy =".GetSQLValueString('N',"text").",
					date_modified = ".time() .",
					modified_by = ".USER_ID ."	
					WHERE id=" .$id;
				
			if(mysql_query ($sql))
			{
				$sql = "DELETE FROM tbl_school_year_term WHERE school_year_id = $id";
				
				mysql_query($sql);
				
				for($ctr=1;$ctr<=$number_of_term;$ctr++)
				{
					if($ctr == 1) $school_term = '1st Term';
					else if($ctr == 2) $school_term =  '2nd Term';
					else if($ctr == 3) $school_term =  '3rd Term';
					else if($ctr == 4) $school_term =  '4th Term';			

					if($ctr == 1) $is_current = 'Y';
					else $is_current = 'N';
				
					$sql = "INSERT INTO tbl_school_year_term
							(
								school_year_id, 
								school_term,
								is_current
							) 
							VALUES 
							(
								".GetSQLValueString($id,"int").", 
								".GetSQLValueString($school_term,"text")." ,
								".GetSQLValueString($is_current,"text")." 
							)";
					
					mysql_query ($sql);
				}			
				echo '<script language="javascript">alert("Successfully Updated the school year!");window.location =\'index.php?comp=com_school_year\';</script>';
			}
		}	
		
	}
}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	$str_arr = array();
	foreach($selected_item as $item)
	{	if(checkIfSchoolYearCanDoAction($item))
		{
			$sql = "DELETE FROM tbl_school_year WHERE id=" .$item;
			mysql_query ($sql);
			
			$sql = "DELETE FROM tbl_school_year_term WHERE school_year_id=" .$item;
			mysql_query ($sql);
		}
		else
		{
			$err_msg = "Cannot delete. System already using the School year " . getSchoolYearStartEnd($item) ;
		}
	}

	if(count($str_arr) > 0)
	{
		echo '<script language="javascript">alert("'.implode("\n",$str_arr).'");</script>';
	}

}
else if($action == 'publish')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		$sql_yr ="SELECT * FROM tbl_school_year_period 
				WHERE ('" . date("Y-m-d") . "' BETWEEN start_of_submission AND end_of_submission ) 				AND school_year_id = " . CURRENT_SY_ID;
		$qry_yr = mysql_query($sql_yr);
		$ctr = @mysql_num_rows($qry_yr);
		
		if($ctr > 0)
		{
			$err_msg = 'You cannot unset the current school. Submission of grades are still active.';
		}
		/*else if(!checkIfSYPeriodIsComplete($item))
		{
			$err_msg = 'Cannot set this School year. Please complete the setting of period per terms.'.checkIfSYPeriodIsComplete($item);
		}
		else if(checkStudentGradesIsComplete(CURRENT_TERM_ID))
		{
			//$err_msg = "Cannot set this School year. Current School Year contains students without grades. Complete Grades First.";
			$view = 'pub_grade';
		}*/
		else
		{
			$sql = "UPDATE tbl_school_year SET 
				is_current_sy =".GetSQLValueString('N',"text").",  
				date_modified = ".time() .",
				modified_by = ".USER_ID ." 
				";
			mysql_query ($sql);	
			
			storedModifiedLogs(tbl_school_year, $item);
			$sqlset = "UPDATE tbl_school_year SET is_current_sy = 'Y', date_modified = ".time() ." WHERE id=" .$item;
			mysql_query ($sqlset);
			$_SESSION[CORE_U_CODE]['current_sy_info']['current_sy_id'] = $item;
			
			echo '<script language="javascript">alert("Successfully Changed School Year. You need to Log-off to refresh the system.");window.location =\'index.php?comp=com_school_year\';</script>';
		}
		
	}
}


if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_school_year where id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);

	$start_year				= $row['start_year'] != $start_year ? $row['start_year'] : $start_year;
	$end_year				= $row['end_year'] != $end_year ? $row['end_year'] : $end_year;
	$number_of_term			= $row['number_of_term'] != $number_of_term ? $row['number_of_term'] : $number_of_term;
	$is_current_sy			= $row['is_current_sy'] != $is_current_sy ? $row['is_current_sy'] : $is_current_sy;
	$number_of_period		= $row['number_of_period'] != $number_of_period ? $row['number_of_period'] : $number_of_period;

}
else if($view == 'add')
{
	
	$current_period			= $_REQUEST['current_period'];
	$start_year				= $_REQUEST['start_year'];	
	$end_year				= $_REQUEST['end_year'];	
	$term					= $_REQUEST['term'];	
	$is_current_term		= $_REQUEST['is_current_term'];	
	$number_of_period		= $_REQUEST['number_of_period'];	
	$enable_enrollment		= $_REQUEST['enable_enrollment'];	
	$publish				= $_REQUEST['publish'];

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_school_year.php';
?>
