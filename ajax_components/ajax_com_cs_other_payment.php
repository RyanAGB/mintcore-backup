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
	
	if($('#payment_met').val()!=''||$('#lname').val()!=''||$('#fname').val()!=''||$('#amount_paid').val()!=''||$('#check_num').val()!=''||$('#bank').val()!=''||$('#snumber').val()!=''||$('#enumber').val()!=''||$('#type').val()!=''||$('#classif').val()!='')
	{
		$('#payment_typ').val($('#type').val());
		$('#payment_method').val($('#payment_met').val());
		
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
			
			if($('#classif').val()!=''&&$('#classif').val()=='stud')
			{
			$.ajax({
				type: "POST",
				data: "mod=updateOtPayment&id=" + $('#classif').val()+"&lname="+$('#lname').val()+"&fname="+$('#fname').val()+"&snum="+$('#snumber').val(),
				url: "ajax_components/ajax_com_payment_field_updater.php",
				success: function(msg){
					if (msg != ''){
						$("#typ").html(msg);
					}
				}
				});	
				
			}
			else if($('#classif').val()!=''&&$('#classif').val()=='emp')
			{
				$.ajax({
				type: "POST",
				data: "mod=updateOtPayment&id=" + $('#classif').val()+"&lname="+$('#lname').val()+"&fname="+$('#fname').val()+"&enum="+$('#enumber').val(),
				url: "ajax_components/ajax_com_payment_field_updater.php",
				success: function(msg){
					if (msg != ''){
						$("#typ").html(msg);
					}
				}
				});	
				
			}
			else
			{
				$.ajax({
				type: "POST",
				data: "mod=updateOtPayment&id=" + $('#classif').val()+"&lname="+$('#lname').val()+"&fname="+$('#fname').val(),
				url: "ajax_components/ajax_com_payment_field_updater.php",
				success: function(msg){
					if (msg != ''){
						$("#typ").html(msg);
					}
				}
				});	
				
			}		
	}
	
	$('#save').click(function(){
		clearTabs();
		var pay = $('#payment_method').val();
		var lname = $('#lastname').val();
		var fname = $('#firstname').val();
		var check_number = $('#check_no').val();
		var bank = $('#bank').val();
		var amount = $('#amount').val();
		var typ = $('#payment_typ').val();
		var empnumber = $('#employeenum').val();
		var studnumber = $('#studentnum').val();
		var classif = $('#classif').val();
	
		if(classif=='')
		{
			alert('Please Select Classification.');
			return false;
		}
		else if(typ=='')
		{
			alert('Select Payment Type');
			return false;
		}
		else if(pay=='')
		{
			alert('Select Payment Method');
			return false;
		}
		else if(pay != '' && pay == <?=getPaymentMethodCheque()?>)
		{

			if(amount!='' && bank!='' && check_number!='' && pay!='' && lname!='' && fname!='' && typ!='')
			{
				$('#check_num').attr("value", check_number);
				$('#bank_brn').attr("value", bank);
				$('#payment_met').attr("value", pay);
				$('#amount_paid').attr("value", amount);
				$('#lname').attr("value", lname);
				$('#fname').attr("value", fname);
				$('#enumber').val(empnumber);
				$('#snumber').val(studnumber);
				$('#type').val(typ);
				$('#action').val('save');
				$("form").submit();	
			}
			else
			{
				alert('Empty Field Found.');
					return false;
			}
		}
		else if(pay!='' && pay!=<?=getPaymentMethodCheque()?>)
		{
				if(amount!='' && pay!='' && lname!='' && fname!='' && typ!='')
			{
				$('#payment_met').attr("value", pay);
				$('#amount_paid').attr("value", amount);
				$('#lname').attr("value", lname);
				$('#fname').attr("value", fname);
				$('#enumber').val(empnumber);
				$('#snumber').val(studnumber);
				$('#type').val(typ);
				$('#action').val('save');
				$("form").submit();		
				}
				else
				{
					alert('Invalid Empty Field.');
					return false;
				}
		}
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

function radioOption(val)
{
//alert(val);
	$("#typ").html('');
		$.ajax({
				type: "POST",
				data: "mod=updateOtPayment&id=" + val,
				url: "ajax_components/ajax_com_payment_field_updater.php",
				success: function(msg){
					if (msg != ''){
						$("#typ").html(msg);
					}
				}
				});	
	$("#classif").val(val);
}

</script>   
<div>&nbsp;</div>
<div class="content-container">

<div class="content-wrapper-wholeBorder">
	<div class="fieldsetContainer50">
     <?php 
	 
	 $sql = "SELECT student.* 
				FROM tbl_student student,
					tbl_course course
				WHERE student.course_id = course.id"
					 . $sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql); 
        if (mysql_num_rows($result) > 0 )
        {
            $x = 0;

        ?>
    <table class="listview">      
          <tr>
              <th class="col_70"><a href="#" class="sortBy" returnFilter="student_number">Student Number</a></th>
              <th class="col_100"><a href="#" class="sortBy" returnFilter="lastname">Full Name</a></th>
              <th class="col_200"><a href="#" class="sortBy" returnFilter="course_id">Course</a></th>
              <th class="col_50">Action</th>
          </tr>
        <?php

            while($row = mysql_fetch_array($result)) 
            {
				$stat = checkIfStudentIsEnroll($row["id"])?'E':'R';
				?>
                <tr class="<?=($x%2==0)?"":"highlight";?>">
                    <td><?=$stat.$row["student_number"]?></td> 
                    <td><?=$row["lastname"].' , '.$row["firstname"].' '.$row["middlename"]?></td>
                    <td><?=getStudentCourse($row["id"])?></td>
                    <td class="action">
                        <ul>
                            <li><a class="curSub" href="#" name="stud_id" returnId="<?=$row['id']?>" title="Pay" returnComp="<?=$_REQUEST['comp']?>"></a></li>
                        </ul>
                    </td>
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
    
    <!--<fieldset>
            <legend><strong>Classification</strong></legend>
            <label><input type="radio" name="chk" id="stud" value="stud" onclick="radioOption(this.value);" <?//=$classif=='stud'?'checked="checked"':''?>> Student</label>
            <span >
            </span><br class="hid" />
            <label><input type="radio" name="chk" id="emp" value="emp" onclick="radioOption(this.value);" <?//=$classif=='emp'?'checked="checked"':''?>> Employee</label>
            <span >
            </span><br class="hid" />
            <label><input type="radio" name="chk" id="out" value="out" onclick="radioOption(this.value);" <?//=$classif=='out'?'checked="checked"':''?>> Outsider</label>
            <span >
            </span><br class="hid" /> 
        </fieldset>!-->
    </div>
    <!--<div class="fieldsetContainer50">
        <fieldset>
            <legend><strong>Payment Set-Up</strong></legend>
            <span >
                
                    <div id="typ">
                    </div>
			
            <label>Type</label>
            <span >
                 <select name="payment_typ" class="txt_150" id="payment_typ">
                  <option value="">Select</option>
                        <?//=generatePaymentType($type)?>
              </select>   
            </span><br class="hid" />
             <label>Payment Method</label>
            <span >
                 <select name="payment_method" class="txt_150" id="payment_method">
                  <option value="">Select</option>
                        <?//=generatePaymentMethod($payment_met)?>
              </select>  
            </span><br class="hid" />               
            <div>&nbsp;</div>
                    <div id="chek">
                    </div>
                
<div>&nbsp;</div>
           
            <a href="#" class="cash-button" title="Save" id="save"><span>Save</span></a>
            
            </span><br class="hid" />    
        </fieldset>
    </div>!-->
   </div>
   </div>     

       
   		    <p id="pagin"></p> 
