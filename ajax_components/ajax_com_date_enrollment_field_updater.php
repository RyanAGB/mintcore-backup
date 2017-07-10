<?php
require_once('../config.php');
include_once ('../includes/functions.php');
include_once("../includes/common.php");

$mod = $_REQUEST['mod'];
$id = $_REQUEST['id'];

$arr_filter = array();
if($mod == 'updateField')
{
	echo generateSchoolTermsBYSY($id);
}
?>