<?php

    session_start();

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //---------------------------------------------------------------add new entry
    echo '<div class="container-fluid" style="margin-top: 100px">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="tripAddition.php">
                                    <img src="images/add.png"/>Add to Trip</a>
                                </div>
                             
                            </div>
                          </div>';
    //---------------------------------------------------------------add new entry

    //---------------------------------------------------------------get the whole table of complain
    $sql = "SELECT * FROM TRIP";
    $result = oci_parse($conn,$sql);


    if (oci_execute($result)) {

        echo "<table class=\"table table-hover table-dark\">
            <thead>
            <tr>
                <th scope=\"col\">Trip Id</th>
                <th scope=\"col\">Train Id</th>
                <th scope=\"col\">Trip Date</th>
                <th scope=\"col\">Departure</th>
                <th scope=\"col\">Arrival</th>
            </tr>
            </thead>
            <tbody>";

        while ($row = oci_fetch_assoc($result))
        {
            echo '<tr><td>'.$row['TRIP_ID'].'</td><td>'.$row['TRAIN_ID'].'</td><td>'.$row['TRIP_DATE'].
            '</td><td>'.$row['STARTING'].'</td><td>'.$row['DESTINATION'].'</td></tr>';
        }

        echo '</thead></table>';
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>trip list</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/complaint_list.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
</head>
<body>

<div class="container">
    <!--NAVBAR-->
    <div class="row" style="margin-bottom: 10%">
        <nav class="navbar fixed-top navbar-light">
            <img src="images/trainLogo.png" style="margin-left: 10px">
            <a href="admin_base.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white">Home</a>
            <a href="destruction.php"
               style="font-size: 17px;font-family: 'Comic Sans MS';color: white">log out</a>
            <p id="tt"
               style="color: white;font-size: 17px;font-family: 'Comic Sans MS';margin-right: 10px;margin-top: 5px">
                date</p>
            <script type="text/javascript">
                dateShower()
            </script>
        </nav>
    </div>
    <!--NAVBAR-->
</div>

</body>
</html>