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
?>	
<script type="text/javascript">
$(document).ready(function(){  
	
	//initialize the tab action
	
	$('#save').click(function(){
		var im = document.getElementById("imports").value
		var imF = document.getElementById("importfile").value
		
		if(im != '' && imF != '')
		{
			clearTabs();
			$('#edit_item').addClass('active');
			$('#view').val('edit');
			$("form").submit();
			$('#importS').val(im);
			$('#importF').val(imF);
		}
		else
		{
			alert('Incomplete Files');
			clearTabs();
			$('#room_list').addClass('active');
			updateList();
			return false;
		}
	});	
	
	});
	</script>
    
<div class="box" id="box_container">
	<div class="formview">
    
        <fieldset>
           <legend><strong>Import Information</strong></legend>
            <label>Import File:</label>
            <span ><select name="imports" id="imports" class="txt_150" >
    
                    <option value="">Select</option>
                    <option value="subject">Subject</option>
                    <option value="course">Course</option>
                    <option value="student">Student</option>
                    <option value="employee">Employee</option>
        
                </select>
            </span><br class="hid" />
            <label>Import File:</label>
            <span><input class="txt_350" name="importfile" type="file" id="importfile" />
            </span><br class="hid" />
    
            <span class="clear"></span>
        </fieldset>
        
        <p class="button_container">
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
           
               <a href="#" class="button" title="Save" id="save"><span>Upload</span></a>
            
            
        </p>
        
    </div><!-- /.formview -->

<p id="formbottom"></p>
</div>

