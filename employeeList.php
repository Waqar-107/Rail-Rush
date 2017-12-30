<?php

    session_start();
    if (empty($_SESSION['user_in']) || $_SESSION['type']==2)
    {
        header('location: base.php');
    }

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if (!$conn) {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //---------------------------------------------------------------add new entry
    echo '<div class="container-fluid" style="margin-top: 100px">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="employeeCreate.php">
                                    <img src="images/add.png"/>Add to Employees</a>
                                </div>
                             
                            </div>
                          </div>';
    //---------------------------------------------------------------add new entry


//---------------------------------------------------------------get the whole table of complain
    $sql = "SELECT EMPLOYEE_ID, (FIRST_NAME||' '||LAST_NAME)\"NM\",EMAIL,PHONE,JOIN_DATE,SALARY,JOB_TYPE
            FROM EMPLOYEE ORDER BY EMPLOYEE_ID";
    $result = oci_parse($conn, $sql);


    if (oci_execute($result))
    {

        echo "<table class=\"table table-hover table-dark\">
            <thead>
            <tr>
                <th scope=\"col\">Employee Id</th>
                <th scope=\"col\">Name</th>
                <th scope=\"col\">Email</th>
                <th scope=\"col\">Phone</th>
                <th scope=\"col\">Joining Date</th>
                <th scope=\"col\">Salary</th>
                <th scope=\"col\">Job Type</th>
            </tr>
            </thead>
            <tbody>";

        while ($row = oci_fetch_assoc($result))
        {
            $link='employeeEdit.php?eid='.$row['EMPLOYEE_ID'];
            echo '<tr><td><a href='.$link.'>'.$row['EMPLOYEE_ID'].'</a></td><td>'.$row['NM'].'</td><td>'.$row['EMAIL'].
                    '</td><td>'.$row['PHONE'].'</td><td>'.$row['JOIN_DATE'].'</td><td>'.$row['SALARY'].'</td><td>'.$row['JOB_TYPE'].'</td></tr>';
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
    <title>list of employees</title>
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