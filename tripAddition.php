<?php
    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

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

    //trains
    $trains=array();
    $sql="SELECT TRAIN_ID FROM TRAIN";
    $result=oci_parse($conn,$sql);
    oci_execute($result);

    while($row=oci_fetch_assoc($result))
    {
        array_push($trains,$row['TRAIN_ID']);
    }

    if(isset($_POST['submit']))
    {
        //check if date is valid
        $h=$_POST['hid'];$m=$_POST['mid'];
        if($_POST['hid']<=9)
            $h='0'.$_POST['hid'];

        if($_POST['mid']<=9)
            $m='0'.$_POST['mid'];

        $ETIME=$h.':'.$m;
        $tdate=$_POST['tdate'];$train=$trains[$_POST['tid']-1];
        $decider=0;

        $sql="BEGIN
                :X := VALID_TRIP(:TDATE,:T_NO);
              END;";

        $result=oci_parse($conn,$sql);

        oci_bind_by_name($result,":TDATE",$tdate,32);
        oci_bind_by_name($result,":T_NO",$train,32);
        oci_bind_by_name($result,":X",$decider,32);

        oci_execute($result);echo $decider;

        //decide if valid
        if(!$decider)
        {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("invalid date","check the date :(","error");';
            echo '}, 50);</script>';
        }

        else
        {

            $sql="SELECT DEPARTURE,ARRIVAL,EMPLOYEE_ID FROM TRAIN WHERE TRAIN_ID='$train'";
            $result=oci_parse($conn,$sql);
            oci_execute($result);$row=oci_fetch_assoc($result);

            $decider=1;
            if($row['EMPLOYEE_ID'])
            {
                $st=$row['DEPARTURE'];$en=$row['ARRIVAL'];
            }

            else
            {
                $decider=0;
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal("this train do not have a driver currently","assign a driver first :(","error");';
                echo '}, 50);</script>';
            }

            $sql="SELECT NVL(MAX(CALC_ID),1) \"WW\" FROM TRIP";
            $result=oci_parse($conn,$sql);oci_execute($result);$row=oci_fetch_assoc($result);
            $calcId=$row['WW']+1;

            if($decider)
            {
               $sql="SELECT NVL(MAX(TRIP_ID),100) \"WW\" FROM TRIP WHERE TRAIN_ID='$train'";
               $result=oci_parse($conn,$sql);
               oci_execute($result);$row=oci_fetch_assoc($result);

               if($row['WW']%2)
               {
                   //return trip
                   $newId=$row['WW']+1;
                   $sql="INSERT INTO TRIP VALUES ('$calcId','$newId','$train',TO_DATE('$tdate','YYYY-MM-DD'),'$ETIME','$en','$st')";
                   $result=oci_parse($conn,$sql);
                   if(oci_execute($result))
                   {
                           echo '<script>
                         setTimeout(function() {
                            swal({
                             title: "successfully registered",
                             text: "",
                             type: "success"
                            }, function() {
                                 window.location = "trips.php";
                                   });
                         }, 50);
                      </script>';
                   }

                   else
                   {
                       echo '<script type="text/javascript">';
                       echo 'setTimeout(function () { swal("sorry something went wrong!!!!","probably problem in the database :(","error");';
                       echo '}, 50);</script>';
                   }
               }

               else
               {
                   $sql="SELECT NVL(MAX(TRIP_ID),0) \"ww\" FROM TRIP";
                   $result=oci_parse($conn,$sql);
                   oci_execute($result);$row=oci_fetch_assoc($result);

                   $newId=$row['ww'];
                   if($newId%2)
                       $newId+=2;
                   else
                       $newId++;

                   $sql="INSERT INTO TRIP VALUES ('$calcId','$newId','$train',TO_DATE('$tdate','YYYY-MM-DD'),'$ETIME','$st','$en')";
                   $result=oci_parse($conn,$sql);

                   $sql2="BEGIN
                            ADD_RETURN_TRIP(:TID);
                          END;";
                   $result2=oci_parse($conn,$sql2);
                   oci_bind_by_name($result2,":TID",$newId,32);

                   if(oci_execute($result))
                   {
                       if(oci_execute($result2))
                       {
                               echo '<script>
                             setTimeout(function() {
                                swal({
                                 title: "successfully registered",
                                 text: "",
                                 type: "success"
                                }, function() {
                                     window.location = "trips.php";
                                       });
                             }, 50);
                          </script>';
                       }

                       else
                       {
                           echo '<script type="text/javascript">';
                           echo 'setTimeout(function () { swal("sorry something went wrong!!!!","probably problem in the database :(","error");';
                           echo '}, 50);</script>';
                       }
                   }

                   else
                   {
                       echo '<script type="text/javascript">';
                       echo 'setTimeout(function () { swal("sorry something went wrong!!!!","probably problem in the database :(","error");';
                       echo '}, 50);</script>';
                   }

               }
            }

        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>add to trip</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
    <link href="css/signup.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Add Trips</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <select class="form-control" name="tid" id="tid" required>
                                <option value="0">trains</option>
                                <?php
                                for($i=0;$i<count($trains);$i++)
                                {
                                    echo '<option value='.($i+1).'>'.$trains[$i].'</option>';
                                }
                                ?>
                            </select>
                        </div>


                        <div class="form-group">
                            <input type="date" name="tdate" id="tdate" class="form-control" placeholder="" required>
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="hid" id="hid" required>
                                <option value="-1">hour</option>
                                <?php
                                for($i=0;$i<24;$i++)
                                {
                                    echo '<option value='.($i).'>'.$i.'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="form-control" name="mid" id="mid" required>
                                <option value="-1">minute</option>
                                <?php
                                for($i=0;$i<60;$i++)
                                {
                                    echo '<option value='.($i).'>'.$i.'</option>';
                                }
                                ?>
                            </select>
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