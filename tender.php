<?PHP

    session_start();
    echo '<script src="js/plotly-latest.min.js" type="text/javascript"></script>';

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //---------------------------------------------------------------add new entry
    echo '<div class="container-fluid" style="margin-top: 100px">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="tenderCreate.php">
                                <img src="images/add.png"/>Add to Tenders</a>
                            </div>
                         
                        </div>
                      </div>';
    //---------------------------------------------------------------add new entry

    //---------------------------------------------------------------history of tenders
    echo '<div class="container-fluid" style="margin-top: 50px">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="tenderAll.php">
                                    <img src="images/helmet.png"/>History</a>
                                </div>
                             
                            </div>
                          </div>';
    //---------------------------------------------------------------history of tenders


    //---------------------------------------------------------------all upcoming tenders
    $sql="SELECT * FROM TENDER_DES";
    $result=oci_parse($conn,$sql);
    if(oci_execute($result))
    {
        echo "<table class=\"table table-hover table-dark\">
        <thead>
        <tr>
            <th scope=\"col\">Tender Id</th>
            <th scope=\"col\">Description</th>
            <th scope=\"col\">Date of Expire</th>
        </tr>
        </thead>
        <tbody>";

        while($row=oci_fetch_assoc($result))
        {
            $tid=$row['TENDER_ID'];

            if($_SESSION['type']==1)
                $link='tenderIndividual.php?tenderId='.$tid;
            else
                $link='tenderOffer.php?tenderId='.$tid;

            echo '<tr><td><a href='.$link.'>'.$tid.'</a></td><td>'.$row['DESCRIPTION'].'</td><td>'.$row['EXP_TIME'].'</td></tr>';
        }

        echo "</tbody>
        </table>";
    }
    //---------------------------------------------------------------all upcoming tenders

    oci_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>tender</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/tender.css" rel="stylesheet"/>
    <script src="js/showDate.js" type="text/javascript"></script>

</head>
<body>

<div class="container-fluid">
    <nav class="navbar fixed-top navbar-light">
        <img src="images/trainLogo.png" style="margin-left: 10px">
        <a href="admin_base.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white">Home</a>
        <a href="destruction.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white";>log out</a>
        <p id="tt" style="color: white;font-size: 17px;font-family: 'Comic Sans MS';margin-right: 10px;margin-top: 5px">
            date</p>
        <script type="text/javascript">
            dateShower()
        </script>
    </nav>
</div>


</body>
</html>