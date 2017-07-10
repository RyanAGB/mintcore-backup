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



	$('#save').click(function(){

		clearTabs();

		$('#add_new').addClass('active');

		$('#action').val('save');

		$('#view').val('add');

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

			$('#import').addClass('active');

			$('#action').val('uplod');

			$('#view').val('map');

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

	// Initialize the list

	<?php

	if($view != 'add' && $view != 'edit' && $view != 'import' && $view != 'map')

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


function getID(val)
{

	$.ajax({
					type: "POST",
					data: "mod=updateEmpNumber&id=" + val,
					url: "ajax_components/ajax_com_year_field_updater.php",
					success: function(msg){
						if (msg != ''){
							$("#emp_id_number").val(msg);
						}
					}
					});	
	
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

	if($('#comp').val() != '' && $('#comp').val() != undefined )

	{

		param = param + '&comp=' + $('#comp').val() + param;

	}

		

	$('#box_container').html(loading);

	$('#box_container').load('ajax_components/ajax_com_employee_profile.php?param=1' + param, null);

}



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

<li id="room_list" <?=$view=='list'?'class="active"':''?>><a href="#" title="Building List"><span>Employee List</span></a></li>

<li id="add_new" <?=$view=='add'?'class="active"':''?>><a href="#" title="Add New"><span>Add New</span></a></li>

<li id="edit_item" <?=$view=='edit'?'class="active"':''?>><a href="#" title="Edit Item"><span>Edit Item</span></a></li>

<li id="import" <?=$view=='import'?'class="active"':''?>><a href="#" title="Import"><span>Import</span></a></li>

<li id="map" <?=$view=='map'?'class="active"':''?>><a href="#" title="Map"><span>Map</span></a></li>

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

           <legend><strong>Personal Information</strong></legend>

                <label>Lastname</label>

                <span >

                <input class="txt_300 {required:true}" title="lastname field is required" name="lastname" type="text" value="<?=$lastname?>" id="lastname" onchange="getID(this.value);" />

                </span><br class="hid" />

                <label>Firstname</label>

                <span >

                <input class="txt_300 {required:true}" title="Firstname field is required" name="firstname" type="text" value="<?=$firstname?>" id="firstname" />

                </span><br class="hid" /> 

                <label>Middlename</label>

                <span >

                <input class="txt_300 {required:true}" title="Middlename field is required" name="middlename" type="text" value="<?=$middlename?>" id="middlename" />

                </span><br class="hid" />        

         

                <label>Date of Birth</label>

                <span >

                <select name="b_month" id="b_month" class="txt_100 {required:true}" title="Month field is required">

                <option value="" >Month</option>

                <option value="1" <?php if($b_month== "1"){ ?> selected = "selected" <?php } ?>>January</option>

                <option value="2" <?php if($b_month== "2"){ ?> selected = "selected" <?php } ?>>February</option>

                <option value="3" <?php if($b_month== "3"){ ?> selected = "selected" <?php } ?>>March</option>

                <option value="4" <?php if($b_month== "4"){ ?> selected = "selected" <?php } ?>>April</option>

                <option value="5" <?php if($b_month== "5"){ ?> selected = "selected" <?php } ?>>May</option>

                <option value="6" <?php if($b_month== "6"){ ?> selected = "selected" <?php } ?>>June</option>

                <option value="7" <?php if($b_month== "7"){ ?> selected = "selected" <?php } ?>>July</option>

                <option value="8" <?php if($b_month== "8"){ ?> selected = "selected" <?php } ?>>August</option>

                <option value="9" <?php if($b_month== "9"){ ?> selected = "selected" <?php } ?>>September</option>

                <option value="10" <?php if($b_month== "10"){ ?> selected = "selected" <?php } ?>>October</option>

                <option value="11" <?php if($b_month== "11"){ ?> selected = "selected" <?php } ?>>November</option>

                <option value="12" <?php if($b_month== "12"){ ?> selected = "selected" <?php } ?>>December</option>

               	</select>

                <select name="b_day" id="b_day" class="txt_70 {required:true}" title="Day field is required">

                  <option value="" selected="selected">Day</option>

                  <?=generateDay($b_day)?>                                                                        

                </select>

                <select name="b_year" id="b_year" class="txt_70 {required:true}" title="Year field is required">

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

                <label>Religion</label>

                <span >

                    <select name="religion" id="religion" class="txt_250 {required:true}" title="Religion field is required">

                      <option value="" selected="selected">Select</option>

                      <?=generateReligion($religion)?>

                    </select>

                 </span><br class="hid" />

                    <label>Sex or Gender</label>

                    <span >

                    <select name="gender" id="gender" class="txt_100 {required:true}" title="Sex or Gender field is required">

						<option value="" selected="selected">Select</option>	

						<option value="M" <?=$gender== "M"?'selected = "selected"':''?> >Male</option>

						<option value="F" <?=$gender== "F"?'selected = "selected"':''?> >Female</option>

                    </select>

                    </span><br class="hid" />  

                    <label>Civil Status</label>

                    <span >

                    <select name="civil_status" id="civil_status" class="txt_100 {required:true}" title="Civil Status field is required">

						<option value="" selected="selected">Select</option>	

						<option value="S" <?=$civil_status== "S"?'selected = "selected"':''?> >Single</option>

						<option value="M" <?=$civil_status== "M"?'selected = "selected"':''?> >Married</option>

                    </select>

                    </span><br class="hid" />                  

            <span class="clear"></span>

        </fieldset>

      	<fieldset>

               <legend><strong>Contact Details</strong></legend>

                    <label>Present Address</label>

                    <span >

                    <input class="txt_300 {required:true}" title="Present Address field is required" name="present_address" type="text" value="<?=$present_address?>" id="present_address" />

                    </span><br class="hid" />

                    <label>ZIP Code	</label>

                    <span >

                    <input class="txt_100 {required:true}" title="ZIP Code field is required" name="present_address_zip" type="text" value="<?=$present_address_zip?>" id="present_address_zip" />

                    </span><br class="hid" />

                    <label>Permanent Address</label>

                    <span >

                    <input class="txt_300 {required:true}" title="Permanent Address field is required" name="permanent_address" type="text" value="<?=$permanent_address?>" id="permanent_address" />

                    </span><br class="hid" />

                    <label>ZIP Code</label>

                    <span >

                    <input class="txt_100 {required:true}" title="ZIP Code field is required" name="permanent_address_zip" type="text" value="<?=$permanent_address_zip?>" id="permanent_address_zip" />

                    </span><br class="hid" />

                    <label>Telephone Number</label>

                    <span >

                    <input class="txt_200 {required:true}" title="Telephone Number field is required" name="tel_number" type="text" value="<?=$tel_number?>" id="tel_number" />

                    </span><br class="hid" />   

                    <label>Mobile Number</label>

                    <span >

                    <input class="txt_200 {required:true}" title="Mobile Number field is required" name="mobile_number" type="text" value="<?=$mobile_number?>" id="mobile_number" />

                    </span><br class="hid" />

                    

                    <label>E-Mail Address</label>

                    <span >

                    <input class="txt_200 required:email" title="Email Address field is required" name="email" type="text" value="<?=$email?>" id="email" />

                    </span><br class="hid" /> 

                    

                    <label>Confirm E-Mail Address</label>

                    <span >

                    <input class="txt_200 required:confirm_email" title="Confirm Email Address field is required" name="confirm_email" type="text" value="<?=$email?>" id="confirm_email" />

                    </span><br class="hid" />                      

                    

                    </span><br class="hid" />                                                                                                    

  

                <span class="clear"></span>

        </fieldset>        



      </div>

      

      <div class="fieldsetContainer50">

      <fieldset>

               <legend><strong>Photo</strong></legend>

                    <label>Upload Photo</label>

                    <span>

                    <br />

					<?php

                    if($image_file != '')

                    {

                    ?>

                        <img src="includes/employee_image.php?employee_id=<?=$id?>"/>

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

                    <input class="txt_250" name="image_file" type="file" id="image_file" />

                    </span>

                    <p>(*maximum size: 120x120)</p>

                    <br class="hid" /><br class="hid" />  

        </fieldset>

        <fieldset>

           <legend><strong>Employment Details</strong></legend>

            <label>Employee Number</label>

            <span >

            <input class="txt_300 {required:true}" title="Employee Number field is required" name="emp_id_number" type="text" value="<?=$emp_id_number?>" id="emp_id_number" />   

            </span><br class="hid" />  

            <label>Employee Type</label>

            <span >

            <select name="employee_type" id="employee_type" class="txt_250 {required:true}" title="Employee Type field is required">

              <option value="" selected="selected">Select</option>

              <?=generateEmployeeType($employee_type)?>

            </select>  

            </span><br class="hid" />

            <label>Department</label>

            <span >

            <select name="department_id" id="department_id" class="txt_250 {required:true}" title="Department field is required">

              <option value="" selected="selected">Select</option>

              <?=generateDepartment($department_id)?>

            </select> 

            <span class="clear"></span>

        </fieldset>        

        <fieldset>

           <legend><strong>Person to notify in case of emergency</strong></legend>

            <label>Fullname</label>

            <span >

            <input class="txt_300 {required:true}" title="Fullname field is required" name="ice_fullname" type="text" value="<?=$ice_fullname?>" id="ice_fullname" />   

            </span><br class="hid" />  

            <label>Address</label>

            <span >

            <input class="txt_300 {required:true}" title="Address field is required" name="ice_address" type="text" value="<?=$ice_address?>" id="ice_address" />   

            </span><br class="hid" />  

            <label>Telephone Number</label>

            <span >

            <input class="txt_300 {required:true}" title="Telephone field is required" name="ice_tel_number" type="text" value="<?=$ice_tel_number?>" id="ice_tel_number" />  

            </span><br class="hid" />    



            <span class="clear"></span>

        </fieldset>



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

            <label> - Employee Type</label>

            <span >

            </span><br class="hid" /> 

            <label> - Employee Number</label>

            <span >

            </span><br class="hid" />

            <label> - Department</label>

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

  <?php } ?>

  

  <?php

	if($view == 'map')

	{

		if($num != 8)

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

                    <option value="employee_type" >Employee Type</option>

                    <option value="emp_id_number" >Employee Number</option>

                    <option value="department_id" >Department</option>

                    <option value="lastname" >Lastname</option>

                    <option value="firstname" >Firstname</option>

                    <option value="middlename" >Middlename</option>

                    <option value="birth_date" >Birthdate</option>

                    <option value="email" >Email</option>

        

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

<input type="hidden" name="temp" id="temp" value="" />

<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />

<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />



<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />

</form>