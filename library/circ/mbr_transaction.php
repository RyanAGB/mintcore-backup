<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../shared/common.php");
  $tab = "circulation";
  $nav = "account";
  $restrictInDemo = true;
  require_once("../shared/logincheck.php");

  require_once("../classes/MemberAccountTransaction.php");
  require_once("../classes/MemberAccountQuery.php");
  require_once("../functions/errorFuncs.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);

  #****************************************************************************
  #*  Checking for post vars.  Go back to form if none found.
  #****************************************************************************
  if (count($_POST) == 0) {
    header("Location: ../circ/index.php");
    exit();
  }

  #****************************************************************************
  #*  Retrieving get var
  #****************************************************************************
  $mbrid = $_POST["mbrid"];

  #****************************************************************************
  #*  Validate data
  #****************************************************************************
  $trans = new MemberAccountTransaction();
  $trans->setMbrid($mbrid);
  $trans->setCreateUserid($_SESSION["userid"]);
  $trans->setTransactionTypeCd($_POST["transactionTypeCd"]);
  $_POST["transactionTypeCd"] = $trans->getTransactionTypeCd();
  $trans->setAmount($_POST["amount"]);
  $_POST["amount"] = $trans->getAmount();
  $trans->setDescription($_POST["description"]);
  $_POST["description"] = $trans->getDescription();
  $validData = $trans->validateData();
  if (!$validData) {
    $pageErrors["amount"] = $trans->getAmountError();
    $pageErrors["description"] = $trans->getDescriptionError();
    $_SESSION["postVars"] = $_POST;
    $_SESSION["pageErrors"] = $pageErrors;
    header("Location: ../circ/mbr_account.php?mbrid=".U($mbrid));
    exit();
  }
	
	  #****************************************************************************
  #*  Check for outstanding balance due
  #****************************************************************************
  getStudentLibraryDueFee($mbrid);
  $acctQ = new MemberAccountQuery();
  $acctQ->connect();
  if ($acctQ->errorOccurred()) {
    $acctQ->close();
    displayErrorPage($acctQ);
  }
  $balance = $acctQ->getBalance($mbrid);
  if ($acctQ->errorOccurred()) {
    $acctQ->close();
    displayErrorPage($acctQ);
  }
  $acctQ->close();
	
  #**************************************************************************
  #*  Insert new member transaction
  #**************************************************************************
  $transQ = new MemberAccountQuery();
  $transQ->connect();
  if ($transQ->errorOccurred()) {
    $transQ->close();
    displayErrorPage($transQ);
  }
 if($_POST["transactionTypeCd"]=='-p')
{
  if($_POST["amount"]<=$balance&&$_POST["amount"]<>0&&$_POST["amount"]>0)
  {
 	 $trans = $transQ->insert($trans);
  }
}
else
{
	$trans = $transQ->insert($trans);
}
  if ($transQ->errorOccurred()) {
    $transQ->close();
    displayErrorPage($transQ);
  }
  $transQ->close();

  #**************************************************************************
  #*  Destroy form values and errors
  #**************************************************************************
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);
	
  $msg = $loc->getText("mbrTransactionSuccess");

if($_POST["transactionTypeCd"]=='-p')
{
  if($balance=='')
	  {
		$msg = "No Balance.";
	  }
  else if($_POST["amount"]>$balance)
	  {
		$msg = "Payment is Larger than the Balance amount.";
	  }
}
  
  header("Location: ../circ/mbr_account.php?mbrid=".U($mbrid)."&reset=Y&msg=".U($msg));
  exit();
?>
