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
	header("Content-type: text/html; charset=iso-8859-1");

	$prof_id = USER_EMP_ID;	
	$sched_id = $_REQUEST['sched_id'];
	$period_id = $_REQUEST['period_id']!=''?$_REQUEST['period_id']:CURRENT_PERIOD_ID;
?>
<script type="text/javascript">
$(function(){

	$('#filter_period').change(function(){
		var param = '';
		if($('#comp').val() != '' && $('#comp').val() != undefined )
		{
			param = param + '&comp=' + $('#comp').val() + param;
		}
		param = param + '&prof_id=<?=$prof_id?>&sched_id=<?=$sched_id?>';
		if($(this).val() != '')
		{
			param = param + '&period_id=' + $(this).val() ;
		}

		$('#box_container').html(loading);
		$('#box_container').load('ajax_components/ajax_com_pr_encode_grade_form.php?list_rows=10' + param, null);
	});		
	
	$('#save').click(function(){
		//$('#action').val('save');
		//$("form").submit();
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
	
	$('#print').click(function() {
			var w=window.open();
			w.document.write($('#print_div').html());
			w.document.close();
			w.focus();
			w.print();
			w.close();
			return false;
		});

});	

function sectionList(pageNum)
{
	var param = '';
	if(pageNum != '' && pageNum != undefined)
	{
		param = param + '&pageNum=' + pageNum;
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
	if($('#filter_schoolterm').val() != '' )
	{
		param = param + '&filter_schoolterm=' + <?=CURRENT_TERM_ID?>;
	}

	$('#box_container').load('ajax_components/ajax_com_pr_encode_grade.php?list_rows=10' + param, null);
}	

function onlyNumbers(evt)
{
	var e = event || evt; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;

	if (charCode != 42 && (charCode > 31 && (charCode < 48 || charCode > 57)))
	{
		alert("Accept numbers only.");
		return false;
	}
	else
	{
		return true;
	}

}
</script>

<?php
$disabled = ''; 
if($period_id != '')
{
	if(checkIfSubmissionGradeIsOpenPerPeriod($period_id))
	{
		$disabled ='';
	}
	else
	{
		$disabled =' disabled="disabled"';	
	}
	
?>
<div id="print_div">
<div id="printable">
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
        <td><?=getSYandTerm(CURRENT_TERM_ID)?></td>
      </tr>
	  <?php
	  if(!checkIfSubmissionGradeIsOpenPerPeriod($period_id))
	  {
	  ?>      
      <tr>
        <td style='font-weight:bold' valign="top">Remarks:</td>
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

<?php
/* TEMPORARY DISABLED - MKT
$sql_sheet = "SELECT * FROM tbl_gradesheet WHERE 
                            schedule_id= " . $sched_id . " AND school_yr_period_id = " . $period_id;						
                $query_sheet = mysql_query($sql_sheet);

	if(mysql_num_rows($query_sheet)>0)
	{*/
?>
    Period&nbsp;&nbsp;
    <select name="filter_period" id="filter_period" class="txt_200">
    	<?=generatePeriodPerTerm($period_id==''?CURRENT_PERIOD_ID:$period_id)?>
    </select>
</div>
        <table class="listview">  
        <tr>
        <td>
        <a class="viewer_print" href="#" id="print" title="print"></a>
        </td>
        </tr>    
            <tr>
              <th class="col_20">#</th>
              <th class="col_100">Student No</th>
              <th class="col_200">Full name</th>
                <?php
                
               /* TEMPORARY DISABLED - MKT
                while($row_sheet = mysql_fetch_array($query_sheet))
                {
                ?>
                    <th>
                    <input name="sheet_id[]" id="sheet_id<?=$row_sheet["id"]?>" type="hidden" value="<?=$row_sheet["id"]?>"/>
                    <?=ucfirst($row_sheet['label']).' ('.$row_sheet['percentage'].'% )'?></th>
                <?php
                }*/
                ?>
               <th>
                    <?=getPeriodName($period_id)?></th>
                <th>Average</th> 
                <th>If Altered</th>  
               <th>Remarks (INC-Incomplete,D-Drop,W-Withdraw)</th>  

            </tr>
            <?php
            $ctr = 1;
            $sql = "SELECT s.* FROM tbl_student_schedule s,tbl_student st WHERE st.id=s.student_id AND s.schedule_id = $sched_id ORDER BY st.lastname";						
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
                   // $query_sheet= mysql_query($sql_sheet);
					//$ctr_sheet = mysql_num_rows($query_sheet);
					if(getStudentAlteredFinalGradePerPeriod($row["student_id"],$sched_id,$period_id))
					{
					?>
                         <td colspan="<?=$ctr_sheet?>" align="center">Grade has been altered.</td>
                    <?php
					}
					else
					{
						//$sheet_ctr = 1;
						//while($row_sheet = mysql_fetch_array($query_sheet))
						{
						
                    ?>
                            <td>
                            <?php
                           /* if(checkIfGradeSheetIslocked($sched_id,$period_id)){
                                echo getStudentGradeInGradeSheet($sched_id,$row_sheet['id'],$row["student_id"]);
							
                            }
                            else
                            {*/
		if(!checkIfSubmissionGradeIsOpenPerPeriod($period_id))
		{
			echo getStudentNotAlteredFinalGradePerPeriod($row["student_id"],$sched_id,$period_id);
		}else{
                            ?>
                            <input name="grade_<?=$row["student_id"]?>[]" id="grade_<?=$row["student_id"].'_'.$sheet_ctr?>" type="text" class="txt_50" value="<?=iconv("UTF-8", "ISO-8859-1//TRANSLIT", getStudentNotAlteredFinalGradePerPeriod($row["student_id"],$sched_id,$period_id))?>"  <?=$disabled?>  maxlength="5"/>
                            <?php
							//onkeypress="return onlyNumbers();"
							
                            }
                            ?>
                            </td>
                    <?php
						$sheet_ctr++;
						}
					}
                    ?>
                    <th>
						<?php
							$ave = getStudentNotAlteredFinalGradePerPeriod($row["student_id"],$sched_id,$period_id);
							if($ave != '')
							{
								echo number_format($ave,1,'.','');
							}
							else
							{
								echo '0.0';
							}
						?>
                    </th>
                    <th>
						<?php
							$altered = getStudentAlteredFinalGradePerPeriod($row["student_id"],$sched_id,$period_id);
							if($altered != '')
							{
								echo number_format($altered,1,'.','');
							}
							else
							{
								echo '0.0';
							}
						?>                    
                    </th>
                    
                    <th> <input name="remarks_<?=$row["student_id"]?>[]" id="remarks_<?=$row["student_id"].'_'.$sheet_ctr?>" type="text" class="txt_100" value=""/></th>
                    
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
        <input type="hidden" name="period_id" id="period_id" value="<?=$period_id?>" />
        <input type="hidden" name="term_id" id="term_id" value="<?=CURRENT_TERM_ID?>" />
        <input type="hidden" name="sched_id" id="sched_id" value="<?=$sched_id?>" />
        <input type="hidden" name="prof_id" id="prof_id" value="<?=USER_EMP_ID?>" />
        <div class="btn_container">
            <p class="button_container">
            <?php
                //if(!checkIfGradeSheetIslocked($sched_id,$period_id) && checkIfSubmissionGradeIsOpenPerPeriod($period_id)){
				if(!checkIfGradeSheetIslocked($sched_id,$period_id) &&checkIfSubmissionGradeIsOpenPerPeriod($period_id)){
            ?>
                    <a href="#" class="button" title="Submit" id="save"><span>Save</span></a>
                    <!--<a href="#" class="button" title="Submit" id="save_lock"><span>Submit and Lock Grade</span></a>!-->
            <?php
                }
			/*}
		else
		{
		 echo 'No GradeSheet Set-Up found. Set-Up Gradesheet First.';
		 ?>
         <label>&nbsp;</label>
		 <?php
		}*/
            ?>                
                <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>            
            </p> 
        </div>
        </div>
        </div>
<?php
	
} // end of main if

?>
<p id="formbottom"></p>
