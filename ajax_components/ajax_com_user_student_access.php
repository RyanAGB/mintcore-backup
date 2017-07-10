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
	$('.profile').click(function(){
		var param = $(this).attr("returnId");
		$('#dialog').load('viewer/view_com_student_profile.php?id='+param, null);
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

			
	if (isset($_REQUEST["search_field"]) and ($_REQUEST["search_field"] != "") and ($_REQUEST["search_key"] != "") and ($_REQUEST["search_key"] != "")){
		$search_field = $_REQUEST["search_field"];
		$search_key = $_REQUEST["search_key"]; 
		$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
	}			

	if(isset($fieldName) || $fieldName != '' )
	{
	
		if($fieldName == 'course_name')
		{
			$sqlOrderBy = " ORDER BY  course.$fieldName ". $_REQUEST['orderBy'] ." , stud.lastname ASC";
		}
		else
		{
			$sqlOrderBy = ' ORDER BY  '. $fieldName .' '. $_REQUEST['orderBy'];
		}
		
	}
	else
	{
		$sqlOrderBy = ' ORDER BY lastname';
	}


	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sqlimplode(' AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	
	$sql_pagination = "SELECT stud.*,
							user.username,
							user.password,
							user.access_id,
							user.blocked,
							user.failed_logs,
							user.no_of_block_times
						FROM 
							tbl_student stud, 
							tbl_user user 
						WHERE 
							user.id = stud.user_id
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
	if($row_ctr>0)
	{
		//This tells us the page number of our last page 
		$last = ceil($row_ctr/$page_rows); 			
				
		$max = 'limit ' .$pagenum .',' .$page_rows; 	
		
		$sql = "SELECT stud.*,
							user.username,
							user.password,
							user.access_id,
							user.blocked,
							user.failed_logs,
							user.no_of_block_times
						FROM 
							tbl_student stud, 
							tbl_user user,
							tbl_course course
						WHERE 
							user.id = stud.user_id
						AND
							course.id = stud.course_id
							" .$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);

?>                  


        
        <?php  
        if (mysql_num_rows($result) > 0 )
        {
            $x = 1;
        ?>
        
        <table class="listview">      
          <tr>
              <th class="col_20">&nbsp;</th>
            <th class="col_70"><a href="#" class="sortBy" returnFilter="student_number">Student Number</th>
              <th class="col_100"><a href="#" class="sortBy" returnFilter="lastname">Full Name</th>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="course_name">Course</th>
              <th class="col_50">Action</th>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            {
			
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td ><input type="checkbox" name="id" id="id_<?=$row['id']?>" value="<?=$row['id']?>" /></td>
                <td><?=$row["student_number"]?></td> 
                <td><?=$row["lastname"].", ".$row["firstname"]." " .$row["middlename"]?></td>
                <td><?=getCourseName($row['course_id'])?></td>
                <td class="action">
                    <ul>
                        <li>
							<?
                            if($row['blocked']=='1'){
                            ?>
                              <a class="lock" href="#" onclick="javascript:lnk_unpublishItem('id_<?=$row['id']?>');" title="click to Unblocked"></a>
                            <?php
                            }
                            else
                            {
                            ?>
                              <a class="unlocked" href="#" onclick="javascript:lnk_publishItem('id_<?=$row['id']?>');" title="click to Block"></a>
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
?>
