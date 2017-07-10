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

if(!isset($_REQUEST['comp']))
{
include_once("../../config.php");
include_once("../../includes/functions.php");
include_once("../../includes/common.php");
}
	
if(USER_IS_LOGGED != '1')
	{
		header('Location: ../../index.php');
	}
	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../../forbid.html');
	}
	else if(in_array($comp,$_SESSION[CORE_U_CODE]['can_edit_comp']))
	{
		$canEdit='N';
	}else{
		$canEdit='Y';
	}
?>

<script type="text/javascript">
$(document).ready(function(){  


	var validator = $("#coreForm").validate({
	errorLabelContainer: $("div.error"),
		rules: {
			email: {
				required: true,
				email: true
			}
		},
		messages: {
			email: "Please enter a valid email address"
		}	
	});
	
	//initialize the tab action
	$('#add_new').click(function(){
			validator.resetForm();
			clearTabs();
			$('#add_new').addClass('active');
			$('#view').val('add');
			$("form").submit();
	});
	
	$('#edit_item').click(function(){
		if(checkbox_checker())
		{
			validator.resetForm();
			clearTabs();
			$('#edit_item').addClass('active');
			$('#view').val('edit');
			$("form").submit();
		}
		else
		{
			validator.resetForm();
			alert('No item was selected.');
			updateList();
			return false;
		}
	});
	
	$('#room_list').click(function(){
		clearTabs();
		$('#room_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	

	$('#import').click(function(){
		clearTabs();
		$('#import').addClass('active');
		$('#view').val('import');
		$('#box_container').html(loading);
		$("form").submit();
	});
	
	$('#upload').click(function(){
		var imF = document.getElementById("importfile").value
		
		if(imF != '')
		{
			clearTabs();
			$('#map').addClass('active');
			$('#view').val('map');
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

	$('#save').click(function(){
		clearTabs();
		$('#add_new').addClass('active');
		$('#action').val('save');
		$('#view').val('add');
		$("form").submit();
	});	
	
	$('#import_fin').click(function(){
		clearTabs();
		$('#import').addClass('active');
		$('#action').val('import_fin');
		$('#view').val('import');
		$("form").submit();
		updateList();
	});	
	
	$('#update').click(function(){
		clearTabs();
		$('#edit_item').addClass('active');
		$('#action').val('update');
		$('#view').val('edit');
		$("form").submit();
	});	
	
		//var add = document.getElementById('admission_type').value;
		//var cor = document.getElementById('course_id').value;
	
	$('#course_id').change(function(){
		
		if($('#view').val()=='add')
		{
			$.ajax({
					type: "POST",
					data: "mod=updateNumber&id=" + $('#course_id').val(),
					url: "ajax_components/ajax_com_year_field_updater.php",
					success: function(msg){
						if (msg != ''){
							$("#student_number").val(msg);
						}
					}
					});		
		}
		
	  if($('#admission_type').val()!='')
	  {
					$.ajax({
					type: "POST",
					data: "mod=updateYear&id=" + $('#course_id').val() +"&ad=" + $('#admission_type').val(),
					url: "ajax_components/ajax_com_year_field_updater.php",
					success: function(msg){
						if (msg != ''){
							$("#year_level").html(msg);
						}
					}
					});		
	  }
	});
	
	$('#admission_type').change(function(){
				
				if($('#course_id').val()!='')
				{
					$.ajax({
					type: "POST",
					data: "mod=updateYear&id=" + $('#course_id').val() +"&ad=" + $('#admission_type').val(),
					url: "ajax_components/ajax_com_year_field_updater.php",
					success: function(msg){
						if (msg != ''){
							$("#year_level").html(msg);
						}
					}
					});
				}
				else
				{
					alert('Select Course');
					return false;
				}		
		});
		
		$('#last_school').change(function(){

			if($('#last_school').val()==0)
			{
				document.getElementById('last_school_1').style.display='block';
				$('#last_school_code').attr('disabled','');
				$('#last_school_type').attr('disabled','');
				$('#last_school_address').attr('disabled','');
			}
			else
			{
				document.getElementById('last_school_1').style.display='none';
				$('#last_school_code').attr('disabled','disabled');
				$('#last_school_type').attr('disabled','disabled');
				$('#last_school_address').attr('disabled','disabled');
				
				$.ajax({
				type: "POST",
				data: "id=" + $('#last_school').val(),
				url: "ajax_components/ajax_com_school_field_updater.php",
				success: function(msg){
					if (msg != ''){
						var arr = msg.split(',');
						//alert (msg);
						$('#last_school_code').attr('value', arr[0]);
						$('#last_school_type').attr('value', arr[1]);
						$('#last_school_address').attr('value', arr[2]);
					}
				}
				});
			}	
	});

	// Initialize the list
	<?php
	if($view != 'add' && $view != 'edit' && $view != 'import' && $view != 'map' && $view != 'uplod')
	{
		echo 'updateList();'; 
	}
	?>

	$('#cancel').click(function(){
		clearTabs();
		$('#room_list').addClass('active');
		$('#view').val('list');
		$('#action').val('list');
		updateList();
		$("form").submit();
	});	
	
	$('#page_rows').change(function(){
		$('#page').val(1);
		updateList();
	});		
	
});	

function checkSuffix(val)
{

	$('#mr').attr("checked",'');
	$('#mrs').attr("checked",'');
	$('#ms').attr("checked",'');
	$('#other').attr("checked",'');
	
	$('#'+val).attr("checked",'checked');
	
	if(val=='other')
	{
		$('#suffix').attr("disabled",'');
		$('#suffix').focus();
	}
	else
	{
		$('#suffix').attr("disabled",'disabled');
	}
	
	$('#suf').val(val);
}

function clearTabs()
{
	$('ul.tabs li').attr('class',''); // clear all active
}

function updateList(pageNum)
{
	var param = '';
	
	$('#page').val(pageNum);
	if($('#page').val() != '' && $('#page').val() != undefined)
	{
		param = param + '&pageNum=' + $('#page').val();
	}
	
	if($('#filter_field').val() != '' && $('#filter_field').val() != undefined && $('#filter_order').val() != '' && $('#filter_order').val() != undefined )
	{
		param =  param + '&fieldName=' + $('#filter_field').val() + '&orderBy=' + $('#filter_order').val();
		
	}
		
	if($('#page_rows').val() != '' && $('#page_rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#page_rows').val();
	}
	
	if($('#canEdit').val() != '' && $('#canEdit').val() != undefined )
	{
		param = param + '&canEdit=' + $('#canEdit').val();
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
	
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_student_profile.php?param=1' + param, null);
}

function onlyNumbers(evt)
{
	var e = event || evt; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;

	if (charCode != 42 && (charCode > 31 && (charCode < 48 || charCode > 57)))
	{
		alert("Zip Code should be numbers only.");
		return false;
	}
	else
	{
		return true;
	}

}
</script>
<script type="text/javascript">

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

<div class="error"></div>

<h2><?=$page_title?></h2>
<ul class="tabs">
<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Student List"><span>Student List</span></a></li>
<?php
	if($canEdit=='Y')
	{
?>
<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>
<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit Item"><span>Edit Item</span></a></li>
<?php } ?>

<?php
	if(ACCESS_ID==1)
	{
?>
<li id="import" <?=$view=='import'?'class="active"':''?>><a href="#" title="Import"><span>Import</span></a></li>
<li id="map" <?=$view=='map'?'class="active"':''?>><a href="#" title="Map"><span>Map</span></a></li>
<?php } ?>
</ul>
<form name="coreForm" id="coreForm" method="post" action="" class="col50" enctype="multipart/form-data">
<?php
	if($view == 'list')
	{
		if(isset($_SESSION[CORE_U_CODE]['pageRows'])&&($_SESSION[CORE_U_CODE]['pageRows']!=''))
		{
			$p_row = $_SESSION[CORE_U_CODE]['pageRows'];
		}
		else if($_SESSION[CORE_U_CODE]['default_record']!='')
		{
			$p_row = $_SESSION[CORE_U_CODE]['default_record'];
		}
		else
		{
			$p_row = DEFAULT_RECORD;
		}	
		
?>
     <div id="pageRows">
        <span>show</span>
        <select name="page_rows" id="page_rows">
          <option value="<?=$_SESSION[CORE_U_CODE]['default_record']!='' ? $_SESSION[CORE_U_CODE]['default_record']:DEFAULT_RECORD?>"<?=$p_row==DEFAULT_RECORD||$p_row==$_SESSION[CORE_U_CODE]['default_record'] ? 'selected=selected':''?>>Default</option>
          <option value="10"<?=$p_row==10 ? 'selected=selected':''?>>10</option>
          <option value="20"<?=$p_row==20 ? 'selected=selected':''?>>20</option>
          <option value="50"<?=$p_row==50 ? 'selected=selected':''?>>50</option>
          <option value="100"<?=$p_row==100 ? 'selected=selected':''?>>100</option>
          <option value="150"<?=$p_row==150 ? 'selected=selected':''?>>150</option>            
        </select>
    </div>
<?php
	} // end of page rows 
?>
<div class="box" id="box_container">
<?php
	if($view == 'edit' || $view == 'add')
	{
		
?>
	<div class="formview">
    <div class="fieldsetContainer50">
		<fieldset>
           <legend><strong>Student Balances</strong></legend>
            <?php
                        if(ACCESS_ID==1)
						{
						?><label>IPAD Balance</label>
                <span >
                <input class="txt_300"  name="ibalance" type="text" value="<?=$ibalance?>" id="ibalance" />
                </span><br class="hid" />
                
                <label>Old Balance</label>
                <span >
                <input class="txt_300"  name="balance" type="text" value="<?=$balance?>" id="balance" />
                </span><br class="hid" />
                
                <?php }
						?>
        </fieldset>
		<fieldset>
           <legend><strong>Student Information</strong></legend>
           
           		<input name="mr" id="mr" type="checkbox" value="mr" onclick="checkSuffix('mr');" <?=$suffix=='mr'?'checked="checked"':''?> />
                 Mr.
                <input name="mrs" id="mrs" type="checkbox" value="mrs" onclick="checkSuffix('mrs');" <?=$suffix=='mrs'?'checked="checked"':''?>/>
                 Mrs.
                <input name="ms" id="ms" type="checkbox" value="ms" onclick="checkSuffix('ms');" <?=$suffix=='ms'?'checked="checked"':''?>/>
                 Miss
                <input name="other" id="other" type="checkbox" value="other" onclick="checkSuffix('other');" <?=$suffix=='other'?'checked="checked"':''?>/>
                Other
                <input name="suffix" id="suffix" type="text" class="txt_150" <?=$suffix!='other'?'disabled="disabled"':'value="'.$suffix.'"'?>/></label>
         
                <label>Lastname</label>
                <span >
                <input class="txt_300 {required:true}" title="Lastname field is required" name="lastname" type="text" value="<?=$lastname?>" id="lastname" />
                </span><br class="hid" />
                <label>Firstname</label>
                <span >
                <input class="txt_300 {required:true}" title="Firstname field is required" name="firstname" type="text" value="<?=$firstname?>" id="firstname" />
                </span><br class="hid" /> 
                <label>Middlename</label>
                <span >
                <input class="txt_300 {required:true}" title="Middlename field is required" name="middlename" type="text" value="<?=$middlename?>" id="middlename" />
                <label>Nickname</label>
                <span >
                <input class="txt_150" title="Nickname" name="nickname" type="text" value="<?=$nickname?>" id="nickname" />
                </span><br class="hid" />        
         
                <label>Date of Birth</label>
                <span >
                <select name="b_month" id="b_month" class="txt_100 {required:true}" title="month field is required">
                <option value="" >Month</option>
                <?=generateMonth($b_month)?>
               	</select>
                <select name="b_day" id="b_day" class="txt_70 {required:true}" title="day field is required">
                  <option value="" selected="selected">Day</option>
                  <?=generateDay($b_day)?>                                                                        
                </select>
                <select name="b_year" id="b_year" class="txt_70 {required:true}" title="year field is required">
                  <option value="" selected="selected">Year</option>
                  <?=generateYear($b_year)?>                                                    
                </select>              
                </span><br class="hid" />
                <label>Place of Birth</label>
                <span >
                <input class="txt_250 {required:true}" title="Place of Birth field is required" name="birth_place" type="text" value="<?=$birth_place?>" id="birth_place" />
                </span><br class="hid" />
                <label>Citizenship</label>
                <span >
                <select name="citizenship" id="citizenship" class="txt_250 {required:true}" title="Citizenship field is required">
                  <option value="" selected="selected">Select</option>
                  <?=generateCitizenship($citizenship)?>
                </select>
                </span><br class="hid" />
               <!-- <label>Religion</label>
                <span >
                    <select name="religion" id="religion" class="txt_250 {required:true}" title="Religion field is required">
                      <option value="" selected="selected">Select</option>
                      <?=generateReligion($religion)?>
                    </select>
                 </span><br class="hid" />!-->
                    <label>Sex or Gender</label>
                    <span >
                    <select name="gender" id="gender" class="txt_100 {required:true}" title="Sex or Gender field is required">
                        <option value="" >Select</option>
                        <option value="M" <?php if($gender== "M") { ?>
                        selected = "selected"
                        <?php } ?>
                        >Male</option>
                        <option value="F" <?php if($gender== "F") { ?>
                        selected = "selected"
                        <?php } ?> 
                        >Female</option>
                    </select>
                    </span><br class="hid" />  
                    <label>Civil Status</label>
                    <span >
                    <select name="civil_status" id="civil_status" class="txt_100 {required:true}" title="Civil Status field is required">
                        <option value="" >Select</option>
             			<option value="S" <?=$civil_status== "S"?'selected = "selected"':''?> >Single</option>
						<option value="M" <?=$civil_status== "M"?'selected = "selected"':''?> >Married</option>
                    </select>
                    </span><br class="hid" /> 
                    <td><label>Home Address</label>
                    <input name="home_address" id="home_address" value="<?=$home_address?>" type="text" class="txt_300"/>
                    <label>City</label>
                    <input name="city" id="city" value="<?=$city?>" type="text" class="txt_100" />
          		<label>Country</label>
          		<select name="country" id="country" class="txt_300 required" title="country field is required">
            <option value="" selected="selected">Select</option>
            <?=generateCountry($country)?>
          </select>     
          <label>Phone</label>
          <input name="tel_number" id="tel_number" value="<?=$tel_number?>" type="text" class="txt_150 required" title="phone field is required"/>
          <label>Mobile Phone</label>
          <input name="mobile_number" id="mobile_number" value="<?=$mobile_number?>" type="text" class="txt_150 required" title="mobile phone field is required"/>
          <label>Fax</label>
          <input name="fax" id="fax" value="<?=$fax?>" type="text" class="txt_150"/>
          <label>Postal Code</label>
          <input name="home_address_zip" id="home_address_zip" value="<?=$home_address_zip?>" type="text" class="txt_100" onkeypress="return onlyNumbers();"/>
          <label>Email</label>
          <input name="email" id="email" type="text" value="<?=$email?>" class="txt_200 required" title="email field is required"/>
          <label>Confirm Email</label>
          <input name="con_email" id="con_email" value="<?=$con_email?>" type="text" class="txt_200 required" title="confirm email field is required"/>                
            <span class="clear"></span>
        </fieldset>
        <fieldset>
           <legend><strong>Previous School Details</strong></legend>
           
           <label>Grade School:</label>
            <input class="txt_300" title="grade school field is required" name="grade_school" type="text" value="<?=$grade_school?>" id="grade_school" />
          <label>Address:</label>
            <input class="txt_300" name="grade_school_address" type="text" value="<?=$grade_school_address?>" id="grade_school_address" /><label>Awards / Recognition:</label></td>
            <input name="grade_school_award" class="txt_300" id="grade_school_award" value="<?=$grade_school_award?>"/>
            <label>Years Attended:</label> 
            <select name="grade_school_month_fr" id="grade_school_month_fr" class="txt_150" >
              <option value="" >Month</option>
              <?=generateMonth($grade_mfr)?>
            </select>
              <select name="grade_school_year_fr" id="grade_school_year_fr" class="txt_100" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($grade_yfr)?>
              </select>
              <label class="dust">to</label>
              <select name="grade_school_month_to" id="grade_school_month_to" class="txt_150" >
                <option value="" >Month</option>
                <?=generateMonth($grade_mto)?>
              </select>
              <select name="grade_school_year_to" id="grade_school_year_to" class="txt_100" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($grade_yto)?>
              </select>
              <label>High School:</label>
            <input class="txt_300" name="high_school" type="text" value="<?=$high_school?>" id="high_school" />
            <label>Address:</label>
            <input class="txt_300" name="high_school_address" type="text" value="<?=$high_school_address?>" id="high_school_address" />
            <label>Awards / Recognition:</label>
            <input name="high_school_award" class="txt_300" id="high_school_award" />
            <label>Years Attended:</label>             
           <select name="high_school_month_fr" id="high_school_month_fr" class="txt_150" >
              <option value="" >Month</option>
              <?=generateMonth($high_mfr)?>
            </select>
              <select name="high_school_year_fr" id="high_school_year_fr" class="txt_100" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($high_yfr)?>
              </select>
              <label class="dust">to</label>
              <select name="high_school_month_to" id="high_school_month_to" class="txt_150" >
                <option value="" >Month</option>
                <?=generateMonth($high_mto)?>
              </select>
              <select name="high_school_year_to" id="high_school_year_to" class="txt_100" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($high_yto)?>
              </select>
              <label>College School:</label>
            <input class="txt_300" name="college_school" type="text" value="<?=$college_school?>" id="college_school" />
            <label>Address:</label>
            <input class="txt_300" name="college_school_address" type="text" value="<?=$college_school_address?>" id="college_school_address" />
           <label>Awards / Recognition:</label>
            <input name="college_school_award" class="txt_300" id="college_school_award" value="<?=$college_school_award?>"/>
            <label>Years Attended:</label>
            <select name="college_school_month_fr" id="college_school_month_fr" class="txt_150" >
              <option value="" >Month</option>
              <?=generateMonth($college_mfr)?>
            </select>
              <select name="college_school_year_fr" id="college_school_year_fr" class="txt_100" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($college_yfr)?>
              </select>
              <label class="dust"> to </label>
              <select name="college_school_month_to" id="college_school_month_to" class="txt_150">
                <option value="" >Month</option>
                <?=generateMonth($college_mto)?>
              </select>
              <select name="college_school_year_to" id="college_school_year_to" class="txt_100" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($college_yto)?>
              </select>              </td>
           
           <!-- <label>Last School Attended</label>
            <span>
            <select name="last_school" id="last_school" class="txt_300 {required:true}" >
                  <option value="" selected="selected">Select</option>
                  <?=generateLastSchool($last_school)?>
                  <option value=0 <?=$last_school==0?'selected="selected"':''?>>OTHERS</option>
            </select>  
            <p>
            <input class="txt_300" name="last_school_1" type="text" value="<?=$last_school_1?>" id="last_school_1" <?=$last_school==0?'style="display:block"':'style="display:none"'?> >
            </span><br class="hid" />
         <span>
            <label>School Code</label>
        <span >
        <input class="txt_200 {required:true}" name="last_school_code" type="text" value="<?=$last_school_code?>" id="last_school_code" <?=$last_school!=0?'disabled="disabled"':''?> />
    </span><br class="hid" />
<label>Address</label>
            <span >
            <input class="txt_300 {required:true}" name="last_school_address" type="text" value="<?=$last_school_address?>" id="last_school_address" <?=$last_school!=0?'disabled="disabled"':''?> />   
            </span><br class="hid" />  
            <label>Type of School</label>
            <span >
                <select name="last_school_type" id="last_school_type" class="txt_100 {required:true}" <?=$last_school!=0?'disabled="disabled"':''?>>
                  	<option value="" >Select</option>
                    <option value="PRI" <?php if($last_school_type== "PRI") { ?>
                    selected = "selected"
                    <?php } ?>
                     >Private</option>
                    <option value="PUB" <?php if($last_school_type== "PUB") { ?>
                    selected = "selected"
                    <?php } ?> 
                    >Public</option>
                </select>            
                </span><br class="hid" />  
               </span>                                                         
          
            <span class="clear"></span>!-->
        </fieldset>
        
        
      </div>
      
      <div class="fieldsetContainer50">
      <?php
                        if(ACCESS_ID==1)
						{
						?>
       <fieldset>
               <legend><strong>Student Photo</strong></legend>
                    <label>Upload Photo</label>
                    <span>
                    <br />
					<?php
                    if($image_file != '')
                    {
                    ?>
                        <img src="includes/student_image.php?student_id=<?=$id?>"/>
                    <?php
                    }
                    else
                    {
                    ?>
                        <img src="images/NoPhotoAvailable.jpg"/>
                    <?php
                    }
                    ?>
                    <br />
                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                    <input class="txt_250" name="student_photo" type="file" id="student_photo" />
                    </span>
                    <p>(*maximum size: 120x120)</p>
                    <br class="hid" /><br class="hid" />  
        </fieldset>
        
        <fieldset>
           <legend><strong>Admission Details</strong></legend>
             <label>Course</label>
            <span >
                <select name="course_id" id="course_id"  class="txt_300 {required:true}" title="Course field is required">
                <option value="" selected="selected">Select</option>
                <?=generateCourse($course_id)?>
                </select>   
            </span><br class="hid" /> 
            <!--  
            <label>Applicable Semester</label>
            <span >
                <select name="year_level" id="year_level"  class="txt_300">
                    <option value ="">Select</option>
                    <option value="1">1st</option>
                    <option value="2">2nd</option>
                </select>    
            </span><br class="hid" /> 
            -->                    
              <label>Admission As</label>
            <span >
                <select name="admission_type" id="admission_type"  class="txt_300 {required:true}" title="Admission As field is required">
                	<option value ="">Select</option>
                    <option value="F" <?php if($admission_type== "F"){ ?> selected = "selected" <?php } ?>>Freshman</option>
                    <option value="T" <?php if($admission_type== "T"){ ?> selected = "selected" <?php } ?>>Transferee</option>
                    <!--
                    <option value="D" <?php if($admission_type== "D"){ ?> selected = "selected" <?php } ?>>Degree Holder</option>
                    <option value="C" <?php if($admission_type== "C"){ ?> selected = "selected" <?php } ?>>Cross Registrant</option>
                    -->
                </select>
            </span><br class="hid" /> 
             <label>Year Level</label>
            <span >
                <select name="year_level" id="year_level"  class="txt_300 {required:true}" title="Year field is required">
                <option value="" selected="selected">Select</option>
                <?=generateYearLevel($year_level)?>
                </select>   
            </span><br class="hid" />         
          	<label>Student Number</label>
                <span >
                <input class="txt_200 {required:true}" title="Student Number field is required" name="student_number" type="text" value="<?=$student_number?>" id="student_number" />
                </span><br class="hid" />
            <span class="clear"></span>
        </fieldset> 
        <?php
						}
						?>
        <!--++LIBRARY-->
        <!--<fieldset>
           <legend><strong>Library Details</strong></legend>
             <label>Classification</label>
            <span >
                <select name="lib_classification" id="lib_classification"  class="txt_200" title="Classification field is required">
                <option value="" selected="selected">Select</option>
                <?=generateLibClassfy($lib_classification)?>
                </select>   
            </span><br class="hid" />          
          	<label>Library Number</label>
                <span >
                <input class="txt_200" title="Library Number field is required" name="lib_card_number" type="text" value="<?=$lib_card_number?>" id="lib_card_number" />
                </span><br class="hid" />
            <span class="clear"></span>
        </fieldset> !-->
        <!--LIBRARY-->
        <fieldset>
           <legend><strong>Guardian Infomation</strong></legend>
            <label>Name</label>
            <span >
            <input class="txt_300 {required:true}" title="First name field is required" name="guardian_name" type="text" value="<?=$guardian_name?>" id="guardian_name" />   
            </span><br class="hid" />
             <label>Profession</label>
          <input class="txt_200" name="guardian_occupation" type="text" value="<?=$guardian_occupation?>" id="guardian_occupation" title="profession field is required"/>
          <label>Company</label>
          <input class="txt_300" name="guardian_company" type="text" value="<?=$guardian_company?>" id="guardian_company" title="company field is required"/>
           <!-- <label>Middlename</label>
            <span >
            <input class="txt_200 {required:true}" title="Middle name field is required" name="guardian_middlename" type="text" value="<?=$guardian_middlename?>" id="guardian_middlename" />   
            </span><br class="hid" /> 
             
            <label>Lastname</label>
            <span >
            <input class="txt_200 {required:true}" title="Last name field is required" name="guardian_lastname" type="text" value="<?=$guardian_lastname?>" id="guardian_lastname" />   
            </span><br class="hid" />  !-->
            <label>Relation</label>
            <span >
                <select name="guardian_relation" id="guardian_relation" class="txt_100 {required:true}" title="Type of relation is required">
                  	<option value="" >Select</option>
					<option value="parent" <?=$guardian_relation== "parent"?'selected = "selected"':''?> >Parent</option>
					<option value="relative" <?=$guardian_relation== "relative"?'selected = "selected"':''?> >Relative</option>
                    <option value="other" <?=$guardian_relation== "other"?'selected = "selected"':''?> >Other</option>
                </select>            
                </span><br class="hid" />                                                            
          	<label>Email Address</label>
            <span >
            <input class="txt_200" title="Guardian Email field is required" name="guardian_email" type="text" value="<?=$guardian_email?>" id="guardian_email" />   
            </span><br class="hid" />
            
            <label>Confirm Email Address</label>
            <span >
            <input class="txt_200" title="Confirm Guardian Email field is required" name="con_guardian_email" type="text" value="<?=$guardian_email?>" id="con_guardian_email" />   
            </span><br class="hid" />
            <label>Mailing Address</label>
            <input class="txt_300" name="guardian_address" type="text" value="<?=$guardian_address?>" id="guardian_address" />
            <label>City</label>
            <input class="txt_150" name="guardian_city" type="text" value="<?=$guardian_city?>" id="guardian_city" />
          <label>Country</label>
          <select name="guardian_country" id="guardian_country" class="txt_300" title="guardian country field is required" >
            <option value="" selected="selected">Select</option>
            <?=generateCountry($guardian_country)?>
        </select>
        <label>Home Phone</label>
        <input class="txt_100" name="guardian_tel_number" type="text" value="<?=$guardian_tel_number?>" id="guardian_tel_number" title="home phone field is required" />
          <label>Work Phone</label>
          <input class="txt_100" name="guardian_work_number" type="text" value="<?=$guardian_work_number?>" id="guardian_work_number" title="work number field is required" />
          <label>Fax</label>
          <input class="txt_100" name="guardian_fax" type="text" value="<?=$guardian_fax?>" id="guardian_fax" />
          <label>Postal Code</label>
          <input class="txt_100" name="guardian_address_zip" type="text" value="<?=$guardian_address_zip?>" id="guardian_address_zip" onkeypress="return onlyNumbers();" maxlength="10"/>
            <span class="clear"></span>
        </fieldset>
        <fieldset>
               <legend><strong>Additional Information</strong></legend>
                    <label>Language:</label>
                    <input class="txt_300" name="language" type="text" value="<?=$language?>" id="language" />
                    <label>Extra Curricular Activities and Leadership Roles:</label>
                    <input class="txt_300" name="extra_curricular" type="text" value="<?=$extra_curricular?>" id="extra_curricular" />
                    <br class="hid" /><br class="hid" />  
        </fieldset>
        <?php
                        if(ACCESS_ID==1)
						{
						?>
         <fieldset>
               <legend><strong>Scholarship</strong></legend>
               <label>Entrance Exam Grade:</label>
           <input class="txt_100" name="exam_grade" type="text" value="<?=$exam_grade?>" id="$exam_grade" />
                    <label>Percentage:</label>
           <input class="txt_100" name="scholarship" type="text" value="<?=$scholarship?>" id="scholarship" />
                    <label>Scholarship Type:</label>
                    <select name="scholarship_type" id="scholarship_type" class="txt_150" title="scholarship type field is required">
                      <option value="SFA" <?=$scholarship_type=="SFA"?"selected='selected'":""?>>Student Financial Aid</option>
                      <option value="A" <?=$scholarship_type=="A"?"selected='selected'":""?>>Academic</option>
                    </select>
                    <br class="hid" /><br class="hid" />  
        </fieldset>
        
        <fieldset id="req">
        <?=getStudentPassedRequirements($id,$admission_type)?>
        </fieldset>
        
        <?php
						}
						?>
      	
    </div>
     <span class="clear"></span>      
        <p class="button_container">
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
            <?php
            if($view == 'add')
            {
            ?>
                <a href="#" class="button" title="Save" id="save"><span>Save</span></a>
            <?php
            }
            else if($view == 'edit')
            {
            ?>
                <a href="#" class="button" title="Update" id="update"><span>Update</span></a>
            <?php
            }
            ?>
            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>
        </p>
        
    </div><!-- /.formview -->
<?php
	}
	if($view == 'import') {
?>

<div class="formview">
    <div class="fieldsetContainer50">
        <fieldset>
           <legend><strong>Required Fields</strong></legend>
            <label> - Admission</label>
            <span >
            </span><br class="hid" /> 
             <label> - Course</label>
            <span >
            </span><br class="hid" /> 
            <label> - Student Number</label>
            <span >
            </span><br class="hid" />
            <label> - Year Level</label>
            <span >
            </span><br class="hid" />
             <label> - Lastname</label>
            <span >
            </span><br class="hid" /> 
             <label> - Firstname</label>
            <span >
            </span><br class="hid" /> 
             <label> - Middlename</label>
            <span >
            </span><br class="hid" /> 
            <label> - Birthdate</label>
            <span >
            </span><br class="hid" />
             <label> - Email Address</label>
            <span >
            </span><br class="hid" />
            <label> - Guardian's Name</label>
            <span >
            </span><br class="hid" /> 
            <label> - Guardian's Email Address</label>
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
  <?php } 
  
	if($view == 'map')
	{
		if($num != 11)
			{
				echo '<div id="message_container"><h4>Number of CSV data did not match number of required Fields</h4></div>';
	?>
    			<div class="fieldsetContainer50">
                 <p class="button_container">
            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>
        		</p>
                </div>
        <?php
			}
			else
			{
		?>
	<div class="formview">
    <div class="fieldsetContainer50">
        <fieldset>
           <legend><strong>Import Map</strong></legend>
            <?php
				for($ctr=1;$ctr<=$num;$ctr++){
			?>
            <label>Table Field for Column<?=$ctr?></label>
            <span >
           
                <select name="field_<?=$ctr?>" id="field_<?=$ctr?>" class="txt_150" >
    
                    <option value="" >-Select Field-</option>
                    <option value="admission_type" >Admission</option>
                    <option value="course_id" >Course</option>
                    <option value="student_number" >Student Number</option>
                    <option value="year_level" >Year Level</option>
                    <option value="lastname" >Lastname</option>
                    <option value="firstname" >Firstname</option>
                    <option value="middlename" >Middlename</option>
                    <option value="birth_date" >Birthdate</option>
                    <option value="email" >Email</option>
                    <option value="pname" >Guardian's Name</option>
                    <option value="pemail" >Guardian's Email</option>
        
                </select>   
            <?php
				}
			
			?> 
            </span><br class="hid" /> 
          
            <span class="clear"></span>
        </fieldset>
    
        
        <p class="button_container">
        <input type="hidden" name="num" id="num" value="<?=$num?>" />
           <input type="hidden" name="uploadfile" id="uploadfile" value="<?=$uploadfile?>" />
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
         
                <a href="#" class="button" title="Import" id="import_fin"><span>Import</span></a>
          
            <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>
        </p>
        
    </div><!-- /.formview --></div>
<?php
	}
	}
?>

<p id="formbottom"></p>
</div>
<input type="hidden" name="filter_field" id="filter_field" value="<?=$filter_field?>" />
<input type="hidden" name="filter_order" id="filter_order" value="<?=$filter_order?>" />
<input type="hidden" name="page" id="page" value="<?=$page?>" />
<input type="hidden" name="canEdit" id="canEdit" value="<?=$canEdit?>" />
<input type="hidden" name="suf" id="suf" value="<?=$suffix!=''?$suffix:''?>" />
<input type="hidden" name="temp" id="temp" value="" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />

<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />
</form>