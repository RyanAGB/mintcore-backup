<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 //require_once("../shared/logincheck.php");
require_once("../shared/global_constants.php");
require_once("../classes/Query.php");
require_once("../classes/BiblioHold.php");

/******************************************************************************
 * BiblioHoldQuery data access component for holds on library bibliography copies
 *
 * @author David Stevens <dave@stevens.name>;
 * @version 1.0
 * @access public
 ******************************************************************************
 */
class BiblioHoldQuery extends Query {
  var $_rowCount = 0;
  var $_loc;

  function BiblioHoldQuery() {
    $this->Query();
    $this->_loc = new Localize(OBIB_LOCALE,"classes");
  }

  function getRowCount() {
    return $this->_rowCount;
  }


  /****************************************************************************
   * Executes a query to select holds
   * @param string $bibid bibid of bibliography copy to select
   * @return boolean returns false if error occurs
   * @access public
   ****************************************************************************
   */
  function queryByBibid($bibid) {
    # setting query that will return all the data
    $sql = $this->mkSQL("select ob_biblio_hold.*, "
                        . " stud.lastname, stud.firstname, "
                        . " ob_biblio_copy.barcode_nmbr, ob_biblio_copy.status_cd, "
                        . " ob_biblio_copy.due_back_dt "
                        . "from ob_biblio_hold, ob_biblio_copy, tbl_student stud "
                        . "where ob_biblio_hold.bibid = ob_biblio_copy.bibid "
                        . " and ob_biblio_hold.copyid = ob_biblio_copy.copyid "
                        . " and ob_biblio_hold.student_id = stud.id "
                        . " and ob_biblio_hold.bibid = %N "
                        . "order by barcode_nmbr, hold_begin_dt ",
                        $bibid);

    if (!$this->_query($sql, $this->_loc->getText("biblioHoldQueryErr1"))) {
      return false;
    }
    $this->_rowCount = $this->_conn->numRows();
    return true;
  }

  /****************************************************************************
   * Executes a query to select holds
   * @param string $mbrid mbrid of member placing holds
   * @return boolean returns false if error occurs
   * @access public
   ****************************************************************************
   */
  function queryByMbrid($mbrid) {
    # setting query that will return all the data
    $sql = $this->mkSQL("select ob_biblio_hold.*, "
                        . "ob_biblio.title, ob_biblio.author, "
                        . "ob_biblio.material_cd, ob_biblio_copy.barcode_nmbr, "
                        . "ob_biblio_copy.status_cd, ob_biblio_copy.due_back_dt "
                        . "from ob_biblio_hold, ob_biblio_copy, ob_biblio "
                        . "where ob_biblio_hold.bibid = ob_biblio_copy.bibid "
                        . "and ob_biblio_hold.copyid = ob_biblio_copy.copyid "
                        . "and ob_biblio_hold.bibid = ob_biblio.bibid "
                        . "and ob_biblio_hold.student_id = %N "
			. "order by ob_biblio_hold.hold_begin_dt desc",
                        $mbrid);

    if (!$this->_query($sql, $this->_loc->getText("biblioHoldQueryErr2"))) {
      return false;
    }
    $this->_rowCount = $this->_conn->numRows();
    return true;
  }

  /****************************************************************************
   * Fetches a row from the query result and populates the BiblioHold object.
   * @return BiblioHold returns hold on bibliography copy or false if no more holds to fetch
   * @access public
   ****************************************************************************
   */
  function fetchRow() {
    $array = $this->_conn->fetchRow();
    if ($array == false) {
      return false;
    }

    $hold = new BiblioHold();
    $hold->setBibid($array["bibid"]);
    $hold->setCopyid($array["copyid"]);
    $hold->setHoldid($array["holdid"]);
    $hold->setHoldBeginDt($array["hold_begin_dt"]);
    $hold->setMbrid($array["mbrid"]);
    $hold->setBarcodeNmbr($array["barcode_nmbr"]);
    $hold->setStatusCd($array["status_cd"]);
    $hold->setDueBackDt($array["due_back_dt"]);
    if (isset($array["title"])) {
      $hold->setTitle($array["title"]);
    }
    if (isset($array["author"])) {
      $hold->setAuthor($array["author"]);
    }
    if (isset($array["material_cd"])) {
      $hold->setMaterialCd($array["material_cd"]);
    }
    if (isset($array["last_name"])) {
      $hold->setLastName($array["last_name"]);
    }
    if (isset($array["first_name"])) {
      $hold->setFirstName($array["first_name"]);
    }
    return $hold;
  }

  /****************************************************************************
   * Inserts a new bibliography copy hold into the biblio_hold table.
   * @param BiblioHold $hold hold to insert
   * @return int 0 - error
   *             1 - success
   *             2 - invalid barcode
   * @access public
   ****************************************************************************
   */
  function insert($mbrid,$barcode) {
    // getting bibid and copyid for a given barcode
    $sql = $this->mkSQL("select bibid, copyid from ob_biblio_copy "
                        . "where barcode_nmbr = %Q", $barcode);
    if (!$this->_query($sql, $this->_loc->getText("biblioHoldQueryErr3"))) {
      return 0;
    }
    if ($this->_conn->numRows() == 0) {
      return 2;
    }
    $array = $this->_conn->fetchRow();
    $bibid = $array["bibid"];
    $copyid = $array["copyid"];

    $sql = $this->mkSQL("insert into ob_biblio_hold values "
                        . "(%N, %N, null, sysdate(), %N)",
                        $bibid, $copyid, $mbrid);
    if (!$this->_query($sql, $this->_loc->getText("biblioHoldQueryErr4"))) {
      return 0;
    }
    return 1;
  }

  /****************************************************************************
   * Deletes a copy from the biblio_copy table.
   * @param string $bibid bibliography id of copy to delete
   * @param string $copyid optional copy id of copy to delete.  If none
   *               supplied then all copies under a given bibid will be deleted.
   * @return boolean returns false, if error occurs
   * @access public
   ****************************************************************************
   */
  function delete($bibid,$copyid,$holdid) {
    $sql = $this->mkSQL("delete from ob_biblio_hold where bibid = %N "
                        . "and copyid = %N and holdid = %N",
                        $bibid, $copyid, $holdid);
    return $this->_query($sql, $this->_loc->getText("biblioHoldQueryErr5"));
  }

  /****************************************************************************
   * Retrieves first entry in hold queue
   * @param long $bibid bibid of bibliography on hold
   * @param long $copyid copyid of bibliography on hold
   * @return BiblioHold first hold in queue or false if error occurs
   * @access public
   ****************************************************************************
   */
  function getFirstHold($bibid,$copyid) {
    $sql = $this->mkSQL("select * from ob_biblio_hold "
                        . "where bibid = %N and copyid = %N "
                        . "order by hold_begin_dt",
                        $bibid, $copyid);
    if (!$this->_query($sql, $this->_loc->getText("biblioHoldQueryErr6"))) {
      return FALSE;
    }
    $this->_rowCount = $this->_conn->numRows();
    if ($this->_rowCount == 0) {
      return FALSE;
    }
    $array = $this->_conn->fetchRow();
    $hold = new BiblioHold();
    $hold->setBibid($array["bibid"]);
    $hold->setCopyid($array["copyid"]);
    $hold->setHoldid($array["holdid"]);
    $hold->setHoldBeginDt($array["hold_begin_dt"]);
    $hold->setMbrid($array["mbrid"]);
    return $hold;
  }

}
?>
