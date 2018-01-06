<?php
    session_start();
    if (empty($_SESSION['user_in']) || $_SESSION['type'] != 2)
    {
        header('location: base.php');
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

    $user=$_SESSION['user_id'];
    $sql="SELECT * FROM PASSENGER WHERE PASSENGER_ID='$user'";
    $result=oci_parse($conn,$sql);
    oci_execute($result);$row=oci_fetch_assoc($result);

    echo '<div class="container-fluid" style="font-family: \'Comic Sans MS\';font-size: 20px;margin-top: 100px">
            <div class="row" style="margin-top: 10px">
                <div class="col-md-4"></div>
                <div class="col-md-4" align="center">Name: '.$row['FIRST_NAME'].' '.$row['LAST_NAME'].'</div>
            </div>
            <div class="row" style="margin-top: 10px">
                <div class="col-md-4"></div>
                <div class="col-md-4" align="center">Email: '.$row['EMAIL_ID'].'</div>
            </div>
            <div class="row" style="margin-top: 10px">
                <div class="col-md-4"></div>
                <div class="col-md-4" align="center">Phone: '.$row['PHONE'].'</div>
            </div>
            
            <div class="row" style="color: orangered" style="margin-top: 20px">
                <div class="col-md-12" align="center">click on the booking id to download or cancel ticket</div>
            </div>
            
          </div>';


    $sql="SELECT B.BOOKING_ID,B.SEAT_NO,B.PRICE,T1.STARTING,T1.DESTINATION,T2.TRAIN_NAME,T1.TRIP_DATE
          FROM BOOKING B
          JOIN TRIP T1 ON T1.TRIP_ID=B.TRIP_ID
          JOIN TRAIN T2 ON T1.TRAIN_ID=T2.TRAIN_ID
          WHERE PASSENGER_ID='$user'";
    $result=oci_parse($conn,$sql);oci_execute($result);

    echo "<table class=\"table table-hover table-dark\">
        <thead>
        <tr>
            <th scope=\"col\">Booking Id</th>
            <th scope=\"col\">Train</th>
            <th scope=\"col\">Departure</th>
            <th scope=\"col\">Arrival</th>
            <th scope=\"col\">Seat</th>
            <th scope=\"col\">Price</th>
            <th scope=\"col\">Trip Date</th>
        </tr>
        </thead>
        <tbody>";

    while ($row=oci_fetch_assoc($result))
    {
        $link='downloadOrCancel.php?bookingID='.$row['BOOKING_ID'];

        //seat
        $act=$row['SEAT_NO'];$k=1;$seat=0;
        for($i=strlen($act)-1;$i>=0;$i--)
        {
            if($act[$i]=='#')
            {
                if($act[$i-1]=='1')
                    $x='first-class, seat no.- ';
                else if($act[$i-1]=='2')
                    $x='second-class, seat no.- ';
                else
                    $x='third-class, seat no.- ';

                $seat=$x.$seat;break;
            }

            $seat+=($k*$act[$i]);$k*=10;
        }

        echo '<tr><td><a target="_blank" href='.$link.'>'.$row['BOOKING_ID'].'</a></td><td>'.$row['TRAIN_NAME'].
            '</td><td>'.$row['STARTING'].'</td><td>'.$row['DESTINATION'].'</td><td>'.$seat.
            '</td><td>'.$row['PRICE'].'</td><td>'.$row['TRIP_DATE'].'</td></tr>';
    }

    echo '</tbody></table>'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>my upcoming trips</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/complaint_list.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
</head>
<body>
<div class="container">
    <!--NAVBAR-->
    <div class="row" style="margin-bottom: 10%">
        <nav class="navbar fixed-top navbar-light">
            <img src="images/trainLogo.png" style="margin-left: 10px">
            <a href="base.php"
               style="font-size: 17px;font-family: 'Comic Sans MS';color: white;margin-left: 100px">Home</a>
            <a href="destruction.php"
               style="font-size: 17px;font-family: 'Comic Sans MS';color: white;margin-left: 100px">log out</a>
            <p id="tt"
               style="color: white;font-size: 17px;font-family: 'Comic Sans MS';margin-right: 10px;margin-top: 5px">
                date</p>
            <script type="text/javascript">
                dateShower()
            </script>
        </nav>
    </div>
    <!--NAVBAR-->
</div>

</body>
</html>