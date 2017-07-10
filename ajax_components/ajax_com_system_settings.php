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
	
	if(USER_IS_LOGGED != '1')
	{
		header('Location: ../index.php');
	}
	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}
	
	$sql = "SELECT * FROM tbl_system_settings";
	$qry =mysql_query($sql);
	$row = mysql_fetch_array($qry);
?>

<div class="box" id="box_container">

	<div class="formview">
    <fieldset>
           <legend><strong>System General Settings</strong></legend>
            <label>Account Activation:</label>
            <span >
            <?=activation_by($row['activation_by'])?>
            </span><br class="hid" />
            
            <label>Password Minimum Length:</label>
            <span class="small_input"><?=$row['password_min']?> 
            </span><br class="hid" />
            <label>Password Maximum Length:</label>
            <span class="small_input"><?=$row['password_max']?>
            </span><br class="hid" />
            
            <label>Password Complexity:</label>
          	<span><?=passComplexity($row['password_complexity'])?>
            </span><br class="hid" /> 
            <span class="clear"></span>
            
            <label>Default Number of Records:</label>
          	<span><?=$row['default_record']?>
            </span><br class="hid" /> 
            <span class="clear"></span>
            
            <label>Notifications:</label>
          	<span><?=$row['notification']?>
            </span><br class="hid" /> 
            <span class="clear"></span>
        </fieldset>
        <fieldset>
           <legend><strong>System General Options</strong></legend>
 
            <label>Maximum Login Attempts:</label>
          	<span><?=$row['max_login_attempt']?>
            </span><br class="hid" /> 
            
            <label>Total Failed Login:</label>
          	<span><?=$row['total_failed_login']?>
            </span><br class="hid" />  
            
            <label>Time To Re-login:</label>
          	<span><?=$row['time_to_relogin']?> Minutes
            </span><br class="hid" />
            
            <label>Simultaneous Login:</label>
            <span ><?php 
			if($row['allowed_sim_login'] == 'Y')
			{
				echo "Yes";
			}
			else
			{
				echo "No";
			}
			?>
            </span><br class="hid" />
            
            <label>Enable Online Enrollment:</label>
            <span >
			<?php 
			if ($row['enable_enrollment']=='Y') 
			{
				echo "Yes";
			}
			else
			{
				echo "No";
			}
			?>
            </span><br class="hid" />
            
            <label>Set Whole System:</label>
            <span >
			<?php 
			if ($row['set_system']=='ON') 
			{
				echo "Online";
			}
			else
			{
				echo "Offline";
			}
			?>
            </span><br class="hid" />
              
            <span class="clear"></span>
        </fieldset>
	</div>        

<p id="formbottom"></p>
</div>
