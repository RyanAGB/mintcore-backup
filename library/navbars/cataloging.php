<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../classes/Localize.php");
  $navLoc = new Localize(OBIB_LOCALE,"navbars");


	if ($_SESSION[CORE_U_CODE]['library']["hasCatalogAuth"]!='N')
	{
?>
<div id="sidebar">  
<div id="accordion">
<p <?=$nav=="searchform"?'class="active"':''?>><a href="../catalog/index.php"><?=$navLoc->getText("catalogSearch2")?></p>
<?php
if($nav=='search')
{
?>
<p class="active-sub"><a href="#"><?=$navLoc->getText("catalogResults")?></p>
<?php 
} 
else if($nav=="view"||$nav=="editcopy"||$nav=="edit"||$nav=="editmarc"||$nav=="newmarc"||$nav=="editmarcfield"||$nav=="holds"||$nav=="delete")
{
?>
<p class="active-sub"><a href="../shared/biblio_view.php?bibid=<?=HURL($bibid)?>"><?=$navLoc->getText("catalogBibInfo")?></p>
<p class="active-sub"><a href="../catalog/biblio_edit.php?bibid=<?=HURL($bibid)?>"><?=$navLoc->getText("catalogBibEdit")?></p>
<p class="active-sub"><a href="../catalog/biblio_marc_list.php?bibid=<?=HURL($bibid)?>"><?=$navLoc->getText("catalogBibEditMarc");?></p>
<p class="active-sub"><a href="../catalog/biblio_copy_new_form.php?bibid=<?=HURL($bibid)?>&reset=Y"><?=$navLoc->getText("catalogCopyNew")?></p>
<p class="active-sub"><a href="../catalog/biblio_hold_list.php?bibid=<?=HURL($bibid)?>"><?=$navLoc->getText("catalogHolds")?></p>
<p class="active-sub"><a href="../catalog/biblio_del_confirm.php?bibid=<?=HURL($bibid)?>"><?=$navLoc->getText("catalogDelete");?></p>
<p class="active-sub"><a href="../catalog/biblio_new_like.php?bibid=<?=HURL($bibid)?>"><?php echo $navLoc->getText("catalogBibNewLike");?></p>
<?php } ?>
<p <?=$nav=="new"?'class="active"':''?>><a href="../catalog/biblio_new.php"><?=$navLoc->getText("catalogBibNew")?></p>
<p <?=$nav=="upload_usmarc"?'class="active"':''?>><a href="../catalog/upload_usmarc_form.php"><?=$navLoc->getText("Upload Marc Data")?></p>
</div>
</div>
<?php
}
?>