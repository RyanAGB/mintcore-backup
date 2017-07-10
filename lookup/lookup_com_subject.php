<?php
include_once("../config.php");
include_once("../includes/functions.php");
include_once("../includes/common.php");

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp'])){
	header('Location: ../forbid.html');
}else{
	?>
<script type="text/javascript">
$(function(){
	$('.selector').click(function() {
		//var id = $(this).attr("val");		
		var valTxt = $(this).attr("returnTxt");
		var valId = $(this).attr("returnId");
		$('#department_id').attr("value", valId);
		$('#department_name_display').attr("value", valTxt);
		$('#dialog').dialog('close');			
	});
});
</script>

	<?php
	$sql = "SELECT * FROM tbl_department";						
	$result = mysql_query($sql);
	?>

<div id="lookup_content">
	<table class="fieldsetList">      
		<tr>
			<th class="col1_100">&nbsp;</th> 
			<th class="col1_400"><a href="#">Department Code</a></th>   
			<th class="col1_400"><a href="#">Department Name</a></th>       
		</tr>
	
	<?php
	$x = 1;
	while($row = mysql_fetch_array($result)){ 
		?>
		<tr class="<?=($x%2==0)?"":"highlight";?>">
			<td><a href="#" name="id" id="id_<?=$row['id']?>" class="selector" returnId="<?=$row['id']?>" returnTxt="<?=$row['department_code']?>">Select</a></td>
			<td><?=$row["department_code"]?></td>
			<td><?=$row["department_name"]?></td>
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