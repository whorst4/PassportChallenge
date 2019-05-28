<?php


	$cleardb_server   = 'us-cdbr-iron-east-02.cleardb.net';
	$cleardb_username = 'bdc7e143151be6';
	
	$cleardb_password = '9db98915';
	$cleardb_db       = 'heroku_2088e27632d87eb';
	
	$conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);

	if (!$conn) {
		die("Connection failed: " . mysqli_connect_error());
	}
	 
   
?>
