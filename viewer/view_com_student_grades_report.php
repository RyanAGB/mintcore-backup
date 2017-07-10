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
	});
</script>

<?php
	$id = $_REQUEST['id'];
	 $sql = "SELECT * FROM tbl_student WHERE id =".$id;						
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	
	$sql_student = "SELECT 
						a.schedule_id,
						b.id,
						b.subject_id,
						b.room_id,
						b.employee_id 
					FROM 
						tbl_student_schedule a,
						tbl_schedule b 
					WHERE 
						a.schedule_id = b.id AND
						a.student_id = ".$id;
	$query_student = mysql_query($sql_student);
	
?>

<table width="100%">
  <tr>
    <td width="18%" valign="top" style='font-weight:bold'>Student Name:</td>
    <td width="82%"><?=$row['lastname']. ", " . $row['firstname'] ." " . $row['middlename']?></td>
  </tr>
  <tr>
    <td style='font-weight:bold' valign="top">Student Number:</td>
    <td><?=$row['student_number']?></td>
  </tr>
  <tr>
    <td style='font-weight:bold' valign="top">Course:</td>
    <td><?=getCourseName($row['course_id'])?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
</tr>
  <tr>
    <td colspan=2 style="border-top:1px solid #333;">&nbsp;</td>
</tr>
<tr><td style='font-weight:bolder; font-size:12px;' colspan="2" align="right"><a target="_blank" href="ajax_components/print_grade_info.php?id=<?=$id?>" >PRINT THIS PAGE</a></td></tr>
</table>
 
<div id="lookup_content">

    <table class="fieldsetList">      
        <tr>
            <th class="col1_150">Code</th>  
            <th class="col1_150">Subject Name</th>   
			<?php
            $sql_period = "SELECT * FROM tbl_school_year_period WHERE 
                        school_year_id=".CURRENT_SY_ID." AND 
                        term_id=".CURRENT_TERM_ID." ORDER BY period_order";						
            $query_period = mysql_query($sql_period);
            while($row_period = mysql_fetch_array($query_period))
            {
            ?>
                <th class="col1_50"><?=$row_period['period_name']?></th>
            <?php
            }
            ?>  
        </tr>
        <?php
        $x = 1;
        while($rowstud = mysql_fetch_array($query_student)) 
        { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>"> 
                <td><?=getSubjCode($rowstud["subject_id"])?></td>
                <td><?=getSubjName($rowstud["subject_id"])?></td>
                <?php
				$query_period = mysql_query($sql_period);
				$period_ctr = 1;
				while($row_period = mysql_fetch_array($query_period))
				{
				?>
                <td><?=getStudentGradePerPeriod($rowstud["id"],$row_period['id'], $row['id'])?></td>
              	<?php
				$period_ctr++;
				}
				?>
            </tr>
        <?php 
            $x++;          
        }
        ?>
    </table>
   <?php
   }
   ?>