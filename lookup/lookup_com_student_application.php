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

		

		$('#printapp').click(function() {

			var w=window.open();

			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');

			w.document.write($('#lookup_content').html());

			w.document.close();

			w.focus();

			w.print();

			//w.close();

			return false;

		});

		

		$('#pdfapp').click(function() {

			var w=window.open ("pdf_reports/rep107.php?id="+<?=$id?>+"&met=app"); 

			return false;

		});

		

		$('#emailapp').click(function() {

			

			if(confirm("Are you sure you want to email this file to this student?"))

			{

				$.ajax({

					type: "POST",

					data: "id="+<?=$id?>+"&met=app&email=1",

					url: "pdf_reports/rep107.php",

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

	

	$sql = "SELECT * FROM tbl_student_application WHERE id = $id";						

	$query = mysql_query($sql);

	$row = mysql_fetch_array($query);

?> 

<div id="lookup_content">

<div id="printable">

<div class="body-container">

<div class="header">

<table width=100%>

<tr>

    <td class="title-head"><?=SCHOOL_NAME?>

    <div class="title-head">Student Information</div>

    </td>

    <td align=right class="date">

    <!-- 20100214 Feb/14/2010-->

    <?=date("M/d/Y") ?>

    </td>

</tr>

<tr>

    <td colspan=2 class="title-action">

    	<a class="viewer_email" href="#" id="emailapp" title="email"></a>

        <a class="viewer_pdf" href="#" id="pdfapp" title="pdf"></a>

        <a class="viewer_print" href="#" id="printapp" title="print"></a>

    </td>

</tr>

</table>

</div>

<div class="content-container">



<div class="content-wrapper-wholeBorder">

<table border="0" cellspacing="10" cellpadding="0">

  <tr>

    <td width="300">

	<?php

    if($row['image_file'] != '')

    {



    ?>

        <img src="includes/getimage_applicant.php?student_id=<?=$row['id']?>"/>

    <?php

    }

    else

    {

    ?>

        <img src="images/NoPhotoAvailable.jpg"/>

    <?php

    }

    ?>

    </td>

    <td width="300">

            

        <table width=100% >

            <tr>

                <td colspan=2 class="title-border">Personal Information</td>

            </tr>

            <tr>

                <td width=45% class="bold" valign="top">Student Name:</td>

                <td width=55%><?=$row['firstname']. ", " . $row['lastname'] ." " . $row['middlename']?></td>

            </tr>

            <tr>

              <td class="bold" valign="top">Course:</td>

              <td><?=getCourseName($row['course_id'])?></td>

            </tr>

            <!--

            <tr>

                <td style='font-weight:bold' valign="top">Year level:</td>

                <td><?=$row['year_level']?></td>

            </tr>

            -->

            <tr>

                <td class="bold" valign="top">Gender:</td>

                <td>

				<?php

                if($row['gender'] == 'M')

				{

					echo "Male";

				}

				else if($row['gender'] == 'F')

				{

					echo "Female";

				}

				?>

                </td>

            </tr>

            <tr>

                <td class="bold" valign="top">Civil Status:</td>

                <td>

                <?php

                if($row['civil_status'] == 'M')

				{

					echo "Married";

				}

				else if($row['civil_status'] == 'S')

				{

					echo "Single";

				}

				?>

                </td>

            </tr>

            <tr>

                <td class="bold" valign="top">BirthDate:</td>

                <td>

				<?php

                $birthday = explode ('-',$row['birth_date']);		

				$birth_year = $birthday['0'];

				$birth_day = $birthday['1'];

				$birth_month = $birthday['2'];

				

				echo date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year));



				?>

                </td>

            </tr>

            <tr>

                <td class="bold" valign="top">E-mail Address:</td>

                <td><?=$row['email']?></td>

            </tr>

            <tr>

                <td class="bold" valign="top">Contact No.:</td>

                <td><?=$row['tel_number']?></td>

            </tr>

            <tr>

                <td class="bold" valign="top">Mobile No.:</td>

                <td><?=$row['mobile_number']?></td>

            </tr>

            <tr>

              <td class="bold" valign="top">&nbsp;</td>

              <td>&nbsp;</td>

            </tr>

        </table>



  	</td>

  </tr>

  <tr>

    <td valign="top"><table width="100%" border="0">

      <tr>

        <td colspan="2" class="title-border">Guardian's Information</td>

      </tr>

      <tr>

        <td class="bold" valign="top">Guardian's Name:</td>

        <td><?=$row['guardian_name']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">Occupation:</td>

        <td><?=$row['guardian_occupation']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">Work Phone:</td>

        <td><?=$row['guardian_work_number']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">Phone:</td>

        <td><?=$row['guardian_tel_number']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">Address:</td>

        <td><?=$row['guardian_address']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">Postal Code:</td>

        <td><?=$row['guardian_address_zip']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">Country:</td>

        <td><?=getCountryName($row['guardian_country'])?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">City:</td>

        <td><?=$row['guardian_city']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">&nbsp;</td>

        <td>&nbsp;</td>

      </tr>

    </table></td>

    <td valign="top">

    

        <table width=100%>

            <tr>

                <td colspan=2 class="title-border">Home Address</td>

            </tr>

            <tr>

                <td width=45% class="bold" valign="top">Home Address:</td>

                <td width=55%><?=$row['home_address']?></td>

            </tr>

            <tr>

                <td class="bold" valign="top">Postal Code:</td>

                <td><?=$row['home_address_zip']?></td>

            </tr>

            <tr>

                <td class="bold" valign="top">Country:</td>

                <td><?=getCountryName($row['country'])?></td>

            </tr>

            <tr>

                <td class="bold" valign="top">City:</td>

                <td><?=$row['city']?></td>

            </tr>

            <tr>

                <td class="bold" valign="top">Fax:</td>

                <td><?=$row['fax']?></td>

            </tr>

            <tr>

              <td class="bold" valign="top">&nbsp;</td>

              <td>&nbsp;</td>

            </tr>

        </table>  

  

    </td>

  </tr>

  <tr>

  	<td valign="top"><table width="100%" border="0">

      <tr>

        <td colspan="2" class="title-border">Academic Background</td>

      </tr>

      <tr>

        <td width="45%" class="bold" valign="top">Grade School Name:</td>

        <td><?=$row['grade_school']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">School Address:</td>

        <td><?=$row['grade_school_address']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">Years Attended:</td>

        <td><?=$row['grade_school_years']?>

        </td>

      </tr>

      <tr>

        <td class="bold" valign="top">Awards:</td>

        <td><?=$row['grade_school_award']?>

        </td>

      </tr>

      <tr>

        <td width="45%" class="bold" valign="top">High School Name:</td>

        <td><?=$row['high_school']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">School Address:</td>

        <td><?=$row['high_school_address']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">Years Attended:</td>

        <td><?=$row['high_school_years']?>

        </td>

      </tr>

      <tr>

        <td class="bold" valign="top">Awards:</td>

        <td><?=$row['high_school_award']?>

        </td>

      </tr>

      <tr>

        <td width="45%" class="bold" valign="top">College School Name:</td>

        <td><?=$row['college_school']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">School Address:</td>

        <td><?=$row['college_school_address']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">Years Attended:</td>

        <td><?=$row['college_school_years']?>

        </td>

      </tr>

      <tr>

        <td class="bold" valign="top">Awards:</td>

        <td><?=$row['college_school_award']?>

        </td>

      </tr>

      <tr>

        <td class="bold" valign="top">&nbsp;</td>

        <td>&nbsp;</td>

      </tr>

    </table></td>

    <td valign="top"><table width="100%">

      <tr>

        <td colspan="2" class="title-border">Additional Information</td>

      </tr>

      <tr>

        <td width="45%" class="bold" valign="top">Language:</td>

        <td width="55%"><?=$row['language']?></td>

      </tr>

      <tr>

        <td class="bold" valign="top">Extra Curricular Activities:</td>

        <td><?=$row['extra_curricular']?></td>

      </tr>



      <tr>

        <td class="bold" valign="top">&nbsp;</td>

        <td>&nbsp;</td>

      </tr>

    </table></td>

  </tr>

</table>

</div>

<?php

}

?>

</div>

</div>

</div>