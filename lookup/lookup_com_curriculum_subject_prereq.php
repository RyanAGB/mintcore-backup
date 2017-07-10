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



$filterfield = $_REQUEST['filterfield'];

$filtervalue = $_REQUEST['filtervalue'];

?>



<script type="text/javascript">

	$(function(){

		$('.selector').click(function() {



			var valTxt = $(this).attr("returnTxt");

			var valTxtCode = $(this).attr("returnTxtCode");

			var valId = $(this).attr("returnId");

			var unique = true;



			$.each($('#prerequisite'), function(index, value) { 

				if($(this).val()== valId)

				{

					unique = false;

				}

			});

			

			if($('#subject_id').val() == valId)

			{

				alert('The current subject is same with the selected prerequisite!');

			}

			else if(unique == true)

			{

				var htmlContents = $('#prereq_container').html();

				

				var filter = '<div id="prerequisite_item_' + valId + '">' +

							 '<label><a href="#" title="remove this subject" onclick="$(\'#prerequisite_item_' + valId + '\').html(\'\'); $(\'#prerequisite_item_' + valId + '\').css(\'display\',\'none\'); return false;" ><img src="images/icon_negative.png" border="0"/></a>&nbsp;&nbsp;' +

							 '<input class="txt" name="prerequisite[]" type="hidden" value="' + valId + '" id="prerequisite" readonly="readonly" />' + 

							 '(' + valTxtCode + ') '+ valTxt + '</label> ' + 

							 '</div>';

				

				$('#prereq_container').html(htmlContents + filter);

			}

			else

			{

				alert('Already exist!');

			}

			$('#preReq_dialog').dialog('close');

								

		});

	});

	

	$(function(){	

	$('#filter_department').change(function(){

		var param = '';

		
			param = param + '&filterfield=department_id&filtervalue=' + $('#filter_department').val()+'&comp='+$('#comp').val();
	

	$('#preReq_dialog').load('lookup/lookup_com_curriculum_subject_prereq.php?listrow=10'+ param, null);

	});



});



</script>



<?php

	if(isset($filterfield) && $filtervalue!='all')

	{

		$sqlcon = 'AND '.$filterfield.'='.$filtervalue;

	}

	

?>



<div id="lookup_content">

	Filter By: Department<select name="filter_department" id="filter_department" class="txt_200" >

    

                    <option value="" >-Select-</option>

                    <option value="all" <?=$filtervalue == 'all'? 'selected="selected"':''?> >All</option>

                     <?=generateDepartment($filtervalue)?>  

                </select>

           <input type="hidden" name="comp" id="comp" value="<?=$_REQUEST['comp']?>" />
           <input type="hidden" name="filterdep" id="filterdep" value="<?=$_REQUEST['filterdep']?>" />

        <?php

        $x = 1;

		if( $filtervalue!='')

		{

		?>



		 <table class="fieldsetList">      

        <tr>

            <th class="col1_100">&nbsp;</th> 

            <th class="col1_400"><a href="#">Subject Code</a></th>   

            <th class="col1_400"><a href="#">Subject Name</a></th>  

            <th class="col1_400"><a href="#">Department</a></th>    

        </tr>

		<?php

		$sql = "SELECT * FROM tbl_subject WHERE publish = 'Y' ".$sqlcon." ORDER BY `subject_code` ASC";						

		$result = mysql_query($sql);

        while($row = mysql_fetch_array($result)) 

        { 

        ?>

            <tr class="<?=($x%2==0)?"":"highlight";?>">

                <td><a href="#" name="id" id="id_<?=$row['id']?>" class="selector" returnId="<?=$row['id']?>" returnTxt="<?=$row['subject_name']?>" returnTxtCode="<?=$row['subject_code']?>">Select</a></td>

                <td><?=$row["subject_code"]?></td>

                <td><?=$row["subject_name"]?></td>

                <td><?=getDeptName($row["department_id"])?></td>

            </tr>

        <?php 

            $x++;          

        }

        ?>

    </table> 

    	<?php 

		if(mysql_num_rows($result) == 0)

		 {  

		 ?>

         	<div style="font-family:Arial; font-size:12px; padding-top:10px;">

                <label>No Records Found.</label>

            </div>

         <?php

		 }

        }

		else

		{

		

        ?>

        	<div style="font-family:Arial; font-size:12px; padding-top:10px;">

                <label>Please select department.</label>

            </div>

         <?php

		 }

		 ?>

</div> <!-- #lookup_content -->

<?php

}

?>