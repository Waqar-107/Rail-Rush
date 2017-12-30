<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if (empty($_SESSION['user_in']) || $_SESSION['type'] != 1)
    {
        header('location: base.php');
    }

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if (!$conn) {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //---------------------------------------------------------------get an id
    $sql="SELECT NVL(MAX(TENDER_ID),0) FROM TENDER_DES";
    $result=oci_parse($conn,$sql);
    oci_execute($result);
    $row=oci_fetch_assoc($result);
    $newId=$row['NVL(MAX(TENDER_ID),0)']+1;

    $sql="SELECT NVL(MAX(TENDER_ID),0) FROM TENDER_HISTORY";
    $result=oci_parse($conn,$sql);
    oci_execute($result);
    $row=oci_fetch_assoc($result);
    $newId=max($row['NVL(MAX(TENDER_ID),0)']+1,$newId);

    if(isset($_POST['submit']))
    {
        $des=$_POST['description'];$tdate=$_POST['tdate'];
        $des=str_replace("\r\n","@",$des);
        $sql="INSERT INTO TENDER_DES VALUES ('$newId','$des',TO_DATE('$tdate','YYYY-MM-DD'))";
        $result=oci_parse($conn,$sql);

        if(oci_execute($result))
        {
            echo '<script>
                     setTimeout(function() {
                        swal({
                         title: "successfully registered",
                         text: "we are looking forward for your booking",
                         type: "success"
                        }, function() {
                             window.location = "tender.php";
                               });
                     }, 50);
                  </script>';
        }

        else
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("sorry something went wrong!!!!","probably problem in the database :(","error");';
            echo '}, 50);</script>';
        }

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>create tender</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
    <link href="css/signup.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Register Tender Detail</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <input type="date" name="tdate" id="tdate" class="form-control" placeholder="expire-date" required>
                        </div>

                        <div class="form-group">
                            <textarea id="description" name="description" class="form-control" rows="7" placeholder="description" required></textarea>
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