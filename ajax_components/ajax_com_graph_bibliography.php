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
	
	$graph_arr = array();
	$color_arr = array();
	
	$filter = $_REQUEST['filter'];
	
	$sql = "SELECT b.bibid, c.barcode_nmbr, b.title, b.author, c.copyid
				FROM ob_biblio_copy c, ob_biblio b
				WHERE c.bibid = b.bibid
				AND b.bibid =".$filter;	
	$query = mysql_query($sql);
	//$cnt = 0;
	$cnt = mysql_num_rows($query);
	$ctr = mysql_num_rows($query) * 2;
if($ctr > 0)
{
	while($row = mysql_fetch_array($query))
	{
			$graph_arr[] = "[' ', ". countCheckoutBibliography($row['bibid'],$row['copyid'])."]";
	}

	
?>
 <?php 
 //This is our first loop, changing the R value 
 $r=0;
 while ($r <= 15)
 { 
 //Our second loop (G value) occurs 6 times for ever R value, 36 times
 $g=$r;
	 while ($g <= 15)
	 { 
	
	 //Our third loop (B value) occurs 6 times for ever G value, or 216 times
		 $b=9;
		 while ($b <= 15) 
		 { 
		
		 //Here we actually generate the color blocks
		 $background = dechex($r) . dechex($r) . dechex($g) .dechex($g) . dechex($b) . dechex ($b);
		 if($background != 'ffffff')
		 {
		 	$color_arr[] = "'#$background'";
		 }
		 //At the end of each loop we add 3
		 $b = $b+3;
		 } 
	 $g = $g+3;
	 }
 $r = $r+3;
 } 

	if($cnt <= 1)
	{
		$color_temp = array();
		 $color_temp[] = array_rand($color_arr,$cnt);
	}
	else
	{
	 $color_temp = array_rand($color_arr,$cnt);
	 }
 
 $ctr = 0;

foreach($color_temp as $index)
{
	$colors[] = $color_arr[$index];
	$color[$ctr] = 	$color_arr[$index];
	$ctr ++;
}
 ?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="graph">Loading...</div></td>
    <td valign="top" style="padding-top:30px;">
        <div class="filter">
        
        <?php
            $sql = "SELECT b.bibid, c.barcode_nmbr, b.title, b.author, c.copyid
				FROM ob_biblio_copy c, ob_biblio b
				WHERE c.bibid = b.bibid
				AND b.bibid =".$filter;	
            $query = mysql_query($sql);
            $ctr = mysql_num_rows($query) * 2;
			$x = 0;
            while($row = mysql_fetch_array($query))
            {
				$quotes = array('/"/',"/'/");
				$replacements = array('');
				$col = preg_replace($quotes,$replacements,$colors[$x]);
				
               ?>
			   <span style="background-color:<?=$col?>">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
             
			   <?php
                echo $row['title']."(".$row['barcode_nmbr'].") "." = " . countCheckoutBibliography($row['bibid'],$row['copyid']) ."<br /><br />";
                $ctr++;	
				$x++;	
            }
        ?>
        </div>
	</td>
  </tr>
</table>

<script type="text/javascript">
	
	var myData = new Array(<?=implode(',',$graph_arr)?>);
	var colors = [<?=implode(',',$colors)?>];
	var myChart = new JSChart('graph', 'pie');
	myChart.setDataArray(myData);
	myChart.colorizePie(colors);
	myChart.setTitle('<?=getBibliographyTitle($filter)?> Checkout Count');
	myChart.setTitleColor('#857D7D');
	myChart.setPieUnitsColor('#9B9B9B');
	myChart.setPieValuesColor('#6A0000');
	myChart.setPieOpacity(0.8);
	myChart.draw();
	
</script>

<?php
	$bargraph_data_arr = array();
	$sql = "SELECT b.bibid, c.barcode_nmbr, b.title, b.author, c.copyid
				FROM ob_biblio_copy c, ob_biblio b
				WHERE c.bibid = b.bibid
				AND b.bibid =".$filter;
	$query = mysql_query($sql);
	while($row = mysql_fetch_array($query))
	{
	
		$bargraph_data_arr[] = "[' ', ". countCheckoutBibliography($row['bibid'],$row['copyid'])."]";
	}					
	
?>
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="padding-left:10px; width:350px;">
        <div id="bargraph_<?=$row['id']?>">Loading...</div>
        </td>
        <td valign="top" style="padding-top:30px;">
            <div class="filter">
            <?php /*
            $sql = "SELECT b.bibid, c.barcode_nmbr, b.title, b.author, c.copyid
				FROM ob_biblio_copy c, ob_biblio b
				WHERE c.bibid = b.bibid
				AND b.bibid =".$filter;	
            $query = mysql_query($sql);
            $ctr = mysql_num_rows($query) * 2;
			$x=0;
            while($row = mysql_fetch_array($query))
            {	
				$quotes = array('/"/',"/'/");
				$replacements = array('');
				$colr = preg_replace($quotes,$replacements,$colors[$x]);
							
				if(countCheckoutBibliography($row['bibid'],$row['copyid']) > 0)
				{
               ?>
			   <span style="background-color:<?=$colr?>">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;
             
			   <?php
			   }
                echo $row['title']."(".$row['barcode_nmbr'].") "." = " . countCheckoutBibliography($row['bibid'],$row['copyid']) ."<br /><br />";
                $ctr ++;
				$x++;	
			}	*/
        ?>
            </div>
        </td>
      </tr>
    </table>   
    <br /><br/> <br /><br/> 
    <script type="text/javascript">
    var myData = new Array(<?=implode(',',$bargraph_data_arr)?>);
    var myChart = new JSChart('bargraph_<?=$row['bibid']?>', 'bar');
    var colors = [<?=implode(',',$colors)?>];
    myChart.setDataArray(myData);
    myChart.colorizeBars(colors);
	myChart.setTitle('');
	myChart.setAxisNameX('');
	myChart.setAxisNameY('');
    myChart.setAxisValuesColor('#008');
    myChart.setAxisColor('#ABABAB');
    myChart.setAxisWidth(1);
    myChart.setAxisValuesColor('#858585');
    myChart.setAxisNameColor('#858585');
    myChart.setBarBorderColor('#bbb');
    myChart.setBarOpacity(0.9);
    myChart.setBarSpacingRatio(70);
    myChart.setBarValues(false);
    myChart.setTitleColor('#928888');
    myChart.setGridColor('#ABABAB');
    myChart.draw();
    </script>
<?php		
		unset($bargraph_data_arr);

?>

<p id="formbottom"></p>
<?php
}
else
{
	 echo '<div id="message_container"><h4>No copies found</h4></div><p id="formbottom"></p>';
}
?>

