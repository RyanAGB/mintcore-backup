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

    if(isset($_GET['id']) && is_numeric($_GET['id'])) 
	{
 
        // get the image from the db
        $sql = "SELECT school_logo FROM tbl_school_settings WHERE id=".$_GET['id'];
 
        // the result of the query
        $result = mysql_query("$sql") or die("Invalid query: " . mysql_error());
 
        // set the header for the image
        header("Content-type: image/png");
        $row= mysql_fetch_array($result);
		echo $row['school_logo'];
 
    }
    else 
	{
        echo 'Please use a real id number';
    }
?>