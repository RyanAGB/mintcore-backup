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

$sched = $_REQUEST['sched'];
$prof = $_REQUEST['prof'];

$sqlgrd = "SELECT * FROM tbl_employee WHERE id=".$prof;
$querygrd = mysql_query($sqlgrd);
$rowgrd = mysql_fetch_array($querygrd);


?>
<div id="viewer_content">

        <table border="0" cellspacing="0" cellpadding="0" class="classic">	

              <tr>
            <td style="font-size:12px; font-weight:bold; padding:5px">Employee Name:&nbsp;
            <?=$rowgrd['lastname'].", " . $rowgrd['firstname'] ." " . $rowgrd['middlename']?></td>
          </tr>
          <tr>
            <td style="font-size:12px; font-weight:bold; padding:5px">Employee No.:&nbsp;
            <?=$rowgrd['emp_id_number']?></td>
          </tr>
          <tr>
            <td style="font-size:12px; font-weight:bold; padding:5px">Department:&nbsp;
            <?=getDeptName($rowgrd['department_id'])?></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
            <td width="600">
                <table border="0" cellspacing="0" cellpadding="0" id="classic">	
                  <tr>
		
          <th class="col_200">Student name</th>
			<?php
			
			$sql_period = "SELECT * FROM tbl_school_year_period WHERE 
						term_id=".CURRENT_TERM_ID." AND 
						start_of_submission < '" .  date("Y-m-d") . "'
						ORDER BY period_order";						
			$query_period = mysql_query($sql_period);
			while($row_period = mysql_fetch_array($query_period))
			{
			?>
                <th class="col_100">
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
        $sql = "SELECT * FROM tbl_student_schedule WHERE schedule_id = ".$sched;						
        $query = mysql_query($sql);
		$row_ctr = mysql_num_rows($query);
		if($row_ctr > 0)
		{
			while($row = mysql_fetch_array($query))
			{
        ?>
            <tr class="<?=($ctr%2==0)?"":"highlight";?>"> 

                <td><?=getStudentFullName($row["student_id"])?></td>
                <?php
				$query_period = mysql_query($sql_period);
				$period_ctr = 1;
				while($row_period = mysql_fetch_array($query_period))
				{
				
				?>
                <td>
				<?php 
					$period_ave = getStudentNotAlteredFinalGradePerPeriod($row["student_id"],$sched,$row_period['id']);
					echo $period_ave != '' ? $period_ave : '0.0';
				?>
                </td>
                <td><?=getStudentAlteredFinalGradePerPeriod($row["student_id"],$sched,$row_period['id']);?></td>                
              	<?php
				$period_ctr++;
				}
				?>
                <th>
                    <?php
						$f_grade = getStudentFinalGrade($row["student_id"],$sched,CURRENT_TERM_ID);
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
                <td colspan="10">No Student Enrolled.</td>    
            </tr>        
        <?php
		}
		?>
                      </tr>
                         
                </table>            
            </td>
          </tr>
          <tr>
            <td width="600">&nbsp;</td>
          </tr>          
        <?php
		/*}
		}
		else
		{
			echo '<div id="message_container"><h4>No Schedule found</h4></div>';
		}*/
		?>
        </table>
</div>
<?php
}
?>