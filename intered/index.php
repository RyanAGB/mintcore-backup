<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<head>
<title></title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="js/jquery_validate/jquery.metadata.js"></script>
<script type="text/javascript" src="js/jquery_validate/jquery.validate.js"></script>
<script type="text/javascript" src="js/jquery_validate/cmxforms.js"></script>

<script type="text/javascript" src="js/default.js"></script>

<script type="text/javascript">

$(document).ready(function(){
	
	$('#register').click(function(){
		
			$('#action').val('reg');
		
	});
	
	$('#back').click(function(){
		
		window.location.href="index.php"
		
	});
	
	$('#print').click(function(){
		
		var w=window.open();

			w.document.write($('#prnt').html());
			
			w.document.write('</table>');
			
			w.document.close();

			w.focus();

			w.print();

			return false;

		
	});
	
	
});

function txtbox(val)
{
	$('#'+val).val('');
}

function e_select(val)
{
	//alert(val);
	if(val=='others')
	{
		$('#other_email').show();
	}
	else
	{
		$('#other_email').hide();
	}
	
}


</script>

</head>
<body>
<div id="body" align="center">

<?php 
	include('config.php');
	include('action.php');

?>
<form id="form" name="form" method="post">

<?php
if(isset($_REQUEST['list']))
{
	?>
    
    
    <table>
      <tr>
        <td align="right"><img src="images/printer.png" id="print" /></td>
      </tr>
      <tr>
        <td>
     <div id="prnt">   
        <table cellpadding="10px" id="lst" border="1" style="border-width:0.5px;">
<tr>
<td colspan="6">STUDENT LISTS</td>

</tr>
	<tr>
		<td>NAME</td>
		<td>EMAIL</td>
		<td>CONTACT</td>
		<td>HIGHSCHOOL</td>
		<td>YEAR LEVEL</td>
        <td>COURSE</td>
	</tr>
    
    <?php
	$sql = "SELECT * FROM tbl_student";
	$query = mysql_query($sql);
	
	if(mysql_num_rows($query)>0)
	{
		
		
		while($row = mysql_fetch_array($query))
		{
			
			$sqlc = "SELECT * FROM tbl_course WHERE id=".$row['course'];
			$queryc = mysql_query($sqlc);
			$rowc = mysql_fetch_array($queryc);
	?>

    <tr>
		<td><?=$row['name']?></td>
		<td><?=$row['email']?></td>
		<td><?=$row['contact']?></td>
		<td><?=$row['highschool']?></td>
		<td><?=$row['year']==3?'3rd':'4th'?></td>
        <td><?=$rowc['course_name']?></td>
	</tr>
    
    <?php
		}
	}
	else
	{
		echo "<tr>
		<td colspan='6'>No Record Found.</td>
	</tr>";	
	}
	?>
    
    <tr>
    <td colspan="6" align="right">Total Records : <?=mysql_num_rows($query)?></td>
    </tr>
    </table></div>
        
        </td>
      </tr>
      <tr>
        <td align="right"><a href="index.php">
          <input type="button" name="back" id="back" class="button" value="BACK" />
        </a></td>
      </tr>
    </table>
    <?php
}
else
{
?>
<table cellpadding="10px">
<tr>
<td colspan="2"><span></span></td>

</tr>
<tr>
	<tr>
		<td colspan="2"><input type="text" name="name" id="name" class="txt" value="NAME" onclick="txtbox(name);" onblur="javascript: if(this.value==''){this.value='NAME';}" /></td>
	</tr>
    <tr>
		<td><input type="text" name="email" id="email" class="txt_sm2" value="EMAIL" onclick="txtbox(name);" onblur="javascript: if(this.value==''){this.value='EMAIL';}"  />&nbsp;<img src="images/at.png" />&nbsp;<select name="domain" id="domain" class="txts" onchange="e_select(this.value);">
		  <option value="yahoo.com">yahoo.com</option>
		  <option value="yahoo.com.ph">yahoo.com.ph</option>
          <option value="rocketmail.com">rocketmail.com</option>
        <option value="gmail.com">gmail.com</option>
        <option value="outlook.com">outlook.com</option>
        <option value="hotmail.com">hotmail.com</option>
        <option value="others">Others...</option>
        </select></td>
		<td>
        
        <input type="text" name="other_email" id="other_email" class="txt_sm" value="" style="display:none;" /></td>
	</tr>
    <tr>
		<td colspan="2"><input type="text" name="contact" id="contact" class="txt" value="CONTACT" onclick="txtbox(name);" onblur="javascript: if(this.value==''){this.value='CONTACT';}"/></td>
	</tr>
    <tr>
		<td colspan="2"><input type="text" name="highschool" id="highschool" class="txt" onclick="txtbox(name);" value="HIGHSCHOOL" onblur="javascript: if(this.value==''){this.value='HIGHSCHOOL';}" /></td>
	</tr>
    <tr>
		<td colspan="2"><select name="year" id="year" class="txts">
        <option value="" disabled="disabled" selected="selected">YEAR LEVEL</option>
        <option value="3">3rd Year</option>
        <option value="4">4th Year</option>
        </select>
        <!--<input type="text" name="year" id="year" value="YEAR" onclick="txtbox(name);" onblur="javascript: if(this.value==''){this.value='YEAR';}" />!--></td>
	</tr>
    <tr>
		<td colspan="2"><select name="course" id="course" class="txtc">
        
        <option value="" disabled="disabled" selected="selected">PREFFERED COURSE</option>
		  <?php
		  $sql = "SELECT * FROM tbl_course WHERE publish='Y'";
		  $query = mysql_query($sql);
		  $g ='';
		  while($row = mysql_fetch_array($query))
		  {
		  	if($g!=$row['course_code'])
			{
				if($row['course_code']=='T')
				{$n='SCHOOL OF TECHNOLOGY';}
				else if($row['course_code']=='V')
				{$n='SCHOOL OF DESIGN & VISUAL ARTS';}
				else if($row['course_code']=='B')
				{$n='SCHOOL OF BUSINESS';}
				else if($row['course_code']=='P')
				{$n='SCHOOL OF PERFORMING ARTS';}
				
				echo '<option value="" disabled="disabled">'.$n.'</option>';
			}
          echo '<option value="'.$row['id'].'">'.$row['course_name'].'</option>';
		  
		  $g = $row['course_code'];
		  
		  }
		  ?>
        </select></td>
	</tr>
    
    <tr>
    <td align="right"><input type="submit" name="register" id="register" class="button" value="SUBMIT" /><input type="hidden" name="action" id="action" value="<?=$action?>" />
    </td>
    <td>&nbsp;</td>
    </tr>
</table>
	
</form>

<div id="foot"><a href="?list"><img src="images/logo_foot.png" /></a></div>
<?php } ?>

</div>
	
</body>
</html>









