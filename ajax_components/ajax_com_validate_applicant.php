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
	include_once('../includes/functions.php');
	include_once("../includes/common.php");	

	$id = $_REQUEST['id'];
	$err = '';
	
	if($id!='')
	{
		$sqlsy = "SELECT * FROM tbl_student_application WHERE id = ".$id;
		$querysy = mysql_query($sqlsy);
		$rowsy = mysql_fetch_array($querysy);
		$term_id = $rowsy['term_id'];
		$school_id = getSchoolYearIdByTermId($rowsy['term_id']);
		
		if (checkIfStudentApplicantSchoolYear($school_id,$term_id))
			{
				$err = 'true';
			}

	}

echo $err;
?>