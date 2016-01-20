<?php
/**
 * Created by PhpStorm.
 * User: sikasep
 * Date: 11/24/15
 * Time: 1:30 AM
 */
    session_start();
    session_destroy();
    header("location:login.php");