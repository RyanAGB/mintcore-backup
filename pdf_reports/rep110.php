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
include_once("../includes/functions.php");
include_once("../includes/common.php");

//----------------------------------------------------------------------------------------------------
error_reporting( 0 );
print_info();

//----------------------------------------------------------------------------------------------------

function print_info()
{
	global $path_to_root;
	
	include_once("pdf_report.inc");

	$cols = array(4, 60, 225, 300, 345, 390, 465, 530);

	$aligns = array('left',	'left',	'left', 'left', 'left', 'left', 'left');
	$margins=array('top'=>10,'bottom'=>10,'right'=>30,'left'=>30);
	
	$rep = new FrontReport('Transcript', 'transcript', 'A3', 11, 'P', $margins);
	$rep->Font();
		$rep->Info($params, $cols, null, $aligns);

	/*if ($email == 0)
	{
		$rep = new FrontReport('INVOICE', "InvoiceBulk");
		$rep->Font();
		$rep->Info($params, $cols, null, $aligns);
	}*/
		
			
		if($_REQUEST['met']=='curriculum')
				{
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];	
					 $querys = mysql_query($sqls);
				  $rows = mysql_fetch_array($querys);
					
					$rep->title = 'STUDENT INFO';
					$rep->No_space_Header();
					
					$rep->fontSize += 2;
					$rep->Font('bold');
					$rep->TextCol(1, 7, "                                               MERIDIAN INTERNATIONAL BUSINESS,");
					$rep->NewLine();
					$rep->TextCol(1, 7, "                         MERIDIAN INTERNATIONAL BUSINESS,ARTS and TECHNOLOGY COLLEGE");
					$rep->Font();
						$rep->NewLine();
						$rep->TextCol(1, 8, "                   1030 Campus Ave. 2F CIP Building Mckinley Hill, Fort Bonifacio, Taguig City, Philippines");
						$rep->NewLine();
						$rep->Font('bold');
						$rep->TextCol(1, 7, "                          ".getCourseName($rows['course_id']));
						$rep->NewLine();
					
						$rep->TextCol(0, 3, "NAME : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);
						
						$rep->TextCol(3, 7, "ENTRY AY : ".getSchoolYearStudentStart($_REQUEST['id']));
						$rep->Font('');
						//$rep->NewLine();
						$rep->fontSize -= 2;
						
					
					$sql = "SELECT * FROM tbl_student  WHERE id = ".$_REQUEST['id'];
					$query = mysql_query($sql);
					$row =mysql_fetch_array($query);	
					$cur_id = $row['curriculum_id'];	
					
					$sql_dis = "SELECT * FROM tbl_curriculum WHERE id = ".$row['curriculum_id'];
					$result_dis = mysql_query($sql_dis);
					$row_dis = mysql_fetch_array($result_dis);
					$no_year = $row_dis['no_of_years'];
					$no_term = $row_dis['term_per_year'];
					
					//$summer = $no_term+1;
					$sql_subj = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = ".$cur_id;
					$query_subj = mysql_query($sql_subj);
					$num = mysql_num_rows($query_subj);
							 
			 		$ctr = 1;
					
					for($ctr_year = 1; $ctr_year<= $no_year; $ctr_year++)
					{
						 for($ctr_terms = 1; $ctr_terms<= $no_term; $ctr_terms++)
        				{
						$rep->NewLine();
						//$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextColLines(0, 3, getYearLevel($ctr_year).'('.getSemesterInWord($ctr_terms).')');
						$rep->Font();
						//$rep->fontSize -= 1;
						
						
						$rep->TextCol(0, 1, "Code");
						$rep->TextCol(1, 2, "Course Title");
						$rep->TextCol(2, 3, "     Unit             ");
						$rep->TextCol(3, 5, "Grade             ");
						$rep->TextCol(5, 6, "Pre-req");
						$rep->NewLine();
						  
						$total_units	= 0;
					  	$sql_sub = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = ".$cur_id." AND year_level = ".$ctr_year." AND term = ".$ctr_terms;
						$query_sub = mysql_query($sql_sub); 
					  	$ctr_row = mysql_num_rows($query_sub);
					  	if($ctr_row > 0)
					  	{
						  while($row = mysql_fetch_array($query_sub))
						  {
							  $sqlc = "SELECT * FROM tbl_student_credit_final_grade WHERE student_id=".$_REQUEST['id']." AND subject_id=".$row['subject_id'];
						  $queryc = mysql_query($sqlc);
						  $rowc = mysql_fetch_array($queryc);
							 
							 if(mysql_num_rows($queryc)>0)
							{
								$str = $rowc['final_grade'].'(credited)';
							}
							else if(checkIfSubjectINCBySubj($_REQUEST['id'],$row['subject_id']))
							{
							$str = 'INC';	
							}
							else if(getStudentFinalGradeBySubject2($_REQUEST['id'],$row['subject_id'])>0)
							{
							$str = getStudentFinalGradeBySubject2($_REQUEST['id'],$row['subject_id']);
							}
							else if(checkIfstudentFailedSubject($_REQUEST['id'],$row['subject_id']))
							{
							$str = 'Failed';
							}
							else if(CheckIfStudentFinishEnrolledBySubject($_REQUEST['id'],$row['subject_id']))
							{
								if(CheckIfStudentEnrolledBySubject($_REQUEST['id'],CURRENT_TERM_ID,$row['subject_id']))
								{
								$str = 'Currently Enrolled';
								}else{
								$str = 'Currently In Process';	
								}
							}
							
							else
							{
							$str = ' ';
							}
						
							$rep->TextCol(0, 1, getSubjCode($row['subject_id']));
							$rep->TextCol(1, 2, getSubjName($row['subject_id']));
							$rep->TextCol(2, 3, "     ".$row['units']);
							$rep->Font('u');
							$rep->TextCol(3, 5, $str);
							$rep->Font('');
							$rep->TextCol(5, 6, getPrereqOfSubject($row['id']));
							
							$rep->NewLine();
							$total_units += $row['units'];
							
							
							//$rep->TextCol(0, 6, "SELECT * FROM tbl_student_grade WHERE subject_id = ".$row['subject_id']." AND student_id=".$_REQUEST['id']);
						}
						//$rep->TextCol(0, 6, "__________________________________________________________________________________________________________________________________");
						//$rep->NewLine(1,2);
						$rep->Font('u');
						$rep->TextCol(2, 3, 'Total : '.$total_units);
						$rep->Font('');
						}
					}
					}
					
					$rep->NewLine(2);
						$rep->TextCol(6, 7, "DATE: ".date('m-d-Y'));
				}	
				else if($_REQUEST['met']=='declined list')
				{}
				
				
				if (isset($_REQUEST['email'])&&$_REQUEST['email']==1)
			{
				$rep->End(1, isset($_REQUEST['emp'])?"Employee ".ucfirst($_REQUEST['met']):"Student ".ucfirst($_REQUEST['met']), $rows);
			}
			else
			{
				$rep->End();
			}


}
?>