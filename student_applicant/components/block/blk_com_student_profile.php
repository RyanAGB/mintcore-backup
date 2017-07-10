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
$(document).ready(function(){  
	
	$('#scholarship_container').hide();

	$('#apply_scholar').change(function(){
	
		if($('#apply_scholar').attr('checked'))
		{
			$('#scholarship_container').show();
		}
		else
		{
			$('#scholarship_container').hide();
		}
		
	});
	
	 $('#coreForm').validate({
        invalidHandler: function(form, validator) {
      var errors = validator.numberOfInvalids();
      if (errors) {
        var message = errors == 1
          ? 'Please correct the following error:\n'
          : 'Please correct the following ' + errors + ' errors.\n';
        var errors = "";
        if (validator.errorList.length > 0) {
            for (x=0;x<validator.errorList.length;x++) {
                errors += "\n\u25CF " + validator.errorList[x].message;
            }
        }
        alert(message + errors);
      }
      validator.focusInvalid();
    }

    });

	$('#save').click(function(){
		$('#action').val('save');
		$("form").submit();
	});	
	
	var row_ctr = <?=$sched_row_ctr == '0' ?'1':$sched_row_ctr+1?>;
	
	
	$('#add_sib').click(function(){
		
		var name 	= $('#sib_name').val();
		var sage 	= $('#age').val();
		var educ	= $('#education_attain').val();
		var school 	= $('#last_school').val();	
		
			if(name != '' && sage != '' && educ != '' && school != '' )
			{
				str_table ='<tr id ="row_'+row_ctr+'">';
				  str_table +='<td><input name="s_name[]" type="hidden" id="s_name" value="'+name+'" />' +name+ '</td>';
				  str_table +='<td><input name="sage[]" type="hidden" id="sage" value="'+sage+'" />' +sage+ '</td>';
				  str_table +='<td><input name="educ[]" type="hidden" id="educ" value="'+educ+'" />' +educ+ '</td>';
				  str_table +='<td><input name="school[]" type="hidden" id="school" value="'+school+'" />'+school+'</td>';
				  str_table +='<td class="action"><a href="#" class="removes" returnId="'+row_ctr+'" onclick="removeRows('+row_ctr+'); return false;" >Remove</a></td>';             
				str_table +='</tr>';
			
				$('#tbl_sibling tbody').append(str_table);
			}
			else
			{
				alert('Some required fields are missing.');
			}
			
		$('#sib_name').val('');
		$('#age').val('');
		$('#education_attain').val('');
		$('#last_school').val('');
		
		return false;
	});
	
	$('#add_exp').click(function(){
		
		var comp 	= $('#company').val();
		var post 	= $('#position').val();
		var e_name	= $('#exp_name').val();
		var dates 	= $('#exp_month_fr').val()+'/'+$('#exp_year_fr').val()+'-'+$('#exp_month_to').val()+'/'+$('#exp_year_to').val();	
		
			if(comp != '' && post != '' && e_name != '' && dates != '' )
			{
				str_table ='<tr id ="row_'+row_ctr+'">';
				  str_table +='<td><input name="comp[]" type="hidden" id="comp" value="'+comp+'" />' +comp+ '</td>';
				  str_table +='<td><input name="post[]" type="hidden" id="post" value="'+post+'" />' +post+ '</td>';
				  str_table +='<td><input name="e_name[]" type="hidden" id="e_name" value="'+e_name+'" />' +e_name+ '</td>';
				  str_table +='<td><input name="dates[]" type="hidden" id="dates" value="'+dates+'" />'+dates+'</td>';
				  str_table +='<td class="action"><a href="#" class="removes" returnId="'+row_ctr+'" onclick="removeRows('+row_ctr+'); return false;" >Remove</a></td>';             
				str_table +='</tr>';
			
				$('#tbl_experience tbody').append(str_table);
			}
			else
			{
				alert('Some required fields are missing.');
			}
			
		$('#company').val('');
		$('#position').val('');
		$('#exp_name').val('')
		return false;
	});
	
	$('.remove').click(function(){

			removeRow($(this).attr("returnId"));
			return false;
		
	});
	
	$('.removes').click(function(){

			removeRow($(this).attr("returnId"));
			return false;
		
	});

	
	$('#course_id').change(function(){
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
				data: "mod=school&id=" + $('#last_school').val(),
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
		
		$('#course_id').change(function(){

					$.ajax({
					type: "POST",
					data: "mod=updateDate&id=" + $('#term_id').val()+"&cor=" + $('#course_id').val(),
					url: "ajax_components/ajax_com_school_field_updater.php",
					success: function(msg){
						if (msg != ''){
						//alert(msg);
							$("#exam_date").html(msg);
						}
					}
					});	
		});
		
		$('#term_id').change(function(){
			if($('#course_id').val()!='')
			{
					$.ajax({
					type: "POST",
					data: "mod=updateDate&id=" + $('#term_id').val()+"&cor=" + $('#course_id').val(),
					url: "ajax_components/ajax_com_school_field_updater.php",
					success: function(msg){
						if (msg != ''){
						//alert(msg);
							$("#exam_date").html(msg);
						}
					}
					});	
			}		
		});
	
});	

function removeRow(id)
{
	var valId = id;
	percent = 0;
	
	$('#row_' + valId).remove();
	return false;
}

function removeRows(id)
{
	var valId = id;
	percent = 0;
	
	$('#row_' + valId).remove();
	return false;
}

function clearTabs()
{
	$('ul.tabs li').attr('class',''); // clear all active
}

function updateList(pageNum)
{
	var param = '';
	var acc = '';
	if(pageNum != '' && pageNum != undefined)
	{
		param = param + '&pageNum=' + pageNum;
	}
	if($('#acc').val() != '' && $('#acc').val() != undefined)
	{
		acc=$('#acc').val();
	}
	else
	{
		acc=1;
	}
	//alert(acc);
	//$('#box_container').html(loading);
	//$('#Accordion'+acc+'Content').load('ajax_components/ajax_com_student_profile.php' + param, null);
}

function onlyNumbers(evt)
{
	var e = event || evt; // for trans-browser compatibility
	var charCode = e.which || e.keyCode;

	if (charCode != 42 && (charCode > 31 && (charCode < 48 || charCode > 57)))
	{
		alert("Postal Code should be numbers only.");
		return false;
	}
	else
	{
		return true;
	}

}

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
function setGen(val)
{

	$('#M').attr("checked",'');
	$('#F').attr("checked",'');
	
	$('#'+val).attr("checked",'checked');
	
	$('#gen').val(val);
}
</script>
<script type="text/javascript">
$(function(){

	// Dialog			
	/*$('#dialog').dialog({
		autoOpen: false,
		width: 600,
		height: 500,
		bgiframe: true,
		modal: true,
		buttons: {
			"Ok": function() { 
				$(this).dialog("close"); 
			}
		}
	});
	
	// Dialog Link
	$('#save').click(function(){
		$('#dialog').load('viewer_requirements.php', null);
		$('#dialog').dialog('open');
		return false;
	});*/
	
});	

</script>

<?php
if($err_msg != '')
{
?>
    <p class="alert">
        <span class="txt"><span class="icon"></span><strong>Alert:</strong> <?=$err_msg?></span>
    </p>
<?php
}
?>
<div class="error"></div>
<form name="coreForm" id="coreForm" method="post" action="" class="col50" enctype="multipart/form-data">
<div class="table">
  <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
    <tr>
      <td></td>
      <td colspan="3"><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <th scope="col"> <input name="mr" id="mr" type="checkbox" value="mr" onclick="checkSuffix('mr')" <?=$suffix=='mr'?'checked="checked"':''?> />
                <label> Mr.</label>
                <input name="mrs" id="mrs" type="checkbox" value="mrs" onclick="checkSuffix('mrs')" <?=$suffix=='mrs'?'checked="checked"':''?>/>
                <label> Mrs.</label>
                <input name="ms" id="ms" type="checkbox" value="ms" onclick="checkSuffix('ms')" <?=$suffix=='ms'?'checked="checked"':''?>/>
                <label> Miss </label>
                <input name="other" id="other" type="checkbox" value="other" onclick="checkSuffix('other')" <?=$suffix=='other'?'checked="checked"':''?>/>
                <label>Other</label>
                <input name="suffix" id="suffix" type="text" class="short" <?=$suffix!='other'?'disabled="disabled"':'value="'.$suffix.'"'?>/>
            </th>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td><label>* Family Name:</label></td>
      <td colspan="2"><input name="lastname" id="lastname" value="<?=$lastname?>" type="text" class="long required" title="lastname field is required"/></td>
      <td rowspan="4"><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="image"></td>
          </tr>
          <tr>
            <td><input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                <input class="mid" name="student_photo" type="file" id="student_photo" value="<?=$student_photo?>" />
            </td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td><label>* First Name:</label></td>
      <td colspan="2"><input name="firstname" id="firstname" value="<?=$firstname?>" type="text" class="long required"/ title="firstname field is required" /></td>
    </tr>
    <tr>
      <td><label>* Middle Name:</label></td>
      <td colspan="2"><input name="middlename" id="middlename" value="<?=$middlename?>" type="text" class="long required" title="middlename field is required"/></td>
    </tr>
    <tr>
      <td><label>Nick Name:</label></td>
      <td colspan="2"><input name="nickname" id="nickname" value="<?=$nickname?>" type="text" class="long"/>
      </td>
    </tr>
    <tr>
      <td><label>* Gender:</label></td>
      <td colspan="3"><input name="M" id="M" type="checkbox" value="M" onclick="setGen('M');" <?=$gender=='M'?'checked="checked"':''?>/>
          <label>Male</label>
          <input name="F" id="F" type="checkbox" value="F" onclick="setGen('F');" <?=$gender=='F'?'checked="checked"':''?>/>
          <label>Female</label>
          <label>* Civil Status:</label>
          <select name="civil_status" id="civil_status" class="short required" title="civil status field is required">
            <option value="" >Select</option>
            <option value="S" <?php if($civil_status== "S") { ?>
                        selected = "selected"
                        <?php } ?>
                        >Single</option>
            <option value="M" <?php if($civil_status== "M") { ?>
                        selected = "selected"
                        <?php } ?> 
                        >Married</option>
        </select></td>
    </tr>
    <tr>
      <td><label>* Citizenship </label>
      </td>
      <td colspan="3"><select name="citizenship" id="citizenship" class="short required" title="citizenship field is required">
          <option value="" selected="selected">Select</option>
          <?=generateCitizenship($citizenship)?>
        </select>
          <label>* Place of Birth:</label>
          <input name="birth_place" id="birth_place" value="<?=$birth_place?>" type="text" class="short required" title="place of birth field is required"/>
      </td>
    </tr>
    <tr>
      <td><label>* Date of Birth</label></td>
      <td colspan="3"><select name="b_month" id="b_month" class="short required" title="birth month field is required">
          <option value="" >Month</option>
          <?=generateMonth($bdate[1])?>
        </select>
          <select name="b_day" id="b_day" class="short required" title="birth day field is required">
            <option value="" selected="selected">Day</option>
            <?=generateDay($bdate[2])?>
          </select>
          <select name="b_year" id="b_year" class="short required" title="birth year field is required">
            <option value="" selected="selected">Year</option>
            <?=generateYear($bdate[0])?>
          </select>
      </td>
    </tr>
    <tr>
      <td><label>Home Address</label></td>
      <td colspan="2"><input name="home_address" id="home_address" value="<?=$home_address?>" type="text" class="long"/></td>
    </tr>
    <tr>
      <td><label>City</label></td>
      <td colspan="3"><input name="city" id="city" value="<?=$city?>" type="text" class="short" />
          <label>* Country</label>
          <select name="country" id="country" class="short required" title="country field is required">
            <option value="" selected="selected">Select</option>
            <?=generateCountry($country)?>
          </select>
      </td>
    </tr>
    <tr>
      <td><label>* Phone</label></td>
      <td colspan="3"><input name="tel_number" id="tel_number" value="<?=$tel_number?>" type="text" class="short required" title="phone field is required"/>
          <label>* Mobile Phone</label>
          <input name="mobile_number" id="mobile_number" value="<?=$mobile_number?>" type="text" class="short required" title="mobile phone field is required"/></td>
    </tr>
    <tr>
      <td><label>Fax</label></td>
      <td colspan="3"><input name="fax" id="fax" value="<?=$fax?>" type="text" class="short"/>
          <label>Postal Code</label>
          <input name="home_address_zip" id="home_address_zip" value="<?=$home_address_zip?>" type="text" class="short" onkeypress="return onlyNumbers();"/>
      </td>
    </tr>
    <tr>
      <td><label>* Email</label></td>
      <td colspan="2"><input name="email" id="email" type="text" value="<?=$email?>" class="long required" title="email field is required"/></td>
    </tr>
    <tr>
      <td><label>* Confirm Email</label></td>
      <td colspan="2"><input name="con_email" id="con_email" value="<?=$con_email?>" type="text" class="long required" title="confirm email field is required"/></td>
    </tr>
    <tr>
      <td><label>* Parent or Legal Guardian's Name</label></td>
      <td colspan="2"><input class="long required" name="guardian_name" type="text" value="<?=$guardian_name?>" id="guardian_name" title="guardian name field is required"/></td>
    </tr>
    <tr>
      <td><label>* Relation</label></td>
      <td colspan="3"><select name="guardian_relation" id="guardian_relation" class="mid required" title="relation field is required">
          <option value="" >Select</option>
          <option value="parent" <?=$guardian_relation== "parent"?'selected = "selected"':''?> >Parent</option>
          <option value="relative" <?=$guardian_relation== "relative"?'selected = "selected"':''?> >Relative</option>
          <option value="other" <?=$guardian_relation== "other"?'selected = "selected"':''?> >Other</option>
        </select>
          <label>Profession</label>
          <input class="mid" name="guardian_occupation" type="text" value="<?=$guardian_occupation?>" id="guardian_occupation"/></td>
    </tr>
    <tr>
      <td><label>Company</label></td>
      <td colspan="2"><input class="long" name="guardian_company" type="text" value="<?=$guardian_company?>" id="guardian_company" title="company field is required"/></td>
    </tr>
    <tr>
      <td><label>Email Address</label></td>
      <td colspan="2"><input class="long" type="text" value="<?=$guardian_email?>" id="guardian_email" name="guardian_email" title="guardian email field is required" /></td>
    </tr>
    <tr>
      <td><label>Confirm Email Address</label></td>
      <td colspan="2"><input class="long" type="text" value="<?=$con_guardian_email?>" id="con_guardian_email" name="con_guardian_email" title="confirm guardian email field is required" /></td>
    </tr>
    <tr>
      <td><label>Mailing Address</label></td>
      <td colspan="2"><input class="long" name="guardian_address" type="text" value="<?=$guardian_address?>" id="guardian_address" /></td>
    </tr>
    <tr>
      <td><label>City</label></td>
      <td colspan="3"><input class="short" name="guardian_city" type="text" value="<?=$guardian_city?>" id="guardian_city" />
          <label>Country</label>
          <select name="guardian_country" id="guardian_country" class="short" title="guardian country field is required" >
            <option value="" selected="selected">Select</option>
            <?=generateCountry($guardian_country)?>
        </select></td>
    </tr>
    <tr>
      <td><label>Home Phone</label></td>
      <td colspan="3"><input class="short" name="guardian_tel_number" type="text" value="<?=$guardian_tel_number?>" id="guardian_tel_number" title="home phone field is required" />
          <label>Work Phone</label>
          <input class="short" name="guardian_work_number" type="text" value="<?=$guardian_work_number?>" id="guardian_work_number" title="work number field is required" /></td>
    </tr>
    <tr>
      <td><label>Fax</label></td>
      <td colspan="3"><input class="short" name="guardian_fax" type="text" value="<?=$guardian_fax?>" id="guardian_fax" />
          <label>Postal Code</label>
          <input class="short" name="guardian_address_zip" type="text" value="<?=$guardian_address_zip?>" id="guardian_address_zip" onkeypress="return onlyNumbers();" maxlength="10"/></td>
    </tr>
  </table>
  <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
    <tr>
      <td class="head" colspan="4">ACADEMIC BACKGROUND</td>
    </tr>
    <tr>
      <td colspan="4"><table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><label>Grade School:</label></td>
            <td><input class="long" title="grade school field is required" name="grade_school" type="text" value="<?=$grade_school?>" id="grade_school" /></td>
          </tr>
            <tr>
            <td><label>Address:</label></td>
            <td><input class="long" name="grade_school_address" type="text" value="<?=$grade_school_address?>" id="grade_school_address" <?=$grade_school_id!=0?'disabled="disabled"':''?> /></td>
            </tr>
          <tr>
            <td><label>Awards / Recognition:</label></td>
            <td><input name="grade_school_award" class="long" id="grade_school_award" value="<?=$grade_school_award?>"/></td>
          </tr>
          <tr>
            <td><label>Years Attended:</label>              </td>
            <td><select name="grade_school_month_fr" id="grade_school_month_fr" class="short" >
              <option value="" >Month</option>
              <?=generateMonth($grade_mfr)?>
            </select>
              <select name="grade_school_year_fr" id="grade_school_year_fr" class="short" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($grade_yfr)?>
              </select>
              <label class="dust">to</label>
              <select name="grade_school_month_to" id="grade_school_month_to" class="short" >
                <option value="" >Month</option>
                <?=generateMonth($grade_mto)?>
              </select>
              <select name="grade_school_year_to" id="grade_school_year_to" class="short" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($grade_yto)?>
              </select></td>
          </tr>
          <tr>
            <td><label>High School:</label></td>
            <td><input class="long" name="high_school" type="text" value="<?=$high_school?>" id="high_school" /></td>
          </tr>
            <tr>
            <td><label>Address:</label></td>
            <td><input class="long" name="high_school_address" type="text" value="<?=$high_school_address?>" id="high_school_address" /></td>
            </tr>
          <tr>
            <td><label>Awards / Recognition:</label></td>
            <td><input name="high_school_award" class="long" id="high_school_award" /></td>
          </tr>
          <tr>
            <td><label>Years Attended:</label>              </td>
            <td><select name="high_school_month_fr" id="high_school_month_fr" class="short" >
              <option value="" >Month</option>
              <?=generateMonth($high_mfr)?>
            </select>
              <select name="high_school_year_fr" id="high_school_year_fr" class="short" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($high_yfr)?>
              </select>
              <label class="dust">to</label>
              <select name="high_school_month_to" id="high_school_month_to" class="short" >
                <option value="" >Month</option>
                <?=generateMonth($high_mto)?>
              </select>
              <select name="high_school_year_to" id="high_school_year_to" class="short" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($high_yto)?>
              </select></td>
          </tr>
          <tr>
            <td><label>College School:</label></td>
            <td><input class="long" name="college_school" type="text" value="<?=$college_school?>" id="college_school" /></td>
          </tr>
            <tr>
            <td><label>Address:</label></td>
            <td><input class="long" name="college_school_address" type="text" value="<?=$college_school_address?>" id="college_school_address" /></td>
            </tr>
          <tr>
            <td><label>Awards / Recognition:</label></td>
            <td><input name="college_school_award" class="long" id="college_school_award" value="<?=$college_school_award?>"/></td>
          </tr>
          <tr>
            <td><label>Years Attended:</label></td>
            <td><select name="college_school_month_fr" id="college_school_month_fr" class="short" >
              <option value="" >Month</option>
              <?=generateMonth($college_mfr)?>
            </select>
              <select name="college_school_year_fr" id="college_school_year_fr" class="short" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($college_yfr)?>
              </select>
              <label class="dust"> to </label>
              <select name="college_school_month_to" id="college_school_month_to" class="short">
                <option value="" >Month</option>
                <?=generateMonth($college_mto)?>
              </select>
              <select name="college_school_year_to" id="college_school_year_to" class="short" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($college_yto)?>
              </select>              </td>
          </tr>
      </table></td>
    </tr>
    </table>
    
    <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
    <tr>
      <td class="head" colspan="4">PROFESSIONAL EXPERIENCE (if applicable)</td>
    </tr>
    <tr>
      <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="tbl_experience">
          <thead>
            <td><label>Company</label></td>
            <td><label>Position Held</label></td>
            <td><label>Name of Supervisor</label></td>
            <td><label>Dates of Employment</label></td>
            <td><label>Action</label></td>
          </thead>
          <tbody>
        <?=$experience?>
        </tbody> 
          </table>
          <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><label>Company:</label>
              <label></label></td>
            <td><input class="mid" name="company" type="text" value="" id="company" /></td>
            <td><label>Position Held:</label>
              <input class="mid" name="position" type="text" value="" id="position" /></td>
          </tr>
            <tr>
            <td><label>Name of Supervisor:</label>
              <label></label></td>
            <td><input class="mid" name="exp_name" type="text" value="" id="exp_name" /></td>
            <td><label>Dates of Employment:</label>
              <select name="exp_month_fr" id="exp_month_fr" class="short" >
                <option value="" >Month</option>
                <?=generateMonth($exp_dates[1])?>
              </select>
              <select name="exp_year_fr" id="exp_year_fr" class="short" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($exp_dates[0])?>
              </select>
              <label class="dust"> to </label>
              <select name="exp_month_to" id="exp_month_to" class="short" >
                <option value="" >Month</option>
                <?=generateMonth($exp_dates[1])?>
              </select>
              <select name="exp_year_to" id="exp_year_to" class="short" >
                <option value="" selected="selected">Year</option>
                <?=generateYearUntilNow($exp_dates[0])?>
              </select></td>
            </tr>
           <tr>
              <td colspan="2"><a href="#" class="button apple-green small" id="add_exp">Add Experience</a></td>
            </tr>
      </table></td>
    </tr>
    </table>
    <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
    <tr>
      <td class="head" colspan="4">INTENDED ACADEMIC PROGRAM</td>
    </tr>
    <tr>
      <td><label>* School Year & Term</label></td>
      <td colspan="3"><select name="term_id" id="term_id" class="short required" title="school year field is required">
          <option value="" selected="selected">Select</option>
          <?=generateSchoolTermsApplicant($term_id)?>
        </select>
          <label></label></td>

    </tr>
    <tr>
      <td><label>* Course</label></td>
      <td colspan="2"><select name="course_id" id="course_id"  class="short required" title="course field is required">
          <option value="" selected="selected">Select</option>
          <?=generateCourse($course_id)?>
        </select>
          <label></label></td>
      
    </tr>
    <tr>
      <td><label>* Admission As</label></td>
      <td>
        <select name="admission_type" id="admission_type"  class="short required" title="admission field is required">
          <option value ="">Select</option>
          <option value="F" <?php if($admission_type== "F"){ ?> selected = "selected" <?php } ?>>Freshman</option>
          <option value="T" <?php if($admission_type== "T"){ ?> selected = "selected" <?php } ?>>Transferee</option>
        </select></td>
        <td><label>* Year Level</label></td>
        <td>
        <select name="year_level" id="year_level"  class="short required" title="year level field is required">
          <option value="" selected="selected">Select</option>
          <?=generateYearLevel($year_level)?>
        </select></td>            
    </tr>
    <tr>
      <td><label> Examination Date</label></td>
      <td colspan="3"><select name="exam_date" id="exam_date"  class="short" title="examination date field is required">
          <option value="" selected="selected">Select</option>
          <?=generateExamDate($exam_date,$_REQUEST['term_id'])?>
      </select></td>
          
    </tr>    
    </table>
    
    <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
    <tr>
      <td class="head" colspan="5">ADDITIONAL INFORMATION</td>
    </tr>
    <tr>
      <td><label>Language:</label></td>
      <td><input class="long" name="language" type="text" value="<?=$language?>" id="language" /></td>
    </tr>
    <tr>
      <td><label>Extra Curricular Activities and Leadership Roles:</label></td>
      <td><input class="long" name="extra_curricular" type="text" value="<?=$extra_curricular?>" id="extra_curricular" /></td>
    </tr>
    </table>
    
    <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
    <tr>
      <td class="head" colspan="4">SIBLINGS (if applicable)</td>
    </tr>
    <tr>
      <td colspan="4"><table width="100%" border="1" cellspacing="0" cellpadding="0" id="tbl_sibling" class="tblWBord">
          <thead>
            <td><label>Name</label></td>
            <td><label>Age</label></td>
            <td><label>Highest Educational Attainment</label></td>
            <td><label>Last School Attended</label></td>
            <td><label>Action</label></td>
          </thead>
          <tbody>
          <?=$siblings?>
          </tbody>
        </table>
          <table border="0" cellspacing="0" cellpadding="0" >
            <tr>
              <td><label>Name:</label></td>
              <td><input class="mid" name="sib_name" type="text" value="" id="sib_name" />                </td>
              <td><label>Highest Educational Attainment:</label></td>
              <td><input class="mid" name="education_attain" type="text" value="" id="education_attain" /></td>
            </tr>
            <tr>
              <td><label>Age:</label></td>
              <td><input class="mid" name="age" type="text" value="" id="age" />                </td>
              <td><label>Last School Attended:</label></td>
              <td><input name="last_school" value="" class="mid" id="last_school" /></td>
            </tr>
            <tr>
              <td colspan="4"><a href="#" class="button apple-green small" id="add_sib">Add Siblings</a></td>
            </tr>
        </table></td>
    </tr>
    </table>

    <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
        <tr>
        	<td class="head" colspan="3">APPLICATION FOR SCHOLARSHIP?</td>
        </tr>
        <tr>
          <td width="250"><label>Do you want to apply for scholarship?</label></td>
          <td colspan="2"><input name="apply_scholar" id="apply_scholar" type="checkbox" value="Yes" class="chk"/></td>
        </tr>        
    </table>
    
	<div id="scholarship_container" style="display:none;">
    <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
        <tr>
        	<td class="head" colspan="4">FAMILY BACKGROUND <em>(for scholarship)</em></td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
          <td><label>Mother</label></td>
          <td><label>Father</label></td>
        </tr>  
        <tr>
          <td colspan="2"><label>Parents' Names</label></td>
          <td><input class="mid" name="sc_mother_name" type="text" value="" id="sc_mother_name" /></td>
          <td><input class="mid" name="sc_father_name" type="text" value="" id="sc_father_name" /></td>
        </tr>   
        <tr>
          <td colspan="2"><label>Age (if deceased, indicate when)</label></td>
          <td><input class="mid" name="sc_mother_age" type="text" value="" id="sc_mother_age" /></td>
          <td><input class="mid" name="sc_father_age" type="text" value="" id="sc_father_age" /></td>
        </tr>   
        <tr>
          <td colspan="2"><label>Residential Address</label></td>
          <td><input class="mid" name="sc_mother_address" type="text" value="" id="sc_mother_address" /></td>
          <td><input class="mid" name="sc_father_address" type="text" value="" id="sc_father_address" /></td>
        </tr>   
        <tr>
          <td colspan="2"><label>Home Phone</label></td>
          <td><input class="mid" name="sc_mother_phone" type="text" value="" id="sc_mother_phone" /></td>
          <td><input class="mid" name="sc_father_phone" type="text" value="" id="sc_father_phone" /></td>
        </tr>  
        <tr>
          <td colspan="2"><label>Mobile Phone</label></td>
          <td><input class="mid" name="sc_mother_mobile" type="text" value="" id="sc_mother_mobile" /></td>
          <td><input class="mid" name="sc_father_mobile" type="text" value="" id="sc_father_mobile" /></td>
        </tr>  
        <tr>
          <td colspan="2"><label>Birth Date</label></td>
          <td>
            <select name="sc_mother_b_month" id="sc_mother_b_month" class="short" title="birth month field is required">
                <option value="" >Month</option>
                <?=generateMonth($bdate[1])?>
            </select>
            <select name="sc_mother_b_day" id="sc_mother_b_day" class="short" title="birth day field is required">
                <option value="" selected="selected">Day</option>
                <?=generateDay($bdate[2])?>
            </select>
            <select name="sc_mother_b_year" id="sc_mother_b_year" class="short" title="birth year field is required">
                <option value="" selected="selected">Year</option>
                <?=generateYear($bdate[0])?>
            </select>          
          </td>
          <td>
            <select name="sc_father_b_month" id="sc_father_b_month" class="short" title="birth month field is required">
                <option value="" >Month</option>
                <?=generateMonth($bdate[1])?>
            </select>
            <select name="sc_father_b_day" id="sc_father_b_day" class="short" title="birth day field is required">
                <option value="" selected="selected">Day</option>
                <?=generateDay($bdate[2])?>
            </select>
            <select name="sc_father_b_year" id="sc_father_b_year" class="short" title="birth year field is required">
                <option value="" selected="selected">Year</option>
                <?=generateYear($bdate[0])?>
            </select>           
          </td>
        </tr>   
        <tr>
          <td colspan="2"><label>Highest Educational Attainment</label></td>
          <td><input class="mid" name="sc_mother_educational" type="text" value="" id="sc_mother_educational" /></td>
          <td><input class="mid" name="sc_father_educational" type="text" value="" id="sc_father_educational" /></td>
        </tr> 
        <tr>
          <td colspan="2"><label>Last School Attended</label></td>
          <td><input class="mid" name="sc_mother_school" type="text" value="" id="sc_mother_school" /></td>
          <td><input class="mid" name="sc_father_school" type="text" value="" id="sc_father_school" /></td>
        </tr>  
        <tr>
          <td colspan="2"><label>Occupation</label></td>
          <td><input class="mid" name="sc_mother_occupation" type="text" value="" id="sc_mother_occupation" /></td>
          <td><input class="mid" name="sc_father_occupation" type="text" value="" id="sc_father_occupation" /></td>
        </tr> 
        <tr>
          <td colspan="2"><label>Employer</label></td>
          <td><input class="mid" name="sc_mother_employer" type="text" value="" id="sc_mother_employer" /></td>
          <td><input class="mid" name="sc_father_employer" type="text" value="" id="sc_father_employer" /></td>
        </tr>   
        <tr>
          <td colspan="2"><label>Position</label></td>
          <td><input class="mid" name="sc_mother_position" type="text" value="" id="sc_mother_position" /></td>
          <td><input class="mid" name="sc_father_position" type="text" value="" id="sc_father_position" /></td>
        </tr> 
        <tr>
          <td colspan="2"><label>Business Address</label></td>
          <td><input class="mid" name="sc_mother_bus_address" type="text" value="" id="sc_mother_bus_address" /></td>
          <td><input class="mid" name="sc_father_bus_address" type="text" value="" id="sc_father_bus_address" /></td>
        </tr>      
        <tr>
          <td colspan="2"><label>Annual Income</label></td>
          <td><input class="mid" name="sc_mother_income" type="text" value="" id="sc_mother_income" /></td>
          <td><input class="mid" name="sc_father_income" type="text" value="" id="sc_father_income" /></td>
        </tr>  
        <tr>
          <td colspan="2"><label>Status of Parents</label></td>
          <td colspan="2">
            <select name="sc_status_parents" id="sc_status_parents" class="short required" title="Status of Parents">
                <option value="" selected="selected">Select</option>
                <option value="married" selected="selected">Married</option>
                <option value="separated" selected="selected">Separated</option>
                <option value="single-parent" selected="selected">Single-parent</option>
                <option value="other" selected="selected">Other</option>
            </select>          
          </td>
        </tr>                                                                                                     
    </table>
    
    <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
        <tr>
        	<td class="head" colspan="4">FINANCIAL STATUS <em>(for scholarship)</em></td>
        </tr>
        <tr>
          <td><label>Family's Annual Gross Income</label></td>
          <td><input class="mid" name="age" type="text" value="" id="age" /></td>
          <td><label>Sources of Income</label></td>
          <td><input class="mid" name="age" type="text" value="" id="age" /></td>
        </tr>  
        <tr>
          <td><label>Residence</label></td>
          <td>
            <select name="status_parents" id="status_parents" class="short required" title="Status of Parents">
                <option value="" selected="selected">Select</option>
                <option value="married" selected="selected">Owned</option>
                <option value="separated" selected="selected">Rented</option>
                <option value="single-parent" selected="selected">Mortgaged</option>
                <option value="other" selected="selected">Living with Relative</option>
            </select>            
          </td>
          <td><label>Monthly Amortization / Rent (if applicable)</label></td>
          <td><input class="mid" name="age" type="text" value="" id="age" /></td>
        </tr>   
        <tr>
          <td><label>Classification of Residence</label></td>
          <td colspan="3">
            <select name="status_parents" id="status_parents" class="short required" title="Status of Parents">
                <option value="" selected="selected">Select</option>
                <option value="married" selected="selected">Single Detached</option>
                <option value="separated" selected="selected">Townhouse</option>
                <option value="single-parent" selected="selected">Apartment</option>
                <option value="other" selected="selected">Condominium</option>
            </select>            
          </td>
        </tr> 
        <tr>
          <td><label>Number of bedrooms</label></td>
          <td><input class="mid" name="age" type="text" value="" id="age" /></td>
          <td><label>Number of bathrooms</label></td>
          <td><input class="mid" name="age" type="text" value="" id="age" /></td>
        </tr> 
        <tr>
          <td colspan="4"><label>ASSETS <br />Properties Owned (residential and commercial lots, business establishments, etc.) </label></td>
        </tr>  
        <tr>
          <td colspan="4"><textarea name="" cols="70" rows="5"></textarea></td>
        </tr> 
        <tr>
          <td colspan="4"><label>Cars / Vehicles / Transportation (include model and year) </label></td>
        </tr>  
        <tr>
          <td colspan="4"><textarea name="" cols="70" rows="5"></textarea></td>
        </tr>                                                         
    </table>
    
    <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
        <tr>
        	<td class="head" colspan="6">APPLIANCE AND HOUSEHOLD MACHINES <em>(for scholarship)</em></td>
        </tr>
        <tr>
          <td></td>
          <td><label>Brand</label></td>
          <td><label>Quantity</label></td>
          <td><label>Estimated Purchase Price</label></td>
          <td><label>Terms (fully paid or installment)</label></td>
          <td><label>Monthly Installment</label></td>
        </tr>  
        <tr>
          <td><label>Television</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr> 
        <tr>
          <td><label>DVD Player</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr> 
        <tr>
          <td><label>Computer (Desktop)</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr> 
        <tr>
          <td><label>Computer (Laptop)</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr>
        <tr>
          <td><label>Air Conditioner</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr> 
        <tr>
          <td><label>Washing Machine</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr> 
        <tr>
          <td><label>Cellular Phone</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr> 
        <tr>
          <td><label>Sofa Set</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr> 
        <tr>
          <td><label>Dining Set</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr> 
        <tr>
          <td><label>Micorwave Oven</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr>         
        <tr>
          <td><label>Oven Toaster</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr> 
        <tr>
          <td><label>Vauum CLeaner</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr> 
        <tr>
          <td><label>Refrigerator</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr>
        <tr>
          <td><label>Printer</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:100px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" style="width:25px;" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr>                                                                    
    </table>
    <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
        <tr>
        	<td class="head" colspan="4">MONTHLY FAMILY EXPENSES <em>(for scholarship)</em></td>
        </tr>
        <tr>
          <td><label>Particulars</label></td>
          <td><label>Amount</label></td>
          <td><label>Particulars</label></td>
          <td><label>Amount</label></td>
        </tr> 
        <tr>
          <td><label>Rentals (residential)</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><label>Loan Amortization</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr>  
        <tr>
          <td><label>Water</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><label>Clothing expense</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr>  
        <tr>
          <td><label>Electricity</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><label>Medical expenses</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr>  
        <tr>
          <td><label>Communication expense</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><label>Insurance (life/medical)</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr>  
        <tr>
          <td><label>Tranportation expense</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><label>Social contributions</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr>  
        <tr>
          <td><label>Grocery / Food</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
          <td><label>Others (pls. specify)</label></td>
          <td><input class="small" name="age" type="text" value="" id="age" /></td>
        </tr>                                                         
    </table>    

    <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
        <tr>
        	<td class="head" colspan="4">BRIEFLY STATE REASONS IN APPLYING FOR A SCHOLARSHIP</td>
        </tr>
        <tr>
          <td colspan="4"><textarea name="" cols="100" rows="10"></textarea></td>
        </tr>          
    </table>

    </div>
    
    <table border="1" cellspacing="0" cellpadding="0" class="tableProperties">
    <tr>
      <td class="head" colspan="3">CERTIFICATION</td>
    </tr>
    <tr>
      <td colspan="3"><label>I hereby certify that I have read and fully understood all instructions regarding my application for admission to Meridian International Business, Arts and Technology College and the information supplied in this application and documentations supporting it are correct and complete. I understand that incomplete or inaccurate information could be prejudicial to my admission and retention. if accepted as a student of Meridian International Business, Arts and Technology College, I agree to abide by all its policies and regulations.</label> </td>
    </tr>
    <tr>
      <td class="center">
          <label>I Accept:</label>
            <label><input name="agree" id="agree" type="checkbox" value="" title="agreement field is required" class="chk required"/></label>
                </td>
    </tr>
    <tr>
              <td colspan="4"><br clear="all" />

        <a href="#" class="button apple-green small" id="save">Submit</a>

        <div class="last">&nbsp;
        </div></td>
            </tr>
  </table>
</div>

<input type="hidden" name="suf" id="suf" value="<?=$suffix!=''?$suffix:''?>" />
<input type="hidden" name="gen" id="gen" value="<?=$gender?>" />
<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />
</form>
