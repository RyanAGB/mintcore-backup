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

include_once("config.php");
include_once("includes/functions.php");
include_once("includes/common.php");

	echo $sql = "SELECT * FROM tbl_student";
	$query = mysql_query($sql);
	
	while($row = mysql_fetch_array($query))
	{
	
		if(checkIfStudentIsEnroll($row['id']))

		{
			echo $sqlSched = "SELECT * FROM tbl_student_schedule WHERE student_id=".$row['id']." AND term_id=11";
			$querySched = mysql_query($sqlSched);
			
			while($rowsched = mysql_fetch_array($querySched))
			{
			$period_id = array(16,17,18);
		$ctr = 0;
		foreach($period_id as $period)
		{
			
			$sql2 = "SELECT * FROM tbl_schedule WHERE id=".$rowsched['schedule_id'];
			$query2 = mysql_query($sql2);
			$row2 = mysql_fetch_array($query2);
				
				echo $sql = "INSERT INTO tbl_student_grade
				(	
					student_id,
					subject_id,
					term_id,
					schedule_id,
					period_id,
					altered_grade,
					is_altered,
					is_locked,
					date_created, 
					created_by,
					date_altered,
					altered_by	                                                                  
				) 
				VALUES 
				(
					".GetSQLValueString($row['id'],"text").",
					".GetSQLValueString($rowsched['subject_id'],"text").",
					".GetSQLValueString('11',"text").",
					".GetSQLValueString($rowsched['schedule_id'],"text").",
					".GetSQLValueString($period,"text").",
					".GetSQLValueString(encrypt(88),"text").",
					".GetSQLValueString('Y',"text").",	
					".GetSQLValueString('Y',"text").",	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."						
				)";
			
				if(mysql_query ($sql))
				{
					echo $sql = "INSERT INTO tbl_grade_submission
					(	
						professor_id,
						term_id,
						period_id,
						schedule_id,
						submission_is_locked,
						locked_date                                                               
					) 
					VALUES 
					(
						".GetSQLValueString($row2['employee_id'],"text").",
						".GetSQLValueString('11',"text").",
						".GetSQLValueString($period,"text").",
						".GetSQLValueString($rowsched['schedule_id'],"text").",
						".GetSQLValueString('N',"text").",
						".time()."				
					)";
		
					if(mysql_query ($sql))
					{
						
					}
				}
				
			$ctr++;
		}
		
		
		$sql = "INSERT INTO tbl_student_final_grade
			(	
				student_id,
				subject_id,
				term_id,
				final_grade,
				type,
				remarks,
				grade_conversion_id,
				date_created,
				created_by
                                                           
			) 
			VALUES 
			(
				".GetSQLValueString($row['id'],"int").",
				".GetSQLValueString($rowsched['subject_id'],"int").",
				".GetSQLValueString('11',"int").",
				".GetSQLValueString(encrypt(88),"text").",
				".GetSQLValueString('S',"text").",
				".GetSQLValueString('P',"text").",
				".GetSQLValueString('14',"text").",
				".time().",
				".USER_ID."		
			)";
			mysql_query ($sql) ;//or die(mysql_error());
		
	}	
	
		
			
	}
	}

?>