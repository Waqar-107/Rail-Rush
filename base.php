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
    <a href="login.php"><h3
                style="color: black;font-family: 'Comic Sans MS';float: right;margin-top: 15px;margin-right: 15px">
            login/signup</h3></a>
</div>

<nav class="navbar sticky-top navbar-expand-md navbar-default justify-content-center">
    <div>
        <ul class="navbar-nav">

            <li>
                <a class="nav-link" href="#">Trains</a>
            </li>

            <li>
                <a class="nav-link" href="#">Schedule and Fares</a>
            </li>

            <li>
                <a class="nav-link" href="#">Admin</a>
            </li>

            <li>
                <a class="nav-link" href="#">Contact Us</a>
            </li>

            <li>
                <a class="nav-link" href="#"><img src="images/ticket.png" style="margin-right: 5px">Book Now</a>
            </li>

        </ul>
    </div>
</nav>


<!--CARDVIEW OPTIONS TO NAVIGATE-->
<div class="container" style="margin-top: 50px;margin-bottom: 50px">
    <div class="row" style="margin-top: 100px;margin-bottom: 100px">
        <div class="col-md-4">
            <div class="card">

                <div class="card-body">
                    <img src="images/train.png" class="rounded mx-auto d-block" style="margin-top: 15px">
                    <p class="fi">Trains</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">

                <div class="card-body">
                    <img src="images/schedule.png" class="rounded mx-auto d-block" style="margin-top: 15px">
                    <p class="fi">Schedule and Fares</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">

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

<div class="container-fluid" style="height: 70px;background-color:#2f3436">

    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4"><img src="images/buet.png" style="float: right;margin-top: 7px">
        </div>
        <div class="col-md-4"><p class="fi" style="float:left">developed by<br>buet</p></div>
    </div>
</div>

</body>
</html>