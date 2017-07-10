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







/*if($_REQUEST['discount_id'] !='undefined' && $_REQUEST['discount_id'] !='0')

{

	$discount_id 	= $_REQUEST['discount_id'];

	$student_id 	= $_REQUEST['student_id'];

	$scheme_id 	= $_REQUEST['scheme_id'];

}*/

	$discount_id 	= $_REQUEST['discount_id'];
	
	$surcharge 	= $_REQUEST['surcharge'];

	$student_id 	= $_REQUEST['student_id'];

	$scheme_id 	= $_REQUEST['scheme_id'];

	

	/* TOTAL AMOUNT */

	$sql = "SELECT * FROM tbl_school_fee WHERE term_id = ".CURRENT_TERM_ID." AND publish =  'Y'";

	$result = mysql_query($sql);

	$sub_total = 0;

	while($row = mysql_fetch_array($result)) 

	{

		$total = getStudentAmountFeeByFeeId($row['id'],$student_id);           

		$sub_total += $total;

	}

	

	/* TOTAL OTHER PAYMENT */

	$sql_fee_other = "SELECT f.fee_type,s.*,sum(s.amount) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND f.id=s.fee_id AND s.is_removed = 'N' AND s.student_id = ".$student_id." AND s.term_id =" .CURRENT_TERM_ID;
	$result_fee_other = mysql_query($sql_fee_other);

	$row_fee_other = mysql_fetch_array($result_fee_other);

	$sub_mis_total = 0;

	$sub_mis_total = $row_fee_other['sum(s.amount)'];

	

	

	/* TOTAL LEC PAYMENT */

			$sql_lec = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlec' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$student_id." AND s.term_id =" .CURRENT_TERM_ID;
			

			$qry_lec = mysql_query($sql_lec);

			$row_lec = mysql_fetch_array($qry_lec);

			$sub_lec_total = 0;

			//$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$student_id);           

			$sub_lec_total = $row_lec['sum(s.quantity)']*$row_lec['amount'];

			

			/* TOTAL LAB PAYMENT */

			$sql_lab = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlab' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$student_id." AND s.term_id =" .CURRENT_TERM_ID;

			$qry_lab = mysql_query($sql_lab);

			$row_lab = mysql_fetch_array($qry_lab);

			$sub_lab_total = $row_lab['sum(s.quantity)']*$row_lab['amount'];

	

	/*TOTAL LEC AND LAB = LEC + LAB*/

	$sub_total =  $sub_lec_total + $sub_lab_total + $sub_mis_total;

	

	/*TOTAL UNITS = TUITION FEE - TOTAL OTHER PAYMENT*/

	$total_lec_lab = $sub_total - $sub_mis_total;

	

	//SCHEME

	$sqlsch = "SELECT *

                        FROM tbl_payment_scheme_details

                        WHERE scheme_id = ".$scheme_id;

                        

	$resultsch = mysql_query($sqlsch);



	while($rowsch = @mysql_fetch_array($resultsch)) 

	{

		if($rowsch['sort_order'] == 1)

		{

			$downpay = $rowsch['payment_value'];

		}

	}

?>   

<table class="classic_borderless">

    <tr>

        <td>Total Lecture Fee Amount:</td>

        <td><div align="right">

        Php <?=number_format($sub_lec_total, 2, ".", ",")?>

        </div></td>

    </tr>

    <tr>

        <td>Total Laboratory Fee Amount:</td>

        <td><div align="right">

        Php <?=number_format($sub_lab_total, 2, ".", ",")?>

        </div></td>

    </tr>

    <tr>

        <td>Total Miscelleneous Fee Amount:</td>

        <td><div align="right">

        Php <?=number_format($sub_mis_total, 2, ".", ",")?>

        </div></td>

    </tr>

     <tr>

        <td colspan="2" class="bottom"></td>

    </tr>

    <tr>

        <td><strong>Total Tuition Fee Amount:</strong></td>

        <td><div align="right"><strong>

        Php <?=number_format($sub_total, 2, ".", ",")?></strong>

        </div></td>

    </tr>
    
     <tr>

        <?php

        /*MBT
		if($_REQUEST['discount_id'] !='undefined' && $_REQUEST['discount_id'] !='0')

		{

			$sql_discount = "SELECT * FROM tbl_discount WHERE term_id = ".CURRENT_TERM_ID." AND publish = 'Y' AND id=" .$discount_id;

			$qry_discount = mysql_query($sql_discount);

			$row_discount = mysql_fetch_array($qry_discount);

			$discount = $row_discount['value'];

			

			/*TOTAL DISCOUNT = TOTAL UNIT LEC/LAB / 100%  x DISCOUNT

			$total_discounted = $sub_total / 100 * $discount;*/
			
			/*if($scheme_id!='')
			{
			$sql2 = "SELECT * FROM tbl_payment_scheme WHERE id=".$scheme_id;

			$query2 = mysql_query($sql2);

			$row2 = mysql_fetch_array($query2);
			
			$surcharge = $row2['surcharge']*getStudentReservedUnit($student_id);
			
			}else{
			$surcharge = GetSchemeForSurcharge2($student_id,CURRENT_TERM_ID)*getStudentReservedUnit($student_id);
			}*/
			$sqlschm2 = "SELECT * FROM tbl_student_enrollment_status WHERE term_id=".CURRENT_TERM_ID." AND student_id=".$student_id;
			$queryschm2 = mysql_query($sqlschm2);
			$rowschm2 = mysql_fetch_array($queryschm2);
			
			$sqlschm = "SELECT * FROM tbl_payment_scheme WHERE id=".$rowschm2['scheme_id'];
			$queryschm = mysql_query($sqlschm);
			$rowschm3 = mysql_fetch_array($queryschm);
			
			$surcharge = $rowschm3['surcharge']*getStudentReservedUnit($student_id);
			
			$sql='SELECT * FROM tbl_student WHERE id = '.$student_id;
			$query = mysql_query($sql);
			$rows = mysql_fetch_array($query);
			
			$sqlt = 'SELECT * FROM tbl_school_year_term WHERE id='.CURRENT_TERM_ID;
			$queryt = mysql_query($sqlt);
			$rowt = mysql_fetch_array($queryt);
			$t = strtolower($rowt['school_term'])=='summer'?0:5000;
echo getStudentNextYearLevel($_REQUEST['id']);
			if($rows['scholarship_type']=='A')
			{
				getStudentNextYearLevel($student_id)>3?$t=0:'';
				  $discount = ($sub_total+$surcharge)-$t;
				$discount = ($discount*$rows['scholarship'])/100;

			}
			
			else
			{

            $discount = $sub_lec_total+$surcharge;
			$discount = ($discount*$rows['scholarship'])/100;
			}
			
			
			?>

			 <td>Discount</td>
             

			<td><div align="right">Php <?=number_format($discount, 2, ".", ",")?></div></td>

        <?php

       /* }

        else

        {

		?>

            <td>Discount</td>

            <td><div align="right">Php 0.00</div></td>

            <?php

		}
*/
		
		?>

    </tr>
    <tr>
      <td>Surcharge</td>
      <td><div align="right">Php <?=number_format($surcharge, 2, ".", ",")?></div></td>
    </tr> 

     <?php

					$credit =0; //getCarriedBalances($student_id,CURRENT_TERM_ID);

					$debit = getCarriedDebits($student_id,CURRENT_TERM_ID);

					$sub_total = $sub_total-$discount;

					$sub_total = abs($sub_total - $debit);

					$sub_total = $sub_total + $credit;

					?>

					<!--<tr>

						<td>Carried Balances:</td>

						<td><div align="right">

						Php <?=number_format($credit, 2, ".", ",")?>

						</div></td>

					</tr>

					<tr>

						<td>Carried Debit Balances:</td>

						<td><div align="right">

						Php <?=number_format($debit, 2, ".", ",")?>

						</div></td>

					</tr>!-->

                     <tr>

                        <td colspan="2" class="bottom"></td>

                    </tr>

                   <!-- <tr>

                        <td><strong>SubTotal:</strong></td>

                        <td><div align="right"><strong>

                        Php <?=number_format($sub_total, 2, ".", ",")?></strong>

                        </div></td>

                    </tr>!-->


    <tr>

        <td colspan="2" class="bottom"></td>

    </tr>

    	<?php

		$sub_total = $sub_total +$surcharge;
		$total_fee = $sub_total;

		?>

    <tr>

        <td><strong>Total Charges:</strong></td>

        <td><div align="right"><strong>

        Php 

        <?=number_format($sub_total, 2, ".", ",")?>

        </strong></div></td>

    	<input type="hidden" name="total" id="total" value="<?=$sub_total?>" />

    </tr>

 </table>

<?php

	if($scheme_id != 0)

	{

		if($sub_total > $downpay )

		{

		$cond = 'true';

?> 

<p>

 <table class="classic_borderless">

        	<tr>

         	<td><strong>Schedule of Fees</strong></td>

            </tr>

			<?php	

				$sqlsch = "SELECT *

                        FROM tbl_payment_scheme_details

                        WHERE scheme_id = ".$scheme_id." ORDER BY sort_order";

                        

                $resultsch = mysql_query($sqlsch);
		
			

                while($rowsch = mysql_fetch_array($resultsch)) 

                {

					if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'A')

					{

						$topay = $rowsch['payment_value'];

						$total_fee = $sub_total - $topay;

						$initial = $rowsch['id'];

					}

					else if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'P')

					{

						$topay = abs(getStudentPaymentScheme($rowsch['id'],$total_fee));;

						//$total_fee = $sub_total - $down;

						$initial = $rowsch['id'];

					}

					else if($rowsch['payment_type'] == 'P')

					{

						$topay = abs(getStudentPaymentScheme($rowsch['id'],$total_fee));

					}

		     ?>

             	<tr>

                    <td><?=$rowsch['payment_name'].' on/before('.$rowsch['payment_date'].')'?></td>

                  	<td>Php <?=number_format($topay, 2, ".", ",")?>

                    <input type="hidden" name="initial" id="initial" value="<?=$initial?>" />

                    <input type="hidden" name="downpay" id="downpay" value="<?=$down?>" />

                    </td>

                </tr>

             <?php

               //	$order++;//}

			   }

            ?>                                

          </table>

<?php }

	else

{

	$cond = 'false';

	echo '<div id="message_container"><h4>Invalid Discount. Total fee is less than the initial Payment.</h4></div>';

}

}
		
		if($scheme_id == 0)

	{

?> 

<p>

 <table class="classic_borderless">

        	<tr>

         	<td><strong>Schedule of Fees</strong></td>

            </tr>

			<?php	

				$sql = "SELECT *

                        FROM tbl_student_enrollment_status

                        WHERE term_id = ".CURRENT_TERM_ID." AND student_id = ".$student_id."";

                        

                $result = mysql_query($sql);
				$row = mysql_fetch_array($result);
		
			$sqlsch = "SELECT *

                        FROM tbl_payment_scheme_details

                        WHERE scheme_id = ".$row['scheme_id']." ORDER BY sort_order";

                        

                $resultsch = mysql_query($sqlsch);
		
			

                while($rowsch = mysql_fetch_array($resultsch)) 

                {

					if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'A')

					{

						$topay = $rowsch['payment_value'];

						$total_fee = $sub_total - $topay;

						$initial = $rowsch['id'];

					}

					else if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'P')

					{

						$topay = abs(getStudentPaymentScheme($rowsch['id'],$total_fee));;

						//$total_fee = $sub_total - $down;

						$initial = $rowsch['id'];

					}

					else if($rowsch['payment_type'] == 'P')

					{

						$topay = abs(getStudentPaymentScheme($rowsch['id'],$total_fee));

					}

		     ?>

             	<tr>

                    <td><?=$rowsch['payment_name'].' on/before('.$rowsch['payment_date'].')'?></td>

                  	<td>Php <?=number_format($topay, 2, ".", ",")?>

                    <input type="hidden" name="initial" id="initial" value="<?=$initial?>" />

                    <input type="hidden" name="downpay" id="downpay" value="<?=$down?>" />

                    </td>

                </tr>

             <?php

               //	$order++;//}

			   }

            ?>                                

          </table>

<?php 
					

}

 ?>

 <input type="hidden" name="con" id="con" value="<?=$cond?>" />