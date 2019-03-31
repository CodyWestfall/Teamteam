<?php
	require_once('session.php');
	error_log('here');
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		error_log(print_r($_FILES, true));
		if (isset($_FILES['image'])) {
			error_log('there');
			$tempName = $_FILES['image']['tmp_name'];
			$fullName = filter_var($_FILES['image']['name'], FILTER_SANITIZE_STRING);
			$size = $_FILES['image']['size'];

			$path_parts = pathinfo($fullName);
			$extension = strtolower($path_parts['extension']);
			
			error_log('okay');
			$destinationPath = $_SESSION["room_selected"] . "." . $extension;
			move_uploaded_file($tempName, "/var/www/html/images/$destinationPath");
		}
	}
	header('Location: ./drawing.php');
?>
