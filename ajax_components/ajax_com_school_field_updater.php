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



$id = $_REQUEST['id'];

$cor = $_REQUEST['cor'];

$mod = $_REQUEST['mod'];



if($mod=='school')

{

		if($id != '')

		{

			$sql = "SELECT * FROM tbl_school_list WHERE id = ".$id;						

			$query = mysql_query($sql);

			$row = mysql_fetch_array($query);

		}



	echo $row['school_code'].','.$row['school_type'].','.$row['address'];

}



if($mod=='updateDate')

{	

	$arr_str = array();

		

		$sql = "SELECT * FROM tbl_exam_date WHERE term_id = ".$id." AND course_id=".$cor;						

		$query = mysql_query($sql);

		while($row = mysql_fetch_array($query))

		{



			if(strtotime($row['entrance_date'])>=strtotime(date('Y-m-d')))

			{

				if($row['id'] == $selected_id)

				{

					$selected = 'selected="selected"';

				}

				else

				{

					$selected = '';

				}

				

				$arr_str[] = '<option value="'.$row['id'].'" '.$selected.' >'.getMonthName($date[1]).' '.$date[2].', '.$date[0]. '</option>';

			}

		}

		

		return implode('',$arr_str);

}

?>