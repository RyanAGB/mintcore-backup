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



$page_title = 'Balance Payment';
$pagination = 'Student Payment> Balance Payment';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$student_id 			= $_REQUEST['student_id'];
$subject_id 			= $_REQUEST['subject_id'];
$term_id				= $_REQUEST['term_id'];
$total_discount			= $_REQUEST['total_discount'];
$discount				= $_REQUEST['discount'];
$amount_paid			= $_REQUEST['amount_paid'];
//$payment_scheme_id	= $_REQUEST['payment_scheme_id'];
$payment_met			= $_REQUEST['payment_met'];
$sub_total 				= $_REQUEST['total'];
$topay					= $_REQUEST['topay'];
$bank_brn				= $_REQUEST['bank_brn'];
$check_num 				= $_REQUEST['check_num'];
$payment_paid			= $_REQUEST['payment_paid'];
$p_month				= $_REQUEST['p_month'];
$p_day					= $_REQUEST['p_day'];
$p_year					= $_REQUEST['p_year'];
$payment_date			= strtotime($p_year.'-'.$p_month.'-'.$p_day);
$order	 				= $_REQUEST['order'];
$down					= $_REQUEST['down'];
$cond					= $_REQUEST['cond'];
$check_id				= $_REQUEST['check_id'];
$OR						= $_REQUEST['or'];
$remarks				= $_REQUEST['rem'];
$surcharge				= $_REQUEST['surcharge'];

$rows 				= $_REQUEST['rows'];
$page 				= $_REQUEST['page'];


if($bank_brn == ''){
		$bank_brn = 'none';
	}
if($check_num == ''){
		$check_num = '0';
	}
if($amount_paid == $total_discount){
		$payment_term = 'F';
	}else{
		$payment_term = 'P';
	}
if($payment_paid != '')
{
	$val_paid = $amount_paid + $payment_paid;
	$lessed_pay = $topay - $payment_paid;
}
else
{
	$lessed_pay = $topay;
}

$payment_scheme_id	= getSchemeByStatus($student_id);
	
if($action == 'save')
{
if($order == 'RESERVED')
{
		/*if($_REQUEST['payment_scheme_id']=='')
	{
		$payment_scheme_id	= getSchemeByStatus($student_id);
	}else{
		$payment_scheme_id	= $_REQUEST['payment_scheme_id'];
	}*/
	
	if($payment_met == '' || $amount_paid =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	/* TEMPORARY DISABLED
	else if($amount_paid < getBalanceBySort($payment_scheme_id,$sub_total,date('Y-m-d')))
	{		
		$err_msg = 'Insufficient Payment! Payment must be more than or equal to Php '.number_format(getBalanceBySort($payment_scheme_id,$sub_total,date('Y-m-d')), 2, ".", ",");
	}
	else if($amount_paid < $down)
	{
		$err_msg = 'Insufficient Payment! Payment must be more than or equal to Php '.number_format($down, 2, ".", ",");
	}
	else if($amount_paid > $sub_total)
	{
		$err_msg = 'Payment Exceeds total tuition fee.';
	}*/
	/*else if($cond != 'true')
	{
		$err_msg = 'Invalid Discount. Total fee is less than the Initial payment.';
	}*/
	else
	{
		$sql = "INSERT INTO tbl_student_payment
				(
					student_id, 
					term_id,
					discount_id,
					payment_scheme_id,
					amount, 
					payment_method,
					payment_term,
					bank,
					check_no,
					remarks,
					or_no,
					date_created,
					created_by
				) 
				VALUES 
				(
					".GetSQLValueString($student_id,"text").",  
					".CURRENT_TERM_ID.", 
					".GetSQLValueString($discount,"int").",
					".GetSQLValueString($payment_scheme_id,"int").",
					".GetSQLValueString($amount_paid,"text").",
					".GetSQLValueString($payment_met,"int").",
					".GetSQLValueString($payment_term,"text").",  
					".GetSQLValueString($bank_brn,"text").",
					".GetSQLValueString($check_num,"text").",
					".GetSQLValueString($remarks,"text").",
					".GetSQLValueString($OR,"text").",
					".GetSQLValueString($payment_date,"text").", 
					".USER_ID."
				)";
		
		if(mysql_query ($sql))
		{
			$pay_id = mysql_insert_id();
			
			/*$sql_scheme = "UPDATE tbl_student_enrollment_status SET 
				scheme_id =".GetSQLValueString($payment_scheme_id,"int")."
				WHERE student_id = " .$student_id;
			mysql_query($sql_scheme);	*/	
		
			$sql= "SELECT * FROM tbl_student_reserve_subject WHERE student_id =" .$student_id;
			$qry = mysql_query($sql);
			while($row = mysql_fetch_array($qry))
			{
				$sql_sched = "INSERT INTO tbl_student_schedule 
					(
						student_id,
						term_id,
						schedule_id,
						subject_id,
						units,
						enrollment_status,
						elective_of,
						date_created,
						created_by,
						date_modified,
						modified_by
					) 
					VALUES 
					(
						".GetSQLValueString($student_id,"int").",  
						".GetSQLValueString($row['term_id'],"int").",  
						".GetSQLValueString($row['schedule_id'],"int").", 
						".GetSQLValueString($row['subject_id'],"int").", 
						".GetSQLValueString($row['units'],"int").", 
						".GetSQLValueString('A',"text").", 
						".GetSQLValueString($row['elective_of'],"int").", 
						".time().",	
						".USER_ID.",
						".time().",	
						".USER_ID."
					)";

				mysql_query($sql_sched);
				
				$sql_del ="DELETE FROM tbl_student_reserve_subject WHERE id = " . $row['id'];
				mysql_query($sql_del);
				
				// [+] STORE SYSTEM LOGS
					$param = array(	
									getStudentNumber($student_id),
									getSYandTerm($term_id)
								   );
					storeSystemLogs(MSG_CASHIER_RESERVED_PAYMENT_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
				// [-] STORE SYSTEM LOGS	
				
				/*temporary disabled
				notification(getStudentUser($student_id),MSG_CASHIER_RESERVED_PAYMENT_FOR_STUDENT,$param,USER_ID);
				notification(getParentUser($student_id),MSG_CASHIER_RESERVED_PAYMENT_FOR_STUDENT,$param,USER_ID);*/
				$admins = getUserAdmin();
				foreach($admins as $admin)
				{
					notification($admin,MSG_CASHIER_RESERVED_PAYMENT_FOR_STUDENT,$param,USER_ID);					
				}
		
			}	
			
			$sql_status = "UPDATE tbl_student_enrollment_status SET 
				enrollment_status =".GetSQLValueString('E',"text").", 
				date_enrolled = ".time() ." 
				WHERE student_id = " .$student_id;	
			mysql_query($sql_status);	
			
			// [+] UPDATE STUDENT YEAR LEVEL
			$sql_yrlvl = "UPDATE tbl_student SET 
				year_level =".GetSQLValueString(getStudentNewYrLevel($student_id),"int")."
				WHERE id = " .$student_id;	
			mysql_query($sql_yrlvl);					
			// [-] UPDATE STUDENT YEAR LEVEL					
			
			echo '<script language="javascript">alert("Successfully Added!"); window.location =\'index.php?comp=com_cs_student_balance_payment&student_id='.$student_id.'\';</script>';
			
			//window.open(\'pdf_reports/rep108.php?id='.$student_id.'&pay_id='.$pay_id.'&met=OR\');
		}
	}
}
else
{

$payment_term = checkPaymentIfPartialOrFull($student_id,$amount_paid);

	if($payment_met == '' || $amount_paid =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	/*else if (checkIfPaymentAlreadyFull($student_id))
	{
		$err_msg = 'Student is Already fully Paid.';
	}
	else if($amount_paid > (getStudentTotalFee($student_id)-getTotalPaymentOfStudent($student_id,CURRENT_TERM_ID)))
	{
		$err_msg = 'Payment exceeds total tuition fee.'.getStudentTotalFee($student_id);
	}
	TEMPORARY DISABLED
	else if($amount_paid < getBalanceBySort(getStudentPaymentSchemeID($student_id),getStudentTotalFee($student_id),date('Y-m-d'),$student_id))
	{		
		$err_msg = 'Insufficient Payment! Payment must be more than or equal to Php '.number_format(getBalanceBySort(getStudentPaymentSchemeID($student_id),getStudentTotalFee($student_id),date('Y-m-d')), 2, ".", ",");
	}*/
	else
	{
		$term = $_REQUEST['term']!=CURRENT_TERM_ID?$_REQUEST['term']:CURRENT_TERM_ID;

		$sql = "INSERT INTO tbl_student_payment
				(
					student_id, 
					term_id,
					amount, 
					payment_scheme_id,
					payment_method,
					payment_term,
					bank,
					check_no,
					or_no,
					remarks,
					date_created,
					created_by
				) 
				VALUES 
				(
					".GetSQLValueString($student_id,"text").",  
					".$term.", 
					".GetSQLValueString($amount_paid,"text").",
					".GetSQLValueString($payment_scheme_id,"int").",
					".GetSQLValueString($payment_met,"text").",
					".GetSQLValueString($payment_term,"text").",  
					".GetSQLValueString($bank_brn,"text").",
					".GetSQLValueString($check_num,"text").",
					".GetSQLValueString($OR,"text").",
					".GetSQLValueString($remarks,"text").",
					".GetSQLValueString(strtotime($_REQUEST['payment_date']),"text").", 
					".USER_ID."
				)";

		if(mysql_query ($sql))
		{
			// [+] STORE SYSTEM LOGS
				/*	$param = array(	
									getStudentNumber($student_id),
									getSYandTerm($term_id)
								   );
					storeSystemLogs(MSG_CASHIER_BALANCE_PAYMENT_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
				// [-] STORE SYSTEM LOGS	
				
				notification(getStudentUser($student_id),MSG_CASHIER_BALANCE_PAYMENT_FOR_STUDENT,$param,USER_ID);
				notification(getParentUser($student_id),MSG_CASHIER_BALANCE_PAYMENT_FOR_STUDENT,$param,USER_ID);
				$admins = getUserAdmin();
				foreach($admins as $admin)
				{
					notification($admin,MSG_CASHIER_BALANCE_PAYMENT_FOR_STUDENT,$param,USER_ID);					
				}*/
				
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_cs_student_balance_payment&student_id='.$student_id.'\';</script>';
			
			//window.open(\'pdf_reports/rep108.php?id='.$student_id.'&pay_id='.mysql_insert_id().'&met=OR\');
			
		}
	}
}
}
else if($action == 'refund')
{	
	//$subject = explode(',',$subject_id);
	//foreach($subject as $sub)
	//{
		$sql = "INSERT INTO tbl_student_payment
				(
					student_id, 
					term_id,
					subject_id,
					amount, 
					is_refund,
					date_created,
					created_by
				) 
				VALUES 
				(
					".GetSQLValueString($student_id,"text").",  
					".CURRENT_TERM_ID.", 
					".GetSQLValueString($subject_id,"text").",
					".GetSQLValueString($amount_paid,"text").",
					"."'Y'".",
					".time().", 
					".USER_ID."
				)";
	//}
		if(mysql_query ($sql))
		{
			// [+] STORE SYSTEM LOGS
					$param = array(	
									getStudentNumber($student_id),
									getSYandTerm($term_id)
								   );
					storeSystemLogs(MSG_CASHIER_REFUND_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
				// [-] STORE SYSTEM LOGS	
				
				notification(getStudentUser($student_id),MSG_CASHIER_REFUND_FOR_STUDENT,$param,USER_ID);
				notification(getParentUser($student_id),MSG_CASHIER_REFUND_FOR_STUDENT,$param,USER_ID);
				$admins = getUserAdmin();
				foreach($admins as $admin)
				{
					notification($admin,MSG_CASHIER_REFUND_FOR_STUDENT,$param,USER_ID);					
				}		
			echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_cs_student_balance_payment&student_id='.$student_id.'\';</script>';
		}
}
else if($action == 'bounce')
{
		$sql = "UPDATE tbl_student_payment
				SET
					is_bounced = ".'"Y"'.",
					date_modified = ".time().",
					modified_by = ".USER_ID."
				WHERE student_id = $student_id AND id = ".$check_id;
				
		if(mysql_query ($sql))
		{	
			// [+] STORE SYSTEM LOGS
					$param = array(	
									getStudentNumber($student_id),
									getSYandTerm($term_id)
								   );
					storeSystemLogs(MSG_CASHIER_BOUNCE_CHECK_PAYMENT_FOR_STUDENT,$param,getStudentUserID($student_id),'N','Y','N','Y');			
				// [-] STORE SYSTEM LOGS	
				
				//notification(getStudentUser($student_id),MSG_CASHIER_BOUNCE_CHECK_PAYMENT_FOR_STUDENT,$param,USER_ID);
				//notification(getParentUser($student_id),MSG_CASHIER_BOUNCE_CHECK_PAYMENT_FOR_STUDENT,$param,USER_ID);
				$admins = getUserAdmin();
				foreach($admins as $admin)
				{
					notification($admin,MSG_CASHIER_BOUNCE_CHECK_PAYMENT_FOR_STUDENT,$param,USER_ID);					
				}		
			
			echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_cs_student_balance_payment&student_id='.$student_id.'\';</script>';
		}
}
else if($action == 'list')
{
	$student_id = '';
	$discount	= '';
	$amount_paid = '';
	$payment_met = '';
	$payment_scheme_id = '';
	$bank_brn = '';
	$check_num = '';
	$OR	= '';
	$remarks = '';
	$surcharge = '';
}

if($view == 'payment')
{	
	$discount				= $_REQUEST['discount'];
	$amount_paid			= $_REQUEST['amount_paid'];
	$payment_met			= $_REQUEST['payment_met'];
	$payment_scheme_id		= $_REQUEST['payment_scheme_id'];
	$bank_brn				= $_REQUEST['bank_brn'];
	$check_num 				= $_REQUEST['check_num'];
	$OR						= $_REQUEST['or'];
	$remarks				= $_REQUEST['rem'];
	$surcharge				= $_REQUEST['surcharge'];
}
// component block, will be included in the template page
$content_template = 'components/block/blk_com_cs_student_balance_payment.php';
?>