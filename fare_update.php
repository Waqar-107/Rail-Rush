<?php
    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if(empty($_SESSION['user_id']))
    {
        header('location: base.php');
    }

    //if not admin, send to base
    if($_SESSION['type']==2)
    {
        header('Location: base.php');
    }

    //---------------------------------------------------------------get the fare id from previous page
    $fare_id;
    if (isset($_GET["data"])) {
        $fare_id = $_GET["data"];
    }
    //---------------------------------------------------------------get the fare id from previous page

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    $sql="SELECT  T.TRAIN_NAME,F.STARTING,F.FINISHING,F.PRICE
          FROM FARE F
          JOIN TRAIN T
          ON F.TRAIN_ID=T.TRAIN_ID
          WHERE FARE_ID='$fare_id'";

    $result=oci_parse($conn,$sql);
    oci_execute($result);

    $row=oci_fetch_assoc($result);
    $place=$row['STARTING'].'-'.$row['FINISHING'];
    $cprice=$row['PRICE'];$trname=$row['TRAIN_NAME'];

    if(isset($_POST['submit']))
    {
        if(empty($_POST['newFare']))
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("fill the update field","","error");';
            echo '}, 50);</script>';
        }

        else
        {
            $newFare=$_POST['newFare'];
            $sql="UPDATE  FARE
                  SET PRICE='$newFare'
                  WHERE FARE_ID='$fare_id'";

            $result=oci_parse($conn,$sql);
            if(oci_execute($result))
            {
                echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "successfully updated",
                                    text: "",
                                    type: "success"
                                }, function() {
                                    window.location = "fare.php";
                                });
                            }, 50);
                            </script>';
            }

            else
            {
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("sorry something went wrong","probably database connection problem :(","error");';
                echo '}, 50);</script>';
            }
        }
    }


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>updateFare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/reply.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
</head>

<body>

<div id="main_panel" class="container-fluid">

    <div class="container" style="margin-top: auto">

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

        <!--FARE ID-->
        <div class="row">
            <div class="col-md-2">
                <p class="rpEx" id="fare_id">Fare id: </p>
                <script type="text/javascript">var id = "<?= $fare_id ?>";
                    document.getElementById("fare_id").innerHTML = "Fare id: " + id;
                </script>
            </div>
        </div>
        <!--FARE ID-->

        <!--Train name-->
        <div class="row">
            <div class="col-md-12">
                <p class="rpEx" id="trname">Train Name: </p>
                <script type="text/javascript">var id = "<?= $trname ?>";
                    document.getElementById("trname").innerHTML = "Train Name: " + id;
                </script>
            </div>
        </div>
        <!--Train name-->

        <!--start-fin-->
        <div class="row">
            <div class="col-md-12">
                <p class="rpEx" id="sf">Route: </p>
                <script type="text/javascript">var id = "<?= $place ?>";
                    document.getElementById("sf").innerHTML = "Route: " + id;
                </script>
            </div>
        </div>
        <!--start-fin-->

        <!--curr fare-->
        <div class="row">
            <div class="col-md-12">
                <p class="rpEx" id="cf">Current Fare: </p>
                <script type="text/javascript">var id = "<?= $cprice ?>";
                    document.getElementById("cf").innerHTML = "Current Fare: " + id;
                </script>
            </div>
        </div>
        <!--curr fare-->

        <div class="row">
            <div class="col-md-12">
                <form action="" method="post" style="word-wrap: break-word">
                    <!--NEW FARE-->
                    <div class="form-group">
                        <input type="text" id="newFare" name="newFare" class="form-control" placeholder="new fare"></input>
                    </div>
                    <!--NEW FARE-->

                    <!--SEND BUTTON-->
                    <div class="form-group" style="margin-top: 50px">
                        <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                               value="update" style="margin-bottom: 75px">
                    </div>
                    <!--SEND BUTTON-->
                </form>
            </div>
        </div>

    </div>

</div>

</body>
</html>
