<?php

session_start();
echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

if(isset($_SESSION['user_id']))
{
    header('Location: base.php');
}

if (isset($_POST['submit']))
{
    $cname = $_POST['cname'];
    $phone = $_POST['phone'];
    $mail = $_POST['mail'];
    $pass1 = $_POST['password'];
    $pass2 = $_POST['password2'];

    if (empty($cname) || empty($phone) || empty($mail) || empty($pass1) || empty($pass2))
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


            //---------------------------------------------------------------get an user id

            $sql = "SELECT NVL(MAX(COMPANY_ID),0) \"WW\" FROM COMPANY";
            $result = oci_parse($conn, $sql);
            oci_execute($result);
            $row = oci_fetch_assoc($result);
            //---------------------------------------------------------------update database
            $newId = $row['WW'] + 1;

            $sql = "INSERT INTO COMPANY VALUES('$newId','$cname','$mail','$phone','$pass1',0)";
            $result = oci_parse($conn, $sql);

            if (oci_execute($result))
            {
                oci_commit($conn);
                oci_close($conn);

                $_SESSION["user_in"] = true;
                $_SESSION["user_id"] = $newId;
                $_SESSION["type"] = 3;
                $_SESSION["uname"]="company-".$cname;

                $msg = "Dear " . $cname . "\r\nwelcome aboard!! Your user id is " . $newId . ". Use the id to log in and offer tenders. Please validate your company to offer\r\n- X-Railways";
                $msg = wordwrap($msg, 70, "\r\n");

                if (mail($mail, "user id", $msg)) {
                    echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "successfully registered",
                                    text: "we are looking forward for offers",
                                    type: "success"
                                }, function() {
                                    window.location = "base.php";
                                });
                            }, 50);
                            </script>';
                }
            } else {
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("sorry something went wrong","probably database connection problem :(","error");';
                echo '}, 50);</script>';
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
                            <input type="text" name="cname" id="cname" class="form-control" placeholder="company_name" required >
                        </div>


                        <div class="form-group">
                            <input type="email" name="mail" id="mail" class="form-control" placeholder="email" required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="phone" id="name" class="form-control" placeholder="cellphone" required>
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" id="password" class="form-control"
                                   placeholder="password" required>
                        </div>

                        <div class="form-group">
                            <input type="password" name="password2" id="password2" class="form-control"
                                   placeholder="confirm password" required>
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