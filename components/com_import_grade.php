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
if(!isset($_REQUEST['comp']))
{
include_once("../config.php");
include_once("../includes/functions.php");
include_once("../includes/common.php");
}


if(USER_IS_LOGGED != '1')
{
	echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main
}
else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}


$page_title = 'Manage Import Grade';
$pagination = 'Users  > Manage Import Grade';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$num	= $_REQUEST['num'];
$uploadfile	= $_REQUEST['uploadfile'];

$field = array();


if($action == 'import_fin')
{
		for($ctr=1;$ctr<=$num;$ctr++){
			$field[]=$_REQUEST['field_'.$ctr];
		}

		$wId=array_keys($field, "student_id");
		$key=$wId[0]; 
		$wId2=array_keys($field, "subject_id");
		$key2=$wId2[0]; 
		$wId3=array_keys($field, "final_grade");
		$key3=$wId3[0]; 
		
		$uniques = array_unique($field);
		$dups = array_diff_assoc($field, $uniques);
		$null = array_values($field);

		if (in_array("", $null)) {		
		
			$err_msg = "Import Unsuccessful Empty Fields has been set! ";
			
		}else if(count($dups) >= 1){
			
			$err_msg = "Import Unsuccessful Fields not set properly! ";
			
		}
		else
		{	
		$studArr=array();
		$subArr=array();
		$nullArr=array();
		$ctr = 0;
		
		$handle = fopen($uploadfile, 'r');
     while (($data = fgetcsv($handle, 1000, ",")and($field)) !== FALSE)

     {
	 	//print_r($data);
		$blnk = array_values($data);
		
		if(!checkStudentIDExist($data["".$key.""])){
			$studArr[] = $ctr++;
		}	
		if(!checkIfSubjectCodeExist($data["".$key2.""])){
			$subArr[] = $ctr++;
		}	
		if(in_array("", $blnk)){
			$nullArr[] = $ctr++;
		}
		
	}
	}
		fclose($handle);
		
			/*echo '<pre>';
			print_r($studArr);
			print_r($subArr);
			print_r($nullArr);
			echo '</pre>';*/
			
	if((count($studArr) >= 1 )or(count($subArr) >= 1 )or (count($nullArr) >=1)){
				
			$err_msg = "Import Interrupted!";
			if(count($studArr) >= 1 ){
			$err_msg ="Student Number Do Not Exist";
			}
			if(count($subArr) >= 1) {
			$err_msg ="Subject Do Not Exist";
			}
			if(count($nullArr) >=1){
			$err_msg = "Invalid Empty CSV Data";
			}
		}
		else
		{	
		$handle2 = fopen($uploadfile, 'r');
		while (($data = fgetcsv($handle2, 1000, ",")and($field)) !== FALSE)	
		{	
	
			$sqlstud = "SELECT id FROM tbl_student WHERE 
					student_number = '".$data["".$key.""]."'";
			$resultstud = mysql_query($sqlstud);
			$row  = mysql_fetch_array($resultstud);
			$studentId = $row["id"];
			
			if($row['admission_type']=='T')
			{
				$subjType = 'C';
			}else{
				$subjType = 'S';
			}
			
			$sqlsub = "SELECT id FROM tbl_subject WHERE 
					subject_code = '".$data["".$key2.""]."'";
			$resultsub = mysql_query($sqlsub);
			$rowsub  = mysql_fetch_array($resultsub);
			$subjectId = $rowsub["id"];
			
			
			$sql_grade = "INSERT INTO tbl_student_final_grade 
				(		
					".$field["".$key.""].",
					 ".$field["".$key2.""].",
					 ".$field["".$key3.""].",
                     type,
					 term_id,
					 remarks,
					 date_created,
					 created_by,
					 date_modified,
					 modified_by                                         
				) 
				VALUES 
				(
					'".$studentId."',
					'".$subjectId."',
					'".encrypt($data["".$key3.""])."',
					'".$subjType."',
					'".CURRENT_TERM_ID."',
					'".'P'."',
					'".time()."',
					'".USER_ID."',
					'".time()."',
					'".USER_ID."'
				)";
				
		if(mysql_query($sql_grade))
					{		
						echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_import_grade\';</script>';
					}
				}
		}
	}	

else if($action == 'uplod')
{
	$file = $_FILES['importfile']['name'];
	$sub = $_REQUEST['imports'];
	$type = explode(".", $file);
	
	if($file == '')
	{
		$err_msg = 'Select a CSV file';
	}
	else if($type[1] != 'csv')
	{
		$err_msg = 'Invalid File';
		$forbid = 1;
	}
	else
	{		
	 if (is_uploaded_file($_FILES['importfile']['tmp_name'])) {
	   
	   }
	
	$uploaddir = 'uploads/';
	$uploadfile = $uploaddir . basename($_FILES['importfile']['name']);
	
	if (move_uploaded_file($_FILES['importfile']['tmp_name'], $uploadfile)) {
		
	} 

     $handle = fopen("$uploadfile", "r");

     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)

     {
		$num = count($data);
     }

     fclose($handle);
	 }

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_import_grade.php';
?>