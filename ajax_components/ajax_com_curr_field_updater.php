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

$id = $_REQUEST['id'];
$mod = $_REQUEST['mod'];
$str_arr = array();

if($mod == 'select')
 {
	if($id != '')
	{
		$sql = "SELECT * FROM tbl_curriculum WHERE course_id=".$id;						
		$query = mysql_query($sql);
		
		$str_arr[] = '<option value="" selected="selected" >Select</option>';
		
		while($row = mysql_fetch_array($query))
		{
			$str_arr[] = '<option value="'.$row['id'].'" >'.$row['curriculum_code'].'</option>';
		}
	
	echo implode('',$str_arr);
	}
 }
else if($mod == 'update')
	{
		if($id != '')
		{
		$sql = "SELECT * FROM tbl_curriculum WHERE course_id=".$id;						
		$query = mysql_query($sql);
		
		
		while($row = mysql_fetch_array($query))
		{
			if($row['course_id'] == $id)
			{
				$selected = 'selected="selected"';
			}
			else
			{
				$selected = '';
			}
			$str_arr[] = '<option value="'.$row['id'].'"  '.$selected.' >'.$row['curriculum_code'].'</option>';
		}
	}
	
	echo implode('',$str_arr);
}
?>