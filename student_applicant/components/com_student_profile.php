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

$page_title = 'Online Application';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];
$acc = $_REQUEST['acc'];

// STUDENT //		
$term_id				= $_REQUEST['term_id'];	
                                                                  
$course_id              = $_REQUEST['course_id'];                                                                   
$curriculum_id          = getCurriculumByCourseId($_REQUEST['course_id']);                                                              
$year_level             = $_REQUEST['year_level'];                                                                  
$admission_type  		= $_REQUEST['admission_type'];
                                          
$firstname              = $_REQUEST['firstname'];                             
$middlename             = $_REQUEST['middlename'];                             
$lastname               = $_REQUEST['lastname']; 
$nickname               = $_REQUEST['nickname'];   
                      
$suffix                 = $_REQUEST['suf']=='other'?$_REQUEST['suffix']:$_REQUEST['suf']; 
                         
$email                  = $_REQUEST['email'];
$con_email              = $_REQUEST['con_email'];

$year             		= $_REQUEST['b_year'];
$month             		= $_REQUEST['b_month'];
$day             		= $_REQUEST['b_day'];

$bdate 					= array($year, $month, $day);
$birth_date 			= implode("-", $bdate);

$exam_date	            = $_REQUEST['exam_date'];
$birth_place            = $_REQUEST['birth_place'];                           
$gender                 = $_REQUEST['gen']==''?'M':$_REQUEST['gen'];                          
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
                      
$guardian_name          = $_REQUEST['guardian_name'];
$annual_family_income   = $_REQUEST['annual_family_income'];                                    
$guardian_address      	= $_REQUEST['guardian_address'];                                     
$guardian_address_zip   = $_REQUEST['guardian_address_zip']==''?0:$_REQUEST['guardian_address_zip'];                                  
$guardian_city    		= $_REQUEST['guardian_city'];                               
$guardian_occupation	= $_REQUEST['guardian_occupation'];                                      
$guardian_tel_number    = $_REQUEST['guardian_tel_number'];                                     
$guardian_work_number   = $_REQUEST['guardian_work_number'];                                   
$guardian_fax    		= $_REQUEST['guardian_fax'];  
$guardian_country    	= $_REQUEST['guardian_country']==''?'0':$_REQUEST['guardian_country'];          
$guardian_company       = $_REQUEST['guardian_company'];                                  
$guardian_relation      = $_REQUEST['guardian_relation'];                                       
$guardian_email			= $_REQUEST['guardian_email'];
$con_guardian_email		= $_REQUEST['con_guardian_email'];

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

$language  				= $_REQUEST['language'];
$extra_curricular		= $_REQUEST['extra_curricular'];

$company	            = $_REQUEST['comp'];
$position            	= $_REQUEST['post'];                           
$name                 	= $_REQUEST['e_name'];
$dates_employed        	= $_REQUEST['dates'];     
                                  
$sname          		= $_REQUEST['s_name'];
$age      				= $_REQUEST['sage'];                                     
$education_attain		= $_REQUEST['educ'];  
$last_school   			= $_REQUEST['school'];

// THIS IS FOR IMAGE //

$file_ext=explode(".",$_FILES['student_photo']['name']);
$file_type=strtolower($file_ext[1]);
if(isset($_FILES['student_photo']['tmp_name']) && $_FILES['student_photo']['tmp_name']!= '')
{
	$imgData = addslashes(file_get_contents($_FILES['student_photo']['tmp_name']));
}

if($action == 'save')
{
	if($lastname==''||$firstname==''||$middlename==''||$gender==''||$civil_status==''||$citizenship==''||$birth_date==''||$birth_place==''||$tel_number==''||$mobile_number==''||$email==''||$guardian_name==''||$guardian_relation==''||$country==''||$term_id==''||$course_id==''||$admission_type==''||$year_level=='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(!isset($_REQUEST['agree']))
	{
		$err_msg = 'Agreement not checked.';
	}
	/*
	else if(!is_numeric($home_address_zip) || !is_numeric($guardian_address_zip))
	{
		$err_msg = 'Invalid Postal Code';
	}
	*/
	else if($con_email == '' )
	{
		$err_msg = 'Please confirm student email.';
	}
	else if($email != $con_email)
	{
		$err_msg = 'Student Email does not match.';
	}
	else if(isValidEmail($guardian_email)===false && $guardian_email != '')
	{
		$err_msg = 'Please enter a valid guardian email.';
	}	
	else if($con_guardian_email == ''  && $guardian_email != '')
	{
		$err_msg = 'Please confirm guardian email.';
	}
	else if($guardian_email != $con_guardian_email && $guardian_email != '')
	{
		$err_msg = 'Guardian email does not match.';
	}
	else if (checkIfStudentEmailExist($email))
	{
		$err_msg = 'Student email address already exist.';
	}
	else if (checkIfParentEmailExist($guardian_email) && $guardian_email != '')
	{
		$err_msg = 'Guardian email address already exist.';
	}
	else if (checkIfParentEmailEqualStudentEmail($guardian_email,$email) && $guardian_email != '')
	{
		$err_msg = 'Guardian email must be different from Student Email.';
	}
	else
	{		
			$sql_student_info = "INSERT INTO tbl_student_application
			(	        
				suffix,                                                     
				course_id,
				curriculum_id,                                                                 
				admission_type,
				year_level,
				entrance_date,
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
				term_id,
				date_created, 		
				date_modified,
				image_type,
				image_file   
			) 
			VALUES 
			(
				".GetSQLValueString($suffix,"int").",
				".GetSQLValueString($course_id,"int").",
				".GetSQLValueString($curriculum_id,"int").",	
				".GetSQLValueString($admission_type,"text").", 
				".GetSQLValueString($year_level,"text").",
				".GetSQLValueString($exam_date,"int").",
				".GetSQLValueString($firstname,"text").",
				".GetSQLValueString($middlename,"text").",
				".GetSQLValueString($lastname,"text").",
				".GetSQLValueString($nickname,"text").",
				".GetSQLValueString($email,"text").",
				".GetSQLValueString($birth_date,"text").",
				".GetSQLValueString($birth_place,"text").",
				".GetSQLValueString($gender,"text").",
				".GetSQLValueString($citizenship,"text").",
				".GetSQLValueString($civil_status,"text").",
				".GetSQLValueString($city,"text").",
				".GetSQLValueString($fax,"text").",
				".GetSQLValueString($country,"text").",
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
				".GetSQLValueString($term_id,"text").",
				".time().",
				".time().",
				".GetSQLValueString($file_type,"text").",
				".GetSQLValueString($imgData,"text")."				   
			)";

			if(mysql_query ($sql_student_info) or die(mysql_error()) )
			{	
				$stud_id = mysql_insert_id();
				$ctr = 0 ;
				if(count($company)>0)
				{
				foreach($company as $comp)
				{
					$sql = "INSERT INTO tbl_experience
							( 
								student_id,
								company,
								position,
								supervisor, 
								dates_employed								
							) 
							VALUES 
							(
								".GetSQLValueString($stud_id,"text").", 
								".GetSQLValueString($comp[$ctr],"text").", 
								".GetSQLValueString($position[$ctr],"text").", 
								".GetSQLValueString($name[$ctr],"text").", 
								".GetSQLValueString($dates_employed[$ctr],"text")."
							)";
							
					mysql_query ($sql);	
					$ctr ++;
					}
				}
					$cnt = 0 ;
				if(count($sname)>0)
				{
				foreach($sname as $name)
				{
					$sql = "INSERT INTO tbl_siblings
							( 
								student_id,
								name,
								age,
								education_attain, 
								last_school								
							) 
							VALUES 
							(
								".GetSQLValueString($stud_id,"text").", 
								".GetSQLValueString($name[$cnt],"text").", 
								".GetSQLValueString($age[$cnt],"text").", 
								".GetSQLValueString($education_attain[$cnt],"text").", 
								".GetSQLValueString($last_school[$cnt],"text")."
							)";
							
					mysql_query ($sql);	
					$cnt ++;
				}
				}
				
				$contents = "";
				$from_header = "From:" .SCHOOL_SYS_EMAIL;
				
				$contents .= "Hello ".$firstname." ".$middlename." ".$lastname.",\n\n";
				$contents .= "	Thank You for using ".SCHOOL_NAME." online enrollment application.\n";					
				$contents .= "You should bring a valid ID with photo, preferably your high school ID and your Admission Requirements as ".getStudentAdmissionType($admission_type).". \n";	
								$contents .= "	The following requirements must be submitted at the Registrars Office before a student is admitted to any ".SCHOOL_NAME." academic program. \n\n";
				
				foreach(getStudentRequirements($admission_type) as $reqs)
				{
					$contents .= "	- ".$reqs."\n";
				}
				
				$contents .= "\n Thank You very much and Goodluck! \n\n\n";	
				$contents .= "Best Regards,\n";
				$contents .= "School Administrator\n";		
				
				$subject= "SIS Application";
				
				$to = $email; 
				
					if(mail($to, $subject, $contents, $from_header))
					{
						echo '<script language="javascript">alert("Successfully Added, Confirmation is sent to your email.");</script>';
						echo '<script language="javascript">window.location =\'index.php?comp=com_student_profile\';</script>';
					}

		}

	}	
}
else if($view == 'add')
{
$term_id				= $_REQUEST['term_id'];	
                                                                  
$course_id              = $_REQUEST['course_id'];                                                                   
$curriculum_id          = getCurriculumByCourseId($_REQUEST['course_id']);                                                              
$year_level             = $_REQUEST['year_level'];                                                                  
$admission_type  		= $_REQUEST['admission_type'];
                                          
$firstname              = $_REQUEST['firstname'];                             
$middlename             = $_REQUEST['middlename'];                             
$lastname               = $_REQUEST['lastname']; 
$nickname               = $_REQUEST['nickname'];   
                      
$suffix                 = $_REQUEST['suf']; 
                         
$email                  = $_REQUEST['email'];
$con_email              = $_REQUEST['con_email'];

$year             		= $_REQUEST['b_year'];
$month             		= $_REQUEST['b_month'];
$day             		= $_REQUEST['b_day'];

$bdate 					= array($year, $month, $day);
$birth_date 			= implode("-", $bdate);

$exam_date	            = $_REQUEST['exam_date'];
$birth_place            = $_REQUEST['birth_place'];                           
$gender                 = $_REQUEST['gen'];                          
$citizenship            = $_REQUEST['citizenship'];                                       
$civil_status           = $_REQUEST['civil_status'];
$home_address      		= $_REQUEST['home_address'];                                     
$home_address_zip    	= $_REQUEST['home_address_zip'];  
$city      				= $_REQUEST['city'];                                     
$fax    				= $_REQUEST['fax'];  
$country    			= $_REQUEST['country']; 
$tel_number             = $_REQUEST['tel_number'];                                     
$mobile_number          = $_REQUEST['mobile_number'];

$student_photo			= $_FILES['student_photo']['name'];
                      
$guardian_name          = $_REQUEST['guardian_name'];
$annual_family_income   = $_REQUEST['annual_family_income'];                                    
$guardian_address      	= $_REQUEST['guardian_address'];                                     
$guardian_address_zip   = $_REQUEST['guardian_address_zip'];                                  
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

$grade_school			= $_REQUEST['grade_school'];
$grade_school_address	= $_REQUEST['grade_school_address'];

$grade_mfr 				= $_REQUEST['grade_school_month_fr'];
$grade_yfr 				= $_REQUEST['grade_school_year_fr'];
$grade_mto  			= $_REQUEST['grade_school_month_to'];
$grade_yto 				= $_REQUEST['grade_school_year_to'];

$grade_school_award		= $_REQUEST['grade_school_award'];

$high_school			= $_REQUEST['high_school'];
$high_school_address	= $_REQUEST['high_school_address'];

$high_mfr 				= $_REQUEST['high_school_month_fr'];
$high_yfr 				= $_REQUEST['high_school_year_fr'];
$high_mto  				= $_REQUEST['high_school_month_to'];
$high_yto 				= $_REQUEST['high_school_year_to'];

$high_school_award		= $_REQUEST['high_school_award'];

$college_school			= $_REQUEST['college_school'];
$college_school_address	= $_REQUEST['college_school_address'];

$college_mfr 			= $_REQUEST['college_school_month_fr'];
$college_yfr 			= $_REQUEST['college_school_year_fr'];
$college_mto  			= $_REQUEST['college_school_month_to'];
$college_yto 			= $_REQUEST['college_school_year_to'];

$college_school_award	= $_REQUEST['college_school_award'];

$company	            = $_REQUEST['comp'];
$position            	= $_REQUEST['post'];                           
$name                 	= $_REQUEST['e_name'];
$dates_employed        	= $_REQUEST['dates'];     
                                  
$sname          		= $_REQUEST['s_name'];
$age      				= $_REQUEST['sage'];                                     
$education_attain		= $_REQUEST['educ'];  
$last_school   			= $_REQUEST['school'];

$student_photo			= $_FILES['student_photo']['name'];
             
$ctr = 0;            
if(count($company)>0)
	{
	foreach($company as $comp)
	{
		$arr_str[] ='<tr id="row_'.$ctr.'">';
		  $arr_str[] ='<td><input name="comp[]" type="hidden" id="comp" value="'.$comp[$ctr].'" />' .$comp[$ctr]. '</td>';
		  $arr_str[] ='<td><input name="post[]" type="hidden" id="post" value="'.$position[$ctr].'" />' .$position[$ctr]. '</td>';
		  $arr_str[] ='<td><input name="e_name[]" type="hidden" id="e_name" value="'.$e_name[$ctr].'" />' .$e_name[$ctr]. '</td>';
		  $arr_str[] ='<td><input name="dates[]" type="hidden" id="dates" value="' .$dates_employed[$ctr]. '" />'.$dates_employed[$ctr].'</td>';
		  $arr_str[] ='<td class="action"><a href="#" class="remove" returnId="'.$ctr.'">Remove</a></td>';             
		$arr_str[] ='</tr>';
		
		$ctr ++;
	}
		$experience = implode('',$arr_str);
	}
	
	$ctr = 0;
	if(count($sname)>0)
	{
	foreach($sname as $name)
	{
		$arr_str[] ='<tr id="row_'.$ctr.'">';
		  $arr_str[] ='<td><input name="s_name[]" type="hidden" id="s_name" value="'.$name[$ctr].'" />' .$name[$ctr]. '</td>';
		  $arr_str[] ='<td><input name="sage[]" type="hidden" id="sage" value="'.$age[$ctr].'" />' .$age[$ctr]. '</td>';
		  $arr_str[] ='<td><input name="educ[]" type="hidden" id="educ" value="'.$education_attain[$ctr].'" />' .$education_attain[$ctr]. '</td>';
		  $arr_str[] ='<td><input name="school[]" type="hidden" id="school" value="' .$last_school[$ctr]. '" />'.$last_school[$ctr].'</td>';
		  $arr_str[] ='<td class="action"><a href="#" class="remove" returnId="'.$ctr.'">Remove</a></td>';             
		$arr_str[] ='</tr>';
		
		$ctr ++;
	}
		$siblings = implode('',$arr_str);
	}
}

// component block, will be included in the template page 
$content_template = 'components/block/blk_com_student_profile.php';
?>