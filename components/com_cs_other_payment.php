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



$page_title = 'Other Payment';
$pagination = 'Payment> Other Payment';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$amount_paid			= $_REQUEST['amount_paid'];
$payment_met			= $_REQUEST['payment_met'];
$bank_brn				= $_REQUEST['bank_brn'];
$check_num 				= $_REQUEST['check_num'];
$lname					= $_REQUEST['lname'];
$fname	 				= $_REQUEST['fname'];
$empnumber				= $_REQUEST['enumber'];
$studnumber				= $_REQUEST['snumber'];
$type					= $_REQUEST['type'];
$classif				= $_REQUEST['classif'];

$rows 				= $_REQUEST['rows'];
$page 				= $_REQUEST['page'];

if($bank_brn == ''){
		$bank_brn = 'none';
	}
if($check_num == ''){
		$check_num = '0';
	}
if($classif == 'emp'){
		$emnumber = getEmployeeIdByNumber($empnumber);
		$stnumber = '0';
	}
	else if($classif == 'stud')
	{
		$stnumber = getStudentIdByNumber($studnumber);
		$emnumber = '0';
	}
	else
	{
		$stnumber = '0';
		$emnumber = '0';
	}
	
if($action == 'save')
{	
	if($payment_met == '' || $amount_paid =='' || $type == '' || $lname == '' || $fname == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfLibraryPayment($type)&&getStudentLibraryBalance(getStudentIdByNumber($studnumber))<=0)
	{
		$err_msg = 'Student Do not have Libary Balance Due.';
	}
	else if(checkIfLibraryPayment($type)&&$amount_paid > getStudentLibraryBalance(getStudentIdByNumber($studnumber)))
	{
		$err_msg = 'Student Payment exceeds Total due.';
	}
	else
	{
		if($classif == 'stud'&&!checkStudentIDExist($studnumber)) 
		{
				$err_msg = 'Student do not exist.';
		}
		else if($classif == 'emp'&&!checkEmpIDExist($empnumber)) 
		{
				$err_msg = 'Employee do not exist.';
		}
		else
		{
			if(checkIfLibraryPayment($type))
			{
				$sql_in = "INSERT INTO ob_member_account 
										(
											student_id,
											create_dt, 
											create_userid,
											transaction_type_cd,
											amount,
											description
										) 
										VALUES 
										(
											".getStudentIdByNumber($studnumber).",  
											'".date("Y-m-d H:s:i")."',
											".USER_ID.",  
											".'"-p"'.", 	
											".($amount_paid*-1).",  
											".'"paid"'."
										)";
							$query_in = mysql_query($sql_in);
			}
			
			$sql = "INSERT INTO tbl_other_payments
				(
					student_id, 
					term_id,
					type_id,
					employee_id,
					firstname,
					lastname,
					amount, 
					payment_method,
					bank,
					check_no,
					date_created,
					created_by
				) 
				VALUES 
				(
					".GetSQLValueString($stnumber,"text").",  
					".CURRENT_TERM_ID.", 
					".GetSQLValueString($type,"int").",
					".GetSQLValueString($emnumber,"text").",
					".GetSQLValueString($fname,"text").",
					".GetSQLValueString($lname,"text").",
					".GetSQLValueString($amount_paid,"text").",  
					".GetSQLValueString($payment_met,"text").",  
					".GetSQLValueString($bank_brn,"text").",
					".GetSQLValueString($check_num,"text").",
					".time().", 
					".USER_ID."
				)";
			
		if(mysql_query ($sql))
		{
			echo '<script language="javascript">alert("Successfully Added!");window.open(\'pdf_reports/rep108.php?id='.mysql_insert_id().'&met=other_OR\');window.location =\'index.php?comp=com_cs_other_payment\';</script>';
		}
       }
	}
}
else if($action == 'list')
{
	$studnumber = '';
	$empnumber	= '';
	$amount_paid = '';
	$payment_met = '';
	$type = '';
	$bank_brn = '';
	$check_num = '';
	$classif = '';
	$lname	= '';
	$fname	= '';
}
else if($action=='save')
{
	$amount_paid			= $_REQUEST['amount_paid'];
    $payment_met			= $_REQUEST['payment_met'];
    $bank_brn				= $_REQUEST['bank_brn'];
    $check_num 				= $_REQUEST['check_num'];
    $lname					= $_REQUEST['lname'];
    $fname	 				= $_REQUEST['fname'];
    $empnumber				= $_REQUEST['enumber'];
    $studnumber				= $_REQUEST['snumber'];
    $type					= $_REQUEST['type'];
	$classif				= $_REQUEST['classif'];

}
// component block, will be included in the template page
$content_template = 'components/block/blk_com_cs_other_payment.php';
?>