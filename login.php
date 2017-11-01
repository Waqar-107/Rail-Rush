<?php

if(isset($_POST['submit']))
{
    $id=$_POST['id'];
    $pass=$_POST['pass'];

    if(empty($id) || empty($pass))
    {
        if (empty($id) && empty($pass))
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

        else if(empty($pass))
        {
            echo '<script language="javascript">';
            echo 'alert("enter password")';
            echo '</script>';
        }
    }

    else
    {
        //redirect to home
    }
}

?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <title>login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/login_cs.css" rel="stylesheet">
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

                        <!--forget password and sign-up-->
                        <a class="su" href="sign_up.php">sign up</a>

                        <a class="fp" href="#" id="forgot_password">forgot password</a>
                        <script>
                            document.getElementById("forgot_password").onclick=function () {
                                alert("password has been sent to your mail!")
                            }
                        </script>
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