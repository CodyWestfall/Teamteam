<?php

	require("connectDB.php");
	session_start();
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$query = "SELECT * FROM ACCOUNTS WHERE username = '" . $_POST["uname"] . "' AND password = SHA('" . mysqli_real_escape_string($conn, $_POST["pword"]) . "')";
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_assoc($result);
			$_SESSION["currentUser"] = $row['username'];

			header("Location: ./index.php");
		} else {
			$error = "Your Username or Password is Invalid";
			echo mysqli_error($conn);
		}
	}

?>

<!DOCTYPE html>

<html>

<head>

	<title>Room Temo Login</title>

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="shortcut icon" type="image/png" href="./images/RTLogo.png"/>

<style>

	input[type=text],
	input[type=password] {
		width: 100%;
		padding: 12px 20px;
		margin: 8px 0;
		display: inline-block;
		border: 1px solid #ccc;
		box-sizing: border-box;
	}
	
	button {
		background-color: #4CAF50;
		color: white;
		padding: 14px 20px;
		margin: 8px 0;
		border: none;
		cursor: pointer;
		width: 100%;
	}
	
	button:hover {
		opacity: 0.8;
	}
	
	.logo {
		display: block;
		margin: 3em auto 0 auto;
		max-width: 40%;
	}

	.container {
		padding: 0 1em;
	}

	.error {
		font: italic 1em Verdana;
		color: #ff0000;
		text-align: center;
		padding-top: 1em;
	}

</style>

</head>

<body style="background-color:dimgrey;">
	
	<form method="post" name="login">
		<img src="./images/RTLogo.png" alt="piCloud Logo" class="logo">
		<?php
			if($_SERVER["REQUEST_METHOD"] == "POST") {
				echo '<div class="error" style="visibility: visible;">' . $error . '</div>';
			} else {
				echo '<div class="error" style="visibility: hidden;">' . $error . '</div>';
			}
		?>

		<div class="container">
			<label for="uname">
				<b>Username</b>
			</label>
			<input type="text" placeholder="Enter Username" name="uname" autofocus required>
			
			<label for="pword">
				<b>Password</b>
			</label>
			<input type="password" placeholder="Enter Password" name="pword" required>
			
			<button type="submit" name ="submit" value="Login">Login</button>
		</div>
	</form>

<script>

</script>

</body>

</html>
