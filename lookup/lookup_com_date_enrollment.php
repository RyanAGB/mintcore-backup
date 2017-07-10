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
		var cnt = $('#cnt').val();
		var ids = "";
		var unique = true;
		
		for(var ctr=1; ctr<=cnt; ctr++) {
			if ($('#chk' + ctr).attr("checked")) {

			var valId = $('#chk' + ctr).val();
			var valTxt = $('#returnTxt' + ctr).val();
			
			$.each($('#course_id'), function(index, value) { 
				if($(this).val()==valId)
				{
					unique = false;
				}
			});
			
			if(unique == true)
			{
				var htmlContents = $('#course_container').html();
				
				var filter = '<div id="course_item_' + valId + '">' +
							 '<label><a href="#" title="remove this subject" onclick="$(\'#course_item_' + valId + '\').html(\'\'); $(\'#course_item_' + valId + '\').css(\'display\',\'none\'); return false;" ><img src="images/icon_negative.png" border="0"/></a>&nbsp;&nbsp;' +
							 '<input class="txt" name="course_id[]" type="hidden" value="' + valId + '" id="course_id" readonly="readonly" />' + 
							 '(' + valTxt + ') '+ '</label>' + valTxt +
							 '</div>';
				
				$('#course_container').html(htmlContents + filter);
			}
			else
			{
				alert(valTxt+' Already exist!');
				return false;
			}

			$('#dialog').dialog('close');			

	}
  }
}
</script>

<?php
	 $sql = "SELECT * FROM tbl_course WHERE publish = 'Y'";						
	$result = mysql_query($sql);
?>

<div id="lookup_content">

    <table class="fieldsetList">      
        <tr>
        <input type="hidden" name="cnt" id="cnt" value="<?=mysql_num_rows($result)?>" />
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
                <td><input type="hidden" name="returnTxt<?=$x?>" id="returnTxt<?=$x?>" value="<?=$row['course_code'].' '.$row['course_name']?>"  />
				<input name="chk<?=$x?>" type="checkbox" value="<?=$row['id']?>" id="chk<?=$x?>"/><!--<a href="#" name="id" id="id_<?=$row['id']?>" class="check" returnId="<?=$row['id']?>" returnTxt="<?=$row['course_name']?>" returnTxtCode="<?=$row['course_code']?>">Select</a>!--></td>
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