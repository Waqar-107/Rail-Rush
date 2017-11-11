<?php
    /**
 * Created by PhpStorm.
 * User: waqar
 * Date: 11/11/2017
 * Time: 2:19 PM
 */

    session_start();
    // remove all session variables
    session_unset();
    header('Location: base.php');

?>