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


$page_title = 'Manage Statement of Account';
$pagination = 'Student Payment  > Manage Statement of Account';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$cnt_stud	= $_REQUEST['cnt_stud'];
$cnt_sheet	= $_REQUEST['cnt_sheet'];

$grade_val		= $_REQUEST[grade];
$student_id 	= $_REQUEST[stud];
$sheet_id 		= $_REQUEST[sheet];

//print_r($grade_val);

if($action == 'update')
{
	
	$sql_del = "DELETE FROM tbl_student_grade WHERE schedule_id = ".$id;
	$result_del = mysql_query($sql_del);
	
	for($ctr=0;$ctr<$cnt_stud;$ctr++)
	{
		for($ctr2=0;$ctr2<$cnt_sheet;$ctr2++)
		{
				$sql = "INSERT INTO tbl_student_grade
						(
							student_id,
							schedule_id,
							professor_id,
							sheet_id, 
							grade,
							date_created, 
							created_by,
							date_modified,
							modified_by
						) 
						VALUES 
						(
							".GetSQLValueString($student_id[$ctr][$ctr2],"int").",  
							".GetSQLValueString($id,"int").",
							".USER_EMP_ID.",
							".GetSQLValueString($sheet_id[$ctr][$ctr2],"int").",
							".GetSQLValueString(encrypt($grade_val[$ctr][$ctr2]),"text").", 	
							".time().",
							".USER_ID.", 
							".time().",
							".USER_ID."
						)";
				
				if(mysql_query ($sql))
				{
					echo '<script language="javascript">window.location =\'index.php?comp=com_pr_encode_grade\';</script>';
             }
		}
	}
}

if($view == 'edit')
{
		$str_arr = array();
		
		$sql = "SELECT * FROM tbl_student_schedule WHERE schedule_id = ".$id;
		$result = mysql_query($sql);		
		
		$sql_dis = "SELECT a.* FROM tbl_gradesheet a,tbl_school_year_period b WHERE b.is_current = 'Y' AND b.id=a.school_yr_period_id";
		$result_dis = mysql_query($sql_dis);
                 
 
        if (mysql_num_rows($result) > 0 )
        {
            $x = 0;

        
         $str_arr[] = '<table class="listview">';     
         $str_arr[] = '<tr>';
         $str_arr[] = '<th class="col_350"><a href="#list">Student Number</a></th>';
          $str_arr[] = '<th class="col_350"><a href="#list">Student Name</a></th>';
            

            while($row_dis = mysql_fetch_array($result_dis)) 
            { 
 
          $str_arr[] = '<th class="col_350"><a href="#list">'.$row_dis['label'].' '.$row_dis['percentage'].'%'.'</a></th>';
             
               }  
          $str_arr[] = '</tr>';
          

		$stud_cnt = 0;
            while($row = mysql_fetch_array($result)) 
            { 

             $str_arr[] = '<tr class="'.($x%2==0).'""? ":""highlight">';
             $str_arr[] = '<td>'.getStudentNumber($row["student_id"]).'</td>';
              $str_arr[] = '<td>'.getStudentFullName($row["student_id"]).'</td>'; 

                
			$sql_per = "SELECT a.* FROM tbl_gradesheet a,tbl_school_year_period b 
				WHERE b.is_current = 'Y' AND b.id=a.school_yr_period_id";
		$result_per = mysql_query($sql_per);
			$sheet_cnt = 0;
            while($row_per = mysql_fetch_array($result_per)) 
            { 

			$sql_grade = "SELECT * FROM tbl_student_grade 
				WHERE sheet_id=".$row_per['id']." AND student_id = ".$row['student_id'];
		$result_grade = mysql_query($sql_grade);
				
		if(mysql_num_rows($result_grade) > 0){
		
            while($row_grade = mysql_fetch_array($result_grade)) 
            { $grade =  $row_grade['grade']; 

                $str_arr[] = '<td><input name="stud['.$stud_cnt.']['.$sheet_cnt.']" type="hidden" value="'.$row['student_id'].'" id="stud'.$x.'" /><input name="sheet['.$stud_cnt.']['.$sheet_cnt.']" type="hidden" value="'.$row_per['id'].'" id="sheet'.$x.'" /><input name="grade[<?=$stud_cnt?>]['.$sheet_cnt.']" type="text" value="'.$grade.'" id="grade'.$x.'"';
				if($row_grade['is_locked'] == 'Y'){ 
				$lock = 1;
				$str_arr[] ='readonly="readonly"'; } 
				$str_arr[] = '/>';
                $str_arr[] = '</td>';   
}
		  }else{ 
               $str_arr[] = '<td><input name="stud['.$stud_cnt.']['.$sheet_cnt.']" type="hidden" value="'.$row['student_id'].'" id="stud'.$x.'" /><input name="sheet['.$stud_cnt.']['.$sheet_cnt.']" type="hidden" value="'.$row_per['id'].'" id="sheet'.$x.'" /><input name="grade[<?=$stud_cnt?>]['.$sheet_cnt.']" type="text" value="" id="grade'.$x.'"';
				if($row_grade['is_locked'] == 'Y'){ 
				$lock = 1;
				$str_arr[] ='readonly="readonly"'; } 
				$str_arr[] = '/>';
                $str_arr[] = '</td>'; 

		   } $sheet_cnt++;
		  }

             $str_arr[] = '</tr>';
 
		$stud_cnt++;
		   $x++;      
			}

        }
        else 
        {
				$lock = 1;
                $dis = 'No students under this section';
        }

         $str_arr[] = '</table>';
         
     $grade_list = implode('',$str_arr); 
        
}
// component block, will be included in the template page
$content_template = 'components/block/blk_com_soa.php';
?>