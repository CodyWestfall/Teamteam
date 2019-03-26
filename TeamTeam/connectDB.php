<?php

	$dbhost = "localhost";
	$username = "ttadmin";
	$password = "t3amt3amst3amPassword*";
	$dbname = "roomtemp";

	$conn = new mysqli($dbhost, $username, $password, $dbname);

	if ($conn->connect_errno) {
		die("Connection failed: " . $conn->connect_error);
	}
?>
