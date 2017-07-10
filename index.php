<?php

include_once('config.php');
include_once('includes/functions.php');
include_once('includes/common.php');

$comp = $_REQUEST['comp'];
$action = $_REQUEST['action'];
$view = $_REQUEST['view'];

//error_reporting( 0 );

if(USER_IS_LOGGED == '1'){
	if($comp != ''){ 
		if($_SESSION[CORE_U_CODE]['current_comp'] != $comp){
			$_SESSION[CORE_U_CODE]['pageNum'] = '1';
			$_SESSION[CORE_U_CODE]['sy_filter'] = CURRENT_TERM_ID;
			unset($_SESSION[CORE_U_CODE]['fieldName']);
			unset($_SESSION[CORE_U_CODE]['orderBy']);
		}
		
		if(file_exists('components/'.$comp.'.php')){ 
			include ('components/'.$comp.'.php');
			
		}else if(file_exists('components/com_verify.php')){
			include_once('components/com_dashboard.php');
		}else{ 
			include_once('components/com_dashboard.php');
		}
	}else{ 
		include_once('components/com_dashboard.php');
	} 			
	
	// Main Page Template
	require_once('template/default.php');		

}else if($comp == 'com_forgot_pass'){
	include_once('components/com_forgot_pass.php');	
	require_once('template/default_forgot_pass.php');

}else if($comp == 'com_verify_employee_account' ){
	include_once('components/com_verify_employee_account.php');	
	require_once('template/default_verify.php');

}else if($comp == 'com_verify_student_account' ){
	include_once('components/com_verify_student_account.php');	
	require_once('template/default_verify.php');

}else if($comp == 'com_verify_parent_account' ){
	include_once('components/com_verify_parent_account.php');	
	require_once('template/default_verify.php');

}else if($comp == 'com_verify_employee_email' ){
	include_once('components/com_verify_employee_email.php');	
	require_once('template/default_verify.php');

}else if($comp == 'com_verify_student_email' ){
	include_once('components/com_verify_student_email.php');	
	require_once('template/default_verify.php');

}else if($comp == 'com_verify_parent_email' ){
	include_once('components/com_verify_parent_email.php');	
	require_once('template/default_verify.php');

}else{
	include_once('components/com_login.php');
	require_once('template/default_login.php');
} 

	

?>