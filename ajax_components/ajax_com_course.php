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
		//alert(<?=CURRENT_TERM_ID?>);
		$('#dialog').load('viewer/viewer_com_course_student_list.php?id='+param+'&filter_schoolterm=<?=CURRENT_TERM_ID?>&comp='+param2, null);
		$('#dialog').dialog('open');
		return false;
	});
	
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

			
	if (isset($_REQUEST["filter_college"]) and ($_REQUEST["filter_college"] != "")){
	
		$arr_sql[] = " college_id =" . $_REQUEST["filter_college"];
	}			

	if(isset($fieldName) || $fieldName != '' )
	{
	
		if($fieldName == 'college_name')
		{
			$sqlOrderBy = " ORDER BY  college.$fieldName ". $_REQUEST['orderBy'] ." , course.course_name ASC";
		}
		else
		{
			$sqlOrderBy = ' ORDER BY  '. $fieldName .' '. $_REQUEST['orderBy'];
		}
		
	}
	else if($_SESSION[CORE_U_CODE]['fieldName']!='' || $_SESSION[CORE_U_CODE]['orderBy'] !='')
	{
		if($_SESSION[CORE_U_CODE]['fieldName'] == 'college_name')
		{
			$sqlOrderBy = " ORDER BY  college.".$_SESSION[CORE_U_CODE]['fieldName']." ". $_SESSION[CORE_U_CODE]['orderBy'] ." , course.course_name ASC";
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
	$sql_pagination = "SELECT course.* 
					FROM 
						tbl_course course, 
						tbl_college college 
					WHERE
						college.id = course.college_id 
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
		
		$sql = "SELECT course.* 
					FROM 
						tbl_course course, 
						tbl_college college 
					WHERE
						college.id = course.college_id 
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
            <th class="col_150"><a href="#" class="sortBy" returnFilter="course_code">Course Code</a></th>
              <th class="col_350"><a href="#" class="sortBy" returnFilter="course_name">Course Name</a></th>
			  <th class="col_350"><a href="#" class="sortBy" returnFilter="college_name">College Name</a></th>
               <?=$canEdit=='Y'?'<th class="col_150 right">Action</th>':''?>
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
				?><a href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" class="edtlnk" val="<?=$row["id"]?>"><?=$row["course_code"]?></a>
                <?php
                }
				else
				{
					echo $row["course_code"];
				}
				?></td> 
                <td><?=$row["course_name"]?></td>
				<td><?=getCollegeName($row["college_id"])?></td>
                <?php
                if($canEdit =='Y')
				{
				?>
                <td class="action">
                    <ul>
                    	<li><a class="profile" href="#" title="View Students" returnId="<?=$row['id']?>" returnComp="<?=$_REQUEST['comp']?>"></a></li>
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

<!-- LIST LOOK UP-->
<div id="dialog" title="Course Profile">
    Loading...
</div><!-- #dialog -->