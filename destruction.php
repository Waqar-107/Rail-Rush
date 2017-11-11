<?php
/**
 * Created by PhpStorm.
 * User: waqar
 * Date: 11/11/2017
 * Time: 2:19 PM
 */

// remove all session variables
session_unset();

// destroy the session
session_destroy();

header('Location: base.php');