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
	$('.applicant').click(function(){
		var param = $(this).attr("returnId");
		var param2 = $(this).attr("returnComp");
		$('#dialog').load('lookup/lookup_com_student_decline.php?id='+param+'&comp='+param2, null);
		$('#dialog').dialog('open');
		return false;
	});
	
});

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
			var w=window.open ("pdf_reports/rep109.php?met=declined list"+"&trm="+<?=$_REQUEST['filter_schoolterm']?>); 
			return false;
		});
		
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file?"))
			{
				$.ajax({
					type: "POST",
					data: "trm="+<?=$_REQUEST['filter_schoolterm']?>+"&met=declined list&email=1",
					url: "pdf_reports/rep109.php",
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

	if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' )
	{
		if($_REQUEST['fieldName'] == 'course_name')
		{
			$sqlOrderBy = ' ORDER BY  course.'. $_REQUEST['fieldName'] .' '. $_REQUEST['orderBy'] .' ,app.lastname ASC';
		}
		else
		{
			$sqlOrderBy = ' ORDER BY  '. $_REQUEST['fieldName'] .' '. $_REQUEST['orderBy'];
		}
	}


	if (isset($_REQUEST['filter_schoolterm']) and ($_REQUEST['filter_schoolterm'] != "")){
		$arr_sql[] =  "term_id = " . $_REQUEST['filter_schoolterm'];
	}
	
	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : ' AND ' . implode('AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	
	$sql_pagination = "SELECT app.* 
						FROM tbl_decline_application app,
							tbl_course course,
							tbl_school_year year,
							tbl_school_year_term term
						WHERE app.course_id = course.id AND
							year.id = term.school_year_id AND 
							term.id = app.term_id	
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
		
		$sql = "SELECT app.* 
						FROM tbl_decline_application app,
							tbl_course course,
							tbl_school_year year,
							tbl_school_year_term term
						WHERE app.course_id = course.id AND
							year.id = term.school_year_id AND 
							term.id = app.term_id
							" .$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);
?>     
   
        <?php  
        if (mysql_num_rows($result) > 0 )
        {
			$num = mysql_num_rows($result);
            $x = 0;
			$ctr = 1;
        ?>
        
        <div id="print_div">
        <div id="printable">
        <div class="body-container">
        <div class="content-container">
		<div class="content-wrapper-withBorder">
        
        <table class="listview">  
        	 <tr>
        	<td colspan=4>
			
            <a class="viewer_email" href="#" id="email" title="email"></a>
            <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
            <a class="viewer_print" href="#" id="print" title="print"></a>

            </td>
        </tr>         
          <tr>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="lastname">Full Name</a></th>
              <th class="col_200"><a href="#" class="sortBy" returnFilter="course_name">Course</a></th>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="start_year">School Year</a></th>
              <th class="col_50">Action</th>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">

                <td><?=$row["lastname"].", ".$row["firstname"]." " .$row["middlename"]?></td> 
                <td><?=getCourseName($row["course_id"])?></td>
                <td><?=getSchoolYearStartEnd(getSchoolYearIdByTermId($row['term_id'])).'('.getSchoolTerm($row['term_id']).')'?></td>
                <td class="action">
                    <ul>
	                    <li><a class="applicant" href="#" title="View Applicant" returnId="<?=$row['id']?>" returnComp="<?=$_REQUEST['comp']?>"></a></li>
                    </ul>
                </td>
            </tr>
        <?php           
        $x++;  
		$ctr++;  }
        }
        else 
        {
                echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';;
        }
        ?>
        </table> 
        </div>
</div>
</div>
</div>
</div>
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
<div id="dialog" title="Student Profile">
    Loading...
</div><!-- #dialog -->
