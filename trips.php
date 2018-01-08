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

    //---------------------------------------------------------------add new entry
    echo '<div class="container-fluid" style="margin-top: 100px">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="tripAddition.php" style="color: black;font-size: 20px;font-family: \'Comic Sans MS\'">
                                    <img src="images/add.png"/>Add to Trip</a>
                                </div>
                                
                                <div class="col-md-3">
                                    <a href="#dell" style="color: black;font-size: 20px;font-family: \'Comic Sans MS\'">
                                    <img src="images/dustbin.png"/>Delete from Trip</a>
                                </div>
                             
                            </div>
                          </div>';
    //---------------------------------------------------------------add new entry

    //---------------------------------------------------------------get the whole table of complain
    $sql = "SELECT * FROM TRIP ORDER BY TRIP_ID";
    $result = oci_parse($conn,$sql);


    if (oci_execute($result))
    {

        echo "<table class=\"table table-hover table-dark\">
            <thead>
            <tr>
                <th scope=\"col\">Trip Id</th>
                <th scope=\"col\">Train Id</th>
                <th scope=\"col\">Trip Date</th>
                <th scope='\"col\"'>Time</th>
                <th scope=\"col\">Departure</th>
                <th scope=\"col\">Arrival</th>
            </tr>
            </thead>
            <tbody>";

        while ($row = oci_fetch_assoc($result))
        {
            echo '<tr><td>'.$row['TRIP_ID'].'</td><td>'.$row['TRAIN_ID'].'</td><td>'.$row['TRIP_DATE'].
            '</td><td>'.$row['TRIP_TIME'].'</td><td>'.$row['STARTING'].'</td><td>'.$row['DESTINATION'].'</td></tr>';
        }

        echo '</thead></table>';
    }

    if(isset($_POST['submit']))
    {
        $tid=$_POST['dn'];$trid=$_POST['tr'];

        $sql="DELETE FROM TRIP WHERE TRIP_ID='$tid'";
        $result=oci_parse($conn,$sql);

        $sql2="BEGIN
                DELETE_RETURN_TRIP(:TID,:TRID);
               END;";
        $result2=oci_parse($conn,$sql2);
        oci_bind_by_name($result2,":TID",$tid,32);
        oci_bind_by_name($result2,":TRID",$trid,32);
        oci_execute($result);oci_execute($result2);

        echo '<script>
                   setTimeout(function() {
                      swal({
                        title: "successfully deleted",
                        text: "",
                        type: "success"
                      }, function() {
                            window.location.href = "admin_base.php";
                         });
                     }, 50);
                 </script>';

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>trip list</title>
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
            <a href="admin_base.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white">Home</a>
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

<div class="container-fluid" style="background-color: rgba(0, 0, 0, 0.7);height: 150px" id="dell">
    <div class="row"></div>
    <form method="post" style="vertical-align: middle">
        <div class="row" style="margin-top: 40px">
            <div class="col-md-3">
                <div class="form-group">
                    <input type="number" id="dn" name="dn" placeholder=" trip id" style="vertical-align: middle;float: right" required>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <input type="number" id="tr" name="tr" placeholder=" train id" style="vertical-align: middle;" required>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                           value="delete" style="float: left">
                </div>
            </div>
        </div>
    </form>
</div>

</body>
</html>