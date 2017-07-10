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

if(USER_IS_LOGGED != '1')
{
	echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main
}


$page_title = 'Manage Import';
$pagination = 'Utility  > Manage Import';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$num	= $_REQUEST['num'];
$sub	= $_REQUEST['sub'];
$upload	= $_REQUEST['upload'];

$field = array();
for($i=1;$i<=$num;$i++){
$field[]=$_REQUEST['field_'.$i];
}
if($sub=="subject"){
$wId=array_keys($field, "department_id");
$key=$wId[0]; 
$wId2=array_keys($field, "subject_code");
$key2=$wId2[0]; 
$wId3=array_keys($field, "subject_name");
$key3=$wId3[0]; 
$wId4=array_keys($field, "subject_type");
$key4=$wId4[0]; 
}

if($action == 'update')
{	
//print_r($field);
	
			$handle = fopen("$upload", "r");
	while (($data = fgetcsv($handle, 1000, ",")and($field)) !== FALSE)	{	
	
			$sql_4 = "SELECT id FROM tbl_department WHERE 
					department_code='".$data["".$key.""]."'";
			$result_4 = mysql_query($sql_4);
			$row  = @mysql_fetch_array($result_4);
			$did = $row["id"];
		
		   $sql = "INSERT into tbl_subject 
		   			(".$field["".$key2.""].",
					 ".$field["".$key3.""].",
					 ".$field["".$key.""].",
					 ".$field["".$key4.""].",
					 date_created,
					 created_by,				
					 date_modified,
					 modified_by ) 
		   		values
		   			('".$data["".$key2.""]."',
					 '".$data["".$key3.""]."',
					 '$did',
		   			'".$data["".$key4.""]."',
					".time().", 
					".USER_ID.", 
					".time().",
					".USER_ID.")";
	   		
	   	}
				
			if(mysql_query ($sql))
			{
				echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_import\';</script>';
		}
}


if($view == 'edit')
{
	$file = $_FILES['importfile']['name'];
	$sub = $_REQUEST['imports'];
			
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

// component block, will be included in the template page
$content_template = 'components/block/blk_com_import.php';
?>