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

	

	$fieldName = $_REQUEST['fieldName'];

	?>

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

	

	$('.edit').click(function(){

			$('#room_list').addClass('active');

			$('#stud_id').val($(this).attr('returnID'));

			$('#view').val('edit');

			$('#box_container').html(loading);

			$("form").submit();

	});

	

});

</script>

<?php

	

	$arr_sql = array();

	$sqlcondition = '';

	$sqlOrderBy = '';

	

	$filter_schoolterm = $_REQUEST['filter_schoolterm'];

	

	if(isset($_REQUEST['list_rows']))

	{

		$page_rows = $_REQUEST['list_rows']; 	

	}		

	else if($_SESSION[CORE_U_CODE]['pageRows'] != '')

	{

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

		

	/*if (isset($filter_schoolterm) and ($filter_schoolterm != "")){

		$arr_sql[] =  "student.term_id = " . $filter_schoolterm;

	}	*/



	if(isset($fieldName) || $fieldName != '' )

	{

	

		if($fieldName == 'course_name')

		{

			$sqlOrderBy = " ORDER BY  course.$fieldName ". $_REQUEST['orderBy'] ." , student.lastname ASC";

		}

		else

		{

			$sqlOrderBy = ' ORDER BY  '. $fieldName .' '. $_REQUEST['orderBy'];

		}

		

	}

	else if($_SESSION[CORE_U_CODE]['fieldName']!='' || $_SESSION[CORE_U_CODE]['orderBy'] !='')

	{

		if($fieldName == 'course_name')

		{

			$sqlOrderBy = " ORDER BY  course.". $_SESSION[CORE_U_CODE]['fieldName'] ." ". $_SESSION[CORE_U_CODE]['orderBy'] ." , student.lastname ASC";

		}

		else

		{

		$sqlOrderBy = ' ORDER BY  '. $_SESSION[CORE_U_CODE]['fieldName'] .' '. $_SESSION[CORE_U_CODE]['orderBy'];

		}

	}





	if(count($arr_sql) > 0)

	{

		$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sqlimplode(' AND ', $arr_sql);

	}

			

	//Here we count the number of results 

	//Edit $data to be your query 

	$sql_pagination = "SELECT * 

					FROM 

						tbl_student

						"  .$sqlcondition; 

						

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

		

		$sql = "SELECT * 

					FROM 

						tbl_student

							" .$sqlcondition  . $sqlOrderBy . " $max" ;

	

		$result = mysql_query($sql);

?>                  





        

        <?php  

        if (mysql_num_rows($result) > 0 )

        {

            $x = 1;

        ?>

        

        <table class="listview">      

          <tr>

              <th class="col_100"><a href="#" class="sortBy" returnFilter="student_number">Student Number</a></th>

              <th class="col_150"><a href="#" class="sortBy" returnFilter="lastname">Full Name</a></th>

              <th class="col_350"><a href="#" class="sortBy" returnFilter="course_name">Course</a></th> 

              <th class="col_50">Action</th>

          </tr>

        <?php

            while($row = mysql_fetch_array($result)) 

            { 

        ?>

            <tr class="<?=($x%2==0)?"":"highlight";?>">



                <td><?=$row["student_number"]?></td> 

                <td><?=$row["lastname"].",".$row["firstname"]." ".$row["middlename"]?></td>

                <td><?=getCourseName($row['course_id'])?></td>

                <td class="action">

                    <ul>

                        <li><a class="edit" returnID="<?=$row['id']?>" href="#" title="shift"></a></li>

                      

                    </ul>

                </td>

            </tr>

        <?php    

			$x++;       

           }

        }

        else 

         {

               echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';

        }

        ?>

        </table> 

        <p id="pagin">



        	<?php

            for($x=1;$x<=$last;$x++) {

                if ($_REQUEST['pageNum'] == $x) {

            ?>	

                <a href="#"><?=$x?></a>

            <?php		

                } else {

            ?>

                <a href="#list" onclick="updateList(<?=$x?>)"><?=$x?></a>

            <?php } 

            } 

            ?>

        

        </p>

        

<?php

	}

	else 

	{

			echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';

	}

?>