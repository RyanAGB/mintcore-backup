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


include_once('../config.php');
include_once('../includes/functions.php');
include_once('../includes/common.php');

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp']))
{
	header('Location: ../forbid.html');
}
else
{

$selected_room_id = $_REQUEST['selected_room_id'];
?>
<script type="text/javascript">
	$(function(){
		$('.selector').click(function() {
			//var id = $(this).attr("val");		
			var valTxt = $(this).attr("returnTxt");
			var valId = $(this).attr("returnId");
			$('#emp_id').attr("value", valId);
			$('#emp_id_display').attr("value", valTxt);
			
			$('#dialog').dialog('close');			
		});
	});
</script>

<?php

$sql = "SELECT * FROM tbl_employee emp, tbl_employee_info emp_info
		WHERE emp.id = emp_info.employee_id";		
$result = mysql_query($sql);
?>
<div id="lookup_content">

<label>This room is only Available during this period.</label>
                <table class="fieldsetList">
                  <tr>
                    <td>Monday</td>
                    <td>06:00 AM - 10:00AM<br />12:00 NN - 01:00PM<br />02:00 PM - 06:00PM</td>
                  </tr>
                  <tr>
                    <td>Tuesday</td>
                    <td>06:00 AM - 10:00AM<br />12:00 NN - 01:00PM<br />02:00 PM - 06:00PM</td>
                  </tr>
                  <tr>
                    <td>Wedsnesday</td>
                    <td>06:00 AM - 10:00AM<br />12:00 NN - 01:00PM<br />02:00 PM - 06:00PM</td>
                  </tr>
                  <tr>
                    <td>Thursday</td>
                    <td>06:00 AM - 10:00AM<br />12:00 NN - 01:00PM<br />02:00 PM - 06:00PM</td>
                  </tr>
                  <tr>
                    <td>Friday</td>
                    <td>06:00 AM - 10:00AM<br />12:00 NN - 01:00PM<br />02:00 PM - 06:00PM</td>
                  </tr>    
                  <tr>
                    <td>Saturday</td>
                    <td>06:00 AM - 10:00AM<br />12:00 NN - 01:00PM<br />02:00 PM - 06:00PM</td>
                  </tr>    
                  <tr>
                    <td>Sunday</td>
                    <td>06:00 AM - 10:00AM<br />12:00 NN - 01:00PM<br />02:00 PM - 06:00PM</td>
                  </tr>                                                                                                            
                </table>
    

<form method="post" enctype="multipart/form-data" name="lookUpForm">   
    <label>Select Day and Time of Room Availability</label>
     
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
            <table border="0" cellspacing="10" cellpadding="0">
                <tr>
                    <td>    
                        <table>
                            <tr>
                                <td><input name="chkday[]" type="checkbox" id="chkday_mon" value="M" /></td>
                                <td><label for="chkday_mon">Monday</label></td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td><input name="chkday[]" type="checkbox" id="chkday_tue" value="T" /></td>
                                <td><label for="chkday_tue">Tuesday</label></td>
                            </tr>
                        </table>            
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td><input name="chkday[]" type="checkbox" id="chkday_wed" value="W" /></td>
                                <td><label for="chkday_wed">Wednesday</label></td>
                            </tr>
                        </table>            
                    </td>  
                   <td>
                        <table>
                            <tr>
                                <td><input name="chkday[]" type="checkbox" id="chkday_thu" value="TH" /></td>
                                <td><label for="chkday_thu">Thursday</label></td>
                            </tr>
                        </table>            
                    </td> 
                   <td>
                        <table>
                            <tr>
                                <td><input name="chkday[]" type="checkbox" id="chkday_fir" value="F" /></td>
                                <td><label for="chkday_fir">Friday</label></td>
                            </tr>
                        </table>            
                    </td> 
                   <td>
                        <table>
                            <tr>
                                <td><input name="chkday[]" type="checkbox" id="chkday_sat" value="S" /></td>
                                <td><label for="chkday_sat">Saturday</label></td>
                            </tr>
                        </table>            
                    </td> 
                   <td>
                        <table>
                            <tr>
                                <td><input name="chkday[]" type="checkbox" id="chkday_sun" value="SU" /></td>
                                <td><label for="chkday_sun">Sunday</label></td>
                            </tr>
                        </table>            
                    </td>                                                           
                </tr>
            </table>    
        </td>
      </tr>
      <tr>
        <td>
        <table border="0" cellspacing="10" cellpadding="0">
          <tr>
            <td>
                <table>
                  <tr>
                    <td>From</td>
                    <td>
                    <select name="from_time" id="from_time">
                    <?=generateTime($from_time,'1',SCHOOL_OPEN_TIME,SCHOOL_CLOSE_TIME)?>                
                    </select>
                    </td>
                  </tr>
                </table>
            </td>
            <td>
                <table>
                  <tr>
                    <td>To</td>
                    <td>
                    <select name="to_time" id="to_time">
                    <?=generateTime($to_time,'1',SCHOOL_OPEN_TIME,SCHOOL_CLOSE_TIME)?>                
                    </select>
                    </td>
                  </tr>
                </table>
            </td>
          </tr>
        </table>
    
        </td>
      </tr>
    </table>   
    <input type="hidden" id="selected_room_id" name="selected_room_id" value="<?=$selected_room_id?>" />
    <input type="hidden" name="temp" id="temp" value="" />
    <input type="hidden" name="action" id="action" value="save_avail" />
    <input type="hidden" name="view" id="view" value="list" />
    <input type="hidden" name="comp" id="comp" value="com_room" />    
    <input name="" type="submit" />
    
</form>
</div> <!-- #lookup_content -->
<?php
}
?>