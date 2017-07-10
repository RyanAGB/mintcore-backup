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
			var w=window.open ("pdf_reports/rep108.php?id="+<?=$id?>+"&trm="+<?=$filter_schoolterm?>+"&met=schedule"); 
			return false;
		});
		
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file to this student?"))
			{
				$.ajax({
					type: "POST",
					data: "id="+<?=$id?>+"&trm="+<?=$filter_schoolterm?>+"&met=schedule&email=1",
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
		$arr_sql[] =  "stud_sched.term_id = " . $filter_schoolterm;
	}	
	
	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sql = implode(' AND ', $arr_sql);
	}	

	$sqldet = "SELECT * FROM tbl_student WHERE id = $id";
	$resultdet = mysql_query($sqldet);
	$row = mysql_fetch_array($resultdet);
	
	$sql = "SELECT 
					stud_sched.subject_id,
					stud_sched.units,
					stud_sched.term_id,
					stud_sched.elective_of, 
					stud_sched.enrollment_status, 
					stud_sched.schedule_id ,
					sched.room_id, 
					sched.section_no
				FROM 
					tbl_student_schedule stud_sched LEFT JOIN tbl_schedule sched ON 
					stud_sched.schedule_id = sched.id
					WHERE enrollment_status <> 'D' AND
					stud_sched.student_id =  " . $id . 
					$sqlcondition ;	
										
		$result = mysql_query($sql);
		$ctr = mysql_num_rows($result);
?>
<div id="lookup_content">
<div id="printable">
<div class="body-container">
<div class="header">
<table width="100%">
  <tr>
    <td width="18%" class="bold">Student Name:</td>
    <td width="82%"><?=$row['lastname']. ", " . $row['firstname'] ." " . $row['middlename']?></td>
  </tr>
  <tr>
    <td class="bold" valign="top">Student Number:</td>
    <td><?=$row['student_number']?></td>
  </tr>
  <tr>
    <td class="bold" valign="top">Course:</td>
    <td><?=getCourseName($row['course_id'])?></td>
  </tr>
  <tr>
        <td class='bold' valign="top">School Year:</td>
        <td><?=getSYandTerm($filter_schoolterm)?></td>
      </tr>
  <tr>
    <td>&nbsp;</td>
</tr>
  <tr>
    <td colspan=2 class="title-action">
    <?php if($ctr > 0)
		{ ?>
    <a class="viewer_email" href="#" id="email" title="email"></a>
    <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
    <a class="viewer_print" href="#" id="print" title="print"></a>
    <?php } ?>
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
        	<th class="col1_150">Section</th>
            <th class="col1_150">Code</th>  
            <th class="col1_150">Subject Name</th>   
            <th class="col1_50">Room</th>  
            <th class="col1_150">Schedule</th>                        
        </tr>
        <?php
        $x = 1;
		
		if($ctr > 0)
		{
			while($row = mysql_fetch_array($result)) 
			{ 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
            	<td><?=getSectionNo($row["schedule_id"])?></td> 
                <td><?=getSubjCode($row["subject_id"])?></td>
                <td><?=$row['elective_of']!=""?getSubjName($row["subject_id"])."(".getSubjName($row['elective_of']).")":getSubjName($row["subject_id"])?></td>
                <td><?=getRoomNo($row["room_id"])?></td>
                <td><?=getScheduleDays($row["schedule_id"])?></td>                              
            </tr>
        <?php 
            $x++;          
        	}
		}
		else
		{
        ?>
            <tr> 
                <td colspan="7">No record found</td>                                
            </tr>        
        <?php
		}
		?>
        
    </table> 
    
</div> 
</div>
</div>
</div><!-- #lookup_content -->
<?php
}
?>