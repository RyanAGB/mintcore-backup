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

	$id = $_REQUEST['id'];

	$arr_sched_id =	explode(',',$schedule_id);
	
	$arr = array();
	
	foreach($arr_sched_id as $arr_id)
	
	{
	
		$sqls = "SELECT * FROM tbl_schedule WHERE id=".$arr_id;
	
		$querys = mysql_query($sqls);
		
		$rows = mysql_fetch_array($querys);
		
		$arr[] = $rows['subject_id'];
	
	}

	$conflict = '';

	foreach($arr_sched_id as $needle)

	{

		if($needle != '')

		{

			$sqls = "SELECT * FROM tbl_schedule WHERE id=".$needle;

			$querys = mysql_query($sqls);

			$rows = mysql_fetch_array($querys);

			

			$sql = "SELECT * FROM tbl_curriculum_subject 

					WHERE subject_id = ".$rows['subject_id']." AND

					  subject_category <>'EO' AND

					  curriculum_id = " .getStudentCurriculumID($id);

			$query = mysql_query($sql);

			$row = mysql_fetch_array($query);

			

			if(checkIfSubjectHasCoreq($row['id'])!=0)

			{

				$coreq = getArrCoreqByCurriculumSubjectId($row['id']);

				

				foreach($coreq as $co)

				{

				

				$sql_finish = "SELECT * FROM 

								tbl_student_final_grade 

							WHERE 

								student_id = $id AND 

								remarks='P' AND subject_id = ".$co;

				$query_finish = mysql_query($sql_finish);	

				$ctr_finish = mysql_num_rows($query_finish);

				

					if(!in_array($co,$arr)&&$ctr_finish == 0)

					{

						if ($conflict != "")

							$conflict .= ",";

						
						$conflict .= $co;

					}

				}

			}

			if(checkIfSubjectIsCoreq($rows['subject_id'])!='')

			{

				foreach(checkIfSubjectIsCoreq($rows['subject_id']) as $cor)

				{

				$sql_finish = "SELECT * FROM 

								tbl_student_final_grade 

							WHERE 

								student_id = $id AND 

								remarks='P' AND subject_id = ".$cor;

				$query_finish = mysql_query($sql_finish);	

				$ctr_finish = mysql_num_rows($query_finish);

				
				$sqls = "SELECT * FROM tbl_curriculum_subject WHERE id=".$cor;

				$querys = mysql_query($sqls);

				$rows = mysql_fetch_array($querys);
				
				
					if(!in_array($rows['subject_id'],$arr)&&$ctr_finish == 0)

					{

						if ($conflict != "")

							$conflict .= ",";
					
						$conflict .= $rows['subject_id'];

					}

				}

			}

		}

		

	}

	

	echo $conflict;



?>