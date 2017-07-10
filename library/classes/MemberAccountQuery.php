<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 //require_once("../shared/logincheck.php");
require_once("../shared/global_constants.php");
require_once("../classes/Query.php");
require_once("../classes/MemberAccountTransaction.php");

/******************************************************************************
 * MemberAccountQuery data access component for member account transactions
 *
 * @author David Stevens <dave@stevens.name>;
 * @version 1.0
 * @access public
 ******************************************************************************
 */
class MemberAccountQuery extends Query {
  var $_rowCount = 0;
  var $_loc;

  function MemberAccountQuery() {
    $this->Query();
    $this->_loc = new Localize(OBIB_LOCALE,"classes");
  }

  function getRowCount() {
    return $this->_rowCount;
  }

  /****************************************************************************
   * Executes a query to select account information
   * @param string $mbrid mbrid of member
   * @return BiblioHold returns hold record or false, if error occurs
   * @access public
   ****************************************************************************
   */
  function doQuery($mbrid) {
    # setting query that will return all the data
    $sql = $this->mkSQL("select ob_member_account.*, "
                        . " ob_transaction_type_dm.description ob_transaction_type_desc "
                        . "from ob_member_account, ob_transaction_type_dm "
                        . "where ob_member_account.transaction_type_cd = ob_transaction_type_dm.code "
                        . " and ob_member_account.student_id = %N "
                        . "order by create_dt ", $mbrid);

    if (!$this->_query($sql, $this->_loc->getText("memberAccountQueryErr1"))) {
      return false;
    }
    $this->_rowCount = $this->_conn->numRows();
    return true;
  }

  /****************************************************************************
   * Executes a query to select account information
   * @param string $mbrid mbrid of member
   * @return decimal returns balance due
   * @access public
   ****************************************************************************
   */
  function getBalance($mbrid) {
    # setting query that will return all the data
    $sql = $this->mkSQL("select sum(ob_member_account.amount) balance "
                        . "from ob_member_account "
                        . "where ob_member_account.student_id = %N ", $mbrid);
    if (!$this->_query($sql, $this->_loc->getText("memberAccountQueryErr1"))) {
      return 0;
    }
    $array = $this->_conn->fetchRow();
    if ($array == false) {
      $this->_errorOccurred = true;
      $this->_error = $this->_loc->getText("memberAccountQueryErr1");
      return 0;
    }
    return $array["balance"];
  }

  /****************************************************************************
   * Fetches a row from the query result and populates the BiblioStatusHist object.
   * @return BiblioStatusHist returns bibliography status history object or false if no more holds to fetch
   * @access public
   ****************************************************************************
   */
  function fetchRow() {
    $array = $this->_conn->fetchRow();
    if ($array == false) {
      return false;
    }
    $trans = new MemberAccountTransaction();
    $trans->setMbrid($array["student_id"]);
    $trans->setTransid($array["transid"]);
    $trans->setCreateDt($array["create_dt"]);
    $trans->setCreateUserid($array["create_userid"]);
    $trans->setTransactionTypeCd($array["transaction_type_cd"]);
    $trans->setTransactionTypeDesc($array["ob_transaction_type_desc"]);
    $trans->setAmount($array["amount"]);
    $trans->setDescription($array["description"]);
    return $trans;
  }

  /****************************************************************************
   * Inserts a new account transaction into the member_account table.
   * @param MemberAccountTransaction $trans account transaction to insert
   * @access public
   ****************************************************************************
   */
  function insert($trans) {
    // change trans type payment and credit amount to negative 
    $transTypeSign = substr($trans->getTransactionTypeCd(),0,1);
    if ($transTypeSign == "-") {
      $amt = $trans->getAmount() * -1;
    } else {
      $amt = $trans->getAmount();
    }
    $sql = $this->mkSQL("insert into ob_member_account "
                        . "values (%N, null, ".CURRENT_TERM_ID.", sysdate(), ".USER_ID.", %Q, %N, %Q) ",
                        $trans->getMbrid(), 
                        $trans->getTransactionTypeCd(), $amt,
                        $trans->getDescription());
						//echo $sql;
    return $this->_query($sql, $this->_loc->getText("memberAccountQueryErr2"));
  }

  /****************************************************************************
   * Deletes history from the biblio_status_hist table.
   * @param string $mbrid member id of history to delete
   * @return boolean returns false, if error occurs
   * @access public 
   ****************************************************************************
   */
  function delete($mbrid,$tranid="") {
    $sql = $this->mkSQL("delete from ob_member_account where student_id = %N ", $mbrid);
    if ($tranid != "") {
      $sql .= $this->mkSQL(" and transid = %N ", $tranid);
    }
    return $this->_query($sql, $this->_loc->getText("memberAccountQueryErr3"));
  }


}
?>
