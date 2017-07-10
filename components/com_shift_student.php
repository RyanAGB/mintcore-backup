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





$page_title = 'Manage Student Shift';

$pagination = 'Users  > Manage Student Shift';



$view = $view==''?'list':$view; // initialize action



$id	= $_REQUEST['id'];

$temp 	= $_REQUEST['temp'];



$stud_id	= $_REQUEST['stud_id'];

$course	= $_REQUEST['course'];

$num = $_REQUEST['num'];


$filter_field = $_REQUEST['filter_field'];

$filter_order = $_REQUEST['filter_order'];

$rows = $_REQUEST['rows'];

$page = $_REQUEST['page'];



	if($_SESSION[CORE_U_CODE]['current_comp'] != $comp || $_SESSION[CORE_U_CODE]['pageNum'] != $page || $_SESSION[CORE_U_CODE]['pageRows'] != $rows || $_SESSION[CORE_U_CODE]['fieldName'] != $filter_field || $_SESSION[CORE_U_CODE]['orderBy'] != $filter_order)

	{

		if($page != '')

		{

			$_SESSION[CORE_U_CODE]['pageNum'] = isset($page)&&$page!='' ? $page : '1';

		}

		if($rows != '')

		{

			$_SESSION[CORE_U_CODE]['pageRows'] = isset($rows)&&$rows!='' ? $rows : '10';

		}

		if($filter_field != '' || $filter_order != '')

		{

			$_SESSION[CORE_U_CODE]['fieldName'] = $filter_field;

			$_SESSION[CORE_U_CODE]['orderBy'] = $filter_order;

		}

		$_SESSION[CORE_U_CODE]['current_comp'] = $comp;

	}



if($action == 'update')

{


	$elec = explode(',',$num);

	if($course!='')

	{
		$cur = getCurriculumByCourseId($course);

				$sql = "UPDATE tbl_student SET

							course_id = ".GetSQLValueString($course,"int").",
							
							curriculum_id = ".GetSQLValueString($cur,"int")."

						WHERE 

							id = ".$id;

				if(mysql_query ($sql))

				{
					$sql = "INSERT INTO tbl_credited_student
		
								(
		
									student_id,
									is_shifted,
									date_created,
									created_by
		
								) 
		
								VALUES 
		
								(
		
									".GetSQLValueString($id,"int").",
									'Y',
									".time().",
									".USER_ID."
		
								)";
		
							mysql_query ($sql);
					
						
					foreach($elec as $e)
					{
						if($e!='')
						{
							
							$sql = "UPDATE tbl_student_schedule SET subject_id=".$e.",elective_of=".$_REQUEST[$e]." WHERE enrollment_status='A' AND subject_id=".$_REQUEST[$e]." AND student_id=".$id;
							mysql_query ($sql);
							
							$sql = "UPDATE tbl_student_final_grade SET subject_id=".$e." WHERE subject_id=".$_REQUEST[$e]." AND student_id=".$id;
							mysql_query ($sql);
							
							$sql = "UPDATE tbl_student_grade SET subject_id=".$e." WHERE subject_id=".$_REQUEST[$e]." AND student_id=".$id;
							mysql_query ($sql);
						}
						
					}

						

					echo '<script language="javascript">alert("Successfully Shifted!");window.location =\'index.php?comp=com_shift_student\';</script>';

             }

	}

}



if($view == 'edit')

{
	

}

// component block, will be included in the template page

$content_template = 'components/block/blk_com_shift_student.php';

?>