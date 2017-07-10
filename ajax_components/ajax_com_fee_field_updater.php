<?php
	include_once("../config.php");

	include_once('../includes/functions.php');

	include_once('../includes/common.php');
	
$mod = $_REQUEST['mod'];
$id = $_REQUEST['id'];
$student_id = $_REQUEST['stud_id'];

$arr_filter = array();
if($mod == 'updateField')
{
	echo generateSchoolTermsWithoutPastBYSY($id);
}
else if($mod == 'updateFee')
{
?>
	<table class="classic">

            <tr>

                <th>Fees</th>

                <th>Amount</th>

                <th>Total</th>
                
                <!--<th>&nbsp;</th>!-->

            </tr> 

        <?php

            $sql = "SELECT *

                    FROM tbl_school_fee

                    WHERE publish =  'Y' AND 

					term_id=" .$id;

                    

            $result = mysql_query($sql);

            $sub_total = 0;

            while($row = mysql_fetch_array($result)) 

            {

               // $total = getStudentTotalFeeLecLab($row['id'],$student_id );

        ?>

            <tr>

                <td><?=$row['fee_name'].' ('.getFeeUnit($row['id'],$student_id).')'?></td>

                <td><?=getFeeAmount($row['id'])?></td>

                <td>

                  <div align="right">

                    Php <?=$total==''?'0.00':number_format($total, 2, ".", ",")?>

                  </div></td>
                  
                  <!--<td><a href="#"><img src="../images/led-ico/add.png" /></a>
                  <a href="#"><img src="../images/led-ico/cross.png" /></a></td>!-->
                  
                

            </tr>

        <?php           

                $sub_total += $total;

           }

		   

		   /* TOTAL OTHER PAYMENT */

			$sql_fee_other = "SELECT * FROM tbl_school_fee WHERE publish =  'Y' AND term_id=" .$id;

			$result_fee_other = mysql_query($sql_fee_other);

			$row_fee_other = mysql_fetch_array($result_fee_other);

			$sub_mis_total = 0;

			$mis_total = getStudentOtherFeeByFeeId($row_fee_other['id'],$student_id);           

			$sub_mis_total += $mis_total;

			

		   /* TOTAL LEC PAYMENT */

			$sql_lec = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlec' AND publish =  'Y' AND term_id=" .$id;

			$qry_lec = mysql_query($sql_lec);

			$row_lec = mysql_fetch_array($qry_lec);

			$sub_lec_total = 0;

			$lec_total = getStudentTotalFeeLecLab($row_lec['id'],$student_id);           

			$sub_lec_total += $lec_total;

			

			/* TOTAL LAB PAYMENT */

			$sql_lab = "SELECT * FROM tbl_school_fee WHERE fee_type = 'perunitlab' AND publish =  'Y' AND term_id=" .$id;

			$qry_lab = mysql_query($sql_lab);

			$row_lab = mysql_fetch_array($qry_lab);

			$sub_lab_total = 0;

			

			

			$lab_total = getStudentTotalFeeLecLab($row_lab['id'],$student_id);           

			$sub_lab_total += $lab_total;

        ?>  

            <tr>

                <td>&nbsp;</td>

                <td><strong>Total</strong></td>

                <td>

                  <div align="right">

                    Php <?=number_format($sub_total, 2, ".", ",")?>

                  </div></td>

            </tr>                              

      </table>
<?php      
}
else if($mod == 'updateScheme')
{

?>
	Select Payment Scheme
        	<select name="scheme_id" class="txt_150" id="scheme_id">

                  <option value="">Select</option>

                        <?=generateScheme('',$id)?>

              </select>       
<?php	
}
?>