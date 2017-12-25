<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if(isset($_SESSION["user_in"]))
    {
        header('Location: admin_base.php');
    }

    if(isset($_POST['submit']))
    {
        $id=$_POST['id'];
        $pass=$_POST['pass'];

        if(empty($id) || empty($pass))
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("fill everything up","i open at the close","error");';
            echo '}, 50);</script>';
        }

        else
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

            //get password from database
            $sql="SELECT A_PASSWORD FROM ADMIN WHERE ADMIN_ID=$id";
            $result = oci_parse($conn,$sql);
            oci_execute($result);
            $row=oci_fetch_assoc($result);

            $pass_db=$row['A_PASSWORD'];

            //password matched, redirect to home
            if($pass==$pass_db)
            {
                $_SESSION['user_in']=true;
                $_SESSION['user_id']=$id;
                $_SESSION['type']=1;

                header('Location: admin_base.php');
            }

            else
            {
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("incorrect password","it\'s bad to play with anothers account ;)","error");';
                echo '}, 50);</script>';
            }

            oci_close($conn);
        }
    }

?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <title>admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/admin.css" rel="stylesheet">
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
</head>


<body>

<div class="container">

    <!--sign in panel-->
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">

            <div class="panel panel-default">
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Sign In</h3>
                <div class="panel-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <input type="text" name="id" id="id" class="form-control" placeholder="user_id">
                        </div>

                        <div class="form-group" style="margin-top: 10px">
                            <input type="password" name="pass" id="pass" class="form-control" placeholder="password">
                        </div>

                        <div class="form-group" style="margin-top: 50px">
                            <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block" value="login">
                        </div>

                        <!--forget password-->
                        <a class="fp" href="getPassword.php" id="forgot_password" name="forgot_password">forgot password</a>
                        <!--forget password and sign-up-->

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--sign in panel-->
</div>

</body>


</html>