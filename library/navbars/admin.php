<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../classes/Localize.php");
  $navLoc = new Localize(OBIB_LOCALE,"navbars");


	if ($_SESSION[CORE_U_CODE]['library']["hasAdminAuth"]!='N')
	{
?>
<div id="sidebar">  
<div id="accordion">
<p <?=$nav=="summary"?'class="active"':''?>><a href="../admin/index.php"><?=$navLoc->getText("adminSummary")?></p>
<p <?=$nav=="staff"?'class="active"':''?>><a href="../admin/staff_list.php"><?=$navLoc->getText("adminStaff")?></p>
<p <?=$nav=="settings"?'class="active"':''?>><a href="../admin/settings_edit_form.php?reset=Y"><?=$navLoc->getText("adminSettings")?></p>
<p <?=$nav=="classifications"?'class="active"':''?>><a href="../admin/mbr_classify_list.php"><?=$navLoc->getText("Member Types")?></a></p>
<!--<p <?=$nav=="member_fields"?'class="active"':''?>><a href="../admin/member_fields_list.php"><?=$navLoc->getText("Member Fields ")?></p>!-->
<p <?=$nav=="materials"?'class="active"':''?>><a href="../admin/materials_list.php"><?=$navLoc->getText("adminMaterialTypes")?></p>
<p <?=$nav=="collections"?'class="active"':''?>><a href="../admin/collections_list.php"><?=$navLoc->getText("adminCollections")?></p>
<p <?=$nav=="checkout_privs"?'class="active"':''?>><a href="../admin/checkout_privs_list.php" ><?=$navLoc->getText("Checkout Privs")?></p>
</div>
</div>
<?php
}
?>