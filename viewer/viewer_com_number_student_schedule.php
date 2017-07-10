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
?>
<script type="text/javascript">
	$(function(){
		$('.selector').click(function() {
			//var id = $(this).attr("val");		
			var valTxt = $(this).attr("returnTxt");
			var valId = $(this).attr("returnId");
			$('#room_id').attr("value", valId);
			$('#room_display').attr("value", valTxt);
			
			$('#dialog').dialog('close');			
		});
		
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
			var w=window.open ("pdf_reports/rep108.php?id="+<?=$id?>+"&trm="+<?=$filter_schoolterm?>+"&met=summary"); 
			return false;
		});
		
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file to this student?"))
			{
				$.ajax({
					type: "POST",
					data: "id="+<?=$id?>+"&trm="+<?=$filter_schoolterm?>+"&met=schedule&email=1",
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
	
	if (isset($filter_schoolterm) and ($filter_schoolterm != "")){
		$arr_sql[] =  "stud_sched.term_id = " . $filter_schoolterm;
	}	
	
	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sql = implode(' AND ', $arr_sql);
	}	

	$total = array();
		
		$sql = "SELECT * FROM tbl_student_schedule WHERE subject_id=".$id." AND term_id=".$filter_schoolterm;
		$query = mysql_query($sql);
		
		if(mysql_num_rows($query)>0)
		{
		
			while($row = mysql_fetch_array($query))
			{
			
				$sql2 = "SELECT * FROM tbl_student_enrollment_status WHERE student_id = ".$row['student_id'];
				$query2 = mysql_query($sql2);
				
				if(mysql_num_rows($query2)>0)
				{
					$row2 = mysql_fetch_array($query2);
					
					if($row2['enrollment_status']=='E')
					{
						$total[] = $row['student_id'];
					}
					
				}
				
			}
			
		}
?>
<div id="lookup_content">
<div id="printable">
<div class="body-container">
<div class="header">
<table width="100%">
  <tr>
    <td width="18%" class="bold">Subject Name:</td>
    <td width="82%"><?=getSubjName($id)?></td>
  </tr>
  <tr>
    <td class="bold" valign="top">Subject Code:</td>
    <td><?=getSubjCode($id)?></td>
  </tr>
  <tr>
        <td class='bold' valign="top">School Year:</td>
        <td><?=getSYandTerm($filter_schoolterm)?></td>
      </tr>
  <tr>
    <td>&nbsp;</td>
</tr>
  <tr>
    <td colspan=2 class="title-action">
    <?php if(count($total) > 0)
		{ ?>
   <!-- <a class="viewer_email" href="#" id="email" title="email"></a>!-->
    <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
    <!--<a class="viewer_print" href="#" id="print" title="print"></a>!-->
    <?php } ?>
    </td>
</tr>
  <tr>
    <td colspan=2>&nbsp;</td>
  </tr>
</table>
 
</div>
<div class="content-container">

<div class="content-wrapper-withoutBorder">
    <table class="classic">      
        <tr>
        	<th class="col1_150">Student Name</th>
            <th class="col1_150">Course</th>
        </tr>
        <?php
        $x = 1;
		
		if(count($total) > 0)
		{
			foreach($total as $tot) 
			{ 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
            	<td><?=getStudentFullName($tot)?></td> 
                <td><?=getStudentCourse($tot)?></td>
            </tr>
        <?php          
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
    
</div> 
</div>
</div>
</div><!-- #lookup_content -->
<?php
}
?>