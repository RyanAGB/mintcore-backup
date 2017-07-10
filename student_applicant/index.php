<?php
include_once('../config.php');
include_once('../includes/functions.php');
include_once('../includes/common.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mint Form</title>
<link rel="stylesheet" href="default.css" />
<script type="text/javascript" src="js/jquery.metadata.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script type="text/javascript" src="js/cmxforms.js"></script>
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="js/default.js"></script> 
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery.validate.js" type="text/javascript"></script>
</head>

<body>

    <div class="headerContainer">
<div class="header"><img src="mint_logo.png" /></div>

</div>

<table border="0" align="center">
  <tr>
  <td><div class="title">
<h4>ENROLLMENT APPLICATION FORM</h4>
</div></td>
  </tr>
  <tr>
  <td><?php
	$comp = $_REQUEST['comp'];
	$action = $_REQUEST['action'];
	$view = $_REQUEST['view'];

     include_once('components/com_student_profile.php');
	 require_once($content_template);

    ?>    </td>
  </tr>
</table>

</body>
</html>
