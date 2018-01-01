<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if(empty($_SESSION['user_in']) || $_SESSION['type']==2)
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

    $sql="SELECT TRAIN_NAME,NVL(EMPLOYEE_ID,-1) FROM TRAIN WHERE TRAIN_ID='$tid'";
    $result=oci_parse($conn,$sql);oci_execute($result);$row=oci_fetch_assoc($result);

    $tname=$row['TRAIN_NAME'];$did=$row['NVL(EMPLOYEE_ID,-1)'];
    if($did!=-1)
    {
        $sql="SELECT (FIRST_NAME||' '||LAST_NAME) \"NM\" FROM EMPLOYEE WHERE EMPLOYEE_ID='$did'";
        $result=oci_parse($conn,$sql);oci_execute($result);$row=oci_fetch_assoc($result);
        $did=$row['NM'];
    }

    echo '<div class="container-fluid" style="margin-top: 100px;font-family: \'Comic Sans MS\';font-size: 20px;color: white">
            <div class="row">
                <div class="col-md-8">Train Id: '.$tid.'</div>
            </div>
            <div class="row">
                <div class="col-md-8">Name: '.$tname.'</div>
            </div>
            <div class="row">
                <div class="col-md-8">Driver: '.$did.'</div>
            </div>
        </div>';

    if(isset($_POST['submit']))
    {
        $did=$_POST['did'];

        //check if the employee is valid
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

                if ($utid > -1)
                {
                    //update
                    $sql = "UPDATE TRAIN
                            SET EMPLOYEE_ID=NULL WHERE TRAIN_ID=$utid";
                    $result = oci_parse($conn, $sql);
                    oci_execute($result);
                }


                $sql="UPDATE TRAIN
                      SET EMPLOYEE_ID=$did WHERE TRAIN_ID=$tid";
                $result = oci_parse($conn, $sql);

                if (oci_execute($result))
                {
                    echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "successfully updated",
                                    text: "other train maybe got driverless :(",
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
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Update Train Driver</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <input type="number" name="did" id="did" class="form-control" placeholder="new driver" required>
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