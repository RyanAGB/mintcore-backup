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



$page_title = 'Manage Student Profile';
$pagination = 'Users > Manage Student';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

// STUDENT //			
$bal         = $_REQUEST['balance']; 
$ibal         = $_REQUEST['ibalance']; 

$student_number         = $_REQUEST['student_number'];                                                                  
$course_id              = $_REQUEST['course_id'];                                                                   
$curriculum_id          = getCurriculumByCourseId($_REQUEST['course_id']);                                                              
$year_level             = $_REQUEST['year_level'];                                                                  
$admission_type  		= $_REQUEST['admission_type'];
                                          
$firstname              = $_REQUEST['firstname'];                             
$middlename             = $_REQUEST['middlename'];                             
$lastname               = $_REQUEST['lastname']; 
$nickname               = $_REQUEST['nickname'];   
                      
$suffix                 = $_REQUEST['suf']!='other'?$_REQUEST['suf']:$_REQUEST['suffix']; 
                         
$email                  = $_REQUEST['email'];
$con_email              = $_REQUEST['con_email'];

$b_year             	= $_REQUEST['b_year'];
$b_month             	= $_REQUEST['b_month'];
$b_day             		= $_REQUEST['b_day'];

$bdate 					= $_REQUEST['b_year'].'-'.$_REQUEST['b_month'].'-'.$_REQUEST['b_day'];
$birth_date 			= explode('-', $bdate);
//var_dump($birth_date);echo 'ssss'.$birth_date[0];
$exam_date	            = $_REQUEST['exam_date'];
$birth_place            = $_REQUEST['birth_place'];                           
$gender                 = $_REQUEST['gender'];                          
$citizenship            = $_REQUEST['citizenship'];                                       
$civil_status           = $_REQUEST['civil_status'];
$home_address      		= $_REQUEST['home_address'];                                     
$home_address_zip    	= $_REQUEST['home_address_zip']==''?0:$_REQUEST['home_address_zip'];  
$city      				= $_REQUEST['city'];                                     
$fax    				= $_REQUEST['fax'];  
$country    			= $_REQUEST['country']==''?0:$_REQUEST['country']; 
$tel_number             = $_REQUEST['tel_number'];                                     
$mobile_number          = $_REQUEST['mobile_number'];

$student_photo			= $_FILES['student_photo']['name'];

$exam_grade				= $_REQUEST['exam_grade'];

//LIBRARY
$lib_classification			= 1;//$_REQUEST['lib_classification'];
$lib_card_number			= generateSpecialNumber();//$_REQUEST['lib_card_number'];

// GUARDIAN //

$guardian_name          = $_REQUEST['guardian_name'];
$annual_family_income   = $_REQUEST['annual_family_income'];                                    
$guardian_address      	= $_REQUEST['guardian_address'];                                     
$guardian_address_zip   = $_REQUEST['guardian_address_zip']==''?0:$_REQUEST['guardian_address_zip'];                                  
$guardian_city    		= $_REQUEST['guardian_city'];                               
$guardian_occupation	= $_REQUEST['guardian_occupation'];                                      
$guardian_tel_number    = $_REQUEST['guardian_tel_number'];                                     
$guardian_work_number   = $_REQUEST['guardian_work_number'];                                   
$guardian_fax    		= $_REQUEST['guardian_fax'];  
$guardian_country    	= $_REQUEST['guardian_country'];          
$guardian_company       = $_REQUEST['guardian_company'];                                  
$guardian_relation      = $_REQUEST['guardian_relation'];                                       
$guardian_email			= $_REQUEST['guardian_email'];
$con_guardian_email		= $_REQUEST['con_guardian_email'];

//LAST SCHOOL
$grade_school			= $_REQUEST['grade_school'];
$grade_school_address	= $_REQUEST['grade_school_address'];

$grade_mfr 				= $_REQUEST['grade_school_month_fr']==''?0:$_REQUEST['grade_school_month_fr'];
$grade_yfr 				= $_REQUEST['grade_school_year_fr']==''?0:$_REQUEST['grade_school_year_fr'];
$grade_mto  			= $_REQUEST['grade_school_month_to']==''?0:$_REQUEST['grade_school_month_to'];
$grade_yto 				= $_REQUEST['grade_school_year_to']==''?0:$_REQUEST['grade_school_year_to'];

$grade_school_years		= $grade_mfr.'/'.$grade_yfr.'-'.$grade_mto.'/'.$grade_yto;
$grade_school_award		= $_REQUEST['grade_school_award'];

$high_school			= $_REQUEST['high_school'];
$high_school_address	= $_REQUEST['high_school_address'];

$high_mfr 				= $_REQUEST['high_school_month_fr']==''?0:$_REQUEST['high_school_month_fr'];
$high_yfr 				= $_REQUEST['high_school_year_fr']==''?0:$_REQUEST['high_school_year_fr'];
$high_mto  				= $_REQUEST['high_school_month_to']==''?0:$_REQUEST['high_school_month_to'];
$high_yto 				= $_REQUEST['high_school_year_to']==''?0:$_REQUEST['high_school_year_to'];

$high_school_years		= $high_mfr.'/'.$high_yfr.'-'.$high_mto.'/'.$high_yto;
$high_school_award		= $_REQUEST['high_school_award'];

$college_school			= $_REQUEST['college_school'];
$college_school_address	= $_REQUEST['college_school_address'];

$college_mfr 			= $_REQUEST['college_school_month_fr']==''?0:$_REQUEST['college_school_month_fr'];
$college_yfr 			= $_REQUEST['college_school_year_fr']==''?0:$_REQUEST['college_school_year_fr'];
$college_mto  			= $_REQUEST['college_school_month_to']==''?0:$_REQUEST['college_school_month_to'];
$college_yto 			= $_REQUEST['college_school_year_to']==''?0:$_REQUEST['college_school_year_to'];

$college_school_years	= $college_mfr.'/'.$college_yfr.'-'.$college_mto.'/'.$college_yto;
$college_school_award	= $_REQUEST['college_school_award'];

//EXTRA
$language  				= $_REQUEST['language'];
$extra_curricular		= $_REQUEST['extra_curricular'];

$scholarship			= $_REQUEST['scholarship'];
$scholarship_type		= $_REQUEST['scholarship_type'];
// THIS IS FOR IMAGE //

if(isset($_FILES['student_photo']['tmp_name']) && $_FILES['student_photo']['tmp_name'] != '')
{
	//$imgData 		= addslashes(file_get_contents($_FILES['student_photo']['tmp_name']));
	$imageSize 		= getimagesize($_FILES['student_photo']['tmp_name']);	
	$width 			= $imageSize[0];
	$height 		= $imageSize[1];
	$file_ext		= explode(".",$_FILES['student_photo']['name']);
	$file_type		= strtolower($file_ext[1]);
	
	//creates the new image using the appropriate function from gd library
	if(!strcmp("jpg",$file_type) || !strcmp("jpeg",$file_type))
	$src_img=imagecreatefromjpeg($_FILES['student_photo']['tmp_name']);
			
	if(!strcmp("png",$file_type))
	$src_img=imagecreatefrompng($_FILES['student_photo']['tmp_name']);
				
	if(!strcmp("gif",$file_type))
	$src_img=imagecreatefromgif($_FILES['student_photo']['tmp_name']);
			
	//gets the dimmensions of the image
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	
	// we create a new image with the new dimmensions
		$dst_img=ImageCreateTrueColor(120,120);
	// resize the big image to the new created one
		imagecopyresampled($dst_img,$src_img,0,0,0,0,120,120,$width,$height);
		
	ob_start(); // Start capturing stdout.
	imageJPEG($dst_img); // As though output to browser.
	$imgData = addslashes(ob_get_contents()); // the raw jpeg image data.
	ob_end_clean(); // Dump the stdout so it does not screw other output. 
}

$veri_code = generateRandomString();

//FOR REQUIREMENTS

$req	= $_REQUEST['chk'];
$remarks = $_REQUEST['remarks'];

//FOR IMPORT

$num	= $_REQUEST['num'];
$uploadfile	= $_REQUEST['uploadfile'];

$field = array();

//FOR SORTING AND PAGINATION

$filter_field = $_REQUEST['filter_field'];
$filter_order = $_REQUEST['filter_order'];
$page = $_REQUEST['page'];

	if($_SESSION[CORE_U_CODE]['current_comp'] != $comp || $_SESSION[CORE_U_CODE]['pageNum'] != $page || $_SESSION[CORE_U_CODE]['fieldName'] != $filter_field || $_SESSION[CORE_U_CODE]['orderBy'] != $filter_order)
	{
		if($page != '')
		{
			$_SESSION[CORE_U_CODE]['pageNum'] = isset($page)&&$page!='' ? $page : '1';
		}
		if($filter_field != '' || $filter_order != '')
		{
			$_SESSION[CORE_U_CODE]['fieldName'] = $filter_field;
			$_SESSION[CORE_U_CODE]['orderBy'] = $filter_order;
		}
		$_SESSION[CORE_U_CODE]['current_comp'] = $comp;
		
	}
//--

if($action == 'save')
{
	/*if(!is_numeric($home_address_zip) || !is_numeric($guardian_address_zip))
	{
		$err_msg = 'Invalid Zip Code';
	}
	else */
	if($con_email == '' )
	{
		$err_msg = 'Please confirm student email.';
	}
	else if($email != $con_email)
	{
		$err_msg = 'Student Email does not match.';
	}
	else if(isValidEmail($guardian_email)===false)
	{
		$err_msg = 'Please enter a valid guardian email.';
	}	
	/*
	else if($con_guardian_email == '' )
	{
		$err_msg = 'Please confirm guardian email.';
	}
	else if($guardian_email != $con_guardian_email)
	{
		$err_msg = 'Guardian email does not match.';
	}
	
	else if (checkStudentIDExist($student_number))
	{
		$err_msg = 'Student ID already exist.';
	}
	else if (checkIfStudentEmailExist($email))
	{
		$err_msg = 'Student email address already exist.';
	}*/
	else if (checkIfParentEmailExist($guardian_email) && $guardian_email!= '')
	{
		$err_msg = 'Parent email address already exist.';
	}
	/*else if($height >= '121' && $height != '')
	{
		$err_msg = 'Error in Height. Maximum of 120 x 120 only is allowed';
	}
	else if($width >= '121' && $width != '')
	{
		$err_msg = 'Error in Width. Maximum of 120 x 120 only is allowed';
	}*/
	else if(!checkCurriculumHasCurrent())
	{
		$err_msg = 'No Current Curriculum is Set.';
	}
	else if(!checkCurriculumSubjectIsSet())
	{
		$err_msg = 'Current Curriculum Subjects are not complete.';
	}
	else
	{
		//MODIFIED BY MKT
		$gen_salt = generateSaltString();
		$userN = explode('-',$student_number);
		
		for($c = 1;$c<=count($userN);$c++)
		{
			if($c==count($userN)){
				$userNm = $lastname.'_'.$userN[$c-1];
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
		
		/*
		$sql_user = "INSERT INTO tbl_user 
				(		
					username,
					verification_code,
					access_id                                                                 
				) 
				VALUES 
				(
					".GetSQLValueString($student_number,"text").",
					".GetSQLValueString($veri_code,"text").",
					".GetSQLValueString('6',"int")."
				)";
		
		*/
			
		if(mysql_query ($sql_user))
		{
			$user_id = mysql_insert_id();
			
			if (checkStudentIDExist($student_number))
			{
				$student_number = generateStudentNumber($course_id,1);
			}
			
			$sql_student_info = "INSERT INTO tbl_student 
			(	
				user_id,
				student_number,                                                                  
				suffix,                                                     
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
				birth_place,
				gender,
				citizenship,
				civil_status,
				city,
				fax,
				country,                                   
				home_address,
				home_address_zip,
				tel_number,
				mobile_number,
				grade_school,
				grade_school_address,
				grade_school_years,
				grade_school_award,
				high_school,
				high_school_address,
				high_school_years,
				high_school_award,
				college_school,
				college_school_address,
				college_school_years,
				college_school_award,
				guardian_name,
				guardian_city,
				guardian_occupation,
				guardian_email,
				guardian_address,
				guardian_tel_number,
				guardian_work_number,
				guardian_relation,
				guardian_fax,
				guardian_company,
				guardian_address_zip,
				guardian_country,
				language,
				extra_curricular,
				lib_classification,
				lib_card_number,
				scholarship,
				scholarship_type,
				exam_grade,
				date_created, 
				created_by,
				date_modified,
				modified_by						                                                                  
			) 
			VALUES 
			(
				".GetSQLValueString($user_id,"int").",
				".GetSQLValueString($student_number,"text").",
				".GetSQLValueString($suffix,"text").",
				".GetSQLValueString($course_id,"int").",
				".GetSQLValueString(getCurriculumByCourseId($course_id),"int").",
				".GetSQLValueString($admission_type,"text").",
				".GetSQLValueString($year_level,"int").",
				".GetSQLValueString($year_level,"int").",	
				".CURRENT_TERM_ID.",					 
				".GetSQLValueString($firstname,"text").",
				".GetSQLValueString($middlename,"text").",
				".GetSQLValueString($lastname,"text").",
				".GetSQLValueString($nickname,"text").",
				".GetSQLValueString($email,"text").",
				".GetSQLValueString($bdate,"text").",
				".GetSQLValueString($birth_place,"text").",
				".GetSQLValueString($gender,"text").",
				".GetSQLValueString($citizenship,"text").",
				".GetSQLValueString($civil_status,"text").",
				".GetSQLValueString($city,"text").",
				".GetSQLValueString($fax,"text").",
				".GetSQLValueString($country,"int").",
				".GetSQLValueString($home_address,"text").",
				".GetSQLValueString($home_address_zip,"text").",
				".GetSQLValueString($tel_number,"text").",
				".GetSQLValueString($mobile_number,"text").",
				".GetSQLValueString($grade_school,"text").",
				".GetSQLValueString($grade_school_address,"text").",
				".GetSQLValueString($grade_school_years,"text").",
				".GetSQLValueString($grade_school_award,"text").",
				".GetSQLValueString($high_school,"text").",
				".GetSQLValueString($high_school_address,"text").",
				".GetSQLValueString($high_school_years,"text").",
				".GetSQLValueString($high_school_award,"text").",
				".GetSQLValueString($college_school,"text").",
				".GetSQLValueString($college_school_address,"text").",
				".GetSQLValueString($college_school_years,"text").",
				".GetSQLValueString($college_school_award,"text").",
				".GetSQLValueString($guardian_name,"text").",
				".GetSQLValueString($guardian_city,"text").",
				".GetSQLValueString($guardian_occupation,"text").",
				".GetSQLValueString($guardian_email,"text").",
				".GetSQLValueString($guardian_address,"text").",
				".GetSQLValueString($guardian_tel_number,"text").",
				".GetSQLValueString($guardian_work_number,"text").",
				".GetSQLValueString($guardian_relation,"text").",
				".GetSQLValueString($guardian_fax,"text").",
				".GetSQLValueString($guardian_company,"text").",
				".GetSQLValueString($guardian_address_zip,"text").",
				".GetSQLValueString($guardian_country,"text").",
				".GetSQLValueString($language,"text").",
				".GetSQLValueString($extra_curricular,"text").",
				".GetSQLValueString($lib_classification,"int").",
				".GetSQLValueString($lib_card_number,"text").",
				".GetSQLValueString($scholarship,"text").",
				".GetSQLValueString($scholarship_type,"text").",
				".GetSQLValueString($exam_grade,"text").",
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
				
				//REQUIREMENTS
				if(count($req)>0)
				{
					foreach($req as $val)
					{
						$sqlReq = "INSERT INTO tbl_student_requirements 
					(		
						student_id,
						requirement_id                                                                 
					) 
					VALUES 
					(
						".GetSQLValueString($stud_id,"text").",
						".GetSQLValueString($val,"text")."
					)";
						$queryReq = mysql_query($sqlReq);
					}
				}
				
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
				
				/*$sql_guardian_user = "INSERT INTO tbl_user 
				(		
					username,
					verification_code,
					access_id                                                                 
				) 
				VALUES 
				(
					".GetSQLValueString('p'.$student_number,"text").",
					".GetSQLValueString($veri_code,"text").",
					".GetSQLValueString('7',"int")."
				)";*/
				
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
					".GetSQLValueString($guardian_name,"text").",
					".GetSQLValueString($guardian_relation,"text").",
					".GetSQLValueString($guardian_email,"text").",
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
				mysql_query ($sql_guardian_info);

				if($imgData != '')
				{
					$sql_photo = "INSERT INTO tbl_student_photo 
					(	
						student_id,	
						image_type,
						image_file                                                                 
					) 
					VALUES 
					(
						".GetSQLValueString($stud_id,"text").",
						".GetSQLValueString($file_type,"text").",
						'".$imgData."'
					)";
					
					mysql_query ($sql_photo);
				}
				/*MODIFIED BY MKT	
				$sql_email = "SELECT * FROM tbl_student WHERE id =" .$stud_id;
				$qry_email = mysql_query($sql_email);
				$row_email = mysql_fetch_array($qry_email);
				
				$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .$row_email['user_id'];
				$qry_ver_code = mysql_query($sql_ver_code);
				$row_ver_code = mysql_fetch_array($qry_ver_code);
				
				$contents = "";
				$from_header = "From:" .SCHOOL_SYS_EMAIL;
				
				$contents .= "SIS Application Details\n";
				$contents .= "========================================================\n\n";					
				$contents .= SCHOOL_SYS_URL."/index.php?comp=com_verify_student_account&verifycode=".$row_ver_code['verification_code']. "\n";		
				$contents .= "\n--------------------------------------------------------\n\n\n";	
				
				$subject= "SIS Application (for verification purpose)";
				
				$to = $row_email['email'];
				//mail($to, $subject, $contents, $from_header);
				
				if(@mail($to, $subject, $contents, $from_header))
				{		
					$sql_guardian = "SELECT * FROM tbl_parent WHERE student_id =" .$stud_id;
					$qry_guardian = mysql_query($sql_guardian);
					$row_guardian = mysql_fetch_array($qry_guardian);
					
					$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .$row_guardian['user_id'];
					$qry_ver_code = mysql_query($sql_ver_code);
					$row_ver_code = mysql_fetch_array($qry_ver_code);
					
					$contents = "";
					$from_header = "From:" .SCHOOL_SYS_EMAIL;
					
					$contents .= "SIS Application Details\n";
					$contents .= "========================================================\n\n";					
					$contents .= SCHOOL_SYS_URL."/index.php?comp=com_verify_parent_account&verifycode=".$row_ver_code['verification_code']. "\n";		
					$contents .= "\n--------------------------------------------------------\n\n\n";	
					
					$subject= "SIS Application (for verification purpose)";
					
					$to = $row_guardian['email'];
					//mail($to, $subject, $contents, $from_header);
					
					if(@mail($to, $subject, $contents, $from_header))
					{		
						echo '<script language="javascript">alert("Student Successfully Added! Please check the email for verication code.");window.location =\'index.php?comp=com_student_profile\';</script>';
					}
					else
					{
						$err_msg = 'Some problem occured while sending your message please try again.';
					}
				}
				else
				{
					$err_msg = 'Some problem occured while sending your message please try again.';
				}*/	
			}
			echo '<script language="javascript">alert("Student Successfully Added.");window.location =\'index.php?comp=com_student_profile\';</script>';
		}
	}	
}
else if($action == 'update')
{
	
	if(!is_numeric($home_address_zip) || !is_numeric($guardian_address_zip))
	{
		$err_msg = 'Invalid Zip Code';
	}
	else if($con_email == '' )
	{
		$err_msg = 'Please confirm student email.';
	}
	else if($email != $con_email)
	{
		$err_msg = 'Student Email does not match.';
	}
	/*else if(isValidEmail($guardian_email)===false)
	{
		$err_msg = 'Please enter a valid guardian email.';
	}	
	else if($con_guardian_email == '' )
	{
		$err_msg = 'Please confirm guardian email.';
	}
	else if($guardian_email != $con_guardian_email)
	{
		$err_msg = 'Guardian email does not match.';
	}*/
	/*else if (checkStudentIDExist($student_number, $id))
	{
		$err_msg = 'Student ID already exits.';
	}*/
	else if (checkIfStudentEmailExist($email, $id))
	{
		$err_msg = 'Student email address already exist.';
	}
	/*else if (checkIfParentEmailExist($guardian_email, $id))
	{
		$err_msg = 'Parent email address already exist.';
	}
	else if($height >= '121' && $height != '')
	{
		$err_msg = 'Error in Height. Maximum of 120 x 120 only is allowed';
	}
	else if($width >= '121' && $width != '')
	{
		$err_msg = 'Error in Width. Maximum of 120 x 120 only is allowed';
	}*/
	else
	{
		if($bal!='' || $ibal!='')
		{
			$del ="DELETE FROM tbl_student_balance WHERE student_id=".$id;
			mysql_query($del);
			
			if($bal!='')
			{
			$sql = "INSERT INTO tbl_student_balance 
				(		
					student_id,
					amount,
					date_created,
					created_by                                                                 
				) 
				VALUES 
				(
					".GetSQLValueString($id,"text").",
					".GetSQLValueString($bal,"text").",
					".time().",
					".time()."
				)";	
				mysql_query($sql);
			}
				
			if($ibal!='')
			{
				
				
				$sql = "INSERT INTO tbl_student_balance 
					(		
						student_id,
						amount,
						is_ipad,
						date_created,
						created_by                                                                 
					) 
					VALUES 
					(
						".GetSQLValueString($id,"text").",
						".GetSQLValueString($ibal,"text").",
						'Y',
						".time().",
						".time()."
					)";	
					mysql_query($sql);
			}
		}
		
		if(storedModifiedLogs('tbl_student', $id))
		{
				
			$sql_student = "UPDATE tbl_student SET
				student_number =".GetSQLValueString($student_number,"text").",
				admission_type =".GetSQLValueString($admission_type,"text").",							
				firstname =".GetSQLValueString($firstname,"text").",
				middlename =".GetSQLValueString($middlename,"text").",
				lastname =".GetSQLValueString($lastname,"text").",
				course_id = ".GetSQLValueString($course_id,"text").",
				curriculum_id = ".GetSQLValueString(getCurriculumByCourseId($course_id),"int").",
				email =".GetSQLValueString($email,"text").",
				birth_date =".GetSQLValueString($bdate,"text").",
				birth_place =".GetSQLValueString($birth_place,"text").",
				gender =".GetSQLValueString($gender,"text").",
				citizenship =".GetSQLValueString($citizenship,"text").",
				civil_status =".GetSQLValueString($civil_status,"text").",
				
				lib_classification =".GetSQLValueString($lib_classification,"text").",
				lib_card_number =".GetSQLValueString($lib_card_number,"text").",
				
				home_address_zip =".GetSQLValueString($home_address_zip,"text").",
				home_address =".GetSQLValueString($home_address,"text").",
				tel_number =".GetSQLValueString($tel_number,"text").",
				mobile_number =".GetSQLValueString($mobile_number,"text").",
				suffix =".GetSQLValueString($suffix,"text").",
				nickname =".GetSQLValueString($nickname,"text").",
				city =".GetSQLValueString($city,"text").",
				fax =".GetSQLValueString($fax,"text").",         
				country =".GetSQLValueString($country,"int").",
				
				guardian_name =".GetSQLValueString($guardian_name,"text").",
				guardian_city =".GetSQLValueString($guardian_city,"text").",
				guardian_occupation =".GetSQLValueString($guardian_occupation,"text").",
				guardian_email =".GetSQLValueString($guardian_email,"text").",
				guardian_address =".GetSQLValueString($guardian_address,"text").",
				guardian_tel_number =".GetSQLValueString($guardian_tel_number,"text").",
				guardian_work_number =".GetSQLValueString($guardian_work_number,"text").",
				guardian_name =".GetSQLValueString($guardian_name,"text").",
				guardian_relation =".GetSQLValueString($guardian_relation,"text").",
				guardian_fax =".GetSQLValueString($guardian_fax,"text").",
				guardian_company =".GetSQLValueString($guardian_company,"text").",
				guardian_address_zip =".GetSQLValueString($guardian_address_zip,"text").",
				guardian_country =".GetSQLValueString($guardian_country,"text").",
				
				language =".GetSQLValueString($language,"text").",
				extra_curricular =".GetSQLValueString($extra_curricular,"text").",
				scholarship =".GetSQLValueString($scholarship,"text").",
				scholarship_type =".GetSQLValueString($scholarship_type,"text").",
				exam_grade =".GetSQLValueString($exam_grade,"text").",
				
				grade_school =".GetSQLValueString($grade_school,"text").",
				grade_school_address =".GetSQLValueString($grade_school_address,"text").",
				grade_school_years =".GetSQLValueString($grade_school_years,"text").",
				grade_school_award =".GetSQLValueString($grade_school_award,"text").",
				high_school =".GetSQLValueString($high_school,"text").",
				high_school_address =".GetSQLValueString($high_school_address,"text").",
				high_school_years =".GetSQLValueString($high_school_years,"text").",
				high_school_award =".GetSQLValueString($high_school_award,"text").",
				college_school =".GetSQLValueString($college_school,"text").",
				college_school_address =".GetSQLValueString($college_school_address,"text").",
				college_school_years =".GetSQLValueString($college_school_years,"text").",
				college_school_award =".GetSQLValueString($college_school_award,"text").",
				
				date_modified = ".time() .",
				modified_by = ".USER_ID." 						
				WHERE id =" .$id;
				
				//REQUIREMENTS
				if(count($req)>0)
				{
					$sqldel = "DELETE FROM tbl_student_requirements WHERE student_id=".$id;
					mysql_query($sqldel);
					
					foreach($req as $val)
					{
						$sqlReq = "INSERT INTO tbl_student_requirements 
					(		
						student_id,
						requirement_id                                                                 
					) 
					VALUES 
					(
						".GetSQLValueString($id,"text").",
						".GetSQLValueString($val,"text")."
					)";
						$queryReq = mysql_query($sqlReq);
					}
				}
				
			
			if($imgData != '')
			{
				if(mysql_query ($sql_student))
				{
					$sql_photo ="DELETE FROM tbl_student_photo WHERE student_id =" .$id ;
					$qry_photo = mysql_query($sql_photo);
					
					$sql_insert = "INSERT INTO tbl_student_photo 
					(	
						student_id,	
						image_type,
						image_file                                                                 
					) 
					VALUES 
					(
						".GetSQLValueString($id,"text").",
						".GetSQLValueString($file_type,"text").",
						'".$imgData."'
					)";
	
					if(mysql_query ($sql_insert))
					{
						$sql_guardian = "UPDATE tbl_parent SET
						name =".GetSQLValueString($guardian_name,"text").",
						relation =".GetSQLValueString($guardian_relation,"text").",
						email =".GetSQLValueString($guardian_email,"text").",
						date_modified = ".time() .",
						modified_by = ".USER_ID." 						
						WHERE student_id =" .$id;
						if(mysql_query ($sql_guardian))
						{
							$sql_stud_user = "SELECT * FROM tbl_student WHERE id=" .$id;
							$qry_stud_user = mysql_query($sql_stud_user);
							$row_stud_user = mysql_fetch_array($qry_stud_user);
							
							$gen_salt = generateSaltString();
							$userN = explode('-',$student_number);
							
							for($c = 1;$c<=count($userN);$c++)
							{
								if($c==count($userN)){
									$userNm = $lastname.'_'.$userN[$c-1];
									$password = md5('mint'.$userN[$c-1].$gen_salt);
								}
							}
	
							$sql_username = "UPDATE tbl_user SET
							username =".GetSQLValueString($userNm,"text").",
							password =".GetSQLValueString($password,"text").",
							salt =".GetSQLValueString($gen_salt,"text")."						
							WHERE id =" .$row_stud_user['user_id'];
							
							if(mysql_query ($sql_username))
							{
								$sql_guar_user = "SELECT * FROM tbl_parent WHERE student_id=" .$id;
								$qry_guar_user = mysql_query($sql_guar_user);
								$row_guar_user = mysql_fetch_array($qry_guar_user);
	
								$sql_username_guardian = "UPDATE tbl_user SET
								username =".GetSQLValueString('p'.$student_number,"text")."					
								WHERE id =" .$row_guar_user['user_id'];;
								if(mysql_query ($sql_username_guardian))
								{
									echo '<script language="javascript">alert("Student Successfully Updated.");window.location =\'index.php?comp=com_student_profile\';</script>';
								}
							}	
						}	
					}
				}
			}
			else
			{
				if(mysql_query ($sql_student))
				{
					$sql_guardian = "UPDATE tbl_parent SET
					name =".GetSQLValueString($guardian_firstname.' '.$guardian_middlename.' '.$guardian_lastname,"text").",        
					relation =".GetSQLValueString($guardian_relation,"text").",
					email =".GetSQLValueString($guardian_email,"text").",
					date_modified = ".time() .",
					modified_by = ".USER_ID." 						
					WHERE student_id =" .$id;
					
					if(mysql_query ($sql_guardian))
					{						
						$sql_stud_user = "SELECT * FROM tbl_student WHERE id=" .$id;
						$qry_stud_user = mysql_query($sql_stud_user);
						$row_stud_user = mysql_fetch_array($qry_stud_user);
						
						$gen_salt = generateSaltString();
						$userN = explode('-',$student_number);
						
						for($c = 1;$c<=count($userN);$c++)
						{
							if($c==count($userN)){
								$userNm = $lastname.'_'.$userN[$c-1];
								$password = md5('mint'.$userN[$c-1].$gen_salt);
							}
						}

						$sql_username = "UPDATE tbl_user SET
						username =".GetSQLValueString($userNm,"text").",
						password =".GetSQLValueString($password,"text").",
						salt =".GetSQLValueString($gen_salt,"text")."						
						WHERE id =" .$row_stud_user['user_id'];
						
						if(mysql_query ($sql_username))
						{
							$sql_guar_user = "SELECT * FROM tbl_parent WHERE student_id=" .$id;
							$qry_guar_user = mysql_query($sql_guar_user);
							$row_guar_user = mysql_fetch_array($qry_guar_user);

							$sql_username_guardian = "UPDATE tbl_user SET
							username =".GetSQLValueString('p'.$student_number,"text")."					
							WHERE id =" .$row_guar_user['user_id'];
							if(mysql_query ($sql_username_guardian))
							{
								echo '<script language="javascript">alert("Student Successfully Updated.");window.location =\'index.php?comp=com_student_profile\';</script>';
							}
						}	
					}
				}
			}
		}
	}
}
else if($action == 'import_fin')
{
		for($ctr=1;$ctr<=$num;$ctr++){
			$field[]=$_REQUEST['field_'.$ctr];
		}

		$wId=array_keys($field, "admission_type");
		$key=$wId[0]; 
		$wId2=array_keys($field, "course_id");
		$key2=$wId2[0]; 
		$wId3=array_keys($field, "lastname");
		$key3=$wId3[0]; 
		$wId4=array_keys($field, "firstname");
		$key4=$wId4[0];
		$wId5=array_keys($field, "middlename");
		$key5=$wId5[0];
		$wId6=array_keys($field, "student_number");
		$key6=$wId6[0];
		$wId7=array_keys($field, "birth_date");
		$key7=$wId7[0];
		$wId8=array_keys($field, "email");
		$key8=$wId8[0];
		$wId9=array_keys($field, "pname");
		$key9=$wId9[0];
		$wId10=array_keys($field, "pemail");
		$key10=$wId10[0];
		$wId11=array_keys($field, "year_level");
		$key11=$wId11[0];
		
		$uniques = array_unique($field);
		$dups = array_diff_assoc($field, $uniques);
		$null = array_values($field);

		if (in_array("", $null)) {		
		
			$err_msg = "Import Unsuccessful Empty Fields has been set! ";
			
		}else if(count($dups) >= 1){
			
			$err_msg = "Import Unsuccessful Fields not set properly! ";
			
		}
		else
		{	
		$studArr= 0;
		$corArr= 0;
		$emArr = 0;
		$nullArr= 0;
		$pemArr =  0;
		$valEm =  0;
		$ctr = 1;
		
		$handle = fopen($uploadfile, 'r');
     while (($data = fgetcsv($handle, 1000, ",")and($field)) !== FALSE)

     {
	 	//print_r($data);
		$blnk = array_values($data);
		
		$studNum = $data["".$key6.""];
			
		if(!checkIfCourseCodeExist($data["".$key2.""])){
			$corArr++;
		}
		else
		{
			$studNum = $data["".$key6.""]==''?generateStudentNumber($data["".$key2.""],$ctr):$data["".$key6.""];
		}
		if(checkStudentIDExist($studNum)){
			$studArr++;
		}
		if(checkIfStudentEmailExist($data["".$key8.""])){
			$emArr++;
		}
		if(checkIfParentEmailExist($data["".$key10.""])){
			$pemArr++;
		}
		if(!isValidEmail($data["".$key10.""]) || !isValidEmail($data["".$key8.""])){
			$valEm++;
		}		
		if(in_array("", $blnk)){
			$nullArr++;
			if($data["".$key6.""]=='')
			{
				--$nullArr;
			}
		}
		$ctr++;
	}
	fclose($handle);
	}
		
		
			/*echo '<pre>';
			print_r($studArr);
			print_r($corArr);
			print_r($nullArr);
			echo '</pre>';*/
			
	if(($studArr >= 1 )or($corArr >= 1 )
				or ($emArr >=1) or ($nullArr >=1) or ($valEm >=1)){
				
			$err_msg = "Import Interrupted, ";
			if($studArr >= 1 ){
			$err_msg ="Student Number Already Exist.";
			}
			if($corArr >= 1) {
			$err_msg ="Course Do Not Exist.";
			}
			if($emArr >= 1) {
			$err_msg ="Email Address Already Exist.";
			}
			if($nullArr >=1){
			$err_msg = "Invalid Empty CSV Data.".$nullArr;
			}
			if($valEm >=1){
			$err_msg = "Invalid Email Address was found.";
			}
		}
		else
		{	
		$handle2 = fopen($uploadfile, 'r');
		while (($data = fgetcsv($handle2, 1000, ",")and($field)) !== FALSE)	
		{	
	
			$sqlcor = "SELECT id FROM tbl_course WHERE 
					course_code = '".$data["".$key2.""]."'";
			$resultcor = mysql_query($sqlcor);
			$row  = mysql_fetch_array($resultcor);
			$did = $row["id"];
			
			$cid = getCurriculumByCourseId($did);
			
			if($data["".$key.""] == 'F')
			{
				$yir = 1;
			}
			else
			{
				$yir = $data["".$key11.""];
			}
			
			$date = $data["".$key7.""];
	  		$bdate = preg_replace( "|\b(\d+)/(\d+)/(\d+)\b|", "\\3-\\1-\\2", $date );
			
			//MODIFIED BY MKT
				$gen_salt = generateSaltString();
				$gen_pass = generatePassword(); 
				$password = md5($gen_pass . $gen_salt);
				$userN = explode('-',$studNum);
				
				for($c = 1;$c<=count($userN);$c++)
				{
					if($c==count($userN)){
						$userNm = $lastname.'_'.$userN[$c];
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
			
			/*$sql_user = "INSERT INTO tbl_user 
				(		
					username,
					verification_code,
					access_id                                                                 
				) 
				VALUES 
				(
					'".$studNum."',
					".GetSQLValueString($veri_code,"text").",
					".GetSQLValueString('6',"int")."
				)";*/
		
		
			
		if(mysql_query ($sql_user))
		{
			$user_id = mysql_insert_id();
			
		  $sql = "INSERT into tbl_student 
		   			( user_id,
					  curriculum_id,
					  year_level,
					  start_year_level,
					  term_id,
					 ".$field["".$key2.""].",
					 ".$field["".$key3.""].",
					 ".$field["".$key.""].",
					 ".$field["".$key4.""].",
					 ".$field["".$key5.""].",
					 ".$field["".$key6.""].",
					 ".$field["".$key7.""].",
					 ".$field["".$key8.""].",
					 guardian_name,
					 guardian_email,
					 date_created,
					 created_by,				
					 date_modified,
					 modified_by ) 
		   		values
		   			(".GetSQLValueString($user_id,"int").",
					 '".$cid."',
					 '".$yir."',
					 '".$yir."',
					 '".CURRENT_TERM_ID."',
					 '".$did."',
					 '".$data["".$key3.""]."',
					 '".$data["".$key.""]."',
		   			 '".$data["".$key4.""]."',
					 '".$data["".$key5.""]."',
					 '".$studNum."',
					 '".$bdate."',
					 '".$data["".$key8.""]."',
					 '".$data["".$key9.""]."',
					 '".$data["".$key10.""]."',
					 ".time().", 
					 ".USER_ID.", 
					 ".time().",
					 ".USER_ID.")";
	   		
			if(mysql_query ($sql))
					{
					$student_id = mysql_insert_id();
					
					//MODIFIED BY MKT
				$gen_salt = generateSaltString();
				$gen_pass = generatePassword(); 
				$password = md5($gen_pass . $gen_salt);
				$userN = explode('-',$studNum);
				
				for($c = 1;$c<=count($userN);$c++)
				{
					if($c==count($userN)){
						$userNm = 'p'.$lastname.'_'.$userN[$c];
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
					
				/*$sql_guardian_user = "INSERT INTO tbl_user 
				(		
					username,
					verification_code,
					access_id                                                                 
				) 
				VALUES 
				(
					'".'p'.$studNum."',
					".GetSQLValueString($veri_code,"text").",
					".GetSQLValueString('7',"int")."
				)";*/
				
				mysql_query ($sql_guardian_user);
				$user_id = mysql_insert_id();
				
				$sql_guardian_info = "INSERT INTO tbl_parent 
				(		
					student_id,
					user_id,
					name,
					email,
					date_created, 
					created_by,
					date_modified,
					modified_by                                                                 
				) 
				VALUES 
				(
					".GetSQLValueString($student_id,"int").",
					".GetSQLValueString($user_id,"int").",
					'".$data["".$key9.""]."',
					'".$data["".$key10.""]."',
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
			  }
			 }
			}
				if(mysql_query ($sql_guardian_info)){

					fclose($handle2);
				/*Modified by MKT	
				$sql_email = "SELECT * FROM tbl_student WHERE id =" .$student_id;
				$qry_email = mysql_query($sql_email);
				$row_email = mysql_fetch_array($qry_email);
				
				$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .$row_email['user_id'];
				$qry_ver_code = mysql_query($sql_ver_code);
				$row_ver_code = mysql_fetch_array($qry_ver_code);
				
				$contents = "";
				$from_header = "From:" .SCHOOL_SYS_EMAIL;
				
				$contents .= "SIS Application Details\n";
				$contents .= "========================================================\n\n";					
				$contents .= SCHOOL_SYS_URL."/index.php?comp=com_verify_student_account&verifycode=".$row_ver_code['verification_code']. "\n";		
				$contents .= "\n--------------------------------------------------------\n\n\n";	
				
				$subject= "SIS Application (for verification purpose)";
				
				$to = $row_email['email'];
				//mail($to, $subject, $contents, $from_header);
				
				if(@mail($to, $subject, $contents, $from_header))
				{		
					$sql_guardian = "SELECT * FROM tbl_parent WHERE student_id =" .$student_id;
					$qry_guardian = mysql_query($sql_guardian);
					$row_guardian = mysql_fetch_array($qry_guardian);
					
					$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .$row_guardian['user_id'];
					$qry_ver_code = mysql_query($sql_ver_code);
					$row_ver_code = mysql_fetch_array($qry_ver_code);
					
					$contents = "";
					$from_header = "From:" .SCHOOL_SYS_EMAIL;
					
					$contents .= "SIS Application Details\n";
					$contents .= "========================================================\n\n";					
					$contents .= SCHOOL_SYS_URL."/index.php?comp=com_verify_parent_account&verifycode=".$row_ver_code['verification_code']. "\n";		
					$contents .= "\n--------------------------------------------------------\n\n\n";	
					
					$subject= "SIS Application (for verification purpose)";
					
					$to = $row_guardian['email'];
					//mail($to, $subject, $contents, $from_header);
					
					if(@mail($to, $subject, $contents, $from_header))
					{		
						echo '<script language="javascript">alert("Student Successfully Added! Please check the email for verication code.");window.location =\'index.php?comp=com_student_profile\';</script>';
					}
					else
					{
						$err_msg = 'Some problem occured while sending your message please try again.';
					}
				}
				else
				{
					$err_msg = 'Some problem occured while sending your message please try again.';
				}	*/
			}
		}
	}	

else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	foreach($selected_item as $item)
	{
		if (checkStudentHasRecord($item))
		{
			$err_msg = 'Cannot delete ' . getStudentFullName($item). '. Currently record is used.';
		}
		else
		{	
			if($item !='') 
			{
				$sql_stud_id ="SELECT * FROM tbl_student WHERE id =" .$item;
				$qry_stud_id = mysql_query($sql_stud_id);
				$row_stud_id = mysql_fetch_array($qry_stud_id);
				
				$sql_parent_id ="SELECT * FROM tbl_parent WHERE student_id =" .$row_stud_id['id'];
				$qry_parent_id = mysql_query($sql_parent_id);
				$row_parent_id = mysql_fetch_array($qry_parent_id);
				
				$sql_student = "DELETE FROM tbl_student WHERE id=" .$row_stud_id['id'];
				$sql_user = "DELETE FROM tbl_user WHERE id=" .$row_stud_id['user_id'];
				$sql_photo = "DELETE FROM tbl_student_photo WHERE student_id=" .$row_stud_id['id'];
				$sql_parent_user = "DELETE FROM tbl_user WHERE id=" .$row_parent_id['user_id'];
				$sql_parent = "DELETE FROM tbl_parent WHERE student_id=" .$row_stud_id['id'];

				mysql_query ($sql_student);
				mysql_query ($sql_student_info);
				mysql_query ($sql_user);
				mysql_query ($sql_photo);
				mysql_query ($sql_parent_user);
				mysql_query ($sql_parent);
			}
		}
	}

	if(count($arr_str) > 0)
	{
		echo '<script language="javascript">alert("'.implode("\n",$arr_str).'");</script>';
	}

}
else if($action == 'publish')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		storedModifiedLogs(tbl_student, $id);
		$sql = "UPDATE tbl_student SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		storedModifiedLogs(tbl_student, $id);
		$sql = "UPDATE tbl_student SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
	}
}
else if($action == 'uplod')
{
	$file = $_FILES['importfile']['name'];
	$sub = $_REQUEST['imports'];
	$type = explode(".", $file);
	
	if($file == '')
	{
		$err_msg = 'Select a CSV file';
	}
	else if($type[1] != 'csv')
	{
		$err_msg = 'Invalid File';
	}
	else
	{		
	 if (is_uploaded_file($_FILES['importfile']['tmp_name'])) {
	   
	   }
	
	$uploaddir = 'uploads/';
	$uploadfile = $uploaddir . basename($_FILES['importfile']['name']);
	
	if (move_uploaded_file($_FILES['importfile']['tmp_name'], $uploadfile)) {
		
	} 

     $handle = fopen("$uploadfile", "r");

     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)

     {
		$num = count($data);
     }

     fclose($handle);
	 }

}

if($view == 'edit')
{
	$sql = "SELECT * FROM tbl_student WHERE id = " .$id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);

	// STUDENT //
					
	$student_number        	= $row['student_number'] != $student_number ? $row['student_number'] : $student_number;
	$course_id             	= $row['course_id'] != $course_id ? $row['course_id'] : $course_id;
	$curriculum_id          = $row['curriculum_id'] != $curriculum_id ? $row['curriculum_id'] : $curriculum_id;
	$year_level             = $row['year_level'] != $year_level ? $row['year_level'] : $year_level;
	$admission_type  		= $row['admission_type'] != $admission_type ? $row['admission_type'] : $admission_type;
											  
	$firstname              = $row['firstname'] != $firstname ? $row['firstname'] : $firstname;
	$middlename             = $row['middlename'] != $middlename ? $row['middlename'] : $middlename;
	$lastname               = $row['lastname'] != $lastname ? $row['lastname'] : $lastname;
	$nickname               = $row['nickname'] != $nickname ? $row['nickname'] : $nickname;
	$suffix                 = $row['suffix'] != $suffix ? $row['suffix'] : $suffix;
	$email                  = $row['email'] != $email ? $row['email'] : $email;
	$con_email              = $row['email'] != $con_email ? $row['email'] : $con_email;
	
	$birth_date 			= $row['birth_date'] != $birth_date ? $row['birth_date'] : $birth_date;
	
	
	$birth_date = explode("-", $birth_date);	
	
	$b_year = $birth_date[0];
	$b_month = $birth_date[1];
	$b_day = $birth_date[2];
	
	$birth_place            = $row['birth_place'] != $birth_place ? $row['birth_place'] : $birth_place;
	$gender                 = $row['gender'] != $gender ? $row['gender'] : $gender;
	$citizenship            = $row['citizenship'] != $citizenship ? $row['citizenship'] : $citizenship;
	$civil_status           = $row['civil_status'] != $civil_status ? $row['civil_status'] : $civil_status;
	$home_address      = $row['home_address'] != $home_address ? $row['home_address'] : $home_address;
	$home_address_zip  = $row['home_address_zip'] != $home_address_zip ? $row['home_address_zip'] : $home_address_zip;
	$tel_number             = $row['tel_number'] != $tel_number ? $row['tel_number'] : $tel_number;
	$mobile_number          = $row['mobile_number'] != $mobile_number ? $row['mobile_number'] : $mobile_number;
	$city                 = $row['city'] != $city ? $row['city'] : $city;
	$fax            = $row['fax'] != $fax ? $row['fax'] : $fax;
	$country           = $row['country'] != $country ? $row['country'] : $country;
	
	//GUARDIAN
	$guardian_name          = $row['guardian_name'] != $guardian_name ? $row['guardian_name'] : $guardian_name;
	$guardian_address          = $row['guardian_address'] != $guardian_address ? $row['guardian_address'] : $guardian_address;
	$guardian_address_zip          = $row['guardian_address_zip'] != $guardian_address_zip ? $row['guardian_address_zip'] : $guardian_address_zip;
	$guardian_city          = $row['guardian_city'] != $guardian_city ? $row['guardian_city'] : $guardian_city;
	$guardian_occupation          = $row['guardian_occupation'] != $guardian_occupation ? $row['guardian_occupation'] : $guardian_occupation;
	$guardian_tel_number          = $row['guardian_tel_number'] != $guardian_tel_number ? $row['guardian_tel_number'] : $guardian_tel_number;
	$guardian_work_number          = $row['guardian_work_number'] != $guardian_work_number ? $row['guardian_work_number'] : $guardian_work_number;
	$guardian_fax          = $row['guardian_fax'] != $guardian_fax ? $row['guardian_fax'] : $guardian_fax;
	$guardian_country          = $row['guardian_country'] != $guardian_country ? $row['guardian_country'] : $guardian_country;
	$guardian_company          = $row['guardian_company'] != $guardian_company ? $row['guardian_company'] : $guardian_company;
	$guardian_relation          = $row['guardian_relation'] != $guardian_relation ? $row['guardian_relation'] : $guardian_relation;
	$guardian_email          = $row['guardian_email'] != $guardian_email ? $row['guardian_email'] : $guardian_email;
	$con_guardian_email          = $row['con_guardian_email'] != $con_guardian_email ? $row['con_guardian_email'] : $con_guardian_email;
	
	//LIBRARY
	$lib_classification			= $row['lib_classification'] != $lib_classification ? $row['lib_classification'] : $lib_classification;
	$lib_card_number		= $row['lib_card_number'] != $lib_card_number ? $row['lib_card_number'] : $lib_card_number;
	
	//LAST SCHOOL
	
	$grade_school		= $row['grade_school'] != $grade_school ? $row['grade_school'] : $grade_school;
	$grade_school_address		= $row['grade_school_address'] != $grade_school_address ? $row['grade_school_address'] : $grade_school_address;
	
	$grade_school_years = explode('-',$row['grade_school_years']);
	$grade_school_year1 = explode('/',$grade_school_years[0]);
	$grade_school_year2 = explode('/',$grade_school_years[1]);
	
	$grade_mfr		= $grade_school_year1[0];
	$grade_yfr		= $grade_school_year1[1];
	$grade_mto		= $grade_school_year2[0];
	$grade_yto		= $grade_school_year2[1];
	
	$grade_school_years		= $row['grade_school_years'] != $grade_school_years ? $row['grade_school_years'] : $grade_school_years;
	$grade_school_award		= $row['grade_school_award'] != $grade_school_award ? $row['grade_school_award'] : $grade_school_award;
	
	$high_school		= $row['high_school'] != $high_school ? $row['high_school'] : $high_school;
	$high_school_address		= $row['high_school_address'] != $high_school_address ? $row['high_school_address'] : $high_school_address;
	
	$high_school_years = explode('-',$row['high_school_years']);
	$high_school_year1 = explode('/',$high_school_years[0]);
	$high_school_year2 = explode('/',$high_school_years[1]);
	
	$high_mfr		= $high_school_year1[0];
	$high_yfr		= $high_school_year1[1];
	$high_mto		= $high_school_year2[0];
	$high_yto		= $high_school_year2[1];
	
	$high_school_years		= $row['high_school_years'] != $high_school_years ? $row['high_school_years'] : $high_school_years;
	$high_school_award		= $row['high_school_award'] != $high_school_award ? $row['high_school_award'] : $high_school_award;
	
	$college_school		= $row['college_school'] != $college_school ? $row['college_school'] : $college_school;
	$college_school_address		= $row['college_school_address'] != $college_school_address ? $row['college_school_address'] : $college_school_address;
	
	$college_school_years = explode('-',$row['college_school_years']);
	$college_school_year1 = explode('/',$college_school_years[0]);
	$college_school_year2 = explode('/',$college_school_years[1]);
	
	$college_mfr		= $college_school_year1[0];
	$college_yfr		= $college_school_year1[1];
	$college_mto		= $college_school_year2[0];
	$college_yto		= $college_school_year2[1];
	
	$college_school_years		= $row['college_school_years'] != $college_school_years ? $row['college_school_years'] : $college_school_years;
	$college_school_award		= $row['college_school_award'] != $college_school_award ? $row['college_school_award'] : $college_school_award;
	
	// STUDENT PHOTO//
	
	$sql_photo = "SELECT * FROM tbl_student_photo WHERE student_id =" .$id;
	$query_photo = mysql_query ($sql_photo);
	$row_photo = mysql_fetch_array($query_photo);
	
	
	$image_type              = $row_photo['image_type'] != $image_type ? $row_photo['image_type'] : $image_type;
	$image_file              = $row_photo['image_file'] != $image_file ? $row_photo['image_file'] : $image_file;

	$scholarship			= $row['scholarship'] != $scholarship ? $row['scholarship'] : $scholarship;
	$scholarship_type		= $row['scholarship_type'] != $scholarship_type ? $row['scholarship_type'] : $scholarship_type;
	
	$exam_grade		= $row['exam_grade'] != $exam_grade ? $row['exam_grade'] : $exam_grade;
}
else if($view == 'add')
{
	
	// STUDENT //
$user_id				= $_REQUEST['user_id'];					
$student_number         = $_REQUEST['student_number'];                                                                  
$course_id              = $_REQUEST['course_id'];                                                                   
$curriculum_id          = $_REQUEST['curriculum_id'];                                                                
$year_level             = $_REQUEST['year_level'];                                                                  
$admission_type  		= $_REQUEST['admission_type'];
                                          
$firstname              = $_REQUEST['firstname'];                             
$middlename             = $_REQUEST['middlename'];                             
$lastname               = $_REQUEST['lastname'];                          
$suffix                 = $_REQUEST['suffix'];                          
$email                  = $_REQUEST['email'];
$con_email              = $_REQUEST['con_email'];

$b_year             	= $_REQUEST['b_year'];
$b_month             	= $_REQUEST['b_month'];
$b_day             		= $_REQUEST['b_day'];

$birth_date 			= $year.'-'.$month.'-'.$day;

$birth_place            = $_REQUEST['birth_place'];                           
$gender                 = $_REQUEST['gender'];                          
$citizenship            = $_REQUEST['citizenship'];                                       
$civil_status           = $_REQUEST['civil_status'];                                         
$religion               = $_REQUEST['religion'];                                          
$father_name            = $_REQUEST['father_name'];                                           
$father_occupation      = $_REQUEST['father_occupation'];                                          
$mother_name            = $_REQUEST['mother_name'];                                           
$mother_occupation      = $_REQUEST['mother_occupation'];                                         
$guardian_name          = $_REQUEST['guardian_name'];
$annual_family_income   = $_REQUEST['annual_family_income'];                                            
$present_address        = $_REQUEST['present_address'];                                            
$present_address_zip    = $_REQUEST['present_address_zip'];                                           
$permanent_address      = $_REQUEST['permanent_address'];                                          
$permanent_address_zip  = $_REQUEST['permanent_address_zip'];                                       
$tel_number             = $_REQUEST['tel_number'];                                     
$mobile_number          = $_REQUEST['mobile_number'];  

if($_REQUEST['last_school']!=0)
	{
		$sql_last = "SELECT * FROM tbl_school_list WHERE id =" .$_REQUEST['last_school'];
		$query_last = mysql_query($sql_last);
		$row_last = mysql_fetch_array($query_last);
		
		$last_school            = $row_last['id'];
		$last_school_address    = $row_last['address'];
		$last_school_type       = $row_last['school_type'];
		$last_school_code       = $row_last['school_code'];
	}
	else
	{	
		$last_school            = '0';
		$last_school_1          = $_REQUEST['last_school_1'];
		$last_school_address    = $_REQUEST['last_school_address'];
		$last_school_type       = $_REQUEST['last_school_type'];
		$last_school_code       = $_REQUEST['last_school_code'];
	}
                                                                            
$ice_fullname           = $_REQUEST['ice_fullname'];                                    
$ice_address            = $_REQUEST['ice_address'];                                       
$ice_tel_number			= $_REQUEST['ice_tel_number'];

// USER //

$username 				= $_REQUEST['username'];                                                                 
$password               = $_REQUEST['password'];                                                    
$access_id              = $_REQUEST['access_id'];                                                    
$blocked                = $_REQUEST['blocked'];    

// GUARDIAN //

$guardian_firstname 	= $_REQUEST['guardian_firstname'];
$guardian_middlename    = $_REQUEST['guardian_middlename'];                                                    
$guardian_lastname      = $_REQUEST['guardian_lastname'];                                                    
$guardian_relation      = $_REQUEST['guardian_relation']; 
$guardian_email         = $_REQUEST['guardian_email'];                        
$con_guardian_email     = $_REQUEST['con_guardian_email'];

//LIBRARY
$lib_classification			= $_REQUEST['lib_classification'];
$lib_card_number			= $_REQUEST['lib_card_number'];

$exam_grade					= $_REQUEST['exam_grade'];
}

if($view == 'map'){ 

}



// component block, will be included in the template page
$content_template = 'components/block/blk_com_student_profile.php';
?>