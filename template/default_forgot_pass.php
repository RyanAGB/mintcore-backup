<?php
/* #############################################################
*	
*					 -- DO NOT REMOVED --
*
* 	  			THIS CODE IS CREATED FOR CORE SIS.
*	  USING IT WITHOUT THE PROPER PERMISSION FROM EGLOBALMD
*			 IS PROHIBITED BUT LIMITED FROM OTHER 
*				  OPEN SOURCE THAT ARE USED.
*
*	------------------------------------------------------------
*	
*	CREATED BY: JBG
*	DATE CREATED: 01 JANUARY 2010
*	FOR ANY ISSUE AND BUG FIXES VISIT http://www.eglobalmd.com
*	OR E-mail support@eglobalmd.com
*
*  ############################################################ */

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CORE - Student Information System</title>
<link type="text/css" href="css/style.css" rel="stylesheet" />	
<!--[if lte IE 7]>
<link href="css/styles_ie.css" rel="stylesheet" type="text/css" media="screen" />
<![endif]-->
<!--[if lte IE 6]>
<link href="css/styles_ie6.css" rel="stylesheet" type="text/css" media="screen" />
<![endif]-->
<link type="text/css" href="css/jquery-ui.css" rel="stylesheet" />	
<link rel="stylesheet" type="text/css" href="css/jquery_superfish/superfish.css" media="screen">   
 
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="js/jquery_superfish/hoverIntent.js"></script>
<script type="text/javascript" src="js/jquery_superfish/superfish.js"></script>
<script type="text/javascript" src="js/jquery_superfish/supersubs.js"></script>   
<script type="text/javascript" src="js/default.js"></script>   

<script type="text/javascript">
    $(function(){
		$('#forgot_pass').click(function(){
			$('#action').val('forgot_pass');
			$("form").submit();
		});
	});
</script>
</head>
<body class="login">

<div id="login_wrap">
    <div id="content">
		<?php
        	require_once($content_template);
		?>
    </div><!-- #content -->
</div><!-- #login_wrap -->

</body>
</html>


