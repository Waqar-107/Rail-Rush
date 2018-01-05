<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    $data1=$_GET['data1'];
    $tid=$_GET['tid'];
    $train_id=$_GET['train_id'];

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
    for($i=0;$i<count($seatId)-1;$i++)
    {
        $sid=0;$k=1;
        for($j=strlen($seatId[$i])-1;$j>1;$j--)
        {
            $sid+=($seatId[$i][$j]*$k);$k*=10;
        }

        if($seatId[$i][$j]=='F')
            $x=1;
        else if($seatId[$i][0]=='S')
            $x=2;
        else
            $x=3;

        $actual=$Date.'#'.$train_id.'#'.$x.'#'.$sid;


    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>confirm the tickets</title>
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/buyTicket.css" rel="stylesheet">
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
</head>
<body>

<div class="container">
    <!--NAVBAR-->
    <div class="row" style="margin-bottom: 10%">
        <nav class="navbar fixed-top navbar-light">
            <img src="images/trainLogo.png" style="margin-left: 10px">
            <a href="admin_base.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white">Home</a>
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