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



?>



<script type="text/javascript">

$(document).ready(function(){  

	

});	

</script>



<?php

if($err_msg != '')

{

?>

    <p class="alert">

        <span class="txt"><span class="icon"></span><strong>Alert:</strong> <?=$err_msg?></span>

        <a href="#" class="close" title="Close"><span class="bg"></span>Close</a>

    </p>

<?php

}

?>



<h2><?=$page_title?></h2>

<ul class="tabs">

</ul>

<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">

<div class="box" id="box_container">

	<div class="formview">

    <div class="fieldsetContainer502">

    

<?php



	// 1 - Superuser; 	2 - Professor; 	3 - Department Head; 

	// 4 - Registrar; 	5 - Cashier; 	6 - Student; 		7 - Parent;

	

	$sql = "SELECT * FROM tbl_access_role a, tbl_components b 

			WHERE a.component_id=b.id and a.access_id = ".ACCESS_ID;

	$query = mysql_query($sql);

	

	if(mysql_num_rows($query)>0)

	{

//if(ACCESS_ID == 1)

//{

?>

        <fieldset class="big">

           <legend><strong>Shortcut</strong></legend>

           <table width="100%" cellpadding="20" cellspacing="10">

           <?php $x = 1;

		   while($row = mysql_fetch_array($query))

		   {

		   	if($row['show_dashboard']=='Y')

			{

				($x%6==0)?"<tr>":"";

		   ?>

           			<td><div align="center"><a href="<?=$row['unique_friendly_title']=='home'||$row['unique_friendly_title']=='circ'||$row['unique_friendly_title']=='catalog'||$row['unique_friendly_title']=='admin'||$row['unique_friendly_title']=='reports'?'library/'.$row['unique_friendly_title']:'index.php?comp='.$row['unique_friendly_title']?>" onclick="changeCurrentMenu('<?=getMainComponent($row['parent_id'])?>');" ><img src="images/icons/<?=$row['dashboard_photo']==''?'no_image.png':$row['dashboard_photo']?>" width="65" height="65" />

                <br /><?=$row['title']?></a></div></td>

                <!--<td><div align="center"><a href="index.php?comp=com_school_details" onclick="changeCurrentMenu('mn_school_setup')"><img src="images/icons/School-Details.jpg" width="65" height="65" />

                <br />School Details</a></div></td>

                <td><div align="center"><a href="index.php?comp=com_system_settings" onclick="changeCurrentMenu('mn_tools')"><img src="images/icons/setting.jpg" /><br />System Settings</a></div></td>

                <td><div align="center"><a href="index.php?comp=com_professor_encode_grade" onclick="changeCurrentMenu('mn_professor')"><img src="images/icons/grade.jpg" /><br />Encode Grade</a></div></td>

              </tr>

              <tr>

                <td><div align="center"><a href="index.php?comp=com_professor_info" onclick="changeCurrentMenu('mn_professor')"><img src="images/icons/prof.jpg" width="65" height="65" /><br />Professor Profile</a></div></td>

                <td><div align="center"><a href="index.php?comp=com_student_info" onclick="changeCurrentMenu('mn_student')"><img src="images/icons/student_profile.jpg" /><br />Student Profile</a></div></td>

                <td><div align="center"><a href="index.php?comp=com_school_calendar" onclick="changeCurrentMenu('mn_school_setup')"><img src="images/icons/calendar.jpg" /><br />Calendar Management</a></div></td>

              </tr>

              <tr>

                <td><div align="center"><a href="index.php?comp=com_professor_schedule" onclick="changeCurrentMenu('mn_professor')"><img src="images/icons/prof_schedule.jpg" width="65" height="65" /><br />Professor Schedule</a></div></td>

                <td><div align="center"><a href="index.php?comp=com_student_schedule" onclick="changeCurrentMenu('mn_student')"><img src="images/icons/Student_schedule.jpg" /><br />Student Schedule</a></div></td>

                <td><div align="center"><a href="index.php?comp=com_schedule" onclick="changeCurrentMenu('mn_schedule')"><img src="images/icons/schedule.jpg" /><br />Schedule Management</a></div></td>!-->

              <?=($x%6==0)?"</tr>":"";

			  $x++;

			  }

			 

			 }

			 ?>

            </table>



        </fieldset>

    </div>

    <?php } ?>

<!--    <div class="fieldsetContainer50">

		

        <fieldset>

           <legend><strong>System Logs</strong></legend>

           <div style="height:275px; overflow:auto; font-size:12px">

           <?/*=generateSystemLogs('1')*/?>                                                     

           </div>

        </fieldset>

        

        <fieldset>

           <legend><strong>Account Overview</strong></legend>

           <label>Username&nbsp;:&nbsp;<span><?=USERNAME?></span></label>

           <br class="hid" />

           <label>Your last visit was on&nbsp;:&nbsp;<span><?//=date('l F d, Y',USER_LAST_LOGIN)?></span></label>

           <br class="hid" />

           <label>From IP&nbsp;:&nbsp;<span><?=USER_LAST_IP_CONNECTED?></span></label>

           <br class="hid" />

           <label>&nbsp;</label>

           <label>You had <?/*=USER_FAILED_LOGS*/?> invalid attempt/s before log-on.</label>

       

        </fieldset>-->

<?php

/*	 if(in_array('library',$_SESSION[CORE_U_CODE]['access_components']))

	{ 

?>       

        <fieldset>

           <legend><strong>Library</strong></legend>

           <table width="100%" cellpadding="20" cellspacing="10">

              <tr>

                <td><div align="center"><a href="library" onclick="changeCurrentMenu('mn_school_setup')"><img src="images/icons/books.png" width="65" height="65" />

                <br />Enter Library</a></div></td>

                </tr>

                </table>

                </fieldset>

        </div>

<?php

}

}

else if(ACCESS_ID == 2)

{

?>

		<fieldset>

           <legend><strong>Shortcut</strong></legend>

           <table width="100%" cellpadding="20" cellspacing="10">

              <tr>

                <td><div align="center"><a href="index.php?comp=com_pr_encode_grade" onclick="changeCurrentMenu('mn_pr_grade')"><img src="images/icons/grade.jpg" /><br />Encode Grade</a></div></td>

                <td><div align="center"><a href="index.php?comp=com_profile" onclick="changeCurrentMenu('mn_pr_dashboard')"><img src="images/icons/prof.jpg" width="65" height="65" /><br />Profile</a></div></td>

                <td><div align="center"><a href="index.php?comp=com_pr_current_schedule" onclick="changeCurrentMenu('mn_pr_schedule')"><img src="images/icons/prof_schedule.jpg" width="65" height="65" /><br />Schedule</a></div></td>

              </tr>

              <tr>

                 <td><div align="center"><a href="index.php?comp=com_pr_school_calendar" onclick="changeCurrentMenu('mn_pr_calendar')"><img src="images/icons/calendar.jpg" /><br />Calendar Management</a></div></td>

              </tr>

            </table>



        </fieldset>

    </div>

    <div class="fieldsetContainer50">

		<!--

        <fieldset>

           <legend><strong>System Logs</strong></legend>

           <div style="height:275px; overflow:auto; font-size:12px">

           <?=generateSystemLogs('2')?>                                                     

           </div>

        </fieldset>

        -->

        <fieldset>

           <legend><strong>Account Overview</strong></legend>

           <label>Username&nbsp;:&nbsp;<span><?=USERNAME?></span></label>

           <br class="hid" />

           <label>Your last visit was on&nbsp;:&nbsp;<span><?=date('l F d, Y',USER_LAST_LOGIN)?></span></label>

           <br class="hid" />

           <label>From IP&nbsp;:&nbsp;<span><?=USER_LAST_IP_CONNECTED?></span></label>

           <br class="hid" />

           <label>&nbsp;</label>

           <label>You had <?=USER_FAILED_LOGS?> invalid attempt/s before log-on.</label>

       

        </fieldset>        

<?php

}

else if(ACCESS_ID == 6)

{

?>

		<fieldset>

           <legend><strong>Shortcut</strong></legend>

           <table width="100%" cellpadding="20" cellspacing="10">

              <tr>

                <td><div align="center"><a href="index.php?comp=com_st_select_schedule" onclick="changeCurrentMenu('mn_st_reserve')"><img src="images/icons/Enroll.jpg" width="65" height="65" />

                <br />Enroll</a></div></td>

                <td><div align="center"><a href="index.php?comp=com_st_grade_report" onclick="changeCurrentMenu('mn_st_grade_1')"><img src="images/icons/grade.jpg" /><br />Grade</a></div></td>

                <td><div align="center"><a href="index.php?comp=com_profile" onclick="changeCurrentMenu('mn_st_dashboard')"><img src="images/icons/student_profile.jpg" /><br />

                Profile</a></div></td>

              </tr>

              <tr>

                <td><div align="center"><a href="index.php?comp=com_st_class_schedule" onclick="changeCurrentMenu('mn_st_schedule_1')"><img src="images/icons/Student_schedule.jpg" /><br />

                Schedule</a></div></td>

              </tr>

            </table>



        </fieldset>

    </div>

    <div class="fieldsetContainer50">

		<!--

        <fieldset>

           <legend><strong>System Logs</strong></legend>

           <div style="height:275px; overflow:auto; font-size:12px">

           <?=generateSystemLogs('6')?>                                                     

           </div>

        </fieldset>

        -->

        <fieldset>

           <legend><strong>Account Overview</strong></legend>

           <label>Username&nbsp;:&nbsp;<span><?=USERNAME?></span></label>

           <br class="hid" />

           <label>Your last visit was on&nbsp;:&nbsp;<span><?=date('l F d, Y',USER_LAST_LOGIN)?></span></label>

           <br class="hid" />

           <label>From IP&nbsp;:&nbsp;<span><?=USER_LAST_IP_CONNECTED?></span></label>

           <br class="hid" />

           <label>&nbsp;</label>

           <label>You had <?=USER_FAILED_LOGS?> invalid attempt/s before log-on.</label>

       

        </fieldset>        

<?php

}

else if(ACCESS_ID == 5)

{

?>

		<fieldset>

           <legend><strong>Shortcut</strong></legend>

           <table width="100%" cellpadding="20" cellspacing="10">

              <tr>

                <td><div><a href="index.php?comp=com_cs_student_balance_payment" onclick="changeCurrentMenu('mn_cs_payments')"><img src="images/icons/payment.jpg" width="65" height="65" />

                <br />Payment</a></div></td>

              </tr>

            </table>



        </fieldset>

    </div>

    <div class="fieldsetContainer50">

		<!--

        <fieldset>

           <legend><strong>System Logs</strong></legend>

           <div style="height:275px; overflow:auto; font-size:12px">

           <?=generateSystemLogs('2')?>                                                     

           </div>

        </fieldset>

        -->

        <fieldset>

           <legend><strong>Account Overview</strong></legend>

           <label>Username&nbsp;:&nbsp;<span><?=USERNAME?></span></label>

           <br class="hid" />

           <label>Your last visit was on&nbsp;:&nbsp;<span><?=date('l F d, Y',USER_LAST_LOGIN)?></span></label>

           <br class="hid" />

           <label>From IP&nbsp;:&nbsp;<span><?=USER_LAST_IP_CONNECTED?></span></label>

           <br class="hid" />

           <label>&nbsp;</label>

           <label>You had <?=USER_FAILED_LOGS?> invalid attempt/s before log-on.</label>

       

        </fieldset>        

<?php

}

else if(ACCESS_ID == 7)

{

?>

		<fieldset>

           <legend><strong>Shortcut</strong></legend>

           <table width="100%" cellpadding="20" cellspacing="10">

              <tr>

                <td><div align="center"><a href="index.php?comp=com_pa_class_schedule"  onclick="changeCurrentMenu('mn_pa_schedule')"><img src="images/icons/Student_schedule.jpg" /><br />

                Schedule</a></div></td>

                <td><div align="center"><a href="index.php?comp=com_pa_grade_report"  onclick="changeCurrentMenu('mn_pa_grade')"><img src="images/icons/grade.jpg" /><br />Grade</a></div></td>

                <td><div align="center"><a href="index.php?comp=com_profile"  onclick="changeCurrentMenu('mn_pa_dashboard')"><img src="images/icons/student_profile.jpg" /><br />

                Profile</a></div></td>

              </tr>

            </table>



        </fieldset>

    </div>

    <div class="fieldsetContainer50">

		<!--

        <fieldset>

           <legend><strong>System Logs</strong></legend>

           <div style="height:275px; overflow:auto; font-size:12px">

           <?=generateSystemLogs('6')?>                                                     

           </div>

        </fieldset>

        -->

        <fieldset>

           <legend><strong>Account Overview</strong></legend>

           <label>Username&nbsp;:&nbsp;<span><?=USERNAME?></span></label>

           <br class="hid" />

           <label>Your last visit was on&nbsp;:&nbsp;<span><?=date('l F d, Y',USER_LAST_LOGIN)?></span></label>

           <br class="hid" />

           <label>From IP&nbsp;:&nbsp;<span><?=USER_LAST_IP_CONNECTED?></span></label>

           <br class="hid" />

           <label>&nbsp;</label>

           <label>You had <?=USER_FAILED_LOGS?> invalid attempt/s before log-on.</label>

       

        </fieldset>        

<?php

}

else

{*/

?>

        <!--<fieldset>

           <legend><strong>Shortcut</strong></legend>

           <table width="100%" cellpadding="20" cellspacing="10">

              <tr>

                <td>&nbsp;</td>

                <td>&nbsp;</td>

                <td>&nbsp;</td>

             </tr>

            </table>!-->



        </fieldset>

    </div>

    <div class="fieldsetContainer502">

		<!--

        <fieldset>

           <legend><strong>System Logs</strong></legend>

           <div style="height:275px; overflow:auto; font-size:12px">

          	<?/*=generateSystemLogs('2'); echo "MALKSALKSLKALSKLASLAKSA"*/?>                                                        

           </div>

        </fieldset>

        -->

        <fieldset class="big">

           <legend><strong>Account Overview</strong></legend>

           <label>Username&nbsp;:&nbsp;<span><?=USERNAME?></span></label>

           <br class="hid" />

           <label>Your last visit was on&nbsp;:&nbsp;<span><?=date('l F d, Y',USER_LAST_LOGIN)?></span></label>

           <br class="hid" />

           <label>From IP&nbsp;:&nbsp;<span><?=USER_LAST_IP_CONNECTED?></span></label>

           <br class="hid" />

           <label>&nbsp;</label>

           <label>You had <?=USER_FAILED_LOGS?> invalid attempt/s before log-on.</label>

       

        </fieldset>        

<?php

//}

?>

    </div>    

    </div><!-- /.formview -->

<p id="formbottom"></p>

</div>

<input type="hidden" name="temp" id="temp" value="" />

<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />

<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />



<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />

</form>