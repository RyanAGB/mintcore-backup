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
	
	if(isset($_REQUEST['cal_rows']))
	{
		$_SESSION[CORE_U_CODE]['cal_rows'] = $_REQUEST['cal_rows'];
		if($_REQUEST['cal_rows']=='N')
		{
			$arr_sql[] = " schedule_id=0";
		} 	
	}
	else if($_SESSION[CORE_U_CODE]['cal_rows']!=''&&$_SESSION[CORE_U_CODE]['cal_rows']=='N')
	{
		$arr_sql[] = " schedule_id=0"; 
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
		$sqlcondition = count($arr_sql) == 1 ? ' WHERE ' . $arr_sql[0] : $arr_sqlimplode(' AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	$sql_pagination = "SELECT * FROM tbl_school_calendar
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
	if($row_ctr>0)
	{
		//This tells us the page number of our last page 
		$last = ceil($row_ctr/$page_rows); 			
				
		$max = 'limit ' .$pagenum .',' .$page_rows; 	
		
		$sql = "SELECT * FROM tbl_school_calendar
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
            <th class="col_350"><a href="#" class="sortBy" returnFilter="title">Title</a></th>
              <th class="col_200"><a href="#" class="sortBy" returnFilter="date_from">Date (from - to)</a></th>
              <th class="col_150">Action</th>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td ><input type="checkbox" name="id" id="id_<?=$row['id']?>" value="<?=$row['id']?>" /></td>
                <td><a href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" class="edtlnk" val="<?=$row["id"]?>"><?=$row["title"]?></a></td>
                <?php			
                	$d_from = explode ('-',$row['date_from']);		
					$from_year = $d_from['0'];
					$from_day = $d_from['1'];
					$from_month = $d_from['2'];
					
					$d_to = explode ('-',$row['date_to']);		
					$to_year = $d_to['0'];
					$to_day = $d_to['1'];
					$to_month = $d_to['2'];
				
				?>
                <td><?=date("M. d, Y", mktime(0, 0, 0, $from_day, $from_month, $from_year)).' - '.date("M. d, Y", mktime(0, 0, 0, $to_day, $to_month, $to_year))?></td>
                <td class="action">
                    <ul>
                        <li><a class="edit" href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" title="edit"></a></li>
                       <li><a class="delete" href="#" onclick="javascript:lnk_deleteItem('id_<?=$row['id']?>');" title="delete"></a></li>
                        <li>
                        <?
						if($row['publish']=='Y'){
						?>
                       	  <a class="publish" href="#" onclick="javascript:lnk_unpublishItem('id_<?=$row['id']?>');" title="click to unpublished"></a>
                        <?php
                        }
						else
						{
						?>
                          <a class="unpublished" href="#" onclick="javascript:lnk_publishItem('id_<?=$row['id']?>');" title="click to publish"></a>
                        <?php
						}
						?>
                        </li>
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