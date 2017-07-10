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

?>
<script type="text/javascript">
	$(function(){
		
		$('#print4').click(function() {
			var w=window.open();
			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
			w.document.write($('#lookup_content').html());
			w.document.close();
			w.focus();
			w.print();
			//w.close()
			return false;
		});
		
		$('#pdf4').click(function() {
			var w=window.open ("pdf_reports/rep108.php?trm="+<?=$filter_schoolterm?>+"&met=fail grade");
			//&course="+$('#filtercourse').val()+"&year="+$('#filteryear').val() 
			return false;
		});
		
		$('#email4').click(function() {
			
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
	
</script>

<?php		

	$sql = "SELECT * FROM tbl_student ORDER BY lastname ". 
					$sqlcondition ;	
										
		$result = mysql_query($sql);
		$ctr = mysql_num_rows($result);
		
?>

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
    <td colspan=3 class="title-action">
    <?php if($ctr > 0)
		{ ?>
    <a class="viewer_email" href="#" id="email4" title="email4"></a>
    <a class="viewer_pdf" href="#" id="pdf4" title="pdf4"></a>
    <a class="viewer_print" href="#" id="print4" title="print4"></a>
    <?php } ?>    </td>
</tr>
  <tr>
    <td colspan="3" align="center" class="bold">DEAN'S LIST | <?=getSchoolTerm($_REQUEST['filter_schoolterm'])?> | SY <?=getSchoolYearStartEndByTerm($_REQUEST['filter_schoolterm'])?></td>
  </tr>
</table>

 
</div>
<div class="content-container">

<div class="content-wrapper-withoutBorder">
    <table class="classic">      
        <tr>
            <th class="col1_150">Student Name</th>
            <th class="col1_150">Course</th>
            <th class="col1_150">Average</th>
        </tr>
        <?php
        $x = 1;
		
		if($ctr > 0)
		{
			$stud_id ='';
			while($row = mysql_fetch_array($result)) 
			{ 
				$deans_ave = getStudentIfDeansLister($_REQUEST['filter_schoolterm'],$row['id']);
				if($deans_ave > 0)
				{
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>">
				
                <td><?=$row["lastname"].' , '.$row["firstname"]." ".$row["middlename"]?></td> 
                <td><?=getCourseName($row["course_id"])?></td>
                <td><?=round($deans_ave,2)?></td>
            </tr>
        <?php 
            $x++;    
			$stud_id = $row['id'];      
        		}
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