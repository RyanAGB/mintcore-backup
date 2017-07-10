<?php


@session_start();

	



	// LOCAL SERVER



	$db_server		='localhost';

	$db_user		='root';

	$db_password		='M3ridi@n';

	$db_name		='lutz';






	// PRODUCTION SERVER

/*	

	$db_server		='localhost';

	$db_user		='root';

	$db_password		='';

	$db_name		='mint';
*/




	$root_path = '';



	

	define ('CORE_U_CODE','core12345');



	// CONNECT TO DATABASE 

	$conn = @mysql_connect ($db_server,$db_user,$db_password) or die('Problem in connecting to server');

	

	if($conn)

	{

		$con_db = @mysql_select_db($db_name ,$conn) or die('Access denied or can\'t find database');

	}

	else

	{

		exit;

	}

define ('CONN',$conn);

define ('CONN_DB',$con_db);



//error_reporting(E_ALL);

//ini_set('display_errors', '1');



?>