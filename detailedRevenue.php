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

    $yst=$_GET['year'].'-01-01';
    $end=$_GET['year'].'-12-31';

    echo '<div class="col-md-12" align="center" style="font-family: \'Comic Sans MS\';font-size: 25px;color: black;margin-top: 100px">'.$_GET['year'].'</div>';
    $sql = "SELECT R.TRAIN_ID,SUM(R.EARNING) \"WW\", TR.TRAIN_NAME,MIN(R.EARNING) \"MN\", MAX(R.EARNING) \"MX\", ROUND(AVG(R.EARNING),2) \"AV\"
            FROM REVENUE R
            JOIN TRAIN TR ON TR.TRAIN_ID=R.TRAIN_ID
            WHERE TO_DATE(R.RDAY,'YYYY-MM-DD')>=TO_DATE('$yst','YYYY-MM-DD') AND 
            TO_DATE(R.RDAY,'YYYY-MM-DD')<=TO_DATE('$end','YYYY-MM-DD')
            GROUP BY R.TRAIN_ID,TR.TRAIN_NAME
            ORDER BY R.TRAIN_ID";


    $result=oci_parse($conn,$sql);
    oci_execute($result);

    $tr_cur=array();$er_cur=array();
    $total_curr=0;

    echo "<table class=\"table table-hover table-dark\">
            <thead>
            <tr>
                <th scope=\"col\">Train Id</th>
                <th scope=\"col\">Train Name</th>
                <th scope=\"col\">Total</th>
                <th scope=\"col\">Min</th>
                <th scope=\"col\">Max</th>
                <th scope=\"col\">Avg</th>
            </tr>
            </thead>
            <tbody>";

    while ($row=oci_fetch_assoc($result))
    {
        array_push($tr_cur,$row['TRAIN_ID'].'-'.$row['TRAIN_NAME']);array_push($er_cur,$row['WW']);
        $total_curr+=$row['WW'];

        echo '<tr><td>'.$row['TRAIN_ID'].'</td><td>'.$row['TRAIN_NAME'].'</td><td>'.$row['WW'].'</td><td>'.
            $row['MN'].'</td><td>'.$row['MX'].'</td><td>'.$row['AV'].'</td></tr>';
    }

    echo "</tbody>
            </table>";

    echo '<div class="container-fluid" style="font-family: \'Comic Sans MS\';font-size: 25px;color: black;margin-top: 100px">
                <div class="row">
                <div class="col-md-12" align="center">Total Earned this year= ' . $total_curr . '/tk </div></div>
             
                <div class="row">
                <div class="col-md-12" align="center">The Graphs Shows earning of Each Train</div></div>
             </div>';

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

<!--YEAR-->
<div class="container-fluid" style="background-color: transparent">
    <div class="container" style="margin-top: 150px ; width: 95%" id="earnedCurrYear"></div>
</div>
<div class="container-fluid" style="background-color: transparent">
    <div class="container" style="margin-top: 150px ; width: 95%" id="earnedCurrYearPI"></div>
</div>
<script>
    var x=<?php echo json_encode($tr_cur);?>;
    var y=<?php echo json_encode($er_cur);?>;

    var data=[{
        x:x,y:y,name:'income from ticket in current year',type:'bar'
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
            title:'Train Id→'
        },
        yaxis:{
            title:'Earned→'
        }
    };

    Plotly.newPlot('earnedCurrYear',data,layout);
</script>

<div class="container-fluid" style="margin-top: 100px;background-color: whitesmoke">
    <div class="container" style="width: 95%" id="two"></div>
    <script type="text/javascript">
        var values=<?php echo json_encode($er_cur);?>;
        var labels=<?php echo json_encode($tr_cur);?>;
        var data=[{
            values:values,labels:labels,type:'pie'
        }];
        var layout={
            height:600,width:800
        }
        Plotly.newPlot('earnedCurrYearPI',data,layout);
    </script>
</div>
<!--YEAR-->

</body>
</html>