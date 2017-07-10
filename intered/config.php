<?php
@session_start();

$local = 'localhost';
$user = 'root';
$pass = "M3ridi@n";
$db = 'mint_intered';

$conn = mysql_connect($local,$user,$pass)or die('Problem in connecting server.');

if($conn)
{
	
	$db = mysql_select_db($db,$conn) or die('Access denied or can\'t find database');
	
}


?>