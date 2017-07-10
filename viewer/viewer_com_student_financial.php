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



if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp']))

{

	header('Location: ../forbid.html');

}

else

{



$id = $_REQUEST['student_id'];

$term_id = $_REQUEST['filter_schoolterm'];

?>

<script type="text/javascript">

	$(function(){

		$('.selector').click(function() {

			//var id = $(this).attr("val");		

			var valTxt = $(this).attr("returnTxt");

			var valId = $(this).attr("returnId");

			$('#room_id').attr("value", valId);

			$('#room_display').attr("value", valTxt);

			

			$('#dialog').dialog('close');			

		});

		

		$('#print').click(function() {

			var w=window.open();

			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');

			w.document.write($('#lookup_content').html());

			w.document.close();

			w.focus();

			w.print();

			//w.close()

			return false;

		});

		

		$('#pdf').click(function() {

			var w=window.open ("pdf_reports/rep108.php?id="+<?=$id?>+"&met=financial"+"&trm="+<?=$term_id?>); 

			return false;

		});

		

		$('#email').click(function() {

			

			if(confirm("Are you sure you want to email this file to this student?"))

			{

				$.ajax({

					type: "POST",

					data: "id="+<?=$id?>+"&trm="+<?=$term_id?>+"&met=financial&email=1",

					url: "pdf_reports/rep108.php",

					success: function(msg){

						if (msg != ''){

							alert('Sending document by email failed.');

							return false;

						}else{

							alert('Email successfully sent.');

							return false;

						}

					}

					});	

					

			}

			else

			{

				return false;

			}

		

		});

		

	});

</script>



<?php

	

	$sql = "SELECT * FROM tbl_student WHERE id = ".$id;						

	$query = mysql_query($sql);

	$row = mysql_fetch_array($query);

	

	$sqlfee = "SELECT * FROM tbl_school_fee WHERE publish =  'Y'";         

	$resultfee = mysql_query($sqlfee);

	

	$sql_stud_payment = "SELECT * FROM tbl_student_payment WHERE term_id = $term_id AND student_id =" .$id;

	$qry_stud_payment = mysql_query($sql_stud_payment);

	$ctr_stud_payment = mysql_num_rows($qry_stud_payment);

?>

<div id="lookup_content">

<div id="printable">

<div class="body-container">

<div class="header">

<table width="100%">

  <tr>

    <td width="18%" valign="top" class="bold">Student Name:</td>

    <td width="82%"><?=$row['lastname']. ", " . $row['firstname'] ." " . $row['middlename']?></td>

  </tr>

  <tr>

    <td class="bold" valign="top">Student Number:</td>

    <td><?=$row['student_number']?></td>

  </tr>

  <tr>

    <td class="bold" valign="top">Course:</td>

    <td><?=getCourseName($row['course_id'])?></td>

  </tr>

   <tr>

        <td class='bold' valign="top">School Year:</td>

        <td><?=getSYandTerm($term_id)?></td>

      </tr>

  <tr>

    <td>&nbsp;</td>

</tr>

  <tr>

    <td colspan=2 class="title-action">

    <?php

if($ctr_stud_payment > 0)

{

?>

     <a class="viewer_email" href="#" id="email" title="email"></a>

    <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>

    <a class="viewer_print" href="#" id="print" title="print"></a>

    <?php } ?>

    </td>

</tr>



</table>

 

</div>

<div class="content-container">



<div class="content-wrapper-withoutBorder">

<?php

if($ctr_stud_payment > 0)

{

?>

    <table width="100%" >

        <tr>

            <td width="50%" valign="top">

              <table class="classic_withoutWidth" width="100%">

                    <tr>

                        <th colspan="3">ASSESSMENT OF FEES</th>

                    </tr>

                    <tr>

                        <th class="col1_200">Fees</th>

                        <th class="col1_100">Amount</th>

                        <th class="col1_100">Total</th>

                    </tr> 

                <?php

                    

					$x = 1;

					$sql = "SELECT *

							FROM tbl_school_fee

							WHERE publish =  'Y'

							AND term_id= $term_id";

					$result = mysql_query($sql); 

                    $sub_total = 0;

                    while($row = mysql_fetch_array($result)) 

                    {

                        $total = getStudentTotalFeeLecLab($row['id'],$id );

                ?>

                    <tr class="<?=($x%2==0)?"":"highlight";?>"> 

                        <td><?=$row['fee_name'].' ('.number_format(getFeeUnit($row['id'],$id), 2, ".", ",").')'?></td>

                        <td><?=getFeeAmount($row['id'])?></td>

                        <td>

                          <div align="right">

                            Php <?=$total==''?'0.00':number_format($total, 2, ".", ",")?>

                          </div></td>

                    </tr>

                <?php           

                        $sub_total += $total;

                        $x++;

                   }

                ?>  

                    <tr>

                        <td>&nbsp;</td>

                        <td><strong>Total</strong></td>

                        <td>

                          <div align="right">

                            Php <?=number_format($sub_total, 2, ".", ",")?>

                          </div></td>

                    </tr>                              

              </table>



            <p>&nbsp;</p>

              <table class="classic_withoutWidth" width="100%">

                    <tr>

                        <th colspan="3">OTHER FEES</th>

                    </tr>

                    <tr>

                        <th class="col1_150">Fees</th>

                        <th class="col1_150">Amount</th>

                        <th class="col1_150">Total</th>

                    </tr> 

                <?php

                    $sql = "SELECT *

							FROM tbl_other_payments

							WHERE student_id = $id

							AND term_id= $term_id";

					$result = mysql_query($sql); 

                    $sub_ototal = 0;

					if(mysql_num_rows($result)>0)

					{

					$x = 1;

                    while($row = mysql_fetch_array($result)) 

                    {

						$sql2 = "SELECT *

							FROM tbl_payment_types

							WHERE id = ".$row['type_id'];

						$result2 = mysql_query($sql2);

						$row2 = mysql_fetch_array($result2);

                ?>

                    <tr class="<?=($x%2==0)?"":"highlight";?>"> 

                        <td><?=$row2['name']?></td>

                        <td><?=$row['amount']?></td>

                        <td>

                          <div align="right">

                            Php <?=$total==''?'0.00':number_format($row['amount'], 2, ".", ",")?>

                          </div></td>

                    </tr>

                <?php           

                        $sub_ototal += $row['amount'];

                        $x++;

                   } 

				   }

				   else

				   {

                ?>  

                	 <tr>

                        <td colspan="3">No Other Payments</td>

                    </tr>                          

                <?php           

				  }

                ?> 

                    <tr>

                        <td>&nbsp;</td>

                        <td><strong>Total</strong></td>

                        <td>

                          <div align="right">

                            Php <?=number_format($sub_ototal, 2, ".", ",")?>

                          </div></td>

                    </tr>                              

              </table> 

              

            </td>

            <td width="50%">

            

			<?php

            /* TOTAL OTHER PAYMENT */

            $sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id= $term_id";

            $result_fee_other = mysql_query($sql_fee_other);

            $row_fee_other = mysql_fetch_array($result_fee_other);

            $sub_mis_total = 0;

            $mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$id);           

            $sub_mis_total += $mis_total;

            

            

            /* TOTAL LEC PAYMENT */

            $sql_lec = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlec' AND publish =  'Y' AND term_id= $term_id";

            $qry_lec = mysql_query($sql_lec);

            $row_lec = mysql_fetch_array($qry_lec);

            $sub_lec_total = 0;

            

            

            $lec_total = getStudentTotalFeeLecLab($row_lec['id'],$id);           

            $sub_lec_total += $lec_total;

            

            /* TOTAL LAB PAYMENT */

            $sql_lab = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlab' AND publish =  'Y' AND term_id= $term_id";

            $qry_lab = mysql_query($sql_lab);

            $row_lab = mysql_fetch_array($qry_lab);

            $sub_lab_total = 0;

            

            

            $lab_total = getStudentTotalFeeLecLab($row_lab['id'],$id);           

            $sub_lab_total += $lab_total;

            

            /*TOTAL LEC AND LAB = LEC + LAB*/

            $total_lec_lab =  $sub_lec_total + $sub_lab_total;

			

			//TOTAL PAYMENT

				$total_payment = getTotalPaymentOfStudent($id,term_id); 

				

                $sql_total_payment = "SELECT sum(amount) FROM tbl_student_payment WHERE  term_id = $term_id AND is_bounced <> 'Y' AND is_refund <> 'Y' AND student_id =" .$id;

                $qry_total_payment = mysql_query($sql_total_payment);

                $row_total_payment = mysql_fetch_array($qry_total_payment);	

                $row_total_payment['amount'];

              

			  //DISCOUNT

			  $sql_payment = "SELECT * FROM tbl_student_payment WHERE term_id = $term_id AND  student_id =" .$id;

                    $qry_payment = mysql_query($sql_payment);

                    $row_payment = mysql_fetch_array($qry_payment);

                    

                    $total_charges = $sub_total - $total;

                    

                   $total_discounted = getStudentDiscount($row_payment['discount_id'], $id, $total_lec_lab); 

				

			/* TOTAL REFUND */

			$sql = "SELECT * FROM tbl_student_payment WHERE is_refund =  'Y' AND student_id = ".$id." AND term_id=" .$term_id;

			$result = mysql_query($sql);

			$ref_total = 0;

			while($row = mysql_fetch_array($result)) 

			{           

				$ref_total += $row['amount'];

			}

	

				$total_refund = getTotalRefundAmount($id,$term_id);

			

			$lib = getLibraryDueFee($id);

			

        	?>

            

              	<table class="classic_borderless" width="100%">

                   <tr>

        <td>Total Lecture Fee Amount:</td>

        <td><div align="right">

        Php <?=number_format($lec_total, 2, ".", ",")?>

        </div></td>

    </tr>

    <tr>

        <td>Total Laboratory Fee Amount:</td>

        <td><div align="right">

        Php <?=number_format($lab_total, 2, ".", ",")?>

        </div></td>

    </tr>

    <tr>

        <td>Total Miscelleneous Fee Amount:</td>

        <td><div align="right">

        Php <?=number_format($mis_total, 2, ".", ",")?>

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

                   <!-- <tr>

                        <td>Balance Carried Forward:</td>

                        <td><div align="right">Php 0.00</div></td>

                    </tr>    

                    <tr>

                    <td><strong>Other Charges</strong></td>

                    </tr>

                    <tr>

                    <td>Library Due Charge:</td>

                    <td><div align="right">

                     Php <?=number_format($lib, 2, ".", ",")?>

                    </div></td>

                    </tr>            

                    <tr>

                        <td>Current Charges:</td>

                        <td><div align="right">

                          Php <?=number_format($sub_total, 2, ".", ",")?>

                        </div></td>

                    </tr>

                    <tr>

                        <td><strong>Total Current Charges:</strong></td>

                        <td><div align="right">

                          Php <?=number_format($sub_total, 2, ".", ",")?>

                        </div></td>

                    </tr>!-->

                    <?php

                   	 /*                    

                    if($row_payment['discount_id'] > '0')

                    {

                    ?>

                        <tr>

                          <td>Discount - <?=getDiscountName($row_payment['discount_id'])?>(<?=getDiscountValue($row_payment['discount_id'])?>%)</td>

                          <td>

                            <div align="right">

                                Php <?=number_format($total_discounted, 2, ".", ",")?>	

                            </div>

                          </td>

                        </tr>

                    <?php

                    }

                    else

                    {*/
					
					$surcharge = GetSchemeForSurcharge($id)*getEnrolledTotalUnits($term_id,$id);
			
			$sqldis = 'SELECT * FROM tbl_student WHERE id='.$id;
			$querydis = mysql_query($sqldis);
			$rowdis = mysql_fetch_array($querydis);
			
			if($rowdis['scholarship_type']=='A')
			{
				  $discount = ($sub_total+$surcharge)-getLearnerfee();
				$discount = ($discount*$rowdis['scholarship'])/100;
			}
			
			else
			{

            $discount = $lec_total+$surcharge;
			$discount = ($discount*$rowdis['scholarship'])/100;
			}

                    ?>  

                        <tr>

                          <td>Discount</td>

                          <td><div align="right">Php <?=number_format($discount, 2, ".", ",")?></div></td>

                        </tr>
                        
                         <tr>

                          <td>Surcharge</td>

                          <td><div align="right">Php <?=number_format($surcharge, 2, ".", ",")?></div></td>

                        </tr>

                    <?php

                   // }

					$credit = getCarriedBalances($id,$term_id);

					$debit = getCarriedDebits($id,$term_id);

					$sub_total = $sub_total-$total_discounted;

					$sub_total = abs($sub_total - $debit);

					$sub_total = $sub_total + $credit;
					
					$sub_total = ($sub_total+$surcharge) - $discount;

					$total_fee = $sub_total;

					?>

					<tr>

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

					</tr>

                     <tr>

                        <td colspan="2" class="bottom"></td>

                    </tr>

                    <tr>

                        <td><strong>SubTotal:</strong></td>

                        <td><div align="right"><strong>

                        Php <?=number_format($sub_total, 2, ".", ",")?></strong>

                        </div></td>

                    </tr>

                   <tr>

                        <td>Refunded Amount:</td>

                        <td><div align="right">

                        Php <?=number_format($ref_total, 2, ".", ",")?>

                        </div></td>

                    </tr>

                        <tr>

                        <td>Refund Balance:</td>

                        <td><div align="right">

                        Php <?=number_format($total_refund, 2, ".", ",")?>

                        </div></td>

                    </tr>

                     <tr>

                        <td colspan="2" class="bottom"></td>

                    </tr>

                    <tr>

                        <td><strong>Total Refunded:</strong></td>

                        <td><div align="right"><strong>

                        Php <?=number_format(abs($total_refund-$ref_total), 2, ".", ",")?>

                        </strong></div></td>

                    </tr>

                    <!--<tr>

                        <td><strong>Total Charges:</strong></td>

                        <td><div align="right"><strong>

                        <?php

                       // $sub_total = ($sub_total - $total_discounted);

                        ?>

                        Php 

                        <?=number_format($sub_total, 2, ".", ",")?>

                        </strong></div></td>

                    </tr>!-->

                       

                    <tr>

                        <td><strong>Total Payment:</strong></td>

                        <td><div align="right"><strong>

                        Php 

                        <?=number_format($row_total_payment['sum(amount)'], 2, ".", ",")?>

                        </strong></div></td>

                        <input type="hidden" name="sub_total" id="sub_total" value="<?=$row_total_payment['amount']?>" />

                    </tr>

                        <?php

                          // $sub_total = ($sub_total - $total_discounted)-($total_payment);

				//$sub_total = $sub_total - $total_discounted;

						if(checkIfStudentPaidFull($id)&&$total_refund!=0)

						{

							$total_rem_bal = $sub_total-$row_total_payment['sum(amount)'];

						}

						else if($row_total_payment['sum(amount)'] > ($sub_total))

						{

							$total_rem_bal = 0;

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

							$total_rem_bal = $sub_total-$row_total_payment['sum(amount)'];

						}

						$sqlo = "SELECT * FROM tbl_other_payments WHERE student_id=".$id." AND term_id=".CURRENT_TERM_ID;

						$queryo = mysql_query($sqlo);

						if(mysql_num_rows($queryo)>0)

						{

							while($rowo = mysql_fetch_array($queryo))

							{

								$other = $rowo['amount'];

							}

						}else{

							$other = 0;

						}

                        ?>

                        <tr>

                        <td><strong>Total Other Payment:</strong></td>

                        <td><div align="right"><strong>

                        Php 

                        <?=number_format($other, 2, ".", ",")?>

                        </strong></div></td>

                    </tr>

                        <tr>

                        <td colspan="2" class="bottom"></td>

                    </tr>

                    <tr>

                        <td><strong>Total Remaining Balance:</strong></td>

                        <td><div align="right"><strong>

                        Php 

                        <?=number_format($total_rem_bal, 2, ".", ",")?>

                        </strong></div></td>

                    </tr>    

                                                

              </table>

    

            </td>

        </tr>

    </table>

    <?php

    $sql = "SELECT * FROM tbl_student_payment WHERE  is_refund <> 'Y' AND term_id = $term_id AND student_id = " .$id ." ".$sqlcondition;

    $result = mysql_query($sql); 

	

	$sqlref = "SELECT * FROM tbl_student_payment WHERE  is_refund = 'Y' AND term_id = $term_id AND student_id = " .$id ." ".$sqlcondition;

    $resultref = mysql_query($sqlref);

	

    if (mysql_num_rows($result) > 0)

    {

        $x = 0;

        ?>

        <p>&nbsp;</p>

        <div class="fieldsetContainer100">

        <table class="classic" style="width:750px">     

        	<tr>

                <th class="col1_150" colspan="7">Payments</th>

            </tr> 

            <tr>

                <th class="col1_150">Term</th>  

                <th class="col1_50">Amount</th>   

                <th class="col1_50">Payment term</th>  

                <th class="col1_50">Payment method</th>

                <th class="col1_50">Remark</th>  

                <th class="col1_50">Date created</th> 

                <th class="col1_50">Prepared by</th> 

            </tr>

        <?php

        while($row = mysql_fetch_array($result)) 

        { 

        ?>

            <tr class="<?=($x%2==0)?"":"highlight";?>">

                <td><?=getSYandTerm($row["term_id"])?></td>

                <td>Php <?=$row["amount"]?></td>

                <td><?=getPaymentTerm($row["payment_term"])?></td>

                <td><?=getPaymentMethod($row["payment_method"])=='Cash'?'Cash':'Cheque Payment '.$row["bank"].'('.$row["check_no"].')'?></td>

                <td><?=$row['is_bounced']=='Y'?'Bounced Check':''?></td>

                <td><?=date('M d Y h:m:s',$row['date_created'])?></td>

                <td><?=getEmployeeFullName($row["created_by"])?></td>

            </tr>

            

        <?php          

        }

	  }	 

		 else

	  {

	  		echo '<td colspan="7">No Payments</td>';

	  }

        ?>

        <br/>

        <p>&nbsp;</p>

        <div class="fieldsetContainer100">

        <table class="classic" style="width:750px">   

        	<tr>

                <th class="col1_150" colspan="5">Refunds</th>

            </tr>    

            <tr>

                <th class="col1_150">Term</th>  

                <th class="col1_50">Amount</th>   

                <!--<th class="col1_50">Payment term</th>  

                <th class="col1_50">Payment method</th>!-->

                <th class="col1_50">Remark</th>  

                <th class="col1_50">Date created</th> 

                <th class="col1_50">Prepared by</th> 

            </tr>

        <?php	  

	if (mysql_num_rows($resultref) > 0)

    {

        while($rowref = mysql_fetch_array($resultref)) 

        { 

        ?>

            <tr class="<?=($x%2==0)?"":"highlight";?>">

                <td><?=getSYandTerm($rowref["term_id"])?></td>

                <td>Php <?=$rowref["amount"]?></td>

                <!--<td><?=getPaymentTerm($rowref["payment_term"])?></td>

                <td><?=getPaymentMethod($rowref["payment_method"])?></td>!-->

                <td>Refunded</td>

                <td><?=date('M d Y h:m:s',$rowref['date_created'])?></td>

                <td><?=getEmployeeFullName($rowref["created_by"])?></td>

            </tr>

            

        <?php          

        }

	  }

	  else

	  {

	  		echo '<td colspan="5">No Refunds</td>';

	  }

	

	if(mysql_num_rows($result) < 0 && mysql_num_rows($resultref) < 0)

	{

        echo'<td colspan="7">No Records Found.</td>';

    }

    ?>

</div>

</div>  

</div> <!-- #lookup_content -->

</div>

<?php

}

}

?>