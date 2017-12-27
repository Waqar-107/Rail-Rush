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

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if (!$conn) {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //get the starting or arrivals from the database
    $sql = "SELECT DISTINCT(STARTING) FROM  TRIP ORDER BY STARTING";
    $result = oci_parse($conn, $sql);
    $place = array();

    //SAVE THEM IN ARRAY
    if (oci_execute($result))
    {
        while ($row = oci_fetch_assoc($result))
        {
            array_push($place, $row['STARTING']);
        }
    }

    //get the train-id from the database
    $sql = "SELECT DISTINCT(TRAIN_ID) FROM  TRAIN ORDER BY TRAIN_ID";
    $result = oci_parse($conn, $sql);
    $trains = array();

    //SAVE THEM IN ARRAY
    if (oci_execute($result))
    {
        while ($row = oci_fetch_assoc($result))
        {
            array_push($trains, $row['TRAIN_ID']);
        }
    }

    //check
    $sql = "SELECT MAX(FARE_ID) FROM  FARE";
    $result = oci_parse($conn, $sql);
    oci_execute($result);
    $row=oci_fetch_assoc($result);
    $fare_id=$row['MAX(FARE_ID)']+1;

    if(isset($_POST['submit']))
    {
        if(empty($_POST['mprice']))
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("fill everything","","error");';
            echo '}, 50);</script>';
        }

        else
        {
            $dest=$place[$_POST['mfinish']-1];$starting=$place[$_POST['mstart']-1];
            $price=$_POST['mprice'];$train_no=$trains[$_POST['mtrain']-1];
            $type=$_POST['mtype'];

            $decider=true;

            echo $dest.' '.$starting.' '.$price.' '.$type.' '.$train_no;
            echo '\r\n';

            //---------------------------------------------------------------------------------self loop
            if($dest==$starting)
            {
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("same departure and arrival place selected","we provide a graph without self loop ;)","error");';
                echo '}, 50);</script>';

                $decider=false;
            }
            //---------------------------------------------------------------------------------self loop


            //---------------------------------------------------------------------------------type check
            //check if cargo is given and the train is cargo
            if($decider)
            {
                $sql="SELECT CARGO FROM TRAIN WHERE TRAIN_ID='$train_no'";
                $result=oci_parse($conn,$sql);
                oci_execute($result);
                $row=oci_fetch_assoc($result);
                $quant=$row['CARGO'];

                if(($type==4 && $quant==0) || ($type<4 && $quant>0))
                {
                    echo '<script type="text/javascript">';
                    echo 'setTimeout(function () { swal("wrong type selected","select the type carefully!!!","error");';
                    echo '}, 50);</script>';

                    $decider=false;
                }
            }
            //---------------------------------------------------------------------------------type check


            //---------------------------------------------------------------------------------train+type
            //check if train and type already exists
            if($decider)
            {
                $sql="SELECT FARE_ID FROM FARE WHERE STYPE='$type' AND TRAIN_ID='$train_no'";
                $result=oci_parse($conn,$sql);
                oci_execute($result);

                if($row=oci_fetch_assoc($result))
                {
                    echo '<script type="text/javascript">';
                    echo 'setTimeout(function () { swal("train and type already is in the table","","error");';
                    echo '}, 50);</script>';

                    $decider=false;
                }

            }
            //---------------------------------------------------------------------------------train+type


            //---------------------------------------------------------------------------------route
            //check if the train really is in route of $dest and $starting
            if($decider)
            {
                $sql="SELECT DEPARTURE,ARRIVAL FROM TRAIN WHERE TRAIN_ID='$train_no'";
                $result=oci_parse($conn,$sql);
                oci_execute($result);
                $row=oci_fetch_assoc($result);

                if(($dest==$row['DEPARTURE'] && $starting==$row['ARRIVAL']) || ($dest==$row['ARRIVAL'] && $starting==$row['DEPARTURE'])){}

                else
                {
                    echo '<script type="text/javascript">';
                    echo 'setTimeout(function () { swal("wrong route!!!","","error");';
                    echo '}, 50);</script>';

                    $decider=false;
                }
            }

            //---------------------------------------------------------------------------------self loop


            //check if the price is a number
            if($decider)
            {
                if(is_numeric($price) && $price > 0 && $price == round($price, 0))
                {
                    $sql="INSERT INTO FARE VALUES('$fare_id','$starting','$dest','$train_no','$type','$price')";
                    $result=oci_parse($conn,$sql);

                    if(oci_execute($result))
                    {
                        echo '<script>
                                setTimeout(function() {
                                    swal({
                                        title: "successfully created",
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
                        echo 'setTimeout(function () { swal("sorry something went wrong!!!","problem in our database :(","error");';
                        echo '}, 50);</script>';
                    }
                }

                else
                {
                    echo '<script type="text/javascript">';
                    echo 'setTimeout(function () { swal("invalid price given","enter the price carefully!!!","error");';
                    echo '}, 50);</script>';
                }
            }

        }
    }


?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <title>new fare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/signup.css" rel="stylesheet">
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
</head>


<body>
<div class="container">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">New Fare</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <select class="form-control" name="mstart" id="mstart">
                                <?php
                                for($i=0;$i<count($place);$i++)
                                {
                                    echo '<option value='.($i+1).'>'.$place[$i].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="mfinish" id="mfinish">
                                <?php
                                for($i=0;$i<count($place);$i++)
                                {
                                    echo '<option value='.($i+1).'>'.$place[$i].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="mtrain" id="mtrain">
                                <?php
                                for($i=0;$i<count($trains);$i++)
                                {
                                    echo '<option value='.($i+1).'>'.$trains[$i].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="mtype" id="mtype">
                                <?php
                                for($i=1;$i<=4;$i++)
                                {
                                    echo '<option value='.($i).'>'.$i.'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="text" name="mprice" id="mprice" class="form-control"
                                   placeholder="price">
                        </div>

                        <div class="form-group" style="margin-top: 50px">
                            <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                                   value="create">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>


</html>