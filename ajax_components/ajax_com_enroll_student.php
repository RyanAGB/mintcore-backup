<?php
include_once("../config.php");
include_once("../includes/functions.php");
include_once("../includes/common.php");		
	
if(USER_IS_LOGGED != '1'){
		header('Location: ../index.php');
}else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])){
	header('Location: ../forbid.html');
}
	
$fieldName = $_REQUEST['fieldName'];
?>

<script type="text/javascript">
$(function(){

	// Dialog			

	$("a[name='stud_id']").click(function(){
		var param = $(this).attr("returnId");
		var param2 = $(this).attr("returnComp");
		clearTabs();
		
		$.ajax({
				type: "POST",
				data: "mod=update",
				url: "ajax_components/ajax_com_slot_updater.php",
				success: function(msg){
					if (msg != ''){
						//alert(msg);
					}
				}
				});
		
		$('#enroll').addClass('active');
		$('#pageRows').hide();
		
		$('#view').val('enroll');
		$('#action').val('enroll');	
		$('#student_id').val($(this).attr("returnId"));	
		//$('#box_container').html(loading);	
		
		
		//if($(this).attr("returnStat")=='E'){
		if(1==1){
			$('#box_container').html(loading);
			$('#box_container').load('ajax_components/ajax_com_enroll_student_sched.php?student_id='+param+'&comp='+param2, null);
		}else{
			$('#box_container').html(loading);
			$.ajax({
				type: "POST",
				data: "stud_id="+$(this).attr("returnId"),
				url: "ajax_components/ajax_com_enroll_process.php",
				success: function(msg){
					
					
					$('#box_container').load('ajax_components/ajax_com_student_reserve_subject.php?stud_id='+param+'&comp='+param2, null);
					
					
				}
			});
		}
		//
		//$('#box_container').load('ajax_components/ajax_com_enroll_student_sched.php?student_id='+param+'&comp='+param2, null);
		
		return false;
	});  
	
});	
 $(document).ready(function(){  

	$('.sortBy').click(function(){
	
		if($('#filter_order').val() == '' || $('#filter_order').val() == 'DESC')
		{
			var order = 'ASC';
		}
		else
		{
			var order = 'DESC';
		}
		
		$('#filter_field').val($(this).attr('returnFilter'));
		$('#filter_order').val(order);
		updateList();
		return false;
	});
	
});
</script>
<?php	
$arr_sql = array();
$sqlcondition = '';
$sqlOrderBy = '';
if(isset($_REQUEST['list_rows'])){
	$_SESSION[CORE_U_CODE]['pageRows'] = $_REQUEST['list_rows'];
	$page_rows = $_SESSION[CORE_U_CODE]['pageRows']; 	
}else if($_SESSION[CORE_U_CODE]['default_record']!=''){
	$page_rows = $_SESSION[CORE_U_CODE]['default_record'];
}else{
	$page_rows = DEFAULT_RECORD;
}	
$student_filter = $_SESSION[CORE_U_CODE]['student_search'];

if(isset($student_filter['course_id']) && $student_filter['course_id']!= '' && $student_filter['filter'] == '1'){
	$search_field = 'course_id';
	$search_key = $student_filter['course_id']; 
	$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
}		

if(isset($student_filter['course_id']) && $student_filter['course_id']!= '' && $student_filter['filter'] == '0'){
	$search_field = 'course_id';
	$search_key = $student_filter['course_id']; 
	$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
}

if(isset($student_filter['student_number']) && $student_filter['student_number']!= '' && $student_filter['filter'] == '1'){
	$search_field = 'student_number';
	$search_key = $student_filter['student_number']; 
	$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
}

if(isset($student_filter['student_number']) && $student_filter['student_number']!= '' && $student_filter['filter'] == '0'){
	$search_field = 'student_number';
	$search_key = $student_filter['student_number']; 
	$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
}

if(isset($student_filter['lastname']) && $student_filter['lastname']!= '' && $student_filter['filter'] == '1'){
	$search_field = 'lastname';
	$search_key = $student_filter['lastname']; 
	$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
}	

if(isset($student_filter['lastname']) && $student_filter['lastname']!= '' && $student_filter['filter'] == '0'){
	$search_field = 'lastname';
	$search_key = $student_filter['lastname']; 
	$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
}

if(isset($student_filter['firstname']) && $student_filter['firstname']!= '' && $student_filter['filter'] == '1'){
	$search_field = 'firstname';
	$search_key = $student_filter['firstname']; 
	$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
}	

if(isset($student_filter['firstname']) && $student_filter['firstname']!= '' && $student_filter['filter'] == '0'){
	$search_field = 'firstname';
	$search_key = $student_filter['firstname']; 
	$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
}

if(isset($student_filter['middlename']) && $student_filter['middlename']!= '' && $student_filter['filter'] == '1'){
	$search_field = 'middlename';
	$search_key = $student_filter['middlename']; 
	$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
}		

if(isset($student_filter['middlename']) && $student_filter['middlename']!= '' && $student_filter['filter'] == '0'){
	$search_field = 'middlename';
	$search_key = $student_filter['middlename']; 
	$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
}

if(isset($fieldName) || $fieldName != '' ){
	if($fieldName == 'course_name'){
		$sqlOrderBy = " ORDER BY  course.$fieldName ". $_REQUEST['orderBy'] ." , student.lastname ASC";
	}else{
		$sqlOrderBy = ' ORDER BY  '. $fieldName .' '. $_REQUEST['orderBy'];
	}
		
}else if($_SESSION[CORE_U_CODE]['fieldName']!='' || $_SESSION[CORE_U_CODE]['orderBy'] !=''){
	if($fieldName == 'course_name'){
		$sqlOrderBy = " ORDER BY  course.". $_SESSION[CORE_U_CODE]['fieldName'] ." ". $_SESSION[CORE_U_CODE]['orderBy'] ." , student.lastname ASC";
	}else{
		$sqlOrderBy = ' ORDER BY  '. $_SESSION[CORE_U_CODE]['fieldName'] .' '. $_SESSION[CORE_U_CODE]['orderBy'];
	}
}

if(count($arr_sql) > 0){
	$sqlcondition =  count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : ' AND ' . implode('AND ', $arr_sql);
}

		
//Here we count the number of results 
//Edit $data to be your query 
if($ctr_reserve != '0'){
	$sql_pagination = "SELECT student.* 
					FROM tbl_student student,
						 tbl_course course
					WHERE student.course_id = course.id"
							 .$sqlcondition ;
						
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
	
	$str_filter_arr = array();
	$str_basic_filter_arr = array();
	
	if(isset($_SESSION[CORE_U_CODE]['student_search'])){
	?>      
		<div id="search_container">
			<?php
			if($student_filter['filter'] == '0'){
				$str_filter_arr[] = "<h4>Search Results: (Exact Match)</h4>";
			}else if($student_filter['filter'] == '1'){
				$str_filter_arr[] = "<h4>Search Results: (First Character Match)</h4>";
			}else{
				$str_filter_arr[] = "<h4>Search Results:</h4>";		
			}
			
			$student_filter['course_id'] 	!= ''? $str_filter_arr[] = 'Course :' . getCourseName($student_filter['course_id']):'';
			$student_filter['student_number']!= ''? $str_filter_arr[] = 'Student Number : ' . $student_filter['student_number']:'';
			$student_filter['lastname'] 	!= ''? $str_filter_arr[] = 'Lastname : ' . $student_filter['lastname']:''; 
			$student_filter['firstname'] 	!= ''? $str_filter_arr[] = 'Firstname : ' . $student_filter['firstname']:''; 
			$student_filter['middlename'] 	!= ''? $str_filter_arr[] = 'Middlename : ' . $student_filter['middlename']:'';      
		
			echo implode('<br />',$str_filter_arr);
			?>
		</div>
		<div class="container">
			<a href="index.php?comp=mn_student&clear=1" class="search_button" id="dialog_link"><span>Clear Search</span></a>        
		</div>
    
	<?php
	}
	
	// search filter if
	if($row_ctr>0){
		//This tells us the page number of our last page 
		$last = ceil($row_ctr/$page_rows); 			
				
		$max = 'limit ' .$pagenum .',' .$page_rows; 	
		
		$sql = "SELECT student.* 
					FROM tbl_student student,
						 tbl_course course
					WHERE student.course_id = course.id"
							 . $sqlcondition  . $sqlOrderBy . " $max" ;

		$result = mysql_query($sql);
	  
		if(!checkIfSchoolFeeIsComplete($id)){
			if (mysql_num_rows($result) > 0 ){
				$x = 1;
			?>
			
			<table class="listview">      
			  <tr>
				  <th class="col_70"><a href="#" class="sortBy" returnFilter="student_number">Student Number</th>
				  <th class="col_100"><a href="#" class="sortBy" returnFilter="lastname">Full Name</th>
				  <th class="col_150"><a href="#" class="sortBy" returnFilter="course_name">Course</th>
				  <th class="col_20">Total Units</th>            
				  <th class="col_50">Action</th>
			  </tr>
			<?php
				while($row = mysql_fetch_array($result)){ 
					$stat = checkIfStudentIsEnroll($row["id"])?'E':'R';
					//if($stat=='E')
					//{
			?>
				<tr class="<?=($x%2==0)?"":"highlight";?>">
					<td><?=$stat.$row["student_number"]?></td> 
					<td><?=$row["lastname"].", ".$row["firstname"]." " .$row["middlename"]?></td>
					<td><?=getCourseName($row['course_id'])?></td> 
					<td><?=getStudentTotalUnitsEnrolled($row["id"])?></td>  
					<td class="action">
						<ul>
							<li><a class="curSub" href="#" name="stud_id" returnId="<?=$row['id']?>" title="Enroll" returnComp="<?=$_REQUEST['comp']?>" returnStat = "<?=$stat?>"></a></li>
						</ul>
					</td>
				</tr>
			<?php   
				$x++;   
					//}
			   }
			}else{
				echo '<div id="message_container"><h4>No student records found</h4></div><p id="formbottom"></p>';
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
			echo '<div id="message_container"><h4>Incomplete School Fee Set-Up. Modify set-up first.</h4></div><p id="formbottom"></p>';
		}
	}else{
		echo '<div id="message_container"><h4>No student records found</h4></div><p id="formbottom"></p>';
	}
}else{
	echo '<div id="message_container"><h4>No student records found</h4></div><p id="formbottom"></p>';
}
?>
