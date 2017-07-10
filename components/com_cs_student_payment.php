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

if(USER_IS_LOGGED != '1')
{
	echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main
}
if(in_array($comp,$_SESSION[CORE_U_CODE]['access_components']))
	{
		echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main
	}

$page_title = 'Reservation Payment';
$pagination = 'Student Payment > Reservation Payment';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$student_id 			= $_REQUEST['student_id'];
$term_id				= $_REQUEST['term_id'];
$discount				= $_REQUEST['discount'];
$amount_paid			= $_REQUEST['amount_paid'];
$payment_met			= $_REQUEST['payment_met'];
$units					= $_REQUEST['units'];
$subject_id				= $_REQUEST['subject_id'];
$schedule_id_dispalay	= $_REQUEST['schedule_id_dispalay'];
$sub_total 				= $_REQUEST['sub_total'];
$scheme_id				= $_REQUEST['scheme_id'];
$down					= $_REQUEST['down'];

$bank_brn				= $_REQUEST['bank_brn'];
$check_num 				= $_REQUEST['check_num'];

$rows 				= $_REQUEST['rows'];
$page 				= $_REQUEST['page'];

if($bank_brn == '')
	{
		$bank_brn = 'none';
	}
if($check_num == '')
	{
		$check_num = '0';
	}
if($amount_paid == $sub_total)
	{
		$payment_term = 'F';
	}
	else
	{
		$payment_term = 'P';
	}
	
if($action == 'save')
{
	if($payment_met == '' || $amount_paid =='')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if($amount < $down)
	{
		$err_msg = 'Insufficient Payment! Payment must be more than or equal to Php '.number_format($down, 2, ".", ",");
	}
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
					date_created,
					created_by
				) 
				VALUES 
				(
					".GetSQLValueString($student_id,"text").",  
					".CURRENT_TERM_ID.", 
					".GetSQLValueString($discount,"int").",
					".GetSQLValueString($scheme_id,"int").",
					".GetSQLValueString($amount_paid,"text").",
					".GetSQLValueString($payment_met,"int").",
					".GetSQLValueString($payment_term,"text").",  
					".GetSQLValueString($bank_brn,"text").",
					".GetSQLValueString($check_num,"text").",
					".time().", 
					".USER_ID."
				)";
			
		if(mysql_query ($sql))
		{
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
						".USER_ID.",	
						".time().",
						".USER_ID.",		
						".time()."
					)";

				mysql_query($sql_sched);
				
				$sql_status = "UPDATE tbl_student_enrollment_status SET 
					enrollment_status =".GetSQLValueString('E',"text").", 
					date_enrolled = ".time() ." 
					WHERE student_id = " .$student_id;	
				mysql_query($sql_status);					
				
				$sql_del ="DELETE FROM tbl_student_reserve_subject WHERE id = " . $row['id'];
				mysql_query($sql_del);
		
			}	
			
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_cs_student_payment\';</script>';
		}
	}
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_cs_student_payment.php';
?>