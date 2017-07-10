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


$sql_acc_type = "SELECT * FROM tbl_access WHERE id =" .ACCESS_ID;
$qry_acc_type = mysql_query($sql_acc_type);
$row_acc_type = mysql_fetch_array($qry_acc_type);



if($row_acc_type['access_type'] == 'S')
{
$sql = "SELECT * FROM tbl_student WHERE user_id=" .USER_ID;
$qry =mysql_query($sql);
$row = mysql_fetch_array($qry);
?>
    <div class="box" id="box_container">
        <div class="formview">
        <fieldset>
               <legend><strong>Personal Information</strong></legend>
                
                <label>Name:</label>
                <span><?=$row['lastname'].', '.$row['firstname'].''.$row['middlename']?></span><br class="hid" />
                
                <label>Student Number:</label>
                <span><?=$row['student_number']?></span><br class="hid" />
                
                <label>Gender:</label>
                <span>
				<?php
                if($row['gender'] == 'M')
				{
					echo "Male";
				}
				else if($row['gender'] == 'F')
				{
					echo "Female";
				}
				?>
                </span><br class="hid" />
                
                <label>Course:</label>
                <span><?=getCourseName($row['course_id'])?></span><br class="hid" /> 
                
                <label>Civil Status:</label>
                <span>
				<?php
                if($row['civil_status'] == 'M')
				{
					echo "Married";
				}
				else if($row['civil_status'] == 'S')
				{
					echo "Single";
				}
				?>
                </span><br class="hid" /> 
                
                <label>BirthDate:</label>
                <span>
				<?php
                $birthday = explode ('-',$row['birth_date']);		
				$birth_year = $birthday['0'];
				$birth_day = $birthday['1'];
				$birth_month = $birthday['2'];
				if( $birth_day!='' && $birth_month!='' &&  $birth_year!='')
				{
					echo date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year));
				}
				?>
                </span><br class="hid" />
                
                <label>E-mail Address:</label>
                <span><?=$row['email']?></span><br class="hid" /> 
                
                <label>Contact No.:</label>
                <span><?=$row['tel_number']?></span><br class="hid" />  
                
                <label>Mobile No.:</label>
                <span><?=$row['mobile_number']?></span><br class="hid" />
                
                <span class="clear"></span>
            </fieldset>
            <fieldset>
               <legend><strong>Photo</strong></legend>
     			<?php
                $sql_student_photo = "SELECT * FROM tbl_student_photo WHERE student_id =" .$row['id'];
                $qry_student_photo = mysql_query($sql_student_photo);
				$row_student_photo = mysql_fetch_array($qry_student_photo);
				
                if($row_student_photo['image_file'] != '')
                {
                ?>
                	<img src="includes/student_image.php?student_id=<?=$row['id']?>"/>
                <?php
                }
                else
                {
                ?>
                	<img src="images/NoPhotoAvailable.jpg"/>
                <?php
                }
                ?>
                 
                <span class="clear"></span>
            </fieldset>
        </div>        
    
    <p id="formbottom"></p>
    </div>
<?php
}
else if ($row_acc_type['access_type'] == 'A' || $row_acc_type['access_type'] == 'E')
{
	$sql = "SELECT * FROM tbl_employee WHERE user_id=" .USER_ID;
	$qry =mysql_query($sql);
	$row = mysql_fetch_array($qry);
	?>
	<div class="box" id="box_container">
		<div class="formview">
		<fieldset>
			   <legend><strong>Personal Information</strong></legend>
				
				<label>Name:</label>
				<span ><?=$row['lastname'].', '.$row['firstname'].''.$row['middlename']?></span><br class="hid" />
				
				<label>Employee Number:</label>
				<span><?=$row['emp_id_number']?></span><br class="hid" />
				
				<label>Gender:</label>
				<span>
				<?php
                if($row['gender'] == 'M')
				{
					echo "Male";
				}
				else if($row['gender'] == 'F')
				{
					echo "Female";
				}
				?>
                </span><br class="hid" />
				
				<label>Department:</label>
				<span><?=getDeptName($row['department_id'])?></span><br class="hid" /> 
				
				<label>Civil Status:</label>
				<span>
				<?php
                if($row['civil_status'] == 'M')
				{
					echo "Married";
				}
				else if($row['civil_status'] == 'S')
				{
					echo "Single";
				}
				?>
                </span><br class="hid" /> 
				
				<label>BirthDate:</label>
				<span>
				<?php
                $birthday = explode ('-',$row['birth_date']);		
				$birth_year = $birthday['0'];
				$birth_day = $birthday['1'];
				$birth_month = $birthday['2'];
				if( $birth_day!='' && $birth_month!='' &&  $birth_year!='')
				{
					echo date("F d, Y", mktime(0, 0, 0, $birth_day, $birth_month, $birth_year));
				}
				?>
                </span><br class="hid" />
				
				<label>E-mail Address:</label>
				<span><?=$row['email']?></span><br class="hid" /> 
				
				<label>Contact No.:</label>
				<span><?=$row['tel_number']?></span><br class="hid" />  
				
				<label>Mobile No.:</label>
				<span><?=$row['mobile_number']?></span><br class="hid" />
				
				<span class="clear"></span>
			</fieldset>
			<fieldset>
			   <legend><strong>Photo</strong></legend>
	 
				<label>Photo</label>
				<?php
                $sql_employee_photo = "SELECT * FROM tbl_employee_photo WHERE employee_id =" .$row['id'];
                $qry_employee_photo = mysql_query($sql_employee_photo);
				$row_employee_photo = mysql_fetch_array($qry_employee_photo);
				
				if($row_employee_photo['image_file'] != '')
				{
				?>
					<img src="includes/employee_image.php?employee_id=<?=$row['id']?>"/>
				<?php
				}
				else
				{
				?>
					<img src="images/NoPhotoAvailable.jpg"/>
				<?php
				}
				?>
				<span class="clear"></span>
			</fieldset>
		</div>        
	
	<p id="formbottom"></p>
	</div>
<?php
}
else if($row_acc_type['access_type'] == 'P')
{
$sql = "SELECT * FROM tbl_parent WHERE user_id=" .USER_ID;
$qry =mysql_query($sql);
$row = mysql_fetch_array($qry);
?>
    <div class="box" id="box_container">
        <div class="formview">
        <fieldset>
               <legend><strong>Personal Information</strong></legend>
                
                <label>Name:</label>
                <span><?=$row['lastname'].', '.$row['firstname'].''.$row['middlename']?></span><br class="hid" />
                
                <label>E-mail Address:</label>
                <span><?=$row['email']?></span><br class="hid" /> 

                <span class="clear"></span>
            </fieldset>
        </div>        
    
    <p id="formbottom"></p>
    </div>
<?php
}
?>