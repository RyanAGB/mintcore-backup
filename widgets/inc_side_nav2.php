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
?>
<script type="text/javascript">
var ContentHeight = 300;
		var TimeToSlide = 250.0;
		
		var openAccordion = '';


		function runAccordion(index)
		{
		  var nID = "Accordion" + index + "Content";
		  if(openAccordion == nID)
			nID = '';
		   
		  setTimeout("animate(" + new Date().getTime() + "," + TimeToSlide + ",'"
			  + openAccordion + "','" + nID + "')", 33);
		 
		  openAccordion = nID;
		 
		}
		
		function animate(lastTick, timeLeft, closingId, openingId)
		{  
		  var curTick = new Date().getTime();
		  var elapsedTicks = curTick - lastTick;
		 
		  var opening = (openingId == '') ? null : document.getElementById(openingId);
		  var closing = (closingId == '') ? null : document.getElementById(closingId);
		 
		  if(timeLeft <= elapsedTicks)
		  {
			if(opening != null)
			  opening.style.height = ContentHeight + 'px';
		   
			if(closing != null)
			{
			  closing.style.display = 'none';
			  closing.style.height = '0px';
			}
			return;
		  }
		 
		  timeLeft -= elapsedTicks;
		  var newClosedHeight = Math.round((timeLeft/TimeToSlide) * ContentHeight);
		
		  if(opening != null)
		  {
			if(opening.style.display != 'block')
			  opening.style.display = 'block';
			opening.style.height = (ContentHeight - newClosedHeight) + 'px';
		  }
		 
		  if(closing != null)
			closing.style.height = newClosedHeight + 'px';
		
		  setTimeout("animate(" + curTick + "," + timeLeft + ",'"
			  + closingId + "','" + openingId + "')", 33);
		}
		
		//runAccordion(1);
</script>
            <!-- Accordion -->

            <div class="header_app">Online Application</div>
            <!--<div id="accordion">
           
                    <h3 id="h0"><a href="#">Welcome</a></h3>
                   
                        <div id="0">
                            <ul>
                              
                            </ul>
                        </div>
                        <h3 id="h1" onclick="runaccordion(1);"><a href="#">Welcome</a></h3>
                   
                        <div id="1">
                            <ul>
                               
                            </ul>
                        </div>
                    	<h3 id="h2"><a href="#">Welcome</a></h3>
                   
                        <div id="2">
                            <ul>
                              
                            </ul>
                        </div>
            </div>
            
            <span class="clearSpacer"></span>!-->
            
           <?php
        require_once($content_template);
    ?>
        

  
    
