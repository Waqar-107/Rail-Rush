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

    $sql="SELECT T.TRAIN_NAME,T.DEPARTURE,T.ARRIVAL, 
          (SELECT PRICE FROM FARE WHERE STYPE=1 AND TRAIN_ID=T.TRAIN_ID ) \"fc\",
          (SELECT PRICE FROM FARE WHERE STYPE=2 AND TRAIN_ID=T.TRAIN_ID ) \"sc\",
          (SELECT PRICE FROM FARE WHERE STYPE=1 AND TRAIN_ID=T.TRAIN_ID ) \"tc\"
          FROM TRAIN T
          WHERE T.CARGO=0";
    $result=oci_parse($conn,$sql);oci_execute($result);

    echo "<table class=\"table table-hover table-dark\">
            <thead>
            <tr>
                <th scope=\"col\">Train</th>
                <th scope=\"col\">Departure</th>
                <th scope=\"col\">Arrival</th>
                <th scope=\"col\">First Class</th>
                <th scope=\"col\">Second Class</th>
                <th scope=\"col\">Third Class</th>
            </tr>
            </thead>
            <tbody>";

    while ($row=oci_fetch_assoc($result))
    {
        echo '<tr><td>'.$row['TRAIN_NAME'].'</td><td>'.$row['DEPARTURE'].'</td><td>'.$row['ARRIVAL'].
            '</td><td>'.$row['fc'].'</td><td>'.$row['sc'].'</td><td>'.$row['tc'].'</td></tr>';
    }

    echo "</tbody>
            </table>";

    oci_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>train info for guest</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/fuel.css" rel="stylesheet"/>
    <script src="js/showDate.js" type="text/javascript"></script>
</head>
<body>

<div class="container">
    <!--NAVBAR-->
    <div class="row" style="margin-bottom: 10%">
        <nav class="navbar fixed-top navbar-light">
            <img src="images/trainLogo.png" style="margin-left: 10px">
            <a href="base.php"
               style="font-size: 17px;padding-left: 100px;font-family: 'Comic Sans MS';color: white">Home</a>
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