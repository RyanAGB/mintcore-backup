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


<div class="inner-container clearfix">

    <ul class="sf-menu">
    	<?php
		if(count($_SESSION[CORE_U_CODE]['user_menuItems']) > 0)
		{
			foreach($_SESSION[CORE_U_CODE]['user_menuItems'] as $menu)
			{
				if($menu['name']=='library')
				{
		?>
        	<li class="current"><a href="<?=$menu['name']?>"><?=$menu['caption']?></a></li>
            	<?php
				}
				else
				{
				?>
            <li class="current"><a href="index.php?comp=<?=$menu['name']?>" onclick="changeCurrentMenu('<?=$menu['name']?>')"><?=$menu['caption']?></a></li>
        <?php
				}
			}
		}
		/*if($_SESSION[CORE_U_CODE]['user_credentials']['access_id']=='1')
		{
		?>
			<li class="current"><a href="library">Library</a></li>
        <?php
		}*/
		if($_SESSION[CORE_U_CODE]['user_credentials']['access_id']=='6')
		{
		?>
			<li class="current"><a href="library/opac">Library</a></li>
		<?php
        }
		?>
        
    </ul>  
           
</div><!-- .inner-container. -->