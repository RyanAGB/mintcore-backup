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
	
	/*$('.xmark').click(function(){
	if(confirm('Are you sure you want to set this as current school year, action cannot be undone?'))
	{
		if($('#chek').val()==1)
		{
			if(confirm('Cannot set this School year. Current School Year contains students without grades. Complete Grades First'))
			{
				var param = $('#comp').val();
				$('#dialog_grd').load('viewer/view_com_encode_grade.php?comp='+param, null);
				$('#dialog_grd').dialog('open');
				return false;
			}
		}
		else
		{
			$('#action').val('publish');
			$("form").submit();
		}
	}
	else
	{
		return false;
	}
	});*/
	
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
	$sql_pagination = "SELECT * FROM tbl_school_year 
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
		
		$sql = "SELECT * FROM tbl_school_year
							" .$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);

?>                  


        
        <?php  
        if (mysql_num_rows($result) > 0 )
        {
            $x = 0;
        ?>
        
        <table class="listview">      
          <tr>
              <th class="col_20">&nbsp;</th>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="start_year">School Year</th>
              <th class="col_350"><a href="#" class="sortBy" returnFilter="number_of_term">Number of Terms</th>
			  <th class="col_350"><a href="#" class="sortBy" returnFilter="number_of_period">Period per Terms</th>
              <th class="col_150">Is Current SY</th>
              <th class="col_150">Action</th>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td ><input type="checkbox" name="id" id="id_<?=$row['id']?>" value="<?=$row['id']?>" /></td>
                <td><a href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" class="edtlnk" val="<?=$row["id"]?>"><?=$row["start_year"] ."-". $row["end_year"]?></a></td> 
                <td><?=$row["number_of_term"]?></td>
				<td><?=$row["number_of_period"]?></td>
                <td>
					<ul>
                        <li>
                        <?
						if($row['is_current_sy']=='Y'){
						?>
                       	  <a class="checkmark" href="#" onclick="javascript:alert('You cannot unset this school year.'); return false;"></a>
                        <?php
                        }
						else
						{
						?>
                          <a class="xmark" href="#" onclick="javascript:if(confirm('Are you sure you want to set this as current school year, action cannot be undone?'))lnk_publishItem('id_<?=$row['id']?>'); else return false;" title="click to set as current"></a>
                        <?php
						}
						?>
                        </li>
                    </ul>
                </td>
                <td class="action">
                    <ul>
                        <li><a class="edit" href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" title="edit"></a></li>
                        <li><a class="delete" href="#" onclick="javascript:lnk_deleteItem('id_<?=$row['id']?>');" title="delete"></a></li>
	                  </ul>
                </td>
            </tr>
        <?php           
           }
        }
        else 
        {
                echo '<div id="message_container"><h4>No records found</h4></div>';
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