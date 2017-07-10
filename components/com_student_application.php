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







$page_title = 'Manage Student Application';

$pagination = 'Student > Manage Student Application';



$view = $view==''?'list':$view; // initialize action 



$id	= $_REQUEST['id'];

$temp 	= $_REQUEST['temp'];



// STUDENT //

				

$num    = $_REQUEST['num'];  



$sId	= $_REQUEST['sid'];

$stud	= $_REQUEST['stud'];  

$cheks	= $_REQUEST['cheks'];

$reqs = $_REQUEST['chk'];    

$score = $_REQUEST['score'];

$number = $_REQUEST['number'];  

$scholar = $_REQUEST['scholar'];

$scholar_type = $_REQUEST['scholar_type'];

$veri_code = generateRandomString();

$veri_code_parent  = generateRandomString();



if($action == 'save')

{

	$checked = explode(',',$cheks);

	

	foreach($checked as $chek)

	{

	

			$sqlsy = "SELECT * FROM tbl_student_application WHERE id = ".$chek;

			$querysy = mysql_query($sqlsy);

			$rowsy = mysql_fetch_array($querysy);

			$term_id = $rowsy['term_id'];

			$school_id = getSchoolYearIdByTermId($rowsy['term_id']);

			$curriculum_id = getCurriculumByCourseId($rowsy['course_id']);

			

			/*if (checkIfStudentApplicantSchoolYear($school_id,$term_id))

			{

				$err_msg = 'You are not allowed to approve applications from previous or on going school year.';

			}

			else

			{*/
				
				//MODIFIED BY MKT
				$sqlname = "SELECT * FROM tbl_student_application WHERE id = ".$chek;
				$queryn = mysql_query($sqlname);
				$rowname = mysql_fetch_array($queryn);
				
				$gen_salt = generateSaltString();
				$userN = explode('-',$number[$chek]);
				
				for($c = 1;$c<=count($userN);$c++)
				{
					if($c==count($userN)){
						$userNm = $rowname['lastname'].'_'.$userN[$c-1];
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

				/*$sql_user = "INSERT INTO tbl_user 

						(		

							username,

							verification_code,

							access_id                                                                 

						) 

						VALUES 

						(

							".GetSQLValueString($number[$chek],"text").",

							".GetSQLValueString($veri_code,"text").",

							".GetSQLValueString('6',"int")."

						)";*/

				

					

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
						
						scholarship,
						
						scholarship_type,

						date_created, 

						created_by,

						date_modified,

						modified_by						                                                                  

					) 

					SELECT 

						".GetSQLValueString($user_id,"int").",

						".GetSQLValueString($number[$chek],"text").",                    

						course_id,

						".$curriculum_id.",                                                            

						admission_type,

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
						
						".GetSQLValueString($scholar[$chek],"text").",              
						".GetSQLValueString($scholarship_type[$chek],"text").",               

						".time().",

						".USER_ID.", 

						".time().",

						".USER_ID." FROM tbl_student_application WHERE id = ".$chek."				

					";

				

					if(mysql_query ($sql_student_info))

					{

						$student_id = mysql_insert_id();

						

						if(count($reqs[$chek])>0)

						{

							foreach($reqs[$chek] as $req)

							{

								$sql_req = "INSERT INTO tbl_student_requirements 

									(		

										student_id,

										requirement_id               

									) 

									VALUES 

									(

										".$student_id.",

										".$req."

									)";

								$query_req = mysql_query($sql_req);

							}

						}

						$sql_guardian_info = "INSERT INTO tbl_parent

						(	

							student_id,

							name,

							relation,

							email,

							date_created, 

							created_by,

							date_modified,

							modified_by                           

						) 

						SELECT 

							".GetSQLValueString($student_id,"int").",

							guardian_name,

							guardian_relation,

							guardian_email,

							".time().",

							".USER_ID.", 

							".time().",

							".USER_ID."

							FROM tbl_student_application WHERE id = ".$chek."				

						";

						if(mysql_query ($sql_guardian_info))

						{

							$sql_student_no = "SELECT * FROM tbl_student WHERE id =".$student_id;

							$qry_student_no = mysql_query($sql_student_no);

							$row_student_no = mysql_fetch_array($qry_student_no);

							
							//MODIFIED BY MKT
				$sqlname = "SELECT * FROM tbl_student_application WHERE id = ".$chek;
				$queryn = mysql_query($sqlname);
				$rowname = mysql_fetch_array($queryn);			
							
				$gen_salt = generateSaltString();
				$userN = explode('-',$number[$chek]);
				
				for($c = 1;$c<=count($userN);$c++)
				{
					if($c==count($userN)){
						$userNm = 'p'.$rowname['lastname'].'_'.$userN[$c-1];
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

								".GetSQLValueString('p'.$number[$chek],"text").",

								".GetSQLValueString($veri_code_parent,"text").",

								".GetSQLValueString('7',"int")."

							)";*/

							

							if(mysql_query ($sql_guardian_user))

							{

								

								$parent_user_id = mysql_insert_id();

								

								$sql_guardian = "UPDATE tbl_parent SET

								user_id =".GetSQLValueString($parent_user_id,"text")."						

								WHERE student_id =" .$student_id;

								

								if(mysql_query ($sql_guardian))

								{	

												

									$sqlpic = "SELECT * FROM tbl_student_application WHERE id = ".$chek;

									$resultpic = mysql_query($sqlpic);

									$rowpic = mysql_fetch_array($resultpic);

									

									if($rowpic['image_type'] != '' && $rowpic['image_file'] != '')

									{

										$sql_photo = "INSERT INTO tbl_student_photo 

										(	

											student_id,	

											image_type,

											image_file                                                                 

										) 

										SELECT 

											".GetSQLValueString($student_id,"text").",

											image_type,

											image_file FROM tbl_student_application WHERE id = ".$chek."

										";

										

											$resphoto = mysql_query ($sql_photo);

									}

									$sqlac = "DELETE FROM tbl_student_application WHERE id = ".$chek;

									$resultac = mysql_query($sqlac);

									

									

									/* EMAIL VERIFICATION EMAIL FOR PARENT*/

									

									/*MODIFIED BY MKT

									$sql_guardian_email = "SELECT * FROM tbl_parent WHERE student_id =" .$student_id;

									$qry_guardian_email = mysql_query($sql_guardian_email);

									$row_guardian_email = mysql_fetch_array($qry_guardian_email);

									

									$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .$row_guardian_email['user_id'];

									$qry_ver_code = mysql_query($sql_ver_code);

									$row_ver_code = mysql_fetch_array($qry_ver_code);

									

									$contents = "";

									$from_header = "From:" .SCHOOL_SYS_EMAIL;

									

									$contents .= "SIS Application Details\n";

									$contents .= "========================================================\n\n";					

									$contents .= SCHOOL_SYS_URL."/index.php?comp=com_verify_parent_account&verifycode=".$row_ver_code['verification_code']. "\n";		

									$contents .= "\n--------------------------------------------------------\n\n\n";	

									

									$subject= "SIS Application (for verification purpose)";

									

									$to = $row_guardian_email['email'];

									

									if(mail($to, $subject, $contents, $from_header))

									{		

										/* EMAIL VERIFICATION EMAIL FOR STUDENT*/

									/*MODIFEIED MY MKT

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

										if(mail($to, $subject, $contents, $from_header))

										{		

											echo '<script language="javascript">alert("Student Successfully Approved! Please check the email for verication code.");window.location =\'index.php?comp=com_student_application\';</script>';

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

								else

								{

								

									/* EMAIL VERIFICATION EMAIL FOR PARENT*/

									

									/*MODIFIED BY MKT

									$sql_guardian_email = "SELECT * FROM tbl_parent WHERE student_id =" .$student_id;

									$qry_guardian_email = mysql_query($sql_guardian_email);

									$row_guardian_email = mysql_fetch_array($qry_guardian_email);

									

									$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .$row_guardian_email['user_id'];

									$qry_ver_code = mysql_query($sql_ver_code);

									$row_ver_code = mysql_fetch_array($qry_ver_code);

									

									$contents = "";

									$from_header = "From:" .SCHOOL_SYS_EMAIL;

									

									$contents .= "SIS Application Details\n";

									$contents .= "========================================================\n\n";					

									$contents .= SCHOOL_SYS_URL."/index.php?comp=com_verify_parent_account&verifycode=".$row_ver_code['verification_code']. "\n";		

									$contents .= "\n--------------------------------------------------------\n\n\n";	

									

									$subject= "SIS Application (for verification purpose)";

									

									$to = $row_guardian_email['email'];

									if(mail($to, $subject, $contents, $from_header))

									{		

										/* EMAIL VERIFICATION EMAIL FOR STUDENT*/
										
										/*MODIFIED BY MKT

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

									

										if(mail($to, $subject, $contents, $from_header))

										{		

											echo '<script language="javascript">alert("Student Successfully Added!");window.location =\'index.php?comp=com_student_application\';</script>';

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



									echo '<script language="javascript">alert("Student Successfully Added!");window.location =\'index.php?comp=com_student_application\';</script>';

							}

						}

					}

				}
				echo '<script language="javascript">alert("Student Successfully Added!");window.location =\'index.php?comp=com_student_application\';</script>';

			}

		//}

}

}

else if($action == 'update')

{

	$checked = explode(',',$cheks);

	

	foreach($checked as $chek)

	{

		$sql_student_info = "INSERT INTO tbl_decline_application 

				(	                        

						course_id,

						curriculum_id,                                                            

						admission_type,

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

						date_created, 

						created_by,

						date_modified,

						modified_by						                                                                  

					) 

					SELECT                    

						course_id,

						curriculum_id,                                                            

						admission_type,

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

						".time().",

						".USER_ID.", 

						".time().",

						".USER_ID." FROM tbl_student_application WHERE id = ".$chek;

			

			if(mysql_query ($sql_student_info))

			{

				$sqlac = "DELETE FROM tbl_student_application WHERE id = ".$chek;

				$resultac = mysql_query($sqlac);

			

			echo '<script language="javascript">window.location =\'index.php?comp=com_student_application\';</script>';

			}

		}

}



if($view=='app')

{

	$str_arr = array();

	$ids = explode(',',$_REQUEST['cheks']);

	$ctr = 1;

	$str_arr[] = '<table class="listview">';

	     

	foreach($ids as $id)

		{

		 $sql = "SELECT app.* 

						FROM tbl_student_application app,

							tbl_course course,

							tbl_school_year year,

							tbl_school_year_term term

						WHERE app.course_id = course.id AND

							year.id = term.school_year_id AND 

							term.id = app.term_id AND app.id=".$id."

							" .$sqlcondition  . $sqlOrderBy . " $max" ;

	

		$result = mysql_query($sql);

		

            while($rowp = mysql_fetch_array($result)) 

            { 	

            

          $str_arr[] = '<tr>';

              $str_arr[] = '<th class="col_250" colspan="3"><a href="#" class="sortBy" returnFilter="lastname">'.$rowp["lastname"].', '.$rowp["firstname"].' ' .$rowp["middlename"].'</a></th>';

          $str_arr[] = '</tr>';

       

            $str_arr[] = '<tr class="'.($x%2==0)?"":"highlight".'">';

               $str_arr[] = '<td>Student Number:

                 <input type="text" class="txt_150" name="number['.$rowp['id'].']" id="number_'.$row['id'].'" value="'.generateStudentNumberForNextTerm($rowp["term_id"],$rowp["course_id"],$ctr).'" /></td> ';

              $str_arr[] = '<td>Score: <input type="text" class="txt_150" name="score['.$rowp['id'].']" id="score_'.$rowp['id'].'" value="" /></td>';

            $str_arr[] = '</tr>';

            $str_arr[] = '<tr>';

            $str_arr[] = '<td colspan="2">Requirements: </td>  ';   

        $str_arr[] = '</tr>';

 

       $sql = "SELECT * FROM tbl_requirements WHERE admission='".$rowp['admission_type']."'";

	   $query = mysql_query($sql);

	   

	   $x=0;

	   while($row = mysql_fetch_array($query))

	   {

			$reqr = isset($_REQUEST['chk'.$x])?'checked="checked"':'';	

			

            $str_arr[] = '<tr>';

            $str_arr[] = '<td colspan="2">		

				<input name="chk['.$rowp['id'].'][]" type="checkbox" value="'.$row['id'].'" id="chk'.$x.'" '.$reqr.'/>

			'.$row['requirement'].'</td>';

            $str_arr[] = '</tr>';

			

		$x++;

		}

        

       $str_arr[] = ' <input type="hidden" name="cnt" id="cnt" value="'.mysql_num_rows($query).'" />';

        

                 

		$ctr++;  }

		}

        

        $str_arr[] = '</table> ';

        $str_arr[] = '<p class="button_container">';

           $str_arr[] = ' <input type="hidden" name="num" id="num" value="<?=mysql_num_rows($result)?>" />';

           $str_arr[] = ' <a href="#" class="button" title="Save" id="save"><span>Accept</span></a>';

            

           $str_arr[] = ' <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>';

        $str_arr[] = '</p>';

	   

	   $list = implode('',$str_arr);

}

else if($view=='list')

{

	$cheks = '';

}

// component block, will be included in the template page

$content_template = 'components/block/blk_com_student_application.php';

?>

