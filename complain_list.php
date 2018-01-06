<?php

    session_start();
    if(empty($_SESSION['user_in']) || $_SESSION['type']==2)
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


    //---------------------------------------------------------------get the whole table of complain
    $sql = "SELECT C.COMPLAINT_ID,(P.FIRST_NAME||' '||P.LAST_NAME) \"CM\",TR.TRAIN_NAME,
            C.RESPONDENT,C.STATUS,C.REPLY,C.TRIP_DATE,C.MESSAGE
            FROM COMPLAINT C
            JOIN  PASSENGER P ON P.PASSENGER_ID=C.COMPLAINANT
            JOIN TRAIN TR ON TR.TRAIN_ID=C.TRAIN_ID";
    $result = oci_parse($conn,$sql);


    if (oci_execute($result))
    {

        echo "<table class=\"table table-hover table-dark\">
        <thead>
        <tr>
            <th scope=\"col\">Complain Id</th>
            <th scope=\"col\">Train</th>
            <th scope=\"col\">Complainant</th>
            <th scope=\"col\">Complaint</th>
            <th scope=\"col\">Respondent</th>
            <th scope=\"col\">Reply</th>
            <th scope=\"col\">Trip Date</th>
        </tr>
        </thead>
        <tbody>";

        while ($row = oci_fetch_assoc($result))
        {
            $status=$row['STATUS'];
            $tripDate=$row['TRIP_DATE'];

            if($status)
            {
                $text = $row['MESSAGE'];
                $text=substr($text, 0, 50);

                $rep = $row['REPLY'];
                if (strlen($rep) < 20)
                    $rep = $rep . "...(more)";
                else
                    $rep = substr($rep, 0, 20) . "...(more)";

                $linkToReadReply = "readReply.php?data=" . $row['COMPLAINT_ID'];
                echo '<tr><td>' . $row["COMPLAINT_ID"] . '</td><td>' . $row["TRAIN_NAME"] .
                    '</td><td> ' . $row["CM"] . '</td><td>'.$text.'<td>'.$row["RESPONDENT"].'
                    </td><td><a href='.$linkToReadReply.'>'.$rep.'</a></td><td>'.$tripDate.'</td></tr>';

            }

            else
            {
                $text = $row['MESSAGE'];
                if (strlen($text) < 20)
                    $text = $text . "...(more)";
                else
                    $text = substr($text, 0, 20) . "...(more)";

                $linkToReply = "reply.php?data=" . $row['COMPLAINT_ID'];
                echo "<tr><td>" . $row["COMPLAINT_ID"] . "</td><td>" . $row["TRAIN_ID"] . "</td><td> " .
                    $row["CM"] . "</td><td><a href=$linkToReply>" . $text . "
                    </a></td><td>null</td><td>null</td><td>".$tripDate."</td></tr>";

            }


        }

        echo "</tbody>
    </table>";
    }
    //---------------------------------------------------------------get the whole table of complain

    oci_close($conn);
?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <title>list of complain</title>
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
            <a href="admin_base.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white">Home</a>
            <a href="destruction.php"
               style="font-size: 17px;font-family: 'Comic Sans MS';color: white">log out</a>
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