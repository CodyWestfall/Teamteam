<?php
	require("connectDB.php");

	if(session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	if(!isset($_SESSION["currentUser"])) {
		header("Location: ./login.php");
		return;
	}

	$user_check = $_SESSION["currentUser"];

	$result = mysqli_query($conn, "SELECT username FROM ACCOUNTS WHERE username = '" . $user_check . "'");

	$row = mysqli_fetch_array($result);

	$login_session = $row["username"];
?>
