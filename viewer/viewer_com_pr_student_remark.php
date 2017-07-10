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
			var w=window.open ("pdf_reports/rep108.php?id="+<?=$id?>+"&met=remarks"); 
			return false;
		});
		
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file to this student?"))
			{
				$.ajax({
					type: "POST",
					data: "id="+<?=$id?>+"&met=remarks&email=1",
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
	
	$sql_student = "SELECT * FROM tbl_student WHERE id =" .$id;
	$qry_student = mysql_query($sql_student);
	$row_student = mysql_fetch_array($qry_student);
?>
<div id="lookup_content">
<div id="printable">
<div class="body-container">
<div class="header">
<table width="100%">
    <tr>
        <td width="18%" valign="top" class="bold">Student Name:</td>
        <td width="82%"><?=$row_student['lastname'].", " . $row_student['firstname'] ." " . $row_student['middlename']?></td>
    </tr>
    <tr>
        <td class="bold" valign="top">Student Number:</td>
        <td><?=$row_student['student_number']?></td>
    </tr>
    <tr>
        <td class="bold" valign="top">Course:</td>
        <td><?=getCourseName($row_student['course_id'])?></td>
    </tr>
    <tr>
    	<td colspan=2>&nbsp;</td>
    </tr>
    <tr>
    	<td colspan=2 class="title-action">
       <?php
        $sql = "SELECT * FROM tbl_student_remarks WHERE student_id=" .$id;
        $query = mysql_query($sql);
        $ctr =  mysql_num_rows($query);
        if($ctr > 0)
        {
		?>
        <a class="viewer_email" href="#" id="email" title="email"></a>
        <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
        <a class="viewer_print" href="#" id="print" title="print"></a>
        </td>
    </tr>
</table>
</div>
<div class="content-container">

<div class="content-wrapper-wholeBorder">
<?php

	while($row = mysql_fetch_array($query))
	{
	?>
		<table width=100% style=" font-family:Arial; font-size:12px;">
			<tr>
				<td style="font-size:12px; font-weight:bold; padding-top:20px;">
					<?=getEmployeeFullName($row['professor_id'])?>
				</td>
				<td align=right style="padding-top:20px;">
					<?=date('F d, Y',$row['date_created'])?>
				</td>
			</tr>
			<tr>
				<td colspan="2"><?=getSubjName($row['subject_id'])?></td>
			</tr>
			<tr>
				<td colspan="2"><?=getSchoolTerm($row['term_id'])?> S.Y. <?=getSchoolYearStartEnd($row['school_year_id'])?></td>
			</tr>
			<tr>
				<td colspan=2 style="border-top:1px solid #333;"><?=$row['description']?></td>
			</tr>
		</table>
		<p>&nbsp;</p>
		<?php
	}
}
else
{
?>
	<table width=100% style=" font-family:Arial; font-size:12px;">
    	<tr>
        	<td>No Record Found.</td>
        </tr>
    </table>
<?php
}
?>
    
</div>
</div>
</div>
</div>
<?php
}
?>