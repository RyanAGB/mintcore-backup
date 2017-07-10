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



$id = $_REQUEST['stud_id'];
$rep = $_REQUEST['rep'];
$class = $_REQUEST['class'];
$term = $_REQUEST['filter_schoolterm'];

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

			var w=window.open ("pdf_reports/rep107.php?id="+<?=$id?>+"&met=stud"); 

			return false;

		});

		

		$('#email').click(function() {

			

			if(confirm("Are you sure you want to email this file to this student?"))

			{

				$.ajax({

					type: "POST",

					data: "id="+<?=$id?>+"&met=stud"+"&email=1",

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

	

	$sql = "SELECT * FROM tbl_student WHERE id = $id";						

	$query = mysql_query($sql);

	$row = mysql_fetch_array($query);



	/*$sql_rep = "SELECT * FROM tbl_school_report WHERE id = $rep";

	$query_rep = mysql_query($sql_rep);

	$row_rep = mysql_fetch_array($query_rep);*/

?>

<div id="lookup_content">

<div id="printable">

<div class="body-container">

<div class="header">

<!--<div class="logo"><img src="includes/getimage.php?id=1" alt="" /></div>!-->

<table width="100%">
<tr>

    <td colspan=2 class="title-action">

    <a class="viewer_email" href="#" id="email" title="email"></a>

    <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>

    <a class="viewer_print" href="#" id="print" title="print"></a>

    </td>

</tr>
<tr>

    <td class="title-head"><?=SCHOOL_NAME?>

    <div class="title-head"><?=$rep['report_name']?></div>

    </td>

    <td align=right class="date">

    <!-- 20100214 Feb/14/2010 -->

    <?=date("M/d/Y") ?>

    </td>

</tr>

<tr>
  <td colspan=2>&nbsp;</td>
</tr>
<tr>
  <td colspan=2>&nbsp;</td>
</tr>
<tr>
  <td colspan=2>&nbsp;</td>
</tr>
<tr>
  <td colspan=2>&nbsp;</td>
</tr>
<tr>
  <td colspan=2>&nbsp;</td>
</tr>
<tr>
  <td colspan=2>&nbsp;</td>
</tr>
<tr>
  <td colspan=2>&nbsp;</td>
</tr>
<tr>
  <td colspan=2>&nbsp;</td>
</tr>
<tr>
  <td colspan=2>&nbsp;</td>
</tr>
<tr>
  <td colspan=2>&nbsp;</td>
</tr>

</table>

</div>

<div class="content-container">



<div class="content-wrapper-wholeBorder"></div>

<?php

}

?>

</div>

</div>

</div>