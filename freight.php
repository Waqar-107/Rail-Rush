<?php

    session_start();
    if(empty($_SESSION['user_in']))
    {
        header('location: base.php');
    }

    //---------------------------------------------------------------add new entry
    echo '<div class="container-fluid" style="margin-top: 100px">
            <div class="row">
                <div class="col-md-1">
                    <a href="addFreight.php">
                    <img src="images/add.png"/> add</a>
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

    //---------------------------------------------------------------get the whole table of freight
    $sql='SELECT * FROM FREIGHT';
    $result=oci_parse($conn,$sql);

    if(oci_execute($result))
    {
        echo "<table class=\"table table-hover table-dark\" style='margin-top: 50px'>
        <thead>
        <tr>
            <th scope=\"col\">Freight Id</th>
            <th scope=\"col\">Train Id</th>
            <th scope=\"col\">Trailer Id</th>
            <th scope=\"col\">Company</th>
            <th scope=\"col\">Weight</th>
            <th scope=\"col\">Inside</th>
            <th scope=\"col\">Trip Date</th>
        </tr>
        </thead>
        <tbody>";

        while ($row = oci_fetch_assoc($result))
        {

            $tripDate=$row['TRIP_DATE'];

            echo '<tr><td>'.$row['FREIGHT_ID'].'</td><td>'.$row['TRAIN_ID'].'</td><td>'.$row['TRAILER_NO'].
                '</td><td>'.$row['COMPANY_NAME'].'</td><td>'.$row['WEIGHT'].'</td><td>'.$row['INSIDE'].
                '</td><td>'.$row['TRIP_DATE'].'</td></tr>';
        }

        echo "</tbody>
    </table>";
    }
    //---------------------------------------------------------------get the whole table of freight

    oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>freight_list</title>
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