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
		
		$('#print').click(function() {
			var w=window.open();
			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
			w.document.write($('#print_div').html());
			w.document.close();
			w.focus();
			w.print();
			w.close()
			return false;
		});
		$('#pdf').click(function() {
			var w=window.open ("pdf_reports/rep108.php?id="+<?=STUDENT_ID?>+"&trm="+<?=$term_id?>+"&met=grade"); 
			return false;
		});
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file to this student?"))
			{
				$.ajax({
					type: "POST",
					data: "id="+<?=STUDENT_ID?>+"&trm="+<?=$term_id?>+"&met=grade&email=1",
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

	if (isset($term_id) and ($term_id != "")){
		$arr_sql[] =  "stud_sched.term_id = " . $term_id;
	}		
		
	if (isset($_REQUEST["search_field"]) and ($_REQUEST["search_field"] != "") and ($_REQUEST["search_key"] != "") and ($_REQUEST["search_key"] != "")){
		$search_field = $_REQUEST["search_field"];
		$search_key = $_REQUEST["search_key"]; 
		$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
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
	$sql_pagination = "SELECT 
								stud_sched.schedule_id,
								sched.id,
								sched.subject_id,
								sched.room_id,
								sched.employee_id 
							FROM 
								tbl_student_schedule stud_sched,
								tbl_schedule sched 
							WHERE 
								stud_sched.schedule_id = sched.id AND 
								stud_sched.student_id = ".STUDENT_ID
								.$sqlcondition ;
						
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
		
		$sql = "SELECT 
						stud_sched.schedule_id,
						sched.id,
						sched.subject_id,
						sched.room_id,
						sched.employee_id
					FROM 
						tbl_student_schedule stud_sched,
						tbl_schedule sched 
					WHERE 
						stud_sched.schedule_id = sched.id AND 
						stud_sched.student_id = ".STUDENT_ID
						.$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);

?>                  
  <div id="print_div">
<div id="printable">
<div class="body-container">
<div class="header">
    <table width="100%" class="head">
      <tr>
        <td width="15%" class="bold">Student Name:</td>
        <td width="85%"><?=getStudentFullName(STUDENT_ID)?></td>
      </tr>
      <tr>
        <td class="bold">Student Number:</td>
        <td><?=getStudentNumber(STUDENT_ID)?></td>
      </tr>
      <tr>
        <td class="bold">Course:</td>
        <td><?=getStudentCourse(STUDENT_ID)?></td>
      </tr>
      <tr>
        <td class="bold">School Year:</td>
        <td><?=getSYandTerm($term_id)?></td>
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
            $x = 0;
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
                <th class="col1_150">Code</th>  
                <th class="col1_150">Subject Name</th>   
				<?php
                $sql_period = "SELECT * FROM tbl_school_year_period WHERE 
                            term_id=".$term_id." ORDER BY period_order";						
                $query_period = mysql_query($sql_period);
                while($row_period = mysql_fetch_array($query_period))
                {
                ?>
                    <th class="col1_50"><?=$row_period['period_name']?></th>
                <?php
                }
                ?> 
				<th class="col1_50">Grade</th> 
          </tr>
        <?php
            while($row = mysql_fetch_array($result)) 
            { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
                <td><?=getSubjCode($row["subject_id"])?></td>
                <td><?=getSubjName($row["subject_id"])?></td>
                <?php
				$query_period = mysql_query($sql_period);
				$period_ctr = 1;
				while($row_period = mysql_fetch_array($query_period))
				{
				?>
                <td <?=checkIfGradeIsPass(getStudentGradePerPeriod($row["id"],$row_period['id'], STUDENT_ID))==='N'?'style="color:red"':''?>><?=getStudentGradePerPeriod($row["id"],$row_period['id'],STUDENT_ID)?></td>
              	<?php
				$period_ctr++;
				}
				?>
                <td <?=checkIfGradeIsPass(getStudentFinalGrade(STUDENT_ID,$row["id"],$term_id))==='N'?'style="color:red"':''?>>
                    <?php
						$f_grade = getStudentFinalGrade(STUDENT_ID,$row["id"],$term_id);
                        if($f_grade != '')
                        {
                            echo getGradeConversionGrade($f_grade);
                        }
                        else
                        {
                            echo '0.0';
                        }
                    ?>  				
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
        </p>
        
<?php
	}
	else 
	{
		echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';
	}
?>
</div>
</div>
</div>
</div>
</div>