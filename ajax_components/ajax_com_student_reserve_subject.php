<?php
include_once("../config.php");
include_once("../includes/functions.php");	
include_once("../includes/common.php");	
	
if(USER_IS_LOGGED != '1'){
	header('Location: ../index.php');
}else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])){
	header('Location: ../forbid.html');
}
	
$student_id = $_REQUEST['stud_id'];
?>

<script type="text/javascript">
$(function(){

	$('#print').click(function(){
			var w=window.open();
			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
			w.document.write($('#print_div').html());
			w.document.close();
			w.focus();
			w.print();
			//w.close()
			return false;
		});
	
	$('#pdf').click(function(){
			var w=window.open ("pdf_reports/rep108.php?id="+<?=$student_id?>+"&trm="+<?=CURRENT_TERM_ID?>+"&met=reserved2"); 
			return false;
		});
		
	$('#email').click(function(){
		
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
		
	$("#revise").click(function(){
		
		clearTabs();
		
		$('#enroll').addClass('active');
		$('#pageRows').hide();
		
		$('#view').val('enroll');
		$('#action').val('enroll');	
		$('#student_id').val(<?=$student_id?>);	
		$('#box_container').html(loading);	
		$('#box_container').load('ajax_components/ajax_com_enroll_student_sched.php?student_id=<?=$student_id?>&comp=<?=$_REQUEST['comp']?>', null);
		
		return false;
	});  	
});

</script>

<?php
$arr_sql = array();
$sqlcondition = '';
$sqlOrderBy = '';
	
if($student_id!=''){
		//setStudentSubjectForEnrollmentInArr($student_id,CURRENT_TERM_ID);
}
	
if(isset($_REQUEST['list_rows'])){
	$page_rows = $_REQUEST['list_rows']; 	
}else{
	$page_rows = 10;
}

			
if(isset($_REQUEST["search_field"]) and ($_REQUEST["search_field"] != "") and ($_REQUEST["search_key"] != "") and ($_REQUEST["search_key"] != "")){
	$search_field = $_REQUEST["search_field"];
	$search_key = $_REQUEST["search_key"]; 
	$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
}			

if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' ){
	$sqlOrderBy = ' ORDER BY  '. $_REQUEST['fieldName'] .' '. $_REQUEST['orderBy'];
}
	
$sqlE = "SELECT * FROM tbl_student_enrollment_status 
		WHERE
			enrollment_status = 'E' AND
			term_id = " . CURRENT_TERM_ID . " AND
			student_id= " .$student_id;
$queryE = mysql_query($sqlE);


if(count($arr_sql) > 0){
	$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sqlimplode(' AND ', $arr_sql);
}

//Here we count the number of results 
//Edit $data to be your query 
$sql_pagination = "SELECT * FROM tbl_student_reserve_subject 
					WHERE
						term_id = " . CURRENT_TERM_ID . " AND
						student_id= " .$student_id.$sqlcondition ;
						
$query_pagination  = mysql_query ($sql_pagination);
$row_ctr = mysql_num_rows($query_pagination); 
	
// initial records
if(isset($_REQUEST['pageNum']) && $_REQUEST['pageNum'] != ''){
	$pagenum = ($_REQUEST['pageNum']*$page_rows) - $page_rows; 
}else{
	$pagenum = 0; 
}

if($row_ctr>0){
	//This tells us the page number of our last page 
	$last = ceil($row_ctr/$page_rows); 			
	$max = 'limit ' .$pagenum .',' .$page_rows; 	
	$sql = "SELECT * FROM tbl_student_reserve_subject 
			WHERE
				term_id = " . CURRENT_TERM_ID . " AND
				student_id= " .$student_id
			.$sqlcondition  . $sqlOrderBy . " $max" ;
	
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result)
?>                  
        
	<?php  
	if (mysql_num_rows($result) > 0 ){
		$x = 0;
		$sql = "SELECT * FROM tbl_student WHERE id = ".$student_id;						
		$query = mysql_query($sql);
		$rows = mysql_fetch_array($query);
		?>
		
<div id="print_div">
	<div id="printable">
		<div class="body-container">
			<div class="header">
				<div class="headerForm">         
					<table class="classic_borderless">
						<tr>
							<td valign="top" style='font-weight:bold'>Student Number:</td>
							<td><?=$rows['student_number']?></td>
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
		$sqle = "SELECT * FROM tbl_student_enrollment_status 
				WHERE
					term_id = " . CURRENT_TERM_ID . " AND
					student_id= " .$student_id;
		$querye = mysql_query($sqle);
		$rowe = mysql_fetch_array($querye);

		if(checkSlotAvailableInBlock2($rowe['block_id'],$student_id)){
		?>

						<tr>
							<td style='font-weight:bold; color:red;' valign="top">Enrollment Remarks:</td>
							<td style='font-weight:bold; color:red;' colspan="3">Some of these Schedules exceeded the maximum slot.</td>
						</tr>
		<?php
		}
		?>     
					</table>  
				</div> 
			</div>

			<div class="content-container">
				<div class="content-wrapper-wholeBorder">
					<table class="listview_classic">     
						<tr>
							<td colspan="7">
								<a class="viewer_email" href="#" id="email" title="email"></a>
								<a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
								<a class="viewer_print" href="#" id="print" title="print"></a>
							</td>
						</tr>
						<tr>
							<th class="col_50">Section</th>
							<th class="col_50">Code</th>
							<th class="col_300">Subject Name</th>
							<th class="col_50">Units</th>
							<!-- <th class="col_100">Schedule</th>
							<th class="col_50">Room</th>!-->
						</tr>
		<?php
        $sql = "SELECT * FROM tbl_student_reserve_subject 
				WHERE
					term_id = " . CURRENT_TERM_ID . " AND
                    student_id= " .$student_id;
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)){
			/*if(!checkSlotAvailableBySched($row['schedule_id'],$row["subject_id"],$row['term_id'])){
				$st = 'style="font-weight:bold; color:red;"';
			}else{*/
				$st='';	
			//}
			?>
						<tr <?=$st?> class="<?=($x%2==0)?"":"highlight";?>">
							<td><?=getSectionNo($row["schedule_id"])?></td>
							<td><?=getSubjCode($row["subject_id"])?></td> 
							<td><?=getSubjName($row["subject_id"])?><?=$row["elective_of"]!=''?' ('.getSubjName($row["elective_of"]).')':''?></td>
							<td><?=$row["units"]?></td>
							<!-- <td><?=getScheduleDays($row['schedule_id'])?></td>
							<td><?=getRoomNo(getSchedRoom($row["schedule_id"]))?></td>!-->
						</tr>
			<?php  
			$x++;         
		}
		?>
						<tr>
							<td colspan="2">&nbsp;</td> 
							<td><?=getStudentReservedUnit($student_id)?></td>
							<td colspan="4">&nbsp;</td>
						</tr>        
	<?php
	}else{
		echo "No records found";
	}
    ?>
					</table>
					
				<div class="fieldsetContainer50">        
					<label>Total Reserved Units: <span id="span_select_units"><?=getStudentReservedUnit($student_id)?></span></label>      
					<label>Total allowed Units: <?=getStudentMaxEnrolledUnit($student_id)?></label>
				</div>
				<label>&nbsp;</label>

				
   		<!--<div class="fieldsetContainer50">!-->

		 <!-- <table class="listview">
          		
                <tr>
                    <th colspan="2">TUITION FEES AND MISCELLANEOUS</th>
                    
      <?php
	  $sqlnum = "SELECT * FROM tbl_payment_scheme WHERE term_id=".CURRENT_TERM_ID;
	  $querynum = mysql_query($sqlnum);
	  $num = mysql_num_rows($querynum);
	  $cntnum=1;
	  	//$sql1 = "SELECT * FROM tbl_payment_scheme WHERE term_id=".CURRENT_TERM_ID;
		$sql1 = "SELECT stat.scheme_id,schem.* FROM tbl_student_enrollment_status stat, tbl_payment_scheme schem WHERE stat.scheme_id = schem.id AND student_id=".$student_id;
		$query1 = mysql_query($sql1);
		
		$row1 = mysql_fetch_array($query1);
		
			//$sql2 = 'SELECT * FROM tbl_payment_scheme WHERE term_id = '.$row1['term_id'];
			$sql2 ="SELECT * FROM tbl_payment_scheme WHERE term_id=".CURRENT_TERM_ID;
			$query2 = mysql_query($sql2);
			
			while($row2 = mysql_fetch_array($query2))
		{
			if($cntnum<=$num)
			{
	  ?>
                   <th><?=$row2['name']?></th>
                
			<?php 
			}$cntnum++;
		}
		?>              
                  
                </tr> 
			<?php
				/* TOTAL LEC PAYMENT 
					$sql_lec = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlec' AND publish =  'Y' AND term_id= ".$row1['term_id'];
					$qry_lec = mysql_query($sql_lec);
					$row_lec = mysql_fetch_array($qry_lec);
					$sub_lec_total = 0;
					
					
					$lec_total = getStudentTotalFeeLecLab($row_lec['id'],USER_STUDENT_ID);           
					$sub_lec_total += $lec_total;*/
					
					$sql_lec = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlec' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$student_id." AND s.term_id =" .CURRENT_TERM_ID;
			

			$qry_lec = mysql_query($sql_lec);

			$row_lec = mysql_fetch_array($qry_lec);

			$sub_lec_total = 0;         

			$sub_lec_total = $row_lec['sum(s.quantity)']*$row_lec['amount'];
					
					/* TOTAL LAB PAYMENT 
					$sql_lab = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlab' AND publish =  'Y' AND term_id= ".$row1['term_id'];
					$qry_lab = mysql_query($sql_lab);
					$row_lab = mysql_fetch_array($qry_lab);
					$sub_lab_total = 0;
					
					
					$lab_total = getStudentTotalFeeLecLab($row_lab['id'],USER_STUDENT_ID);           
					$sub_lab_total += $lab_total;*/
					
					$sql_lab = "SELECT f.fee_type,s.*,sum(s.quantity) FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'perunitlab' AND s.is_removed = 'N' AND f.id=s.fee_id AND s.student_id = ".$student_id." AND s.term_id =" .CURRENT_TERM_ID;

			$qry_lab = mysql_query($sql_lab);

			$row_lab = mysql_fetch_array($qry_lab);

			$sub_lab_total = $row_lab['sum(s.quantity)']*$row_lab['amount'];
					
					/*TOTAL LEC AND LAB = LEC + LAB*/
					$total_lec_lab =  $sub_lec_total + $sub_lab_total;
				
				$sql2 = "SELECT * FROM tbl_payment_scheme WHERE term_id=".$row1['term_id'];
				$query2 = mysql_query($sql2);
				?>
				<tr>
                <td>Tuition Fee</td>
                <td><?=$row_lec['amount']?></td>
				<?php
				$cnt=0;
				$cntnum=1;
				while($row2 = mysql_fetch_array($query2))
				{
					if($cntnum<=$num)
					{
					$totalSur = getStudentReservedUnit($student_id)*$row2['surcharge'];
					
					$sqldis = 'SELECT * FROM tbl_student WHERE id='.$student_id;
					$querydis = mysql_query($sqldis);
					$rowdis = mysql_fetch_array($querydis);
					
					if($rowdis['scholarship']!=0)
					{
						if($rowdis['scholarship_type']=='SFA')
						{
			
							$discount = $lec_total+$totalSur;
							$discount = ($discount*$rowdis['scholarship'])/100;
						}
					}
			  ?>
						   
                    <td><?=($totalSur+$total_lec_lab)-$discount?></td>        				
						
					<?php 
					}$cntnum++;
					$cnt++;
				}
				?>
			</tr>
            <?php
               /* $sql = "SELECT *
                        FROM tbl_school_fee
                        WHERE publish =  'Y' AND fee_type='mc' AND term_id=" .CURRENT_TERM_ID;
                        
                $result = mysql_query($sql);
				$sub_total = 0;*/
				
				 $sql = "SELECT f.fee_type,s.* FROM tbl_school_fee f,tbl_student_fees s WHERE f.fee_type = 'mc' AND f.id=s.fee_id AND s.is_removed = 'N' AND s.student_id = ".$student_id." AND s.term_id =" .CURRENT_TERM_ID;

            $result = mysql_query($sql);

            $sub_total = 0;
				
                while($row = mysql_fetch_array($result)) 
                {
					$total = $row['amount']*$row['quantity'];
            ?>
                <tr>
                    <td><?=getFeeName($row['fee_id'])?></td>
                  	<td><?=$row['amount']?></td>
              <?php      
                    for($x=0;$x<$num;$x++)
				{
			  ?>
						   
                    <td><?=$row['amount']?></td>	
						
					<?php 
				}
				?>
                    
        </tr>
        <?php
					$sub_total += $total;
               }
			 //ADDITIONAL OTHER FEE  
			   $sql2 = "SELECT * FROM tbl_student_other_fees s,tbl_school_other_fee f WHERE f.id=s.fee_id AND s.is_removed = 'N' AND s.student_id=".$student_id." AND s.term_id=".CURRENT_TERM_ID;
				$query2 = mysql_query($sql2);
			
			if(mysql_num_rows($query2)>0)
			{	
			while($row2 = mysql_fetch_array($query2))
			{
				$yr = explode(',',$row2['school_year_level']);
				
				//if(in_array(getStudentNextYearLevel($id),$yr))
				//{
				?>
                <tr>
                    <td><?=$row2['fee_name']?></td>
                  	<td><?=$row2['amount']?></td>
              <?php      
                    for($x=0;$x<$cnt;$x++)
				{
			  ?>
						   
                    <td><?=$row2['amount']?></td>	
						
					<?php 
				}    
                   $sub_total +=$row2['amount'];
				//}
				?>
                    
        </tr>
        <?php
				
			}
			}
			   
			   
			    /* TOTAL OTHER PAYMENT */
			$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id=" .CURRENT_TERM_ID;;
			$result_fee_other = mysql_query($sql_fee_other);
			$row_fee_other = mysql_fetch_array($result_fee_other);
			$sub_mis_total = 0;
			$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$student_id);           
			$sub_mis_total += $mis_total;
			
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
            ?>  
                <tr>
                    <td><strong>Total</strong></td>
                  	
                    <td>&nbsp;
                      </td>
       <?php               
        $sql3 = "SELECT * FROM tbl_payment_scheme WHERE term_id=".CURRENT_TERM_ID;
				$query3 = mysql_query($sql3);
				
				$cnt=0;
				while($row3 = mysql_fetch_array($query3))
				{
					
					$totalSur = getStudentReservedUnit($student_id)*$row3['surcharge'];
					
					$sqldis = 'SELECT * FROM tbl_student WHERE id='.$student_id;
					$querydis = mysql_query($sqldis);
					$rowdis = mysql_fetch_array($querydis);
					
					if($rowdis['scholarship']!=0)
					{
						if($rowdis['scholarship_type']=='A')
						{
							$discount = ($sub_total+$totalSur)-5000;
							$discount = ($discount*$rowdis['scholarship'])/100;
						}
						
						else
						{
			
							$discount = $total_lec_lab+$totalSur;
							$discount = ($discount*$rowdis['scholarship'])/100;
						}
					}
			  ?>
						   
                    <td><div align="right"><?=number_format((($total_lec_lab+$totalSur)+$sub_total)-$discount, 2, ".", ",")?></div></td>        				
						
					<?php 
					$cnt++;
				}
				?>              
             </tr> 
             <tr>
             <td colspan="2">&nbsp;</td>
              <?php               
       			$sql4 = "SELECT * FROM tbl_payment_scheme WHERE term_id=".CURRENT_TERM_ID;
				$query4 = mysql_query($sql4);
				
				while($row4 = mysql_fetch_array($query4))
				{
					$totalSur = getStudentReservedUnit($student_id)*$row4['surcharge'];
					
					$sqldis = 'SELECT * FROM tbl_student WHERE id='.$student_id;
					$querydis = mysql_query($sqldis);
					$rowdis = mysql_fetch_array($querydis);
					
					if($rowdis['scholarship']!=0)
					{
						if($rowdis['scholarship_type']=='A')
						{
							$discount = ($sub_total+$totalSur)-getLearnerfee();
							$discount = ($discount*$rowdis['scholarship'])/100;
						}
						
						else
						{
			
							$discount = $total_lec_lab+$totalSur;
							$discount = ($discount*$rowdis['scholarship'])/100;
						}
					}
					
					$sql5 = "SELECT * FROM tbl_payment_scheme_details WHERE scheme_id=".$row4['id'];
					$query5 = mysql_query($sql5);
					?>
                    
					<td>
                    <?php
					$subtotal = (($total_lec_lab+$sub_total)+$totalSur)-$discount;
					while($row5 = mysql_fetch_array($query5))
					{
						if($row5['sort_order'] == 1 && $row5['payment_type'] == 'A')
						{
							$topay = $row5['payment_value'];
							$subtotal = $subtotal - $topay;
						}
						else if($row5['sort_order'] == 1 && $row5['payment_type'] == 'P')
						{
							$topay = abs(getStudentPaymentScheme($row5['id'],$subtotal));
						}
						else if($row5['payment_type'] == 'P')
						{
							$topay = abs(getStudentPaymentScheme($row5['id'],$subtotal));
						}
			  ?>
						   
                    <div align="right"><?=$row5['payment_name']." (on/before ".$row5['payment_date'].")   ".number_format($topay, 2, ".", ",")?></div>       				
						
					<?php 
					}
					?>
					</td>
               <?php
			   $subtotal=0;
			   $discount=0;
				}
				?>   
             
             </tr>         
                                             
          </table>!-->

       <!-- </div>       !-->
   		<div class="fieldsetContainer50">
          <!--<table class="classic_borderless">
          		<?php
				
				$sql_reservation ="SELECT * FROM tbl_student_enrollment_status WHERE enrollment_status ='R' AND student_id =" .$student_id;
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
				else
				{
				?>
                <tr>
                  <td><strong>Date Enrolled:</strong></td>
                  <td>March 16, 2010</td>
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
			$surcharge = GetSchemeForSurcharge($student_id)*getStudentReservedUnit($student_id);
			
			if($rows['scholarship_type']=='A')
			{
				  $discount = ($sub_total+$surcharge)-getLearnerfee();
				$discount = ($discount*$rows['scholarship'])/100;
				
			}
			
			else
			{

				$discount = $lec_total+$surcharge;
				$discount = ($discount*$rows['scholarship'])/100;
			}
            
			?>
            <tr>
              <td>Discount:</td>
              <td><div align="right"><strong>
                Php <?=number_format($discount, 2, ".", ",")?></strong>
                </div></td>
            </tr>
            <tr>
              <td>Surcharge:</td>
              <td><div align="right"><strong>
                Php <?=number_format($surcharge, 2, ".", ",")?></strong>
                </div></td>
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
                    <td><strong>Total Charges:</strong></td>
                    <td><div align="right"><strong>
                    Php 
                    <?=number_format($sub_total, 2, ".", ",")?>
                    </strong></div></td>
                </tr>                                
          </table>
          
          <table class="classic_borderless">

        	<tr>

         	<td><strong>Schedule of Fees</strong></td>

            </tr>

			<?php

			

				$sqlsch = "SELECT *

                        FROM tbl_payment_scheme_details

                        WHERE scheme_id = ".GetStudentScheme($student_id)." ORDER BY sort_order";

                        

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

          </table>!-->
<?php if(ACCESS_ID==1 || ACCESS_ID==4 || ACCESS_ID==13){ ?>
 <a href="#" class="button" title="Submit" id="revise"><span>Revise Subjects</span></a>
 <?php } ?>
          
        </div>
   		<p id="formbottom"></p>    
<?php
	}
	else if(mysql_num_rows($queryE)>0)
	{
		echo '<div id="message_container"><h4>Student is currently Enrolled.</h4></div><p id="formbottom"></p>';
	}
	else 
	{
		//echo '<div id="message_container"><h4>You are currently enrolled in School Year ( '.getSchoolYearStartEnd(getSchoolYearIdByTermId(CURRENT_TERM_ID)).' '.getSchoolTerm(CURRENT_TERM_ID).' )</h4></div><p id="formbottom"></p>';
		//echo getStudentNextYearLevel($student_id);
		echo '<div id="message_container"><h4>No Schedule set-up / Full schedule slots detected.'.getStudentNextYearLevel($student_id).'</h4>';
		//echo mysql_num_rows($queryE).$sqlE;
		
		if(ACCESS_ID==1 || ACCESS_ID==8 || ACCESS_ID==4 || ACCESS_ID==13){ 
echo'<p></p><p></p><p><a href="#" class="button" title="Submit" id="revise"><span>Revise Subjects</span></a></p>'; }

		echo '</div><p id="formbottom"></p>';
	}
?>
<!--<div id="dialog" title="Curriculum Subjects">
    Loading...
</div> #dialog -->
<!--
</div>
-->