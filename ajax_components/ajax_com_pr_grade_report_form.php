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
?>
<script type="text/javascript">
$(function(){
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
		sectionList();
	});	

});	

function sectionList(pageNum)
{
	var param = '';
	if(pageNum != '' && pageNum != undefined)
	{
		param = param + '&pageNum=' + pageNum;
	}
	
	if($('#filter_schoolterm').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#filter_schoolterm').val();
	}

	$('#box_container').load('ajax_components/ajax_com_pr_grade_report.php?list_rows=10' + param, null);
}	

</script>
<h3><strong>Student List</strong></h3>
    <table class="listview">    
        <tr>
          <th class="col_20">#</th>
          <th class="col_100">Student No</th>
          <th class="col_200">Full name</th>
			<?php
			
			$sql_sheet = "SELECT * FROM tbl_gradesheet WHERE 
						schedule_id=".$sched_id;						
			$query_sheet = mysql_query($sql_sheet);
			while($row_sheet = mysql_fetch_array($query_sheet))
			{
			?>
                <th><?=$sched_id?>
                <input name="sheet_id[]" id="sheet_id<?=$row_sheet["id"]?>" type="hidden" value="<?=$row_sheet["id"]?>" />
				<?=ucfirst($row_sheet['label'])?></th>
			<?php
			}
			?>     
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
				$query_sheet= mysql_query($sql_sheet);
				$sheet_ctr = 1;
				while($row_sheet = mysql_fetch_array($query_sheet))
				{
				
				?>
                <td>
                <?php
                if(checkIfGradeSheetIslocked($sched_id)){
					echo getStudentGradeInGradeSheet($sched_id,$row_sheet['id'],$row["student_id"]);
				}
				else
				{
				?>
                <input name="grade_<?=$row["student_id"]?>[]" id="grade_<?=$row["student_id"].'_'.$sheet_ctr?>" type="text" class="txt_50" value="<?=getStudentGradeInGradeSheet($sched_id,$row_sheet['id'],$row["student_id"])?>" />
                <?php
				}
				?>
                </td>
              	<?php
				$sheet_ctr++;
				}
				?>
  
            </tr>
        <?php
			$ctr++;
			}
		}
		else
		{
        ?>
            <tr> 
                <td colspan="6">No records found.</td>    
            </tr>        
        <?php
		}
		?>
    </table>
    <input type="hidden" name="term_id" id="term_id" value="<?=CURRENT_TERM_ID?>" />
    <input type="hidden" name="sched_id" id="sched_id" value="<?=$sched_id?>" />
    <input type="hidden" name="prof_id" id="prof_id" value="<?=USER_EMP_ID?>" />
    <div class="btn_container">
        <p class="button_container">
        <?php
            if(!checkIfGradeSheetIslocked($sched_id)){
		?>
            	<a href="#" class="button" title="Submit" id="save"><span>Save</span></a>
	            <a href="#" class="button" title="Submit" id="save_lock"><span>Submit and Lock Grade</span></a>
        <?php
			}
		?>                
            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>            
        </p> 
    </div>   
    <p id="formbottom"></p>
