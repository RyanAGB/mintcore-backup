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
/*
echo '<pre>';
print_r($_SESSION[CORE_U_CODE]['user_menuItems']);
echo '</pre>';
*/
if(count($_SESSION[CORE_U_CODE]['user_menuItems'])> 0)
{
	foreach($_SESSION[CORE_U_CODE]['user_menuItems'] as $menu)
	{
		if($menu['name'] == $_COOKIE['currentMenu'])
		{
?>

            <!-- Accordion -->

            <?php
            if(isset($menu['subItems']))
            {
			?>
            <div class="header"><?=$menu['caption']?></div>
            <div id="accordion">
            <?php			
                foreach($menu['subItems'] as $subItems)
                {
            ?>
                    <h3><a href="#"><?=$subItems['caption']?></a></h3>
                    <?php
                    if(isset($subItems['subItems'] ))
                    {
                    ?>
                        <div>
                            <ul>
                                <?php
                                    foreach($subItems['subItems'] as $child)
                                    {
                                ?>                
                                    <li><a href="index.php?comp=<?=$child['name']?>" title="<?=$child['caption']?>"><?=$child['caption']?></a></li>
								<?php
                                 }
                                ?>
                            </ul>
                        </div>
                    <?php
                        }
                    ?>            
            <?php
                }
			?>
            </div>
            <span class="clearSpacer"></span>
            <?php
            }
            ?>
            
            
<?php
		}
	}
}
?>