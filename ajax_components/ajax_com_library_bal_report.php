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

	$arr_filter_fieldName 	= $_REQUEST['fieldNameFilter'];
	$arr_filter_condition 	= $_REQUEST['conditionFilter'];
	$arr_filter_value 		= $_REQUEST['valueFilter'];		

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
	$('.schedule').click(function(){
		var param = $(this).attr("returnId");
	
		if($('#filter_schoolterm').val() != '' )
		{
			param = param + '&filter_schoolterm=' + $('#filter_schoolterm').val();
		}	
				
		$('#dialog').load('viewer/viewer_com_student_grade.php?id='+param, null);
		$('#dialog').dialog('open');
		return false;
	});
	
});	
 
</script>
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
	
	$ctr = 0;
	if(count($arr_filter_fieldName) > 0)
	{
		foreach($arr_filter_fieldName as $fieldname)
		{
			$condition 	= $arr_filter_condition[$ctr] ;
			$value 		= $arr_filter_value[$ctr];

			if($condition == 'EQ')
			{
				 $arr_sql[] = $fieldname . " = '" . addslashes($value) . "'";
			}
			else if($condition == 'EX')
			{
				$arr_sql[] = $fieldname . " <> '" . addslashes($value) . "'";
			}
			else if($condition == 'LKA')
			{
				$arr_sql[] = $fieldname . " like '%" . addslashes($value) . "%'";
			}
			else if($condition == 'LKF')
			{
				$arr_sql[] = $fieldname . " like '%" . addslashes($value) . "'";
			}
			else if($condition == 'GT')
			{
				$arr_sql[] = $fieldname . " > '" . addslashes($value) . "'";
			}
			else if($condition == 'LT')
			{
				$arr_sql[] = $fieldname . " < '" . addslashes($value) . "'";
			}
			
			$ctr++;
		}		
	}
	
	if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' )
	{
		$sqlOrderBy = ' ORDER BY  '. $_REQUEST['fieldName'] .' '. $_REQUEST['orderBy'];
	}


	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : ' AND ' . implode(' AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	
	$sql_pagination = "SELECT m.id, m.lib_card_number,
				m.lastname,m.firstname,m.middlename,m.student_number,m.course_id,
				sum(acct.amount) balance
			FROM tbl_student m, ob_member_account acct
			WHERE m.id=acct.student_id ".$sqlcondition." 
			GROUP by m.id, m.lib_card_number, m.lastname, m.firstname
							"  ;
						
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
		
		$sql = "SELECT m.id, m.lib_card_number,
				m.lastname,m.firstname,m.middlename,m.student_number,m.course_id,
				sum(acct.amount) balance
			FROM tbl_student m, ob_member_account acct
			WHERE m.id=acct.student_id ".$sqlcondition." 
			GROUP by m.id, m.lib_card_number, m.lastname, m.firstname
							"  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);

        if (mysql_num_rows($result) > 0 )
        {
            $x = 1;

        ?>
        
        <table class="listview">      
          <tr>
              <th class="col_100"><a href="#" class="sortBy" returnFilter="lib_card_number">Card Number</a></th>
              <th class="col_100"><a href="#" class="sortBy" returnFilter="student_number">Student Number</a></th>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="lastname">Name</a></th>
              <th class="col_150">Balance</th>
          </tr>
        <?php
			while($row = mysql_fetch_array($result)) 
            {
				if($row['balance'] > 0)
				{
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td ><?=$row["lib_card_number"]?></td> 
                <td ><?=$row["student_number"]?></td>
                <td><?=$row["lastname"].','.$row["firstname"].' '.$row["middlename"]?></td>
                <td><?=$row['balance']?></td>
            </tr>
        <?php  
				}
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
<!-- LIST LOOK UP-->
<div id="dialog" title="Student Grade">
    Loading...
</div><!-- #dialog -->
