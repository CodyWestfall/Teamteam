<?php

	$dbhost = "localhost:33306";
	$username = "pi";
	$password = "chickenlover";
	$dbname = "piCloud";

	$conn = mysqli_connect($dbhost, $username, $password, $dbname);

	if (!$conn) {
		die("Connection failed: " . mysqli_error());
	}
?>
