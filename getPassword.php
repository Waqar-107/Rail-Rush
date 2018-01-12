<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';
    if(isset($_SESSION['user_id']))
    {
        header('Location: base.php');
    }

    $ft=$_GET['t'];

    if(isset($_POST['submit']))
    {
        $id=$_POST['id'];

        //---------------------------------------------------------------connect to the database
        //create connection
        $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

        //check connection
        if(!$conn)
        {
            echo 'connection error';
        }
        //---------------------------------------------------------------connect to the database

        $sp;$mail;
        if($ft==1)
        {
            $sql="SELECT A_PASSWORD,EMAIL_ID FROM ADMIN WHERE ADMIN_ID='$id'";
            $result=oci_parse($conn,$sql);
            oci_execute($result);$row=oci_fetch_assoc($result);

            $sp=$row['A_PASSWORD'];$mail=$row['EMAIL_ID'];
        }

        else if($ft==2)
        {
            $sql="SELECT P_PASSWORD,EMAIL_ID FROM PASSENGER WHERE PASSENGER_ID='$id'";
            $result=oci_parse($conn,$sql);
            oci_execute($result);$row=oci_fetch_assoc($result);

            $sp=$row['P_PASSWORD'];$mail=$row['EMAIL_ID'];
        }

        else
        {
            $sql="SELECT P_PASSWORD,EMAIL_ID FROM COMPANY WHERE COMPANY_ID='$id'";
            $result=oci_parse($conn,$sql);
            oci_execute($result);$row=oci_fetch_assoc($result);

            $sp=$row['P_PASSWORD'];$mail=$row['EMAIL_ID'];
        }

        $link="http://localhost/Rail-Rush/resetPassword.php?t=".$ft."&id=".$id."&pass=".$sp;
        $msg = "Dear User". "\r\nuse this link to reset your password " . $link . "\r\n- X-Railways";
        $msg = wordwrap($msg, 70, "\r\n");

        if (mail($mail, "reset password", $msg))
        {
            echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "check mail",
                                    text: "we are have sent a link to reset your password",
                                    type: "success"
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
    <title>getPassword</title>
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
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Get Password</h3>
                <div class="panel-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <input type="text" name="id" id="id" class="form-control" placeholder="user_id" required>
                        </div>

                        <div class="form-group" style="margin-top: 50px">
                            <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                                   value="get mail">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--in panel-->
</div>
</html>