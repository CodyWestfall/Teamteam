<?php
	require_once("connectDB.php");
	session_start();

	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$stmt = $conn->prepare('SELECT username FROM ACCOUNTS WHERE username = ? AND password = SHA(?)');
		$stmt->bind_param('ss', $username, $password);
		
		$username = filter_input(INPUT_POST, 'uname', FILTER_SANITIZE_STRING);
		$password = filter_input(INPUT_POST, 'pword', FILTER_SANITIZE_STRING);

		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($username);

		if($stmt->fetch()) {
			$_SESSION['current_account'] = $username;

			header("Location: ./index.php");
		} else {
			$errorMessage = 'invalid credentials';
		}

		$stmt->free_result();
		$stmt->close();
	}
?>

<!DOCTYPE html>

<html>

<head>

	<title>Room Temp Login</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="shortcut icon" type="image/png" href="./images/RTLogo.png"/>

<style>
	body {
		background-color:dimgrey;
	}

	button {
	font-family: Verdana;
	font-size: 18px;
	background-color: white;
	border: 2px solid black;
	border-radius: 5px;
	cursor: pointer;
	text-align: center;
	transition: 0.25s;
}

button:hover {
	color: #818181;
}

#container {
	width: 536px;
	padding: 25px;
	border: 5px solid black;
	border-radius: 10px;
	background-color: azure;
	margin: 25px 0;
}

#form-container {
	display: flex;
	flex-direction: column;
	align-items: center;
}

label {
	width: 60%;
	font-family: Verdana;
	font-size: 18px;
	text-align: left;
	transition: 0.25s;
}

input[type='text'],
input[type='password'] {
	width: 60%;
	font-family: Verdana;
	font-size: 16px;
	text-align: left;
	background-color: #006400;
	border: 2px solid black;
	border-radius: 5px;
}

#logo {
	width: 200px;
}

@media only screen and (max-width: 740px) {
	#container {
		width: 75%;
	}
}

@media only screen and (max-width: 375px) {
	#logo {
		width: 75%;
	}
	
	label,
	button {
		font-size: 14px;
	}
}

@media only screen and (max-width: 300px) {
	label,
	button {
		font-size: 10px;
	}
}

@media only screen and (max-width: 225px) {
	label,
	button {
		font-size: 8px;
	}
}

</style>

</head>

<body>
	<center>
		<div id='container'>
			<img id='logo' src="./images/RTLogo.png">
			<form method="POST">
				<div id='form-container'>
					<div style='height: 24px; color: #c6f0d8; margin: auto;'>
						<?php if($_SERVER['REQUEST_METHOD'] == 'POST') { echo $errorMessage; } ?>
					</div>
					<label for="username">
						Username:
					</label>
					<input id='username' type="text" name="uname" autofocus required>
					<label for='password'>
						Password:
					</label>
					<input id='password' type="password" name="pword" required>
					<div style='width: 60%; display: flex; margin-top: 1em;'>
						<button style='width: auto;'>Login</button>
					</div>
				</div>
			</form>
		</div>
	</center>
</body>

</html>
