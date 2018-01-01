<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if(empty($_SESSION['user_in']) || $_SESSION['type']==2)
    {
        header('Location: base.php');
    }

    if(isset($_POST['submit']))
    {
        //---------------------------------------------------------------connect to the database
        //create connection
        $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');
        //check connection
        if(!$conn)
        {
            echo 'connection error';
        }
        //---------------------------------------------------------------connect to the database

        $tname=$_POST['tname'];$st=$_POST['start'];$fin=$_POST['fin'];$com=$_POST['compartment'];
        $fc=$_POST['fc'];$sc=$_POST['sc'];$tc=$_POST['tc'];$cargo=$_POST['cargo'];$did=$_POST['did'];

        //check if name exists
        $sql="SELECT TRAIN_ID FROM TRAIN WHERE TRAIN_NAME='$tname'";
        $result=oci_parse($conn,$sql);oci_execute($result);

        $decider=true;
        if($row=oci_fetch_assoc($result))
        {
            $decider=false;
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("train exists with same name","try a different name","error");';
            echo '}, 50);</script>';
        }

        if($decider)
        {
            //check seat arrangement
            if(($cargo>0 && ($fc>0 || $sc>0 || $tc>0)) || ($cargo+$tc+$sc+$fc)==0)
            {
                $decider=false;
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("wrong seat arrangement","cargo and passenger trains are different","error");';
                echo '}, 50);</script>';
            }
        }

        if($decider)
        {
            if($st==$fin)
            {
                $decider=false;
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("same departure and arrival selected","self loops are not allowed in this graph ;)","error");';
                echo '}, 50);</script>';
            }
        }

        //if driver is not in the employee list
        if($decider)
        {
            $sql="SELECT EMPLOYEE_ID,JOB_TYPE FROM EMPLOYEE WHERE EMPLOYEE_ID='$did'";
            $result=oci_parse($conn,$sql);oci_execute($result);

            if($row=oci_fetch_assoc($result))
            {
                if($row['JOB_TYPE']=="DRIVER")
                {
                    $sql = "SELECT TRAIN_ID FROM TRAIN WHERE EMPLOYEE_ID=$did";

                    $result = oci_parse($conn, $sql);
                    oci_execute($result);

                    $utid = -1;
                    if ($row = oci_fetch_assoc($result)) {
                        $utid = $row['TRAIN_ID'];
                    }

                    if ($utid > -1) {
                        //update
                        $sql = "UPDATE TRAIN
                          SET EMPLOYEE_ID=NULL WHERE TRAIN_ID=$utid";
                        $result = oci_parse($conn, $sql);
                        oci_execute($result);
                    }

                    $sql = "SELECT MAX(TRAIN_ID) FROM TRAIN";
                    $result = oci_parse($conn, $sql);
                    oci_execute($result);
                    $row = oci_fetch_assoc($result);
                    $newId = $row['MAX(TRAIN_ID)'] + 1;

                    echo $newId;
                    $sql = "INSERT INTO TRAIN 
                            VALUES('$newId','$tname','$did','$st','$fin','$com','$fc','$sc','$tc','$cargo')";
                    $result = oci_parse($conn, $sql);

                    if (oci_execute($result))
                    {
                        echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "successfully registered",
                                    text: "new train in the fleet!!! Add the fares",
                                    type: "success"
                                }, function() {
                                    window.location = "trains.php";
                                });
                            }, 50);
                            </script>';
                    }
                }

                else
                {
                    echo '<script type="text/javascript">';
                    echo 'setTimeout(function () { swal("employee doesn\'t exists","give a valid employee_id","error");';
                    echo '}, 50);</script>';
                }
            }

            else
            {
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("employee doesn\'t exists","give a valid employee_id","error");';
                echo '}, 50);</script>';
            }
        }

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>create new train</title>
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
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Sign Up</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <input type="text" name="tname" id="tname" class="form-control" placeholder="train name" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="did" id="did" class="form-control" placeholder="employee id">
                        </div>

                        <div class="form-group">
                            <input type="text" name="start" id="start" class="form-control" placeholder="departure" required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="fin" id="fin" class="form-control" placeholder="arrival" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="compartment" id="compartment" class="form-control" placeholder="compartment" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="fc" id="fc" class="form-control" placeholder="first class" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="sc" id="sc" class="form-control" placeholder="second class" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="tc" id="tc" class="form-control" placeholder="third class" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="cargo" id="cargo" class="form-control" placeholder="cargo" required>
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