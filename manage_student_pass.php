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



include_once("config.php");

include_once("includes/functions.php");

include_once("includes/common.php");

$sql = "SELECT * FROM tbl_student";
$query = mysql_query($sql);

	while($row = mysql_fetch_array($query))
	{
							
							$gen_salt = generateSaltString();
							$userN = explode('-',$row['student_number']);
							
							for($c = 1;$c<=count($userN);$c++)
							{
								if($c==count($userN)){
									$userNm = $row['lastname'].'_'.$userN[$c-1];
									$password = md5('mint'.$userN[$c-1].$gen_salt);
								}
							}
	
							$sql_username = "UPDATE tbl_user SET
							username =".GetSQLValueString($userNm,"text").",
							password =".GetSQLValueString($password,"text").",
							salt =".GetSQLValueString($gen_salt,"text")."						
							WHERE id =" .$row['user_id'];
							
							mysql_query($sql_username);
	}
?>

