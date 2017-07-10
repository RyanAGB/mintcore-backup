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

if(!isset($_REQUEST['comp']))
{
include_once("../config.php");
include_once("../includes/functions.php");
include_once("../includes/common.php");
}


if(USER_IS_LOGGED != '1')
{
	echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main
}
else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}



$page_title = 'Manage Student Grade Report';
$pagination = 'Report  > Manage Student Grade Report';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$building_code		= $_REQUEST['building_code'];
$building_name		= $_REQUEST['building_name'];
$address			= $_REQUEST['address'];
$publish			= $_REQUEST['publish'];


if($view == 'edit')
{
	$str_arr = array();
	$canP = '1';
	$pagenum = $_REQUEST['pagenum'];
	$field = $_REQUEST['filter_field'];
	$ord = $_REQUEST['filter_order'];
	
	if(isset($_REQUEST['page_rows']))
	{
		$page_rows = $_REQUEST['page_rows']; 	
	}		
	else
	{
		$page_rows = 10;
	}
	
	if ($_REQUEST['course_id']!= '' && $_REQUEST['filter'] == '1'){
		$search_field = 'course_id';
		$search_key = $_REQUEST['course_id']; 
		$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
	}		
	
	if ($_REQUEST['course_id']!= '' && $_REQUEST['filter'] == '0'){
		$search_field = 'course_id';
		$search_key = $_REQUEST['course_id']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if ($_REQUEST['student_number']!= '' && $_REQUEST['filter'] == '1'){
		$search_field = 'student_number';
		$search_key = $_REQUEST['student_number']; 
		$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
	}
	
	if ($_REQUEST['student_number']!= '' && $_REQUEST['filter'] == '0'){
		$search_field = 'student_number';
		$search_key = $_REQUEST['student_number']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if ($_REQUEST['lastname']!= '' && $_REQUEST['filter'] == '1'){
		$search_field = 'lastname';
		$search_key = $_REQUEST['lastname']; 
		$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
	}	
	
	if ($_REQUEST['lastname']!= '' && $_REQUEST['filter'] == '0'){
		$search_field = 'lastname';
		$search_key = $_REQUEST['lastname']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if ($_REQUEST['firstname']!= '' && $_REQUEST['filter'] == '1'){
		$search_field = 'firstname';
		$search_key = $_REQUEST['firstname']; 
		$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
	}	
	
	if ($_REQUEST['firstname']!= '' && $_REQUEST['filter'] == '0'){
		$search_field = 'firstname';
		$search_key = $_REQUEST['firstname']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if ($_REQUEST['middlename']!= '' && $_REQUEST['filter'] == '1'){
		$search_field = 'middlename';
		$search_key = $_REQUEST['middlename']; 
		$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
	}		
	
	if ($_REQUEST['middlename']!= '' && $_REQUEST['filter'] == '0'){
		$search_field = 'middlename';
		$search_key = $_REQUEST['middlename']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if($field != '' )
	{
	
		if($field == 'course_name')
		{
			$sqlOrderBy = " ORDER BY  course.$field ". $ord ." , student.firstname ASC";
		}
		else
		{
			$sqlOrderBy = ' ORDER BY  '. $field .' '.$ord ;
		}
	}
	
	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? 'WHERE ' . $arr_sql[0] : 'WHERE ' . implode('AND ', $arr_sql);
	}
					
		$sql_pagination = "SELECT * FROM tbl_student
						" .$sqlcondition ;
						
	$query_pagination  = mysql_query ($sql_pagination);
	$row_ctr = mysql_num_rows($query_pagination); 
	
	// initial records
	if($pagenum != 0)
	{
		$pageNum = ($pagenum*$page_rows) - $page_rows; 
	}
	else
	{
		$pageNum = 0; 
	}

	if($row_ctr>0)
	{
		$last = ceil($row_ctr/$page_rows); 			
				
		$max = 'limit ' .$pageNum .',' .$page_rows;
		
		$sql = "SELECT student.* 
					FROM 
						tbl_student student,
						tbl_course course
					WHERE 
						student.course_id = course.id
							" .$sqlcondition . $sqlOrderBy ." $max";
	
		$result = mysql_query($sql);

	$str_arr[] = '<input type="hidden" name="con" id="con" value="'.$sqlcondition.'" />';	
		if (mysql_num_rows($result) > 0 )
        {
            $x = 0;

      
        
        $str_arr[] = '<table class="listview">';      
          $str_arr[] = '<tr>';
              $str_arr[] ='<th class="col_150"><a href="#" class="sortBy" returnFilter="student_number">Student Number</a></th>';
              $str_arr[] ='<th class="col_450"><a href="#" class="sortBy" returnFilter="firstname">Full Name</a></th>';
              $str_arr[] ='<th class="col_450"><a href="#" class="sortBy" returnFilter="course_name">Course</a></th>';
			  $str_arr[] ='<th class="col_50">Action</th>';
			 
          $str_arr[] ='</tr>';

			 while($row = mysql_fetch_array($result)){ 
            $str_arr[] ='<tr>';
            
                $str_arr[] ='<td class="col_150">'.$row['student_number'].'</a></td>'; 
                 $str_arr[] ='<td class="col_350">'.$row['firstname']." ".$row['middlename']." ".$row['lastname'].'</a></td>';
                  $str_arr[] ='<td class="col_350">'.getCourseName($row['course_id']).'</a></td>'; 
                $str_arr[] ='<td class="col_50">';
                    $str_arr[] ='<ul>';
                        $str_arr[] ='<li><a class="profile" href="#" title="View Profile" returnId="'.$row['id'].'"></a></li>';
                    $str_arr[] ='</ul>';
                $str_arr[] ='</td>';
            $str_arr[] ='</tr>';
        
		}        
        }
        else 
        {
                $norec = '<h4>No records found</h4>';
        }

        $str_arr[] ='</table>'; 
		
		$fil_list = implode('', $str_arr);
}
}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_student_grades_report.php';
?>