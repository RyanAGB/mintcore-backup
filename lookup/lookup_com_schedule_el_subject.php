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

$filterfield = $_REQUEST['filterfield'];
$filtervalue = $_REQUEST['filtervalue'];
?>
<script type="text/javascript">
	$(function(){
		$('.selector').click(function() {
			//var id = $(this).attr("val");		
			var valTxt = $(this).attr("returnTxt");
			var valId = $(this).attr("returnId");
			var valElec = $(this).attr("retElec");
			$('#el_subject_id').attr("value", valId);
			$('#el_subject_display').attr("value", valTxt);
			
			$('#el_subject_dialog').dialog('close');			
		});


		$('#filterdepartment2').change(function(){
			//alert('aaaa');
			updateList();
		});


	});


function updateList()
{
		var param = '';
		
		if($('#filterdepartment2').val() != '')
		{
			param = param + '&filterfield=department_id&filtervalue=' + $('#filterdepartment2').val();
		}
		param = param + '&comp='+ $('#comp').val();
		//alert(param);
		$('#el_subject_dialog').load('lookup/lookup_com_schedule_el_subject.php?listrow=10'+ param, null);
}
</script>

<?php

	if(isset($filterfield) && $filtervalue!='all')
	{
		$sqlcon = 'AND a.'.$filterfield.'='.$filtervalue;
	}	

?>

<div id="lookup_content">
Filter By: Department<select name="filterdepartment2" id="filterdepartment2" class="txt_200">
    
                    <option value="" >-Select-</option>
                    <option value="all" <?=$filterfield == 'all' ? 'selected="selected"' : '';?> >All</option>
                     <?=generateDepartment($filtervalue)?>  
                </select>
                <input type="hidden" name="comp" id="comp" value="<?=$_REQUEST['comp']?>" />
            <label>&nbsp;</label>
 
        <?php
        $x = 1;
		if( $filterfield!='')
		{
		?>
            <table class="fieldsetList">           
                <tr>
                    <th class="col1_50">&nbsp;</th> 
                    <th class="col1_300"><a href="#">Code</a></th>   
                    <th class="col1_400"><a href="#">Subject Name</a></th>  
                    <th class="col1_300"><a href="#">Department</a></th>    
                </tr>
            <?php
               //$sql = "SELECT a.* FROM tbl_subject a,tbl_curriculum_subject b WHERE a.id=b.subject_id " . $sqlcon." AND b.subject_category<>'E' AND b.subject_category<>'EO' ";	
			   $sql = "SELECT * FROM tbl_subject WHERE 1=1 " . $sqlcon ."ORDER BY `subject_code`";
                $result = mysql_query($sql);
                
                while($row = mysql_fetch_array($result)) 
                { 
            ?>
                <tr class="<?=($x%2==0)?"":"highlight";?>">
                    <td><a href="#" name="id" id="id_<?=$row['id']?>" class="selector" returnId="<?=$row['id']?>" returnTxt="<?=$row['subject_name']?>">Select</a></td>
                    <td><?=$row["subject_code"]?></td>
                    <td><?=$row["subject_name"]?></td>
                    <td><?=getDeptName($row["department_id"])?></td>
                </tr>
            <?php 
                    $x++;   
                }  
            ?>
            </table> 
       <?php 
		if(mysql_num_rows($result) == 0)
		 {  
		 ?>
         	<div style="font-family:Arial; font-size:12px; padding-top:10px;">
                <label>No Records Found.</label>
            </div>
         <?php
		 }
        }
		else
		{
		
        ?>
        	<div style="font-family:Arial; font-size:12px; padding-top:10px;">
                <label>Please select department.</label>
            </div>
         <?php
		 }
		 ?>
    
</div> <!-- #lookup_content -->
<?php
}
?>