<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';
    if(isset($_SESSION['user_id']))
    {
        header('Location: base.php');
    }

    $ft=$_GET['t'];$id=$_GET['id'];$acPass=$_GET['pass'];

    if(isset($_POST['submit']))
    {
        $newPass=$_POST['pass'];

        //---------------------------------------------------------------connect to the database
        //create connection
        $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

        //check connection
        if(!$conn)
        {
            echo 'connection error';
        }
        //---------------------------------------------------------------connect to the database

        if($ft==1)
        {
            $sql="SELECT A_PASSWORD FROM ADMIN WHERE ADMIN_ID='$id'";
            $result=oci_parse($conn,$sql);oci_execute($result);
            $row=oci_fetch_assoc($result);

            $retPass=$row['A_PASSWORD'];
        }

        else if($ft==2)
        {
            $sql="SELECT P_PASSWORD FROM PASSENGER WHERE PASSENGER_ID='$id'";
            $result=oci_parse($conn,$sql);oci_execute($result);
            $row=oci_fetch_assoc($result);

            $retPass=$row['P_PASSWORD'];
        }

        else
        {
            $sql="SELECT P_PASSWORD FROM COMPANY WHERE COMPANY_ID='$id'";
            $result=oci_parse($conn,$sql);oci_execute($result);
            $row=oci_fetch_assoc($result);

            $retPass=$row['P_PASSWORD'];
        }

        //link that was mailed
        $newPass=md5($newPass);
        if($retPass==$acPass)
        {
            if($ft==1)
                $sql="UPDATE ADMIN SET A_PASSWORD='$newPass' WHERE ADMIN_ID='$id'";

            else if($ft==2)
                $sql="UPDATE PASSENGER SET P_PASSWORD='$newPass' WHERE PASSENGER_ID='$id'";

            else
                $sql="UPDATE COMPANY SET P_PASSWORD='$newPass' WHERE COMPANY_ID='$id'";

            $result=oci_parse($conn,$sql);

            if(oci_execute($result))
            {
                echo '<script>
                    setTimeout(function() {
                         swal({
                                    title: "successfully updated",
                                    text: " ",
                                    type: "success"
                                }, function() {
                                    window.location = "base.php";
                                });
                            }, 50);
                            </script>';
            }

        }

        //may be bot or hacking attemt!!!
        else
        {
            echo '<script>
                    setTimeout(function() {
                         swal({
                                    title: "seems like you are not an user",
                                    text: " ",
                                    type: "error"
                                }, function() {
                                    window.location = "base.php";
                                });
                            }, 50);
                            </script>';
        }

        oci_close($conn);
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>reset password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/login_cs.css" rel="stylesheet">
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
</head>

<body>

</body>
<div class="container">

    <!--panel-->
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">

            <div class="panel panel-default">
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Reset Password</h3>
                <div class="panel-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <input type="text" name="pass" id="pass" class="form-control" placeholder="new password" required>
                        </div>

                        <div class="form-group" style="margin-top: 50px">
                            <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                                   value="reset">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--in panel-->
</div>
</html>