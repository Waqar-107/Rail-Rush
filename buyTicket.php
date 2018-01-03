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

    $tid=$_GET['tripId'];
    $sql="SELECT TO_CHAR(TRIP_DATE,'DD-MM-YYYY') \"DT\" ,TRAIN_ID 
          FROM TRIP
          WHERE TRIP_ID='$tid'";
    $result=oci_parse($conn,$sql);oci_execute($result);$row=oci_fetch_assoc($result);

    $train_id=$row['TRAIN_ID'];
    $sid=$row['DT'].'#'.$train_id.'%';
    $sql="SELECT SEAT_ID,SOLD 
          FROM SEAT
          WHERE SEAT_ID LIKE '$sid'";
    $result=oci_parse($conn,$sql);oci_execute($result);

    //three arrays. one for class,one for seat no. and one for the id of the passenger who has it
    $type=array();$sno=array();$sold=array();
    while ($row=oci_fetch_assoc($result))
    {
        $temp=$row['SEAT_ID'];array_push($sold,$row['SOLD']);

        $i=strlen($temp)-1;
        $x=0;$k=1;
        while ($i>=0)
        {
            if($temp[$i]=='#')
            {
                $i--;break;
            }

            $x+=($temp[$i]*$k);$k*=10;$i--;
        }

        array_push($sno,$x);

        $x=0;$k=1;
        while ($i>=0)
        {
            if($temp[$i]=='#')
            {
                $i--;break;
            }

            $x+=($temp[$i]*$k);$k*=10;$i--;
        }

        array_push($type,$x);
    }

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
          </div>';

    //variables that will determine the button colors
    $first_class;$second_class;$third_class;
    $temp1=0;$temp2=0;$temp3=0;

    for($i=0;$i<count($type);$i++)
    {
        if($type[$i]==2 && $type[$i-1]==1)
            $first_class=$i;

        if($type[$i]==3 && $type[$i-1]==2)
        {
            $second_class=$i-$first_class;
            $third_class=count($type)-$second_class-$first_class;
            break;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>buy tickets online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="css/buyTicket.css" rel="stylesheet"/>
    <script src="js/showDate.js" type="text/javascript"></script>
    <script src="js/jquery.min.js" type="text/javascript"></script>
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

    var stype=<?php echo json_encode($type);?>;
    var sno=<?php echo json_encode($sno);?>;
    var sold=<?php echo json_encode($sold);?>;

    var uno=0,dus=0,tres=0;
    for(var i=0;i<stype.length;i++)
    {
        if(stype[i-1]==1 && stype[i]==2)
            uno=i;

        if(stype[i-1]==2 && stype[i]==3)
            dos=i-uno;
    }

    tres=stype.length-uno-dos;
    //document.writeln(uno+' '+dos+' '+tres+'</br>');

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
                $(this).removeClass('btn-warning');
                $(this).addClass('btn-danger');
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
        var s1="",s2="";
        for(var i=0;i<first.length;i++)
        {
            s1+=(first[i]+'W');

            if(second[i]<=1)
                s2+=(second[i]+'W');
            else
                s2+=("2W");
        }

        window.location.href=("confirmTicket.php?data1="+s1+"&data2="+s2);
    }
    //-----------------------------------------------------function to confirm
</script>


</body>

</html>