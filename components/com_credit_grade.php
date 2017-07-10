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





$page_title = 'Manage Credited Grade';

$pagination = 'Users  > Manage Credited Grade';



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

	

	$sql_del = "DELETE FROM tbl_student_credit_final_grade WHERE student_id = ".$stud_id;

	$result_del = mysql_query($sql_del);

	

	for($ctr=1;$ctr<$num;$ctr++)

	{

			if($grade[$ctr]!=''){
				
				$passfail = 'P';//checkIfGradeIsPass($grade[$ctr])=='Y'?'P':'F';

				$sql = "INSERT INTO tbl_student_credit_final_grade

						(

							student_id,

							term_id,

							subject_id,

							final_grade,

							type,

							remarks,

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

							".GetSQLValueString($grade[$ctr],"text").",

							"."'C'".", 	

							".GetSQLValueString($passfail,"text").",

							".time().",

							".USER_ID.", 

							".time().",

							".USER_ID."

						)";

				

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
									'N',
									".time().",
									".USER_ID."
		
								)";

					mysql_query ($sql);

						

					echo '<script language="javascript">alert("Successfully Saved!");window.location =\'index.php?comp=com_credit_grade\';</script>';

             }

         }

	}

}



if($view == 'edit')

{

		/*if(checkIfStudentIsCredited($stud_id))

		{

			$sql_chk = "SELECT * FROM tbl_student_credit_final_grade WHERE student_id = ".$stud_id;

			$res_chk = mysql_query($sql_chk);

			

			$str_arr = array();

			$stud_arr = array();

			

			$sql = "SELECT * FROM tbl_student  WHERE id = ".$stud_id;

			$query = mysql_query($sql);

			$row =mysql_fetch_array($query);

			

				$birthday = explode ('-',$row['birth_date']);		

				$birth_year = $birthday['0'];

				$birth_day = $birthday['1'];

				$birth_month = $birthday['2'];

				

				$gen = $row['gender']=='F'?'Female':'Male';



			$str_arr[] = '<div id="print_div">';

			$str_arr[] = '<div id="printable">';

			$str_arr[] = '<div class="body-container">';

			$str_arr[] = '<div class="header_big">';



			$str_arr[] = '<table style="padding:0 10px 30px 10px";>';     

			$str_arr[] = '<tr>';

			$str_arr[] = '<td style=" font-weight:bold; font-size:12px; ">&nbsp;Student Number:</td>';

			$str_arr[] = '<td style=" font-size:12px; padding:0px 10px 0px 10px">'.$row['student_number'].'</td>';

			$str_arr[] = '</tr>';

			$str_arr[] = '<tr>';

			$str_arr[] = '<td style=" font-weight:bold; font-size:12px">&nbsp;Student Name:</td>';

			$str_arr[] = '<td style=" font-size:12px; padding:0px 10px 0px 10px">'.$row['lastname'].', '.$row['firstname'].' '.$row['middlename'].'</td>';

			$str_arr[] = '</tr>';

			$str_arr[] = '<tr>';

			$str_arr[] = '<td style=" font-weight:bold; font-size:12px; ">&nbsp;Birth of Date:</td>';

			$str_arr[] = '<td style=" font-size:12px; padding:0px 10px 0px 10px">'.date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year)).'</td>';

			$str_arr[] = '<td style=" font-weight:bold; font-size:12px">&nbsp;Sex:</td>';

			$str_arr[] = '<td style=" font-size:12px; padding:0px 10px 0px 10px">'.$gen.'</td>';

			$str_arr[] = '</tr>';

			$str_arr[] = '<tr>';

			$str_arr[] = '<td style=" font-weight:bold; font-size:12px">&nbsp;Address:</td>';

			$str_arr[] = '<td style=" font-size:12px; padding:0px 10px 0px 10px">'.$row['home_address'].'</td>';

			$str_arr[] = '</tr>';

			$str_arr[] = '<tr>';

			$str_arr[] = '<td style=" font-weight:bold; font-size:12px; ">&nbsp;Department:</td>';

			$str_arr[] = '<td style=" font-size:12px; padding:0px 10px 0px 10px">'.getStudentCollegeName($row['course_id']).'</td>';

			$str_arr[] = '<td style=" font-weight:bold; font-size:12px">&nbsp;Curriculum:</td>';

			$str_arr[] = '<td style=" font-size:12px; padding:0px 10px 0px 10px">'.getCurriculumCode($row['curriculum_id']).'</td>';

			$str_arr[] = '</tr>';

			$str_arr[] = '<tr>';

			$str_arr[] = '<td style=" font-weight:bold; font-size:12px; ">&nbsp;Last School Attended:</td>';

			$str_arr[] = '<td style=" font-size:12px; padding:0px 10px 0px 10px">'.getStudentLastSchool($row['id']).'</td>';

			$str_arr[] = '<td style=" font-weight:bold; font-size:12px">&nbsp;School Year:</td>';

			$str_arr[] = '<td style=" font-size:12px; padding:0px 10px 0px 10px">'.getSYandTerm($row['term_id']).'</td>';

			$str_arr[] = '</tr>';

			$str_arr[] = '</table>';

			

			$str_arr[] = '</div>';

			$str_arr[] = '<div class="content-container">';



			$str_arr[] = '<div class="content-wrapper-withBorder">';



			

			$str_arr[] = '<table class="listview">';

			

			$sql = "SELECT * FROM tbl_student_credit_final_grade  WHERE type='C' AND student_id = ".$stud_id;

			$query = mysql_query($sql);

			

				$str_arr[] = '<tr>';

        		$str_arr[] = '<td colspan="5">';

        		$str_arr[] = '<a class="viewer_email" href="#" id="email" title="email"></a>';

        		$str_arr[] = '<a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>';

        		$str_arr[] = '<a class="viewer_print" href="#" id="print" title="print"></a>';

        		$str_arr[] = '</td>';

        		$str_arr[] = '</tr>';   

			  $str_arr[] = '<tr class="'.$class.'">';

			  $str_arr[] = '<td width="150">Subject Code</td>';

			  $str_arr[] = '<td>Subject Name</td>';

			   $str_arr[] = '<td>Unit</td>';

			   $str_arr[] = '<td width="110">Grade</td>';

			    $str_arr[] = '<td>Remarks</td>';

			  $str_arr[] = '</tr>';

				$units = 0;

				while($row_dis = mysql_fetch_array($query)) 

				{ 						

					$str_arr[] = '<tr class="'.$class.'">';

					$str_arr[] = '<td>'.getSubjCode($row_dis['subject_id']).'</td>';

					$str_arr[] = '<td>'.getSubjName($row_dis['subject_id']).'</td>';

					$str_arr[] = '<td>'.getSubjUnit($row_dis['subject_id']).'</td>';

					$str_arr[] = '<td>'.$row_dis['final_grade'].'</td>';

					$str_arr[] = $row_dis['remarks']=='P'?'<td>Passed</td>':'<td>Failed</td>';

					

					$units+=getSubjUnit($row_dis['subject_id']);

				   }  

				   $str_arr[] = '<tr>';

        		$str_arr[] = '<td colspan="2" align="right">Total Units</td>';

        		$str_arr[] = '<td colspan="3">'.$units.'</td>';

        		$str_arr[] = '</tr>';  

			 $str_arr[] = '</tr>';

			 $str_arr[] = '</table>';

			  $str_arr[] = '</div>';

			 $str_arr[] = '</div>';

			 $str_arr[] = '</div>';

			 $str_arr[] = '</div>';

			 $str_arr[] = '</div>';

			

			$grade_list = implode('',$str_arr); 

		}

		else

		{*/

			$sql_chk = "SELECT * FROM tbl_student_credit_final_grade WHERE student_id = ".$stud_id;

			$res_chk = mysql_query($sql_chk);

			

			$str_arr = array();

			$stud_arr = array();

			

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

			   $str_arr[] = '<td width="110">Final Grade</td>';

			  $str_arr[] = '</tr>';

			

				while($row_dis = mysql_fetch_array($query_sub)) 

				{ 

					$sql_chk = "SELECT final_grade FROM tbl_student_credit_final_grade WHERE student_id = ".$stud_id." AND subject_id = ".$row_dis['subject_id']." AND type='C'";

					$res_chk = mysql_query($sql_chk);

					$rowchk = mysql_fetch_array($res_chk);

					//$grade = decrypt($rowchk[0]);
					$grade = $rowchk[0];

						

					$str_arr[] = '<tr class="'.$class.'">';

					$str_arr[] = '<td>'.getSubjCode($row_dis['subject_id']).'</td>';

					$str_arr[] = '<td>'.getSubjName($row_dis['subject_id']).'</td>';

					$str_arr[] = '<td><input name="grade['.$ctr.']" type="text" value="'.$grade.'" id="grade'.$ctr.'" class ="txt_50" ';

					if($lock !=''){ $str_arr[] ='readonly="readonly"'; }

					$str_arr[] ='><input name="subject['.$ctr.']" type="hidden" value="'.$row_dis['subject_id'].'" id="subject'.$ctr.'" class ="txt_50"><input name="term['.$ctr.']" type="hidden" value="'.$ctr_terms.'" id="term'.$ctr.'" class ="txt_50"></td>';

				   $ctr++;

				   }  

				}

			 }

			}       

			 $str_arr[] = '</tr>';

			 $str_arr[] = '</table>';

			 

		 $grade_list = implode('',$str_arr);

		 $stud_list = implode('',$stud_arr); 

		//}

        

}

// component block, will be included in the template page

$content_template = 'components/block/blk_com_credit_grade.php';

?>