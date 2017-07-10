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
		$('#password').keyup(function(e) {
			if(e.keyCode == 13) {
				$('#action').val('login');	
				$('#coreForm').submit();
			}	
		});		
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
<h2>CORE SIS - User Login ver 2.0</h2>
<form name="coreForm" id="coreForm" method="post" action="">
<div id="loginBox" style=" <?=$action=='forgot_password'?'display:none;':''?>">
<?php
if(SYS_SET_SYSTEM == 'ON')
{
	$status = '<span class="green">ONLINE</span>';
}
else if(SYS_SET_SYSTEM == 'OFF')
{
	$status = '<span class="red">OFFLINE</span>';
}
?>
    <div class="caption">SYSTEM IS <?=$status?> [ON-GOING <?=getSchoolTerm(CURRENT_TERM_ID)?>, S.Y. <?=getSchoolYearStartEnd(CURRENT_SY_ID)?>]</div>
    <fieldset>
        <label>Username:</label>
        <span><input class="txt_250" name="username" type="text" value="" id="username" tabindex="1" />
        </span><br class="hid" />
        
        <label>Password:</label>
        <span><input class="txt_250" name="password" type="password" value="" id="password" tabindex="2" />
        </span><br class="hid" />
                  
        <p><a href="#" id="forgot_password">forgot password</a>&nbsp;&nbsp;<!--|&nbsp;&nbsp;<a href="student_applicant/index.php" id="new_applicant">new applicant?</a>!--></p>

    
        <br/>
      <a href="#" class="button" title="Login" id="btnlogin" tabindex="3"><span>Login</span></a>
        <span class="clear"></span>
    </fieldset>
</div>
<div id="forgotPasswordBox" style=" <?=$action!='forgot_password'?'display:none;':''?>">

    <div class="caption">To retrieve your password enter your account email address and 
    click Continue.</div>
    <fieldset>
    	
        <br class="hid" />
        <label>Email Address:</label>
        <span><input class="txt_250" name="email" type="text" value="" id="email" tabindex="1" />
        </span><br class="hid" />
        <label>&nbsp;</label>  
        
      	<a href="#" class="button" title="Continue" id="btncontinue" tabindex="3"><span>Continue</span></a>
        <a href="#" class="button" title="Cancel" id="btncancel" tabindex="3"><span>Cancel</span></a>
        <span class="clear"></span>
    </fieldset>
</div>
<input name="action" type="hidden" id="action" value="" />
</form>