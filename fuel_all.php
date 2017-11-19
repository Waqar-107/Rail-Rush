<?php

    session_start();
    if(empty($_SESSION['user_in']))
    {
        header('location: base.php');
    }

    //---------------------------------------------------------------connect to the database
    $server = "localhost/orcl";
    $username = "HR";
    $password = "hr";

    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database


    //---------------------------------------------------------------get the whole table of complain
    $sql = "SELECT * FROM FUEL";
    $result = oci_parse($conn,$sql);


    if (oci_execute($result))
    {

        echo "<table class=\"table table-hover table-dark\">
            <thead>
            <tr>
                <th scope=\"col\">Date</th>
                <th scope=\"col\">Train Id</th>
                <th scope=\"col\">Refueled By</th>
                <th scope=\"col\">Quantity(L)</th>
                <th scope=\"col\">Cost(/tk)</th>
            </tr>
            </thead>
            <tbody>";

        while ($row = oci_fetch_assoc($result))
        {
            $train_id=$row['TRAIN_ID'];
            $refueledBy=$row['EMPLOYEE_ID'];
            $quantity=$row['QUANTITY'];
            $cost=$row['FCOST'];
            $date=$row['REFUELING_DATE'];

            echo '<tr><td><a href="#">'.$date.'</a></td><td><a href="#">'.$train_id.'</a></td><td>'.$refueledBy.'</td><td>'.$quantity.'</td><td>'.$cost.'</td></tr>';
        }

        echo "</tbody>
        </table>";
    }
    //---------------------------------------------------------------get the whole table of complain

    oci_close($conn);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>full fuel history</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/fuel.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
</head>
<body>

<div class="container">
    <!--NAVBAR-->
    <div class="row" style="margin-bottom: 10%">
        <nav class="navbar fixed-top navbar-light">
            <img src="images/trainLogo.png" style="margin-left: 10px">
            <a href="destruction.php"
               style="font-size: 17px;font-family: 'Comic Sans MS';color: white;margin-left: 150px">log out</a>
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