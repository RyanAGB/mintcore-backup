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
?>
<script type="text/javascript">
	function getselected() {
		var i = $('#cnt').val();
		var ids = "";

		for(var x=0; x<=i; x++) {
			if ($('#chk' + x).attr("checked")) {
				if (ids != "")
					ids += ",";
				ids += $('#chk'+x).val(); 
			}
		}
		
			$('#req').attr("value", ids);
			//$('#dialog_acc').dialog('close');	
			
	}
</script>

<?php
	$id = $_REQUEST['id'];
	$admission = $_REQUEST['adm'];

	$sql = "SELECT * FROM tbl_student_application WHERE id = $id";						
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);

?>
<div id="lookup_content">
<div id="printable" style="font-family:Arial;">
<table width=100% style=" font-family:Arial; font-size:12px;">
<tr>
    <td  style="font-size:15px; font-weight:bold; padding-top:20px;"><?=SCHOOL_NAME?>
    <div style="font-size:12px;">Requirements Information</div>
    </td>
    <td align=right style="padding-top:20px;">
    <!-- 20100214 Feb/14/2010-->
    <?=date("M/d/Y") ?>
    </td>
</tr>
<tr>
    <td colspan=2 style="border-top:1px solid #333;">
    	<!--<a class="viewer_email" href="#" id="email" title="email"></a>
        <a class="viewer_pdf" href="#" id="pdf" title="pdf"></a>
        <a class="viewer_print" href="#" id="print" title="print"></a>!-->
    </td>
</tr>
</table>
<table width="100%">
  <tr>
    <td width="18%" valign="top" class="bold">Student Name:</td>
    <td width="82%"><?=$row['lastname']. ", " . $row['firstname'] ." " . $row['middlename']?></td>
  </tr>
  <tr>
    <td class="bold" valign="top">Course:</td>
    <td><?=getCourseName($row['course_id'])?></td>
  </tr>
   <tr>
        <td class='bold' valign="top">School Year:</td>
        <td><?=getSYandTerm($row['term_id'])?></td>
      </tr>
  <tr>
    <td>&nbsp;</td>
</tr>
</table>
<label>&nbsp;</label>
<div class="fieldsetContainer50">
<table class="fieldsetList">        
        <tr>
            <th class="col1_100">&nbsp;</th>
            <th class="col1_400"><a href="#">Requirement</a></th>     
        </tr>
        <?php
       $sql = "SELECT * FROM tbl_requirements WHERE admission='".$admission."'";
	   $query = mysql_query($sql);
	   
	   $x=0;
	   while($row = mysql_fetch_array($query))
	   {
	   ?>				
            <tr class="<?=($x%2==0)?"":"highlight";?>">
            <td>		
				<input name="chk<?=$x?>" type="checkbox" value="<?=$row['id']?>" id="chk<?=$x?>"/>
			</td>
            <td><?=$row['requirement']?></td>
            </tr>
		<?php	
		$x++;
		}
        ?>
        <input type="hidden" name="cnt" id="cnt" value="<?=mysql_num_rows($query)?>" />
    </table> 
</div>
</div>
</div>
<?php
}
?>