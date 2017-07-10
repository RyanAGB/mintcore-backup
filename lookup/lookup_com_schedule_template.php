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
	
	var row_ctr = 1;
	
		$('.selector').click(function() {
				
			var valReturn = $(this).attr("returnId");
			var arrReturn = valReturn.split("|");

			$('#section_no').attr("value", arrReturn[0]);
			$('#subject_id').attr("value", arrReturn[1]);
			$('#subject_display').attr("value", arrReturn[2]);
			$('#room_id').attr("value", arrReturn[3]);
			$('#room_display').attr("value", arrReturn[4]);
			$('#employee_id').attr("value", arrReturn[5]);
			$('#employee_display').attr("value", arrReturn[6]);
			$('#number_of_student').attr("value", arrReturn[7]);
			var day = arrReturn[8].split('/');
			var wday = '';
			var ctr = 1;
			
			$.each($("input[name*=days]"), function(index, value) { 
				$('#row_'+ctr).remove();
			});
			
			for(var i=0;i<day.length;i++)
			{
				var days = day[i].split('-');
				
				if(days[0]=='M')
				{
					wday = 'monday';
				}
				else if(days[0]=='T')
				{
					wday = 'tuesday';
				}
				else if(days[0]=='W')
				{
					wday = 'wednesday';
				}
				else if(days[0]=='Th')
				{
					wday = 'thursday';
				}
				else if(days[0]=='F')
				{
					wday = 'friday';
				}
				else if(days[0]=='S')
				{
					wday = 'saturday';
				}
				else if(days[0]=='SU')
				{
					wday = 'sunday';
				}
				
			//alert(days);	
			str_table ='<tr id ="row_'+row_ctr+'">';
		  str_table +='<td><input name="days[]" type="hidden" id="days" value="'+wday+'" />' +wday+ '</td>';
		  str_table +='<td><input name="start['+i+']" type="hidden" id="start" value="'+days[1]+'" />' +days[1]+ '</td>';
		  str_table +='<td><input name="end['+i+']" type="hidden" id="end" value="'+days[2]+'" />' +days[2]+ '</td>';
		  str_table +='<td class="action"><a href="#" class="remove" returnId="'+row_ctr+'" onclick="removeRow('+row_ctr+'); return false;" >Remove</a></td>';             
		str_table +='</tr>';

		$('#tbl_sched tbody').append(str_table);
		}
			
			$('#template_dialog').dialog('close');		
				
		});
		
		$('#filterTemp').change(function(){
			updateList();
		});
	
	});
	
	
	function updateList()
{
		var param = '';
		
		if($('#filterTemp').val() != '')
		{
			param = param + '&filterfield=template_id&filtervalue=' + $('#filterTemp').val();
		}
		param = param + '&comp='+ $('#comp').val();
		//alert(param);
		$('#template_dialog').load('lookup/lookup_com_schedule_template.php?listrow=10'+ param, null);
}
</script>

<?php
	if(isset($filterfield) && $filtervalue!='all')
	{
		$sqlcon = ' WHERE '.$filterfield.'='.$filtervalue;
	}	
	
	$sql = "SELECT * FROM tbl_schedule_template_subjects". $sqlcon;						
	$query = mysql_query($sql);
?>

<div id="lookup_content">
    
    Filter By: Template<select name="filterTemp" id="filterTemp" class="txt_200" >
    
                    <option value="" <?=$filterfield == 'all' ? 'selected="selected"' : '';?> >All</option>
                     <?=generateTempSched($filtervalue)?>  
                </select>
                <input type="hidden" name="comp" id="comp" value="<?=$_REQUEST['comp']?>" />
            <label>&nbsp;</label>
    
        <?php
        $x = 1;
		if( mysql_num_rows($query)>0)
		{
		?>
		<table class="fieldsetList">      
        <tr>
            <th class="col1_100">&nbsp;</th> 
            <th class="col1_400"><a href="#">Section No.</a></th> 
            <th class="col1_400"><a href="#">Subject Code</a></th>    
            <th class="col1_400"><a href="#">Subject Name</a></th>
            <th class="col1_400"><a href="#">Room</a></th>
            <th class="col1_400"><a href="#">Professor</a></th>
            <th class="col1_400"><a href="#">Slot</a></th>
            <th class="col1_400"><a href="#">Schedule</a></th>
        </tr>
		<?php
        while($row = mysql_fetch_array($query)) 
        { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td><a href="#" name="id" id="id_<?=$row['id']?>" class="selector" 
                returnId="<?=$row['section_no'].'|'.$row['subject_id'].'|'.
							getSubjName($row['subject_id']).'|'.
							$row['room_id'].'|'.
							getRoomNo($row['room_id']).'|'.
							$row['employee_id'].'|'.
							getProfessorFullName($row['employee_id']).'|'.
							$row['number_of_student'].'|'.
							getSepScheduleTempDays($row["id"])
							?>">Select</a></td>
                <td><?=$row['section_no']!=''?$row['section_no']:'-'?></td>
                <td><?=$row['subject_id']!=''?getSubjCode($row["subject_id"]):'-'?></td> 
                <td><?=$row['subject_id']!=''?getSubjName($row["subject_id"]):'-'?></td>
                <td><?=$row["room_id"]!=''?getRoomNo($row["room_id"]):'-'?></td>
                <td><?=$row["employee_id"]!=''?getProfessorFullName($row["employee_id"]):'-'?></td>
                <td><?=$row["number_of_student"]!=''?$row["number_of_student"]:'-'?></td>
                <td><?=getScheduleDaysTemplate($row["id"])!=''?getScheduleDaysTemplate($row["id"]):'-'?></td>
            </tr>
        <?php 
            $x++;          
        }
        ?>
    </table> 
    	<?php 
		}
		else
		 {  
		 ?>
         	<div style="font-family:Arial; font-size:12px; padding-top:10px;">
                <label>No Records Found.</label>
            </div>
         <?php
		 }
?>
		
    		
</div> <!-- #lookup_content -->
<?php
}
?>