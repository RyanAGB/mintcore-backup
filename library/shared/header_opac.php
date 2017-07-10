<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 require_once("../../config.php");
  require_once("../../includes/functions.php");
  require_once("../../includes/common.php");
 
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
}
function returnLookup(formName,fieldName,val) {
    window.opener.document.forms[formName].elements[fieldName].value=val;
    window.opener.focus();
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
                
                   <img src="../../images/mint_header_logo.png" alt="" /><!-- your title -->
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
					  if(USER_IS_LOGGED == '1'&&!isset($_GET['lookup']))
						{
						?>
							<a href="../../index.php?comp=com_logout" onClick="return confirm('Are you sure you want to logout?')">logout</a>
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
<tr><td><div id="nav">
	<div class="inner-container">
    		<?php
			if(!isset($_GET['lookup']))
			{
			?>
    		<span id="inner"><a href="../opac/index.php">Search</a></span>
    	<?php
			}
			if(ACCESS_ID==6&&!isset($_GET['lookup']))
			{
			?>
				<span id="inner"><a href="../opac/mbr_info.php">Member</a></span>
			<?php
			}
					 if(ACCESS_ID == '1'&&!isset($_GET['lookup'])) {?>
						<span id="inner"><a href="../index.php">Library</a></span>
					  <?php } 
					  
					  if(USER_IS_LOGGED == '1'&&!isset($_GET['lookup']))
						{
						?>
						 <span id="inner"><a href="../../index.php">CORE</a></span>
                    <?php    
                    }
                    ?>
    
    </div>
</div><!-- #nav --> 
</td></tr>
</table>

	<!-- **************************************************************************************
     * Left nav
     **************************************************************************************-->
<table height="100%" width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr bgcolor="<?php echo H(OBIB_ALT1_BG);?>">
    <td colspan="6"><img src="../images/shim.gif" width="1" height="15" border="0"></td>
  </tr>
  <tr>
    <td bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="10" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="140" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="10" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="10" height="1" border="0"></td>
  </tr>
  <tr>
    <td bgcolor="<?php echo H(OBIB_ALT1_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td valign="top" bgcolor="<?php echo H(OBIB_ALT1_BG);?>">
      <font  class="alt1">
      <?php include("../navbars/opac.php"); ?>
      </font>
    <br><br><br><br>
    </td>
    <td bgcolor="<?php echo H(OBIB_BORDER_COLOR);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td bgcolor="<?php echo H(OBIB_PRIMARY_BG);?>"><img src="../images/shim.gif" width="1" height="1" border="0"></td>
    <td height="100%" width="100%" valign="top">
      <font class="primary">
      <br>
<!-- **************************************************************************************
     * beginning of main body
     **************************************************************************************-->
