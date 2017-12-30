<?php



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>create new train</title>
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
                            <input type="text" name="tname" id="tname" class="form-control" placeholder="train name" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="did" id="did" class="form-control" placeholder="employee id">
                        </div>

                        <div class="form-group">
                            <input type="text" name="start" id="start" class="form-control" placeholder="departure">
                        </div>

                        <div class="form-group">
                            <input type="text" name="fin" id="fin" class="form-control" placeholder="arrival">
                        </div>

                        <div class="form-group">
                            <input type="number" name="compartment" id="compartment" class="form-control" placeholder="compartment" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="fc" id="fc" class="form-control" placeholder="first class" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="sc" id="sc" class="form-control" placeholder="second class" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="tc" id="tc" class="form-control" placeholder="third class" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="cargo" id="cargo" class="form-control" placeholder="cargo" required>
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