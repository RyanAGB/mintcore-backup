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
	include_once('../includes/functions.php');	
	include_once('../includes/common.php');	

$id = $_REQUEST['id'];
$mod = $_REQUEST['mod'];

 if($mod == 'compute')
 {
	if($id != '')
	{
		$sql_count = "SELECT * FROM tbl_schedule WHERE id = ".$id;
		$result_count = mysql_query($sql_count);
		$row = mysql_fetch_array($result_count);
		
		$number_of_student = $row['number_of_student'];
		
		$number_of_reserved = $row['number_of_reserved'];
		$reserved = $number_of_reserved + 1;

		$available = $number_of_student - $reserved;
		
		$sql = "UPDATE tbl_schedule 
						SET 
							number_of_reserved = ".$reserved.",
							number_of_available = ".$available."
						WHERE id = ".$id;
				  
		if(mysql_query($sql))
		{
			echo 'success';
		}
	}
 }
 else if($mod == 'reverse')
 {
	if($id != '')
	{
		$sql_sched = "SELECT * FROM tbl_schedule 
				WHERE term_id = ".CURRENT_TERM_ID." AND subject_id = ".$id;
		$query_sched = mysql_query($sql_sched);
		$row_sched = mysql_fetch_array($query_sched);
		$sched_id = $row_sched['id'];

		$sql_count = "SELECT * FROM tbl_schedule WHERE id = ".$sched_id;
		$result_count = mysql_query($sql_count);
		$row = mysql_fetch_array($result_count);
		
		$number_of_student = $row['number_of_student'];
		
		$number_of_reserved = $row['number_of_reserved'];
		$reserved = $number_of_reserved - 1;

		$available = $number_of_student - $reserved;
		
		$sql = "UPDATE tbl_schedule 
						SET 
							number_of_reserved = ".$reserved.",
							number_of_available = ".$available."
						WHERE id = ".$sched_id;
				  
		if(mysql_query($sql))
		{
			echo 'success';
		}						
	}
 }
  else if($mod == 'update')
 {
		$sql_sched = "SELECT * FROM tbl_schedule 
				WHERE term_id = ".CURRENT_TERM_ID;
		$query_sched = mysql_query($sql_sched);
		
		while($row = mysql_fetch_array($query_sched))
		{
			$student = $row['number_of_student'];
			$reserved = $row['number_of_reserved'];
			$avail = $student - $reserved;
		
			$sqlsch = "UPDATE tbl_schedule SET number_of_available = ".$avail." 
					WHERE id = ".$row['id'];	
				  
			  mysql_query($sqlsch);
			 
		}	
 }

?>