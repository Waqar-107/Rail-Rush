<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if (empty($_SESSION['user_in']) || $_SESSION['type'] != 2)
    {
        header('location: base.php');
    }

    $data1=$_GET['data1'];
    $tid=$_GET['tid'];
    $train_id=$_GET['train_id'];
    $user=$_SESSION['user_id'];

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //get password from database
    $sql="SELECT TO_CHAR(TRIP_DATE,'DD-MM-YYYY') \"dt\" FROM TRIP WHERE TRIP_ID='$tid'";
    $result = oci_parse($conn,$sql);
    oci_execute($result);
    $row=oci_fetch_assoc($result);
    $Date=$row['dt'];

    $seatId=explode('W',$data1);

    $updatedSeat=array();$total=0;
    $prices=array();

    for($i=0;$i<count($seatId)-1;$i++)
    {
        $sid=0;$k=1;
        for($j=strlen($seatId[$i])-1;$j>1;$j--)
        {
            $sid+=($seatId[$i][$j]*$k);$k*=10;
        }

        if($seatId[$i][0]=='F')
            $x=1;
        else if($seatId[$i][0]=='S')
            $x=2;
        else
            $x=3;

        $actual=$Date.'#'.$train_id.'#'.$x.'#'.$sid;

        $sql="SELECT PRICE FROM FARE WHERE TRAIN_ID='$train_id' AND STYPE='$x'";
        $result=oci_parse($conn,$sql);oci_execute($result);
        $row=oci_fetch_assoc($result);

        $total+=$row['PRICE'];
        array_push($prices,$row['PRICE']);

        $XU=$user.'1';
        $sql="UPDATE SEAT SET SOLD='$XU' WHERE SEAT_ID='$actual'";
        $result=oci_parse($conn,$sql);oci_execute($result);

        array_push($updatedSeat,$actual);
    }

    //query if the user can afford it
    if(isset($_POST['submit']))
    {
        $bank_id=$_POST['accno'];
        $pass=$_POST['pass'];

        $sql="SELECT B_PASSWORD,TAKA FROM BANK WHERE ACCOUNT_NO='$bank_id'";
        $result=oci_parse($conn,$sql);
        oci_execute($result);$row=oci_fetch_assoc($result);

        $ac_pass=$row['B_PASSWORD'];$tk=$row['TAKA'];

        //no account
        if(empty($ac_pass))
        {
            for($i=0;$i<count($updatedSeat);$i++)
            {
                $XU=0;
                $sql="UPDATE SEAT SET SOLD='$XU' WHERE SEAT_ID='$actual'";
                $result=oci_parse($conn,$sql);oci_execute($result);
            }

            echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "no such account exists",
                                    text: "try valid account number",
                                    type: "error"
                                }, function() {
                                    window.location = "base.php";
                                });
                            }, 50);
                            </script>';
        }

        else
        {
            if($tk<$total || $pass!=$ac_pass)
            {
                for($i=0;$i<count($updatedSeat);$i++)
                {
                    $XU=0;
                    $sql="UPDATE SEAT SET SOLD='$XU' WHERE SEAT_ID='$actual'";
                    $result=oci_parse($conn,$sql);oci_execute($result);
                }

                echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "wrong password or no balance in the account",
                                    text: "try refilling your account",
                                    type: "error"
                                }, function() {
                                    window.location = "base.php";
                                });
                            }, 50);
                            </script>';
            }

            else
            {
                $sql="SELECT NVL(MAX(BOOKING_ID),0) \"WW\" FROM BOOKING";
                $result=oci_parse($conn,$sql);
                oci_execute($result);$row=oci_fetch_assoc($result);

                $bookid=$row['WW']+1;

                for($i=0;$i<count($updatedSeat);$i++)
                {
                    $XU=$user.'2';$actual=$updatedSeat[$i];
                    $sql="UPDATE SEAT SET SOLD='$XU' WHERE SEAT_ID='$actual'";
                    $result=oci_parse($conn,$sql);oci_execute($result);

                    $sql="INSERT INTO BOOKING VALUES('$bookid','$user',SYSDATE,'$updatedSeat[$i]','$prices[$i]','$tid')";
                    $result=oci_parse($conn,$sql);
                    oci_execute($result);

                    $bookid++;
                }

                $sql="UPDATE BANK SET TAKA=(SELECT TAKA FROM BANK WHERE ACCOUNT_NO='$bank_id')-'$total' 
                      WHERE ACCOUNT_NO='$bank_id'";
                $result=oci_parse($conn,$sql);oci_execute($result);

                echo '<script>
                            setTimeout(function() {
                                swal({
                                    title: "successfully bought",
                                    text: "make sure to come an hour earlier before the train starts",
                                    type: "success"
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
    <title>confirm the tickets</title>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/signup.css" rel="stylesheet">
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Pay</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <input type="text" name="accno" id="accno" class="form-control" placeholder="account no." required>
                        </div>

                        <div class="form-group">
                            <input type="password" name="pass" id="pass" class="form-control" placeholder="pin" required>
                        </div>


                        <div class="form-group" style="margin-top: 50px">
                            <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                                   value="confirm">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>