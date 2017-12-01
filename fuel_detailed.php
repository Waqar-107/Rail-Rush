<?php

    session_start();
    if(empty($_SESSION['user_id']))
    {
        header('location: base.php');
    }

    //---------------------------------------------------------------get the complain id from previous page
    if (isset($_GET["type"]))
    {
        $type= $_GET["type"];
        $type2=$_GET["type2"];
        $data=$_GET["data"];
    }
    //---------------------------------------------------------------get the complain id from previous page


    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database


    //---------------------------------------------------------------type 2
    if($type==2)
    {
        //group by date
        if ($type2 == 1)
        {
            $sql = "SELECT TRAIN_ID,EMPLOYEE_ID,QUANTITY,FCOST 
                    FROM FUEL
                    WHERE REFUELING_DATE=TO_DATE('$data','DD/MM/YYYY')";
            $result = oci_parse($conn, $sql);


            if (oci_execute($result)) {

                echo "<table class=\"table table-hover table-dark\">
                    <thead>
                    <tr>
                        <th scope=\"col\">Train Id</th>
                        <th scope=\"col\">Refueled By</th>
                        <th scope=\"col\">Quantity(L)</th>
                        <th scope=\"col\">Cost(/tk)</th>
                    </tr>
                    </thead>
                    <tbody>";

                while ($row = oci_fetch_assoc($result)) {
                    $train_id = $row['TRAIN_ID'];
                    $refueledBy = $row['EMPLOYEE_ID'];
                    $quantity = $row['QUANTITY'];
                    $cost = $row['FCOST'];
                    echo '<tr><td>' . $train_id . '</a></td><td>' . $refueledBy . '</td><td>' . $quantity . '</td><td>' . $cost . '</td></tr>';
                }

                echo "</tbody>
                 </table>";
            }
        }
        else
        {
            $sql = "SELECT TO_CHAR(REFUELING_DATE,'DD/MM/YYYY')\"REFUELING_DATE\" ,EMPLOYEE_ID,QUANTITY,FCOST 
                    FROM FUEL
                    WHERE TRAIN_ID='$data'";
            $result = oci_parse($conn, $sql);


            if (oci_execute($result)) {

                echo "<table class=\"table table-hover table-dark\">
                    <thead>
                    <tr>
                        <th scope=\"col\">Date</th>
                        <th scope=\"col\">Refueled By</th>
                        <th scope=\"col\">Quantity(L)</th>
                        <th scope=\"col\">Cost(/tk)</th>
                    </tr>
                    </thead>
                    <tbody>";

                while ($row = oci_fetch_assoc($result)) {
                    $date = $row['REFUELING_DATE'];
                    $refueledBy = $row['EMPLOYEE_ID'];
                    $quantity = $row['QUANTITY'];
                    $cost = $row['FCOST'];
                    echo '<tr><td>' . $date . '</a></td><td>' . $refueledBy . '</td><td>' . $quantity . '</td><td>' . $cost . '</td></tr>';
                }

                echo "</tbody>
                 </table>";
            }
        }

    }
    //---------------------------------------------------------------type 2

    //---------------------------------------------------------------type 1
    else
    {

    }
    //---------------------------------------------------------------type 2

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
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