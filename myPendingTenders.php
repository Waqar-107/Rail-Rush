<?php
    session_start();
    if(empty($_SESSION['user_in']) || $_SESSION['type']!=3)
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

    $cid=$_SESSION['user_id'];

    $sql="SELECT T1.COSTING ,T2.DESCRIPTION
          FROM TENDER_OFFER T1 
          JOIN TENDER_DES T2 ON T1.TENDER_ID=T2.TENDER_ID
          WHERE T1.C_ID='$cid'";

    $result=oci_parse($conn,$sql);
    oci_execute($result);

    echo "<table class=\"table table-hover table-dark\">
            <thead>
            <tr>
                <th scope=\"col\">Offered</th>
                <th scope=\"col\">Tender Description</th>
            </tr>
            </thead>
            <tbody>";
    while ($row=oci_fetch_assoc($result))
    {
        echo '<tr><td>'.$row['COSTING'].'</td><td>'.$row['DESCRIPTION'].'</td></tr>';
    }

    echo "</tbody>
        </table>";

    oci_close($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>tenders bid and pending</title>
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
            <a href="base.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white">Home</a>
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