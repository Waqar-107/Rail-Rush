<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if(empty($_SESSION['user_in']) || $_SESSION['type']!=1)
    {
        header('location: base.php');
    }

    $tid=$_GET['tid'];
    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    if(isset($_POST['submit']))
    {
        $hid=$_POST['hid'];
        if($hid<10)
            $hid='0'.$hid;

        $mid=$_POST['mid'];
        if($mid<10)
            $mid='0'.$mid;

        $tu=$hid.':'.$mid;

        $sql="UPDATE TRIP SET UPDATED='1',TRIP_TIME='$tu' WHERE TRIP_ID='$tid'";
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
                                     window.location = "trips.php";
                                       });
                             }, 50);
                          </script>';
        }

        else
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("sorry something went wrong!!!!","probably problem in the database :(","error");';
            echo '}, 50);</script>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>edit train</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/signup.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
</head>
<body>

<!--NAVBAR-->
<div class="row" style="margin-bottom: 10%">
    <nav class="navbar fixed-top navbar-light">
        <img src="images/trainLogo.png" style="margin-left: 10px">
        <a href="admin_base.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white">Home</a>
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

<div class="container">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Update Trip Time</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <select class="form-control" name="hid" id="hid" required>
                                <option value="-1">hour</option>
                                <?php
                                for($i=0;$i<24;$i++)
                                {
                                    echo '<option value='.($i).'>'.$i.'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="mid" id="mid" required>
                                <option value="-1">minute</option>
                                <?php
                                for($i=0;$i<60;$i++)
                                {
                                    echo '<option value='.($i).'>'.$i.'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-top: 50px">
                            <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                                   value="update">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>