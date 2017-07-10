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

<div id="header">
        <div class="inner-container clearfix">
            <!--<h1 id="logo">
                <a class="home" href="#" title="Go to admin's homepage">
                    <img src="includes/getimage.php?id=1" alt="" /> your title 
                    <span class="ir"></span>
                </a><br />-->

            </h1>
            <div id="userbox">
                <div class="inner">
                	
                    <strong>Hello <?=USER_FIRST_NAME . ' ' . USER_LAST_NAME?></strong><br />
                    <?php
					/*if(USER_LAST_LOGIN != '')
					{
					?>
                    	<span>Last login: <?=date('D F d, Y',USER_LAST_LOGIN)?></span>
                    <?php
					}
					else
					{
					?>
                    	<span>Last login: <?=date('D F d, Y')?></span>
                    <?php
					}*/
					?>
                    <ul class="clearfix">
                        <li><a href="index.php?comp=com_logout" onclick="return confirm('Are you sure you want to logout?')">logout</a></li>
                        <li>|&nbsp;<a href="index.php?comp=com_profile">profile</a></li>
                        <!--
                        <li>|&nbsp;<a href="#">settings</a></li>
                        -->
                    </ul>
                    
                </div>
            </div><!-- #userbox -->
        </div><!-- .inner-container -->
    
</div><!-- #header -->

<div id="nav">
    <?php
		include_once('widgets/inc_menu_nav.php');
	?>
</div><!-- #nav --> 