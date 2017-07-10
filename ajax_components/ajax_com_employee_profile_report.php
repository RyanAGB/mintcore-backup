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

			
	if (isset($_REQUEST["search_field"]) and ($_REQUEST["search_field"] != "") and ($_REQUEST["search_key"] != "") and ($_REQUEST["search_key"] != "")){
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
	$sql_pagination = "SELECT * FROM tbl_building
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
		
		$sql = "SELECT * FROM tbl_building
							" .$sqlcondition  . $sqlOrderBy . " $max" ;
	
		$result = mysql_query($sql);

?>               
<script type="text/javascript">
	$('#search').click(function(){
		clearTabs();
		$('#edit_item').addClass('active');
		$('#view').val('edit');
		$("form").submit();
	});	
	</script>
        <div class="box" id="box_container">
        <div class="formview">
        <fieldset>
           <legend><strong>Employee Information</strong></legend>
             <label>Department</label>
            <span >
                <select name="department_id" id="department_id"  class="txt_300">
                  <option value="">Select</option>
                  <?=generateDepartment($department_id)?>
                </select>   
            </span><br class="hid" />            
          	<label>Employee Number</label>
                <span >
                <input class="txt_200" name="emp_id_number" type="text" value="<?=$emp_id_number?>" id="emp_id_number" />
                </span><br class="hid" />
            <span class="clear"></span>
            <label>Lastname</label>
                <span >
                <input class="txt_300" name="lastname" type="text" value="<?=$lastname?>" id="lastname" />
                </span><br class="hid" />
                <label>Firstname</label>
                <span >
                <input class="txt_300" name="firstname" type="text" value="<?=$firstname?>" id="firstname" />
                </span><br class="hid" /> 
                <label>Middlename</label>
                <span >
                <input class="txt_300" name="middlename" type="text" value="<?=$middlename?>" id="middlename" />
                </span><br class="hid" />
                <label>Filter Way</label>
            <span >
                <select name="filter" id="filter"  class="txt_300">
                   <option value="0">Exact Match</option>
                    <option value="1">First Character Match</option>
                </select>   
            </span><br class="hid" />
        </fieldset>
        
    
        
        <p class="button_container">
            <input type="hidden" name="id" id="id" value="<?=$id?>" />
                <a href="#" class="button" title="Search" id="search"><span>Search</span></a>
           <!-- <a href="#" class="button" title="Submit" id="cancel"><span>Cancel</span></a>!-->
        </p>
        
    </div><!-- /.formview -->

<p id="formbottom"></p>
</div>

<?php } ?>