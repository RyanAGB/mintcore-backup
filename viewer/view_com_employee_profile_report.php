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
	});
</script>

<?php
	$id = $_REQUEST['id'];
	$sql = "SELECT * FROM tbl_employee WHERE id = ".$id;						
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	
	$sql_employee_photo = "SELECT * FROM tbl_employee_photo WHERE employee_id = $id";
	$query_employee_photo = mysql_query($sql_employee_photo);
	$row_employee_photo = mysql_fetch_array($query_employee_photo);
?>

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
<tr><td style='font-weight:bolder; font-size:12px;' colspan="2" align="right"><a target="_blank" href="ajax_components/print_emp_info.php?id=<?=$id?>" >PRINT THIS PAGE</a></td></tr>
</table>

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
    ?>    </td>
    <td width="300">
            
        <table width=100% >
            <tr>
                <td colspan=2 style="border-bottom:1px solid #333;  font-size:13px;  font-weight:bold;">Personal Information</td>
            </tr>
            <tr>
                <td width=45% style='font-weight:bold' valign="top">Employee Name:</td>
                <td width=55%><?=$row['lastname'].", " . $row['firstname'] ." " . $row['middlename']?></td>
            </tr>
            <!--
            <tr>
                <td style='font-weight:bold' valign="top">Year level:</td>
                <td><?=$row['year_level']?></td>
            </tr>
            -->
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
				?>                </td>
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
				?>                </td>
            </tr>
            <tr>
                <td style='font-weight:bold' valign="top">BirthDate:</td>
                <td>
				<?php
                $birthday = explode ('-',$row['birth_date']);		
				$birth_year = $birthday['0'];
				$birth_day = $birthday['1'];
				$birth_month = $birthday['2'];
				
				echo date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year));

				?>                </td>
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
        </table>  	</td>
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
        </table>    </td>
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
        </table>    </td>
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
        </table>    </td>
  </tr>
</table>
<?php
}
?>