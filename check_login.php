<?php
/**
 * Created by PhpStorm.
 * User: sikasep
 * Date: 11/24/15
 * Time: 1:16 AM
 */
    ob_start();
    session_start();
    include_once 'koneksi.php';

    // Connect to server and select databse.
    try
    {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $db = new PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $username, $password);
    }
    catch(Exception $e)
    {
        die('Error : ' . $e->getMessage());
    }

    // Define $myusername and $mypassword
    $eusername = $_POST['eusername'];
    $epassword = $_POST['epassword'];

    // To protect MySQL injection
    $eusername = stripslashes($eusername);
    $epassword = stripslashes($epassword);

    $stmt = $db->query("SELECT * FROM $tbl_name WHERE username='$eusername' and password='$epassword'");

    // rowCount() is counting table row
    $count = $stmt->rowCount();

    // If result matched $eusername and $epassword, table row must be 1 row
    if($count == 1){

        // Register $eusername, $epassword and print "true"
        echo "true";
        //header("location:index.php");
        $_SESSION['username'] = 'eusername';
        $_SESSION['password'] = 'epassword';

    }
    else {
        //return the error message
        echo "Wrong Username or Password";
    }

    ob_end_flush();