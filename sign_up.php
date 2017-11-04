<?php

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $mail = $_POST['mail'];
    $pass1 = $_POST['password'];
    $pass2 = $_POST['password2'];

    if (empty($fname) || empty($lname) || empty($phone) || empty($mail) || empty($pass1) || empty($pass2)) {
        echo '<script language="javascript">';
        echo 'alert("please fill all of the informations!!!")';
        echo '</script>';
    } else {
        if ($pass1 == $pass2) {

            $msg = "Dear " . $fname . "\r\n welcome aboard!! Your user id is 007. Use the id to log in and buy tickets\r\n- X-Railways";
            $msg = wordwrap($msg, 70, "\r\n");

            if (mail($mail, "user id", $msg)) {
                echo '<script language="javascript">';
                echo 'alert("registered succesfully, an user id has been sent to your mail")';
                echo '</script>';

            }
        } else {
            echo '<script language="javascript">';
            echo 'alert("password does not match")';
            echo '</script>';
        }

    }
}

?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <title>sign up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/signup.css" rel="stylesheet">
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
                            <input type="text" name="fname" id="fname" class="form-control" placeholder="first_name">
                        </div>

                        <div class="form-group">
                            <input type="text" name="lname" id="lname" class="form-control" placeholder="last_name">
                        </div>

                        <div class="form-group">
                            <input type="email" name="mail" id="mail" class="form-control" placeholder="email">
                        </div>

                        <div class="form-group">
                            <input type="text" name="phone" id="name" class="form-control" placeholder="cellphone">
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" id="password" class="form-control"
                                   placeholder="password">
                        </div>

                        <div class="form-group">
                            <input type="password" name="password2" id="password2" class="form-control"
                                   placeholder="confirm password">
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