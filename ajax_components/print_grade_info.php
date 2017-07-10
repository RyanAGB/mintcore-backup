<?php
	include_once("../config.php");
	include_once("../includes/functions.php");
	include_once("../includes/common.php");

	if(isset($_REQUEST['print'])){
		$con = $_REQUEST['print'];
		$sql = "SELECT * FROM tbl_student ".$con;						
		$query = mysql_query($sql);
	}
	if(isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
		$sql = "SELECT * FROM tbl_student WHERE id = ".$id;						
		$query = mysql_query($sql);
	}

	while($row = mysql_fetch_array($query)){
	
	$sql_student = "SELECT 
						a.schedule_id,
						b.id,
						b.subject_id,
						b.room_id,
						b.employee_id 
					FROM 
						tbl_student_schedule a,
						tbl_schedule b 
					WHERE 
						a.schedule_id = b.id AND
						a.student_id = ".$row ['id'];
	$query_student = mysql_query($sql_student);
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
    <td colspan=2 style="border-top:1px solid #333;">&nbsp;</td>
</tr>
</table>
 
<div id="lookup_content">

    <table class="fieldsetList">      
        <tr>
            <th class="col1_150">Code</th>  
            <th class="col1_150">Subject Name</th>   
			<?php
            $sql_period = "SELECT * FROM tbl_school_year_period WHERE 
                        school_year_id=".CURRENT_SY_ID." AND 
                        term_id=".CURRENT_TERM_ID." ORDER BY period_order";						
            $query_period = mysql_query($sql_period);
            while($row_period = mysql_fetch_array($query_period))
            {
            ?>
                <th class="col1_50"><?=$row_period['period_name']?></th>
            <?php
            }
            ?>  
        </tr>
        <?php
        $x = 1;
        while($rowstud = mysql_fetch_array($query_student)) 
        { 
        ?>
            <tr class="<?=($x%2==0)?"":"highlight";?>"> 
                <td><?=getSubjCode($rowstud["subject_id"])?></td>
                <td><?=getSubjName($rowstud["subject_id"])?></td>
                <?php
				$query_period = mysql_query($sql_period);
				$period_ctr = 1;
				while($row_period = mysql_fetch_array($query_period))
				{
				?>
                <td><?=getStudentGradePerPeriod($rowstud["id"],$row_period['id'], $row['id'])?></td>
              	<?php
				$period_ctr++;
				}
				?>
            </tr>
        <?php 
            $x++;          
        }
        ?>
    </table>
<div style="page-break-before: always;">&nbsp;</div>
<?php } ?>
<script type="text/javascript">
window.print();
</script>