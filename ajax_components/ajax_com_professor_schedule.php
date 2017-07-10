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
$(function(){

	// Dialog			
	$('#dialog').dialog({
		autoOpen: false,
		width: 680,
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
	$('.profile').click(function(){
		var param = $(this).attr("returnId");
		var param2 = $(this).attr("returnComp");
		if($('#filter_schoolterm').val() != '' )
		{
			param = param + '&filter_schoolterm=' + $('#filter_schoolterm').val();
		}	
		
		$('#dialog').load('viewer/viewer_com_professor_schedule.php?id='+param+'&comp='+param2, null);
		$('#dialog').dialog('open');
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
	
	$('#facload').click(function() {

			var w=window.open ("pdf_reports/rep108.php?trm="+$('#filter_schoolterm').val()+"&met=facload"); 
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
		$arr_sql[] = " employee.department_id=" . addslashes($_REQUEST['filterdept']);
	}		
	
	if(isset($fieldName) || $fieldName != '' )
	{
	
		if($fieldName == 'department_name')
		{
			$sqlOrderBy = " ORDER BY  department.$fieldName ". $_REQUEST['orderBy'] ." , employee.lastname ASC";
		}
		else
		{
			$sqlOrderBy = ' ORDER BY  '. $fieldName .' '. $_REQUEST['orderBy'];
		}
		
	}

	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : ' AND ' . implode(' AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	
	$sql_pagination = "SELECT employee.* 
				FROM 
					tbl_employee employee,
					tbl_department department
				WHERE 
					employee.employee_type = '2' AND
					department.id = employee.department_id
						" .$sqlcondition ;
						
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
		
		$sql ="SELECT employee.* 
				FROM 
					tbl_employee employee,
					tbl_department department
				WHERE 
					employee.employee_type = '2' AND
					department.id = employee.department_id
							" .$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);


        if (mysql_num_rows($result) > 0 )
        {
            $x = 1;
        ?>
        <a href="#" class="button" title="Faculty Loading" id="facload" name="facload"><span>Faculty Loading</span></a>
        <table class="listview">      
          <tr>
              <th class="col_20">&nbsp;</th>
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
                <td ><input type="checkbox" name="id" id="id_<?=$row['id']?>" value="<?=$row['id']?>" /></td>
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
		?>
            <div class="container">
                <a href="index.php?comp=mn_professor&clear=1" class="search_button" id="dialog_link"><span>Clear Search</span></a>        
            </div>
    	<?php
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
?>
<!-- LIST LOOK UP-->
<div id="dialog" title="Professor Schedule">
    Loading...
</div><!-- #dialog -->
