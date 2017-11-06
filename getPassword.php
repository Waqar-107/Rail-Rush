<?php

if(isset($_POST['submit']))
{
    $id=$_POST['id'];

    if($_POST['admin'])
        $category="admin";

    else
        $category="user";

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

    if(empty($id) || empty($category))
    {
        if (empty($id) && empty($category))
        {
            echo '<script language="javascript">';
            echo 'alert("please fill all of the informations!!!")';
            echo '</script>';
        }


        else if(empty($id) )
        {
            echo '<script language="javascript">';
            echo 'alert("enter user id")';
            echo '</script>';
        }

        else if(empty($category))
        {
            echo '<script language="javascript">';
            echo 'alert("enter category")';
            echo '</script>';
        }
    }

    else
    {
        //---------------------------------------------------------------get password using id
        $category=strtolower($category);

        if($category=="user")
        {
            $sql="SELECT EMAIL_ID,FIRST_NAME,P_PASSWORD FROM PASSENGER WHERE PASSENGER_ID=$id";
            $result = $conn->query($sql) or die($conn->error);
            $row=$result->fetch_assoc();

            $fname=$row['FIRST_NAME'];
            $pass=$row['P_PASSWORD'];
            $mail=$row['EMAIL_ID'];

            $msg = "Dear " . $fname . "\r\n welcome aboard!! Your user password is ".$pass." Use the id to log in and buy tickets\r\n- X-Railways";
            $msg = wordwrap($msg, 70, "\r\n");

            if (mail($mail, "user id", $msg))
            {
                echo '<script language="javascript">';
                echo 'alert("password has been sent to your mail")';
                echo '</script>';

            }

            else
            {
                echo '<script language="javascript">';
                echo 'alert("something went wrong, please try again!")';
                echo '</script>';
            }

        }

        else if($category=="admin")
        {
            $sql="SELECT EMAIL_ID,FIRST_NAME,a_PASSWORD FROM ADMIN WHERE ADMIN_ID=$id";
            $result = $conn->query($sql) or die($conn->error);
            $row=$result->fetch_assoc();

            $fname=$row['FIRST_NAME'];
            $pass=$row['a_PASSWORD'];
            $mail=$row['EMAIL_ID'];

            $msg = "Dear " . $fname . "\r\n Your user password is ".$pass." As a responsible officer, it is not expected from you to forget your password.\r\n- X-Railways";
            $msg = wordwrap($msg, 70, "\r\n");

            if (mail($mail, "password", $msg))
            {
                echo '<script language="javascript">';
                echo 'alert("password has been sent to your mail")';
                echo '</script>';

            }

            else
            {
                echo '<script language="javascript">';
                echo 'alert("something went wrong, please try again!")';
                echo '</script>';
            }
        }

        else
        {
            echo '<script language="javascript">';
            echo 'alert("invalid category")';
            echo '</script>';
        }
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>getPassword</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/getPassword.css" rel="stylesheet">
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
                            <input type="text" name="id" id="id" class="form-control" placeholder="user_id">
                        </div>

                        <div class="form-group" style="float: left;margin-top: 10px">
                            <label><input type="radio" name="admin" id="admin">admin</label>
                        </div>

                        <div class="form-group" style="float: left;margin-top: 10px;margin-left: 25px">
                            <label><input type="radio" name="user" id="user">user</label>
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