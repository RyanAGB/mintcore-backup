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

if(!isset($_REQUEST['comp']))
{
include_once("../config.php");
include_once("../includes/functions.php");
include_once("../includes/common.php");
}


if(USER_IS_LOGGED != '1')
{
	echo '<script language="javascript">redirect(\'index.php\');</script>'; // No Access Redirect to main
}
else if(!in_array($_REQUEST['comp'],$_SESSION[CORE_U_CODE]['access_components']))
	{
		header('Location: ../forbid.html');
	}



$page_title = 'Manage Payment Scheme';
$pagination = 'Billing  > Manage Payment Scheme';

$view = $view==''?'list':$view; // initialize action

$id	= $_REQUEST['id'];
$temp 	= $_REQUEST['temp'];

$name			= $_REQUEST['name'];
$surcharge		= $_REQUEST['surcharge'];
$term_id		= $_REQUEST['term_id'];
$publish		= $_REQUEST['publish'];

$payment_name	= $_REQUEST['payment_name'];
$payment_type	= $_REQUEST['payment_type'];
$payment_value	= $_REQUEST['payment_value'];
$payment_date	= $_REQUEST['payment_date'];
$sort_order		= $_REQUEST['sort_order'];

$f_term_id 		= $_REQUEST['f_term_id'];
$filter_field 	= $_REQUEST['filter_field'];
$filter_order 	= $_REQUEST['filter_order'];
$page			= $_REQUEST['page'];

	if($_SESSION[CORE_U_CODE]['current_comp'] != $comp || $_SESSION[CORE_U_CODE]['pageNum'] != $page || $_SESSION[CORE_U_CODE]['fieldName'] != $filter_field || $_SESSION[CORE_U_CODE]['orderBy'] != $filter_order || $_SESSION[CORE_U_CODE]['sy_filter'] != $f_term_id)
	{
		if($page != '')
		{
			$_SESSION[CORE_U_CODE]['pageNum'] = isset($page)&&$page!='' ? $page : '1';
		}
		if($filter_field != '' || $filter_order != '')
		{
			$_SESSION[CORE_U_CODE]['fieldName'] = $filter_field;
			$_SESSION[CORE_U_CODE]['orderBy'] = $filter_order;
		}
		if($f_term_id != '')
		{
		$_SESSION[CORE_U_CODE]['sy_filter'] = $f_term_id;
		}
			$_SESSION[CORE_U_CODE]['current_comp'] = $comp;
	}

if($action == 'save')
{
	if($name == '' || $term_id == '' || $surcharge == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfPaySchemeNameExist($name,$term_id))
	{
		$err_msg = 'Scheme name already exist.';
	}
	else if(checkIfPaymentValueIs100($payment_value, $payment_type))
	{
		$err_msg = 'Total percentage exceeds 100%';
	}
	else if(checkIfPaymentValueNot100($payment_value, $payment_type))
	{
		$err_msg = 'Total percentage must total 100%';
	}
	else if(checkIfPaymentNameIsExist($payment_name))
	{
		$err_msg = 'Scheme caption already exist.';
	}
	else if(checkIfPaymentDateIsExist($payment_date))
	{
		$err_msg = 'Scheme date already exist.';
	}
	else
	{
		$sql = "INSERT INTO tbl_payment_scheme 
				( 
					name,
					surcharge,
					term_id,
					publish,
					date_created, 
					created_by,
					date_modified,
					modified_by
				) 
				VALUES 
				(
					".GetSQLValueString($name,"text").", 
					".GetSQLValueString($surcharge,"text").",
					".GetSQLValueString($term_id,"text").", 
					".GetSQLValueString($publish,"text").",  	
					".time().",
					".USER_ID.", 
					".time().",
					".USER_ID."
				)";
				
		if(mysql_query ($sql))
		{
			$scheme_id = mysql_insert_id();
			$ctr = 0 ;
			foreach($payment_name as $payment)
			{
				$sql = "INSERT INTO tbl_payment_scheme_details 
						( 
							scheme_id,
							payment_name,
							payment_type,
							payment_value, 
							sort_order,
							payment_date
						) 
						VALUES 
						(
							".GetSQLValueString($scheme_id,"text").", 
							".GetSQLValueString($payment_name[$ctr],"text").", 
							".GetSQLValueString($payment_type[$ctr],"text").", 
							".GetSQLValueString($payment_value[$ctr],"text").", 
							".GetSQLValueString($sort_order[$ctr],"text").", 
							".GetSQLValueString($payment_date[$ctr],"text")."
						)";
						
				mysql_query ($sql);	
				$ctr ++;
			}
			
			echo '<script language="javascript">alert("Successfully Added!");window.location =\'index.php?comp=com_payment_scheme\';</script>';
		}
	}	
}
else if($action == 'update')
{		
	if(checkSchemeEditable($id,$term_id))
	{			
		$err_msg ='Cannot Edit Payment Scheme . Currently there are Payment Associated.';
	}
	else if($name == '' || $term_id == '' || $surcharge == '')
	{
		$err_msg = 'Some of the required fields are missing.';
	}
	else if(checkIfPaySchemeNameExist($name,$term_id, $id))
	{
		$err_msg = 'Scheme name already exist.';
	}
	else if(checkIfPaymentValueIs100($payment_value, $payment_type))
	{
		$err_msg = 'Total percentage may not exceed 100%';
	}
	else if(checkIfPaymentValueNot100($payment_value, $payment_type))
	{
		$err_msg = 'Total percentage must total 100%';
	}
	else if(checkIfPaymentNameIsExist($payment_name))
	{
		$err_msg = 'Scheme caption already exist.';
	}
	else if(checkIfPaymentDateIsExist($payment_date))
	{
		$err_msg = 'Scheme date already exist.';
	}
	else
	{
		if(storedModifiedLogs(tbl_payment_scheme, $id))
		{
			$sql = "UPDATE tbl_payment_scheme SET 
					name  =".GetSQLValueString($name,"text").",
					surcharge  =".GetSQLValueString($surcharge,"int").",
					term_id  =".GetSQLValueString($term_id,"text").",				
					publish =".GetSQLValueString($publish,"text").",				 
					date_modified = ".time() .",
					modified_by = ".USER_ID." 
					WHERE id = " .$id;
				
			if(mysql_query ($sql))
			{
				$sql = "DELETE FROM tbl_payment_scheme_details WHERE scheme_id = " . $id;
				$query = mysql_query($sql);
				
				$ctr = 0 ;
				foreach($payment_name as $payment)
				{
					$sql = "INSERT INTO tbl_payment_scheme_details 
							( 
								scheme_id,
								payment_name,
								payment_type,
								payment_value, 
								sort_order,
								payment_date
							) 
							VALUES 
							(
								".GetSQLValueString($id,"text").", 
								".GetSQLValueString($payment_name[$ctr],"text").", 
								".GetSQLValueString($payment_type[$ctr],"text").", 
								".GetSQLValueString($payment_value[$ctr],"text").",
								".GetSQLValueString($sort_order[$ctr],"text").", 
								".GetSQLValueString($payment_date[$ctr],"text")."
							)";
							
					mysql_query ($sql);	
					$ctr ++;
				}			
				echo '<script language="javascript">alert("Successfully Updated!");window.location =\'index.php?comp=com_payment_scheme\';</script>';
			}
		}
	}
}
else if($action == 'delete')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		$sql_sched= "SELECT * FROM tbl_student_payment pay, tbl_payment_scheme scheme 	
						WHERE pay.payment_scheme_id=scheme.id AND scheme.id = " .$item;
		$qry_sched = mysql_query($sql_sched);
		$ctr = @mysql_num_rows($qry_sched);
		
		if($ctr > 0 )
		{			
			$err_msg ='Cannot Delete Payment Scheme . Currently there are Payment Associated.';
		}
		else
		{
			$sql_payment_scheme = "DELETE FROM tbl_payment_scheme WHERE id=" .$item;
			mysql_query ($sql_payment_scheme);
			$sql_payment_details = "DELETE FROM tbl_payment_scheme_details WHERE scheme_id=" .$item;
			mysql_query ($sql_payment_details);
		}
	}

	if(count($arr_str) > 0)
	{
		echo '<script language="javascript">alert("'.implode("\n",$arr_str).'");</script>';
	}

}
else if($action == 'publish')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{
		storedModifiedLogs(tbl_discount, $id);
		$sql = "UPDATE tbl_payment_scheme SET publish = 'Y', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
	}
}
else if($action == 'unpublished')
{
	$selected_item = explode(',',$temp);
	
	foreach($selected_item as $item)
	{		
		if(!checkSchemeEditable($id))
		{			
			$err_msg ='Cannot Unpublish Payment Scheme . Currently there are Payment Associated.';
		}
		else
		{
		storedModifiedLogs(tbl_payment_scheme, $id);
		$sql = "UPDATE tbl_payment_scheme SET publish = 'N', date_modified = ".time() ." WHERE id=" .$item;
		mysql_query ($sql);
		}
	}
}



if($view == 'edit')
{
	$arr_str = array();
	
	$sql = "SELECT * FROM tbl_payment_scheme WHERE id = " . $id;
	$query = mysql_query ($sql);
	$row = mysql_fetch_array($query);

	$name		= $row['name'] != $name ? $row['name'] : $name;
	$term_id	= $row['term_id'] != $term_id ? $row['term_id'] : $term_id;
	$publish	= $row['publish'] != $publish ? $row['publish'] : $publish;
	$surcharge	= $row['surcharge'] != $surcharge ? $row['surcharge'] : $surcharge;
	

	$sql = "SELECT * FROM tbl_payment_scheme_details WHERE scheme_id = " . $id;
	$query = mysql_query ($sql);
	$sched_row_ctr = mysql_num_rows($query);
	$ctr = 1;
	while($row = mysql_fetch_array($query))
	{
		$str_payment_type = $row['payment_type']=='P'?'Percentage':'Amount';
		$arr_str[] ='<tr id="row_'.$ctr.'">';
		  $arr_str[] ='<td><input name="payment_name[]" type="hidden" id="payment_name" value="'.$row['payment_name'].'" />' .$row['payment_name']. '</td>';
		  $arr_str[] ='<td><input name="payment_type[]" type="hidden" id="payment_type" value="'.$row['payment_type'].'" />' .$str_payment_type. '</td>';
		  $arr_str[] ='<td><input name="payment_value[]" type="hidden" id="payment_value" value="'.$row['payment_value'].'" />' .$row['payment_value']. '</td>';
		  $arr_str[] ='<td><input name="sort_order[]" type="text" id="sort_order" value="' .$row['sort_order']. '" /></td>';
		  $arr_str[] ='<td><input name="payment_date[]" type="hidden" id="payment_date" value="'.$row['payment_date'].'" />' .$row['payment_date']. '</td>';
		  $arr_str[] ='<td class="action"><a href="#" class="remove" returnId="'.$ctr.'">Remove</a></td>';             
		$arr_str[] ='</tr>';
		if($row['payment_type']!='P'&&$row['sort_order']==1)
		{
		$percent = $row['payment_value'];
		}
		$ctr ++;
	}
	
	$payment_sched = implode('',$arr_str);
	$sort_cnt = $ctr-1;

}
else if($view == 'add')
{
	
	$name				= $_REQUEST['name'];
	$term_id			= $_REQUEST['term_id'];
	$value				= $_REQUEST['value'];
	$publish			= $_REQUEST['publish'];
	$e_month			= $_REQUEST['e_month'];
	$e_day				= $_REQUEST['e_day'];
	$e_year				= $_REQUEST['e_year'];
	$surcharge			= $_REQUEST['surcharge'];	
	$payment_name		= $_REQUEST['payment_name'];
	$payment_type		= $_REQUEST['payment_type'];
	$payment_value		= $_REQUEST['payment_value'];
	$payment_date		= $_REQUEST['payment_date'];
	$sort_order			= $_REQUEST['sort_order'];
	$ctr = 0;
	
	if(count($payment_name)>0)
	{
	foreach($payment_name as $payment)
	{
		$str_payment_type = $payment_type[$ctr]=='P'?'Percentage':'Amount';
		$arr_str[] ='<tr id="row_'.$ctr.'">';
		  $arr_str[] ='<td><input name="payment_name[]" type="hidden" id="payment_name" value="'.$row['payment_name'].'" />' .$payment_name[$ctr]. '</td>';
		  $arr_str[] ='<td><input name="payment_type[]" type="hidden" id="payment_type" value="'.$row['payment_type'].'" />' .$str_payment_type. '</td>';
		  $arr_str[] ='<td><input name="payment_value[]" type="hidden" id="payment_value" value="'.$row['payment_value'].'" />' .$payment_value[$ctr]. '</td>';
		  $arr_str[] ='<td><input name="sort_order[]" type="text" id="sort_order" value="' .$sort_order[$ctr]. '" /></td>';
		  $arr_str[] ='<td><input name="payment_date[]" type="hidden" id="payment_date" value="'.$row['payment_date'].'" />' .$payment_date[$ctr]. '</td>';
		  $arr_str[] ='<td class="action"><a href="#" class="remove" returnId="'.$ctr.'">Remove</a></td>';             
		$arr_str[] ='</tr>';
		if($payment_type[$ctr]!='P'&&$sort_order[$ctr]==1)
		{
		$percent = $payment_value[$ctr];
		}
		$ctr ++;
	}
		$payment_sched = implode('',$arr_str);
	}

}

// component block, will be included in the template page
$content_template = 'components/block/blk_com_payment_scheme.php';
?>