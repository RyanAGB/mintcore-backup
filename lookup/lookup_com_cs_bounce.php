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

if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp']))
{
	header('Location: ../forbid.html');
}
else
{
?>
<script type="text/javascript">
	$(function(){
		$('.lookup_button').click(function() {	
			
			var check = $('#bounce_chek').val();
			
			$('#check_id').attr("value", check);
			$('#add_new').addClass('active');
			$('#action').val('bounce');
			$("form").submit();
			$('#dialog_2').dialog('close');
			
		});
	});
</script>

<?php
	$id = $_REQUEST['id'];
?>
	<div id="lookup_content">
<div id="printable" style="font-family:Arial;">
<table width=100% style=" font-family:Arial; font-size:12px;">
<tr>
    <td  style="font-size:15px; font-weight:bold; padding-top:20px;"><?=SCHOOL_NAME?>
    <div style="font-size:12px;">Cheque Information</div>
    </td>
    <td align=right style="padding-top:20px;">
    <!-- 20100214 Feb/14/2010-->
    <?=date("M/d/Y") ?>
    </td>
</tr>
<tr>
    <td colspan=2 style="border-top:1px solid #333;">
    	<!--<a class="viewer_email" href="#" id="email" title="email"></a>
        <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
        <a class="viewer_print" href="#" id="print" title="print"></a>!-->
    </td>
</tr>
</table>
<label>&nbsp;</label>
<div class="fieldsetContainer50">
<table class="classic_borderless">
             <!--<tr>
              <td width="40%"><strong>Payment</strong></td>
              <td>
                  <select name="payment_type" class="txt_100" id="payment_type">
                   <option value="1">Balance</option>
                    <option value="2">Refund</option>
              </select>              </td>
            </tr>!-->  
            <tr>
              <td width="40%"><strong>Cheque No:</strong></td>
              <td>
                  <select name="bounce_chek" class="txt_100" id="bounce_chek">
                  <option value="">Select</option>
                        <?=generateCheckNo($id)?>
              </select>              </td>
            </tr>  
            <tr>

                <td colspan="2">
                    <div id="chek">
                    </div>
                </td>

            </tr>   
             <tr>
              <td colspan="2">
              <a href="#" class="lookup_button" title="Bounce" id="bounce"><span>Bounce</span></a></td>
            </tr>                                    
          </table>
</div>
</div>
</div>
<?php
}
?>