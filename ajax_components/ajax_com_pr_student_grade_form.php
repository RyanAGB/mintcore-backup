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

	$prof_id = USER_EMP_ID;	
	$sched_id = $_REQUEST['sched_id'];
	$period_id = $_REQUEST['period_id']!=''?$_REQUEST['period_id']:CURRENT_PERIOD_ID;
	
	$term_id = $_REQUEST['filter_schoolterm'];	
?>
<script type="text/javascript">
$(function(){

	// Dialog			
	$('#dialog').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Close": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('.schedule').click(function(){
		var param = $(this).attr("returnId");
	
		if($('#filter_schoolterm').val() != '' )
		{
			param = param + '&filter_schoolterm=' + <?=CURRENT_TERM_ID?>;
		}	
				
		$('#dialog').load('viewer/viewer_com_student_grade.php?id='+param, null);
		$('#dialog').dialog('open');
		return false;
	});
	
});	
$(function(){

	$('#filter_period').change(function(){
		var param = '';
		param = param + '&prof_id=<?=$prof_id?>&sched_id=<?=$sched_id?>';
		if($(this).val() != '')
		{
			param = param + '&period_id=' + $(this).val() ;
		}

		$('#box_container').html(loading);
		$('#box_container').load('ajax_components/ajax_com_pr_student_grade_form.php?list_rows=10' + param, null);
	});		
	
	$('#save').click(function(){
		$('#action').val('save');
		$("form").submit();
	});	
	
	$('#save_lock').click(function(){
		if(confirm('Are you sure you want to submit this gradesheet? This cannot be undone.'))
		{	
			$('#action').val('save_lock');
			$("form").submit();
		}
		else
		{
			return false;
		}
	});		
	
	$('#cancel').click(function(pageNum){
		updateList();
	});	

});	

function sectionList(pageNum)
{

	var param = '';
	if(pageNum != '' && pageNum != undefined)
	{
		param = param + '&pageNum=' + pageNum;
	}
	
	if($('#f_term_id').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#f_term_id').val();
	}	
	
	$('#box_container').load('ajax_components/ajax_com_pr_sudent_grade.php?list_rows=10' + param, null);
}	

</script>
<div class="filter">      
    <table class="classic_borderless">
      <tr>
        <td valign="top" style='font-weight:bold'>Subject:</td>
        <td><?=getSubjNameBySchedId($sched_id)?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Section:</td>
        <td><?=getSectionNo($sched_id)?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Schedule:</td>
        <td><?=getScheduleDays($sched_id)?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">School Year:</td>
        <td><?=getSYandTerm($term_id)?></td>
      </tr>
	  <?php
	  if(!checkIfSubmissionGradeIsOpenPerPeriod($period_id))
	  {
	  ?>      
      <tr>
        <td style='font-weight:bold' valign="top">Remarks</td>
        <td>The schedule of the submission of grade for <?=getPeriodName($period_id) . ' is ' . getGradeSubmissionPerPeriod($period_id)?></td>
      </tr>
      <?php
	  }
	  ?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>      
    </table>  

</div>
        <table class="listview">    
            <tr>
              <th class="col_20">#</th>
              <th class="col_70"><a href="#" class="sortBy" returnFilter="student_number">Student Number</th>
              <th class="col_100"><a href="#" class="sortBy" returnFilter="lastname">Full Name</th>
              <?php
				$sql_period = "SELECT * FROM tbl_school_year_period WHERE 
							term_id=".$term_id." AND 
							start_of_submission <= '" .  date("Y-m-d") . "'
							ORDER BY period_order";						
				$query_period = mysql_query($sql_period);
				while($row_period = mysql_fetch_array($query_period))
				{
				?>
					<th class="col_50">
					<input name="period_id[]" id="period_id_<?=$row_period["id"]?>" type="hidden" value="<?=$row_period["id"]?>" />
					<?=$row_period['period_name']?></th>
				<?php
				}
				?>  
				<th class="col_50">Grade</th>    
            </tr>
            <?php
            $ctr = 1;
            $sql = "SELECT * FROM tbl_student_schedule WHERE schedule_id = $sched_id ";						
            $query = mysql_query($sql);
            $row_ctr = mysql_num_rows($query);
            if($row_ctr > 0)
            {
                while($row = mysql_fetch_array($query))
                {
            ?>
                <tr class="<?=($ctr%2==0)?"":"highlight";?>"> 
                    <td>
                    <input name="student_id[]" id="student_id_<?=$row["student_id"]?>" type="hidden" value="<?=$row["student_id"]?>" />
                    <?=$ctr?>
                    </td>
                    <td><?=getStudentNumber($row["student_id"])?></td>
                    <td><?=getStudentFullName($row["student_id"])?></td>
	                <?php
					$query_period = mysql_query($sql_period);
					$period_ctr = 1;
					while($row_period = mysql_fetch_array($query_period))
					{
					
					?>
					<td>
					<?php 
					
						if( getStudentAlteredFinalGradePerPeriod($row["student_id"],$sched_id,$row_period['id']) != '')
						{
							$period_ave = getStudentAlteredFinalGradePerPeriod($row["student_id"],$sched_id,$row_period['id']);
						}
						else
						{
							$period_ave = getStudentNotAlteredFinalGradePerPeriod($row["student_id"],$sched_id,$row_period['id']);
						}
						
						echo $period_ave != '' ? $period_ave : '0.0';
					?>
					</td>
					<?php
					$period_ctr++;
					}
					?>
					<th>
						<?php
							$f_grade = getStudentFinalGrade($row["student_id"],$sched_id,$term_id);
							if($f_grade != '')
							{
								echo getGradeConversionGrade($f_grade);
							}
							else
							{
								echo '0.0';
							}
						?>                    
					</th>
                </tr>
            <?php
                $ctr++;
                }
            }
            else
            {
            ?>
                <tr> 
                    <td colspan="7">No student is enrolled in this section.</td>    
                </tr>        
            <?php
            }
            ?>
        </table>
        <p class="button_container" style="padding:10px">
            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>
        </p>        
        <input type="hidden" name="period_id" id="period_id" value="<?=$period_id?>" />
        <input type="hidden" name="term_id" id="term_id" value="<?=CURRENT_TERM_ID?>" />
        <input type="hidden" name="sched_id" id="sched_id" value="<?=$sched_id?>" />
        <input type="hidden" name="prof_id" id="prof_id" value="<?=USER_EMP_ID?>" />


<p id="formbottom"></p>
<!-- LIST LOOK UP-->
<div id="dialog" title="Student Grade">
    Loading...
</div><!-- #dialog -->
