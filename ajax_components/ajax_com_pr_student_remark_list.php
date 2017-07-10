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

	$prof_id = USER_EMP_ID;	
	$sched_id = $_REQUEST['sched_id'];
	$period_id = $_REQUEST['period_id']!=''?$_REQUEST['period_id']:CURRENT_PERIOD_ID;
?>
<script type="text/javascript">
$(document).ready(function(){  

	$('.edit').click(function(){
		$('#id').val($(this).attr('returnID'));
		$('#subject_id').val($(this).attr('subjectreturnID'));
		$('#view').val('edit');
		$("form").submit();
	});
	
});

</script>
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
		var param2 = $(this).attr("returnComp");
		$('#dialog').load('viewer/viewer_com_pr_student_remark.php?id='+param+'&comp='+param2, null);
		$('#dialog').dialog('open');
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

	if (isset($_REQUEST["search_field"]) and ($_REQUEST["search_field"] != "") and ($_REQUEST["search_key"] != "") and ($_REQUEST["search_key"] != "")){
		$search_field = $_REQUEST["search_field"];
		$search_key = $_REQUEST["search_key"]; 
		$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
	}			

	if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' )
	{
		$sqlOrderBy = ' ORDER BY  '. $_REQUEST['fieldName'] .' '. $_REQUEST['orderBy'];
	}


	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sqlimplode(' AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	$sql_pagination = "SELECT * FROM tbl_student_schedule WHERE schedule_id =".$sched_id 
						.$sqlcondition;
						
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
		
		$sql = "SELECT * FROM tbl_student_schedule WHERE schedule_id =".$sched_id
						 .$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);

?>                  
        <?php  
        if (mysql_num_rows($result) > 0 )
        {
            $x = 0;
        ?>
        
        <table class="listview">      
          <tr>
              <th class="col_100">Student Number</th>
              <th class="col_150">Student Name</th>
              <th class="col_200">Course</th>
              <th class="col_100 right">Action</th>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                
                <td><?=getStudentNumber($row["student_id"])?></td> 
                <td><?=getStudentFullName($row["student_id"])?></td>
                <td><?=getStudentCourseName($row["student_id"])?></td>
                <td class="action">
                    <ul>
                        <li><a class="edit" href="#" returnID="<?=$row['student_id']?>" subjectreturnID="<?=$row['subject_id']?>" title="Add Remarks"></a></li>
                        <li><a class="profile" href="#" returnId="<?=$row['student_id']?>" title="Remark List" returnComp="<?=$_REQUEST['comp']?>"></a></li>       
                    </ul>
                </td>
            </tr>
        <?php           
           }
        }
        else 
        {
                echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';
        }
        ?>
        </table> 
  		<input type="hidden" name="id" id="id" value="" />
        <input type="hidden" name="subject_id" id="subject_id" value="" />
        <input type="hidden" name="period_id" id="period_id" value="<?=$period_id?>" />
        <input type="hidden" name="term_id" id="term_id" value="<?=CURRENT_TERM_ID?>" />
        <input type="hidden" name="sched_id" id="sched_id" value="<?=$sched_id?>" />
        <input type="hidden" name="prof_id" id="prof_id" value="<?=USER_EMP_ID?>" />
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
<div id="dialog" title="Student Remarks">
    This will be replace by the look up contents
</div>
<!-- #dialog -->
