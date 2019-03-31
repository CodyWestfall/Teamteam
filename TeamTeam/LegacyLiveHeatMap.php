<?php
	require ('./session.php');
	
	$isRoomSelected = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$isRoomSelected = true;
		$stmt = $conn->prepare('SELECT serial FROM NODES WHERE roomid = ?');
		$stmt->bind_param('i', $selectedRoom);
		
		$_SESSION["room_selected"] = filter_input(INPUT_POST, 'RoomId', FILTER_SANITIZE_STRING);
		$selectedRoom = $_SESSION["room_selected"];

		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($serial);

		$listOfNodes = array();
		while($stmt->fetch()) {
			array_push($listOfNodes, $serial);
		}

		$stmt->free_result();
		$stmt->close();
	} else {
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
	}
		
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



    <title>Live Heat Maps</title>
    <style>
        #heatmapContainer {
            position: absolute;
            left: calc(50% - 512px);
            top: 0;
            width: 1024px;
            height: 720px;
            border: 5px solid black;
            overflow: hidden;
        }

        #heatmapContainer canvas {
            transform: translate(-5px, -5px);
        }

        .row {
            position: absolute;
            z-index: 2;
            color: white;
            font-weight: bold;
        }

        #node1 {
            left: 12px;
            top: 12px;
        }

        #node2 {
            left: 50%;
            top: 12px;
            transform: translateX(-50%);
        }

        #node3 {
            right: 12px;
            top: 12px;
        }

        #node4 {
            left: 12px;
            bottom: 12px;
        }

        #node5 {
            left: 50%;
            bottom: 12px;
            transform: translateX(-50%);
        }

        #node6 {
            right: 12px;
            bottom: 12px;
        }

        label {
            background: none;
            border: none;
            text-align: center;
            font-weight: bold;
        }
		.hover-text:hover ~ .node {
		  display: block;  
		}
    </style>
</head>

<?php if ($isRoomSelected): ?>
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
                <li class="nav-item active">
                    <a class="nav-link" href="LiveHeatMap.php">Heat Map</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Recommendations.html">Recommendations</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <br />

    <!-- Content of page -->
    <div id="heatmapContainer">
        <div class="form">
            <div class="row" id="node1">
                <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                    <label>Node 1</label><br />
                    <label disabled type="number" class="form center-block" data-bind="text: tempNode1, style: { color: ((tempNode1() > 85 && selectedUnit() == 'Fahrenheit') || (tempNode1() > 29 && selectedUnit() == 'Celsius')) ? 'red' : 'white' }" />
                    <span style="color: red;" data-bind="visible: ((tempNode1() > 85 && selectedUnit() == 'Fahrenheit') || (tempNode1() > 29 && selectedUnit() == 'Celsius'))">2 Spicy 5 me</span>
                </div>
            </div>

            <div class="row" id="node2">
                <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                    <label>Node 2</label><br />
                    <label disabled type="number" class="form center-block" data-bind="text: tempNode2, style: { color: ((tempNode2() > 85 && selectedUnit() == 'Fahrenheit') || (tempNode2() > 29 && selectedUnit() == 'Celsius')) ? 'red' : 'white' }" />
                </div>
            </div>

            <div class="row" id="node3">
                <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                    <label>Node 3</label><br />
                    <label disabled type="number" class="form center-block" data-bind="text: tempNode3, style: { color: ((tempNode3() > 85 && selectedUnit() == 'Fahrenheit') || (tempNode3() > 29 && selectedUnit() == 'Celsius')) ? 'red' : 'white' }" />
                </div>
            </div>

            <div class="row" id="node4">
                <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                    <label>Node 4</label><br />
                    <label disabled type="number" class="form center-block" data-bind="text: tempNode4, style: { color: ((tempNode4() > 85 && selectedUnit() == 'Fahrenheit') || (tempNode4() > 29 && selectedUnit() == 'Celsius')) ? 'red' : 'white' }" />
                </div>
            </div>

            <div class="row" id="node5">
                <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                    <label>Node 5</label><br />
                    <label disabled type="number" class="form center-block" data-bind="text: tempNode5, style: { color: ((tempNode5() > 85 && selectedUnit() == 'Fahrenheit') || (tempNode5() > 29 && selectedUnit() == 'Celsius')) ? 'red' : 'white' }" />
                </div>
            </div>

            <div class="row" id="node6">
                <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                    <label>Node 6</label><br />
                    <label disabled type="number" class="form center-block" data-bind="text: tempNode6, style: { color: ((tempNode6() > 85 && selectedUnit() == 'Fahrenheit') || (tempNode6() > 29 && selectedUnit() == 'Celsius')) ? 'red' : 'white' }" />
                </div>
            </div>
        </div>
        <!--HEATMAP CANVAS GOES HERE-->
    </div>

    <br />

    <div class="text-center">
        <button class="btn btn-primary" data-bind="click: playClicked, text: playText"></button>
        <label style="color:black">Past </label>
        <input class="form text-center" type="radio" name="interval" id="9" value="9" data-bind="checked: interval" />

        <input class="form text-center" type="radio" name="interval" id="8" value="8" data-bind="checked: interval" />

        <input class="form text-center" type="radio" name="interval" id="7" value="7" data-bind="checked: interval" />

        <input class="form text-center" type="radio" name="interval" id="6" value="6" data-bind="checked: interval" />

        <input class="form text-center" type="radio" name="interval" id="5" value="5" data-bind="checked: interval" />

        <input class="form text-center" type="radio" name="interval" id="4" value="4" data-bind="checked: interval" />

        <input class="form text-center" type="radio" name="interval" id="3" value="3" data-bind="checked: interval" />

        <input class="form text-center" type="radio" name="interval" id="2" value="2" data-bind="checked: interval" />

        <input class="form text-center" type="radio" name="interval" id="1" value="1" data-bind="checked: interval" />

        <input class="form text-center" type="radio" checked name="interval" id="0" value="0" data-bind="checked: interval" />
        <label style="color:black"> Present</label>
    </div>

    <div class="text-center">
        <label style="color:black">Displayed Time: </label>
        <label style="color:black" data-bind="text: displayedTime"></label>
    </div>

    <div class="text-center">
        <label style="color:black" for="Celsius">Celsius</label>
        <input class="form text-center" type="radio" name="unit" id="Celsius" value="Celsius" data-bind="checked: selectedUnit" />

        <label style="color:black" for="Fahrenheit">Fahrenheit</label>
        <input class="form text-center" type="radio" checked name="unit" id="Fahrenheit" value="Fahrenheit" data-bind="checked: selectedUnit" />
    </div>

    <div class="text-center">
        <label style="color:black" for="Celsius">Hour Interval</label>
        <input class="form text-center" type="number" id="time" data-bind="value: time" />
    </div>

    <br />
    <!-- Scripts -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="Scripts/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="Scripts/knockout-3.4.2.js" type="text/javascript"></script>
    <script src="Scripts/popper.min.js" type="text/javascript"></script>
    <script src="Scripts/heatmap.min.js" type="text/javascript"></script>
    <script src="ViewModels/LiveHeatMapViewModel.js"></script>
</body>

<?php else: ?>
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
                <li class="nav-item active">
                    <a class="nav-link" href="LiveHeatMap.php">Heat Map</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Recommendations.html">Recommendations</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <br />

    <!-- Content of page -->
	<div class="container">
		<form class="form" method="POST" id="roomSelect">
			<select class="btn btn-secondary" name="RoomId" form="roomSelect">
				<?php for($i = 0; $i < sizeOf($listOfRoomIds); $i++) {
					echo "<option value=\"".$listOfRoomIds[$i]."\">".$listOfRoomNames[$i]."</option>";
				}?>
			</select>

			<input class="btn btn-primary" type="submit" value="Select Room" />
		</form>
	</div>

	<!-- Scripts -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->

    <script src="Scripts/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="Scripts/knockout-3.4.2.js" type="text/javascript"></script>
    <script src="Scripts/popper.min.js" type="text/javascript"></script>
    <script src="Scripts/heatmap.min.js" type="text/javascript"></script>
    <script src="ViewModels/LiveHeatMapViewModel.js"></script>
</body>

<?php endif; ?>

</html>
