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
$filterbuilding_id = $_REQUEST['filterbuilding_id'];
$filterroom = $_REQUEST['filterroom'];
$filterroom_type = $_REQUEST['filterroom_type'];

?>
<script type="text/javascript">
	$(function(){
		$('.selector').click(function() {
			//var id = $(this).attr("val");		
			var valTxt = $(this).attr("returnTxt");
			var valId = $(this).attr("returnId");
			$('#room_id').attr("value", valId);
			$('#room_display').attr("value", valTxt);
			
			$('#room_dialog').dialog('close');			
		});
	});
	
	$(function(){	

	$('#filterbuild').change(function(){
		
		var param ='';
		if($('#filteroom').val() != '')
		{
			param = param + '&filterfield=building_id&filterbuilding_id=' + $('#filterbuild').val()+ '&filterroom=room_type&filterroom_type='+$('#filteroom').val();
		}
		else
		{
			param = param + '&filterfield=building_id&filterbuilding_id=' + $('#filterbuild').val();
		}
		param = param + '&comp='+ $('#comp').val();
		$('#room_dialog').load('lookup/lookup_com_schedule_room.php?listrow=10'+ param, null);
	});

});

	$(function(){	

	$('#filteroom').change(function(){
		
		var param = '';
		if($('#filterbuild').val() != '')
		{
			param = param + '&filterroom=room_type&filterroom_type='+$('#filteroom').val()+ '&filterfield=building_id&filterbuilding_id=' + $('#filterbuild').val();
		}
		else
		{
			param = param + '&filterroom=room_type&filterroom_type='+$('#filteroom').val();
		}
		param = param + '&comp='+ $('#comp').val();
		$('#room_dialog').load('lookup/lookup_com_schedule_room.php?listrow=10'+ param, null);
		
	});

});

</script>

<?php
	$arr_sql = array();

	if (isset($filterfield) && $filterbuilding_id!='all' && $filterbuilding_id!='')
	{
		$arr_sql[] = $filterfield.'='.$filterbuilding_id;
	}
	if(isset($filterroom) && $filterroom_type!='all' && $filterroom_type !='')
	{
		$arr_sql[] = $filterroom.'="'.$filterroom_type.'"';
	}		
	if(count($arr_sql) > 0)
	{
		$sqlcon = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : ' AND '.implode(' AND ', $arr_sql);
	}

?>

<div id="lookup_content">

	<!--Filter By: Building<select name="filterbuild" id="filterbuild" class="txt_200" >
    
                    <option value="" >-Select-</option>
                    <option value="all" <?=$filterbuilding_id == 'all' ? 'selected="selected"' : '';?> >All</option>
                     <?=generateBuilding($filterbuilding_id)?>  
                </select>
            <label>&nbsp;</label>
           	Room Type<select name="filteroom" id="filteroom" class="txt_200" >
                    <option value="" >-Select-</option>
                    <option value="lec" <?=$filterroom_type == 'lec' ? 'selected="selected"' : '';?> >Lec</option>
                    <option value="lab" <?=$filterroom_type == 'lab' ? 'selected="selected"' : '';?> >Lab</option>
                    <option value="field" <?=$filterroom_type == 'field' ? 'selected="selected"' : '';?> >Field</option> 
                </select>!-->
                <input type="hidden" name="comp" id="comp" value="<?=$_REQUEST['comp']?>" />

        <?php
        $x = 1;
		/*if( $filterbuilding_id!='' || $filterroom_type != '')
		{*/
		?>
         <table class="fieldsetList">
        <tr>
            <th class="col1_100">&nbsp;</th> 
            <th class="col1_400"><a href="#">Room No</a></th>   
            <th class="col1_400"><a href="#">Room Type</a></th>  
            <th class="col1_400"><a href="#">Building</a></th>    
        </tr>
		<?php
		$sql = "SELECT * FROM tbl_room WHERE publish = 'Y'" . $sqlcon . "ORDER BY `room_no` ASC";						
		$result = mysql_query($sql);
		
        while($row = mysql_fetch_array($result)) 
        { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td><a href="#" name="id" id="id_<?=$row['id']?>" class="selector" returnId="<?=$row['id']?>" returnTxt="<?=$row['room_no']?>">Select</a></td>
                <td><?=$row["room_no"]?></td>
                <td><?=$row["room_type"]?></td>
                <td><?=getBuildingName($row["building_id"])?></td>
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
       /* }
		else
		{
		
        ?>
        	<div style="font-family:Arial; font-size:12px; padding-top:10px;">
                <label>Please select Building or Room Type.</label>
            </div>
         <?php
		 }*/
		 ?>
    
</div> <!-- #lookup_content -->
<?php
}
?>