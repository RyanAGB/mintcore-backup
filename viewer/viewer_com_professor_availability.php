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
	$sql = "SELECT * FROM tbl_employee WHERE id = $id";						
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	
?>

<table width=100% style=" font-family:Arial; font-size:12px;">
<tr>
  <td  style="font-size:12px; font-weight:bold; padding-top:20px;">
  <table cellspacing="5">
      <tr>
        <td width="45%" style='font-weight:bold' valign="top">Employee Name:</td>
        <td width="55%"><?=$row['lastname'].", " . $row['firstname'] ." " . $row['middlename']?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Employee No.:</td>
        <td><?=$row['emp_id_number']?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Department:</td>
        <td><?=getDeptName($row['department_id'])?></td>
      </tr>
    </table>
  </td>
  <td align="right" valign="bottom" style="padding-top:20px;"><?=date("M/d/Y") ?>   </td>
</tr>
<tr>
    <td colspan=2 style="border-top:1px solid #333;">&nbsp;</td>
</tr>
</table>


<div id="lookup_content">

    <table class="fieldsetList">     
        <tr>
          <th class="col_70">Section</th>
          <th class="col_250">Subject</th>
          <th class="col_70">Room</th>
          <th class="col_100">Time</th>    
          <th class="col_70">Days</th>                            
        </tr>
		<?php
		$ctr = 1;
        $sql_sched = "SELECT * FROM tbl_schedule WHERE employee_id = $id";						
        $query_sched = mysql_query($sql_sched);
        while($row_sched = mysql_fetch_array($query_sched))
        {
        ?>
            <tr class="<?=($ctr%2==0)?"":"highlight";?>"> 
                <td class="col_70"><?=$row_sched['section_no']?></td>
                <td><?=getSubjName($row_sched["subject_id"])?></td>
                <td><?=getRoomNo($row_sched["room_id"])?></td>
                <td><?=$row_sched["time_from"] .'-'.$row_sched["time_to"] ?></td>
                <td><?=getScheduleDays($row_sched["id"])?></td>                          
            </tr>
        <?php
		$ctr++;
        }
        ?>
    </table>
</div>
<?php
}
?>