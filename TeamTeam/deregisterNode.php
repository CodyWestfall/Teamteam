<?php
require_once('./session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['selectedSerial'])) {
		$stmt = $conn->prepare('DELETE FROM NODES WHERE serial = ?');
		$stmt->bind_param('s', $selectedSerial);
		
		$selectedSerial = filter_input(INPUT_POST, 'selectedSerial', FILTER_SANITIZE_STRING);
		
		$stmt->execute();
		$stmt->close();
	}
	return;
}

header('Location: ./index.php');
return;