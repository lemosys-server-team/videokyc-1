<?php
	return [
		//User Role Ids
		'ROLE_TYPE_SUPERADMIN_ID' => 1,
		'ROLE_TYPE_SALES_ID' => 2,	
		'ROLE_TYPE_USER_ID' => 3,	


		//Schedules Statsus Constant
		'COMPLETED' => 'completed',
		'PENDING' => 'pending',	
		'FAILED' => 'failed',	
		
		//Directories Path
		'USERS_UPLOADS_PATH' => '/uploads/users/',
		'SETTING_IMAGE_URL' => '/uploads/setting/',
		'SCHEDULE_UPLOAD_PATH_SALES' => '/uploads/schedules/sales/',
		'SCHEDULE_UPLOAD_PATH_USER' => '/uploads/schedules/users/',

		

		// Defaults
		'NO_IMAGE_URL' =>'/images/no_image.png',

		// Default Datetiem format
		'DATE_FORMAT' => 'd M Y',
		'SITE_DATE_FORMAT' => 'm/d/Y',
		'DATETIME_FORMAT' => 'd M Y, h:i A',
		'TIME_FORMAT' => 'h:i A',
		
	    // Default Datetiem format
		
		'MYSQL_STORE_DATE_FORMAT' => 'Y-m-d',
		'MYSQL_STORE_TIME_FORMAT' => 'h:i:s',
		'MYSQL_STORE_24TIME_FORMAT' => 'H:i:s',
		'MYSQL_STORE_DATETIME_FORMAT' => 'Y-m-d h:i:s',
		'MYSQL_DATE_FORMAT' => '%d %b %Y',
		'MYSQL_DATETIME_FORMAT' => '%d %b %Y, %h:%i %p',

		// Form error class		
		'ERROR_FORM_GROUP_CLASS' => 'has-error border-left-danger',

		//twilio Keys
		'TWILIO_ACCOUNT_SID' => 'AC9c4946e7297ef20525589bab03294be4',
		'TWILIO_API_TOKEN' => 'caf9cc9501dedd9da849c9f60a62843e', 
		'TWILIO_API_KEY_SID' => 'SK0450f5649c3fbf181c37eae780576937',
		'TWILIO_API_KEY_SECRET' => 'j6WJ6pw0GhBmAbafpM7vDb4Akt7jYgTR',

		//buzzify SMS
		'SMS_SENDER_ID'=>'INSPCN',
		'SMS_API_KEY'=>'lB62uhTi7qPXXX6N',
		'SMS_API_URL'=>'http://buzzify.in/V2/http-api.php?',

	];
?>