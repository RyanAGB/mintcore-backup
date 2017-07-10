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
$payment_scheme_id		= $_REQUEST['payment_scheme_id'];
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


	
if($action == 'save')
{

			
			$sql_scheme = "UPDATE tbl_student_enrollment_status SET 
				scheme_id =".GetSQLValueString($payment_scheme_id,"int")."
				WHERE student_id = " .$student_id." AND term_id=".CURRENT_TERM_ID;
			if(mysql_query($sql_scheme))
			{		
		
				
				// [+] STORE SYSTEM LOGS
					$param = array(	
									getStudentNumber($student_id),
									getSYandTerm($term_id)
								   );
					storeSystemLogs("BURSAR SETS PAYMENT PLAN",$param,getStudentUserID($student_id),'N','Y','N','Y');			
				// [-] STORE SYSTEM LOGS	
				

			
			echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_cs_student_payment_plan&student_id='.$student_id.'\';</script>';
		
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
$content_template = 'components/block/blk_com_cs_student_payment_plan.php';
?>