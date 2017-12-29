<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

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

    //check if date is valid
    $ans;$xtid;
    $sql="BEGIN
             :ans:=VALID_TENDER(:tid);
          END;";
    $result=oci_parse($conn,$sql);

    oci_bind_by_name($result,":tid",$xtid,32);
    oci_bind_by_name($result,":ans",$ans,32);
    $xtid=$tid;$ans=null;oci_execute($result);

    if($ans==0)
    {
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

    if(isset($_POST['submit']) && $ans)
    {
        $com=$_POST['cname'];
        $e1=$_POST['e1'];$p1=$_POST['p1'];
        $e2=$_POST['e2'];$p2=$_POST['p2'];
        $cost=$_POST['costing'];

        //check if same company has proposed tenders for the id
        $sql="SELECT TENDER_ID FROM TENDER_OFFER WHERE TENDER_ID='$tid' AND COMPANY='$com'";
        $result=oci_parse($conn,$sql);
        oci_execute($result);

        if($row=oci_fetch_assoc($result))
        {
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
        }

        else
        {
            $sql="INSERT INTO TENDER_OFFER VALUES('$tid','$com','$e1','$e2','$p1','$p2','$cost')";
            $result=oci_parse($conn,$sql);

            if(oci_execute($result))
            {
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
            }

            else
            {
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
                            <input type="text" name="cname" id="cname" class="form-control" placeholder="company name" required>
                        </div>

                        <div class="form-group">
                            <input type="email" name="e1" id="e1" class="form-control" placeholder="email-1" required>
                        </div>

                        <div class="form-group">
                            <input type="email" name="e2" id="e2" class="form-control" placeholder="email-2" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="p1" id="p1" class="form-control" placeholder="cellphone-1" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="p2" id="p2" class="form-control" placeholder="cellphone-2" required>
                        </div>

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