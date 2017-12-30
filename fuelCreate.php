<?PHP

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if(empty($_SESSION['user_in']) || $_SESSION['type']==2)
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

    //---------------------------------------------------------------select options
    $fillers=array();$trains=array();
    $sql="SELECT DISTINCT(TRAIN_ID) FROM TRAIN";
    $result=oci_parse($conn,$sql);
    oci_execute($result);
    while ($row=oci_fetch_assoc($result))
    {
        array_push($trains,$row['TRAIN_ID']);
    }

    $sql="SELECT EMPLOYEE_ID FROM EMPLOYEE WHERE JOB_TYPE='FILLER'";
    $result=oci_parse($conn,$sql);
    oci_execute($result);
    while ($row=oci_fetch_assoc($result))
    {
        array_push($fillers,$row['EMPLOYEE_ID']);
    }
    //---------------------------------------------------------------select options

    if(isset($_POST['submit']))
    {
        $tid=$trains[$_POST['tid']-1];$eid=$fillers[$_POST['eid']-1];$quant=$_POST['quant'];
        $price=$_POST['price']*$quant;

        $sql="INSERT INTO FUEL VALUES(SYSDATE,'$eid','$tid','$quant','$price')";
        $result=oci_parse($conn,$sql);

        if(oci_execute($result))
        {
            echo '<script>
                 setTimeout(function() {
                      swal({
                        title: "successfully registered",
                        text: "keep running :(",
                        type: "success"
                      }, function() {
                             window.location = "fuel_all.php";
                           });
                 }, 50);
            </script>';
        }

        else
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("sorry something went wrong","probably database connection problem :(","error");';
            echo '}, 50);</script>';
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>create fuel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/signup.css" rel="stylesheet">
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Register Fuel History</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <select class="form-control" name="eid" id="eid" required>
                                <option value="0">re-fueler</option>
                                <?php
                                for($i=0;$i<count($fillers);$i++)
                                {
                                    echo '<option value='.($i+1).'>'.$fillers[$i].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="tid" id="tid" required>
                                <option value="0">train-id</option>
                                <?php
                                for($i=0;$i<count($trains);$i++)
                                {
                                    echo '<option value='.($i+1).'>'.$trains[$i].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <input type="number" name="quant" id="quant" class="form-control" placeholder="quantiti in L" required>
                        </div>

                        <div class="form-group">
                            <input type="text" name="price" id="price" class="form-control" placeholder="price per unit" required>
                        </div>

                        <div class="form-group" style="margin-top: 50px">
                            <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                                   value="create">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>