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
	
	$rep = new FrontReport('Transcript', 'transcript', 'letter', 7, 'P', $margins);
	
	

	if ($email == 0&&$_REQUEST['met']!='transcript')
	{
		$rep = new FrontReport('INVOICE', "InvoiceBulk");

		$rep->Font();
		$rep->Info($params, $cols, null, $aligns);
	}
		if(!isset($_REQUEST['emp']))
		{
		  if($_REQUEST['met']=='course curriculum')
		  {
		  	$sql = "SELECT * FROM tbl_curriculum WHERE id = ".$_REQUEST['id'];
			$query = mysql_query($sql);
			$row = mysql_fetch_array($query);
			
			$rep->title = 'Curriculum';
			$rep->Header();
			
				$rep->TextCol(0, 3, "Curriculum : ".$row['curriculum_code']);
			
				$rep->NewLine();
	
				$rep->TextCol(0, 3, "Course : ".getCourseName($row['course_id']));
		
				$rep->NewLine(3);
		  }
		  else if($_REQUEST['met']=='schedule list')
				{		
					$sqldet = "SELECT * FROM tbl_schedule WHERE id = ".$_REQUEST['id'];
					$resultdet = mysql_query($sqldet);
					$row = mysql_fetch_array($resultdet);
					
					$rep->Header();
					/*$rep->TextCol(0, 1, "Course:");
					$rep->TextCol(1, 5, getCourseName($_REQUEST['course']));
					$rep->NewLine();
					$rep->NewLine();
					$rep->TextCol(0, 1, "School Year");
					$rep->TextCol(1, 2, getSYandTerm($_REQUEST['trm']));
					$rep->TextCol(2, 3, "Year Level:");
					$rep->TextCol(3, 4, getYearLevel($_REQUEST['year']));
					$rep->NewLine();*/
					$rep->TextCol(0, 1, "Subject Code: ");
					$rep->Font('bold');
					$rep->TextCol(1, 2, getSubjCode($row["subject_id"]));
					$rep->Font('');
					$rep->TextCol(3, 5, "Class Schedule:");
					$rep->NewLine();
					$rep->TextCol(3, 6, getScheduleDays($_REQUEST['id']));
					$rep->TextCol(0, 1, "Subject Title: ");
					$rep->Font('bold');
					$rep->TextCol(1, 2, getSubjName($row["subject_id"])."(".getSubjName($row['elective_of']).")");
					$rep->Font('');
					$rep->NewLine();
					$rep->TextCol(0, 1, "Instructor: ");
					$rep->Font('bold');
					$rep->TextCol(1, 2, getEmployeeFullNameBySchedId($_REQUEST['id']));
					$rep->Font('');
					/*$rep->TextCol(2, 3, "Department:");
					$rep->TextCol(3, 4, getStudentCollegeName($_REQUEST['course']));
					$rep->NewLine();*/
					
					//$rep->TextCol(2, 3, "Room:");
					$rep->TextCol(3, 6, getRoomNo($row["room_id"]));
					$rep->NewLine();
					
					$sql = "SELECT * FROM tbl_student_schedule a,tbl_student b 
					WHERE a.student_id=b.id 
					AND a.schedule_id =  " . $_REQUEST['id'] . " AND a.term_id = ".$_REQUEST['trm']." ORDER BY b.lastname";//. " AND b.course_id=".$_REQUEST['course']." AND b.year_level=".$_REQUEST['year'];	
										
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(700);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(675);
					$rep->NewLine();
					$rep->NewLine();
					
					$rep->TextCol(0, 1, "");
					$rep->TextCol(0, 2, "         Student No.");
					$rep->TextCol(1, 3, "                       Student Name");
					$rep->TextCol(4, 6, "Course");
					$rep->NewLine();
					$rep->NewLine();
					
					$x=1;
					while($row = mysql_fetch_array($result)) 
					{
						$rep->TextCol(0, 1, $x);
						$rep->TextCol(0, 2, '         '.$row["student_number"]);
						$rep->TextCol(1, 3, '                       '.$row["lastname"]." , ".$row["firstname"]." ".$row["middlename"]);
						$rep->TextCol(4, 6, getCourseCode($row["course_id"]));
						$rep->NewLine();
						$x++;
					}
					
					$rep->NewLine();
					$rep->TextCol(1, 6, "----------------------------------------------------nothing follows----------------------------------------------------");
					
					$sql2 = "SELECT * FROM tbl_student_reserve_subject a,tbl_student b 
					WHERE a.student_id=b.id 
					AND a.schedule_id =  " . $_REQUEST['id'] . " AND a.term_id = ".$_REQUEST['trm']." ORDER BY b.lastname";//. " AND b.course_id=".$_REQUEST['course']." AND b.year_level=".$_REQUEST['year'];	
										
					$result2 = mysql_query($sql2);
					$ctr2 = mysql_num_rows($result2);
					
					if($ctr2>0)
					{
					$rep->NewLine();
					$rep->TextCol(0, 2, 'NOT OFFICIALLY ENROLLED');
					$rep->NewLine();
					
					$x=1;
					while($row = mysql_fetch_array($result2)) 
					{
						$rep->TextCol(0, 1, $x);
						$rep->TextCol(0, 2, '         '.$row["student_number"]);
						$rep->TextCol(1, 3, '                       '.$row["lastname"]." , ".$row["firstname"]." ".$row["middlename"]);
						$rep->TextCol(4, 6, getCourseCode($row["course_id"]));
						$rep->NewLine();
						$x++;
					}
					}
					
				}
				
				else if($_REQUEST['met']=='sched_summary')
				{		
					$sqldet = "SELECT * FROM tbl_schedule WHERE term_id = ".$_REQUEST['trm']." ORDER BY subject_id";
					$resultdet = mysql_query($sqldet);
					
					
					$rep->Header();
					/*$rep->TextCol(0, 1, "Course:");
					$rep->TextCol(1, 5, getCourseName($_REQUEST['course']));
					$rep->NewLine();
					$rep->NewLine();
					$rep->TextCol(0, 1, "School Year");
					$rep->TextCol(1, 2, getSYandTerm($_REQUEST['trm']));
					$rep->TextCol(2, 3, "Year Level:");
					$rep->TextCol(3, 4, getYearLevel($_REQUEST['year']));
					$rep->NewLine();*/
					$rep->Font('bold');
					$rep->TextCol(0, 1, "Subject Code: ");
					$rep->Font('');
					$rep->TextCol(3, 5, "Classes ");
					$rep->NewLine();
					
					
					
					while($row = mysql_fetch_array($resultdet)) 
					{
						$rep->TextCol(0, 1, $x);
						$rep->TextCol(0, 2, '         '.$row["student_number"]);
						$rep->TextCol(1, 3, '                       '.$row["lastname"]." , ".$row["firstname"]." ".$row["middlename"]);
						$rep->TextCol(4, 6, getCourseCode($row["course_id"]));
						$rep->NewLine();
						$x++;
					}
					
					$rep->NewLine();
					$rep->TextCol(1, 6, "----------------------------------------------------nothing follows----------------------------------------------------");
					
				}
				
				else if($_REQUEST['met']=='room_load')
				{
				
							
					$sqldet = "SELECT * FROM tbl_room WHERE id = ".$_REQUEST['id'];
					$resultdet = mysql_query($sqldet);
					$row = mysql_fetch_array($resultdet);
					
					$rep->Blank_Header();
					$rep->NewLine(2);
					
					$rep->Font('bold');
					$rep->TextCol(1, 5, "CLASSROOM SCHEDULES | ".getSchoolTerm($_REQUEST['trm'])." | SY ".getSchoolYearStartEndByTerm($_REQUEST['trm']));
					$rep->fontSize += 6;
					$rep->TextCol(5, 7, $row['room_no']);
					$rep->fontSize -= 6;
					
					$rep->NewLine();
					$rep->TextCol(1, 5, "AS OF ".date('F d,Y'));
					$rep->Font('');
					$rep->NewLine(2);
					//
					$rep->TextCol(0, 8, "___________________________________________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					$rep->TextCol(0, 1, "");
					$rep->TextCol(1, 2, "     TIME");
					$rep->TextCol(1, 2, "                                       CODE");
					$rep->TextCol(2, 5, "SUBJECT DESCRIPTION");
					$rep->TextCol(6, 6, "BLOCK");
					$rep->TextCol(7, 8, "TEACHER");
					$rep->NewLine();
					
					$days = array('monday','tuesday','wednesday','thursday','friday','saturday');
					
					$x=1;
					
					foreach($days as $day)
					{
						$sql = "SELECT * FROM tbl_schedule 
					WHERE room_id =  " . $_REQUEST['id'] . " AND term_id = ".$_REQUEST['trm']." AND ".$day."='Y' ORDER BY ".$day."_time_from";				
						$result = mysql_query($sql);
						$ctr = mysql_num_rows($result);
						
						
						$rep->TextCol(0, 8, "___________________________________________________________________________________________________________________________________________________________________________");
						$rep->NewLine(2);
						$rep->TextCol(0, 1, strtoupper($day));
						
						while($row = mysql_fetch_array($result)) 
						{			
							$el = $row['elective_of']!=''?'('.getSubjName($row['elective_of']).')':'';		
							$rep->TextCol(1, 2, '     '.getScheduleTimeConverted($row[$day.'_time_from'],$row[$day.'_time_to']));
							$rep->TextCol(1, 2, "                                       ".getSubjCode($row['subject_id']));
							$rep->TextCol(2, 6, getSubjName($row['subject_id']).$el.'         ');
							$rep->TextCol(6, 6, $row['section_no']);
							$rep->TextCol(7, 8, getProfessorInitial($row['employee_id']));
							$rep->NewLine();
						}
						
						
						$x++;
					}
					
					$rep->TextCol(0, 8, "___________________________________________________________________________________________________________________________________________________________________________");
					
				}
				
				else if($_REQUEST['met']=='collection')
				{
				
							
					$sqldet = "SELECT * 
					FROM tbl_student student,
						 tbl_student_payment pay
					WHERE student.id = pay.student_id AND pay.date_created BETWEEN " . $_REQUEST['id']." AND ".$_REQUEST['trm'];
					$resultdet = mysql_query($sqldet);
					$row = mysql_fetch_array($resultdet);
					
					$rep->Header();
					$rep->NewLine(2);
					
					$rep->Font('bold');
					$rep->TextCol(1, 5, "COLLECTIONS FROM ".date('F d, Y',$_REQUEST['id'])." TO ".date('F d, Y',$_REQUEST['trm']));
					$rep->NewLine();
					$rep->Font('');
					$rep->NewLine(2);
					//
					$rep->TextCol(0, 8, "___________________________________________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					$rep->TextCol(0, 1, "");
					$rep->TextCol(1, 2, "     STUDENT NO.");
					$rep->TextCol(2, 5, "NAME");
					$rep->TextCol(5, 6, "     AMOUNT");
					$rep->NewLine();
					
					$days = array('monday','tuesday','wednesday','thursday','friday','saturday');
					
					$x=1;
						
						$rep->TextCol(0, 8, "___________________________________________________________________________________________________________________________________________________________________________");
						$rep->NewLine(2);
						
						while($row = mysql_fetch_array($resultdet)) 
						{
							$rep->TextCol(1, 2, '     '.$row['student_number']);
							$rep->TextCol(2, 6, $row['lastname'].', '.$row['firstname']);
							$rep->TextColNum(5, 6, number_format($row['amount'],2));
							$total+=$row['amount'];
							$rep->NewLine();
						}
					
					$rep->TextCol(0, 8, "___________________________________________________________________________________________________________________________________________________________________________");
					$rep->NewLine();
					$rep->Font('bold');
					$rep->TextColNum(5, 6, number_format($total,2));
					$rep->Font();
					
				}

		 
				else if($_REQUEST['met']=='course list')
				{		
					
					
					$rep->Header();
					/*$rep->TextCol(0, 1, "Course:");
					$rep->TextCol(1, 5, getCourseName($_REQUEST['course']));
					$rep->NewLine();
					$rep->NewLine();
					$rep->TextCol(0, 1, "School Year");
					$rep->TextCol(1, 2, getSYandTerm($_REQUEST['trm']));
					$rep->TextCol(2, 3, "Year Level:");
					$rep->TextCol(3, 4, getYearLevel($_REQUEST['year']));
					$rep->NewLine();*/
					$rep->Font('bold');
					$rep->TextCol(0, 7, "Course Code:    ".getCourseCode($_REQUEST['id']));
					$rep->NewLine();
					$rep->TextCol(0, 7, "Course Name:    ".getCourseName($_REQUEST['id']));
					$rep->Font('bold');
					$rep->Font('');
					$rep->NewLine();
					
					
					if($_REQUEST['filter'] == 'E')
					{
						$sql = "SELECT * FROM tbl_student a,tbl_student_enrollment_status b 
									WHERE a.id=b.student_id 
									AND a.course_id =  " . $_REQUEST['id'] . " AND b.enrollment_status = 'E' AND b.term_id = ".$_REQUEST['trm']. " ORDER BY a.lastname ASC" ;	
					
					}
					else
					{
					
						$sql = "SELECT * FROM tbl_student b 
					WHERE course_id =  " . $_REQUEST['id']." ORDER BY b.lastname ASC";
					
					}
					
										
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(715);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(685);
					$rep->NewLine();
					$rep->NewLine();
					
					$rep->TextCol(0, 1, "");
					$rep->TextCol(1, 2, "Student No.");
					$rep->TextCol(2, 7, "Student Name");
					$rep->NewLine();
					$rep->NewLine();
					
					$x=1;
					while($row = mysql_fetch_array($result)) 
					{
						$rep->TextCol(0, 1, $x);
						$rep->TextCol(1, 2, $row["student_number"]);
						$rep->TextCol(2, 7, $row["lastname"]." , ".$row["firstname"]." ".$row["middlename"]);
						$rep->NewLine();
						$x++;
					}
					
					$rep->NewLine();
					$rep->TextCol(1, 6, "----------------------------------------------------nothing follows----------------------------------------------------");
					
				}
				
				else if($_REQUEST['met']=='schedule')
				{
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];						
				  $querys = mysql_query($sqls);
				  $rows = mysql_fetch_array($querys);
					
					$rep->title = 'STUDENT INFO';
					$_REQUEST['met']=='assessment'?$rep->As_Header():$rep->Header();
					
						$rep->TextCol(0, 3, "Student Name : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Student Number : ".$rows['student_number']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Course : ".getCourseName($rows['course_id']));
						
						if(isset($_REQUEST['trm']))
						{
						$rep->NewLine();
						$rep->TextCol(0, 1, "School Year : ", -2);
						$rep->TextCol(1, 4, getSYandTerm($_REQUEST['trm']), -2);
						}
					
					$sql = "SELECT 
					stud_sched.subject_id,
					stud_sched.units,
					stud_sched.term_id,
					stud_sched.enrollment_status, 
					stud_sched.schedule_id ,
					sched.room_id, 
					sched.section_no
				FROM 
					tbl_student_schedule stud_sched LEFT JOIN tbl_schedule sched ON 
					stud_sched.schedule_id = sched.id
					WHERE enrollment_status <> 'D' AND
					stud_sched.student_id =  " . $_REQUEST['id'] . 
					" AND stud_sched.term_id = " . $_REQUEST['trm'];	
										
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(700);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(675);
					$rep->NewLine();
					$rep->NewLine();
					
					$rep->TextCol(0, 1, "Section");
					$rep->TextCol(1, 3, "Subject Name");
					$rep->TextCol(3, 4, "Code             ");
					$rep->TextCol(3, 7, "             Room");
					$rep->TextCol(3, 7, "                                            Days");
					$rep->NewLine();
					$rep->NewLine();
					
					while($row = mysql_fetch_array($result)) 
					{
						$rep->TextCol(0, 1, getSectionNo($row["schedule_id"]));
						$rep->TextCol(1, 3, getSubjName($row["subject_id"])."     ");
						$rep->TextCol(3, 4, getSubjCode($row["subject_id"]).'             ');
						$rep->TextCol(3, 7, "             ".getRoomNo($row["room_id"]));
						$rep->TextCol(3, 7, "                                            ".getScheduleDays($row["schedule_id"]));
						$rep->NewLine();
					}
				}
				
				else if($_REQUEST['met']=='soa')
				{
					$rep->Blank_Header();
					
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];						
				  	$querys = mysql_query($sqls);
				  	$rows = mysql_fetch_array($querys);
						
					$sqlbal = "SELECT * FROM tbl_student_payment WHERE student_id =".$_REQUEST['id']." AND term_id=".$_REQUEST['trm'];
					$querybal = mysql_query($sqlbal);
					
					$cnt = 0;
					
					while($rowbal = mysql_fetch_array($querybal))
					{
						$totalpayment += $rowbal['amount'];
						
						
						
						
						
						if($cnt==0)
						{
							$date_enroll = $rowbal['date_created'];	
						}
						
						$cnt++;
						$line2[$cnt] = $rowbal['or_no'].'       '.date('Y-m-d',$rowbal['date_created']).'            '.$rowbal['amount'];
					}
					
					if(checkIfStudentIsEnrollByTerm($_REQUEST['id'],$_REQUEST['trm']))
					{
						$totalfee = getStudentTotalFee($_REQUEST['id'],$_REQUEST['trm']);
						
						$bal = $totalfee-$totalpayment;
					}
					
					$rep->Image2('../images/mint_logo.png', 10, 10,140,86);
					
					
					$rep->NewLine(3);
					
					$rep->TextCol(0, 8, "        ________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					$rep->Font('bold');
					$rep->fontSize += 2;
					$rep->TextCol(2, 7, "STATEMENT OF ACCOUNT");
					$rep->Font();
					$rep->fontSize -= 1;
					$rep->NewLine();
					$rep->TextCol(2, 4, "        AS OF   ".date('m/d/Y'));
					$rep->NewLine();
					$rep->TextCol(0, 8, "        ________________________________________________________________________________________________________________________________");
					
					$rep->NewLine(2);
					$rep->TextCol(0, 3, "        SY : ".getSchoolYearStartEndByTerm($_REQUEST['trm']));
					$rep->TextCol(1, 3, "                                       TERM : ".getSchoolTerm($_REQUEST['trm']));
					$rep->TextCol(3, 7, "PROGRAM : ".getCourseCode($rows['course_id']));
					//$rep->TextCol(6, 7, "YEAR LEVEL : ".getStudentYearLevel($rows['id']));
					//$rep->TextCol(5, 8, "                   STUDENT NO. : ".$rows['student_number']);
					$rep->NewLine();
					
					//$rep->TextCol(3, 7, $rows['admission']=='F'?'STATUS : Regular':'STATUS : Irregular');
					
					$rep->NewLine();
					$rep->TextCol(0, 3, "        LASTNAME : ");
					$rep->TextCol(1, 3, "                                       FIRSTNAME :");
					//$rep->TextCol(3, 7, "MIDDLE INITIAL : ");
					$rep->TextCol(3, 7, "STUDENT NO. : ");
					$rep->NewLine();
					$rep->Font('bold');
					$rep->TextCol(0, 3, "        ".$rows['lastname']);
					$rep->TextCol(1, 3, "                                       ".$rows['firstname']);
					//$rep->TextCol(3, 7, $rows['middlename']);
					$rep->TextCol(3, 7, $rows['student_number']);
					$rep->Font();
					$rep->NewLine(2);
					$rep->TextCol(0, 3, "        ADDRESS : ");
					$rep->Font('bold');
					$rep->TextCol(0, 8, "                              ".$rows['home_address']);
					$rep->Font('');
					$rep->NewLine();
					$rep->TextCol(0, 8, "        ________________________________________________________________________________________________________________________________");
					
					$rep->NewLine(3);
					$part = getNextBalanceDate(GetStudentScheme($_REQUEST['id'],$_REQUEST['trm']),$totalfee,$_REQUEST['trm'],$_REQUEST['id']);
					if(strtotime($part[0])>=strtotime(date('Y-m-d')."+1 day"))
					{
						$d=date('F d, Y',strtotime($part[0]));
					}else{
						$d='immediately';
					}
					
					$sqlbal2 = "SELECT * FROM tbl_student_balance WHERE student_id =".$_REQUEST['id'];
					$querybal2 = mysql_query($sqlbal2);
					$rowbal2 = mysql_fetch_array($querybal2);
					$bal2 = $rowbal2['amount']>0?$rowbal2['amount']:0;
					
					$sqlbal3 = "SELECT * FROM tbl_student_balance WHERE is_ipad='Y' AND student_id =".$_REQUEST['id'];
					$querybal3 = mysql_query($sqlbal3);
					$rowbal3 = mysql_fetch_array($querybal3);
					$bal3 = $rowbal3['amount']>0?$rowbal3['amount']:0;
					
					$rep->TextColLinesJust(0, 8, "        Our records show that you have a total balance of  ".number_format($bal+$bal2+$bal3, 2, ".", ",")."  of which the amount ".number_format($part[1]+$bal2+$bal3, 2, ".", ",")."  is due on ".$d.".");
					$rep->TextCol(0, 8, "      Please present this to the Front Desk to facilitate payment. If payment has been made, please disregard this notice and ");
					$rep->NewLine();
					$rep->TextCol(0, 8, "      accept our thanks.");
					$rep->NewLine();						
					
					
					$sqlsch = "SELECT *

				FROM tbl_payment_scheme_details

				WHERE scheme_id = ".GetStudentScheme($_REQUEST['id'],$_REQUEST['trm'])." ORDER BY sort_order";

                $resultsch = mysql_query($sqlsch);
				$cnt = 0;

                while($rowsch = mysql_fetch_array($resultsch)) 

                {

					if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'A')

					{

						$topay = $rowsch['payment_value'];

						$totalfee = $totalfee - $topay;

						$initial = $rowsch['id'];

					}

					else if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'P')

					{

						$topay = abs(getStudentPaymentScheme($rowsch['id'],$totalfee));;

						//$total_fee = $sub_total - $down;

						$initial = $rowsch['id'];

					}

					else if($rowsch['payment_type'] == 'P')

					{

						$topay = abs(getStudentPaymentScheme($rowsch['id'],$totalfee));

					}
					$cnt++;
					
					$line[$cnt]['name'] = $rowsch['payment_name'];
					$line[$cnt]['det'] = $rowsch['payment_date'].'       '.number_format($topay, 2, ".", ",");
				}
					
					$rep->TextCol(0, 8, "        ________________________________________________________________________________________________________________________________");
					
					$rep->NewLine(2);
					$rep->TextCol(1, 3, "       ASSESSMENT");
					$rep->TextCol(3, 7, "PAYMENT");	
					$rep->TextCol(6, 7, "        BALANCE");
					
					$rep->NewLine(2);
					$rep->TextCol(0, 3, "     Particulars");
					$rep->TextCol(1, 7, "                 Date");	
					$rep->TextCol(1, 7, "                                       Amt Due");
					$rep->TextCol(2, 7, "        O/R No.");
					$rep->TextCol(2, 7, "                             Date");
					$rep->TextCol(3, 7, "                            Amount Paid");
					
					$rep->NewLine();
					$rep->TextCol(0, 8, "        ________________________________________________________________________________________________________________________________");
					
					$rep->NewLine(2);
					
					if($bal2>0)
					{
					$rep->TextCol(0, 2, "     Previous Balance : ".number_format($bal2, 2, ".", ","));
					$rep->NewLine();
					}
					
					
					if($bal3>0)
					{
					$rep->NewLine();
					$rep->TextCol(0, 2, "     IPAD Balance : ".number_format($bal3, 2, ".", ","));
					$rep->NewLine();
					}
					
					$totalfee = $totalfee+$bal2+$bal3;
					$bal = $totalfee-$totalpayment;
					//$rep->MultiCell(550, 10, '', 1, '', 0, 0, 1, 0, true, 0);
					$rep->TextCol(0, 2, "     ".$line[1]['name']);
					$rep->TextCol(1, 2, "             ".$line[1]['det']);
					$rep->TextCol(2, 7, "        ".$line2[1]);
					$rep->TextCol(6, 7, "        ".number_format($totalfee, 2, ".", ","));
					$rep->NewLine();
					$rep->TextCol(0, 2, "     ".$line[2]['name']);
					$rep->TextCol(1, 2, "             ".$line[2]['det']);
					$rep->TextCol(2, 7, "        ".$line2[2]);
					$rep->TextCol(6, 7, "        ( ".number_format($totalpayment, 2, ".", ",")." )");
					$rep->NewLine();
					$rep->TextCol(0, 2, "     ".$line[3]['name']);
					$rep->TextCol(1, 2, "             ".$line[3]['det']);
					$rep->TextCol(2, 7, "        ".$line2[3]);
					$rep->NewLine();
					$rep->TextCol(0, 2, "     ".$line[4]['name']);
					$rep->TextCol(1, 2, "             ".$line[4]['det']);
					$rep->TextCol(2, 7, "        ".$line2[4]);
					$rep->NewLine();
					$rep->TextCol(0, 2, "     ".$line[5]['name']);
					$rep->TextCol(1, 2, "             ".$line[5]['det']);
					$rep->TextCol(2, 7, "        ".$line2[5]);
					$rep->NewLine();
					$rep->TextCol(0, 2, "     ".$line[6]['name']);
					$rep->TextCol(1, 2, "             ".$line[6]['det']);
					$rep->TextCol(2, 7, "        ".$line2[6]);
					$rep->NewLine();
					$rep->TextCol(0, 2, "     ".$line[7]['name']);
					$rep->TextCol(1, 2, "             ".$line[7]['det']);
					$rep->TextCol(2, 7, "        ".$line2[7]);
					$rep->NewLine();
					$rep->TextCol(0, 2, "     ".$line[8]['name']);
					$rep->TextCol(1, 2, "             ".$line[8]['det']);
					$rep->TextCol(2, 7, "        ".$line2[8]);
					$rep->NewLine();
					$rep->TextCol(0, 2, "     ".$line[9]['name']);
					$rep->TextCol(1, 2, "             ".$line[9]['det']);
					$rep->TextCol(2, 7, "        ".$line2[9]);
					$rep->NewLine();
					$rep->TextCol(0, 2, "     ".$line[10]['name']);
					$rep->TextCol(1, 2, "             ".$line[10]['det']);
					$rep->TextCol(2, 7, "        ".$line2[10]);
					
					$rep->NewLine(5);
					
					$rep->TextCol(0, 8, "        ________________________________________________________________________________________________________________________________");
					$rep->NewLine(3);
					$rep->TextCol(0, 3, "     TOTAL");
					$rep->Font('bold');
					$rep->TextCol(1, 7, "                                       ".number_format($totalfee, 2, ".", ","));
					$rep->Font('');
					$rep->TextCol(2, 7, "        TOTAL");
					$rep->Font('bold');
					$rep->TextCol(3, 7, "                            ".number_format($totalpayment, 2, ".", ","));
					$rep->TextCol(6, 7, "        ".number_format($bal, 2, ".", ","));
					$rep->Font('');
					
					
					
					$rep->NewLine(2);
					
					$rep->TextCol(0, 8, "        ________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					$rep->Font("bold");
					$rep->TextCol(0, 7, "        Payment Details:");
					$rep->Font();
					$rep->NewLine();
					$rep->TextCol(0, 7, "        Please make check payable to:");
					$rep->NewLine();
					$rep->Font("bold");
					$rep->TextCol(0, 7, "                 MERIDIAN INTERNATIONAL COLLEGE");
					$rep->Font();
					$rep->NewLine();
					$rep->TextCol(0, 7, "        For bank deposit, please fax bank receipt copy with student's name to 403-8676");
					$rep->NewLine();
					$rep->TextCol(0, 7, "                 Account Name:Meridian International College of Business and Arts Inc.");
					$rep->NewLine();
					$rep->TextCol(0, 7, "                 BDO Mckinley Branch                             Current Acct. No. 6968-0009-64");
					$rep->NewLine();
					$rep->TextCol(0, 7, "                 BPI Bonifacio Global City Branch            Current Acct. No. 1921-1156-32");
					$rep->NewLine();	
					$rep->TextCol(0, 8, "        ________________________________________________________________________________________________________________________________");
					
					
					$rep->NewLine(3);
					$rep->TextCol(0, 7, "         Validated and Signed by : ______________________________");
					$rep->TextCol(5, 7, "Date : ".date('F d, Y'));
					$rep->NewLine();
					$rep->TextCol(1, 7, "                                       REGISTRAR / ACCOUNTANT");
				}
				
				else if($_REQUEST['met']=='report')
					{
						$rep->title = 'STUDENT INFO';
						$rep->Header();
						
						$field = explode(',',$_REQUEST['id']);
						$condition = explode(',',$_REQUEST['id2']);
						$value = explode(',',$_REQUEST['id3']);
						
						$arr_sql = array();
						$ctr = 0;
						if(count($field) > 0)
							{ 
							
								foreach($field as $fieldname)
								{
									if($fieldname=='age')
									{
										$val = date('Y')-$value[$ctr];
										 $arr_sql[] = "birth_date BETWEEN '" . addslashes($val) . "-01-01' AND '" . addslashes($val) . "-12-31'";
									}
									else
									{
						
										if($condition[$ctr] == 'EQ')
										{
											 $arr_sql[] = $fieldname . " = '" . addslashes($value[$ctr]) . "'";
										}
										else if($condition[$ctr] == 'EX')
										{
											$arr_sql[] = $fieldname . " <> '" . addslashes($value[$ctr]) . "'";
										}
										else if($condition[$ctr] == 'LKA')
										{
											$arr_sql[] = $fieldname . " like '%" . addslashes($value[$ctr]) . "%'";
										}
										else if($condition[$ctr] == 'LKF')
										{
											$arr_sql[] = $fieldname . " like '%" . addslashes($value[$ctr]) . "'";
										}
										else if($condition[$ctr] == 'GT')
										{
											$arr_sql[] = $fieldname . " > '" . addslashes($value[$ctr]) . "'";
										}
										else if($condition[$ctr] == 'LT')
										{
											$arr_sql[] = $fieldname . " < '" . addslashes($value[$ctr]) . "'";
										}
										
									}
									$ctr++;
								}		
							}
							
							if(count($arr_sql) > 0)
								{
									$sqlcondition = count($arr_sql) == 1 ? ' WHERE ' . $arr_sql[0] : ' WHERE ' . implode(' AND ', $arr_sql);
								}
								
							if($_REQUEST['ord'] != '' )
							{
								$ords = explode(',',$_REQUEST['ord']);
								$sqlOrderBy = ' ORDER BY  '. $ords[0] .' '. $ords[1];
							}
						
						$sql = "SELECT * FROM tbl_student
							" .$sqlcondition . $sqlOrderBy;
											
						$result = mysql_query($sql);
						//$ctr = mysql_num_rows($result);
						$ctr = 0;
						
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line(740);
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line(710);
						$rep->NewLine();
						$rep->NewLine();
						
						$rep->TextCol(0, 3, "First Name");
						$rep->TextCol(0, 3, "                                   Middlename");
						$rep->TextCol(0, 3, "                                                                 Lastname");
						//$rep->TextCol(2.5, 4, "                Year Level");
						$rep->TextCol(3, 7, "Course");
						$rep->NewLine();
						$rep->NewLine();
						
						if(isset($_REQUEST['trm']))
						{
							$term=$_REQUEST['trm'];
						}else{
							$term=CURRENT_TERM_ID;
						}
						
						while($row = mysql_fetch_array($result)) 
						{
							$sqle = 'SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status="E" AND student_id='.$row['id'].' AND term_id='.$term;
							$querye = mysql_query($sqle);
							
							if(mysql_num_rows($querye)>0)
							{
							$rep->TextCol(0, 3, $row["firstname"]);
							$rep->TextCol(0, 3, '                                   '.$row["middlename"]);
							$rep->TextCol(0, 3, '                                                                 '.$row["lastname"]);
							//$rep->TextCol(2.5, 4, "                ".getyearlevel($row["year_level"]));
							$rep->TextCol(3, 7, getCourseName($row["course_id"]));
							$rep->NewLine();
							$ctr++;
							}
						}
						$rep->NewLine();
						$rep->SetTextColor(205, 205, 205);
							$rep->TextCol(0, 7, "__________________________________________________________________________________________________________________________________________________________________________");
							
							$rep->NewLine();
							$rep->SetTextColor(0, 0, 0);
						$rep->Font("bold");
						$rep->TextCol(0, 7, "Total Record: ".$ctr);
						$rep->Font("");
					}
					
					else if($_REQUEST['met']=='report-term')
					{
						$rep->title = 'STUDENT INFO';
						$rep->Header();
						
						$field = explode(',',$_REQUEST['id']);
						$condition = explode(',',$_REQUEST['id2']);
						$value = explode(',',$_REQUEST['id3']);
						
						$arr_sql = array();
						$ctr = 0;
						if(count($field) > 0)
							{ 
							
								foreach($field as $fieldname)
								{
									if($fieldname=='age')
									{
										$val = date('Y')-$value[$ctr];
										 $arr_sql[] = "birth_date BETWEEN '" . addslashes($val) . "-01-01' AND '" . addslashes($val) . "-12-31'";
									}
									else
									{
						
										if($condition[$ctr] == 'EQ')
										{
											 $arr_sql[] = $fieldname . " = '" . addslashes($value[$ctr]) . "'";
										}
										else if($condition[$ctr] == 'EX')
										{
											$arr_sql[] = $fieldname . " <> '" . addslashes($value[$ctr]) . "'";
										}
										else if($condition[$ctr] == 'LKA')
										{
											$arr_sql[] = $fieldname . " like '%" . addslashes($value[$ctr]) . "%'";
										}
										else if($condition[$ctr] == 'LKF')
										{
											$arr_sql[] = $fieldname . " like '%" . addslashes($value[$ctr]) . "'";
										}
										else if($condition[$ctr] == 'GT')
										{
											$arr_sql[] = $fieldname . " > '" . addslashes($value[$ctr]) . "'";
										}
										else if($condition[$ctr] == 'LT')
										{
											$arr_sql[] = $fieldname . " < '" . addslashes($value[$ctr]) . "'";
										}
										
									}
									$ctr++;
								}		
							}
							
							if(count($arr_sql) > 0)
								{
									$sqlcondition = count($arr_sql) == 1 ? ' WHERE ' . $arr_sql[0] : ' WHERE ' . implode(' AND ', $arr_sql);
								}
								
							if($_REQUEST['ord'] != '' )
							{
								$ords = explode(',',$_REQUEST['ord']);
								$sqlOrderBy = ' ORDER BY  '. $ords[0] .' '. $ords[1];
							}
						
						
						
						$sql = "SELECT * FROM tbl_student
							" .$sqlcondition . $sqlOrderBy;
											
						$result = mysql_query($sql);
						//$ctr = mysql_num_rows($result);
						$ctr = 0;
						
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line(740);
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line(710);
						$rep->NewLine();
						$rep->NewLine();
						
						$rep->TextCol(0, 3, "First Name");
						$rep->TextCol(0, 3, "                                   Middlename");
						$rep->TextCol(0, 3, "                                                                 Lastname");
						//$rep->TextCol(2.5, 4, "                Year Level");
						$rep->TextCol(3, 7, "Course");
						$rep->NewLine();
						$rep->NewLine();
						
						$sql1 = "SELECT * FROM tbl_school_year_period";
						$query1 = mysql_query($sql1);
						
						if(mysql_num_rows($query1)>0)
						{
						while($row1=mysql_fetch_array($query1))
						{
							$rep->Font('bold');
						$rep->TextCol(0, 3, getSYandTerm($row1['term_id']));
						$rep->Font('');
						$rep->NewLine();
						//while($row = mysql_fetch_array($result)) 
						//{
							$sqle = 'SELECT * 
FROM tbl_student_enrollment_status st, tbl_student stu
WHERE st.enrollment_status =  "E" AND stu.id = st.student_id AND stu.course_id=8 
AND st.term_id ='.$row1['term_id'];
							$querye = mysql_query($sqle);
							
							if(mysql_num_rows($querye)>0)
							{
								while($rowe=mysql_fetch_array($querye))
								{
									
							$rep->TextCol(0, 3, $rowe["firstname"]);
							$rep->TextCol(0, 3, '                                   '.$rowe["middlename"]);
							$rep->TextCol(0, 3, '                                                                 '.$rowe["lastname"]);
							//$rep->TextCol(2.5, 4, "                ".getyearlevel($row["year_level"]));
							$rep->TextCol(3, 7, getCourseName($rowe["course_id"]));
							$rep->NewLine();
							$ctr++;
								}
							}
						}
						}
						//}
						$rep->NewLine();
						$rep->SetTextColor(205, 205, 205);
							$rep->TextCol(0, 7, "__________________________________________________________________________________________________________________________________________________________________________");
							
							$rep->NewLine();
							$rep->SetTextColor(0, 0, 0);
						$rep->Font("bold");
						$rep->TextCol(0, 7, "Total Record: ".$ctr);
						$rep->Font("");
					}
				
				else if($_REQUEST['met']=='studreport')
				{
					
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];						
				  	$querys = mysql_query($sqls);
				  	$rows = mysql_fetch_array($querys);
					
					$rep->NewLine(5);
					
					if($_REQUEST['rep']=='H')
					{
						$rep->fontSize += 4;
						$rep->Font("bold");
						$rep->TextCol(1, 7, "                          CERTIFICATE OF HONORABLE DISMISSAL");
						$rep->Font();
						$rep->fontSize -= 4;
						
						$rep->NewLine(4);
						$rep->fontSize += 2;
						$rep->Font("bold");
						$rep->TextCol(1, 7, "To Whom It May Concern :");
						$rep->Font();
						
						$rep->NewLine(3);
						$rep->TextColLines(1, 7, "         This is to certify that ".$rows['firstname']." ".$rows['middlename']." ".$rows['lastname'].", Student No. ".$rows['student_number'].", was enrolled as 1st Year, ".getCourseName($rows['course_id'])." at Meridian International College of Arts and Business Inc. during the ".getSchoolTerm($rows['term_id'])." of the SY ".getSchoolYearStartEndByTerm($rows['term_id']).", and was honorably dismissed as of this date.  The official transcript of record will be forwarded upon receipt of accomplished Transfer Credential Certificate.
");
						$rep->NewLine();
						$rep->TextCol(1, 7, "         Issued this ".date('d S F Y')." .");
						
						$rep->NewLine(6);
						$rep->TextCol(5, 7, "ARIES B. BUROG");
						$rep->NewLine();
						$rep->TextCol(5, 7, "MIS Manager/Registrar");
						$rep->NewLine();
						$rep->TextCol(5, 7, "Mint College");

						$rep->fontSize -= 2;
						
					}
					else if($_REQUEST['rep']=='C')
					{
						$rep->fontSize += 4;
						$rep->Font("bold");
						$rep->TextCol(2, 7, "C E R T I F I C A T I O N");
						$rep->Font();
						$rep->fontSize -= 4;
						
						$rep->NewLine(4);
						$rep->fontSize += 2;
						$rep->Font("bold");
						$rep->TextCol(1, 7, "To Whom It May Concern :");
						$rep->Font();
						
						$rep->NewLine(3);
						$rep->TextColLines(1, 7, "This hereby certifies that ".$rows['firstname']." ".$rows['middlename']." ".$rows['lastname']." with student no. ".$rows['student_number']." has been accepted as a freshman student at the MERIDIAN INTERNATIONAL COLLEGE OF BUSINESS AND ARTS INC. (MINT COLLEGE) since the ".getSchoolTerm($rows['term_id'])." of the SY ".getSchoolYearStartEndByTerm($rows['term_id']).".");
						$rep->NewLine();
						$rep->TextColLines(1, 7, "She is currently enrolled under the ".getCourseName($rows['course_id'])."");
						$rep->NewLine();
						$rep->TextCol(1, 7, "This certification is issued on the ".date('d S F Y')." for all legal intents and purposes. ");
						
						$rep->NewLine(6);
						$rep->TextCol(1, 7, "ARIES B. BUROG");
						$rep->NewLine();
						$rep->TextCol(1, 7, "MIS Manager/Registrar");

						$rep->fontSize -= 2;
						
					}
					
					else if($_REQUEST['rep']=='I')
					{
						$rep->Blank_Header();
						
						if($rows['term_id']<14)
						{
						
						$rep->NewLine(2);
						$rep->fontSize += 4;
						
						$rep->TextCol(1, 7, date('d F Y'));
						$rep->NewLine();
						
						$rep->NewLine(2);
						
						$suf = $rows['gender']=='F'?'Ms. ':'Mr. ';
						
						$rep->Font("bold");
						$rep->TextCol(1, 7, $suf.strtoupper($rows['firstname']." ".$rows['lastname']));
						$rep->Font();
						
						$cadd = strlen($rows['home_address'])+strlen(getCourseName($rows['course_id']));
						
						$rep->NewLine(1.5);
						$rep->TextColLines(1, 5, $rows['home_address']);
						$rep->NewLine(0.5);
						$rep->TextColLines(1, 4, getCourseName($rows['course_id']));
						
						$rep->NewLine();
						$rep->TextCol(1, 7, "Re: iPad for Students Program");
						$rep->NewLine();
						$rep->TextCol(1, 7, "____________________________________________________________________________________________________________________________________________________________________________________");
						
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "Dear ".$suf.strtoupper($rows['lastname']));
						
						$rep->NewLine(2);
						$rep->TextColLinesJust(1, 7, "We are very pleased to welcome you to MERIDIAN INTERNATIONAL BUSINESS, ARTS AND TECHNOLOGY COLLEGE (MINT College), where ");
						$rep->TextColLinesJust(1, 7, "creativity, ingenuity and responsibility are interfaced to develop future leaders, innovators and thinkers for a better society, economy and environment.                          ");
						 
						//$rep->NewLine();
						$rep->TextColLinesJust(1, 7, "We at MINT College strongly believe that students should be able to enjoy excellent facilities, equipment and tools in school to be able to maximize learning.  Thus, we will assign to you an iPad with the following specifications, which you will use during your stint with the school:                            ");
						$rep->NewLine();
						$rep->TextCol(1, 7, "Apple iPad, WiFi, 16 GB");
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "Serial Number: ___________________");
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "MAC Address: ___________________");
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "Color: __________________________");
						
						$rep->NewLine(3);
						$rep->TextColLinesJust(1, 7, "The assignment of the iPad to you shall be subject to your compliance of the following conditions:                                                              ");
						$rep->NewLine(2);
						$rep->Font("B");
						$rep->TextCol(1, 7, "1.	iPad for Students.");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                            As a bonafide freshman of a DEGREE Program, the iPad will be provided to you, at no cost, as part of your learning tools, provided, however, that you remain current and updated in the payment of your tuition fee and other miscellaneous fees for the duration of your stay at MINT; and subject to the other terms and conditions set forth in this document.         ");
						
						$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "2.	Lost, Stolen, or Destroyed iPad.");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                                          If, at any time within the duration of your program, your iPad gets lost, stolen or destroyed beyond repair, MINT College will replace the unit subject to the payment of any and all expenses for its replacement, such as but not limited to: processing and replacement cost in the amount of Five Thousand Pesos (Php 5,000.00).                         ");
						$rep->NewLine();
						$rep->TextColLinesJust(1, 7, "However, you, as the owner of the unit, have to prove the circumstances that led to the loss or destruction of the iPad by submitting competent evidence, such as ");
						//$rep->NewLine();
						$rep->TextColLinesJust(1, 7, "but not limited to sworn statements, police reports which will be subject to evaluation.The veracity of the proofs will be determined by MINT College.");
						$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "3.	Repairs outside of warranty.");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                                             Costs to repair your iPad that are not covered by warranty shall be charged to your account and are required to be paid in full before the repaired iPad is released.                          ");
						$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "4.	Separation from MINT.");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                                   If, at any time, you drop out or discontinue your studies at MINT College for whatever reason, you shall be required to purchase the iPad at the applicable net value computed below:                                 ");
						
						//$rep->NewLine();//$rep->UnderlineCell(7, 0, 1, 1, 'B');
						
						$rep->fontSize -= 4;
						
						$rep->TextCol(1, 6, "                _______________________________________________________________________");
						$rep->NewLine(2);
						$rep->TextCol(1, 2, "                No. of semesters completed");
						$rep->TextCol(2, 2, "   Deductible Value");
						$rep->TextCol(4, 6, "Net Payable for Clearance");
						$rep->NewLine();
						$rep->TextCol(1, 6, "                _______________________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(1, 2, "                              1");
						$rep->TextCol(2,4, "         2,500.00");
						$rep->TextCol(4, 6, "        21,490.00");
						$rep->NewLine();
						$rep->TextCol(1, 6, "                _______________________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(1, 2, "                              2");
						$rep->TextCol(2,4, "         2,500.00");
						$rep->TextCol(4, 6, "        18,990.00");
						$rep->NewLine();
						$rep->TextCol(1, 6, "                _______________________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(1, 2, "                              3");
						$rep->TextCol(2,4, "         2,500.00");
						$rep->TextCol(4, 6, "        16,490.00");
						$rep->NewLine();
						$rep->TextCol(1, 6, "                _______________________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(1, 2, "                              4");
						$rep->TextCol(2,4, "         2,500.00");
						$rep->TextCol(4, 6, "        13,990.00");
						$rep->NewLine();
						$rep->TextCol(1, 6, "                _______________________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(1, 2, "                              5");
						$rep->TextCol(2,4, "         2,500.00");
						$rep->TextCol(4, 6, "        11,490.00");
						$rep->NewLine();
						$rep->TextCol(1, 6, "                _______________________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(1, 2, "                              6");
						$rep->TextCol(2,4, "         2,500.00");
						$rep->TextCol(4, 6, "         8,990.00");
						$rep->NewLine();
						$rep->TextCol(1, 6, "                _______________________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(1, 2, "                              7");
						$rep->TextCol(2,4, "         4,500.00");
						$rep->TextCol(4, 6, "         4,490.00");
						$rep->NewLine();
						$rep->TextCol(1, 6, "                _______________________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(1, 2, "                              8");
						$rep->TextCol(2,4, "         4,500.00");
						$rep->TextCol(4, 6, "             0");
						$rep->NewLine();
						$rep->TextCol(1, 6, "                _______________________________________________________________________");
						$rep->NewLine();
						$rep->NewLine(2);
						
						$rep->fontSize += 4;
						
						$rep->Font("B");
						$rep->TextCol(1, 7, "5.	Return of iPad.");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                             Considering that you have already taken possession of and used the iPad (regardless of duration), in no case will you be allowed to return it ");
						$rep->TextCol(1, 7, "to MINT College.                            ");
						$rep->NewLine(2);
						$rep->Font("B");
						$rep->TextCol(1, 7, "6.	Ownership.");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                     Ownership of the iPad is automatically transferred to you upon successful completion of your program and the fulfillment of all the requirements for graduation.                                ");
						$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "7.	Mischievous Acts.");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                         Any mischievous or dishonest act of feigning or making it appear that the iPad was lost, stolen or destroyed, when in truth and in fact, it was not, and you only did it for the purpose of personal gain and to secure a replacement iPad defrauding MINT College, shall be sufficient ground for your immediate expulsion from the school.                       ");
						$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "8.	Clearance from MINT College.");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                                          The terms and conditions governing the iPad for students program shall form part of the clearance process of every student. Any student that has outstanding payables or concerns regarding the ");
						$rep->TextCol(1, 7, "iPad issued to him shall not be given the proper clearance.");
						$rep->NewLine(2);
						$rep->Font("B");
						$rep->TextCol(1, 7, "9.	Conformity to the Methodology of MINT College.");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                                                            By signing this Letter - Agreement, you expressly agree to the prescribed methodology and requirement ");
						$rep->TextColLinesJust(1, 7, "of MINT College for the students to use an iPad, which the school believes to be one to the effective tools in imparting to the students the subject topics of the curriculum.");
						$rep->NewLine(3);
						$rep->Font("B");
						$rep->TextCol(1, 7, "10.	No Liability to MINT College.");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                                         You agree to be fully and solely responsible for the use of the iPad and, hence, you hereby hold MINT College, as well as its stockholders, directors, officers, administrators and employees free and ");
						$rep->TextColLinesJust(1, 7, "harmless from any and all claims, liability and suits, of whatever nature, that may be filed by any person or entity, arising from your use of the iPad.              ");
						$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "11.	Confidentiality.");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                         You agree to keep in strictest confidence the learning materials provided to you through the iPad and other technology tools given ");
						$rep->TextCol(1, 7, "by MINT College.");
						
						$rep->NewLine(3);
						$rep->TextColLines(1, 7, "Please affix your signature below to signify your conformity to this agreement.");
						
						$rep->NewLine();
						$rep->TextColLines(1, 7, "Thank you.");
						
						$rep->NewLine(2);
						$rep->TextColLines(1, 7, "Very truly yours,");
						
						/*if($cadd>150)
						{	
							$a = 365;
						}
						else
						{*/
							$a = 280;
						//}
						
						//$rep->Image2('../images/sir_mike_signature.png', 45, $a,185,65);
						
						$rep->NewLine(4);
						$rep->TextColLines(1, 7, "BALTAZAR N. ENDRIGA");
						//$rep->NewLine();
						$rep->TextColLines(1, 7, "President");
						//$rep->NewLine();
						$rep->TextColLines(1, 7, "MINT College");
						
						$rep->NewLine(2);
						$rep->TextColLines(1, 7, "With my conformity:");
						
						$rep->NewLine();
						$rep->TextCol(1, 7, "______________________________");
						$rep->TextCol(4, 7, "___________");
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "(Printed Name& Signature of Student)");
						$rep->TextCol(4, 7, "(Date)");
						
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "If Student is a minor (below 18 years of age):");
						
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "______________________________");
						$rep->TextCol(4, 7, "___________");
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "(Printed Name & Signature of Father or Mother)");
						$rep->TextCol(4, 7, "(Date)");
						
						$rep->NewLine(3);
						$rep->TextColLinesJust(1, 7, "SUBSCRIBED AND SWORN to before me this _____ day of ____________ ".date('Y')." at ______________, affiant _____________________________________________ exhibiting to me his _______________________ issued on ________________ at __________________.                                ");
						
						$rep->NewLine(2);
						$rep->TextCol(1, 3, "Doc. No. _____;");
						$rep->TextCol(5, 7, "NOTARY PUBLIC");
						$rep->NewLine();
						$rep->TextCol(1, 7, "Page No. _____;");
						$rep->NewLine();
						$rep->TextCol(1, 7, "Book No. _____;");
						$rep->NewLine(1.5);
						$rep->TextCol(1, 7, "Series of ".date('Y').".");

						$rep->fontSize -= 2;
						
						}
						else
						{
							
							$rep->NewLine(2);
						$rep->fontSize += 4;
						
						$rep->TextCol(1, 7, "03 JULY 2014");//date('d F Y'));
						$rep->NewLine();
						
						$rep->NewLine(2);
						
						$suf = $rows['gender']=='F'?'Ms. ':'Mr. ';
						
						$rep->Font("bold");
						$rep->TextCol(1, 7, $suf.strtoupper($rows['firstname']." ".$rows['lastname']));
						$rep->Font();
						
						$cadd = strlen($rows['home_address'])+strlen(getCourseName($rows['course_id']));
						
						$rep->NewLine(1.5);
						$rep->TextColLines(1, 5, $rows['home_address']);
						$rep->NewLine(0.5);
						$rep->TextColLines(1, 4, getCourseName($rows['course_id']));
						
						$rep->NewLine();
						$rep->TextCol(1, 7, "Re: iPad for Students Program");
						$rep->NewLine();
						$rep->TextCol(1, 7, "____________________________________________________________________________________________________________________________________________________________________________________");
						
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "Dear ".$suf.strtoupper($rows['lastname']));
						
						$rep->NewLine(2);
						$rep->TextColLinesJust(1, 7, "We are very pleased to welcome you to MERIDIAN INTERNATIONAL BUSINESS, ARTS AND TECHNOLOGY COLLEGE (MINT College), where ");
						$rep->TextColLinesJust(1, 7, "creativity, ingenuity and responsibility are interfaced to develop future leaders, innovators and thinkers for a better society, economy and environment.                          ");
						 
						//$rep->NewLine();
						$rep->TextColLinesJust(1, 7, "We at MINT College strongly believe that students should be able to enjoy excellent facilities, equipment and tools in school to be able to maximize learning.  Thus, we will assign to you an iPad with the following specifications, which you will use while attending MINT College:                            ");
						$rep->NewLine();
						$rep->TextCol(1, 7, "Apple iPad, WiFi, 16 GB");
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "Serial Number: ___________________");
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "MAC Address: ___________________");
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "Color: __________________________");
						
						$rep->NewLine(2);
						$rep->TextColLinesJust(1, 7, "The assignment of the iPad to you shall be subject to your compliance of the following conditions:                                                              ");
						$rep->NewLine(2);
						$rep->Font("B");
						$rep->TextCol(1, 7, "1.	iPad for Students ");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                          - As a bonafide freshman or transfer student of a qualified DEGREE Program, the iPad will be provided to you, at no extra cost, as part of your learning tools, provided, however, that you remain current and updated in the payment of your tuition fee and other miscellaneous fees for the duration of your stay at MINT; and subject to the other terms and conditions set forth in this document.                           ");
						
						//$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "2.	Transfer of Ownership ");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                                 - Upon signing of this contract, the student agrees to take ownership of the iPad and all the responsibilities stipulated in this agreement.         ");
						
						//$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "3.	Lost, Stolen, or Destroyed iPad ");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                                  - If, at any time within the duration of your program, your iPad gets lost, stolen or destroyed beyond repair, MINT College will replace the unit subject to the payment of any and all expenses for its replacement, such as but not limited to: processing and replacement cost in the amount of Twenty-Two Thousand Pesos (Php 22,000.00). MINT College will not be responsible for any lost software or data contained in the lost, stolen or damaged iPad. Students should make every effort to back up their devices on a regular basis to an external hard drive or computer.                         ");
						$rep->NewLine();
						$rep->TextColLinesJust(1, 7, "However, you, as the owner of the unit, have to prove the circumstances that led to the loss or destruction of the iPad by submitting competent evidence, such as ");
						//$rep->NewLine();
						$rep->TextColLinesJust(1, 7, "but not limited to sworn statements, police reports which will be subject to evaluation, the veracity of which will be determined by MINT College.       ");
						$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "4.	Warranty ");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "               - The iPad unit carries a 1-year warranty from our Apple equipment provider, Sw!tch. The warranty covers the cost to repair or replace the unit due to any hardware or software problems or defects not caused by the student. Any costs to repair the iPad that are not covered by the warranty shall be charged to student's account and are required to be paid in full before the repaired iPad is released.                  ");
						//$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "5.	Separation from MINT ");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                                - If, at any time, you discontinue your enrollment at MINT College, you shall be required to purchase the iPad based on the following conditions:                ");
						
						//$rep->NewLine();//$rep->UnderlineCell(7, 0, 1, 1, 'B');
						$rep->NewLine();
						$rep->TextCol(1, 7, "* 	Departure within one (1) year from initial enrollment: P22,000.00 owed to MINT");
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "* 	Departure after one (1) full year from initial enrollment: P12,000.00 owed to MINT");
						$rep->NewLine(2);
						$rep->TextColLines(1, 7, "* 	Departure after two (2) full years from initial enrollment: Full clearance (nothing owed after this point)");
						$rep->NewLine();
						
						$rep->Font("B");
						$rep->TextCol(1, 7, "6.	Returns ");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                 - Considering that the student has already taken possession and made use of the iPad (regardless of duration), in no case will student be allowed to return the device to MINT College.                    ");
						$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "7.	Mischievous Acts ");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                      - Any deceiving or dishonest act of feigning or making it appear that the iPad was damaged, lost, stolen or destroyed, when in truth and in fact, it was not, and was only made to appear so for the purpose of personal gain and to secure a replacement iPad, thus defrauding MINT College, shall be sufficient grounds for immediate expulsion from the school.                       ");
						$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "8.	Clearance from MINT College ");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                                            - The terms and conditions governing the iPad for students program shall form part of the clearance process for every departing student of MINT College.                 ");
						$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "9.	No Liability to MINT College ");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                                     - You agree to be fully and solely responsible for the use of the iPad and, hence, you hereby hold MINT College, as well as its stockholders, directors, officers, administrators and employees free and ");
						$rep->TextColLinesJust(1, 7, "harmless from any and all claims, liability and suits, of whatever nature, that may be filed by any person or entity, arising from your use of the iPad.              ");
						$rep->NewLine();
						$rep->Font("B");
						$rep->TextCol(1, 7, "10.	Confidentiality ");
						$rep->Font("");
						$rep->TextColLinesJust(1, 7, "                    - You agree to keep in strictest confidence any and all customized learning materials provided to you for use on the iPad and other technology tools given exclusively by MINT College.       ");
						
						$rep->NewLine(9);
						$rep->TextColLines(1, 7, "Please affix your signature below to signify your conformity to this agreement.");
						
						$rep->NewLine();
						$rep->TextColLines(1, 7, "Thank you.");
						
						$rep->NewLine(2);
						$rep->TextColLines(1, 7, "Very truly yours,");
						
						/*if($cadd>150)
						{	
							$a = 365;
						}
						else
						{*/
							$a = 280;
						//}
						
						//$rep->Image2('../images/sir_mike_signature.png', 45, $a,185,65);
						
						$rep->NewLine(4);
						$rep->Font("B");
						$rep->TextColLines(1, 7, "BALTAZAR N. ENDRIGA");
						$rep->Font("");
						//$rep->NewLine();
						$rep->TextColLines(1, 7, "President");
						//$rep->NewLine();
						$rep->TextColLines(1, 7, "MINT College");
						
						$rep->NewLine(2);
						$rep->TextColLines(1, 7, "With my conformity:");
						
						$rep->NewLine();
						$rep->TextCol(1, 7, "______________________________");
						$rep->TextCol(4, 7, "___________");
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "(Printed Name& Signature of Student)");
						$rep->TextCol(4, 7, "(Date)");
						
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "If Student is a minor (below 18 years of age):");
						
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "______________________________");
						$rep->TextCol(4, 7, "___________");
						$rep->NewLine(2);
						$rep->TextCol(1, 7, "(Printed Name & Signature of Father or Mother)");
						$rep->TextCol(4, 7, "(Date)");
						
						$rep->NewLine(3);
						$rep->TextColLinesJust(1, 7, "SUBSCRIBED AND SWORN to before me this _____ day of ____________ ".date('Y')." at ______________, affiant _____________________________________________ exhibiting to me his _______________________ issued on ________________ at __________________.                                ");
						
						$rep->NewLine(2);
						$rep->TextCol(1, 3, "Doc. No. _____;");
						$rep->TextCol(5, 7, "NOTARY PUBLIC");
						$rep->NewLine();
						$rep->TextCol(1, 7, "Page No. _____;");
						$rep->NewLine();
						$rep->TextCol(1, 7, "Book No. _____;");
						$rep->NewLine(1.5);
						$rep->TextCol(1, 7, "Series of ".date('Y').".");

						$rep->fontSize -= 2;
							
						}
						
					}
				}
				
				else if($_REQUEST['met']=='facload')
				{
					$rep->No_Header();
					
					$rep->TextCol(0, 3, "MINT FACULTY LOADING");
					$rep->NewLine();
					$rep->TextCol(0, 3, 'SY '.getSchoolYearStartEndByTerm($_REQUEST['trm']) .' | '.getSchoolTerm($_REQUEST['trm']));
					
					$rep->NewLine(2);
					$rep->TextCol(0, 1, "Subject Code");
					$rep->TextCol(1, 2, "       Units");
					$rep->TextCol(1, 3, "                      Class Schedule");
					$rep->TextCol(3, 4, "Section");
					$rep->TextCol(5, 5, "Room");
					$rep->TextCol(6, 7, "No. of Student");
					
					$sql = "SELECT * FROM tbl_employee WHERE employee_type = 2 ORDER by lastname";						
					$query = mysql_query($sql);
					
					
					while($row = mysql_fetch_array($query))
					{
						$sql_sched = "SELECT * FROM tbl_schedule WHERE employee_id = ".$row['id']." AND term_id =".$_REQUEST['trm'];						
						$query_sched = mysql_query($sql_sched);
						
						if(mysql_num_rows($query_sched)>0)
					{
						$rep->Font("bold");
						$rep->NewLine(2);
						$rep->TextCol(0, 7, $row['lastname']." ".$row['firstname']." ".$row['middlename']);
						$rep->Font();
						$rep->NewLine();
						$totalU = 0;
						
						while($row_sched = mysql_fetch_array($query_sched))
						{
							//if(!checkIfElective($row_sched['subject_id']))
							//{
						
								$rep->TextCol(0, 1, getSubjCode($row_sched['subject_id']));
								$rep->TextCol(1, 2, "       ".getSubjUnit($row_sched['subject_id']));
								$rep->TextCol(1, 3, "                      ".getScheduleDays($row_sched["id"]));
								$rep->TextCol(3, 4, $row_sched['section_no']);
								$rep->TextCol(5, 6, getSubjRoom($row_sched['id']));
								$rep->TextCol(6, 7, "         ".getNumberScheduleEnrolled($row_sched["id"],$_REQUEST['trm']));
								$rep->NewLine();
								
								$totalU += getSubjUnit($row_sched['subject_id']);
							//}
						}
						
						$rep->TextCol(0, 1, "Total Units : ".$totalU);
						$rep->NewLine();
						
						$rep->TextCol(0, 7, "__________________________________________________________________________________________________________________________________________");
					}
					
					}
				}
				else if($_REQUEST['met']=='submits')
				{
				
					$rep->Blank_Header();
					
					$rep->TextCol(0, 3, "GRADE SUBMISSION");
					$rep->NewLine();
					$rep->TextCol(0, 3, 'SY '.getSchoolYearStartEndByTerm($_REQUEST['trm']) .' | '.getSchoolTerm($_REQUEST['trm']));
					
					$rep->NewLine(2);
					
					$sql_period = "SELECT * FROM tbl_school_year_period WHERE term_id=".$_REQUEST['trm']." ORDER BY period_order"; 
			
					$query_period = mysql_query($sql_period);
					
					while($row_period=mysql_fetch_array($query_period))
					{
					$rep->NewLine(2);
					$rep->Font('bold');
					$rep->TextCol(0, 2, strtoupper($row_period['period_name']));
					$rep->NewLine(2);
					$rep->Font('');
					
					$rep->TextCol(0, 2, "NAME");
					$rep->TextCol(2, 3, "DATE");
					$x=1;
					
					$sql = "SELECT * FROM tbl_employee WHERE employee_type = 2 ORDER BY lastname";						
					$query = mysql_query($sql);
					
					
					while($row = mysql_fetch_array($query))
					{
						$sql_sched = "SELECT * FROM tbl_professor_gradesheet WHERE professor_id = ".$row['id']." AND period_id=".$row_period['id'];						
						$query_sched = mysql_query($sql_sched);
						$rows = mysql_fetch_array($query_sched);
						
						if(mysql_num_rows($query_sched)>0)
					{
						
						$rep->NewLine(2);
						$rep->TextCol(0, 2, $x.'. '.getProfessorFullName($row['id']));
						$rep->TextCol(2, 3, date('F d,Y',$rows['date_created']));
						$rep->NewLine();
						
						
						$rep->TextCol(0, 7, "__________________________________________________________________________________________________________________________________________");
						$x++;
					}
					
					}
					}
				}
				else if($_REQUEST['met']=='account')
				{
					$rep->Blank_Header();
					
					$rep->TextCol(2, 6, "MINT STUDENT BALANCES");
					$rep->NewLine();
					$rep->TextCol(2, 6, 'SY '.getSchoolYearStartEndByTerm($_REQUEST['trm']) .' | '.getSchoolTerm($_REQUEST['trm']));
					
					$rep->NewLine(2);
					$rep->TextCol(1, 7, " __________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					$rep->TextCol(1, 2, "Student Name");
					$rep->TextCol(2, 3, "       Total Fees");
					$rep->TextCol(4, 6, "Total Payment");
					$rep->TextCol(6, 7, "Balance");
					$rep->NewLine(2);
					$rep->TextCol(1, 7, " __________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					
					$sql = "SELECT st.* FROM tbl_student st,tbl_student_enrollment_status e WHERE e.enrollment_status='E' AND st.id=e.student_id AND e.term_id=".$_REQUEST['trm']." ORDER BY st.lastname";						
					$query = mysql_query($sql);
					
					
					while($row = mysql_fetch_array($query))
					{
						$sqlp = "SELECT sum(amount) as payment

							FROM 
				
								tbl_student_payment 
				
							WHERE 
				
								term_id = ".$_REQUEST['trm']." AND  
				
								is_bounced <> 'Y' AND is_refund <> 'Y' AND 
				
								student_id =" .$row['id'];
						$queryp = mysql_query($sqlp);
						$rowp = mysql_fetch_array($queryp);
						
						$rep->TextCol(1, 2, $row['lastname']." ".$row['firstname']." ".$row['middlename']);
						
						$total = getStudentTotalDue($row['id'],$_REQUEST['trm']);
						$totfee = $total-$rowp['payment'];
						$rep->TextCol(2, 3, "       ".number_format($total, 2, ".", ","));
						$rep->TextCol(4, 6, "       ".number_format($rowp['payment'], 2, ".", ","));
						$rep->TextCol(6, 7, number_format($totfee, 2, ".", ","));
						$rep->NewLine();
						
						$totald += $total;
						$totalp += $rowp['payment'];
						$totalb += $totfee;
						
						$rep->NewLine();
						
					}
					
					$rep->TextCol(1, 7, "__________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					$rep->Font('bold');
					$rep->TextCol(1, 2, "TOTAL");
					$rep->TextCol(2, 3, "       ".number_format($totald, 2, ".", ","));
					$rep->TextCol(4, 6, "       ".number_format($totalp, 2, ".", ","));
					$rep->TextCol(6, 7, number_format($totalb, 2, ".", ","));
					$rep->Font('');
					
				}
				else if($_REQUEST['met']=='temp')
				{
				updateStudentYearLevel();
					$rep->Blank_Header();
					
					$rep->TextCol(2, 6, "MINT STUDENTS");
					$rep->NewLine();
					$rep->TextCol(2, 6, 'SY '.getSchoolYearStartEndByTerm($_REQUEST['trm']) .' | '.getSchoolTerm($_REQUEST['trm']));
					
					$rep->NewLine(2);
					$rep->TextCol(1, 7, " __________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					$rep->TextCol(1, 2, "Student Name");
					$rep->TextCol(2, 2, "  Admission");
					$rep->TextCol(3, 6, "School");
					$rep->NewLine(2);
					$rep->TextCol(1, 7, " __________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					
					$sql = "SELECT st.* FROM tbl_student st,tbl_student_enrollment_status e WHERE e.enrollment_status='E' AND st.id=e.student_id AND e.term_id=".$_REQUEST['trm']." ORDER BY st.lastname";						
					$query = mysql_query($sql);
					
					
					while($row = mysql_fetch_array($query))
					{
						if($row['year_level']==1)
						{
						$rep->TextCol(1, 2, $row['lastname']." ".$row['firstname']." ".$row['middlename']);

						$rep->TextCol(2, 2, "  ".$row['admission_type']);
						$rep->TextCol(3, 6, $row['high_school']);
						$rep->NewLine();
						
						$rep->NewLine();
						}
						
					}
					
					
				}
				else if($_REQUEST['met']=='scholar')
				{
				
				
					$rep->Blank_Header();
					
					$rep->TextCol(2, 6, "MINT STUDENT SCHOLARSHIP");
					$rep->NewLine();
					$rep->TextCol(2, 6, 'SY '.getSchoolYearStartEndByTerm($_REQUEST['trm']) .' | '.getSchoolTerm($_REQUEST['trm']));
					
					$rep->NewLine(2);
					$rep->TextCol(1, 7, " __________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					$rep->TextCol(1, 2, "Student Name");
					$rep->TextCol(2, 2, "  Course");
					$rep->TextCol(3, 3, "Total Fees");
					$rep->TextCol(4, 6, "   Year Level");
					$rep->TextCol(5, 6, "             Scholarship");
					$rep->TextCol(6, 6, "  Type");
					$rep->TextCol(6, 7, "            Total");
					$rep->NewLine(2);
					$rep->TextCol(1, 7, " __________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					
					$sql = "SELECT st.* FROM tbl_student st,tbl_student_enrollment_status e WHERE e.enrollment_status='E' AND st.id=e.student_id AND e.term_id=".$_REQUEST['trm']." ORDER BY st.lastname";						
					$query = mysql_query($sql);
					
					
					while($row = mysql_fetch_array($query))
					{
						$sqlp = "SELECT *

							FROM 
				
								tbl_student_fees 
				
							WHERE 
				
								term_id = ".$_REQUEST['trm']." AND 
				
								student_id =" .$row['id'];
						$queryp = mysql_query($sqlp);
						while($rowp = mysql_fetch_array($queryp))
						{
							$sqllec = "SELECT * FROM tbl_school_fee WHERE id=".$rowp['fee_id'];
							$querylec = mysql_query($sqllec);
							$rowlec = mysql_fetch_array($querylec);
							
							$lec += $rowlec['fee_type']=='perunitlec'?$rowp['amount']*$rowp['quantity']:0;
							
							$total+=$rowp['amount']*$rowp['quantity'];
						}
						
						$rep->TextCol(1, 2, $row['lastname']." ".$row['firstname']." ".$row['middlename']);
						
						//$total = getStudentTotalDue($row['id'],$_REQUEST['trm']);
						//$totfee = $total-$rowp['payment'];
						
						if($row['scholarship_type']=='A')
							{
								$sch = ($total)-5000;
								$sch = ($sch*$row['scholarship'])/100;
							}
							
							else
							
							{
				
								$sch = $lec;
								$sch = ($sch*$row['scholarship'])/100;
							}
						
						$t = $sch>0?$row['scholarship_type']:'';
						$rep->TextCol(2, 2, "  ".getCourseCode($row['course_id']));
						$rep->TextCol(3, 3, number_format($total, 2, ".", ","));
						$rep->TextCol(4, 6, "   ".getYearLevel(getStudentYearLevel($row['id'])).'('.$row['admission_type'].')');
						$rep->TextCol(5, 6, "             ".$row['scholarship'].'%');
						$rep->TextCol(6, 6, $t);
						$rep->TextCol(6, 7, "         ".number_format($sch, 2, ".", ","));
						$rep->NewLine();
						
						$totald += $total;
						$totalp += $rowp['payment'];
						$totalb += $totfee;
						
						$rep->NewLine();
						
						$total=0;
						$lec=0;
						
					}
					
					/*$rep->TextCol(1, 7, "__________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					$rep->Font('bold');
					$rep->TextCol(1, 2, "TOTAL");
					$rep->TextCol(2, 3, "       ".number_format($totald, 2, ".", ","));
					$rep->TextCol(4, 6, "       ".number_format($totalp, 2, ".", ","));
					$rep->TextCol(6, 7, number_format($totalb, 2, ".", ","));
					$rep->Font('');*/
					
				
					
				}
				else if($_REQUEST['met']=='summary')
				{
					$total = array();
		
					$sql = "SELECT * FROM tbl_student_schedule WHERE subject_id=".$_REQUEST['id']." AND term_id=".$_REQUEST['trm'];
					$query = mysql_query($sql);
					
					if(mysql_num_rows($query)>0)
					{
					
						while($row = mysql_fetch_array($query))
						{
						
							$sql2 = "SELECT * FROM tbl_student_enrollment_status WHERE student_id = ".$row['student_id'];
							$query2 = mysql_query($sql2);
							
							if(mysql_num_rows($query2)>0)
							{
								$row2 = mysql_fetch_array($query2);
								
								if($row2['enrollment_status']=='E')
								{
									$total[] = $row['student_id'];
								}
								
							}
							
						}
						
					}
					
					$rep->title = 'STUDENT INFO';
					$_REQUEST['met']=='assessment'?$rep->As_Header():$rep->Header();
					
						$rep->TextCol(0, 3, "Subject Name : ".getSubjName($_REQUEST['id']));
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Subject Code : ".getSubjCode($_REQUEST['id']));
						
						if(isset($_REQUEST['trm']))
						{
						$rep->NewLine();
						$rep->TextCol(0, 1, "School Year : ", -2);
						$rep->TextCol(1, 4, getSYandTerm($_REQUEST['trm']), -2);
						}
					
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(700);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(675);
					$rep->NewLine(3);
					
					$rep->TextCol(0, 2, "Student Name");
					$rep->TextCol(2, 7, "Course");
					$rep->NewLine();
					$rep->NewLine();
					
					if(count($total) > 0)
					{
						foreach($total as $tot) 
						{ 
						$rep->TextCol(0, 2, getStudentFullName($tot));
						$rep->TextCol(2, 7, getStudentCourse($tot)."     ");
						$rep->NewLine();
						}
					}
				}
				
				else if($_REQUEST['met']=='subject_summary')
				{
				
					$rep->title = 'STUDENT INFO';
					$_REQUEST['met']=='assessment'?$rep->As_Header():$rep->Header();
					
						if(isset($_REQUEST['id']))
						{
						$rep->TextCol(0, 1, "School Year : ", -2);
						$rep->TextCol(1, 4, getSYandTerm($_REQUEST['id']), -2);
						}
					
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(725);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(700);
					$rep->NewLine(3);
					
					$rep->TextCol(0, 3, "Subject Name");
					$rep->TextCol(4, 6, "Subject Code");
					$rep->TextCol(6, 7, "No. Enrolled");
					$rep->NewLine();
					$rep->NewLine();
					
					$sql = "SELECT * FROM tbl_subject WHERE publish='Y'";
					$query = mysql_query($sql);
					
					if(mysql_num_rows($query)>0)
					{
					
						while($row = mysql_fetch_array($query))
						{
						
							$rep->TextCol(0, 3, $row['subject_name']);
							$rep->TextCol(4, 6, $row['subject_code']);
							$rep->TextCol(6, 7, getNumberSubjectEnrolled($row['id'],$_REQUEST['id']));
							$rep->NewLine();
							
						}
						
					}
						
				}
				
				else if($_REQUEST['met']=='misc')
				{
				
				
					$rep = new FrontReport('Summary', 'summary', 'A4', 7, 'L');
					
					$rep->Blank_Header();
					
					$rep->TextCol(3, 8, "MISCELLENEOUS SUMMARY");
					//$rep->NewLine();
					//$rep->TextCol(2, 6, 'SY '.getSchoolYearStartEndByTerm($_REQUEST['trm']) .' | '.getSchoolTerm($_REQUEST['trm']));
					
					$rep->NewLine(2);
					$rep->TextCol(1, 7, " __________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					$rep->TextCol(1, 2, "Student Name");
					
					$sql = 'SELECT * FROM tbl_school_fee WHERE fee_type="mc" AND term_id='.$_REQUEST['trm'];
					$query = mysql_query($sql);
					$sp = '  ';
					while($row = mysql_fetch_array($query))
					{
						$rep->TextCol(2, 8, $sp.$row['fee_name']);
						$sp.='             ';
					}
					
					$rep->NewLine(2);
					$rep->TextCol(1, 7, " __________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);					
					
					while($row2 = mysql_fetch_array($query))
					{
						$sqlp = "SELECT *

							FROM 
				
								tbl_student_fees f,
								tbl_student s
				
							WHERE
											
								f.term_id = ".$_REQUEST['trm']." AND 
				
								f.fee_id =" .$row['id'];
						$queryp = mysql_query($sqlp);
						
						$sp = '  ';
						while($rowp = mysql_fetch_array($queryp))
						{
							$rep->TextCol(1, 2, $rowp['lastname']." ".$rowp['firstname']." ".$rowp['middlename']);
							$rep->TextCol(2, 8, $sp.$rowp['amount']);
							$sp.='             ';
							$rep->NewLine();
							
							$total += $rowp['amount'];
						}
						
						if($rowp['scholarship_type']=='A')
							{
								$sch = ($total)-5000;
								$sch = ($sch*$rowp['scholarship'])/100;
							}
							
							else
							
							{
				
								$sch = $lec;
								$sch = ($sch*$rowp['scholarship'])/100;
							}
						
						
						
					}
					
					/*$rep->TextCol(1, 7, "__________________________________________________________________________________________________________________________________________");
					$rep->NewLine(2);
					$rep->Font('bold');
					$rep->TextCol(1, 2, "TOTAL");
					$rep->TextCol(2, 3, "       ".number_format($totald, 2, ".", ","));
					$rep->TextCol(4, 6, "       ".number_format($totalp, 2, ".", ","));
					$rep->TextCol(6, 7, number_format($totalb, 2, ".", ","));
					$rep->Font('');*/
					
				
					
				}
				
				else if($_REQUEST['met']=='application list')
				{
					$rep->Header();
					
					$sql = "SELECT * FROM 
					tbl_student_application
					WHERE term_id = " . $_REQUEST['trm'];	
										
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					$rep->TextCol(0, 1, "Full Name");
					$rep->TextCol(1, 2, "Course");
					$rep->TextCol(2, 3, "Examination Date             ");
					$rep->TextCol(3, 4, "School Year");
					$rep->NewLine();
					$rep->NewLine();
					
					while($row = mysql_fetch_array($result)) 
					{
						$rep->TextCol(0, 2, $row["lastname"].", ".$row["firstname"]." " .$row["middlename"]);
						$rep->TextCol(1, 2, getCourseName($row["course_id"]));
						$rep->TextCol(2, 3, getExamDate($row['entrance_date']).'             ');
						$rep->TextCol(3, 4, getSchoolYearStartEnd(getSchoolYearIdByTermId($row['term_id'])).'('.getSchoolTerm($row['term_id']).')');
						$rep->NewLine();
					}
				}
				else if($_REQUEST['met']=='grade')
				{					
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];						
				  $querys = mysql_query($sqls);
				  $rows = mysql_fetch_array($querys);
					
					$rep->title = 'STUDENT INFO';
					$_REQUEST['met']=='assessment'?$rep->As_Header():$rep->Header();
					//$rep->NewLine(7);
						$rep->TextCol(0, 3, "Student Name : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Student Number : ".$rows['student_number']);
						
						$rep->NewLine();
						$rep->TextCol(0, 7, "Course : ".getCourseName($rows['course_id']));
						
						if(isset($_REQUEST['trm']))
						{
						$rep->NewLine();
						$rep->TextCol(0, 1, "School Year : ", -2);
						$rep->TextCol(1, 4, getSYandTerm($_REQUEST['trm']), -2);
						}
				
					$sql = "SELECT 
					stud_sched.schedule_id,
					sched.elective_of,
					sched.id,
					sched.subject_id,
					sched.room_id,
					sched.employee_id
				FROM 
					tbl_student_schedule stud_sched,
					tbl_schedule sched 
				WHERE 
					stud_sched.schedule_id = sched.id AND 
					stud_sched.student_id = ".$_REQUEST['id']."  AND 
					sched.term_id = ".$_REQUEST['trm'];				
					$result = mysql_query($sql);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(700);//675
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(675);//650
					$rep->NewLine();
					$rep->NewLine();
					
					$rep->TextCol(0, 1, "Code");
					$rep->TextCol(1, 2, "Subject Name");
					$rep->TextCol(2, 2, "               Units");
					
					$sql_period = "SELECT * FROM tbl_school_year_period WHERE 
                        term_id=".$_REQUEST['trm']." ORDER BY period_order";						
					$query_period = mysql_query($sql_period);
					$x = 3;
					
					while($row_period = mysql_fetch_array($query_period))
					{
						$x>=2?$rep->TextCol($x, $x+1, $row_period['period_name'].'          '):$rep->TextCol($x, $x+1, $row_period['period_name']);
						$x++;
					}
					
					$rep->TextCol($x, $x+1, "Grade");
					$rep->NewLine();
					$rep->NewLine();
					
					$y = 3;
					while($row = mysql_fetch_array($result)) 
					{
						$el = $row['elective_of']!=""?"(".getSubjName($row['elective_of']).")":'';
						
						$rep->TextCol(0, 1, getSubjCode($row["subject_id"]));
						$rep->TextCol(1, 3, getSubjName($row["subject_id"]).$el."               ");
						$rep->TextCol(2, 2, '                  '.getSubjUnit($row["subject_id"])."               ");
						
						$totalU += getSubjUnit($row["subject_id"]);
						
						$sql_period2 = "SELECT * FROM tbl_school_year_period WHERE 
                        term_id=".$_REQUEST['trm']." ORDER BY period_order";						
						$query_period2 = mysql_query($sql_period2);
						while($row_period = mysql_fetch_array($query_period2))
						{
							$grd = getStudentGradePerPeriod($row["id"],$row_period['id'], $_REQUEST['id']);
							$rep->TextCol($y, $y+1, $grd!=''?$grd:'0.00');
							
							$y++;
						}
						
						$f_grade = getStudentFinalGrade($_REQUEST['id'],$row['schedule_id'],$_REQUEST['trm']);
						
						if(checkIfSubjectDroppedByTerm($_REQUEST['id'],$row['id'],$_REQUEST['trm']))
						{
							$dr = getSubjectDropped($_REQUEST['id'],$row['id'],$_REQUEST['trm'])=='D'?'WITHDRAW':'FAILED';
							$rep->TextCol($y, $y+1, $dr);
							$rep->NewLine();//
							$y = 3;
						}else if(checkIfSubjectINCByTerm($_REQUEST['id'],$row['id'],$_REQUEST['trm'])){
							$rep->TextCol($y, $y+1, 'INC');
							$rep->NewLine();
							$y = 3;
						}else{
							
							$rep->TextCol($y, $y+1, $f_grade!=''?getGradeConversionGrade2($_REQUEST['id'],$row['schedule_id'],$_REQUEST['trm']):'0.00');
							//$rep->TextCol($y, $y+1, $f_grade!=''?$f_grade:'0.00');
							//" (".getGradeConversionGrade2($_REQUEST['id'],$row['schedule_id'],$_REQUEST['trm']).")"
							$rep->NewLine();
							$y = 3;
						}
						
						
					
					}
					
					
					$rep->TextCol(2, 3, "Total Units: ".$totalU);
					$rep->NewLine();
					
					$rep->TextCol(0, 7, "General Average: ".getGradeConversionGrade(getStudentAverage($_REQUEST['trm'],$_REQUEST['id'])));
					//$rep->TextCol(0, 7, "General Average: ".@round(getStudentAverage($_REQUEST['trm'],$_REQUEST['id']),2));//.' ('.@getAverageConversion(getStudentAverage($_REQUEST['trm'],$_REQUEST['id']),$_REQUEST['id'],$_REQUEST['trm']).' )');
					
					$rep->NewLine(2);
					
					$rep->TextCol(0, 7, "Grade Point Equivalence");
					$rep->NewLine();
					$rep->TextColLines(0, 7, "1.00 : 97 - 100 | 1.25 : 92 - 96 | 1.50 : 87 - 91 | 1.75 : 79 - 86 | 2.00 : 72 - 78 | 2.25 : 64 - 71 | 2.50 : 57 - 63 | 2.75 : 53 - 56 | 3.00 : 50 - 52 | 40 - 49 : 4.00 (prelims and midterm) 5.00(finals)");
					$rep->NewLine();
					
					$rep->NewLine(4);
					$rep->TextCol(5, 7, "Certified By:");
					
					$rep->NewLine(4);
					$rep->TextCol(5, 7, "Carla Jerremia B. Alona");
					$rep->NewLine();
					$rep->TextCol(5, 7, "Registrar");
					$rep->NewLine();
					$rep->TextCol(5, 7, "MINT College");
					
					
				}
				else if($_REQUEST['met']=='grade-list')
				{
					$sql1 = "SELECT * FROM tbl_student st, tbl_student_enrollment_status sta WHERE sta.student_id=st.id AND sta.enrollment_status='E' AND sta.term_id=".$_REQUEST['trm']." ORDER BY st.lastname";						
				  $query1 = mysql_query($sql1);
				  
				  while($row1 = mysql_fetch_array($query1))
				  {
				  					
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$row1['id'];						
				  $querys = mysql_query($sqls);
				  $rows = mysql_fetch_array($querys);
				  
				  $ave = getGradeConversionGrade(getStudentAverage($_REQUEST['trm'],$row1['student_id']));
				  
				  if($ave<=1.75)
				  {
					$totalU=0;
					$rep->title = 'STUDENT INFO';
					$_REQUEST['met']=='assessment'?$rep->As_Header():$rep->Header();
					//$rep->NewLine(7);
						$rep->TextCol(0, 3, "Student Name : ".$row1['lastname'].' , '.$row1['firstname'].' '.$row1['middlename']);
						
						//$rep->NewLine();
						//$rep->TextCol(0, 3, "Student Number : ".$rows['student_number']);
						
						$rep->NewLine();
						$rep->TextCol(0, 7, "Course : ".getCourseName($row1['course_id']));
						
						/*if(isset($_REQUEST['trm']))
						{
						$rep->NewLine();
						$rep->TextCol(0, 1, "School Year : ", -2);
						$rep->TextCol(1, 4, getSYandTerm($_REQUEST['trm']), -2);
						}*/
				
					$sql = "SELECT 
					stud_sched.schedule_id,
					sched.elective_of,
					sched.id,
					sched.subject_id,
					sched.room_id,
					sched.employee_id
				FROM 
					tbl_student_schedule stud_sched,
					tbl_schedule sched 
				WHERE 
					stud_sched.schedule_id = sched.id AND 
					stud_sched.student_id = ".$row1['student_id']."  AND 
					sched.term_id = ".$_REQUEST['trm'];				
					$result = mysql_query($sql);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(700);//675
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(675);//650
					$rep->NewLine();
					$rep->NewLine();
					
					$rep->TextCol(0, 1, "Code");
					$rep->TextCol(1, 2, "Subject Name");
					$rep->TextCol(2, 2, "               Units");
					
					$sql_period = "SELECT * FROM tbl_school_year_period WHERE 
                        term_id=".$_REQUEST['trm']." ORDER BY period_order";						
					$query_period = mysql_query($sql_period);
					$x = 3;
					
					while($row_period = mysql_fetch_array($query_period))
					{
						$x>=2?$rep->TextCol($x, $x+1, $row_period['period_name'].'          '):$rep->TextCol($x, $x+1, $row_period['period_name']);
						$x++;
					}
					
					$rep->TextCol($x, $x+1, "Grade");
					$rep->NewLine();
					$rep->NewLine();
					
					$y = 3;
					while($row = mysql_fetch_array($result)) 
					{
						$el = $row['elective_of']!=""?"(".getSubjName($row['elective_of']).")":'';
						
						$rep->TextCol(0, 1, getSubjCode($row["subject_id"]));
						$rep->TextCol(1, 3, getSubjName($row["subject_id"]).$el."               ");
						$rep->TextCol(2, 2, '                  '.getSubjUnit($row["subject_id"])."               ");
						
						$totalU += getSubjUnit($row["subject_id"]);
						
						$sql_period2 = "SELECT * FROM tbl_school_year_period WHERE 
                        term_id=".$_REQUEST['trm']." ORDER BY period_order";						
						$query_period2 = mysql_query($sql_period2);
						while($row_period = mysql_fetch_array($query_period2))
						{
							$grd = getStudentGradePerPeriod($row["id"],$row_period['id'], $row1['student_id']);
							$rep->TextCol($y, $y+1, $grd!=''?$grd:'0.00');
							
							$y++;
						}
						
						$f_grade = getStudentFinalGrade($row1['student_id'],$row['schedule_id'],$_REQUEST['trm']);
						
						if(checkIfSubjectDroppedByTerm($row1['student_id'],$row['id'],$_REQUEST['trm']))
						{
							$dr = getSubjectDropped($row1['student_id'],$row['id'],$_REQUEST['trm'])=='D'?'WITHDRAW':'FAILED';
							$rep->TextCol($y, $y+1, $dr);
							$rep->NewLine();//
							$y = 3;
						}else if(checkIfSubjectINCByTerm($row1['student_id'],$row['id'],$_REQUEST['trm'])){
							$rep->TextCol($y, $y+1, 'INC');
							$rep->NewLine();
							$y = 3;
						}else{
							
							$rep->TextCol($y, $y+1, $f_grade!=''?getGradeConversionGrade2($row1['student_id'],$row['schedule_id'],$_REQUEST['trm']):'0.00');
							//$rep->TextCol($y, $y+1, $f_grade!=''?$f_grade:'0.00');
							//" (".getGradeConversionGrade2($_REQUEST['id'],$row['schedule_id'],$_REQUEST['trm']).")"
							$rep->NewLine();
							$y = 3;
						}
						
						
					
					}
					
					
					$rep->TextCol(2, 3, "Total Units: ".$totalU);
					$rep->NewLine();
					
					
					$rep->TextCol(0, 7, "General Average: ".$ave);
					//$rep->TextCol(0, 7, "General Average: ".@round(getStudentAverage($_REQUEST['trm'],$_REQUEST['id']),2));//.' ('.@getAverageConversion(getStudentAverage($_REQUEST['trm'],$_REQUEST['id']),$_REQUEST['id'],$_REQUEST['trm']).' )');
					
					$rep->NewLine();
					
					//$rep->TextCol(0, 7, "Grade Point Equivalence");
					//$rep->NewLine();
					//$rep->TextColLines(0, 7, "1.00 : 97 - 100 | 1.25 : 92 - 96 | 1.50 : 87 - 91 | 1.75 : 79 - 86 | 2.00 : 72 - 78 | 2.25 : 64 - 71 | 2.50 : 57 - 63 | 2.75 : 53 - 56 | 3.00 : 50 - 52 | 40 - 49 : 4.00 (prelims and midterm) 5.00(finals)");
					//$rep->NewLine();
					
					//$rep->NewLine(4);
					//$rep->TextCol(5, 7, "Certified By:");
					
					//$rep->NewLine(4);
					//$rep->TextCol(5, 7, "Carla Jerremia B. Alona");
					//$rep->NewLine();
					//$rep->TextCol(5, 7, "Registrar");
					//$rep->NewLine();
					//$rep->TextCol(5, 7, "MINT College");
				  }
				  }
					
				}
				else if($_REQUEST['met']=='credit grade')
				{
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];	
					$querys = mysql_query($sqls);
				  $rows = mysql_fetch_array($querys);
					
					$rep->title = 'STUDENT INFO';
					$_REQUEST['met']=='assessment'?$rep->As_Header():$rep->Header();
					
						
				  
					$birthday = explode ('-',$rows['birth_date']);		
					$birth_year = $birthday['0'];
					$birth_day = $birthday['1'];
					$birth_month = $birthday['2'];
					
					$rep->NewLine();
					$rep->TextCol(0, 3, "Student Number : ".$rows['student_number']);
					$rep->NewLine();
					$rep->TextCol(0, 3, "Student Name : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);	
						
					$rep->NewLine();
					$rep->TextCol(0, 1, "Date of Birth:");
					$rep->TextCol(1, 2, date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year)));
					$rep->TextCol(2, 3, "Sex:");
					$rep->TextCol(3, 4, $rows['gender']);
					$rep->NewLine();
					$rep->TextCol(0, 1, "Department:");
					$rep->TextCol(1, 2, getStudentCollegeName($rows['course_id']));
					$rep->TextCol(2, 3, "Curriculum:");
					$rep->TextCol(3, 4, getCurriculumCode($rows['curriculum_id']));
					$rep->NewLine();
					$rep->TextCol(0, 1, "Address:");
					$rep->TextCol(1, 2, $rows['home_address']);
					$rep->NewLine();
					if($_REQUEST['met']=='credit grade')
					{
					$rep->TextCol(0, 1, "Last School:");
					$rep->TextCol(1, 2, getStudentLastSchool($rows['id']));
					}
										
					$sql = "SELECT * FROM tbl_student_final_grade WHERE student_id = ".$_REQUEST['id'];				
					$result = mysql_query($sql);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(615);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(585);
					$rep->NewLine();
					$rep->NewLine();
					
					$rep->TextCol(0, 1, "Code");
					$rep->TextCol(1, 2, "Subject Name");
					$rep->TextCol(2, 3, "Unit");
					$rep->TextCol(3, 4, "Grade");
					$rep->TextCol(4, 5, "Remarks");
					$rep->NewLine();
					$rep->NewLine();
					
					while($row = mysql_fetch_array($result)) 
					{
						$rep->TextCol(0, 1, getSubjCode($row["subject_id"]));
						$rep->TextCol(1, 2, getSubjName($row["subject_id"]));
						$rep->TextCol(2, 3, getSubjUnit($row['subject_id']));
						$rep->TextCol(3, 4, decrypt($row['final_grade']));
						$rep->TextCol(4, 5, $row['remarks']=='P'?'Passed':'Failed');
						$rep->NewLine();
						$units+=getSubjUnit($row['subject_id']);
					}
					$rep->TextCol(1, 2, "                                                                    Total Units ");
					$rep->TextCol(2, 3, $units);
					$rep->NewLine(3);
					$rep->NewLine(3);
					
					$rep->TextCol(3, 6, "_______________________________________");
					$rep->NewLine();
					$rep->TextCol(3, 6, "PROGRAM HEAD/ACADEMIC COORDINATOR");
				}
				else if($_REQUEST['met']=='remarks')
				{
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];	
					$querys = mysql_query($sqls);
				  $rows = mysql_fetch_array($querys);
					
					$rep->title = 'STUDENT INFO';
					$_REQUEST['met']=='assessment'?$rep->As_Header():$rep->Header();
					
						$rep->TextCol(0, 3, "Student Name : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Student Number : ".$rows['student_number']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Course : ".getCourseName($rows['course_id']));
						
						if(isset($_REQUEST['trm']))
						{
						$rep->NewLine();
						$rep->TextCol(0, 1, "School Year : ", -2);
						$rep->TextCol(1, 4, getSYandTerm($_REQUEST['trm']), -2);
						}

					
					 $sql = "SELECT * FROM tbl_student_remarks WHERE student_id=" .$_REQUEST['id'];
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					
					$x=0;
					while($row = mysql_fetch_array($result)) 
					{
						$rep->NewLine();
						$rep->Font('bold');
						$rep->fontSize += 1;
						$rep->NewLine(2);
						$rep->TextCol(0, 2, getEmployeeFullName($row['professor_id']));
						$rep->NewLine();
						$rep->TextCol(0, 2, getSubjName($row['subject_id']));
						$rep->NewLine();
						$rep->TextCol(0, 2, getSYandTerm($row['term_id']));
						$rep->Font();
						$rep->fontSize -= 1;
						$rep->NewLine(2);
						$rep->TextColLines(0,7, $row['description']);
						
						$wid = $rep->pageWidth-($rep->rightMargin+$rep->leftMargin);
						$swid = $rep->GetStringWidth($row['description']);
						$ded = round($swid/$wid);
						$nln = $rep->lineHeight*($ded+8);
						//$rep->TextCol(0, 2, $ded+8);
						//$rep->NewLine();
						$ln1 = ($ln1-$nln)*1;
						$ln2 = ($ln1-50)*1;
						$x++;
						
					}
				}
				else if($_REQUEST['met']=='financial')
				{
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];	
					 $querys = mysql_query($sqls);
				  $rows = mysql_fetch_array($querys);
					
					$rep->title = 'STUDENT INFO';
					$_REQUEST['met']=='assessment'?$rep->As_Header():$rep->Header();
					
						$rep->TextCol(0, 3, "Student Name : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Student Number : ".$rows['student_number']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Course : ".getCourseName($rows['course_id']));
						
						if(isset($_REQUEST['trm']))
						{
						$rep->NewLine();
						$rep->TextCol(0, 1, "School Year : ", -2);
						$rep->TextCol(1, 4, getSYandTerm($_REQUEST['trm']), -2);
						}

					
					$sql = "SELECT *
							FROM tbl_school_fee
							WHERE publish =  'Y'
							AND term_id= ".$_REQUEST['trm'];
					$result = mysql_query($sql); 
                    $sub_total = 0;
					$ctr = mysql_num_rows($result);
					
						$rep->NewLine(2);
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextColLines(0, 3, "Assessment of Fees");
						$rep->Font();
						$rep->fontSize -= 1;
						$rep->NewLine();
					
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line(660);
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line(640);
						$rep->NewLine();
						$rep->TextCol(0, 1, "Fee");
						$rep->TextCol(1, 2, "                                                  Amount");
						$rep->TextCol(2, 3, "                Total");
						$rep->NewLine(2);
						
						$cnt = 1;
					
					while($row = mysql_fetch_array($result)) 
					{
						$total = getStudentTotalFeeLecLab($row['id'],$_REQUEST['id'] );
						$rep->TextCol(0, 2, $row['fee_name'].' ('.number_format(getFeeUnit($row['id'],$_REQUEST['id']), 2, ".", ",").')');
						$rep->TextColNum(1, 2, '                                               '.number_format(getFeeAmount($row['id']), 2, ".", ","));
						$rep->TextColNum(2, 3, number_format($total, 2, ".", ","));
						$rep->NewLine();
						$sub_total += $total;
						
						$cnt==1?$total_u=getFeeUnit($row['id'],$_REQUEST['id']):'';
						
						$cnt++;
					}
					
					$surcharge = GetSchemeForSurcharge($_REQUEST['id'])*$total_u;
					
					/* TOTAL OTHER PAYMENT */
					$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id= ".$_REQUEST['trm'];
					$result_fee_other = mysql_query($sql_fee_other);
					$row_fee_other = mysql_fetch_array($result_fee_other);
					$sub_mis_total = 0;
					$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$_REQUEST['id']);           
					$sub_mis_total += $mis_total;
					
					
					/* TOTAL LEC PAYMENT */
					$sql_lec = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlec' AND publish =  'Y' AND term_id= ".$_REQUEST['trm'];
					$qry_lec = mysql_query($sql_lec);
					$row_lec = mysql_fetch_array($qry_lec);
					$sub_lec_total = 0;
					
					
					$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$_REQUEST['id']);           
					$sub_lec_total += $lec_total;
					
					/* TOTAL LAB PAYMENT */
					$sql_lab = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlab' AND publish =  'Y' AND term_id= ".$_REQUEST['trm'];
					$qry_lab = mysql_query($sql_lab);
					$row_lab = mysql_fetch_array($qry_lab);
					$sub_lab_total = 0;
					
					
					$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$_REQUEST['id']);           
					$sub_lab_total += $lab_total;
					
					/*TOTAL LEC AND LAB = LEC + LAB*/
					$total_lec_lab =  $sub_lec_total + $sub_lab_total;
					
					//TOTAL PAYMENT
						$total_payment = getTotalPaymentOfStudent($_REQUEST['id'],$_REQUEST['trm']); 
						
						$sql_total_payment = "SELECT sum(amount) FROM tbl_student_payment WHERE  term_id = ".$_REQUEST['trm']." AND is_bounced <> 'Y' AND is_refund <> 'Y' AND student_id =" .$_REQUEST['id'];
						$qry_total_payment = mysql_query($sql_total_payment);
						$row_total_payment = mysql_fetch_array($qry_total_payment);	
						$row_total_payment['amount'];
					  
					  //DISCOUNT
					  $sql_payment = "SELECT * FROM tbl_student_payment WHERE term_id = ".$_REQUEST['trm']." AND  student_id =" .$_REQUEST['id'];
							$qry_payment = mysql_query($sql_payment);
							$row_payment = mysql_fetch_array($qry_payment);
							
							$total_charges = $sub_total - $total;
							
							$total_discounted = getStudentDiscount($row_payment['discount_id'], $_REQUEST['id'], $total_lec_lab); 
						
					/* TOTAL REFUND */
					$sql = "SELECT * FROM tbl_student_payment WHERE is_refund =  'Y' AND student_id = ".$_REQUEST['id']." AND term_id=" .$_REQUEST['trm'];
					$result = mysql_query($sql);
					$ref_total = 0;
					while($row = mysql_fetch_array($result)) 
					{           
						$ref_total += $row['amount'];
					}
			
					if($total_payment>($sub_total-$total_discounted))
					{
						$total_refund = $total_payment-($sub_total-$total_discounted);
					}
					else
					{
						$total_refund = getTotalRefundAmount($_REQUEST['id'],$_REQUEST['trm']);
					}
					//TOTAL
					
					$credit = getCarriedBalances($_REQUEST['id'],$_REQUEST['trm']);
					$debit = getCarriedDebits($_REQUEST['id'],$_REQUEST['trm']);
					$sub_total = ($sub_total-$total_discounted)+$surcharge;
					$sub_total = abs($sub_total - $debit);
					$sub_total = $sub_total + $credit;
					
					if(checkIfStudentPaidFull($_REQUEST['id'])&&$total_refund!=0)
						{
							$total_rem_bal = $sub_total-$total_payment;
						}
						else if($total_payment > ($sub_total))
						{
							$total_rem_bal = 0;
						}
						else if(checkIfStudentDropAllSubjects($_REQUEST['id'])&&$total_payment > ($sub_total))
						{
							$total_rem_bal = 0;
						}
						else if(!checkIfStudentDropAllSubjects($_REQUEST['id'])&&$total_payment > ($sub_total))
						{
							$total_rem_bal = 0;
						}
						else
						{
							$total_rem_bal = $sub_total-($total_payment);
						}
					
					$lib = getLibraryDueFee($id);
					
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Lecture Fee Amount :");
						$rep->TextColNum(2, 3, "Php ".number_format($lec_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Laboratory Fee Amount :");
						$rep->TextColNum(2, 3, "Php ".number_format($lab_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Miscelleneous Fee Amount :");
						$rep->TextColNum(2, 3, "Php ".number_format($mis_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Surcharge :");
						$rep->TextColNum(2, 3, "Php ".number_format($surcharge, 2, ".", ","));						
						$rep->NewLine();
						$rep->TextCol(0, 3, "___________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Tuition Fee Amount :");
						$rep->TextColNum(2, 3, "Php ".number_format($sub_total, 2, ".", ","));						
						$rep->NewLine();
						$rep->TextCol(0, 2, "Carried Balance :");
						$rep->TextColNum(2, 3, "Php ".number_format($credit, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Carried Debit Balance :");
						$rep->TextColNum(2, 3, "Php ".number_format($debit, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, $row_payment['discount_id'] > '0'?"Discount - ".getDiscountName($row_payment['discount_id'])."(".getDiscountValue($row_payment['discount_id'])."%)":"Discount:");
						$rep->TextColNum(2, 3, $row_payment['discount_id'] > '0'?"Php ".number_format($total_discounted, 2, ".", ","):"Php 0.00");
						$rep->NewLine();
						$rep->TextCol(0, 3, "___________________________________________________________");
						
						$rep->NewLine();
						$rep->TextCol(0, 2, "SubTotal :");
						$rep->TextColNum(2, 3, "Php ".number_format($sub_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Refunded Amount :");
						$rep->TextColNum(2, 3, "Php ".number_format($ref_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Refund Balance :");
						$rep->TextColNum(2, 3, "Php ".number_format($total_refund-$ref_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 3, "___________________________________________________________");
						$rep->NewLine(); 
						$rep->TextCol(0, 2, "Total Refund :");
						$rep->TextColNum(2, 3, "Php ".number_format($ref_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Payment :");
						$rep->TextColNum(2, 3, "Php ".number_format($total_payment, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Other Payment :");
						$rep->TextColNum(2, 3, "Php ".number_format($o, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 3, "___________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Remaining Balance :");
						$rep->TextColNum(2, 3, "Php ".number_format($total_rem_bal, 2, ".", ","));
						
						 $sql = "SELECT *

							FROM tbl_other_payments

							WHERE student_id = ".$_REQUEST['id']."

							AND term_id= ".$_REQUEST['trm'];

					$result = mysql_query($sql); 

                    $sub_ototal = 0;

					if(mysql_num_rows($result)>0)

					{

					$x = 1;

						
						$rep->NewLine(4);
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextCol(0, 2, "Other Fees");
						$rep->Font();
						$rep->fontSize -= 1;
						$rep->NewLine(2);
						
						$rep->TextCol(0, 2, "Fees");
						$rep->TextCol(1, 3, "                                   Amount");
						$rep->TextCol(2, 3, "Total");
						$rep->NewLine(2);
						
						while($row = mysql_fetch_array($result)) 

                    {

						$sql2 = "SELECT *

							FROM tbl_payment_types

							WHERE id = ".$row['type_id'];

						$result2 = mysql_query($sql2);

						$row2 = mysql_fetch_array($result2);
						
						$rep->TextCol(0, 2, $row2['name']);
						$rep->TextCol(1, 2, '                                   '.$row['amount']);
						$rep->TextCol(2, 3, $total==''?'Php 0.00':'Php '.number_format($row['amount'], 2, ".", ","));
                        $sub_ototal += $row['amount'];

                        $x++;

                   		} 
						
						$rep->TextCol(2, 3, 'Php '.number_format($sub_ototal, 2, ".", ","));
						
					}
						$sql = "SELECT * FROM tbl_student_payment 
						WHERE  is_refund <> 'Y' AND term_id = ".$_REQUEST['trm']." AND student_id = " .$_REQUEST['id'];
    					$result = mysql_query($sql); 
	
						$sqlref = "SELECT * FROM tbl_student_payment 
						WHERE  is_refund = 'Y' AND term_id = ".$_REQUEST['trm']." AND student_id = " .$_REQUEST['id'];
						$resultref = mysql_query($sqlref);
						
						
						$rep->NewLine(2);
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextCol(0, 2, "Payments");
						$rep->Font();
						$rep->fontSize -= 1;
						$rep->NewLine(2);
						
						$rep->TextCol(0, 2, "Term");
						$rep->TextCol(1, 3, "                                   Amount");
						$rep->TextCol(2, 3, "Payment Term");
						$rep->TextCol(3, 4, "Method");
						//$rep->TextCol(4, 5, "Remarks");
						$rep->TextCol(5, 6, "Date");
						$rep->TextCol(6, 7, "Prepared by");
						$rep->NewLine(2);
						
						 while($row = mysql_fetch_array($result)) 
        				{ 
							$rep->TextCol(0, 2, getSYandTerm($row["term_id"]));
							$rep->TextCol(1, 2, '                                   '.$row['amount']);
							$rep->TextCol(2, 3, getPaymentTerm($row["payment_term"]));
							$rep->TextCol(3, 4, getPaymentMethod($row["payment_method"])=='Cash'?'Cash':'Cheque '.$row["bank"].'('.$row["check_no"].')');
							//$rep->TextCol(4, 5, $row['is_bounced']=='Y'?'Bounced Check':'');
							$rep->TextCol(5, 6, date('M d, Y',$row['date_created']));
							$rep->TextCol(6, 7, getUserInitial($row["created_by"]));
							$rep->NewLine();
						}
						
						
						/*$rep->NewLine(4);
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextCol(0, 2, "Refunds");
						$rep->Font();
						$rep->fontSize -= 1;
						$rep->NewLine(2);
						
						$rep->TextCol(0, 2, "Term");
						$rep->TextCol(1, 3, "                                   Amount");
						$rep->TextCol(2, 3, "Remarks");
						$rep->TextCol(3, 4, "Date Created");
						$rep->TextCol(5, 6, "Prepared by");
						$rep->NewLine(2);
						
						while($rowref = mysql_fetch_array($resultref)) 
        				{ 
							$rep->TextCol(0, 2, getSYandTerm($rowref["term_id"]));
							$rep->TextCol(1, 2, '                                   '.$rowref['amount']);
							$rep->TextCol(2, 3, getPaymentTerm($rowref["payment_term"]));
							$rep->TextCol(3, 4, getPaymentMethod($rowref["payment_method"])=='Cash'?'Cash':'Cheque '.$row["bank"].'('.$rowref["check_no"].')');
							$rep->TextCol(4, 5, $rowref['is_bounced']=='Y'?'Bounced Check':'');
							$rep->TextCol(5, 6, date('M d Y h:m:s',$rowref['date_created']));
							$rep->TextCol(6, 7, getEmployeeFullName($row["created_by"]));
							$rep->NewLine();
						}*/
				}
				else if($_REQUEST['met']=='assessment')
				{
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];	
					 $querys = mysql_query($sqls);
				  $rows = mysql_fetch_array($querys);
					
					$rep->title = 'STUDENT INFO';
					$_REQUEST['met']=='assessment'?$rep->As_Header():$rep->Header();
					
						$rep->TextCol(0, 3, "Student Number : ".$rows['student_number']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Student Name : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);
						
						$rep->NewLine();
						$rep->TextCol(0, 7, "Course : ".getCourseName($rows['course_id']));
						
						if(isset($_REQUEST['trm']))
						{
						$rep->NewLine();
						$rep->TextCol(0, 1, "School Year : ", -2);
						$rep->TextCol(1, 4, getSYandTerm($_REQUEST['trm']), -2);
						}

				
					$sql = "SELECT 
					stud_sched.subject_id,
					stud_sched.units,
					stud_sched.term_id,
					sched.employee_id,
					stud_sched.enrollment_status, 
					stud_sched.schedule_id ,
					sched.elective_of,
					sched.room_id
				FROM 
					tbl_student_schedule stud_sched LEFT JOIN tbl_schedule sched ON 
					stud_sched.schedule_id = sched.id
					WHERE enrollment_status <> 'D' AND
					stud_sched.student_id =  " . $_REQUEST['id'] . 
					" AND stud_sched.term_id = " . $_REQUEST['trm'];	
										
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(670);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(645);
					$rep->NewLine(2);
					
					$rep->Font('bold');
					$rep->TextCol(0, 1, "Code");
					$rep->TextCol(1, 4, "Subject Name");
					$rep->TextCol(3, 5, "Units");
					$rep->TextCol(4, 6, "Days & Time");
					$rep->TextCol(6, 6, "Room");
					$rep->TextCol(7, 8, "Section");
					$rep->Font();
					$rep->NewLine(2);
					
					while($row = mysql_fetch_array($result)) 
					{
						$el = $row['elective_of']!=''?'('.getSubjName($row['elective_of']).')':'';	
						$rep->TextCol(0, 1, getSubjCode($row["subject_id"]));
						$rep->TextCol(1, 4, getSubjName($row["subject_id"]).$el."                      ");
						$rep->TextCol(3, 5, "   ".getSubjUnit($row["subject_id"]));
						$rep->TextCol(4, 6, getScheduleDays($row["schedule_id"]));
						$rep->TextCol(6, 6, getRoomNo($row["room_id"])."                      ");
						//$rep->TextCol(6, 8, getProfessorFullName($row["employee_id"]));
						$rep->TextCol(7, 8, getSectionNo($row["schedule_id"]));
						$rep->NewLine();
						
						$totalU+=getSubjUnit($row["subject_id"]);
					}
						
						$rep->Font('bold');
						$rep->TextCol(2, 3, "          ".$totalU);
						$rep->Font();
						$rep->NewLine();
						/*$tline = (8+mysql_num_rows($result))*$rep->lineHeight;
						$tl1 = 675-$tline;
						$tl2 = $tl1-25;
						
						$rep->NewLine(2);
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextColLines(0, 3, "Drop Subjects");
						$rep->Font();
						$rep->fontSize -= 1;
						//$rep->NewLine();
					
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl1);
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl2);
						$rep->NewLine();
						$rep->TextCol(0, 1, "Section");
						$rep->TextCol(1, 2, "Subject Name");
						$rep->TextCol(2, 3, "Code             ");
						$rep->TextCol(3, 4, "Room");
						$rep->TextCol(4, 7, "Days");
						$rep->NewLine(2);
						
						$sqldrop = "SELECT 
						stud_sched.subject_id,
						stud_sched.units,
						stud_sched.term_id,
						stud_sched.enrollment_status, 
						stud_sched.schedule_id ,
						sched.room_id, 
						sched.section_no
					FROM 
						tbl_student_schedule stud_sched LEFT JOIN tbl_schedule sched ON 
						stud_sched.schedule_id = sched.id
						WHERE stud_sched.enrollment_status = 'D' AND 
						stud_sched.student_id =  " . $_REQUEST['id'] . 
						" AND stud_sched.term_id = " . $_REQUEST['trm'];	
											
						$querydrop = mysql_query($sqldrop);
						
						while($rowd = mysql_fetch_array($querydrop)) 
					{
						$rep->TextCol(0, 1, getSectionNo($rowd["schedule_id"]));
						$rep->TextCol(1, 2, getSubjName($rowd["subject_id"]));
						$rep->TextCol(2, 3, getSubjCode($rowd["subject_id"]).'             ');
						$rep->TextCol(3, 4, getRoomNo($rowd["room_id"]));
						$rep->TextCol(4, 7, getScheduleDays($rowd["schedule_id"]));
						$rep->NewLine();
					}
					*/
						$tline = (6+mysql_num_rows($result))*$rep->lineHeight;
						$tl1 = 695-$tline;
						$tl2 = $tl1-25;
						
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl1);
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl2);
						
						$rep->NewLine();
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextColLines(2, 3, "Assessment");
						$rep->Font();
						$rep->fontSize -= 1;
						$rep->NewLine();
						
						/* TOTAL OTHER PAYMENT 
					$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id= ".$_REQUEST['trm'];
					$result_fee_other = mysql_query($sql_fee_other);
					$row_fee_other = mysql_fetch_array($result_fee_other);
					$sub_mis_total = 0;
					$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$_REQUEST['id']);           
					$sub_mis_total += $mis_total;*/
					
					
					/* TOTAL LEC PAYMENT */
					$sql_lec = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlec' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$_REQUEST['id']." AND s.term_id =" .$_REQUEST['trm'];
					$qry_lec = mysql_query($sql_lec);
					$row_lec = mysql_fetch_array($qry_lec);
					$sub_lec_total = 0;
					
					$sub_lec_total = $row_lec['sum(s.quantity)']*$row_lec['amount'];
					
					/* TOTAL LAB PAYMENT */
					$sql_lab = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlab' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$_REQUEST['id']." AND s.term_id =" .$_REQUEST['trm'];
					$qry_lab = mysql_query($sql_lab);
					$row_lab = mysql_fetch_array($qry_lab);
					$sub_lab_total = 0;
					
					
					$sub_lab_total = $row_lab['sum(s.quantity)']*$row_lab['amount'];
					
					/*TOTAL LEC AND LAB = LEC + LAB*/
					$total_lec_lab =  $sub_lec_total + $sub_lab_total;
					
					$surcharge = GetSchemeForSurcharge2($_REQUEST['id'],$_REQUEST['trm'])*$totalU;
					$tfee = $row_lec['amount']+GetSchemeForSurcharge2($_REQUEST['id'],$_REQUEST['trm']);
					
						$rep->TextCol(2, 4, "Tuition Fee");
						$rep->TextColNum(3, 5, '                         '.number_format($tfee, 2, ".", ","));
						$rep->TextColNum(5, 6, '          '.number_format($sub_lec_total+$surcharge, 2, ".", ","));
						$rep->NewLine();
						
						$sql = "SELECT f.fee_type,s.* FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND f.id=s.fee_id AND s.is_removed = 'N' AND s.student_id = ".$_REQUEST['id']." AND s.term_id =" .$_REQUEST['trm'];
                    
            			$result = mysql_query($sql);
					
						while($row = mysql_fetch_array($result)) 
					{
						$total = $row['amount']*$row['quantity'];
						$rep->TextCol(2, 4, getFeeName($row['fee_id']));
						$rep->TextColNum(3, 5, '                         '.$row['amount']);
						$rep->TextColNum(5, 6, '          '.number_format($total, 2, ".", ","));
						$rep->NewLine();
						$mis_total += $total;
						$sub_total += $total;
					}
					
					$sql2 = "SELECT * FROM tbl_student_other_fees s,tbl_school_other_fee f WHERE f.id=s.fee_id AND s.is_removed <> 'Y' AND s.student_id=".$_REQUEST['id']." AND s.term_id=".$_REQUEST['trm'];
				$query2 = mysql_query($sql2);
				$otherFee = mysql_num_rows($query2);
				
					while($row2 = mysql_fetch_array($query2)) 
					{
						$total = $row2['amount'];
						$rep->TextCol(2, 4, $row2['fee_name']);
						$rep->TextCol(3, 5, "                         ".$row2['amount']);
						$rep->TextColNum(5, 6, '          '.number_format($total, 2, ".", ","));
						$rep->NewLine();
						$sub_total += $row2['amount'];
						$mis_total += $row2['amount'];
					}
					
					$sub_total = $sub_total+$total_lec_lab;
					//TOTAL PAYMENT
						$total_payment = getTotalPaymentOfStudent($_REQUEST['id'],$_REQUEST['trm']); 
						
						$sql_total_payment = "SELECT sum(amount) FROM tbl_student_payment WHERE  term_id = ".$_REQUEST['trm']." AND is_bounced <> 'Y' AND is_refund <> 'Y' AND student_id =" .$_REQUEST['id'];
						$qry_total_payment = mysql_query($sql_total_payment);
						$row_total_payment = mysql_fetch_array($qry_total_payment);	
						$row_total_payment['amount'];
					  
					  //DISCOUNT
					 $surcharge = GetSchemeForSurcharge2($_REQUEST['id'],$_REQUEST['trm'])*$totalU;
			
					$sqldis = 'SELECT * FROM tbl_student WHERE id='.$_REQUEST['id'];
					$querydis = mysql_query($sqldis);
					$rowdis = mysql_fetch_array($querydis);
					
					$sqlt = 'SELECT * FROM tbl_school_year_term WHERE id='.$_REQUEST['trm'];
						$queryt = mysql_query($sqlt);
						$rowt = mysql_fetch_array($queryt);
						$t = strtolower($rowt['school_term'])!='summer'?5000:0;
					
					if($rowdis['scholarship_type']=='A')
					{
						getStudentYearLevel($_REQUEST['id'])>3?$t=0:'';
						$discount = ($sub_total+$surcharge)-$t;
						
						/*if($otherFee>0)
						{
							$discount = $discount-6920;
						}*/
						
						$discount = ($discount*$rowdis['scholarship'])/100;
					}
					
					else
					{
		
						$discount = $sub_lec_total+$surcharge;
						
						/*if(getStudentCourseId($_REQUEST['id'])==13&&getStudentYearLevel($_REQUEST['id'])>1)
						{
							$discount = $discount-6920;
						}*/
						
						$discount = ($discount*$rowdis['scholarship'])/100;
					}
						
					/* TOTAL REFUND */
					$sql = "SELECT * FROM tbl_student_payment WHERE is_refund =  'Y' AND student_id = ".$_REQUEST['id']." AND term_id=" .$_REQUEST['trm'];
					$result = mysql_query($sql);
					$ref_total = 0;
					while($row = mysql_fetch_array($result)) 
					{           
						$ref_total += $row['amount'];
					}
			
					if($row_total_payment['sum(amount)']>($sub_total-$discount))
					{
						$total_refund = $row_total_payment['sum(amount)']-($sub_total-$discount);
					}
					else
					{
						$total_refund = getTotalRefundAmount($_REQUEST['id'],$_REQUEST['trm']);
					}
					//TOTAL
					$sub_total=$sub_total-$discount;
					
					if(checkIfStudentPaidFull($id)&&$total_refund!=0)
						{
							$total_rem_bal = $sub_total-$row_total_payment['sum(amount)'];
						}
						else if(checkIfStudentDropAllSubjects($id)&&$row_total_payment['sum(amount)'] > ($sub_total))
						{
							$total_rem_bal = 0;
						}
						else if(!checkIfStudentDropAllSubjects($student_id)&&$row_total_payment['sum(amount)'] > ($sub_total))
						{
							$total_rem_bal = 0;
						}
						else
						{
							$total_rem_bal = $sub_total-($row_total_payment['sum(amount)']);
						}
					
					$lib = getLibraryDueFee($id);
					
						$rep->Font("bold");
						$rep->TextCol(2, 4, "Summary".getStudentYearLevel($_REQUEST['id']));
						$rep->Font();
						$rep->NewLine();
						$rep->Image2('../images/enrolled.png', 40, 425,140,86);
						$rep->TextCol(0, 2, "Date Enrolled: ".date('F d, Y',getEnrolledDate($_REQUEST['id'],$_REQUEST['trm'])));
						$rep->TextCol(2, 4, "Total Lecture Fee :");
						$rep->TextColNum(5, 6, "          ".number_format($sub_lec_total+$surcharge, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "_______________________________________");
						//$rep->TextCol(2, 4, "Total Laboratory Fee Amount :");
						//$rep->TextCol(5, 7, "          Php ".number_format($sub_lab_total, 2, ".", ","));
						$rep->TextCol(2, 4, "Total Miscelleneous Fee :");
						$rep->TextColNum(5, 6, "          ".number_format($mis_total, 2, ".", ","));
						$rep->NewLine();
						$rep->Font("bold");
						$rep->TextCol(0, 2, "Payment History");
						$rep->Font();
						
						$s = $rowdis['scholarship']>0?$rowdis['scholarship_type']:'';
						
						$rep->TextCol(2, 4, "Scholarship (".$rowdis['scholarship']."%) / ".$s." :");
						$rep->TextColNum(5, 6, $discount > 0?"          (".number_format($discount, 2, ".", ",").")":"           0.00");
						$rep->NewLine();
						
						$rep->TextCol(0, 2, "Date                                    O/R No.          Amount");
						
						//$rep->NewLine();
						
						$a = 0;
						$b = 0;
						$c = array();
						$sqlp = "SELECT * FROM tbl_student_payment WHERE student_id=".$_REQUEST['id']." AND term_id=".$_REQUEST['trm'];
						$queryp = mysql_query($sqlp);
						
						while($rowp = mysql_fetch_array($queryp))
						{
							//$rep->TextCol(0, 2, date('F d, Y',$rowp['date_created'])."                      ".$rowp['or_no']."          ".number_format($rowp['amount'], 2, ".", ","));
							$c[$a] = date('M d, Y',$rowp['date_created'])."                      ".$rowp['or_no']."          ".number_format($rowp['amount'], 2, ".", ",");	
							//$rep->NewLine();
							$a++;
							$b++;
						}
									
						
						
						/*$rep->TextCol(2, 7, "_________________________________________________");
						$rep->NewLine();
						$rep->TextCol(2, 4, "Total Tuition Fee Amount :");
						$rep->TextCol(5, 7, "Php ".number_format($sub_total, 2, ".", ","));
						$rep->NewLine();*/
						$rep->TextCol(2, 7, "_________________________________________________");
						$rep->NewLine();
						
						$rep->TextCol(0, 2, $c[0]);
						
						$rep->Font("bold");
						$rep->TextCol(2, 4, "Total Assessment :");
						$rep->TextColNum(5, 6, "          ".number_format(($sub_total+$surcharge)-$total_discounted, 2, ".", ","));
						$rep->Font();
						/*$rep->NewLine();
						$rep->TextCol(0, 2, "Refunded Amount :");
						$rep->TextCol(2, 3, "Php ".number_format($ref_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Refund Balance :");
						$rep->TextCol(2, 3, "Php ".number_format($total_refund-$ref_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 3, "_____________________________________________________________________");
						$rep->NewLine(); 
						$rep->TextCol(0, 2, "Total Refund :");
						$rep->TextCol(2, 3, "Php ".number_format($ref_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(2, 4, "Total Payment :");
						$rep->TextCol(5, 7, "Php ".number_format($row_total_payment['sum(amount)'], 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(2, 4, "Total Other Payment :");
						$rep->TextCol(5, 7, "Php ".number_format($o, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(2, 7, "_________________________________________________");
						$rep->NewLine();
						$rep->Font("bold");
						$rep->TextCol(2, 4, "Total Remaining Balance :");
						$rep->TextCol(5, 7, "Php ".number_format($total_rem_bal, 2, ".", ","));
						$rep->Font();*/
						$rep->NewLine();
						
						$totalfee = ($sub_total+$surcharge)-$total_discounted;
						
						$rep->TextCol(0, 2, $c[1]);
						
						$rep->Font("bold");
						$rep->TextCol(2, 4, "Mode Of Payment");
						$rep->Font();
						
				$sqlsch = "SELECT *

				FROM tbl_payment_scheme_details

				WHERE scheme_id = ".GetStudentScheme($_REQUEST['id'],$_REQUEST['trm'])." ORDER BY sort_order";

                $resultsch = mysql_query($sqlsch);

				$d=2;
                while($rowsch = mysql_fetch_array($resultsch)) 

                {

					if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'A')

					{

						$topay = $rowsch['payment_value'];

						$totalfee = $totalfee - $topay;

						$initial = $rowsch['id'];

					}

					else if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'P')

					{

						$topay = abs(getStudentPaymentScheme($rowsch['id'],$totalfee));;

						//$total_fee = $sub_total - $down;

						$initial = $rowsch['id'];

					}

					else if($rowsch['payment_type'] == 'P')

					{

						$topay = abs(getStudentPaymentScheme($rowsch['id'],$totalfee));

					}
					
						$rep->NewLine();
						$rep->TextCol(2, 4, $rowsch['payment_name']);
						$rep->TextColNum(5, 6, "          ".number_format($topay, 2, ".", ","));
						
						$rep->TextCol(0, 2, $c[$d]);
						$d++;
					
				}
						$rep->NewLine();
						$rep->TextCol(0, 2, $c[$d]);
						$rep->Font("bold");
						//$rep->NewLine();
						
						$rep->TextCol(0, 2, $c[$d+1]);
						
						$rep->TextCol(2, 7, "_________________________________________________");
						$rep->NewLine();
						
						$rep->TextCol(0, 2, $c[$d+2]);
						
						$rep->TextCol(2, 4, "Total");
						$rep->TextColNum(5, 6, "          ".number_format($totalfee, 2, ".", ","));
						$rep->Font();
						
						$rep->NewLine(3);
						$rep->TextCol(0, 2, "Carla Jerremia B. Alona
						");
						$rep->NewLine();
						$rep->TextCol(0, 2, "Registrar");
				}
				else if($_REQUEST['met']=='transcript')
				{
					
					$margins = array('top'=>30,'bottom'=>100,'left'=>30,'right'=>30);
					$rep = new FrontReport('Transcript', 'transcript', 'A4', 7, 'P', $margins);

					$rep->Font();
					$rep->Info($params, $cols, null, $aligns);
		
					$rep->title = 'STUDENT INFO';
					$rep->Header3();
						
					
					/*$sql_student_photo = "SELECT * FROM tbl_student_photo WHERE student_id = ".$_REQUEST['id'];
					$query_student_photo = mysql_query($sql_student_photo);
					$row_student_photo = mysql_fetch_array($query_student_photo);
					
					if($row_student_photo['image_file']!='')
						{
							$rep->Image($row_student_photo['image_file'], 400, 140,110,110);
						}
						else
						{
							$rep->Image2('../images/NoPhotoAvailable.jpg', 400, 140,110,110);
						}*/
					
					$sqldet = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];
					$resultdet = mysql_query($sqldet);
					$row = mysql_fetch_array($resultdet);
					$cur = $row['curriculum_id'];
					$yir = $row['start_year_level'];
					
					if($row['admission_type']=='T'){
					$sqlcur = "SELECT * FROM tbl_student_credit_final_grade WHERE type = 'C' AND student_id = ".$_REQUEST['id'];
					$querycur = mysql_query($sqlcur);
					$ctr_rowcur = mysql_num_rows($querycur);
					}
					
						
						$rep->fontSize = 10;
						
						
						
						$name = strtoupper($row['lastname'].' , '.$row['firstname'].' '.$row['middlename']);
						
						$rep->TextCol(0, 3, "Name : ");
						$rep->Font('bold');
						$rep->TextCol(0, 3, "              ".$name);
						$rep->Font();
						$rep->NewLine();
						$rep->TextCol(0, 3, "Student Number : ".$row['student_number']);
						$rep->NewLine();
						$rep->TextCol(0, 3, $row['gender']=='F'?'Gender : Female':'Gender : Male');
						$rep->NewLine();
						$birth = explode('-',$row['birth_date']);
						$rep->TextCol(0, 3, "Date of Birth : ".getMonthName($birth[1])." ".$birth[2].", ".$birth[0]);
						$rep->NewLine();
						$rep->TextCol(0, 3, "Citizenship : ".getCitizenship($row['citizenship']));
						$rep->NewLine();
						$rep->TextCol(0, 7, "Address : ".$row['home_address']);
						
						$rep->NewLine();
						$rep->TextCol(0, 7, "________________________________________________________________________________________________________________________");
						$rep->NewLine(2);
						
						$rep->TextCol(0, 3, "Primary School : ".$row['grade_school']);
						$rep->NewLine(2);
						$rep->TextCol(0, 3, "Secondary School : ".$row['high_school']);
						$rep->NewLine(2);
						
						if($row['admission_type']=='T')
						{
						$rep->TextCol(0, 3, "School Last Attended : ".$row['college_school']);
						$rep->NewLine(2);
						}
						else
						{
							$rep->NewLine(2);
						}
						
						$rep->TextCol(0, 4, "Degree : ".getCourseName($row['course_id']));
						$rep->NewLine(3);
						
						$rep->TextCol(0, 3, "Date of Graduation : ".getGraduationDate($_REQUEST['id']));
						$rep->NewLine();
						
						$rep->TextCol(0, 7, "________________________________________________________________________________________________________________________");
						$rep->NewLine(2);
						
						$rep->TextCol(0, 7, "GRADE POINT EQUIVALENCE");
					$rep->NewLine();
					$rep->TextColLines(0, 7, "1.00 : 97-100 | 1.25 : 92-96 | 1.50 : 87-91 | 1.75 : 79-86 | 2.00 : 72-78 | 2.25 : 64-71 | 2.50 : 57-63 | 2.75 : 53-63 | 3.00 : 50-52 : 4.00 for Prelims &amp; 5.00 for finals | INC : Incomplete | W : Dropped with Formality | D : Dropped without Formality");
					$rep->NewLine(2);
					
					$rep->TextCol(0, 7, "NOTE");
					$rep->NewLine();
					$rep->TextColLines(0, 7, "This Transcript is valid only when it bears the seal of the school and the original signature of the School Registrar. Any erasure or alteration made on this copy renders the whole transcript invalid.");
					$rep->NewLine();
					$rep->TextCol(0, 7, "CERTIFICATION");
					$rep->NewLine();
					$rep->TextColLines(0, 7, "I hereby certify that the foregoing records of ".$name." have been verified and that the original copies of the official records substaining the same are kept in the files of the college.");
					
					$rep->NewLine(5);
					$rep->TextCol(0, 7, "________________");
					$rep->NewLine();
					$rep->TextCol(0, 7, "Carla Jerremia B. Alona
					");
					$rep->NewLine();
					$rep->TextCol(0, 7, "Registrar");
					
					
					$rep->NewLine(18);
						$rep->Font('bold');
						$rep->TextCol(0, 1, "Code");
						$rep->TextCol(1, 4, "       Descriptive Title ");
						$rep->TextCol(4, 4, "Units           ");
						$rep->TextCol(5, 6, "Grade             ");
						$rep->TextCol(6, 7, "Remarks             ");
						$rep->Font('');
						$rep->NewLine();
				
					$sqlcurr = "SELECT * FROM tbl_curriculum WHERE id = ".$cur;
					$rescurr = mysql_query($sqlcurr);
					$rowcurr = mysql_fetch_array($rescurr);
					$termper = $rowcurr['term_per_year'];
				
					$sqlsub = "SELECT * FROM tbl_student_schedule
					WHERE student_id = ".$_REQUEST['id']." ORDER BY term_id";
					  $resultsub = mysql_query($sqlsub);
					  $ctr_row = mysql_num_rows($resultsub);
					
					$curTerm = 0;
					$total_units = 0;
					$tem = 0;
					$yirs = $yir;
					
					$x=0;
					
					if($ctr_row > 0)
				{
					 if($ctr_rowcur > 0)
				  	{
						$rep->Font('bold');
						$rep->TextCol(0, 7, getStudentLastSchool($_REQUEST['id']));
						$rep->Font('');
                      	$rep->NewLine(2);
					 while($rowcur = mysql_fetch_array($querycur))
					  {
						$rep->TextCol(0, 1, getSubjCode($rowcur['subject_id']));
						$rep->TextCol(1, 4, getSubjName($rowcur['subject_id']));
						$rep->TextCol(4, 4, "      ".getSubjUnit($rowcur['subject_id']));
						$rep->TextCol(5, 6, $rowcur['final_grade']);
						$rep->TextCol(6, 7, 'Passed');
					
                      	$rep->NewLine();
                     $trm = $rowcur["term_id"];
					  } 
					  }
					  $rep->NewLine();
					  $rep->Font('bold');
					  $rep->TextCol(0, 7, 'Meridian International College');
					  $rep->Font('');
                      	$rep->NewLine();
				  while($row = mysql_fetch_array($resultsub))
				{
				if ($curTerm != $row["term_id"]) {
					
					if($tem==$termper)
					{
						$yirs++;
					}
				  
				  $curTerm = $row["term_id"];
				  $total_units = 0;
				  if($tem==0){
					  if($row['school_term']=='Second Term'){
					  $tem=2;
					  }else if($row['school_term']=='Third Term' || $row['school_term']=='Summer'){
					  $tem=3;
					  }else{
					  $tem++;
					  }
				  }else{
				  $tem++;
				  }
				  }
					/*$sql_grade = "SELECT * FROM tbl_student_final_grade 
					  			WHERE subject_id = ".$row['subject_id']." 
								AND term_id = '".$row["term_id"]."'
								AND student_id = ".$_REQUEST['id'];
						$result_grade = mysql_query($sql_grade);
						$row_grade = mysql_fetch_array($result_grade);*/
						
						$row_grade =getGradeConversionById(getGradeConversionId(getStudentFinalGrade($_REQUEST['id'],$row['schedule_id'],$row["term_id"]),$row["term_id"]));
						
					$el = $row['elective_of']?' ('.getSubjName($row['elective_of']).')':'';
						
						$trm!=$row["term_id"]?$x=2:$x=3;

                    if($x==2)
					{
						$trm!=''?$rep->NewLine():'';
						$rep->Font('bold');
						$rep->TextCol(1, 2, getSchoolTerm($row['term_id']).' '.getSchoolYearStartEndByTerm($row["term_id"]));
						$rep->NewLine();
						$rep->Font();
						$trm = $row["term_id"];
					}
						if(checkIfSubjectDroppedByTerm($_REQUEST['id'],$row['schedule_id'],$row["term_id"]))
						{
							$grade = 'W';
							$stat = 'WITHDRAW';
						}
						else if(checkIfSubjectINCByTerm($_REQUEST['id'],$row['schedule_id'],$row["term_id"]))
						{
							$grade = 0;
							$stat = 'INC';
						}
						else
						{
							//$grade = number_format(getGradeConversionById($row_grade["grade_conversion_id"]), 2, '.', '');
							$grade = number_format($row_grade, 2, '.', '');
							if($grade>3)
							{
								$stat='Failed';
							}
							else
							{
								if($grade>0)
								{
									$stat = 'Passed';
									$totalU += $row['units'];
									$total_units += $row['units'];
								}
								else
								{
									if($row["term_id"]!=CURRENT_TERM_ID)
									{
										$stat = 'Currently in Process';
									}else{
										$stat = 'Currently Enrolled';
									}
								}
							}
						}
					
					$rep->TextCol(0, 1, getSubjCode($row['subject_id']));
						$rep->TextCol(1, 4, getSubjName($row['subject_id']).$el);
						$rep->TextCol(4, 4, "      ".$row['units']);
						$rep->TextCol(5, 6, $grade);
						$rep->TextCol(6, 7, $stat);
				  		
						$trm = $row["term_id"];
						$rep->NewLine();
						
						
					
				  }
						//$rep->NewLine();
						$rep->TextCol(0, 7, "---------------------------------------------------------------------nothing follows----------------------------------------------------------------------------------");

					}
					
					//$rep->newPage();
					$rep->NewLine();
					//$rep->Line(750,1);
					
					$rep->TextCol(1, 7, "Total Units Earned : ".$totalU.".00");
					$rep->NewLine();
					
					$rep->TextCol(1, 7, "REMARKS : ** ".$_REQUEST['copy']." **");
					$rep->NewLine();
					
					$rep->NewLine();
					$rep->TextCol(1, 7, "Prepared by: ________________");$rep->TextCol(5, 7, "________________");
					$rep->NewLine();
					$rep->TextCol(1, 5, "Checked by: ________________");$rep->TextCol(5, 7, "Carla Jerremia B. Alona");
					$rep->NewLine();
					$rep->TextCol(5, 7, "Registrar");
					
					
					
					
				}
				else if($_REQUEST['met']=='curriculum')
				{
					//$rep = new FrontReport('Transcript', 'transcript', 'custom', 7, 'P', $margins);
					
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];	
					 $querys = mysql_query($sqls);
				  $rows = mysql_fetch_array($querys);
					
					$rep->title = 'STUDENT INFO';
					$rep->No_space_Header();
					$rep->fontSize =7;
					$rep->Font('bold');
					$rep->TextCol(1, 5, "                                               MERIDIAN INTERNATIONAL BUSINESS,");
					$rep->NewLine();
					$rep->TextCol(1, 5, "                                                  ARTS and TECHNOLOGY COLLEGE");
					$rep->Font();
						$rep->NewLine();
						$rep->TextCol(1, 7, "                   1030 Campus Ave. 2F CIP Building Mckinley Hill, Fort Bonifacio, Taguig City, Philippines");
						$rep->NewLine();
						$rep->Font('bold');
						$rep->TextCol(1, 7, "                          ".getCourseName($rows['course_id']));
						$rep->NewLine();
					
						$rep->TextCol(0, 3, "NAME : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);
						
						$rep->TextCol(3, 7, "ENTRY AY : ".getSchoolYearStudentStart($_REQUEST['id']));
						$rep->Font('');
						//$rep->NewLine();
						
						
						
					
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
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextColLines(0, 3, getYearLevel($ctr_year).'('.getSemesterInWord($ctr_terms).')');
						$rep->Font();
						$rep->fontSize -= 1;
						
						
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
							/*else if(CheckIfStudentEnrolledBySubject($_REQUEST['id'],CURRENT_TERM_ID,$row['subject_id']))
							{
							$str = 'Currently Enrolled';
							}*/
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
						$rep->TextCol(0, 6, "__________________________________________________________________________________________________________________________________");
						$rep->NewLine(1,2);
						$rep->TextCol(2, 3, $total_units);
						}
					}
					}
					
					$rep->NewLine(2);
						$rep->TextCol(6, 7, "DATE: ".date('m-d-Y'));
				}
				else if($_REQUEST['met']=='payment')
				{
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];	
					$querys = mysql_query($sqls);
				  $rows = mysql_fetch_array($querys);
					
					$rep->title = 'STUDENT INFO';
					$rep->Header();
					
						$rep->TextCol(0, 3, "Student Name : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Student Number : ".$rows['student_number']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Course : ".getCourseName($rows['course_id']));
						
						if(isset($_REQUEST['trm']))
						{
						$rep->NewLine();
						$rep->TextCol(0, 1, "School Year : ", -2);
						$rep->TextCol(1, 4, getSYandTerm($_REQUEST['trm']), -2);
						}

					
					$sql = "SELECT 
					stud_sched.subject_id,
					stud_sched.units,
					stud_sched.term_id,
					stud_sched.enrollment_status, 
					stud_sched.schedule_id ,
					sched.room_id, 
					sched.section_no
				FROM 
					tbl_student_schedule stud_sched LEFT JOIN tbl_schedule sched ON 
					stud_sched.schedule_id = sched.id
					WHERE enrollment_status <> 'D' AND
					stud_sched.student_id =  " . $_REQUEST['id'] . 
					" AND stud_sched.term_id = " . $_REQUEST['trm'];	
										
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(700);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(675);
					$rep->NewLine(2);
					
					$rep->TextCol(0, 1, "Section");
					$rep->TextCol(1, 2, "Subject Name");
					$rep->TextCol(2, 3, "Code             ");
					$rep->TextCol(3, 4, "Room");
					$rep->TextCol(4, 7, "Days");
					$rep->NewLine(2);
					
					while($row = mysql_fetch_array($result)) 
					{
						$rep->TextCol(0, 1, getSectionNo($row["schedule_id"]));
						$rep->TextCol(1, 2, getSubjName($row["subject_id"]));
						$rep->TextCol(2, 3, getSubjCode($row["subject_id"]).'             ');
						$rep->TextCol(3, 4, getRoomNo($row["room_id"]));
						$rep->TextCol(4, 7, getScheduleDays($row["schedule_id"]));
						$rep->NewLine();
					}
					
						$tline = (5+mysql_num_rows($result))*$rep->lineHeight;
						$tl1 = 675-$tline;
						$tl2 = $tl1-25;
						
						$sqldrop = "SELECT * FROM 
							tbl_student_schedule 
						WHERE
							term_id = ".CURRENT_TERM_ID." AND
							student_id= " .$_REQUEST['trm']. " AND
							enrollment_status='D' OR enrollment_status='DR'";	
											
						$querydrop = mysql_query($sqldrop);
						
						if(mysql_num_rows($querydrop)>0)
						{
						
						$rep->NewLine(2);
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextColLines(0, 3, "Refunds");
						$rep->Font();
						$rep->fontSize -= 1;
						$rep->NewLine();
					
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl1);
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl2);
						$rep->NewLine();
						$rep->TextCol(0, 1, "Section");
						$rep->TextCol(1, 2, "Subject Name");
						$rep->TextCol(2, 3, "Code             ");
						$rep->TextCol(3, 4, "Room");
						$rep->TextCol(4, 5, "Days");
						$rep->TextCol(5, 6, "From");
						$rep->TextCol(6, 7, "To");
						$rep->NewLine(2);
						
						while($rowd = mysql_fetch_array($querydrop)) 
					{
						$rep->TextCol(0, 1, getSectionNo($rowd["schedule_id"]));
						$rep->TextCol(1, 2, getSubjName($rowd["subject_id"]));
						$rep->TextCol(2, 3, getSubjCode($rowd["subject_id"]).'             ');
						$rep->TextCol(3, 4, getRoomNo($rowd["room_id"]));
						$rep->TextCol(4, 5, getScheduleDays($rowd["schedule_id"]));
						$rep->TextCol(5, 6, $rowd["time_from"]);
						$rep->TextCol(6, 7, $rowd["time_to"]);
						$rep->NewLine();
					}
					
						$tline = (18+mysql_num_rows($querydrop))*$rep->lineHeight;
						$tl1 = 675-$tline;
						$tl2 = $tl1-25;
					}
						$rep->NewLine(2);
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextColLines(0, 3, "Assessment of Fees");
						$rep->Font();
						$rep->fontSize -= 1;
						$rep->NewLine();
					
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl1);
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl2);
						$rep->NewLine();
						$rep->TextCol(0, 1, "Fee");
						$rep->TextCol(1, 2, "                           Amount");
						$rep->TextCol(2, 3, "Total             ");
						$rep->NewLine(2);
						
						$sql = "SELECT *
						FROM tbl_school_fee
						WHERE publish =  'Y'
						AND term_id=" .CURRENT_TERM_ID;
                    
            			$result = mysql_query($sql);
					
						while($row = mysql_fetch_array($result)) 
					{
						$total = getStudentTotalFeeLecLab($row['id'],$_REQUEST['id'] );
						$rep->TextCol(0, 2, $row['fee_name']);
						$rep->TextCol(1, 2, '                           '.getFeeAmount($row['id']));
						$rep->TextCol(2, 3, $total);
						$rep->NewLine();
						$sub_total += $total;
					}
					
					/* TOTAL OTHER PAYMENT */
					$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id= ".$_REQUEST['trm'];
					$result_fee_other = mysql_query($sql_fee_other);
					$row_fee_other = mysql_fetch_array($result_fee_other);
					$sub_mis_total = 0;
					$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$_REQUEST['id']);           
					$sub_mis_total += $mis_total;
					
					
					/* TOTAL LEC PAYMENT */
					$sql_lec = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlec' AND publish =  'Y' AND term_id= ".$_REQUEST['trm'];
					$qry_lec = mysql_query($sql_lec);
					$row_lec = mysql_fetch_array($qry_lec);
					$sub_lec_total = 0;
					
					
					$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$_REQUEST['id']);           
					$sub_lec_total += $lec_total;
					
					/* TOTAL LAB PAYMENT */
					$sql_lab = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlab' AND publish =  'Y' AND term_id= ".$_REQUEST['trm'];
					$qry_lab = mysql_query($sql_lab);
					$row_lab = mysql_fetch_array($qry_lab);
					$sub_lab_total = 0;
					
					
					$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$_REQUEST['id']);           
					$sub_lab_total += $lab_total;
					
					/*TOTAL LEC AND LAB = LEC + LAB*/
					$total_lec_lab =  $sub_lec_total + $sub_lab_total;
					
					//TOTAL PAYMENT
						$total_payment = getTotalPaymentOfStudent($_REQUEST['id'],$_REQUEST['trm']); 
						
						$sql_total_payment = "SELECT sum(amount) FROM tbl_student_payment WHERE  term_id = ".$_REQUEST['trm']." AND is_bounced <> 'Y' AND is_refund <> 'Y' AND student_id =" .$_REQUEST['id'];
						$qry_total_payment = mysql_query($sql_total_payment);
						$row_total_payment = mysql_fetch_array($qry_total_payment);	
						$row_total_payment['amount'];
					  
					  //DISCOUNT
					  $sql_payment = "SELECT * FROM tbl_student_payment WHERE term_id = ".$_REQUEST['trm']." AND  student_id =" .$_REQUEST['id'];
							$qry_payment = mysql_query($sql_payment);
							$row_payment = mysql_fetch_array($qry_payment);
							$paymentId = $row_payment['payment_scheme_id'];
							$total_charges = $sub_total - $total;
							
							//$total_discounted = getStudentDiscount($row_payment['discount_id'], $_REQUEST['id'], $total_lec_lab); 
							if($rows['scholarship_type']=='A')
							{
								$total_discounted = ($sub_total)-5000;
								$total_discounted = ($total_discounted*$rows['scholarship'])/100;
							}
							
							else
							
							{
				
								$total_discounted = $sub_lec_total;
								$total_discounted = ($total_discounted*$rows['scholarship'])/100;
							}
						
					/* TOTAL REFUND */
					$sql = "SELECT * FROM tbl_student_payment WHERE is_refund =  'Y' AND student_id = ".$_REQUEST['id']." AND term_id=" .$_REQUEST['trm'];
					$result = mysql_query($sql);
					$ref_total = 0;
					while($row = mysql_fetch_array($result)) 
					{           
						$ref_total += $row['amount'];
					}
			
					if($row_total_payment['sum(amount)']>($sub_total-$total_discounted))
					{
						$total_refund = $row_total_payment['sum(amount)']-($sub_total-$total_discounted);
					}
					else
					{
						$total_refund = getTotalRefundAmount($_REQUEST['id'],$_REQUEST['trm']);
					}
					//TOTAL
					
					$credit = getCarriedBalances($_REQUEST['id'],$_REQUEST['trm']);
					$debit = getCarriedDebits($_REQUEST['id'],$_REQUEST['trm']);
					$sub_total = $sub_total-$total_discounted;
					$sub_total = abs($sub_total - $debit);
					$sub_total = $sub_total + $credit;
					$total_fee = $sub_total;
					
					if(checkIfStudentPaidFull($id)&&$total_refund!=0)
						{
							$total_rem_bal = $sub_total-$row_total_payment['sum(amount)'];
						}
						else if(checkIfStudentDropAllSubjects($id)&&$row_total_payment['sum(amount)'] > ($sub_total))
						{
							$total_rem_bal = 0;
						}
						else if(!checkIfStudentDropAllSubjects($student_id)&&$row_total_payment['sum(amount)'] > ($sub_total))
						{
							$total_rem_bal = 0;
						}
						else
						{
							$total_rem_bal = $sub_total-($row_total_payment['sum(amount)']-$total_refund);
						}
					
					$lib = getLibraryDueFee($id);
					
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Lecture Fee Amount :");
						$rep->TextCol(2, 3, "Php ".number_format($lec_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Laboratory Fee Amount :");
						$rep->TextCol(2, 3, "Php ".number_format($lab_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Miscelleneous Fee Amount :");
						$rep->TextCol(2, 3, "Php ".number_format($mis_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 3, "___________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Tuition Fee Amount :");
						$rep->TextCol(2, 3, "Php ".number_format($lec_total+$lab_total+$mis_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Carried Balance :");
						$rep->TextCol(2, 3, "Php ".number_format($credit, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Carried Debit Balance :");
						$rep->TextCol(2, 3, "Php ".number_format($debit, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, $rows['scholarship']."% Discount");
						$rep->TextCol(2, 3, $total_discounted > '0'?"Php ".number_format($total_discounted, 2, ".", ","):"Php 0.00");
						$rep->NewLine();
						$rep->TextCol(0, 3, "___________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(0, 2, "SubTotal :");
						$rep->TextCol(2, 3, "Php ".number_format($sub_total-$total_discounted, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Refunded Amount :");
						$rep->TextCol(2, 3, "Php ".number_format($ref_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Refund Balance :");
						$rep->TextCol(2, 3, "Php ".number_format($total_refund-$ref_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 3, "___________________________________________________________");
						$rep->NewLine(); 
						$rep->TextCol(0, 2, "Total Refund :");
						$rep->TextCol(2, 3, "Php ".number_format($ref_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Payment :");
						$rep->TextCol(2, 3, "Php ".number_format($row_total_payment['sum(amount)'], 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Other Payment :");
						$rep->TextCol(2, 3, "Php ".number_format($o, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 3, "___________________________________________________________");
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Remaining Balance :");
						$rep->TextCol(2, 3, "Php ".number_format($total_rem_bal, 2, ".", ","));
						
						$sql = "SELECT * FROM tbl_student_payment 
						WHERE  is_refund <> 'Y' AND term_id = ".$_REQUEST['trm']." AND student_id = " .$_REQUEST['id'];
    					$result = mysql_query($sql); 
	
						$sqlref = "SELECT * FROM tbl_student_payment 
						WHERE  is_refund = 'Y' AND term_id = ".$_REQUEST['trm']." AND student_id = " .$_REQUEST['id'];
						$resultref = mysql_query($sqlref);
						
						$rep->NewLine(2);
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextColLines(0, 2, "Payments");
						$rep->Font();
						$rep->fontSize -= 1;
						$rep->NewLine();
						
						$rep->TextCol(0, 2, "Term");
						$rep->TextCol(1, 3, "                                   Amount");
						$rep->TextCol(2, 3, "Payment Term");
						$rep->TextCol(3, 4, "Method");
						$rep->TextCol(4, 5, "Remarks");
						$rep->TextCol(5, 6, "Date Created");
						$rep->TextCol(6, 7, "Prepared by");
						$rep->NewLine(2);
						
						 while($row = mysql_fetch_array($result)) 
        				{ 
							$rep->TextCol(0, 2, getSYandTerm($row["term_id"]));
							$rep->TextCol(1, 2, '                                   '.$row['amount']);
							$rep->TextCol(2, 3, getPaymentTerm($row["payment_term"]));
							$rep->TextCol(3, 4, getPaymentMethod($row["payment_method"])=='Cash'?'Cash':'Cheque '.$row["bank"].'('.$row["check_no"].')');
							$rep->TextCol(4, 5, $row['is_bounced']=='Y'?'Bounced Check':'');
							$rep->TextCol(5, 6, date('M d Y h:m:s',$row['date_created']));
							$rep->TextCol(6, 7, getEmployeeFullName($row["created_by"]));
							$rep->NewLine();
						}
						
						if(mysql_num_rows($resultref)>0)
						{
						$rep->NewLine(2);
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextColLines(0, 2, "Refunds");
						$rep->Font();
						$rep->fontSize -= 1;
						$rep->NewLine();
						
						$rep->TextCol(0, 2, "Term");
						$rep->TextCol(1, 3, "                                   Amount");
						$rep->TextCol(2, 3, "Remarks");
						$rep->TextCol(3, 4, "Date Created");
						$rep->TextCol(5, 6, "Prepared by");
						$rep->NewLine(2);
						
						while($rowref = mysql_fetch_array($resultref)) 
        				{ 
							$rep->TextCol(0, 2, getSYandTerm($rowref["term_id"]));
							$rep->TextCol(1, 2, '                                   '.$rowref['amount']);
							$rep->TextCol(2, 3, getPaymentTerm($rowref["payment_term"]));
							$rep->TextColLines(3, 4, getPaymentMethod($rowref["payment_method"])=='Cash'?'Cash':'Cheque '.$row["bank"].'('.$rowref["check_no"].')');
							$rep->TextCol(4, 5, $rowref['is_bounced']=='Y'?'Bounced Check':'');
							$rep->TextColLines(5, 6, date('M d Y h:m:s',$rowref['date_created']));
							$rep->TextCol(6, 7, getEmployeeFullName($row["created_by"]));
							$rep->NewLine();
						}
						
						}
						$sqlsch = "SELECT *
                        FROM tbl_payment_scheme_details
                        WHERE scheme_id = ".$paymentId." ORDER BY sort_order";
                        
					$resultsch = mysql_query($sqlsch);
						
						$rep->NewLine(2);
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextColLines(0, 3, "Schedule of Fees");
						$rep->Font();
						$rep->fontSize -= 1;
						$rep->NewLine();

						$rep->TextCol(0, 1, "Schedule");
						$rep->TextCol(1, 2, "                                         ");
						$rep->TextCol(2, 3, " Paid Balance");
						$rep->TextCol(3, 4, " Balance");
						$rep->NewLine(2);
						
					while($rowsch = @mysql_fetch_array($resultsch)) 
					{
						if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'A')
						{
							$topay = $rowsch['payment_value'];
							$total_fee = $total_fee - $topay;
						}
						else if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'P')
						{
							$topay = abs(getStudentPaymentScheme($rowsch['id'],$total_fee));
						}
						else if($rowsch['payment_type'] == 'P')
						{
							$topay = abs(getStudentPaymentScheme($rowsch['id'],$total_fee));
						}
						
						$rep->NewLine();
						$rep->TextCol(0, 1, $rowsch['payment_name']);
						$rep->TextCol(1, 2, " on/before(".$rowsch['payment_date'].")   - Php".number_format($topay, 2, ".", ","));
						
						if($rowsch['sort_order'] == 1 && ($total_payment >= $topay))
						{
							$bal = 0;
							$paid = $topay;
							$carried = $total_payment - $topay;
							$remarks = 'Paid';
							$order = $rowsch['sort_order'];
							$num_payment = 1+1;
						}
						else if($rowsch['sort_order'] == 1 && $total_payment <= $topay)
						{
							$bal = abs($total_payment - $topay);
							$paid = abs($topay - $bal);
							$carried = $total_payment - $topay;
							$remarks = 'Unpaid';
							$order = $rowsch['sort_order'];
							$num_payment = 1;
						}
						else if($carried > 0 && $carried >= $topay)
						{
							$bal = 0;
							$paid = $topay;
							$carried = $carried - $topay;
							$remarks = 'Paid';
							$order = $rowsch['sort_order'];
							$num_payment = $num_payment+1;
						}
						else if($carried > 0 && $carried < $topay)
						{
							$bal = $topay - $carried;
							$paid = $carried;
							$carried = 0;
							$remarks = 'Unpaid';
							$order = $rowsch['sort_order'];
							$num_payment = $num_payment;
						}
						else
						{
							$bal = $topay;
							$paid = 0;
							$carried = 0;
							$remarks = 'Unpaid';
							$order = $rowsch['sort_order'];
							$num_payment = $num_payment;
						}
						
						$rep->TextCol(2, 3, 'Php '.number_format($paid, 2, ".", ","));
						$rep->TextCol(3, 4, 'Php '.number_format($bal, 2, ".", ","));
					
					}
				}
				else if($_REQUEST['met']=='OR')
				{
					$rep->Image2('../images/mint_logo2.png', 240, 20,100,41);
					$rep->NewLine(3);
					$rep->Font('bold');
					$rep->TextCol(1, 7, "                                                    MINT COLLEGE OF BUSINESS AND ARTS INC.");
					$rep->NewLine();
					$rep->TextCol(1, 7, "                          MERIDIAN INTERNATIONAL COLLEGE OF BUSINESS,ARTS,&TECHNOLOGY");
					$rep->Font();
					$rep->NewLine();
					$rep->fontSize -= 1;
					$rep->TextCol(1, 7, "                                                 2nd Floor Commerce & Industry Plaza, Campus Ave., Cor. Park Ave.");
					$rep->NewLine();
					$rep->TextCol(1, 7, "                                                                         McKinley Hills, Fort Bonifacio, Taguig City");
					$rep->NewLine();
					$rep->TextCol(1, 7, "                                                                                TIN 007-788-868-000 Non VAT");
					$rep->fontSize += 1;
					
					$rep->NewLine(2);
					$rep->fontSize += 3;
					$rep->Font('bold');
					$rep->TextCol(1, 7, "                                                     OFFICIAL RECEIPT");
					$rep->fontSize -= 3;
					$rep->Font();
					
					$rep->NewLine(1.5);
					$rep->TextCol(0, 2, "O.R. Number:______________________");
					$rep->TextCol(1, 7, "                                                 Payment Date:   ".date('F d, Y'));
					$rep->NewLine(1.5);
					$rep->TextCol(0, 2, "Student No.:    ".getStudentNumber($_REQUEST['id']));
					$rep->TextCol(1, 7, "                                                 Name:   ".getStudentFullName($_REQUEST['id']));
					$rep->TextCol(5, 7, "Program:  ".getStudentCourseCode($_REQUEST['id']));
					
					$sql = "SELECT * FROM tbl_student_payment WHERE id=".$_REQUEST['pay_id'];
					$query = mysql_query($sql);
					$row = mysql_fetch_array($query);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(650);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(625);
					
					$rep->NewLine(2);
					$rep->Font('bold');
					$rep->TextCol(0, 2, "PARTICULARS");
					$rep->TextCol(5, 7, "          AMOUNT");
					$rep->Font();
					
					$rep->NewLine(2);
					$chek = checkPaymentIfPartialOrFull($_REQUEST['id'],getTotalPaymentOfStudent($_REQUEST['id'],$row['term_id']));
					$rep->TextCol(1, 5, $chek=='F'?"Full Payment | ":"Partial Payment | ".getSYandTerm($row['term_id']));
					$rep->TextCol(5, 7, "          ".number_format($row['amount'], 2, ".", ","));
					
					$pay = $row['check_no']!=0?number_format($row['amount'], 2, ".", ","):'';
					$cash = $row['check_no']==0?number_format($row['amount'], 2, ".", ","):'';
					
					$rep->NewLine(2);
					$rep->TextCol(1, 5, "                         FORM OF PAYMENT");
					$rep->NewLine();
					$rep->TextCol(1, 5, "CHECK  ".$pay);
					$rep->TextCol(2, 5, "CASH  ".$cash);
					$rep->NewLine();
					$rep->TextCol(1, 5, "BANK  ".$row['bank']);
					$rep->TextCol(2, 5, "CREDIT  ");
					$rep->NewLine();
					$rep->TextCol(1, 5, "CHECK #  ".$row['check_no']);
					$rep->TextCol(2, 5, "CARD  ");
					$rep->NewLine();
					$rep->TextCol(1, 5, "CHECK DATE  ");
					$rep->fontSize -= 3;
					$rep->TextColLines(4, 5, "Vatable:  VAT-Exempt:  Zero-Rated:  Total Sale:  Total Vat:  Amount Due:");
					$rep->fontSize += 3;
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(420);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(475);
					
					$rep->NewLine(2);
					$rep->TextCol(5, 7, "          ______________________");
					$rep->NewLine();
					$rep->TextCol(5, 7, "                 ".getUserFullName(USER_ID));
				}
				else if($_REQUEST['met']=='other_OR')
				{
					$rep->Image2('../images/mint_logo2.png', 240, 20,100,41);
					$rep->NewLine(3);
					$rep->Font('bold');
					$rep->TextCol(1, 7, "                                                    MINT COLLEGE OF BUSINESS AND ARTS INC.");
					$rep->NewLine();
					$rep->TextCol(1, 7, "                          MERIDIAN INTERNATIONAL COLLEGE OF BUSINESS,ARTS,&TECHNOLOGY");
					$rep->Font();
					$rep->NewLine();
					$rep->fontSize -= 1;
					$rep->TextCol(1, 7, "                                                 2nd Floor Commerce & Industry Plaza, Campus Ave., Cor. Park Ave.");
					$rep->NewLine();
					$rep->TextCol(1, 7, "                                                                         McKinley Hills, Fort Bonifacio, Taguig City");
					$rep->NewLine();
					$rep->TextCol(1, 7, "                                                                                TIN 007-788-868-000 Non VAT");
					$rep->fontSize += 1;
					
					$rep->NewLine(2);
					$rep->fontSize += 3;
					$rep->Font('bold');
					$rep->TextCol(1, 7, "                                                     OFFICIAL RECEIPT");
					$rep->fontSize -= 3;
					$rep->Font();
					
					$rep->NewLine(1.5);
					$rep->TextCol(0, 2, "O.R. Number:______________________");
					$rep->TextCol(1, 7, "                                                 Payment Date:   ".date('F d, Y'));
					$rep->NewLine(1.5);
					$rep->TextCol(0, 2, "Student No.:______________________");
					$rep->TextCol(1, 7, "                                                 Name:   ".getOtherFullName($_REQUEST['id']));
					$rep->TextCol(5, 7, "Program:______________________");
					
					$sql = "SELECT * FROM tbl_other_payments WHERE id=".$_REQUEST['id'];
					$query = mysql_query($sql);
					$row = mysql_fetch_array($query);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(650);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(625);
					
					$rep->NewLine(2);
					$rep->Font('bold');
					$rep->TextCol(0, 2, "PARTICULARS");
					$rep->TextCol(5, 7, "          AMOUNT");
					$rep->Font();
					
					$rep->NewLine(2);
					$chek = checkPaymentIfPartialOrFull($_REQUEST['id'],getTotalPaymentOfStudent($_REQUEST['id'],$row['term_id']));
					$rep->TextCol(1, 5, "Full Payment");
					$rep->TextCol(5, 7, "          ".number_format($row['amount'], 2, ".", ","));
					
					$rep->NewLine(5);
					
					$rep->fontSize -= 3;
					$rep->TextColLines(4, 5, "Vatable:  VAT-Exempt:  Zero-Rated:  Total Sale:  Total Vat:  Amount Due:");
					$rep->fontSize += 3;
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(420);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(475);
					
					$rep->NewLine(2);
					$rep->TextCol(5, 7, "          ______________________");
					$rep->NewLine();
					$rep->TextCol(5, 7, "                 ".getUserFullName(USER_ID));
				}
				else if($_REQUEST['met']=='course curriculum')
				{					
					
					$sql_dis = "SELECT * FROM tbl_curriculum WHERE id = ".$_REQUEST['id'];
					$result_dis = mysql_query($sql_dis);
					$row_dis = mysql_fetch_array($result_dis);
					$no_year = $row_dis['no_of_years'];
					$no_term = $row_dis['term_per_year']+1;
					
					//$summer = $no_term+1;
					$sql_subj = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = ".$_REQUEST['id'];
					$query_subj = mysql_query($sql_subj);
					$num = mysql_num_rows($query_subj);
							 
			 		$ctr = 1;
					
					for($ctr_year = 1; $ctr_year<= $no_year; $ctr_year++)
					{
						 for($ctr_terms = 1; $ctr_terms<= $no_term; $ctr_terms++)
        				{
							if($ctr_terms < $no_term)
							{
								$termWord = getSemesterInWord($ctr_terms);	
							}
							else
							{
								$termWord = 'Summer';	
							}
							
						$rep->NewLine(2);
						$rep->fontSize += 1;
						$rep->Font('bold');
						$rep->TextColLines(0, 3, getYearLevel($ctr_year).'('.$termWord.')');
						$rep->Font();
						$rep->fontSize -= 1;
						$rep->NewLine();
						
						
						$rep->TextCol(0, 1, "Subject Code");
						$rep->TextCol(1, 2, "Subject Name");
						$rep->TextCol(2, 3, "Units             ");
						$rep->TextCol(3, 5, "Pre-Requisites             ");
						$rep->TextCol(5, 6, "Co-Requisites             ");
						$rep->NewLine(2);
						  
						$total_units	= 0;
					  	$sql_sub = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = ".$_REQUEST['id']." AND year_level = ".$ctr_year." AND term = ".$ctr_terms." AND subject_category <> 'EO'";
						$query_sub = mysql_query($sql_sub); 
					  	$ctr_row = mysql_num_rows($query_sub);
					  	if($ctr_row > 0)
					  	{
						  while($row = mysql_fetch_array($query_sub))
						  {				
							$rep->TextCol(0, 1, getSubjCode($row['subject_id']));
							$rep->TextCol(1, 2, getSubjName($row['subject_id']));
							$rep->TextCol(2, 3, $row['units']);
							$rep->TextCol(3, 5, getPrereqOfSubject($row['id']));
							$rep->TextCol(5, 6, getCoreqOfSubject($row['id']));
							$rep->NewLine();
							$total_units += $row['units'];
						}
						$rep->TextCol(0, 6, "_________________________________________________________________________________________________________________________________________________________________________________________________");
						$rep->NewLine(1,2);
						$rep->TextColLines(2, 3, $total_units);
						}else{
							$rep->TextCol(0, 6, 'No summer classes.');
							$rep->NewLine(1);
							$rep->TextCol(0, 6, "_________________________________________________________________________________________________________________________________________________________________________________________________");
						}
					}
					}
					}
					else if($_REQUEST['met']=='reserved')
				{
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];	
					$querys = mysql_query($sqls);
				  	$rows = mysql_fetch_array($querys);
					
					$rep->fontSize -= 1;
					$rep->title = 'STUDENT INFO';
					$rep->Blank_Header();
					
					$rep->Image2('../images/mint_logo.png', 5, 10,85,50);
					$rep->NewLine(1);

						$rep->Font('bold');
						$rep->TextCol(0, 3, "ENROLLMENT ASSESSMENT FORM");
						$rep->TextCol(5, 7, "SY ".getSYandTerm($_REQUEST['trm']));
						$rep->Font();
						$rep->NewLine(1.5);
					
						$rep->TextCol(0, 7, "Course : ".getCourseName($rows['course_id']));
						$rep->NewLine();
						
						$rep->TextCol(0, 3, "Student Number : ".$rows['student_number']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Student Name : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);
						
						
						
						
						/*if(isset($_REQUEST['trm']))
						{
						$rep->NewLine();
						$rep->TextCol(0, 1, "School Year : ", -2);
						$rep->TextCol(1, 4, getSYandTerm($_REQUEST['trm']), -2);
						}
						
						$birthday = explode ('-',$rows['birth_date']);		
					$birth_year = $birthday['0'];
					$birth_day = $birthday['1'];
					$birth_month = $birthday['2'];
					
					$rep->NewLine();
					$rep->TextCol(0, 1, "Date of Birth:");
					$rep->TextCol(1, 2, date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year)));
					$rep->TextCol(2, 3, "Sex:");
					$rep->TextCol(3, 4, $rows['gender']);
					$rep->NewLine();
					$rep->TextCol(0, 1, "Department:");
					$rep->TextCol(1, 2, getStudentCollegeName($rows['course_id']));
					$rep->TextCol(2, 3, "Curriculum:");
					$rep->TextCol(3, 4, getCurriculumCode($rows['curriculum_id']));
					$rep->NewLine();
					$rep->TextCol(0, 1, "Address:");
					$rep->TextCol(1, 2, $rows['home_address']);
					$rep->NewLine();*/
				
					$sql = "SELECT * FROM 
							tbl_student_reserve_subject st, tbl_schedule sc
						WHERE
							st.schedule_id = sc.id AND
							st.student_id = ". $_REQUEST['id'] ." AND
							st.term_id= " .$_REQUEST['trm'];
										
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(720);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(695);
					$rep->NewLine(2);
					
					$rep->Font('bold');
					$rep->TextCol(0, 2, "Subject Name");
					$rep->TextCol(2, 2, "Code");
					$rep->TextCol(2, 3, "                        Units");
					$rep->TextCol(3, 4, "   Section");
					$rep->TextCol(4, 7, "    Room Days & Time");
					//$rep->TextCol(5, 8, "                 Days & Time");
					$rep->Font();
					$rep->NewLine(2);
					
					while($row = mysql_fetch_array($result)) 
					{
						$el = $row['elective_of']!=''?'('.getSubjName($row['elective_of']).')':'';	
						$rep->TextCol(0, 2, getSubjName($row["subject_id"]).$el);
						$rep->TextCol(2, 2, getSubjCode($row["subject_id"]));
						$rep->TextCol(2, 3, "                          ".getSubjUnit($row["subject_id"]));
						$rep->TextCol(3, 4, "   ".getSectionNo($row["schedule_id"]));
						$rep->TextColLines(4, 7, "    ".getRoomNo(getSchedRoom($row["schedule_id"]))." - ".getScheduleDays($row["schedule_id"]));
						//$rep->TextColLines(5, 7, "                 ".getScheduleDays($row["schedule_id"]));
						//$rep->NewLine();
						
						$totalU += getSubjUnit($row["subject_id"]);
					}
						
						$rep->TextCol(0, 9, "_____________________________________________________________________________________________________________________________________________________________________");
						$rep->NewLine();
						$rep->Font('bold');
						//$rep->TextCol(2, 3, "       ".$totalU);
						$rep->Font();
						
						$tline = (6+mysql_num_rows($result))*$rep->lineHeight;
						$tl1 = 745-$tline;
						$tl2 = $tl1-25;
						
						/*$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl1);
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl2);*/
						
						$rep->NewLine();
						$rep->Font('bold');
						$rep->TextCol(0, 3, "Tuition Fees and Miscellaneous");
						$rep->NewLine();
						$rep->TextCol(0, 9, "_____________________________________________________________________________________________________________________________________________________________________");
						$rep->NewLine();
						
						/* TOTAL  PAYMENT */
					$sql_fee_other = "SELECT f.fee_type,s.* FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND f.id=s.fee_id AND s.is_removed <> 'Y' AND s.student_id = ".$_REQUEST['id']." AND s.term_id =" .$_REQUEST['trm'];
					
					$result_fee_other = mysql_query($sql_fee_other);
					while($row_fee_other = mysql_fetch_array($result_fee_other))         
					$sub_total1 += $row_fee_other['amount'];
					
					$sql2 = "SELECT * FROM tbl_student_other_fees s,tbl_school_other_fee f WHERE f.id=s.fee_id AND s.is_removed <> 'Y' AND s.student_id=".$_REQUEST['id']." AND s.term_id=".$_REQUEST['trm'];
				$query2 = mysql_query($sql2);
				while($row2 = mysql_fetch_array($query2))         
					$sub_total1 += $row2['amount'];
						
					$sqlschm = "SELECT * FROM tbl_payment_scheme WHERE term_id=".$_REQUEST['trm'];
					$queryschm = mysql_query($sqlschm);
					$space = "     ";
					
						while($rowschm = mysql_fetch_array($queryschm))
						{
							$rep->TextCol(2, 7, $space.$rowschm['name']);
							$space .= "                                                     ";
						}
						
						$rep->Font();
						$rep->NewLine(2);
					
						/*$rep->TextCol(0, 1, "Fee");
						$rep->TextCol(1, 2, "                           Amount");
						$rep->TextCol(2, 3, "Total             ");
						$rep->NewLine(2);*/
						
						/* TOTAL LEC PAYMENT */
					$sql_lec = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlec' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$_REQUEST['id']." AND s.term_id =".$_REQUEST['trm'];
					$qry_lec = mysql_query($sql_lec);
					$row_lec = mysql_fetch_array($qry_lec);
					$sub_lec_total = 0;
					
					$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$_REQUEST['id']);           
					$sub_lec_total += $lec_total;
					
					$sqlschm = "SELECT * FROM tbl_payment_scheme WHERE term_id=".$_REQUEST['trm'];
					$queryschm = mysql_query($sqlschm);
					$space = "     ";
					
					$totalSur = GetSchemeForSurchargePerTerm($_REQUEST['id']);
					$c=0;
					
						$rep->TextCol(0, 3, "Tuition Fee");
						while($rowschm = mysql_fetch_array($queryschm))
						{
							//$totalSur = $rowschm['surcharge'];
							$rep->TextCol(2, 7, $space.number_format($row_lec['amount']+$totalSur[$c], 2, ".", ","));
							$space .= "                                                     ";
							
							$c++;
						}
						$rep->NewLine();
						$rep->Font("bold");
						$rep->TextCol(0, 3, "         x ".$totalU);
					
					$sqlschm = "SELECT * FROM tbl_payment_scheme WHERE term_id=".$_REQUEST['trm'];
					$queryschm = mysql_query($sqlschm);	
					$space = "     ";
					
					$cnt = 1;
					$c=0;
					
					while($rowschm = mysql_fetch_array($queryschm))
					{
						//$totalSur = getStudentReservedUnit($_REQUEST['id'])*$rowschm['surcharge'];
						$totalSurs = $totalSur[$c]*getStudentReservedUnit($_REQUEST['id']);
						
						$sqldis = 'SELECT * FROM tbl_student WHERE id='.$_REQUEST['id'];
						$querydis = mysql_query($sqldis);
						$rowdis = mysql_fetch_array($querydis);
						
						$sqlt = 'SELECT * FROM tbl_school_year_term WHERE id='.$_REQUEST['trm'];
						$queryt = mysql_query($sqlt);
						$rowt = mysql_fetch_array($queryt);
						$t = strtolower($rowt['school_term'])=='summer'?0:5000;
						
						$tot[$cnt] = ($row_lec['amount']*$totalU)+$totalSurs;
						
						if($rowdis['scholarship_type']=='A')
							{
								getStudentNextYearLevel($_REQUEST['id'])==4?$t=0:'';
								$discount1 = ($sub_total1+$tot[$cnt])-$t;
																
								/*if(getStudentCourseId($_REQUEST['id'])==13&&getStudentNextYearLevel($_REQUEST['id'])>1)
								{
									$discount1 = $discount1-6920;
								}*/
								
								$discount[$cnt] = ($discount1*$rowdis['scholarship'])/100;
							}
							
							else
							
							{
								$discount1 = ($tot[$cnt]);
								
								/*if(getStudentCourseId($_REQUEST['id'])==13&&getStudentNextYearLevel($_REQUEST['id'])>1)
								{
									$discount1 = $discount1-6920;
								}*/
								
								$discount[$cnt] = ($discount1*$rowdis['scholarship'])/100;
							}
							
						
						
						$rep->TextCol(2, 7, $space.number_format($tot[$cnt], 2, ".", ","));
						$space .= "                                                     ";
						
						$cnt++;
						$c++;
					}
					$rep->Font();
						$rep->NewLine(2);
						
						$sql = "SELECT f.*,s.* FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND f.id=s.fee_id AND s.is_removed <> 'Y' AND s.student_id = " .$_REQUEST['id']." AND s.term_id=".$_REQUEST['trm'];
                    
            			$result = mysql_query($sql);
						
						$rep->Font('bold');
						$rep->TextColLines(0, 3, "Miscellaneous");
						$rep->Font();
					
						while($row = mysql_fetch_array($result)) 
					{
						$total = $row['amount'];
						$rep->TextCol(0, 2, $row['fee_name']);
						$rep->TextCol(1, 3, "                                          ".$row['amount']);
						//$rep->TextCol(2, 3, $total);
						$rep->NewLine();
						$sub_total += $row['amount'];
						$sub_mis_total += $row['amount'];
					}
					
					 $sql2 = "SELECT * FROM tbl_student_other_fees s,tbl_school_other_fee f WHERE f.id=s.fee_id AND s.is_removed <> 'Y' AND s.student_id=".$_REQUEST['id']." AND s.term_id=".$_REQUEST['trm'];
				$query2 = mysql_query($sql2);
				
					while($row2 = mysql_fetch_array($query2)) 
					{
						$total = $row2['amount'];
						$rep->TextCol(0, 2, $row2['fee_name']);
						$rep->TextCol(1, 3, "                                          ".$row2['amount']);
						//$rep->TextCol(2, 3, $total);
						$rep->NewLine();
						$sub_total += $row2['amount'];
						$sub_mis_total += $row2['amount'];
					}
					
					/* TOTAL OTHER PAYMENT 
					$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id= ".$_REQUEST['trm'];
					$result_fee_other = mysql_query($sql_fee_other);
					$row_fee_other = mysql_fetch_array($result_fee_other);
					$sub_mis_total = 0;
					$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$_REQUEST['id']);           
					$sub_mis_total += $mis_total;*/
					
					
					/* TOTAL LAB PAYMENT */
					$sql_lab = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlab' AND publish =  'Y' AND term_id= ".$_REQUEST['trm'];
					$qry_lab = mysql_query($sql_lab);
					$row_lab = mysql_fetch_array($qry_lab);
					$sub_lab_total = 0;
					
					
					$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$_REQUEST['id']);           
					$sub_lab_total += $lab_total;
					
					/*TOTAL LEC AND LAB = LEC + LAB
					$total_lec_lab =  $tot + $sub_lab_total;
					
					$sub_total += $total_lec_lab;*/
					
					/*$sql_reservation ="SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status ='R' AND student_id =" .$_REQUEST['id'];
					$qry_reservation = mysql_query($sql_reservation);
					$row_reservation = mysql_fetch_array($qry_reservation);
					
						
						$rep->TextCol(0, 2, "Reservation Date : ");
						$rep->TextCol(2, 3, date('F d, Y', $row_reservation['date_reserved']));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Last Day of Payment :");
						$rep->TextCol(2, 3, date('F d, Y', $row_reservation['expiration_date']));
						
						$rep->NewLine(2);
						$rep->TextCol(0, 2, "Total Lecture Fee Amount :");
						$rep->TextCol(2, 3, "Php ".number_format($lec_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Laboratory Fee Amount :");
						$rep->TextCol(2, 3, "Php ".number_format($lab_total, 2, ".", ","));*/
						//$rep->NewLine();
						$sqlnum = "SELECT * FROM tbl_payment_scheme WHERE term_id=".CURRENT_TERM_ID;
						  $querynum = mysql_query($sqlnum);
						  $num = mysql_num_rows($querynum);
						
						$rep->Font("bold");
						$rep->TextCol(0, 2, "Total Miscelleneous ");
						$rep->TextCol(2, 3, number_format($sub_mis_total, 2, ".", ","));
						$rep->TextCol(3, 5, "                      ".number_format($sub_mis_total, 2, ".", ","));
						if($num>2)
						{
						$rep->TextCol(5, 8, "                                   ".number_format($sub_mis_total, 2, ".", ","));
						}
						$rep->NewLine();
						
						
					
					if($rows['scholarship']>0)
					{
					$rep->Font("bold");
						$rep->TextCol(0, 2, $rows['scholarship']."% Discount ");
						$rep->TextCol(2, 3, number_format($discount[1], 2, ".", ","));
						$rep->TextCol(3, 5, "                      ".number_format($discount[2], 2, ".", ","));
						if($num>2)
						{
						$rep->TextCol(5, 8, "                                   ".number_format($discount[3], 2, ".", ","));
						}
						$rep->NewLine();
					}
						
						$rep->TextCol(0, 9, "_____________________________________________________________________________________________________________________________________________________________________");
						$rep->NewLine();
						
						
						$rep->TextCol(0, 2, "Total Payment ");
						
						$space = "     ";
						
						$sqlsch1 = "SELECT *
                        FROM tbl_payment_scheme
                        WHERE term_id = ".$_REQUEST['trm'];
						$resultsch1 = mysql_query($sqlsch1);
						
						$colsch = mysql_num_rows($resultsch1);
						$scId = 1;
						$cnt = 1;
					
					while($rowsch1 = mysql_fetch_array($resultsch1))
					{
						$totalSur = $totalU*$rowsch1['surcharge'];
						
						$sqldis = 'SELECT * FROM tbl_student WHERE id='.$_REQUEST['id'];
						$querydis = mysql_query($sqldis);
						$rowdis = mysql_fetch_array($querydis);
						
						/*if($rowdis['scholarship']!=0)
						{
							if($rowdis['scholarship_type']=='A')
							{
								$discount1 = ($sub_total+$totalSur)-5000);
								$discount1 = ($discount1*$rowdis['scholarship'])/100;
								$tot = ($sub_total+$totalSur)-$discount1;
							}
							else
							{
								$discount2 = $total_lec_lab+$totalSur;
								$discount2 = ($discount2*$rowdis['scholarship'])/100;
								$tot = ($sub_total+$totalSur)-$discount2;
							}
						}else{*/
							$tot1 = $tot[$cnt]+$sub_total1;
						//}
							
							$rep->TextCol(2, 7, $space.number_format($tot1-$discount[$cnt], 2, ".", ","));
							$space .= "                                                     ";
							$cnt++;
					}
						$rep->Font();
						$space = "           ";
						$rep->NewLine();
						
						
					$sqlsch1 = "SELECT *
                        FROM tbl_payment_scheme
                        WHERE term_id = ".$_REQUEST['trm'];
					$resultsch1 = mysql_query($sqlsch1);
					
					$l=2;
					$l2=4;
					
					$cnt1 = 1;
					
					while($rowsch1 = mysql_fetch_array($resultsch1))
					{
						
						//$totalSurs = $totalU*$rowsch1['surcharge'];
						/*$sqldis = 'SELECT * FROM tbl_student WHERE id='.$_REQUEST['id'];
						$querydis = mysql_query($sqldis);
						$rowdis = mysql_fetch_array($querydis);
						
						if($rowdis['scholarship']!=0)
						{
							if($rowdis['scholarship_type']=='A')
							{
								$discountA = ($sub_total)-5000;
								$discountA = ($discountA*$rowdis['scholarship'])/100;
								
								$tot1 = ($sub_total)-$discountA;
							}
							else
							{
								$discountS = $total_lec_lab;
								$discountS = ($discountS*$rowdis['scholarship'])/100;
								
								$tot1 = ($sub_total)-$discountS;
							}	
						}else{
							$subtotal = $sub_total;
						}*/
						$subtotal = ($sub_total1+$tot[$cnt1])-$discount[$cnt1];
						
						//$rep->TextCol(2, 7, number_format($sub_total+$rowsch1['surcharge'], 2, ".", ","));
						//$rep->NewLine();
						
					$sqlsch = "SELECT *
                        FROM tbl_payment_scheme_details
                        WHERE scheme_id = ".$rowsch1['id']." ORDER BY sort_order";
                        
					$resultsch = mysql_query($sqlsch);
					
					$cnt = 1;
						
					while($rowsch = @mysql_fetch_array($resultsch)) 
					{
						if(mysql_num_rows($resultsch)>1)
						{
							if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'A')
							{
								$topay = $rowsch['payment_value'];
								$subtotal = $subtotal - $topay;
							}
							else if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'P')
							{
								$topay = abs(getStudentPaymentScheme($rowsch['id'],$subtotal));
							}
							else if($rowsch['payment_type'] == 'P')
							{
								$topay = abs(getStudentPaymentScheme($rowsch['id'],$subtotal));
							}
							
							if($rowsch['sort_order'] == 1)
							{
								$name = 'DP             ';
								$date = '';
							}else{
								$name = '';
								$date = $rowsch['payment_date'];
							}
							
							$line[$cnt1][$cnt] = $space.$name.$date."   ".number_format($topay, 2, ".", ",");
							//$rep->TextColLines($l, 7, $space.$name.$date."   ".number_format($topay, 2, ".", ","));
							
							$cnt++;
						}
					}
						//$rep->NewLine();
						$l++;
						$l2+=2;
						$space .= "               ";
						
						
						$cnt1++;
						
					}
						$rep->TextCol(3, 7, $line[2][1]);
						$rep->TextCol(5, 8, $line[3][1]);
						$rep->NewLine();
						$rep->TextCol(3, 7, $line[2][2]);
						$rep->TextCol(5, 8, $line[3][2]);
						$rep->NewLine();
						$rep->TextCol(3, 7, $line[2][3]);
						$rep->TextCol(5, 8, $line[3][3]);
						$rep->NewLine();
						$rep->TextCol(3, 7, $line[2][4]);
						$rep->TextCol(5, 8, $line[3][4]);
						$rep->NewLine();
						$rep->TextCol(3, 7, $line[2][5]);
						$rep->TextCol(5, 8, $line[3][5]);
						//$rep->NewLine();
						
						/*$rep->NewLine();
						$tline = (15+mysql_num_rows($result))*$rep->lineHeight;
						$tl12 = ($tl1-5)-$tline;
						$tl22 = $tl12-85;
						
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl12);
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl22);*/
						
						$rep->fontSize -= 1;
						$rep->Font("bold");
						$rep->TextCol(0, 7, "Payment Details:");
						$rep->Font();
						$rep->NewLine();
						$rep->TextCol(0, 7, "Please make check payable to:");
						$rep->NewLine();
						$rep->Font("bold");
						$rep->TextCol(0, 7, "         MERIDIAN INTERNATIONAL COLLEGE");
						$rep->Font();
						$rep->NewLine();
						$rep->TextCol(0, 7, "For bank deposit, please fax bank receipt copy with student's name to 403-8676");
						$rep->NewLine();
						$rep->TextCol(0, 7, "         Account Name:Meridian International College of Business and Arts Inc.");
						$rep->NewLine();
						$rep->TextCol(0, 7, "         BDO Mckinley Branch                             Current Acct. No. 6968-0009-64");
						$rep->NewLine();
						$rep->TextCol(0, 7, "         BPI Bonifacio Global City Branch            Current Acct. No. 1921-1156-32");
						$rep->fontSize += 1;
						
						$rep->NewLine(3);
						$rep->TextCol(0, 7, "Prepared by:    MARLON N. MICUA                                                                         Noted by:    Carla Jerremia B. Alona");
						$rep->NewLine();
						$rep->TextCol(0, 7, "                           Network Administrator                                                                                                        Registrar");
						$rep->fontSize += 1;
				}
				else if($_REQUEST['met']=='reserved2')
				{
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];	
					$querys = mysql_query($sqls);
				  	$rows = mysql_fetch_array($querys);
					
					$rep->fontSize -= 1;
					$rep->title = 'STUDENT INFO';
					$rep->Blank_Header();
					
					$rep->Image2('../images/mint_logo.png', 5, 10,85,50);
					$rep->NewLine(1);

						$rep->Font('bold');
						$rep->TextCol(0, 3, "ENROLLMENT ASSESSMENT FORM (LIST OF SUBJECTS)");
						$rep->TextCol(5, 7, "SY ".getSYandTerm($_REQUEST['trm']));
						$rep->Font();
						$rep->NewLine(1.5);
					
						$rep->TextCol(0, 7, "Course : ".getCourseName($rows['course_id']));
						$rep->NewLine();
						
						$rep->TextCol(0, 3, "Student Number : ".$rows['student_number']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Student Name : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);
						
						
						
						
						/*if(isset($_REQUEST['trm']))
						{
						$rep->NewLine();
						$rep->TextCol(0, 1, "School Year : ", -2);
						$rep->TextCol(1, 4, getSYandTerm($_REQUEST['trm']), -2);
						}
						
						$birthday = explode ('-',$rows['birth_date']);		
					$birth_year = $birthday['0'];
					$birth_day = $birthday['1'];
					$birth_month = $birthday['2'];
					
					$rep->NewLine();
					$rep->TextCol(0, 1, "Date of Birth:");
					$rep->TextCol(1, 2, date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year)));
					$rep->TextCol(2, 3, "Sex:");
					$rep->TextCol(3, 4, $rows['gender']);
					$rep->NewLine();
					$rep->TextCol(0, 1, "Department:");
					$rep->TextCol(1, 2, getStudentCollegeName($rows['course_id']));
					$rep->TextCol(2, 3, "Curriculum:");
					$rep->TextCol(3, 4, getCurriculumCode($rows['curriculum_id']));
					$rep->NewLine();
					$rep->TextCol(0, 1, "Address:");
					$rep->TextCol(1, 2, $rows['home_address']);
					$rep->NewLine();*/
				
					$sql = "SELECT * FROM 
							tbl_student_reserve_subject st, tbl_schedule sc
						WHERE
							st.schedule_id = sc.id AND
							st.student_id = ". $_REQUEST['id'] ." AND
							st.term_id= " .$_REQUEST['trm'];
										
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(720);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(695);
					$rep->NewLine(2);
					
					$rep->Font('bold');
					$rep->TextCol(0, 2, "Subject Name");
					$rep->TextCol(2, 2, "Code");
					$rep->TextCol(2, 3, "                        Units");
					//$rep->TextCol(3, 4, "   Section");
					//$rep->TextCol(4, 7, "    Room Days & Time");
					//$rep->TextCol(5, 8, "                 Days & Time");
					$rep->Font();
					$rep->NewLine(2);
					
					while($row = mysql_fetch_array($result)) 
					{
						$el = $row['elective_of']!=''?'('.getSubjName($row['elective_of']).')':'';	
						$rep->TextCol(0, 2, getSubjName($row["subject_id"]).$el);
						$rep->TextCol(2, 2, getSubjCode($row["subject_id"]));
						$rep->TextCol(2, 3, "                          ".getSubjUnit($row["subject_id"]));
						//$rep->TextCol(3, 4, "   ".getSectionNo($row["schedule_id"]));
						//$rep->TextColLines(4, 7, "    ".getRoomNo(getSchedRoom($row["schedule_id"]))." - ".getScheduleDays($row["schedule_id"]));
						//$rep->TextColLines(5, 7, "                 ".getScheduleDays($row["schedule_id"]));
						$rep->NewLine();
						
						$totalU += getSubjUnit($row["subject_id"]);
					}
						
						$rep->TextCol(0, 9, "_____________________________________________________________________________________________________________________________________________________________________");
						$rep->NewLine();
						$rep->Font('bold');
						$rep->TextCol(2, 3, "                          ".$totalU);
						$rep->Font();
						
						$tline = (6+mysql_num_rows($result))*$rep->lineHeight;
						$tl1 = 745-$tline;
						$tl2 = $tl1-25;
						
						/*$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl1);
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl2);*/
					
					
						$rep->NewLine(6);
						$rep->TextCol(0, 7, "Prepared by:    MARLON N. MICUA                                                                        Noted by:    Carla Jerremia B. Alona");
						$rep->NewLine();
						$rep->TextCol(0, 7, "                           Network Administrator                                                                                                         Registrar");
						$rep->fontSize += 1;
				}
				else if($_REQUEST['met']=='reserved3')
				{
					$sqls = "SELECT * FROM tbl_student WHERE id = ".$_REQUEST['id'];	
					$querys = mysql_query($sqls);
				  	$rows = mysql_fetch_array($querys);
					
					$rep->fontSize -= 1;
					$rep->title = 'STUDENT INFO';
					$rep->Blank_Header();
					
					$rep->Image2('../images/mint_logo.png', 5, 10,85,50);
					$rep->NewLine(1);

						$rep->Font('bold');
						$rep->TextCol(0, 3, "ENROLLMENT ASSESSMENT FORM (ASSESSMENT & TUITION FEES)");
						$rep->TextCol(5, 7, "SY ".getSYandTerm($_REQUEST['trm']));
						$rep->Font();
						$rep->NewLine(1.5);
					
						$rep->TextCol(0, 7, "Course : ".getCourseName($rows['course_id']));
						$rep->NewLine();
						
						$rep->TextCol(0, 3, "Student Number : ".$rows['student_number']);
						
						$rep->NewLine();
						$rep->TextCol(0, 3, "Student Name : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);
						
						
						
						
						/*if(isset($_REQUEST['trm']))
						{
						$rep->NewLine();
						$rep->TextCol(0, 1, "School Year : ", -2);
						$rep->TextCol(1, 4, getSYandTerm($_REQUEST['trm']), -2);
						}
						
						$birthday = explode ('-',$rows['birth_date']);		
					$birth_year = $birthday['0'];
					$birth_day = $birthday['1'];
					$birth_month = $birthday['2'];
					
					$rep->NewLine();
					$rep->TextCol(0, 1, "Date of Birth:");
					$rep->TextCol(1, 2, date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year)));
					$rep->TextCol(2, 3, "Sex:");
					$rep->TextCol(3, 4, $rows['gender']);
					$rep->NewLine();
					$rep->TextCol(0, 1, "Department:");
					$rep->TextCol(1, 2, getStudentCollegeName($rows['course_id']));
					$rep->TextCol(2, 3, "Curriculum:");
					$rep->TextCol(3, 4, getCurriculumCode($rows['curriculum_id']));
					$rep->NewLine();
					$rep->TextCol(0, 1, "Address:");
					$rep->TextCol(1, 2, $rows['home_address']);
					$rep->NewLine();*/
				
					$sql = "SELECT * FROM 
							tbl_student_reserve_subject st, tbl_schedule sc
						WHERE
							st.schedule_id = sc.id AND
							st.student_id = ". $_REQUEST['id'] ." AND
							st.term_id= " .$_REQUEST['trm'];
										
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(720);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(695);
					$rep->NewLine(2);
					
					$rep->Font('bold');
					$rep->TextCol(0, 2, "Subject Name");
					$rep->TextCol(2, 2, "Code");
					$rep->TextCol(2, 3, "                        Units");
					//$rep->TextCol(3, 4, "   Section");
					//$rep->TextCol(4, 7, "    Room Days & Time");
					//$rep->TextCol(5, 8, "                 Days & Time");
					$rep->Font();
					$rep->NewLine(2);
					
					while($row = mysql_fetch_array($result)) 
					{
						$el = $row['elective_of']!=''?'('.getSubjName($row['elective_of']).')':'';	
						$rep->TextCol(0, 2, getSubjName($row["subject_id"]).$el);
						$rep->TextCol(2, 2, getSubjCode($row["subject_id"]));
						$rep->TextCol(2, 3, "                          ".getSubjUnit($row["subject_id"]));
						//$rep->TextCol(3, 4, "   ".getSectionNo($row["schedule_id"]));
						//$rep->TextColLines(4, 7, "    ".getRoomNo(getSchedRoom($row["schedule_id"]))." - ".getScheduleDays($row["schedule_id"]));
						//$rep->TextColLines(5, 7, "                 ".getScheduleDays($row["schedule_id"]));
						$rep->NewLine();
						
						$totalU += getSubjUnit($row["subject_id"]);
					}
						
						$rep->TextCol(0, 9, "_____________________________________________________________________________________________________________________________________________________________________");
						$rep->NewLine();
						$rep->Font('bold');
						//$rep->TextCol(2, 3, "       ".$totalU);
						$rep->Font();
						
						$tline = (6+mysql_num_rows($result))*$rep->lineHeight;
						$tl1 = 745-$tline;
						$tl2 = $tl1-25;
						
						/*$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl1);
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl2);*/
						
						$rep->NewLine();
						$rep->Font('bold');
						$rep->TextCol(0, 3, "Tuition Fees and Miscellaneous");
						$rep->NewLine();
						$rep->TextCol(0, 9, "_____________________________________________________________________________________________________________________________________________________________________");
						$rep->NewLine();
						
						/* TOTAL  PAYMENT */
					$sql_fee_other = "SELECT f.fee_type,s.* FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND f.id=s.fee_id AND s.is_removed <> 'Y' AND s.student_id = ".$_REQUEST['id']." AND s.term_id =" .$_REQUEST['trm'];
					
					$result_fee_other = mysql_query($sql_fee_other);
					while($row_fee_other = mysql_fetch_array($result_fee_other))         
					$sub_total1 += $row_fee_other['amount'];
					
					$sql2 = "SELECT * FROM tbl_student_other_fees s,tbl_school_other_fee f WHERE f.id=s.fee_id AND s.is_removed <> 'Y' AND s.student_id=".$_REQUEST['id']." AND s.term_id=".$_REQUEST['trm'];
				$query2 = mysql_query($sql2);
				while($row2 = mysql_fetch_array($query2))         
					$sub_total1 += $row2['amount'];
						
					/*$sqlschm = "SELECT * FROM tbl_payment_scheme WHERE term_id=".$_REQUEST['trm'];
					$queryschm = mysql_query($sqlschm);
					$space = "     ";
					
						while($rowschm = mysql_fetch_array($queryschm))
						{
							$rep->TextCol(2, 7, $space.$rowschm['name']);
							$space .= "                                                     ";
						}
						
						$rep->Font();
						$rep->NewLine(2);
					
						$rep->TextCol(0, 1, "Fee");
						$rep->TextCol(1, 2, "                           Amount");
						$rep->TextCol(2, 3, "Total             ");
						$rep->NewLine(2);*/
						
						/* TOTAL LEC PAYMENT */
					$sql_lec = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlec' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$_REQUEST['id']." AND s.term_id =".$_REQUEST['trm'];
					$qry_lec = mysql_query($sql_lec);
					$row_lec = mysql_fetch_array($qry_lec);
					$sub_lec_total = 0;
					
					$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$_REQUEST['id']);           
					$sub_lec_total += $lec_total;
					
					$sqlschm2 = "SELECT * FROM tbl_student_enrollment_status WHERE term_id=".$_REQUEST['trm']." AND student_id=".$_REQUEST['id'];
					$queryschm2 = mysql_query($sqlschm2);
					$rowschm2 = mysql_fetch_array($queryschm2);
					
					$sqlschm = "SELECT * FROM tbl_payment_scheme WHERE id=".$rowschm2['scheme_id'];
					$queryschm = mysql_query($sqlschm);
					$rowschm3 = mysql_fetch_array($queryschm);
					$space = "     ";
					
					$rep->TextCol(2, 7, $space.$rowschm3['name']);
					$rep->NewLine();
					
					//$totalSur = GetSchemeForSurchargePerTerm($_REQUEST['id']);
					$totalSur = GetSchemeForSurcharge2($_REQUEST['id'],$_REQUEST['trm']);
					$c=0;
					
						$rep->TextCol(0, 3, "Tuition Fee");
						//while($rowschm = mysql_fetch_array($queryschm))
						//{
							//$totalSur = $rowschm['surcharge'];
							//$rep->TextCol(2, 7, $space.number_format($row_lec['amount']+$totalSur[$c], 2, ".", ","));
							$rep->TextCol(2, 7, $space.number_format($row_lec['amount']+$rowschm3['surcharge'], 2, ".", ","));
							$space .= "                                                     ";
							
							$c++;
						//}
						$rep->NewLine();
						$rep->Font("bold");
						$rep->TextCol(0, 3, "         x ".$totalU);
						
						if($rowschm2['scheme_id'])
						{
								
						}
					
					$sqlschm = "SELECT * FROM tbl_payment_scheme WHERE id=".$rowschm2['scheme_id'];
					$queryschm = mysql_query($sqlschm);	
					$space = "     ";
					
					$cnt = 1;
					$c=0;
					
					while($rowschm = mysql_fetch_array($queryschm))
					{
						//$totalSur = getStudentReservedUnit($_REQUEST['id'])*$rowschm['surcharge'];
						$totalSurs = $rowschm3['surcharge']*getStudentReservedUnit($_REQUEST['id']);
						
						$sqldis = 'SELECT * FROM tbl_student WHERE id='.$_REQUEST['id'];
						$querydis = mysql_query($sqldis);
						$rowdis = mysql_fetch_array($querydis);
						
						$sqlt = 'SELECT * FROM tbl_school_year_term WHERE id='.$_REQUEST['trm'];
						$queryt = mysql_query($sqlt);
						$rowt = mysql_fetch_array($queryt);
						$t = strtolower($rowt['school_term'])=='summer'?0:5000;
						
						$tot[$cnt] = ($row_lec['amount']*$totalU)+$totalSurs;
						
						if($rowdis['scholarship_type']=='A')
							{
								getStudentNextYearLevel($_REQUEST['id'])>3?$t=0:'';
								$discount1 = ($sub_total1+$tot[$cnt])-$t;
																
								/*if(getStudentCourseId($_REQUEST['id'])==13&&getStudentNextYearLevel($_REQUEST['id'])>1)
								{
									$discount1 = $discount1-6920;
								}*/
								
								$discount[$cnt] = ($discount1*$rowdis['scholarship'])/100;
							}
							
							else
							
							{
								$discount1 = ($tot[$cnt]);
								
								/*if(getStudentCourseId($_REQUEST['id'])==13&&getStudentNextYearLevel($_REQUEST['id'])>1)
								{
									$discount1 = $discount1-6920;
								}*/
								
								$discount[$cnt] = ($discount1*$rowdis['scholarship'])/100;
							}
							
						
						
						$rep->TextCol(2, 7, $space.number_format($tot[$cnt], 2, ".", ","));
						$space .= "                                                     ";
						
						$cnt++;
						$c++;
					}
					$rep->Font();
						$rep->NewLine(2);
						
						$sql = "SELECT f.*,s.* FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND f.id=s.fee_id AND s.is_removed <> 'Y' AND s.student_id = " .$_REQUEST['id']." AND s.term_id=".$_REQUEST['trm'];
                    
            			$result = mysql_query($sql);
						
						$rep->Font('bold');
						$rep->TextColLines(0, 3, "Miscellaneous");
						$rep->Font();
					
						while($row = mysql_fetch_array($result)) 
					{
						$total = $row['amount'];
						$rep->TextCol(0, 2, $row['fee_name']);
						$rep->TextCol(1, 3, "                                          ".$row['amount']);
						//$rep->TextCol(2, 3, $total);
						$rep->NewLine();
						$sub_total += $row['amount'];
						$sub_mis_total += $row['amount'];
					}
					
					 $sql2 = "SELECT * FROM tbl_student_other_fees s,tbl_school_other_fee f WHERE f.id=s.fee_id AND s.is_removed <> 'Y' AND s.student_id=".$_REQUEST['id']." AND s.term_id=".$_REQUEST['trm'];
				$query2 = mysql_query($sql2);
				
					while($row2 = mysql_fetch_array($query2)) 
					{
						$total = $row2['amount'];
						$rep->TextCol(0, 2, $row2['fee_name']);
						$rep->TextCol(1, 3, "                                          ".$row2['amount']);
						//$rep->TextCol(2, 3, $total);
						$rep->NewLine();
						$sub_total += $row2['amount'];
						$sub_mis_total += $row2['amount'];
					}
					
					/* TOTAL OTHER PAYMENT 
					$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id= ".$_REQUEST['trm'];
					$result_fee_other = mysql_query($sql_fee_other);
					$row_fee_other = mysql_fetch_array($result_fee_other);
					$sub_mis_total = 0;
					$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$_REQUEST['id']);           
					$sub_mis_total += $mis_total;*/
					
					
					/* TOTAL LAB PAYMENT */
					$sql_lab = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlab' AND publish =  'Y' AND term_id= ".$_REQUEST['trm'];
					$qry_lab = mysql_query($sql_lab);
					$row_lab = mysql_fetch_array($qry_lab);
					$sub_lab_total = 0;
					
					
					$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$_REQUEST['id']);           
					$sub_lab_total += $lab_total;
					
					/*TOTAL LEC AND LAB = LEC + LAB
					$total_lec_lab =  $tot + $sub_lab_total;
					
					$sub_total += $total_lec_lab;*/
					
					/*$sql_reservation ="SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status ='R' AND student_id =" .$_REQUEST['id'];
					$qry_reservation = mysql_query($sql_reservation);
					$row_reservation = mysql_fetch_array($qry_reservation);
					
						
						$rep->TextCol(0, 2, "Reservation Date : ");
						$rep->TextCol(2, 3, date('F d, Y', $row_reservation['date_reserved']));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Last Day of Payment :");
						$rep->TextCol(2, 3, date('F d, Y', $row_reservation['expiration_date']));
						
						$rep->NewLine(2);
						$rep->TextCol(0, 2, "Total Lecture Fee Amount :");
						$rep->TextCol(2, 3, "Php ".number_format($lec_total, 2, ".", ","));
						$rep->NewLine();
						$rep->TextCol(0, 2, "Total Laboratory Fee Amount :");
						$rep->TextCol(2, 3, "Php ".number_format($lab_total, 2, ".", ","));
						//$rep->NewLine();
						$sqlnum = "SELECT * FROM tbl_payment_scheme WHERE term_id=".CURRENT_TERM_ID;
						  $querynum = mysql_query($sqlnum);
						  $num = mysql_num_rows($querynum);*/
						
						$rep->Font("bold");
						$rep->TextCol(0, 2, "Total Miscelleneous ");
						$rep->TextCol(2, 3, number_format($sub_mis_total, 2, ".", ","));
						/*$rep->TextCol(3, 5, "                      ".number_format($sub_mis_total, 2, ".", ","));
						if($num>2)
						{
						$rep->TextCol(5, 8, "                                   ".number_format($sub_mis_total, 2, ".", ","));
						}*/
						$rep->NewLine();
						
						
					
					if($rows['scholarship']>0)
					{
					$rep->Font("bold");
						$rep->TextCol(0, 2, $rows['scholarship']."% Scholarship ");
						$rep->TextCol(2, 3, number_format($discount[1], 2, ".", ","));
						/*$rep->TextCol(3, 5, "                      ".number_format($discount[2], 2, ".", ","));
						if($num>2)
						{
						$rep->TextCol(5, 8, "                                   ".number_format($discount[3], 2, ".", ","));
						}*/
						$rep->NewLine();
					}
						
						$rep->TextCol(0, 9, "_____________________________________________________________________________________________________________________________________________________________________");
						$rep->NewLine();
						
						
						$rep->TextCol(0, 2, "Total Payment ");
						
						$space = "     ";
						
						$sqlsch1 = "SELECT *
                        FROM tbl_payment_scheme
                        WHERE id = ".$rowschm2['scheme_id'];
						$resultsch1 = mysql_query($sqlsch1);
						
						$colsch = mysql_num_rows($resultsch1);
						$scId = 1;
						$cnt = 1;
					
					while($rowsch1 = mysql_fetch_array($resultsch1))
					{
						$totalSur = $totalU*$rowsch1['surcharge'];
						
						$sqldis = 'SELECT * FROM tbl_student WHERE id='.$_REQUEST['id'];
						$querydis = mysql_query($sqldis);
						$rowdis = mysql_fetch_array($querydis);
						
						/*if($rowdis['scholarship']!=0)
						{
							if($rowdis['scholarship_type']=='A')
							{
								$discount1 = ($sub_total+$totalSur)-5000);
								$discount1 = ($discount1*$rowdis['scholarship'])/100;
								$tot = ($sub_total+$totalSur)-$discount1;
							}
							else
							{
								$discount2 = $total_lec_lab+$totalSur;
								$discount2 = ($discount2*$rowdis['scholarship'])/100;
								$tot = ($sub_total+$totalSur)-$discount2;
							}
						}else{*/
							$tot1 = $tot[$cnt]+$sub_total1;
						//}
							
							$rep->TextCol(2, 7, $space.number_format($tot1-$discount[$cnt], 2, ".", ","));
							$space .= "                                                     ";
							$cnt++;
					}
						$rep->Font();
						$space = "           ";
						$rep->NewLine();
						
						
					$sqlsch1 = "SELECT *
                        FROM tbl_payment_scheme
                        WHERE id = ".$rowschm2['scheme_id'];
					$resultsch1 = mysql_query($sqlsch1);
					
					$l=2;
					$l2=4;
					
					$cnt1 = 1;
					
					while($rowsch1 = mysql_fetch_array($resultsch1))
					{
						
						
						$subtotal = ($sub_total1+$tot[$cnt1])-$discount[$cnt1];
						
					$sqlsch = "SELECT *
                        FROM tbl_payment_scheme_details
                        WHERE scheme_id = ".$rowschm2['scheme_id']." ORDER BY sort_order";
                        
					$resultsch = mysql_query($sqlsch);
					
					$cnt = 1;
						
					while($rowsch = @mysql_fetch_array($resultsch)) 
					{
						if(mysql_num_rows($resultsch)>1)
						{
							if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'A')
							{
								$topay = $rowsch['payment_value'];
								$subtotal = $subtotal - $topay;
							}
							else if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'P')
							{
								$topay = abs(getStudentPaymentScheme($rowsch['id'],$subtotal));
							}
							else if($rowsch['payment_type'] == 'P')
							{
								$topay = abs(getStudentPaymentScheme($rowsch['id'],$subtotal));
							}
							
							if($rowsch['sort_order'] == 1)
							{
								$name = 'DP             ';
								$date = '';
							}else{
								$name = '';
								$date = $rowsch['payment_date'];
							}
							
							//$line[$cnt1][$cnt] = $space.$name.$date."   ".number_format($topay, 2, ".", ",");
							//$rep->TextColLines($l, 7, $space.$name.$date."   ".number_format($topay, 2, ".", ","));
							$rep->TextColLines($l, 7, $space.$name.$date."   ".number_format($topay, 2, ".", ","));
							
							$cnt++;
						}
					}
						$rep->NewLine();
						$l++;
						$l2+=2;
						$space .= "               ";
						
						
						$cnt1++;
						
					}
						/*$rep->TextCol(3, 7, $line[2][1]);
						$rep->TextCol(5, 8, $line[3][1]);
						$rep->NewLine();
						$rep->TextCol(3, 7, $line[2][2]);
						$rep->TextCol(5, 8, $line[3][2]);
						$rep->NewLine();
						$rep->TextCol(3, 7, $line[2][3]);
						$rep->TextCol(5, 8, $line[3][3]);
						$rep->NewLine();
						$rep->TextCol(3, 7, $line[2][4]);
						$rep->TextCol(5, 8, $line[3][4]);
						$rep->NewLine();
						$rep->TextCol(3, 7, $line[2][5]);
						$rep->TextCol(5, 8, $line[3][5]);
						//$rep->NewLine();
						
						$rep->NewLine();
						$tline = (15+mysql_num_rows($result))*$rep->lineHeight;
						$tl12 = ($tl1-5)-$tline;
						$tl22 = $tl12-85;
						
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl12);
						$rep->SetDrawColor(205, 205, 205);
						$rep->Line($tl22);*/
						
						$rep->fontSize -= 1;
						$rep->Font("bold");
						$rep->TextCol(0, 7, "Payment Details:");
						$rep->Font();
						$rep->NewLine();
						$rep->TextCol(0, 7, "Please make check payable to:");
						$rep->NewLine();
						$rep->Font("bold");
						$rep->TextCol(0, 7, "         MERIDIAN INTERNATIONAL COLLEGE");
						$rep->Font();
						$rep->NewLine();
						$rep->TextCol(0, 7, "For bank deposit, please fax bank receipt copy with student's name to 403-8676");
						$rep->NewLine();
						$rep->TextCol(0, 7, "         Account Name:Meridian International College of Business and Arts Inc.");
						$rep->NewLine();
						$rep->TextCol(0, 7, "         Security Bank                              Current Acct. No. 0000-002918-585");
						$rep->NewLine();
						$rep->TextCol(0, 7, "         BPI Bonifacio Global City Branch            Current Acct. No. 1921-1156-32");
						$rep->fontSize += 1;
						
						$rep->NewLine(2);
						$rep->TextCol(0, 7, "Prepared by:    MARLON N. MICUA");                                                                         //Noted by:    Carla Jerremia B. Alona");
						$rep->NewLine();
						$rep->TextCol(0, 7, "                           Programmer");                                                                                                       // Registrar");
						$rep->fontSize += 1;
				}
			}
			else
			{
				$sqls = "SELECT * FROM tbl_employee WHERE id = ".$_REQUEST['id'];						
				$querys = mysql_query($sqls);
				$rows = mysql_fetch_array($querys);
				
				$rep->title = 'EMPLOYEE INFO';
				$rep->Header();
			
				$rep->TextCol(0, 3, "Employee Name : ".$rows['lastname'].' , '.$rows['firstname'].' '.$rows['middlename']);
				//$rep->TextCol(1, 4, , -2);
				$rep->NewLine();
				$rep->TextCol(0, 3, "Employee Number : ".$rows['emp_id_number']);
				//$rep->TextCol(2, 4, , -2);
				$rep->NewLine();
				$rep->TextCol(0, 1, "Department : ", -2);
				$rep->TextCol(1, 4, getDeptName($rows['department_id']), -2);
				if(isset($_REQUEST['trm']))
				{
				$rep->NewLine();
				$rep->TextCol(0, 1, "School Year : ", -2);
				$rep->TextCol(1, 4, getSYandTerm($_REQUEST['trm']), -2);
				}
				$rep->NewLine();
				$rep->NewLine();
				
				if($_REQUEST['met']=='schedule')
				{
					$sql = "SELECT * FROM tbl_schedule 
					WHERE employee_id  =  " . $_REQUEST['id'] . 
					" AND term_id = " . $_REQUEST['trm'];	
										
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(675);
					$rep->SetDrawColor(205, 205, 205);
					$rep->Line(650);
					$rep->NewLine();
					$rep->NewLine();
					
					$rep->TextCol(0, 1, "Code");
					$rep->TextCol(1, 1, "Units");
					$rep->TextCol(1, 3, "               Schedule");
					$rep->TextCol(2, 4, "     Section");
					$rep->TextCol(4, 7, "No. of Students");

					$rep->NewLine();
					$rep->NewLine();
					
					while($row = mysql_fetch_array($result)) 
					{
						$rep->TextCol(0, 1, getSubjCode($row["subject_id"]));
						$rep->TextCol(1, 1, getSubjUnit($row["subject_id"]));
						$rep->TextCol(1, 3, "               ".getScheduleDays($row["id"]).'             ');
						$rep->TextCol(2, 4, "     ".$row["section_no"]);
						$rep->TextCol(4, 7, getNumberScheduleEnrolled($row["id"],$_REQUEST['trm']));
						$rep->NewLine();
					}
				}
				
			}
				
				
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