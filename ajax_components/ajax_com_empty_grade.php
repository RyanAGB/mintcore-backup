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
	
	$canEdit = $_REQUEST['canEdit'];
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
			"Ok": function() { 
				$(this).dialog("close"); 
			}
		}
	});
	
	// Dialog Link
	$('.schedule').click(function(){
		var param = '?comp='+$(this).attr("returnComp")+'&sched='+$(this).attr("returnId")+'&prof='+$(this).attr("returnProf");
		$('#dialog').load('viewer/viewer_encode_grade.php'+param, null);
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
	
	$('#save').click(function(){
		
		if($('#cnt').val()!=0)
		{
			if(confirm("Are you sure you want to apply * to all empty Grades?"))
			{
			$('#ctr').val($('#cnt').val());
			$('#sched').val($('#sched_id').val());
			$('#action').val('save');
			$("form").submit();
			}
			else
			{
				return false;
			}
		}
		else
		{
			alert('No Empty Grades need to be filled.');
			return false;
		}
		
	});	
	
});
</script>
<?php
	$arr_sql = array();
	$sqlcondition = '';
	$sqlOrderBy = '';
	$order = '';
	
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

	if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' )
	{
		$sqlOrderBy = ' ORDER BY  '. $_REQUEST['fieldName'] .' '. $_REQUEST['orderBy'];
	}
	else if($_SESSION[CORE_U_CODE]['fieldName']!='' || $_SESSION[CORE_U_CODE]['orderBy'] !='')
	{
		$sqlOrderBy = ' ORDER BY  '. $_SESSION[CORE_U_CODE]['fieldName'] .' '. $_SESSION[CORE_U_CODE]['orderBy'];
	}



	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sqlimplode(' AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	$sql_pagination = "SELECT schedule.* 
					FROM tbl_schedule schedule, 
						tbl_subject subject,
						tbl_room room,
						tbl_employee employee
					WHERE schedule.subject_id = subject.id AND
						schedule.room_id = room.id AND
						schedule.employee_id = employee.id
					AND term_id =".CURRENT_TERM_ID."
						" .$sqlcondition;
						
	$query_pagination  = mysql_query ($sql_pagination);
	$row_ctr = @mysql_num_rows($query_pagination); 
	
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
	
	if($row_ctr>0)
	{
		//This tells us the page number of our last page 
		$last = ceil($row_ctr/$page_rows); 			
				
		$max = 'limit ' .$pagenum .',' .$page_rows; 	
		
		$sql = "SELECT schedule.* 
					FROM tbl_schedule schedule, 
						tbl_subject subject,
						tbl_room room,
						tbl_employee employee
					WHERE schedule.subject_id = subject.id AND
						schedule.room_id = room.id AND
						schedule.employee_id = employee.id
					AND term_id =".CURRENT_TERM_ID."
							" .$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);

		$grade=array();
		$alt_grade=array();
		
					
		
?>                  


        
        <?php  
        if (mysql_num_rows($result)>0)
       	{
			$cnt = 0;
            $x = 0;
			$sched_id = array();
        ?>
        <div style="padding:10px">
        <p class="button_container">
        
               <a href="#" class="button" title="Apply * to all Grades" id="save"><span>Apply * to all Grades</span></a>
        </p>
        </div>
        <table class="listview">     

          <tr>
              <th class="col_50"><a href="#" class="sortBy" returnFilter="section_no">Section</a></th>
              <th class="col_100"><a href="#" class="sortBy" returnFilter="subject_code">Subject Code</a></th>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="subject_name">Subject Name</a></th>
              <th class="col_50"><a href="#" class="sortBy" returnFilter="room_no">Room</a></th>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="lastname">Professor</a></th>   
              <th class="col_100"><a href="#" class="sortBy" returnFilter="monday">Schedule</a></th>                            
              <th class="col_70">Action</th>
           </tr>
        <?php

			
			while($row = @mysql_fetch_array($result)) 
            { 
				if(checkEmptyGrades($row["id"]))
				{
					$sched_id[] = $row['id']
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
              
                <td><?=$row["section_no"]?></td>
                <td><?=getSubjCode($row["subject_id"])?></td> 
                <td><?=getSubjName($row["subject_id"])?></td>
                <td><?=getRoomNo($row["room_id"])?></td>
                <td><?=getProfessorFullName($row["employee_id"])?></td>
                <td><?=getScheduleDays($row["id"])?></td>
                <td class="action">
                    <ul>
                        <li><a class="schedule" href="#" returnId="<?=$row['id']?>" returnProf="<?=$row['employee_id']?>" returnComp="<?=$_REQUEST['comp']?>" title="edit"></a></li>
                    </ul>
                </td>
            </tr>
        <?php  $cnt++;
		}
				}         
           }
		   if($cnt==0){
        ?>
        <tr><td colspan="8">No Empty Grades Found</td></tr>
        <?php } 
		?>
        <input type="hidden" name="cnt" id="cnt" value="<?=$cnt?>" />
         <input type="hidden" name="sched_id" id="sched_id" value="<?=$sched_id?>" />
        </table> 
  
       <p id="pagin">

        	<?php
            /*for($x=1;$x<=$last;$x++) {
                if ($_REQUEST['pageNum'] == $x) {
            ?>	
                <a href="#"><?=$x?></a>
            <?php		
                } else {
            ?>
                <a href="#list" onclick="updateList(<?=$x?>)"><?=$x?></a>
            <?php } 
            } */
            ?>
        
        </p>
        
<?php
	}
	else 
	{
		echo '<div id="message_container"><h4>No Empty Grades Found</h4></div><p id="formbottom"></p>';
	}

?>
<!-- LIST LOOK UP-->
            <div id="dialog" title="Grade List">
                Loading...
            </div><!-- #dialog -->