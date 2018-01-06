<?php

    $bookingId = $_GET['bookingID'];

    session_start();

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if (!$conn) {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    $sql = "SELECT * FROM BOOKING WHERE BOOKING_ID='$bookingId'";
    $result = oci_parse($conn, $sql);
    oci_execute($result);
    $row = oci_fetch_assoc($result);

    $passengerId = $row['PASSENGER_ID'];
    $tripId = $row['TRIP_ID'];
    $seatId = $row['SEAT_NO'];
    //----------------------------------------------------
    $k = 1;
    $actualSeat = 0;
    for ($i = strlen($seatId) - 1; $i > 0; $i--) {
        if ($seatId[$i] == '#') {
            if ($seatId[$i - 1] == '1')
                $seatId = 'First-Class, ';
            else if ($seatId[$i - 1] == '2')
                $seatId = 'Second-Class, ';
            else
                $seatId = 'Third-Class, ';

            $seatId = $seatId . 'seat no.- ' . $actualSeat;

            break;
        }

        $actualSeat += ($seatId[$i] * $k);
    }
    //----------------------------------------------------
    $price = $row['PRICE'];

    $sql = "SELECT (FIRST_NAME||' '||LAST_NAME) \"NM\" FROM PASSENGER WHERE PASSENGER_ID='$passengerId'";
    $result = oci_parse($conn, $sql);
    oci_execute($result);
    $row = oci_fetch_assoc($result);
    $passengerName = $row['NM'];

    $sql = "SELECT T.STARTING,T.DESTINATION,T.TRIP_DATE,T.TRIP_TIME,TR.TRAIN_NAME
              FROM TRIP T
              JOIN TRAIN TR
              ON T.TRAIN_ID=TR.TRAIN_ID 
              WHERE TRIP_ID='$tripId'";

    $result = oci_parse($conn, $sql);
    oci_execute($result);
    $row = oci_fetch_assoc($result);

    $route = $row['STARTING'] . ' - ' . $row['DESTINATION'];
    $trip_date = $row['TRIP_DATE'];
    $trip_time = $row['TRIP_TIME'];
    $train_name = $row['TRAIN_NAME'];

    echo '<div class="container-fluid" style="font-family: \'Comic Sans MS\';font-size: 20px">
                <div class="row">
                    <div class="col-md-4"><img src="images/old-train.png"></div>
                </div>
                <div class="row" style="margin-top: 50px">
                    <div class="col-md-8">
                        Train: ' . $train_name . '    
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-8">
                        Date: ' . $trip_date . '    
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-8">
                        Time: ' . $trip_time . ' (24-format)    
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-8">
                        Route: ' . $route . '    
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-8">
                        Passenger: ' . $passengerName . '    
                    </div>
                </div>
                <div class="row" style="margin-top: 10px">
                    <div class="col-md-8">
                        Seat: ' . $seatId . '    
                    </div>
                </div>
                 <div class="row" style="margin-top: 10px">
                    <div class="col-md-8">
                        Price: ' . $price . '    
                    </div>
                </div>
             </div>'

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>trip list</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body>

<div class="container-fluid">
    <div class="row" style="margin-top: 20px">
        <div class="col-md-4">
            <button class="btn btn-info btn-lg" id="down" onclick="down()">Download</button>
        </div>
        <div class="col-md-4">
            <button class="btn btn-info btn-lg" id="cancel" onclick="cancel()">Cancel Ticket</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    function down() {
        document.getElementById("down").style.display = 'none';
        document.getElementById("cancel").style.display='none';
        window.print();
    }

    function cancel() {
        var id=<?php echo json_encode($bookingId);?>;
        window.location.href="cancelTicket.php?bookingID="+id;
    }
</script>

</body>
</html>
