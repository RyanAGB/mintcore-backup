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


if($_REQUEST['clear'] == 1)
{
	clearStudentFilter();
	echo '<script language="javascript">window.location =\'index.php?comp=mn_cs_payments\';</script>';
}

$page_title = 'Student Search';
$pagination = 'Student Payment  > Student';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$course_id			= $_REQUEST['course_id'];
$student_number		= $_REQUEST['student_number'];
$student_no_bsc		= $_REQUEST['student_no_bsc'];
$lastname			= $_REQUEST['lastname'];
$firstname			= $_REQUEST['firstname'];
$middlename			= $_REQUEST['middlename'];
$filter				= $_REQUEST['filter'];


if($action == 'search')
{

		setStudentSearch($course_id,$student_number,$lastname,$firstname,$middlename,$filter);
		echo '<script language="javascript">window.location =\'index.php?comp=com_cs_student_balance_payment\';</script>';
	
}



// component block, will be included in the template page
$content_template = 'components/block/blk_mn_cs_payments.php';
?>