<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if (empty($_SESSION['user_in']) || $_SESSION['type'] != 2)
    {
        header('location: login.php');
    }

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if (!$conn) {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //get the starting or arrivals from the database
    $sql = "SELECT DEPARTURE FROM TRAIN
            UNION
            (
                SELECT ARRIVAL FROM TRAIN
            )";
    $result = oci_parse($conn, $sql);
    $arr = array();

    //SAVE THEM IN ARRAY
    if (oci_execute($result))
    {
        while ($row = oci_fetch_assoc($result))
        {
            array_push($arr, $row['DEPARTURE']);
        }
    }


    if (isset($_POST['submit']))
    {

        $trip_date = $_POST['mdate'];
        $starting = $_POST['mstart'];
        $dest = $_POST['mfinish'];

        if($dest==$starting)
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("same departure and arrival place selected","we provide a graph without self loop ;) ","error");';
            echo '}, 50);</script>';
        }

        else
        {
            $link = 'getSpecTravel.php?date=' . $trip_date . '&start=' . $arr[$starting-1] . '&end=' . $arr[$dest-1];
            header("Location: $link");
        }
    }

    oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>bookNow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/bookNow.css" rel="stylesheet">
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
    <script src="bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
</head>
<body>

<div class="container">
    <!--NAVBAR-->
    <div class="row" style="margin-bottom: 10%">
        <nav class="navbar fixed-top navbar-light">
            <img src="images/trainLogo.png" style="margin-left: 10px">
            <p style="color: white;font-size: 17px;font-family: 'Comic Sans MS';margin-right: 10px;margin-top: 5px">
                <?PHP echo $_SESSION['uname'];?> </p>
            <a href="base.php"
               style="font-size: 17px;font-family: 'Comic Sans MS';color: white;margin-left: 100px">Home</a>
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


<div class="container-fluid">
    <form method="post">
        <div class="row" id="headings">

            <div class="col-md-4">Date</div>
            <div class="col-md-4">Departure</div>
            <div class="col-md-4">Arrival</div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <input type="date" class="form-control" id="mdate" name="mdate">
            </div>
            <div class="col-md-4 mb-3">
                <select class="form-control" name="mstart" id="mstart" style="height: 100%">
                    <?php
                        for($i=0;$i<count($arr);$i++)
                        {
                            echo '<option value='.($i+1).'>'.$arr[$i].'</option>';
                        }
                    ?>
                </select>
            </div>

            <div class="col-md-4 mb-3">
                <select class="form-control" name="mfinish" id="mfinish" style="height: 100%">
                    <?php
                    for($i=0;$i<count($arr);$i++)
                    {
                        echo '<option value='.($i+1).'>'.$arr[$i].'</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5"></div>
            <div class="col-md-2">
                <button class="btn btn-success btn-lg btn-block" type="submit" id="submit" name="submit">Search</button>
            </div>
        </div>
    </form>
</div>

</body>
</html>