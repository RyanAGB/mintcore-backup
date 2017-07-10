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

			$('#course_id').attr("value", valId);
			$('#course_name_display').attr("value", valTxt);
			
			$.ajax({
					type: "POST",
					data: "mod=updateYear&ad=T&id=" + valId,
					url: "ajax_components/ajax_com_year_field_updater.php",
					success: function(msg){
						if (msg != ''){
							$("#year_level").html(msg);
						}
					}
					});
			
			$('#course_dialog').dialog('close');			
		});
	});
</script>

<?php
	 $sql = "SELECT * FROM tbl_course";						
	$result = mysql_query($sql);
?>

<div id="lookup_content">

    <table class="fieldsetList">      
        <tr>
            <th class="col1_100">&nbsp;</th> 
            <th class="col1_400"><a href="#">Course Code</a></th>   
            <th class="col1_400"><a href="#">Course Name</a></th>       
        </tr>
        <?php
        $x = 1;
        while($row = mysql_fetch_array($result)) 
        { 	
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td><a href="#" name="id" id="id_<?=$row['id']?>" class="selector" returnId="<?=$row['id']?>" returnTxt="<?=$row['course_code']?>">Select</a></td>
                <td><?=$row["course_code"]?></td>
                <td><?=$row["course_name"]?></td>
            </tr>
        <?php 
            $x++;          
        }
        ?>
    </table> 
    
</div> <!-- #lookup_content -->
<?php
}
?>