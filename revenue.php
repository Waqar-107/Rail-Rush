<?php
    session_start();

    if (empty($_SESSION['user_in']) || $_SESSION['type'] != 1)
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

    $sql="SELECT RDAY,EARNING 
          FROM REVENUE";

    $result=oci_parse($conn,$sql);
    oci_execute($result);

    $dt=array();$er=array();

    while ($row=oci_fetch_assoc($result))
    {
        array_push($dt,$row['RDAY']);array_push($er,$row['EARNING']);
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>revenue</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="js/showDate.js" type="text/javascript"></script>
    <link href="css/tender.css" rel="stylesheet"/>
    <script src="js/plotly-latest.min.js" type="text/javascript"></script>
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

<div class="container-fluid" style="background-color: transparent">
    <div class="container" style="margin-top: 150px ; width: 95%" id="monthlyEarning"></div>
</div>
<script>
    var x=<?php echo json_encode($dt);?>;
    var y=<?php echo json_encode($er);?>;

    var data=[{
        x:x,y:y,name:'income from ticket',type:'scatter'
    }];

    var layout={
        title:'income from ticket',
        legend:{
            x:0,y:1,traceorder:'normal',
            font:{
                family:'sans-sarif',
                size:15,
                color:'#000'
            },
            bgcolor:'#FFFFFF',
            bordercolor:'#FFFFFF',
            borderwidth:2
        },
        xaxis:{
            title:'Date→'
        },
        yaxis:{
            title:'Earned→'
        }
    };

    Plotly.newPlot('monthlyEarning',data,layout);
</script>

</body>
</html>