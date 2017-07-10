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
				
	$sql = "SELECT * FROM tbl_user as usr, tbl_parent as parent WHERE usr.id = parent.user_id AND usr.verification_code = '" . $verifyCode . "'";
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	$id = $row["user_id"];
	
	$new_email_encrypted = $row['verification_code'];
	$email = explode("|", $new_email_encrypted);
	$new_email = base64_decode($email[1]);
		
	$contents = "";
	$from_header = "From:" .SCHOOL_SYS_EMAIL;
	
	$contents .= "SIS Email Changed\n";
	$contents .= "========================================================\n\n";					
	$contents .= "Email has been successfully changed\n";
	$contents .= "From: " .$row['email'] . " to " . $new_email ."\n";		
	$contents .= "\n--------------------------------------------------------\n\n\n";	
		
	$subject= "SIS Core v2 Email ";
	
	$to = $new_email;
	
	if(mail($to, $subject, $contents, $from_header))
	{
	
		$contents = "";
		$from_header = "From:" .SCHOOL_SYS_EMAIL;
		
		$contents .= "SIS Email Changed\n";
		$contents .= "========================================================\n\n";					
		$contents .= "Email has been successfully changed\n";
		$contents .= "From: " .$row['email'] . " to " . $new_email ."\n";		
		$contents .= "\n--------------------------------------------------------\n\n\n";	
			
		$subject= "SIS Core v2 Email ";
		$to = $row['email'];
		
		if(mail($to, $subject, $contents, $from_header))
		{
		
			$sql_ver = "UPDATE tbl_user SET 	
				verification_code =".GetSQLValueString('',"text")."	
				WHERE id=" .$id; 		
		
			mysql_query ($sql_ver);
			
			$sql_new_email = "UPDATE tbl_parent SET 	
				email =".GetSQLValueString($new_email,"text")."	
				WHERE user_id=" .$id; 		
		
			mysql_query ($sql_new_email);
		
			$msgs = "Email verification completed";
		}
	}
	else
	{
		$msgs = 'Some problem occured while sending your message please try again.';
	}	


$content_template = 'components/block/blk_com_verify_parent_email.php';
?>