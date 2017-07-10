<?php

include_once("config.php");

include_once("includes/functions.php");

include_once("includes/common.php");


$sql = "INSERT INTO tbl_session_logs_backup 

					(	

						session_id,

						user_id,                          

						ip_connected,

						date_logged                                                            
						
					) 

					SELECT 

						session_id,

						user_id,                          

						ip_connected,

						date_logged
						
						FROM tbl_session_logs";

		if(mysql_query($sql))
		{
			echo 'COPIED SESSION LOGS<br>';
			
			$sql_del = "DELETE FROM tbl_session_logs";
			mysql_query($sql_del);	
			
			echo 'DELETED SESSION LOGS<br>';
		}
		
		
		$sql = "INSERT INTO tbl_system_logs_backup 

					(	

						user_id,
						
						for_user_id,

						message,                          

						for_admin,

						for_self,
						
						for_prof,
						
						for_student,
						
						date_created                                                            
						
					) 

					SELECT 

						user_id,
						
						for_user_id,

						message,                          

						for_admin,

						for_self,
						
						for_prof,
						
						for_student,
						
						date_created
						
						FROM tbl_system_logs";

		if(mysql_query($sql))
		{
			echo 'COPIED SYSTEM LOGS<br>';
			
			$sql_del = "DELETE FROM tbl_system_logs";
			mysql_query($sql_del);	
			
			echo 'DELETED SYSTEM LOGS<br>';
		}

?>