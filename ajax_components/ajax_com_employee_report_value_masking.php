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

$fieldname = $_REQUEST['fieldname'];
$fieldvalue = $_REQUEST['fieldvalue'];

if($fieldname  == 'text_field')
{
	echo $fieldvalue;
}
else if($fieldname  == 'yr_level_field')
{
	echo $fieldvalue;	
}
else if($fieldname == 'gender_field')
{
	if($fieldvalue == 'M')
	{
		echo 'Male';
	}	
	else if($fieldvalue == 'F')
	{
		echo 'Female';
	}	
}
else if($fieldname == 'date_field')
{
	echo $fieldvalue;	
}
else if($fieldname == 'curriculum_field')
{
	echo getCurriculumCode($fieldvalue);
}	
else if($fieldname  == 'course_field')
{
	echo getCourseName($fieldvalue);	
}	
else if($fieldname == 'civil_status_field')
{
	if($fieldvalue == 'S')
	{
		echo 'Single';
	}	
	else if($fieldvalue == 'M')
	{
		echo 'Married';
	}	
}	
?>