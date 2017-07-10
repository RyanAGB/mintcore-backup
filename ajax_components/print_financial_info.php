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

	 $sqlfee = "SELECT * FROM tbl_school_fee WHERE publish =  'Y'";         
     $resultfee = mysql_query($sqlfee);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CORE - Student Information System</title>	
<link type="text/css" href="../css/style_printPage.css" rel="stylesheet" />	
<link type="text/css" href="css/style_tableList.css" rel="stylesheet" />	
<link type="text/css" href="css/style_lookup.css" rel="stylesheet" />	
<link type="text/css" href="css/style_formObjects.css" rel="stylesheet" />		 
</head>
<body>

<table width="70%">
    <tr>
        <td width="21%" valign="top" style='font-weight:bold'>Student Name:</td>
        <td width="79%"><?=$row['firstname']. ", " . $row['lastname'] ." " . $row['middlename']?></td>
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
<table width="70%">
    <tr>
        <td width="50%" valign="top">
            <table width="100%" border="1" cellpadding="3" cellspacing="0">      
                <tr>
                  <th>Fee</th>    
               	  <th>Total</th>                         
              	</tr>
                <?php
                $sub_total = 0;
                while($rowfee = mysql_fetch_array($resultfee)) 
                {
                    $total = getStudentTotalFeeLecLab($rowfee['id'],$row['id']);
                    ?>
                    <tr class="<?=($x%2==0)?"":"highlight";?>"> 
                        <td><?=$rowfee['fee_name']?></td>
                        <td>
                        <div align="right">
                        Php <?=$total==''?'0.00':number_format($total, 2, ".", ",")?>
                        </div></td>                                
                    </tr>
                    <?php 
                    $sub_total += $total;          
                }
                ?>
                <tr>
                    <td><strong>Total</strong></td>
                    <td>
                    <div align="right"><strong>
                    Php <?=number_format($sub_total, 2, ".", ",")?>
                    </strong></div></td>
                </tr> 
            </table>
        </td>
        <td> 
            <table width="100%">      
                <tr class="<?=($x%2==0)?"":"highlight";?>"> 
                    <td>Total Tuition Fee Amount:</td>
                    <td><div align="right">
                    Php <?=number_format($sub_total, 2, ".", ",")?>
                    </div></td>                             
                </tr>
                <tr>
                    <td>Balance Carried Forward:</td>
                    <td><div align="right">Php 0.00</div></td>
                </tr>                
                <tr>
                    <td>Current Charges:</td>
                    <td><div align="right">
                    Php <?=number_format($sub_total, 2, ".", ",")?>
                    </div></td>
                </tr>
                <tr>
                    <td><strong>Total Current Charges:</strong></td>
                    <td><div align="right">
                    Php <?=number_format($sub_total, 2, ".", ",")?>
                    </div></td>
                </tr>
                <tr>
                <?php
				$sql_payment = "SELECT * FROM tbl_student_payment WHERE student_id =" .$id;
				$qry_payment = mysql_query($sql_payment);
				$row_payment = mysql_fetch_array($qry_payment);
				
				$total_charges = $sub_total - $total;
				
				$total_charges = $totalpay + getStudentDiscount($row_payment['discount_id'], $id, $total_charges); 

				if($row_payment['discount_id'] != '0')
				{
				?>	
                    <td>Student Discount: (<?=getDiscountValue($row_payment['discount_id'])?>%)</td>
                    <td>
                    <div align="right">
                        Php <?=number_format($total_charges, 2, ".", ",")?>	
                    </div>
                    </td>
				<?php
				}
				else
				{
				?>  
                    <td>Student Discount:</td>
                    <td><div align="right">Php 0.00</div></td>
				<?php
				}
				?> 
                </tr>
                <tr>
                    <td colspan=2 style="border-top:1px solid #333;">&nbsp;</td>
                </tr>
                <tr>
                    <td><strong>Total Charges:</strong></td>
                    <td><div align="right"><strong>
                    Php 
                    <?=number_format($sub_total, 2, ".", ",")?>
                    </strong></div></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div style="page-break-before: always;">&nbsp;</div>
<?php
	}
?>
<script type="text/javascript">
window.print();
</script>