<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../classes/Localize.php");
  $navLoc = new Localize(OBIB_LOCALE,"navbars");
  
  require_once("../../includes/functions.php");
  require_once("../../includes/common.php");
?>

<div id="sidebar">  
<div id="accordion">
<?php
if($nav=='home')
{
?>
<p class="active"><a href="../opac/index.php"><?php echo $navLoc->getText("catalogSearch2"); ?></p>
<?php
}
if($nav=='pending')
{
?>
<p class="active"><a href="../opac/mbr_info.php">Member Status</a></p>
<?php
}
if($nav=='search')
{
?>
<p class="active-sub"><a href="#"><?=$navLoc->getText("catalogResults")?></p>
<?php 
} 
else if($nav=="view")
{
?>
<p class="active-sub"><a href="#"><?=$navLoc->getText("catalogBibInfo")?></p>
<?php
}
?>
</div>
</div>