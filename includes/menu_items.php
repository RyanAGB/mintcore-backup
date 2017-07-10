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


$admin_menuArr = array(
				0 => array(	'id'		=>	'dashboard',
							'name'		=>	'Dashboard',
							'link'		=>	'index.php?comp=com_dashboard'
						),
				1 => array(	'id'		=>	'school_setup',
							'name'		=>	'School Setup',
							'link'		=>	'index.php?comp=com_school_setup',
							'submenu'	=>	array(	
											0 => array(	'id'		=>	'school_settings',
														'name'		=>	'School Settings',
														'link'		=>	'#',
														'submenu'	=>	array(	
																		0 => array(	'id'		=>	'school_settings',
																					'name'		=>	'School Settings',
																					'link'		=>	'#'
																				),
																		1 => array(	'id'		=>	'curriculum_setup',
																					'name'		=>	'Curriculum Setup',
																					'link'		=>	'#'
																				),
																		2 => array(	'id'		=>	'subject_setup',
																					'name'		=>	'Subject Setup',
																					'link'		=>	'#'
																				),
																		3 => array(	'id'		=>	'date_enrollment',
																					'name'		=>	'Date Enrollment',
																					'link'		=>	'#'
																				),
																		4 => array(	'id'		=>	'grade_management',
																					'name'		=>	'Grade Management',
																					'link'		=>	'#'
																				),	
																		5 => array(	'id'		=>	'utility',
																					'name'		=>	'Utility',
																					'link'		=>	'#'
																				)																																																															
																		)																	
													),
											1 => array(	'id'		=>	'curriculum_setup',
														'name'		=>	'Curriculum Setup',
														'link'		=>	'#'
													),
											2 => array(	'id'		=>	'subject_setup',
														'name'		=>	'Subject Setup',
														'link'		=>	'#'
													),
											3 => array(	'id'		=>	'date_enrollment',
														'name'		=>	'Date Enrollment',
														'link'		=>	'#'
													),
											4 => array(	'id'		=>	'grade_management',
														'name'		=>	'Grade Management',
														'link'		=>	'#'
													),	
											5 => array(	'id'		=>	'utility',
														'name'		=>	'Utility',
														'link'		=>	'#'
													)																																																															
											)
						),							
			);
	
?>