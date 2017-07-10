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

$(function(){  



	//initialize the tab action

	$('#add_new').click(function(){

		clearTabs();

		$('#add_new').addClass('active');

		$('#view').val('add');

		$('#box_container').html(loading);

		$("form").submit();

	});

	

	$('#edit_item').click(function(){

			clearTabs();

			$('#room_list').addClass('active');

			$('#view').val('list');

			$('#box_container').html(loading);

			$("form").submit();

	});

	

	

	$('#room_list').click(function(){

		clearTabs();

		$('#room_list').addClass('active');

		$('#view').val('list');

		updateList();

		$("form").submit();

	});	



	$('#submit_final').click(function(){

		clearTabs();

		$('#edit_item').addClass('active');

		alert('Are you sure to submit this final grades?');

		$('#action').val('save');

		$('#view').val('edit');

		$("form").submit();

	});	

	

	$('#update').click(function(){

		if(confirm('Are you sure to shift this student?'))
		{
			clearTabs();
	
			$('#edit_item').addClass('active');
	
			$('#action').val('update');
	
			$('#view').val('edit');
	
			$("form").submit();
		}
		else
		{
			return false;
		}

	});	

	
	// Initialize the list

	<?php

	if($view != 'add' && $view != 'edit')

	{

		echo 'updateList();'; 

	}

	?>



	$('#cancel').click(function(){

		clearTabs();

		$('#room_list').addClass('active');

		$('#view').val('list');

		updateList();

		$("form").submit();

	});	

	

	$('#page_rows').change(function(){

		$('#rows').val($('#page_rows').val());

		$('#page').val(1);

		updateList();

	});	
	
	
	$('#course').change(function(){
				
				if($('#course').val()!='')
				{
					$.ajax({
					type: "POST",
					data: "mod=updateElective&id=" + $('#course').val() +"&ad=<?=$stud_id?>",
					url: "ajax_components/ajax_com_year_field_updater.php",
					success: function(msg){
						if (msg != ''){
							//alert(msg);
							$("#elec").html(msg);
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

	
	$('#print').click(function() {

			var w=window.open();

			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');

			w.document.write($('#print_div').html());

			w.document.close();

			w.focus();

			w.print();

			//w.close()

			return false;

		});

		

		
	

});	


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

	

	if($('#fil_term').val() != '' )

	{

		param = param + '&filter_schoolterm=' + $('#fil_term').val();

	}

	

	if($('#rows').val() != '' && $('#rows').val() != undefined )

	{

		param = param + '&list_rows=' + $('#rows').val() + param;

	}

	if($('#comp').val() != '' && $('#comp').val() != undefined )

	{

		param = param + '&comp=' + $('#comp').val() + param;

	}

//alert (param);

	$('#box_container').html(loading);

	$('#box_container').load('ajax_components/ajax_com_shift_student.php?param=1' + param, null);

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



<h2><?=$page_title?></h2>

<ul class="tabs">

<li id="room_list" <?=$view=='list'||$view=='edit'?'class="active"':''?>><a href="#" title="Room List"><span>Student List</span></a></li>

</ul>

<form name="coreForm" id="coreForm" method="post" action="" class="fields" enctype="multipart/form-data">



<?php

	if($view == 'list')

	{

	?>

	

   <!-- <div class="filter">

    Filter By: School Year <select name="filter_schoolterm" id="filter_schoolterm" class="txt_200" >

    

                    <option value="" >-Select-</option>

                     <?=generateSchoolTerms($fil_term)?>  

                </select>

         

</div>!-->

    

	<?php

		if(isset($rows) && $rows!='')

		{

			$p_row = $rows; 

		}

		else if(isset($_SESSION[CORE_U_CODE]['pageRows'])&&($_SESSION[CORE_U_CODE]['pageRows']!=''))

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

	if($view == 'edit')

	 {

?>

	<div class="formview"> 

		<script type="text/javascript">

$(function(){
	
	$('#pdf').click(function() {

			var w=window.open ("pdf_reports/rep108.php?id="+<?=$stud_id?>+"&trm="+<?=$fil_term?>+"&met=credit grade"); 

			return false;

		});

		

		$('#email').click(function() {

			

			if(confirm("Are you sure you want to email this file to this student?"))

			{

				$.ajax({

					type: "POST",

					data: "id="+<?=$stud_id?>+"&trm="+<?=$fil_term?>+"&met=credit grade&email=1",

					url: "pdf_reports/rep108.php",

					success: function(msg){

						if (msg != ''){

							alert('Sending document by email failed.'+msg);

							return false;

						}else{

							alert('Email successfully sent.');

							return false;

						}

					}

					});	

					

			}

			else

			{

				return false;

			}

		});	

	
});
</script>
<?php
	$sql = 'SELECT * FROM tbl_student WHERE id='.$stud_id;
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	
	/*$sql2 = 'SELECT * FROM tbl_student_schedule WHERE student_id='.$stud_id.' AND elective_of<>""';
	$query2 = mysql_query($sql2);
	$e = mysql_num_rows($query2)>0?(4-mysql_num_rows($query2))+1:1;*/
?>

<table>     

			<tr>

			<td style=" font-weight:bold; font-size:12px">&nbsp;Student Number:</td>

			<td style=" font-size:12px"><?=$row['student_number']?></td>

			</tr>'

			<tr>

			<td style=" font-weight:bold; font-size:12px">&nbsp;Student Name:</td>

			<td style=" font-size:12px"><?=$row['lastname'].', '.$row['firstname'].' '.$row['middlename']?></td>

			</tr>

			<tr>

			<td style=" font-weight:bold; font-size:12px">&nbsp;Course:</td>

			<td style=" font-size:12px"><?=getCourseName($row['course_id'])?></td>

			</tr>

			</table>
    <p>&nbsp;</p>        
   <fieldset>
   
           <legend><strong>Shift To:</strong></legend>
            <label>Course</label>
            <span>
            <select name="course" id="course" class="txt_350 {required:true}" title="Course field is required" onchange="" >
                <option value="" selected="selected">Select</option>
                <?=generateCourse($course_id)?>
            </select>
    		</span><br class="hid" />
     
            <label>Set Electives</label>
            <span>
           <div id="elec">
            	
    		</div>
        </span><br class="hid" />  
  </fieldset>
  
  
  
   <p class="button_container">

      <input type="hidden" name="id" id="id" value="<?=$stud_id?>" />


                <a href="#" class="button" title="Save" id="update"><span>Save</span></a>

            	<a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>

        </p>

        

    </div><!-- /.formview -->

<?php

	}

?>

<p id="formbottom"></p>

</div>

<input type="hidden" name="stud_id" id="stud_id" value="<?=$stud_id==''?0:$stud_id?>" />

<input type="hidden" name="filter_field" id="filter_field" value="<?=$filter_field?>" />

<input type="hidden" name="filter_order" id="filter_order" value="<?=$filter_order?>" />

<input type="hidden" name="page" id="page" value="<?=$page?>" />

<input type="hidden" name="rows" id="rows" value="<?=$rows?>" />

<input type="hidden" name="fil_term" id="fil_term" value="<?=$fil_term==''?CURRENT_TERM_ID:$fil_term?>" />

<input type="hidden" name="cnt_stud" id="cnt_stud" value="<?=mysql_num_rows($result)?>" />

<input type="hidden" name="cnt_sheet" id="cnt_sheet" value="<?=mysql_num_rows($result_per)?>" />

<input type="hidden" name="temp" id="temp" value="" />

<input type="hidden" name="action" id="action" value="<?=$action==''?'list':$action?>" />

<input type="hidden" name="view" id="view" value="<?=$view==''?'list':$view?>" />



<input type="hidden" name="comp" id="comp" value="<?=$comp?>" />

</form>