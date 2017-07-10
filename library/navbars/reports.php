<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../classes/Localize.php");
  $navLoc = new Localize(OBIB_LOCALE,"navbars");


 if (isset($_SESSION["userid"])) {
   $sess_userid = $_SESSION["userid"];
 } else {
   $sess_userid = "";
 }


if ($_SESSION[CORE_U_CODE]['library']["hasReportsAuth"]!='N') 
{

Nav::node('reportlist', 'Report List', '../reports/index.php');
if (isset($_SESSION['rpt_Report'])) {
  Nav::node('results', "Report Results",
           '../reports/run_report.php?type=previous');
}

Nav::display("$nav");
}
?>

