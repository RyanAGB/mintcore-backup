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
	
	
	$student_id = $_REQUEST['student_id'];
?>
<script type="text/javascript">
$(function(){

	// Dialog			
	$('#dialog').dialog({
		autoOpen: false,
		width: 500,
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
	$('.pay').click(function(){
		var param = $(this).attr("returnId");
		$('#scheme_id').val(param);
		//alert(param);
		$('#dialog').load('lookup/lookup_com_cs_pay.php?id='+<?=$student_id?>+'&bill='<?=$sub_total?>+'&type=reserve', null);
		$('#dialog').dialog('open');
		return false;
	});
	
});	
 
$(function(){

	$('#save').click(function(){
		clearTabs();
		$('#add_new').addClass('active');
		$('#action').val('save');
		$('#view').val('add');
		$("form").submit();
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
	
	$('#discount_id').change(function(){
			
			update_computation($(this).val());	
	});
	
	update_computation();
	
	$('#print').click(function() {
			w=window.open();
			w.document.write($('#printable').html());
			w.document.write($('#printable2').html());
			w.document.write($('#printable3').html());
			w.print();
			w.close();
			return false;
		});	
});	
function update_computation(discount_id){
	$.ajax({
				type: "POST",
				data: "discount_id="+discount_id + "&student_id="+$('#student_id').val(),
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

			
	if (isset($_REQUEST["search_field"]) and ($_REQUEST["search_field"] != "") and ($_REQUEST["search_key"] != "") and ($_REQUEST["search_key"] != "")){
		$search_field = $_REQUEST["search_field"];
		$search_key = $_REQUEST["search_key"]; 
		$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
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
	
	$sql_pagination = "SELECT * FROM 
							tbl_student_reserve_subject 
						WHERE
							term_id = ".CURRENT_TERM_ID." AND
							student_id= " .$student_id
						.$sqlcondition ;
						
	$query_pagination  = mysql_query ($sql_pagination);
	$row_ctr = mysql_num_rows($query_pagination); 
	
	// initial records
	if(isset($_REQUEST['pageNum']) && $_REQUEST['pageNum'] != '')
	{
		$pagenum = ($_REQUEST['pageNum']*$page_rows) - $page_rows; 
	}
	else
	{
		$pagenum = 0; 
	}
	if($row_ctr>0)
	{
		//This tells us the page number of our last page 
		$last = ceil($row_ctr/$page_rows); 			
				
		$max = 'limit ' .$pagenum .',' .$page_rows; 	
		
		$sql = "SELECT * FROM 
							tbl_student_reserve_subject 
						WHERE
							term_id = ".CURRENT_TERM_ID." AND
							student_id= " .$student_id
				 .$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		

	$sql_info = "SELECT * FROM tbl_student WHERE id = $student_id";
	$query_info = mysql_query($sql_info);
	$row_info = mysql_fetch_array($query_info);		
?>         
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
    </table>  
</div>          
        <?php  
        if (mysql_num_rows($result) > 0 )
        {
            $x = 0;
        ?>
<div id="printable" style="font-family:Arial;">
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
			  <th class="col_100">Schedule</th>
          </tr>
        <?php
            
//CURRENT_TERM_ID
			$sql = "SELECT * FROM 
							tbl_student_reserve_subject 
						WHERE
							term_id =  ".CURRENT_TERM_ID."  AND
							student_id= " .$student_id;
			$result = mysql_query($sql);
			$total_units = 0;
			while($row = mysql_fetch_array($result)) 
            {
				$total_units += getSubjUnit($row["subject_id"]);
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
            	<td><?=getSectionNo($row["schedule_id"])?></td>
                <td><?=getSubjCode($row["subject_id"])?></td> 
				<td><?=getSubjName($row["subject_id"])?></td>
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
                echo "No records found";
        }
        ?>
		</table> 
        </div>
        <label>&nbsp;</label>
        
   		<div class="fieldsetContainer50">

        <div id="printable2" style="font-family:Arial;">
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
                $sql = "SELECT *
                        FROM tbl_school_fee
                        WHERE publish =  'Y'
						AND term_id=" .CURRENT_TERM_ID;
                        
                $result = mysql_query($sql);
				$sub_total = 0;
                while($row = mysql_fetch_array($result)) 
                {
					$total = getStudentTotalFeeLecLab($row['id'],$student_id);
            ?>
                <tr>
                    <td><?=$row['fee_name']?></td>
                  	<td><?=getFeeAmount($row['id'])?></td>
                    <td>
                      <div align="right">
                        Php <?=$total==''?'0.00':number_format($total, 2, ".", ",")?>
                      </div></td>
                </tr>
			<?php           
					$sub_total += $total;
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

          
          <p>&nbsp;</p>
          <!--<table class="classic" style="background-color:#FBFBFB">
            <tr>
              <th colspan="2"><strong>Add payment</strong></th>
            </tr>
            <tr>
              <td width="40%"><strong>Discount</strong></td>
              <td>
              	  <input type="hidden" name="student_id" id="student_id" value="<?=$student_id?>" />
                  <select name="discount_id" class="txt_100" id="discount_id">
                  <option value="0">None</option>
                        <?=generateDiscount($payment_method)?>
              </select>              
              </td>
            </tr>
            <tr>
              <td width="40%"><strong>Payment Method</strong></td>
              <td>
                  <select name="payment_method" class="txt_100" id="payment_method">
                  <option value="">Select</option>
                        <?=generatePaymentMethod($payment_method)?>
              </select>              </td>
            </tr>  
            <tr>

                <td colspan="2">
                    <div id="chek">
                    </div>
                </td>

            </tr>
            <!-- <tr>
             <td><strong>Payment Terms</strong></td>
              <td>
                  <select name="payment_term" class="txt_100" id="payment_term">
                        
              </select>              </td>!
            </tr>   
            <tr>
              <td><strong>Amount</strong></td>
              <td><input name="amount" type="text" class="txt_100" id="amount"/></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><a href="#" class="button" title="Save" id="save"><span>Save</span></a></td>
            </tr>                                        
          </table>-->
        </div>       
   		<div class="fieldsetContainer50">
        <div id="printable3" style="font-family:Arial;">
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
        <p>
         
              
          </div>
        </div>
   		<p>
        <table class="listview_classic">
        	<tr>
         	<th class="col_50" colspan="2">Schedule of Fees</th>
            <th class="col_150">Paid Balance</th>
            <th class="col_150">Remaining Balance</th>
            <th class="col_150">Remarks</th>
            <th class="col_50">Pay</th>
            </tr>
			<?php
                $sqlsch = "SELECT det.*
                        FROM tbl_payment_scheme_details det,
						tbl_payment_scheme sch
                        WHERE sch.id = det.scheme_id
						AND sch.term_id=" .CURRENT_TERM_ID;
                        
                $resultsch = mysql_query($sqlsch);
                while($rowsch = mysql_fetch_array($resultsch)) 
                {
					if($rowsch['sort_order'] == 1)
					{
						$down = $rowsch['payment_value'];
            ?>
                <tr>
                    <td><?=$rowsch['payment_name']?></td>
                  	<td>Php <?=number_format($down, 2, ".", ",")?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="action">
                    <ul>
                        <li><a class="pay" href="#" name="stud_id" returnId="<?=$rowsch['id']?>" title="Pay"></a></li>
                    </ul>
                </td>
                </tr>
			<?php   
					}
					else
					{
		     ?>
             	<tr>
                    <td><?=$rowsch['payment_name']?></td>
                  	<td>Php <?=number_format(getStudentPaymentScheme($rowsch['id'],$sub_total,$down), 2, ".", ",")?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="action">
                    <ul>
                        <li><a class="pay" href="#" title="Pay Balance" returnId="<?=$rowsch['id']?>"></a></li>
                    </ul>
                </td>
                </tr>
             <?php
               	}
			   }
            ?>                                
          </table>  
        
       
          
          
          <p id="formbottom"></p> 
<?php
	}
	else 
	{
		echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';
	}
?>
<!-- LIST LOOK UP-->
<div id="dialog" title="Pay Balance">
    Loading...
</div><!-- #dialog -->