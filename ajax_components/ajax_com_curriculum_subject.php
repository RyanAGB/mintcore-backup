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

	include_once('../includes/functions.php');

	include_once("../includes/common.php");	

	

	if(USER_IS_LOGGED != '1')

	{

		header('Location: ../index.php');

	}

	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))

	{

		header('Location: ../forbid.html');

	}

	

	$fieldName = $_REQUEST['fieldName'];

	?>

	<script type="text/javascript">

$(function(){



	// Dialog			

	$('#dialog').dialog({

		autoOpen: false,

		width: 600,

		height: 500,

		bgiframe: true,

		modal: true,

		buttons: {

			"Close": function() { 

				$(this).dialog("close"); 

			} 

		}

	});

	

	// Dialog Link

	$('.curSub').click(function(){

		var id = $(this).attr("returnId");

		$('#dialog').html(loading);

		$('#dialog').load('lookup/lookup_com_curriculum_info.php?id='+id, null);

		$('#dialog').dialog('open');

		return false;

	});

	

});	

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

		updateList();

		return false;

	});

	

});

</script>

	<?php

	$arr_sql = array();

	$sqlcondition = '';

	$sqlOrderBy = '';

	if(isset($_REQUEST['list_rows']))

	{

		$_SESSION[CORE_U_CODE]['pageRows'] = $_REQUEST['list_rows'];

		$page_rows = $_SESSION[CORE_U_CODE]['pageRows']; 	

	}

	else if($_SESSION[CORE_U_CODE]['default_record']!='')

	{

		$page_rows = $_SESSION[CORE_U_CODE]['default_record'];

	}

	else

	{

		$page_rows = DEFAULT_RECORD;

	}	



			

	if (isset($_REQUEST["filterCourse"]) and ($_REQUEST["filterCourse"] != "")){

		$search_key = $_REQUEST["filterCourse"]; 

		$arr_sql[] = " course.id= " . addslashes($search_key);

	}	

	if (isset($_REQUEST["filterCurr"]) and ($_REQUEST["filterCurr"] != "")){

		$search_key = $_REQUEST["filterCurr"]; 

		$arr_sql[] = " curriculum.id= " . addslashes($search_key);

	}			



	if(isset($fieldName) || $fieldName != '' )

	{

	

		if($fieldName == 'subject_name')

		{

			$sqlOrderBy = " ORDER BY  subject.$fieldName ". $_REQUEST['orderBy'] ." , curriculum.curriculum_code ASC";

		}

		else

		{

			$sqlOrderBy = ' ORDER BY  '. $fieldName .' '. $_REQUEST['orderBy'];

		}

		

	}

	else if($_SESSION[CORE_U_CODE]['fieldName']!='' || $_SESSION[CORE_U_CODE]['orderBy'] !='')

	{

		if($_SESSION[CORE_U_CODE]['fieldName'] == 'subject_name')

		{

			$sqlOrderBy = " ORDER BY  subject.".$_SESSION[CORE_U_CODE]['fieldName']." ". $_SESSION[CORE_U_CODE]['orderBy'] ." , curriculum.curriculum_code ASC";

		}

		else

		{

			$sqlOrderBy = ' ORDER BY  '. $_SESSION[CORE_U_CODE]['fieldName'] .' '. $_SESSION[CORE_U_CODE]['orderBy'];

		}

	}





	if(count($arr_sql) > 0)

	{

		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : ' AND '.implode(' AND ', $arr_sql);

	}

			

	//Here we count the number of results 

	//Edit $data to be your query 

	$sql_pagination = "SELECT cur_s.*,course.id as cID 

					FROM 

						tbl_curriculum_subject cur_s,

						tbl_curriculum curriculum,

						tbl_subject subject,

						tbl_course course

					WHERE

						cur_s.curriculum_id = curriculum.id AND

						course.id = curriculum.course_id AND

						subject.id = cur_s.subject_id

						" .$sqlcondition ;

						

	$query_pagination  = mysql_query ($sql_pagination);

	$row_ctr = mysql_num_rows($query_pagination); 

	

	// initial records

	if(isset($_REQUEST['pageNum']))

	{

		$pagenum = ($_REQUEST['pageNum']*$page_rows) - $page_rows; 

	}

	else if($_SESSION[CORE_U_CODE]['pageNum'] != '')

	{

		$pagenum = ($_SESSION[CORE_U_CODE]['pageNum']*$page_rows) - $page_rows; 

	}

	else

	{

		$pagenum = '1';

	}

	if($row_ctr>0)

	{

		//This tells us the page number of our last page 

		$last = ceil($row_ctr/$page_rows); 			

				

		$max = 'limit ' .$pagenum .',' .$page_rows; 	

		

		$summer = 1;

		$sqlmax = "SELECT * 

					FROM tbl_curriculum";

			if(isset($_REQUEST["filterCurr"]))

			{

				 $sqlmax.=" WHERE id=".$_REQUEST["filterCurr"];

			}

			//echo $sqlmax;

		$querymax = mysql_query($sqlmax);

		$rowmax = mysql_fetch_array($querymax);



  if (isset($_REQUEST["filterCourse"]) and ($_REQUEST["filterCurr"] != ""))

  {

  

		$termAll = ($rowmax['term_per_year']*1) + ($summer*1);

		

		for($ctr_year = 1; $ctr_year<= $rowmax['no_of_years']; $ctr_year++)

		{

			for($ctr_terms = 1; $ctr_terms<= $termAll; $ctr_terms++)

        	{

			
			$sql = "SELECT cur_s.*,course.id as cID 

					FROM 

						tbl_curriculum_subject cur_s,

						tbl_curriculum curriculum,

						tbl_subject subject,

						tbl_course course

					WHERE

						cur_s.curriculum_id = curriculum.id AND

						course.id = curriculum.course_id AND

						subject.id = cur_s.subject_id AND

						year_level = ".$ctr_year." AND

						term = ".$ctr_terms."

							" .$sqlcondition  . $sqlOrderBy . " $max" ;

	

			$result = mysql_query($sql);

        ?>

        

        <table class="listview">
        	
            <?php
				if(mysql_num_rows($result)>0)
				{
			?>

        	<tr>

                <th colspan="6" class="col_20"><?=getYearLevel($ctr_year)?>( <?=$ctr_terms== $termAll?'Summer':getSemesterInWord($ctr_terms)?>)</th>

           </tr>      

          <tr>

                <th class="col_20">&nbsp;</th>

            <th class="col_50"><a href="#" class="sortBy" returnFilter="curriculum_code">Curriculum</a></th>

                <!--<th class="col_250"><a href="#" class="sortBy" returnFilter="course_name">Course</a></th>!-->

                <th class="col_250"><a href="#" class="sortBy" returnFilter="subject_name">Subject</a></th>

                <th class="col_20"><a href="#" class="sortBy" returnFilter="units">Units</a></th>

                <th class="col_100"><a href="#" class="sortBy" returnFilter="term">Standing</a></th>

                <th class="col_20">Action</th>

          </tr>

        <?php

            while($row = mysql_fetch_array($result)) 

            { 

        ?>

            <tr class="<?=($x%2==0)?"":"highlight";?>">

                <td ><input type="checkbox" name="id" id="id_<?=$row['id']?>" value="<?=$row['id']?>" /></td>

                <td ><?=getCurriculumCode($row['curriculum_id'])?></td>

                <!--<td ><?=getCourseName($row['cID'])?></td>!-->

                <td ><?=getSubjName($row['subject_id']).' ('.getSubjCode($row['subject_id']).')'?></td>

                <td ><?=$row['units']?></td>

                <td><?=getYearLevel($row['year_level']).' ('.getSemesterInWord($row['term']).')'?></td>

                <td class="action">

                    <ul>

                        <li><a class="edit" href="javascript:doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');" title="edit"></a></li>

                        <li><a class="delete" href="#" onclick="javascript:lnk_deleteItem('id_<?=$row['id']?>');" title="delete"></a></li>

         

                    </ul>

                </td>

            </tr>

        <?php           

		  }

		 }

        }
	}

        //else 

        //{

        //        echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';

       // }

        ?>

        </table> 

        <p id="pagin">



        	<?php /*

            for($x=1;$x<=$last;$x++) {

                if ($_REQUEST['pageNum'] == $x) {

            ?>	

                <a href="#"><?=$x?></a>

            <?php		

                } else {

            ?>

                <a href="#list" onclick="updateList(<?=$x?>)"><?=$x?></a>

            <?php } 

            } */

            ?>

        

        </p>

        

<?php

}

	else 

	{

			echo '<div id="message_container"><h4>Select Course and Curriculum</h4></div><p id="formbottom"></p>';

	}

	}

	else 

	{

			echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';

	}

?>

<!-- LIST LOOK UP-->

<div id="dialog" title="Curriculum Subjects">

    This will be replace by the look up contents

</div><!-- #dialog -->

