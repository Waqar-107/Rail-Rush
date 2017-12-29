<?php
    /**
     * Created by PhpStorm.
     * User: waqar hassan khan
     * Date: 12/29/2017
     * Time: 6:47 PM
     */

    //---------------------------------------------------------------connect to the database
    //create connection
    $conn = oci_connect('ANONYMOUS', '1505107', 'localhost/orcl');
    //check connection
    if(!$conn)
    {
        echo 'connection error';
    }
    //---------------------------------------------------------------connect to the database

    $tid;$ans;$tid=2;$ans=null;
    $sql="
          BEGIN
            :ans:=VALID_TENDER(:tid);
          END;";
    $result=oci_parse($conn,$sql);

    oci_bind_by_name($result,":tid",$tid,32);
    oci_bind_by_name($result,":ans",$ans,32);


    oci_execute($result);

    echo $ans;














