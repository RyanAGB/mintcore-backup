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

	

	$student_id = $_REQUEST['student_id'];

?>

<script type="text/javascript">



	$(function(){



	// Dialog			

	$('#dialog').dialog({

		autoOpen: false,

		width: 400,

		height: 450,

		bgiframe: true,

		modal: true,

		buttons: { 

			"Close": function() { 

				$(this).dialog("close"); 

			} 

		}

	});

	

	// Dialog Link

	$('.pay').click(function(){

		var param = $(this).attr("returnComp");

		var id = $(this).attr("returnId");

		var pay = $(this).attr("returnPay");

		var carried = $(this).attr("returnCarried");

		var sorts = $(this).attr("returnSort");

		var downp = $(this).attr("returnDown");

		var dis_id = $(this).attr("returnDiscount");

		$('#payment_scheme_id').val(id);

		$('#topay').val(pay);

		$('#payment_paid').val(carried);

		$('#order').val(sorts);

		$('#down').val(downp);

		//alert(sorts);

		$('#dialog').load('lookup/lookup_com_cs_pay.php?id='+<?=$student_id?>+'&bill='<?=$sub_total?>+'&status='+sorts+'&disc='+dis_id+'&comp='+param, null);

		$('#dialog').dialog('open');

		return false;

	});

	

});	



$(function(){



	// Dialog			

	$('#dialog_2').dialog({

		autoOpen: false,

		width: 400,

		height: 400,

		bgiframe: true,

		modal: true,

		buttons: { 

			"Close": function() { 

				$(this).dialog("close"); 

			} 

		}

	});

	

	// Dialog Link

	$('.check').click(function(){

		var param = $(this).attr("returnComp");

		$('#dialog_2').load('lookup/lookup_com_cs_bounce.php?id='+<?=$student_id?>+'&comp='+param,null);

		$('#dialog_2').dialog('open');

		return false;

	});

	

});	



	if($('#payment_met').val()!=''||$('#discount').val()!=''||$('#payment_scheme_id').val()!=''||$('#amount_paid').val()!=''||$('#check_num').val()!=''||$('#bank').val()!=''||$('#or').val()!=''||$('#rem').val()!='')

	{

		$('#discount').val()!=''?$('#discount_id').val($('#discount').val()):$('#discount_id').val(0);

		$('#scheme_id').val($('#payment_scheme_id').val());

		$('#payment_method').val($('#payment_met').val());

		$('#OR').val($('#or').val());

		$('#remarks').val($('#rem').val());

		

		update_computation($('#discount_id').val());

		

		if($('#payment_method').val() == 2){

				$.ajax({

				type: "POST",

				data: "mod=updatePayment&id=" + $('#payment_method').val()+"&chk="+$('#check_num').val()+"&bnk="+$('#bank_brn').val()+"&am="+$('#amount_paid').val(),

				url: "ajax_components/ajax_com_payment_field_updater.php",

				success: function(msg){

					if (msg != ''){

						$("#chek").html(msg);

					}

				}

				});	

			}else{

				$.ajax({

				type: "POST",

				data: "mod=updatePayment2&id=" + $('#payment_method').val()+"&am="+$('#amount_paid').val(),

				url: "ajax_components/ajax_com_payment_field_updater.php",

				success: function(msg){

					if (msg != ''){

						$("#chek").html(msg);

					}

				}

				});	

			}			

	}

	else

	{

		update_computation();

	}



$(function(){

	

	/*$('.check').click(function(){

		if(confirm('Are you sure you want to set this Payment as Bounced Check?'))

		{

			clearTabs();

			$('#add_new').addClass('active');

			$('#check_num').val($(this).attr("returnId"));

			$('#action').val('bounce');

			$('#view').val('payment');

			$("form").submit();

		}else{

			return false;

		}

		$('#dialog2').load('lookup/lookup_com_cs_pay.php?method="bounced"&id='+<?=$student_id?>;

		$('#dialog2').dialog('open');

		return false;

	});*/

	

	$('.refund').click(function(){

		if(confirm('Are you Sure you want to Refund this Payment?'))

		{

		//alert($(this).attr("returnSub"));

			clearTabs();

			$('#add_new').addClass('active');

			$('#amount_paid').val($(this).attr("returnId"));

			$('#subject_id').val($(this).attr("returnSub"));

			$('#action').val('refund');

			$('#view').val('payment');

			$("form").submit();

		}

		else

		{

			return false;

		}

	});

	

	$('#save').click(function(){

		clearTabs();

		var pay = $('#payment_method').val();

		var dis = $('#discount_id').val();

		var amount_pay = $('#amount').val();

		var bank_branch = $('#bank').val();

		var check_number = $('#check_no').val();

		var schem = $('#scheme_id').val();

		var totalfee = $('#total').val();

		var or = $('#OR').val();

		var rem = $('#remarks').val();

	

		/*if(parseInt(amount_pay) > parseInt(totalfee))

		{

			alert('Payment Exceeds Total Amount of Fees.');

			return false;

		}

		else*/ if(pay != '' && pay == 2)

		{

			if(amount_pay!='' && bank_branch!='' && check_number!='' && schem!='')

			{

				$('#payment_met').attr("value", pay);

				$('#discount').attr("value", dis);

				$('#amount_paid').attr("value", amount_pay);

				$('#check_num').attr("value", check_number);

				$('#bank_brn').attr("value", bank_branch);

				$('#total_discount').attr("value", $('#total').val());

				$('#down').attr("value", $('#downpay').val());

				$('#cond').attr("value", $('#con').val());

				$('#order').attr("value", 'RESERVED');

				$('#or').attr("value", or);

				$('#rem').attr("value", rem);

				$('#payment_scheme_id').attr("value", schem);

				$('#add_new').addClass('active');

				$('#action').val('save');

				$('#view').val('payment');

				$("form").submit();

			}

			else

			{

				alert('Empty Field Found.');

					return false;

			}

		}

		else if(pay!='' && (pay==1 || pay==3))

		{

				if(amount_pay!='')

				{

					$('#payment_met').attr("value", pay);

					$('#discount').attr("value", dis);

					$('#amount_paid').attr("value", amount_pay);

					$('#check_num').attr("value", check_number);

					$('#bank_brn').attr("value", bank_branch);

					$('#total_discount').attr("value", $('#total').val());

					$('#down').attr("value", $('#downpay').val());

					$('#cond').attr("value", $('#con').val());

					$('#order').attr("value", 'RESERVED');

					$('#or').attr("value", or);

					$('#rem').attr("value", rem);

					$('#payment_scheme_id').attr("value", schem);

					$('#add_new').addClass('active');

					$('#action').val('save');

					$('#view').val('payment');

					$("form").submit();			}

				else

				{

					alert('Invalid Empty Field.');

					return false;

				}

		}

		/*else if($('#con').val()!='true'&&schem!=''&&dis!='')

		{

			alert('Invalid Discount. Total Fee is less than the initial Payment. Please choose other discount or payment scheme.');

			return false;

		}*/

		else

		{

				alert('Invalid Payment Method.');

				return false;

		}

	});	

	

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

			var w=window.open ("pdf_reports/rep108.php?id="+<?=$student_id?>+"&trm="+<?=CURRENT_TERM_ID?>+"&met=assessment"); 

			return false;

		});

		

		$('#email').click(function() {

			

			if(confirm("Are you sure you want to email this file to this student?"))

			{

				$.ajax({

					type: "POST",

					data: "id="+<?=$student_id?>+"&trm="+<?=CURRENT_TERM_ID?>+"&met=payment&email=1",

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

		

		$('#print2').click(function() {

			var w=window.open();

			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');

			w.document.write($('#print_div').html());

			w.document.close();

			w.focus();

			w.print();

			w.close()

			return false;

		});

		

		$('#pdf2').click(function() {

			var w=window.open ("pdf_reports/rep108.php?id="+<?=$student_id?>+"&trm="+<?=CURRENT_TERM_ID?>+"&met=reserved3"); 

			return false;

		});

		

		$('#email2').click(function() {

			

			if(confirm("Are you sure you want to email this file to this student?"))

			{

				$.ajax({

					type: "POST",

					data: "id="+<?=$student_id?>+"&trm="+<?=CURRENT_TERM_ID?>+"&met=reserved&email=1",

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

	

	/* MODIFIED BY TINE
	$('#discount_id').change(function(){

			update_computation($(this).val());	

		});*/

		

		

	$('#scheme_id').change(function(){

			update_computation($('#discount_id').val());		

		});

		

	

	$('#payment_method').change(function(){

			if($('#payment_method').val() == 2){

				$.ajax({

				type: "POST",

				data: "mod=updatePayment&id=" + $('#payment_method').val(),

				url: "ajax_components/ajax_com_payment_field_updater.php",

				success: function(msg){

					if (msg != ''){

						$("#chek").html(msg);

					}

				}

				});	

			}else{

				$.ajax({

				type: "POST",

				data: "mod=updatePayment2&id=" + $('#payment_method').val(),

				url: "ajax_components/ajax_com_payment_field_updater.php",

				success: function(msg){

					if (msg != ''){

						$("#chek").html(msg);

					}

				}

				});	

			}	

	});

});	

function update_computation(discount_id){

	$.ajax({

				type: "POST",

				data: "discount_id="+discount_id + "&student_id="+$('#student_id').val()+"&scheme_id="+$('#scheme_id').val()+"&surcharge="+$('#surcharge').val(),

				url: "ajax_components/ajax_com_payment_computation_updater.php",

				success: function(msg){

					if (msg != ''){

						$("#computation_container").html(msg);

					}

				}

				});

}

</script>

<?php

if(checkIfStudentIsEnroll($student_id))

{

//student is enrolled	

	

		$sql = "SELECT * FROM 

							tbl_student_schedule st,
							tbl_schedule sc

						WHERE
						
							st.schedule_id = sc.id AND

							st.term_id = ".CURRENT_TERM_ID." AND

							st.student_id= " .$student_id. " AND

							st.enrollment_status='A'";

	

		$result = mysql_query($sql);

		

		$status = 'ENROLLED';



    //if (mysql_num_rows($result) > 0 )

    //{

         



	$sql_info = "SELECT * FROM tbl_student WHERE id = $student_id";

	$query_info = mysql_query($sql_info);

	$row_info = mysql_fetch_array($query_info);		

?>         

<div id="print_div">

<div id="printable">

<div class="body-container">

<div class="header">

<div class="headerForm">         

    <table class="classic_borderless">

      <tr>

        <td valign="top" style='font-weight:bold;'>Student Name:</td>

        <td><?=$row_info['firstname']. ", " . $row_info['lastname'] ." " . $row_info['middlename']?></td>

      </tr>

      <tr>

        <td style='font-weight:bold' valign="top">Student Number:</td>

        <td><?=$row_info['student_number']?></td>

      </tr>

      <tr>

        <td style='font-weight:bold' valign="top">Course:</td>

        <td><?=getCourseName($row_info['course_id'])?></td>

      </tr>

      <!--<tr>

        <td style='font-weight:bold' valign="top">School Year:</td>

        <td><?=getSYandTerm(CURRENT_TERM_ID)?></td>

      </tr>!-->

      <tr>

        <td style='font-weight:bold' valign="top">Status:</td>

        <td><?=$status?></td>

      </tr>

      

    </table>  

</div> 

</div>

<div class="content-container">



<div class="content-wrapper-wholeBorder">  

 <table class="listview_classic">            

        	<tr>

    <td colspan=7 align="right">

    <a class="viewer_email" href="#" id="email" title="email"></a>

    <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>

    <a class="viewer_print" href="#" id="print" title="print"></a>

    </td>

</tr>

 <?php  

        if (mysql_num_rows($result) > 0 )

        {

            $x = 0;

        ?>

          <tr>

          	  <th class="col_50">Section</th>

              <th class="col_50">Code</th>

              <th class="col_300">Subject Name</th>

			  <th class="col_50">Units</th>

			  <th class="col_100">Schedule</th>

          </tr>

        <?php

            

//CURRENT_TERM_ID



			$total_units = 0;

			while($row = mysql_fetch_array($result)) 

            {

				$total_units += getSubjUnit($row["subject_id"]);

        ?>

            <tr class="<?=($x%2==0)?"":"highlight";?>">

            	<td><?=getSectionNo($row["schedule_id"])?></td>

                <td><?=getSubjCode($row["subject_id"])?></td> 

				<td><?=getSubjName($row["subject_id"])."(".getSubjName($row['elective_of']).")"?></td>

				<td><?=$row["units"]?></td>

				<td><?=getScheduleDays($row['schedule_id'])?></td>

            </tr>

		<?php  

			$x++;         

           }

		?>

            <tr>

                <td colspan="3">&nbsp;</td> 

				<td><?=$total_units?></td>

				<td colspan="3">&nbsp;</td>

            </tr>        

		<?php

        }

        else 

        {

         ?>

         	<tr><td colspan="8">All Subjects Dropped</td></tr>

         <?php

        }

        ?>

		</table> 

 		

        

        <?php 

		$sqldrop = "SELECT * FROM 

							tbl_student_schedule 

						WHERE

							term_id = ".CURRENT_TERM_ID." AND

							student_id= " .$student_id. " AND

							enrollment_status='D' OR enrollment_status='DR'";

	

		$querydrop = mysql_query($sqldrop);

		 

            $xd = 0;

			$subject= "";

        ?>



        <table class="listview_classic">   

        	<tr>

                <th class="col1_150" colspan="6">Student Refunds</th>   

          </tr>    

          <tr>

          	  <th class="col_50">Section</th>

              <th class="col_50">Code</th>

              <th class="col_300">Subject Name</th>

			  <th class="col_50">Units</th>

              <th class="col_100">Refund</th>

          </tr>

        <?php

            

//CURRENT_TERM_ID



			if (mysql_num_rows($querydrop) > 0 )

       		 {

				while($rowd = mysql_fetch_array($querydrop)) 

				{

				if(checkIfStudentDropAllSubjects($student_id)&&!checkIfStudentPaidFull($student_id))

				{

					$refund_amount = (getTotalPaymentOfStudent($student_id,CURRENT_TERM_ID)-getStudentTotalFee($student_id)) / mysql_num_rows($querydrop);

				}

				else

				{

					$refund_amount = getRefundAmountBySubjectId($rowd["subject_id"],$student_id);

				}

				

			$subject!=""?$subject.=',':''	

				

        ?>

            <tr class="<?=($xd%2==0)?"":"highlight";?>">

            	<td><?=getSectionNo($rowd["schedule_id"])?></td>

                <td><?=getSubjCode($rowd["subject_id"])?></td> 

				<td><?=getSubjName($rowd["subject_id"])?></td>

				<td><?=$rowd["units"]?></td>

              <td class="action">

                <ul>

                <li><?php

         if(!checkIfStudentRefunded($rowd["subject_id"],$student_id))

		 {

		 	$subject.=$rowd["subject_id"];

			$am +=$refund_amount;

		 }

		 else

		 {

		 	echo 'Refunded';

		 }



		 	?></li>

                </ul>                </td>

            </tr>

		<?php  

		$un +=$rowd["units"];

			$xd++;         

           }

		  // echo $subject;

					$am = getTotalRefundAmount($student_id,CURRENT_TERM_ID);	

		   ?>

		  		<!-- <tr>

                 <td colspan="2">&nbsp;</td>

                 <td style="font-weight:bold; text-align:right">TOTAL</td>

                 <td><?=$un?></td>

                 <td><?php

                 	if($subject != '' && !checkIfStudentDropAllSubjects($student_id)&&getTotalPaymentOfStudent($student_id,CURRENT_TERM_ID)>getStudentTotalFee($student_id)&& !checkIfStudentPaidFull($student_id)&&$am>0)

					{

						echo 'Php '.number_format($am, 2, ".", ",").'<a class="refund" href="#" title="Refund" returnId='.$am.' returnSub='.$subject.'></a>';

					}

					else if($subject != '' && (checkIfStudentPaidFull($student_id)||checkIfStudentDropAllSubjects($student_id))&&$am>0)

					{

						echo 'Php '.number_format($am, 2, ".", ",").'<a class="refund" href="#" title="Refund" returnId='.$am.' returnSub='.$subject.'></a>';

					}

					else if(!checkIfStudentPaidFull($student_id)&&$subject != ''&&$am>0)

					{

						echo 'Deducted( Php '.number_format($am, 2, ".", ",").' )';

					}

				 ?>

                 </td>

                 </tr>!-->

		<?php

        }

        else 

        {

			?>

                <tr><td colspan="6">No records found</td></tr>

        	<?php

        }

        ?>

		</table> 

        

        <?php 

        	$sqlpaym = "SELECT * FROM tbl_student_payment WHERE student_id = ".$student_id." AND term_id =".CURRENT_TERM_ID;

			$resultpaym = mysql_query($sqlpaym);

          

        if (mysql_num_rows($resultpaym) > 0 )

        {

            $x = 0;

			$totalpay = 0;

        ?>

           <table class="listview">  

            <tr>

                <th class="col1_150" colspan="7">Payment History</th>   

          </tr>    

          <tr>

                <th class="col1_150">Term</th>  

                <th class="col1_150">Amount</th>   

                <th class="col1_50">Payment term</th>  

                <th class="col1_50">Payment method</th> 

                <th class="col1_50">Date created</th> 

                <th class="col1_50">OR #</th> 

                <th class="col1_50">Prepared by</th> 

          </tr>

           <?php

            while($rowm = mysql_fetch_array($resultpaym)) 

            { 

        ?>

            <tr class="<?=($x%2==0)?"":"highlight";?>">

                <td><?=getSYandTerm($rowm["term_id"])?></td>

                <td>Php<?=$rowm["amount"]?></td>

                <td><?=$rowm['is_refund']=='Y'?'-':getPaymentTerm($rowm["payment_term"])?></td>

                <?php

				if($rowm['is_refund']!='Y')

				{

					if($rowm["check_no"] != 0 && $rowm["bank"] != 'none')

					{

					?>

                        <td>

                            <?='Cheque Payment <br />'.$rowm["bank"].' ('.$rowm["check_no"].')'?>       

                        </td>

					<?php

                	}

					else

					{

					?>

						<td><?=getPaymentMethod($rowm["payment_method"])?></td>

					<?php

					}

				}

				else

				{

				?>

					<td>-</td>

				<?php

                }

				?>

                <td><?=date('F d, Y',$rowm['date_created'])?></td>

                <td> <?php /*if($rowm['is_bounced']=='Y')
				{
					echo 'class="remarks">Bounced Cheque';
				}
				else if($rowm['is_refund']=='Y')
				{
					echo 'class="remarks">Refund';
				}
				else
				{
					echo $rowm["remarks"];
				}*/
				echo $rowm["or_no"];?></td>

                <td><?=getEmployeeFullName($rowm["created_by"])?></td>

            </tr>

        <?php  

		if($rowm['is_bounced'] == 'N'){

		$totalpay += $rowm['amount']; 

		}        

           }

        }

        else 

        {

                echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';

        }

        ?>

        </table> 

        

 <label>&nbsp;</label>

       

<div class="fieldsetContainer50">

        

  <table class="classic">

            <tr>

                <th>Fees</th>

                <th>Amount</th>

                <th>Total</th>
                
                <!--<th>&nbsp;</th>!-->

            </tr> 

        <?php
		
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


?>

		 <tr>

                <td>Tuition Fee</td>

                <td><?=$row_lec['amount']?></td>

                <td>

                  <div align="right">

                    Php <?=$sub_lec_total==''?'0.00':number_format($sub_lec_total, 2, ".", ",")?>

                  </div></td>
                  
    </tr>


<?php
             $sql = "SELECT f.fee_type,s.* FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND f.id=s.fee_id AND s.is_removed = 'N' AND s.student_id = ".$student_id." AND s.term_id =" .CURRENT_TERM_ID;

            $result = mysql_query($sql);

            $sub_total = 0;

            while($row = mysql_fetch_array($result)) 

            {

                $total = $row['amount']*$row['quantity'];
			   

        ?>

            <tr>

                <td><?=getFeeName($row['fee_id']).' ('.$row['quantity'].')'?></td>

                <td><?=$row['amount']?></td>

                <td>

                  <div align="right">

                    Php <?=$total==''?'0.00':number_format($total, 2, ".", ",")?>

                  </div></td>
 
            </tr>

        <?php           

                $sub_total += $total;
				
				$sub_mis_total += $total;

           }


	$sub_total = $sub_total+$sub_lec_total;
		   
		   
		   $sql2 = "SELECT * FROM tbl_student_other_fees s,tbl_school_other_fee f WHERE f.id=s.fee_id AND s.is_removed <> 'Y' AND s.student_id=".$student_id." AND s.term_id=".CURRENT_TERM_ID;
				$query2 = mysql_query($sql2);
				$otherFee = mysql_num_rows($query2);
				
					while($row2 = mysql_fetch_array($query2)) 
					{
						$total = $row2['amount'];
						?>

            <tr>

                <td><?=$row2['fee_name'].' ('.$row2['quantity'].')'?></td>

                <td><?=$row2['amount']?></td>

                <td>

                  <div align="right">

                    Php <?=$total==''?'0.00':number_format($total, 2, ".", ",")?>

                  </div></td>
 
            </tr>

        <?php
						$sub_total += $row2['amount'];
						$sub_mis_total += $row2['amount'];
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



		  <table class="classic">

                    <tr>

                        <th colspan="5">LIBRARY FEES</th>

                    </tr>

                    <tr>

                        <th class="col1_150">Checked Out</th>

                        <th class="col1_150">Due Date</th>

                        <th class="col1_50">Day/s Late</th>

                        <th class="col1_100">Fee</th>

                    </tr> 

                <?php

                    $sql_out = "SELECT c.bibid, b.title, c.status_begin_dt, c.due_back_dt

					FROM ob_biblio b, ob_biblio_copy c, tbl_student m, ob_collection_dm d

					WHERE b.bibid = c.bibid

						AND c.student_id = m.id

						AND c.status_cd = 'out'	

						AND b.collection_cd=d.code and id =".$student_id;

						

					$query_out = mysql_query($sql_out);

					$lib_total = 0;

					

					if(mysql_num_rows($query_out) > 0)

					{

					  while($row_out = mysql_fetch_array($query_out))

					  {

						$sql = "SELECT b.collection_cd, 	

						floor(to_days(now())-to_days(c.due_back_dt)) days_late

						FROM ob_biblio b, ob_biblio_copy c, tbl_student m, ob_collection_dm d

						WHERE b.bibid = c.bibid

							AND c.student_id = m.id

							AND c.status_cd = 'out'

							AND b.collection_cd=d.code 

							AND c.bibid=".$row_out['bibid']. " 

							AND m.id=".$student_id;

						$query = mysql_query($sql);

						

						while($row = mysql_fetch_array($query))

						{

							if($row['days_late'] > 0)

							{

								$sql_due = "SELECT * FROM ob_collection_dm WHERE code=".$row['collection_cd'];

								$query_due = mysql_query($sql_due);

								

								$row_due = mysql_fetch_array($query_due);

								

								echo '<tr class="'.($x%2==0)?"":"highlight".'">';

								echo '<td>'.$row_out['title'].' ('.$row_out['status_begin_dt'].')</td>';

								echo '<td>'.$row_out['due_back_dt'].'</td>';

								echo '<td>'.$row['days_late'].'</td>';

								echo '<td>Php '.$row_due['daily_late_fee'].'</td>';

								echo '</tr>';

								

								$lib_total+=$row_due['daily_late_fee']*$row['days_late'];

							}

							

						}

					}

				}

				else

							{

								echo '<tr>

                        <td colspan="5">No Library Fees</td>

                    </tr>';

							}

                ?>                        

               

                    <tr>

                        <td>&nbsp;</td><td><div align="right"><strong>Total</strong></div></td>

                          <td colspan="3"><div align="right">

                            Php <?=number_format($lib_total, 2, ".", ",")?>

                          </div></td>

                    </tr>                              

              </table>

      

          

          <p>&nbsp;</p>

         

</div>       

   		<div class="fieldsetContainer50">

        <?php

          /* TOTAL AMOUNT */

	$sql = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id=" .CURRENT_TERM_ID;;

	$result = mysql_query($sql);

	$sub_total = 0;

	while($row = mysql_fetch_array($result)) 

	{

		$total = getStudentAmountFeeByFeeId($row['id'],$student_id);           

		$sub_total += $total;

	}

	

	/* TOTAL OTHER PAYMENT 

	$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id=" .CURRENT_TERM_ID;;

	$result_fee_other = mysql_query($sql_fee_other);

	$row_fee_other = mysql_fetch_array($result_fee_other);

	$sub_mis_total = 0;

	$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$student_id);           

	$sub_mis_total += $mis_total;*/

	

	

	/* TOTAL LEC PAYMENT 

	$sql_lec = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlec' AND publish =  'Y' AND term_id=" .CURRENT_TERM_ID;;

	$qry_lec = mysql_query($sql_lec);

	$row_lec = mysql_fetch_array($qry_lec);

	$sub_lec_total = 0;

	

	

	$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$student_id);           

	$sub_lec_total += $lec_total;*/

	

	/* TOTAL LAB PAYMENT 

	$sql_lab = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlab' AND publish =  'Y' AND term_id=" .CURRENT_TERM_ID;;

	$qry_lab = mysql_query($sql_lab);

	$row_lab = mysql_fetch_array($qry_lab);

	$sub_lab_total = 0;

	

	

	$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$student_id);           

	$sub_lab_total += $lab_total;*/

	

	/*TOTAL LEC AND LAB = LEC + LAB*/

	$sub_total =  $sub_lec_total + $sub_lab_total + $sub_mis_total;

	

	/*TOTAL UNITS = TUITION FEE - TOTAL OTHER PAYMENT*/

	$total_lec_lab = ($sub_total - $sub_mis_total);

	

	      /* TOTAL REFUND */

	$sql = "SELECT * FROM tbl_student_payment WHERE is_refund =  'Y' AND student_id = ".$student_id." AND term_id=" .CURRENT_TERM_ID;

	$result = mysql_query($sql);

	$ref_total = 0;

	while($row = mysql_fetch_array($result)) 

	{           

		$ref_total += $row['amount'];

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

   <!-- <tr>

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

    </tr>!-->

    <tr>

        <?php

			$sql_disc = "SELECT * FROM tbl_student_payment WHERE student_id=" .$student_id ." AND term_id=" .CURRENT_TERM_ID;

			$qry_disc = mysql_query($sql_disc);

			$row_disc = @mysql_fetch_array($qry_disc);

			$disc = $row_disc['discount_id'];

			$paymentId = $row_disc['payment_scheme_id'];
/*#############MODIFIED BY TINE###################################
			//$paymentId = getPaymentSchemeId($row_disc['payment_scheme_id']);

			

			$sql_discount = "SELECT * FROM tbl_discount WHERE publish = 'Y' AND id=" .$disc ." AND term_id=" .CURRENT_TERM_ID;;

			$qry_discount = mysql_query($sql_discount);

			$row_discount = @mysql_fetch_array($qry_discount);

			$discount = $row_discount['value'];

			

			$total_discounted = $sub_total / 100 * $discount;

			

		if($discount != ''){	

			?>

			<td>Discount - <?=$row_discount['name']?>(<?=$row_discount['value'].'%'?>) </td>

			<td><div align="right">Php <?=number_format($total_discounted, 2, ".", ",")?></div></td>

        <?php

        }

        else

        {

		?>

            <td>Discount:</td>

            <td><div align="right">Php 0.00</div></td>

            <?php

		}*/
		
			$surcharge = GetSchemeForSurcharge($student_id)*$total_units;
			
			$sqldis = 'SELECT * FROM tbl_student WHERE id='.$student_id;
			$querydis = mysql_query($sqldis);
			$rowdis = mysql_fetch_array($querydis);
			
			if($rowdis['scholarship_type']=='A')
			{
				$discount = ($sub_total+$surcharge)-5000;
						if($otherFee>0)
						{
							$discount = $discount-6920;
						}
				$discount = ($discount*$rowdis['scholarship'])/100;
			}
			
			else
			{

				$discount = $sub_lec_total+$surcharge;
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
		

		$credit = 0;//getCarriedBalances($student_id,CURRENT_TERM_ID);

		$debit = getCarriedDebits($student_id,CURRENT_TERM_ID);

		$sub_total = $sub_total-$total_discounted;

		$sub_total = abs($sub_total - $debit);

		$sub_total = $sub_total + $credit;
		
		$sub_total = $sub_total - $discount;
		$sub_total = $sub_total + $surcharge;
		
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

        Php <?=number_format($sub_total, 2, ".", ",")?>

        </strong></div></td>

    </tr>

    

    <?php

    	//TOTAL PAYMENT

		$total_payment = getTotalPaymentOfStudent($student_id,CURRENT_TERM_ID); 

		

		//TOTAL REFUND

		/*if(checkIfStudentDropAllSubjects($student_id)&&!checkIfStudentPaidFull($student_id))

		{

			$total_refund = abs($total_payment-$sub_total);

		}

		else if(!checkIfStudentDropAllSubjects($student_id)&&$total_payment>$sub_total&& !checkIfStudentPaidFull($student_id))

		{

			$total_refund = abs($total_payment-$sub_total);

		}*/

			//$total_refund = getTotalRefundAmount($student_id,CURRENT_TERM_ID);


		?>

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

        <tr>

        <td><strong>Total Payment:</strong></td>

        <td><div align="right"><strong>

        Php 

        <?=number_format($total_payment, 2, ".", ",")?>

        </strong></div></td>

    </tr>

    <tr>

        <td colspan="2" class="bottom"></td>

    </tr>

    	<?php		

		//$sub_total = ($sub_total - $total_discounted)-$total_payment;

		//TOTAL

		if(checkIfStudentPaidFull($student_id)&&$total_refund != 0)

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

			

		?>

    <tr>

        <td><strong>Total Remaining Balance:</strong></td>

        <td><div align="right"><strong>

        Php 

        <?=number_format($total_rem, 2, ".", ",")?>

        </strong></div></td>

    	<input type="hidden" name="sub_total" id="sub_total" value="<?=$total_rem?>" />

    </tr>

 </table>

          

        </div>

          <p>
<a class="pay" href="#" title="Pay Balance" returnId="0" returnPay="0" returnCarried="0" returnSort="" returnDown="0" returnComp="'<?=$_REQUEST['comp']?>'">PAY</a>
      <?php if(!checkIfStudentDropAllSubjects($student_id))

	  {

	  ?> 

          <table class="listview_classic">

        	<tr>

         	<th class="col_150" colspan="2">Schedule of Fees</th>

            <th class="col_150">Paid Balance</th>

            <th class="col_150">Remaining Balance</th>

            <th class="col_150">Remarks</th>

            <th class="col_50">Pay / Cheque</th>

            </tr>

			<?php

				

                $sqlsch = "SELECT *

                        FROM tbl_payment_scheme_details

                        WHERE scheme_id = ".$paymentId." ORDER BY sort_order";

                        

                $resultsch = mysql_query($sqlsch);

			

                while($rowsch = @mysql_fetch_array($resultsch)) 

                {

					if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'A')

					{

						$topay = $rowsch['payment_value'];

						$total_fee = $total_fee - $topay;

					}

					else if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'P')

					{

						$topay = abs(getStudentPaymentScheme($rowsch['id'],$total_fee));

					}

					else if($rowsch['payment_type'] == 'P')

					{

						$topay = abs(getStudentPaymentScheme($rowsch['id'],$total_fee));

					}

		     ?>

             	<tr>

                    <td><?=$rowsch['payment_name'].' on/before('.$rowsch['payment_date'].')'?></td>

                  	<td>Php <?=number_format($topay, 2, ".", ",")?></td>

             <?php

			 		if($rowsch['sort_order'] == 1 && ($total_payment >= $topay))

					{

						$bal = 0;

						$paid = $topay;

						$carried = $total_payment - $topay;

						$remarks = 'Paid';

						$order = $rowsch['sort_order'];

						$num_payment = 1+1;

					}

					else if($rowsch['sort_order'] == 1 && $total_payment <= $topay)

					{

						$bal = abs($total_payment - $topay);

						$paid = abs($topay - $bal);

						$carried = $total_payment - $topay;

						$remarks = 'Unpaid';

						$order = $rowsch['sort_order'];

						$num_payment = 1;

					}

					else if($carried > 0 && $carried >= $topay)

					{

						$bal = 0;

						$paid = $topay;

						$carried = $carried - $topay;

						$remarks = 'Paid';

						$order = $rowsch['sort_order'];

						$num_payment = $num_payment+1;

					}

					else if($carried > 0 && $carried < $topay)

					{

						$bal = $topay - $carried;

						$paid = $carried;

						$carried = 0;

						$remarks = 'Unpaid';

						$order = $rowsch['sort_order'];

						$num_payment = $num_payment;

					}

					else

					{

						$bal = $topay;

						$paid = 0;

						$carried = 0;

						$remarks = 'Unpaid';

						$order = $rowsch['sort_order'];

						$num_payment = $num_payment;

					}

					

			 ?>

                     <td><?='Php '.number_format($paid, 2, ".", ",")?></td>

                    <td><?='Php '.number_format($bal, 2, ".", ",")?></td>

                    <td><?=$remarks?>

                    </td>

                    <td class="action">

                    <ul>

                        <?php

                        if($bal!=0&&$num_payment==$order)

						{

						echo '<li><a class="pay" href="#" title="Pay Balance" returnId="'.$rowsch['id'].'" returnPay="'.$topay.'" returnCarried="'.$paid.'" returnSort="'.$status.'" returnDown="'.$down.'" returnComp="'.$_REQUEST['comp'].'"></a></li>';

							/*if(checkPaymentIfCheque($student_id))

							{

							echo '<li><a class="check" href="#" title="Bounce Check" returnId="'.$rowpay["check_no"].'" returnComp="'.$_REQUEST['comp'].'"></a></li>';

							}*/

						}

						/*else if(checkPaymentIfCheque($student_id)&&$order==mysql_num_rows($resultsch)&&$bal==0)

						{

						echo '<li><a class="check" href="#" title="Bounce Check" returnId="'.$rowpay["check_no"].'" returnComp="'.$_REQUEST['comp'].'"></a></li>';

						}*/

						?>

                    </ul>

                </td>

                </tr>

             <?php

			   }

            ?>           

          </table>

          	<?php

			   }

            ?>

<p id="pagin"></p>   

   

   </div>

   </div>

   </div>

   </div>

   </div>     

        

<?php

	}

	//else 

	//{

	//	echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';

	//}

//}//end of enrolled student

else

{

//student is reserved	

		

		$sql = "SELECT * FROM 

							tbl_student_reserve_subject st, tbl_schedule sc 

						WHERE
							st.schedule_id = sc.id AND 

							st.term_id = ".CURRENT_TERM_ID." AND

							st.student_id= " .$student_id;


		$result = mysql_query($sql);

		$row = mysql_fetch_array($result);

		$status = 'RESERVED';

 

        if (mysql_num_rows($result) > 0 )

        {



	$sql_info = "SELECT * FROM tbl_student WHERE id = $student_id";

	$query_info = mysql_query($sql_info);

	$row_info = mysql_fetch_array($query_info);		

?>    

    <div id="print_div">

<div id="printable">

<div class="body-container">

<div class="header">

<div class="headerForm">       

    <table class="classic_borderless">

      <tr>

        <td valign="top" style='font-weight:bold'>Student Name:</td>

        <td><?=$row_info['lastname']. ", " . $row_info['firstname'] ." " . $row_info['middlename']?></td>

      </tr>

      <tr>

        <td style='font-weight:bold' valign="top">Student Number:</td>

        <td><?=$row_info['student_number']?></td>

      </tr>

      <tr>

        <td style='font-weight:bold' valign="top">Course:</td>

        <td><?=getCourseName($row_info['course_id'])?></td>

      </tr>

      <tr>

        <td style='font-weight:bold' valign="top">Status:</td>

        <td><?=$status?></td>

      </tr>

    </table> 



  </div>

  </div>

<!--</div>

</div>

  </div>!-->



<div class="content-container">



<div class="content-wrapper-wholeBorder">

<!--<div id="print_div2">

<div id="printable">!-->

        <?php  

        if (mysql_num_rows($result) > 0 )

        {

            $x = 0;

        ?>

        <table class="listview_classic">    

  			<tr>

            <td colspan=4 align="right">

            <a class="viewer_email" href="#" id="email2" title="email2"></a>

            <a class="viewer_pdf" href="#" id="pdf2" title="pdf2"></a>

            <a class="viewer_print" href="#" id="print2" title="print"></a>

            </td>

		</tr>

          <tr>

          	  <th class="col_50">Section</th>

              <th class="col_50">Code</th>

              <th class="col_300">Subject Name</th>

			  <th class="col_50">Units</th>

			 <!-- <th class="col_100">Schedule</th>!-->

          </tr>

        <?php

            

//CURRENT_TERM_ID

			$sql = "SELECT * FROM 

							tbl_student_reserve_subject st, tbl_schedule sc 

						WHERE
							st.schedule_id = sc.id AND 

							st.term_id = ".CURRENT_TERM_ID." AND

							st.student_id= " .$student_id;

			$result = mysql_query($sql);

			$total_units = 0;

			while($row = mysql_fetch_array($result)) 

            {

				$total_units += getSubjUnit($row["subject_id"]);

        ?>

            <tr class="<?=($x%2==0)?"":"highlight";?>">

            	<td><?=getSectionNo($row["schedule_id"])?></td>

                <td><?=getSubjCode($row["subject_id"])?></td> 

				<td><?=getSubjName($row["subject_id"])."(".getSubjName($row['elective_of']).")"?></td>

				<td><?=$row["units"]?></td>

				<!--<td><?=getScheduleDays($row['schedule_id'])?></td>!-->

            </tr>

		<?php  

			$x++;         

           }

		?>

            <tr>

                <td colspan="3">&nbsp;</td> 

				<td><?=$total_units?></td>

			</tr>        

		<?php

        }

        else 

        {

                echo "No records found";

        }

        ?>

		</table> 



      <label>&nbsp;</label>

  <!--</div>

  </div>!-->

  

   		<div class="fieldsetContainer50">

          <!--<div id="print_div3">

<div id="printable">!-->

		 <table class="classic">

            <tr>

                <th>Fees</th>

                <th>Amount</th>

                <th>Total</th>
                
                <!--<th>&nbsp;</th>!-->

            </tr> 

        <?php
		
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

			

			

			$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$student_id);           

			$sub_lab_total += $lab_total;

?>

		 <tr>

                <td>Tuition Fee</td>

                <td><?=$row_lec['amount']?></td>

                <td>

                  <div align="right">

                    Php <?=$sub_lec_total==''?'0.00':number_format($sub_lec_total, 2, ".", ",")?>

                  </div></td>
                  
    </tr>


<?php
             $sql = "SELECT f.fee_type,s.* FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND f.id=s.fee_id AND s.is_removed = 'N' AND s.student_id = ".$student_id." AND s.term_id =" .CURRENT_TERM_ID;

            $result = mysql_query($sql);

            $sub_total = 0;

            while($row = mysql_fetch_array($result)) 

            {

                $total = $row['amount']*$row['quantity'];
			   

        ?>

            <tr>

                <td><?=getFeeName($row['fee_id']).' ('.$row['quantity'].')'?></td>

                <td><?=$row['amount']?></td>

                <td>

                  <div align="right">

                    Php <?=$total==''?'0.00':number_format($total, 2, ".", ",")?>

                  </div></td>

            </tr>

        <?php           

                $sub_total += $total;
				
				$sub_mis_total += $total;

           }

	
	$sub_total = $sub_total+$sub_lec_total;
		   
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



		  <table class="classic">

                    <tr>

                        <th colspan="5">LIBRARY FEES</th>

                    </tr>

                    <tr>

                        <th class="col1_150">Checked Out</th>

                        <th class="col1_150">Due Date</th>

                        <th class="col1_50">Day/s Late</th>

                        <th class="col1_100">Fee</th>

                    </tr> 

                <?php

                    $sql_out = "SELECT c.bibid, b.title, c.status_begin_dt, c.due_back_dt

					FROM ob_biblio b, ob_biblio_copy c, tbl_student m, ob_collection_dm d

					WHERE b.bibid = c.bibid

						AND c.student_id = m.id

						AND c.status_cd = 'out'	

						AND b.collection_cd=d.code and id =".$student_id;

						

					$query_out = mysql_query($sql_out);

					$lib_total = 0;

					

					if(mysql_num_rows($query_out) > 0)

					{

					  while($row_out = mysql_fetch_array($query_out))

					  {

						$sql = "SELECT b.collection_cd, 	

						floor(to_days(now())-to_days(c.due_back_dt)) days_late

						FROM ob_biblio b, ob_biblio_copy c, tbl_student m, ob_collection_dm d

						WHERE b.bibid = c.bibid

							AND c.student_id = m.id

							AND c.status_cd = 'out'

							AND b.collection_cd=d.code 

							AND c.bibid=".$row_out['bibid']. " 

							AND m.id=".$student_id;

						$query = mysql_query($sql);

						

						while($row = mysql_fetch_array($query))

						{

							if($row['days_late'] > 0)

							{

								$sql_due = "SELECT * FROM ob_collection_dm WHERE code=".$row['collection_cd'];

								$query_due = mysql_query($sql_due);

								

								$row_due = mysql_fetch_array($query_due);

								

								echo '<tr class="'.($x%2==0)?"":"highlight".'">';

								echo '<td>'.$row_out['title'].' ('.$row_out['status_begin_dt'].')</td>';

								echo '<td>'.$row_out['due_back_dt'].'</td>';

								echo '<td>'.$row['days_late'].'</td>';

								echo '<td>Php '.$row_due['daily_late_fee'].'</td>';

								echo '</tr>';

								

								$lib_total+=$row_due['daily_late_fee']*$row['days_late'];

							}

							

						}

					}

				}

				else

							{

								echo '<tr>

                        <td colspan="5">No Library Fees</td>

                    </tr>';

							}

                ?>                        

               

                    <tr>

                        <td>&nbsp;</td><td><div align="right"><strong>Total</strong></div></td>

                          <td colspan="3"><div align="right">

                            Php <?=number_format($lib_total, 2, ".", ",")?>

                          </div></td>

                    </tr>                              

              </table>

      

          <p>&nbsp;</p>

		<!--</div>

		</div>!-->

		  

          <table class="classic" style="background-color:#FBFBFB" id="table_payment" >

            <tr>

              <th colspan="2"><strong>Payment Set-Up</strong></th>

            </tr>
            <tr>
            <td width="40%"><strong>Payment Date</strong></td>
            <td>
            
            <select name="p_month" id="p_month" class="txt_100 {required:true}" title="Month field is required">
                    <option value="" selected="selected"></option>
                    <?=generateMonth(date('m'))?>
                </select>
                
                <select name="p_day" id="p_day" class="txt_50 {required:true}" title="Day field is required">
                    <option value="" selected="selected"></option>
                    <?=generateDay(date('d'))?>
                </select>
                
                <select name="p_year" id="p_year" class="txt_70 {required:true}" title="Year field is required">
                    <option value="" selected="selected"></option>
                    <?=generateYearForSchoolYear(date('Y'))?>
                </select>
            
            </td>
            </tr>

     <!-- <tr>

              <td width="40%"><strong>Payment Scheme</strong></td>

              <td>

                  <select name="scheme_id" class="txt_150" id="scheme_id">

                  <option value="">Select</option>

                        <?=generateScheme($payment_scheme_id)?>

              </select>              

              </td>

            </tr>!-->

              	  <input type="hidden" name="student_id" id="student_id" value="<?=$student_id?>" />
<!-- MODIFIED BY: TINE (DISCOUNT & SURCHARGE)
                  <select name="discount_id" class="txt_150" id="discount_id">

                  <option value="0">None</option>

                        <?=generateDiscount($discount)?>

              </select>      
    !-->           
              <tr>

              <td width="40%"><strong></strong></td>

              <td>     
               <input type="hidden" class="txt_150" name="discount_id" id="discount_id" value="<?=$discount_id?>" />
              
                

              </td>

            </tr> 

            <tr>

              <td width="40%"><strong></strong></td>

              <td>

              	  <input type="hidden" class="txt_150" name="surcharge" id="surcharge" value="<?=$surcharge?>" /></td>

            </tr>   

             <tr>

              <td width="40%"><strong>O.R Number</strong></td>

              <td><input type="text" class="txt_150" name="OR" id="OR" value="<?=generateOR()?>" /></td>

            </tr>                                   

            <tr>

              <td width="40%"><strong>Payment Method</strong></td>

              <td>

                  <select name="payment_method" class="txt_150" id="payment_method">

                  <option value="">Select</option>

                        <?=generatePaymentMethod($payment_met)?>

              </select>              </td>

            </tr>  

            <tr>



                <td colspan="2">

                    <div id="chek">

                    </div>

                </td>



            </tr>

            <tr>

              <td>&nbsp;</td>

              <td><a href="#" class="cash-button" title="Save" id="save"><span>Save</span></a></td>

            </tr>                                        

          </table>

		  </div>

        

	

   		<div class="fieldsetContainer50">

		<!--<div id="print_div4">

<div id="printable">!-->	

          <table class="classic_borderless">

                <?php

				$sql_reservation ="SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status ='R' AND student_id =" .$student_id . " AND term_id = ".CURRENT_TERM_ID;

				

				$qry_reservation = mysql_query($sql_reservation);

				$row_reservation = mysql_fetch_array($qry_reservation);

				$ctr_reservation = mysql_num_rows($qry_reservation);

					

				if($ctr_reservation > 0)

				{	

				?>

                <tr>

                  <td><strong>Reservation Date:</strong></td>

                  <td><?=date('F d, Y', $row_reservation['date_reserved'])?></td>

                </tr>

                <tr>

                  <td><strong>Last Day of Payment:</strong></td>

                  <td><?=date('F d, Y', $row_reservation['expiration_date'])?></td>

                </tr>

                <?php

				}

				else

				{	

					$sql_reservation ="SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status ='R' AND student_id =" .$student_id . " AND term_id = ".CURRENT_TERM_ID;

					

					$qry_reservation = mysql_query($sql_reservation);

					$row_reservation = mysql_fetch_array($qry_reservation);

					$ctr_reservation = mysql_num_rows($qry_reservation);

				

				?>

                <tr>

                  <td><strong>Date Enrolled:</strong></td>

                  <td><?=date('F d, Y', $row_reservation['date_enrolled'])?></td>

                </tr>

                <?php

				}

				?>

                <tr>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                </tr>

		</table>

        <div id="computation_container">

        </div> 

<!--</div>

</div>!-->

</div>		

       

   		    <p id="pagin"></p> 

<?php

	}

	else 

	{

		echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';

	}

}//end

?>

<!-- LIST LOOK UP-->

<div id="dialog" title="Payment">

    Loading...

</div><!-- #dialog_2 -->

<div id="dialog_2" title="Bounce Cheque">

    Loading...

</div><!-- #dialog_2 -->

<!--

</div>

</div>

-->

