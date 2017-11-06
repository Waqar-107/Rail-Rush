<?php


    if(isset($_POST['submit']))
    {
        $reply=$_POST['reply'];
        if(empty($reply))
        {
            echo '<script language="javascript">';
            echo 'alert("message is empty")';
            echo '</script>';
        }

        else
        {
            $final_reply = str_replace("\n","\r\n",$reply);

            //email the reply
            $final_reply = wordwrap($final_reply, 70, "\r\n");

            //database query to get email-id
            $mail="waqar.hassan866@gmail.com";

            if (mail($mail, "reply to your complain", $final_reply))
            {
                echo '<script language="javascript">';
                echo 'alert("message sent!")';
                echo '</script>';

            }

            else
            {
                echo '<script language="javascript">';
                echo 'alert("sorry! something went wrong. please try again.")';
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
    <script src="js/reply.js" type="text/javascript"></script>
</head>

<body>

<div id="main_panel" class="container-fluid">

    <div class="container" style="margin-top: auto">

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

        <!--COMPLAIN ID-->
        <div class="row">
            <div class="col-md-2">
                <p class="rp" id="complaint_id">complain id: </p>
                <script type="text/javascript">updateId()</script>
            </div>
        </div>
        <!--COMPLAIN ID-->

        <!--COMPLAIN OF THE COMPLAINANT-->
        <div class="row">
            <div class="col-md-12">
                <p class="rp" id="message">complain: </p>
                <script type="text/javascript">updateMessage()</script>
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