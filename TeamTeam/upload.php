<?php
	require_once('session.php');
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_FILES['image'])) {
			$tempName = $_FILES['image']['tmp_name'];
			$fullName = filter_var($_FILES['image']['name'], FILTER_SANITIZE_STRING);
			$size = $_FILES['image']['size'];

			$destinationPath = $_SESSION["room_selected"];
			move_uploaded_file($tempName, "/var/www/html/images/$destinationPath");
		}
	}
	header('Location: ./LiveHeatMap.php');
?>
