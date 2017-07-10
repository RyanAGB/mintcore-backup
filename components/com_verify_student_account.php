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

if($_SESSION[CORE_U_CODE] == '1')
{
	echo '<script language="javascript">window.location =\'index.php\';</script>';// No Access Redirect to main
}

$verifyCode = $_GET["verifycode"];
			
$sql = "SELECT * FROM tbl_user as usr, tbl_student as stud WHERE usr.id = stud.user_id AND usr.verification_code = '" . $verifyCode . "'";

$result = mysql_query($sql);

	$row = mysql_fetch_array($result);
	$id = $row["user_id"];
	$email = $row["email"];
	$username = $row["username"];
	$blocked = "0";
	$subject = "Username and Password";
	
	$gen_salt = generateSaltString();
	$gen_pass = generatePassword(); 
	$password = md5($gen_pass . $gen_salt);
	
	$result = mysql_query($sql);
	
	$contents = "";
	$from_header = "From:" .SCHOOL_SYS_EMAIL;
	
	$contents .= "SIS Username And Password\n";
	$contents .= "========================================================\n\n";					
	$contents .= "Username:" .$username. "\n";
	$contents .= "Password:" .$gen_pass. "\n";		
	$contents .= "\n--------------------------------------------------------\n\n\n";	
		
	$subject= "SIS Core v2 Username And Password";
	
	$to = $email;
	
	if(mail($to, $subject, $contents, $from_header))
	{
		$sql = "UPDATE tbl_user SET 
			blocked  =".GetSQLValueString($blocked,"int")."	,
			password  =".GetSQLValueString($password,"text").",
			salt =".GetSQLValueString($gen_salt,"text").",
			verification_code =".GetSQLValueString('',"text")."		
			WHERE id=" .$id; 		
		
		mysql_query ($sql);

		$msgs = "Username and Password has been sent to email $email";
	}
	else
	{
		$msgs = 'Some problem occured while sending your message please try again.';
	}	


$content_template = 'components/block/blk_com_verify_student_account.php';
?>