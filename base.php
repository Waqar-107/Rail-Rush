<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/base.css" rel="stylesheet">
</head>


<body>

<script src="js/jquery.min.js"></script>
<script src="bootstrap/dist/js/bootstrap.min.js"></script>

<div id="myContainer" class="container-fluid" style="color: whitesmoke;height: 400px">
    <?php

    if (isset($_SESSION['user_id']))
    { ?>

        <a href="destruction.php"><h3
                    style="color: black;font-family: 'Comic Sans MS';float: right;margin-top: 15px;margin-right: 15px">
                logout</h3></a>

    <?php
    }

    else
    {
        ?>

        <a href="login.php"><h3
                    style="color: black;font-family: 'Comic Sans MS';float: right;margin-top: 15px;margin-right: 15px">
                login/signup</h3></a>

        <?php
    }
    ?>

</div>

<nav class="navbar sticky-top navbar-expand-md navbar-default justify-content-center">
    <div>
        <ul class="navbar-nav">

            <li>
                <a class="nav-link" href="trainForGuest.php">Train and Fares</a>
            </li>

            <li>
                <a class="nav-link" href="scheduleForGuest.php">Schedule</a>
            </li>

            <li>
                <a class="nav-link" href="admin.php">Admin</a>
            </li>

            <li>
                <a class="nav-link" href="company_login.php">Company login</a>
            </li>

            <li>
                <a class="nav-link" href="bookNow.php"><img src="images/ticket.png" style="margin-right: 5px">Book Now</a>
            </li>

        </ul>
    </div>
</nav>


<!--CARDVIEW OPTIONS TO NAVIGATE-->
<div class="container" style="margin-top: 50px;margin-bottom: 50px">
    <div class="row" style="margin-top: 100px;margin-bottom: 100px">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body" onclick="window.location.href='tender.php'">
                    <img src="images/tender.png" class="rounded mx-auto d-block" style="margin-top: 15px">
                    <p class="fi">Tenders</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" onclick="window.location.href='myBookings.php'">

                <div class="card-body">
                    <img src="images/portfolio.png" class="rounded mx-auto d-block" style="margin-top: 15px">
                    <p class="fi">My Bookings</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" onclick='window.location.href="file_complaint.php"'>

                <div class="card-body">
                    <img src="images/mail.png" class="rounded mx-auto d-block" style="margin-top: 15px">
                    <p class="fi">Contact Us</p>
                </div>
            </div>
        </div>

    </div>
</div>
<!--CARDVIEW OPTIONS TO NAVIGATE-->


<!--LOCATION OF THE HEADQUARTER-->
<p style="margin-top: 150px;color: black;font-family: 'Comic Sans MS';font-size: 15px">Our Headquarters: ECE Building,
    BUET, West Palashi, Dhaka-1100 </p>
<div id="map">

</div>
<script>
    function initMap() {
        var location = {lat: 23.7266054, lng: 90.3875269};
        var map = new google.maps.Map(document.getElementById("map"), {
            zoom: 18,
            center: location,
            styles: [
                {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
                {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
                {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
                {
                    featureType: 'administrative.locality',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'poi',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'geometry',
                    stylers: [{color: '#263c3f'}]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#6b9a76'}]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry',
                    stylers: [{color: '#38414e'}]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry.stroke',
                    stylers: [{color: '#212a37'}]
                },
                {
                    featureType: 'road',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#9ca5b3'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry',
                    stylers: [{color: '#746855'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry.stroke',
                    stylers: [{color: '#1f2835'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#f3d19c'}]
                },
                {
                    featureType: 'transit',
                    elementType: 'geometry',
                    stylers: [{color: '#2f3948'}]
                },
                {
                    featureType: 'transit.station',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'water',
                    elementType: 'geometry',
                    stylers: [{color: '#17263c'}]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#515c6d'}]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.stroke',
                    stylers: [{color: '#17263c'}]
                }
            ]

        });

        var marker = new google.maps.Marker({
            position: location,
            map: map
        });
    }
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBXzplfd0VddM_-1yMFOFu7hfKvqazSt_c&callback=initMap"
        type="text/javascript">

</script>
<!--LOCATION OF THE HEADQUARTER-->

<!--footer-->
<footer class="fixed-bottom" style="position: fixed;height: 35px;bottom: 0;width: 100%;background-color: #2f3436">
    <p style="color: white;padding-top: 5px;padding-left: 10px">&copy CSE, BUET<p>
</footer>
<!--footer-->

</body>
</html>