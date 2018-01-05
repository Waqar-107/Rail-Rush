<?php

    include('SeatDrawer.php');
    session_start();
    echo '<script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>';


    if (empty($_SESSION['user_in']) || $_SESSION['type'] != 2)
    {
        header('location: base.php');
    }

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');

    //check connection
    if (!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    //----------------------------------------------------------------------
    //trip id, train-id and date
    $tid=$_GET['tripId'];
    $sql="SELECT TO_CHAR(TRIP_DATE,'DD-MM-YYYY') \"DT\" ,TRAIN_ID 
          FROM TRIP
          WHERE TRIP_ID='$tid'";
    $result=oci_parse($conn,$sql);oci_execute($result);$row=oci_fetch_assoc($result);

    $train_id=$row['TRAIN_ID'];
    $sid=$row['DT'].'#'.$train_id.'#';
    //----------------------------------------------------------------------


    //----------------------------------------------------------------------
    //variables that will determine the button colors
    $first_class;$second_class;$third_class;
    $temp1=0;$temp2=0;$temp3=0;

    $sql="SELECT FIRST_CLASS,SECOND_CLASS,THIRD_CLASS FROM TRAIN WHERE TRAIN_ID='$train_id'";
    $result=oci_parse($conn,$sql);oci_execute($result);
    $row=oci_fetch_assoc($result);
    $first_class=$row['FIRST_CLASS']; $second_class=$row['SECOND_CLASS'];$third_class=$row['THIRD_CLASS'];
    //----------------------------------------------------------------------

    //----------------------------------------------------------------------sold array
    $sold=array();

    //first-class
    for($i=1;$i<=$first_class;$i++)
    {
        $temp=$sid.'1#'.$i;

        $sql="SELECT SOLD FROM SEAT WHERE SEAT_ID='$temp'";
        $result=oci_parse($conn,$sql);oci_execute($result);$row=oci_fetch_assoc($result);

        array_push($sold,$row['SOLD']);
    }

    //second-class
    for($i=1;$i<=$second_class;$i++)
    {
        $temp=$sid.'2#'.$i;

        $sql="SELECT SOLD FROM SEAT WHERE SEAT_ID='$temp'";
        $result=oci_parse($conn,$sql);oci_execute($result);$row=oci_fetch_assoc($result);

        array_push($sold,$row['SOLD']);
    }

    //third-class
    for($i=1;$i<=$third_class;$i++)
    {
        $temp=$sid.'3#'.$i;

        $sql="SELECT SOLD FROM SEAT WHERE SEAT_ID='$temp'";
        $result=oci_parse($conn,$sql);oci_execute($result);$row=oci_fetch_assoc($result);

        array_push($sold,$row['SOLD']);
    }
    //----------------------------------------------------------------------sold array

    //show fares
    $fr=array();
    $sql="SELECT PRICE
          FROM FARE
          WHERE TRAIN_ID='$train_id'
          ORDER BY STYPE";
    $result=oci_parse($conn,$sql);oci_execute($result);
    while ($row=oci_fetch_assoc($result))
    {
        array_push($fr,$row['PRICE']);
    }

    echo '<div class="container-fluid">
            <div class="row" style="font-family: \'Comic Sans MS\';color: black;font-size: 25px">
                <div class="col-md-4" align="center">first-class: '.$fr[0].'</div>
                <div class="col-md-4" align="center">second-class: '.$fr[1].'</div>
                <div class="col-md-4" align="center">third-class: '.$fr[2].'</div>
            </div>
            <div class="row"><div class="col-md-12" align="center" style="font-family: \'Comic Sans MS\';color: orangered;font-size: 25px">
            you can buy atmost 4 tickets !!!</div>
            
          </div>';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>buy tickets online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/buyTicket.css" rel="stylesheet"/>
    <link href="sweetalert/sweetalert.css" rel="stylesheet">
    <script src="js/showDate.js" type="text/javascript"></script>
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="sweetalert/sweetalert.min.js" type="text/javascript"></script>
</head>

<body>

<div class="container">
    <!--NAVBAR-->
    <div class="row" style="margin-bottom: 10%">
        <nav class="navbar fixed-top navbar-light">
            <img src="images/trainLogo.png" style="margin-left: 10px">
            <a href="base.php"
               style="font-size: 17px;font-family: 'Comic Sans MS';color: white;margin-left: 100px">Home</a>
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
</div>


<div class="container-fluid">

    <div class="row">
        <div class="col-md-4"></div><div class="col-md-4" align="center">
            <button type="button" class="btn btn-lg btn-info" onclick="confirmTicket()">confirm</button>
        </div>
    </div>

    <!--HEADER-->
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4" align="center"><h3>first class</h3></div>
    </div>
    <!--FIRST CLASS 1-->
    <div class="row" id="F1">
        <div class="col-lg-4" id="F11" align="center">
            <?php
            if ($temp1 < $first_class)
            {
                drawingSeat('F', 0, 0, $sold);
                $temp1 += 20;
            }
            ?>
        </div>

        <div class="col-lg-4" id="F12" align="center">
            <?php
            if ($temp1 < $first_class)
            {
                drawingSeat('F', 0, 20, $sold);
                $temp1 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="F13" align="center">
            <?php
            if ($temp1 < $first_class)
            {
                drawingSeat('F', 0, 40, $sold);
                $temp1 += 20;
            }
            ?>
        </div>
    </div>
    <!--FIRST CLASS 1-->

    <!--FIRST CLASS 2-->
    <div class="row" id="F2">
        <div class="col-lg-4" id="F21" align="center">
            <?php
            if ($temp1 < $first_class) {
                drawingSeat('F', 0, 80, $sold);
                $temp1 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="F22" align="center">
            <?php
            if ($temp1 < $first_class) {
                drawingSeat('F', 0, 100, $sold);
                $temp1 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="F23" align="center">
            <?php
            if ($temp1 < $first_class) {
                drawingSeat('F', 0, 120, $sold);
                $temp1 += 20;
            }
            ?>
        </div>
    </div>
    <!--FIRST CLASS 2-->

    <!--FIRST CLASS 3-->
    <div class="row" id="F3">
        <div class="col-lg-4" id="F31" align="center">
            <?php
            if ($temp1 < $first_class) {
                drawingSeat('F', 0, 140, $sold);
                $temp1 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="F32" align="center">
            <?php
            if ($temp1 < $first_class) {
                drawingSeat('F', 0, 160, $sold);
                $temp1 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="F33" align="center">
            <?php
            if ($temp1 < $first_class) {
                drawingSeat('F', 0, 180, $sold);
                $temp1 += 20;
            }
            ?>
        </div>
    </div>
    <!--FIRST CLASS 3-->


    <!--HEADER-->
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4" align="center"><h3>second class</h3></div>
    </div>
    <!--SECOND CLASS 1-->
    <div class="row" id="S1">
        <div class="col-lg-4" id="S11" align="center">
            <?php
            if ($temp2 < $second_class) {
                drawingSeat('S', $first_class, 0, $sold);
                $temp2 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="S12" align="center">
            <?php
            if ($temp2 < $second_class) {
                drawingSeat('S', $first_class, 20, $sold);
                $temp2 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="S13" align="center">
            <?php
            if ($temp2 < $second_class) {
                drawingSeat('S', $first_class, 40, $sold);
                $temp2 += 20;
            }
            ?>
        </div>
    </div>
    <!--SECOND CLASS 1-->

    <!--SECOND CLASS 2-->
    <div class="row" id="S2">
        <div class="col-lg-4" id="S21" align="center">
            <?php
            if ($temp2 < $second_class) {
                drawingSeat('S', $first_class, 60, $sold);
                $temp2 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="S22" align="center">
            <?php
            if ($temp2 < $second_class) {
                drawingSeat('S', $first_class, 80, $sold);
                $temp2 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="S23" align="center">
            <?php
            if ($temp2 < $second_class) {
                drawingSeat('S', $first_class, 100, $sold);
                $temp2 += 20;
            }
            ?>
        </div>
    </div>
    <!--SECOND CLASS 2-->

    <!--SECOND CLASS 3-->
    <div class="row" id="S3">
        <div class="col-lg-4" id="S31" align="center">
            <?php
            if ($temp2 < $second_class) {
                drawingSeat('S', $first_class, 120, $sold);
                $temp2 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="S32" align="center">
            <?php
            if ($temp2 < $second_class) {
                drawingSeat('S', $first_class, 140, $sold);
                $temp2 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="S33" align="center">
            <?php
            if ($temp2 < $second_class) {
                drawingSeat('S', $first_class, 160, $sold);
                $temp2 += 20;
            }
            ?>
        </div>
    </div>
    <!--SECOND CLASS 3-->


    <!--HEADER-->
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4" align="center"><h3>third class</h3></div>
    </div>
    <!--THIRD CLASS 1-->
    <div class="row" id="T1">
        <div class="col-lg-4" id="T11" align="center">
            <?php
            if ($temp3 < $third_class) {
                drawingSeat('T', $first_class + $second_class, 0, $sold);
                $temp3 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="T12" align="center">
            <?php
            if ($temp3 < $third_class) {
                drawingSeat('T', $first_class + $second_class, 20, $sold);
                $temp3 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="T13" align="center">
            <?php
            if ($temp3 < $third_class) {
                drawingSeat('T', $first_class + $second_class, 40, $sold);
                $temp3 += 20;
            }
            ?>
        </div>
    </div>
    <!--THIRD CLASS 1-->

    <!--THIRD CLASS 2-->
    <div class="row" id="T2">
        <div class="col-lg-4" id="T21" align="center">
            <?php
            if ($temp3 < $third_class) {
                drawingSeat('T', $first_class + $second_class, 60, $sold);
                $temp3 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="T22" align="center">
            <?php
            if ($temp3 < $third_class) {
                drawingSeat('T', $first_class + $second_class, 80, $sold);
                $temp3 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="T23" align="center">
            <?php
            if ($temp3 < $third_class) {
                drawingSeat('T', $first_class + $second_class, 100, $sold);
                $temp3 += 20;
            }
            ?>
        </div>
    </div>
    <!--THIRD CLASS 2-->

    <!--THIRD CLASS 3-->
    <div class="row" id="T3">
        <div class="col-lg-4" id="T31" align="center">
            <?php
            if ($temp3 < $third_class) {
                drawingSeat('T', $first_class + $second_class, 120, $sold);
                $temp3 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="T32" align="center">
            <?php
            if ($temp3 < $third_class) {
                drawingSeat('T', $first_class + $second_class, 140, $sold);
                $temp3 += 20;
            }
            ?>
        </div>
        <div class="col-lg-4" id="T33" align="center">
            <?php
            if ($temp3 < $third_class) {
                drawingSeat('T', $first_class + $second_class, 160, $sold);
                $temp3 += 20;
            }
            ?>
        </div>
    </div>
    <!--THIRD CLASS 3-->

</div>

<!--TO MAKE INVISIBLE THOSE DIVS THAT ARE OUT OF RANGE OF  SEAT NUMBER-->
<script type="text/javascript">

    var uno=<?php echo json_encode($first_class);?>;
    var dos=<?php echo json_encode($second_class);?>;
    var tres=<?php echo json_encode($third_class);?>;
    console.log(uno+' '+dos+' '+tres);
    //-------------------------------------------------FIRST CLASS REMOVAL
    var ID;
    var fr,fc;
    if(uno%60==0)
        fr=uno/60;
    else
        fr=Math.floor(uno/60)+1

    //un-wanted rows removed
    for(var i=3;i>fr;i--)
    {
        ID='F'+i;
        document.getElementById(ID).style.display='none';
    }

    //unwanted columns
    fc=uno-((fr-1)*20);
    fc=Math.floor(fc/20);
    for(var i=3;i>fc;i--)
    {
        ID='F'+fr+i;
        document.getElementById(ID).style.display='none';
    }
    //-------------------------------------------------FIRST CLASS REMOVAL


    //-------------------------------------------------SECOND CLASS REMOVAL
    var sr,sc;
    if(dos%60==0)
        sr=dos/60;
    else
        sr=Math.floor(dos/60)+1

    //un-wanted rows removed
    for(var i=3;i>sr;i--)
    {
        ID='S'+i;
        document.getElementById(ID).style.display='none';
    }

    //unwanted columns
    sc=dos-((sr-1)*20);
    sc=Math.floor(sc/20);
    for(var i=3;i>sc;i--)
    {
        ID='S'+sr+i;
        document.getElementById(ID).style.display='none';
    }
    //-------------------------------------------------SECOND CLASS REMOVAL


    //-------------------------------------------------THIRD CLASS REMOVAL
    var tr,tc;
    if(tres%60==0)
        tr=tres/60;
    else
        tr=Math.floor(tres/60)+1

    //un-wanted rows removed
    for(var i=3;i>tr;i--)
    {
        ID='T'+i;
        document.getElementById(ID).style.display='none';
    }

    //unwanted columns
    tc=tres-((tr-1)*20);
    tc=Math.floor(tc/20);
    for(var i=3;i>tc;i--)
    {
        ID='T'+tr+i;
        document.getElementById(ID).style.display='none';
    }
    //-------------------------------------------------THIRD CLASS REMOVAL
</script>
<!--TO MAKE INVISIBLE THOSE DIVS THAT ARE OUT OF RANGE OF  SEAT NUMBER-->

<script type="text/javascript">
    //-------------------------------------------------by clicking the seats will be bought
    var first=[];var second=[];

    $("button").click(function ()
    {
        var f=1;

        for(var i=0;i<first.length;i++)
        {
            if(first[i]==this.id)
            {
                second[i]++;f=0;

                //from yellow to red
                if(second[i]%3==2) {
                    $(this).removeClass('btn-warning');
                    $(this).addClass('btn-danger');
                }

                //from red to green
                else if(second[i]%3==0){
                    $(this).removeClass('btn-danger');
                    $(this).addClass('btn-outline-success');
                }

                //from green to yellow
                else if(second[i]%3==1){
                    $(this).removeClass('btn-outline-success');
                    $(this).addClass('btn-warning');
                }

                break;
            }
        }

        if(f)
        {
            first.push(this.id);second.push(1);
            $(this).removeClass('btn-outline-success');
            $(this).addClass('btn-warning');
        }
    })
    //-------------------------------------------------by clicking the seats will be bought

    //-----------------------------------------------------function to confirm
    function confirmTicket()
    {
        var s1="";var final=[];var als="";
        for(var i=0;i<first.length;i++)
        {
            if(second[i]%3==2) {
                s1+=(first[i]+'W');als+=(first[i]+" ");
                final.push(first[i]);
            }
        }

        var tid=<?php echo json_encode($tid);?>;
        var train_id=<?php echo json_encode($train_id);?>;

        if(final.length==0)
        {
            setTimeout(function() {
                swal({
                    title: "you haven't selected yet!!!",
                    text: "will you sit on air ;)",
                    type: "error"
                }, function() {
                    window.location = "buyTicket.php?tripId="+tid;
                });
            }, 50);
        }

        else if(final.length>4)
        {
            setTimeout(function() {
                swal({
                    title: "you are not allowed to buy more than four!!!",
                    text: "please select <= four :)",
                    type: "error"
                }, function() {
                    window.location = "buyTicket.php?tripId="+tid;
                });
            }, 50);
        }

        else{
            var link=("confirmTicket.php?data1="+s1+"&tid="+tid+"&train_id="+train_id);

            //try a warning
            setTimeout(function () {
                swal({
                        title: "you have selected "+final.length+" seats",
                        text: "are you sure about them? we refund less than half :)",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#02a9e0",
                        confirmButtonText: "Yes, buy them!",
                        cancelButtonText: "No, cancel please!",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm)
                    {
                        if (isConfirm)
                        {
                            window.location.href=link;    // submitting the form when user press yes
                        }

                        else {
                            setTimeout(function() {
                                swal({
                                    title: "decide again !!!",
                                    text: "please select carefully :)",
                                    type: "success"
                                }, function() {
                                    window.location = "buyTicket.php?tripId="+tid;
                                });
                            }, 50);
                        }
                    });
            },50);


        }
    }
    //-----------------------------------------------------function to confirm
</script>


</body>

</html>