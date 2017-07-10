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
	
	$term_id = $_REQUEST['term_id'];
?>

<script type="text/javascript">
$(document).ready(function(){  
	$('#filter_schoolterm').change(function(){
		var param = '';
		param = param + '&term_id=' + $('#filter_schoolterm').val();
		param = param + '&comp=' + $('#comp').val();
		$('#filter_termId').val($('#filter_schoolterm').val());
		$('#box_container').load('ajax_components/ajax_com_school_period.php?list_rows=10' + param, null);
	});	
});
</script>
              

<div class="filter">
    Select School Term&nbsp;&nbsp;
    <select name="filter_schoolterm" id="filter_schoolterm" class="txt_200">
    	<!--<option value="" selected="selected">Select School Year Term</option>!-->
    	<?=generateSchoolTerms($term_id)?>
    </select>
</div>
        


<table class="listview">      
  <tr>
      <th class="col_20">&nbsp;</th>
    <th class="col_150"><a href="#list" onclick="sortList('period_name');">Period Name</a></th>
      <th class="col_250"><a href="#list" onclick="sortList('start_of_submission');">Start of Grade Submission</a></th>
      <th class="col_250"><a href="#list" onclick="sortList('end_of_submission');">End of Grade Submission</a></th>
      <th class="col_100"><a href="#list" onclick="sortList('percentage');">Percentage</a></th>
      <th class="col_100">Is Current</th>
      <th class="col_50">Action</th>
  </tr>
<?php
	$sql = "SELECT * FROM tbl_school_year_period  WHERE term_id = '$term_id' order by period_order";
	$query = mysql_query($sql);
	$ctr = mysql_num_rows($query);
	if($ctr >0)
	{
		$cnt = 1;
		while($row = mysql_fetch_array($query)) 
		{ 
?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td ><input type="checkbox" name="id" id="id_<?=$row['id']?>" value="<?=$row['id']?>" /></td>
                <td><a href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" class="edtlnk" val="<?=$row["id"]?>"><?=$row["period_name"]?></a></td>
                <?php			
                	$sub_start = explode ('-',$row['start_of_submission']);		
					$start_year = $sub_start['0'];
					$start_day = $sub_start['1'];
					$start_month = $sub_start['2'];
					
					$sub_end = explode ('-',$row['end_of_submission']);		
					$end_year = $sub_end['0'];
					$end_day = $sub_end['1'];
					$end_month = $sub_end['2'];
				?> 
                <td><?=date("M. d, Y", mktime(0, 0, 0, $start_day, $start_month, $start_year))?></td>
                <td><?=date("M. d, Y", mktime(0, 0, 0, $end_day, $end_month, $end_year))?></td>
                <td><?=$row["percentage"]?>%</td>
                <td>
					<ul>
                        <li>
                        <?
						if($row['is_current']=='Y' && $ctr != $cnt ){
						?>
                       	  <a class="checkmark" href="#" onclick="javascript:alert('You cannot unset this period.'); return false;"></a>
                        <?php
                        }
						else if($row['is_current']=='Y' && $ctr == $cnt)
						{
						?>
                          <a class="checkmark" href="#" onclick="javascript:if(confirm('Are you sure you want to set this as current period?'))lnk_unpublishItem('id_<?=$row['id']?>'); else return false;" title="click to set as current"></a>
                        <?php
						}
						else
						{
						?>
                          <a class="xmark" href="#" onclick="javascript:if(confirm('Are you sure you want to set this as current period?'))lnk_publishItem('id_<?=$row['id']?>'); else return false;" title="click to set as current"></a>
                        <?php
						}
						?>
                        </li>
                    </ul>
                </td>                
                <td class="action">
                    <ul>
                        <li><a class="edit" href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" title="edit"></a></li>
                    </ul>
                </td>
            </tr>
<?php 
		$cnt++;          
		} // end of while
	}
	else
	{
?>
		<tr>
        	<td colspan="7">No period is found under this term.</td>
        </tr>
<?php
	}
?>
</table> 
<p id="formbottom"></p>

