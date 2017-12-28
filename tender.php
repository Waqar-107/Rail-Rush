<?PHP

    session_start();
    if(empty($_SESSION['user_in']))
    {
        header('location: base.php');
    }

    //if not admin, send to base
    if($_SESSION['type']==2)
    {
        header('Location: base.php');
    }

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

    //---------------------------------------------------------------show graph 1
    
    //---------------------------------------------------------------show graph 1


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

</body>
</html>