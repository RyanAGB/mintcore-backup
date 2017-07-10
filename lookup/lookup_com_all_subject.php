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
	
		function getselected() {
				
			//alert('aaa');
			var i = $('#cnt').val();
			//alert(i);
			var ids = "";
			var names = "";

		for(var x=1; x<=i; x++) {
			if ($('#chk' + x).attr("checked")) {
				if (ids != "")
					ids += ",";
				if (names != "")
					names += ",";
				ids += $('#chk'+x).val(); 
				names += $('#chk'+x).attr('retName');
			}
		}
			
			$('#subject_id').attr("value", ids);
			$('#subject_name_display').attr("value", names);
			
			$('#dialog').dialog('close');			
		};

</script>

<?php
	 $sql = "SELECT * FROM tbl_subject";						
	$result = mysql_query($sql);
?>

<div id="lookup_content">

    <table class="fieldsetList">      
        <tr>
            <th class="col1_100">&nbsp;</th> 
            <th class="col1_400"><a href="#">Subject Code</a></th>   
            <th class="col1_400"><a href="#">Subject Name</a></th>       
        </tr>
        <?php
        $x = 1;
        while($row = mysql_fetch_array($result)) 
        { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td><input name="chk<?=$x?>" type="checkbox" value="<?=$row['id']?>" id="chk<?=$x?>" retName = <?=$row["subject_name"]?></td>
                <td><?=$row["subject_code"]?></td>
                <td><?=$row["subject_name"]?></td>
            </tr>
        <?php 
            $x++;          
        }
        ?>
        <input type="hidden" name="cnt" id="cnt" value="<?=mysql_num_rows($result)?>" />
    </table> 
    
</div> <!-- #lookup_content -->
<?php
}
?>