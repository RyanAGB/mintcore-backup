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
	
	if(USER_IS_LOGGED != '1')
	{
		header('Location: ../index.php');
	}
	/*else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}*/
	
	
	
	if($_REQUEST['stud_id']!='')
	{
		echo $sql = "SELECT * FROM

									tbl_student_enrollment_status 

									WHERE

									term_id = " . CURRENT_TERM_ID . " AND

									student_id= " .$_REQUEST['stud_id'];

		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		
		if($row['enrollment_status']=='EXP' || mysql_num_rows($query)<1)
		{
			/* [+] Re-credit Slot
			$sqlrev = "SELECT schedule_id FROM tbl_student_reserve_subject WHERE 
				student_id = ".$_REQUEST['stud_id']." AND 
				term_id = ".CURRENT_TERM_ID;
			$queryrev = mysql_query($sqlrev);	
			while($rowrev = mysql_fetch_array($queryrev))
			{
				reverseSlot($rowrev['schedule_id']);
			}
			 [-] Re-credit Slot*/
			
			echo $sqldel = "DELETE FROM tbl_student_enrollment_status 

									WHERE

									term_id = " . CURRENT_TERM_ID . " AND

									student_id= " .$_REQUEST['stud_id'];

			$querydel = mysql_query($sqldel);
			
			$sqldel = "DELETE FROM tbl_student_reserve_subject 

									WHERE

									term_id = " . CURRENT_TERM_ID . " AND

									student_id= " .$_REQUEST['stud_id'];

			$querydel = mysql_query($sqldel);
			
			$sqldel = "DELETE FROM tbl_student_other_fees 

									WHERE

									term_id = " . CURRENT_TERM_ID . " AND

									student_id= " .$_REQUEST['stud_id'];

			$querydel = mysql_query($sqldel);
		
		
		$sql_stud = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['stud_id'];
		$query_stud = mysql_query($sql_stud);
		$row_stud = mysql_fetch_array($query_stud);
		
		
		echo $sql_blok = "SELECT * FROM tbl_block_section

					WHERE course_id = ".$row_stud['course_id']."

					AND term_id = ".CURRENT_TERM_ID."

					AND year_level= ".getStudentNextYearLevel($_REQUEST['stud_id']);
					
		$query_blok = mysql_query($sql_blok);
		
		//echo mysql_num_rows($query_blok);
		
		$id = '';
		
		if(mysql_num_rows($query_blok) > 1)
		{
			while($row_blok = mysql_fetch_array($query_blok))
			{
				if(!checkSlotAvailableInBlock2($row_blok['id'],$_REQUEST['stud_id']))
				{
					
						if($id=='' && $id!=$row_blok['id'])
						{
						/*$sql = "SELECT * FROM tbl_block_subject a 
								LEFT JOIN tbl_schedule b ON a.schedule_id = b.id 
								LEFT JOIN tbl_curriculum_subject c ON b.subject_id=c.subject_id 
								WHERE a.block_section_id = ".$row_blok['id']." 
								AND c.curriculum_id = ".getStudentCurriculumID($_REQUEST['stud_id']);*/
						$sql = "SELECT b.* FROM tbl_block_subject a 

							LEFT JOIN tbl_schedule b ON a.schedule_id = b.id 
			
							WHERE a.block_section_id =".$row_blok['id'];
						$query = mysql_query($sql);
				
						while($row = mysql_fetch_array($query)){
							
						if(getStudentNextYearLevel($_REQUEST['stud_id'])==1)
						{
							
							$sql = "INSERT INTO tbl_student_reserve_subject 
									(
										schedule_id, 
										student_id, 
										term_id, 
										units,
										subject_id,
										elective_of,
										date_created,
										created_by,
										date_modified
									) 
									VALUES 
									(
										".$row['id'].",  
										".$_REQUEST['stud_id'].",  
										".CURRENT_TERM_ID.", 
										".getSubjUnit($row['subject_id']).", 
										".$row['subject_id'].",
										'".$row['elective_of']."',
										".time().",
										".USER_ID.",		
										".time()."
									)";
									
							
								mysql_query ($sql);
							
						}
						else
						{
							if(in_array($row['subject_id'],getStudentSubjectForEnrollmentInArr($_REQUEST['stud_id'])))
							{
					
								//deductSlot($row['schedule_id']);
				
								$sql = "INSERT INTO tbl_student_reserve_subject 
									(
										schedule_id, 
										student_id, 
										term_id, 
										units,
										subject_id,
										elective_of,
										date_created,
										created_by,
										date_modified
									) 
									VALUES 
									(
										".$row['schedule_id'].",  
										".$_REQUEST['stud_id'].",  
										".CURRENT_TERM_ID.", 
										".getSubjUnit($row['subject_id']).", 
										".$row['subject_id'].",
										'".$row['elective_of']."',
										".time().",
										".USER_ID.",		
										".time()."
									)";
									
							
								mysql_query ($sql);
								
								}
						}
						}
						$id = $row_blok['id'];
						//
						storeStudentFee($_REQUEST['stud_id'],$row_stud['term_id']);
						storeStudentOtherFee($_REQUEST['stud_id'],$row_stud['term_id']);
						//getAdditionalFees($_REQUEST['stud_id']);//TEMPORARY GETTING FEES FOR MBM
						
						$reserv_days = 0;//getStudentReservationDay($_REQUEST['stud_id']);
						$exp_date = 0;//mktime(0, 0, 0, date("m") , date("d")+ $reserv_days, date("Y"));
						
						
						$sql_sch = "SELECT * FROM tbl_payment_scheme
	
						WHERE term_id = ".CURRENT_TERM_ID;
						
						$query_sch = mysql_query($sql_sch);
						$row_sch = mysql_fetch_array($query_sch);
						
						
							echo $sql = "INSERT INTO tbl_student_enrollment_status 
								(
									student_id, 
									term_id,
									scheme_id,
									enrollment_status,
									block_id,
									days_of_reservation,
									date_reserved,
									expiration_date
								) 
								VALUES 
								(
									".GetSQLValueString($_REQUEST['stud_id'],"int").",  
									".GetSQLValueString(CURRENT_TERM_ID,"int").",
									".GetSQLValueString($row_sch['id'],"int").",  
									".GetSQLValueString('R',"text").",
									".$row_blok['id'].",
									".GetSQLValueString($reserv_days,"int").", 
									".GetSQLValueString(time(),"int").", 
									".GetSQLValueString($exp_date,"int")." 
								)";	
							mysql_query($sql);
						
						
						echo 'reserved';
					}
					//echo 'reserved';
				}
				
			}
		}
			
		else if(mysql_num_rows($query_blok) > 0 && mysql_num_rows($query_blok) < 2)
		{
		
			
			while($row_blok = mysql_fetch_array($query_blok))
			{
				//if(!checkSlotAvailableInBlock($row_blok['id'],$_REQUEST['stud_id']))
				//{
					
						//if($id=='' && $id!=$row_blok['id'])
						//{
						/*$sql = "SELECT * FROM tbl_block_subject a 
								LEFT JOIN tbl_schedule b ON a.schedule_id = b.id 
								LEFT JOIN tbl_curriculum_subject c ON b.subject_id=c.subject_id 
								WHERE a.block_section_id = ".$row_blok['id']." 
								AND c.curriculum_id = ".getStudentCurriculumID($_REQUEST['stud_id']);*/
						echo $sql = "SELECT b.* FROM tbl_block_subject a 

							LEFT JOIN tbl_schedule b ON a.schedule_id = b.id 
			
							WHERE a.block_section_id =".$row_blok['id'];
						$query = mysql_query($sql);
				
						while($row = mysql_fetch_array($query)){
							
						if(in_array($row['subject_id'],getStudentSubjectForEnrollmentInArr($_REQUEST['stud_id'])))
						{
				
							//deductSlot($row['schedule_id']);
			
							$sql = "INSERT INTO tbl_student_reserve_subject 
								(
									schedule_id, 
									student_id, 
									term_id, 
									units,
									subject_id,
									elective_of,
									date_created,
									created_by,
									date_modified
								) 
								VALUES 
								(
									".$row['id'].",  
									".$_REQUEST['stud_id'].",  
									".CURRENT_TERM_ID.", 
									".getSubjUnit($row['subject_id']).", 
									".$row['subject_id'].",
									'".$row['elective_of']."',
									".time().",
									".USER_ID.",		
									".time()."
								)";
								
						
							mysql_query ($sql);
							
							}
						}
						$id = $row_blok['id'];
						//
						storeStudentFee($_REQUEST['stud_id'],$row_stud['term_id']);
						storeStudentOtherFee($_REQUEST['stud_id'],$row_stud['term_id']);
						//getAdditionalFees($_REQUEST['stud_id']);//TEMPORARY GETTING FEES FOR MBM
						
						$reserv_days = 0;//getStudentReservationDay($_REQUEST['stud_id']);
						$exp_date = 0;//mktime(0, 0, 0, date("m") , date("d")+ $reserv_days, date("Y"));
						
						storeDefaultScheme(CURRENT_TERM_ID,$_REQUEST['stud_id'],$row_blok['id']);
						
						
						
						echo 'reserved';
					//}
					//echo 'reserved';
				//}
				
			}
	
		
		}//
		else
		{
			echo 'block';	
		}
		
		}
		else if($row['enrollment_status']=='R')
		{
			echo 'reserved';
		}
		else
		{
			echo 'enroll';
		}
	}
?>
