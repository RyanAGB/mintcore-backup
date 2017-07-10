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
	});
</script>

<?php
	$id = $_REQUEST['student_id'];
	$term_id = $_REQUEST['term_id'];	
	
	$sql = "SELECT * FROM tbl_student WHERE id = ".$id;						
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	
	 $sqlfee = "SELECT * FROM tbl_school_fee WHERE publish =  'Y'";         
     $resultfee = mysql_query($sqlfee);
?>
<table width="100%">
  <tr>
    <td width="18%" valign="top" style='font-weight:bold'>Saaaatudent Name:</td>
    <td width="82%"><?=$row['lastname']. ", " . $row['firstname'] ." " . $row['middlename']?></td>
  </tr>
  <tr>
    <td style='font-weight:bold' valign="top">Student Number:</td>
    <td><?=$row['student_number']?></td>
  </tr>
  <tr>
    <td style='font-weight:bold' valign="top">Course:</td>
    <td><?=getCourseName($row['course_id'])?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
</tr>
  <tr>
    <td colspan=2 style="border-top:1px solid #333;">&nbsp;</td>
</tr>

</table>
 
<div id="lookup_content">


<table width="100%">
	<tr>
    	<td width="50%" valign="top">
		  <table class="classic_withoutWidth" width="100%">
                <tr>
                    <th class="col1_150">Fees</th>
                    <th class="col1_150">Amount</th>
                    <th class="col1_150">Total</th>
                </tr> 
			<?php
				$x = 1;
                $sql = "SELECT *
                        FROM tbl_school_fee
                        WHERE publish =  'Y'";
                        
                $result = mysql_query($sql);
				$sub_total = 0;
                while($row = mysql_fetch_array($result)) 
                {
					$total = getStudentTotalFeeLecLab($row['id'],$id );
            ?>
                <tr class="<?=($x%2==0)?"":"highlight";?>"> 
                    <td><?=$row['fee_name']?></td>
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
  		</td>
    	<td width="50%">
        
    <?php
    /* TOTAL AMOUNT */
	$sql = "SELECT * FROM tbl_school_fee WHERE publish =  'Y'";
	$result = mysql_query($sql);
	$sub_total = 0;
	while($row = mysql_fetch_array($result)) 
	{
		$total = getStudentAmountFeeByFeeId($row['id'],$id);           
		$sub_total += $total;
	}
	
	/* TOTAL OTHER PAYMENT */
	$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y'";
	$result_fee_other = mysql_query($sql_fee_other);
	$row_fee_other = mysql_fetch_array($result_fee_other);
	$sub_mis_total = 0;
	$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$id);           
	$sub_mis_total += $mis_total;
	
	
	/* TOTAL LEC PAYMENT */
	$sql_lec = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlec' AND publish =  'Y'";
	$qry_lec = mysql_query($sql_lec);
	$row_lec = mysql_fetch_array($qry_lec);
	$sub_lec_total = 0;
	
	
	$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$id);           
	$sub_lec_total += $lec_total;
	
	/* TOTAL LAB PAYMENT */
	$sql_lab = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlab' AND publish =  'Y'";
	$qry_lab = mysql_query($sql_lab);
	$row_lab = mysql_fetch_array($qry_lab);
	$sub_lab_total = 0;
	
	
	$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$id);           
	$sub_lab_total += $lab_total;
	
	/*TOTAL LEC AND LAB = LEC + LAB*/
	$sub_total =  $sub_lec_total + $sub_lab_total + $sub_mis_total;
	
	/*TOTAL UNITS = TUITION FEE - TOTAL OTHER PAYMENT*/
	$total_lec_lab = $sub_total - $sub_mis_total
	
?>
        
          <table class="classic_borderless" width="100%">
                <tr>
                    <td>Total Tuition Fee Amount:</td>
                    <td><div align="right">
                     Php <?=number_format($sub_total, 2, ".", ",")?>
                    </div></td>
                    </tr>
                    <tr>
                        <td>Balance Carried Forward:</td>
                        <td><div align="right">Php 0.00</div></td>
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
                    </tr>
                    <?php
                    $sql_payment = "SELECT * FROM tbl_student_payment WHERE student_id =" .$id;
                    $qry_payment = mysql_query($sql_payment);
                    $row_payment = mysql_fetch_array($qry_payment);
                    
                    $total_charges = $sub_total - $total;
                    
                    $total_discounted = getStudentDiscount($row_payment['discount_id'], $id, $total_lec_lab); 
                    
                    
                
                    if($row_payment['discount_id'] != '0')
                    {
                    ?>
                        <tr>
                          <td>Student Discount (<?=getDiscountValue($row_payment['discount_id'])?>%)</td>
                          <td>
                            <div align="right">
                                Php <?=number_format($total_discounted, 2, ".", ",")?>	
                            </div>
                          </td>
                        </tr>
                    <?php
                    }
                    else
                    {
                    ?>  
                        <tr>
                          <td>Student Discount</td>
                          <td><div align="right">Php 0.00</div></td>
                        </tr>
                    <?php
                    }
                    ?>  
                    <tr>
                      <td colspan="2" class="bottom"></td>
                    </tr>
                    <tr>
                        <td><strong>Total Charges:</strong></td>
                        <td><div align="right"><strong>
                        <?php
                        $sub_total = ($sub_total - $total_discounted)-($total_payment);
                        ?>
                        Php 
                        <?=number_format($sub_total, 2, ".", ",")?>
                        </strong></div></td>
                    </tr>
                        <?php
                        $sql_total_payment = "SELECT sum(amount) FROM tbl_student_payment WHERE is_bounced <> 'Y' AND student_id =" .$id;
                        $qry_total_payment = mysql_query($sql_total_payment);
                        $row_total_payment = mysql_fetch_array($qry_total_payment);	
                        $row_total_payment['amount'];
                        ?>
                    <tr>
                        <td><strong>Total Payment:</strong></td>
                        <td><div align="right"><strong>
                        Php 
                        <?=number_format($row_total_payment['sum(amount)'], 2, ".", ",")?>
                        </strong></div></td>
                        <input type="hidden" name="sub_total" id="sub_total" value="<?=$row_total_payment['amount']?>" />
                    </tr>
                        <?php
                            $total_rem_bal = $sub_total - $row_total_payment['sum(amount)'];
                        ?>
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
			$sql = "SELECT * FROM tbl_student_payment WHERE student_id = " .$id;
			$result = mysql_query($sql); 
			if (mysql_num_rows($result) > 0 )
			{
				$x = 0;
				?>
                <p>&nbsp;</p>
                <table class="classic">      
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
                        <td>Php<?=$row["amount"]?></td>
                        <td><?=getPaymentTerm($row["payment_term"])?></td>
                        <td><?=getPaymentMethod($row["payment_method"])?></td>
			<td class="remarks"><?php if($row['is_bounced']=='Y'){ echo 'Bounced Check'; }?></td>
                        <td><?=date('M d Y h:m:s',$row['date_created'])?></td>
                        <td><?=getEmployeeFullName($row["created_by"])?></td>
                    </tr>
                <?php           
                }
			}
			else 
			{
				echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';
			}
			?>
			</table>

    
    
</div> <!-- #lookup_content -->
<?php
}
?>