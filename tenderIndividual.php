<?php

    session_start();
    echo '<script src="js/plotly-latest.min.js" type="text/javascript"></script>';

    if(empty($_SESSION['user_in']) || $_SESSION['type']==2)
    {
        header('location: base.php');
    }

    //---------------------------------------------tender id
    $tid=$_GET['tenderId'];
    echo '<div class="container-fluid">
            <p>'.$tid.'</p>
          </div>';
    //---------------------------------------------tender id

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //---------------------------------------------------------------query and show table
    $sql="SELECT COMPANY,EMAIL1,COSTING FROM TENDER";
    $result=oci_parse($conn,$sql);

    $com=array();$comPrice=array();
    if(oci_execute($result))
    {
        echo "<table class=\"table table-hover table-dark\">
        <thead>
        <tr>
            <th scope=\"col\">Company</th>
            <th scope=\"col\">Email</th>
            <th scope=\"col\">Costing</th>
        </tr>
        </thead>
        <tbody>";
        while ($row = oci_fetch_assoc($result))
        {
            array_push($com,$row['COMPANY']);array_push($comPrice,$row['COSTING']);
            echo '<tr><td>'.$row['COMPANY'].'</td><td>'.$row['EMAIL1'].'</td><td>'.$row['COSTING'].'</td></tr>';
        }
        echo "</tbody>
        </table>";
    }

    else
    {
        echo '<script>
                     setTimeout(function() {
                        swal({
                         title: "sorry something went wrong!!!",
                         text: "probably problem in the database :(",
                         type: "error"
                        }, function() {
                             window.location = "tender.php";
                               });
                     }, 50);
                  </script>';
    }

    //---------------------------------------------------------------query and show table
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>individual tender with graphs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
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

<div class="container-fluid" style="background-color: whitesmoke">
    <div class="container" style="margin-top: 150px ; width: 95%" id="comCost"></div>
</div>
<script>
    var x=<?php echo json_encode($com);?>;
    var y=<?php echo json_encode($comPrice);?>;
    var trace={
        x:x,y:y,type:'bar'
    };

    var layout={
        title:'Company-Cost',
        xaxis:{
            title:'Company→',

        },

        yaxis:{
            title:'Cost→'
        }
    };

    var data=[trace];
    Plotly.newPlot('comCost',data,layout);
</script>
</body>
</html>