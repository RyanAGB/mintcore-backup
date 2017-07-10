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
$(document).ready(function(){  
	
	//initialize the tab action
	$('.period').click(function(){
			clearTabs();
			$('#period').addClass('active');
			$('#view').val('period');
			$('#syId').val($(this).attr("returnSYId"));
			//$('#termId').val($(this).attr("returnTermId"));	
			$('#box_container').html(loading);		
			$("form").submit();
	});
	
	
	$('.add_period').click(function(){
			clearTabs();
			$('#add_period').addClass('active');
			$('#view').val('add_period');
			$('#syId').val($(this).attr("returnSYId"));
			$('#box_container').html(loading);
			//$('#termId').val($(this).attr("returnTermId"));			
			$("form").submit();
	});	
	//doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');
});	
</script>
<?php	
	
	$arr_sql = array();
	$sqlcondition = '';
	$sqlOrderBy = '';
	
	$filter_schoolterm = $_REQUEST['filter_schoolterm'];
	
	if(isset($_REQUEST['list_rows']))
	 {
		$page_rows = $_REQUEST['list_rows']; 	
	}		
	else
	{
		$page_rows = 10;
	}

			
	if (isset($filter_schoolterm) and ($filter_schoolterm != "")){
		$arr_sql[] =  "term_id = " . $filter_schoolterm;
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
	$sql_pagination = "SELECT * FROM tbl_schedule WHERE employee_id = ".USER_EMP_ID."
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
		
		 $sql = "SELECT * FROM tbl_schedule WHERE employee_id = ".USER_EMP_ID."
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
              <th class="col_50"><a href="#list" onclick="sortList('period_name');">Section No.</a></th>
              <th class="col_200"><a href="#list" onclick="sortList('start_of_submission');">Subject</a></th>
			  <th class="col_50"><a href="#list" onclick="sortList('end_of_submission');">Room</a></th>
              <th class="col_50">Schedule</th>
              <th class="col_50">Action</th>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
        ?>
                <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td><?=$row["section_no"]?></td> 
                <td><?=getSubjName($row["subject_id"])?></td>
				<td><?=getRoomNo($row["room_id"])?></td>
				<td><?=getScheduleDays($row["id"])?></td>
                <td class="action">
                        <ul>
                            <li><a class="period" href="#" returnSyId="<?=$row['id']?>" title="click to manage gradesheet"></a></li>
                            <li><a class="add_period" href="#" returnSYId="<?=$row['id']?>"  title="click to add set-up"></a></li>
                        </ul>
                    </td>                    
                </tr>
        <?php 
			$x++;
			}          
	   }
        else 
        {
               echo '<div id="message_container"><h4>No class record found<h4></div><p id="formbottom"></p>';
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
               echo '<div id="message_container"><h4>No class record found<h4></div><p id="formbottom"></p>';
	}
?>