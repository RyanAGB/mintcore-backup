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

//----------------------------------------------------------------------------------------------------
error_reporting( 0 );
print_info();

//----------------------------------------------------------------------------------------------------

function print_info()
{
	global $path_to_root;
	
	include_once("pdf_report.inc");

	$cols = array(4, 60, 225, 300, 345, 390, 465, 530);

	$aligns = array('left',	'left',	'left', 'left', 'left', 'left', 'left');

	if ($email == 0)
	{
		$rep = new FrontReport('INVOICE', "InvoiceBulk");
		$rep->Font();
		$rep->Info($params, $cols, null, $aligns);
	}
		
		$rep->title = 'STUDENT INFO';
			$rep->Header();
			
		if($_REQUEST['met']=='application list')
				{
					$sql = "SELECT * FROM 
					tbl_student_application
					WHERE term_id = " . $_REQUEST['trm'];	
										
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					$rep->Font("bold");
					$rep->TextCol(0, 1, "Full Name");
					$rep->TextCol(1, 5, "                                    Course");
					$rep->TextCol(6, 7, "    School Year");
					$rep->Font();
					$rep->NewLine();
					
					while($row = mysql_fetch_array($result)) 
					{
						$rep->TextCol(0, 2, $row["lastname"].", ".$row["firstname"]." " .$row["middlename"]."                                         ");
						$rep->TextCol(1, 6, '                                    '.getCourseName($row["course_id"]));
						$rep->TextCol(6, 8, "    ".getSchoolYearStartEnd(getSchoolYearIdByTermId($row['term_id'])).'('.getSchoolTerm($row['term_id']).')');
						$rep->NewLine();
					}
				}	
				else if($_REQUEST['met']=='declined list')
				{
					$sql = "SELECT * FROM 
					tbl_decline_application
					WHERE term_id = " . $_REQUEST['trm'];	
										
					$result = mysql_query($sql);
					$ctr = mysql_num_rows($result);
					
					$rep->TextCol(0, 1, "Full Name");
					$rep->TextCol(1, 2, "    Course");
					$rep->TextCol(2, 4, "School Year");
					$rep->NewLine();
					$rep->NewLine();
					
					while($row = mysql_fetch_array($result)) 
					{
						$rep->TextCol(0, 1, $row["lastname"].", ".$row["firstname"]." " .$row["middlename"]);
						$rep->TextCol(1, 2, '    '.getCourseName($row["course_id"]));
						$rep->TextCol(2, 4, getSchoolYearStartEnd(getSchoolYearIdByTermId($row['term_id'])).'('.getSchoolTerm($row['term_id']).')');
						$rep->NewLine();
					}
				}
				
				$sql = "SELECT * FROM tbl_employee WHERE user_id=".USER_ID;
				$query = mysql_query($sql);
				$rows = mysql_fetch_array($query);
				
				if (isset($_REQUEST['email'])&&$_REQUEST['email']==1)
			{
				$rep->End(1, isset($_REQUEST['emp'])?"Employee ".ucfirst($_REQUEST['met']):"Student ".ucfirst($_REQUEST['met']), $rows);
			}
			else
			{
				$rep->End();
			}


}
?>