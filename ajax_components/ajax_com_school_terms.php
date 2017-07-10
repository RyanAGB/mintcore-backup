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

?>

<script type="text/javascript">



	$(document).ready(function(){  



	//initialize the tab action

	$('.period').click(function(){

	clearTabs();

	$('#period').addClass('active');

	$('#view').val('period');

	$('#syId').val($(this).attr("returnSYId"));

	$('#termId').val($(this).attr("returnTermId"));	

	$('#box_container').html(loading);		

	$("form").submit();

	});





	$('.add_period').click(function(){

	clearTabs();

	$('#add_period').addClass('active');

	$('#view').val('add_period');

	$('#syId').val($(this).attr("returnSYId"));

	$('#termId').val($(this).attr("returnTermId"));

	$('#box_container').html(loading);			

	$("form").submit();

	});	

	

	

	//doTheAction ('edit', 'edit', 'id_<?=$row['id']?>');

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

	$page_rows = $_REQUEST['list_rows']; 	

}		

else

{

	$page_rows = 10;

}





if (isset($_REQUEST["search_field"]) and ($_REQUEST["search_field"] != "") and ($_REQUEST["search_key"] != "") and ($_REQUEST["search_key"] != ""))

{

	$search_field = $_REQUEST["search_field"];

	$search_key = $_REQUEST["search_key"]; 

	$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";

}			



if(isset($_REQUEST['fieldName']) || $_REQUEST['fieldName'] != '' )

{

	$sqlOrderBy = ' ORDER BY  '. $_REQUEST['fieldName'] .' '. $_REQUEST['orderBy'];

}





if(count($arr_sql) > 0)

{

	$sqlcondition = count($arr_sql) == 1 ? ' AND ' . $arr_sql[0] : $arr_sqlimplode(' AND ', $arr_sql);

}



//Here we count the number of results 

//Edit $data to be your query 

$sql_pagination = "SELECT * FROM tbl_school_year

				  " .$sqlcondition ;

				  $query_pagination  = mysql_query ($sql_pagination);

				  $row_ctr = mysql_num_rows($query_pagination); 



// initial records

if(isset($_REQUEST['pageNum']) && $_REQUEST['pageNum'] != '')

{

	$pagenum = ($_REQUEST['pageNum']*$page_rows) - $page_rows; 

}

else

{

	$pagenum = 0; 

}

if($row_ctr>0)

{

	//This tells us the page number of our last page 

	$last = ceil($row_ctr/$page_rows); 			

	

	$max = 'limit ' .$pagenum .',' .$page_rows; 	

	

	$sql = "SELECT * FROM tbl_school_year

	" .$sqlcondition  . $sqlOrderBy . " $max" ;

	

	$result = mysql_query($sql);

	?>                  

	<?php  

    if (mysql_num_rows($result) > 0 )

    {



		if(CURRENT_SY_ID != '') /* ADDED BY RYAN2C*/

		{

			$x = 0;

			?>

			<table class="listview">      

				<tr>

					<th class="col_20">&nbsp;</th>

					<th class="col_150">School Year</th>

					<th class="col_350">Term</th>

					<th class="col_150">Current Term</th>           

				</tr>

				<?php

				while($row = mysql_fetch_array($result)) 

				{ 

					$sql_term = "SELECT * FROM tbl_school_year_term WHERE school_year_id = ".$row['id']. " AND school_year_id =" .CURRENT_SY_ID;

					$query_term = mysql_query($sql_term);

					$ctr = 0;

					while($row_term = mysql_fetch_array($query_term))

					{

						$ctr++;

						?>

						<tr class="<?=($x%2==0)?"":"highlight";?>">

							<td ><input type="checkbox" name="id" id="id_<?=$row_term['id']?>" value="<?=$row_term['id']?>" /></td>

							<td><?	if($ctr == 1) echo $row['start_year'] . '-' . $row['end_year']; ?></td> 

							<td><?=$row_term['school_term']?></td>

							<td>

								<ul>

									<li>

										<?

										if($row_term['is_current']=='Y')

										{

										?>

											<a class="checkmark" href="#" onclick="javascript:alert('You cannot unset this term.'); return false;" title="click to unset as current"></a>

										<?php

										}

										else

										{

										?>

											<a class="xmark" href="#" onclick="javascript:if(confirm('Are you sure you want to set this as current school term?'))lnk_publishItem('id_<?=$row_term['id']?>'); else return false;" title="click to set as current"></a>

										<?php

										}

										?>

									</li>

								</ul>

							</td>

						</tr>

                    

					<?php 

					}

					          

				}

				?>

                </table> 

                <p id="formbottom"></p>

                <?php

			}

			else

			{

				 echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';	

			}

		}

		else 

		{

			echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';	

		}

		?>

	



<?php

}

else 

{

	echo '<div id="message_container"><h4>No records found</h4></div><p id="formbottom"></p>';	

}

?>