<?php
/**
 * Created by PhpStorm.
 * User: sikasep
 * Date: 10/10/15
 * Time: 12:35 AM
 */
    session_start();

    if(!isset($_SESSION['username'])){
        header("location:login.php");
    }
?>
<html>
    <?php include('meta.php'); ?>
    <body>
        <div class="container">
            <div class="row">
                <div class="col l2">&nbsp;</div>
                <div class="col l8 index-page">
                    <div class="card-panel center">
                        <div class="header">
                            <img src="img/logo.png">
                            <h5>Project document credential software</h5>
                        </div>
                        <div class="navigasi">
                            <a class="waves-effect waves-light btn-large" href="encode.php"><i class="mdi mdi-login right"></i>Encrypter</a>
                            <a class="waves-effect waves-light btn-large" href="decode.php"><i class="mdi mdi-key right"></i>Decrypter</a>
                            <a class="waves-effect waves-light btn-large" href="logout.php"><i class="mdi mdi-power right"></i>Log out</a>
                        </div>
                    </div>
                </div>
                <div class="col l2">&nbsp;</div>
            </div>

        </div>
    </body>
</html>
