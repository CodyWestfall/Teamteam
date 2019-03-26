<?php
	require_once('connectDB.php');
	session_start();

	$stmt = $conn->prepare('SELECT username FROM ACCOUNTS WHERE username = ?');
	$stmt->bind_param('s', $account_name);

	if (isset($_SESSION['current_account'])) {

		$account_name = $_SESSION['current_account'];
	} else {
		header('Location: ./login.php');
		exit;
	}

	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($username);
	
	$stmt->fetch();
	
	$stmt->free_result();
	$stmt->close();

	if (!isset($username)) {
		header('Location: ./login.php');
		exit;
	}
?>
