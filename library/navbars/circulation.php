<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../classes/Localize.php");
  $navloc = new Localize(OBIB_LOCALE,"navbars");


	if ($_SESSION[CORE_U_CODE]['library']["hasCircAuth"]!='N' || $_SESSION[CORE_U_CODE]['library']["hasCircMbrAuth"]!='N') 
		{	
?>
<div id="sidebar">  
<div id="accordion">
<p <?=$nav=="searchform"?'class="active"':''?>><a href="../circ/index.php"><?=$navloc->getText("memberSearch")?></p>
<?php
if($nav=='search')
{
?>
<p class="active-sub"><a href="#"><?=$navloc->getText("catalogResults")?></p>
<?php 
} 
else if($nav=="view"||$nav=="edit"||$nav=="hist"||$nav=="account")
{
?>
<p class="active-sub"><a href="../circ/mbr_view.php?mbrid=<?=HURL($mbrid)?>&reset=Y"><?=$navloc->getText("memberInfo")?></p>
<p class="active-sub"><a href="../circ/mbr_edit_form.php?mbrid=<?=HURL($mbrid)?>"><?=$navloc->getText("editInfo")?></p>
<p class="active-sub"><a href="../circ/mbr_account.php?mbrid=<?=HURL($mbrid)?>&reset=Y"><?=$navloc->getText("account")?></p>
<p class="active-sub"><a href="../circ/mbr_history.php?mbrid=<?=HURL($mbrid)?>"><?=$navloc->getText("checkoutHistory")?></p>
<?php } ?>
<p <?=$nav=="checkin"?'class="active"':''?>><a href="../circ/checkin_form.php?reset=Y"><?=$navloc->getText("checkIn")?></p>
</div>
</div>
<?php
}
?>