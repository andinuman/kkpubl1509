<?php
/**
 * Created by PhpStorm.
 * User: sikasep
 * Date: 1/26/16
 * Time: 3:02 AM
 */
session_start();

if(!isset($_SESSION['username'])){
    header("location:login.php");
}