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



if(!isset($_REQUEST['comp']))

{

include_once("../config.php");

include_once("../includes/functions.php");

include_once("../includes/common.php");

}





if(USER_IS_LOGGED != '1')

{

	echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main

}

else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))

	{

		header('Location: ../forbid.html');

	}





$page_title = 'Manage Schedule Template';

$pagination = 'Schedule  > Manage Schedule Template';



$view = $view==''?'list':$view; // initialize action



$id	= $_REQUEST['id'];

$temp 	= $_REQUEST['temp'];



$section_no			= $_REQUEST['section_no'];

$template_name		= $_REQUEST['template_name'];

$publish			= $_REQUEST['publish'];

$num				= $_REQUEST['num'];	



$filter_field = $_REQUEST['filter_field'];

$filter_order = $_REQUEST['filter_order'];

$page = $_REQUEST['page'];



	if($_SESSION[CORE_U_CODE]['current_comp'] != $comp || $_SESSION[CORE_U_CODE]['pageNum'] != $page || $_SESSION[CORE_U_CODE]['fieldName'] != $filter_field || $_SESSION[CORE_U_CODE]['orderBy'] != $filter_order)

	{

		if($page != '')

		{

			$_SESSION[CORE_U_CODE]['pageNum'] = isset($page)&&$page!='' ? $page : '1';

		}

		if($filter_field != '' || $filter_order != '')

		{

			$_SESSION[CORE_U_CODE]['fieldName'] = $filter_field;

			$_SESSION[CORE_U_CODE]['orderBy'] = $filter_order;

		}

		$_SESSION[CORE_U_CODE]['current_comp'] = $comp;

		

	}



if($action == 'save')

{

	if(checkIfScheduleTemplateExist($template_name)) 

	{

		$err_msg = 'Schedule Template already exist.';

	}

	else

	{	

		$sql = "INSERT INTO tbl_schedule_template

				(

					section_no, 

					template_name,

					publish,

					date_created, 

					created_by,

					date_modified,

					modified_by

				) 

				VALUES 

				(

					".GetSQLValueString($section_no,"text").",  

					".GetSQLValueString($template_name,"text").",  

					".GetSQLValueString($publish,"text").",  	

					".time().",

					".USER_ID.", 

					".time().",

					".USER_ID."

				)";

		if(mysql_query ($sql))

		{

			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_schedule_template\';</script>';

		}

	}	

}

else if($action == 'update')

{

	if(checkIfScheduleTemplateExist($template_name, $id)) 

	{

		$err_msg = 'Schedule Template already exist.';

	}

	else

	{

		if(storedModifiedLogs(tbl_schedule_template, $id))

		{

			$sql = "UPDATE tbl_schedule_template SET 

					section_no =".GetSQLValueString($section_no,"text").",

					template_name =".GetSQLValueString($template_name,"text").",

					publish =".GetSQLValueString($publish,"text").",

					date_modified = ".time() .",

					modified_by = ".USER_ID." 

					WHERE id = " .$id;

					

			if(mysql_query ($sql) or die(mysql_error()))

			{

				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_schedule_template\';</script>';

			}

		}

	}

}

else if($action == 'save_temp')

{

	for($ctr=1;$ctr<=$num;$ctr++){

		

		if (isset($_REQUEST["chk_".$ctr])) {

			$arrID[] = $_REQUEST["chk_".$ctr];

		}

	}

		if(count($arrID) > 0){

			

			$sqldel ="DELETE FROM tbl_schedule_template_subjects WHERE template_id = " . $id;

			$query = mysql_query($sqldel);

			//print_r($arrID);

			foreach($arrID as $item){

			  $sql = "INSERT INTO tbl_schedule_template_subjects 

					(	

						template_id,

						section_no, 

						subject_id,

						room_id, 

						employee_id,

						number_of_student,

						number_of_available,

						monday,

						monday_time_from,

						monday_time_to,

						tuesday,

						tuesday_time_from,

						tuesday_time_to,

						wednesday,

						wednesday_time_from,

						wednesday_time_to,

						thursday,

						thursday_time_from,

						thursday_time_to,

						friday,

						friday_time_from,

						friday_time_to,

						saturday,

						saturday_time_from,

						saturday_time_to,

						sunday,

						sunday_time_from,

						sunday_time_to,

						date_created, 

						created_by,

						date_modified,

						modified_by					                                                                  

					) 

					SELECT 

						".GetSQLValueString($id,"int").",                   

						section_no, 

						subject_id,

						room_id, 

						employee_id,

						number_of_student,

						number_of_available,

						monday,

						monday_time_from,

						monday_time_to,

						tuesday,

						tuesday_time_from,

						tuesday_time_to,

						wednesday,

						wednesday_time_from,

						wednesday_time_to,

						thursday,

						thursday_time_from,

						thursday_time_to,

						friday,

						friday_time_from,

						friday_time_to,

						saturday,

						saturday_time_from,

						saturday_time_to,

						sunday,

						sunday_time_from,

						sunday_time_to,

						".time().",

						".USER_ID.", 

						".time().",

						".USER_ID." FROM tbl_schedule WHERE id = ".$item."				

					";

			

				if(mysql_query ($sql)){

					echo '<script language="javascript">window.location =\'index.php?comp=com_schedule_template\';</script>';	

					}

			}

	}

	

}	

else if($action == 'delete')

{

	$selected_item = explode(',',$temp);

	

	foreach($selected_item as $item)

	{

		if ($item != '')

		{

				$sql = "DELETE FROM tbl_schedule_template WHERE id=" .$item;

				mysql_query ($sql);

			}

	}



	if(count($arr_str) > 0)

	{

		echo '<script language="javascript">alert("'.implode("\n",$arr_str).'");</script>';

	}



}

else if($action == 'publish')

{

	$selected_item = explode(',',$temp);

	

	foreach($selected_item as $item)

	{

		storedModifiedLogs(tbl_schedule_template, $id);

		$sql = "UPDATE tbl_schedule_template SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;

		mysql_query ($sql);

	}

}

else if($action == 'unpublished')

{

	$selected_item = explode(',',$temp);

	

	foreach($selected_item as $item)

	{

		if ($item != '')

		{

				storedModifiedLogs(tbl_schedule_template, $id);

				$sql = "UPDATE tbl_schedule_template SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;

				mysql_query ($sql);

		}

	}

}







if($view == 'edit')

{

	$sql = "SELECT * FROM tbl_schedule_template WHERE id = " . $id;

	$query = mysql_query ($sql);

	$row = mysql_fetch_array($query);

	



	$section_no			= $row['section_no'] != $section_no ? $row['section_no'] : $section_no;

	$template_name		= $row['template_name'] != $template_name ? $row['template_name'] : $template_name;

	$publish			= $row['publish'] != $publish ? $row['publish'] : $publish;												





}

else if($view == 'add')

{

	

	$section_no			= $_REQUEST['section_no'];

	$template_name		= $_REQUEST['template_name'];

	$publish			= $_REQUEST['publish'];



}

else if($view == 'period')

{

		$id = $_REQUEST['id'];

		$str_arr = array();



		$sql = "SELECT * FROM tbl_schedule";



		$result = mysql_query($sql);

		$num = mysql_num_rows($result);

		if (mysql_num_rows($result) > 0 )

        {

            $x = 1;

			$ctr = 1;





		$str_arr[] = '<table class="listview">';

			$str_arr[] = '<tr>';

			$str_arr[] = '<th class="col_20"><input type="checkbox" name="id_all" id="id_all" value="" /></th>';

			$str_arr[] = '<th class="col_50">Section No</th>';

			$str_arr[] = '<th class="col_300">Subject</th>';

			$str_arr[] = '<th class="col_100">Room</th>';

			$str_arr[] = '<th class="col_250">Professor</th>';

			$str_arr[] = '<th class="col_40">Slots</th>';

			$str_arr[] = '<th class="col_150">Schedule</th>';

			$str_arr[] = '</tr>';

			

			while($row = mysql_fetch_array($result)) 

			{ 

				$sqls = "SELECT * FROM tbl_schedule_template a 

				JOIN tbl_schedule_template_subjects b ON a.id = b.template_id 

				WHERE  b.subject_id = ".$row['subject_id']." AND b.room_id = ".$row['room_id']." AND b.employee_id = ".$row['employee_id']." AND b.section_no = '".$row['section_no']."' AND b.number_of_student = ".$row['number_of_student']." AND a.id=".$id;

	

				$results = mysql_query($sqls);

				if(mysql_num_rows($results) > 0)

				{

					$checked = 'checked="checked"';

				}

				else

				{

					$checked = '';

				}

	

            $str_arr[] = '<tr class="'.($x%2==0)?"":"highlight".'">';

                $str_arr[] = '<td><input type="checkbox" name="chk_'.$ctr.'" id="chk_'.$ctr.'" value="'.$row['id'].'" '.$checked.' /></td>';

                $str_arr[] = '<td>'.$row["section_no"].'</td>';

                $str_arr[] = '<td>'.getSubjName($row["subject_id"]).'</td>';

               $str_arr[] = ' <td>'.getRoomNo($row["room_id"]).'</td>';

                $str_arr[] = '<td>'.getProfessorFullName($row["employee_id"]).'</td>';

                $str_arr[] = '<td>'.$row["number_of_student"].'</td>';

                $str_arr[] = '<td>'.getScheduleDays($row["id"]).'</td>';

			$str_arr[] = '</tr>	';



	

			$x++;

			$ctr++;

            }	

	

       $str_arr[] = ' </table>';

	   

	   $sched = implode('',$str_arr);

	}

}



// component block, will be included in the template page

$content_template = 'components/block/blk_com_schedule_template.php';

?>