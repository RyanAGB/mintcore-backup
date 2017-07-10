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
?>

<script type="text/javascript">
$(function(){

	// Dialog			

	$("a[name='stud_id']").click(function(){
		var param = $(this).attr("returnId")+'&comp='+$(this).attr("returnComp");
		clearTabs();
		$('#enroll').addClass('active');
		$('#view').val('enroll');
		$('#action').val('enroll');	
		$('#student_id').val($(this).attr("returnId"));		
		$('#box_container').html(loading);
		$('#box_container').load('ajax_components/ajax_com_pa_pending_payment_history.php?student_id='+param, null);
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
	$page_rows = $_REQUEST['list_rows']; 	
}		
else
{
	$page_rows = 10;
}

$student_filter = $_SESSION[CORE_U_CODE]['student_search'];
$student_basic_filter = $_SESSION[CORE_U_CODE]['student_basic_search'];

if (isset($student_basic_filter['student_no_bsc']) && $student_basic_filter['student_no_bsc']!= '' && $student_basic_filter['filter'] == '1'){
	$search_field = 'student_no_bsc';
	$search_key = $student_basic_filter['student_no_bsc']; 
	$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
}

if (isset($student_basic_filter['student_no_bsc']) && $student_basic_filter['student_no_bsc']!= '' && $student_basic_filter['filter'] == '0'){
	$search_field = 'student_no_bsc';
	$search_key = $student_basic_filter['student_no_bsc']; 
	$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
}

if (isset($student_filter['course_id']) && $student_filter['course_id']!= '' && $student_filter['filter'] == '1'){
	$search_field = 'course_id';
	$search_key = $student_filter['course_id']; 
	$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
}		

if (isset($student_filter['course_id']) && $student_filter['course_id']!= '' && $student_filter['filter'] == '0'){
	$search_field = 'course_id';
	$search_key = $student_filter['course_id']; 
	$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
}

if (isset($student_filter['student_number']) && $student_filter['student_number']!= '' && $student_filter['filter'] == '1'){
	$search_field = 'student_number';
	$search_key = $student_filter['student_number']; 
	$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
}

if (isset($student_filter['student_number']) && $student_filter['student_number']!= '' && $student_filter['filter'] == '0'){
	$search_field = 'student_number';
	$search_key = $student_filter['student_number']; 
	$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
}

if (isset($student_filter['lastname']) && $student_filter['lastname']!= '' && $student_filter['filter'] == '1'){
	$search_field = 'lastname';
	$search_key = $student_filter['lastname']; 
	$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
}	

if (isset($student_filter['lastname']) && $student_filter['lastname']!= '' && $student_filter['filter'] == '0'){
	$search_field = 'lastname';
	$search_key = $student_filter['lastname']; 
	$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
}

if (isset($student_filter['firstname']) && $student_filter['firstname']!= '' && $student_filter['filter'] == '1'){
	$search_field = 'firstname';
	$search_key = $student_filter['firstname']; 
	$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
}	

if (isset($student_filter['firstname']) && $student_filter['firstname']!= '' && $student_filter['filter'] == '0'){
	$search_field = 'firstname';
	$search_key = $student_filter['firstname']; 
	$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
}

if (isset($student_filter['middlename']) && $student_filter['middlename']!= '' && $student_filter['filter'] == '1'){
	$search_field = 'middlename';
	$search_key = $student_filter['middlename']; 
	$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
}		

if (isset($student_filter['middlename']) && $student_filter['middlename']!= '' && $student_filter['filter'] == '0'){
	$search_field = 'middlename';
	$search_key = $student_filter['middlename']; 
	$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
}

if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' )
{
	$sqlOrderBy = ' ORDER BY  '. $_REQUEST['fieldName'] .' '. $_REQUEST['orderBy'];
}


if(count($arr_sql) > 0)
{
	$sqlcondition = count($arr_sql) == 1 ? ' ' . $arr_sql[0] : ' ' . implode('AND ', $arr_sql);
}

		
//Here we count the number of results 
//Edit $data to be your query 
if($ctr_reserve != '0')
{
	$sql_pagination = "SELECT DISTINCT student_id FROM tbl_student_reserve_subject WHERE student_id =".STUDENT_ID 
							 .$sqlcondition ;
						
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
	if($row_ctr>0)
	{
		//This tells us the page number of our last page 
		$last = ceil($row_ctr/$page_rows); 			
				
		$max = 'limit ' .$pagenum .',' .$page_rows; 	
		
		$sql = "SELECT DISTINCT student_id FROM tbl_student_reserve_subject"
							 . $sqlcondition  . $sqlOrderBy . " WHERE student_id =". STUDENT_ID ." $max"  ;
	
		$result = mysql_query($sql);
	?>     
	<?php
	$str_filter_arr = array();
	$str_basic_filter_arr = array();
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
        <a href="index.php?comp=mn_cs_payments&clear=1" class="search_button" id="dialog_link"><span>Clear Search</span></a>        
    </div>
    
<?php
}
else if(isset($_SESSION[CORE_U_CODE]['student_basic_search']))
{
?>      
    <div id="search_container">
        <?php
            if($student_basic_filter['filter'] == '0')
            {
                $str_basic_filter_arr[] = "<h4>Search Results: (Exact Match)</h4>";
            }
            else
            {
                $str_basic_filter_arr[] = "<h4>Search Results: All</h4>";		
            }
            
            $student_basic_filter['student_number']!= ''? $str_basic_filter_arr[] = 'Student Number : ' . $student_basic_filter['student_number']:'';      
        
            echo implode('<br />',$str_basic_filter_arr);
        ?>
    </div>
    <div class="container">
        <a href="index.php?comp=mn_cs_payments&clear=2" class="search_button" id="dialog_link"><span>Clear Search</span></a>        
    </div>
    
<?php
}  // search filter if
?>    
        <?php  
        if (mysql_num_rows($result) > 0 )
        {
            $x = 1;
        ?>
        
        <table class="listview">      
          <tr>
              <th class="col_70">Student Number</th>
              <th class="col_100">Full Name</th>
              <th class="col_150">Course</th>            
              <th class="col_50">Action</th>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td><?=getStudentNumber($row["student_id"])?></td> 
                <td><?=getStudentFullName($row["student_id"])?></td>
                <td><?=getStudentCourse($row["student_id"])?></td>  
                <td class="action">
                    <ul>
                        <li><a class="curSub" href="#" name="stud_id" returnId="<?=$row['student_id']?>" returnComp="<?=$_REQUEST['comp']?>" title="Payment"></a></li>
                    </ul>
                </td>
            </tr>
        <?php   
			$x++;         
           }
        }
        else 
        {
                 echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';
        }
        ?>
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
}
else
{
	 echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';
}
?>
