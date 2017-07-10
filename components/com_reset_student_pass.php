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


$page_title = 'Reset Password';
$pagination = 'Users > Manage Student > Reset Password';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

if($action == 'reset')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		$sql_student ="SELECT * FROM tbl_student WHERE id =" .$item;
		$qry_student = mysql_query($sql_student);
		$row_student = mysql_fetch_array($qry_student);
		
		$gen_salt = generateSaltString();
		//$gen_pass = generatePassword(); 
		
		/*
		
		/* md5(concat('password','salt')) */
		//$password = md5($gen_pass . $gen_salt);

		$userN = explode('-',$row_student['student_number']);
		
		for($c = 1;$c<=count($userN);$c++)
		{
			if($c==count($userN)){
				//$userNm = $lastname.'_'.$userN[$c-1];
				$password = md5('mint'.$userN[$c-1].$gen_salt);
				//$gen_pass = 'mint'.$userN[$c-1];
			}
		}
		
		$sql = "UPDATE tbl_user SET 
				password  =".GetSQLValueString($password,"text").",
				salt =".GetSQLValueString($gen_salt,"text")." 		
				WHERE id=" .$row_student['user_id'];		 
		mysql_query($sql);
		
		/*DISABLED EMAIL TEMPORARILY
		$contents = "";
		$from_header = "From:" .SCHOOL_SYS_EMAIL;
		
		$contents .= "EMPLOYEE RESET PASSWORD\n";
		$contents .= "========================================================================\n\n";
		$contents .= "PASSWORD:".$gen_pass. "\n";
		$contents .= "\n------------------------------------------------------------------------\n\n";	
											
		$subject= "SIS CORE v2 Password Reset";

		$to = $row_student['email'];
		if(mail($to, $subject, $contents, $from_header))
		{		
			$sql = "UPDATE tbl_user SET 
				password  =".GetSQLValueString($password,"text").",
				salt =".GetSQLValueString($gen_salt,"text")." 		
				WHERE id=" .$row_student['user_id'];		 
			if(mysql_query($sql))
			{
				echo '<script language="javascript">alert("New password has been send to the users email.");window.location =\'index.php?comp=com_reset_student_pass\';</script>';
			}
		}
		else
		{
			$err_msg = 'Some problem occured while sending your password please try again.';
			break;
		}*/
	}	

	if(count($arr_str) > 0)
	{
		echo '<script language="javascript">alert("'.implode("\n",$arr_str).'");</script>';
	}

}


// component block, will be included in the template page
$content_template = 'components/block/blk_com_reset_student_pass.php';
?>