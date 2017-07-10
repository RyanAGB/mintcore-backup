<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2013 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.9, 2013-06-02
 */

/** Error reporting 
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);*/
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once '../Classes/PHPExcel.php';
include_once("../../config.php");
include_once("../../includes/functions.php");
include_once("../../includes/common.php");


// Create new PHPExcel object
//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
//echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("MINT")
							 ->setLastModifiedBy("MINT")
							 ->setTitle("MINT REPORT")
							 ->setSubject("MINT REPORT")
							 ->setDescription("MINT REPORT")
							 ->setKeywords("MINT REPORT")
							 ->setCategory("MINT REPORT");

if($_REQUEST['met']=='sched_summary')
{
		// Add some data
		//echo date('H:i:s') , " Add some data" , EOL;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Subject Code')
					->setCellValue('B1', 'Subject Name')
					->setCellValue('C1', 'Class 1')
					->setCellValue('D1', 'Class 2')
					->setCellValue('E1', 'Class 3')
					->setCellValue('F1', 'Class 4')
					->setCellValue('G1', 'Class 5')
					->setCellValue('H1', 'Class 6')
					->setCellValue('I1', 'Class 7')
					->setCellValue('J1', 'Total Students')
					->setCellValue('K1', 'Total Class')
					->setCellValue('L1', 'Average');
		$arcell = array("C","D","E","F","G","H","I");
							$sqldet = "SELECT DISTINCT subject_id FROM tbl_schedule WHERE elective_of IS NULL AND number_of_reserved>0 AND term_id = ".$_REQUEST['trm'];
							$resultdet = mysql_query($sqldet);
				$x=2;
			while($row = mysql_fetch_array($resultdet))
			{	
			//$el = $row['elective_of']!=''?'('.getSubjName($row['elective_of']).')':'';
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, getSubjCode($row['subject_id']))
					->setCellValue('B'.$x, getSubjName($row['subject_id']).$el);
		
				$sql2 = 'SELECT * FROM tbl_schedule where elective_of='.$row['subject_id'].' AND term_id='.$_REQUEST['trm'];
				$query2 = mysql_query($sql2);
				$totalstud2=0;
				while($row3=mysql_fetch_array($query2))
				{
					$sqlcount2 = 'SELECT * FROM tbl_student_schedule WHERE schedule_id='.$row3['id'].' AND elective_of= '.$row['subject_id'].' AND term_id='.$_REQUEST['trm'];
					$querycount2 = mysql_query($sqlcount2);
					$totalstud2+=mysql_num_rows($querycount2);
				}
				
				$sql = 'SELECT * FROM tbl_schedule where subject_id='.$row['subject_id'].' AND term_id='.$_REQUEST['trm'];
				$query = mysql_query($sql);	
				$c=0;
				$totalstud=0;
				
				while($row2 = mysql_fetch_array($query))
				{
					$sqlcount = 'SELECT * FROM tbl_student_schedule WHERE schedule_id='.$row2['id'].' AND term_id='.$_REQUEST['trm'];
					$querycount = mysql_query($sqlcount);
					
					if((mysql_num_rows($querycount)+$totalstud2)>0)
					{
						if($c==0)
						{$a='C';}else if($c==1){$a='D';}else if($c==2){$a='E';}else if($c==3){$a='F';}else if($c==4){$a='G';}else if($c==5){$a='H';}else if($c==6){$a='I';}
						
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$a$x", mysql_num_rows($querycount)+$totalstud2);	
						
						$totalstud+=mysql_num_rows($querycount)+$totalstud2;
						 $c++;
					}
				}
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('J'.$x, $totalstud)
					->setCellValue('K'.$x, $c)
					->setCellValue('L'.$x, $totalstud/mysql_num_rows($query));
				$x++;
			}
		
		/* Miscellaneous glyphs, UTF-8
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A4', 'Miscellaneous glyphs')
					->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');*/
		
		
		/*$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
		$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
		$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);*/
}
else if($_REQUEST['met']=='misc_summary')
{
	
		// Add some data
		//echo date('H:i:s') , " Add some data" , EOL;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Student Name');
		
		$sqldet = "SELECT * FROM tbl_school_fee WHERE fee_type='mc' AND term_id = ".$_REQUEST['trm'];
		$resultdet = mysql_query($sqldet);
		
		$x=1;
		$c=1;
		
		
			while($row = mysql_fetch_array($resultdet))
			{	
				if($c==0){$a='A';}else if($c==1){$a='B';}else if($c==2){$a='C';}else if($c==3){$a='D';}else if($c==4){$a='E';}else if($c==5){$a='F';}else if($c==6){$a='G';}else if($c==7){$a='H';}else if($c==8){$a='I';}else if($c==9){$a='J';}else if($c==10){$a='K';}else if($c==11){$a='L';}else if($c==12){$a='M';}else if($c==13){$a='N';}else if($c==14){$a='O';}else if($c==15){$a='P';}else if($c==16){$a='Q';}else if($c==17){$a='R';}else if($c==18){$a='S';}else if($c==19){$a='T';}else if($c==20){$a='U';}else if($c==21){$a='V';}else if($c==22){$a='W';}else if($c==23){$a='X';}else if($c==24){$a='Y';}else if($c==25){$a='Z';}
				
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$a$x", $row['fee_name']);
					$c++;
			}
			
			$x++;
			$c=0;
			
			$sql = "SELECT s.* FROM tbl_student_enrollment_status st,tbl_student s WHERE s.id=st.student_id AND st.enrollment_status='E' AND st.term_id=".$_REQUEST['trm']." ORDER BY s.lastname";
			$query = mysql_query($sql);
			
			while($row2 = mysql_fetch_array($query))
			{
				if($c==0){$a='A';}else if($c==1){$a='B';}else if($c==2){$a='C';}else if($c==3){$a='D';}else if($c==4){$a='E';}else if($c==5){$a='F';}else if($c==6){$a='G';}else if($c==7){$a='H';}else if($c==8){$a='I';}else if($c==9){$a='J';}else if($c==10){$a='K';}else if($c==11){$a='L';}else if($c==12){$a='M';}else if($c==13){$a='N';}else if($c==14){$a='O';}else if($c==15){$a='P';}else if($c==16){$a='Q';}else if($c==17){$a='R';}else if($c==18){$a='S';}else if($c==19){$a='T';}else if($c==20){$a='U';}else if($c==21){$a='V';}else if($c==22){$a='W';}else if($c==23){$a='X';}else if($c==24){$a='Y';}else if($c==25){$a='Z';}
				
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$a$x", $row2['lastname'].", ".$row2['firstname']);
				
				$c++;
						
				$sql2 = "SELECT * FROM tbl_student_fees WHERE subject_id IS NULL AND student_id = ".$row2['id']." AND term_id=".$_REQUEST['trm'];
				$query2 = mysql_query($sql2);
				
				$b=1;
				
				while($row3 = mysql_fetch_array($query2))
				{
					if($c==0){$a='A';}else if($c==1){$a='B';}else if($c==2){$a='C';}else if($c==3){$a='D';}else if($c==4){$a='E';}else if($c==5){$a='F';}else if($c==6){$a='G';}else if($c==7){$a='H';}else if($c==8){$a='I';}else if($c==9){$a='J';}else if($c==10){$a='K';}else if($c==11){$a='L';}else if($c==12){$a='M';}else if($c==13){$a='N';}else if($c==14){$a='O';}else if($c==15){$a='P';}else if($c==16){$a='Q';}else if($c==17){$a='R';}else if($c==18){$a='S';}else if($c==19){$a='T';}else if($c==20){$a='U';}else if($c==21){$a='V';}else if($c==22){$a='W';}else if($c==23){$a='X';}else if($c==24){$a='Y';}else if($c==25){$a='Z';}
					
					if($row2['scholarship_type']!='A')
					{
						
						$amt = $row3['amount']!='Y'?$row3['amount']:0;
					}
					else
					{
						if($row2['scholarship']<100)
						{
							$s = $row2['scholarship']/100;
							$s2 = $row3['amount']*$s;
							$amt = $row3['amount']-$s2;
						}
						else
						{
							$amt = $row3['amount']=='5000'?'5000':0;
						}
					}
					
						$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue("$a$x", $amt);
						
						$total[$b] += $amt;
					
					$c++;
					$b++;
					
				}
					$x++;
					$c=0;
				
				
			}
			
			$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("A".$x, "TOTAL");
			
			$c=1;
			for($q=1;$q<=20;$q++)
				{
					
					
					if($c==0){$a='A';}else if($c==1){$a='B';}else if($c==2){$a='C';}else if($c==3){$a='D';}else if($c==4){$a='E';}else if($c==5){$a='F';}else if($c==6){$a='G';}else if($c==7){$a='H';}else if($c==8){$a='I';}else if($c==9){$a='J';}else if($c==10){$a='K';}else if($c==11){$a='L';}else if($c==12){$a='M';}else if($c==13){$a='N';}else if($c==14){$a='O';}else if($c==15){$a='P';}else if($c==16){$a='Q';}else if($c==17){$a='R';}else if($c==18){$a='S';}else if($c==19){$a='T';}else if($c==20){$a='U';}else if($c==21){$a='V';}else if($c==22){$a='W';}else if($c==23){$a='X';}else if($c==24){$a='Y';}else if($c==25){$a='Z';}
					
					$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue("$a$x", $total[$q]);
							
					$c++;
					
				}
	
}

else if($_REQUEST['met']=='soa_summary')
{
		// Add some data
		//echo date('H:i:s') , " Add some data" , EOL;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Student Name ')
					->setCellValue('B1', 'Student Number ');
					//->setCellValue('C1', 'Total Tuition');
					
					$sc = getPaymentSchemeTotal($_REQUEST['trm']);
		
				$sql2 = 'SELECT * FROM tbl_payment_scheme_details where scheme_id='.$sc;
				$query2 = mysql_query($sql2);
				
				$c=0;
				
				while($row2 = mysql_fetch_array($query2))
				{
					$month = split('-',$row2['payment_date']);
						if($c==0)
						{$a='C';}else if($c==1){$a='D';}else if($c==2){$a='E';}else if($c==3){$a='F';}else if($c==4){$a='G';}else if($c==5){$a='H';}else if($c==6){$a='I';}
						
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue($a.'1', getMonthName($month[1]));
						$c++;
				}
					
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('H1', 'Total Balance');
					
		$arcell = array("C","D","E","F","G","H","I");
				$sqldet = "SELECT s.*,st.scheme_id FROM tbl_student_enrollment_status st,tbl_student s WHERE s.id=st.student_id AND st.enrollment_status='E' AND st.term_id=".$_REQUEST['trm']." ORDER BY s.lastname";
				$resultdet = mysql_query($sqldet);
				$x=2;
			while($row = mysql_fetch_array($resultdet))
			{	
			
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, $row['lastname'].', '.$row['firstname'])
					->setCellValue('B'.$x, $row['student_number']);
					//->setCellValue('C'.$x, getStudentTotalFee($row['id'],$_REQUEST['trm']));
					
					$total1 += getStudentTotalFee($row['id'],$_REQUEST['trm']);//getStudentTotalDue($row['id'],$_REQUEST['trm']);
					
		
				$sql2 = 'SELECT * FROM tbl_payment_scheme_details where scheme_id='.$sc;
				$query2 = mysql_query($sql2);
				
				$c=0;
				
				while($row2 = mysql_fetch_array($query2))
				{
					$month = split('-',$row2['payment_date']);
						if($c==0)
						{$a='C';}else if($c==1){$a='D';}else if($c==2){$a='E';}else if($c==3){$a='F';}else if($c==4){$a='G';}else if($c==5){$a='H';}else if($c==6){$a='I';}
						
					//$objPHPExcel->setActiveSheetIndex(0)
						//->setCellValue("$a$x", getMonthName($month[1]));
						$d1 = strtotime($month[0].'-'.$month[1].'-1');
						$d2 = strtotime($month[0].'-'.$month[1].'-31');
						
						$sqlpay = 'SELECT * FROM tbl_student_payment WHERE student_id='.$row['id'].' AND term_id='.$_REQUEST['trm'].' AND date_created BETWEEN "'.$d1.'" AND "'.$d2.'"';
						$querypay = mysql_query($sqlpay);
						
						$p=0;
						while($rowp = mysql_fetch_array($querypay))
						{
							$totalp += $rowp['amount'];
							$p += $rowp['amount'];
							$p2 += $rowp['amount'];
							$ps[$c] += $rowp['amount'];
						}
						
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$a$x", $p);	
						 $c++;
					
				}
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('H'.$x, getStudentTotalFee($row['id'],$_REQUEST['trm'])-$p2);//getStudentTotalDue($row['id'],$_REQUEST['trm'])-$p2);
				$p2=0;
				$x++;
			}
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, 'TOTAL')
					->setCellValue('B'.$x, $total1);
					
					$c=0;
					foreach($ps as $pa)
					{
						if($c==0)
						{$a='C';}else if($c==1){$a='D';}else if($c==2){$a='E';}else if($c==3){$a='F';}else if($c==4){$a='G';}else if($c==5){$a='H';}else if($c==6){$a='I';}
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$a$x", $pa);
						$c++;
					}
					
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('H'.$x, $totalp);
		/* Miscellaneous glyphs, UTF-8
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A4', 'Miscellaneous glyphs')
					->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');*/
		
		
		/*$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
		$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
		$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);*/
}

else if($_REQUEST['met']=='soa_monthly')
{
		// Add some data
		//echo date('H:i:s') , " Add some data" , EOL;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Student Name ')
					->setCellValue('B1', 'Student Number ');
					//->setCellValue('C1', 'Total Tuition');
					
					$sc = getPaymentSchemeTotal($_REQUEST['trm']);
		
				/*$sql2 = 'SELECT * FROM tbl_payment_scheme_details where scheme_id='.$sc;
				$query2 = mysql_query($sql2);
				
				$c=0;
				
				while($row2 = mysql_fetch_array($query2))
				{
					$month = split('-',$row2['payment_date']);
						if($c==0)
						{$a='C';}else if($c==1){$a='D';}else if($c==2){$a='E';}else if($c==3){$a='F';}else if($c==4){$a='G';}else if($c==5){$a='H';}else if($c==6){$a='I';}
						
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue($a.'1', getMonthName($month[1]));
						$c++;
				}*/
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('C1', 'January')
						->setCellValue('D1', 'February')
						->setCellValue('E1', 'March');

		$arcell = array("C","D","E","F","G","H","I");
				$sqldet = "SELECT s.*,st.scheme_id FROM tbl_student_enrollment_status st,tbl_student s WHERE s.id=st.student_id AND st.enrollment_status='E' AND st.term_id=".$_REQUEST['trm']." ORDER BY s.lastname";
				$resultdet = mysql_query($sqldet);
				$x=2;
			while($row = mysql_fetch_array($resultdet))
			{	
			
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, $row['lastname'].', '.$row['firstname'])
					->setCellValue('B'.$x, $row['student_number']);
					//->setCellValue('C'.$x, getStudentTotalFee($row['id'],$_REQUEST['trm']));
					
					$total1 += getStudentTotalFee($row['id'],$_REQUEST['trm']);//getStudentTotalDue($row['id'],$_REQUEST['trm']);
					
		
				//$sql2 = 'SELECT * FROM tbl_payment_scheme_details where scheme_id='.$sc;
				//$query2 = mysql_query($sql2);
				
				$c=1;
				
				for($i=1;$i<=3;$i++)
				{
					//$month = split('-',$row2['payment_date']);
						if($c==0)
						{$a='C';}else if($c==1){$a='D';}else if($c==2){$a='E';}else if($c==3){$a='F';}else if($c==4){$a='G';}else if($c==5){$a='H';}else if($c==6){$a='I';}
						
					//$objPHPExcel->setActiveSheetIndex(0)
						//->setCellValue("$a$x", getMonthName($month[1]));
						$d1 = strtotime('2015-'.$i.'-1');
						$d2 = strtotime('2015-'.$i.'-31');
						
						$sqlpay = 'SELECT * FROM tbl_student_payment WHERE student_id='.$row['id'].' AND term_id='.$_REQUEST['trm'].' AND date_created BETWEEN "'.$d1.'" AND "'.$d2.'"';
						$querypay = mysql_query($sqlpay);
						
						$p=0;
						while($rowp = mysql_fetch_array($querypay))
						{
							$totalp += $rowp['amount'];
							$p += $rowp['amount'];
							$p2 += $rowp['amount'];
							$ps[$c] += $rowp['amount'];
						}
						
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$a$x", $p);	
						 $c++;
					
				}
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('H'.$x, getStudentTotalFee($row['id'],$_REQUEST['trm'])-$p2);//getStudentTotalDue($row['id'],$_REQUEST['trm'])-$p2);
				$p2=0;
				$x++;
			}
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, 'TOTAL')
					->setCellValue('B'.$x, $total1);
					
					$c=0;
					foreach($ps as $pa)
					{
						if($c==0)
						{$a='C';}else if($c==1){$a='D';}else if($c==2){$a='E';}else if($c==3){$a='F';}else if($c==4){$a='G';}else if($c==5){$a='H';}else if($c==6){$a='I';}
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$a$x", $pa);
						$c++;
					}
					
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('H'.$x, $totalp);
		
}

else if($_REQUEST['met']=='soa_monthly2')
{
		// Add some data
		//echo date('H:i:s') , " Add some data" , EOL;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Student Name ')
					->setCellValue('B1', 'Student Number ');
					//->setCellValue('C1', 'Total Tuition');
					
					$sc = getPaymentSchemeTotal($_REQUEST['trm']);
		
				for($i=1;$i<=3;$i++)
				{
					if($i==1)
						{$a='C';}else if($i==2){$a='D';}else if($i==3){$a='E';}else if($i==4){$a='F';}else if($i==5){$a='G';}else if($i==6){$a='H';}else if($i==7){$a='I';}
						
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$a$i", '');
				}

		$arcell = array("C","D","E","F","G","H","I");
				$sqldet = "SELECT s.*,st.scheme_id FROM tbl_student_enrollment_status st,tbl_student s WHERE s.id=st.student_id AND st.enrollment_status='E' AND st.term_id=".$_REQUEST['trm']." ORDER BY s.lastname";
				$resultdet = mysql_query($sqldet);
				$x=2;
			while($row = mysql_fetch_array($resultdet))
			{	
			
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, $row['lastname'].', '.$row['firstname'])
					->setCellValue('B'.$x, $row['student_number']);
					//->setCellValue('C'.$x, getStudentTotalFee($row['id'],$_REQUEST['trm']));
					
					$total1 += getStudentTotalFee($row['id'],$_REQUEST['trm']);//getStudentTotalDue($row['id'],$_REQUEST['trm']);
					
		
				//$sql2 = 'SELECT * FROM tbl_payment_scheme_details where scheme_id='.$sc;
				//$query2 = mysql_query($sql2);
				
				$c=1;
				
				for($i=1;$i<=3;$i++)
				{
					//$month = split('-',$row2['payment_date']);
						if($c==0)
						{$a='C';}else if($c==1){$a='D';}else if($c==2){$a='E';}else if($c==3){$a='F';}else if($c==4){$a='G';}else if($c==5){$a='H';}else if($c==6){$a='I';}
						
					//$objPHPExcel->setActiveSheetIndex(0)
						//->setCellValue("$a$x", getMonthName($month[1]));
						$d1 = strtotime('2015-'.$i.'-1');
						$d2 = strtotime('2015-'.$i.'-31');
						
						$sqlpay = 'SELECT * FROM tbl_student_payment WHERE student_id='.$row['id'].' AND term_id='.$_REQUEST['trm'].' AND date_created BETWEEN "'.$d1.'" AND "'.$d2.'"';
						$querypay = mysql_query($sqlpay);
						
						$p=0;
						while($rowp = mysql_fetch_array($querypay))
						{
							$totalp += $rowp['amount'];
							$p += $rowp['amount'];
							$p2 += $rowp['amount'];
							$ps[$c] += $rowp['amount'];
						}
						
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$a$x", $p);	
						 $c++;
					
				}
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('H'.$x, getStudentTotalFee($row['id'],$_REQUEST['trm'])-$p2);//getStudentTotalDue($row['id'],$_REQUEST['trm'])-$p2);
				$p2=0;
				$x++;
			}
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, 'TOTAL')
					->setCellValue('B'.$x, $total1);
					
					$c=0;
					foreach($ps as $pa)
					{
						if($c==0)
						{$a='C';}else if($c==1){$a='D';}else if($c==2){$a='E';}else if($c==3){$a='F';}else if($c==4){$a='G';}else if($c==5){$a='H';}else if($c==6){$a='I';}
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$a$x", $pa);
						$c++;
					}
					
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('H'.$x, $totalp);
		
}

else if($_REQUEST['met']=='soa_bal')
{
		// Add some data
		//echo date('H:i:s') , " Add some data" , EOL;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Student Name ')
					->setCellValue('B1', 'Student Number ')
					->setCellValue('C1', 'Total Balance');
					
					$sc = getPaymentSchemeTotal($_REQUEST['trm']);

					
		$arcell = array("C","D","E","F","G","H","I");
				$sqldet = "SELECT s.*,st.scheme_id FROM tbl_student_enrollment_status st,tbl_student s WHERE s.id=st.student_id AND st.enrollment_status='E' AND st.term_id=".$_REQUEST['trm']." ORDER BY s.lastname";
				$resultdet = mysql_query($sqldet);
				$x=2;
			while($row = mysql_fetch_array($resultdet))
			{	
			
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, $row['lastname'].', '.$row['firstname'])
					->setCellValue('B'.$x, $row['student_number']);
					//->setCellValue('C'.$x, getStudentTotalFee($row['id'],$_REQUEST['trm']));
					
					$total1 += getStudentTotalFee($row['id'],$_REQUEST['trm']);//getStudentTotalDue($row['id'],$_REQUEST['trm']);
				
				$c=0;
				
				$sqlpay = 'SELECT * FROM tbl_student_payment WHERE student_id='.$row['id'].' AND term_id='.$_REQUEST['trm'];
						$querypay = mysql_query($sqlpay);
						
						$p=0;
						while($rowp = mysql_fetch_array($querypay))
						{
							$totalp += $rowp['amount'];
							$p += $rowp['amount'];
							$p2 += $rowp['amount'];
							$ps[$c] += $rowp['amount'];
						}
				$bal = getStudentTotalFee($row['id'],$_REQUEST['trm'])-$p2;
				$balfin = $bal>0?$bal:0;
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C'.$x, $balfin);//getStudentTotalDue($row['id'],$_REQUEST['trm'])-$p2);
				$p2=0;
				$x++;
			}
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, 'TOTAL');
					
					$c=0;
					
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C'.$x, $totalp);
		/* Miscellaneous glyphs, UTF-8
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A4', 'Miscellaneous glyphs')
					->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');*/
		
		
		/*$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
		$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
		$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);*/
}

else if($_REQUEST['met']=='soa2')
{
	
	
		// Add some data
		//echo date('H:i:s') , " Add some data" , EOL;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Student Number ')
					->setCellValue('B1', 'Student Name ')
					->setCellValue('C1', 'Date Enrolled ')
					->setCellValue('D1', 'Period')
					->setCellValue('E1', 'Total Tuition');
					
					$sc = getPaymentSchemeTotal($_REQUEST['trm']);
		
				/*$sql2 = 'SELECT * FROM tbl_payment_scheme_details where scheme_id='.$sc;
				$query2 = mysql_query($sql2);
				
				$c=0;
				
				while($row2 = mysql_fetch_array($query2))
				{
					$month = split('-',$row2['payment_date']);
						if($c==0)
						{$a='C';}else if($c==1){$a='D';}else if($c==2){$a='E';}else if($c==3){$a='F';}else if($c==4){$a='G';}else if($c==5){$a='H';}else if($c==6){$a='I';}
						
					$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue($a.'1', getMonthName($month[1]));
						$c++;
				}
					
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('H1', 'Total Balance');
					*/
		$arcell = array("C","D","E","F","G","H","I");
				$sqldet = "SELECT s.*,st.scheme_id,st.date_enrolled,st.term_id as term FROM tbl_student_enrollment_status st,tbl_student s WHERE s.id=st.student_id AND st.enrollment_status='E' AND st.term_id=".$_REQUEST['trm']." ORDER BY s.lastname";
				$resultdet = mysql_query($sqldet);
				$x=2;
			while($row = mysql_fetch_array($resultdet))
			{ 
			$sqlp = "SELECT * FROM tbl_student_payment WHERE student_id=".$row['id']." AND term_id=".$_REQUEST['trm'];
			$queryp = mysql_query($sqlp);
			$rowp = mysql_fetch_array($queryp);
			
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, $row['student_number'])
					->setCellValue('B'.$x, $row['lastname'].', '.$row['firstname'])
					->setCellValue('C'.$x, date('F d, Y',$rowp['date_created']))
					->setCellValue('D'.$x, getSchoolTerm($_REQUEST['trm'])." ".getSchoolYearStartEndByTerm($_REQUEST['trm']))
					->setCellValue('E'.$x, getStudentTotalFee($row['id'],$_REQUEST['trm']));
					
					$total1 += getStudentTotalFee($row['id'],$_REQUEST['trm']);//getStudentTotalDue($row['id'],$_REQUEST['trm']);
					
		
				$sql2 = 'SELECT * FROM tbl_payment_scheme_details where scheme_id='.$sc;
				$query2 = mysql_query($sql2);
				
				/*$c=0;
				
				while($row2 = mysql_fetch_array($query2))
				{
					$month = split('-',$row2['payment_date']);
						if($c==0)
						{$a='C';}else if($c==1){$a='D';}else if($c==2){$a='E';}else if($c==3){$a='F';}else if($c==4){$a='G';}else if($c==5){$a='H';}else if($c==6){$a='I';}
						
					//$objPHPExcel->setActiveSheetIndex(0)
						//->setCellValue("$a$x", getMonthName($month[1]));
						$d1 = strtotime($month[0].'-'.$month[1].'-1');
						$d2 = strtotime($month[0].'-'.$month[1].'-31');
						
						$sqlpay = 'SELECT * FROM tbl_student_payment WHERE student_id='.$row['id'].' AND term_id='.$_REQUEST['trm'].' AND date_created BETWEEN "'.$d1.'" AND "'.$d2.'"';
						$querypay = mysql_query($sqlpay);
						
						$p=0;
						while($rowp = mysql_fetch_array($querypay))
						{
							$totalp += $rowp['amount'];
							$p += $rowp['amount'];
							$p2 += $rowp['amount'];
							$ps[$c] += $rowp['amount'];
						}
						
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$a$x", $p);	
						 $c++;
					
				}
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('H'.$x, getStudentTotalFee($row['id'],$_REQUEST['trm'])-$p2);//getStudentTotalDue($row['id'],$_REQUEST['trm'])-$p2);
				$p2=0;*/
				$x++;
			}
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, 'TOTAL')
					->setCellValue('B'.$x, $total1);
					
					/*$c=0;
					foreach($ps as $pa)
					{
						if($c==0)
						{$a='C';}else if($c==1){$a='D';}else if($c==2){$a='E';}else if($c==3){$a='F';}else if($c==4){$a='G';}else if($c==5){$a='H';}else if($c==6){$a='I';}
						$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue("$a$x", $pa);
						$c++;
					}
					
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('H'.$x, $totalp);*/
		/* Miscellaneous glyphs, UTF-8
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A4', 'Miscellaneous glyphs')
					->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');*/
		
		
		/*$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
		$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
		$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);*/
}
else if($_REQUEST['met']=='soa_coll')
{
	
	
		// Add some data
		//echo date('H:i:s') , " Add some data" , EOL;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Student Number ')
					->setCellValue('B1', 'Student Name ');
		
				
		$arcell = array("C","D","E","F","G","H","I");
				$sqldet = "SELECT s.*,st.scheme_id,st.date_enrolled,st.term_id as term FROM tbl_student_enrollment_status st,tbl_student s WHERE s.id=st.student_id AND st.enrollment_status='E' AND st.term_id=".$_REQUEST['trm']." ORDER BY s.lastname";
				$resultdet = mysql_query($sqldet);
				$x=2;
			while($row = mysql_fetch_array($resultdet))
			{ 
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, $row['student_number'])
					->setCellValue('B'.$x, $row['lastname'].', '.$row['firstname']);
				$x++;
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, 'OR No. ')
					->setCellValue('B'.$x, 'Date')
					->setCellValue('C'.$x, 'Amount');
				$x++;	
			$sqlp = "SELECT * FROM tbl_student_payment WHERE student_id=".$row['id']." AND term_id=".$_REQUEST['trm'];
			$queryp = mysql_query($sqlp);
			
				while($rowp = mysql_fetch_array($queryp))
				{
				
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A'.$x, $rowp['or_no'])
						->setCellValue('B'.$x, date('F d, Y',$rowp['date_created']))
						->setCellValue('C'.$x, $rowp['amount']);
					
					$x++;
				}
			}
		
}	

else if($_REQUEST['met']=='fin_summary')
{
		// Add some data
		//echo date('H:i:s') , " Add some data" , EOL;
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Student Name ')
					->setCellValue('B1', 'Course')
					->setCellValue('C1', 'Cost Per Unit')
					->setCellValue('D1', 'Total Units')
					->setCellValue('E1', 'Total Misc')
					->setCellValue('F1', 'Scholarship Percentage')
					->setCellValue('G1', 'Scholarship Amount');
					
					
		$arcell = array("C","D","E","F","G","H","I");
		
				$sqldet = "SELECT * FROM tbl_student_enrollment_status st,tbl_student s WHERE s.id=st.student_id AND st.enrollment_status='E' AND st.term_id=".$_REQUEST['trm']." ORDER BY s.lastname";
				$resultdet = mysql_query($sqldet);
				$x=2;
			while($row = mysql_fetch_array($resultdet))
			{	
			
				$sql_misc = "SELECT f.fee_type,s.*,sum(s.amount) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$row['id']." AND s.term_id =" .$_REQUEST['trm'];

				$qry_misc = mysql_query($sql_misc);
	
				$row_misc = mysql_fetch_array($qry_misc);
	
				$sub_mis_total = $row_misc['sum(s.amount)'];
				
				$sql_lec = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlec' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$row['id']." AND s.term_id =" .$_REQUEST['trm'];

				$qry_lec = mysql_query($sql_lec);
	
				$row_lec = mysql_fetch_array($qry_lec);
				
				$sqls2 = "SELECT * FROM tbl_payment_scheme WHERE id=".$row['scheme_id'];
				$querys2 = mysql_query($sqls2);
				$rows2 = mysql_fetch_array($querys2);
				
				$total = getStudentTotalFee($row['id'],$_REQUEST['trm']);
				
				if($row['scholarship']=='A')
				{
					$d = $row['scholarship']/100;
					$dis=($total*$d)-5000;
				}else{
					$d = $row['scholarship']/100;
					$dis=($total*$d);
				}
			
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$x, $row['lastname'].', '.$row['firstname']." ".$row['middlename'])
					->setCellValue('B'.$x, getCourseCode($row['course_id']))
					->setCellValue('C'.$x, $row_lec['amount']+$rows2['surcharge'])
					->setCellValue('D'.$x, getEnrolledTotalUnits($_REQUEST['trm'],$row['id']))
					->setCellValue('E'.$x, $sub_mis_total)
					->setCellValue('F'.$x, $row['scholarship'].'%'.$row['scholarship_type'])
					->setCellValue('G'.$x, '('.$dis.')');
					
					//getStudentTotalDue($row['id'],$_REQUEST['trm']);
				
				$x++;
				
			}
		
		/* Miscellaneous glyphs, UTF-8
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A4', 'Miscellaneous glyphs')
					->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');*/
		
		
		/*$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
		$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
		$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);*/
}
else if($_REQUEST['met']=='report')
{

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
						$ctr = 2;
						
						$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Firstname')
					->setCellValue('B1', 'Middlename')
					->setCellValue('C1', 'Lastname');
						
						while($row = mysql_fetch_array($result)) 
						{
							$sqle = 'SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status="E" AND student_id='.$row['id'].' AND term_id='.CURRENT_TERM_ID;
							$querye = mysql_query($sqle);
							
							if(mysql_num_rows($querye)>0)
							{
							$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('A'.$ctr, $row['lastname'])
							->setCellValue('B'.$ctr, $row['firstname'])
							->setCellValue('C'.$ctr, $row['middlename']);
							$ctr++;
							}
						}
						
							
	
}

// Rename worksheet
//echo date('H:i:s') , " Rename worksheet" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('MINT');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$y = "MintReport (".getSchoolTerm($_REQUEST['trm']).") ".getSchoolYearStartEndByTerm($_REQUEST['trm']);
// Redirect output to a client's web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$y.'.xls"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
