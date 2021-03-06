﻿<?php
	require ("./session.php");
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/png" href="./images/RTLogo.png" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">



    <title>Team Team</title>
    <style>
        #carouselExampleIndicators {
            position: absolute;
            left: calc(50% - 225px);
            top: 0;
            width: 450px;
            height: 300px;
            overflow: hidden;
            transform: translate(0px, 100px);
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
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="LiveHeatMap.php">Heat Map</a>
                </li>
				<li class="nav-item">
                    <a class="nav-link" href="LiveHumidityMap.php">Humidity Map</a>
                </li>
                <li class="nav-item">
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


    <!-- Carousal with images to make the page pretty
         This is supposed to scroll through these three images, but it doesn't oh well-->

    <div class="container">
        <div class="row">
            <div id="carouselExampleIndicators" class="carousel slide carousel-center" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item">
                        <img class="d-block w-100" src="images/map1.png" width="450" height="300" alt="Third slide">
                    </div>
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="images/map2.png" width="450" height="300" alt="Second slide">
                    </div>
                    <div class="carousel-item ">
                        <img class="d-block w-100" src="images/map3.png" width="450" height="300" alt="First slide">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content of page -->
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />


    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <h3 class="text-center">Welcome to Room Temp!</h3>
                <h3 class="text-center">Hand crafted with love by: </h3>
                <h3 class="text-center">Matt Fair, Jacob Morrison, David Nikonowicz, Caleb Phillips, Erin Rourke, Cody Westfall</h3>

                <br>

                <!--<div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                        <a role="button" href="LiveHeatMap.php" class="btn btn-primary btn-lg center-block">Heat Map</a>
                    </div>
                </div>

                <br />

                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                        <a role="button" href="Recommendations.html" class="btn btn-primary btn-lg center-block">Recommendations</a>
                    </div>
                </div>

                <br />

                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 text-center">
                        <a role="button" href="#" class="btn btn-primary btn-lg center-block">Live Graphs</a>
                    </div>
                </div>-->

            </div>
        </div>
    </div>





    <!-- Scripts -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="Scripts/jquery-3.3.1.min.js" type="text/javascript"></script>
    <script src="Scripts/bootstrap.min.js" type="text/javascript"></script>
    <script src="Scripts/knockout-3.4.2.js" type="text/javascript"></script>
    <script src="Scripts/popper.min.js" type="text/javascript"></script>
    <script src="Scripts/heatmap.min.js" type="text/javascript"></script>
    <script language="JavaScript" type="text/javascript">
        $(document).ready(function () {
            $('.carousel').carousel({
                interval: 3000
            })
        });
    </script>
</body>
</html>
