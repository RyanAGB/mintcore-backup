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
	
	$comp = $_REQUEST['comp'];
	$prof_id = $_REQUEST['prof_id'];	
	$sched_id = $_REQUEST['sched_id'];
	$term_id = $_REQUEST['filter_schoolterm'];		
?>
<script type="text/javascript">
$(function(){
	$('#save').click(function(){
		$('#action').val('save');
		$("form").submit();
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
	if($('#page').val() != '' && $('#page').val() != undefined)
	{
		param = param + '&pageNum=' + $('#page').val();
	}
	if($('#filter_dept').val() != '' && $('#filter_dept').val() != undefined )
	{
		param = param + '&filterdept=' + $('#filter_dept').val();
	}
	if($('#schoolterm_id').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#schoolterm_id').val();
	}
	param = param + '&comp=<?=$comp?>';
	param = param + '&prof_id=<?=$prof_id?>';
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_professor_encode_grade_list.php?list_rows=10' + param, null);
}	

function onlyNumbers(evt)
{
	var e = event || evt; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;

	if (charCode != 42 && (charCode > 31 && (charCode < 48 || charCode > 57)))
	{
		alert("Please input numeric characters only.");
		return false;
	}
	else
	{
		return true;
	}

}

</script>

<div id="print_div">
<div id="printable">
<table width=100% style=" font-family:Arial; font-size:12px;">

<tr>
  <td  style="font-size:12px; font-weight:bold; padding-top:20px;">
  <table cellspacing="5">
      <tr>
        <td width="100" style='font-weight:bold' valign="top">Professor Name:</td>
        <td width="250"><?=getProfessorFullName($prof_id)?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Employee No.:</td>
        <td><?=getEmployeeNumber($prof_id)?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Section</td>
        <td><?=getSectionNo($sched_id)?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Subject</td>
        <td><?=getSubjNameBySchedId($sched_id)?></td>
      </tr>
    </table>
  </td>
</tr>
</table>

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
			
			$sql_period = "SELECT * FROM tbl_school_year_period WHERE 
						term_id=".$term_id." AND 
						start_of_submission <= '" .  date("Y-m-d") . "'
						ORDER BY period_order";						
			$query_period = mysql_query($sql_period);
			while($row_period = mysql_fetch_array($query_period))
			{
			?>
                <th class="col_100">
                <input name="period_id[]" id="period_id_<?=$row_period["id"]?>" type="hidden" value="<?=$row_period["id"]?>" />
				<?=$row_period['period_name']?></th>
                <th class="col_100">
				Alteration</th>                
			<?php
			}
			?>  
            <th class="col_100">Grade</th>    
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
				$query_period = mysql_query($sql_period);
				$period_ctr = 1;
				while($row_period = mysql_fetch_array($query_period))
				{
				
				?>
                <td>
				<?php 
					//$period_ave =  getStudentNotAlteredFinalGradePerPeriod($row["student_id"],$sched_id,$row_period['id']);
					
					$period_ave = getStudentNotAlteredFinalGradePerPeriod($row["student_id"],$sched_id,$row_period['id']);
					
					echo $period_ave != '' ? $period_ave : '0.0';
					//checkIfSubjectDropped($row["student_id"],$sched_id)
				?>
                </td>
                <td><input name="grade_<?=$row["student_id"]?>[]" id="grade_<?=$row["student_id"].'_'.$period_ctr?>" type="text" class="txt_50" value="<?=getStudentAlteredFinalGradePerPeriod($row["student_id"],$sched_id,$row_period['id'])?>" maxlength="5"/></td>                
              	<?php
				//onkeypress="return onlyNumbers();"
				
				$period_ctr++;
				}
				?>
                <th>
                    <?php
						$f_grade = getStudentFinalGrade($row["student_id"],$sched_id,$term_id);
                        if($f_grade != '')
                        {
                            echo number_format($f_grade,1,'.','');
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
                <td colspan="10">No records found.</td>    
            </tr>        
        <?php
		}
		?>
    </table>
    <input type="hidden" name="term_id" id="term_id" value="<?=CURRENT_TERM_ID?>" />
    <input type="hidden" name="sched_id" id="sched_id" value="<?=$sched_id?>" />
    <input type="hidden" name="subject_id" id="subject_id" value="<?=getSchedSubjectId($sched_id)?>" />
    <input type="hidden" name="prof_id" id="prof_id" value="<?=$prof_id?>" />
    <input type="hidden" name="comp" id="comp" value="<?=$_REQUEST['comp']?>" />
    
    </div>
    </div>
    
    <div class="btn_container">
        <p class="button_container">
        <?php
		if(mysql_num_rows($query_period)>0)
		{
		?>
            <a href="#" class="button" title="Submit" id="save"><span>Save</span></a>
        <?php } ?> 
            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>           
        </p> 
    </div>   
    <p id="formbottom"></p>
