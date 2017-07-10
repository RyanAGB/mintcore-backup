<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
  require_once("../classes/Localize.php");
  $headerLoc = new Localize(OBIB_LOCALE,"shared");

// code html tag with language attribute if specified.
echo "<html";
if (OBIB_HTML_LANG_ATTR != "") {
  echo " lang=\"".H(OBIB_HTML_LANG_ATTR)."\"";
}
echo ">\n";

// code character set if specified
if (OBIB_CHARSET != "") { ?>
<META http-equiv="content-type" content="text/html; charset=<?php echo H(OBIB_CHARSET); ?>">
<?php } ?>

<head>
<style type="text/css">
  <?php include("../css/style.php");?>
</style>
<meta name="description" content="OpenBiblio Library Automation System">
<title><?php echo H(OBIB_LIBRARY_NAME);?></title>

<script language="JavaScript">
<!--
function popSecondary(url) {
    var SecondaryWin;
    SecondaryWin = window.open(url,"secondary","resizable=yes,scrollbars=yes,width=535,height=400");
    self.name="main";
}
function popSecondaryLarge(url) {
    var SecondaryWin;
    SecondaryWin = window.open(url,"secondary","toolbar=yes,resizable=yes,scrollbars=yes,width=850,height=500");
    self.name="main";
}
function backToMain(URL) {
    var mainWin;
    mainWin = window.open(URL,"main");
    mainWin.focus();
    this.close();
}
-->
</script>


</head>
<body bgcolor="<?php echo H(OBIB_PRIMARY_BG);?>" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0" <?php
  if (isset($focus_form_name) && ($focus_form_name != "")) {
    if (ereg('^[a-zA-Z0-9_]+$', $focus_form_name)
        && ereg('^[a-zA-Z0-9_]+$', $focus_form_field)) {
      echo 'onLoad="self.focus();document.'.$focus_form_name.".".$focus_form_field.'.focus()"';
    }
  } ?> >

<!-- **************************************************************************************
     * Library Name and hours
     **************************************************************************************-->
    <table class="primary" width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr><td> 
     <div id="header">
        <div class="inner-container clearfix">
            <h1 id="logo">
                
                    <!--<img src="../../includes/getimage.php?id=1" alt="" />MODIFIED BY:TINE<!-- your title -->
                    <img src="../../images/mint_header_logo.png" alt="" />
                    <span class="ir"></span>
                <br />

            </h1>
            <div id="userbox">
                <div class="inner">
                	
                    <strong>Hello <?=USER_FIRST_NAME . ' ' . USER_LAST_NAME?></strong><br />
                    <?php
					if(USER_LAST_LOGIN != '')
					{
					?>
                    	<span>Last login: <?=date('D F d, Y',USER_LAST_LOGIN)?></span>
                    <?php
					}
					else
					{
					?>
                    	<span>Last login: <?=date('D F d, Y')?></span>
                    <?php
					}
					?>
                    
                      <br/>
                  	<?php
					  if(USER_IS_LOGGED == '1')
						{
						?>
							<span><a href="../../index.php?comp=com_logout" onClick="return confirm('Are you sure you want to logout?')">logout</a></span>
                    <?php    
                    }
                    ?>
                        <!--
                        <li>|&nbsp;<a href="#">settings</a></li>
                        -->
                    </ul>
                    
                </div>
            </div><!-- #userbox -->
        </div><!-- .inner-container -->
    
</div><!-- #header -->
</td></tr>
<tr><td>
<div id="nav">
   <div class="inner-container">

    <span id="inner"><a href="../home/index.php"><span><?=$headerLoc->getText("headerHome")?></span></a></span>
<span id="inner"><a href="../circ/index.php"><span><?=$headerLoc->getText("headerCirculation")?></span></a></span>
<span id="inner"><a href="../catalog/index.php"><span><?=$headerLoc->getText("headerCataloging")?></span></a></span>
<?php if(ACCESS_ID==1){?>
<span id="inner"><a href="../admin/index.php"><span><?=$headerLoc->getText("headerAdmin")?></span></a></span>
<?php }else{ }?>
<span id="inner"><a href="../reports/index.php"><span><?=$headerLoc->getText("headerReports")?></span></a></span>
<?php 
					  if (OBIB_OPAC_URL != "") { ?>
						<span id="inner"><a href="<?=H(OBIB_OPAC_URL)?>"><?=$headerLoc->getText("footerOPAC")?></a></span>
					  <?php } ?>
                      
                  	<?php
					  if(USER_IS_LOGGED == '1')
						{
						?>
						 <span id="inner"><a href="../../index.php">CORE</a></span>
                    <?php    
                    }
                    ?>
    
    </span>
    </div>
    </div>
</div><!-- #nav --> 
</td></tr>
</table>
     
<!--<table class="primary" width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr bgcolor="<?php echo H(OBIB_TITLE_BG);?>">
    <td width="100%" class="title" valign="top">
    <div style="padding:8px">
    	<img src="../../includes/getimage.php?id=1" alt="" /></br>
        <?=H(OBIB_LIBRARY_NAME)?>
    </div>
       <?php /*
         if (OBIB_LIBRARY_IMAGE_URL != "") {
           echo "<img align=\"middle\" src=\"".H(OBIB_LIBRARY_IMAGE_URL)."\" border=\"0\">";
         }
         if (!OBIB_LIBRARY_USE_IMAGE_ONLY) {
           echo " ".H(OBIB_LIBRARY_NAME);
         }*/
       ?>
    </td>
    <td valign="top">
    <div style="padding:20px;">
      <table class="primary" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td class="title" nowrap="yes"><font class="small"><?php echo $headerLoc->getText("headerTodaysDate"); ?></font></td>
          <td class="title" nowrap="yes"><font class="small"><?php echo H(date($headerLoc->getText("headerDateFormat")));?></font></td>
        </tr>
        <tr>
          <td class="title" nowrap="yes"><font class="small"><?php if (OBIB_LIBRARY_HOURS != "") echo $headerLoc->getText("headerLibraryHours");?></font></td>
          <td class="title" nowrap="yes"><font class="small"><?php if (OBIB_LIBRARY_HOURS != "") echo H(OBIB_LIBRARY_HOURS);?></font></td>
        </tr>
        <tr>
          <td class="title" nowrap="yes"><font class="small"><?php if (OBIB_LIBRARY_PHONE != "") echo $headerLoc->getText("headerLibraryPhone");?></font></td>
          <td class="title" nowrap="yes"><font class="small"><?php if (OBIB_LIBRARY_PHONE != "") echo H(OBIB_LIBRARY_PHONE);?></font></td>
        </tr>
        <tr>
          <td class="title" nowrap="yes"><font class="small"><?php if (OBIB_LIBRARY_URL != "") { ?>
    <a href="<?php echo H(OBIB_LIBRARY_URL);?>"><?php echo $headerLoc->getText("footerLibraryHome"); ?></a> |
  <?php }
  if (OBIB_OPAC_URL != "") { ?>
    <a href="<?php echo H(OBIB_OPAC_URL);?>"><?php echo $headerLoc->getText("footerOPAC"); ?></a> |
  <?php } 
  if(USER_IS_LOGGED == '1')
	{
	?>
		 <a href="../../index.php">CORE</a>
         |
    <?php    
	}
	?>
  <a href="javascript:popSecondary('../shared/help.php<?php if (isset($helpPage)) echo "?page=".H(addslashes(U($helpPage))); ?>')"><?php echo $headerLoc->getText("footerHelp"); ?></a>
    </td>
    <tr><td>&nbsp;</td></tr>
        </tr>
      </table>
      </div>
    </td>
  </tr>
</table>
 **************************************************************************************
     * Tabs
     **************************************************************************************-->
<!--<table class="primary" width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td bgcolor="<?php echo H(OBIB_TITLE_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_TITLE_BG);?>" colspan="3"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_TITLE_BG);?>" colspan="3"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_TITLE_BG);?>" colspan="3"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_TITLE_BG);?>" colspan="3"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_TITLE_BG);?>" colspan="3"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
  </tr>
  <tr bgcolor="<?php echo H(OBIB_TITLE_BG);?>">
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>

    <?php if ($tab == "home") { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } else { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } ?>

    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_TITLE_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>

    <?php if ($tab == "circulation") { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } else { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } ?>

    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_TITLE_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>

    <?php if ($tab == "cataloging") { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } else { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } ?>

    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_TITLE_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>

    <?php if ($tab == "admin") { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } else { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } ?>

    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_TITLE_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>

    <?php if ($tab == "reports") { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } else { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } ?>

    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_TITLE_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td width="2000" bgcolor="<?php echo H(OBIB_TITLE_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>

  </tr>
  <tr bgcolor="<?php echo H(OBIB_TITLE_BG);?>">
    <?php if ($tab == "home") { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
      <td class="tab1" nowrap="yes"> <?php echo $headerLoc->getText("headerHome"); ?></td>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } else { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
      <td class="tab2" nowrap="yes"> <a href="../home/index.php" class="tab"><?php echo $headerLoc->getText("headerHome"); ?></a> </td>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } ?>

    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>

    <?php if ($tab == "circulation") { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
      <td class="tab1" nowrap="yes"> <?php echo $headerLoc->getText("headerCirculation"); ?></td>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } else { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
      <td class="tab2" nowrap="yes"> <a href="../circ/index.php" class="tab"><?php echo $headerLoc->getText("headerCirculation"); ?></a> </td>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } ?>

    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>

    <?php if ($tab == "cataloging") { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
      <td class="tab1" nowrap="yes"> <?php echo $headerLoc->getText("headerCataloging"); ?></td>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } else { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
      <td class="tab2" nowrap="yes"> <a href="../catalog/index.php" class="tab"><?php echo $headerLoc->getText("headerCataloging"); ?></a> </td>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } ?>

    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>

    <?php if ($tab == "admin") { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
      <td class="tab1" nowrap="yes"> <?php echo $headerLoc->getText("headerAdmin"); ?></td>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } else { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
      <td class="tab2" nowrap="yes"> <a href="../admin/index.php" class="tab"><?php echo $headerLoc->getText("headerAdmin"); ?></a> </td>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } ?>

    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>

    <?php if ($tab == "reports") { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
      <td class="tab1" nowrap="yes"> <?php echo $headerLoc->getText("headerReports"); ?></td>
      <td  bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } else { ?>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
      <td class="tab2" nowrap="yes"> <a href="../reports/index.php" class="tab"><?php echo $headerLoc->getText("headerReports"); ?></a> </td>
      <td  bgcolor="<?php echo H(OBIB_ALT2_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <?php } ?>

    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td width="2000" bgcolor="<?php echo H(OBIB_TITLE_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>

  </tr>
  <tr bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>">
    <td colspan="3" <?php if ($tab == "home") { echo " bgcolor='".H(OBIB_ALT1_BG)."'"; } ?>><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td colspan="3" <?php if ($tab == "circulation") { echo " bgcolor='".H(OBIB_ALT1_BG)."'"; } ?>><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td colspan="3" <?php if ($tab == "cataloging") { echo " bgcolor='".H(OBIB_ALT1_BG)."'"; } ?>><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td colspan="3" <?php if ($tab == "admin") { echo " bgcolor='".H(OBIB_ALT1_BG)."'"; } ?>><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td colspan="3" <?php if ($tab == "reports") { echo " bgcolor='".H(OBIB_ALT1_BG)."'"; } ?>><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td><img src="../images/shim.gif" width="1" height="1" border="0"></td>
  </tr>

<tr>
<td>
<ul class="tabs">
<p <?=$tab == "home"?'class="active"':''?>><a href="../home/index.php"><span><?=$headerLoc->getText("headerHome")?></span></a></p>
<p <?=$tab == "circulation"?'class="active"':''?>><a href="../circ/index.php"><span><?=$headerLoc->getText("headerCirculation")?></span></a></p>
<p <?=$tab == "cataloging"?'class="active"':''?>><a href="../catalog/index.php"><span><?=$headerLoc->getText("headerCataloging")?></span></a></p>
<p <?=$tab == "admin"?'class="active"':''?>><a href="../admin/index.php"><span><?=$headerLoc->getText("headerAdmin")?></span></a></p>
<p <?=$tab == "reports"?'class="active"':''?>><a href="../reports/index.php"><span><?=$headerLoc->getText("headerReports")?></span></a></p>
</ul>
</td>
</tr>
</table>!-->