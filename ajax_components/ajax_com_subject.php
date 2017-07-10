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
	include_once('../includes/functions.php');
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
	
	$('.drop').click(function(){
		
		if(confirm("Are you sure you want to dissolve this subject?"))
		{
			clearTabs();
			$('#view').val('list');
			$('#action').val('dissolve');
			$('#dis_id').val($(this).attr('returnId'));
			updateList();
			$("form").submit();
		}
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

			
	if (isset($_REQUEST["filter_dept"]) and ($_REQUEST["filter_dept"] != "")){
 
		$arr_sql[] = " department.id =" . $_REQUEST["filter_dept"];
	}			

	if(isset($fieldName) || $fieldName != '' )
	{
	
		if($fieldName == 'department_name')
		{
			$sqlOrderBy = " ORDER BY  department.$fieldName ". $_REQUEST['orderBy'] ." , subject.subject_name ASC";
		}
		else
		{
			$sqlOrderBy = ' ORDER BY  '. $fieldName .' '. $_REQUEST['orderBy'];
		}
		
	}
	else if($_SESSION[CORE_U_CODE]['fieldName']!='' || $_SESSION[CORE_U_CODE]['orderBy'] !='')
	{
		if($_SESSION[CORE_U_CODE]['fieldName'] == 'department_name')
		{
			$sqlOrderBy = " ORDER BY  department.".$_SESSION[CORE_U_CODE]['fieldName']." ". $_SESSION[CORE_U_CODE]['orderBy'] ." , subject.subject_name  ASC";
		}
		else
		{
			$sqlOrderBy = ' ORDER BY  '. $_SESSION[CORE_U_CODE]['fieldName'] .' '. $_SESSION[CORE_U_CODE]['orderBy'];
		}
	}


	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sqlimplode(' AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	$sql_pagination = "SELECT subject.* 
					FROM 
						tbl_subject subject,
						tbl_department department
					WHERE
						subject.department_id = department.id
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
		
		$sql = "SELECT subject.* 
					FROM 
						tbl_subject subject,
						tbl_department department
					WHERE
						subject.department_id = department.id
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
            <th class="col_150"><a href="#" class="sortBy" returnFilter="subject_code">Subject Code</a></th>
               <th class="col_350"><a href="#" class="sortBy" returnFilter="subject_name">Subject Name</a></th>
               <th class="col_350"><a href="#" class="sortBy" returnFilter="department_name">Department Name</a></th>
              <th class="col_150">Action</th>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td ><input type="checkbox" name="id" id="id_<?=$row['id']?>" value="<?=$row['id']?>" /></td>
                <td><a href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" class="edtlnk" val="<?=$row["id"]?>"><?=$row["subject_code"]?></a></td> 
                <td><?=$row["subject_name"]?></td>
                <td><?=getDeptName($row["department_id"])?></td>
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
                        
                        <li><a class="drop" href="#" title="Dissolve Subject" returnId="<?=$row['id']?>"></a></li>
                        
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