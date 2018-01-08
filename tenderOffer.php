<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if(empty($_SESSION['user_in']) || $_SESSION['type']!=3)
    {
        header('location: base.php');
    }

    $tid=$_GET['tenderId'];
    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');
    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //---------------------------------------------------------------check if the company is validate
    $user=$_SESSION['user_id'];
    $sql="SELECT VALID FROM COMPANY WHERE COMPANY_ID='$user'";
    $result=oci_parse($conn,$sql);oci_execute($result);$row=oci_fetch_assoc($result);

    $decider=1;
    if(!$row['VALID'])
    {
        $decider=0;
        echo '<script>
                    setTimeout(function() {
                        swal({
                            title: "you haven\'t validate your company yet!!!",
                            text: "communicate with the office",
                            type: "error"
                        }, function() {
                            window.location = "base.php";
                        });
                    }, 50);
                    </script>';
    }
    //---------------------------------------------------------------check if the company is validate

    if($decider) {
        //check if date is valid
        $ans;
        $xtid;
        $sql = "BEGIN
             :ans:=VALID_TENDER(:tid);
          END;";
        $result = oci_parse($conn, $sql);

        oci_bind_by_name($result, ":tid", $xtid, 32);
        oci_bind_by_name($result, ":ans", $ans, 32);
        $xtid = $tid;
        $ans = null;
        oci_execute($result);

        if ($ans == 0) {
            echo '<script>
                     setTimeout(function() {
                      swal({
                         title: "deadline expired!!!",
                         text: "wait till the next tender launches",
                         type: "error"
                         }, function() {
                         window.location = "base.php";
                      });
                    }, 50);
                 </script>';
        }

        if (isset($_POST['submit']) && $ans)
        {
            $com = $_SESSION['user_id'];
            $cost = $_POST['costing'];

            //check if same company has proposed tenders for the id
            $sql = "SELECT TENDER_ID FROM TENDER_OFFER WHERE TENDER_ID='$tid' AND C_ID='$com'";
            $result = oci_parse($conn, $sql);
            oci_execute($result);

            if ($row = oci_fetch_assoc($result)) {
                echo '<script>
                 setTimeout(function() {
                  swal({
                     title: "you already have filed a tender!!!",
                     text: "wait till the meeting :)",
                     type: "error"
                     }, function() {
                     window.location = "base.php";
                  });
                }, 50);
             </script>';
            } else {
                $sql = "INSERT INTO TENDER_OFFER VALUES('$tid','$com','$cost')";
                $result = oci_parse($conn, $sql);

                if (oci_execute($result)) {
                    echo '<script>
                     setTimeout(function() {
                      swal({
                         title: "successfully registered!!!",
                         text: "wait till the meeting :)",
                         type: "success"
                         }, function() {
                         window.location = "base.php";
                      });
                    }, 50);
                 </script>';
                } else {
                    echo '<script>
                     setTimeout(function() {
                      swal({
                         title: "sorry something went wrong!!!",
                         text: "problem in database, try again later:)",
                         type: "error"
                         }, function() {
                         window.location = "base.php";
                      });
                    }, 50);
                 </script>';
                }
            }
        }
    }

    oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>offer tender</title>
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
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Tender Contract</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <input type="number" name="costing" id="costing" class="form-control" placeholder="proposed cost" required>
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