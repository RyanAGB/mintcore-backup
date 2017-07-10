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




/*
echo '<pre>';

print_r($_SESSION);

echo '</pre>';
*/




putenv("TZ=Asia/Manila"); 



	$sql = "SELECT * FROM tbl_system_settings";

	$query = mysql_query($sql);

	$row = mysql_fetch_array($query);	

error_reporting( 0 );

	if(!isset($_SESSION[CORE_U_CODE]['school_settings']) && !isset($_SESSION[CORE_U_CODE]['system_settings'])  )

	{

		setSystemSessionData();

	}

	else if($row['set_system'] != $_SESSION[CORE_U_CODE]['system_settings']['set_system'] && $_SESSION[CORE_U_CODE]['user_credentials']['access_id'] == 1)

	{

		setSystemSessionData();

	}

	else if($row['set_system'] != $_SESSION[CORE_U_CODE]['system_settings']['set_system'] && $_SESSION[CORE_U_CODE]['user_credentials']['access_id'] != 1)

	{

		

		updateSystemLogForLogout();

		session_unset();

		session_destroy();

		session_regenerate_id(true);

		header("Location: index.php");

	}

	



	userAuthentication();



	$root_path = '';

	

	define ( 'ROOT_PATH', $root_path == '' ? '' : $root_path );





	$sql = "SELECT * FROM tbl_school_settings";

	$query = mysql_query($sql);

	$row = mysql_fetch_array($query);	



	define('SYS_ACTIVATION_BY', 		$_SESSION[CORE_U_CODE]['system_settings']['activation_by']);

	define('SYS_MAX_LOGIN_ATTEMPT', 	$_SESSION[CORE_U_CODE]['system_settings']['max_login_attempt']);

	define('SYS_TOTAL_FAILED_LOGIN', 	$_SESSION[CORE_U_CODE]['system_settings']['total_failed_login']);

	define('SYS_TIME_TO_RELOGIN', 		$_SESSION[CORE_U_CODE]['system_settings']['time_to_relogin']);

	define('SYS_PASSWORD_MIN', 			$_SESSION[CORE_U_CODE]['system_settings']['password_min']);

	define('SYS_PASSWORD_MAX', 			$_SESSION[CORE_U_CODE]['system_settings']['password_max']);

	define('SYS_PASSWORD_COMPLEXITY', 	$_SESSION[CORE_U_CODE]['system_settings']['password_complexity']);

	define('SYS_ALLOWED_SIM_LOGIN', 	$_SESSION[CORE_U_CODE]['system_settings']['allowed_sim_login']);

	define('SYS_SET_SYSTEM', 			$_SESSION[CORE_U_CODE]['system_settings']['set_system']);

	define('DEFAULT_RECORD', 			$_SESSION[CORE_U_CODE]['system_settings']['default_record']);

	define('NOTIFICATION', 			$_SESSION[CORE_U_CODE]['system_settings']['notification']);

												

	define('SCHOOL_NAME', 		$_SESSION[CORE_U_CODE]['school_settings']['school_name']);

	define('SCHOOL_ADDRESS', 	$_SESSION[CORE_U_CODE]['school_settings']['school_address']);

	define('SCHOOL_CITY', 		$_SESSION[CORE_U_CODE]['school_settings']['school_city']);

	define('SCHOOL_POSTAL', 	$_SESSION[CORE_U_CODE]['school_settings']['school_postal']);

	define('SCHOOL_TEL', 		$_SESSION[CORE_U_CODE]['school_settings']['school_tel']);

	define('SCHOOL_FAX', 		$_SESSION[CORE_U_CODE]['school_settings']['school_fax']);

	define('SCHOOL_LOGO', 		$_SESSION[CORE_U_CODE]['school_settings']['school_logo']);

	define('SCHOOL_SYS_EMAIL',	$_SESSION[CORE_U_CODE]['school_settings']['school_sys_email']);

	define('SCHOOL_SYS_URL',	$_SESSION[CORE_U_CODE]['school_settings']['school_sys_url']);

	define('SCHOOL_OPEN_TIME', 	$_SESSION[CORE_U_CODE]['school_settings']['school_open_time']);

	define('SCHOOL_CLOSE_TIME', $_SESSION[CORE_U_CODE]['school_settings']['school_close_time']);



	define('CURRENT_SY_ID', 			$_SESSION[CORE_U_CODE]['current_sy_info']['current_sy_id']);	

	define('CURRENT_SY_START', 			$_SESSION[CORE_U_CODE]['current_sy_info']['current_sy_start']);	

	define('CURRENT_SY_END', 			$_SESSION[CORE_U_CODE]['current_sy_info']['current_sy_end']);	

	define('CURRENT_SY_NO_OF_TERM', 	$_SESSION[CORE_U_CODE]['current_sy_info']['current_sy_number_of_term']);	

	define('CURRENT_SY_NO_OF_PERIOD', 	$_SESSION[CORE_U_CODE]['current_sy_info']['current_sy_number_of_period']);	

		

	

	define('CURRENT_TERM_ID', 			$_SESSION[CORE_U_CODE]['current_term_info']['current_term_id']);

	define('CURRENT_TERM_SY_ID', 		$_SESSION[CORE_U_CODE]['current_term_info']['current_term_sy_id']);

	

	define('CURRENT_PERIOD_ID', 		$_SESSION[CORE_U_CODE]['current_period_info']['current_period_id']);

	define('CURRENT_PERIOD_SY_ID', 		$_SESSION[CORE_U_CODE]['current_period_info']['current_period_sy_id']);

	define('CURRENT_PERIOD_TERM_ID', 	$_SESSION[CORE_U_CODE]['current_period_info']['current_period_term_id']);

	define('CURRENT_PERIOD_NAME', 		$_SESSION[CORE_U_CODE]['current_period_info']['current_period_name']);

	define('CURRENT_PERIOD_SUB_START', 	$_SESSION[CORE_U_CODE]['current_period_info']['current_period_sub_start']);

	define('CURRENT_PERIOD_SUB_END', 	$_SESSION[CORE_U_CODE]['current_period_info']['current_period_sub_end']);

	define('CURRENT_PERIOD_PERCENTAGE', $_SESSION[CORE_U_CODE]['current_period_info']['current_period_percentage']);

	define('CURRENT_PERIOD_SUB_LOCKED', $_SESSION[CORE_U_CODE]['current_period_info']['current_period_sub_locked']);	





	define('USER_ID', 			$_SESSION[CORE_U_CODE]['user_credentials']['user_id']);

	define('USERNAME', 			$_SESSION[CORE_U_CODE]['user_credentials']['username']);	

	define('ACCESS_ID', 		$_SESSION[CORE_U_CODE]['user_credentials']['access_id']);	

	define('USER_FIRST_NAME', 	$_SESSION[CORE_U_CODE]['user_info']['firstname']);

	define('USER_LAST_NAME', 	$_SESSION[CORE_U_CODE]['user_info']['lastname']);	

	define('USER_EMAIL', 		$_SESSION[CORE_U_CODE]['user_info']['email']);	

	define('USER_LAST_LOGIN', 	$_SESSION[CORE_U_CODE]['user_credentials']['last_login']);	

	define('USER_LAST_IP_CONNECTED', 	$_SESSION[CORE_U_CODE]['user_credentials']['last_ip_connected']);

	define('USER_FAILED_LOGS', 	$_SESSION[CORE_U_CODE]['user_credentials']['failed_logs']);			

			



	if(ACCESS_ID == '6')

	{



		define('USER_STUDENT_ID',	$_SESSION[CORE_U_CODE]['user_info']['student_id']);	

		define('USER_CURRICULUM_ID',	$_SESSION[CORE_U_CODE]['user_info']['curriculum_id']);	

		define('USER_STUDENT_NUMBER',	$_SESSION[CORE_U_CODE]['user_info']['student_number']);	

		define('USER_COURSE_ID',	$_SESSION[CORE_U_CODE]['user_info']['course_id']);	

		define('USER_YEAR_LEVEL',	$_SESSION[CORE_U_CODE]['user_info']['year_level']);			

	

	}

	else if(ACCESS_ID == '2')

	{



		define('USER_EMP_ID',	$_SESSION[CORE_U_CODE]['user_info']['professor_id']);			

	

	}

	else if(ACCESS_ID == '7')

	{

	

		define('GUARDIAN_ID',	$_SESSION[CORE_U_CODE]['user_info']['guardian_id']);

		define('STUDENT_ID',	$_SESSION[CORE_U_CODE]['user_info']['student_id']);	

		

	}

	else if(ACCESS_ID == '1')

	{



		define('ADMIN_EMP_ID',	$_SESSION[CORE_U_CODE]['user_info']['professor_id']);			

	

	}





	// [+] LOGS TEMPLATES



		// GRADING MESSAGES

		define('MSG_ADMIN_SUBMIT_STUDENT_GRADE',' submitted the grade on behalf of %s for student %s under section %s ( %s | %s )');

		define('MSG_ADMIN_MODIFIED_STUDENT_GRADE',' modified the grade on behalf of %s for student %s under section %s ( %s | %s )');	

	

		define('MSG_PROFESSOR_SUBMIT_GRADESHEET',' submitted the gradesheet for section %s ( %s | %s )');

		define('MSG_PROFESSOR_MODIFIED_GRADESHEET',' modified the gradesheet for section %s ( %s | %s )');	

		define('MSG_PROFESSOR_LOCKED_GRADESHEET',' submitted and locked the gradesheet for section %s ( %s | %s )');

		

		//ENROLLMENT

		define('MSG_ADMIN_RESERVE_SUBJECT_FOR_STUDENT',' reserved subject on behalf of %s for school year %s');

		define('MSG_ADMIN_MODIFIED_RESERVE_SUBJECT_FOR_STUDENT',' modified reserved subject details on behalf of %s for school year %s');		

		

		define('MSG_ADMIN_RESERVE_IN_BLOCK_FOR_STUDENT',' reserved in a block on behalf of %s for school year %s');		

		

		define('MSG_STUDENT_RESERVE_SUBJECT',' successfully reserved subjects for school year %s');	

		define('MSG_STUDENT_RESERVE_IN_BLOCK_SUBJECT',' successfully reserved in a block for school year %s');	

		define('MSG_ADMIN_ADD_SUBJECT_FOR_STUDENT',' successfully added subject on behalf of %s for school year %s');	

		define('MSG_ADMIN_DROP_SUBJECT_FOR_STUDENT',' successfully dropped subject on behalf of %s for school year %s');		

		

		//PAYMENTS

		define('MSG_CASHIER_RESERVED_PAYMENT_FOR_STUDENT',' successfully added enrollment payment of %s for school year %s');

		define('MSG_CASHIER_BALANCE_PAYMENT_FOR_STUDENT',' successfully added balance payment of %s for school year %s');

		define('MSG_CASHIER_REFUND_FOR_STUDENT',' successfully refunded payment of %s for school year %s');

		define('MSG_CASHIER_BOUNCE_CHECK_PAYMENT_FOR_STUDENT',' modified cheque payment of %s for school year %s');

		

		//CALENDAR

		define('MSG_PROF_CALENDAR_FOR_STUDENTS',' posts events/news in the calendar for students under section %s  %s ( %s )');

		define('MSG_MODIFIED_PROF_CALENDAR_FOR_STUDENTS',' modified posted events/news in the calendar for students under section %s  %s ( %s )');

		define('MSG_ADMIN_CALENDAR_FOR_ALL',' posts events/news about %s in the calendar');

		define('MSG_MODIFIED_ADMIN_CALENDAR_FOR_ALL',' modified posted events/news about %s in the calendar');

?>