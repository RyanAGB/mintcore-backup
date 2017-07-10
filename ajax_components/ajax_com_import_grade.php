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
	
	if(USER_IS_LOGGED != '1')
	{
		header('Location: ../index.php');
	}
	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}
?>

<script type="text/javascript">
$(document).ready(function(){  

	$('#upload').click(function(){
		var imF = document.getElementById("importfile").value

		if(imF != '')
		{
			clearTabs();
			$('#map').addClass('active');
			$('#view').val('map');
			$('#import').val(imF);
			$('#action').val('uplod');
			$("form").submit();
		}
		else
		{
			alert('No file selected');
			clearTabs();
			$('#import').addClass('active');
			//updateList();
			return false;
		}
	});	
});
	</script>
        
        <div class="formview">
    <div class="fieldsetContainer50">
        <fieldset>
           <legend><strong>Required Fields</strong></legend>
            <label></label>
            <label> - Student Number</label>
            <span >
            </span><br class="hid" />
             <label> - Subject Code</label>
            <span >
            </span><br class="hid" />
            <label> - Final Grade</label>
            <span >
            </span><br class="hid" />
        </fieldset>
        </div>
         <div class="fieldsetContainer50">
        <fieldset>
           <legend><strong>Import File</strong></legend>
            <label>Upload:</label>
            <span >
               <input class="txt_250" name="importfile" type="file" id="importfile" />
            </span><br class="hid" /> 
          
            <span class="clear"></span>
        </fieldset>
   
        <p class="button_container">

            <input type="hidden" name="id" id="id" value="<?=$id?>" />
           
               <a href="#" class="button" title="Upload" id="upload"><span>Upload</span></a>
            
            
        </p>
        
    </div><!-- /.formview --></div> 
  <p id="formbottom"></p>
