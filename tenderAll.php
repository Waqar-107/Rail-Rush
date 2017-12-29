<?php

    session_start();
    if(empty($_SESSION['user_in']) || $_SESSION['type']!=1)
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

    //---------------------------------------------------------------all upcoming tenders
    $sql="SELECT * FROM TENDER_HISTORY";
    $result=oci_parse($conn,$sql);
    if(oci_execute($result))
    {
        echo "<table class=\"table table-hover table-dark\">
            <thead>
            <tr>
                <th scope=\"col\">Tender Id</th>
                <th scope=\"col\">Company</th>
                <th scope=\"col\">Email</th>
                <th scope=\"col\">Date</th>
                <th scope=\"col\">Costing</th>
                <th scope=\"col\">Description</th>
            </tr>
            </thead>
            <tbody>";

        while($row=oci_fetch_assoc($result))
        {
            echo '<tr><td>'.$row['TENDER_ID'].'</td><td>'.$row['COMPANY'].'</td><td>'.$row['EMAIL'].'
                    </td><td>'.$row['TDATE'].'</td><td>'.$row['COSTING'].'</td><td>'.$row['WORK_DESCRIPTION'].'</td></tr>';
        }

        echo "</tbody>
            </table>";
    }
    //---------------------------------------------------------------all upcoming tenders

    oci_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>History of All Tenders</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/fuel.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
</head>
<body>

<div class="container">
    <!--NAVBAR-->
    <div class="row" style="margin-bottom: 10%">
        <nav class="navbar fixed-top navbar-light">
            <img src="images/trainLogo.png" style="margin-left: 10px">
            <a href="admin_base.php"
               style="font-size: 17px;font-family: 'Comic Sans MS';color: white">Home</a>
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