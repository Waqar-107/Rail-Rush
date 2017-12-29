<?php

    session_start();
    echo '<script src="js/plotly-latest.min.js" type="text/javascript"></script>';
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';


    if(empty($_SESSION['user_in']) || $_SESSION['type']==2)
    {
        header('location: base.php');
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

    $tid=$_GET['tenderId'];
    $sql="SELECT DESCRIPTION FROM TENDER_DES WHERE TENDER_ID='$tid'";
    $result=oci_parse($conn,$sql);oci_execute($result);$row=oci_fetch_assoc($result);
    $des=$row['DESCRIPTION'];
    //---------------------------------------------tender id

    echo '<div class="container-fluid" style="margin-top: 100px">
                <p style="font-size: 25px;font-family:\'Comic Sans MS\';color: white">'.$tid.': '.$des.'</p>
              </div>';
    //---------------------------------------------tender id


    //---------------------------------------------------------------query and show table
    $sql="SELECT COMPANY,EMAIL1,COSTING FROM TENDER_OFFER WHERE TENDER_ID='$tid'";
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

        //-----------------------------------------------finalize
        //-----------------------------------------------company options
        $companies=array();
        $sql="SELECT COMPANY FROM TENDER_OFFER WHERE TENDER_ID='$tid'";
        $result=oci_parse($conn,$sql);oci_execute($result);
        while($row=oci_fetch_assoc($result))
        {
            array_push($companies,$row['COMPANY']);
        }

        $decider=0;
        //call the procedure
        if(isset($_POST['submit'])=='final')
        {
            if($_POST['mcom']>=1)
            {
                $com = $companies[$_POST['mcom'] - 1];
                $sql = "BEGIN
                            DEAL_WITH_TENDER(:TID,:COM);
                        END;";

                $result = oci_parse($conn, $sql);

                oci_bind_by_name($result, ":TID", $tid, 32);
                oci_bind_by_name($result, ":COM", $com, 32);

                oci_execute($result);

                $decider = 0;

                    echo '<script>
                setTimeout(function() {
                    swal({
                        title: "moved to history!!!",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = "tender.php";
                    });
                }, 50);
                </script>';
            }

            else
            {

                echo '<script>
                setTimeout(function() {
                    swal({
                        title: "nobody has proposed any tender!!!",
                        text: "",
                        type: "error"
                    }, function() {
                        window.location = "tender.php";
                    });
                }, 50);
                </script>';
            }
        }

        if(isset($_POST['submit'])=='delete')
        {
            $sql="DELETE FROM TENDER_DES WHERE TENDER_ID='$tid'";
            $result=oci_parse($conn,$sql);

            if(oci_execute($result))
            {
                    echo '<script>
                setTimeout(function() {
                    swal({
                        title: "deleted!!!",
                        text: "",
                        type: "success"
                    }, function() {
                        window.location = "tender.php";
                    });
                }, 50);
                </script>';
            }

            else
            {
                    echo '<script>
                setTimeout(function() {
                    swal({
                        title: "sorry something went wrong!!!",
                        text: "probably problem with the database",
                        type: "error"
                    }, function() {
                        window.location = "tender.php";
                    });
                }, 50);
                </script>';
            }
        }


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
    <link href="css/tender.css" rel="stylesheet"/>
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
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

<div class="container-fluid" style="margin-top: 100px">

    <form method="post">
        <div class="row">
            <div class="col-md-4">
                <select class="form-control" name="mcom" id="mcom" style="height: 100%">
                    <option value="0">Select a Company</option>
                    <?php
                    for($i=0;$i<count($companies);$i++)
                    {
                        echo '<option value='.($i+1).'>'.$companies[$i].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <button class="btn btn-success btn-lg btn-block" type="submit" id="submit" name="submit" value="final">Finalize</button>
            </div>

            <div class="col-md-4">
                <button class="btn btn-success btn-lg btn-block" type="submit" id="del" name="submit" value="delete">Delete</button>
            </div>
        </div>
    </form>

</div>

<!--footer-->
<div class="container-fluid" id="footer">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-1">
            <img src="images/buet.png" style="float: right">
        </div>
        <div class="col-md-3" style="padding-top: 10px;float: left">&copy Waqar Hassan Khan</div>
    </div>
</div>
<!--footer-->

</body>
</html>