<?php
require_once('./session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (isset($_POST['nodeSerial']) and isset($_POST['leftCoord']) and isset($_POST['topCoord'])) {
		$stmt = $conn->prepare('UPDATE NODES SET pos_x = ?, pos_y = ? WHERE serial = ?');
		$stmt->bind_param('iis', $left, $top, $nodeSerial);

		$left = filter_input(INPUT_POST, 'leftCoord', FILTER_SANITIZE_STRING);
		$top = filter_input(INPUT_POST, 'topCoord', FILTER_SANITIZE_STRING);
		$nodeSerial = filter_input(INPUT_POST, 'nodeSerial', FILTER_SANITIZE_STRING);

		$stmt->execute();
		$stmt->close();
	}
	return;
}

header('Location: ./index.php');
return;