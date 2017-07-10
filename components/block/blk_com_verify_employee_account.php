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

<script type="text/javascript">
    $(function(){
		$('#btnlogin').click(function(){
			$('#action').val('login');
			$("form").submit();
		});
		$('#btncontinue').click(function(){
			$('#action').val('forgot_password');
			$("form").submit();
		});	
		$('#btncancel').click(function(){
			$('#forgotPasswordBox').hide();
			$('#loginBox').show();
			$('#action').val('login');	
		}) ;			
	});	
	$(document).ready(function(){  
		$('#forgot_password').click(function(){
			$('#loginBox').hide();
			$('#forgotPasswordBox').show();
			$('#action').val('forgot');
			$('.alert').hide();
		}) ;
		$('#lnklogin').click(function(){
			$('#forgotPasswordBox').hide();
			$('#loginBox').show();
			$('#action').val('login');
		}) ;

	});	
</script>
<?php
if($err_msg != '')
{
?>
    <p class="alert">
        <span class="txt"><span class="icon"></span><strong>Alert:</strong> <?=$err_msg?></span>
        <a href="#" class="close" title="Close"><span class="bg"></span>Close</a>
    </p>
<?php
}
?>
<h2>CORE SIS - User Verify Account ver 2.0</h2>
<form method="post" action="">
<div id="loginBox">
    <fieldset>
    	<label>
        <br class="hid" />
		<?php 
        if (isset($msgs) and ($msgs != "")) 
        {
            echo $msgs;
        } 
        ?>
        <br class="hid" />
        </label>
    </fieldset>
</div>
<input name="action" type="hidden" id="action" value="" />
</form>