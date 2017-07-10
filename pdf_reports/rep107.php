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
error_reporting( 0 );
//----------------------------------------------------------------------------------------------------

print_info();

//----------------------------------------------------------------------------------------------------

function print_info()
{
	global $path_to_root;
	
	include_once("pdf_report.inc");

	$cols = array(4, 60, 225, 300, 325, 385, 450, 515);

	// $headers in doctext.inc
	$aligns = array('left',	'left',	'right', 'left', 'right', 'right', 'right');

	//$params = array('comments' => $comments);

	//$cur = get_company_Pref('curr_default');

	if (!isset($_REQUEST['email']))
	{
		$rep = new FrontReport('STUDENT', "studentInfo");
		//$rep->currency = $cur;
		$rep->Font();
		$rep->Info($params, $cols, null, $aligns);
	}
		if($_REQUEST['met']=='stud')
		{
			$sql = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];						
			$query = mysql_query($sql);
			
			$sql_student_photo = "SELECT image_file FROM tbl_student_photo WHERE student_id = ".$_REQUEST['id'];
			$query_student_photo = mysql_query($sql_student_photo);
			$row_student_photo = mysql_fetch_array($query_student_photo);
		}
		else if($_REQUEST['met']=='app')
		{
			$sql = "SELECT * FROM tbl_student_application WHERE id =".$_REQUEST['id'];						
			$query = mysql_query($sql);
			
			$sql_student_photo = "SELECT image_file FROM tbl_student_application WHERE id = ".$_REQUEST['id'];
			$query_student_photo = mysql_query($sql_student_photo);
			$row_student_photo = mysql_fetch_array($query_student_photo);
		}
		else if($_REQUEST['met']=='dec')
		{
			$sql = "SELECT * FROM tbl_decline_application WHERE id =".$_REQUEST['id'];						
			$query = mysql_query($sql);
			
			$sql_student_photo = "SELECT image_file FROM tbl_decline_application WHERE id = ".$_REQUEST['id'];
			$query_student_photo = mysql_query($sql_student_photo);
			$row_student_photo = mysql_fetch_array($query_student_photo);
		}
		else
		{
			$sql = "SELECT * FROM tbl_employee WHERE id = ".$_REQUEST['id'];					
			$query = mysql_query($sql);
		}
		
		$row = mysql_fetch_array($query);
			//$sign = $j==ST_SALESINVOICE ? 1 : -1;
			//$myrow = get_customer_trans($i, $j);
		//	$baccount = get_default_bank_account($myrow['curr_code']);
			//$params['bankaccount'] = $baccount['id'];

			//$branch = get_branch($myrow["branch_code"]);
			//$branch['disable_branch'] = $paylink; // helper
			/*if ($j == ST_SALESINVOICE)
				$sales_order = get_sales_order_header($myrow["order_"], ST_SALESORDER);
			else
				$sales_order = null;*/
			if (isset($_REQUEST['email'])&&$_REQUEST['email']==1)
			{
				$rep = new FrontReport("", "", letter);
				$rep->currency = $cur;
				$rep->Font();
				
					$rep->title = 'STUDENT INFO';
					$rep->filename = "StudentInfo.pdf";
				
				$rep->Info($params, $cols, null, $aligns);
			}
			else
				$rep->title = 'STUDENT INFO';
			$rep->Header2($query, $branch, $sales_order, $baccount, $j);

			if($_REQUEST['met']!='emp')
			{
				
				if($row_student_photo['image_file']!='')
				{
					$rep->Image($row_student_photo['image_file'], 10, 90,112,112);
				}
				else
				{
					$rep->Image2('../images/NoPhotoAvailable.jpg', 10, 90,112,112);
				}
				$rep->NewLine();
				$birthday = explode ('-',$row['birth_date']);		
				$birth_year = $birthday['0'];
				$birth_day = $birthday['1'];
				$birth_month = $birthday['2'];
				
				$sqlpic = "SELECT image_file FROM tbl_student_photo WHERE student_id=".$_REQUEST['id'];
				$resultpic = mysql_query("$sql") or die("Invalid query: " . mysql_error());
				//header("Content-type: image/jpeg");
				$rowpic= mysql_fetch_array($resultpic);
				
			
				$rep->NewLine();
				$rep->fontSize += 1;
				$rep->Font('bold');
				$rep->TextColLines(0, 2, "Personal Information", -2);
				$rep->Font();
				$rep->fontSize -= 1;
				$rep->NewLine();
				$rep->TextCol(0, 2, "Student Name : ", -2);
				$rep->TextCol(1, 4, '           '.$row['lastname'].' , '.$row['firstname'].' '.$row['middlename'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Student Number : ", -2);
				$rep->TextCol(1, 4, '           '.$row['student_number'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Course : ", -2);
				$rep->TextCol(1, 4, '           '.getCourseName($row['course_id']), -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Gender : ", -2);
				$rep->TextCol(1, 4, '                  '.$row['gender']=='M'?'Male':'Female', -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Civil Status : ", -2);
				$rep->TextCol(1, 4, '           '.$row['civil_status']=='M'?'Married':'Single', -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Birthdate : ", -2);
				$rep->TextCol(1, 4, '           '.date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year)), -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Email Address : ", -2);
				$rep->TextCol(1, 4, '           '.$row['email'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Contact No. : ", -2);
				$rep->TextCol(1, 4, '           '.$row['tel_number'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Mobile No. : ", -2);
				$rep->TextCol(1, 4, '           '.$row['mobile_number'], -2);
				$rep->NewLine();
				$rep->NewLine();
				
				$rep->fontSize += 1;
				$rep->Font('bold');
				$rep->TextColLines(0, 2, "Home Address:", -2);
				$rep->Font();
				$rep->fontSize -= 1;
				$rep->NewLine();
				$rep->TextCol(0, 2, "Home Address: : ", -2);
				$rep->TextCol(1, 4, '           '.$row['home_address'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Postal Code : ", -2);
				$rep->TextCol(1, 4, '           '.$row['home_address_zip'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Country : ", -2);
				$rep->TextCol(1, 4, '           '.getCountryName($row['country']), -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "City : ", -2);
				$rep->TextCol(1, 4, '           '.$row['city'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Fax : ", -2);
				$rep->TextCol(1, 4, '           '.$row['fax'], -2);
				$rep->NewLine();
				$rep->NewLine();

				$rep->fontSize += 1;
				$rep->Font('bold');
				$rep->TextColLines(0, 2, "Guardian's Information", -2);
				$rep->Font();
				$rep->fontSize -= 1;
				$rep->NewLine();
				$rep->TextCol(0, 2, "Name : ", -2);
				$rep->TextCol(1, 4, '           '.$row['guardian_name'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Occupation : ", -2);
				$rep->TextCol(1, 4, '           '.$row['guardian_occupation'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Work Phone : ", -2);
				$rep->TextCol(1, 4, '           '.$row['guardian_work_number'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Phone : ", -2);
				$rep->TextCol(1, 4, '           '.$row['guardian_tel_number'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Address : ", -2);
				$rep->TextCol(1, 4, '           '.$row['guardian_address'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Postal Code : ", -2);
				$rep->TextCol(1, 4, '           '.$row['guardian_address_zip'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Country : ", -2);
				$rep->TextCol(1, 4, '           '.$row['guardian_country'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "City : ", -2);
				$rep->TextCol(1, 4, '           '.$row['guardian_city'], -2);
				$rep->NewLine();
				$rep->NewLine();
				
				$rep->fontSize += 1;
				$rep->Font('bold');
				$rep->TextColLines(0, 2, "Academic Background", -2);
				$rep->Font();
				$rep->fontSize -= 1;
				$rep->NewLine();
				$rep->TextCol(0, 2, "Grade School Name : ", -2);
				$rep->TextCol(1, 4, '           '.$row['grade_school'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "School Address : ", -2);
				$rep->TextCol(1, 4, '           '.$row['grade_school_address'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 1, "Years Attended : ", -2);
				$rep->TextCol(1, 4, '           '.$row['grade_school_years'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Awards : ", -2);
				$rep->TextCol(1, 4, '           '.$row['grade_school_awards'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "High School Name : ", -2);
				$rep->TextCol(1, 4, '           '.$row['high_school'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "School Address : ", -2);
				$rep->TextCol(1, 4, '           '.$row['high_school_address'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 1, "Years Attended : ", -2);
				$rep->TextCol(1, 4, '           '.$row['high_school_years'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Awards : ", -2);
				$rep->TextCol(1, 4, '           '.$row['high_school_awards'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "College School Name : ", -2);
				$rep->TextCol(1, 4, '           '.$row['college_school'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "School Address : ", -2);
				$rep->TextCol(1, 4, '           '.$row['college_school_address'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 1, "Years Attended : ", -2);
				$rep->TextCol(1, 4, '           '.$row['college_school_years'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Awards : ", -2);
				$rep->TextCol(1, 4, '           '.$row['college_school_awards'], -2);
				$rep->NewLine();
				$rep->NewLine();
				
				$rep->fontSize += 1;
				$rep->Font('bold');
				$rep->TextCol(0, 2, "Additional Information", -2);
				$rep->Font();
				$rep->fontSize -= 1;
				$rep->NewLine();
				$rep->TextCol(0, 2, "Language : ", -2);
				$rep->TextCol(1, 4, '           '.$row['language'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Extra Curricular Activities : ", -2);
				$rep->TextCol(1, 4, '           '.$row['extra_curricular'], -2);
				$rep->NewLine();
			
			}
			else
			{
				$sql_emp_photo = "SELECT * FROM tbl_employee_photo WHERE employee_id = ".$_REQUEST['id'];
				$query_emp_photo = mysql_query($sql_emp_photo);
				$row_emp_photo = mysql_fetch_array($query_emp_photo);
				if(mysql_num_rows($query_emp_photo)>0)
				{
					$rep->Image($row_emp_photo['image_file'], $rep->topMargin, $rep->leftMargin+50,112,112);
				}
				else
				{
					$rep->Image2('../images/NoPhotoAvailable.jpg', $rep->topMargin, $rep->leftMargin+50,112,112);
				}
				
				$rep->NewLine();
				$birthday = explode ('-',$row['birth_date']);		
				$birth_year = $birthday['0'];
				$birth_day = $birthday['1'];
				$birth_month = $birthday['2'];
			
				$rep->NewLine();
				$rep->fontSize += 1;
				$rep->Font('bold');
				$rep->TextColLines(0, 2, "Personal Information", -2);
				$rep->Font();
				$rep->fontSize -= 1;
				$rep->NewLine();
				$rep->TextCol(0, 2, "Student Name : ", -2);
				$rep->TextCol(1, 4, '           '.$row['lastname'].' , '.$row['firstname'].' '.$row['middlename'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Employee Number : ", -2);
				$rep->TextCol(1, 4, '           '.$row['emp_id_number'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Department : ", -2);
				$rep->TextCol(1, 4, '           '.getDeptName($row['department_id']), -2);
				$rep->NewLine();
				$rep->TextCol(0, 1, "Gender : ", -2);
				$rep->TextCol(1, 4, $row['gender']=='M'?'Male':'Female', -2);
				$rep->NewLine();
				$rep->TextCol(0, 1, "Civil Status : ", -2);
				$rep->TextCol(1, 4, $row['civil_status']=='M'?'Married':'Single', -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Birthdate : ", -2);
				$rep->TextCol(1, 4, '           '.date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year)), -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Email Address : ", -2);
				$rep->TextCol(1, 4, '           '.$row['email'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Contact No. : ", -2);
				$rep->TextCol(1, 4, '           '.$row['tel_number'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Mobile No. : ", -2);
				$rep->TextCol(1, 4, '           '.$row['mobile_number'], -2);
				$rep->NewLine();
				$rep->NewLine();
				
				$rep->fontSize += 1;
				$rep->Font('bold');
				$rep->TextColLines(0, 2, "Present Address", -2);
				$rep->Font();
				$rep->fontSize -= 1;
				$rep->NewLine();
				$rep->TextCol(0, 2, "Present Address : ", -2);
				$rep->TextCol(1, 4, '           '.$row['present_address'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Zip Code : ", -2);
				$rep->TextCol(1, 4, '           '.$row['present_address_zip'], -2);
				$rep->NewLine();
				$rep->NewLine();
				
				$rep->fontSize += 1;
				$rep->Font('bold');
				$rep->TextColLines(0, 2, "Permanent Address", -2);
				$rep->Font();
				$rep->fontSize -= 1;
				$rep->NewLine();
				$rep->TextCol(0, 2, "Permanent Address: ", -2);
				$rep->TextCol(1, 4, '           '.$row['permanent_address'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Zip Code : ", -2);
				$rep->TextCol(1, 4, '           '.$row['permanent_address_zip'], -2);
				$rep->NewLine();
				$rep->NewLine();
				
				$rep->fontSize += 1;
				$rep->Font('bold');
				$rep->TextColLines(0, 2, "Person to Notify in Case of Emergency", -2);
				$rep->Font();
				$rep->fontSize -= 1;
				$rep->NewLine();
				$rep->TextCol(0, 2, "Name : ", -2);
				$rep->TextCol(1, 4, '           '.$row['ice_fullname'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Address : ", -2);
				$rep->TextCol(1, 4, '           '.$row['ice_address'], -2);
				$rep->NewLine();
				$rep->TextCol(0, 2, "Contact No. : ", -2);
				$rep->TextCol(1, 24, '           '.$row['ice_tel_number'], -2);
				$rep->NewLine();
			}	
			
			if (isset($_REQUEST['email'])&&$_REQUEST['email']==1)
			{
				$rep->End(1, 'Student Profile', $row);
			}
			else
			{
				$rep->End();
			}
}

?>