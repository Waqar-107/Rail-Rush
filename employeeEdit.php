<?php

    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';

    if(empty($_SESSION['user_in']) || $_SESSION['type']==2)
    {
        header('location: base.php');
    }

    $eid=$_GET['eid'];
    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    $sql="SELECT EMPLOYEE_ID, (FIRST_NAME||' '||LAST_NAME)\"NM\",EMAIL,PHONE,JOIN_DATE,SALARY,JOB_TYPE
          FROM EMPLOYEE WHERE EMPLOYEE_ID='$eid'";
    $result=oci_parse($conn,$sql);oci_execute($result);$row=oci_fetch_assoc($result);

    echo '<div class="container-fluid" style="margin-top: 100px;font-family: \'Comic Sans MS\';font-size: 20px;color: white">
            <div class="row">
                <div class="col-md-8">Employee Id: '.$row['EMPLOYEE_ID'].'</div>
            </div>
            <div class="row">
                <div class="col-md-8">Name: '.$row['NM'].'</div>
            </div>
            <div class="row">
                <div class="col-md-8">Email: '.$row['EMAIL'].'</div>
            </div>
            <div class="row">
                <div class="col-md-8">Phone: '.$row['PHONE'].'</div>
            </div>
            <div class="row">
                <div class="col-md-8">Join Date: '.$row['JOIN_DATE'].'</div>
            </div>
            <div class="row">
                <div class="col-md-8">Salary: '.$row['SALARY'].'</div>
            </div>
            <div class="row">
                <div class="col-md-8">Job Type: '.$row['JOB_TYPE'].'</div>
            </div>
        </div>';

    //show a warning
    $isDriver=false;
    if($row['JOB_TYPE']=='DRIVER')
    {
        $isDriver=true;
        echo '<div class="container-fluid" style="background-color: white;margin-top: 10px">
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <p style="color: orange;font-size: 25px;font-family: \'Comic Sans MS\'">
                         this person is a driver, change carefully</p></div>
                    </div>
              </div>';
    }

    //if retired then not permitted to do antrhing
    $decider=true;
    if($row['JOB_TYPE']=='RETIRED')
    {
        echo '<script>
             setTimeout(function() {
                 swal({
                  title: "the person has retired already!!!",
                  text: "",
                  type: "error"
                }, function() {
                       window.location = "employeeList.php";
                     });
              }, 50);
        </script>';

        $decider=false;
    }

    if(isset($_POST['submit']) && $decider)
    {
        $jtype=$_POST['job'];$nsal=$_POST['sal'];
        $jtype=strtoupper($jtype);

        $sql="UPDATE EMPLOYEE
              SET JOB_TYPE='$jtype',SALARY='$nsal'
              WHERE EMPLOYEE_ID='$eid'";

        $result=oci_parse($conn,$sql);

        if($isDriver)
        {
            $sql2="SELECT TRAIN_ID FROM TRAIN WHERE EMPLOYEE_ID='$eid'";
            $result2=oci_parse($conn,$sql2);oci_execute($result2);$row=oci_fetch_assoc($result2);
            $tid=$row['TRAIN_ID'];

            $sql2="UPDATE TRAIN 
                   SET EMPLOYEE_ID=NULL
                   WHERE TRAIN_ID='$tid'";
            $result2=oci_parse($conn,$sql2);

            if(oci_execute($result) && oci_execute($result2))
            {
                echo '<script>
                     setTimeout(function() {
                        swal({
                         title: "successfully updated!!!",
                         text: "",
                         type: "success"
                        }, function() {
                             window.location = "employeeList.php";
                               });
                     }, 50);
                  </script>';
            }

            else
            {
                echo '<script>
                     setTimeout(function() {
                        swal({
                         title: "something went wrong!!!",
                         text: "please try again later :)",
                         type: "error"
                        }, function() {
                             window.location = "employeeList.php";
                               });
                     }, 50);
                  </script>';
            }
        }

        else
        {
            if(oci_execute($result))
            {
                echo '<script>
                     setTimeout(function() {
                        swal({
                         title: "successfully updated!!!",
                         text: "",
                         type: "success"
                        }, function() {
                             window.location = "employeeList.php";
                               });
                     }, 50);
                  </script>';
            }

            else
            {
                echo '<script>
                     setTimeout(function() {
                        swal({
                         title: "something went wrong!!!",
                         text: "please try again later :)",
                         type: "error"
                        }, function() {
                             window.location = "employeeList.php";
                               });
                     }, 50);
                  </script>';
            }
        }






    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>edit employee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/signup.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
</head>
<body>

<!--NAVBAR-->
<div class="row" style="margin-bottom: 10%">
    <nav class="navbar fixed-top navbar-light">
        <img src="images/trainLogo.png" style="margin-left: 10px">
        <a href="admin_base.php" style="font-size: 17px;margin-left: 100px;font-family: 'Comic Sans MS';color: white">Home</a>
        <a href="destruction.php"
           style="font-size: 17px;font-family: 'Comic Sans MS';color: white;margin-left: 100px">log out</a>

        <p id="tt"
           style="color: white;font-size: 17px;font-family: 'Comic Sans MS';margin-right: 10px;margin-top: 5px">
            date</p>
        <script type="text/javascript">
            dateShower()
        </script>
    </nav>
</div>
<!--NAVBAR-->

<div class="container">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <h3 class="text-center login-title" style="color:white;padding-bottom: 10px">Update Employee</h3>
                <div class="panel-body">
                    <form action="" method="post">

                        <div class="form-group">
                            <input type="text" name="job" id="job" class="form-control" placeholder="new job type" required>
                        </div>

                        <div class="form-group">
                            <input type="number" name="sal" id="sal" class="form-control" placeholder="new salary" required>
                        </div>

                        <div class="form-group" style="margin-top: 50px">
                            <input type="submit" name="submit" id="submit" class="btn btn-success btn-lg btn-block"
                                   value="update">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>