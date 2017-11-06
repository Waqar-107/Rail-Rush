<?php

if (isset($_POST['submit']))
{
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $mail = $_POST['mail'];
    $pass1 = $_POST['password'];
    $pass2 = $_POST['password2'];

    if (empty($fname) || empty($lname) || empty($phone) || empty($mail) || empty($pass1) || empty($pass2))
    {
        echo '<script language="javascript">';
        echo 'alert("please fill all of the informations!!!")';
        echo '</script>';
    }

    else
    {
        if ($pass1 == $pass2)
        {
            //---------------------------------------------------------------connect to the database
            $server = "localhost";
            $username = "root";
            $password = "1505107";
            $dbname = "phpmyadmin";

            //create connection
            $conn = mysqli_connect($server, $username, $password, $dbname);

            //check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            //---------------------------------------------------------------connect to the database

            //---------------------------------------------------------------get an user id
            $sql="SELECT MAX(PASSENGER_ID) FROM PASSENGER";
            $result=$conn->query($sql) or die($conn->error);
            $row=$result->fetch_assoc();

            //---------------------------------------------------------------update database
            $newId=$row['MAX(PASSENGER_ID)']+1;

            $sql="INSERT INTO PASSENGER(PASSENGER_ID,FIRST_NAME,LAST_NAME,EMAIL_ID,P_PASSWORD) VALUES('$newId','$fname','$lname','$mail','$pass1')";

            if ($conn->query($sql) === TRUE)
            {
                echo "New record created successfully";
            }
            else
            {
                echo $pass1,"  ",$mail,"  ";
                echo "Error: " . $sql . "<br>" . $conn->error;
            }

            $msg = "Dear " . $fname . "\r\nwelcome aboard!! Your user id is ".$newId.". Use the id to log in and buy tickets\r\n- X-Railways";
            $msg = wordwrap($msg, 70, "\r\n");

            if (mail($mail, "user id", $msg))
            {
                echo '<script language="javascript">';
                echo 'alert("registered succesfully, an user id has been sent to your mail")';
                echo '</script>';

            }
        }

        else
        {
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