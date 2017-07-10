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
	
	$fieldNames = $_REQUEST['fieldName'];
	
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
?>
<script type="text/javascript">
$(function(){
	$('#dialog').dialog({
		autoOpen: false,
		width: 600,
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
	$('#grade').click(function(){
		var fil = $('#filter_schoolterm').val();
		var param = $('#comp').val();
		$('#dialog').load('viewer/viewer_encode_grade.php?term='+fil+'&comp='+param, null);
		$('#dialog').dialog('open');
		return false;
	});
});

$(function(){	
	$('.profile').click(function(){
		$('#prof_id').val($(this).attr('returnId'))
		updateList('1',$(this).attr('returnId'));
	});

	$('#filter_schoolterm').change(function(){
		$('#schoolterm_id').val($(this).val())
	});	
	
	$('#filterdept').change(function(){
		$('#filter_dept').val($(this).val());
		updateList2();
	});	
	
	$('#page_rows').change(function(){
		$('#rows').val($('#page_rows').val());
		$('#page').val(1);
		updateList2();
	});	
	
});	

function updateList(pageNum,prof_id)
{

	var param = '';
	if(pageNum != '' && pageNum != undefined)
	{
		param = param + '&pageNum=' + pageNum;
	}

	if($('#schoolterm_id').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#schoolterm_id').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
	if($('#filter_dept').val() != '' && $('#filter_dept').val() != undefined )
	{
		param = param + '&filterdept=' + $('#filter_dept').val();
	}

	param = param + '&prof_id=' + prof_id;
	
	$('#rows').val($('#page_rows').val());
	$('#schoolterm_id').val($('#filter_schoolterm').val());

	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_professor_encode_grade_list.php?param=1' + param, null);
}

function updateList2(pageNum)
{

	var param = '';
	
	$('#page').val(pageNum);
	if($('#page').val() != '' && $('#page').val() != undefined)
	{
		param = param + '&pageNum=' + $('#page').val();
	}
	
	if($('#filter_field').val() != '' && $('#filter_field').val() != undefined && $('#filter_order').val() != '' && $('#filter_order').val() != undefined )
	{
		param =  param + '&fieldName=' + $('#filter_field').val() + '&orderBy=' + $('#filter_order').val();
		
	}
	
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#page_rows').val() + param;
	}
	if($('#filter_dept').val() != '' && $('#filter_dept').val() != undefined )
	{
		param = param + '&filterdept=' + $('#filter_dept').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
	if($('#schoolterm_id').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#schoolterm_id').val();
	}
	//alert(param);
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_professor_encode_grade.php?param=1' + param, null);
}
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
		updateList2();

	});
	
});

</script>
<?php
	$arr_sql = array();
	$sqlcondition = '';
	$sqlOrderBy = '';
	/*if(isset($_REQUEST['list_rows']))
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
	}*/	
		$prof_filter = $_SESSION[CORE_U_CODE]['prof_search'];
			
	if (isset($prof_filter['emp_id_number']) && $prof_filter['emp_id_number']!= '' && $prof_filter['filter'] == '1'){
		$search_field = 'emp_id_number';
		$search_key = $prof_filter['emp_id_number']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}	
	if (isset($prof_filter['emp_id_number']) && $prof_filter['emp_id_number']!= '' && $prof_filter['filter'] == '0'){
		$search_field = 'emp_id_number';
		$search_key = $prof_filter['emp_id_number']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if (isset($prof_filter['lastname']) && $prof_filter['lastname']!= '' && $prof_filter['filter'] == '1'){
		$search_field = 'lastname';
		$search_key = $prof_filter['lastname']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}	
	if (isset($prof_filter['lastname']) && $prof_filter['lastname']!= '' && $prof_filter['filter'] == '0'){
		$search_field = 'lastname';
		$search_key = $prof_filter['lastname']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}		

	if (isset($prof_filter['firstname']) && $prof_filter['firstname']!= '' && $prof_filter['filter'] == '1'){
		$search_field = 'firstname';
		$search_key = $prof_filter['firstname']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}	
	if (isset($prof_filter['firstname']) && $prof_filter['firstname']!= '' && $prof_filter['filter'] == '0'){
		$search_field = 'firstname';
		$search_key = $prof_filter['firstname']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if (isset($prof_filter['middlename']) && $prof_filter['middlename']!= '' && $prof_filter['filter'] == '1'){
		$search_field = 'middlename';
		$search_key = $prof_filter['middlename']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}	
	if (isset($prof_filter['middlename']) && $prof_filter['middlename']!= '' && $prof_filter['filter'] == '0'){
		$search_field = 'middlename';
		$search_key = $prof_filter['middlename']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if (isset($_REQUEST['filterdept']) && $_REQUEST['filterdept']!= ''){
		$arr_sql[] = " emp.department_id=" . addslashes($_REQUEST['filterdept']);
	}		
	
	if(isset($fieldNames) || $fieldNames != '' )
	{
	
		if($fieldNames == 'department_name')
		{
			$sqlOrderBy = " ORDER BY  department.$fieldNames ". $_REQUEST['orderBy'] ." , emp.lastname ASC";
		}
		else
		{
			$sqlOrderBy = ' ORDER BY  '. $fieldNames .' '. $_REQUEST['orderBy'];
		}
		
	}
	else if($_SESSION[CORE_U_CODE]['fieldName']!='' || $_SESSION[CORE_U_CODE]['orderBy'] !='')
	{
		if($fieldNames == 'department_name')
		{
			$sqlOrderBy = " ORDER BY  department.". $_SESSION[CORE_U_CODE]['fieldName'] ." ". $_SESSION[CORE_U_CODE]['orderBy'] ." , emp.lastname ASC";
		}
		else
		{
		$sqlOrderBy = ' ORDER BY  '. $_SESSION[CORE_U_CODE]['fieldName'] .' '. $_SESSION[CORE_U_CODE]['orderBy'];
		}
	}

	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : ' AND ' . implode(' AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	
	$sql_pagination = "SELECT emp.*,
							user.username,
							user.password,
							user.access_id,
							user.blocked,
							user.failed_logs,
							user.no_of_block_times
						FROM 
							tbl_employee emp, 
							tbl_user user,
							tbl_department department 
						WHERE 
							user.id = emp.user_id AND 
							emp.employee_type = '2' AND
							emp.department_id = department.id
						" .$sqlcondition ;
						
	$query_pagination  = mysql_query ($sql_pagination);
	$row_ctr = mysql_num_rows($query_pagination); 
	
	// initial records
	if(isset($_REQUEST['pageNum']))
	{
		$pagenum = ($_REQUEST['pageNum']*$page_rows) - $page_rows; 
	}
	else if($_SESSION[CORE_U_CODE]['pageNum'] != '')
	{
		$pagenum = ($_SESSION[CORE_U_CODE]['pageNum']*$page_rows) - $page_rows; 
	}
	else
	{
		$pagenum = '1';
	}
	
	$str_filter_arr = array();
if(isset($_SESSION[CORE_U_CODE]['prof_search']))
{
?>      
    <div id="search_container">
        <?php
            if($prof_filter['filter'] == '0')
            {
                $str_filter_arr[] = "<h4>Search Results: (Exact Match)</h4>";
            }
            else if($prof_filter['filter'] == '1')
            {
                $str_filter_arr[] = "<h4>Search Results: (First Character Match)</h4>";
            }
            else
            {
                $str_filter_arr[] = "<h4>Search Results:</h4>";		
            }
            
            $prof_filter['emp_id_number']!= ''? $str_filter_arr[] = 'Professor Code : ' . $prof_filter['emp_id_number']:'';
            $prof_filter['lastname'] 	!= ''? $str_filter_arr[] = 'Lastname : ' . $prof_filter['lastname']:''; 
            $prof_filter['firstname'] 	!= ''? $str_filter_arr[] = 'Firstname : ' . $prof_filter['firstname']:''; 
            $prof_filter['middlename'] 	!= ''? $str_filter_arr[] = 'Middlename : ' . $prof_filter['middlename']:'';      
        
            echo implode('<br />',$str_filter_arr);
        ?>
    </div>
    <div class="container">
        <a href="index.php?comp=mn_professor&clear=1" class="search_button" id="dialog_link"><span>Clear Search</span></a>        
    </div>
    
<?php
} // serach filter if
	
	if($row_ctr>0)
	{
		//This tells us the page number of our last page 
		$last = ceil($row_ctr/$page_rows); 			
				
		$max = 'limit ' .$pagenum .',' .$page_rows; 	
		
		$sql = "SELECT emp.*,
							user.username,
							user.password,
							user.access_id,
							user.blocked,
							user.failed_logs,
							user.no_of_block_times
						FROM 
							tbl_employee emp, 
							tbl_user user,
							tbl_department department 
						WHERE 
							user.id = emp.user_id AND 
							emp.employee_type = '2' AND
							emp.department_id = department.id
							" .$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);

?>                  

<?php

if(isset($_SESSION[CORE_U_CODE]['pageRows'])&&($_SESSION[CORE_U_CODE]['pageRows']!=''))
		{
			$p_row = $_SESSION[CORE_U_CODE]['pageRows'];
		}
		else if($_SESSION[CORE_U_CODE]['default_record']!='')
		{
			$p_row = $_SESSION[CORE_U_CODE]['default_record'];
		}
		else
		{
			$p_row = DEFAULT_RECORD;
		}
		
?> 

<div class="filter">
    Select School Term&nbsp;&nbsp;
    <select name="filter_schoolterm" id="filter_schoolterm" class="txt_200">
    	<?=generateSchoolTerms($_REQUEST['filter_schoolterm'])?>
    </select>
    Filter By: Department <select name="filterdept" id="filterdept" class="txt_150" >
    
                    <option value="" >-Select-</option>
                     <?=generateDepartment($_REQUEST['filterdept'])?>  
                </select>
</div>

		<div id="pageRows">
        <span>show</span>
        <select name="page_rows" id="page_rows">
        <option value="<?=$_SESSION[CORE_U_CODE]['default_record']!='' ? $_SESSION[CORE_U_CODE]['default_record']:DEFAULT_RECORD?>"<?=$p_row==DEFAULT_RECORD||$p_row==$_SESSION[CORE_U_CODE]['default_record'] ? 'selected=selected':''?>>Default</option>
          <option value="10"<?=$p_row==10 ? 'selected=selected':''?>>10</option>
          <option value="20"<?=$p_row==20 ? 'selected=selected':''?>>20</option>
          <option value="50"<?=$p_row==50 ? 'selected=selected':''?>>50</option>
          <option value="100"<?=$p_row==100 ? 'selected=selected':''?>>100</option>
          <option value="150"<?=$p_row==150 ? 'selected=selected':''?>>150</option>            
        </select>
    </div>
    
    <!--<div class="btn_container">
        <p class="button_container">
            <a href="#" class="button" title="No grades" id="grade"><span>View All Grades</span></a>            
        </p> 
    </div>!-->   

        <?php  
        if (mysql_num_rows($result) > 0 )
        {
            $x = 1;
        ?>
        
        <table class="listview">      
          <tr>
              <th class="col_100"><a href="#" class="sortBy" returnFilter="emp_id_number">Employee Code</a></th>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="lastname">Full Name</a></th>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="department_name">Department</a></th>
              <th class="col_150">Action</th>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td><?=$row["emp_id_number"]?></td> 
                <td><?=$row["lastname"].", ".$row["firstname"]." " .$row["middlename"]?></td>
                <td><?=getDeptName($row["department_id"])?></td>
                <td class="action">
                    <ul>
	                    <li><a class="profile" href="#" title="View Profile" returnId="<?=$row['id']?>" returnComp="<?=$_REQUEST['comp']?>"></a></li>
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
        <input type="hidden" name="filter_fields" id="filter_fields" value="<?=$_REQUEST['fieldNames']?>" />
		<input type="hidden" name="filter_orders" id="filter_orders" value="<?=$_REQUEST['orderBy']?>" />
        <p id="pagin">

        	<?php
            for($x=1;$x<=$last;$x++) {
                if ($_REQUEST['pageNum'] == $x) {
            ?>	
                <a href="#"><?=$x?></a>
            <?php		
                } else {
            ?>
                <a href="#list" onclick="updateList2(<?=$x?>)"><?=$x?></a>
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
<!-- GRADE LIST LOOK UP-->
        <div id="dialog" title="Grade List">
            Loading...
        </div><!-- #dialog -->
