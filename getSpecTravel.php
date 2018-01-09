<?php
    session_start();
    if(empty($_SESSION['user_id']) || $_SESSION['type']!=2)
    {
        header('Location: admin.php');
    }

    $trip_date=$_GET["date"];$departure=$_GET["start"];$arrival=$_GET["end"];

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if (!$conn) {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //heading
    echo '<div class="container-fluid" style="margin-top: 100px">
            <h1 style="font-family: \'Comic Sans MS\'">'.$departure.'-'.$arrival.'</h1>
          </div>';

    //show all departure-arrival
    if(empty($trip_date))
    {
        $sql="SELECT T.TRIP_ID,TR.TRAIN_NAME ,T.TRIP_DATE,T.TRIP_TIME
              FROM TRIP T
              JOIN TRAIN TR 
              ON T.TRAIN_ID=TR.TRAIN_ID
              WHERE T.STARTING='$departure' AND T.DESTINATION='$arrival' AND TR.CARGO=0 AND T.TRIP_DATE>=SYSDATE
              ORDER BY TRIP_DATE";
    }

    else
    {
        $sql="SELECT T.TRIP_ID,TR.TRAIN_NAME,T.TRIP_DATE,T.TRIP_TIME
              FROM TRIP T
              JOIN TRAIN TR 
              ON T.TRAIN_ID=TR.TRAIN_ID
              WHERE T.STARTING='$departure' AND T.DESTINATION='$arrival' AND TO_DATE('$trip_date','YYYY-MM-DD')=T.TRIP_DATE 
              AND TR.CARGO=0 AND TO_DATE('$trip_date','YYYY-MM-DD')>=SYSDATE";
    }

    $result=oci_parse($conn,$sql);
    oci_execute($result);

    echo "<table class=\"table table-hover table-dark\">
            <thead>
            <tr>
                <th scope=\"col\">Trip Id</th>
                <th scope=\"col\">Train Name</th>
                <th scope='\"col\"'>Trip Date</th>
                <th scope='\"col\"'>Trip Time</th>
            </tr>
            </thead>
            <tbody>";

    while($row=oci_fetch_assoc($result))
    {
        $link='buyTicket.php?tripId='.$row['TRIP_ID'];
        echo '<tr><td><a href='.$link.'>'.$row['TRIP_ID'].'</a></td><td>'.$row['TRAIN_NAME'].
            '</td><td>'.$row['TRIP_DATE'].'</td><td>'.$row['TRIP_TIME'].'</td></tr>';
    }

    echo "</tbody>
        </table>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>getSpecTravel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script type="text/javascript" src="js/showDate.js"></script>
    <link href="css/bookNow.css" rel="stylesheet"/>
</head>
<body>

<div class="container-fluid">
    <nav class="navbar fixed-top navbar-light">
        <img src="images/trainLogo.png" style="margin-left: 10px">
        <p style="color: white;font-size: 17px;font-family: 'Comic Sans MS';margin-right: 10px;margin-top: 5px">
            <?PHP echo $_SESSION['uname'];?> </p>
        <a href="base.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white">home</a>
        <a href="destruction.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white">log out</a>
        <p id="tt" style="color: white;font-size: 17px;font-family: 'Comic Sans MS';margin-right: 10px;margin-top: 5px">
            date</p>
        <script type="text/javascript">
            dateShower()
        </script>
    </nav>
</div>

</body>
</html>