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


/********************************************************
# NOTES !! NOTES !! NOTES !! NOTES !! NOTES !! NOTES !! 
#
# PASSWORD = md5(concat('password','salt'))
*********************************************************/
$username = strtolower($_POST["username"]);
$password = $_POST["password"];
$email = $_POST["email"];
$action = $_POST["action"];

if($action == 'login')
{
	if($username != '' || $password != '')
	{

		$pass = md5($password . getUserSalt($username));
		$sql = "SELECT * FROM tbl_user WHERE 
				username= ".GetSQLValueString($username,"text")
				;
				
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$ctr = mysql_num_rows($query);

		if($ctr > 0)
		{ 
			if($row['blocked'] == '1')
			{
				$err_msg = 'Your account is blocked. Please contact the Administrator.';
			}		
			else if(checkIfUserCanLogin($username))
			{
				$sql = "SELECT * FROM tbl_user WHERE 
						username= ".GetSQLValueString($username,"text")." AND 
						password= ".GetSQLValueString($pass,"text")
						;
						
				$query = mysql_query($sql);
				$row = mysql_fetch_array($query);
				$ctr = mysql_num_rows($query);	
				if($row['blocked'] == '1')
				{
					$err_msg = 'Your account is blocked. Please contact the Administrator.';
				}
				else if(strtolower($row['username']) == $username)
				{
					if(SYS_SET_SYSTEM == 'ON')
					{
						setSessionData($row['username']); // will set all session needed
						clearUserFailedLoginHistory($row['username']);						
						storedSessionLogs(); // save the session logs in the database
						updateUserLastLogin();	// update user last login
						checkExpirationOfAllReservation(); // THIS FUNCTION IS GENERIC; AND SHOULD BE IN A CRON JOB.
						echo '<script language="javascript">window.location =\'index.php\';</script>';
					}
					else if(SYS_SET_SYSTEM == 'OFF' && $row['access_id'] == 1)
					{
						setSessionData($row['username']); // will set all session needed
						clearUserFailedLoginHistory($row['username']);						
						storedSessionLogs(); // save the session logs in the database
						updateUserLastLogin();	// update user last login
						checkExpirationOfAllReservation(); // THIS FUNCTION IS GENERIC; AND SHOULD BE IN A CRON JOB.
						echo '<script language="javascript">window.location =\'index.php\';</script>';					
					}
					else
					{
						$err_msg = 'The system is currently offline.';	
					}
				}
				else
				{
					storeLoginFailedLoginAttemp($username);
					$err_msg = 'Invalid username and password or your account is blocked.';
				}
			}
			else
			{
				$err_msg = 'Account is locked. Try to re-login after '.getRemainingMinutesTologin($username) .' minute(s)';
			}
		}
		else
		{
			$err_msg = 'Invalid username or password';
		}
	}
	else
	{
		$err_msg = 'Invalid username or password';
	}
	
}

if($action == 'forgot_password')
{
	if($email != '')
	{
		$veri_code = generateRandomString();

		$sql = "SELECT * FROM tbl_student WHERE 
				email= ".GetSQLValueString($email,"text");
				
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$ctr = mysql_num_rows($query);
		if($ctr > 0)
		{
			$sql_veri_code = "UPDATE tbl_user SET 
							verification_code  = ".GetSQLValueString($veri_code,"text")."		
							WHERE id=" .$row['user_id'];
							mysql_query ($sql_veri_code);
			 
			$sql_email = "SELECT * FROM tbl_student WHERE id =" .$row['id'];
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
			
			$subject= "Forgot Password";
			
			$to = $row_email['email'];
			if(mail($to, $subject, $contents, $from_header))
			{		
				echo '<script language="javascript">alert("Success! Please check the email for verification code.");window.location =\'index.php\';</script>';
			}
			else
			{
				$err_msg = 'Some problem occured while sending your message please try again.';
			}
		}
		else
		{
			$err_msg = 'Invalid Email Address';
		} 

	}
	if($email != '')
	{
		$veri_code = generateRandomString();

		$sql = "SELECT * FROM tbl_employee WHERE 
				email= ".GetSQLValueString($email,"text");
				
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$ctr = mysql_num_rows($query);
		if($ctr > 0)
		{
			$sql_veri_code = "UPDATE tbl_user SET 
							verification_code  = ".GetSQLValueString($veri_code,"text")."		
							WHERE id=" .$row['user_id'];	
							mysql_query ($sql_veri_code);
			 
			$sql_email = "SELECT * FROM tbl_employee WHERE id =" .$row['id'];
			$qry_email = mysql_query($sql_email);
			$row_email = mysql_fetch_array($qry_email);
			
			$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .$row_email['user_id'];
			$qry_ver_code = mysql_query($sql_ver_code);
			$row_ver_code = mysql_fetch_array($qry_ver_code);
			
			$contents = "";
			$from_header = "From:" .SCHOOL_SYS_EMAIL;
			
			$contents .= "SIS Application Details\n";
			$contents .= "========================================================\n\n";					
			$contents .= SCHOOL_SYS_URL."/core_v2/index.php?comp=com_verify_employee_account&verifycode=".$row_ver_code['verification_code']. "\n";		
			$contents .= "\n--------------------------------------------------------\n\n\n";	
			
			$subject= "Forgot Password";
			
			$to = $row_email['email'];
			if(mail($to, $subject, $contents, $from_header))
			{		
				echo '<script language="javascript">alert("Success! Please check the email for verification code.");window.location =\'index.php\';</script>';
			}
			else
			{
				$err_msg = 'Some problem occured while sending your message please try again.';
			}
		}
		else
		{
			$err_msg = 'Invalid Email Address';
		} 

	}
	else
	{
		$err_msg = 'Invalid Email Address';
	}
	
	if($email != '')
	{
		$veri_code = generateRandomString();

		$sql = "SELECT * FROM tbl_parent WHERE 
				email= ".GetSQLValueString($email,"text");
				
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$ctr = mysql_num_rows($query);
		if($ctr > 0)
		{
			$sql_veri_code = "UPDATE tbl_user SET 
							verification_code  = ".GetSQLValueString($veri_code,"text")."		
							WHERE id=" .$row['user_id'];	
							mysql_query ($sql_veri_code);
			 
			$sql_email = "SELECT * FROM tbl_parent WHERE id =" .$row['id'];
			$qry_email = mysql_query($sql_email);
			$row_email = mysql_fetch_array($qry_email);
			
			$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .$row_email['user_id'];
			$qry_ver_code = mysql_query($sql_ver_code);
			$row_ver_code = mysql_fetch_array($qry_ver_code);
			
			$contents = "";
			$from_header = "From:" .SCHOOL_SYS_EMAIL;
			
			$contents .= "SIS Application Details\n";
			$contents .= "========================================================\n\n";					
			$contents .= SCHOOL_SYS_URL."/core_v2/index.php?comp=com_verify_parent_account&verifycode=".$row_ver_code['verification_code']. "\n";		
			$contents .= "\n--------------------------------------------------------\n\n\n";	
			
			$subject= "Forgot Password";
			
			$to = $row_email['email'];
			if(mail($to, $subject, $contents, $from_header))
			{		
				echo '<script language="javascript">alert("Success! Please check the email for verification code.");window.location =\'index.php\';</script>';
			}
			else
			{
				$err_msg = 'Some problem occured while sending your message please try again.';
			}
		}
		else
		{
			$err_msg = 'Invalid Email Address';
		} 

	}
	else
	{
		$err_msg = 'Invalid Email Address';
	}
	
}

$content_template = 'components/block/blk_com_login.php';
?>