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

//
		$course = array(1,4,6,8,9,10,12,13,31,32);
		$x=1;

foreach($course as $co)
{
		
	for($a = $x;$a<=$x+17;$a++)
	{
		$student_number = generateStudentNumber($co,1);
		$gen_salt = generateSaltString();
		$userN = explode('-',$student_number);
	
		for($c = 1;$c<=count($userN);$c++)
		{
			if($c==count($userN)){
				$userNm = 'Inquiry'.$a.'_'.$userN[$c-1];
				$password = md5('mint'.$userN[$c-1].$gen_salt);
			}
		}
		
		$sql_user = "INSERT INTO tbl_user 
				(		
					username,
					blocked,
					password,
					salt,
					access_id                                                                 
				) 
				VALUES 
				(
					".GetSQLValueString($userNm,"text").",
					".GetSQLValueString('0',"int").",
					".GetSQLValueString($password,"text").",
					".GetSQLValueString($gen_salt,"text").",
					".GetSQLValueString('6',"int")."
				)";
			
		if(mysql_query ($sql_user))
		{
			$user_id = mysql_insert_id();
			$sql_student_info = "INSERT INTO tbl_student 
			(	
				user_id,
				student_number,                                                     
				course_id,
				curriculum_id,                                                                 
				admission_type,
				start_year_level,
				year_level,
				term_id,
				firstname,
				middlename,
				lastname,
				nickname,
				email,
				birth_date,
				date_created, 
				created_by,
				date_modified,
				modified_by						                                                                  
			) 
			VALUES 
			(
				".GetSQLValueString($user_id,"int").",
				".GetSQLValueString($student_number,"text").",
				".GetSQLValueString($co,"int").",
				".GetSQLValueString(getCurriculumByCourseId($co),"int").",
				".GetSQLValueString('F',"text").",
				".GetSQLValueString(1,"int").",
				".GetSQLValueString(1,"int").",	
				".CURRENT_TERM_ID.",					 
				".GetSQLValueString('Inquiry'.$a,"text").",
				".GetSQLValueString('Inquiry'.$a,"text").",
				".GetSQLValueString('Inquiry'.$a,"text").",
				".GetSQLValueString('Inquiry'.$a,"text").",
				".GetSQLValueString('Inquiry'.$a.'@x.x',"text").",
				".GetSQLValueString('1990-9-9',"text").",
				".time().",
				".USER_ID.", 
				".time().",
				".USER_ID."						
			)";
			
			$veri_code = generateRandomString();
			
			if(mysql_query ($sql_student_info))
			{		
				$studid = mysql_insert_id();
				$stud_id = $studid;	
				
				
				//MODIFIED BY MKT
				$gen_salt = generateSaltString();
				$userN = explode('-',$student_number);
				
				for($c = 1;$c<=count($userN);$c++)
				{
					if($c==count($userN)){
						$userNm = 'p'.$lastname.'_'.$userN[$c-1];
						$password = md5('mint'.$userN[$c-1].$gen_salt);
					}
				}	
				
				$sql_guardian_user = "INSERT INTO tbl_user 
				(		
					username,
					blocked,
					password,
					salt,
					access_id                                                                 
				) 
				VALUES 
				(
					".GetSQLValueString($userNm,"text").",
					".GetSQLValueString('0',"int").",
					".GetSQLValueString($password,"text").",
					".GetSQLValueString($gen_salt,"text").",
					".GetSQLValueString('7',"int")."
				)";
				
				
				mysql_query ($sql_guardian_user);
				$user_id = mysql_insert_id();
				
				$sql_guardian_info = "INSERT INTO tbl_parent 
				(		
					student_id,
					user_id,
					name,
					relation,
					email,
					date_created, 
					created_by,
					date_modified,
					modified_by                                                                 
				) 
				VALUES 
				(
					".GetSQLValueString($stud_id,"int").",
					".GetSQLValueString($user_id,"int").",
					".GetSQLValueString('pInquiry'.$a,"text").",
					".GetSQLValueString('P',"text").",
					".GetSQLValueString('pInquiry'.$a.'@x.x',"text").",
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
				mysql_query ($sql_guardian_info);

			}
		}
		
	}echo $x;$x=$a;
}

	?>