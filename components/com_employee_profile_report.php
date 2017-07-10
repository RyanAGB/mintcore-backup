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

if(USER_IS_LOGGED != '1')
{
	echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main
}


$page_title = 'Manage Employee Report';
$pagination = 'Report  > Manage Employee Report';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

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
	
	if ($_REQUEST['department_id']!= '' && $_REQUEST['filter'] == '1'){
		$search_field = 'department_id';
		$search_key = $_REQUEST['department_id']; 
		$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
	}		
	
	if ($_REQUEST['department_id']!= '' && $_REQUEST['filter'] == '0'){
		$search_field = 'department_id';
		$search_key = $_REQUEST['department_id']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if ($_REQUEST['emp_id_number']!= '' && $_REQUEST['filter'] == '1'){
		$search_field = 'emp_id_number';
		$search_key = $_REQUEST['emp_id_number']; 
		$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
	}
	
	if ($_REQUEST['emp_id_number']!= '' && $_REQUEST['filter'] == '0'){
		$search_field = 'emp_id_number';
		$search_key = $_REQUEST['emp_id_number']; 
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
	
		if($field == 'department_name')
		{
			$sqlOrderBy = " ORDER BY  department.$field ". $ord ." , employee.firstname ASC";
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
					
		$sql_pagination = "SELECT * FROM tbl_employee
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
		
		$sql = "SELECT employee.* 
					FROM 
						tbl_employee employee,
						tbl_department department
					WHERE
						employee.department_id = department.id
							" .$sqlcondition . $sqlOrderBy . " $max";
	
		$result = mysql_query($sql);
		
		$str_arr[] = '<input type="hidden" name="con" id="con" value="'.$sqlcondition.'" />';	
		if (mysql_num_rows($result) > 0 )
        {
            $x = 0;

      
        
        $str_arr[] = '<table class="listview">';      
          $str_arr[] = '<tr>';
              $str_arr[] ='<th class="col_150"><a href="#" class="sortBy" returnFilter="emp_id_number">Employee Number</a></th>';
              $str_arr[] ='<th class="col_450"><a href="#" class="sortBy" returnFilter="firstname">Full Name</a></th>';
              $str_arr[] ='<th class="col_450"><a href="#" class="sortBy" returnFilter="department_name">Department</a></th>';
			  $str_arr[] ='<th class="col_50">Action</a></th>';
			 
          $str_arr[] ='</tr>';

			 while($row = mysql_fetch_array($result)){ 
            $str_arr[] ='<tr>';
            
                $str_arr[] ='<td class="col_150">'.$row['emp_id_number'].'</a></td>'; 
                 $str_arr[] ='<td class="col_350">'.$row['firstname']." ".$row['middlename']." ".$row['lastname'].'</a></td>';
                  $str_arr[] ='<td class="col_350">'.getDeptName($row['department_id']).'</a></td>'; 
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
$content_template = 'components/block/blk_com_employee_profile_report.php';
?>