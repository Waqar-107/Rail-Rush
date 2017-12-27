<?php
    session_start();
    if(empty($_SESSION['user_id']))
    {
        header('Location: admin.php');
    }

    //if not passenger, send to base
    if($_SESSION['type']!=2)
    {
        header('Location: base.php');
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
        $sql="";
    }

    else
    {
        $sql="SELECT  TO_CHAR(T.TRIP_DATE,'DD-MM-YYYY'),TR.TRAIN_NAME 
              FROM TRIP T
              JOIN TRAIN TR
              ON T.TRAIN_ID=TR.TRAIN_ID
              WHERE T.STARTING='$departure' AND T.DESTINATION='$arrival'";
        $result=oci_parse($conn,$sql);
        $row=oci_execute($result);


    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>getSpecTravel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script type="text/javascript" src="js/showDate.js"></script>
    <link href="css/getSpecTravel.css" rel="stylesheet"/>
</head>
<body>

<div class="container-fluid">
    <nav class="navbar fixed-top navbar-light">
        <img src="images/trainLogo.png" style="margin-left: 10px">
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