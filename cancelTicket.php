<?php
    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if(empty($_SESSION['user_in']) || $_SESSION['type']!=2)
    {
        header('Location: base.php');
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

    if(isset($_POST['submit']))
    {
        $bookingID=$_GET['bookingID'];
        $acc=$_POST['acc'];

        $sql="SELECT B.PRICE,B.SEAT_NO,TO_CHAR(B.BDATE,'DD-MM-YYYY')
              FROM BOOKING B
              WHERE BOOKING_ID='$bookingID'";
        $result=oci_parse($conn,$sql);oci_execute($result);
        $row=oci_fetch_assoc($result);

        $refund=$row['PRICE']/2;$seat=$row['SEAT_NO'];
        $dt=$row['TO_CHAR(B.BDATE,\'DD-MM-YYYY\')'];

        //echo $refund.'  '.$seat.'  '.$dt.'</br>';

        $sql="UPDATE SEAT SET SOLD='0' WHERE SEAT_ID='$seat'";
        $result1=oci_parse($conn,$sql);

        $sql2="UPDATE BANK 
               SET TAKA=(SELECT TAKA FROM BANK WHERE ACCOUNT_NO='$acc')+'$refund' 
               WHERE ACCOUNT_NO='$acc'";
        $result2=oci_parse($conn,$sql2);

        $sql3="UPDATE REVENUE 
               SET EARNING=(SELECT EARNING FROM REVENUE WHERE RDAY='$dt')-'$refund'
               WHERE RDAY='$dt'";
        $result3=oci_parse($conn,$sql3);

        $sql4="DELETE FROM BOOKING WHERE BOOKING_ID='$bookingID'";
        $result4=oci_parse($conn,$sql4);

        if(oci_execute($result1))
        {
            if(oci_execute($result2))
            {
                if(oci_execute($result3) && oci_execute($result4))
                {
                    echo '<script type="text/javascript">';
                    echo 'setTimeout(function () { swal("refunded!!!","we will look forward for your booking :)","success");';
                    echo '}, 50);</script>';
                    echo '<script type="text/javascript">window.location.href="cancelTicket.php?bookingID=-1"</script>';
                }
            }

        }

        else
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("sorry something went wrong !!!","please try again","error");';
            echo '}, 50);</script>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>cancel ticket</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/signup.css" rel="stylesheet">
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid" style="margin-top: 100px">
    <div class="row" style="color: black;margin-bottom: 20px;font-size: 25px;font-family: 'Comic Sans MS'">
        <div class="col-md-12" align="center">you will get back 50% of the ticket price</div></div>
    <form method="post">
        <div class="form-group">
            <input type="text" name="acc" id="acc" class="form-control" placeholder="account no." required>
        </div>

        <div class="form-group" style="margin-top: 50px">
            <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                   value="confirm cancellation">
        </div>
    </form>
</div>

</body>
</html>