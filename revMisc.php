<?php

    session_start();

    if (empty($_SESSION['user_in']) || $_SESSION['type'] != 1)
    {
        header('location: base.php');
    }

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if (!$conn) {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    echo '<div class="col-md-12" align="center" style="font-family: \'Comic Sans MS\';font-size: 25px;margin-top: 100px">
            Top Routes</a></div>';

    $sql="SELECT B.TRAIN_ID,SUM(CNT),T.TRAIN_NAME,T.DEPARTURE, T.ARRIVAL
          FROM BOOKING_HISTORY B
          JOIN TRAIN T
          ON B.TRAIN_ID=T.TRAIN_ID
          GROUP BY B.TRAIN_ID,T.TRAIN_NAME,T.DEPARTURE, T.ARRIVAL
          ORDER BY SUM(CNT) DESC";
    $result=oci_parse($conn,$sql);oci_execute($result);

    echo "<table class=\"table table-hover table-dark\">
        <thead>
        <tr>
            <th scope=\"col\">Train Id</th>
            <th scope=\"col\">Train Name</th>
            <th scope=\"col\">Total Sold</th>
            <th scope=\"col\">Route</th>
        </tr>
        </thead>
        <tbody>";

    while($row=oci_fetch_assoc($result))
    {
        echo '<tr><td>'.$row['TRAIN_ID'].'</td><td>'.$row['TRAIN_NAME'].'</td><td>'.$row['SUM(CNT)'].
            '</td><td>'.$row['DEPARTURE'].'-'.$row['ARRIVAL'].'</td></tr>';
    }

    echo "</tbody>
        </table>";


    echo '<div class="col-md-12" align="center" style="font-family: \'Comic Sans MS\';font-size: 25px;margin-top: 100px">
            Top Passengers</a></div>';

    $sql="SELECT B.PASSENGER_ID,SUM(CNT) ,(P.FIRST_NAME || ' ' || P.LAST_NAME) \"NM\"
          FROM BOOKING_HISTORY B
          JOIN PASSENGER P 
          ON B.PASSENGER_ID=P.PASSENGER_ID
          GROUP BY B.PASSENGER_ID,(P.FIRST_NAME || ' ' || P.LAST_NAME) 
          ORDER BY SUM(CNT) DESC";

    $result=oci_parse($conn,$sql);oci_execute($result);

    echo "<table class=\"table table-hover table-dark\">
        <thead>
        <tr>
            <th scope=\"col\">Passenger Id</th>
            <th scope=\"col\">Name</th>
            <th scope=\"col\">Total Purchase</th>
        </tr>
        </thead>
        <tbody>";

    while($row=oci_fetch_assoc($result))
    {
        echo '<tr><td>'.$row['PASSENGER_ID'].'</td><td>'.$row['NM'].'</td><td>'.$row['SUM(CNT)'].
            '</td></tr>';
    }

    echo "</tbody>
        </table>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>miscellaneous stats</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="js/showDate.js" type="text/javascript"></script>
    <link href="css/tender.css" rel="stylesheet"/>
</head>
<body>

<div class="container-fluid">
    <nav class="navbar fixed-top navbar-light">
        <img src="images/trainLogo.png" style="margin-left: 10px">
        <a href="admin_base.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white">Home</a>
        <a href="destruction.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white";>log out</a>
        <p id="tt" style="color: white;font-size: 17px;font-family: 'Comic Sans MS';margin-right: 10px;margin-top: 5px">
            date</p>
        <script type="text/javascript">
            dateShower()
        </script>
    </nav>
</div>

</body>
</html>