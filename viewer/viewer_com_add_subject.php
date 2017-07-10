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
	//$(function(){
		/*$('.selector').click(function() {
			
			if(confirm("Are you sure you want to add this subject?"))
			{
				var valId = $(this).attr("returnId");
				$('#id').val(valId);
				$('#action').val('saveblocksubject');
				
				$("form").submit();//
				updateList();
				
				$('#dialog2').dialog('close');
			}
		});*/
		function getselected() {
				
			//alert('aaa');
			var i = $('#cnt').val();
			//alert(i);
			var ids = "";

		for(var x=1; x<=i; x++) {
			if ($('#chk' + x).attr("checked")) {
				if (ids != "")
					ids += ",";
				ids += $('#chk'+x).val();
			}
		}
		//alert(ids);
			
			$('#id').attr("value", ids);
			$('#action').val('saveblocksubject');
			$("form").submit();
			$('#dialog').dialog('close');			
		};
	//});
	
</script>
	
	<?php

		$courseid = $_REQUEST['course'];

		$bid = $_REQUEST['blk'];

		$termId = $_REQUEST['term_id'];
		

	$sql = "SELECT *, c.id as cid FROM tbl_curriculum a 

				JOIN tbl_curriculum_subject b ON a.id = b.curriculum_id 

				JOIN tbl_schedule c ON c.subject_id = b.subject_id 

				WHERE a.course_id = ".$courseid. " AND c.term_id = ".$termId." AND a.is_current ='Y' ORDER BY c.section_no";

	

		$result = mysql_query($sql);
		$ctr = mysql_num_rows($result);
		
?>

            <label>&nbsp;</label>
    <div id="lookup_content">
<div class="header">

<table width="100%">
  
  <tr>
    <td colspan="3" align="center" class="bold">SUBJECTS</td>
  </tr>
</table>
 
</div>

    <table class="fieldsetList">      
        <tr>
        	<th class="col_100">&nbsp;&nbsp;&nbsp;</th>
            
            <th class="col_50"><a href="#">Section</a></th>

			<th class="col_100"><a href="#">Subject</a></th>

			<th class="col_100"><a href="#">Room</a></th>

			<th class="col_250"><a href="#">Professor</a></th>

			<th class="col_40"><a href="#">Slots</a></th>

			<th class="col_50"><a href="#">Schedule</a></th>
        </tr>
        <?php
        $x = 1;
		
		if($ctr > 0)
		{

			while($row = mysql_fetch_array($result)) 

			{ 

				if(!checkIfScheduleIsInBlock($row['cid'],$bid))

				{


		?>

            <tr class="<?=($x%2==0)?"":"highlight";?>">

                <!--<td><a href="#" name="id" id="id_<?//=$row["id"]?>" class="selector" returnId="<?//=$row['id']?>">Select</a></td>!-->
                <td><input name="chk" id="chk<?=$x?>" type="checkbox" value="<?=$row['id']?>" /></td>

	            <td><?=$row["section_no"]?></td> 

                <td><?=getSubjName($row["subject_id"])?></td>

                <td><?=getRoomNo($row["room_id"])?></td>

                <td><?=getProfessorFullName($row["employee_id"])?></td>

                <td><?=$row["number_of_student"]?></td>

                <td><?=getScheduleDays($row["id"])?></td>

			</tr>	
        <?php 
            $x++;       
        	}
			}
			?>
            
            <input name="cnt" id="cnt" type="hidden" value="<?=$x-1?>" />
            
            <?php
			
		}
		else
		{
        ?>
            <tr> 
                <td colspan="7">No record found</td>                                
            </tr>        
        <?php
		}
		?>
        
    </table> 
     

</div><!-- #lookup_content -->
<?php
}
?>