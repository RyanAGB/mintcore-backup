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
	
	$sql = "SELECT 
					stud_sched.schedule_id,
					sched.elective_of,
					sched.id,
					sched.subject_id,
					sched.room_id,
					sched.employee_id
				FROM 
					tbl_student_schedule stud_sched,
					tbl_schedule sched 
				WHERE 
					stud_sched.schedule_id = sched.id AND 
					stud_sched.student_id = $id  AND 
					sched.term_id = $term_id" ;				
	$result = mysql_query($sql);
	$ctr_sched = mysql_num_rows($result);
	
	$sqldet = "SELECT * FROM tbl_student WHERE id = $id";
	$resultdet = mysql_query($sqldet);
	$row = mysql_fetch_array($resultdet);
		
	
?>
<div id="lookup_content">
<div id="printable">
<div class="body-container">
<div class="header">
<table width="100%">
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
        <td class='bold' valign="top">School Year:</td>
        <td><?=getSYandTerm($term_id)?></td>
      </tr>
  <tr>
    <td>&nbsp;</td>
</tr>
  <tr>
    <td colspan=2 class="title-action">
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
<?php
if($ctr_sched > 0)
{
?>
    <table class="fieldsetList">      
        <tr>
            <th class="col1_150">Code</th>  
            <th class="col1_150">Subject Name</th>   
			<?php
            $sql_period = "SELECT * FROM tbl_school_year_period WHERE 
                        term_id=".$term_id." ORDER BY period_order";						
			
            $query_period = mysql_query($sql_period);
			
            while($row_period = mysql_fetch_array($query_period))
            {
            ?>
                <th class="col1_50"><?=$row_period['period_name']?></th>
            <?php
            }
            ?> 
            	<th colspan="2" class="col1_50">Grade</th>
          </tr>
        <?php
        $x = 1;
		$cnt = 0;
		
        while($row = mysql_fetch_array($result)) 
        { 
		$el = $row['elective_of']!=""?"(".getSubjName($row['elective_of']).")":"";
		
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>" > 
                <td><?=getSubjCode($row["subject_id"])?></td>
                <td><?=getSubjName($row["subject_id"]).$el?></td>
                <?php
				$query_period = mysql_query($sql_period);
				$period_ctr = 1;
				while($row_period = mysql_fetch_array($query_period))
				{
					
				?>
                <td <?=checkIfGradeIsPass(getStudentGradePerPeriod($row["id"],$row_period['id'], $id))==='N'?'style="color:red"':''?>>
				<?=getStudentGradePerPeriod($row["id"],$row_period['id'], $id)?>
                </td>
              	<?php
				$period_ctr++;
				}
				
				$f_grade = getStudentFinalGrade($id,$row['schedule_id'],$term_id);
				if(checkIfSubjectDroppedByTerm($id,$row['id'],$term_id))
					{
				?>
                
              <td style="color:red"><?=getSubjectDropped($id,$row['id'],$term_id)=='D'?'DROPPED':'FAILED'?></td>
                <td style="color:red"><?=getSubjectDropped($id,$row['id'],$term_id)=='D'?'0.0':'5.0'?></td>
                
				<?php	
					}else if(checkIfSubjectINCByTerm($id,$row['id'],$term_id)){
				?>
                    <td style="color:red">INC</td>
                	<td style="color:red">0.0</td>    
             <?php
					}else{
				?>
                <td><?=$f_grade?></td>
                <td <?=checkIfGradeIsPass($f_grade)==='N'?'style="color:red"':''?>>
                    <?php
						
                        if($f_grade != '')
                        {
                            echo getGradeConversionGrade2($id,$row['schedule_id'],$term_id);
                        }
                        else
                        {
                            echo '0.0';
                        }
						
                    ?>  				
                </td>
                <?php } ?>
            </tr>
        <?php 
            $x++; 
			        
        }
        ?>
        
        <tr>
        	<td colspan="7">General Average : <?=@round(getStudentAverage($term_id,$id),2).' ('.@getAverageConversion(getStudentAverage($term_id,$id),$id,$term_id).' )'?></td>
            </tr>
        
    </table> 
<?php
}
else
{
	echo '<div>No records found.</div>';
}
?>
</div> 
</div> 
</div> 
</div><!-- #lookup_content -->
<?php
}
?>