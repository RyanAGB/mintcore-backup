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
$id = $_REQUEST['id'];
$term_id = $_REQUEST['filter_schoolterm'];


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
		
		$('#print').click(function() {
			var w=window.open();
			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
			w.document.write($('#lookup_content').html());
			w.document.close();
			w.focus();
			w.print();
			//w.close()
			return false;
		});
		
		$('#pdf').click(function() {
			var w=window.open ("pdf_reports/rep108.php?id="+<?=$id?>+"&trm="+<?=$term_id?>+"&met=grade"); 
			return false;
		});
		
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file to this student?"))
			{
				$.ajax({
					type: "POST",
					data: "id="+<?=$id?>+"&trm="+<?=$term_id?>+"&met=grade&email=1",
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
	
	$sqldet = "SELECT * FROM tbl_student WHERE id = $id";
	$resultdet = mysql_query($sqldet);
	$row = mysql_fetch_array($resultdet);
		
	$sqlbal = "SELECT * FROM tbl_student_payment WHERE student_id =".$id." AND term_id=".$term_id;
	$querybal = mysql_query($sqlbal);
	
	$cnt = 0;
	
	while($rowbal = mysql_fetch_array($querybal))
	{
		$totalpayment += $rowbal['amount'];
		
		if($cnt==0)
		{
			$date_enroll = $rowbal['date_created'];	
		}
		
		$cnt++;
	}
	
	$bal = getStudentTotalFee($id)-$totalpayment;
?>
<div id="lookup_content">
<div id="printable">
<div class="body-container">
<div class="header">
<table class="classic" width="100%">
<tr>
    <td colspan=5 class="title-action">
 
    <!--<a class="viewer_email" href="#" id="email" title="email"></a>
    <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>!-->
    <a class="viewer_print" href="#" id="print" title="print"></a>
    </td>
</tr>

  <tr>
    <td colspan="5" align="center" valign="top" class="bold"><p>STATEMENT OF ACCOUNT</p>
      <p>As of <?=date('m/d/Y')?></p></td>
    </tr>
  <tr>
    <td width="18%" valign="top" class="bold">Student :</td>
    <td width="82" colspan="4"><strong>
      <?=$row['lastname']. ", " . $row['firstname'] ." " . $row['middlename']?>
    </strong></td>
  </tr>
  <tr>
    <td rowspan="2" valign="top" class="bold">Outstanding Balance:</td>
    <td colspan="4" align="center"><strong>AGING PROFILE</strong></td>
  </tr>
  <tr>
    <td align="center">Current</td>
    <td align="center">31-60 DAYS</td>
    <td align="center">61-90 DAYS</td>
    <td align="center">OVER 90 DAYS</td>
  </tr>
  <tr>
    <td class="bold" valign="top"><?=$bal?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><?=$bal?></td>
  </tr>
   <tr>
        <td colspan="5" valign="top" class='bold'>
        
        <table width="100%">
        
        <tr>
        <td>
        	DATE
        </td>
        <td><strong>PARTICULARS</strong></td>
        <td>AMOUNT</td>
        <td>PAYMENTS</td>
        <td><strong>BAL.DUE</strong></td>
        </tr>
        
        <tr>
        <td><?=date('m/d/Y',$date_enroll)?></td>
        <td><?=$row['lastname']. ", " . $row['firstname'] ." " . $row['middlename']?></td>
        <td><?=getStudentTotalFee($id)?></td>
        <td><?=$totalpayment?></td>
        <td><?=$bal?></td>
        </tr>
        
        <tr>
          <td colspan="5"><p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td><strong>TOTAL</strong></td>
          <td><?=$bal?></td>
        </tr>
        
        <tr>
          <td colspan="5">This S/A is deemed correct unless we recieved a written notice of any exception within five (5) days from receipt hereof.</td>
        </tr>
        
        </table>
        
        </td>
        </tr>
   <tr>
     <td colspan="2" align="center" valign="top" class='bold'><p>CERTIFIED CORRECT :</p>
       <p>ARIES/CESAR</p></td>
     <td align="center" valign="top" class='bold'><p>NOTED BY :</p>
       <p>MIKE DEL ROSARIO</p></td>
     <td align="center" valign="top" class='bold'>RECEIVED BY :</td>
     <td align="center" valign="top" class='bold'>DATE RECIEVED :</td>
   </tr>
  <tr>
    <td colspan=5 class="title-action">
      <?php
if($ctr_sched > 0)
{
?>
      <a class="viewer_email" href="#" id="email" title="email"></a>
      <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
      <a class="viewer_print" href="#" id="print" title="print"></a>
      <?php } ?>
      </td>
</tr>
</table>
 </div>
<div class="content-container">

<div class="content-wrapper-withoutBorder">

</div> 
</div> 
</div> 
</div><!-- #lookup_content -->
<?php
}
?>