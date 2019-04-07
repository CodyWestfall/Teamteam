<?php
require_once('./session.php');

$stmt = $conn->prepare('SELECT roomid, name FROM ROOMS WHERE username = ?');
$stmt->bind_param('s', $userName);
	
$userName = $account_name;

$stmt->execute();
$stmt->store_result();
$stmt->bind_result($roomid, $name);

$listOfRoomIds = array();
$listOfRoomNames = array();
while($stmt->fetch()) {
	array_push($listOfRoomIds, $roomid);
	array_push($listOfRoomNames, $name);
}

$stmt->free_result();
$stmt->close();

$currMonth = date('n', time());
$currDay = date('j', time());
$currYear = date('Y', time());
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/png" href="images/favicon.png" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

    <title>Report Generator</title>
</head>


<body style="background-color:dimgrey;">

	<!-- NavBar -->
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <a class="navbar-brand" href="index.php">Team Team</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="LiveHeatMap.php">Heat Map</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="LiveHumidityMap.php">Humidity Map</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="Reports.php">Report</a>
                </li>
				<li class="nav-item">
                    <a class="nav-link" target="_blank" href="SetupInstructions.pdf">Setup Instructions</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
	
	<div style="text-align: center; padding-top: 25px;">
		<div style="display: inline-block">
			<form method="post" id="dateform" target="_blank" onsubmit="showReport.html">
				<select class="btn btn-default" name="RoomId" form="dateform" required>
					<?php for($i = 0; $i < sizeOf($listOfRoomIds); $i++) {
						echo "<option value=\"".$listOfRoomIds[$i]."\">".$listOfRoomNames[$i]."</option>";
					}?>
				</select>
				<div style='display: grid; padding-top: 50px; grid-template-columns: 1fr 0.5fr 1fr; grid-gap: 100px'>
					<div>
						<fieldset>
							<legend>FROM</legend>
							<div style="display: grid; grid-template-columns: 1fr 1fr; grid-gap: 30px; margin-top: 15px;">
								Month:
								<select style="width: 80px;" required>
									<?php
										for($i = 1; $i <= 12; $i++){
											echo "<option>$i</option>";
										}
									?>
								</select>
								Day:
								<select style="width: 80px;" required>
									<?php
										for($i = 1; $i <= 31; $i++){
											echo "<option>$i</option>";
										}
									?>
								</select>
								Year:
								<select style="width: 80px;" required>
									<?php
										for($i = $currYear; $i >= 2019; $i--){
											echo "<option>$i</option>";
										}
									?>
								</select>
							</div>
						</fieldset>
					</div>
					
					<img src="images/arrow.png" alt="->" style="width: 100px; margin-top: 80px;">
								
					<div>
						<fieldset>
							<legend>TO</legend>
							<div style="display: grid; grid-template-columns: 1fr 1fr; grid-gap: 30px; margin-top: 15px;">
								Month:
								<select style="width: 80px;" required>
									<?php
										for($i = $currMonth; $i > 0; $i--){
											echo "<option>$i</option>";
										}
									?>
								</select>
								Day:
								<select style="width: 80px;" required>
									<?php
										for($i = $currDay; $i > 0; $i--){
											echo "<option>$i</option>";
										}
									?>
								</select>
								Year:
								<select style="width: 80px;" required>
									<?php
										for($i = $currYear; $i >= 2019; $i--){
											echo "<option>$i</option>";
										}
									?>
								</select>
							</div>
						</fieldset>
					</div>
				</div>
				<button type="button" style="margin-top: 50px;" onclick="checkDate()">GENERATE</button>
				<br>
				<button type="submit" style = "visibility: hidden">SUBMIT</button>
			</form>
		</div>
	</div>
	
    <!-- Scripts -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="Scripts/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="Scripts/knockout-3.4.2.js" type="text/javascript"></script>
    <script src="Scripts/popper.min.js" type="text/javascript"></script>
    <script src="Scripts/bootstrap.min.js" type="text/javascript"></script>
    <script src="Scripts/heatmap.min.js" type="text/javascript"></script>
	
	<script>
	function checkDate() {
		
	}
	</script>
	
</body>
</html>
