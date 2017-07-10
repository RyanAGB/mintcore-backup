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

/*if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components'])||!isset($_REQUEST['comp']))
{
	header('Location: ../forbid.html');
}
else
{*/
?>
<script type="text/javascript">
	$(function(){
		$('.lookup_button').click(function() {	
			var pay = $('#payment_method').val();
			var dis = $('#discount_id').val();
			var amount_pay = $('#amount').val();
			var bank_branch = $('#bank').val();
			var check_number = $('#check_no').val();
			var pay_date = $('#p_year').val()+'-'+$('#p_month').val()+'-'+$('#p_day').val();
			var or = $('#OR').val();
			var rem = $('#remarks').val();
			var term = $('#filter_schoolterm').val();
			
		if(pay != '' && pay == 2)
		{
			if(amount_pay!='' && bank_branch!='' && check_number!='')
			{
			$('#payment_met').attr("value", pay);
			$('#discount').attr("value", dis);
			$('#amount_paid').attr("value", amount_pay);
			$('#check_num').attr("value", check_number);
			$('#bank_brn').attr("value", bank_branch);
			$('#or').attr("value", or);
			$('#rem').attr("value", rem);
			$('#term').attr("value", term);
			$('#total_discount').attr("value", $('#total').val());
			$('#payment_date').attr("value", pay_date);
			$('#add_new').addClass('active');
			$('#action').val('save');
			$('#view').val('add');
			$("form").submit();
			//alert(amount_pay);
			$('#dialog').dialog('close');
			}
			else
			{
				alert('Empty Field Found.');
				return false;
			}			
		}
		else if(pay!='' && (pay==1 || pay==3))
		{
			if(amount_pay!='')
			{
			$('#payment_met').attr("value", pay);
			$('#discount').attr("value", dis);
			$('#amount_paid').attr("value", amount_pay);
			$('#check_num').attr("value", check_number);
			$('#bank_brn').attr("value", bank_branch);
			$('#or').attr("value", or);
			$('#rem').attr("value", rem);
			$('#term').attr("value", term);
			$('#total_discount').attr("value", $('#total').val());
			$('#payment_date').attr("value", pay_date);
			$('#add_new').addClass('active');
			$('#action').val('save');
			$('#view').val('add');
			$("form").submit();
			//alert(amount_pay);
			$('#dialog').dialog('close');
			}
			else
			{
				alert('Invalid Empty Amount.');
				return false;
			}
		}
		else
		{
			alert('Invalid Payment Method.');
				return false;
		}
		});
	
});	

	function getMethod(id)
	{
			if($('#payment_method').val()=='')
			{
				$.ajax({
				type: "POST",
				data: "mod=updatePayment3",
				url: "ajax_components/ajax_com_payment_field_updater.php",
				success: function(msg){
					if (msg != ''){
						$("#chek").html(msg);
					}
				}
				});	
			}	
			else if(id == <?=getPaymentMethodCheque()?>)
			{
				$.ajax({
				type: "POST",
				data: "mod=updatePayment&id=" + id,
				url: "ajax_components/ajax_com_payment_field_updater.php",
				success: function(msg){
					if (msg != ''){
						$("#chek").html(msg);
					}
				}
				});	
			}else{
				$.ajax({
				type: "POST",
				data: "mod=updatePayment2&id=" + $('#payment_method').val(),
				url: "ajax_components/ajax_com_payment_field_updater.php",
				success: function(msg){
					if (msg != ''){
						$("#chek").html(msg);
					}
				}
				});	
			}	
	}

</script>

<?php
	$id = $_REQUEST['id'];

	$sql = "SELECT * FROM tbl_student WHERE id = $id";						
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);

?>
<div id="lookup_content">
<div id="printable" style="font-family:Arial;">
<table width=100% style=" font-family:Arial; font-size:12px;">
<tr>
    <td  style="font-size:15px; font-weight:bold; padding-top:20px;"><?=SCHOOL_NAME?>
    <div style="font-size:12px;">Payment Information</div>
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
</div>
<div class="fieldsetContainer50">
<table class="classic_borderless">
		<tr><td><strong>Select School Term</strong>
        </td><td><select name="filter_schoolterm" id="filter_schoolterm" class="txt_200">
    	<?=generateSchoolTerms(CURRENT_TERM_ID)?>
    </select>
        </td></tr>
             <tr>
            <td width="40%"><strong>Payment Date</strong></td>
            <td>
            
            <select name="p_month" id="p_month" class="txt_100 {required:true}" title="Month field is required">
                    <option value="" selected="selected"></option>
                    <?=generateMonth(date('m'))?>
                </select>
                
                <select name="p_day" id="p_day" class="txt_50 {required:true}" title="Day field is required">
                    <option value="" selected="selected"></option>
                    <?=generateDay(date('d'))?>
                </select>
                
                <select name="p_year" id="p_year" class="txt_70 {required:true}" title="Year field is required">
                    <option value="" selected="selected"></option>
                    <?=generateYearForSchoolYear(date('Y'))?>
                </select>
            
            </td>
            </tr> 
            <tr>
              <td width="40%"><strong>O.R Number</strong></td>
              <td>
              	  <input type="text" class="txt_150" name="OR" id="OR" value="<?=generateOR()?>" />        
              </td>
            </tr>   
             <tr>
              <td width="40%"><strong>Remarks</strong></td>
              <td>
              	  <input type="text" class="txt_150" name="remarks" id="remarks" value="<?=$remarks?>" />        
              </td>
            </tr>    
            <tr>
              <td width="40%"><strong>Payment Method</strong></td>
              <td>
                  <select name="payment_method" class="txt_100" id="payment_method" onchange="getMethod(this.value);">
                  <option value="">Select</option>
                        <?=generatePaymentMethod($payment_method)?>
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
              <a href="#" class="lookup_button" title="Save" id="save"><span>Save</span></a></td>
            </tr>                                    
          </table>
</div>
</div>
</div>
<?php
//}
?>