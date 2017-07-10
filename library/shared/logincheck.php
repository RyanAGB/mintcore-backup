<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
#*********************************************************************************
#*  checklogin.php
#*  Description: Used to verify signon token on every secured page.
#*               Redirects to the login page if token not valid.
#*********************************************************************************

  //require_once("../classes/SessionQuery.php");
 //require_once("../functions/errorFuncs.php");
 require_once("../../config.php");
  require_once("../../includes/functions.php");
  require_once("../../includes/common.php");

  #****************************************************************************
  #*  Temporarily disabling security for demo since sourceforge.net
  #*  seems to be using mirrored servers that do not share session info.
  #****************************************************************************
  /*if (!OBIB_DEMO_FLG) {

    $pages = array(
      'opac'=>'../opac/index.php',
      'home'=>'../home/index.php',
      'circulation'=>'../circ/index.php',
      'cataloging'=>'../catalog/index.php',
      'admin'=>'../admin/index.php',
      'reports'=>'../reports/index.php',
    );
  $returnPage = $pages[$tab];
  $_SESSION["returnPage"] = $returnPage;*/

  #****************************************************************************
  #*  Checking to see if session variables exist
  #****************************************************************************
  /*if (!isset($_SESSION["userid"]) or ($_SESSION["userid"] == "")) {
    header("Location: ../shared/loginform.php");
    exit();
  }
  if (!isset($_SESSION["token"]) or ($_SESSION["token"] == "")) {
    header("Location: ../shared/loginform.php");
    exit();
  }*/
  if(USER_IS_LOGGED != '1')
  	{
  		header('Location: ../../index.php');
    	exit();
  	}
  else if(!in_array('library',$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../../forbid.html');
    	exit();
	}

  #****************************************************************************
  #*  Checking session table to see if session_id has timed out
  #****************************************************************************
 /* $sessQ = new SessionQuery();
  $sessQ->connect();
  if ($sessQ->errorOccurred()) {
    displayErrorPage($sessQ);
  }
  if (!$sessQ->validToken($_SESSION["userid"], $_SESSION["token"])) {
    if ($sessQ->errorOccurred()) {
      displayErrorPage($sessQ);
    }
    $sessQ->close();
    header("Location: ../shared/loginform.php?RET=".U($returnPage));
    exit();
  }
  $sessQ->close();
  
  else if ($_SESSION[CORE_U_CODE]['library']["hasSuspendAuth"]=='Y') 
		{
		  echo '<script language="javascript">alert("You have been suspended to access the library.");window.location="../../index.php"</script>';
		} 
*/
  #****************************************************************************
  #*  Checking authorization for this tab
  #*  The session authorization flags were set at login in login.php
  #****************************************************************************
  if ($tab == "circulation"){
  
		if(USER_IS_LOGGED != '1')
		{
			header('Location: ../../index.php');
			exit();
		}
		else if(!in_array('circ',$_SESSION[CORE_U_CODE]['access_components']))
		{
		  header("Location: ../circ/noauth.php");
		  exit();
		} 
		else if ($_SESSION[CORE_U_CODE]['library']["hasCircMbrAuth"]=='N') 
		{
		  header("Location: ../circ/noauth.php");
		  exit();
		}
	 
  } elseif ($tab == "cataloging") {
  
		if(USER_IS_LOGGED != '1')
		{
			header('Location: ../../index.php');
			exit();
		}
		else if(!in_array('catalog',$_SESSION[CORE_U_CODE]['access_components']))
		{
		  header("Location: ../catalog/noauth.php");
		  exit();
		}
	
  } elseif ($tab == "admin") {
  
		if(USER_IS_LOGGED != '1')
		{
			header('Location: ../../index.php');
			exit();
		}
		else if ($_SESSION[CORE_U_CODE]['library']["hasAdminAuth"]=='N') 
		{
		  header("Location: ../admin/noauth.php");
		  exit();
		}
	
  } elseif ($tab == "reports") {
    
		if(USER_IS_LOGGED != '1')
		{
			header('Location: ../../index.php');
			exit();
		}
		else if(!in_array('reports',$_SESSION[CORE_U_CODE]['access_components']))
		{
		  header("Location: ../reports/noauth.php");
		  exit();
		}
  }
	

 // }

?>
