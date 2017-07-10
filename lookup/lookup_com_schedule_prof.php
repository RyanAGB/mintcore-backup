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
			$('#employee_id').attr("value", valId);
			$('#employee_display').attr("value", valTxt);
			
			$('#prof_dialog').dialog('close');			
		});

		$('#filter_department').change(function(){
			var param = '';
			
			if($('#filter_department').val() != '')
			{
				param = param + '&filterfield=department_id&filtervalue=' + $('#filter_department').val();
			}
			param = param + '&comp='+ $('#comp').val();
			//alert(param);
			$('#prof_dialog').load('lookup/lookup_com_schedule_prof.php?listrow=10'+ param, null);
		});
	
	});

</script>

<?php
	if(isset($filterfield) && $filtervalue!='all')
	{
		$sqlcon = 'AND '.$filterfield.'='.$filtervalue;
	}
?>

<div id="lookup_content">
	Filter By: Department<select name="filter_department" id="filter_department" class="txt_200" >
    
                    <option value="" >-Select-</option>
                    <option value="all" <?=$filterfield == 'all' ? 'selected="selected"' : '';?> >All</option>
                     <?=generateDepartment($filtervalue)?>  
                </select>
    			<input type="hidden" name="comp" id="comp" value="<?=$_REQUEST['comp']?>" />
        <?php
        $x = 1;
		if( $filtervalue!='')
		{
		?>
		<table class="fieldsetList">      
        <tr>
            <th class="col1_100">&nbsp;</th> 
            <th class="col1_400"><a href="#">Full Name</a></th> 
            <th class="col1_400"><a href="#">Department</a></th>    
        </tr>
		<?php
		$sql = "SELECT * FROM tbl_employee WHERE employee_type = 2 " . $sqlcon . " ORDER BY `lastname` ASC";						
		$result = mysql_query($sql);
		
        while($row = mysql_fetch_array($result)) 
        { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td><a href="#" name="id" id="id_<?=$row['id']?>" class="selector" returnId="<?=$row['id']?>" returnTxt="<?=$row['lastname'].', '.$row['firstname'].' '.$row['middlename']?>">Select</a></td>
                <td><?=$row['lastname'].', '.$row['firstname'].' '.$row['middlename']?></td>
                <td><?=getDeptName($row['department_id'])?></td>
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
                <label>Please select Department.</label>
            </div>
         <?php
		 }
		 ?>
    		
</div> <!-- #lookup_content -->
<?php
}
?>