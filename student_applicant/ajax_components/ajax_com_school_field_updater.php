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


	include_once("../../config.php");
	include_once("../../includes/functions.php");

$id = $_REQUEST['id'];
$mod = $_REQUEST['mod'];
$cor = $_REQUEST['cor'];
$str_arr = array();

/*if($mod=='school')
{
	if($id != '')
	{
		$sql = "SELECT * FROM tbl_school_list WHERE id = ".$id;						
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
	}

	echo $row['school_code'].','.$row['school_type'].','.$row['address'];
}
else */if($mod=='updateDate')
{
	if($id != '' && $cor != '')
	{
		$sql = "SELECT * FROM tbl_exam_date WHERE term_id = ".$id." AND course_id=".$cor;						
		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query))
		{
			$date = explode('-',$row['entrance_date']);
			
			$str_arr[] = '<option value="'.$row['id'].'">'.getMonthName($date[1]).' '.$date[2].', '.$date[0]. '</option>';
		}
		
		echo implode(' ',$str_arr); 
	}
}
?>