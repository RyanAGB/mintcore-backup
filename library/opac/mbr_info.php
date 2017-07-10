<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 require_once("../../config.php");
 require_once("../../includes/functions.php");
  require_once("../../includes/common.php");
  
   if(USER_IS_LOGGED != '1')
  	{
  		header('Location: ../../index.php');
    	exit();
  	}
  else if(ACCESS_ID != 6)
	{
		header('Location: ../../forbid.html');
    	exit();
	}
 
  require_once("../shared/common.php");
  $tab = "opac";
  $nav = "pending";


 require_once("../functions/inputFuncs.php");
  require_once("../functions/formatFuncs.php");
  require_once("../classes/Member.php");
  require_once("../classes/MemberQuery.php");
  require_once("../classes/BiblioSearch.php");
  require_once("../classes/BiblioSearchQuery.php");
  require_once("../classes/BiblioHold.php");
  require_once("../classes/BiblioHoldQuery.php");
  require_once("../classes/MemberAccountQuery.php");
  require_once("../classes/DmQuery.php");
  require_once("../shared/get_form_vars.php");
  require_once("../classes/Localize.php");
  $loc = new Localize(OBIB_LOCALE,$tab);


  #****************************************************************************
  #*  Retrieving get var
  #****************************************************************************
  $mbrid = USER_STUDENT_ID;
  if (isset($_GET["msg"])) {
    $msg = "<font class=\"error\">".H($_GET["msg"])."</font><br><br>";
  } else {
    $msg = "";
  }

  #****************************************************************************
  #*  Loading a few domain tables into associative arrays
  #****************************************************************************
  $dmQ = new DmQuery();
  $dmQ->connect();
  $mbrClassifyDm = $dmQ->getAssoc("ob_mbr_classify_dm");
  $mbrMaxFines = $dmQ->getAssoc("ob_mbr_classify_dm", "max_fines");
  $biblioStatusDm = $dmQ->getAssoc("ob_biblio_status_dm");
  $materialTypeDm = $dmQ->getAssoc("ob_material_type_dm");
  $materialImageFiles = $dmQ->getAssoc("ob_material_type_dm", "image_file");
  $memberFieldsDm = $dmQ->getAssoc("ob_member_fields_dm");
  $dmQ->close();

  #****************************************************************************
  #*  Search database for member
  #****************************************************************************
  $mbrQ = new MemberQuery();
  $mbrQ->connect();
  $mbr = $mbrQ->get($mbrid);
  $mbrQ->close();

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
  $balMsg = "";
  if ($balance > 0 ){//&& $balance >= $mbrMaxFines[$mbr->getClassification()]) {
    $balText = moneyFormat($balance,2);
    $balMsg = "<font class=\"error\">Note: You have an outstanding account balance of ".$balText."</font><br><br>";
  }

  #**************************************************************************
  #*  Show member information
  #**************************************************************************
  require_once("../shared/header_opac.php");
?>

<?php echo $balMsg ?>
<?php echo $msg ?>


<table class="primary_index">
  <tr>
    <th valign="top" nowrap="yes" align="left">Checked Out</th>
    <th valign="top" nowrap="yes" align="left">Material</th>
    <th valign="top" nowrap="yes" align="left">Barcode</th>
    <th valign="top" nowrap="yes" align="left">Title</th>
    <th valign="top" nowrap="yes" align="left">Author</th>
    <th valign="top" nowrap="yes" align="left">
      Due Date
    </th>
    <!--<th valign="top" align="left">Renewal</th>!-->
    <th valign="top" align="left">Days Late</th>
  </tr>

<?php
  #****************************************************************************
  #*  Search database for BiblioStatus data
  #****************************************************************************
  $biblioQ = new BiblioSearchQuery();
  $biblioQ->connect();
  if ($biblioQ->errorOccurred()) {
    $biblioQ->close();
    displayErrorPage($biblioQ);
  }
  if (!$biblioQ->doQuery(OBIB_STATUS_OUT,$mbrid)) {
    $biblioQ->close();
    displayErrorPage($biblioQ);
  }
  if ($biblioQ->getRowCount() == 0) {
?>
  <tr>
    <td class="primary" align="center" colspan="8">
      No Bibliographies are currently Checked Out
    </td>
  </tr>
<?php
  } else {
    while ($biblio = $biblioQ->fetchRow()) {
?>
  <tr>
    <td class="primary" valign="top" nowrap="yes">
      <?php echo H($biblio->getStatusBeginDt());?>
    </td>
    <td class="primary" valign="top">
      <img src="../images/<?php echo HURL($materialImageFiles[$biblio->getMaterialCd()]);?>" width="20" height="20" border="0" align="middle" alt="<?php echo H($materialTypeDm[$biblio->getMaterialCd()]);?>">
      <?php echo H($materialTypeDm[$biblio->getMaterialCd()]);?>
    </td>
    <td class="primary" valign="top" >
      <?php echo H($biblio->getBarcodeNmbr());?>
    </td>
    <td class="primary" valign="top" >
      <?php echo H($biblio->getTitle());?></a>
    </td>
    <td class="primary" valign="top" >
      <?php echo H($biblio->getAuthor());?>
    </td>
    <td class="primary" valign="top" nowrap="yes">
      <?php echo H($biblio->getDueBackDt());?>
    </td>
    <td class="primary" valign="top" >
      <?php echo H($biblio->getDaysLate());?>
    </td>
  </tr>
<?php
    }
  }
  $biblioQ->close();
?>

</table>

<br>

<h1>Bibliographies Currently On Hold</h1>
<table class="primary_index">
  <tr>
    <!--<th valign="top" nowrap="yes" align="left">
      <?php echo $loc->getText("mbrViewHoldHdr1"); ?>
    </th>!-->
    <th valign="top" nowrap="yes" align="left">
      Placed On Hold
    </th>
    <th valign="top" nowrap="yes" align="left">Material</th>
    <th valign="top" nowrap="yes" align="left">Barcode</th>
    <th valign="top" nowrap="yes" align="left">Title</th>
    <th valign="top" nowrap="yes" align="left">
      Author
    </th>
    <th valign="top" align="left">
      Status
    </th>
    <th valign="top" align="left">
      Due Date
    </th>
  </tr>
<?php
  #****************************************************************************
  #*  Search database for BiblioHold data
  #****************************************************************************
  $holdQ = new BiblioHoldQuery();
  $holdQ->connect();
  if ($holdQ->errorOccurred()) {
    $holdQ->close();
    displayErrorPage($holdQ);
  }
  if (!$holdQ->queryByMbrid($mbrid)) {
    $holdQ->close();
    displayErrorPage($holdQ);
  }
  if ($holdQ->getRowCount() == 0) {
?>
  <tr>
    <td class="primary" align="center" colspan="8">No Bibliographies are currently On Hold</td>
  </tr>
<?php
  } else {
    while ($hold = $holdQ->fetchRow()) {
?>
  <tr>
    <td class="primary" valign="top" nowrap="yes">
      <?php echo H($hold->getHoldBeginDt());?>
    </td>
    <td class="primary" valign="top">
      <img src="../images/<?php echo HURL($materialImageFiles[$hold->getMaterialCd()]);?>" width="20" height="20" border="0" align="middle" alt="<?php echo H($materialTypeDm[$hold->getMaterialCd()]);?>">
      <?php echo H($materialTypeDm[$hold->getMaterialCd()]);?>
    </td>
    <td class="primary" valign="top" >
      <?php echo H($hold->getBarcodeNmbr());?>
    </td>
    <td class="primary" valign="top" >
      <?php echo H($hold->getTitle());?></a>
    </td>
    <td class="primary" valign="top" >
      <?php echo H($hold->getAuthor());?>
    </td>
    <td class="primary" valign="top" >
      <?php echo H($biblioStatusDm[$hold->getStatusCd()]);?>
    </td>
    <td class="primary" valign="top" >
      <?php echo H($hold->getDueBackDt());?>
    </td>
  </tr>
<?php
    }
  }
  $holdQ->close();
?>


</table>

<br>

<table class="primary_index">
  <tr><td class="noborder" valign="top">
  <br>
<table class="primary">
  <tr>
    <th align="left" colspan="2" nowrap="yes">
      Member Information
    </th>
  </tr>
  <tr>
    <td nowrap="true" class="primary" valign="top">
      Name
    </td>
    <td valign="top" class="primary">
      <?php echo H($mbr->getLastName());?>, <?php echo H($mbr->getFirstName());?>
    </td>
  </tr>
  <tr>
    <td class="primary" valign="top">
      Card Number
    </td>
    <td valign="top" class="primary">
      <?php echo H($mbr->getBarcodeNmbr());?>
    </td>
  </tr>
  <tr>
    <td class="primary" valign="top">
      Classification
    </td>
    <td valign="top" class="primary">
      <?php echo H($mbrClassifyDm[$mbr->getClassification()]);?>
    </td>
  </tr>
  <tr>
    <td class="primary" valign="top">
      Email
    </td>
    <td valign="top" class="primary">
      <?php echo H($mbr->getEmail());?>
    </td>
  </tr>
<?php
  foreach ($memberFieldsDm as $name => $title) {
    if (($value = $mbr->getCustom($name))) {
?>
  <tr>
    <td class="primary" valign="top">
      <?php echo H($title); ?>
    </td>
    <td valign="top" class="primary">
      <?php echo H($value);?>
    </td>
  </tr>
<?php
    }
  }
?>
</table>

  </td>
  <td class="noborder" valign="top">

<?php
  #****************************************************************************
  #*  Show checkout stats
  #****************************************************************************
  $dmQ = new DmQuery();
  $dmQ->connect();
  $dms = $dmQ->getCheckoutStats($mbr->getMbrid());
  $dmQ->close();
?>
Checkout Stats:
<table class="primary_index">
  <tr>
    <th align="left" rowspan="2">
      Materials
    </th>
    <th align="left" rowspan="2">
      Count
    </th>
    <th align="center" colspan="2" nowrap="yes">Limits</th>
  </tr>
  <tr>
    <th align="left">Checkout</th>
    <th align="left">Renewal</th>
  </tr>
<?php
  foreach ($dms as $dm) {
?>
  <tr>
    <td nowrap="true" class="primary" valign="top">
      <?php echo H($dm->getDescription()); ?>
    </td>
    <td valign="top" class="primary">
      <?php echo H($dm->getCount()); ?>
    </td>
    <td valign="top" class="primary">
      <?php echo H($dm->getCheckoutLimit()); ?>
    </td>
    <td valign="top" class="primary">
      <?php echo H($dm->getRenewalLimit()); ?>
    </td>
  </tr>
<?php
  }
?>
  </table>
</td></tr></table>
<br />
