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
	include_once('../includes/common.php');		
	
	if(USER_IS_LOGGED != '1')
	{
		header('Location: ../index.php');
	}
	else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}
	
	$student_id = $_REQUEST['student_id'];
?>
<script type="text/javascript">

$(function(){

	$('#print').click(function() {
			var w=window.open();
			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
			w.document.write($('#print_div').html());
			w.document.close();
			w.focus();
			w.print();
			//w.close()
			return false;
		});
		
		$('#print2').click(function() {
			var w=window.open();
			w.document.write('<link type="text/css" href="css/style_print.css" rel="stylesheet"/>');
			w.document.write($('#print_div').html());
			w.document.close();
			w.focus();
			w.print();
			w.close()
			return false;
		});		
	
		$('#page_rows').change(function(){
		$('#page').val(1);
		$('#rows').val($('#page_rows').val());
		updateList();
	});
});	

</script>   

<?php
	$arr_sql = array();
	$sqlcondition = '';
	$sqlOrderBy = '';
	$order = '';
	
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

	if (isset($_REQUEST["search_field"]) and ($_REQUEST["search_field"] != "") and ($_REQUEST["search_key"] != "") and ($_REQUEST["search_key"] != "")){
		$search_field = $_REQUEST["search_field"];
		$search_key = $_REQUEST["search_key"]; 
		$arr_sql[] = $search_field . " like '%" . addslashes($search_key) . "%'";
	}			

	if(count($arr_sql) > 0)
	{
		$sqlcondition = count($arr_sql) == 1 ? ' WHERE ' . $arr_sql[0] : $arr_sqlimplode(' AND ', $arr_sql);
	}
			
	//Here we count the number of results 
	//Edit $data to be your query 
	$sql_pagination = "SELECT * FROM tbl_system_logs
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
		
		$sql = "SELECT * FROM tbl_system_logs
							" .$sqlcondition  .  " ORDER BY id desc $max" ;
	
		$result = mysql_query($sql);

?>                  		
<!-- <div id="pageRows">
        <span>show</span>
        <select name="page_rows" id="page_rows">
          <option value="<?=$_SESSION[CORE_U_CODE]['default_record']!='' ? $_SESSION[CORE_U_CODE]['default_record']:DEFAULT_RECORD?>"<?=$page_rows==DEFAULT_RECORD||$page_rows==$_SESSION[CORE_U_CODE]['default_record'] ? 'selected=selected':''?>>Default</option>
          <option value="10"<?=$page_rows==10 ? 'selected=selected':''?>>10</option>
          <option value="20"<?=$page_rows==20 ? 'selected=selected':''?>>20</option>
          <option value="50"<?=$page_rows==50 ? 'selected=selected':''?>>50</option>
          <option value="100"<?=$page_rows==100 ? 'selected=selected':''?>>100</option>
          <option value="150"<?=$page_rows==150 ? 'selected=selected':''?>>150</option>            
        </select>
    </div>!-->
<div>&nbsp;</div>
<div class="fieldsetContainer502">
        <fieldset class="fieldset_big">
            <legend><strong>System Logs</strong></legend>
            <div style="height:600px; overflow:auto; font-size:12px">
            <?php 
				while($row = mysql_fetch_array($result))
				{
			?>
            <?=getUserFullNameFromUserId($row['user_id']) . ' ( '.  date("F  d , Y h:i" ,$row['date_created']) .' )' . '<br />'. $row['message']. '<br /><br />';?>
            <?php
				}
			?>
            </div>
        </fieldset>
</div>
       
   		    <p id="pagin">

        	<?php
            /*for($x=1;$x<=$last;$x++) {
                if ($_REQUEST['pageNum'] == $x) {
            ?>	
                <a href="#"><?=$x?></a>
            <?php		
                } else {
            ?>
                <a href="#list" onclick="updateList(<?=$x?>)"><?=$x?></a>
            <?php } 
            }*/ 
            ?>
        
        </p>
<?php
}
?>