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

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp']))
{
	header('Location: ../forbid.html');
}
else
{

$id = $_REQUEST['id'];
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
			var w=window.open ("pdf_reports/rep107.php?id="+<?=$id?>+"&met=emp"); 
			return false;
		});
		
		$('#email').click(function() {
			
			if(confirm("Are you sure you want to email this file to this student?"))
			{
				$.ajax({
					type: "POST",
					data: "id="+<?=$id?>+"&met=emp&email=1",
					url: "pdf_reports/rep107.php",
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
	
	$sql = "SELECT * FROM tbl_employee WHERE id = $id";						
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);

	$sql_employee_photo = "SELECT * FROM tbl_employee_photo WHERE employee_id = $id";
	$query_employee_photo = mysql_query($sql_employee_photo);
	$row_employee_photo = mysql_fetch_array($query_employee_photo);
?>	
<div id="lookup_content">
<div id="printable">
<div class="body-container">
<div class="header">
<!--<div class="logo"><img src="includes/getimage.php?id=1" alt="" /></div>!-->
<table width=100%>
<tr>
    <td class="title-head"><?=SCHOOL_NAME?>
    <div class="title-head">Employee Information</div>
    </td>
    <td class="date" align=right>
    <!-- 20100214 Feb/14/2010-->
    <?=date("M/d/Y") ?>
    </td>
</tr>
<tr>
    <td colspan=2 align="right" class="title-action">
    <a class="viewer_email" href="#" id="email" title="email"></a>
    <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
    <a class="viewer_print" href="#" id="print" title="print"></a>
    </td>
</tr>

</table>
</div>
<div class="content-container">

<div class="content-wrapper-wholeBorder">
<table border="0" cellspacing="10" cellpadding="0">
  <tr>
    <td width="300">
	<?php
    if($row_employee_photo['image_file'] != '')
    {
    ?>
        <img src="includes/employee_image.php?employee_id=<?=$row['id']?>"/>
    <?php
    }
    else
    {
    ?>
        <img src="images/NoPhotoAvailable.jpg"/>
    <?php
    }
    ?>
    </td>
    <td width="300">
            
        <table width=100% >
            <tr>
                <td colspan=2 class="title-border" style="">Personal Information</td>
            </tr>
            <tr>
                <td width=45% class="bold">Employee Name:</td>
                <td width=55%><?=$row['lastname'].", " . $row['firstname'] ." " . $row['middlename']?></td>
            </tr>
            <tr>
                <td class="bold" valign="top">Employee No.:</td>
              <td><?=$row['emp_id_number']?></td>
            </tr>
            <tr>
              <td class="bold" valign="top">Employee Type:</td>
              <td><?=getEmpType($row['employee_type'])?></td>
            </tr>
            <tr>
                <td class="bold" valign="top">Department:</td>
                <td><?=getDeptName($row['department_id'])?></td>
            </tr>
            <tr>
                <td class="bold" valign="top">Gender:</td>
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
                <td class="bold" valign="top">Civil Status:</td>
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
                <td class="bold" valign="top">BirthDate:</td>
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
                <td class="bold" valign="top">E-mail Address:</td>
                <td><?=$row['email']?></td>
            </tr>
            <tr>
                <td class="bold" valign="top">Contact No.:</td>
                <td><?=$row['tel_number']?></td>
            </tr>
            <tr>
                <td class="bold" valign="top">Mobile No.:</td>
                <td><?=$row['mobile_number']?></td>
            </tr>
            <tr>
              <td class="bold" valign="top">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
        </table>

  	</td>
  </tr>
  <tr>
    <td valign="top">
    
        <table width=100%>
            <tr>
                <td colspan=2 class="title-border">Present Address</td>
            </tr>
            <tr>
                <td width=45% class="bold" valign="top">Present Address:</td>
                <td width=55%><?=$row['present_address']?></td>
            </tr>
            <tr>
                <td class="bold" valign="top">Zipcode:</td>
                <td width=55%><?=$row['present_address_zip']?></td>
            </tr>
            <tr>
                <td class="bold" valign="top">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>  
  
    </td>
    <td valign="top">
    
        <table width=100%>
            <tr>
                <td colspan=2 class="title-border">Permanent Address</td>
            </tr>
            <tr>
                <td width=45% class="bold" valign="top">Permanet Address:</td>
                <td width=55%><?=$row['permanent_address']?></td>
            </tr>
            <tr>
                <td class="bold" valign="top">Zipcode:</td>
                <td><?=$row['permanent_address_zip']?></td>
            </tr>
            <tr>
              <td class="bold" valign="top">&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
        </table>  
  
    </td>
  </tr>
  <tr>
  	<td valign="top">
        <table width=100% border=0>
            <tr>
                <td colspan=2 class="title-border">Person to notify in case of emergency</td>
            </tr>
            <tr>
                <td width=45% class="bold" valign="top">Name:</td>
               	<td><?=$row['ice_fullname']?></td>
          	</tr>
            <tr>
                <td class="bold" valign="top">Address:</td>
              	<td><?=$row['ice_address']?></td>
            </tr>
            <tr>
                <td class="bold" valign="top">Contact No.:</td>
              	<td><?=$row['ice_tel_number']?></td>
            </tr>
            <tr>
                <td class="bold" valign="top">&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>  
  
    </td>
  </tr>
</table>
</div>
<?php
}
?>
</div>
</div>
</div>
</div>