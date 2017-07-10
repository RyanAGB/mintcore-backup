/*********************************************************
 *  Body Style
 *********************************************************/
body {
  background-color: #efefef;
	font: normal normal 62.5%/1 'Lucida Grande', Arial, Helvetica, sans-serif;
	background:url(../../images/bg-body.jpg) #efefef repeat-x;
	color:#333333;

}

/*********************************************************
 *  Font Styles
 *********************************************************/
font.primary {
  color: <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
}
font.alt1 {
  color: <?php echo H(OBIB_ALT1_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_ALT1_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_ALT1_FONT_FACE);?>;
}
font.alt1tab {
  color: <?php echo H(OBIB_ALT1_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_ALT2_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_ALT2_FONT_FACE);?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
}
font.alt2 {
  color: <?php echo H(OBIB_ALT2_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_ALT2_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_ALT2_FONT_FACE);?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
}
font.error {
  color: <?php echo H(OBIB_PRIMARY_ERROR_COLOR);?>;
  font-size: <?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  font-weight: bold;
}
font.small {
  font-size: 10px;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
}
a.nav {
  color: <?php echo H(OBIB_ALT1_FONT_COLOR);?>;
  font-size: 10px;
  font-family: <?php echo H(OBIB_ALT1_FONT_FACE);?>;
  text-decoration: none;
  background-color: <?php echo H(OBIB_ALT1_BG);?>;
  border-style: solid;
  border-color: <?php echo H(OBIB_BORDER_COLOR);?>;
  border-width: <?php echo H(OBIB_BORDER_WIDTH);?>
}
h1 {
  font-size: 16px;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  font-weight: bold;
  color:#566a97;
}

/*********************************************************
 *  Link Styles
 *********************************************************/
a:link {
  color: <?php echo H(OBIB_PRIMARY_LINK_COLOR);?>;
}
a:visited {
  color: <?php echo H(OBIB_PRIMARY_LINK_COLOR);?>;
}
a.primary:link {
  color: <?php echo H(OBIB_PRIMARY_LINK_COLOR);?>;
}
a.primary:visited {
  color: <?php echo H(OBIB_PRIMARY_LINK_COLOR);?>;
}
a.alt1:link {
  color: <?php echo H(OBIB_ALT1_LINK_COLOR);?>;
}
a.alt1:visited {
  color: <?php echo H(OBIB_ALT1_LINK_COLOR);?>;
}
a.alt2:link {
  color: <?php echo H(OBIB_ALT2_LINK_COLOR);?>;
}
a.alt2:visited {
  color: <?php echo H(OBIB_ALT2_LINK_COLOR);?>;
}
a.tab:link {
  color: <?php echo H(OBIB_ALT2_LINK_COLOR);?>;
  font-size: <?php echo H(OBIB_ALT2_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_ALT2_FONT_FACE);?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
  text-decoration: none;
}
a.tab:visited {
  color: <?php echo H(OBIB_ALT2_LINK_COLOR);?>;
  font-size: <?php echo H(OBIB_ALT2_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_ALT2_FONT_FACE);?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
  text-decoration: none
}
a.tab:hover {text-decoration: underline}

/*********************************************************
 *  Table Styles
 *********************************************************/
table.primary {
  border-collapse: collapse
}
table.border {
  border-style: solid;
  border-color: <?php echo H(OBIB_BORDER_COLOR);?>;
  border-width: <?php echo H(OBIB_BORDER_WIDTH);?>
}
th {
  background-color: <?php echo H(OBIB_ALT2_BG);?>;
  color: <?php echo H(OBIB_ALT2_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_ALT2_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_ALT2_FONT_FACE);?>;
  padding: <?php echo H(OBIB_PADDING);?>;
  border-style: solid;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
  border-color: <?php echo H(OBIB_BORDER_COLOR);?>;
  border-width: <?php echo H(OBIB_BORDER_WIDTH);?>;
  height: 1
}
th.rpt {
  background-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  color: <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
  font-size: <?php echo (OBIB_PRIMARY_FONT_SIZE - 2);?>px;
  font-family: Arial;
  font-weight: bold;
  padding: <?php echo H(OBIB_PADDING);?>;
  border-style: solid;
  border-color: <?php echo H(OBIB_BORDER_COLOR);?>;
  border-width: 1;
  text-align: left;
  vertical-align: bottom;
}
td.primary {
  background-color: white;
  color: <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  padding: <?php echo H(OBIB_PADDING);?>;
  border-style: solid;
  border-color: <?php echo H(OBIB_BORDER_COLOR);?>;
  border-width: <?php echo H(OBIB_BORDER_WIDTH);?>
}
td.rpt_param {
  background-color: white;
  color: <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
  font-size: <?php echo (OBIB_PRIMARY_FONT_SIZE - 2);?>px;
  font-family: Arial;
  padding: <?php echo H(OBIB_PADDING);?>;
  border-top-style: none;
  border-bottom-style: none;
  border-left-style: solid;
  border-left-color: <?php echo H(OBIB_BORDER_COLOR);?>;
  border-left-width: 1;
  border-right-style: solid;
  border-right-color: <?php echo H(OBIB_BORDER_COLOR);?>;
  border-right-width: 1;
  text-align: left;
  vertical-align: top;
}
td.primaryNoWrap {
  background-color: white;
  color: <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  padding: <?php echo H(OBIB_PADDING);?>;
  border-style: solid;
  border-color: <?php echo H(OBIB_BORDER_COLOR);?>;
  border-width: <?php echo H(OBIB_BORDER_WIDTH);?>;
  white-space: nowrap
}

td.title {
  background-color: <?php echo H(OBIB_TITLE_BG);?>;
  color: <?php echo H(OBIB_TITLE_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_TITLE_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_TITLE_FONT_FACE);?>;
  padding: <?php echo H(OBIB_PADDING);?>;
<?php if (OBIB_TITLE_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
  border-color: <?php echo H(OBIB_BORDER_COLOR);?>;
  border-width: <?php echo H(OBIB_BORDER_WIDTH);?>;
  text-align: <?php echo H(OBIB_TITLE_ALIGN);;?>
}
td.alt1 {
  background-color: <?php echo H(OBIB_ALT1_BG);?>;
  color: <?php echo H(OBIB_ALT1_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_ALT1_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_ALT1_FONT_FACE);?>;
  padding: <?php echo H(OBIB_PADDING);?>;
  border-style: solid;
  border-color: <?php echo H(OBIB_BORDER_COLOR);?>;
  border-width: <?php echo H(OBIB_BORDER_WIDTH);?>
}

.tabs	{
	float: left;
	margin-top: -46px;
	padding-left: 10px;
}
.tabs p	{
	float: left;
	padding-left: 0px;
}
.tabs p a, .tabs p a:visited	{
	color: #fff;
	float: left;
    font-family: <?php echo H(OBIB_ALT1_FONT_FACE);?>;
    text-decoration:none;
	display: block;
	font-size: 12px;
	background: #b6b6b6 url(../../images/bg_tabs_a.png) no-repeat left top;
}
.tabs p.active a, .tabs p.active a:visited	{
	text-decoration:none;
	color: #566a97;
	background:  url(../../images/bg_tabs_a.png) no-repeat left bottom;
}
.tabs p a span, .tabs p a:visited span	{
	float: left;
	height: 20px;
	display: block;
	cursor: pointer;
	font-weight: bold;
	padding: 10px 10px 0 10px;
	background: url(../../images/bg_tabs_span.png) no-repeat right top;
}
.tabs p.active a span, .tabs p.active a:visited span	{
	background: url(../../images/bg_tabs_span.png) no-repeat right bottom;
}
.tabs p a:hover	{
	color: #6c6c6c;
}


#sidebar #accordion p a{
	margin: 0;
    text-decoration:none;
}


#sidebar #accordion p a:hover{
	color:#666666;
    text-decoration:none;
    background: url(../../images/arrow_right.png) no-repeat 0px 5px;
	padding:3px 0 0 10px;
}

#sidebar #accordion p.active a{
	color:#666666;
    text-decoration:none;
    background: url(../../images/arrow_right.png) no-repeat 0px 5px;
	padding:3px 0 0 10px;
}
.primary_index { width:580px; border-collapse:collapse; font-size:12px; font-family: <?php echo H(OBIB_ALT1_FONT_FACE);?>;}
.primary_index { border: 1px solid #ccc; padding:5px; font-weight:bold;}
.primary_index tr.highlight	{ background: #f2f2f2 url(../../images/bg_td_highlight.png) repeat-x left top; }
.primary_index td{ border: 1px solid #ccc; padding:5px;}
.primary_index td a:hover{color:#566a97;}
.primary_index td.remarks	{ color:#FF0000; font-weight:bold }

td.tab1 {
  background-color: white;
  color: <?php echo H(OBIB_ALT1_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_ALT1_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_ALT2_FONT_FACE);?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
  padding: <?php echo H(OBIB_PADDING);?>;
}
td.tab2 {
  background-color: white;
  color: <?php echo H(OBIB_ALT2_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_ALT2_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_ALT2_FONT_FACE);?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
  padding: <?php echo H(OBIB_PADDING);?>;
}
td.noborder {
  background-color: white;
  color: <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  padding: <?php echo H(OBIB_PADDING);?>;
}
/*********************************************************
 *  Form Styles
 *********************************************************/
input.button {
  background-color: <?php echo H(OBIB_ALT1_BG);?>;
  border-color: <?php echo H(OBIB_ALT1_BG);?>;
  border-left-color: <?php echo H(OBIB_ALT1_BG);?>;
  border-top-color: <?php echo H(OBIB_ALT1_BG);?>;
  border-bottom-color: <?php echo H(OBIB_ALT1_BG);?>;
  border-right-color: <?php echo H(OBIB_ALT1_BG);?>;
  padding: <?php echo H(OBIB_PADDING);?>;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  color: <?php echo H(OBIB_ALT1_FONT_COLOR);?>;
}
input.navbutton {
  background-color: <?php echo H(OBIB_ALT2_BG);?>;
  border-color: <?php echo H(OBIB_ALT2_BG);?>;
  border-left-color: <?php echo H(OBIB_ALT2_BG);?>;
  border-top-color: <?php echo H(OBIB_ALT2_BG);?>;
  border-bottom-color: <?php echo H(OBIB_ALT2_BG);?>;
  border-right-color: <?php echo H(OBIB_ALT2_BG);?>;
  padding: <?php echo H(OBIB_PADDING);?>;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  color: <?php echo H(OBIB_ALT2_FONT_COLOR);?>;
}
input {
  background-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-left-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-top-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-bottom-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-right-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  padding: 0px;
  scrollbar-base-color: <?php echo H(OBIB_ALT1_BG);?>;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  color: <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
}
textarea {
  background-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-left-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-top-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-bottom-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-right-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  padding: 0px;
  scrollbar-base-color: <?php echo H(OBIB_ALT1_BG);?>;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  color: <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
  font-size: <?php echo H(OBIB_PRIMARY_FONT_SIZE);?>px;
}
select {
  background-color: white;
  border-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-left-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-top-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-bottom-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  border-right-color: <?php echo H(OBIB_PRIMARY_BG);?>;
  padding: 0px;
  scrollbar-base-color: <?php echo H(OBIB_ALT1_BG);?>;
  font-family: <?php echo H(OBIB_PRIMARY_FONT_FACE);?>;
  color: <?php echo H(OBIB_PRIMARY_FONT_COLOR);?>;
}

<!--ul.nav_main { list-style-type: none; padding-left: 0; margin-left: 0; }
li.nav_selected:before { white-space: pre-wrap; content: "\bb  " }
ul.nav_main li.nav_selected { font-weight: bold }
ul.nav_sub li.nav_selected { font-weight: bold }
ul.nav_main li { font-weight: normal }
ul.nav_sub li { font-weight: normal }

li.report_category { margin-bottom: 1em }
!-->
ul.nav_main, ul.nav_sub{
	margin: 0;
    text-decoration:none;
}

ul.nav_main li.nav_selected a, ul.nav_main li.nav_selected a:visited{
	color: #566a97;
	width: 150px;
	height: 20px;;
	font-size: 13px;
    font-weight:bold;
	float:right;
    text-decoration:none;
	padding:3px 0 0 10px;
}

ul.nav_main a:hover, ul.nav_sub a:hover{
	color:#666666;
    text-decoration:none;
    background: url(../../images/arrow_right.png) no-repeat 0px 5px;
	padding:3px 0 0 10px;
}

table.results {
  width: 100%;
  border-collapse: collapse;
}
table.resultshead {
  width: 100%;
  border-collapse: separate;
  border-top: solid <?php echo OBIB_ALT2_BG;?> 3px;
  border-bottom: solid <?php echo OBIB_ALT2_BG;?> 3px;
  clear: both;
}
table.resultshead th {
  text-align: left;
  color: <?php echo OBIB_PRIMARY_FONT_COLOR;?>;
  border: none;
  background: <?php echo OBIB_PRIMARY_BG;?>;
  font-size: 16px;
  font-weight: bold;
  vertical-align: middle;
  padding: 2px;
}
table.resultshead td {
  text-align: right;
}
table.results td.primary { border-top: none; }



/*						HEADER STYLES						*/

	#header .inner-container {padding: 0px 0 16px 0; background-image:url(../images/header.png); background-repeat:repeat-x; font: normal normal 62.5%/1 'Lucida Grande', Arial, Helvetica, sans-serif;}
	/* logo: set the width and height of your logo (in px) and margin-top depending on height of your logo */
	#header #logo {position: relative; float: left; width: 550px; height: 55px; margin-left: 170px; }
		#header #logo a.home {border: 0; display: block; width: 100%; height: 100%; overflow: hidden; text-shadow: #003f6c 1px 1px 0; font-size: 180%; font-weight: bold; font-style: italic; color: #fff; text-decoration:none;}
		#header #logo a.home .ir {background:url("../../images/logo-login.png");}
		
		#header #logo a.button {position: absolute; left: 100%; top: 3px; margin-left: 20px; padding: 4px 9px 4px; white-space: nowrap;}
	/* box for user info, settings and logout */
	#header #userbox {background: url("../../images/icon-greet-user.gif") no-repeat ; position: relative; float: right; width: 265px; min-height: 65px; line-height: 1.3;}
		#header #userbox .inner {padding: 17px 0px 0 0px;font-size: 10px; font-weight: normal; color: #fff;}
		#header #userbox a#logout {font-size: 120%; font-weight: normal; color: #FFD672;}
		#header #userbox a#logout .ir {background: url("../../images/logout.png");}
		#header #userbox a#logout:hover .ir,
		#header #userbox a#logout:focus .ir,
		#header #userbox a#logout:active .ir {background-position: -23px 0;}
		#header #userbox strong {font-size: 120%; font-weight: normal; color: #fff;}
		#header #userbox span {font-size: 11px; font-weight: normal; color:#333333;}
		#header #userbox a {font-size: 11px; font-weight: normal; color: #ffffff; text-decoration:none;}
		#header #userbox a:hover,
		#header #userbox a:active,
		#header #userbox a:focus {font-size: 11px; font-weight: normal; color: #F6BA27;}
		#header #userbox ul {}
		#header #userbox ul li {display: inherit; float:left}

	#nav {min-height: 30px; height:38px; }
    #nav .inner-container {padding: 10px 17px 95px 170px;font-size: 13px; font-weight: normal; color: #fff; font:'Lucida Grande', Arial, Helvetica, sans-serif; text-decoration:none;}
    #nav #inner a {font-family: <?php echo H(OBIB_ALT1_FONT_FACE);?>; text-decoration:none;padding: 9px 10px 11px 10px; color:white;}
    #nav #inner a:hover { font-family: <?php echo H(OBIB_ALT1_FONT_FACE);?>; text-decoration:none;padding: 9px 10px 11px 10px; background-color:#76c218;}