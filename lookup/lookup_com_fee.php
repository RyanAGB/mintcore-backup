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

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp']))
{
	header('Location: ../forbid.html');
}
else
{
?>
<script type="text/javascript">
	function getselected() {
		var i = $('#cnt').val();
		var j = $('#cnt2').val();
		var valTxt = "";
		var ids = "";
		//var titles = $(this).attr("returnTxt");
		for(var x=1; x<=i; x++) {
			for(var y=1; y<=j; y++) {
			if ($('#chk' + x+'-'+y).attr("checked")) {
				if (ids != "")
					ids += ",";
				if (valTxt != "")
					valTxt+=",";
					//titles += ",";
				ids += $('#chk' + x+'-'+y).val(); 
				valTxt+=$('#returnTxt' + x+'-'+y).val();
			}
		}
		}
		//	alert("value of i: " + i);
		//	alert("fetch ids are: "+ids);
		$('#school_year_level').attr("value", ids);
		$('#school_year_level_id_display').attr("value", valTxt);
			$('#dialog').dialog('close');
			
			
	}
</script>

<?php
	$sql = "SELECT MAX(`year_level`) as yr, MAX(`term`) as term FROM tbl_curriculum_subject";
	$resSkulYr = mysql_query($sql);
	$rowSkulYr = mysql_fetch_array($resSkulYr);
	$maxSkulYr = $rowSkulYr["yr"];
	$maxSkulYrPeriod = $rowSkulYr["term"];
?>

<div id="lookup_content">

      <table class="fieldsetList">        
        <tr>
        <input type="hidden" name="cnt" id="cnt" value="<?=$maxSkulYr?>" />
        <input type="hidden" name="cnt2" id="cnt2" value="<?=$maxSkulYrPeriod?>" />
            <th class="col1_100">&nbsp;</th>
            <th class="col1_350"><a href="#">Year</a></th>  
            <th class="col1_400"><a href="#">Period</a></th>     
        </tr>
        <?php
       for($x = 1;$x <= $maxSkulYr; $x++) {
					$yrname = "";	
					if ($x == 1) {
						$yrname = "1st Year";
					} else if ($x == 2) {
						$yrname = "2nd Year";
					} else if ($x == 3) {
						$yrname = "3rd Year";
					} else if ($x == 4) {
						$yrname = "4th Year";
					} else if ($x == 5) {
						$yrname = "5th Year";
					} 
					for($y = 1;$y <= $maxSkulYrPeriod; $y++) {
					
						$yrpername = "";
						if ($y == 1) {
							$yrpername = "1st Period";
						} else if ($y == 2) {
							$yrpername = "2nd Period";
						} else if ($y == 3) {
							$yrpername = "3rd Period";
						} else if ($y == 4) {
							$yrpername = "4th Period";
						} else if ($y == 5) {
							$yrpername = "5th Period";
						}	
					
						?>
						
            <tr class="<?=($x%2==0)?"":"highlight";?>">
            <td>		
				<input type="hidden" name="returnTxt<?=$x.'-'.$y?>" id="returnTxt<?=$x.'-'.$y?>" value="<?=$yrname." ".$yrpername?>"  />
				<input name="chk<?=$x.'-'.$y?>" type="checkbox" value="<?=$x.'-'.$y?>" id="chk<?=$x.'-'.$y?>"/>
			</td>
            <td><?=$yrname?></td>
            <td><?=$yrpername?></td>
            </tr><?php	
				
				}
				
			}
        ?>
    </table> 
    
</div> <!-- #lookup_content -->
<?php
}
?>