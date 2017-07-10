<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  require_once("../classes/Staff.php");
  require_once("../classes/StaffQuery.php");
  require_once("../classes/SessionQuery.php");
  require_once("../functions/errorFuncs.php");

  #****************************************************************************
  #*  Checking for post vars.  Go back to form if none found.
  #****************************************************************************
  $pageErrors = "";
  if (count($_POST) == 0) {
    header("Location: ../shared/loginform.php");
    exit();
  }

  #****************************************************************************
  #*  Username edits
  #****************************************************************************
  $username = $_POST["username"];
  if ($username == "") {
    $error_found = true;
    $pageErrors["username"] = "Username is required.";
  }

  #****************************************************************************
  #*  Password edits
  #****************************************************************************
  $error_found = false;
  $pwd = $_POST["pwd"];
  if ($pwd == "") {
    $error_found = true;
    $pageErrors["pwd"] = "Password is required.";
  } else {


    $staffQ = new StaffQuery();
    $staffQ->connect();
    if ($staffQ->errorOccurred()) {
      displayErrorPage($staffQ);
    }
    $staffQ->verifySignon($username, $pwd);
    if ($staffQ->errorOccurred()) {
      displayErrorPage($staffQ);
    }
    $staff = $staffQ->fetchStaff();
    if ($staff == false) {
      # invalid password.  Add one to login attempts.
      $error_found = true;
      $pageErrors["pwd"] = "Invalid signon.";
      if (!isset($_SESSION["loginAttempts"]) || ($_SESSION["loginAttempts"] == "")) {
        $sess_login_attempts = 1;
      } else {
        $sess_login_attempts = $_SESSION["loginAttempts"] + 1;
      }
      # Suspend userid if login attempts >= 3
      if ($sess_login_attempts >= 3) {
        $staffQ->suspendStaff($username);
        $staffQ->close();
        header("Location: suspended.php");
        exit();
      }
    }
    $staffQ->close();
  }

  #****************************************************************************
  #*  Redirect back to form if error occured
  #****************************************************************************
  if ($error_found == true) {
    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;
    header("Location: ../shared/loginform.php");
    exit();
  }

  #****************************************************************************
  #*  Redirect to suspended message if suspended
  #****************************************************************************
  if ($staff->isSuspended()) {
    header("Location: ../shared/suspended.php");
    exit();
  }

  #**************************************************************************
  #*  Insert new session row with random token
  #**************************************************************************

  $sessionQ = new SessionQuery();
  $sessionQ->connect();
  if ($sessionQ->errorOccurred()) {
    $sessionQ->close();
    displayErrorPage($sessionQ);
  }
  $token = $sessionQ->getToken($staff->getUserid());
  if ($token == false) {
    $sessionQ->close();
    displayErrorPage($sessionQ);
  }
  $sessionQ->close();

  #**************************************************************************
  #*  Destroy form values and errors and reset signon variables
  #**************************************************************************
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

	$_SESSION[CORE_U_CODE]['library'] = array(
  										username => $staff->getUsername(),
  										userid => $staff->getUserid(),
  										token => $token,
  										loginAttempts => 0,
  										hasAdminAuth => $staff->hasAdminAuth(),
  										hasCircAuth => $staff->hasCircAuth(),
  										hasCircMbrAuth => $staff->hasCircMbrAuth(),
  										hasCatalogAuth => $staff->hasCatalogAuth(),
  										hasReportsAuth => $staff->hasReportsAuth()
										);

  #**************************************************************************
  #*  Redirect to return page
  #**************************************************************************
  header("Location: ".$_SESSION["returnPage"]);
  exit();

?>
