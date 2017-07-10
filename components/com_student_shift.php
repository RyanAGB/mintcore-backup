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





$page_title = 'Manage Student Shift Course';

$pagination = 'Users  > Manage Student Shift Course';



$view = $view==''?'list':$view; // initialize action



$id	= $_REQUEST['id'];

$temp 	= $_REQUEST['temp'];



$stud_id	= $_REQUEST['stud_id'];

$num	= $_REQUEST['num'];

$fil_term = $_REQUEST['fil_term'];

$grade = $_REQUEST[grade];

$subject = $_REQUEST[subject];

$term = $_REQUEST[term];



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

	

	//$sql_del = "DELETE FROM tbl_student_grade WHERE professor_id = ".USER_EMP_ID;

	//$result_del = mysql_query($sql_del);

	

	for($ctr=1;$ctr<$num;$ctr++)

	{

			if($grade[$ctr]!=''){
				
				$passfail = checkIfGradeIsPass($grade[$ctr])=='Y'?'P':'F';

				$sql = "INSERT INTO tbl_student_final_grade

						(

							student_id,

							term_id,

							subject_id,

							final_grade,

							type,

							remarks,

							grade_conversion_id,

							date_created, 

							created_by,

							date_modified,

							modified_by

						) 

						VALUES 

						(

							".GetSQLValueString($id,"int").",

							".GetSQLValueString($term[$ctr],"text").", 

							".GetSQLValueString($subject[$ctr],"text").", 

							".GetSQLValueString(encrypt($grade[$ctr]),"text").",

							"."'C'".", 	

							".GetSQLValueString($passfail,"text").",

							".getGradeConversionId($grade[$ctr]).",

							".time().",

							".USER_ID.", 

							".time().",

							".USER_ID."

						)";

				

				if(mysql_query ($sql))

				{

					$sql = "INSERT INTO tbl_credited_student

						(

							student_id

						) 

						VALUES 

						(

							".GetSQLValueString($id,"int")."

						)";

					mysql_query ($sql);

						

					echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_credit_grade\';</script>';

             }

         }

	}

}



if($view == 'edit')

{


			$sql_chk = "SELECT * FROM tbl_student_final_grade WHERE student_id = ".$stud_id;

			$res_chk = mysql_query($sql_chk);

			

			$str_arr = array();

			$stud_arr = array();
			

			$sqlc = "UPDATE tbl_student SET course_id = ".$fil_term.", curriculum_id=".getCurriculumByCourseId($fil_term)." WHERE id=".$stud_id;

			mysql_query($sqlc);

			$sql = "SELECT * FROM tbl_student  WHERE id = ".$stud_id;

			$query = mysql_query($sql);

			$row =mysql_fetch_array($query);		

			

			$sql_dis = "SELECT * FROM tbl_curriculum WHERE id = ".$row['curriculum_id'];

			$result_dis = mysql_query($sql_dis);

			$row_dis = mysql_fetch_array($result_dis);

			$no_year = $row_dis['no_of_years'];

			$no_term = $row_dis['term_per_year'];

			

			$summer = $no_term+1;

			$sql_subj = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = ".$row['curriculum_id'];

			$query_subj = mysql_query($sql_subj);

			$num = mysql_num_rows($query_subj);

					 

	 $ctr = 1;

			 

			$str_arr[] = '<table>';     

			$str_arr[] = '<tr>';

			$str_arr[] = '<td style=" font-weight:bold; font-size:12px">&nbsp;Student Number:</td>';

			$str_arr[] = '<td style=" font-size:12px">'.$row['student_number'].'</td>';

			$str_arr[] = '</tr>';

			$str_arr[] = '<tr>';

			$str_arr[] = '<td style=" font-weight:bold; font-size:12px">&nbsp;Student Name:</td>';

			$str_arr[] = '<td style=" font-size:12px">'.$row['lastname'].', '.$row['firstname'].' '.$row['middlename'].'</td>';

			$str_arr[] = '</tr>';

			$str_arr[] = '<tr>';

			$str_arr[] = '<td style=" font-weight:bold; font-size:12px">&nbsp;Course:</td>';

			$str_arr[] = '<td style=" font-size:12px">'.getCourseName($row['course_id']).'</td>';

			$str_arr[] = '</tr>';

			$str_arr[] = '</table>';

			$str_arr[] = '<p>';

		 for($ctr_year = 1; $ctr_year<= $no_year; $ctr_year++){

			 for($ctr_terms = 1; $ctr_terms<= $summer; $ctr_terms++){

	

			 $str_arr[] = '<table class="listview">';     

				

			 $sql_sub = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = ".$row['curriculum_id']." AND year_level = ".$ctr_year." AND term = ".$ctr_terms;

			$query_sub = mysql_query($sql_sub); 

	

			if(mysql_num_rows($query_sub)>0)

			{

	

				$str_arr[] = '<tr>';

			 $str_arr[] = '<th class="col_150" colspan="3">'.getYearLevel($ctr_year).' ( ';

			 $ctr_terms==$summer?$str_arr[]='Summer )</th>':$str_arr[]=getSemesterInWord($ctr_terms).' )</th>';            

				$str_arr[] = '</tr>';

	

			  $str_arr[] = '<tr class="'.$class.'">';

			  $str_arr[] = '<td width="150">Subject Code</td>';

			  $str_arr[] = '<td>Subject Name</td>';

			   $str_arr[] = '<td width="110"></td>';

			  $str_arr[] = '</tr>';

			

				while($row_dis = mysql_fetch_array($query_sub)) 

				{ 

					$sql_chk = "SELECT * FROM tbl_student_final_grade WHERE student_id = ".$stud_id." AND subject_id = ".$row_dis['subject_id'];

					$res_chk = mysql_query($sql_chk);

					$rowchk = mysql_fetch_array($res_chk);

					$grade = decrypt($rowchk['final_grade']);
					
					$r = $rowchk['remarks']=='P'?'Credited':'';

					$i = $row_dis['subject_category']!='R'?'<input type="text">':'';
					

					$str_arr[] = '<tr class="'.$class.'">';

					$str_arr[] = '<td>'.getSubjCode($row_dis['subject_id']).'</td>';

					$str_arr[] = '<td>'.getSubjName($row_dis['subject_id']).'</td>';

					$str_arr[] = '<td>'.$r.$i;

					$str_arr[] ='<input name="subject['.$ctr.']" type="hidden" value="'.$row_dis['subject_id'].'" id="subject'.$ctr.'" class ="txt_50"><input name="term['.$ctr.']" type="hidden" value="'.$ctr_terms.'" id="term'.$ctr.'" class ="txt_50"></td>';

				   $ctr++;

				   }  

				}

			 }

			}       

			 $str_arr[] = '</tr>';

			 $str_arr[] = '</table>';

			 

		 $grade_list = implode('',$str_arr);

		 $stud_list = implode('',$stud_arr); 



        

}

// component block, will be included in the template page

$content_template = 'components/block/blk_com_student_shift.php';

?>