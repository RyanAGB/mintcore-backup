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
	
	$filter_schoolterm = $_REQUEST['filter_schoolterm'];
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
		var param2 = $(this).attr("returnComp");
	
		/*if($('#filter_schoolterm').val() != '' )
		{
			param = param + '&filter_schoolterm=' + <?=$filter_schoolterm?>);
		}	*/
				
		$('#dialog').load('viewer/viewer_com_number_student_schedule.php?id='+param+'&filter_schoolterm=' + <?=$filter_schoolterm?>'+&comp='+param2, null);
		$('#dialog').dialog('open');
		return false;
	});
	
});	
 
</script>

	<script type="text/javascript">
	$(function(){
		
		$('#print').click(function() {
			var w=window.open();
			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
			w.document.write($('#print_div').html());
			w.document.close();
			w.focus();
			w.print();
			//w.close()
			return false;
		});
		
		$('#pdf').click(function() {
			var w=window.open ("pdf_reports/rep108.php?id="+<?=$filter_schoolterm?>+"&met=subject_summary"); 
			return false;
		});
		
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file to this student?"))
			{
				$.ajax({
					type: "POST",
					data: "id="+<?=USER_STUDENT_ID?>+"&trm="+<?=$filter_schoolterm?>+"&met=schedule&email=1",
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
	$student_filter = $_SESSION[CORE_U_CODE]['student_search'];
	
	if (isset($student_filter['course_id']) && $student_filter['course_id']!= '' && $student_filter['filter'] == '1'){
		$search_field = 'course_id';
		$search_key = $student_filter['course_id']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}		
	
	if (isset($student_filter['course_id']) && $student_filter['course_id']!= '' && $student_filter['filter'] == '0'){
		$search_field = 'course_id';
		$search_key = $student_filter['course_id']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if (isset($student_filter['student_number']) && $student_filter['student_number']!= '' && $student_filter['filter'] == '1'){
		$search_field = 'student_number';
		$search_key = $student_filter['student_number']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}
	
	if (isset($student_filter['student_number']) && $student_filter['student_number']!= '' && $student_filter['filter'] == '0'){
		$search_field = 'student_number';
		$search_key = $student_filter['student_number']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if (isset($student_filter['lastname']) && $student_filter['lastname']!= '' && $student_filter['filter'] == '1'){
		$search_field = 'lastname';
		$search_key = $student_filter['lastname']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}	
	
	if (isset($student_filter['lastname']) && $student_filter['lastname']!= '' && $student_filter['filter'] == '0'){
		$search_field = 'lastname';
		$search_key = $student_filter['lastname']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if (isset($student_filter['firstname']) && $student_filter['firstname']!= '' && $student_filter['filter'] == '1'){
		$search_field = 'firstname';
		$search_key = $student_filter['firstname']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}	
	
	if (isset($student_filter['firstname']) && $student_filter['firstname']!= '' && $student_filter['filter'] == '0'){
		$search_field = 'firstname';
		$search_key = $student_filter['firstname']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}
	
	if (isset($student_filter['middlename']) && $student_filter['middlename']!= '' && $student_filter['filter'] == '1'){
		$search_field = 'middlename';
		$search_key = $student_filter['middlename']; 
		$arr_sql[] = $search_field . " like '".addslashes($search_key)."%'";
	}		
	
	if (isset($student_filter['middlename']) && $student_filter['middlename']!= '' && $student_filter['filter'] == '0'){
		$search_field = 'middlename';
		$search_key = $student_filter['middlename']; 
		$arr_sql[] = $search_field . " like '" . addslashes($search_key) . "'";
	}

	if(isset($fieldName) || $fieldName != '' )
	{
	
		if($fieldName == 'course_name')
		{
			$sqlOrderBy = " ORDER BY  course.$fieldName ". $_REQUEST['orderBy'] ." , student.lastname ASC";
		}
		else
		{
			$sqlOrderBy = ' ORDER BY  '. $fieldName .' '. $_REQUEST['orderBy'];
		}
		
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	$sql_pagination = "SELECT * FROM tbl_subject
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
		
		$sql = "SELECT * FROM tbl_subject
					 " .$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);
		//$row = mysql_fetch_array($result);
?>        
<div id="print_div">
<div id="printable">
<div class="body-container">
<div class="header">
    
</div>
<div class="content-container">

<div class="content-wrapper-withBorder">		         
        <?php  
        if (mysql_num_rows($result) > 0 )
        {
            $x = 0;
        ?>
        <table class="listview">  
        <tr>
        <td colspan="7">
        <!--<a class="viewer_email" href="#" id="email" title="email"></a>!-->
        <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
        <!--<a class="viewer_print" href="#" id="print" title="print"></a>!-->
        </td>
        </tr>    
         <tr>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="subject_name">Subject Name</a></th>
              <th class="col_70"><a href="#" class="sortBy" returnFilter="subject_code">Subject Code</a></th>
              <th class="col_50">No. of Enrolled</th>
              <th class="col_50">View Students</th>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td ><?=$row["subject_name"]?></td> 
                <td><?=$row["subject_code"]?></td>
                <td><?=getNumberSubjectEnrolled($row['id'],$_REQUEST['filter_schoolterm'])?></td>
                <td class="action">
                    <ul>
	                    <li><a class="schedule" href="#" title="View Schedule" returnId="<?=$row['id']?>" returnComp="<?=$_REQUEST['comp']?>"></a></li>
                       
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
        
<?php
	}
	else 
	{
		echo '<div id="message_container"><h4>No records found</h4></div>';
	}
?>
<!-- LIST LOOK UP-->
<div id="dialog" title="Student List">
    Loading...
</div><!-- #dialog -->
<p id="pagin"></p>
</div>
</div>
</div>
</div>
</div>
