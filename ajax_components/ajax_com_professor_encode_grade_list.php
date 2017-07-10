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
	
	if(USER_IS_LOGGED != '1')
	{
		header('Location: ../index.php');
	}
	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}
	
	$prof_id = $_REQUEST['prof_id'];
	$comp = $_REQUEST['comp'];

	/*if($_SESSION[CORE_U_CODE]['sy_filter'] !='')
	{
		$filter_school = $_SESSION[CORE_U_CODE]['sy_filter'];	
	}
	else*/ if($_REQUEST['filter_schoolterm'] !='')
	{
		$filter_school = $_REQUEST['filter_schoolterm'];
	}
	
	if (isset($filter_school) and ($filter_school != "")){
		$arr_sql[] =  "term_id = " . $filter_school;
	}	
	
	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sql = implode(' AND ', $arr_sql);
	}			
?>
<script type="text/javascript">
$(function(){
	
	$('#cancel').click(function(pageNum){
		$('#prof_id').val('')
		profList();
	});	
	
	$('.profile').click(function(){
		updateList($('#page').val(),$(this).attr('returnId'));
	});


});	

function profList(pageNum)
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
	if($('#filter_dept').val() != '' && $('#filter_dept').val() != undefined )
	{
		param = param + '&filterdept=' + $('#filter_dept').val();
	}
	if($('#rows').val() != '' && $('#rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#rows').val() + param;
	}
	
	if($('#schoolterm_id').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#schoolterm_id').val();
	}
	param = param + '&comp=<?=$comp?>';
	param = param + '&prof_id=<?=$prof_id?>';
	
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_professor_encode_grade.php?param=1' + param, null);
}	

function updateList(pageNum,sched_id)
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
	if($('#filter_dept').val() != '' && $('#filter_dept').val() != undefined )
	{
		param = param + '&filterdept=' + $('#filter_dept').val();
	}
	if($('#schoolterm_id').val() != '' )
	{
		param = param + '&filter_schoolterm=' + $('#schoolterm_id').val();
	}

	if($('#rows').val() != '' && $('#rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#rows').val() + param;
	}
	param = param + '&comp=<?=$comp?>';
	param = param + '&prof_id=<?=$prof_id?>' + '&sched_id=' + sched_id;
	
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_professor_encode_grade_form.php?param=1' + param, null);
}	
		
</script>
<script type="text/javascript">
$(document).ready(function(){  

	$('.sortBy').click(function(){

		if($('#filter_order').val() == '' || $('#filter_order').val() == 'DESC')
		{
			var order = 'ASC';
		}
		else
		{
			var order = 'DESC';
		}
		
		$('#filter_field').val($(this).attr('returnFilter'));
		$('#filter_order').val(order);
		updateList3();
		return false;
	});
	
});
function updateList3(pageNum)
{
	var param = '';
	
	$('#page').val(pageNum);
	if($('#page').val(); != '' && $('#page').val(); != undefined)
	{
		param = param + '&pageNum=' + $('#page').val();;
	}
	
	if($('#filter_field').val() != '' && $('#filter_field').val() != undefined && $('#filter_order').val() != '' && $('#filter_order').val() != undefined )
	{
		param =  param + '&fieldName=' + $('#filter_field').val() + '&orderBy=' + $('#filter_order').val() + '&prof_id=<?=$prof_id?>';
		
	}
	if($('#comp').val() != '' && $('#comp').val() != undefined )
	{
		param = param + '&comp=' + $('#comp').val() + param;
	}
	if($('#rows').val() != '' && $('#rows').val() != undefined )
	{
		param = param + '&list_rows=' + $('#rows').val();
	}
		
	
	$('#box_container').html(loading);
	$('#box_container').load('ajax_components/ajax_com_professor_encode_grade_list.php?param=1' + param, null);
}
</script>
<table width=100% style=" font-family:Arial; font-size:12px;">
<tr>
  <td  style="font-size:12px; font-weight:bold; padding-top:20px;">
  <table cellspacing="5">
      <tr>
        <td width="100" style='font-weight:bold' valign="top">Professor Name:</td>
        <td width="250"><?=getProfessorFullName($prof_id)?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Employee No.:</td>
        <td><?=getEmployeeNumber($prof_id)?></td>
      </tr>
      <tr>
        <td style='font-weight:bold' valign="top">Department:</td>
        <td><?=getEmployeeDeptName($prof_id)?></td>
      </tr>
       <tr>
        <td style='font-weight:bold' valign="top">School Year:</td>
        <td><?=getSYandTerm($filter_school)?></td>
      </tr>
    </table>
  </td>
</tr>
</table>

    <table class="listview">    
        <tr>
          <th class="col_70"><!--<a href="#" class="sortBy" returnFilter="section_no">-->Section</th>
          <th class="col_70">Code</th>
          <th class="col_250">Subject</th>
          <th class="col_70">Room</th>   
          <th class="col_70">Schedule</th>   
          <th class="col_150">Action</th>                         
        </tr>
		<?php
		if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' )
		{
		$sqlOrderBy = ' ORDER BY  '. $_REQUEST['fieldName'] .' '. $_REQUEST['orderBy'];
		}
		$ctr = 1;
       $sql = "SELECT * FROM tbl_schedule WHERE employee_id = $prof_id ".$sqlcondition . $sqlOrderBy;						
        $query = mysql_query($sql);
        while($row = mysql_fetch_array($query))
        {
        ?>
            <tr class="<?=($ctr%2==0)?"":"highlight";?>"> 
                <td class="col_70"><?=$row['section_no']?></td>
                <td><?=getSubjCode($row["subject_id"])?></td>
                <td><?=getSubjName($row["subject_id"]).getSubjElecName($row['subject_id'],$prof_id)?></td>
                <td><?=getRoomNo($row["room_id"])?></td>
                <td><?=getScheduleDays($row["id"])?></td>    
                <td class="action">
                    <ul>
	                    <li><a class="profile" href="#" title="Encode Grade" returnId="<?=$row['id']?>"></a></li>
                    </ul>
                </td>                                     
            </tr>
        <?php
		$ctr++;
        }
        ?>
    </table>
    <div class="btn_container">
        <p class="button_container">
            <a href="#" class="button" title="Submit" id="cancel" returnComp="<?=$_REQUEST['comp']?>"><span>Cancel</span></a>
        </p>    
    </div>
    <p id="formbottom"></p>
<input type="hidden" name="comp" id="comp" value="<?=$_REQUEST['comp']?>" />