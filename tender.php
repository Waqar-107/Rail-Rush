<?PHP

    session_start();
    echo '<script src="js/plotly-latest.min.js" type="text/javascript"></script>';

    if(empty($_SESSION['user_in']))
    {
        header('location: base.php');
    }

    //if not admin, send to base
    if($_SESSION['type']==2)
    {
        header('Location: base.php');
    }

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
                                <img src="images/add.png"/> add to tenders</a>
                            </div>
                         
                        </div>
                      </div>';
    //---------------------------------------------------------------add new entry

    //---------------------------------------------------------------data of graph 2
    $sql="SELECT TO_CHAR(TDATE,'YYYY-MM-DD'),SUM(COSTING)
          FROM TENDER
          GROUP BY TDATE
          ORDER BY TDATE";

    $result=oci_parse($conn,$sql);
    oci_execute($result);
    $mtime=array();$cost=array();
    while($row=oci_fetch_assoc($result))
    {
        array_push($mtime,$row['TO_CHAR(TDATE,\'YYYY-MM-DD\')']);array_push($cost,$row['SUM(COSTING)']);
    }
    //---------------------------------------------------------------data of graph 2

    //---------------------------------------------------------------data of graph 2
    $sql="SELECT COMPANY, SUM(COSTING) FROM TENDER
          GROUP BY COMPANY";
    $result=oci_parse($conn,$sql);oci_execute($result);
    $com=array();$comPrice=array();
    while($row=oci_fetch_assoc($result))
    {
        array_push($com,$row['COMPANY']);array_push($comPrice,$row['SUM(COSTING)']);
    }
    //---------------------------------------------------------------data of graph 2

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

<div class="container-fluid" style="background-color: whitesmoke">
    <div class="container" style="margin-top: 150px;width: 95%" id="timeCost"></div>
</div>
<script type="text/javascript">
    var x=<?php echo json_encode($mtime);?>;
    var y=<?php echo json_encode($cost);?>;
    var data=[{
        x:x,
        y:y,
        name:'time-cost',
        type:'scatter'}
    ];

    var layout={
        title:'Date-Cost',
        xaxis:{
            title:'Date→',

        },

        yaxis:{
            title:'Cost→'
        }
    };

    Plotly.newPlot('timeCost',data,layout);
</script>

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