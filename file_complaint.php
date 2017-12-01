<?php

    session_start();
    if(empty($_SESSION['user_id']))
    {
        header('Location: login.php');
    }


    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if (!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database


    if (isset($_POST['submit']))
    {
        $complain = $_POST['complain'];
        $trainNo=$_POST['trainNo'];
        $day=$_POST['date'];

        if (empty($complain) || empty($trainNo) || empty($day))
        {
            echo '<script language="javascript">';
            echo 'alert("FIELDS CANNOT BE EMPTY")';
            echo '</script>';
        }

        else
        {
            //get the complain id
            $sql="SELECT MAX(COMPLAINT_ID) FROM COMPLAINT";
            $result=oci_parse($conn,$sql);
            $complain=str_replace("\r\n","@",$complain);

            if (oci_execute($result))
            {
                $row=oci_fetch_assoc($result);

                $complainant=$_SESSION['user_id'];
                $newId=$row['MAX(COMPLAINT_ID)']+1;

                $sql="INSERT INTO COMPLAINT(COMPLAINT_ID,COMPLAINANT,TRAIN_ID,MESSAGE,TRIP_DATE) VALUES('$newId','$complainant','$trainNo','$complain',TO_DATE('$day','DD/MM/YYYY'))";
                $result=oci_parse($conn,$sql);

                if(oci_execute($result))
                {
                    oci_commit($conn);oci_close($conn);
                    echo '<script language="javascript">';
                    echo 'alert("THANKS FOR THE FEEDBACK.");';
                    echo 'location="base.php";';
                    echo '</script>';
                }

                else
                {
                    echo oci_error($conn);
                }
            }

            else
            {
                echo '<script language="javascript">';
                echo 'alert("SORRY!! SOMETHING WENT WRONG, PLEASE TRY AGAIN :(")';
                echo '</script>';
            }

        }
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>file_complaint</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/reply.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
</head>

<body>

<div class="container-fluid">

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

        <!--COMPLAIN OF THE COMPLAINANT-->
        <div class="row">
            <div class="col-md-12">
                <p class="rp" id="message">complaint: </p>
            </div>
        </div>
        <!--COMPLAIN OF THE COMPLAINANT-->

        <div class="row">
            <div class="col-md-12">
                <form action="" method="post" style="word-wrap: break-word">

                    <div class="form-group">
                        <input type="text" id="date" name="date" class="form-group" style="padding-left: 5px" placeholder="in dd/mm/yyyy format">
                    </div>

                    <!--train number-->
                    <div class="form-group">
                        <textarea id="trainNo" name="trainNo" class="form-control" rows="2"
                                  placeholder="train no."></textarea>
                    </div>
                    <!--train number-->

                    <!--complain-->
                    <div class="form-group">
                        <textarea id="complain" name="complain" class="form-control" rows="15"
                                  placeholder="feedback"></textarea>
                    </div>
                    <!--complain-->

                    <!--SEND BUTTON-->
                    <div class="form-group" style="margin-top: 50px">
                        <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                               value="send" style="margin-bottom: 75px">
                    </div>
                    <!--SEND BUTTON-->

                </form>
            </div>
        </div>
    </div>


</div>

</body>
</html>