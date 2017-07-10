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
	
	if(USER_IS_LOGGED != '1')
	{
		header('Location: ../index.php');
	}
	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}
	
	$fieldName = $_REQUEST['fieldName'];
?>
<script type="text/javascript">
/*$(function(){

	// Dialog			
	$('#dialog').dialog({
		autoOpen: false,
		width: 650,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Close": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('.schedule').click(function(){
		var param = $(this).attr("returnId");
		var param2 = $(this).attr("returnComp");
	
		if($('#filter_schoolterm').val() != '' )
		{
			param = param + '&filter_schoolterm=' + $('#filter_schoolterm').val();
		}	
		
		$('#dialog').load('viewer/viewer_com_statement.php?id='+param+'&comp='+param2, null);
		$('#dialog').dialog('open');
		return false;
	});
	
	
});	*/
 
</script>
<script type="text/javascript">
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
	
	$('.filter').change(function() {
		updateList();
		return false;
		});
	
	$('#summary').click(function() {

			//var w=window.open ("pdf_reports/rep108.php?trm="+$('#filter_schoolterm').val()+"&met=account"); 
			var w=window.open ("excel_reports/examples/01simple.php?trm="+$('#filter_schoolterm').val()+"&met=soa_summary")
			return false;
		});
		
	$('#scholar').click(function() {

			var w=window.open ("pdf_reports/rep108.php?trm="+$('#filter_schoolterm').val()+"&met=scholar"); 
			return false;
		});
		
		$('#misc').click(function() {

			var w=window.open ("excel_reports/examples/01simple.php?trm="+$('#filter_schoolterm').val()+"&met=misc_summary"); 
			return false;
		});
		
	$('.schedule').click(function() {
			
			var param = $(this).attr("returnId");
	
		if($('#filter_schoolterm').val() != '' )
		{
			param = param + '&trm=' + $('#filter_schoolterm').val();
		}	
		//alert(param);
			var w=window.open ("pdf_reports/rep108.php?id="+param+"&met=soa"); 
			return false;
		});
	
});
</script>
<?php	
	$arr_sql = array();
	$sqlcondition = '';
	$sqlOrderBy = '';
	if(isset($_REQUEST['list_rows']))
	{
		$_SESSION[CORE_U_CODE]['pageRows'] = $_REQUEST['list_rows'];
		$page_rows = $_SESSION[CORE_U_CODE]['pageRows']; 	
	}		
	else if($_SESSION[CORE_U_CODE]['default_record']!='')
	{
		$page_rows = $_SESSION[CORE_U_CODE]['default_record'];
	}
	else
	{
		$page_rows = DEFAULT_RECORD;
	}	

	$student_filter = $_SESSION[CORE_U_CODE]['student_search'];
	
	if (isset($student_filter['course_id']) && $student_filter['course_id']!= '' && $student_filter['filter'] == '1'){
		$search_field = 'course_id';
		$search_key = $student_filter['course_id']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}		
	
	if (isset($student_filter['course_id']) && $student_filter['course_id']!= '' && $student_filter['filter'] == '0'){
		$search_field = 'course_id';
		$search_key = $student_filter['course_id']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if (isset($student_filter['student_number']) && $student_filter['student_number']!= '' && $student_filter['filter'] == '1'){
		$search_field = 'student_number';
		$search_key = $student_filter['student_number']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}
	
	if (isset($student_filter['student_number']) && $student_filter['student_number']!= '' && $student_filter['filter'] == '0'){
		$search_field = 'student_number';
		$search_key = $student_filter['student_number']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if (isset($student_filter['lastname']) && $student_filter['lastname']!= '' && $student_filter['filter'] == '1'){
		$search_field = 'lastname';
		$search_key = $student_filter['lastname']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}	
	
	if (isset($student_filter['lastname']) && $student_filter['lastname']!= '' && $student_filter['filter'] == '0'){
		$search_field = 'lastname';
		$search_key = $student_filter['lastname']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if (isset($student_filter['firstname']) && $student_filter['firstname']!= '' && $student_filter['filter'] == '1'){
		$search_field = 'firstname';
		$search_key = $student_filter['firstname']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}	
	
	if (isset($student_filter['firstname']) && $student_filter['firstname']!= '' && $student_filter['filter'] == '0'){
		$search_field = 'firstname';
		$search_key = $student_filter['firstname']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if (isset($student_filter['middlename']) && $student_filter['middlename']!= '' && $student_filter['filter'] == '1'){
		$search_field = 'middlename';
		$search_key = $student_filter['middlename']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}		
	
	if (isset($student_filter['middlename']) && $student_filter['middlename']!= '' && $student_filter['filter'] == '0'){
		$search_field = 'middlename';
		$search_key = $student_filter['middlename']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}

	if(isset($fieldName) || $fieldName != '' )
	{
	
		if($fieldName == 'course_name')
		{
			$sqlOrderBy = " ORDER BY  course.$fieldName ". $_REQUEST['orderBy'] ." , student.lastname ASC";
		}
		else
		{
			$sqlOrderBy = ' ORDER BY  '. $fieldName .' '. $_REQUEST['orderBy'];
		}
		
	}else{
		$sqlOrderBy = ' ORDER BY lastname ASC ';
	}



	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : ' AND ' . implode('AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	if($_REQUEST['filter']=='C')
		{
			$sql_pagination = "SELECT student.* 
					FROM tbl_student student,
						 tbl_course course,
						 tbl_student_enrollment_status enrol
					WHERE student.id = enrol.student_id AND student.course_id = course.id AND enrol.term_id = " . $_REQUEST['filter_schoolterm']." ".$sqlcondition ;
		}else{
			$sql_pagination = "SELECT student.* 
					FROM tbl_student student,
						 tbl_course course
					WHERE student.course_id = course.id ".$sqlcondition ;
		}
						
	$query_pagination  = mysql_query ($sql_pagination);
	$row_ctr = mysql_num_rows($query_pagination); 
	
	// initial records
	if(isset($_REQUEST['pageNum']) && $_REQUEST['pageNum'] != '')
	{
		$pagenum = ($_REQUEST['pageNum']*$page_rows) - $page_rows; 
	}
	else
	{
		$pagenum = 0; 
	}
	
	
$str_filter_arr = array();
if(isset($_SESSION[CORE_U_CODE]['student_search']))
{
?>      
    <div id="search_container">
        <?php
            if($student_filter['filter'] == '0')
            {
                $str_filter_arr[] = "<h4>Search Results: (Exact Match)</h4>";
            }
            else if($student_filter['filter'] == '1')
            {
                $str_filter_arr[] = "<h4>Search Results: (First Character Match)</h4>";
            }
            else
            {
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
} // serach filter if  
	
	if($row_ctr>0)
	{
		//This tells us the page number of our last page 
		$last = ceil($row_ctr/$page_rows); 			
				
		$max = 'limit ' .$pagenum .',' .$page_rows; 
		if($_REQUEST['filter']=='C')
		{
			$sql = "SELECT student.* 
					FROM tbl_student student,
						 tbl_course course,
						 tbl_student_enrollment_status enrol
					WHERE student.id = enrol.student_id AND student.course_id = course.id AND enrol.term_id = " . $_REQUEST['filter_schoolterm']." ".$sqlcondition  . $sqlOrderBy . " $max" ;
		}else{
			$sql = "SELECT student.* 
					FROM tbl_student student,
						 tbl_course course
					WHERE student.course_id = course.id ".$sqlcondition  . $sqlOrderBy . " $max" ;
		}
	
		$result = mysql_query($sql);
 
        if (mysql_num_rows($result) > 0 )
        {
            $x = 1;
			$cnt = 0;
        if(ACCESS_ID==1)
		{
		?>
       <a href="#" class="button" title="Account Summary" id="summary" name="summary"><span>Summary</span></a>
       <a href="#" class="button" title="Scholarship Summary" id="scholar" name="scholar"><span>Scholarships</span></a>
       <a href="#" class="button" title="Miscelleneous Summary" id="misc" name="scholar"><span>Miscelleneous</span></a>
       <?php } ?>
        <table class="listview"> 
           
          <tr>
              <th class="col_70"><a href="#" class="sortBy" returnFilter="student_number">Student Number</a></th>
              <th class="col_100"><a href="#" class="sortBy" returnFilter="lastname">Full Name</a></th>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="course_name">Course</a></th>
              <th class="col_50">View SOA</th>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
				//if(checkIfStudentIsEnrollByTerm($row['id'],$_REQUEST['filter_schoolterm']))
				//{
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td ><?=$row["student_number"]?></td> 
                <td><?=$row["lastname"].", ".$row["firstname"]." " .$row["middlename"]?></td>
                <td><?=getCourseName($row['course_id'])?></td>
                <td class="action">
                    <ul>
                        <li><a class="schedule" href="#" title="View Statement" returnId="<?=$row['id']?>" returnComp="<?=$_REQUEST['comp']?>"></a></li>
                       
                    </ul>
                </td>
            </tr>
        <?php  
				$cnt++;	//}  
			$x++;          
           }
        }
        else 
        {

                echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';
        }
		if($cnt == 0)
		{
        ?>
        <tr><td colspan="4">No Enrolled Students</td></tr>
         <?php } ?>
        </table> 
        <p id="pagin">

        	<?php
            for($x=1;$x<=$last;$x++) {
                if ($_REQUEST['pageNum'] == $x) {
            ?>	
                <a href="#"><?=$x?></a>
            <?php		
                } else {
            ?>
                <a href="#list" onclick="updateList(<?=$x?>)"><?=$x?></a>
            <?php } 
            } 
            ?>
        
        </p>
        
<?php
	} 
	else 
	{
		echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';
	}
?>
<!-- LIST LOOK UP
<div id="dialog" title="Student Grade">
    Loading...
</div><!-- #dialog -->
