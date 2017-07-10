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
?>
<div id="sidebar">  
<div id="accordion">
<p <?=$nav=="home"?'class="active"':''?>><a href="../home/index.php"><?=$navLoc->getText("homeHomeLink")?></p>
<!--<p <?=$nav=="license"?'class="active"':''?>><a href="../home/license.php"><?=$navLoc->getText("homeLicenseLink")?></p>!-->
</div>
</div>