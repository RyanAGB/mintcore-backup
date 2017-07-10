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

@session_start();

	



	// TEST SERVER


/*
	$db_server		='localhost';

	$db_user		='root';

	$db_password	='root';

	$db_name		='start_feb_28_mist_db';
*/


	// LOCAL SERVER




	$db_server		='localhost';

	$db_user		='root';

	$db_password	='root';

	$db_name		='mist_local';







	// REMOTE SERVER

/*
	$db_server		='localhost';

	$db_user		='mintph_mist_sys';

	$db_password		='o;lc9E(!~[O$';

	$db_name		='mintph_mintsis';
*/



	// NETWORK SERVER

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