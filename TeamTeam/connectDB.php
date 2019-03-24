<?php

	$dbhost = "localhost";
	$username = "ttadmin";
	$password = "t3amt3amst3amPassword*";
	$dbname = "roomtemp";

	$conn = mysqli_connect($dbhost, $username, $password, $dbname);

	if (!$conn) {
		die("Connection failed: " . mysqli_error());
	}
?>
