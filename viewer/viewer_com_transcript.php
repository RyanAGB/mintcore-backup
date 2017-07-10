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

			var copy = $('#copy').val();

			//alert(num);

			var w=window.open ("pdf_reports/rep108.php?id="+<?=$id?>+"&met=transcript&copy="+copy); 

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

	

	
	$sqlcur = "SELECT * FROM tbl_student_schedule

	WHERE student_id = ".$id." ORDER BY term_id";

	$querycur = mysql_query($sqlcur);

	$ctr_row = mysql_num_rows($querycur);

	

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

    <td rowspan="3" valign="top" class="bold"></td>

  </tr>

  <tr>

  <td class="bold" valign="top">Address: <span><?=$row['home_address']?></span></td>

    <td valign="top" class="bold">Course: <span><?=getCourseName($row['course_id'])?></span></td>

    </tr>

   <tr>

  <td class="bold" valign="top">School Last Attended: <span><?=$row['high_school']?></span></td>

    <td valign="top" class="bold">Date Enrolled: <span></span></td>

    </tr>

  <tr>
    
    <td colspan="3">COPY FOR <input type="text" class="txt_250" name="copy" id="copy" /></td>
    
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

				  while($row = mysql_fetch_array($querycur))

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



					 $sql_grade = "SELECT * FROM tbl_student_final_grade 

					  			WHERE subject_id = ".$row['subject_id']." 

								AND term_id = '".$row["term_id"]."'

								AND student_id = ".$id;

						$result_grade = mysql_query($sql_grade);

						$row_grade = mysql_fetch_array($result_grade);
						
						if(checkIfSubjectDroppedByTerm($id,$row['schedule_id'],$row["term_id"]))
						{
							$stat = 'D';
						}
						else
						{
							$stat = getGradeConversionById($row_grade["grade_conversion_id"]);
						}

				  ?>

                      <tr>

                      <td><?=(isset($trm)&&$trm!=$row["term_id"])||!isset($trm)?getSchoolYearStartEndByTerm($row["term_id"]).' ('.getSchoolTerm($row['term_id']).')':''?></td>

                        <td><?=getSubjCode($row['subject_id'])?></td>

                        <td><?=getSubjName($row['subject_id'])?></td>

                        <td><?=$row['units']?></td>

                        <td <?=checkIfGradeIsPass(decrypt($row_grade[4]))==='N'?'style="color:#FF0000"':''?>>
						<?=$stat?>
                        </td>

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



    <tr><td>GRADE POINT EQUIVALENCE:</td>

          </tr>   
          <tr>
            <td>1.00 : 98-100 | 1.25 : 95-97 | 1.50 : 92-94 | 1.75 : 89-91 | 2.00 : 86-88 | 2.25 : 83-85 | 2.50 : 80-82 | 2.75 : 77-79 | 3.00 : 75-76 : 4.00 for Prelims &amp; 5.00 for finals | INC : Incomplete | D : Dropped with Formality | W : Dropped without Formality</td>

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