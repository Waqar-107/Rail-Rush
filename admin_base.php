<?php

    session_start();
    if(empty($_SESSION['user_id']))
    {
        header('Location: admin.php');
    }

    //if not admin, send to base
    if($_SESSION['type']!=1)
    {
        header('Location: base.php');
    }

?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <title>admin_base</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/admin_base.css" rel="stylesheet">
    <script type="text/javascript" src="js/showDate.js"></script>
</head>


<body>

<div class="container-fluid">
    <nav class="navbar fixed-top navbar-light">
        <img src="images/trainLogo.png" style="margin-left: 10px">
        <p style="color: white;font-size: 17px;font-family: 'Comic Sans MS';margin-right: 10px;margin-top: 5px">
           <?PHP echo $_SESSION['uname'];?> </p>
        <a href="destruction.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white">log out</a>
        <p id="tt" style="color: white;font-size: 17px;font-family: 'Comic Sans MS';margin-right: 10px;margin-top: 5px">
            date</p>
        <script type="text/javascript">
            dateShower()
        </script>
    </nav>
</div>
<div class="parallax"></div>

<div class="container">

    <!--FIRST ROW-->
    <div class="row" style="margin-top: 100px">
        <div class="col-md-4">
            <div class="card">

                <div class="card-body" onclick="window.location.href='employeeList.php'">
                    <img src="images/employees.png" class="rounded mx-auto d-block" style="margin-top: 15px">
                    <p class="fi">employees</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" onclick="window.location.href='freight.php'">

                <div class="card-body">
                    <img src="images/freight.png" class="rounded mx-auto d-block" style="margin-top: 15px">
                    <p class="fi">freight</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" onclick="window.location.href='complain_list.php';">

                <div class="card-body">
                    <img src="images/complain.png" class="rounded mx-auto d-block" style="margin-top: 15px">
                    <p class="fi">complaints</p>
                </div>
            </div>
        </div>

    </div>
    <!--FIRST ROW-->


    <!--SECOND ROW-->
    <div class="row" style="margin-top: 100px;margin-bottom: 100px">
        <div class="col-md-4">
            <div class="card" onclick="window.location.href='trips.php'">

                <div class="card-body">
                    <img src="images/schedule.png" class="rounded mx-auto d-block"
                         style="margin-top: 15px">
                    <p class="fi">schedule</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">

                <div class="card-body" onclick="window.location.href='trains.php';">
                    <img src="images/train.png" class="rounded mx-auto d-block"
                         style="margin-top: 15px">
                    <p class="fi">trains</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">

                <div class="card-body" onclick="window.location.href='fare.php'">
                    <img src="images/piggy-bank.png" class="rounded mx-auto d-block"
                         style="margin-top: 15px">
                    <p class="fi">fare</p>
                </div>
            </div>
        </div>

    </div>
    <!--SECOND ROW-->
</div>

<div class="parallax"></div>

<!--THIRD ROW-->
<div class="container">
    <div class="row" style="margin-top: 100px;margin-bottom: 100px">
        <div class="col-md-4">
            <div class="card">

                <div class="card-body" onclick="window.location.href='fuel_all.php';">
                    <img src="images/fuel.png" class="rounded mx-auto d-block" style="margin-top: 15px">
                    <p class="fi">fuel</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">

                <div class="card-body" onclick="window.location.href='tender.php'">
                    <img src="images/tender.png" class="rounded mx-auto d-block" style="margin-top: 15px">
                    <p class="fi">tender</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card" onclick="window.location.href='revenue.php'">

                <div class="card-body">
                    <img src="images/revenue.png" class="rounded mx-auto d-block" style="margin-top: 15px">
                    <p class="fi">revenue</p>
                </div>
            </div>
        </div>

    </div>
    <!--THIRD ROW-->
</div>

<!--FOURTH ROW-->
<div class="container">
    <div class="row" style="margin-top: 100px;margin-bottom: 100px">
        <div class="col-md-4">

        </div>

        <div class="col-md-4">
            <div class="card">

                <div class="card-body" onclick="window.location.href='companyList.php'">
                    <img src="images/factory.png" class="rounded mx-auto d-block" style="margin-top: 15px">
                    <p class="fi">company</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">

        </div>

    </div>
    <!--FOURTH ROW-->
</div>

<!--footer-->
<footer class="fixed-bottom" style="position: fixed;height: 35px;bottom: 0;width: 100%;background-color:#2f3436">
    <p style="color: white;padding-top: 5px;padding-left: 10px">&copy CSE, BUET<p>
</footer>
<!--footer-->

</body>

</html>