<?php
	include_once("../config.php");
	include_once("../includes/functions.php");
	include_once("../includes/common.php");

	if(isset($_REQUEST['print'])){
		$con = $_REQUEST['print'];
	
		$sql = "SELECT * FROM tbl_employee ".$con;						
		$query = mysql_query($sql);
	}
	if(isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];

		$sql = "SELECT * FROM tbl_employee WHERE id =".$id;						
		$query = mysql_query($sql);
	}


	while($row = mysql_fetch_array($query)){
	
	$sql_employee_photo = "SELECT * FROM tbl_employee_photo WHERE employee_id = ".$row['id'];
	$query_employee_photo = mysql_query($sql_employee_photo);
	$row_employee_photo = mysql_fetch_array($query_employee_photo);
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

<table width=100% style=" font-family:Arial; font-size:12px;">
<tr>
    <td  style="font-size:15px; font-weight:bold; padding-top:20px;"><?=SCHOOL_NAME?>
    <div style="font-size:12px;">Employee Information</div>
    </td>
    <td align=right style="padding-top:20px;">
    <!-- 20100214 Feb/14/2010-->
    <?=date("M/d/Y") ?>
    </td>
</tr>
<tr>
    <td colspan=2 style="border-top:1px solid #333;">&nbsp;</td>
</tr>
</table>

<table border="0" cellspacing="10" cellpadding="0">
  <tr>
    <td width="300">
	<?php
    if($row_employee_photo['image_file'] != '')
    {
    ?>
        <img src="../includes/employee_image.php?employee_id=<?=$row['id']?>"/>
    <?php
    }
    else
    {
    ?>
        <img src="../images/NoPhotoAvailable.jpg"/>
    <?php
    }
    ?>
    </td>
    <td width="300">
            
        <table width=100% >
            <tr>
                <td colspan=2 style="border-bottom:1px solid #333;  font-size:13px;  font-weight:bold;">Personal Information</td>
            </tr>
            <tr>
                <td width=45% style='font-weight:bold' valign="top">Employee Name:</td>
                <td width=55%><?=$row['firstname']. ", " . $row['lastname'] ." " . $row['middlename']?></td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">Employee No.:</td>
              <td><?=$row['emp_id_number']?></td>
            </tr>
            <tr>
              <td style='font-weight:bold' valign="top">Employee Type:</td>
              <td><?=getEmpType($row['employee_type'])?></td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">Department:</td>
                <td><?=getDeptName($row['department_id'])?></td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">Gender:</td>
                <td>
                <?php
                if($row['gender'] == 'M')
				{
					echo "Male";
				}
				else if($row['gender'] == 'F')
				{
					echo "Female";
				}
				?>
                </td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">Civil Status:</td>
                <td>
                <?php
                if($row['civil_status'] == 'M')
				{
					echo "Married";
				}
				else if($row['civil_status'] == 'S')
				{
					echo "Single";
				}
				?>
                </td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">BirthDate:</td>
                <td>
				<?php
				if($row['birth_date'] != '')
				{			
                	$birthday = explode ('-',$row['birth_date']);		
					$birth_year = $birthday['0'];
					$birth_day = $birthday['1'];
					$birth_month = $birthday['2'];
					
					echo date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year));
				}
				else
				{
				?>
				
				<?php	
				}
				?>
                </td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">E-mail Address:</td>
                <td><?=$row['email']?></td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">Contact No.:</td>
                <td><?=$row['tel_number']?></td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">Mobile No.:</td>
                <td><?=$row['mobile_number']?></td>
            </tr>
            <tr>
              <td style='font-weight:bold' valign="top">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
        </table>

  	</td>
  </tr>
  <tr>
    <td valign="top">
    
        <table width=100%>
            <tr>
                <td colspan=2 style="border-bottom:1px solid #333;  font-size:13px; font-weight:bold;">Present Address</td>
            </tr>
            <tr>
                <td width=45% style='font-weight:bold' valign="top">Present Address:</td>
                <td width=55%><?=$row['present_address']?></td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">Zipcode:</td>
                <td width=55%><?=$row['present_address_zip']?></td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>  
  
    </td>
    <td valign="top">
    
        <table width=100%>
            <tr>
                <td colspan=2 style="border-bottom:1px solid #333;  font-size:13px; font-weight:bold;">Permanent Address</td>
            </tr>
            <tr>
                <td width=45% style='font-weight:bold' valign="top">Permanet Address:</td>
                <td width=55%><?=$row['permanent_address']?></td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">Zipcode:</td>
                <td><?=$row['permanent_address_zip']?></td>
            </tr>
            <tr>
              <td style='font-weight:bold' valign="top">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
        </table>  
  
    </td>
  </tr>
  <tr>
  	<td valign="top">
        <table width=100% border=0>
            <tr>
                <td colspan=2 style="border-bottom:1px solid #333;  font-size:13px;  font-weight:bold;">Person to notify in case of emergency</td>
            </tr>
            <tr>
                <td width=45% style='font-weight:bold' valign="top">Name:</td>
               	<td><?=$row['ice_fullname']?></td>
          	</tr>
            <tr>
                <td style='font-weight:bold' valign="top">Address:</td>
              	<td><?=$row['ice_address']?></td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">Contact No.:</td>
              	<td><?=$row['ice_tel_number']?></td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>  
  
    </td>
  </tr>
</table>
<div style="page-break-before: always;">&nbsp;</div>
<?php } ?>
<script type="text/javascript">
window.print();
</script>