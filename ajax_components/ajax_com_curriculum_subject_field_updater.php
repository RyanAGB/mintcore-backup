<?php
require_once('../config.php');
include_once ('../includes/functions.php');
include_once("../includes/common.php");

$mod = $_REQUEST['mod'];
$filter = $_REQUEST['filter'];
$id = $_REQUEST['id'];

$arr_filter = array();
if($mod == 'updateField')
{
	if(isset($filter) && $filter!='')
	{

		switch ($filter)
		{
			case 'year_level':
				echo generateYearByCurriculum($year_level,$id);
				break;
			case 'term':
				echo generateTermByCurriculum($term,$id);
				break;
		}
		
	}

}
?>