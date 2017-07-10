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
	include_once("../includes/functions.php");
	include_once("../includes/common.php");
	
$mod = $_REQUEST['mod'];
$id = $_REQUEST['id'];
$ad = $_REQUEST['ad'];
$str_arr = array();
if($mod == 'updateYear')
{
	
	
	if($id != '')
	{
		if($ad == 'T')
	{
		
		$sql = "SELECT no_of_years FROM tbl_curriculum WHERE is_current = 'Y' AND course_id =" .$id;						
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$years = $row['no_of_years'];
		
		for($ctr=1; $ctr<=$years; $ctr++) {
			if($ctr == 1)
			{
				$yir = '1st Year';
			}if($ctr == 2)
			{
				$yir = '2nd Year';
			}if($ctr == 3)
			{
				$yir = '3rd Year';
			}if($ctr == 4)
			{
				$yir = '4th Year';
			}
			
			$str_arr[] = '<option value="'.$ctr.'" >'.$yir.'</option>';
		}
	
	echo implode('',$str_arr);
	}
	else{
			
			$str_arr[] = '<option value="1">1st Year</option>';
	
	echo implode('',$str_arr);
	}
}
}

else if($mod == 'updateYearForSched')
{

	if($id != '')
	{
		if($ad == 'T')
	{
		
		$sql = "SELECT no_of_years FROM tbl_curriculum WHERE is_current = 'Y' AND course_id =" .$id;						
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		$years = $row['no_of_years'];
		
		$str_arr[] = '<option value="" >Select</option>';
		
		for($ctr=1; $ctr<=$years; $ctr++) {
			if($ctr == 1)
			{
				$yir = '1st Year';
			}if($ctr == 2)
			{
				$yir = '2nd Year';
			}if($ctr == 3)
			{
				$yir = '3rd Year';
			}if($ctr == 4)
			{
				$yir = '4th Year';
			}
			
			$str_arr[] = '<option value="'.$ctr.'" >'.$yir.'</option>';
		}
	
	echo implode('',$str_arr);
	}
}
}

else if($mod == 'updateSchool')
{
	if($id != '')
	{
		$str_arr[] = '<option value="1">1st Year</option>';
	}	
	echo implode('',$str_arr);
}

else if($mod == 'updateNumber')
{
	if($id!='')
	{
		echo generateStudentNumber($id,1);
	}
}

else if($mod == 'updateEmpNumber')
{
	if($id!='')
	{
		echo generateEmployeeNumber($id,1);
	}
}

else if($mod == 'updateElective')
{
	if($id!='')
	{
		$sql = "SELECT * FROM tbl_curriculum WHERE course_id=".$id." AND is_current='Y'";
		$query = mysql_query($sql);
		$row = mysql_fetch_array($query);
		
		$sqlc = "SELECT * FROM tbl_curriculum c,tbl_curriculum_subject s WHERE c.id=s.curriculum_id AND s.subject_category='E' AND c.id=".$row['id'];	
		$queryc = mysql_query($sqlc);
		
		while($rowc = mysql_fetch_array($queryc))
		{
		 	$str_arr[] = '<label>'.getSubjName($rowc['subject_id']).'</label><select name="'.$rowc['subject_id'].'" id="'.$rowc['subject_id'].'" class="txt_350"><option value="" selected="selected">Select</option>'.generateStudentSubject($ad).'</select>';
            
			$ids .= $rowc['subject_id'].',';
        }
            
            $str_arr[] = '<input type="hidden" name="num" id="num" value="'.$ids.'" />';
			
			echo implode('',$str_arr);
	}
}

?>