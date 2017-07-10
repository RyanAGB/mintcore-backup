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







$page_title = 'Manage Employee Profile';

$pagination = 'Users > Manage Employee Profile';



$view = $view==''?'list':$view; // initialize action



$id	= $_REQUEST['id'];

$temp 	= $_REQUEST['temp'];



// EMPLOYEE //

$user_id				= $_REQUEST['user_id'];					

$emp_id_number         = $_REQUEST['emp_id_number'];    

$employee_type          = $_REQUEST['employee_type'];      

$department_id          = $_REQUEST['department_id'];   



$employee_id			= $_REQUEST['employee_id'];	                                      

$firstname              = $_REQUEST['firstname'];                             

$middlename             = $_REQUEST['middlename'];                             

$lastname               = $_REQUEST['lastname'];                          

$suffix                 = $_REQUEST['suffix'];                          

$email                  = $_REQUEST['email'];

$confirm_email			= $_REQUEST['confirm_email'];

$year             		= $_REQUEST['b_year'];

$month             		= $_REQUEST['b_month'];

$day             		= $_REQUEST['b_day'];

$bdate 					= array($year, $month, $day);

$birth_date 			= implode("-", $bdate);

$birth_place            = $_REQUEST['birth_place'];                           

$gender                 = $_REQUEST['gender'];                          

$citizenship            = $_REQUEST['citizenship'];                                       

$civil_status           = $_REQUEST['civil_status'];                                         

$religion               = $_REQUEST['religion'];                                          

$present_address        = $_REQUEST['present_address'];                                            

$present_address_zip    = $_REQUEST['present_address_zip'];                                           

$permanent_address      = $_REQUEST['permanent_address'];                                          

$permanent_address_zip  = $_REQUEST['permanent_address_zip'];                                       

$tel_number             = $_REQUEST['tel_number'];                                     

$mobile_number          = $_REQUEST['mobile_number'];                                           

$ice_fullname           = $_REQUEST['ice_fullname'];                                    

$ice_address            = $_REQUEST['ice_address'];                                       

$ice_tel_number			= $_REQUEST['ice_tel_number'];



// USER //



$username 				= $_REQUEST['username'];                                                                 

$password               = $_REQUEST['password'];                                                    

$access_id              = $_REQUEST['access_id'];                                                    

$blocked                = $_REQUEST['blocked'];                                     

$userN = explode('_',$emp_id_number);



// THIS IS FOR IMAGE //



$image_file             = $_REQUEST['image_file'];



if(isset($_FILES['image_file']['tmp_name']) && $_FILES['image_file']['tmp_name'] != '')

{

	$imgData 		= addslashes(file_get_contents($_FILES['image_file']['tmp_name']));

	$imageSize 		= getimagesize($_FILES['image_file']['tmp_name']);	

	$width 			= $imageSize[0];

	$height 		= $imageSize[1];

	$file_ext		= explode(".",$_FILES['image_file']['name']);

	$file_type		= strtolower($file_ext[1]);
	
	//creates the new image using the appropriate function from gd library
	if(!strcmp("jpg",$file_type) || !strcmp("jpeg",$file_type))
	$src_img=imagecreatefromjpeg($_FILES['image_file']['tmp_name']);
			
	if(!strcmp("png",$file_type))
	$src_img=imagecreatefrompng($_FILES['image_file']['tmp_name']);
				
	if(!strcmp("gif",$file_type))
	$src_img=imagecreatefromgif($_FILES['image_file']['tmp_name']);
			
	//gets the dimmensions of the image
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	
	// we create a new image with the new dimmensions
		$dst_img=ImageCreateTrueColor(120,120);
	// resize the big image to the new created one
		imagecopyresampled($dst_img,$src_img,0,0,0,0,120,120,$width,$height);
		
	ob_start(); // Start capturing stdout.
	imageJPEG($dst_img); // As though output to browser.
	$imgData = addslashes(ob_get_contents()); // the raw jpeg image data.
	ob_end_clean(); // Dump the stdout so it does not screw other output. 

}



$veri_code = generateRandomString();



//FOR IMPORT



$num	= $_REQUEST['num'];

$uploadfile	= $_REQUEST['uploadfile'];



$field = array();



//FOR SORTING AND PAGINATION



$filter_field = $_REQUEST['filter_field'];

$filter_order = $_REQUEST['filter_order'];

$page = $_REQUEST['page'];



	if($_SESSION[CORE_U_CODE]['current_comp'] != $comp || $_SESSION[CORE_U_CODE]['pageNum'] != $page || $_SESSION[CORE_U_CODE]['fieldName'] != $filter_field || $_SESSION[CORE_U_CODE]['orderBy'] != $filter_order)

	{

		if($page != '')

		{

			$_SESSION[CORE_U_CODE]['pageNum'] = isset($page)&&$page!='' ? $page : '1';

		}

		if($filter_field != '' || $filter_order != '')

		{

			$_SESSION[CORE_U_CODE]['fieldName'] = $filter_field;

			$_SESSION[CORE_U_CODE]['orderBy'] = $filter_order;

		}

		$_SESSION[CORE_U_CODE]['current_comp'] = $comp;

		

	}



if($action == 'save')

{

	if($emp_id_number == '' || $employee_type == '')

	{

		$err_msg = 'Some of the required fields are missing.';

	}

	else if($confirm_email == '' )

	{

		$err_msg = 'Please confirm email.';

	}

	else if($email != $confirm_email)

	{

		$err_msg = 'Email does not match.';

	}

	else if (checkEmpIDExist($emp_id_number, $id))

	{

		$err_msg ='Employee ID number already exist.';

	}

	else if (checkIfEmployeeEmailExist($email, $id))

	{

		$err_msg = 'Employee email address already exist.';

	}

	/*else if($height >= '121' && $height != '')

	{

		$err_msg = 'Error in Height. Maximum of 120 x 120 only is allowed';

	}

	else if($width >= '121' && $width != '')

	{

		$err_msg = 'Error in Width. Maximum of 120 x 120 only is allowed';

	}*/

	else

	{
		$gen_salt = generateSaltString();		
		
		$userNm = $lastname.'_'.$userN[1];
		$password = md5('mint'.$userN[1].$gen_salt);
			
		
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
					".GetSQLValueString(getAccessId($employee_type),"int")."
				)";
		/*$sql_user = "INSERT INTO tbl_user 

				(		

					username,

					verification_code,

					access_id                                                                 

				) 

				VALUES 

				(

					".GetSQLValueString($lastname.'_'.$userN[1],"text").",

					".GetSQLValueString($veri_code,"text").",

					".GetSQLValueString(getAccessId($employee_type),"text")."

				)";	*/

		if(mysql_query ($sql_user))

		{

			$user_id = mysql_insert_id();

			$sql = "INSERT INTO tbl_employee

			(	

				user_id,

				employee_type,                                                                  

				department_id,                                             

				emp_id_number, 	

				firstname,

				middlename,

				lastname,

				email,

				birth_date,

				birth_place,

				gender,

				citizenship,

				civil_status,                                                                                  

				religion,

				present_address,

				present_address_zip,

				permanent_address,

				permanent_address_zip,

				tel_number,

				mobile_number,

				ice_fullname,

				ice_address,

				ice_tel_number,

				date_created, 

				created_by,

				date_modified,

				modified_by	                                                                  

			) 

			VALUES 

			(

				".GetSQLValueString($user_id,"int").",

				".GetSQLValueString($employee_type,"int").",

				".GetSQLValueString($department_id,"int").",

				".GetSQLValueString($emp_id_number,"text").",

				".GetSQLValueString($firstname,"text").",

				".GetSQLValueString($middlename,"text").",

				".GetSQLValueString($lastname,"text").",

				".GetSQLValueString($email,"text").",

				".GetSQLValueString($birth_date,"text").",

				".GetSQLValueString($birth_place,"text").",

				".GetSQLValueString($gender,"text").",

				".GetSQLValueString($citizenship,"text").",

				".GetSQLValueString($civil_status,"text").",

				".GetSQLValueString($religion,"text").",

				".GetSQLValueString($present_address,"text").",

				".GetSQLValueString($permanent_address_zip,"text").",

				".GetSQLValueString($permanent_address,"text").",

				".GetSQLValueString($present_address_zip,"text").",

				".GetSQLValueString($tel_number,"text").",

				".GetSQLValueString($mobile_number,"text").",

				".GetSQLValueString($ice_fullname,"text").",

				".GetSQLValueString($ice_address,"text").",

				".GetSQLValueString($ice_tel_number,"text").",

				".time().",

				".USER_ID.", 

				".time().",

				".USER_ID."						

			)";

		

			if(mysql_query ($sql))

			{

				$employee_id = mysql_insert_id();

				

				if($imgData != '')

				{

					$sql_photo = "INSERT INTO tbl_employee_photo 

					(	

						employee_id,	

						image_type,

						image_file                                                                 

					) 

					VALUES 

					(

						".GetSQLValueString($employee_id,"text").",

						".GetSQLValueString($file_type,"text").",

						'".$imgData."'

					)";

					

					if(mysql_query ($sql_photo))

					{

						

						echo '<script language="javascript">window.location =\'index.php?comp=com_employee_profile\';</script>';

					

						$sql_email = "SELECT * FROM tbl_employee WHERE id =" .$employee_id;

						$qry_email = mysql_query($sql_email);

						$row_email = mysql_fetch_array($qry_email);

						

						$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .$row_email['user_id'];

						$qry_ver_code = mysql_query($sql_ver_code);

						$row_ver_code = mysql_fetch_array($qry_ver_code);

						

						$contents = "";

						$from_header = "From:" .SCHOOL_SYS_EMAIL;

						

						$contents .= "SIS Application Details\n";

						$contents .= "========================================================\n\n";					

						$contents .= SCHOOL_SYS_URL."/index.php?comp=com_verify_employee_account&verifycode=".$row_ver_code['verification_code']. "\n";		

						$contents .= "\n--------------------------------------------------------\n\n\n";	

						

						$subject= "SIS Application (for verification purpose)";

						

						$to = $row_email['email'];

						if(@mail($to, $subject, $contents, $from_header))

						{		

							echo '<script language="javascript">alert("Employee Successfully Added! Please check the email for verication code.");window.location =\'index.php?comp=com_employee_profile\';</script>';

						}

						else

						{

							$err_msg = 'Some problem occured while sending your message please try again.';

						}

					

					}

				}

				else

				{

					$sql_email = "SELECT * FROM tbl_employee WHERE id =" .$employee_id;

					$qry_email = mysql_query($sql_email);

					$row_email = mysql_fetch_array($qry_email);

					

					$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .$row_email['user_id'];

					$qry_ver_code = mysql_query($sql_ver_code);

					$row_ver_code = mysql_fetch_array($qry_ver_code);

					

					$contents = "";

					$from_header = "From:" .SCHOOL_SYS_EMAIL;

					

					$contents .= "SIS Application Details\n";

					$contents .= "========================================================\n\n";					

					$contents .= SCHOOL_SYS_URL."/index.php?comp=com_verify_employee_account&verifycode=".$row_ver_code['verification_code']. "\n";		

					$contents .= "\n--------------------------------------------------------\n\n\n";	

					

					$subject= "SIS Application (for verification purpose)";

					

					$to = $row_email['email'];

				

					if(@mail($to, $subject, $contents, $from_header))

					{		

						echo '<script language="javascript">alert("Employee Successfully Added!");window.location =\'index.php?comp=com_employee_profile\';</script>';

					}

					else

					{

						$err_msg = 'Some problem occured while sending your message please try again.';

					}

					

					echo '<script language="javascript">window.location =\'index.php?comp=com_employee_profile\';</script>';

				}

								

			}

		}

	}	

}

else if($action == 'update')

{

	if($emp_id_number == '' || $employee_type == '')

	{

		$err_msg = 'Some of the required fields are missing.';

	}

	else if($confirm_email == '' )

	{

		$err_msg = 'Please confirm email.';

	}

	else if($email != $confirm_email)

	{

		$err_msg = 'Email does not match.';

	}

	else if (checkEmpIDExist($emp_id_number, $id))

	{

		$err_msg ='Employee ID number already exist.';

	}

	else if (checkIfEmployeeEmailExist($email, $id))

	{

		$err_msg = 'Employee email address already exist.';

	}

	/*else if($height >= '121' && $height != '')

	{

		$err_msg = 'Error in Height. Maximum of 120 x 120 only is allowed';

	}

	else if($width >= '121' && $width != '')

	{

		$err_msg = 'Error in Width. Maximum of 120 x 120 only is allowed';

	}*/

	else

	{

		if(storedModifiedLogs('tbl_employee', $id))

		{

			

			 $sql_employee_info = "UPDATE tbl_employee SET

				employee_type =".GetSQLValueString(getAccessId($employee_type),"int").",

				department_id  =".GetSQLValueString($department_id,"int").",				

				emp_id_number =".GetSQLValueString($emp_id_number,"text").",

				firstname =".GetSQLValueString($firstname,"text").",

				middlename =".GetSQLValueString($middlename,"text").",

				lastname =".GetSQLValueString($lastname,"text").",

				email =".GetSQLValueString($email,"text").",

				birth_date =".GetSQLValueString($birth_date,"text").",

				birth_place =".GetSQLValueString($birth_place,"text").",

				gender =".GetSQLValueString($gender,"text").",

				citizenship =".GetSQLValueString($citizenship,"text").",

				civil_status =".GetSQLValueString($civil_status,"text").",

				religion =".GetSQLValueString($religion,"text").",

				present_address =".GetSQLValueString($present_address,"text").",

				present_address_zip =".GetSQLValueString($present_address_zip,"text").",

				permanent_address =".GetSQLValueString($permanent_address,"text").",

				permanent_address_zip =".GetSQLValueString($permanent_address_zip,"text").",

				tel_number =".GetSQLValueString($tel_number,"text").",

				mobile_number =".GetSQLValueString($mobile_number,"text").",

				ice_fullname =".GetSQLValueString($ice_fullname,"text").",

				ice_address =".GetSQLValueString($ice_address,"text").",

				ice_tel_number =".GetSQLValueString($ice_tel_number,"text").",

				date_modified = ".time() .",

				modified_by = ".USER_ID." 						

				WHERE id =" .$id;

			

			

			if(mysql_query ($sql_employee_info))

			{
				$sqlu = "SELECT * FROM tbl_employee WHERE id=".$id;
				$queryu = mysql_query($sqlu);
				$rowu = mysql_fetch_array($queryu);
				
				$gen_salt = generateSaltString();		
		
				$userNm = $lastname.'_'.$userN[1];
				$password = md5('mint'.$userN[1].$gen_salt);
				
				$sql_username = "UPDATE tbl_user SET

								username =".GetSQLValueString($userNm,"text").",
								
								password =".GetSQLValueString($password,"text").",	
								
								blocked =0,
								
								salt =".GetSQLValueString($gen_salt,"text").",				

								access_id =".GetSQLValueString(getAccessId($employee_type),"int")."

								WHERE id =" .$rowu['user_id'];

					

				if(mysql_query ($sql_username))

				{			

					if($_FILES['image_file']['tmp_name'] !='')

					{

						$sql_photo ="DELETE FROM tbl_employee_photo WHERE employee_id =" .$id ;

						$qry_photo = mysql_query($sql_photo);

						

						$sql_insert = "INSERT INTO tbl_employee_photo 

						(	

							employee_id,	

							image_type,

							image_file                                                                 

						) 

						VALUES 

						(

							".GetSQLValueString($id,"text").",

							".GetSQLValueString($file_type,"text").",

							'".$imgData."'

						)";

						

						if(mysql_query ($sql_insert))

						{

							echo '<script language="javascript">alert("Successfully updated the employee profile");window.location =\'index.php?comp=com_employee_profile\';</script>';

						}	

					}

					else

					{

						echo '<script language="javascript">alert("Successfully updated the employee profile");window.location =\'index.php?comp=com_employee_profile\';</script>';

					}

				}

			}



		}

	}

}

else if($action == 'import_fin')

{

		for($i=1;$i<=$num;$i++){

			$field[]=$_REQUEST['field_'.$i];

		}

		//print_r($field);

		$wId=array_keys($field, "employee_type");

		$key=$wId[0]; 

		$wId2=array_keys($field, "department_id");

		$key2=$wId2[0]; 

		$wId3=array_keys($field, "lastname");

		$key3=$wId3[0]; 

		$wId4=array_keys($field, "firstname");

		$key4=$wId4[0];

		$wId5=array_keys($field, "middlename");

		$key5=$wId5[0];

		$wId6=array_keys($field, "emp_id_number");

		$key6=$wId6[0];

		$wId7=array_keys($field, "birth_date");

		$key7=$wId7[0];

		$wId8=array_keys($field, "email");

		$key8=$wId8[0];

		

		$uniques = array_unique($field);

		$dups = array_diff_assoc($field, $uniques);

		$null = array_values($field);



		if (in_array("", $null)) {		

		

			$err_msg = "Import Unsuccessful Empty Fields has been set! ";

			

		}else if(count($dups) >= 1){

			

			$err_msg = "Import Unsuccessful Fields not set properly! ";

			

		}else{

			

		$depArr 	= array();

		$numArr 	= array();

		$emArr 		= array();

		$emTyp 		= array();

		$nullArr 	= array();

		$valEm 		= array();

		$ctr 		= 0;

		

		$handle = fopen($uploadfile, 'r');

     while (($data = fgetcsv($handle, 1000, ",")and($field)) !== FALSE)



     {

	 	//print_r($data);

		$blnk = array_values($data);

		

		if(checkEmpIDExist($data["".$key6.""])){

			$numArr[] = $data["".$key6.""];

		}

		if(!checkIfDeptCodeExist($data["".$key2.""])){

			$depArr[] = $data["".$key2.""];

		}

		if(checkIfEmployeeEmailExist($data["".$key8.""])){

			$emArr[] = $data["".$key8.""];

		}

		if(!checkEmployeeTypeByTitleExist($data["".$key.""])){

			$emTyp[] = $data["".$key.""];

		}

		if(!isValidEmail($data["".$key8.""])){

			$valEm[] = $ctr++;

		}

		if(in_array("", $blnk)){

			$nullArr[] = $ctr++;

		}

		

	}

		fclose($handle);

	}

	/*echo '<pre>';

			print_r($studArr);

			print_r($corArr);

			print_r($emTyp);

			print_r($nullArr);

			echo '</pre>';*/

	if((count($numArr) >= 1 )or(count($depArr) >= 1 )

				or (count($emArr) >=1) or (count($nullArr) >=1)or (count($emTyp) >=1) or (count($valEm) >=1)){

			$err_msg = "Import Interrupted!";

			if(count($numArr) >= 1 ){

			$err_msg ="Employee Number Already Exist";

			}

			if(count($depArr) >= 1) {

			$err_msg ="Department Code Do Not Exist";

			}

			if(count($emArr) >= 1) {

			$err_msg ="Email Address Already Exist";

			}

			if(count($nullArr) >=1){

			$err_msg = "Invalid Empty CSV Data";

			}

			if(count($emTyp) >=1){

			$err_msg = "Employee Type Do not Exist";

			}

			if(count($valEm) >=1){

			$err_msg = "Invalid Email Address was found.";

			}

		}else{

			

		$handle2 = fopen($uploadfile, 'r');

		while (($data = fgetcsv($handle2, 1000, ",")and($field)) !== FALSE)	{	

	

			$sqlty = "SELECT * FROM tbl_employee_type 

					WHERE type_title = '".$data["".$key.""]."'";

			$queryty = mysql_query($sqlty);

			$rowty  = mysql_fetch_array($queryty);

			$typ_id = $rowty['id'];

			

			$sql_4 = "SELECT id FROM tbl_department WHERE 

					department_code='".$data["".$key2.""]."'";

			$result_4 = mysql_query($sql_4);

		

			$row  = mysql_fetch_array($result_4);

			$did = $row["id"];

	

			$date=$data["".$key7.""];

	  		$bdate= preg_replace( "|\b(\d+)/(\d+)/(\d+)\b|", "\\3-\\1-\\2", $date );

			

			$sql_user = "INSERT INTO tbl_user 

				(		

					username,

					verification_code,

					access_id                                                                 

				) 

				VALUES 

				(

					'".$data["".$key6.""]."',

					".GetSQLValueString($veri_code,"text").",

					".GetSQLValueString(getAccessId($typ_id),"int")."

				)";

		

		

			

		if(mysql_query ($sql_user))

		{

			$user_id = mysql_insert_id();

			

		  $sql = "INSERT into tbl_employee 

		   			( user_id,

					".$field["".$key2.""].",

					 ".$field["".$key3.""].",

					 ".$field["".$key.""].",

					 ".$field["".$key4.""].",

					 ".$field["".$key5.""].",

					 ".$field["".$key6.""].",

					 ".$field["".$key7.""].",

					 ".$field["".$key8.""].",

					 date_created,

					 created_by,				

					 date_modified,

					 modified_by ) 

		   		values

		   			(".GetSQLValueString($user_id,"int").",

					'".$did."',

					 '".$data["".$key3.""]."',

					 '".$typ_id."',

		   			'".$data["".$key4.""]."',

					'".$data["".$key5.""]."',

					'".$data["".$key6.""]."',

					'".$bdate."',

					'".$data["".$key8.""]."',

					".time().", 

					".USER_ID.", 

					".time().",

					".USER_ID.")";

	   		

			if(mysql_query ($sql))

					{

					

					fclose($handle2);

			

					$employee_id = mysql_insert_id();

					

					$sql_email = "SELECT * FROM tbl_employee WHERE id =" .$employee_id;

						$qry_email = mysql_query($sql_email);

						$row_email = mysql_fetch_array($qry_email);

						

						$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .$row_email['user_id'];

						$qry_ver_code = mysql_query($sql_ver_code);

						$row_ver_code = mysql_fetch_array($qry_ver_code);

						

						$contents = "";

						$from_header = "From:" .SCHOOL_SYS_EMAIL;

						

						$contents .= "SIS Application Details\n";

						$contents .= "========================================================\n\n";					

						$contents .= SCHOOL_SYS_URL."/index.php?comp=com_verify_employee_account&verifycode=".$row_ver_code['verification_code']. "\n";		

						$contents .= "\n--------------------------------------------------------\n\n\n";	

						

						$subject= "SIS Application (for verification purpose)";

						

						$to = $row_email['email'];

						if(@mail($to, $subject, $contents, $from_header))

						{		

							echo '<script language="javascript">alert("Employee Successfully Added! Please check the email for verication code.");window.location =\'index.php?comp=com_employee_profile\';</script>';

						}

						else

						{

							$err_msg = 'Some problem occured while sending your message please try again.';

						}

					

					}

				}

				else

				{

					$sql_email = "SELECT * FROM tbl_employee WHERE id =" .$employee_id;

					$qry_email = mysql_query($sql_email);

					$row_email = mysql_fetch_array($qry_email);

					

					$sql_ver_code = "SELECT * FROM tbl_user WHERE id =" .$row_email['user_id'];

					$qry_ver_code = mysql_query($sql_ver_code);

					$row_ver_code = mysql_fetch_array($qry_ver_code);

					

					$contents = "";

					$from_header = "From:" .SCHOOL_SYS_EMAIL;

					

					$contents .= "SIS Application Details\n";

					$contents .= "========================================================\n\n";					

					$contents .= "http://localhost/core_v2/index.php?comp=com_verify_employee_account&verifycode=".$row_ver_code['verification_code']. "\n";		

					$contents .= "\n--------------------------------------------------------\n\n\n";	

					

					$subject= "SIS Application (for verification purpose)";

					

					$to = $row_email['email'];

				

					if(@mail($to, $subject, $contents, $from_header))

					{		

						echo '<script language="javascript">alert("Employee Successfully Added!");window.location =\'index.php?comp=com_employee_profile\';</script>';

					}

					else

					{

						$err_msg = 'Some problem occured while sending your message please try again.';

					}

					

					echo '<script language="javascript">window.location =\'index.php?comp=com_employee_profile\';</script>';

								

			}

		}

	}

}

				

else if($action == 'delete')

{

	$selected_item = explode(',',$temp);

	

	foreach($selected_item as $item)

	{

		if(checkEmployeeHasRecord($item))

		{

			$err_msg = 'Cannot delete ' . getEmployeeFullName($item). '. Currently record is used.';

		}

		else

		{

			if ($item != '')

			{

				$sql_user_id ="SELECT * FROM tbl_employee WHERE id =" .$item;

				$qry_user_id = mysql_query($sql_user_id);

				$row_user_id = mysql_fetch_array($qry_user_id);

				

				$sql_user = "DELETE FROM tbl_user WHERE id=" .$row_user_id['user_id'];

				$sql_employee = "DELETE FROM tbl_employee WHERE id=" .$item;

				$sql_photo = "DELETE FROM tbl_employee_photo WHERE employee_id=" .$item;

				

				mysql_query ($sql_user);

				mysql_query ($sql_employee);

				mysql_query ($sql_photo);

			}

		}

	}



	if(count($arr_str) > 0)

	{

		echo '<script language="javascript">alert("'.implode("\n",$arr_str).'");</script>';

	}



}

else if($action == 'publish')

{

	$selected_item = explode(',',$temp);

	

	foreach($selected_item as $item)

	{

		storedModifiedLogs(tbl_student, $id);

		$sql = "UPDATE tbl_student SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;

		mysql_query ($sql);

	}

}

else if($action == 'unpublished')

{

	$selected_item = explode(',',$temp);

	

	foreach($selected_item as $item)

	{

		storedModifiedLogs(tbl_student, $id);

		$sql = "UPDATE tbl_student SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;

		mysql_query ($sql);

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





if($view == 'edit')

{

	$sql = "SELECT * FROM tbl_employee WHERE id = " .$id;

						

	$query = mysql_query ($sql);

	$row = mysql_fetch_array($query);

	

	// EMPLOYEE //

						

	$employee_type        	= $row['employee_type'] != $employee_type ? $row['employee_type'] : $employee_type;

	$department_id          = $row['department_id'] != $department_id ? $row['department_id'] : $department_id;

	$emp_id_number          = $row['emp_id_number'] != $emp_id_number ? $row['emp_id_number'] : $emp_id_number;

	$date_hired             = $row['date_hired'] != $date_hired ? $row['date_hired'] : $date_hired;

	$date_resigned  		= $row['date_resigned'] != $date_resigned ? $row['date_resigned'] : $date_resigned;

											  

	$firstname              = $row['firstname'] != $firstname ? $row['firstname'] : $firstname;

	$middlename             = $row['middlename'] != $middlename ? $row['middlename'] : $middlename;

	$lastname               = $row['lastname'] != $lastname ? $row['lastname'] : $lastname;

	$suffix                 = $row['suffix'] != $suffix ? $row['suffix'] : $suffix;

	$email                  = $row['email'] != $email ? $row['email'] : $email;

	$confirm_email          = $row['confirm_email'] != $confirm_email ? $row['confirm_email'] : $confirm_email;

	

	

	$b_year					= $row['b_year'] != $b_year ? $row['b_year'] : $b_year;

	$b_month				= $row['b_month'] != $b_month ? $row['b_month'] : $b_month;

	$b_day					= $row['b_day'] != $b_day ? $row['b_day'] : $b_day;

	

	$year             		= $row['year'] != $year ? $row['year'] : $year;

	$month             		= $row['month'] != $month ? $row['month'] : $month;

	$day             		= $row['day'] != $day ? $row['day'] : $day;

	$bdate 					= $row['bdate'] != $bdate ? $row['bdate'] : $bdate;

	

	$birth_date 			= $row['birth_date'] != $birth_date ? $row['birth_date'] : $birth_date;

	

	

	$birth = explode("-", $birth_date);

	$b_year = $birth[0]; 

	$b_month = $birth[1]; 

	$b_day = $birth[2]; 

	

	

	$birth_place            = $row['birth_place'] != $birth_place ? $row['birth_place'] : $birth_place;

	$gender                 = $row['gender'] != $gender ? $row['gender'] : $gender;

	$citizenship            = $row['citizenship'] != $citizenship ? $row['citizenship'] : $citizenship;

	$civil_status           = $row['civil_status'] != $civil_status ? $row['civil_status'] : $civil_status;

	$religion               = $row['religion'] != $religion ? $row['religion'] : $religion;

	$present_address        = $row['present_address'] != $present_address ? $row['present_address'] : $present_address;

	$present_address_zip    = $row['present_address_zip'] != $present_address_zip ? $row['present_address_zip'] : $present_address_zip;

	$permanent_address      = $row['permanent_address'] != $permanent_address ? $row['permanent_address'] : $permanent_address;

	$permanent_address_zip  = $row['permanent_address_zip'] != $permanent_address_zip ? $row['permanent_address_zip'] : $permanent_address_zip;

	$tel_number             = $row['tel_number'] != $tel_number ? $row['tel_number'] : $tel_number;

	$mobile_number          = $row['mobile_number'] != $mobile_number ? $row['mobile_number'] : $mobile_number;

	$ice_fullname           = $row['ice_fullname'] != $ice_fullname ? $row['ice_fullname'] : $ice_fullname;

	$ice_address            = $row['ice_address'] != $ice_address ? $row['ice_address'] : $ice_address;

	$ice_tel_number			= $row['ice_tel_number'] != $ice_tel_number ? $row['ice_tel_number'] : $ice_tel_number;

	

	// USER //

	

	$username 				= $row['username'] != $username ? $row['username'] : $username;

	$password               = $row['password'] != $password ? $row['password'] : $password;

	$access_id              = $row['access_id'] != $access_id ? $row['access_id'] : $access_id;

	$blocked                = $row['blocked'] != $blocked ? $row['blocked'] : $blocked;  



	// STUDENT PHOTO//

	

	$sql_photo = "SELECT * FROM tbl_employee_photo WHERE employee_id =" .$id;

	$query_photo = mysql_query ($sql_photo);

	$row_photo = mysql_fetch_array($query_photo);

	

	

	$image_type              = $row_photo['image_type'] != $image_type ? $row_photo['image_type'] : $image_type;

	$image_file              = $row_photo['image_file'] != $image_file ? $row_photo['image_file'] : $image_file;



}

else if($view == 'add')

{

	

// EMPLOYEE //

$user_id				= $_REQUEST['user_id'];					

$emp_id_number         = $_REQUEST['emp_id_number'];    

$employee_type          = $_REQUEST['employee_type'];      

$department_id          = $_REQUEST['department_id'];                                                              

                   

$firstname              = $_REQUEST['firstname'];                             

$middlename             = $_REQUEST['middlename'];                             

$lastname               = $_REQUEST['lastname'];                          

$suffix                 = $_REQUEST['suffix'];                          

$email                  = $_REQUEST['email'];

$confirm_email          = $_REQUEST['confirm_email'];

$year             		= $_REQUEST['b_year'];

$month             		= $_REQUEST['b_month'];

$day             		= $_REQUEST['b_day'];

$bdate 					= array($year, $month, $day);

$birth_date 			= implode("-", $bdate);

$birth_place            = $_REQUEST['birth_place'];                           

$gender                 = $_REQUEST['gender'];                          

$citizenship            = $_REQUEST['citizenship'];                                       

$civil_status           = $_REQUEST['civil_status'];                                         

$religion               = $_REQUEST['religion'];                                          

$present_address        = $_REQUEST['present_address'];                                            

$present_address_zip    = $_REQUEST['present_address_zip'];                                           

$permanent_address      = $_REQUEST['permanent_address'];                                          

$permanent_address_zip  = $_REQUEST['permanent_address_zip'];                                       

$tel_number             = $_REQUEST['tel_number'];                                     

$mobile_number          = $_REQUEST['mobile_number'];                                           

$ice_fullname           = $_REQUEST['ice_fullname'];                                    

$ice_address            = $_REQUEST['ice_address'];                                       

$ice_tel_number			= $_REQUEST['ice_tel_number'];



// USER //



$username 				= $_REQUEST['username'];                                                                 

$password               = $_REQUEST['password'];                                                    

$access_id              = $_REQUEST['access_id'];                                                    

$blocked                = $_REQUEST['blocked'];                         



}



if($view == 'import'){



}



// component block, will be included in the template page

$content_template = 'components/block/blk_com_employee_profile.php';

?>