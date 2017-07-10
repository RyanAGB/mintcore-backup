<?php
$db_server		='localhost';

	$db_user		='root';

	$db_password		='M3ridi@n';

	$db_name		='mint';
	
	set_time_limit(60000);
	
/*	$db_server		='localhost';

	$db_user		='root';

	$db_password		='';

	$db_name		='mist';*/


	$root_path = '';


	$conn = mysql_connect ($db_server,$db_user,$db_password) or die('Problem in connecting to server');
	
	if($conn)

	{

		$con_db = mysql_select_db($db_name ,$conn) or die('Access denied or can\'t find database');

	}
	
include_once("includes/functions.php");	
include_once("includes/common.php");

if(isset($_REQUEST['import']))
{
	
	if (is_uploaded_file($_FILES['importfile']['tmp_name'])) {
	   
	   }
	
	$uploaddir = 'uploads/';
	$uploadfile = $uploaddir . basename($_FILES['importfile']['name']);
	
	if (move_uploaded_file($_FILES['importfile']['tmp_name'], $uploadfile)) {
		
	} 
	
			$handle2 = fopen($uploadfile, 'r');
			
			while (($data = fgetcsv($handle2, 1000, ",")))	
			{
				$student_number = generateStudentNumber($data[10],1);	
				
				$gen_salt = generateSaltString();
				$userN = explode('-',$student_number);
				
				for($c = 1;$c<=count($userN);$c++)
				{
					if($c==count($userN)){
						$userNm = $data[3].'_'.$userN[$c-1];
						$password = md5('mint'.$userN[$c-1].$gen_salt);
					}
				}
				
				$sql_user = "INSERT INTO tbl_user 
				(		
					username,
					blocked,
					password,
					salt,
					access_id                                                                 
				) 
				VALUES 
				(
					".GetSQLValueString($userNm,"text").",
					".GetSQLValueString('0',"int").",
					".GetSQLValueString($password,"text").",
					".GetSQLValueString($gen_salt,"text").",
					".GetSQLValueString('6',"int")."
				)";
		
			
		if(mysql_query ($sql_user))
		{
			$user_id = mysql_insert_id();
			
			echo $sql_student_info = "INSERT INTO tbl_student 
			(	
				user_id,
				student_number,                             
				course_id,
				curriculum_id,                                    
				admission_type,
				start_year_level,
				year_level,
				term_id,
				firstname,
				middlename,
				lastname,
				email,
				birth_date,
				gender,                                   
				home_address,
				tel_number,
				mobile_number,
				high_school,
				scholarship,
				scholarship_type,
				date_created						                                                                  
			) 
			VALUES 
			(
				'".$user_id."',
				'".$student_number."',
				'".$data[10]."',
				'".getCurriculumByCourseId($data[10])."',
				'".$data[0]."',
				'1',
				'1',
				'15',
				'".$data[1]."',
				'".$data[2]."',
				'".$data[3]."',
				'".$data[13]."',
				'".$data[4]."',
				'".$data[5]."',
				'".$data[8]."',
				'".$data[12]."',
				'".$data[13]."',
				'".$data[6]."',
				'".$data[14]."',
				'".$data[15]."',
				'".time()."'	
				)";
				

		if(mysql_query($sql_student_info))
		{
				echo 'FIN';
		}else{
			echo 'Failed';
		}
		}
			}
			
			
}
			
?>

 <form name="imported" id="imported"  method="post" action="#" enctype="multipart/form-data">
 <input name="importfile" type="file" id="importfile" />
<p><input name="import" type="submit" value="IMPORT" id="import"></p>
</form>
