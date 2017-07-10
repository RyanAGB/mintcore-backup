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
	clearProfFilter();
	echo '<script language="javascript">window.location =\'index.php?comp=mn_professor\';</script>';
}

$page_title = 'Professor Advanced Search';
$pagination = 'Professor  > Manage Student';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$emp_id_number		= $_REQUEST['emp_id_number'];
$lastname			= $_REQUEST['lastname'];
$firstname			= $_REQUEST['firstname'];
$middlename			= $_REQUEST['middlename'];
$filter				= $_REQUEST['filter'];


if($action == 'search')
{

		setProfSearch($emp_id_number,$lastname,$firstname,$middlename,$filter);
		
			echo '<script language="javascript">window.location =\'index.php?comp=com_professor_schedule\';</script>';
	
}

// component block, will be included in the template page
$content_template = 'components/block/blk_mn_professor.php';
?>