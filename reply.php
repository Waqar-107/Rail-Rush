<?php

    session_start();
    if(empty($_SESSION['user_id']))
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
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

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
    //---------------------------------------------------------------get the whole row of complain

    if (isset($_POST['submit']))
    {
        $reply = $_POST['reply'];
        if (empty($reply))
        {
            echo '<script language="javascript">';
            echo 'alert("message is empty")';
            echo '</script>';
        }

        else
        {
            $final_reply = str_replace("\n", "\r\n", $reply);

            //email the reply
            $final_reply = wordwrap($final_reply, 70, "\r\n");

            //database query to get email-id
            $sql="SELECT EMAIL_ID FROM PASSENGER WHERE PASSENGER_ID=$complainant";
            $result = oci_parse($conn,$sql);
            oci_execute($result);

            $row=oci_fetch_assoc($result);
            $mail=$row['EMAIL_ID'];

            $f=0;
            if (mail($mail, "reply to your complain", $final_reply))
            {
                $f=1;
            }

            else
            {
                echo '<script language="javascript">';
                echo 'alert("SORRY!! SOMETHING WENT WRONG, PLEASE TRY AGAIN :(")';
                echo '</script>';
            }

            //update the complaint table
            $final_reply=str_replace("\r\n","@",$final_reply);
            $userId=$_SESSION['user_id'];echo $userId."\r\n";echo $final_reply."\r\n".$complaint_id."\r\n";
            $i=1;

            $sql="UPDATE COMPLAINT 
                  SET RESPONDENT='$userId', REPLY='$final_reply', STATUS='$i'
                  WHERE COMPLAINT_ID='$complaint_id'";
            $result = oci_parse($conn,$sql);

            if(oci_execute($result) && $f)
            {
                oci_commit($conn);oci_close($conn);
                echo '<script language="javascript">';
                echo 'alert("MAIL SENT!");';
                echo 'location="complain_list.php";';
                echo '</script>';
            }

        }
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>reply</title>
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
                <p class="rp" id="complaint_id">complain id: </p>
                <script type="text/javascript">var id = "<?= $complaint_id ?>";
                    document.getElementById("complaint_id").innerHTML = "complain id: " + id;
                </script>
            </div>
        </div>
        <!--COMPLAIN ID-->

        <!--COMPLAIN OF THE COMPLAINANT-->
        <div class="row">
            <div class="col-md-12">
                <p class="rp" id="message">complaint: </p>
                <script type="text/javascript">var text = "<?= $complaint ?>";
                    document.getElementById("message").innerHTML = "complaint: " + text;
                </script>
            </div>
        </div>
        <!--COMPLAIN OF THE COMPLAINANT-->

        <div class="row">
            <div class="col-md-12">
                <form action="" method="post" style="word-wrap: break-word">
                    <!--REPLY OF ADMIN-->
                    <div class="form-group">
                        <textarea id="reply" name="reply" class="form-control" rows="15" placeholder="reply"></textarea>
                    </div>
                    <!--REPLY OF ADMIN-->

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