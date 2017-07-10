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
	$canEdit = $_REQUEST['canEdit'];
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

function print_load(id)
{
	//alert(id);
			var w=window.open ("pdf_reports/rep108.php?id="+id+"&trm=<?=CURRENT_TERM_ID?>&met=room_load"); 
			return false;
}
</script>
	
    <?php
	$arr_sql = array();
	$sqlcondition = '';
	$sqlOrderBy = '';
	
	$open_lookup = $_REQUEST['open_lookup']; 
	
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
			
	if (isset($_REQUEST['filterfield']) && $_REQUEST['filterbuilding_id']!='')
	{
		$arr_sql[] = $_REQUEST['filterfield'].'='.'"'.$_REQUEST['filterbuilding_id'].'"';
	}
	if(isset($_REQUEST['filterroom']) && $_REQUEST['filterroom_type'] !='')
	{
		$arr_sql[] = $_REQUEST['filterroom'].'='.'"'.$_REQUEST['filterroom_type'].'"';
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
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : ' AND '.implode(' AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	$sql_pagination = "SELECT r.id,r.room_no,r.room_type,r.building_id,r.publish,b.building_name FROM tbl_room r, tbl_building b WHERE r.building_id = b.id
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
		
		$sql = "SELECT r.id,r.room_no,r.room_type,r.building_id,r.publish,b.building_name 
					FROM tbl_room r, tbl_building b WHERE r.building_id = b.id
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
            <th class="col_150"><a href="#" class="sortBy" returnFilter="room_no">Room No.</a></th>
               <th class="col_150"><a href="#" class="sortBy" returnFilter="room_type">Room Type</a></th>
              <th class="col_350"><a href="#" class="sortBy" returnFilter="building_name">Building Name</a></th>
               <?=$canEdit=='Y'?'<th class="col_350 right">Action</th>':''?>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td ><input type="checkbox" name="id" id="id_<?=$row['id']?>" value="<?=$row['id']?>" /></td>
                <td><?php
                if($canEdit =='Y')
				{
				?>
                <a href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" class="edtlnk" val="<?=$row["id"]?>"><?=$row["room_no"]?></a>
                <?php
                }
				else
				{
					echo $row["room_no"];
				}
				?></td> 
                <td><?=$row["room_type"]?></td>
                <td><?=$row["building_name"]?></td>
                <?php
                if($canEdit =='Y')
				{
				?>
                <td>
                    <ul>
                    	<li><a class="availability" href="javascript:doTheAction ('availability', 'availability', 'id_<?=$row['id']?>');" title="availability"></a></li>
                        <!--<li><a class="edit" href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" title="edit"></a></li>!-->
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
                        
                        <li><a onclick="print_load(<?=$row['id']?>);" href="#" title="room loading"><img src="../images/led-ico/printer.png" /></a></li>
                    </ul>
                    <?php } ?>
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