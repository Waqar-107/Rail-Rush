<?php

//---------------------------------------------------------------connect to the database
$server = "localhost";
$username = "root";
$password = "1505107";
$dbname = "phpmyadmin";

//create connection
$conn = mysqli_connect($server, $username, $password, $dbname);

//check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//---------------------------------------------------------------connect to the database


//---------------------------------------------------------------get the whole table of complain
$sql = "SELECT * FROM COMPLAIN";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    echo "<table class=\"table table-hover table-dark\">
    <thead>
    <tr>
        <th scope=\"col\">Complain Id</th>
        <th scope=\"col\">Train Id</th>
        <th scope=\"col\">Complainant</th>
        <th scope=\"col\">Complaint</th>
    </tr>
    </thead>
    <tbody>";

    while ($row = $result->fetch_assoc()){

        $text=$row['MESSAGE'];
        if(strlen($text)<25)
            $text=$text."...(more)";
        else
            $text=substr($text,0,25)."...(more)";

        $linkToReply="reply.php?data=".$row['COMPLAIN_ID'];
        echo "<><td>".$row["COMPLAIN_ID"]."</td><td>".$row["TRAIN_ID"]."</td><td> ".$row["COMPLAINANT_ID"]."</td><td><a href=$linkToReply>".$text."</a></td></tr>";
    }

    echo "</tbody>
</table>";
}
//---------------------------------------------------------------get the whole table of complain

$conn->close();
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