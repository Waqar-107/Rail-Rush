<?php
    session_start();
    if(empty($_SESSION['user_in']))
    {
        header('location: base.php');
    }

    //---------------------------------------------------------------add new entry
    echo '<div class="container-fluid" style="margin-top: 100px">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="fare_create.php">
                            <img src="images/add.png"/> add new fare</a>
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

    //---------------------------------------------------------------get the whole table of fare
    $sql='SELECT F.FARE_ID,T.TRAIN_NAME,F.STARTING,F.FINISHING ,F.STYPE,F.PRICE
          FROM FARE F
          JOIN  TRAIN T
          ON T.TRAIN_ID=F.TRAIN_ID
          ORDER BY T.TRAIN_NAME';
    $result=oci_parse($conn,$sql);

    if(oci_execute($result))
    {
        echo "<table class=\"table table-hover table-dark\" style='margin-top: 50px'>
                <thead>
                <tr>
                    <th scope=\"col\">Fare Id</th>
                    <th scope=\"col\">Train Name</th>
                    <th scope=\"col\">Starting</th>
                    <th scope=\"col\">Destination</th>
                    <th scope=\"col\">type</th>
                    <th scope=\"col\">price</th>
                </tr>
                </thead>
                <tbody>";

        while ($row = oci_fetch_assoc($result))
        {
            if($row['STYPE']==1)
                $stype="first class";

            else if($row['STYPE']==2)
                $stype="second class";

            else if($row['STYPE']==3)
                $stype="third class";

            else
                $stype="cargo";

            $linktoupdate="fare_update.php?data=" . $row['FARE_ID'];

            echo "<tr><td>".$row['FARE_ID']."</td><td>".$row['TRAIN_NAME']."</td><td>".$row['STARTING'].
                "</td><td>".$row['FINISHING']."</td><td>".$stype."</td><td><a href=$linktoupdate>".$row['PRICE']."</a></td></tr>";
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
    <title>trains</title>
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
            <a href="destruction.php"
               style="font-size: 17px;font-family: 'Comic Sans MS';color: white;margin-left: 100px">log out</a>
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
