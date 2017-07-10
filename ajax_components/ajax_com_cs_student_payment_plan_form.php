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
	echo '<div id="message_container"><h4>Student is already enrolled</h4></div><p id="formbottom"></p>';
	
	}//end of enrolled student

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

			  <!--<th class="col_100">Schedule</th>!-->

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
		
		//$stud_term = getStudentEnrollTerm($student_id);

        ?>

		</table> 



        <label>&nbsp;</label>

  <!--</div>

  </div>!-->

  

   		<div class="fieldsetContainer50">

          <!--<div id="print_div3">

<div id="printable">!-->

<table class="classic" style="background-color:#FBFBFB" id="table_payment" >

            <tr>

              <th colspan="2"><strong>Payment Set-Up</strong></th>

            </tr>
            <!--<tr>
            <td width="40%"><strong>Payment Date</strong></td>
            <td>
            
            <select name="p_month" id="p_month" class="txt_100 {required:true}" title="Month field is required">
                    <option value="" selected="selected"></option>
                    <?//=generateMonth(date('m'))?>
                </select>
                
                <select name="p_day" id="p_day" class="txt_50 {required:true}" title="Day field is required">
                    <option value="" selected="selected"></option>
                    <?//=generateDay(date('d'))?>
                </select>
                
                <select name="p_year" id="p_year" class="txt_70 {required:true}" title="Year field is required">
                    <option value="" selected="selected"></option>
                    <?//=generateYearForSchoolYear(date('Y'))?>
                </select>
            
            </td>
            </tr>!-->

            <tr>

              <td width="40%"><strong>Payment Scheme</strong></td>

              <td>

                  <select name="scheme_id" class="txt_150" id="scheme_id">

                  <option value="">Select</option>

                        <?=generateScheme($payment_scheme_id)?>

              </select>              

              </td>

            </tr>

              	  <input type="hidden" name="student_id" id="student_id" value="<?=$student_id?>" />
<!-- MODIFIED BY: TINE (DISCOUNT & SURCHARGE)
                  <select name="discount_id" class="txt_150" id="discount_id">

                  <option value="0">None</option>

                        <?//=generateDiscount($discount)?>

              </select>      
              
              <tr>

              <td width="40%"><strong></strong></td>

              <td>     
               <input type="hidden" class="txt_150" name="discount_id" id="discount_id" value="<?//=$discount_id?>" />
              
                

              </td>

            </tr> !--> 

            <tr>

              <td width="40%"><strong></strong></td>

              <td>

              	  <input type="hidden" class="txt_150" name="surcharge" id="surcharge" value="<?=$surcharge?>" /></td>

            </tr>   

            <!-- <tr>

              <td width="40%"><strong>O.R Number</strong></td>

              <td><input type="text" class="txt_150" name="OR" id="OR" value="<?//=generateOR()?>" /></td>

            </tr>                                  

            <tr>

              <td width="40%"><strong>Payment Method</strong></td>

              <td>

                  <select name="payment_method" class="txt_150" id="payment_method">

                  <option value="">Select</option>

                        <?//=generatePaymentMethod($payment_met)?>

              </select>              </td>

            </tr>  

            <tr>



                <td colspan="2">

                    <div id="chek">

                    </div>

                </td>



            </tr>!--> 

            <tr>

              <td>&nbsp;</td>

              <td><a href="#" class="cash-button" title="Save" id="save"><span>Save</span></a></td>

            </tr>                                        

          </table>
          
          <p>&nbsp;</p>
           <p>&nbsp;</p>


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
          <p>&nbsp;</p>

		<!--</div>

		</div>!-->

		  

          

		  </div>

        

	

   		<div class="fieldsetContainer50">

		<!--<div id="print_div4">

<div id="printable">	

          <table class="classic_borderless">!-->

                <?php

				$sql_reservation ="SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status ='R' AND student_id =" .$student_id . " AND term_id = ".CURRENT_TERM_ID;

				

				$qry_reservation = mysql_query($sql_reservation);

				$row_reservation = mysql_fetch_array($qry_reservation);

				$ctr_reservation = mysql_num_rows($qry_reservation);

					

				if($ctr_reservation > 0)

				{	

				?>

                <!--<<tr>

                  <td><strong>Reservation Date:</strong></td>

                  <td><?//=date('F d, Y', $row_reservation['date_reserved'])?></td>

                </tr>

                <tr>

                  <td><strong>Last Day of Payment:</strong></td>

                  <td><?//=date('F d, Y', $row_reservation['expiration_date'])?></td>

                </tr>!-->

                <?php

				}

				else

				{	

					$sql_reservation ="SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status ='R' AND student_id =" .$student_id . " AND term_id = ".CURRENT_TERM_ID;

					

					$qry_reservation = mysql_query($sql_reservation);

					$row_reservation = mysql_fetch_array($qry_reservation);

					$ctr_reservation = mysql_num_rows($qry_reservation);

				

				?>

                <!--<<tr>

                  <td><strong>Date Enrolled:</strong></td>

                  <td><?//=date('F d, Y', $row_reservation['date_enrolled'])?></td>

                </tr>!-->

                <?php

				}

				?>

                <!--<<tr>

                  <td>&nbsp;</td>

                  <td>&nbsp;</td>

                </tr>

		</table>!-->
        <?php
		//TEMPORARY 
		
		$bal = getStudentTotalFee($student_id,15)-getTotalPaymentOfStudent($student_id,15);
		
		if($bal>0)
		{
		?>
        <table class="classic_borderless">
  <tr style="color:red; font-weight:bold;">
    <td>OUTSTANDING BALANCE</td>
    <td>Php <?=number_format($bal, 2, ".", ",")?></td>
  </tr>
</table>
<?php } ?>


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


