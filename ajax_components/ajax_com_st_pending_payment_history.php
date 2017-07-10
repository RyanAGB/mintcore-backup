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

if(USER_IS_LOGGED != '1')
	{
		header('Location: ../index.php');
	}
	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}

$id = $_REQUEST['student_id'];	
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
			var w=window.open ("pdf_reports/rep108.php?id="+<?=$id?>+"&met=reserved"+"&trm="+<?=CURRENT_TERM_ID?>); 
			return false;
		});
});	
 
</script>

<?php
if(checkIfStudentIsReserve($id))
{
		$sql = "SELECT * FROM 
							tbl_student_reserve_subject 
						WHERE
							term_id = " . CURRENT_TERM_ID . " AND
							student_id= " .$id 
				 ;
	
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
			
?>         
<div id="print_div">
<div id="printable">
<div class="body-container">
<div class="header">
    <table width="100%" class="head">
      <tr>
        <td width="15%" class="bold">Student Name:</td>
        <td width="85%"><?=getStudentFullName(USER_STUDENT_ID)?></td>
      </tr>
      <tr>
        <td class="bold">Student Number:</td>
        <td><?=getStudentNumber(USER_STUDENT_ID)?></td>
      </tr>
      <tr>
        <td class="bold">Course:</td>
        <td><?=getStudentCourse(USER_STUDENT_ID)?></td>
      </tr>
      <tr>
        <td class="bold">School Year:</td>
        <td><?=getSYandTerm(CURRENT_TERM_ID)?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
    </tr>
    </table>
</div>
<div class="content-container">
<div class="content-wrapper-wholeBorder">
    	<table align="right">
        <tr>
        <td>
        <a class="viewer_email" href="#" id="email" title="email"></a>
        <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
        <a class="viewer_print" href="#" id="print" title="print"></a>
        </td>
        </tr>
        </table>
	<div class="fieldsetContainer50">	
	
        <table class="classic">
      		<tr>
            	<th colspan="3">ASSESSMENT OF FEES</th>
            </tr>
            <tr>
                <th>Fees</th>
                <th>Amount</th>
                <th>Total</th>
            </tr> 
            <?php
            $x = 1;
            $sql = "SELECT *
                    FROM tbl_school_fee
                    WHERE  term_id = ".CURRENT_TERM_ID." AND publish =  'Y'" .$sqlcondition;
                    
            $result = mysql_query($sql);
            $sub_total = 0;
            while($row = mysql_fetch_array($result)) 
            {
                $total = getStudentTotalFeeLecLab($row['id'],USER_STUDENT_ID );
        ?>
            <tr> 
                <td><?=$row['fee_name'].' ('.getFeeUnit($row['id'],USER_STUDENT_ID).')'?></td>
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
  	</div>
    <div class="fieldsetContainer50">
    	<table class="classic_borderless" width="100%">
        <?php
                $sql_reservation ="SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status ='R' AND student_id =" .$id;
                $qry_reservation = mysql_query($sql_reservation);
                $row_reservation = mysql_fetch_array($qry_reservation);
                $ctr_reservation = mysql_num_rows($qry_reservation);
                
                if($ctr_reservation > 0)
                {	
                ?>
                    <tr>
                    <td><strong>Reservation Date:</strong></td>
                    <td><div align="right"><?=date('F d, Y', $row_reservation['date_reserved'])?></div></td>
                    </tr>
                    <tr>
                    <td><strong>Last Day of Payment:</strong></td>
                    <td><div align="right"><?=date('F d, Y', $row_reservation['expiration_date'])?></div></td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
        <?php
            /* TOTAL OTHER PAYMENT */
            $sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id= ".CURRENT_TERM_ID;
            $result_fee_other = mysql_query($sql_fee_other);
            $row_fee_other = mysql_fetch_array($result_fee_other);
            $sub_mis_total = 0;
            $mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],USER_STUDENT_ID);           
            $sub_mis_total += $mis_total;
            
            
            /* TOTAL LEC PAYMENT */
            $sql_lec = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlec' AND publish =  'Y' AND term_id= ".CURRENT_TERM_ID;
            $qry_lec = mysql_query($sql_lec);
            $row_lec = mysql_fetch_array($qry_lec);
            $sub_lec_total = 0;
            
            
            $lec_total = getStudentTotalFeeLecLab($row_lec['id'],USER_STUDENT_ID);           
            $sub_lec_total += $lec_total;
            
            /* TOTAL LAB PAYMENT */
            $sql_lab = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlab' AND publish =  'Y' AND term_id= ".CURRENT_TERM_ID;
            $qry_lab = mysql_query($sql_lab);
            $row_lab = mysql_fetch_array($qry_lab);
            $sub_lab_total = 0;
            
            
            $lab_total = getStudentTotalFeeLecLab($row_lab['id'],USER_STUDENT_ID);           
            $sub_lab_total += $lab_total;
            
            /*TOTAL LEC AND LAB = LEC + LAB*/
            $total_lec_lab =  $sub_lec_total + $sub_lab_total;
			
			$lib = getLibraryDueFee(USER_STUDENT_ID);
        	?>
        
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
            
             <?php
			$surcharge = GetSchemeForSurcharge(USER_STUDENT_ID)*getStudentReservedUnit(USER_STUDENT_ID);
			
			$sqldis = 'SELECT * FROM tbl_student WHERE id='.USER_STUDENT_ID;
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
              <td>Discount:</td>
              <td><div align="right">
                Php <?=number_format($discount, 2, ".", ",")?>
                </div></td>
            </tr>
            <tr>
              <td>Surcharge:</td>
              <td><div align="right">
                Php <?=number_format($surcharge, 2, ".", ",")?>
                </div></td>
            </tr>
            
            <?php
			$credit = getCarriedBalances(USER_STUDENT_ID,CURRENT_TERM_ID);
			$debit = getCarriedDebits(USER_STUDENT_ID,CURRENT_TERM_ID);
			$sub_total = abs($sub_total - $debit);
			$sub_total = $sub_total + $credit;
			
			$sub_total = $sub_total - $discount;
			$sub_total = $sub_total + $surcharge;
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
                <td><strong>Total Current Charges:</strong></td>
                <td><div align="right"><strong>
                <?php
                $sub_total = ($sub_total - $total_discounted)-($total_payment);
                ?>
                Php 
                <?=number_format($sub_total, 2, ".", ",")?>
                </strong></div></td>
            </tr>  
                <?php
                    $total_rem_bal = $sub_total;
                ?>                                
      </table>
      
       <table class="classic_borderless">

        	<tr>

         	<td><strong>Schedule of Fees</strong></td>

            </tr>

			<?php

			

				$sqlsch = "SELECT *

                        FROM tbl_payment_scheme_details

                        WHERE scheme_id = ".GetStudentScheme(USER_STUDENT_ID)." ORDER BY sort_order";

                        

                $resultsch = mysql_query($sqlsch);

			

                while($rowsch = mysql_fetch_array($resultsch)) 

                {

					if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'A')

					{

						$topay = $rowsch['payment_value'];

						$sub_total = $sub_total - $topay;

						$initial = $rowsch['id'];

					}

					else if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'P')

					{

						$topay = abs(getStudentPaymentScheme($rowsch['id'],$sub_total));;

						//$total_fee = $sub_total - $down;

						$initial = $rowsch['id'];

					}

					else if($rowsch['payment_type'] == 'P')

					{

						$topay = abs(getStudentPaymentScheme($rowsch['id'],$sub_total));

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
      
	</div>
    <?php
    echo '<div id="message_container"></div><p id="formbottom"></p>';
}

?> 
</div>
</div>
</div>
</div>
</div>