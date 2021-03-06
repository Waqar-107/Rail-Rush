<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if(isset($_SESSION['user_id']))
    {
        header('Location: base.php');
    }

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
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("what\'s the hurry??","fill everything first","error");';
            echo '}, 50);</script>';
        }

        else
        {
            if ($pass1 == $pass2)
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


                //---------------------------------------------------------------check if email and password are unique
                $sql="SELECT EMAIL_ID,PHONE FROM PASSENGER ";
                $result=oci_parse($conn,$sql);
                oci_execute($result);

                $green=true;
                while($row=oci_fetch_assoc($result))
                {
                    if($mail==$row['EMAIL_ID'])
                    {
                        $green=false;session_unset();
                        echo '<script type="text/javascript">';
                        echo 'setTimeout(function () { swal("account exists with same email id","try a different mail","error");';
                        echo '}, 50);</script>';

                    }
                }

                //---------------------------------------------------------------check if email and password are unique


                //---------------------------------------------------------------get an user id
                if($green)
                {
                    $sql = "SELECT MAX(PASSENGER_ID) FROM PASSENGER";
                    $result = oci_parse($conn, $sql);
                    oci_execute($result);
                    $row = oci_fetch_assoc($result);
                    //---------------------------------------------------------------update database
                    $newId = $row['MAX(PASSENGER_ID)'] + 1;

                    $sql = "INSERT INTO PASSENGER(PASSENGER_ID,FIRST_NAME,LAST_NAME,EMAIL_ID,PHONE,P_PASSWORD) VALUES('$newId','$fname','$lname','$mail','$phone','$pass1')";
                    $result = oci_parse($conn, $sql);

                    if (oci_execute($result)) {
                        oci_commit($conn);
                        oci_close($conn);

                        $_SESSION["user_in"] = true;
                        $_SESSION["user_id"] = $newId;
                        $_SESSION["type"]=2;
                        $_SESSION['uname']="user-".$fname;

                        $msg = "Dear " . $fname . "\r\nwelcome aboard!! Your user id is " . $newId . ". Use the id to log in and buy tickets\r\n- X-Railways";
                        $msg = wordwrap($msg, 70, "\r\n");

                        if (mail($mail, "user id", $msg))
                        {
                                echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "successfully registered",
                                    text: "we are looking forward for your booking",
                                    type: "success"
                                }, function() {
                                    window.location = "base.php";
                                });
                            }, 50);
                            </script>';
                        }
                    }

                    else
                    {
                        echo '<script type="text/javascript">';
                        echo 'setTimeout(function () { swal("sorry something went wrong","probably database connection problem :(","error");';
                        echo '}, 50);</script>';
                    }
                }
            }

            else
            {
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("password did\'nt matched","give same password","error");';
                echo '}, 50);</script>';
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