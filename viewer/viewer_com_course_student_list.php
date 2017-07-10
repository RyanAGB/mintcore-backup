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

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp']))
{
	header('Location: ../forbid.html');
}
else
{

$id = $_REQUEST['id'];
$filter_schoolterm = $_REQUEST['filter_schoolterm'];
$filterfield = $_REQUEST['filterfield'];
$filtervalue = $_REQUEST['filtervalue'];
$filterfield2 = $_REQUEST['filterfield2'];
$filtervalue2 = $_REQUEST['filtervalue2'];
$filter = $_REQUEST['filter']!=''?$_REQUEST['filter']:'A';
?>
<script type="text/javascript">
	$(function(){
		
		$('#print').click(function() {
			var w=window.open();
			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
			w.document.write($('#lookup_content').html());
			w.document.close();
			w.focus();
			w.print();
			//w.close()
			return false;
		});
		
		$('#pdf').click(function() {
			var w=window.open ("pdf_reports/rep108.php?id=<?=$id?>&trm=<?=$filter_schoolterm?>&met=course list&filter=<?=$filter?>");
			//&course="+$('#filtercourse').val()+"&year="+$('#filteryear').val() 
			return false;
		});
		
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file to this student?"))
			{
				$.ajax({
					type: "POST",
					data: "id=<?=$id?>&trm=<?=$filter_schoolterm?>&met=schedule&email=1",
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
		
		
		$('#filter').change(function(){
			updateList();
		});
	});
	
	function updateList()
{
		var param = '';
		
		if($('#filter').val() != '')
		{
			param = param + '&filter=' + $('#filter').val();
		}
		param = param + '&comp=<?=$_REQUEST['comp']?>&id=<?=$id?>&filter_schoolterm=<?=$filter_schoolterm?>';
		//alert(param);
		$('#dialog').load('viewer/viewer_com_course_student_list.php?listrow=10'+ param, null);
}
</script>

<?php	
	
	if(isset($filterfield)){
		$arr_sql[] =  $filterfield.'='.$filtervalue;
	}	
	if(isset($filterfield2)){
		$arr_sql[] =  $filterfield2.'='.$filtervalue2;
	}	
	
	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : ' AND ' .$arr_sql = implode(' AND ', $arr_sql);
	}	
	
	if($filter == 'E')
	{
	$sql = "SELECT * FROM tbl_student a,tbl_student_enrollment_status b 
					WHERE a.id=b.student_id 
					AND a.course_id =  " . $id . " AND b.enrollment_status = 'E' AND b.term_id = ".$filter_schoolterm. 
					$sqlcondition." ORDER BY a.lastname ASC" ;	
	
	}
	else
	{
	
	$sqldet = "SELECT * FROM tbl_student WHERE course_id = $id";
	$resultdet = mysql_query($sqldet);
	$row = mysql_fetch_array($resultdet);
	
	$sql = "SELECT * FROM tbl_student WHERE course_id = " . $id .$sqlcondition ;	
	}
										
		$result = mysql_query($sql);
		$ctr = mysql_num_rows($result);
		
?>

	<!--Filter By: Course<select name="filtercourse" id="filtercourse" class="txt_200" >
    
                    <option value="" >-Select-</option>
                     <?=generateCourse($filtervalue)?>  
                </select>
                <input type="hidden" name="comp" id="comp" value="<?=$_REQUEST['comp']?>" />
            <label>&nbsp;</label>
            
            Year<select name="filteryear" id="filteryear" class="txt_100" >
                   <option value="" >-Select-</option>
                     <?=generateYearLevelByCourse($filtervalue,$filtervalue2)?>
                </select>!-->
                
                <select name="filter" id="filter" class="txt_150" >
    
                    <option value="A" <?=$filter=='A'?'selected="selected"':''?>>All</option>
                    <option value="E" <?=$filter=='E'?'selected="selected"':''?> >Enrolled</option>
                </select>
            
            <label>&nbsp;</label>
    <div id="lookup_content">
<div id="printable">
<div class="body-container">
<div class="header">
<?php
		/*if( $filterfield!=''&&$filterfield2!='')
		{*/
		?>
<table width="100%">
<tr>
  <td colspan="3" align="center" class="bold">PROGRAM LIST | <?=getSchoolTerm($filter_schoolterm)?> | SY <?=getSchoolYearStartEndByTerm($filter_schoolterm)?></td>
</tr>
  <tr>
    <td class="bold" valign="top">Course Name:</td>
    <td><?=getCourseName($id)?></td>
  </tr>
  <tr>
    <td class="bold">Course Code:</span></td>
    <td><?=getCourseCode($id)?></td>
  </tr>
   
 
  <tr>
    <td colspan=3 class="title-action">
    <?php if($ctr > 0)
		{ ?>
    <a class="viewer_email" href="#" id="email" title="email"></a>
    <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
    <a class="viewer_print" href="#" id="print" title="print"></a>
    <?php } ?>    </td>
</tr>
  <tr>
    <td colspan=3>&nbsp;</td>
  </tr>
</table>
 
</div>
<div class="content-container">

<div class="content-wrapper-withoutBorder">
    <table class="classic">      
        <tr>
        	<th class="col1_50">&nbsp;</th>
        	<th class="col1_150">Student No.</th>
            <th class="col1_300">Student Name</th>
        </tr>
        <?php
        $x = 1;
		
		if($ctr > 0)
		{
			while($row = mysql_fetch_array($result)) 
			{ 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
            	<td><?=$x?></td>
            	<td><?=$row["student_number"]?></td> 
                <td><?=$row["lastname"].' , '.$row["firstname"]." ".$row["middlename"]?></td>
            </tr>
        <?php 
            $x++;          
        	}
		}
		else
		{
        ?>
            <tr> 
                <td colspan="7">No record found</td>                                
            </tr>        
        <?php
		}
		?>
        
    </table> 
     <?php
      /*  }
		else
		{
		
        ?>
        	<div style="font-family:Arial; font-size:12px; padding-top:10px;">
                <label>Please select course.</label>
            </div>
         <?php
		 }*/
		 ?>
</div> 
</div>
</div>
</div><!-- #lookup_content -->
<?php
}
?>