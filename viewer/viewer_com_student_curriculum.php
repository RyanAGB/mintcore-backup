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
			var w=window.open ("pdf_reports/rep108.php?id="+<?=$id?>+"&met=curriculum"); 
			return false;
		});
		
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file to this student?"))
			{
				$.ajax({
					type: "POST",
					data: "id="+<?=$id?>+"&met=curriculum&email=1",
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


		$sql = "SELECT * FROM tbl_student  WHERE id = ".$id;
		$query = mysql_query($sql);
		$row =mysql_fetch_array($query);	
		$cur_id = $row['curriculum_id'];	
		
		$sql_dis = "SELECT * FROM tbl_curriculum WHERE id = ".$row['curriculum_id'];
		$result_dis = mysql_query($sql_dis);
		$row_dis = mysql_fetch_array($result_dis);
		$no_year = $row_dis['no_of_years'];
		$no_term = $row_dis['term_per_year'];
		
		//$summer = $no_term+1;
		$sql_subj = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = ".$cur_id;
		$query_subj = mysql_query($sql_subj);
		$num = mysql_num_rows($query_subj);
                 
 $ctr = 1;

?>
<div id="lookup_content">
<div id="printable">
<div class="body-container">
<div class="header">
<table width=100%>
<tr>
    <td width="18%" valign="top" class="bold">Student Name:</td>
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
    <td class="bold" valign="top">Curriculum:</td>
    <td><?=$row_dis['curriculum_code']?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
</tr>
  <tr>
    <td colspan=2 class="title-action">

    <a class="viewer_email" href="#" id="email" title="email"></a>
    <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
    <a class="viewer_print" href="#" id="print" title="print"></a>
    
    </td>
</tr>
</table>
</div>
<div class="content-container">

<div class="content-wrapper-wholeBorder">
<table border="0" cellspacing="10" cellpadding="0">
<?php
for($ctr_year = 1; $ctr_year<= $no_year; $ctr_year++)
{
?>
  <tr>
    <td width="600"><?=getYearLevel($ctr_year) ?></td>
  </tr>
  <tr>
    <td width="600" style="padding-left:10px;">
        <table border="0" cellspacing="0" cellpadding="0" class="classic">	
		<?php
        for($ctr_terms = 1; $ctr_terms<= $no_term; $ctr_terms++)
        {
        ?>
          <tr>
            <td width="600" colspan="4"><strong><?=getSemesterInWord($ctr_terms)?></strong></td>
          </tr>
                  <tr>
                    <th width="100"><strong>Subject Code</strong></th>
                    <th width="350"><strong>Subject</strong></th>
                    <th width="50"><strong>Units</strong></th>
                    <th width="100"><strong>Status</strong></th>
                  </tr>
                  <?php
				  $total_units	= 0;
				  $sql_sub = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = ".$cur_id." AND year_level = ".$ctr_year." AND term = ".$ctr_terms;
					$query_sub = mysql_query($sql_sub); 
				  $ctr_row = mysql_num_rows($query_sub);
				  if($ctr_row > 0)
				  {
					  while($row = mysql_fetch_array($query_sub))
					  {
						  
						  $sqlc = "SELECT * FROM tbl_student_credit_final_grade WHERE student_id=".$id." AND subject_id=".$row['subject_id'];
						  $queryc = mysql_query($sqlc);
						  $rowc = mysql_fetch_array($queryc);
	
				  ?>
                      <tr>
                        <td><?=getSubjCode(getElectiveSubject($row['subject_id'])!=0?getElectiveSubject($row['subject_id']):$row['subject_id'])?></td>
                        <td><?=getSubjName(getElectiveSubject($row['subject_id'])!=0?getElectiveSubject($row['subject_id']):$row['subject_id'])?></td>
                        <td><?=$row['units']?></td>
                        <td>
				<?php
				if(mysql_num_rows($queryc)>0)
				{
					echo $rowc['final_grade'].'(credited)';
				}
				else if(checkIfstudentFinishedSubject($id,$row['subject_id'],$row['id'])===false && !checkIfstudentFailedSubject($id,$row['subject_id']))
				{
				echo getStudentFinalGradeBySubject($id,$row['subject_id']);
				}
				else if(checkIfSubjectINCBySubj($id,$row['subject_id']))
				{
				echo 'INC';	
				}
				else if(checkIfstudentFailedSubject($id,$row['subject_id']))
				{
				echo 'Failed';
				}
				else if(CheckIfStudentEnrolledBySubject($id,CURRENT_TERM_ID,$row['subject_id']))
				{
				echo 'Currently Enrolled';
				}
				else
				{
				echo '';
				}
				?>
                </td>
                      </tr>
                  <?php
				  		$total_units += $row['units'];
				  	}
				  }
				  else
				  {
				  ?>
                  	  <tr>
                        <td colspan="4"><strong>No subject found.</strong></td>
                      </tr>
                  <?php
				  }
				  ?>
                    <tr>
                      <td colspan="2">&nbsp;</td>
                      <td><?=$total_units?></td>
                      <td>&nbsp;</td>
                    </tr>                  
               
        <?php
		}
		?>
        </table>
    </td>
  </tr>
  <tr>
    <td width="600">&nbsp;</td>
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