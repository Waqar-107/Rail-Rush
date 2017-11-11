<?php

    session_start();
    if(empty($_SESSION['user_in']))
    {
        header('location: base.php');
    }

    //---------------------------------------------------------------get the complain id from previous page
    $complaint_id;
    $complaint;
    if (isset($_GET["data"])) {
        $complaint_id = $_GET["data"];
    }
    //---------------------------------------------------------------get the complain id from previous page


    //---------------------------------------------------------------connect to the database
    $server = "localhost/orcl";
    $username = "HR";
    $password = "hr";

    //create connection
    $conn = oci_connect('HR', 'hr', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database


    //---------------------------------------------------------------get the whole row of complain
    $sql = "SELECT * FROM COMPLAINT WHERE COMPLAINT_ID=$complaint_id";

    $result = oci_parse($conn,$sql);
    oci_execute($result);
    $row=oci_fetch_assoc($result);

    $complaint = $row['MESSAGE'];$complainant=$row['COMPLAINANT'];
    $reply=$row['REPLY'];

    //---------------------------------------------------------------get the whole row of complain

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>readReply</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/reply.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
</head>

<body>

<div id="main_panel" class="container-fluid">

    <div class="container" style="margin-top: auto">

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

        <!--COMPLAIN ID-->
        <div class="row">
            <div class="col-md-2">
                <p class="rpEx" id="complaint_id">complain id: </p>
                <script type="text/javascript">var id = "<?= $complaint_id ?>";
                    document.getElementById("complaint_id").innerHTML = "complaint id: " + id;
                </script>
            </div>
        </div>
        <!--COMPLAIN ID-->

        <!--COMPLAIN OF THE COMPLAINANT-->
        <div class="row">
            <div class="col-md-12">
                <p class="rpEx" id="message">complaint: </p>
                <script type="text/javascript">var text = "<?= $complaint ?>";
                    document.getElementById("message").innerHTML = "complaint: " + text;
                </script>
            </div>
        </div>
        <!--COMPLAIN OF THE COMPLAINANT-->

        <!--reply-->
        <div class="row">
            <div class="col-md-12">
                <p class="rpEx" id="reply">reply: </p>
                <script type="text/javascript">var rep = "<?= $reply ?>";
                    rep = rep.replace(/@/gi,"\n");
                    document.getElementById("reply").innerHTML = "reply: " + rep;
                </script>
            </div>
        </div>
        <!--reply-->

    </div>


</div>

</body>
</html>