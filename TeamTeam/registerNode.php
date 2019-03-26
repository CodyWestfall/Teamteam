<?php
require_once('./session.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	if (isset($_POST['newNodeSerial']) and isset($_POST['RoomId'])) {
		$stmt = $conn->prepare('SELECT serial FROM NODES WHERE serial = ?');
		$stmt->bind_param('s', $newNodeSerial);
		
		$newNodeSerial = filter_input(INPUT_POST, 'newNodeSerial', FILTER_SANITIZE_STRING);
		
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($serial);
		
		$inNodesTable = $stmt->fetch();
		
		$stmt->free_result();
		$stmt->close();
		
		if ($inNodesTable) {
			$stmt = $conn->prepare('UPDATE NODES SET roomid = ? WHERE serial = ?');
			$stmt->bind_param('is', $roomId, $newNodeSerial);
			
			$roomId = filter_input(INPUT_POST, 'RoomId', FILTER_SANITIZE_STRING);
			
			$stmt->execute();
			$stmt->close();
		} else {
			$stmt = $conn->prepare('INSERT INTO NODES (serial, roomid) VALUES (?, ?)');
			$stmt->bind_param('si', $newNodeSerial, $roomId);
			
			$roomId = filter_input(INPUT_POST, 'RoomId', FILTER_SANITIZE_STRING);
			error_log("INSERT INTO NODES (serial, roomid) VALUES ($newNodeSerial, $roomId)");

			$stmt->execute();
			$stmt->close();
		}
	}
	return;
}

header('Location: ./index.php');
return;