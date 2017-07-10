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

	

	if(USER_IS_LOGGED != '1')

	{

		header('Location: ../index.php');

	}

	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))

	{

		header('Location: ../forbid.html');

	}

	$term_id = $_REQUEST['filter_schoolterm'];



?>

<script type="text/javascript">

$(function(){

	

	$('#print').click(function() {

			var w=window.open();

			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');

			w.document.write($('#print_div').html());

			w.document.close();

			w.focus();

			w.print();

			//w.close()

			return false;

		});

	$('#pdf').click(function() {

			var w=window.open ("pdf_reports/rep108.php?id="+<?=STUDENT_ID?>+"&trm="+<?=$term_id?>+"&met=financial"); 

			return false;

		});

		$('#email').click(function() {

			

			if(confirm("Are you sure you want to email this file to this student?"))

			{

				$.ajax({

					type: "POST",

					data: "id="+<?=STUDENT_ID?>+"&trm="+<?=$term_id?>+"&met=financial&email=1",

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

	

$arr_sql = array();

$sqlcondition = '';

$sqlOrderBy = '';





if(isset($_REQUEST['list_rows']))

{

	$page_rows = $_REQUEST['list_rows']; 	

}		

else

{

	$page_rows = 10;

}



		

if (isset($term_id) and ($term_id != "")){

	$arr_sql[] =  "term_id = " . $term_id;

}			



if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' )

{

	$sqlOrderBy = ' ORDER BY  '. $_REQUEST['fieldName'] .' '. $_REQUEST['orderBy'];

}





if(count($arr_sql) > 0)

{

	$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sqlimplode(' AND ', $arr_sql);

}

		

//Here we count the number of results 

//Edit $data to be your query 

/*

$id = $_REQUEST['student_id'];

$term_id = $_REQUEST['term_id'];	

*/





$sqlfee = "SELECT * FROM tbl_school_fee WHERE term_id = $term_id AND publish =  'Y'" .$sqlcondition;         

$resultfee = mysql_query($sqlfee);



$sql_stud_payment = "SELECT * FROM tbl_student_payment WHERE term_id = $term_id AND  student_id =" .STUDENT_ID .$sqlcondition;

$qry_stud_payment = mysql_query($sql_stud_payment);

$ctr_stud_payment = mysql_num_rows($qry_stud_payment); 



if($ctr_stud_payment > 0)

{

?>

<style type="text/css">

<!--

.style1 {color: #000000}

-->

</style>

 

	<div id="print_div">

<div id="printable">

<div class="body-container">

<div class="header">

    <table width="100%" class="head">

      <tr>

        <td width="15%" class="bold">Student Name:</td>

        <td width="85%"><?=getStudentFullName(STUDENT_ID)?></td>

      </tr>

      <tr>

        <td class="bold">Student Number:</td>

        <td><?=getStudentNumber(STUDENT_ID)?></td>

      </tr>

      <tr>

        <td class="bold">Course:</td>

        <td><?=getStudentCourse(STUDENT_ID)?></td>

      </tr>

      <tr>

        <td class="bold">School Year:</td>

        <td><?=getSYandTerm($term_id)?></td>

      </tr>

      <tr>

        <td>&nbsp;</td>

        <td><table class="head" align="right">

          <tr>

            <td><a class="viewer_email" href="#" id="email" title="email"></a> <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a> <a class="viewer_print" href="#" id="print" title="print"></a> </td>

          </tr>

        </table></td>

    </tr>

    </table>

</div>

<div class="content-container">

<div class="content-wrapper-wholeBorder" style="padding:0px 0px 0px 10px">

  <table width="100%" border="0">

    <tr>

      <td width="49%" rowspan="2" valign="top"><table class="paymentlisthalf" style="width:350px;">

          <tr>

            <th colspan="4"><center>

                <strong>ASSESSMENT OF FEES</strong>

            </center></th>

          </tr>

          <tr>

            <th><div align="center"><strong>Fees</strong></div></th>

            <th><div align="center"><strong>Units</strong></div></th>

            <th><div align="center"><strong>Amount</strong></div></th>

            <th><div align="center"><strong>Total</strong></div></th>

          </tr>

          <?php

            $x = 1;

            $sql = "SELECT *

                    FROM tbl_school_fee

                    WHERE  term_id = $term_id AND publish =  'Y'" .$sqlcondition;

                    

            $result = mysql_query($sql);

            $sub_total = 0;

            while($row = mysql_fetch_array($result)) 

            {

                $total = getStudentTotalFeeLecLab($row['id'],STUDENT_ID );

        ?>

          <tr>

            <td style="color:#000000"><?=$row['fee_name'].' ('.getFeeUnit($row['id'],STUDENT_ID).')'?>

                <div align="center"></div></td>

                <td style="color:#000000"><?=getFeeUnit($row['id'],STUDENT_ID)==''?'0.00':getFeeUnit($row['id'],STUDENT_ID)?>

                <div align="center"></div></td>

            <td style="color:#000000"><?=getFeeAmount($row['id'])?>

                <div align="center"></div></td>

            <td><div align="right"> Php

              <?=$total==''?'0.00':number_format($total, 2, ".", ",")?>

            </div></td>

          </tr>

          <?php           

                $sub_total += $total;

                $x++;

           }

        ?>

          <tr>

            <td colspan="2">&nbsp;</td>

            <td><strong>Total</strong></td>

            <td><div align="right"> Php

              <?=number_format($sub_total, 2, ".", ",")?>

            </div></td>

          </tr>

      </table>       <p>&nbsp;</p>

       <table class="paymentlisthalf" style="width:350px;">

                    <tr>

                        <th colspan="3">OTHER PAYMENTS</th>

                    </tr>

                    <tr>

                        <th class="col1_150">Fees</th>

                        <th class="col1_150">Amount</th>

                        <th class="col1_150">Total</th>

                    </tr> 

                <?php

                    $sql = "SELECT *

							FROM tbl_other_payments

							WHERE student_id = ".STUDENT_ID."

							AND term_id= $term_id";

					$result = mysql_query($sql); 

                    $other_total = 0;

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

                            Php <?=$row['amount']==''?'0.00':number_format($row['amount'], 2, ".", ",")?>

                          </div></td>

                    </tr>

                <?php           

                        $other_total += $row['amount'];

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

                            Php <?=number_format($other_total, 2, ".", ",")?>

                          </div></td>

                    </tr>                              

              </table></td>

      <td width="51%"><table width="100%" border="0" class="fieldsetList">

          <?php

           /* TOTAL OTHER PAYMENT */

            $sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id= $term_id";

            $result_fee_other = mysql_query($sql_fee_other);

            $row_fee_other = mysql_fetch_array($result_fee_other);

            $sub_mis_total = 0;

            $mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],STUDENT_ID);           

            $sub_mis_total += $mis_total;

            

            

            /* TOTAL LEC PAYMENT */

            $sql_lec = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlec' AND publish =  'Y' AND term_id= $term_id";

            $qry_lec = mysql_query($sql_lec);

            $row_lec = mysql_fetch_array($qry_lec);

            $sub_lec_total = 0;

            

            

            $lec_total = getStudentTotalFeeLecLab($row_lec['id'],STUDENT_ID);           

            $sub_lec_total += $lec_total;

            

            /* TOTAL LAB PAYMENT */

            $sql_lab = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlab' AND publish =  'Y' AND term_id= $term_id";

            $qry_lab = mysql_query($sql_lab);

            $row_lab = mysql_fetch_array($qry_lab);

            $sub_lab_total = 0;

            

            

            $lab_total = getStudentTotalFeeLecLab($row_lab['id'],STUDENT_ID);           

            $sub_lab_total += $lab_total;

            

            /*TOTAL LEC AND LAB = LEC + LAB*/

            $total_lec_lab =  $sub_lec_total + $sub_lab_total;

			

			//DISCOUNT

			$sql_payment = "SELECT * FROM tbl_student_payment WHERE  term_id = $term_id AND student_id =" .STUDENT_ID;

            $qry_payment = mysql_query($sql_payment);

            $row_payment = mysql_fetch_array($qry_payment);

            

            $total_charges = $sub_total - $total;

            

            $total_discounted = getStudentDiscount($row_payment['discount_id'], STUDENT_ID, $total_lec_lab); 

			

			//PAYMENT

			 $sql_total_payment = "SELECT sum(amount) FROM tbl_student_payment WHERE  term_id = $term_id AND is_bounced <> 'Y' AND is_refund <> 'Y' AND student_id =" .STUDENT_ID;

                $qry_total_payment = mysql_query($sql_total_payment);

                $row_total_payment = mysql_fetch_array($qry_total_payment);	

                $row_total_payment['amount'];

			

			/* TOTAL REFUND */

			$sql = "SELECT * FROM tbl_student_payment WHERE is_refund =  'Y' AND student_id = ".STUDENT_ID." AND term_id=" .$term_id;

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

				$total_refund = getTotalRefundAmount(STUDENT_ID,$term_id);

			}

			//

			$lib = getLibraryDueFee(STUDENT_ID);

        	?>

          <tr>

            <td>Total Lecture Fee Amount:</td>

            <td><div align="right"> Php

              <?=number_format($lec_total, 2, ".", ",")?>

            </div></td>

          </tr>

          <tr>

            <td>Total Laboratory Fee Amount:</td>

            <td><div align="right"> Php

              <?=number_format($lab_total, 2, ".", ",")?>

            </div></td>

          </tr>

          <tr>

            <td>Total Miscelleneous Fee Amount:</td>

            <td><div align="right"> Php

              <?=number_format($mis_total, 2, ".", ",")?>

            </div></td>

          </tr>

          <tr>

            <td colspan="2" class="bottom"></td>

          </tr>

          <tr>

            <td><strong>Total Tuition Fee Amount:</strong></td>

            <td><div align="right"><strong> Php

              <?=number_format($sub_total, 2, ".", ",")?>

            </strong> </div></td>

          </tr>

          <!-- <tr>

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

            

            if($row_payment['discount_id'] != '0')

            {

            ?>

          <tr>

            <td>Discount -

              <?=getDiscountName($row_payment['discount_id'])?>

              (

              <?=getDiscountValue($row_payment['discount_id'])?>

              %)</td>

            <td><div align="right"> Php

              <?=number_format($total_discounted, 2, ".", ",")?>

            </div></td>

          </tr>

          <?php

            }

            else

            {

            ?>

          <tr>

            <td>Discount</td>

            <td><div align="right">Php 0.00</div></td>

          </tr>

          <?php

            }

            $credit = getCarriedBalances(STUDENT_ID,$term_id);

			$debit = getCarriedDebits(STUDENT_ID,$term_id);

			$sub_total = $sub_total-$total_discounted;

			$sub_total = abs($sub_total - $debit);

			$sub_total = $sub_total + $credit;

			$total_fee = $sub_total;

                    ?>

          <tr>

            <td>Carried Balances:</td>

            <td><div align="right"> Php

              <?=number_format($credit, 2, ".", ",")?>

            </div></td>

          </tr>

          <tr>

            <td>Carried Debit Balances:</td>

            <td><div align="right"> Php

              <?=number_format($debit, 2, ".", ",")?>

            </div></td>

          </tr>

          <tr>

            <td colspan="2" class="bottom"></td>

          </tr>

          <tr>

            <td><strong>SubTotal:</strong></td>

            <td><div align="right"><strong> Php

              <?=number_format($sub_total, 2, ".", ",")?>

            </strong> </div></td>

          </tr>

          <tr>

            <td>Refunded Amount:</td>

            <td><div align="right"> Php

              <?=number_format($ref_total, 2, ".", ",")?>

            </div></td>

          </tr>

          <tr>

            <td>Refund Balance:</td>

            <td><div align="right"> Php

              <?=number_format($total_refund, 2, ".", ",")?>

            </div></td>

          </tr>

          <tr>

            <td colspan="2" class="bottom"></td>

          </tr>

          <tr>

            <td><strong>Total Refunded:</strong></td>

            <td><div align="right"><strong> Php

              <?=number_format(abs($total_refund-$ref_total), 2, ".", ",")?>

            </strong></div></td>

          </tr>

          <!-- <tr>

                <td><strong>Total Charges:</strong></td>

                <td><div align="right"><strong>

                <?php

                //$sub_total = ($sub_total - $total_discounted)-($total_payment);

                ?>

                Php 

                <?=number_format($sub_total, 2, ".", ",")?>

                </strong></div></td>

            </tr>!-->

          <tr>

            <td><strong>Total Payment:</strong></td>

            <td><div align="right"><strong> Php

              <?=number_format($row_total_payment['sum(amount)'], 2, ".", ",")?>

            </strong></div></td>

            <input type="hidden" name="sub_total" id="sub_total" value="<?=$row_total_payment['amount']?>" />

          </tr>

          <?php

					if(checkIfStudentPaidFull($id))

						{

							$total_rem_bal = $sub_total + $total_refund ;//+ $lib;

						}

						else if(checkIfStudentDropAllSubjects($id)&&$row_total_payment['sum(amount)'] > $sub_total)

						{

							$total_rem_bal = 0;

						}

						else if($row_total_payment['sum(amount)'] > $sub_total)

						{

							$total_rem_bal = 0;

						}

						else if(!checkIfStudentDropAllSubjects($student_id)&&$row_total_payment['sum(amount)'] > $sub_total)

						{

							$total_rem_bal = 0;

						}

						else

						{

							$total_rem_bal = ($sub_total)-$row_total_payment['sum(amount)'];

						}

                   // $total_rem_bal = $sub_total - $row_total_payment['sum(amount)'];

				   $sqlo = "SELECT * FROM tbl_other_payments WHERE student_id=".STUDENT_ID." AND term_id=".$term_id;

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

            <td><div align="right"><strong> Php

              <?=number_format($other, 2, ".", ",")?>

            </strong></div></td>

          </tr>

          <tr>

            <td colspan="2" class="bottom"></td>

          </tr>

          <tr>

            <td><strong>Total Remaining Balance:</strong></td>

            <td><div align="right"><strong> Php

              <?=number_format($total_rem_bal, 2, ".", ",")?>

            </strong></div></td>

          </tr>

      </table></td>

    </tr>

    <tr>

      <td>&nbsp;</td>

    </tr>

  </table>

</div>

<p>&nbsp;</p>

<?php

    $sql = "SELECT * FROM tbl_student_payment WHERE  term_id = $term_id AND student_id = " .STUDENT_ID .$sqlcondition;

    $result = mysql_query($sql); 

	

	$sqlref = "SELECT * FROM tbl_student_payment WHERE  is_refund = 'Y' AND term_id = $term_id AND student_id = " .STUDENT_ID .$sqlcondition;

    $resultref = mysql_query($sqlref);

	

    if (mysql_num_rows($result) > 0 )

    {

        $x = 0;

        ?>

        <div class="fieldsetContainer100">

        <table class="paymentlistview" style="width:720px">      

            <tr>

                <th class="style1 col1_150"><strong>Term</strong></th>  

                <th class="style1 col1_50"><strong>Amount</strong></th>   

                <th class="col1_50 style1"><strong>Payment</strong> <strong>term</strong></th>  

                <th class="col1_50 style1"><strong>Payment</strong> <strong>method</strong></th>

                <th class="style1 col1_50"><strong>Remark</strong></th>  

                <th class="col1_50 style1"><strong>Date</strong> <strong>created</strong></th> 

                <th class="col1_50 style1"><strong>Prepared</strong> <strong>by</strong></th> 

            </tr>

        <?php

        while($row = mysql_fetch_array($result)) 

        { 

        ?>

            <tr class="<?=($x%2==0)?"":"highlight";?>">

                <td style="color:#333333"><?=getSYandTerm($row["term_id"])?></td>

                <td style="color:#333333">Php <?=$row["amount"]?></td>

                <td style="color:#333333"><?=getPaymentTerm($row["payment_term"])?></td>

                <td style="color:#333333"><?=getPaymentMethod($row["payment_method"])?></td>

                <td style="color:#333333"><?php if($row['is_bounced']=='Y'){ echo 'Bounced Check'; }?></td>

                <td style="color:#333333"><?=date('M d Y h:m:s',$row['date_created'])?></td>

                <td style="color:#333333"><?=getEmployeeFullName($row["created_by"])?></td>

            </tr>

        <?php           

        }

    }

    else

	  {

	  		echo '<td colspan="7">No Payments</td>';

	  }

        ?>

        <p>&nbsp;</p>

        <div class="fieldsetContainer100">

        <table class="paymentlistview" style="width:720px">   

        	<tr>

                <th class="col1_150" colspan="7">Refunds</th>

            </tr>    

            <tr>

                <th class="col1_150">Term</th>  

                <th class="col1_50">Amount</th>   

                <!--<th class="col1_50">Payment term</th>  

                <th class="col1_50">Payment method</th>!-->

                <th class="col1_50">Remarks</th>  

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

	  		echo '<td colspan="7">No Refunds</td>';

	  }

	

	if(mysql_num_rows($result) < 0 && mysql_num_rows($resultref) < 0)

	{

        echo '<div id="message_container"><h4>No records found</h4></div>';

    }

    ?>

    </table>

    </div>

    <?php

    echo '<div id="message_container"></div><p id="formbottom"></p>';

}

else

{

	echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';

}

?> 

</div>

</div>

</div>

</div>

</div>