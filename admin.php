<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <title>admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/admin.css" rel="stylesheet">
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
                    <form action="">
                        <div class="form-group">
                            <input type="text" name="" class="form-control" placeholder="user_id">
                        </div>

                        <div class="form-group" style="margin-top: 10px">
                            <input type="password" name="" class="form-control" placeholder="password">
                        </div>

                        <div class="form-group" style="margin-top: 50px">
                            <input type="submit" name="" class="btn btn-success btn-lg btn-block" value="login">
                        </div>

                        <!--forget password-->
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