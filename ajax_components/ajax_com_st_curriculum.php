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
	
	if(USER_IS_LOGGED != '1')
	{
		header('Location: ../index.php');
	}
	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}
?>
	<script type="text/javascript">
	$(function(){
		
		$('#print').click(function() {
			var w=window.open();
			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
			w.document.write($('#printable').html());
			w.document.close();
			w.focus();
			w.print();
			//w.close()
			return false;
		});
		
	});
</script>
<?php
	
	$sql = "SELECT * FROM tbl_student  WHERE id = ".USER_STUDENT_ID;
		$query = mysql_query($sql);
		$row =mysql_fetch_array($query);		
		
		$sql_dis = "SELECT * FROM tbl_curriculum WHERE id = ".$row['curriculum_id'];
		$result_dis = mysql_query($sql_dis);
		$row_dis = mysql_fetch_array($result_dis);
		$no_year = $row_dis['no_of_years'];
		$no_term = $row_dis['term_per_year'];
		
		$sql_subj = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = ".$row['curriculum_id'];
		$query_subj = mysql_query($sql_subj);
		$num = mysql_num_rows($query_subj);
                 
 $ctr = 1;
 	?>	 
		<table style="padding:10px">    
		<tr>
		<td style=" font-weight:bold; font-size:12px">&nbsp;Curriculum:</td>
		<td style=" font-size:12px"><?=$row_dis['curriculum_code']?></td>
		</tr>
        <tr>
		<td style=" font-weight:bold; font-size:12px">&nbsp;Course:</td>
		<td style=" font-size:12px"><?=getCourseName($row['course_id'])?></td>
		</tr>
		</table>
		<p>
        <?php
     for($ctr_year = 1; $ctr_year<= $no_year; $ctr_year++){
         for($ctr_terms = 1; $ctr_terms<= $no_term; $ctr_terms++){
		?>
         <table class="listview">     
         <tr>
         <th class="col_150" colspan="3"><?=getYearLevel($ctr_year).' ( '.getSemesterInWord($ctr_terms).' )'?></th>
 		</tr>
		<?php	
		 $sql_sub = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = ".$row['curriculum_id']." AND year_level = ".$ctr_year." AND term = ".$ctr_terms;
		$query_sub = mysql_query($sql_sub); 
		?>
		  <tr class="'.$class.'">
          <td width="150"><strong>Subject Code</td>
		  <td><strong>Subject Name</td>
		  <td width="110"><strong>Status</td>
		  </tr>
		<?php
            while($row_dis = mysql_fetch_array($query_sub)) 
            { 
		?>
				<tr class="'.$class.'">
 		 		<td><?=getSubjCode($row_dis['subject_id'])?></td>
				<td><?=getSubjName($row_dis['subject_id'])?></td>
             	<td><?php
                if(checkIfstudentFinishedSubject(USER_STUDENT_ID,$row_dis['subject_id'],$row_dis['id'])===false && !checkIfstudentFailedSubject(USER_STUDENT_ID,$row_dis['subject_id']))
				{
				echo'Completed';
				}
				else if(checkIfstudentFailedSubject(USER_STUDENT_ID,$row_dis['subject_id']))
				{
				echo 'Failed';
				}
				else
				{
				echo 'Not yet taken';
				}
				?></td>
            <?php    
               $ctr++;
			   }  
			}
		}  
		?>     
