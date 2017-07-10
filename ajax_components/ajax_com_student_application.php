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
		$('#dialog').load('lookup/lookup_com_student_application.php?id='+param+'&comp='+param2, null);
		$('#dialog').dialog('open');
		return false;
	});
	


/*$(function(){

	// Dialog			
	$('#dialog_acc').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Accept": function() { 
					getselected();
					if($('#req').val()!='')
					{
						clearTabs();
						$('#room_list').addClass('active');
						$('#view').val('list');
						$('#action').val('save');
						$("form").submit();
						$(this).dialog("close");
					}
					else
					{
						alert('No Selected Requirement');
						return false;
					}
				},
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		}
	});
	
	// Dialog Link
	$('.accept').click(function(){
		var param = $(this).attr("returnId");
		var param2 = $(this).attr("returnComp");
		var param3 = $(this).attr("returnAd");
		$('#sid').val($(this).attr("returnId"));
		$('#dialog_acc').load('lookup/lookup_com_requirements.php?id='+param+'&comp='+param2+'&adm='+param3, null);
		$('#dialog_acc').dialog('open');
		return false;
	});
	
});*/

	$('#accept').click(function(){
		var ids = "";
		var err = 0;
		for(var x=1;x<=$('#num').val();x++)
		{ 
			if ($('#id_' + x).attr("checked")) {
				var chek = $('#id_' + x).val();
					
					if (ids != "")
						ids += ",";
						ids += chek;
		
			}
		}
		
				if(ids!='')
				{
					var chek = $('#id_' + $('#num').val()).val();
					
					/*$.ajax({
					type: "POST",
					data: "id="+chek,
					url: "ajax_components/ajax_com_validate_applicant.php",
					success: function(msg){
							if (msg == '')
							{*/
								if (confirm('Are you sure to accept this student/s?')) {
								$('#cheks').val(ids);
								var param = $('#comp').val();
								$('#room_list').addClass('active');
								$('#view').val('app');
								updateList();
								$("form").submit();
								//$('#box_container').html(loading);
								//$('#box_container').load('ajax_components/ajax_com_student_application_form.php?ids='+ids+'&comp='+param, null);
								}
							/*}
							else
							{
								alert('You are not allowed to approve applications from previous or on going school year.');
								return false;
							}
						} // success function
					});	 */// success	
					
				}
				else
				{
					alert('Select at least one student.');
					return false;
				}
					
	});

	$('#decline').click(function(){
		var ids = "";
		for(var x=1;x<=$('#num').val();x++)
		{
			if ($('#id_' + x).attr("checked")) {
				if (ids != "")
					ids += ",";
				ids += $('#id_' + x).val(); 
			}
		}

		if(ids!='')
		{
			if (confirm('Are you sure to decline this student?')) {
			clearTabs();
			$('#cheks').val(ids);
			$('#add_new').addClass('active');
			$('#view').val('add');
			$('#action').val('update');
			$('#sId').val($(this).attr("returnId"));
			$("form").submit();
			}
		}
		else
			{
				alert('No Selected Student/s.');
			}
	});
	
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
			var w=window.open ("pdf_reports/rep109.php?met=application list"+"&trm="+<?=$_REQUEST['filter_schoolterm']?>); 
			return false;
		});
		
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file?"))
			{
				$.ajax({
					type: "POST",
					data: "trm="+<?=$_REQUEST['filter_schoolterm']?>+"&met=application list&email=1",
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
						FROM tbl_student_application app,
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
	
	
	if(!checkCurriculumHasCurrent())
	{ 
		echo '<div id="message_container"><h4>No Current Curriculum is Set.</h4></div><p id="formbottom"></p>';
	}
	/* CHECK IF CURRICULUM IS ALREADY SET... REMOVE BY JULIUS
	else if(!checkCurriculumSubjectIsSet())
	{
		echo '<div id="message_container"><h4>Current Curriculum Subjects are not complete.</h4></div><p id="formbottom"></p>';
	}
	*/
	else if($row_ctr>0)
	{
		//This tells us the page number of our last page 
		$last = ceil($row_ctr/$page_rows); 			
				
		$max = 'limit ' .$pagenum .',' .$page_rows; 	
		
		$sql = "SELECT app.* 
						FROM tbl_student_application app,
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
			
		//echo generateStudentNumber(1);
        ?>
         <div id="print_div">
        <div id="printable">
        <div class="body-container">
        
        <table class="listview"> 
        <tr>
        <td colspan="6">
        <a class="viewer_email" href="#" id="email" title="email"></a>
        <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
        <a class="viewer_print" href="#" id="print" title="print"></a>
        </td>
        </tr>     
          <tr>
          	<th class="col_20">&nbsp;</th>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="lastname">Full Name</a></th>
              <th class="col_200"><a href="#" class="sortBy" returnFilter="course_name">Course</a></th>
              <th class="col_150"><a href="#" class="sortBy" returnFilter="start_year">School Year</a></th>
              <th class="col_100"><a href="#" class="sortBy" returnFilter="entrance_date">Examination Date</a></th>
              <th class="col_70">Action</th>
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
				
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
               <td><input type="checkbox" name="id[]" id="id_<?=$ctr?>" value="<?=$row['id']?>" />
                <td><?=$row["lastname"].", ".$row["firstname"]." " .$row["middlename"]?></td> 
                <td><?=getCourseName($row["course_id"])?></td>
                <td><?=getSchoolYearStartEnd(getSchoolYearIdByTermId($row['term_id'])).'('.getSchoolTerm($row['term_id']).')'?></td>
                <td><?=getExamDate($row['entrance_date'])?></td>
                <td class="action">
                    <ul>
	                    <li><a class="applicant" href="#" title="View Applicant" returnId="<?=$row['id']?>" returnComp="<?=$_REQUEST['comp']?>"></a></li>
                        <!--<li><a class="accept" href="#" title="Accept" returnId="<?=$row['id']?>" returnStud=<?=$ctr?> returnComp="<?=$_REQUEST['comp']?>" returnAd="<?=$row['admission_type']?>"></a></li>
                        <li><a class="decline" href="#" title="Decline" returnId="<?=$row['id']?>"></a></li>!-->
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
        }//
        ?>
        </table> 
        </div>
        </div>
        </div>
        </div>
        </div>
        <div class="formview">
        <p class="button_container">
            <input type="hidden" name="num" id="num" value="<?=mysql_num_rows($result)?>" />
            <a href="#" class="button" title="Save" id="accept"><span>Accept</span></a>
            
            <a href="#" class="button" title="Submit" id="decline"><span>Decline</span></a>
        </p>
        </div>
        
        <p id="pagin">

        	<?php
            /*for($x=1;$x<=$last;$x++) {
                if ($_REQUEST['pageNum'] == $x) {
            ?>	
                <a href="#"><?=$x?></a>
            <?php		
                } else {
            ?>
                <a href="#list" onclick="updateList(<?=$x?>)"><?=$x?></a>
            <?php } 
            } */
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
<div id="dialog" title="Student Profile">
    Loading...
</div><!-- #dialog -->
<!-- LIST LOOK UP
<div id="dialog_acc" title="Student Requirements">
    Loading...
</div> #dialog -->
