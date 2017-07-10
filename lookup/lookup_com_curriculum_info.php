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

$sql = "SELECT * FROM tbl_curriculum WHERE id = $id";
$query = mysql_query($sql);
$row = mysql_fetch_array($query);

$curriculum_code	= $row['curriculum_code'];
$course_id			= $row['course_id'];
$no_of_years		= $row['no_of_years'];
$term_per_year		= $row['term_per_year'];


?>
<table width=100% style=" font-family:Arial; font-size:12px;">
<tr>
    <td  style="font-size:15px; font-weight:bold; padding-top:20px;"><?=SCHOOL_NAME?>
    <div style="font-size:12px;">Curriculum : <?=getCourseName($course_id)?> (<?=$curriculum_code?>)</div>
    </td>
    <td align=right style="padding-top:20px;">
    <!-- 20100214 Feb/14/2010-->
    <?=date("M/d/Y") ?>
    </td>
</tr>
<tr>
    <td colspan=2 style="border-top:1px solid #333;">&nbsp;</td>
</tr>
</table>

<table border="0" cellspacing="10" cellpadding="0">
<?php
for($ctr_years = 1; $ctr_years<= $no_of_years; $ctr_years++)
{
?>
  <tr>
    <td width="600"><?=getYearLevel($ctr_years) ?></td>
  </tr>
  <tr>
    <td width="600" style="padding-left:10px;">
        <table border="0" cellspacing="0" cellpadding="0">	
		<?php
        for($ctr_terms = 1; $ctr_terms<= $term_per_year; $ctr_terms++)
        {
        ?>
          <tr>
            <td width="600"><strong><?=getSemesterInWord($ctr_terms)?></strong></td>
          </tr>
          <tr>
            <td width="600">
                <table border="0" cellspacing="0" cellpadding="0" class="listview">	
                  <tr>
                    <td width="100"><strong>Subject Code</strong></td>
                    <td width="350"><strong>Subject</strong></td>
                    <td width="50"><strong>Units</strong></td>
                    <td width="100"><strong>Pre-requisites</strong></td>
                  </tr>
                </table>            
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
<?php
}
?>