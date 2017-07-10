<?php
$action = $_REQUEST['action'];

if($action == 'reg')
{
	if($_REQUEST['email']!='EMAIL')
	{
		$dom = $_REQUEST['other_email']!=""?$_REQUEST['other_email']:$_REQUEST['domain'];
		
		$sql = "INSERT INTO tbl_student
			(
			name,
			email,
			contact,
			highschool,
			year,
			course,
			date
			)
			VALUES
			(
			'".$_REQUEST['name']."',
			'".$_REQUEST['email'].'@'.$dom."',
			'".$_REQUEST['contact']."',
			'".$_REQUEST['highschool']."',
			'".$_REQUEST['year']."',
			'".$_REQUEST['course']."',
			'".time()."'
			
			)
		
		";
		
		if(mysql_query($sql))
		{
			echo '<script language="javascript">$(document).ready(function(){$("span").text("SUCCESSFULLY ADDED!").show().fadeOut(3000);});</script>';
		}
	}
	else
	{
		echo '<script language="javascript">$(document).ready(function(){$("span").text("INVALID EMAIL!").show().fadeOut(3000);});</script>';
	}
	
}

?>