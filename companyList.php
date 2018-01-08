<?php

    session_start();

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //---------------------------------------------------------------valid
    echo '<div class="container-fluid" style="margin-top: 100px">
                                <div class="row">
                                   
                                    <div class="col-md-3">
                                        <a href="#dell" style="color: black;font-size: 20px;font-family: \'Comic Sans MS\'">
                                        <img src="images/dustbin.png"/>update company</a>
                                    </div>
                                 
                                </div>
                              </div>';
    //---------------------------------------------------------------valid


    //---------------------------------------------------------------get the whole table of complain
    $sql = "SELECT * FROM COMPANY ORDER BY COMPANY_ID";
    $result = oci_parse($conn,$sql);


    if (oci_execute($result))
    {

        echo "<table class=\"table table-hover table-dark\">
                <thead>
                <tr>
                    <th scope=\"col\">Id</th>
                    <th scope=\"col\">Name</th>
                    <th scope=\"col\">Email</th>
                    <th scope='\"col\"'>Phone</th>
                    <th scope=\"col\">Validity</th>
                </tr>
                </thead>
                <tbody>";

        while ($row = oci_fetch_assoc($result))
        {
            if($row['VALID'])
                $per='permitted';
            else
                $per='non-permitted';

            echo '<tr><td>'.$row['COMPANY_ID'].'</td><td>'.$row['CNAME'].'</td><td>'.$row['EMAIL_ID'].
                '</td><td>'.$row['PHONE'].'</td><td>'.$per.'</td></tr>';
        }

        echo '</thead></table>';
    }

    if(isset($_POST['submit']))
    {
        $tid=$_POST['dn'];

        $sql="UPDATE COMPANY 
              SET VALID=1-(SELECT VALID FROM COMPANY WHERE COMPANY_ID='$tid') 
              WHERE COMPANY_ID='$tid'";
        $result=oci_parse($conn,$sql);

        if(oci_execute($result))
        {
            echo '<script>
                       setTimeout(function() {
                          swal({
                            title: "successfully updated",
                            text: "",
                            type: "success"
                          }, function() {
                                window.location.href= "admin_base.php";
                             });
                         }, 50);
                     </script>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>company list</title>
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

<div class="container-fluid" style="background-color: rgba(0, 0, 0, 0.7);height: 150px" id="dell">
    <div class="row"></div>
    <form method="post" style="vertical-align: middle">
        <div class="row" style="margin-top: 40px">
            <div class="col-md-3">
                <div class="form-group">
                    <input type="number" id="dn" name="dn" placeholder=" company id" style="vertical-align: middle;float: right" required>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                           value="change validity" style="float: left">
                </div>
            </div>
        </div>
    </form>
</div>

</body>
</html>