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



$id = $_REQUEST['id'];



?>

<script type="text/javascript">

	$(function(){

		$('.selector').click(function() {

			//var id = $(this).attr("val");		

			var valTxt = $(this).attr("returnTxt");

			var valId = $(this).attr("returnId");

			$('#room_id').attr("value", valId);

			$('#room_display').attr("value", valTxt);

			

			$('#dialog').dialog('close');			

		});

		

		$('#print').click(function() {

			var w=window.open();

			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');

			w.document.write($('#lookup_content').html());

			w.document.close();

			w.focus();

			w.print();

			//w.close();

			return false;

		});

		

		$('#pdf').click(function() {

			var num = $(this).attr('returnNum');

			//alert(num);

			var w=window.open ("pdf_reports/rep108.php?id="+<?=$id?>+"&met=transcript&num="+num); 

			return false;

		});

		

		$('#email').click(function() {

			

			if(confirm("Are you sure you want to email this file to this student?"))

			{

				$.ajax({

					type: "POST",

					data: "id="+<?=$id?>+"&met=transcript&email=1",

					url: "pdf_reports/rep108.php",

					success: function(msg){

						if (msg != ''){

							alert('Sending document by email failed.');

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

	$sql_student_photo = "SELECT * FROM tbl_student_photo WHERE student_id = $id";

	$query_student_photo = mysql_query($sql_student_photo);

	$row_student_photo = mysql_fetch_array($query_student_photo);

	

	$sqldet = "SELECT * FROM tbl_student WHERE id = $id";

	$resultdet = mysql_query($sqldet);

	$row = mysql_fetch_array($resultdet);

	$cur = $row['curriculum_id'];

	$yir = $row['start_year_level'];

	

	if($row['admission_type']=='T'){

	$sqlcur = "SELECT * FROM tbl_student_final_grade a, tbl_school_year_term b 

	WHERE a.type = 'C' AND a.term_id = b.id AND student_id = ".$id;

	$querycur = mysql_query($sqlcur);

	$ctr_rowcur = mysql_num_rows($querycur);

	}



	$sqlcurr = "SELECT * FROM tbl_curriculum WHERE id = ".$cur;

	$rescurr = mysql_query($sqlcurr);

	$rowcurr = mysql_fetch_array($rescurr);

	$termper = $rowcurr['term_per_year'];



	$sqlsub = "SELECT * FROM tbl_student_schedule a, tbl_school_year_term b 

	WHERE a.term_id = b.id AND student_id = ".$id;

	  $resultsub = mysql_query($sqlsub);

	  $ctr_row = mysql_num_rows($resultsub);

	

	$curTerm = '';

	$total_units = 0;

	$tem = 0;

	$yirs = $yir;

	

	$num = generateSpecialNumber();

?>

<div id="lookup_content">

<div id="printable">

<div class="body-container">

<div class="header_big">

<table width="100%">

	<tr>

    <td valign="top" class="bold">PERSONAL DATA</td>

    <td colspan="2" ><?php if($ctr_row > 0 || $ctr_rowcur > 0)

		{ ?>

      <a class="viewer_email" href="#" id="email" title="email"></a> <a class="viewer_pdf" href="#" id="pdf" title="pdf" returnNum=<?="'".$num."'"?>></a> <a class="viewer_print" href="#" id="print" title="print"></a>

      <?php } ?></td>

	</tr>

  <tr>

    <td valign="top" class="bold">Student Name: <span><?=$row['lastname']. ", " . $row['firstname'] ." " . $row['middlename']?></span></td>

    <td class="bold" valign="top">Student Number: <span><?=$row['student_number']?></span></td>

    <td rowspan="4" valign="top" class="bold"><?php

    if($row_student_photo['image_file'] != '')

    {

    ?>

        <img src="includes/student_image.php?student_id=<?=$row['id']?>"/>

    <?php

    }

    else

    {

    ?>

        <img src="images/NoPhotoAvailable.jpg"/>

    <?php

    }

	

	 $birthday = explode ('-',$row['birth_date']);		

				$birth_year = $birthday['0'];

				$birth_day = $birthday['1'];

				$birth_month = $birthday['2'];

				

    ?></td>

  </tr>

  <tr>

    <td class="bold" valign="top">Birth Date: <span><?=date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year))?></span></td>

    <td valign="top" class="bold">Special Order No: <span><?=$num?></span></td>

    </tr>

  <tr>

  <td class="bold" valign="top">Address: <span><?=$row['home_address']?></span></td>

    <td valign="top" class="bold">Course: <span><?=getCourseName($row['course_id'])?></span></td>

    </tr>

   <tr>

  <td class="bold" valign="top">Nationality: <span><?=getCitizenship($row['citizenship'])?></span></td>

    <td valign="top" class="bold">Date Graduated: <span><?=count(getStudentSubjectForEnrollmentInArr($id))>0?'N/A':getLastTerm($id)?></span></td>

    </tr>

<tr>

    <td valign="top" class="bold" colspan="3">ENTRANCE DATA</td>

	</tr>

  <tr>

    <td valign="top" class="bold">Date/Term & School Year Admitted:</td>

    <td colspan="2"><?=getSYandTerm($row['term_id'])?></td>

  </tr>

  <tr>

    <td class="bold" valign="top">Category:</td>

    <td colspan="2"><?=$row['admission_type']==D?'College Graduate':'HighSchool Graduate'?></td>

  </tr>

  <tr>

  <td class="bold" valign="top">HighSchool/College Last Attended:</td>

    <td colspan="2"><?=getStudentLastSchool($id)?></td>

  </tr>

   <tr>

  <td class="bold" valign="top">Date Graduated/Last School Year Attended:</td>

    <td colspan="2"><?=getStudentLastSchoolYears($id)?></td>

  </tr>

  <tr>

    <td colspan="3">&nbsp;</td>

</tr>

<tr>

    <td valign="top" class="bold" colspan="3">ACADEMIC RECORD</td>

	</tr>

</table>

</div>

<div class="content-container">



<div class="content-wrapper-wholeBorder">

   



	<?php

	

	   if($ctr_row > 0)

		{

		

			  

	?>

        

<table border="0" cellspacing="0" cellpadding="0">

<?php

//for($ctr_year = 1; $ctr_year<= $yir; $ctr_year++)

//{

?>

 

  <tr>

    <td width="600" cellpadding='0'>



        		<table border="0" cellspacing="0" cellpadding="0" id="classic">

   

                  <tr>

                  <th width="100"><strong>School Year and Term</strong></th>

                    <th width="100"><strong>Subject Code</strong></th>

                    <th width="350"><strong>Subject</strong></th>

                    <th width="50"><strong>Units</strong></th>

                    <th width="100"><strong>Grade</strong></th>

                  </tr>

                  

                  <?php

					 if($ctr_rowcur > 0)

				  	{

					 while($rowcur = mysql_fetch_array($querycur))

					  {

				?>

                      <tr>

                      	<td><?=(isset($trm)&&$trm!=$rowcur["term_id"])||!isset($trm)?$rowcur['school_term'].''.getSchoolYearStartEnd($rowcur['term_id']):''?></td>

                        <td><?=getSubjCode($rowcur['subject_id'])?></td>

                        <td><?=getSubjName($rowcur['subject_id'])?></td>

                        <td><?=getSubjUnit($rowcur['subject_id'])?></td>

                        <td><?=getGradeConversionGrade(decrypt($rowcur['final_grade']))?></td>

                      </tr>  

                      <?php $trm = $rowcur["term_id"];

					  } 

					  }?> 

                  

                  <?php

				  while($row = mysql_fetch_array($resultsub))

				{

				if ($curTerm != $row["term_id"]) {

					

					if($tem==$termper)

					{

						$yirs++;

					}

				  

				  $curTerm = $row["term_id"];

				  $total_units = 0;

				  if($tem==0){

					  if($row['school_term']=='Second Term'){

					  $tem=2;

					  }else if($row['school_term']=='Third Term' || $row['school_term']=='Summer'){

					  $tem=3;

					  }else{

					  $tem++;

					  }

				  }else{

				  $tem++;

				  }

				  }



					 $sql_grade = "SELECT final_grade FROM tbl_student_final_grade 

					  			WHERE subject_id = ".$row['subject_id']." 

								AND term_id = '".$row["term_id"]."'

								AND student_id = ".$id;

						$result_grade = mysql_query($sql_grade);

						$row_grade = mysql_fetch_array($result_grade);

				  ?>

                      <tr>

                      <td><?=(isset($trm)&&$trm!=$row["term_id"])||!isset($trm)?$row['school_term'].''.getSchoolYearStartEnd($row["term_id"]):''?></td>

                        <td><?=getSubjCode($row['subject_id'])?></td>

                        <td><?=getSubjName($row['subject_id'])?></td>

                        <td><?=$row['units']?></td>

                        <td <?=checkIfGradeIsPass(decrypt($row_grade[0]))==='N'?'style="color:#FF0000"':''?>><?=getGradeConversionGrade(decrypt($row_grade[0]))?></td>

                      </tr>

                  <?php

				  		$total_units += $row['units'];

						

						$trm = $row["term_id"];

				  }

				 

				  ?>

         

                </table>   

                         

    </td>

  </tr>

  <tr>

    <td width="600">&nbsp;</td>

  </tr>    

</table>



			<table border="0" cellspacing="0" cellpadding="0">



    <tr><td>



        <table border="0" cellspacing="0" cellpadding="0" id="classic">

                  <tr>

                    <th width="100"><strong>Official Seal</strong></th>

                    <th width="350"><strong>Grading System</strong></th>

                    <th width="100"><strong>School Registrar</strong></th>

                  </tr>

				

                      <tr>

                        <td valign="bottom">(not valid w/out seal)</td>

                        <td valign="bottom"><table border="0" cellspacing="0" cellpadding="0" width="100%">

                        	<tr>

                              <td>Grade</td>

                              <td colspan="2">Equivalent</td>

                            </tr>

                            <?php $sql = "SELECT * FROM tbl_grade_conversion ORDER BY ceiling_grade DESC";

								$query = mysql_query($sql);

								while($row = mysql_fetch_array($query))

								{ ?>

                            <tr>

                              <td><?=$row['grade_code']?></td>

                              <td><?=$row['floor_grade'].'-'.$row['ceiling_grade']?></td>

                              <td><?=$row['description']?></td>

                            </tr>

                            <?php } ?>

                        </table></td>

                        <td valign="top"><p>Issued this 

                          <?=date('jS')?> 

                          day of 

                          <?=date('F')?>

                          , 

                          <?=date('Y')?> 

                          for Employment purpose.

                      </p>

                        <p>&nbsp;</p>

                        <p>&nbsp;</p>

                        <p>&nbsp;</p>

                        <p>&nbsp;</p>

                        <p>&nbsp;</p>

                        <p>__________________________</p>

                        <p><?=getEmployeeFullName(USER_ID)?></p></td>

                      </tr> 

                </table>            

            </td>

          </tr>          

        </table> 



        <?php

		} else {

		?>

  <table width=100%>

    	<tr>

        	<td>No Record Found.</td>

        </tr>



    </table>

<?php

}

}

?>

 </div>

 </div>

</div> <!-- #lookup_content -->

</div>