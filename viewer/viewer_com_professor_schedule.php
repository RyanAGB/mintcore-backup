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

	$filter_schoolterm = $_REQUEST['filter_schoolterm'];
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
			var w=window.open ("pdf_reports/rep108.php?id="+<?=$id?>+"&met=schedule&emp"+"&trm="+<?=$filter_schoolterm?>); 
			return false;
		});
		
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file to this student?"))
			{
				$.ajax({
					type: "POST",
					data: "id="+<?=$id?>+"&met=schedule&emp"+"&trm="+<?=$filter_schoolterm?>+"&email=1",
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
		
	
	if (isset($filter_schoolterm) and ($filter_schoolterm != "")){
		$arr_sql[] =  "term_id = " . $filter_schoolterm;
	}	
	
	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sql = implode(' AND ', $arr_sql);
	}
	
	$sql = "SELECT * FROM tbl_employee WHERE id = $id";						
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	
	$sqlterm = "SELECT *
					FROM tbl_school_year_term
					WHERE id = ".$filter_schoolterm;
	$queryterm = mysql_query($sqlterm);
	$rowterm = mysql_fetch_array($queryterm); 
	
	$sql_sched = "SELECT * FROM tbl_schedule WHERE employee_id = $id ".$sqlcondition;						
    $query_sched = mysql_query($sql_sched);
?>
<div id="lookup_content">
<div id="printable">
<div class="body-container">
<div class="header">
  <table width="100%">
      <tr>
        <td width="112" class="bold" valign="top">Employee Name:</td>
        <td width="619"><?=$row['lastname'].", " . $row['firstname'] ." " . $row['middlename']?></td>
      </tr>
      <tr>
        <td class="bold" valign="top">Employee No.:</td>
        <td><?=$row['emp_id_number']?></td>
      </tr>
      <tr>
        <td class="bold" valign="top">Department:</td>
        <td><?=getDeptName($row['department_id'])?></td>
      </tr>
      <tr>
        <td width="112" class="bold">School Year:</td>
        <td width="619"><?=getSchoolYearStartEnd($rowterm['school_year_id']) .' ('.getSchoolTerm($rowterm['id']).')'?></td>
      </tr>
	<tr>
    <td>&nbsp;</td>
</tr>
<tr>
    <td colspan=2 class="title-action">
    	<?php
		if(mysql_num_rows($query_sched) > 0){
	?>
    <a class="viewer_email" href="#" id="email" title="email"></a>
    <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
    <a class="viewer_print" href="#" id="print" title="print"></a>
    </td>
</tr>
<tr>
    <td colspan=2>&nbsp;</td>
  </tr>
</table>

</div>
<div class="content-container">

<div class="content-wrapper-withoutBorder">

    <table class="classic">     
        <tr>
          <th class="col_150">Code</th>
          <th class="col_150">Units</th>
          <th class="col_200">Schedule</th>
          <th class="col_50">Section</th>   
          <th class="col_50">No.of Students</th>                            
        </tr>
		<?php
		$ctr = 1;
		$totalU = 0;

        while($row_sched = mysql_fetch_array($query_sched))
        {
			if(!checkIfElective($row_sched['subject_id']))
			{
        ?>
            <tr class="<?=($ctr%2==0)?"":"highlight";?>"> 
                <td class="col_70"><?=getSubjCode($row_sched['subject_id'])?></td>
                <td class="col_70"><?=getSubjUnit($row_sched['subject_id'])?></td>
                <td><?=getScheduleDays($row_sched["id"])?></td>
                <td><?=$row_sched['section_no']?></td>
                <td><?=getNumberScheduleEnrolled($row_sched['id'],$filter_schoolterm)?></td>                          
            </tr>
        <?php
		$totalU += getSubjUnit($row_sched['subject_id']);
			}
		$ctr++;
        }
		?>
        
        <tr> 
                <td colspan="6">Total Units : <?=$totalU?></td>                                
            </tr> 
        
        <?php
	}
	else
	{
	?>
			<tr> 
                <td colspan="6">No record found</td>                                
            </tr> 
	<?php
    }	
        ?>
    </table>
    </div>
    </div>
</div>
</div>
<?php
}
?>