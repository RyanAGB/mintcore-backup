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

error_reporting(0);

if(USER_IS_LOGGED != '1')
	{
		header('Location: ../index.php');
	}
	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}

$student_id = $_REQUEST['student_id'];

$sql = "SELECT * FROM tbl_student WHERE id = ".$student_id;						
$query = mysql_query($sql);
$row = mysql_fetch_array($query);

?>
<div class="headerForm">         
    <table class="classic_borderless">
      <tr>
        <td valign="top" style='font-weight:bold'>Student Name:</td>
        <td><?=$row['lastname']. ", " . $row['firstname'] ." " . $row['middlename']?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Student Number:</td>
        <td><?=$row['student_number']?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Course:</td>
        <td><?=getCourseName($row['course_id'])?></td>
      </tr>
      <?php
	  if(checkIfStudentReservationIsExpiredByStudId($student_id))
    	{
	  ?>
      <tr>
        <td style='font-weight:bold' valign="top">Enrollment Remarks:</td>
        <td>The enrollment reservation of this student has expired.</td>
      </tr>
      <?php
	  }
	  ?>      
    </table>  
</div> 
<?php
if(checkIfStudentIsEnroll($student_id))
{
     echo '<div id="message_container"><h4>Student is already enrolled.</h4></div><p id="formbottom"></p>';
}
else
{
?>
<script type="text/javascript">
$(function(){

	// Dialog			
	$('#dialog_block').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Ok": function() { 
				$(this).dialog("close"); 
			}, 
			"Cancel": function() {  
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('.applicant').click(function(){
	var param = $(this).attr("returnId");
	var param2 = $(this).attr("returnsId");
	var param3 = $(this).attr("returnComp");
		$('#dialog_block').load('lookup/lookup_com_block_subject.php?id='+param+'&student_id='+param2+'&comp='+param3, null);
		$('#dialog_block').dialog('open');
		return false;
	});
	
});		

$(document).ready(function(){ 

$('.publish').click(function(){
		var param = $(this).attr("returnId");
		if($('#scheme_id').val()!='')
		{
			if(confirm('Are you sure you want to complete the reservation? This will reflect to student assessment.'))
			{
				clearTabs();
				$('#enroll').addClass('active');
				$('#view').val('enroll');
				$('#sched').val(param);
				$('#scheme').val($('#scheme_id').val());
				$('#fees_ID').val($('#feesID').val());
				$('#action').val('enrol_block');
				schedList();
				$("form").submit();
			}
			else
			{
				return false;
			}
		}
		else
		{
			alert('Please Select Payment Scheme');	
		}
	});	
	
	$('.unpublished').click(function(){
		
		alert('This block section contains some subject with full slots.');
		
	});	
	
});	

</script>

<?php	
	$cnt = 0;

	$sql = "SELECT * FROM tbl_student WHERE id = ".$student_id;						
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	

	$sqlblok='SELECT * FROM tbl_block_section WHERE course_id = '.$row['course_id'].' 
				AND term_id = '.CURRENT_TERM_ID. ' AND year_level='.getStudentNextYearLevel($student_id);
	$resultblok = mysql_query($sqlblok);
 
        if (mysql_num_rows($resultblok) > 0 )
        {
            $x = 0;
?>        
        
       <table class="listview">    
          <tr>
             <th class="col_150">Block Name</th>
              <th class="col_150">Action</th>
          </tr>
        
        <?php    while($rowblok = mysql_fetch_array($resultblok)) 
            { 
				if(checkBlockSubjectExist($rowblok['id']))
				{
        ?>
            <tr class="<?=($x%2==0)?"":"highlight"?>">
                <td><?=$rowblok["block_name"]?></td>
                <td class="action">
                   <ul>
	                    <li><a class="applicant" href="#" title="View Schedule" returnId='<?=$rowblok['id']?>' returnsId='<?=$student_id?>' returnComp='<?=$_REQUEST['comp']?>'></a></li>
                        
                   <?php if(checkSlotAvailableInBlock($rowblok['id'],$student_id)) {?>
                   
						<li><a class="unpublished" href="#" title="Reserve"></a></li>
                        
                <?php }else{ ?>
                
                        <li><a class="publish" href="#" title="Reserve" returnId='<?=$rowblok['id']?>'></a></li>
                <?php } ?>
                
                    </ul>
                </td>
            </tr>
            
        <?php  
			}
           }
		   ?>
		 <!--  <tr>
    	<td colspan="5">Select Payment Scheme
        	<select name="scheme_id" class="txt_150" id="scheme_id">

                  <option value="">Select</option>

                        <?=generateScheme($payment_scheme_id)?>

              </select>       
        </td>
    
    </tr>
    <tr>
    	<td colspan="5">Select Fee Format
        	<select name="feesID" class="txt_200" id="feesID" onchange="getfees(this.value);">

                  <option value="">Select</option>

                        <?=generateFees($fees_ID)?>

              </select>       
        </td>
    
    </tr>!-->
    </table>
		  <?php 
        }
        else 
        {
                echo '<div id="message_container"><h4>No records found</h4></div>';
        }
?>

	<p id="formbottom"></p> 
      
    <div id="dialog_block" title="Block Subjects">
    Loading...
    </div><!-- #dialog -->
       
<?php
}
?>
