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

	include_once('../includes/functions.php');	

	include_once('../includes/common.php');	

//
$sql = "SELECT * FROM tbl_student";
$query = mysql_query($sql);


while($row = mysql_fetch_array($query))
{

if(checkIfStudentIsEnroll($row['id']))

{
	
		/* TOTAL LEC PAYMENT */

			$sql_lec = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlec' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$row['id']." AND s.term_id =" .CURRENT_TERM_ID;
			

			$qry_lec = mysql_query($sql_lec);

			$row_lec = mysql_fetch_array($qry_lec);

			$sub_lec_total = 0;

			//$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$student_id);           

			$sub_lec_total = $row_lec['sum(s.quantity)']*$row_lec['amount'];

			

			/* TOTAL LAB PAYMENT */

			$sql_lab = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlab' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$row['id']." AND s.term_id =" .CURRENT_TERM_ID;

			$qry_lab = mysql_query($sql_lab);

			$row_lab = mysql_fetch_array($qry_lab);

			$sub_lab_total = $row_lab['sum(s.quantity)']*$row_lab['amount'];

             $sql = "SELECT f.fee_type,s.* FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND f.id=s.fee_id AND s.is_removed = 'N' AND s.student_id = ".$row['id']." AND s.term_id =" .CURRENT_TERM_ID;

            $result = mysql_query($sql);

            $sub_total = 0;

            while($row_mc = mysql_fetch_array($result)) 

            {

                $total = $row_mc['amount']*$row_mc['quantity'];       

                $sub_total += $total;
				
				$sub_mis_total += $total;

           }


	$sub_total = $sub_total+$sub_lec_total;


			$sql_disc = "SELECT * FROM tbl_student_payment WHERE student_id=" .$row['id'] ." AND term_id=" .CURRENT_TERM_ID;

			$qry_disc = mysql_query($sql_disc);

			$row_disc = @mysql_fetch_array($qry_disc);

			$disc = $row_disc['discount_id'];

			$paymentId = $row_disc['payment_scheme_id'];

		
			$surcharge = GetSchemeForSurcharge($row['id'])*$total_units;
			
			$sqldis = 'SELECT * FROM tbl_student WHERE id='.$row['id'];
			$querydis = mysql_query($sqldis);
			$rowdis = mysql_fetch_array($querydis);
			
			if($rowdis['scholarship_type']=='A')
			{
				$discount = ($sub_total+$surcharge)-5000;
				$discount = ($discount*$rowdis['scholarship'])/100;
			}
			
			else
			{

				$discount = $sub_lec_total+$surcharge;
				$discount = ($discount*$rowdis['scholarship'])/100;
			}
		

		$credit = getCarriedBalances($row['id'],CURRENT_TERM_ID);

		$debit = getCarriedDebits($row['id'],CURRENT_TERM_ID);

		$sub_total = $sub_total-$total_discounted;

		$sub_total = abs($sub_total - $debit);

		$sub_total = $sub_total + $credit;
		
		$sub_total = $sub_total - $discount;
		$sub_total = $sub_total + $surcharge;
		
		$total_fee = $sub_total;


    	//TOTAL PAYMENT

		$total_payment = getTotalPaymentOfStudent($row['id'],CURRENT_TERM_ID); 



		//TOTAL

		if(checkIfStudentPaidFull($row['id'])&&$total_refund != 0)

		{

			$total_rem = $sub_total-$total_payment;

		}

		else if($total_payment > $sub_total)

		{

			$total_rem = 0;

		}

		else

		{

			$total_rem = ($sub_total - $total_payment);

		}

		if($total_rem > 0)
		{
			
			echo $sql = "INSERT INTO tbl_student_payment
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
					".GetSQLValueString($row['id'],"text").",  
					".CURRENT_TERM_ID.", 
					".GetSQLValueString($total_rem,"text").",
					".GetSQLValueString($paymentId,"int").",
					1,
					".GetSQLValueString('F',"text").",  
					".GetSQLValueString('none',"text").",
					".GetSQLValueString('0',"text").",
					".GetSQLValueString('0',"text").",
					".GetSQLValueString('',"text").",
					".time().", 
					1
				)";

		if(mysql_query ($sql))
		{
			echo 'paid-'.$row['id'];	
		}
		else
		{
			echo 'fail payment';
		}
			
		}
		else
		{
			'0 balance';	
		}
		
	}
}

	?>