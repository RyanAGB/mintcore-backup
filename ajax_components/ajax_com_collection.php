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
	/*else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}*/
	
	$f_date = $_REQUEST['f_date'];
	$t_date = $_REQUEST['t_date'];
?>
<script type="text/javascript">
/*$(function(){

	// Dialog			
	$('#dialog').dialog({
		autoOpen: false,
		width: 650,
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
		var param2 = $(this).attr("returnComp");
	
		if($('#filter_schoolterm').val() != '' )
		{
			param = param + '&filter_schoolterm=' + $('#filter_schoolterm').val();
		}	
		
		$('#dialog').load('viewer/viewer_com_statement.php?id='+param+'&comp='+param2, null);
		$('#dialog').dialog('open');
		return false;
	});
	
	
});	*/
 
</script>
<script type="text/javascript">
$(document).ready(function(){  

	
});

function print_load()
{
	//alert(id);
			var w=window.open ("pdf_reports/rep108.php?id=<?=strtotime($f_date)?>&trm=<?=strtotime($t_date)?>&met=collection"); 
			return false;
}
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


	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : ' AND ' . implode('AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	
	$sql_pagination = "SELECT student.* 
					FROM tbl_student student,
						 tbl_student_payment pay
					WHERE student.id = pay.student_id AND pay.date_created BETWEEN " . strtotime($f_date)." AND ".strtotime($t_date).$sqlcondition ;
						
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
		//$last = ceil($row_ctr/$page_rows); 			
				
		//$max = 'limit ' .$pagenum .',' .$page_rows; 
		
		$sql = "SELECT * 
					FROM tbl_student student,
						 tbl_student_payment pay
					WHERE student.id = pay.student_id AND pay.date_created BETWEEN " . strtotime($f_date)." AND ".strtotime($t_date).$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);
 
        if (mysql_num_rows($result) > 0 )
        {
            $x = 1;
			$cnt = 0;
        ?>
        <table class="listview"> 
           <tr><td colspan="3">
           <a onclick="print_load();" href="#" title="print"><img src="../images/led-ico/printer.png" /></a>
           </td></tr>
          <tr>
              <th class="col_70"><a href="#" class="sortBy" returnFilter="student_number">Student Number</a></th>
              <th class="col_100"><a href="#" class="sortBy" returnFilter="lastname">Full Name</a></th>
              <th class="col_150">Amount</th>
              <!--<th class="col_50">View SOA</th>!-->
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
				
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td ><?=$row["student_number"]?></td> 
                <td><?=$row["lastname"].", ".$row["firstname"]." " .$row["middlename"]?></td>
                <td><?=number_format($row['amount'],2)?></td>
                <!--<td class="action">
                    <ul>
                        <li><a class="schedule" href="#" title="View Statement" returnId="<?=$row['id']?>" returnComp="<?=$_REQUEST['comp']?>"></a></li>
                       
                    </ul>
                </td>!-->
            </tr>
        <?php  
		$total+=$row['amount'];
				$cnt++;  
			$x++;          
           }
		   ?>
           
           <tr><td colspan="2">&nbsp;</td>
         <td> <strong> Total : <?=number_format($total,2)?></strong>
           </td>
          </tr>
           
       <?php    
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
<!-- LIST LOOK UP
<div id="dialog" title="Student Grade">
    Loading...
</div><!-- #dialog -->
