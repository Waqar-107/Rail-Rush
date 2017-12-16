<?php
    session_start();
    if(empty($_SESSION['user_in']))
    {
        header('location: base.php');
    }

    //---------------------------------------------------------------add new entry
    echo '<div class="container-fluid" style="margin-top: 100px">
                <div class="row">
                    <div class="col-md-1">
                        <a href="base.php">
                        <img src="images/add.png"/> add</a>
                    </div>
                 
                </div>
              </div>';
    //---------------------------------------------------------------add new entry


    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //---------------------------------------------------------------get the whole table of freight
    $sql='SELECT T.TRAIN_ID,T.TRAIN_NAME,(E.FIRST_NAME||\' \'|| E.LAST_NAME) "NAME",T.COMPARTMENT,T.FIRST_CLASS,T.SECOND_CLASS,T.THIRD_CLASS,T.CARGO
          FROM TRAIN T
          JOIN EMPLOYEE E
          ON T.EMPLOYEE_ID=E.EMPLOYEE_ID
          ORDER BY T.TRAIN_ID';
    $result=oci_parse($conn,$sql);

    if(oci_execute($result))
    {
        echo "<table class=\"table table-hover table-dark\" style='margin-top: 50px'>
            <thead>
            <tr>
                <th scope=\"col\">Train Id</th>
                <th scope=\"col\">Train Name</th>
                <th scope=\"col\">Driver</th>
                <th scope=\"col\">Compartments</th>
                <th scope=\"col\">First Class</th>
                <th scope=\"col\">Second Class</th>
                <th scope=\"col\">Third Class</th>
                <th scope=\"col\">Cargo</th>
            </tr>
            </thead>
            <tbody>";

        while ($row = oci_fetch_assoc($result))
        {

            echo '<tr><td>'.$row['TRAIN_ID'].'</td><td>'.$row['TRAIN_NAME'].'</td><td>'.$row['NAME'].
                '</td><td>'.$row['COMPARTMENT'].'</td><td>'.$row['FIRST_CLASS'].'</td><td>'.$row['SECOND_CLASS'].
                '</td><td>'.$row['THIRD_CLASS'].'</td><td>'.$row['CARGO'].'</td></tr>';
        }

        echo "</tbody>
        </table>";
    }
    //---------------------------------------------------------------get the whole table of freight

    oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>trains</title>
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
            <a href="destruction.php"
               style="font-size: 17px;font-family: 'Comic Sans MS';color: white;margin-left: 150px">log out</a>
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