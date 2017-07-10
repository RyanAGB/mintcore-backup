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


	include_once("../config.php");
	//include_once('../includes/common.php');
	include_once('../includes/functions.php');


$mod = $_REQUEST['mod'];
$id = $_REQUEST['id'];
$str_arr = array();
if($mod == 'updateCurriculum')
{
	
	
	if($id != '')
	{
		$sql = "SELECT number_year FROM tbl_curriculum WHERE id = $id";						
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$years=$row['number_year'];
		
		$str_arr[] = '<option value="" selected="selected">Select Year</option>';
		for($x=1; $x<=$years; $x++) {
			if($years == $id)
			{
				$selected = 'selected="selected"';
			}
			else
			{
				$selected = '';
			}
			
			$str_arr[] = '<option value="'.$x.'" '.$selected.' >'.$x.'</option>';
		}
	}
	else
	{
		$str_arr[] = '<option value="" selected="selected">Select Year</option>';	
	}
	echo implode('',$str_arr);
	
}

if($mod == 'updateTerm')
{
	
	
	if($id != '')
	{
		$sql = "SELECT termyear FROM tbl_curriculum WHERE id = $id";						
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$years=$row['termyear'];
		
		$str_arr[] = '<option value="" selected="selected">Select Term</option>';
		for($x=1; $x<=$years; $x++) {
			if($years == $id)
			{
				$selected = 'selected="selected"';
			}
			else
			{
				$selected = '';
			}
			
			$str_arr[] = '<option value="'.$x.'" '.$selected.' >'.$x.'</option>';
		}
	}
	else
	{
		$str_arr[] = '<option value="" selected="selected">Select Term</option>';	
	}
	echo implode('',$str_arr);
	
}

/*if($mod == 'updatePeriod')
{
	
	
	if($id != '')
	{
		$sql = "SELECT * FROM school_year WHERE school_yr_id = $id";						
		$query = mysql_query($sql);

		$str_arr[] = '<option value="" selected="selected">Select School Year</option>';
		while($row = mysql_fetch_array($query))
		{
			if($row['id'] == $id)
			{
				$selected = 'selected="selected"';
			}
			else
			{
				$selected = '';
			}
			
			$str_arr[] = '<option value="'.$row['school_yr_id'].'" '.$selected.' >'.$row['start_year']."-".$row['end_year'].'</option>';
		}
	}
	else
	{
		$str_arr[] = '<option value="" selected="selected">Select School Year</option>';	
	}
	echo implode('',$str_arr);
	
}*/
?>