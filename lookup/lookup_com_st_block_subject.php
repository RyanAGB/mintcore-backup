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

$id = $_REQUEST['id'];

	$sql = "SELECT * FROM tbl_block_subject a LEFT JOIN tbl_schedule b ON a.schedule_id = b.id LEFT JOIN tbl_curriculum_subject c ON b.subject_id=c.subject_id WHERE a.block_section_id = ".$id." AND c.curriculum_id = ".USER_CURRICULUM_ID;
	$query = mysql_query($sql);

?>
<div id="lookup_content">

    <table class="fieldsetList">      
        <tr>
        	<th class="col1_400"><a href="#">Section</a></th>
            <th class="col1_400"><a href="#">Subject Code</a></th>   
            <th class="col1_400"><a href="#">Subject Name</a></th>  
            <th class="col1_400"><a href="#">Schedule</a></th>
            <th class="col1_400"><a href="#">Room</a></th>  
        </tr>
        <?php
        $x = 1;
        while($row = mysql_fetch_array($query)) 
        { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
            	<td><?=$row['section_no']?></td>
                <td><?=getSubjCode($row["subject_id"])?></td>
				<td><?=getSubjName($row["subject_id"])?></td>
				<td><?=getScheduleDays($row['schedule_id'])?></td>
				<td><?=getRoomNo($row["room_id"])?></td>
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