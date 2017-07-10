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

	 include_once("../config.php");
    // just so we know it is broken
    error_reporting(E_ALL);
    // some basic sanity checks

    if(isset($_GET['employee_id']) && is_numeric($_GET['employee_id'])) 
	{
 
        // get the image from the db
        $sql = "SELECT image_file FROM tbl_employee_photo WHERE employee_id=".$_GET['employee_id'];
 
        // the result of the query
        $result = mysql_query("$sql") or die("Invalid query: " . mysql_error());
 
        // set the header for the image
        header("Content-type: image/jpeg");
        $row= mysql_fetch_array($result);
		echo $row['image_file'];
 
    }
    else 
	{
        echo 'Please use a real id number';
    }
?>