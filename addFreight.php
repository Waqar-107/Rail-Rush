<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if (empty($_SESSION['user_id']) || $_SESSION['type'] == 2)
    {
        header('Location: base.php');
    }

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');
    //check connection
    if (!$conn) {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //make the train options
    $sql = "SELECT TRAIN_ID FROM TRAIN WHERE CARGO>0";
    $result = oci_parse($conn, $sql);
    oci_execute($result);
    $trains = array();
    while ($row = oci_fetch_assoc($result))
    {
        array_push($trains, $row['TRAIN_ID']);
    }


    if (isset($_POST['submit']))
    {
        $trainNo = $trains[$_POST['trainNo'] - 1];
        $company = $_POST['company'];
        $weight = $_POST['weight'];
        $inside = $_POST['inside'];
        $trip_date = $_POST['trip_date'];

        //check if the train is available in that date
        $decider = 1;
        $sql = "SELECT SEAT_ID,SOLD FROM SEAT WHERE SEAT_ID  
                LIKE (TO_CHAR(TO_DATE('$trip_date','YYYY-MM-DD'),'DD-MM-YYYY') || '#' || '$trainNo' || '#4#%')";
        $result = oci_parse($conn, $sql);
        oci_execute($result);$seatId=array();

        $cnt=0;
        while($row = oci_fetch_assoc($result))
        {
            if($row['SOLD']==0)
                array_push($seatId,$row['SEAT_ID']);
            else
                $cnt++;
        }

        //trip does not exists
        if($cnt==0 && count($seatId)==0)
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("trip doesn\'t exists","select valid trip","error");';
            echo '}, 50);</script>';
        }

        else if($cnt>0 && count($seatId)==0)
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("no empty trailer remaining!!!","try another train :(","error");';
            echo '}, 50);</script>';
        }

        else
        {
            $trailer=$seatId[0];
            $newId;
            $sql="SELECT NVL(MAX(FREIGHT_ID),0) \"WW\" FROM FREIGHT";
            $result=oci_parse($conn,$sql); oci_execute($result);$row=oci_fetch_assoc($result);
            $newId=$row['WW']+1;

            //encode trailer no.
            $ftrailer=0;$k=1;
            for($i=strlen($trailer)-1;$i>=0;$i--)
            {
                if($trailer[$i]=='#')
                    break;

                $ftrailer+=($trailer[$i])*$k;$k*=10;
            }

            $sql="INSERT INTO FREIGHT VALUES('$newId','$trainNo','$ftrailer','$company','$weight','$inside','0',TO_DATE('$trip_date','YYYY-MM-DD'))";
            $result=oci_parse($conn,$sql);

            if(oci_execute($result))
            {
                echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "successfully registered",
                                    text: "",
                                    type: "success"
                                }, function() {
                                    window.location = "freight.php";
                                });
                            }, 50);
                            </script>';
            }

            else
            {
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("sorry something went wrong!!!","probably problem with the database :(","error");';
                echo '}, 50);</script>';
            }
        }

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>add freight</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
    <link href="css/addFreight.css" rel="stylesheet">
    <script type="text/javascript" src="js/showDate.js"></script>
</head>
<body>

<div class="container-fluid">
    <nav class="navbar fixed-top navbar-light">
        <img src="images/trainLogo.png" style="margin-left: 10px">
        <a href="admin_base.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white"
           ;>Home</a>
        <a href="destruction.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white"
           ;>log out</a>
        <p id="tt" style="color: white;font-size: 17px;font-family: 'Comic Sans MS';margin-right: 10px;margin-top: 5px">
            date</p>
        <script type="text/javascript">
            dateShower()
        </script>
    </nav>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Add Freight</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <select class="form-control" name="trainNo" id="trainNo" required>
                                <option value="0">train no</option>
                                <?php
                                for ($i = 0; $i < count($trains); $i++) {
                                    echo '<option value=' . ($i + 1) . '>' . $trains[$i] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="text" name="company" id="company" class="form-control" placeholder="company"
                                   required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="weight" id="weight" class="form-control" placeholder="weight"
                                   required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="inside" id="inside" class="form-control"
                                   placeholder="inside" required>
                        </div>

                        <div class="form-group">
                            <input type="date" name="trip_date" id="trip_date" class="form-control"
                                   placeholder="trip date" required>
                        </div>

                        <div class="form-group" style="margin-top: 50px">
                            <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                                   value="add">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>