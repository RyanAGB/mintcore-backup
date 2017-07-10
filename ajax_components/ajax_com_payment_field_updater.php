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
	//include_once('../includes/functions.php');
if(isset($_REQUEST['chk']))
{
	$chek = $_REQUEST['chk'];
}
if(isset($_REQUEST['bnk']))
{
	$bank = $_REQUEST['bnk'];
}	
if(isset($_REQUEST['am']))
{
	$amt = $_REQUEST['am'];
}
if(isset($_REQUEST['lname']))
{
	$lname = $_REQUEST['lname'];
}
if(isset($_REQUEST['fname']))
{
	$fname = $_REQUEST['fname'];
}	
if(isset($_REQUEST['snum']))
{
	$snum = $_REQUEST['snum'];
}
if(isset($_REQUEST['enum']))
{
	$enum = $_REQUEST['enum'];
}

$mod = $_REQUEST['mod'];
$id = $_REQUEST['id'];
$str_arr = array();

if($mod == 'updatePayment')
{
	if($id != '')
	{
			$str_arr[] = '<table width="100%" border="1" cellspacing="0" cellpadding="0">';
			  $str_arr[] = '<tr>';
				$str_arr[] = '<td width="40%"><strong>Bank/Branch</strong></td>';
				$str_arr[] = '<td><input name = "bank" id = "bank" value = "'.$bank.'" class = "txt_150"></td>';
			 $str_arr[] = '</tr>';
			  $str_arr[] = '<tr>';
				$str_arr[] = '<td><strong>Check Number</strong></td>';
				$str_arr[] = '<td><input name = "check_no" id = "check_no" value = "'.$chek.'" class = "txt_150"></td>';
			  $str_arr[] = '</tr>';
			  $str_arr[] = '<tr>';
				$str_arr[] = '<td><strong>Amount</strong></td>';
				$str_arr[] = '<td><input name = "amount" id = "amount" value = "'.$amt.'" class = "txt_150"></td>';
			  $str_arr[] = '</tr>';
			$str_arr[] = '</table>';
	}	
	echo implode('',$str_arr);
}


if($mod == 'updatePayment2')
{

	if($id != '')
	{
			$str_arr[] = '<table width="100%" border="1" cellspacing="0" cellpadding="0">';
			  $str_arr[] = '<tr>';
				$str_arr[] = '<td width="40%"><strong>Amount</strong></td>';
				$str_arr[] = '<td><input name="amount" type="text" value = "'.$amt.'" class="txt_150" id="amount"/></td>';
			 $str_arr[] = '</tr>';
			$str_arr[] = '</table>';
		
	}	
		echo implode('',$str_arr);
}
if($mod == 'updatePayment3')
{

		$str_arr[] = '<table width="100%" border="1" cellspacing="0" cellpadding="0">';
			  $str_arr[] = '<tr>';
			 $str_arr[] = '</tr>';
			$str_arr[] = '</table>';
		
		echo implode('',$str_arr);
}

if($mod == 'updateOtPayment')
{
	if($id != '')
	{
			if($id == 'stud')
			{
			$str_arr[] = '<table width="100%" border="1" cellspacing="0" cellpadding="0">';
			  $str_arr[] = '<tr>';
				$str_arr[] = '<td width="40%"><strong>Student Number</strong></td>';
				$str_arr[] = '<td><input name = "studentnum" id = "studentnum" value = "'.$snum.'" class = "txt_150"></td>';
			 $str_arr[] = '</tr>';
			 $str_arr[] = '<tr>';
				$str_arr[] = '<td><strong>Firstname</strong></td>';
				$str_arr[] = '<td><input name = "firstname" id = "firstname" value = "'.$fname.'" class = "txt_150"></td>';
			  $str_arr[] = '</tr>';
			  $str_arr[] = '<tr>';
				$str_arr[] = '<td><strong>Lastname</strong></td>';
				$str_arr[] = '<td><input name = "lastname" id = "lastname" value = "'.$lname.'" class = "txt_150"></td>';
			  $str_arr[] = '</tr>';
			$str_arr[] = '</table>';
			 }
			 else if($id == 'emp')
			 {
			 	$str_arr[] = '<table width="100%" border="1" cellspacing="0" cellpadding="0">';
			  $str_arr[] = '<tr>';
				$str_arr[] = '<td width="40%"><strong>Employee Number</strong></td>';
				$str_arr[] = '<td><input name = "employeenum" id = "employeenum" value = "'.$enum.'" class = "txt_150"></td>';
			 $str_arr[] = '</tr>';
			 $str_arr[] = '<tr>';
				$str_arr[] = '<td><strong>Firstname</strong></td>';
				$str_arr[] = '<td><input name = "firstname" id = "firstname" value = "'.$fname.'" class = "txt_150"></td>';
			  $str_arr[] = '</tr>';
			  $str_arr[] = '<tr>';
				$str_arr[] = '<td><strong>Lastname</strong></td>';
				$str_arr[] = '<td><input name = "lastname" id = "lastname" value = "'.$lname.'" class = "txt_150"></td>';
			  $str_arr[] = '</tr>';
			$str_arr[] = '</table>';
			 }
			 else
			 {
			 $str_arr[] = '<table width="100%" border="1" cellspacing="0" cellpadding="0">';
			 	$str_arr[] = '<tr>';
				$str_arr[] = '<td><strong>Firstname</strong></td>';
				$str_arr[] = '<td><input name = "firstname" id = "firstname" value = "'.$fname.'" class = "txt_150"></td>';
			  $str_arr[] = '</tr>';
			  $str_arr[] = '<tr>';
				$str_arr[] = '<td><strong>Lastname</strong></td>';
				$str_arr[] = '<td><input name = "lastname" id = "lastname" value = "'.$lname.'" class = "txt_150"></td>';
			  $str_arr[] = '</tr>';
			$str_arr[] = '</table>';
			 }
			 
			  
	}	
	echo implode('',$str_arr);
}
?>