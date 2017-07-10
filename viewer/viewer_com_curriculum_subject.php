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

		

		$('#print').click(function() {

			var w=window.open();

			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');

			w.document.write($('#lookup_content').html());

			w.document.close();

			w.focus();

			w.print();

			//w.close()

			return false;

		});

		

		$('#pdf').click(function() {

			var w=window.open ("pdf_reports/rep108.php?id="+<?=$id?>+"&met=course curriculum"); 

			return false;

		});

		

		/*$('#email').click(function() {

			

			if(confirm("Are you sure you want to email this file to this student?"))

			{

				$.ajax({

					type: "POST",

					data: "id="+<?=$id?>+"&met=curriculums&email=1",

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

		});	*/

		

	});

</script>



<?php



$sql = "SELECT * FROM tbl_curriculum WHERE id = $id";

$query = mysql_query($sql);

$row = mysql_fetch_array($query);



$curriculum_code	= $row['curriculum_code'];

$course_id			= $row['course_id'];

$no_of_years		= $row['no_of_years'];

$term_per_year		= $row['term_per_year'];



$termAll = $term_per_year*1 + 1;

?>

<div id="lookup_content">

<div id="printable">

<div class="body-container">

<div class="header">

<table width=100%>

<tr>

    <td class="title-head"><?=SCHOOL_NAME?>

    <div class="title-head">Curriculum : <?=getCourseName($course_id)?> (<?=$curriculum_code?>)</div>

    </td>

    <td class="date">

    <!-- 20100214 Feb/14/2010-->

    <?=date("M/d/Y") ?>

    </td>

</tr>

<tr>

    <td colspan=2 class="title-action">

     <!--<a class="viewer_email" href="#" id="email" title="email"></a>!-->

    <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>

    <a class="viewer_print" href="#" id="print" title="print"></a>

    </td>

</tr>

</table>

</div>

<div class="content-container">



<div class="content-wrapper">

<table border="0" cellspacing="10" cellpadding="0">

<?php

for($ctr_year = 1; $ctr_year<= $no_of_years; $ctr_year++)

{

        for($ctr_terms = 1; $ctr_terms<= $termAll; $ctr_terms++)

        {


			$total_units	= 0;

				  $sql = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = $id AND year_level = $ctr_year AND term = $ctr_terms AND subject_category <> 'EO'";

				  $query = mysql_query($sql);

				  $ctr_row = mysql_num_rows($query);

				  if($ctr_row > 0)

				  {
			
        ?>
        
        
  <tr>

    <td width="600" >

        <table border="0" cellspacing="0" cellpadding="0">	

          <tr>

            <td width="600"><strong><?=$ctr_terms== $termAll?getYearLevel($ctr_year).'( Summer )':getYearLevel($ctr_year).'( '.getSemesterInWord($ctr_terms).' )'?></strong></td>

          </tr>

          <tr>

            <td width="600">

                <table border="0" cellspacing="0" cellpadding="0" id="classic">	

                  <tr>

                    <th width="100"><strong>Subject Code</strong></th>

                    <th width="350"><strong>Subject</strong></th>

                    <th width="50"><strong>Units</strong></th>

                    <th width="100"><strong>Pre-requisites</strong></th>
                    
                    <th width="100"><strong>Co-requisites</strong></th>

                  </tr>

                  <?php

					  while($row = mysql_fetch_array($query))

					  {

				  ?>

                      <tr>

                        <td><?=getSubjCode($row['subject_id'])?></td>

                        <td><?=getSubjName($row['subject_id'])?></td>

                        <td><?=$row['units']?></td>

                        <td><?=getPrereqOfSubject($row['id'])?></td>
                        
                        <td><?=getCoreqOfSubject($row['id'])?></td>

                      </tr>

                  <?php

				  		$total_units += $row['units'];

				  	}

				  ?>

                    <tr>

                      <td colspan="3">&nbsp;</td>

                      <td><?=$total_units?></td>

                      <td>&nbsp;</td>

                    </tr>                  

                </table>            

				<?php
				
				}
				
				?>

            </td>

          </tr>

          <tr>

            <td width="600">&nbsp;</td>

          </tr>          

        <?php

		}

		?>

        </table>

    </td>

  </tr>  

<?php

}

?>

</table> 

<table border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td width="600">

Elective Subjects

	</td>

  </tr>

  <tr>

    <td width="600">  

    <table border="0" cellspacing="0" cellpadding="0" id="classic">	

      <tr>

        <th width="100"><strong>Subject Code</strong></th>

        <th width="350"><strong>Subject</strong></th>

        <th width="50"><strong>Units</strong></th>

        <th width="100"><strong>Pre-requisites</strong></th>
        
        <th width="100"><strong>Co-requisites</strong></th>

      </tr>

      <?php

      $total_units	= 0;

      $sql = "SELECT * FROM tbl_curriculum_subject WHERE curriculum_id = $id AND subject_category = 'EO'";

      $query = mysql_query($sql);

      $ctr_row = mysql_num_rows($query);

      if($ctr_row > 0)

      {

          while($row = mysql_fetch_array($query))

          {

      ?>

          <tr>

            <td><?=getSubjCode($row['subject_id'])?></td>

            <td><?=getSubjName($row['subject_id'])?></td>

            <td><?=$row['units']?></td>

            <td><?=getPrereqOfSubject($row['id'])?></td>
            
            <td><?=getCoreqOfSubject($row['id'])?></td>

          </tr>

      <?php

           // $total_units += $row['units'];

        }

      }

      else

      {

      ?>

          <tr>

            <td colspan="5"><strong>No subject found.</strong></td>

          </tr>

      <?php

      }

      ?>

       <!-- <tr>

          <td colspan="2">&nbsp;</td>

          <td><?=$total_units?></td>

          <td>&nbsp;</td>

        </tr> !-->                 

    </table> 

	</td>

  </tr>   

</table>    

</div>

</div>

</div>

</div>

<?php

}

?>