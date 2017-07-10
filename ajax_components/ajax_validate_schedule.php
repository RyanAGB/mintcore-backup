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


	include_once("../config.php");
	include_once('../includes/functions.php');
	include_once("../includes/common.php");	

	$schedule_id = $_REQUEST['selected'];
	
	$arr_sched_id =	explode(',',$schedule_id);
	
	$conflict = 'false';
	foreach($arr_sched_id as $needle)
	{
		if(needle != '')
		{
			foreach($arr_sched_id as $mixed_array)
			{
				if($needle != $mixed_array && $mixed_array!= '')
				{
					if(checkScheduleForConflict($needle, $mixed_array))
					{
						$conflict = 'true';
						break;
					}

				}
			}
		}
		
		if($conflict == 'true')
		{
			break;
		}
	}
	
	echo $conflict;
	/*
	if(checkScheduleForConflict($schedule_id_selected, $schedule_id))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
	*/

?>