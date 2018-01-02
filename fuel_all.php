<?php

    session_start();
    if(empty($_SESSION['user_in']))
    {
        header('location: base.php');
    }

    //if not admin, send to base
    if($_SESSION['type']==2)
    {
        header('Location: base.php');
    }

    //---------------------------------------------------------------add new entry
    echo '<div class="container-fluid" style="margin-top: 100px">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="fuelCreate.php" style="color: black">
                                    <img src="images/add.png"/> Add to FUEL</a>
                                </div>
                             
                            </div>
                          </div>';
    //---------------------------------------------------------------add new entry


//---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database


    //---------------------------------------------------------------get the whole table of complain
    $sql = "SELECT TO_CHAR(REFUELING_DATE,'DD/MM/YYYY') \"RE_DATE\",TRAIN_ID,EMPLOYEE_ID,QUANTITY,FCOST
            FROM FUEL
            ORDER BY REFUELING_DATE";
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
            $date=$row['RE_DATE'];

            $sendToTrain="fuel_detailed.php?type=2&type2=2&data=".$train_id;
            $sendToDate="fuel_detailed.php?type=2&type2=1&data=".$date;
            echo "<tr><td><a href=$sendToDate>".$date."</a></td><td><a href=$sendToTrain>".$train_id."</a></td><td>".$refueledBy."</td><td>".$quantity."</td><td>".$cost."</td></tr>";
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
            <a href="admin_base.php"
               style="font-size: 17px;font-family: 'Comic Sans MS';color: white">Home</a>
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