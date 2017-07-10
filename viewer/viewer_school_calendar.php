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

$date = $_REQUEST['date'];
$sched_id = getSchedIdCalendar($date);

?>
<div id="lookup_content">
<div class="body-container">
<div class="content-container">

<div class="content-wrapper-wholeBorder">
<?php

	$sql = "SELECT * FROM tbl_school_calendar WHERE (date_from <='$date' AND date_to >='$date')";
	$query = mysql_query($sql);

while($row = mysql_fetch_array($query))
{
	if(in_array($row['schedule_id'],$sched_id))
	{
	
		if($row['schedule_id'] == 0)
		{
			$sub = 'All';
			$post = getEmployeeFullNameByUserId($row['created_by']);
		}
		else
		{
			$sub = getSubjNameBySchedId($row['schedule_id']).' ('.getSubjCodeBySchedId($row['schedule_id']).')';
			$post = getEmployeeFullNameBySchedId($row['schedule_id']);
		}
?>
<table width="100%" cellpadding="10" style=" font-family:Arial; font-size:12px;">
<tr>
    <td colspan="2"  style="font-size:15px; font-weight:bold; padding-top:20px;"><?=ucwords($row['title'])?>    
      <!-- 20100214 Feb/14/2010--></td>
    <td align=right style="padding-top:20px;"><?=changeDateFormat($row['date_from'])?> to <?=changeDateFormat($row['date_to'])?></td>
</tr>
<tr>
    <td><table width=100% style=" font-family:Arial; font-size:12px;">
			<tr>
				<td style="font-size:12px; font-weight:bold;">
					Schedule:
				</td>
				<td>
					<?=$sub?>
				</td>
			</tr>
			<tr>
				<td style="font-size:12px; font-weight:bold;">Posted By:</td>
                <td><?=$post?></td>
			</tr>
		</table></td>
</tr>
<tr>
    <td colspan=3 style="border-top:1px solid #333;">&nbsp;</td>
</tr>

<tr>
    <td colspan=3><?=nl2br($row['description'])?></td>
</tr>
<tr>
    <td colspan="3">&nbsp;</td>
</tr>
</table>
<p>&nbsp;</p>
<?php
	}
}
?>
    </div>
    </div>
    </div>
</div>
