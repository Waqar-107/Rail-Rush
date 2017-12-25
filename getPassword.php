<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';
    if(isset($_SESSION['user_id']))
    {
        header('Location: base.php');
    }

    if(isset($_POST['submit']))
    {
        $id=$_POST['id'];

        if(isset($_POST['admin']) && empty($_POST['user']))
            $category="admin";

        else if(isset($_POST['user']) && empty($_POST['admin']))
            $category="user";

        else
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("fill everything correctly","","error");';
            echo '}, 50);</script>';
        }

        //---------------------------------------------------------------connect to the database
        //create connection
        $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

        //check connection
        if(!$conn)
        {
            echo 'connection error';
        }
        //---------------------------------------------------------------connect to the database

        if(empty($id) || empty($category))
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("fill everything correctly","","error");';
            echo '}, 50);</script>';
        }

        else
        {
            //---------------------------------------------------------------get password using id
            $category=strtolower($category);

            if($category=="user")
            {
                $sql="SELECT EMAIL_ID,FIRST_NAME,P_PASSWORD FROM PASSENGER WHERE PASSENGER_ID=$id";
                $result = oci_parse($conn,$sql);
                oci_execute($result);
                $row=oci_fetch_assoc($result);

                $fname=$row['FIRST_NAME'];
                $pass=$row['P_PASSWORD'];
                $mail=$row['EMAIL_ID'];

                $msg = "Dear " . $fname . "\r\n welcome aboard!! Your user password is '".$pass."'. Use the id to log in and buy tickets\r\n- X-Railways";
                $msg = wordwrap($msg, 70, "\r\n");

                if (mail($mail, "user id", $msg))
                {
                    echo '<script>
                    setTimeout(function() {
                        swal({
                            title: "password has been sent to your mail",
                            text: "eat nuts to not to forget passwords",
                            type: "success"
                        }, function() {
                            window.location = "freight.php";
                        });
                    }, 50);
                </script>';
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
                $sql="SELECT EMAIL_ID,FIRST_NAME,A_PASSWORD FROM ADMIN WHERE ADMIN_ID=$id";
                $result =oci_parse($conn,$sql);
                oci_execute($result);
                $row=oci_fetch_assoc($result);

                $fname=$row['FIRST_NAME'];
                $pass=$row['A_PASSWORD'];
                $mail=$row['EMAIL_ID'];

                $msg = "Dear " . $fname . "\r\n Your user password is '".$pass."'. As a responsible officer, it is not expected from you to forget your password.\r\n- X-Railways";
                $msg = wordwrap($msg, 70, "\r\n");

                if (mail($mail, "password", $msg))
                {
                    echo '<script language="javascript">';
                    echo 'alert("THE PASSWORD HAS BEEN SENT TO YOUR MAIL.");';
                    echo 'location="base.php";';
                    echo '</script>';

                }

                else
                {
                    echo '<script type="text/javascript">';
                    echo 'setTimeout(function () { swal("sorry something went wrong","","error");';
                    echo '}, 50);</script>';
                }
            }

            else
            {
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("fill everything correctly","","error");';
                echo '}, 50);</script>';
            }
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
    <link href="css/getPassword.css" rel="stylesheet">
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