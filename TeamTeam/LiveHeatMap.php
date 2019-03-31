<?php
 // check if user is logged in
require_once('./session.php');

// if there is no room selected, redirect to the page where you select a room
//if (!isset($_SESSION["room_selected"])) {
//	header('Location: ./LiveHeatMap.php');
//}
	//copy pasta from liveheatmap.php to check room selected
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

$stmt = $conn->prepare('SELECT serial, pos_x, pos_y FROM NODES WHERE roomid = ?');
$stmt->bind_param('i', $roomId);

$roomId = $_SESSION["room_selected"];

$stmt->execute();
$stmt->store_result();
$stmt->bind_result($serial, $pos_x, $pos_y);

$nodesInRoom = array();
$xPositions = array();
$yPositions = array();
while ($stmt->fetch()) {
	array_push($nodesInRoom, $serial);
	array_push($xPositions, $pos_x);
	array_push($yPositions, $pos_y);
}

$stmt->free_result();
$stmt->close();
?>

<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>LiveHeatMap</title>
    <script src="./Scripts/jquery-3.3.1.min.js" type="text/javascript"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/png" href="images/favicon.png" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">


	<style>
        *:focus {
            outline: none;
        }

        #container {
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            align-items: center;
        }

        #buttons {
            display: flex;
        }

        #buttons button {
            margin: 0 1em;
        }

        #canvasWrap {
            position: relative;
        }

        #drawArea {
            width: 1000px;
            height: 500px;
            border: 1px solid black;
            cursor: crosshair;
        }

        #boxDiv {
            position: absolute;
            border: 4px solid black;
        }

        .mdl {
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .mdl-content {
            display: grid;
            grid-template-columns: 1fr 4fr 1fr;
            grid-template-areas:
                ". message ."
                ". buttons .";
            background-color: #fefefe;
            font-size: 24px;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .message {
            grid-area: message;
            text-align: center;
            padding: 0.5em;
        }

        .buttonContainer {
            grid-area: buttons;
            display: flex;
            justify-content: space-evenly;
            font-size: 18px;
        }

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

	<!-- CONTENT OF PAGE -->
	<?php if ($isRoomSelected): ?>
	<div>
		<div id="container">
			<div id="canvasWrap" ondragover="allowDrop(event)" ondrop="drop(event)">
				<canvas id="drawArea" width="1000" height="500"></canvas>
				<?php
				for ($i = 0; $i < sizeOf($nodesInRoom); $i++) {
					echo "<img id='node$i' class='node' style='position: absolute; cursor: move; left: $xPositions[$i]px; top: $yPositions[$i]px;' src='./images/node.png' alt='$nodesInRoom[$i]' title='$nodesInRoom[$i]' width='50' height='50' draggable='true' ondragstart='drag(event)' />";
					echo "<p class='hover-text'  text='$nodesInRoom[$i]'></p>";
				}
				?>
			</div>

			
			<div id="buttons">
				<button class="btn btn-default" id="Freestyle">Freestyle</button>
				<button class="btn btn-default" id="Rectangle">Rectangle</button>
				<button class="btn btn-default" id="Undo">Undo</button>
				<button class="btn btn-default" id="Redo">Redo</button>
				<button class="btn btn-default" id="addNode">Add Node</button>
			</div>
			<form action="./upload.php" method="POST" enctype="multipart/form-data">
				<input class="btn btn-default" type="file" name="image" id="image"/>
				<input class="btn btn-default" type="submit" value="Upload" />
			</form>

			<br />

			<div id="heatmapdiv" class="heatmapdiv" style="width:1000px; height: 1000px; border: 2px solid black; overflow: hidden;"></div>
		</div>

		

		<br />
		<div style="z-index: 100;">

			<div class="text-center">
				<button class="btn btn-default" data-bind="click: playClicked, text: playText"></button>
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

		</div>
		<br />
	</div>
	<?php else: ?>
	<!-- Content of page -->
	<div class="container">
		<form class="form" method="POST" id="roomSelect">
			<select class="btn btn-default" name="RoomId" form="roomSelect">
				<?php for($i = 0; $i < sizeOf($listOfRoomIds); $i++) {
					echo "<option value=\"".$listOfRoomIds[$i]."\">".$listOfRoomNames[$i]."</option>";
				}?>
			</select>

			<input class="btn btn-primary" type="submit" value="Select Room" />
		</form>
	</div>
	<?php endif; ?>

	<!-- Scripts -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->

	<script src="Scripts/jquery-3.3.1.min.js" type="text/javascript"></script>
	<script src="Scripts/knockout-3.4.2.js" type="text/javascript"></script>
	<script src="Scripts/popper.min.js" type="text/javascript"></script>
	<script src="Scripts/heatmap.min.js" type="text/javascript"></script>
	<script src="ViewModels/LiveHeatMapViewModel.js"></script>

    <script>
		

        // initiate global variables
        var canvas, ctx, mouseX, mouseY, mouseDown = 0,
            firstX, firstY, lastX, lastY, size = 2,
            undoStack = [],
            redoStack = [],
            saved = true;

        $(document).ready(function() {
            canvas = document.getElementById('drawArea');
            ctx = canvas.getContext('2d');
            ctx.fillStyle = "#000000";
            ctx.lineWidth = 2 * size;

            useRectDraw();
            loadImage();

            // add action listeners to the buttons
            $('#Freestyle').on('click', useFreeDraw);
            $('#Rectangle').on('click', useRectDraw);
            $('#Save').on('click', function() {
                saved = true;
            });
            $('#addNode').on('click', createNode);
            $('#Undo').on('click', function() {
                if (undoStack.length > 0) {
                    redoStack.push(ctx.getImageData(0, 0, canvas.width, canvas.height));
                    var newImage = undoStack.pop();
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.putImageData(newImage, 0, 0);
                    saved = false;
                }
            });
            $('#Redo').on('click', function() {
                if (redoStack.length > 0) {
                    undoStack.push(ctx.getImageData(0, 0, canvas.width, canvas.height));
                    var newImage = redoStack.pop();
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    ctx.putImageData(newImage, 0, 0);
                    saved = false;
                }
            });
/*
            // alert the user that they have unsaved changes when they try to leave the page
            $(window).on('beforeunload', function() {
                if (!saved) {
                    return "You have unsaved changes on this page. Do you want to leave this page and discard your changes?";
                }
            });
*/
        });

        // prompt the user to enter a serial number, then draw the node onto the canvas
        function createNode() {
            var mdl, mdlContent, message, inputBox, buttonContainer, cancelButton, addButton;
            mdl = $('<div id="modal" class="mdl"></div>');

            mdlContent = $('<div class="mdl-content"></div>');
            mdl.append(mdlContent);

            message = $('<div class="message"></div>');
            message.text('Enter a serial number:');

            inputBox = $('<input id="customInputBox" type="text" placeholder="Serial number..." />');
            message.append(inputBox);

            buttonContainer = $('<div class="buttonContainer"></div>');
            mdlContent.append(message, buttonContainer);

            cancelButton = $('<button>Cancel</button>');

            addButton = $('<button>Add</button>');
            buttonContainer.append(cancelButton, addButton);

            cancelButton.on('click', function() {
                $('#modal').remove();
            });

            addButton.on('click', function() {
                if ($('#customInputBox').val() == "") {
                    $('#customInputBox').css('outline', '1px solid red');
                } else {
                    $('#customInputBox').css('outline', '');
                    url = "./registerNode.php";
                    data = {
                        newNodeSerial: $('#customInputBox').val(),
                        RoomId: <?php echo $roomId; ?>
                    };
                    $.post(url, data).done(function() {
                        var img = $('<img class="node" src="./images/node.png" width="50" height="50" draggable="true" ondragstart="drag(event)" />');
                        img.attr({
                            id: 'node' + $('.node').length,
                            alt: $('#customInputBox').val(),
							title: $('#customInputBox').val()
                        });
                        img.css({
                            position: 'absolute',
                            left: '1px',
                            top: '1px',
                            cursor: 'move'
                        });
						var hoverp = $('<p class="hover-text"></p>');
						hoverp.attr({text: $('#customInputBox').val()});
                        $('#canvasWrap').append(img);
						$('#canvasWrap').append(hoverp);
                        $('#modal').remove();
						LiveHeatMapViewModel.getNodes();
                    });
                }
            });

            $('body').prepend(mdl);
            $(window).on('click', function(evt) {
                if ($(evt.target).is('#customPassword')) {
                    $('#customPassword').remove();
                }
            });
        }

        // functions to handle when a node is being dragged
        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev) {
            getMousePos(ev);
            ev.dataTransfer.setData("id", ev.target.id);
            ev.dataTransfer.setData("x", mouseX);
            ev.dataTransfer.setData("y", mouseY);
            ev.dataTransfer.setDragImage(ev.target, mouseX, mouseY);
        }

        function drop(ev) {
            ev.preventDefault();
            var oldX = ev.dataTransfer.getData("x");
            var oldY = ev.dataTransfer.getData("y");
            getMousePos(ev);
            $('#' + ev.dataTransfer.getData('id')).css('left', mouseX - oldX);
            $('#' + ev.dataTransfer.getData('id')).css('top', mouseY - oldY);
            url = "./updateNodeLocation.php";
            data = {
                nodeSerial: $('#' + ev.dataTransfer.getData('id')).attr('alt'),
                leftCoord: $('#' + ev.dataTransfer.getData('id')).position().left,
                topCoord: $('#' + ev.dataTransfer.getData('id')).position().top
            };
            $.post(url, data);
        }

        // preliminary method:
        // for now, it applies the Blueprint.jpg image that is already in the filesystem onto the canvas and standardizes its size.
        // eventually, we will want to implement an image upload feature.
        function loadImage() {
            var img = new Image();

            img.onload = function() {
                // get new size to scale image to a max width of 1000px and a max height of 500px
                var newSize = getNewSize(img.width, img.height);
                $('#drawArea').css(newSize);
                $('#drawArea').attr(newSize);
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                ctx.lineWidth = 2 * size;
                undoStack = [];
                redoStack = [];
            };

            img.src = './images/' + <?php echo $_SESSION["room_selected"]; ?>;
        }

        // remove all action listeners that have to do with drawing (in order to switch between free draw and the rectangle tool).
        function clearDrawAreaEvents() {
            $('#drawArea').off('mousedown');
            $('#drawArea').off('mousemove');
            $(window).off('mouseup');
        }

        // lets the user draw freely
        function useFreeDraw() {
            clearDrawAreaEvents();
            $('#drawArea').on('mousedown', function() {
                undoStack.push(ctx.getImageData(0, 0, canvas.width, canvas.height));
                redoStack = [];
                mouseDown = 1;
                freeDraw();
            });
            $('#drawArea').on('mousemove', function(e) {
                getMousePos(e);
                if (mouseDown == 1) {
                    freeDraw();
                    saved = false;
                }
            });
            $(window).on('mouseup', function() {
                mouseDown = 0;
                lastX = 0;
                lastY = 0;
            });
        }

        function freeDraw() {
            if (lastX && lastY && (mouseX !== lastX || mouseY !== lastY)) {
                ctx.beginPath();
                ctx.moveTo(lastX, lastY);
                ctx.lineTo(mouseX, mouseY);
                ctx.stroke();
            }
            ctx.beginPath();
            ctx.arc(mouseX, mouseY, size, 0, Math.PI * 2, true);
            ctx.closePath();
            ctx.fill();
            lastX = mouseX;
            lastY = mouseY;
        }

        // lets the user draw with a rectangle tool
        function useRectDraw() {
            clearDrawAreaEvents();
            $('#drawArea').on('mousedown', rectDrawDown);
            $('#drawArea').on('mousemove', rectDrawMove);
            $(window).on('mouseup', rectDrawUp);
        }

        function rectDrawDown(e) {
            undoStack.push(ctx.getImageData(0, 0, canvas.width, canvas.height));
            redoStack = [];
            mouseDown = 1;
            getMousePos(e);
            firstX = mouseX;
            firstY = mouseY;
            var divBox = $('<div id="boxDiv"></div>');
            divBox.css('pointer-events', 'none');
            $('#canvasWrap').append(divBox);
            divBox.css({
                left: firstX + 'px',
                top: firstY + 'px'
            });
        }

        function rectDrawMove(e) {
            getMousePos(e);
            if (mouseDown == 1) {
                $('#boxDiv').css({
                    width: Math.abs(mouseX - firstX) + 'px',
                    height: Math.abs(mouseY - firstY) + 'px',
                    left: (mouseX - firstX < 0) ? mouseX + 'px' : firstX + 'px',
                    top: (mouseY - firstY < 0) ? mouseY + 'px' : firstY + 'px'
                });
            }
        }

        function rectDrawUp() {
            mouseDown = 0;
            var divBox = $('#boxDiv');
            if (divBox.length) {
                ctx.strokeRect(
                    divBox.position().left + 1,
                    divBox.position().top + 1,
                    divBox.width() + 4,
                    divBox.height() + 4);
                divBox.remove();
                saved = false;
            }
        }

        // return the mouse position relative to the hovered DOM element
        function getMousePos(e) {
            if (!e) {
                e = event;
            }
            if (e.offsetX) {
                mouseX = e.offsetX;
                mouseY = e.offsetY;
            } else if (e.layerX) {
                mouseX = e.layerX;
                mouseY = e.layerY;
            }
        }

        // calculate the dimensions of how to scale the image such that it has a max width of 1000px and a max height of 300px, but its aspect ratio remains unchanged.
        function getNewSize(w, h) {
            var MAX_IMG_WIDTH = 1000;
            var MAX_IMG_HEIGHT = 500;
            var oldWidth = w;
            var oldHeight = h;
            var ratio = oldWidth / oldHeight;

            var newWidth;
            var newHeight;
            if (ratio > 1.5) {
                newWidth = MAX_IMG_WIDTH;
                newHeight = newWidth / oldWidth * oldHeight;
            } else {
                newHeight = MAX_IMG_HEIGHT;
                newWidth = newHeight / oldHeight * oldWidth;
            }

            return {
                width: newWidth,
                height: newHeight
            };
        }
    </script>
</body>

</html> 