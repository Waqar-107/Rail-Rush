<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if (empty($_SESSION['user_in']) || $_SESSION['type'] == 2)
    {
        header('Location: base.php');
    }

    if (isset($_POST['submit']))
    {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $phone = $_POST['phone'];
        $mail = $_POST['mail'];
        $salary = $_POST['salary'];
        $jt = $_POST['jt'];


        //---------------------------------------------------------------connect to the database
        //create connection
        $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');
        //check connection
        if (!$conn) {
            echo 'connection error';
        }
        //---------------------------------------------------------------connect to the database


        //---------------------------------------------------------------check if email and password are unique
        $sql = "SELECT EMAIL,PHONE FROM EMPLOYEE ";
        $result = oci_parse($conn, $sql);
        oci_execute($result);

        $green = true;
        while ($row = oci_fetch_assoc($result))
        {
            if ($mail == $row['EMAIL'] || $phone == $row['PHONE'])
            {
                $green = false;
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("account exists with same email id or phone","try a different mail","error");';
                echo '}, 50);</script>';

            }
        }

        //---------------------------------------------------------------check if email and password are unique


        //---------------------------------------------------------------get an user id
        if ($green)
        {
            $sql = "SELECT MAX(EMPLOYEE_ID) FROM EMPLOYEE";
            $result = oci_parse($conn, $sql);
            oci_execute($result);
            $row = oci_fetch_assoc($result);
            //---------------------------------------------------------------update database
            $newId = $row['MAX(EMPLOYEE_ID)'] + 1;

            //using uppercase forjob type for the sake of simplicity
            $jt=strtoupper($jt);
            $sql = "INSERT INTO EMPLOYEE VALUES('$newId','$fname','$lname','$mail','$phone',SYSDATE,'$salary','$jt')";
            $result = oci_parse($conn, $sql);

            if (oci_execute($result))
            {
                oci_close($conn);

                echo '<script>
                            setTimeout(function() {
                               swal({
                                  title: "employee successfully registered",
                                        text: "",
                                        type: "success"
                                     }, function() {
                                              window.location = "employeeList.php";
                                         });
                                }, 50);
                            </script>';

            }

            else
            {
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("sorry something went wrong","probably database connection problem :(","error");';
                echo '}, 50);</script>';
            }
        }
    }


?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <title>employee create</title>
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
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">New Employee</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <input type="text" name="fname" id="fname" class="form-control" placeholder="first_name"
                                   required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="lname" id="lname" class="form-control" placeholder="last_name"
                                   required>
                        </div>

                        <div class="form-group">
                            <input type="email" name="mail" id="mail" class="form-control" placeholder="email" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="phone" id="name" class="form-control" placeholder="cellphone"
                                   required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="salary" id="salary" class="form-control" placeholder="salary"
                                   required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="jt" id="jt" class="form-control" placeholder="job" required>
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