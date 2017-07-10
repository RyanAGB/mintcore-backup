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
	
	$sql = "SELECT * FROM tbl_school_settings";
	$qry =mysql_query($sql);
	$row = mysql_fetch_array($qry);
?>

<div class="box" id="box_container">

	<div class="formview">
    <fieldset>
           <legend><strong>School Information</strong></legend>
            <label>School Name:</label>
            <span class="txt"><?=$row['school_name']?>
            </span><br class="hid" />
            
            <label>School Address:</label>
            <span class="txt"><?=$row['school_address'].', '.$row['school_city'].' '.$row['school_postal'] ?>
            </span><br class="hid" />
            
            <label>School Telphone:</label>
            <span class="txt"><?=$row['school_tel']?>
            </span><br class="hid" />
            
            <label>School Fax:</label>
            <span class="txt"><?=$row['school_fax']?>
            </span><br class="hid" />
            
            <label>School Hours:</label>
            <span class="txt"><?=covertTimeTo12($row['school_open_time']).' - '.covertTimeTo12($row['school_close_time'])?>
            </span><br class="hid" />
            
            <span class="clear"></span>
    </fieldset>        
	<fieldset>
           <legend><strong>System Information</strong></legend>
            <label>School Logo:</label>
            <span >
            <br />
            <?php
			if($row['school_logo'] != '')
			{
			?>
				<img src="includes/getimage.php?id=<?=$row['id']?>"  width="275"/>
            <?php
			}
			else
			{
			?>
            	<img src="images/NoPhotoAvailable.jpg"/>
            <?php
			}
			?>
            <br />
            (maximum size: 550x55)
            </span><br class="hid" />
            
            <label>System Email Address:</label>
            <span class="txt"><?=$row['school_sys_email']?>
            </span><br class="hid" />
            
            <label>System URL:</label>
            <span class="txt"><?=$row['school_sys_url']?>
            </span><br class="hid" />
            
            <span class="clear"></span>
    </fieldset>
	</div>        

<p id="formbottom"></p>
</div>
