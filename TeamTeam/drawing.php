<?php
 // check if user is logged in
require_once('./session.php');

// if there is no room selected, redirect to the page where you select a room
if (!isset($_SESSION["room_selected"])) {
	header('Location: ./LiveHeatMap.php');
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
    <title>Drawing</title>
    <script src="./Scripts/jquery-3.3.1.min.js" type="text/javascript"></script>
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

        .modal {
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

        .modal-content {
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
    </style>
</head>

<body>
    <div id="container">
        <div id="canvasWrap" ondragover="allowDrop(event)" ondrop="drop(event)">
            <canvas id="drawArea" width="1000" height="500"></canvas>
            <?php
			for ($i = 0; $i < sizeOf($nodesInRoom); $i++) {
				echo "<img id='node$i' class='node' style='position: absolute; cursor: move; left: $xPositions[$i]px; top: $yPositions[$i]px;' src='./images/node.png' alt='$nodesInRoom[$i]' width='50' height='50' draggable='true' ondragstart='drag(event)' />";
			}
			?>
        </div>
        <div id="buttons">
            <button id="Freestyle">Freestyle</button>
            <button id="Rectangle">Rectangle</button>
            <button id="Erase">Eraser</button>
            <button id="Undo">Undo</button>
            <button id="Redo">Redo</button>
            <button id="Trigger">Trigger</button>
            <button id="Save">Save</button>
            <button id="addNode">Add Node</button>
        </div>
    </div>

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

            // add action listeners to the buttons
            $('#Freestyle').on('click', useFreeDraw);
            $('#Rectangle').on('click', useRectDraw);
            $('#Trigger').on('click', loadImage);
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

            // alert the user that they have unsaved changes when they try to leave the page
            $(window).on('beforeunload', function() {
                if (!saved) {
                    return "You have unsaved changes on this page. Do you want to leave this page and discard your changes?";
                }
            });
        });

        // prompt the user to enter a serial number, then draw the node onto the canvas
        function createNode() {
            var modal, modalContent, message, inputBox, buttonContainer, cancelButton, addButton;
            modal = $('<div id="modal" class="modal"></div>');

            modalContent = $('<div class="modal-content"></div>');
            modal.append(modalContent);

            message = $('<div class="message"></div>');
            message.text('Enter a serial number:');

            inputBox = $('<input id="customInputBox" type="text" placeholder="Serial number..." />');
            message.append(inputBox);

            buttonContainer = $('<div class="buttonContainer"></div>');
            modalContent.append(message, buttonContainer);

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
                            alt: $('#customInputBox').val()
                        });
                        img.css({
                            position: 'absolute',
                            left: '1px',
                            top: '1px',
                            cursor: 'move'
                        });
                        $('#canvasWrap').append(img);
                        $('#modal').remove();
                    });
                }
            });

            $('body').append(modal);
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
                saved = false;
            };

            img.src = './images/Blueprint.jpg';
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