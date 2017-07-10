<?php
include_once("../config.php");
include_once("../includes/functions.php");
include_once("../includes/common.php");	
	
if(USER_IS_LOGGED != '1'){
	header('Location: ../index.php');
}else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])){
	header('Location: ../forbid.html');
}
	
$filter_schoolterm = $_REQUEST['filter_schoolterm'];	
?>

<script type="text/javascript">

$(document).ready(function(){  

	$('.sortBy').click(function(){
		if($('#filter_order').val() == '' || $('#filter_order').val() == 'DESC'){
			var order = 'ASC';
		}else{
			var order = 'DESC';
		}
		
		$('#filter_field').val($(this).attr('returnFilter'));
		$('#filter_order').val(order);
		updateList();
		return false;
	});
	
	$('#summary').click(function() {
		var w=window.open ("excel_reports/examples/01simple.php?trm="+$('#filter_schoolterm').val()+"&met=sched_summary"); 
		return false;
	});
});

$(function(){
	// Dialog			
	$('#dialog').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons:{
			"Close": function() { 
				$(this).dialog("close"); 
			}
		}
	});
	
	// Dialog Link
	$('.profile').click(function(){
		var param = $(this).attr("returnId");
		var param2 = $(this).attr("returnComp");
		var param3 = $(this).attr("returnTerm");
		$('#dialog').load('viewer/viewer_com_schedule_student_list.php?id='+param+'&comp='+param2+'&filter_schoolterm='+param3, null);
		$('#dialog').dialog('open');
		return false;
	});
});	
</script>

<?php
$arr_sql = array();
$sqlcondition = '';
$sqlOrderBy = '';

//default sort
$sqlOrderBy = ' ORDER BY tbl_schedule.monday ASC, tbl_schedule.monday_time_from ASC, 
					tbl_schedule.tuesday ASC, tbl_schedule.tuesday_time_from ASC,
					tbl_schedule.wednesday ASC, tbl_schedule.wednesday_time_from ASC,
					tbl_schedule.thursday ASC, tbl_schedule.thursday_time_from ASC,
					tbl_schedule.friday ASC, tbl_schedule.friday_time_from ASC,
					tbl_schedule.saturday ASC, tbl_schedule.saturday_time_from ASC,
					tbl_schedule.sunday ASC, tbl_schedule.sunday_time_from ASC,
					tbl_subject.subject_name ASC, tbl_room.room_no ASC'; 

if(isset($_REQUEST['list_rows'])){
	$_SESSION[CORE_U_CODE]['pageRows'] = $_REQUEST['list_rows'];
	$page_rows = $_SESSION[CORE_U_CODE]['pageRows']; 	
}else if($_SESSION[CORE_U_CODE]['default_record']!=''){
	$page_rows = $_SESSION[CORE_U_CODE]['default_record'];
}else{
	$page_rows = DEFAULT_RECORD;
}	

if(isset($_REQUEST["search_field"]) and ($_REQUEST["search_field"] != "") and ($_REQUEST["search_key"] != "") and ($_REQUEST["search_key"] != "")){
	$search_field = $_REQUEST["search_field"];
	$search_key = $_REQUEST["search_key"]; 
	$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
}			

if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' ){
	if($_REQUEST['fieldName'] == 'subject_code' || $_REQUEST['fieldName'] == 'subject_name'){
		$sqlOrderBy = ' ORDER BY  tbl_subject.'.$_REQUEST['fieldName'].' '.$_REQUEST['orderBy'].',
								tbl_schedule.monday ASC, tbl_schedule.monday_time_from ASC, 
								tbl_schedule.tuesday ASC, tbl_schedule.tuesday_time_from ASC,
								tbl_schedule.wednesday ASC, tbl_schedule.wednesday_time_from ASC,
								tbl_schedule.thursday ASC, tbl_schedule.thursday_time_from ASC,
								tbl_schedule.friday ASC, tbl_schedule.friday_time_from ASC,
								tbl_schedule.saturday ASC, tbl_schedule.saturday_time_from ASC,
								tbl_schedule.sunday ASC, tbl_schedule.sunday_time_from ASC,
								tbl_schedule.section_no ASC';
		goto CONTINUERESULT;
	}else{
		//$sqlOrderBy = ' ORDER BY  '.$_REQUEST['fieldName'].' '.$_REQUEST['orderBy'];
	}
}else if($_SESSION[CORE_U_CODE]['fieldName']!='' || $_SESSION[CORE_U_CODE]['orderBy'] !=''){
	if($_SESSION[CORE_U_CODE]['fieldName'] == 'subject_code' || $_SESSION[CORE_U_CODE]['fieldName'] == 'subject_name'){
		$sqlOrderBy = ' ORDER BY  tbl_subject.'.$_SESSION[CORE_U_CODE]['fieldName'].' '.$_SESSION[CORE_U_CODE]['orderBy'].',
								tbl_schedule.monday ASC, tbl_schedule.monday_time_from ASC, 
								tbl_schedule.tuesday ASC, tbl_schedule.tuesday_time_from ASC,
								tbl_schedule.wednesday ASC, tbl_schedule.wednesday_time_from ASC,
								tbl_schedule.thursday ASC, tbl_schedule.thursday_time_from ASC,
								tbl_schedule.friday ASC, tbl_schedule.friday_time_from ASC,
								tbl_schedule.saturday ASC, tbl_schedule.saturday_time_from ASC,
								tbl_schedule.sunday ASC, tbl_schedule.sunday_time_from ASC,
								tbl_schedule.section_no ASC';
		goto CONTINUERESULT;
	}else{
		//$sqlOrderBy = ' ORDER BY  '.$_SESSION[CORE_U_CODE]['fieldName'].' '.$_SESSION[CORE_U_CODE]['orderBy'];
	}
}

if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' ){
	if($_REQUEST['fieldName'] == 'room_no'){
		$sqlOrderBy = ' ORDER BY  tbl_room.room_no '.$_REQUEST['orderBy'].', 
								tbl_schedule.monday ASC, tbl_schedule.monday_time_from ASC, 
								tbl_schedule.tuesday ASC, tbl_schedule.tuesday_time_from ASC,
								tbl_schedule.wednesday ASC, tbl_schedule.wednesday_time_from ASC,
								tbl_schedule.thursday ASC, tbl_schedule.thursday_time_from ASC,
								tbl_schedule.friday ASC, tbl_schedule.friday_time_from ASC,
								tbl_schedule.saturday ASC, tbl_schedule.saturday_time_from ASC,
								tbl_schedule.sunday ASC, tbl_schedule.sunday_time_from ASC,
								tbl_schedule.section_no ASC';
		goto CONTINUERESULT;
	}else{
		//$sqlOrderBy = ' ORDER BY  '.$_REQUEST['fieldName'].' '.$_REQUEST['orderBy'];
	}
}else if($_SESSION[CORE_U_CODE]['fieldName']!='' || $_SESSION[CORE_U_CODE]['orderBy'] !=''){
	if($_SESSION[CORE_U_CODE]['fieldName'] == 'room_no'){
		$sqlOrderBy = ' ORDER BY  tbl_room.room_no '.$_SESSION[CORE_U_CODE]['orderBy'].', 
								tbl_schedule.monday ASC, tbl_schedule.monday_time_from ASC, 
								tbl_schedule.tuesday ASC, tbl_schedule.tuesday_time_from ASC,
								tbl_schedule.wednesday ASC, tbl_schedule.wednesday_time_from ASC,
								tbl_schedule.thursday ASC, tbl_schedule.thursday_time_from ASC,
								tbl_schedule.friday ASC, tbl_schedule.friday_time_from ASC,
								tbl_schedule.saturday ASC, tbl_schedule.saturday_time_from ASC,
								tbl_schedule.sunday ASC, tbl_schedule.sunday_time_from ASC,
								tbl_schedule.section_no ASC';
		goto CONTINUERESULT;
	}else{
		//$sqlOrderBy = ' ORDER BY  '.$_SESSION[CORE_U_CODE]['fieldName'].' '.$_SESSION[CORE_U_CODE]['orderBy'];
	}
}

if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' ){
	if($_REQUEST['fieldName'] == 'professor'){
		$sqlOrderBy = ' ORDER BY proflastname '.$_REQUEST['orderBy'].', proffirstname '.$_REQUEST['orderBy'].', profmiddlename '.$_REQUEST['orderBy'].', 
								tbl_schedule.monday ASC, tbl_schedule.monday_time_from ASC, 
								tbl_schedule.tuesday ASC, tbl_schedule.tuesday_time_from ASC,
								tbl_schedule.wednesday ASC, tbl_schedule.wednesday_time_from ASC,
								tbl_schedule.thursday ASC, tbl_schedule.thursday_time_from ASC,
								tbl_schedule.friday ASC, tbl_schedule.friday_time_from ASC,
								tbl_schedule.saturday ASC, tbl_schedule.saturday_time_from ASC,
								tbl_schedule.sunday ASC, tbl_schedule.sunday_time_from ASC,
								tbl_schedule.section_no ASC';
		goto CONTINUERESULT;
	}else{
		//$sqlOrderBy = ' ORDER BY  '.$_REQUEST['fieldName'].' '.$_REQUEST['orderBy'];
	}
}else if($_SESSION[CORE_U_CODE]['fieldName']!='' || $_SESSION[CORE_U_CODE]['orderBy'] !=''){
	if($_SESSION[CORE_U_CODE]['fieldName'] == 'professor'){
		$sqlOrderBy = ' ORDER BY proflastname '.$_SESSION[CORE_U_CODE]['orderBy'].', proffirstname '.$_SESSION[CORE_U_CODE]['orderBy'].', profmiddlename '.$_SESSION[CORE_U_CODE]['orderBy'].', 
								tbl_schedule.monday ASC, tbl_schedule.monday_time_from ASC, 
								tbl_schedule.tuesday ASC, tbl_schedule.tuesday_time_from ASC,
								tbl_schedule.wednesday ASC, tbl_schedule.wednesday_time_from ASC,
								tbl_schedule.thursday ASC, tbl_schedule.thursday_time_from ASC,
								tbl_schedule.friday ASC, tbl_schedule.friday_time_from ASC,
								tbl_schedule.saturday ASC, tbl_schedule.saturday_time_from ASC,
								tbl_schedule.sunday ASC, tbl_schedule.sunday_time_from ASC,
								tbl_schedule.section_no ASC';
		goto CONTINUERESULT;
	}else{
		//$sqlOrderBy = ' ORDER BY  '.$_SESSION[CORE_U_CODE]['fieldName'].' '.$_SESSION[CORE_U_CODE]['orderBy'];
	}
}

if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' ){
	if($_REQUEST['fieldName'] == 'schedule_days'){
		$sqlOrderBy = ' ORDER BY tbl_schedule.monday '.$_REQUEST['orderBy'].', tbl_schedule.monday_time_from ASC, 
								tbl_schedule.tuesday '.$_REQUEST['orderBy'].', tbl_schedule.tuesday_time_from ASC,
								tbl_schedule.wednesday '.$_REQUEST['orderBy'].', tbl_schedule.wednesday_time_from ASC,
								tbl_schedule.thursday '.$_REQUEST['orderBy'].', tbl_schedule.thursday_time_from ASC,
								tbl_schedule.friday '.$_REQUEST['orderBy'].', tbl_schedule.friday_time_from ASC,
								tbl_schedule.saturday '.$_REQUEST['orderBy'].', tbl_schedule.saturday_time_from ASC,
								tbl_schedule.sunday '.$_REQUEST['orderBy'].', tbl_schedule.sunday_time_from ASC,
								tbl_room.room_no ASC';
		goto CONTINUERESULT;
	}else{
		//$sqlOrderBy = ' ORDER BY  '.$_REQUEST['fieldName'].' '.$_REQUEST['orderBy'];
	}
}else if($_SESSION[CORE_U_CODE]['fieldName']!='' || $_SESSION[CORE_U_CODE]['orderBy'] !=''){
	if($_SESSION[CORE_U_CODE]['fieldName'] == 'schedule_days'){
		$sqlOrderBy = ' ORDER BY tbl_schedule.monday '.$_SESSION[CORE_U_CODE]['orderBy'].', tbl_schedule.monday_time_from ASC, 
								tbl_schedule.tuesday '.$_SESSION[CORE_U_CODE]['orderBy'].', tbl_schedule.tuesday_time_from ASC,
								tbl_schedule.wednesday '.$_SESSION[CORE_U_CODE]['orderBy'].', tbl_schedule.wednesday_time_from ASC,
								tbl_schedule.thursday '.$_SESSION[CORE_U_CODE]['orderBy'].', tbl_schedule.thursday_time_from ASC,
								tbl_schedule.friday '.$_SESSION[CORE_U_CODE]['orderBy'].', tbl_schedule.friday_time_from ASC,
								tbl_schedule.saturday '.$_SESSION[CORE_U_CODE]['orderBy'].', tbl_schedule.saturday_time_from ASC,
								tbl_schedule.sunday '.$_SESSION[CORE_U_CODE]['orderBy'].', tbl_schedule.sunday_time_from ASC,
								tbl_room.room_no ASC';
		goto CONTINUERESULT;
	}else{
		//$sqlOrderBy = ' ORDER BY  '.$_SESSION[CORE_U_CODE]['fieldName'].' '.$_SESSION[CORE_U_CODE]['orderBy'];
	}
}

CONTINUERESULT:	
if (isset($filter_schoolterm) and ($filter_schoolterm != "")){
	$arr_sql[] =  "term_id = " . $filter_schoolterm;
}	
	
if(count($arr_sql) > 0){
	$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sqlimplode(' AND ', $arr_sql);
}
			
//Here we count the number of results 
//Edit $data to be your query 
	
/*SELECT schedule.* 
	FROM tbl_schedule schedule, 
		tbl_subject subject,
		tbl_room room,
		tbl_employee employee
	WHERE schedule.subject_id = subject.id AND
		schedule.room_id = room.id AND
		schedule.employee_id = employee.id*/
	
$sql_pagination = "SELECT tbl_schedule.* FROM tbl_schedule, tbl_subject
					WHERE tbl_schedule.subject_id = tbl_subject.id
						" .$sqlcondition ;
						
$query_pagination  = mysql_query ($sql_pagination);
$row_ctr = mysql_num_rows($query_pagination);
	
// initial records
if(isset($_REQUEST['pageNum'])){
	$pagenum = ($_REQUEST['pageNum']*$page_rows) - $page_rows; 
}else if($_SESSION[CORE_U_CODE]['pageNum'] != ''){
	$pagenum = ($_SESSION[CORE_U_CODE]['pageNum']*$page_rows) - $page_rows; 
}else{
	$pagenum = '1';
}

if($row_ctr>0){
	//This tells us the page number of our last page 
	$last = ceil($row_ctr/$page_rows); 			
				
	$max = 'limit ' .$pagenum .',' .$page_rows; 	
		
	/*SELECT schedule.* 
		FROM tbl_schedule schedule, 
			tbl_subject subject,
			tbl_room room,
			tbl_employee employee
		WHERE schedule.subject_id = subject.id AND
			schedule.room_id = room.id AND
			schedule.employee_id = employee.id */
	
	
	/*
	$sql = "SELECT schedule.* FROM tbl_schedule schedule, tbl_subject subject
			WHERE schedule.subject_id = subject.id " .$sqlcondition  . $sqlOrderBy . " $max" ;
	*/
	$sql = "SELECT tbl_schedule.*,
					tbl_subject.subject_code, tbl_subject.subject_name, 
					tbl_employee.lastname AS 'proflastname', tbl_employee.firstname AS 'proffirstname', tbl_employee.middlename AS 'profmiddlename', 
					tbl_room.room_no 
			FROM tbl_schedule, tbl_subject, tbl_employee, tbl_room 
			WHERE tbl_schedule.subject_id = tbl_subject.id 
				AND tbl_schedule.employee_id=tbl_employee.id 
				AND tbl_schedule.room_id=tbl_room.id 
			" .$sqlcondition  . $sqlOrderBy . " $max" ;
	
	$result = mysql_query($sql);
	?>                  

	<?php  
    if (mysql_num_rows($result) > 0 ){
		$x = 1;
        ?>
        
<table class="listview">  
	<tr>
		<td colspan="9">
			<a href="#" class="button" title="Account Summary" id="summary" name="summary"><span>Summary</span></a>
		</td>
	</tr>    
	<tr>
		<th class="col_20">&nbsp;</th>
		<th class="col_50"><a href="#" class="sortBy" returnFilter="section_no">Section</a></th>
		<th class="col_100"><a href="#" class="sortBy" returnFilter="subject_code">Subject Code</a></th>
		<th class="col_100"><a href="#" class="sortBy" returnFilter="subject_name">Subject Name</a></th>
		<th class="col_20"><a href="#" class="sortBy" returnFilter="room_no">Room</a></th>
		<th class="col_50"><a href="#" class="sortBy" returnFilter="professor">Professor</a></th>  
		<th class="col_20"><a href="#" class="sortBy" returnFilter="number_of_available">Slots</a></th>    
		<th class="col_100"><a href="#" class="sortBy" returnFilter="schedule_days">Schedule</a></th>                                
		<th class="col_100">Action</th>
	</tr>
        <?php
		while($row = mysql_fetch_array($result)){
			?>
	<tr class="<?=($x%2==0)?"":"highlight";?>">
		<td ><input type="checkbox" name="id" id="id_<?=$row['id']?>" value="<?=$row['id']?>" /></td>
		<td><a href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" class="edtlnk" val="<?=$row["id"]?>"><?="(".$row["id"].") ".$row["section_no"]?></a></td>
		<td><?="(".$row["subject_id"].") ".$row["subject_code"]?></td> 
		<td><?=$row["subject_name"]?></td>
		<td><?=$row["room_no"]?></td>
		<td><?=$row["proflastname"].", ".$row["proffirstname"]." ".$row["profmiddlename"]?></td>
		<td><?=$row["number_of_available"].'/'.$row["number_of_student"]?></td>
		<td><?=getScheduleDays($row["id"])?></td>
		<td class="action">
			<ul>
				<li><a class="profile" href="#" title="View Students" returnId="<?=$row['id']?>" returnComp="<?=$_REQUEST['comp']?>" returnTerm=<?=$filter_schoolterm?>></a></li>
				<li><a class="edit" href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" title="edit"></a></li>
				<li><a class="delete" href="#" onclick="javascript:lnk_deleteItem('id_<?=$row['id']?>');" title="delete/dissolve"></a></li>
				<li>
			<?php
			if($row['publish']=='Y'){
			?>
					<a class="publish" href="#" onclick="javascript:lnk_unpublishItem('id_<?=$row['id']?>');" title="click to unpublished"></a>
			<?php
			}else{
			?>
					<a class="unpublished" href="#" onclick="javascript:lnk_publishItem('id_<?=$row['id']?>');" title="click to publish"></a>
			<?php
			}
			?>
				</li>
			</ul>
		</td>
	</tr>
			<?php   
			$x++;
        }
	}else{
		echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';
	}
    ?>
</table> 

<p id="pagin">

	<?php
	for($x=1;$x<=$last;$x++){
		if($_REQUEST['pageNum'] == $x){
		?>	
	<a href="#"><?=$x?></a>
		<?php		
		}else{
        ?>
	<a href="#list" onclick="updateList(<?=$x?>)"><?=$x?></a>
		<?php
		} 
	} 
	?>
</p>
        
<?php
}else{
	echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';
}
?>

<!-- LIST LOOK UP-->
<div id="dialog" title="Schedule Profile">
    Loading...
</div><!-- #dialog -->

