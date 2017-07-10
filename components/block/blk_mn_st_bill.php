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

if(!isset($_REQUEST['comp']))
{
include_once("../../config.php");
include_once("../../includes/functions.php");
include_once("../../includes/common.php");
}
	
if(USER_IS_LOGGED != '1')
	{
		header('Location: ../../index.php');
	}
	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../../forbid.html');
	}

?>

<script type="text/javascript">

function clearTabs()
{
	$('ul.tabs li').attr('class',''); // clear all active
}



</script>

<?php
if($err_msg != '')
{
?>
    <p class="alert">
        <span class="txt"><span class="icon"></span><strong>Alert:</strong> <?=$err_msg?></span>
        <a href="#" class="close" title="Close"><span class="bg"></span>Close</a>
    </p>
<?php
}
?>

<h2><?=$page_title?></h2>
<ul class="tabs">
<!--<li id="college_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="College List"><span>College List</span></a></li>
<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit Item"><span>Edit Item</span></a></li>
-->
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">
<div class="box" id="box_container">
<?php

	//$arrStudYrPeriod = getStudentNextYearLevelAndPeriod(USER_STUDENT_ID,USER_CURRICULUM_ID);
	//$studentYearLvl = $arrStudYrPeriod[0];
	//$studentYearLvlPeriod = $arrStudYrPeriod[1];
	
	

	 $sql_fee = "SELECT * 
			FROM tbl_school_fee  syf
			LEFT JOIN tbl_school_year sy
			ON sy.id = syf.school_year_id
			WHERE is_current_sy = 'Y'
			AND term_id = '".  CURRENT_TERM_ID." '";
	
	$result_fee = mysql_query($sql_fee);
	
	$arrFees = array();
	$result_1 = array();
	$feePerUnitLec = 0;
	$feePerUnitLab = 0;
	$feeRoom = 0;
	while ($rowFee = mysql_fetch_array($result_fee) ) {
		$result_1[] = $rowFee;
		$mcFee = array();
		 
		foreach($result_1 as $key => $val) {
			if ($val["fee_type"]=="perunitlec") {
				$feePerUnitLec = $val["amount"];
			} else if ($val["fee_type"]=="perunitlab") {
				$feePerUnitLab = $val["amount"];
			} else if ($val["fee_type"]=="room") {
				$feeRoom = $val["amount"];
			} else if ($val["fee_type"]=="mc") {
				$mcFee[count($mcFee)] = array($val["fee_name"], $val["amount"]);
			}
	
		}
		
		$start_year = $rowFee["start_year"];		
		$end_year = $rowFee["end_year"];
		
	}
	
$numberOfLecUnit = 0;
$numberOfLabUnit = 0;

	$sql = "SELECT srs.schedule_id, 
					s.subject_id, s.room_id, s.section_no, 
					s.monday, s.tuesday, s.wednesday, s.thursday, 
					s.friday, s.saturday, s.sunday, 
					c.term,c.units, r.room_no, b.building_name, s.id,
					sub.subject_code, sub.subject_name, sub.subject_type
					FROM tbl_student_reserve_subject srs  
					LEFT JOIN tbl_schedule s 
					ON srs.schedule_id = s.id
					LEFT JOIN tbl_curriculum_subject c
					ON c.subject_id = s.subject_id
					LEFT JOIN tbl_room r
					ON r.id = s.room_id
					LEFT JOIN tbl_building b
					ON r.building_id = b.id
					LEFT JOIN tbl_subject sub
					ON sub.id = s.subject_id
					WHERE student_id = '".USER_STUDENT_ID. "'
					AND c.curriculum_id = '".USER_CURRICULUM_ID."'";
	
	$result = mysql_query($sql); 
	
		$totalUnits = 0;
		 
        if (mysql_num_rows($result) > 0 )
        {
            $x = 0;
        ?>
	<table class="listview">      
          <tr><input type="hidden" name="cid" 	id="cid" 	value="<?=USER_CURRICULUM_ID?>" />
			  <input type="hidden" name="stdid"	id="stdid"	value="<?=USER_STUDENT_ID?>" />
              <th class="col_20"><input type="checkbox" name="id_all" id="id_all" value="" /></th>
              <th class="col_150"><a href="#list" onclick="sortList('subject_code');">School Year</a></th>
              <th class="col_150"><a href="#list" onclick="sortList('subject_name');">Term</a></th>
			  <th class="col_150"><a href="#list" onclick="sortList('units');">Total Fee</a></th>
	
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
				$term = $row["term"];
				if($row["subject_type"]=="Lec") {
					$numberOfLecUnit += $row["units"];
				} else if ($row["subject_type"]=="Lab") {
					$numberOfLabUnit += $row["units"];
				}
			
			}
				
				$totalTuitionFee = 0;
				
				$totalTuitionFee += ($numberOfLecUnit * $feePerUnitLec) + ($numberOfLabUnit * $feePerUnitLab);
				
				if (count($mcFee) > 0) {
					foreach($mcFee as $key => $val) {
					 $totalTuitionFee += $val[1];
					}
				
				}
			
			
			
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td ><!--<input type="checkbox" name="id" id="id_<?=$row['id']?>" value="<?=$row['id']?>" /></td> -->
                <td><?=$start_year . " - " . $end_year?></td> 
				<td><?=getSchoolTerm($term)?></td>
				<td><?=$totalTuitionFee?></td>
			
				
         
            </tr>
			 <?php           
           
        }
        else 
        {
                echo "No records found";
        }
        ?>
		</table> 
		
<p id="formbottom"></p>
</div>
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>