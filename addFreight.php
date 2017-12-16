<?php

    session_start();
    if (empty($_SESSION['user_id']))
    {
        header('Location: base.php');
    }

    //if not admin, send to base
    if($_SESSION['type']==2)
    {
            header('Location: base.php');
    }

    if (isset($_POST['submit']))
    {
        $trainNo = $_POST['trainNo'];
        $tno = $_POST['tno'];
        $company = $_POST['company'];
        $weight = $_POST['weight'];
        $inside = $_POST['inside'];
        $trip_date = $_POST['trip_date'];

    if (empty($trainNo) || empty($tno) || empty($company) || empty($weight) || empty($inside) || empty($trip_date))
    {
        echo '<script language="javascript">';
        echo 'alert("PLEASE FILL ALL THE INFORMATIONS!!!");';
        echo '</script>';
    }

    else
    {
        //---------------------------------------------------------------connect to the database
        //create connection
        $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');
        //check connection
        if (!$conn)
        {
            echo 'connection error';
        }
        //---------------------------------------------------------------connect to the database

        $sql="SELECT NVL(MAX(FREIGHT_ID),0) FROM FREIGHT";
        $result=oci_parse($conn,$sql);
        oci_execute($result);
        $row=oci_fetch_assoc($result);

        $id=$row['NVL(MAX(FREIGHT_ID),0)']+1;

        $sql="INSERT INTO FREIGHT VALUES('$id','$trainNo','$tno','$company','$weight','$inside',0,TO_DATE('$trip_date','YYYY-MM-DD'))";
        $result=oci_parse($conn,$sql);

        if(oci_execute($result))
        {
            echo '<script language="javascript">';
            echo 'alert("freight added.");';
            echo 'location="freight.php";';
            echo '</script>';
        }

        else
        {
            echo '<script language="javascript">';
            echo 'alert("something went wrong :(");';
            echo 'location="addfreight.php";';
            echo '</script>';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>add freight</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/addFreight.css" rel="stylesheet">
    <script type="text/javascript" src="js/showDate.js"></script>
</head>
<body>

<div class="container-fluid">
    <nav class="navbar fixed-top navbar-light">
        <img src="images/trainLogo.png" style="margin-left: 10px">
        <a href="destruction.php" style="font-size: 17px;font-family: 'Comic Sans MS';color: white;margin-left: 150px">log out</a>
        <p id="tt" style="color: white;font-size: 17px;font-family: 'Comic Sans MS';margin-right: 10px;margin-top: 5px">
            date</p>
        <script type="text/javascript">
            dateShower()
        </script>
    </nav>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Add Freight</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <input type="text" name="trainNo" id="trainNo" class="form-control" placeholder="train no.">
                        </div>

                        <div class="form-group">
                            <input type="text" name="tno" id="tno" class="form-control" placeholder="trailer no.">
                        </div>

                        <div class="form-group">
                            <input type="text" name="company" id="company" class="form-control" placeholder="company">
                        </div>

                        <div class="form-group">
                            <input type="text" name="weight" id="weight" class="form-control" placeholder="weight">
                        </div>

                        <div class="form-group">
                            <input type="text" name="inside" id="inside" class="form-control"
                                   placeholder="inside">
                         </div>

                        <div class="form-group">
                            <input type="date" name="trip_date" id="trip_date" class="form-control"
                                   placeholder="trip date">
                        </div>

                        <div class="form-group" style="margin-top: 50px">
                            <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                                   value="add">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>