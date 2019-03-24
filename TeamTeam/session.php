<?php
	require("connectDB.php");

	if(session_status() == PHP_SESSION_NONE) {
		session_start();
	}

	if(!isset($_SESSION["currentUser"])) {
		header("Location: /cloud/login.php");
		return;
	}

	$user_check = $_SESSION["currentUser"];

	$result = mysqli_query($conn, "SELECT Username FROM Users WHERE Username = '" . $user_check . "'");

	$row = mysqli_fetch_array($result);

	$login_session = $row["Username"];

	if($_SERVER['PHP_SELF'] != "/cloud/files.php" and $_SERVER['PHP_SELF'] != "/cloud/trash.php") {
		header("Location: /cloud/files.php");
	}
?>
