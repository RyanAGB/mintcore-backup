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

$mod = $_REQUEST['mod'];
$id = $_REQUEST['id'];
$ad = $_REQUEST['ad'];
$str_arr = array();
if($mod == 'updateYear')
{
	
	
	if($id != '')
	{
		if($ad == 'T')
	{
	
		$sql = "SELECT no_of_years FROM tbl_curriculum WHERE is_current = 'Y' AND course_id =" .$id;						
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$years = $row['no_of_years'];
		
		for($ctr=1;$ctr<=$years;$ctr++) {
			if($ctr == 1)
			{
				$yir = '1st Year';
			}if($ctr == 2)
			{
				$yir = '2nd Year';
			}if($ctr == 3)
			{
				$yir = '3rd Year';
			}if($ctr == 4)
			{
				$yir = '4th Year';
			}
			

			$str_arr[] = '<option value="'.$ctr.'" >'.$yir.'</option>';
			
		}
	
	echo implode('',$str_arr);
	}
	else{
			
			$str_arr[] = '<option value="1">1st Year</option>';
	
	echo implode('',$str_arr);
	}
}
}


?>