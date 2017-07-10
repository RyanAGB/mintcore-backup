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



if(USER_IS_LOGGED != '1')

{

	echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main

}





$page_title = 'Profile';

$pagination = 'Profile  > Profile';



$view = $view==''?'list':$view; // initialize action







$id	= $_REQUEST['id'];

$temp 	= $_REQUEST['temp'];





$old_password		= $_REQUEST['old_password'];

$new_password		= $_REQUEST['new_password'];

$confirm_password	= $_REQUEST['confirm_password'];



$old_email			= $_REQUEST['old_email'];

$new_email			= $_REQUEST['new_email'];

$confirm_email		= $_REQUEST['confirm_email'];







if($action == 'update')

{



	if( $old_password != '')

	{

		$sql_old_pass = "SELECT * FROM tbl_user WHERE id=" .USER_ID;

		$qry_old_pass = mysql_query($sql_old_pass);

		$row_old_pass = mysql_fetch_array($qry_old_pass);

		

		$o_password = md5($old_password . $row_old_pass['salt']);





		if($o_password == $row_old_pass['password'])

		{

			if($new_password =='')

			{

				$err_msg = "Please enter a new password!";

			}

			else if($confirm_password =='')

			{

				$err_msg = "Please confirm the password!";

			}

			else if($new_password != $confirm_password)

			{

				$err_msg = "The new and confirm password you entered are not match!";

			}

			else

			{

				$gen_salt = generateSaltString();

				$password = md5($confirm_password . $gen_salt);

				

				$sql_emp = "SELECT * FROM tbl_employee WHERE user_id =" .USER_ID;

				$qry_emp = mysql_query($sql_emp);

				$ctr_emp = mysql_num_rows($qry_emp);

				

				$sql_stud = "SELECT * FROM tbl_student WHERE user_id =" .USER_ID;

				$qry_stud = mysql_query($sql_stud);

				$ctr_stud = mysql_num_rows($qry_stud);

				

				$sql_parent = "SELECT * FROM tbl_parent WHERE user_id =" .USER_ID;

				$qry_parent = mysql_query($sql_parent);

				$ctr_parent = mysql_num_rows($qry_parent);

				

				if($ctr_emp != '0')

				{

					$sql = "UPDATE tbl_user SET 

							password  =".GetSQLValueString($password,"text").",

							salt  =".GetSQLValueString($gen_salt,"text")."

							WHERE id=" .USER_ID;

					if(mysql_query($sql))

					{

						$sql_email = "SELECT * FROM tbl_employee WHERE user_id =" .USER_ID;

						$qry_email = mysql_query($sql_email);

						$row_email = mysql_fetch_array($qry_email);

						

						$contents = "";

						$from_header = "From:" .SCHOOL_SYS_EMAIL;

						

						$contents .= "SIS Change Password Details\n";

						$contents .= "========================================================\n\n";		

						$contents .= "New Password: " .$confirm_password;

						$contents .= "\n--------------------------------------------------------\n\n\n";	

						

						$subject= "SIS Change Password";

						

						$to = $row_email['email'];

					

						if(mail($to, $subject, $contents, $from_header))

						{		

							echo '<script language="javascript">alert("Password is successfully changed! \nYou will be automatically logout");window.location =\'index.php?comp=com_logout\';</script>';

						}

						else

						{

							$err_msg = 'Some problem occured while sending your message please try again.';

						}

						

					}

					

				}

				else if($ctr_stud != '0')

				{

					$sql = "UPDATE tbl_user SET 

							password  =".GetSQLValueString($password,"text").",

							salt  =".GetSQLValueString($gen_salt,"text")."	

							WHERE id=" .USER_ID;

					if(mysql_query($sql))

					{

						$sql_email = "SELECT * FROM tbl_student WHERE user_id =" .USER_ID;

						$qry_email = mysql_query($sql_email);

						$row_email = mysql_fetch_array($qry_email);

						

						$contents = "";

						$from_header = "From:" .SCHOOL_SYS_EMAIL;

						

						$contents .= "SIS Change Password Details\n";

						$contents .= "========================================================\n\n";		

						$contents .= "New Password: " .$confirm_password;

						$contents .= "\n--------------------------------------------------------\n\n\n";	

						

						$subject= "SIS Change Password";

						

						$to = $row_email['email'];

					

						if(mail($to, $subject, $contents, $from_header))

						{		

							echo '<script language="javascript">alert("Password is successfully changed! \nYou will be automatically logout");window.location =\'index.php?comp=com_logout\';</script>';

						}

						else

						{

							$err_msg = 'Some problem occured while sending your message please try again.';

						}

					}

				}

				else if($ctr_parent != '0')

				{

					$sql = "UPDATE tbl_user SET 

							password  =".GetSQLValueString($password,"text").",

							salt  =".GetSQLValueString($gen_salt,"text")."	

							WHERE id=" .USER_ID;

					if(mysql_query($sql))

					{

						$sql_email = "SELECT * FROM tbl_parent WHERE user_id =" .USER_ID;

						$qry_email = mysql_query($sql_email);

						$row_email = mysql_fetch_array($qry_email);

						

						$contents = "";

						$from_header = "From:" .SCHOOL_SYS_EMAIL;

						

						$contents .= "SIS Change Password Details\n";

						$contents .= "========================================================\n\n";		

						$contents .= "New Password: " .$confirm_password;

						$contents .= "\n--------------------------------------------------------\n\n\n";	

						

						$subject= "SIS Change Password";

						

						$to = $row_email['email'];

					

						if(mail($to, $subject, $contents, $from_header))

						{		

							echo '<script language="javascript">alert("Password is successfully changed! \nYou will be automatically logout");window.location =\'index.php?comp=com_logout\';</script>';

						}

						else

						{

							$err_msg = 'Some problem occured while sending your message please try again.';

						}

					}

				}

			}

		}

	}

	if($old_email != '')

	{

		

		$sql_emp = "SELECT * FROM tbl_employee WHERE user_id =" .USER_ID;

		$qry_emp = mysql_query($sql_emp);

		$row_emp = mysql_fetch_array($qry_emp);

		$ctr_emp = mysql_num_rows($qry_emp);

		

		$sql_stud = "SELECT * FROM tbl_student WHERE user_id =" .USER_ID;

		$qry_stud = mysql_query($sql_stud);

		$row_stud = mysql_fetch_array($qry_stud);

		$ctr_stud = mysql_num_rows($qry_stud);

		

		$sql_parent = "SELECT * FROM tbl_parent WHERE user_id =" .USER_ID;

		$qry_parent = mysql_query($sql_parent);

		$row_parent = mysql_fetch_array($qry_parent);

		$ctr_parent = mysql_num_rows($qry_parent);

		

		if($ctr_emp != '0')

		{

			if($old_email == $row_emp['email'])

			{

				if($new_email =='')

				{

					$err_msg = "Please enter a new email!";

				}

				else if($confirm_email =='')

				{

					$err_msg = "Please confirm the email!";

				}

				else if($new_email != $confirm_email)

				{

					$err_msg = "The new and confirm email you entered are not match!";

				}

				else if(validateEmail($new_email))

				{

					$err_msg = 'Invalid Email.';

				}

				else if(checkIfEmployeeEmailExist($new_email, $id) || checkIfStudentEmailExist($new_email, $id) || checkIfParentEmailExist($new_email, $id))

				{

					$err_msg = 'Email address already exist.';

				}

				else

				{

				

					$sql_email = "SELECT * FROM tbl_employee WHERE user_id =" .USER_ID;

					$qry_email = mysql_query($sql_email);

					$row_email = mysql_fetch_array($qry_email);

					

					$contents = "";

					$from_header = "From:" .SCHOOL_SYS_EMAIL;

					

					$contents .= "SIS Change Email Details\n";

					$contents .= "========================================================\n\n";		

					$contents .= "Please check " .$confirm_email ." for verification code";

					$contents .= "\n--------------------------------------------------------\n\n\n";	

					

					$subject= "SIS Change Email";

					

					$to = $row_email['email'];

				

					$gen_salt = generateSaltString();

					$gen_code= base64_encode($confirm_email);

					

					$verification_code = $gen_salt."|".$gen_code;

					

					

					$sql = "UPDATE tbl_user SET 

							verification_code  =".GetSQLValueString($verification_code,"text")."

							WHERE id=" .USER_ID;

					mysql_query($sql);		

					

					if(mail($to, $subject, $contents, $from_header))

					{	

						$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .USER_ID;

						$qry_ver_code = mysql_query($sql_ver_code);

						$row_ver_code = mysql_fetch_array($qry_ver_code);

					

						$contents = "";

						$from_header = "From:" .SCHOOL_SYS_EMAIL;

						

						$contents .= "SIS Change Email Details\n";

						$contents .= "========================================================\n\n";	

						$contents .= SCHOOL_SYS_URL."/index.php?comp=com_verify_employee_email&verifycode=".$row_ver_code['verification_code']."\n";	

						$contents .= "\n--------------------------------------------------------\n\n\n";	

						

						$subject= "SIS Change Email Verification Code";

						

						$to = $confirm_email;

					

						if(mail($to, $subject, $contents, $from_header))

						{

							echo '<script language="javascript">alert("Please check the new email you entered for verification.");window.location =\'index.php?comp=com_profile\';</script>';

						}

						else

						{

							$err_msg = 'Some problem occured while sending your message please try again.';

						}

					

					}

					else

					{

						$err_msg = 'Some problem occured while sending your message please try again.';

					}

						

				}

			}

		}

		else if($ctr_stud != '0')

		{

			

			if($old_email == $row_stud['email'])

			{

				if($new_email =='')

				{

					$err_msg = "Please enter a new email!";

				}

				else if($confirm_email =='')

				{

					$err_msg = "Please confirm the email!";

				}

				else if($new_email != $confirm_email)

				{

					$err_msg = "The new and confirm email you entered are not match!";

				}

				else if(validateEmail($new_email))

				{

					$err_msg = 'Invalid Email.';

				}

				else if(checkIfEmployeeEmailExist($new_email, $id) || checkIfStudentEmailExist($new_email, $id) || checkIfParentEmailExist($new_email, $id))

				{

					$err_msg = 'Email address already exist.';

				}

				else

				{

				

					$sql_email = "SELECT * FROM tbl_student WHERE user_id =" .USER_ID;

					$qry_email = mysql_query($sql_email);

					$row_email = mysql_fetch_array($qry_email);

					

					$contents = "";

					$from_header = "From:" .SCHOOL_SYS_EMAIL;

					

					$contents .= "SIS Change Email Details\n";

					$contents .= "========================================================\n\n";		

					$contents .= "Please check " .$confirm_email ." for verification code";

					$contents .= "\n--------------------------------------------------------\n\n\n";	

					

					$subject= "SIS Change Email";

					

					$to = $row_email['email'];

				

					$gen_salt = generateSaltString();

					$gen_code= base64_encode($confirm_email);

					

					$verification_code = $gen_salt."|".$gen_code;

					

					

					$sql = "UPDATE tbl_user SET 

							verification_code  =".GetSQLValueString($verification_code,"text")."

							WHERE id=" .USER_ID;

					mysql_query($sql);		

					

					if(mail($to, $subject, $contents, $from_header))

					{	

						$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .USER_ID;

						$qry_ver_code = mysql_query($sql_ver_code);

						$row_ver_code = mysql_fetch_array($qry_ver_code);

					

						$contents = "";

						$from_header = "From:" .SCHOOL_SYS_EMAIL;

						

						$contents .= "SIS Change Email Details\n";

						$contents .= "========================================================\n\n";	

						$contents .= SCHOOL_SYS_URL."/index.php?comp=com_verify_student_email&verifycode=".$row_ver_code['verification_code']."\n";	

						$contents .= "\n--------------------------------------------------------\n\n\n";	

						

						$subject= "SIS Change Email Verification Code";

						

						$to = $confirm_email;

					

						if(mail($to, $subject, $contents, $from_header))

						{

							echo '<script language="javascript">alert("Please check the new email you entered for verification.");window.location =\'index.php?comp=com_profile\';</script>';

						}

						else

						{

							$err_msg = 'Some problem occured while sending your message please try again.';

						}

					

					}

					else

					{

						$err_msg = 'Some problem occured while sending your message please try again.';

					}

				}

			}

		}

		else if($ctr_parent != '0')

		{

			

			if($old_email == $row_parent['email'])

			{

				if($new_email =='')

				{

					$err_msg = "Please enter a new email!";

				}

				else if($confirm_email =='')

				{

					$err_msg = "Please confirm the email!";

				}

				else if($new_email != $confirm_email)

				{

					$err_msg = "The new and confirm email you entered are not match!";

				}

				else if(validateEmail($new_email))

				{

					$err_msg = 'Invalid Email.';

				}

				else if(checkIfEmployeeEmailExist($new_email, $id) || checkIfStudentEmailExist($new_email, $id) || checkIfParentEmailExist($new_email, $id))

				{

					$err_msg = 'Email address already exist.';

				}

				else

				{

					$sql_email = "SELECT * FROM tbl_parent WHERE user_id =" .USER_ID;

					$qry_email = mysql_query($sql_email);

					$row_email = mysql_fetch_array($qry_email);

					

					$contents = "";

					$from_header = "From:" .SCHOOL_SYS_EMAIL;

					

					$contents .= "SIS Change Email Details\n";

					$contents .= "========================================================\n\n";		

					$contents .= "Please check " .$confirm_email ." for verification code";

					$contents .= "\n--------------------------------------------------------\n\n\n";	

					

					$subject= "SIS Change Email";

					

					$to = $row_email['email'];

				

					$gen_salt = generateSaltString();

					$gen_code= base64_encode($confirm_email);

					

					$verification_code = $gen_salt."|".$gen_code;

					

					

					$sql = "UPDATE tbl_user SET 

							verification_code  =".GetSQLValueString($verification_code,"text")."

							WHERE id=" .USER_ID;

					mysql_query($sql);		

					

					if(mail($to, $subject, $contents, $from_header))

					{	

						$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .USER_ID;

						$qry_ver_code = mysql_query($sql_ver_code);

						$row_ver_code = mysql_fetch_array($qry_ver_code);

					

						$contents = "";

						$from_header = "From:" .SCHOOL_SYS_EMAIL;

						

						$contents .= "SIS Change Email Details\n";

						$contents .= "========================================================\n\n";	

						$contents .= SCHOOL_SYS_URL."/index.php?comp=com_verify_parent_email&verifycode=".$row_ver_code['verification_code']."\n";	

						$contents .= "\n--------------------------------------------------------\n\n\n";	

						

						$subject= "SIS Change Email Verification Code";

						

						$to = $confirm_email;

					

						if(mail($to, $subject, $contents, $from_header))

						{

							echo '<script language="javascript">alert("Please check the new email you entered for verification.");window.location =\'index.php?comp=com_profile\';</script>';

						}

						else

						{

							$err_msg = 'Some problem occured while sending your message please try again.';

						}

					

					}

					else

					{

						$err_msg = 'Some problem occured while sending your message please try again.';

					}				

				}

			}

		}


	}

	if($old_email == '' && $old_password == '')

		{
			
			echo '<script language="javascript">alert("No Updates are made.");window.location =\'index.php?comp=com_profile\';</script>';	

		}

}





// component block, will be included in the template page

$content_template = 'components/block/blk_com_profile.php';

?>