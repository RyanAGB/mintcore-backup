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
 $(document).ready(function(){  
	
	$('#accept').click(function(){
		var ids = "";
		for(var x=1;x<=$('#num').val();x++)
		{
			if ($('#id_' + x).attr("checked")) {
				if (ids != "")
					ids += ",";
				ids += $('#id_' + x).val(); 
			}
		}
		
		if(ids>0)
			{
				if (confirm('Are you sure to accept this student/s?')) {
				$('#cheks').val(ids);
				var param = $('#comp').val();
				$('#box_container').html(loading);
				$('#box_container').load('ajax_components/ajax_com_student_application_form.php?comp='+param, null);
				}
			}
			else
			{
				alert('Select at least one student.');
			}
	});

	$('#cancel').click(function(){
		clearTabs();
		$('#room_list').addClass('active');
		$('#view').val('list');
		updateList();
		$("form").submit();
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
	if($row_ctr>0)
	{
		//This tells us the page number of our last page 
		$last = ceil($row_ctr/$page_rows); 			
				
		$max = 'limit ' .$pagenum .',' .$page_rows; 	
		
		$ids = explode(',',$_REQUEST['ids']);
?>     
    
        <?php  
            $x = 0;
			$ctr = 1;
			
		//echo generateStudentNumber(1);
        ?>
        
        <table class="listview"> 
         <?php
		foreach($ids as $id)
		{
		 $sql = "SELECT app.* 
						FROM tbl_student_application app,
							tbl_course course,
							tbl_school_year year,
							tbl_school_year_term term
						WHERE app.course_id = course.id AND
							year.id = term.school_year_id AND 
							term.id = app.term_id AND app.id=".$id."
							" .$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);
		
            while($row = mysql_fetch_array($result)) 
            { 
			//echo $ctr; generateStudentNumber($row["course_id"],$ctr)
        ?>     
          <tr>
              <th class="col_250" colspan="3"><a href="#" class="sortBy" returnFilter="lastname"><?=$row["lastname"].", ".$row["firstname"]." " .$row["middlename"]?></a></th>
          </tr>
       
            <tr class="<?=($x%2==0)?"":"highlight";?>">
               <td>Student Number:
                 <input type="text" class="txt_150" name="number[]" id="number_<?=$row['id']?>" value="" /></td> 
              <td>Score: <input type="text" class="txt_150" name="score[]" id="score_<?=$row['id']?>" value="" /></td>
            </tr>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
              <td>Scholarship: 
              <input type="text" class="txt_150" name="scholar_<?=$row['id']?>" id="scholar_<?=$row['id']?>2" value="" /></td>
              <td>Scholarship Type: 
              <select name="scholar_type_<?=$row['id']?>" id="scholar_type_<?=$row['id']?>2">
                <option value="P">Promo</option>
                <option value="A">Academic</option>
              </select></td>
            </tr>
            <tr>
            <td colspan="2">Requirements: </td>     
        </tr>
        <?php
       $sql = "SELECT * FROM tbl_requirements WHERE admission='".$row['admission_type']."'";
	   $query = mysql_query($sql);
	   
	   $x=0;
	   while($row = mysql_fetch_array($query))
	   {
	   ?>				
            <tr>
            <td colspan="2">		
				<input name="chk[]" type="checkbox" value="<?=$row['id']?>" id="chk<?=$x?>"/>
			<?=$row['requirement']?></td>
            </tr>
		<?php	
		$x++;
		}
        ?>
        <input type="hidden" name="cnt" id="cnt" value="<?=mysql_num_rows($query)?>" />
        
        <?php            
		$ctr++;  }
		}
        ?>
        </table> 
        <div class="formview">
  <p class="button_container">
            <input type="hidden" name="num" id="num" value="<?=mysql_num_rows($result)?>" />
            <a href="#" class="button" title="Save" id="accept"><span>Accept</span></a>
            
            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>
        </p>
        </div>
        <p id="pagin">        
        </p>
        
<?php
	}
	else 
	{
		echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';
	}
?>
<!-- LIST LOOK UP
<div id="dialog" title="Student Profile">
    Loading...
</div> #dialog -->
<!-- LIST LOOK UP
<div id="dialog_acc" title="Student Requirements">
    Loading...
</div> #dialog -->
