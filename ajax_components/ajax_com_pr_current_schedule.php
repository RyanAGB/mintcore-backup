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
	$term_id = $_REQUEST['filter_schoolterm'];
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
	$('.profile').click(function(){
		var param = $(this).attr("returnId");
		var param2 = $(this).attr("returnComp");
		var param3 = $(this).attr("returnTerm");
		$('#dialog').load('viewer/viewer_com_schedule_student_list.php?id='+param+'&comp='+param2+'&filter_schoolterm='+param3, null);
		$('#dialog').dialog('open');
		return false;
	});
	
	$('#print').click(function() {
			var w=window.open();
			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
			w.document.write($('#print_div').html());
			w.document.close();
			w.focus();
			w.print();
			//w.close();
			return false;
		});
	
	$('#pdf').click(function() {
			var w=window.open ("pdf_reports/rep108.php?id="+<?=USER_EMP_ID?>+"&met=schedule&emp"+"&trm="+<?=$term_id?>); 
			return false;
		});
	$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file to this student?"))
			{
				$.ajax({
					type: "POST",
					data: "id="+<?=USER_EMP_ID?>+"&met=schedule&emp"+"&trm="+<?=$term_id?>+"&email=1",
					url: "pdf_reports/rep108.php",
					success: function(msg){
						if (msg != ''){
							alert('Sending document by email failed.');
							return false;
						}else{
							alert('Email successfully sent.');
							return false;
						}
					}
					});	
					
			}
			else
			{
				return false;
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
		$page_rows = $_REQUEST['list_rows']; 	
	}		
	else
	{
		$page_rows = 10;
	}
	
	if (isset($_REQUEST['filter_schoolterm']) and ($_REQUEST['filter_schoolterm'] != "")){
		$arr_sql[] =  "term_id = " . $_REQUEST['filter_schoolterm'];
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
	
	$sql_pagination = "SELECT schedule.* FROM tbl_schedule as schedule, tbl_subject as subject WHERE schedule.subject_id = subject.id AND schedule.employee_id = ".USER_EMP_ID."	
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
		
		$sql = "SELECT schedule.* FROM tbl_schedule as schedule, tbl_subject as subject WHERE schedule.subject_id = subject.id AND schedule.employee_id = ".USER_EMP_ID."
							" .$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);

?>                  

<div id="print_div">
<div id="printable">
<div class="body-container">
<div class="header">
    <table width="100%" class="head">
      <tr>
        <td width="15%" class="bold">Professor Name:</td>
        <td width="85%"><?=getEmployeeFullName(USER_EMP_ID)?></td>
      </tr>
      <tr>
        <td class="bold">Professor Number:</td>
        <td><?=getEmployeeNumber(USER_EMP_ID)?></td>
      </tr>
      <tr>
        <td class="bold">Department:</td>
        <td><?=getEmployeeDeptName(USER_EMP_ID)?></td>
      </tr>
       <tr>
        <td class="bold">School Year:</td>
        <td><?=getSYandTerm($_REQUEST['filter_schoolterm'])?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
    </tr>
    </table>
</div>
<div class="content-container">

<div class="content-wrapper-withBorder">	
        
        <?php  
        if (mysql_num_rows($result) > 0 )
        {
            $x = 1;
        ?>
        
        <table class="listview">    
        <tr>
        <td colspan="7">
         <a class="viewer_email" href="#" id="email" title="email"></a>
        <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
        <a class="viewer_print" href="#" id="print" title="print"></a>
        </td>
        </tr>    
          <tr>
            
                <th class="col1_150">Section</th>
                <th class="col1_150">Code</th>  
                <th class="col1_150">Subject Name</th>   
                <th class="col1_50">Room</th>  
                <th class="col1_50">Schedule</th> 
                <th class="col1_50">Class List</th>

          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td><?=$row["section_no"]?></td>
                <td><?=getSubjCode($row["subject_id"])?></td> 
                <td><?=getSubjName($row["subject_id"])?></td>
                <td><?=getRoomNo($row["room_id"])?></td>
                <td><?=getScheduleDays($row["id"])?></td>
               <td class="action">
                    <ul>
                    	 <li><a class="profile" href="#" title="View Students" returnId="<?=$row['id']?>" returnComp="<?=$_REQUEST['comp']?>" returnTerm=<?=$_REQUEST['filter_schoolterm']?>></a></li></ul></td>
            </tr>
        <?php           
			$x++;
           }
        }
        else 
        {
                echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom">';
        }
        ?>
        </table> 
        <p id="pagin">

        
        </p>
        
<?php
	}
	else 
	{
			echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom">';
	}
?>
</div>
</div>
</div>
</div>
</div>
<!-- LIST LOOK UP-->
<div id="dialog" title="Professor Schedule">
    This will be replace by the look up contents
</div><!-- #dialog -->
