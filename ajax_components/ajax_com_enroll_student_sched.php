<?php
include_once("../config.php");
include_once('../includes/functions.php');	
include_once('../includes/common.php');	


if(USER_IS_LOGGED != '1'){
	header('Location: ../index.php');
}else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])){
	header('Location: ../forbid.html');
}

$student_id = $_REQUEST['student_id'];
?>

<script type="text/javascript">

	$(function(){
		$('#list').click(function(){
			clearTabs();
			$('#list').addClass('active');
			$('#view').val('list');
			$('#action').val('list');
			updateList();
			$("form").submit();
		});	

		$('#print').click(function(){
			var w=window.open();
			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
			w.document.write($('#print_div').html());
			w.document.close();
			w.focus();
			w.print();
			w.close()
			return false;
		});

		$('#pdf').click(function(){
			var w=window.open ("pdf_reports/rep108.php?id="+<?=$student_id?>+"&trm="+<?=CURRENT_TERM_ID?>+"&met=assessment"); 
			return false;
		});

		$('#email').click(function(){
			if(confirm("Are you sure you want to email this file to this student?")){
				$.ajax({
					type: "POST",
					data: "id="+<?=$student_id?>+"&trm="+<?=CURRENT_TERM_ID?>+"&met=assessment&email=1",
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
			}else{
				return false;
			}
		});

		$('.drop').click(function(){
			var subj = $(this).attr('returnId');
			var sched = $(this).attr('returnSched');
			if(confirm('Are you sure you want to drop this subject?')){
				if(confirm('Will this remove from the fees?')){
					$('#deduct').val('Y');
				}

				$('#dropped').val(subj);
				$('#sched').val(sched);
				$('#view').val('enroll');
				$('#action').val('drop');
				$("form").submit();
			}else{
				return false;
			}
		});

		// Dialog	
        $('#dialog_sub').dialog({
            autoOpen: false,
            width: 840,
            height: 500,
            bgiframe: true,
            modal: true,
            buttons:{
                "Close": function(){
                    $(this).dialog("close"); 
				} 
            }
        });

        // Dialog Link
        $('#subject').click(function(){
            var id = $(this).attr("returnId");	
			var param = $(this).attr("returnComp");	
			var param2 = $(this).attr("returnUnit");	

            //alert('ids'+iD);
            $('#dialog_sub').load('lookup/lookup_com_st_add_schedule.php?id='+id+'&comp='+param+'&unit='+param2, null);
            $('#dialog_sub').dialog('open');
            return false;
        });
	});
	
	//DIALOG2
	$('#dialog2').dialog({
		autoOpen: false,
		width: 840,
		height: 500,
		bgiframe: true,
		modal: true,

		buttons:{
			"Close": function(){
				$(this).dialog("close"); 
			} 
		}
	});
       
	// Dialog Link
	$('.refund').click(function(){
		var id = $(this).attr("returnStudId");
		var sub_id = $(this).attr("returnId");
		var param2 = $(this).attr("returnSched");	
		var param = $(this).attr("returnComp");	

		//alert('ids'+iD);
		$('#dialog2').load('lookup/lookup_com_st_change_schedule.php?id='+id+'&comp='+param+'&sched='+param2+'&sub='+sub_id, null);
		$('#dialog2').dialog('open');
		return false;
	});

	
	function removeE(id){
		if(confirm("Are you sure you want to remove this from the list?")){
			$('#action').val('remove');
			$('#misc_id').val(id);
			updateList();
			$("form").submit();
		}		
	}
	
	function addE(id){
		if(confirm("Are you sure you want to add this to the list?")){
			$('#action').val('add');
			$('#misc_id').val(id);
			updateList();
			$("form").submit();
		}
	}
	
	function removeE2(id){
		if(confirm("Are you sure you want to remove this from the list?")){
			$('#action').val('remove2');
			$('#misc_id').val(id);
			updateList();
			$("form").submit();
		}
	}
	
	function addE2(id){
		if(confirm("Are you sure you want to add this to the list?")){
			$('#action').val('add2');
			$('#misc_id').val(id);
			updateList();
			$("form").submit();
		}
	}

</script>

<?php

$sql = "SELECT * FROM tbl_student WHERE id = ".$student_id;						
$query = mysql_query($sql);
$rows = mysql_fetch_array($query);

//echo $_REQUEST['comp'];	

?>

<div id="print_div">
	<div id="printable">
		<div class="body-container">
			<div class="header">
				<div class="headerForm">         
					<table class="classic_borderless">
						<tr>
							<td valign="top" style='font-weight:bold'>Student Number:</td>
							<td><?=$row['student_number']?></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td style='font-weight:bold' valign="top">Student Name:</td>
							<td><?=$rows['lastname']. ", " . $rows['firstname'] ." " . $rows['middlename']?></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>

<?php
$birthday = explode ('-',$rows['birth_date']);		
$birth_year = $birthday['0'];
$birth_day = $birthday['1'];
$birth_month = $birthday['2'];
?>

						<tr>
							<td style='font-weight:bold' valign="top">Date of Birth:</td>
							<td><?=date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year))?></td>
							<td><span style="font-weight:bold">Sex:</span></td>
							<td><?=$rows['gender']=='F'?'Female':'Male'?></td>
						</tr>
						<tr>
							<td style='font-weight:bold' valign="top">Department:</td>
							<td><?=getStudentCollegeName($rows['course_id'])?></td>
							<td><span style="font-weight:bold">Curriculum:</span></td>
							<td><?=getCurriculumCode($rows['curriculum_id'])?></td>
						</tr>
<?php
if(checkIfStudentReservationIsExpiredByStudId($student_id)){
?>

						<tr>
							<td style='font-weight:bold' valign="top">Enrollment Remarks:</td>
							<td colspan="3">The enrollment reservation of this student has expired.</td>
						</tr>

<?php
}
?>     
					</table>  
				</div> 
			</div>

			<div class="content-container">
				<div class="content-wrapper-wholeBorder">
<?php
//if(checkIfStudentIsEnroll($student_id)){	//THON - check if student is enrolled
if(1==1){									//THON - student is assumed to be enrolled
	// student is paid
	$sql = "SELECT 
				stud_sched.subject_id,
				stud_sched.units,
				stud_sched.term_id,
				stud_sched.enrollment_status, 
				stud_sched.schedule_id ,
				sched.elective_of
			FROM 
				tbl_student_schedule stud_sched LEFT JOIN tbl_schedule sched ON 
				stud_sched.schedule_id = sched.id
				WHERE stud_sched.enrollment_status = 'A' AND 
				stud_sched.student_id =  " . $student_id . " AND
				stud_sched.term_id = " . CURRENT_TERM_ID
				;	
	$query = mysql_query($sql);
	$ctr = mysql_num_rows($query);
?>

					<table class="listview">

	<?php
	if($ctr > 0 ){
		$x = 1;
		$enroll_units = 0;
		$enroll_subj = array();
	?>
						<tr>
							<td colspan="8">
								<a class="viewer_email" href="#" id="email" title="email"></a>
								<a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
								<a class="viewer_print" href="#" id="print" title="print"></a>
							</td>
						</tr> 
						<tr>
							<th class="col_100">Section</th>
							<th class="col_100">Code</th>
							<th class="col_300">Subject Name</th>
							<th class="col_50">Units</th>
							<th class="col_150">Schedule</th>
							<th class="col_20">Action</th>
						</tr>
		<?php
		while($row = mysql_fetch_array($query)){
		?>
						<tr class="<?=($x%2==0)?"":"highlight";?>">
							<td><?="(".$row["schedule_id"].")".getSectionNo($row["schedule_id"])?></td> 
							<td><?="(".$row["subject_id"].")".getSubjCode($row["subject_id"])?></td>
							<td><?=getSubjName($row["subject_id"])."(".getSubjName($row['elective_of']).")"?></td>
							<td><?=getSubjUnit($row["subject_id"])?></td>
							<td><?=getScheduleDays($row["schedule_id"])?></td>
							<td class="action">
								<ul>
								<?php if(ACCESS_ID==1){ ?>
									<li><a class="drop" href="#" name="drop" returnId="<?=$row["subject_id"]?>" returnSched="<?=$row["schedule_id"]?>" title="Drop Subject"></a></li>
									<li><a class="refund" href="#" name="change" returnStudId="<?=$student_id?>" returnId="<?=$row["subject_id"]?>" returnComp="<?=$_REQUEST['comp']?>" returnSched="<?=$row["schedule_id"]?>" title="Change Subject"></a></li>
								<?php }else if(strtotime(getDroppingAddingEndDate($rows['course_id'],CURRENT_TERM_ID))>=strtotime(date('Y-m-d'))){ ?>
									<li><a class="drop" href="#" name="drop" returnId="<?=$row["subject_id"]?>" returnSched="<?=$row["schedule_id"]?>" title="Drop Subject"></a></li>
									<li><a class="refund" href="#" name="change" returnStudId="<?=$student_id?>" returnId="<?=$row["subject_id"]?>" returnComp="<?=$_REQUEST['comp']?>" returnSched="<?=$row["schedule_id"]?>" title="Change Subject"></a></li>
								<?php } ?>
								</ul>
							</td>                              
						</tr>
		<?php  
			$x++;   
			$enroll_units += getSubjUnit($row["subject_id"]);     
		}
	}else {
	?>
						<tr>
							<td colspan="8">All Subjects Dropped</td>
						</tr>
	<?php
	}
	?>
					</table> 

					<div class="fieldsetContainer50">      
						<label>Total Enrolled Units: <?=$enroll_units?></label>      
						<label>Total allowed Units: <?=getCurrentStudentMaxEnrolledUnit($student_id)?></label>
						<label>&nbsp;</label>
					</div>

					<input type="hidden" name="max_unit" id="max_unit" value="<?=getStudentMaxEnrolledUnit($student_id)?>" >
					<input type="hidden" name="deduct" id="deduct" value="N" >

					<div class="btn_container">
						<p class="button_container">
						
						<?php if(ACCESS_ID==1){ ?>
							<a href="#" class="button" title="Add Subject" id="subject" returnId="<?=$student_id?>" returnComp="<?=$_REQUEST['comp']?>" returnUnit="<?=getStudentMaxEnrolledUnit($student_id)?>"><span>Add Subject</span></a>
						<?php }else if(strtotime(getDroppingAddingEndDate($rows['course_id'],CURRENT_TERM_ID))>=strtotime(date('Y-m-d'))){ ?>
							<a href="#" class="button" title="Add Subject" id="subject" returnId="<?=$student_id?>" returnComp="<?=$_REQUEST['comp']?>" returnUnit="<?=getStudentMaxEnrolledUnit($student_id)?>"><span>Add Subject</span></a>
						<?php } ?>
						</p>
					</div>

					<div id="dialog_sub" title="Add Subject">
						Loading...
					</div><!-- #dialog -->

					<div id="dialog2" title="Change Subject">
						Loading...
					</div><!-- #dialog -->
					
					<p>&nbsp;</p>

	<?php
	$sqldrop = "SELECT 
			stud_sched.subject_id,
			stud_sched.units,
			stud_sched.term_id,
			stud_sched.enrollment_status, 
			stud_sched.schedule_id,
			sched.room_id, 
			sched.section_no
		FROM 
			tbl_student_schedule stud_sched LEFT JOIN tbl_schedule sched ON 
			stud_sched.schedule_id = sched.id
			WHERE stud_sched.enrollment_status = 'D' AND 
			stud_sched.student_id =  " . $student_id . " AND
			stud_sched.term_id = " . CURRENT_TERM_ID
			;	

	$querydrop = mysql_query($sqldrop);
	$ctrdrop = mysql_num_rows($querydrop);

	$xd = 1;
	?>

					<table class="listview">  
						<tr>
							<th colspan="7" class="col_50">Dropped Subject</th>
						</tr>     
						<tr>
							<th class="col_50">Section</th>
							<th class="col_50">Code</th>
							<th class="col_300">Subject Name</th>
							<th class="col_50">Units</th>
							<th class="col_150">Schedule</th>
						</tr>
	<?php
	if ($ctrdrop > 0 ){
		while($rowd = mysql_fetch_array($querydrop)){ ?>
				
						<tr class="<?=($xd%2==0)?"":"highlight";?>">
							<td><?=getSectionNo($rowd["schedule_id"])?></td> 
							<td><?=getSubjCode($rowd["subject_id"])?></td>
							<td><?=getSubjName($rowd["subject_id"])?></td>
							<td><?=getSubjUnit($rowd["subject_id"])?></td>
							<td><?=getScheduleDays($rowd["schedule_id"])?></td>                    
						</tr>
			<?php  
			$xd++;         
		}
	}else{
	?>
						<tr>
							<td colspan="7">No records found</td>
						</tr>
	<?php
	}
	?>
					</table> 

					<label>&nbsp;</label>
					<label>&nbsp;</label>

				<div class="fieldsetContainer50">
					<table class="classic">
						<tr>
							<th colspan="4">ASSESSMENT OF FEES</th>
						</tr>
						<tr>
							<th>Fees</th>
							<th>Amount</th>
							<th>Total</th>
							<th>&nbsp;</th>
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
								</div>
							</td>
							<td>&nbsp;</td>  
						</tr>

	<?php
	$sql = "SELECT f.fee_type,s.* FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND f.id=s.fee_id  AND s.student_id = ".$student_id." AND s.term_id =" .CURRENT_TERM_ID;
	//AND s.is_removed = 'N'
	$result = mysql_query($sql);
	$sub_total = 0;

	while($row = mysql_fetch_array($result)){
		$total = $row['amount']*$row['quantity'];
	?>
						<tr>
							<td><?=getFeeName($row['fee_id'])?></td>
							<td><?=$row['amount']?></td>
							<td>
								<div align="right">
									Php <?=$total==''?'0.00':number_format($total, 2, ".", ",")?>
								</div>
							</td>
							<td>
							
							<?php
							if(ACCESS_ID==1){
								if($row['is_removed']=='N'){ 
									echo '<a href="#" onclick="removeE('.$row['id'].');"><img src="images/led-ico/cross.png" /></a>';
								}else{
									echo '<a href="#" onclick="addE('.$row['id'].');"><img src="images/led-ico/add.png" /></a>';
								}
							}
							?>
							</td>
						</tr>
		<?php           
		$sub_total += $total;
		$row['is_removed']=='N'?$sub_mis_total += $total:'';
	}
		 
	$sql2 = "SELECT * FROM tbl_student_other_fees s,tbl_school_other_fee f WHERE f.id=s.fee_id AND s.student_id=".$student_id." AND s.term_id=".CURRENT_TERM_ID;
	$query2 = mysql_query($sql2);

	// $sub_total = 0;
	while($row = mysql_fetch_array($query2)){
		//$total = $row['amount']*$row['quantity'];
	?>
						<tr>
							<td><?=$row['fee_name']?></td>
							<td><?=$row['amount']?></td>
							<td>
								<div align="right">
									Php <?=$row['amount']==''?'0.00':number_format($row['amount'], 2, ".", ",")?>
								</div>
							</td>
							<td>
							
							<?php if(ACCESS_ID==1){
								if($row['is_removed']=='N'){ 
									echo '<a href="#" onclick="removeE2('.$row['id'].');"><img src="images/led-ico/cross.png" /></a>';
								}else{
									echo '<a href="#" onclick="addE2('.$row['id'].');"><img src="images/led-ico/add.png" /></a>';
								}
							}?>
							</td>
						</tr>
		<?php           
		$sub_total += $row['amount'];
		$row['is_removed']=='N'?$sub_mis_total += $row['amount']:'';
	}
	?> 

						<tr>
							<td>&nbsp;</td>
							<td><strong>Total</strong></td>
							<td>
								<div align="right">
									Php <?=number_format($sub_total, 2, ".", ",")?>
								</div>
							</td>
							<td>&nbsp;</td>
						</tr>                              
					</table>
				</div>       

				<div class="fieldsetContainer50">
					<table class="classic_borderless">
	<?php
	$sql_reservation ="SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status ='R' AND student_id =" .$student_id ." AND term_id = " . CURRENT_TERM_ID;
	$qry_reservation = mysql_query($sql_reservation);
	$row_reservation = mysql_fetch_array($qry_reservation);
	$ctr_reservation = mysql_num_rows($qry_reservation);
				   
	if($ctr_reservation > 0){	
	?>
						<tr>
							<td><strong>Reservation Date:</strong></td>
							<td><div align="right"><?=date('F d, Y', $row_reservation['date_reserved'])?></div></td>
						</tr>
						<tr>
							<td><strong>Last Day of Payment:</strong></td>
							<td><div align="right"><?=date('F d, Y', $row_reservation['expiration_date'])?></div></td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>            
	<?php
	}
	?>

	<?php
	/* TOTAL AMOUNT */
	$sql = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id=" .CURRENT_TERM_ID;
	$result = mysql_query($sql);
	$sub_total = 0;
	while($row = mysql_fetch_array($result)){
		$total = getStudentAmountFeeByFeeId($row['id'],$student_id);           
		$sub_total += $total;
	}

	/* TOTAL OTHER PAYMENT 
	$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id=" .CURRENT_TERM_ID;
	$result_fee_other = mysql_query($sql_fee_other);
	$row_fee_other = mysql_fetch_array($result_fee_other);
	$sub_mis_total = 0;
	$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$student_id);           
	$sub_mis_total += $mis_total;*/
	 
	/*TOTAL LEC AND LAB = LEC + LAB*/
	$sub_total =  $sub_lec_total + $sub_lab_total + $sub_mis_total;

	/*TOTAL UNITS = TUITION FEE - TOTAL OTHER PAYMENT*/
	$total_lec_lab = $sub_total - $sub_mis_total;
					
	//TOTAL PAYMENT
	$total_payment = getTotalPaymentOfStudent($student_id,CURRENT_TERM_ID); 
					
	//DISCOUNT
	$sql_payment = "SELECT * FROM tbl_student_payment WHERE student_id =" .$student_id ." AND term_id=" .CURRENT_TERM_ID;
	$qry_payment = mysql_query($sql_payment);
	$row_payment = mysql_fetch_array($qry_payment);
	$total_charges = $sub_total - $total;
	$total_discounted = getStudentDiscount($row_payment['discount_id'], $student_id, $total_lec_lab); 
				
	//TEMPORARY DISCOUNT FOR MBM
	if(getStudentCourseId($student_id)==13&&getStudentYearLevel($student_id)>1&&$total_discounted>13520){
		$total_discounted = $total_discounted-13520;
	}

	/* TOTAL REFUND */
	$sql = "SELECT * FROM tbl_student_payment WHERE is_refund =  'Y' AND student_id = ".$student_id." AND term_id=" .CURRENT_TERM_ID;
	$result = mysql_query($sql);
	$ref_total = 0;

	while($row = mysql_fetch_array($result)){
		$ref_total += $row['amount'];
	}

	$total_refund = getTotalRefundAmount($student_id,CURRENT_TERM_ID);
	?>
						<tr>
							<td>Total Lecture Fee Amount:</td>
							<td>
								<div align="right">
									Php <?=number_format($sub_lec_total, 2, ".", ",")?>
								</div>
							</td>
						</tr>
						<tr>
							<td>Total Laboratory Fee Amount:</td>
							<td>
								<div align="right">
									Php <?=number_format($sub_lab_total, 2, ".", ",")?>
								</div>
							</td>
						</tr>
						<tr>
							<td>Total Miscelleneous Fee Amount:</td>
							<td>
								<div align="right">
									Php <?=number_format($sub_mis_total, 2, ".", ",")?>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="bottom"></td>
						</tr>
						<tr>
							<td><strong>Total Tuition Fee Amount:</strong></td>
							<td>
								<div align="right"><strong>
									Php <?=number_format($sub_total, 2, ".", ",")?></strong>
								</div>
							</td>
						</tr>

	<?php
	/*
	if($row_payment['discount_id'] != '0'){
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
	}else{*/
		$surcharge = GetSchemeForSurcharge2($student_id)*$enroll_units;
		$sqldis = 'SELECT * FROM tbl_student WHERE id='.$student_id;
		$querydis = mysql_query($sqldis);
		$rowdis = mysql_fetch_array($querydis);
				
		$sqlt = 'SELECT * FROM tbl_school_year_term WHERE id='.$_REQUEST['trm'];
		$queryt = mysql_query($sqlt);
		$rowt = mysql_fetch_array($queryt);
				
		$t = strtolower($rowt['school_term'])!='summer'?5000:0;
				
		if($rowdis['scholarship_type']=='A'){
			getStudentYearLevel($_REQUEST['id'])==4?$t=0:'';
			$discount = ($sub_total+$surcharge)-$t;
			$discount = ($discount*$rowdis['scholarship'])/100;
			
			/*TEMPORARY DISCOUNT FOR MBM
			if(getStudentCourseId($student_id)==13&&getStudentYearLevel($student_id)>1&&$discount>6920){
				$discount = $discount-6920;	
			}*/
		}else{
			$discount = $sub_lec_total+$surcharge;
			$discount = ($discount*$rowdis['scholarship'])/100;
				
			/*TEMPORARY DISCOUNT FOR MBM
			if(getStudentCourseId($student_id)==13&&getStudentYearLevel($student_id)>1&&$discount>6920){
				$discount = $discount-6920;	
			}*/
		}
				
	?>
						<tr>
							<td>Discount:</td>
							<td>
								<div align="right">
									Php <?=number_format($discount, 2, ".", ",")?>
								</div>
							</td>
						</tr>
						<tr>
							<td>Surcharge:</td>
							<td>
								<div align="right">
									Php <?=number_format($surcharge, 2, ".", ",")?>
								</div>
							</td>
						</tr>
	<?php
	// }

	$credit = getCarriedBalances($student_id,CURRENT_TERM_ID);
	$debit = getCarriedDebits($student_id,CURRENT_TERM_ID);
	$sub_total = $sub_total-$total_discounted;
	$sub_total = abs($sub_total - $debit);
	$sub_total = $sub_total + $credit;
	$total_fee = $sub_total;
	$sub_total = $sub_total - $discount;
	$sub_total = $sub_total + $surcharge;
	?>

<!--
						<tr>
							<td>Carried Balances:</td>
							<td>
								<div align="right">
									Php <?//=number_format($credit, 2, ".", ",")?>
								</div>
							</td>
						</tr>
						<tr>
							<td>Carried Debit Balances:</td>
							<td>
								<div align="right">
									Php <?//=number_format($debit, 2, ".", ",")?>
								</div>
							</td>
						</tr> 
						<tr>
							<td colspan="2" class="bottom"></td>
						</tr>
!-->
						<tr>
							<td><strong>SubTotal:</strong></td>
							<td>
								<div align="right"><strong>
									Php <?=number_format($sub_total, 2, ".", ",")?></strong>
								</div>
							</td>
						</tr>

<!--
						<tr>
							<td>Refunded Amount:</td>
							<td>
								<div align="right">
									Php <?//=number_format($ref_total, 2, ".", ",")?>
								</div>
							</td>
						</tr>
                        <tr>
							<td>Refund Balance:</td>
							<td>
								<div align="right">
									Php <?//=number_format($total_refund, 2, ".", ",")?>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2" class="bottom"></td>
						</tr>
						<tr>
							<td><strong>Total Refunded:</strong></td>
							<td>
								<div align="right"><strong>
									Php <?//=number_format(abs($total_refund-$ref_total), 2, ".", ",")?></strong>
								</div>
							</td>
						</tr>
!-->

						<tr>
							<td><strong>Total Payment:</strong></td>
							<td>
								<div align="right"><strong>
									Php <?=number_format($total_payment, 2, ".", ",")?></strong>
								</div>
							</td>
							<input type="hidden" name="sub_total" id="sub_total" value="<?=$row_total_payment['amount']?>" />
						</tr>
	<?php
	//$sub_total = ($sub_total - $total_discounted)-($total_payment);
	/*
	if(checkIfStudentPaidFull($student_id)){
		$total_rem_bal = $sub_total-$total_payment;
	}else
		*/

	if(checkIfStudentDropAllSubjects($student_id)&&$total_payment > $sub_total){
		$total_rem_bal = 0;
	}else if(!checkIfStudentDropAllSubjects($student_id)&&$total_payment > $sub_total){
		$total_rem_bal = 0;
	}else{
		$total_rem_bal = ($sub_total)-$total_payment;
	}
	?>
						<tr>
							<td colspan="2" class="bottom"></td>
						</tr>
						<tr>
							<td><strong>Total Remaining Balance:</strong></td>
							<td>
								<div align="right"><strong>
									Php <?=number_format($total_rem_bal, 2, ".", ",")?></strong>
								</div>
							</td>
						</tr>                                
					</table>
      
					<table class="classic_borderless">
						<tr>
							<td><strong>Schedule of Fees</strong></td>
						</tr>
	<?php	
	$sqlsch = "SELECT * FROM tbl_payment_scheme_details
				WHERE scheme_id = ".GetStudentScheme($student_id)." ORDER BY sort_order";
	$resultsch = mysql_query($sqlsch);
		
	while($rowsch = mysql_fetch_array($resultsch)){
		if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'A'){
			$topay = $rowsch['payment_value'];
			$sub_total = $sub_total - $topay;
			$initial = $rowsch['id'];
		}else if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'P'){
			$topay = abs(getStudentPaymentScheme($rowsch['id'],$sub_total));;
			//$total_fee = $sub_total - $down;
			$initial = $rowsch['id'];
		}else if($rowsch['payment_type'] == 'P'){
			$topay = abs(getStudentPaymentScheme($rowsch['id'],$sub_total));
		}
		?>
						<tr>
							<td><?=$rowsch['payment_name'].' on/before('.$rowsch['payment_date'].')'?></td>
							<td>
								Php<?=number_format($topay, 2, ".", ",")?>
								<input type="hidden" name="initial2" id="initial2" value="<?=$initial?>" />
							</td>
						</tr>
		<?php
		//	$order++;//}

	}
	?>
					</table>
				</div>
			</div> 
		</div> 
	</div> 

<?php
}else{ // If not yet paid
?>

	<script type="text/javascript">

    $(function(){
		// Dialog			
        $('#dialog').dialog({
            autoOpen: false,
            width: 820,
            height: 500,
            bgiframe: true,
            modal: true,
            buttons: {
                "Close": function() { 
                    $(this).dialog("close"); 
                } 
            }
        });

        // Dialog Link
        $('.curSub').click(function(){
            var id = $(this).attr("returnId");
			var param = $(this).attr("returnComp");
			var subjectType = $(this).attr("returnSubjectType");
			var subjectUnit = $(this).attr("returnSubjectUnit");
			var order = $(this).attr("returnOrd");
            var cid = document.getElementById("cid").value;
			
            //alert(param);
            $('#dialog').load('lookup/lookup_com_st_select_schedule.php?id='+id+'&cid='+cid+'&subjectType='+subjectType+'&subjectUnit='+subjectUnit+'&ord='+order+'&comp='+param, null);
            $('#dialog').dialog('open');
            return false;
        });

        $('.delete').click(function(){
			var ObjId = $(this).attr("returnId");
			var subject_unit = $(this).attr("returnSubjectUnit");
			var total_select_units = $('#enrolled_unit').val();
			var total_max_units = $('#max_unit').val();
			if($('#schedule_id_'+ObjId).val() != ''){
				$.ajax({
					type: "POST",
					data: "mod=reverse&id=" + ObjId,
					url: "ajax_components/ajax_com_slot_updater.php",
					success: function(msg){
						if (msg != ''){
							//alert(msg);
						}
					}
				});
			
				$('#span_select_units').html(parseInt(total_select_units) - parseInt(subject_unit));
				$('#enrolled_unit').val(parseInt(total_select_units) - parseInt(subject_unit));
			}

			$('#schedule_id_'+ObjId).attr("value", '');
			//$('#elective_of_'+ObjId).val('');
			//$('#subject_id_'+ObjId).val('');
			$('#units_'+ObjId).val('');
			//$('#subject_type_'+ObjId).val('');
			$('#from_'+ObjId).html('');
			$('#to_'+ObjId).html('');
			$('#day_'+ObjId).html('');
			$('#froms_'+ObjId).val('');
			$('#tos_'+ObjId).val('');
			$('#days_'+ObjId).val('');

            return false;
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
			var w=window.open ("pdf_reports/rep108.php?id="+<?=$student_id?>+"&trm="+<?=CURRENT_TERM_ID?>+"&met=reserved2"); 
			return false;
		});

		$('#email2').click(function() {
			if(confirm("Are you sure you want to email this file to this student?")){
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
			}else{
				return false;
			}
		});

        $('#save').click(function(){
			var needle = '';
			$.each($("input[name*=schedule_id]"), function(index, value) { 
				var mixed_array = '';
				if($(this).val() != ''){
					if(needle != ''){
						needle = needle + ',';
					}
					needle = needle + $(this).val();
				} // end if of first each
			}); // first each			
			
			if($('#scheme_id').val()!=''){
				if(needle != ''){
					$.ajax({
						type: "POST",
						data: "selected=" + needle ,
						url: "ajax_components/ajax_validate_schedule.php",
						success: function(msg){
							if (msg == 'false'){
								$.ajax({
									type: "POST",
									data: "id="+<?=$student_id?>+"&selected=" + needle,
									url: "ajax_components/ajax_validate_corequisite.php",
									success: function(msg){
										if (msg == ''){
										//alert(msg);
											if(confirm('Are you sure you want to complete the reservation? This will reflect to student assessment.')){
												$('#scheme').val($('#scheme_id').val());
												$('#fees_ID').val($('#feesID').val());
												$('#action').val('save');
												//$('#enrolled').val('0');
												$("form").submit();
											}else{
												return false;
											}
										}else{
											$('#scheme').val($('#scheme_id').val());
											$('#fees_ID').val($('#feesID').val());
											$('#action').val('save');
											//$('#enrolled').val('0');
											$("form").submit();
											//alert(msg);
											/*
											var corq = msg.split(',');
											var non = '';
											for(var x=0;x<corq.length;x++){
												if (document.getElementById('tr_'+corq[x])){
													$('#tr_'+corq[x]).attr('class','highlight_error');
												}else{
													non = corq[x];
												}
											}

											if(non!=''){
												alert('Co-Requisites not available.');
											}else{
												alert('Co-Requisites not selected.');
											}
											*/
										}
									} // success function
								});	 // success				
							}else{
								alert('This time slot conflicts with your existing schedule');
							}
						} // success function
					});	 // success				
				}else{
					alert('No action was made, please select at least one(1) schedule.');
				}
			}else{
				alert('Please select payment scheme.');	
			}
		});				
    });	

	function checkConflictSched(Day,id){
		var cnt = 0;

		for(var i=0;i<Day.length;i++){
			var days = Day[i].split('-');
			var timef = days[1].split(':').join("");
			var timet = days[2].split(':').join("");
			$.each($("input[name*=ids]"), function(index, value) { 
				var obId = $(this).val();
				var arrDay = $('#days_'+obId).val().split('/');
				if(id != obId && arrDay != ''){
					for(var x=0;x<arrDay.length;x++){
						var days2 = arrDay[x].split('-');
						var timef2 = days2[1].split(':').join("");
						var timet2 = days2[2].split(':').join("");
						if((timef == timef2 && timef == timet2) || (timet == timef && timet == timet2)){
							if(days[0]==days2[0]){
								cnt++;
							}
						}else{
							if((timef >= timef2 && timef < timet2) || (timet > timef2 && timet <= timet2)){
								if(days[0]==days2[0]){
									cnt++;
								}
							}
						}
					}
				}
			});
		}

		if(cnt == 0){
			return true;
		}else{
			return false;
		}
	}

	
	function getfees(id){
		//alert(id);
		$.ajax({
			type: "POST",
			data: "mod=updateFee&id=" + id+"&stud_id="+<?=$student_id?>,
			url: "ajax_components/ajax_com_fee_field_updater.php",
			success: function(msg){
				if (msg != ''){
					$('#div_fees').html(msg);
				}
			}
		});
				
		/*
		$.ajax({
			type: "POST",
			data: "mod=updateScheme&id=" + id,
			url: "ajax_components/ajax_com_fee_field_updater.php",
			success: function(msg){
				if (msg != ''){
					$('#div_scheme').html(msg);
				}
			}
		});*/
	}
	
	function removeR(id){
		if(confirm("Are you sure you want to remove this from the list?")){
			$('#action').val('remove');
			$('#misc_id').val(id);
			updateList();
			$("form").submit();
		}
	}
	
	function addR(id){
		if(confirm("Are you sure you want to add this to the list?")){
			$('#action').val('add');
			$('#misc_id').val(id);
			updateList();
			$("form").submit();
		}
	}
	
    </script>

	<?php
	$arr_sql = array();
	$arr_subject = getStudentSubjectForEnrollmentInArr_2_1($student_id);
	if (count($arr_subject) > 0 ){
		$x = 1;
		?>

	<table class="listview" id="tbl_enrol_subj">     
		<tr>
			<td colspan="5">
				<a class="viewer_email" href="#" id="email2" title="email2"></a>
				<a class="viewer_pdf" href="#" id="pdf2" title="pdf2"></a>
				<!--<a class="viewer_print" href="#" id="print2" title="print2"></a>!-->
			</td>
        </tr>  
		<tr>	
			<th class="col_50">Code</th>
			<th class="col_300">Subject Name</th>
			<th class="col_50">Units</th>
			<th class="col_100">Schedule</th>
			<th class="col_50">Action</th>
		</tr>

		<?php
		foreach($arr_subject as $subject_id){
			$sql = "SELECT 
						cur.curriculum_id, 
						cur.subject_id, 
						cur.year_level, 
						cur.subject_category, 
						cur.term, 
						cur.units, 
						sub.id, 
						sub.subject_code, 
						sub.subject_name
					FROM 
						tbl_curriculum_subject cur LEFT JOIN 
						tbl_subject sub ON cur.subject_id = sub.id
					WHERE 
						cur.subject_id = " .$subject_id ." AND 
						cur.subject_category <>'EO'";
			$result = mysql_query($sql);   
			$row = @mysql_fetch_array($result); 
			$ctr_subject = @mysql_num_rows($result);

			if($ctr_subject > 0){	   
				if($row['subject_category'] == 'R'){ // REGULAR SUBJECT
					$sql_sched = "SELECT * FROM tbl_student_reserve_subject 
								WHERE 
									student_id = ". $student_id ." AND 
									subject_id = " . $subject_id . " AND 
									term_id=" .CURRENT_TERM_ID;
					$query_sched = mysql_query($sql_sched);
					$row_sched = mysql_fetch_array($query_sched);
				?>
		<tr id="tr_<?=$row['id']?>" class="<?=($x%2==0)?"":"highlight";?>">
			<td id="code_<?=$row['id']?>"><?=$row["subject_code"]?></td> 
			<td id="name_<?=$row['id']?>"><?=$row["subject_name"]?></td>
			<td id="units_<?=$row['id']?>"><?=$row["units"]?></td>
			<td id="day_<?=$row['id']?>"><?=getScheduleDays($row_sched['schedule_id'])?></td>
			<td class="action">
				<input type="hidden" name="schedule_id[]" id="schedule_id_<?=$row['id']?>" value="<?=$row_sched['schedule_id']?>" >  
				<input type="hidden" name="days_<?=$row['id']?>" id="days_<?=$row['id']?>" value="<?=getSepScheduleDays($row_sched['schedule_id'])?>"  > 
				<input type="hidden" name="ids[]" id="ids_<?=$row['id']?>" value="<?=$row['id']?>" >
				<input type="hidden" name="order_<?=$row['id']?>" id="order_<?=$row['id']?>" value="<?=$x?>" >
				<input type="hidden" name="units[]" id="units_<?=$row['id']?>" value="<?=$row["units"]?>" >                		<input type="hidden" name="subject_id[]" id="subject_id_<?=$row['id']?>" value="<?=$row["subject_id"]?>" >
				<input type="hidden" name="subject_type[]" id="subject_type_<?=$row['id']?>" value="<?=$row['subject_category']?>" >                
				<input type="hidden" name="elective_of[]" id="elective_of_<?=$row['id']?>" value="<?=$row["elective_of"]?>" >                      
				<ul>
					<li><a class="curSub" href="#" title="Select Schedule" returnId="<?=$row['subject_id']?>" returnSubjectType="<?=$row['subject_category']?>" returnSubjectUnit="<?=$row['units']?>" returnOrd="<?=$x?>" returnComp="<?=$_REQUEST['comp']?>"></a></li>
					<li><a class="delete" href="#" title="Remove Schedule" returnId="<?=$row['id']?>" returnSubjectType="<?=$row['subject_category']?>" returnSubjectUnit="<?=$row['units']?>"></a></li>
				</ul>
			</td>
		</tr>

				<?php  
				}else{ // ELECTIVE SUBJECT 
					$sql_sched = "SELECT * FROM tbl_student_reserve_subject st, tbl_schedule sc 
								WHERE
								sc.id = st.schedule_id AND 
								st.student_id = ". $student_id ." AND 
								st.subject_id = " . $subject_id . " AND 
								st.term_id=" .CURRENT_TERM_ID;
					$query_sched = mysql_query($sql_sched);
					$row_sched = mysql_fetch_array($query_sched);
					$row_sched_ctr = mysql_num_rows($query_sched);
							
					if($row_sched_ctr > 0){
						$subject_id = $row_sched['subject_id'];
						$code	= getSubjCode($row_sched['subject_id']);
						$name	= getSubjName($row_sched['subject_id']);
						$units	= $row_sched["units"];
						$schedule_id = $row_sched['schedule_id'];
					}else{
						$subject_id = $row["id"];
						$code	= getSubjCode($row['id']);
						$name	= getSubjName($row['id']);
						$units	= $row["units"];
						$schedule_id = '';						
					}
					?>
		<tr class="<?=($x%2==0)?"":"highlight";?>">
			<td id="code_<?=$subject_id?>"><?=$code?></td> 
			<td id="name_<?=$subject_id?>"><?=$name?></td>
			<td id="units_<?=$subject_id?>"><?=$units?></td>
			<td id="day_<?=$subject_id?>"><?=getScheduleDays($row_sched['schedule_id']) ?></td>
			<td class="action">
				<input type="hidden" name="schedule_id[]" id="schedule_id_<?=$subject_id?>" value="<?=$schedule_id?>" >
				<input type="hidden" name="days_<?=$row['id']?>" id="days_<?=$row['id']?>" value="<?=getSepScheduleDays($row_sched['schedule_id'])?>" > 
				<input type="hidden" name="ids[]" id="ids_<?=$row['id']?>" value="<?=$row['id']?>" >   
				<input type="hidden" name="units[]" id="units_<?=$subject_id?>" value="<?=$row["units"]?>" >                		<input type="hidden" name="subject_id[]" id="subject_id_<?=$subject_id?>" value="<?=$subject_id?>" >
				<input type="hidden" name="subject_type[]" id="subject_type_<?=$subject_id?>" value="<?=$row['subject_category']?>" >                
				<input type="hidden" name="elective_of[]" id="elective_of_<?=$subject_id?>" value="<?=$row_sched["elective_of"]?>" >                    
				<ul>
					<li><a class="curSub" href="#" title="Select Schedule" returnId="<?=$row['subject_id']?>" returnSubjectType="<?=$row['subject_category']?>" returnSubjectUnit="<?=$row['units']?>" returnOrd="<?=$x?>" returnComp="<?=$_REQUEST['comp']?>"></a></li>
					<li><a class="delete" href="#" title="Remove Schedule" returnId="<?=$subject_id?>" returnSubjectType="<?=$row['subject_category']?>" returnSubjectUnit="<?=$row['units']?>"></a></li>
				</ul>
			</td>
		</tr>
				<?php  					
				} // end of if for subject category
			
				$x++;  
			}       
		}
	}else{
		echo "No records found";
	}
	?> 
        
<!--
		<tr>
			<td colspan="5">Select Fee Format
				<select name="feesID" class="txt_200" id="feesID" onchange="getfees(this.value);">
				<option value="">Select</option>
				<?//=generateFees($fees_ID)?>
				</select>       
			</td>
		</tr>
        <tr>
			<td colspan="5">
				<div id="div_scheme">Select Payment Scheme
					<select name="scheme_id" class="txt_150" id="scheme_id">
					<option value="">Select</option>
					<?//=generateScheme($scheme)?>
					</select>      
				</div>
			</td>
		</tr>
-->
	</table>

	<div class="fieldsetContainer50">            
		<label>Total Selected Units: <span id="span_select_units"><?=getStudentReservedUnit($student_id)?></span></label>
		<label>Total allowed Units: <span><?=getStudentMaxEnrolledUnit($student_id)?></span></label>
		<label>&nbsp;</label>
	</div>

	<div class="btn_container">
		<p class="button_container">
			<input type="hidden" name="max_unit" id="max_unit" value="<?=getStudentMaxEnrolledUnit($student_id)?>" >
			<input type="hidden" name="enrolled_unit" id="enrolled_unit" value="<?=getStudentReservedUnit($student_id)?>" >
			<input type="hidden" name="cid" id="cid" value="<?=getStudentCurriculumID($student_id)?>" />
			<input type="hidden" name="enrolled" id="enrolled" value="<?=$enrolled?>" >
			<input type="hidden" name="term" id="term" value="<?=CURRENT_TERM_ID?>" />
			<a href="#" class="button" title="Save" id="save"><span>Update Reserve</span></a>
		</p>
	</div>
	
	<label>&nbsp;</label>
    <div class="fieldsetContainer50" id="div_fees">
    
	<?php
	if(checkIfStudentIsReserve($student_id)){
		?>
    
		<table class="classic">
			<tr>
				<th>Fees</th>
				<!--<th>Amount</th>
				<th>Total</th>!-->
				<th>&nbsp;</th>
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
				<!-- <td><?//=$row_lec['amount']?></td>
				<td>
					<div align="right">
						Php <?//=$sub_lec_total==''?'0.00':number_format($sub_lec_total, 2, ".", ",")?>
					</div></td>!-->
				<td>&nbsp;
				</td>
			</tr>

		<?php
        $sql = "SELECT f.fee_type,s.* FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND f.id=s.fee_id  AND s.student_id = ".$student_id." AND s.term_id =" .CURRENT_TERM_ID;
		//AND s.is_removed = 'N'
        $result = mysql_query($sql);
		$sub_total = 0;
		
		while($row = mysql_fetch_array($result)){
			$total = $row['amount']*$row['quantity'];
			?>

			<tr>
				<td><?=getFeeName($row['fee_id'])?></td>
				<!--<td><?//=$row['amount']?></td>
				<td>
					<div align="right">
						Php <?//=$total==''?'0.00':number_format($total, 2, ".", ",")?>
					</div></td>!-->
				<td>
			<?php
			if($row['is_removed']=='N'){ 
				echo '<a href="#" onclick="removeR('.$row['id'].');"><img src="images/led-ico/cross.png" /></a>';
			}else{
				echo '<a href="#" onclick="addR('.$row['id'].');"><img src="images/led-ico/add.png" /></a>';
			}
			?>
				</td>
				<!--<td><a href="#"><img src="../images/led-ico/add.png" /></a>
				<a href="#"><img src="../images/led-ico/cross.png" /></a></td>!-->
			</tr>

			<?php           
			$sub_total += $total;
			$row['is_removed']=='N'?$sub_mis_total += $total:'';
		}		
		   
		$sql2 = "SELECT * FROM tbl_student_other_fees s,tbl_school_other_fee f WHERE f.id=s.fee_id AND s.student_id=".$student_id." AND s.term_id=".CURRENT_TERM_ID;
		$query2 = mysql_query($sql2);
		// $sub_total = 0;

		while($row = mysql_fetch_array($query2)){
			//$total = $row['amount']*$row['quantity'];
			?>

			<tr>
				<td><?=$row['fee_name']?></td>
				<!-- <td><?//=$row['amount']?></td>
				<td>
					<div align="right">
						Php <?//=$row['amount']==''?'0.00':number_format($row['amount'], 2, ".", ",")?>
					</div>
				</td>!-->
				<td>
			
			<?php
			if(ACCESS_ID==1||ACCESS_ID==13){
				if($row['is_removed']=='N'){ 
					echo '<a href="#" onclick="removeE2('.$row['id'].');"><img src="images/led-ico/cross.png" /></a>';
				}else{
					echo '<a href="#" onclick="addE2('.$row['id'].');"><img src="images/led-ico/add.png" /></a>';
				}
			}//
			?>
                
				</td>
			</tr>

        <?php           
			$sub_total += $row['amount'];
			$row['is_removed']=='N'?$sub_mis_total += $row['amount']:'';
		}

        /*
		TOTAL OTHER PAYMENT 
		$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id=" .CURRENT_TERM_ID;;
		$result_fee_other = mysql_query($sql_fee_other);
		$row_fee_other = mysql_fetch_array($result_fee_other);
		$sub_mis_total = 0;
		$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$student_id);           
		$sub_mis_total += $mis_total;
		*/
		$sub_total = $sub_total+$sub_lec_total;
        ?>  

			<!--
			<tr>
				<td>&nbsp;</td>
				<td><strong>Total</strong></td>
				<td>
					<div align="right">
						Php <?//=number_format($sub_total, 2, ".", ",")?>
					</div>
				</td>
				<td>&nbsp;</td>
			</tr>    !-->                          
		</table>

	<?php
	}else{
	?>

		<table class="classic">
			<tr>
				<th>Fees</th>
				<!--<th>Amount</th>
                <th>Total</th>
				<th>&nbsp;</th>!-->
			</tr> 
		<?php
		/* TOTAL LEC PAYMENT */
		$sql_lec = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlec' AND publish =  'Y' AND term_id=" .CURRENT_TERM_ID;;
		$qry_lec = mysql_query($sql_lec);
		$row_lec = mysql_fetch_array($qry_lec);
		$sub_lec_total = 0;
		$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$student_id);           
		$sub_lec_total += $lec_total;
		
		/* TOTAL LAB PAYMENT */
		$sql_lab = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlab' AND publish =  'Y' AND term_id=" .CURRENT_TERM_ID;;
		$qry_lab = mysql_query($sql_lab);
		$row_lab = mysql_fetch_array($qry_lab);
		$sub_lab_total = 0;
		$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$student_id);           
		$sub_lab_total += $lab_total;
		
		$sql = "SELECT * FROM tbl_school_fee
				WHERE publish =  'Y'
				AND term_id=" .CURRENT_TERM_ID;
		$result = mysql_query($sql);

		$sub_total = 0;
	
		while($row = mysql_fetch_array($result)){
			$total = getStudentTotalFeeLecLab($row['id'],$student_id );
			?>

            <tr>
				<td><?=$row['fee_name']?></td>
				<!-- <td><?//=getFeeAmount($row['id'])?></td>
				<td>
					<div align="right">
						Php <?//=$total==''?'0.00':number_format($total, 2, ".", ",")?>
					</div>
				</td>!-->
                <!--<td><a href="#"><img src="../images/led-ico/add.png" /></a>
					<a href="#"><img src="../images/led-ico/cross.png" /></a></td>!-->
			</tr>

        <?php           
			$sub_total += $total;
		}

		/* TOTAL OTHER PAYMENT */
		//
		$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id=" .CURRENT_TERM_ID;
		$result_fee_other = mysql_query($sql_fee_other);
		$row_fee_other = mysql_fetch_array($result_fee_other);
		$sub_mis_total = 0;
		$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$student_id);           
		$sub_mis_total += $mis_total;   
        ?>  

			<!--
			<tr>
				<td>&nbsp;</td>
				<td><strong>Total</strong></td>
				<td>
					<div align="right">
						Php <?//=number_format($sub_total, 2, ".", ",")?>
					</div>
				</td>
			</tr>
			!-->
		</table>

	<?php
	}
	?>
	</div>       

	<!--
	<div class="fieldsetContainer50">
		<table class="classic_borderless">

	<?php
	$sub_total = $sub_mis_total+$sub_lab_total+$sub_lec_total;
	$sql_reservation ="SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status ='R' AND student_id =" .$student_id ." AND term_id=" .CURRENT_TERM_ID;
	$qry_reservation = mysql_query($sql_reservation);
	$row_reservation = mysql_fetch_array($qry_reservation);
	$ctr_reservation = mysql_num_rows($qry_reservation);

	if($ctr_reservation > 0){	
		?>
			<tr>
				<td><strong>Reservation Date:</strong></td>
				<td><?//=date('F d, Y', $row_reservation['date_reserved'])?></td>
			</tr>
			<tr>
				<td><strong>Last Day of Payment:</strong></td>
				<td><?//=date('F d, Y', $row_reservation['expiration_date'])?></td>
			</tr>
	<?php
	}
	?>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>Total Lecture Fee Amount:</td>
				<td>
					<div align="right">
						Php <?//=number_format($sub_lec_total, 2, ".", ",")?>
					</div>
				</td>
			</tr>
			<tr>
				<td>Total Laboratory Fee Amount:</td>
				<td>
					<div align="right">
						Php <?//=number_format($sub_lab_total, 2, ".", ",")?>
					</div>
				</td>
			</tr>
			<tr>
				<td>Total Miscelleneous Fee Amount:</td>
				<td>
					<div align="right">
						Php <?//=number_format($sub_mis_total, 2, ".", ",")?>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="bottom"></td>
			</tr>
			<tr>
				<td><strong>Total Tuition Fee Amount:</strong></td>
				<td>
					<div align="right"><strong>
						Php <?//=number_format($sub_total, 2, ".", ",")?></strong>
					</div>
				</td>
			</tr>
	<?php
	$surcharge = GetSchemeForSurcharge($student_id)*getStudentReservedUnit($student_id);
	$sqldis = 'SELECT * FROM tbl_student WHERE id='.$student_id;
	$querydis = mysql_query($sqldis);
	$rowdis = mysql_fetch_array($querydis);
			
	if($rowdis['scholarship_type']=='A'){
		$discount = ($sub_total+$surcharge)-5000;
		$discount = ($discount*$rowdis['scholarship'])/100;
		//TEMPORARY DISCOUNT FOR MBM
		if($rows['course_id']==13&&getStudentNextYearLevel($student_id)>1&&$discount>13520){
			$discount = $discount-13520;
		}
	}else{
		$discount = $sub_lec_total+$surcharge;
		$discount = ($discount*$rowdis['scholarship'])/100;
		
		//TEMPORARY DISCOUNT FOR MBM
		if($rows['course_id']==13&&getStudentNextYearLevel($student_id)>1&&$discount>13520){
			$discount = $discount-13520;
		}
	}
	?>
			<tr>
				<td>Discount:</td>
				<td>
					<div align="right">
						Php <?//=number_format($discount, 2, ".", ",")?>
					</div>
				</td>
			</tr>
            <tr>
				<td>Surcharge:</td>
				<td>
					<div align="right">
						Php <?//=number_format($surcharge, 2, ".", ",")?>
					</div>
				</td>
			</tr>

	<?php
	$credit = getCarriedBalances($student_id,CURRENT_TERM_ID);
	$debit = getCarriedDebits($student_id,CURRENT_TERM_ID);
	$sub_total = abs($sub_total - $debit);
	$sub_total = $sub_total + $credit;
	$sub_total = $sub_total - $discount;
	$sub_total = $sub_total + $surcharge;
	?>
            <tr>
                <td>Carried Balances:</td>
                <td>
					<div align="right">
						Php <?//=number_format($credit, 2, ".", ",")?>
					</div>
				</td>
            </tr>
            <tr>
                <td>Carried Debit Balances:</td>
                <td>
					<div align="right">
						Php <?//=number_format($debit, 2, ".", ",")?>
					</div>
				</td>
            </tr>         
            <tr>
				<td colspan="2" class="bottom"></td>
            </tr>
            <tr>
                <td><strong>Total Charges:</strong></td>
                <td>
					<div align="right"><strong>
						Php <?//=number_format($sub_total, 2, ".", ",")?></strong>
					</div>
				</td>
            </tr>                                
		</table>
	<?php
	if(GetStudentScheme($student_id)!=''){
		?>
		<table class="classic_borderless">
			<tr>
				<td><strong>Schedule of Fees</strong></td>
			</tr>

		<?php	
		$sqlsch = "SELECT * FROM tbl_payment_scheme_details
					WHERE scheme_id = ".GetStudentScheme($student_id)." ORDER BY sort_order";
		$resultsch = mysql_query($sqlsch);
		while($rowsch = mysql_fetch_array($resultsch)){
			if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'A'){
				$topay = $rowsch['payment_value'];
				$sub_total = $sub_total - $topay;
				$initial = $rowsch['id'];
			}else if($rowsch['sort_order'] == 1 && $rowsch['payment_type'] == 'P'){
				$topay = abs(getStudentPaymentScheme($rowsch['id'],$sub_total));;
				//$total_fee = $sub_total - $down;
				$initial = $rowsch['id'];
			}else if($rowsch['payment_type'] == 'P'){
				$topay = abs(getStudentPaymentScheme($rowsch['id'],$sub_total));
			}
			?>
			<tr>
				<td><?=$rowsch['payment_name'].' on/before('.$rowsch['payment_date'].')'?></td>
				<td>Php <?=number_format($topay, 2, ".", ",")?>
					<input type="hidden" name="initial" id="initial" value="<?=$initial?>" /></td>
			</tr>

             <?php
			//	$order++;//}
		}
		?>                                
		</table>
	<?php
	}
	?>

</div>!-->

	<div id="dialog" title="Subject Schedule">

    Loading...

		<input type="hidden" name="downpay" id="downpay" value="<?=$down?>" />
	</div><!-- #dialog -->

<?php
} // main if else
?>

<p id="formbottom"></p>        





