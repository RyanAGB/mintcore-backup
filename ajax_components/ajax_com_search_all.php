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
	include_once("../includes/functions.php");
	include_once("../includes/common.php");
	if(USER_IS_LOGGED != '1')
	{
		header('Location: ../index.php');
	}
	/*else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}*/
	
	$value = $_REQUEST['val'];
	
	$sql = "SHOW COLUMNS FROM tbl_student";
	
	$query = mysql_query($sql);
	
	$s_search = array();
	
	while($row = mysql_fetch_array($query))
	{
	
		echo $sql_search = "SELECT * FROM tbl_student WHERE ".$row['Field']." LIKE '".$value."%'";
		$query_search = mysql_query($sql_search);
		
		while($row_search = mysql_fetch_array($query_search))
		{
			if(mysql_num_rows($query_search)>0)
			{
			
				$s_search[] = $row['Field'];
				
			}
		}
		
	}
	
	$sql = "SHOW COLUMNS FROM tbl_employee";
	
	$query = mysql_query($sql);
	
	$e_search = array();
	
	while($row = mysql_fetch_array($query))
	{
	
		echo $sql_search = "SELECT * FROM tbl_employee WHERE ".$row['Field']." LIKE '".$value."%'";
		$query_search = mysql_query($sql_search);
		
		while($row_search = mysql_fetch_array($query_search))
		{
			if(mysql_num_rows($query_search)>0)
			{
			
				$e_search[] = $row['Field'];
				
			}
		}
		
	}
	
	if(count($s_search)>0)
	{
		
		foreach($s_search as $s)
		{
			
			if($s == 'course_id')
			{
				$course_id = $value;
			}
			else if($s == 'student_number')
			{
				$student_number = $value;
			}
			else if($s == 'lastname')
			{
				$lastname = $value;
			}
			else if($s == 'firstname')
			{
				$firstname = $value;
			}
			/*else if($s == 'middlename')
			{
				$middlename = $value;
			}*/
			
		}
		
		$filter = 1;
		
		setStudentSearch($course_id,$student_number,$lastname,$firstname,$middlename,$filter);
		
	}
	
	if(count($e_search)>0)
	{
		
		foreach($e_search as $e)
		{
			
			if($e == 'emp_id_number')
			{
				$emp_id_number = $value;
			}
			else if($e == 'lastname')
			{
				$lastname = $value;
			}
			else if($e == 'firstname')
			{
				$firstname = $value;
			}
			/*else if($e == 'middlename')
			{
				$middlename = $value;
			}*/
			
		}
		
		$filter = 1;
		
		setProfSearch($emp_id_number,$lastname,$firstname,$middlename,$filter);
		
	}
	
	//filter = 1; 
	
	/*setStudentSearch($course_id,$student_number,$lastname,$firstname,$middlename,$filter);
	
		
	setProfSearch($emp_id_number,$lastname,$firstname,$middlename,$filter);*/
	
	
	?>