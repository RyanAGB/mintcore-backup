<?php
	include_once("../config.php");
	include_once("../includes/functions.php");

	$id = $_REQUEST['id'];

	$sqldet = "SELECT * FROM tbl_student WHERE id = $id";
	$resultdet = mysql_query($sqldet);
	$row = mysql_fetch_array($resultdet);
	$cur = $row['curriculum_id'];
	$yir = $row['year_level'];
	
	if($row['admission_type']=='T'){
	$sqlcur = "SELECT * FROM tbl_student_final_grade WHERE type = 'C' AND student_id = ".$id;
	$querycur = mysql_query($sqlcur);
	$ctr_rowcur = mysql_num_rows($querycur);
	}

	$sqlcurr = "SELECT * FROM tbl_curriculum WHERE id = ".$cur;
	$rescurr = mysql_query($sqlcurr);
	$rowcurr = mysql_fetch_array($rescurr);
	$termper = $rowcurr['term_per_year'];

	$sqlsub = "SELECT * FROM tbl_student_schedule a, tbl_school_year_term b 
	WHERE a.term_id = b.id AND student_id = ".$id;
	  $resultsub = mysql_query($sqlsub);
	  $ctr_row = mysql_num_rows($resultsub);
	
	$curTerm = 0;
	$total_units = 0;
	$tem = 0;
	$yirs = $yir;

?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CORE - Student Information System</title>	
<link type="text/css" href="../css/style_lookup.css" rel="stylesheet" />
<link type="text/css" href="../css/style_printPage.css" rel="stylesheet" />	
<link type="text/css" href="../css/style_formObjects.css" rel="stylesheet" />	 
</head>
<body>
	

<table width="100%">
  <tr>
    <td width="18%" valign="top" style='font-weight:bold'>Student Name:</td>
    <td width="82%"><?=$row['lastname']. ", " . $row['firstname'] ." " . $row['middlename']?></td>
  </tr>
  <tr>
    <td style='font-weight:bold' valign="top">Student Number:</td>
    <td><?=$row['student_number']?></td>
  </tr>
  <tr>
    <td style='font-weight:bold' valign="top">Course:</td>
    <td><?=getCourseName($row['course_id'])?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
</tr>
  <tr>
    <td colspan=2 style="border-top:1px solid #333;"></td>
</tr>
</table>

<?php
	   if($ctr_rowcur > 0)
				  {?>
<table border="0" cellspacing="10" cellpadding="0">

    <td width="600" style="padding-left:10px;">

        <table border="0" cellspacing="0" cellpadding="0" id="classic">
                  <tr>
            <td colspan="4"><strong>Credited Grades</strong></td>
          </tr>
                  <tr>
                    <th width="100"><strong>Subject Code</strong></th>
                    <th width="350"><strong>Subject</strong></th>
                    <th width="100"><strong>Grade</strong></th>
                  </tr>
				<?php
					 while($rowcur = mysql_fetch_array($querycur))
					  {
				?>
                      <tr>
                        <td><?=getSubjCode($rowcur['subject_id'])?></td>
                        <td><?=getSubjName($rowcur['subject_id'])?></td>
                        <td><?=decrypt($rowcur['final_grade'])?></td>
                      </tr>  
                      <?php } ?>               
                </table>            
            </td>
          </tr>
          <tr>
            <td width="600">&nbsp;</td>
          </tr>          
        </table>  
	</td>
  </tr>   
</table>

	<?php
	}
	   if($ctr_row > 0)
				  {
		while($row = mysql_fetch_array($resultsub))
					  {
					  if ($curTerm != $row["term_id"]) {
			
			if($tem==$termper){
				$yirs++;
			}
			  
    		if ($curTerm != 0) {
	?>
    		<tr>
                      <td colspan="2">&nbsp;</td>
                      <td><?=$total_units?></td>
                      <td>&nbsp;</td>
                    </tr> 
    <?php 
    		}
			  
		?>
       <table border="0" cellspacing="10" cellpadding="0">
<?php
//for($ctr_year = 1; $ctr_year<= $yir; $ctr_year++)
//{
?>
  <tr>
    <td width="600"><?=getYearLevel($yirs) ?></td>
  </tr>
  <tr>
    <td width="600" style="padding-left:10px;">

        <table border="0" cellspacing="0" cellpadding="0" id="classic">
        
                  <tr>
            <td colspan="4"><strong><?=$row['school_term']?></strong></td>
          </tr>
                  <tr>
                    <th width="100"><strong>Subject Code</strong></th>
                    <th width="350"><strong>Subject</strong></th>
                    <th width="50"><strong>Units</strong></th>
                    <th width="100"><strong>Grade</strong></th>
                  </tr>
                  <?php
				  $curTerm = $row["term_id"];
				  $total_units = 0;
				  $tem++;
				  }
				  
				
					  $sql_grade = "SELECT final_grade FROM tbl_student_final_grade 
					  			WHERE subject_id = ".$row['subject_id']." 
								AND term_id = '".$row["term_id"]."'
								AND student_id = ".$id;
						$result_grade = mysql_query($sql_grade);
						$row_grade = mysql_fetch_array($result_grade);
				  ?>
                      <tr>
                        <td><?=getSubjCode($row['subject_id'])?></td>
                        <td><?=getSubjName($row['subject_id'])?></td>
                        <td><?=$row['units']?></td>
                        <td><?=decrypt($row_grade[0])?></td>
                      </tr>
                  <?php
				  		$total_units += $row['units'];
				  }
				 
				  ?>
                  	 
                    <tr>
                      <td colspan="2">&nbsp;</td>
                      <td><?=$total_units?></td>
                      <td>&nbsp;</td>
                    </tr>                 
                </table>   
                         
            </td>
          </tr>
          <tr>
            <td width="600">&nbsp;</td>
          </tr>    
      
        <?php
		}
		?>
        </table>
<script type="text/javascript">
window.print();
</script>
</body>
</html>
