<?php
/**
 * Created by PhpStorm.
 * User: sikasep
 * Date: 11/24/15
 * Time: 12:56 AM
 */
    session_start();
    if(isset($_SESSION['username'])){
        header("location:index.php");
    }
    ?>
<html>
    <?php include('meta.php'); ?>
    <body>
        <div class="container">
            <div class="row">
                <div class="col l3">&nbsp;</div>
                <div class="col l6 index-page">
                    <div class="card-panel center">
                        <div class="header">
                            <img src="img/logo.png">
                        </div>
                        <div class="navigasi">
                            <h4>Please sign in</h4>
                            <div class="col s12">
                                <form method="post" action="">
                                    <div class="input-field col s12">
                                        <input name="eusername" id="username" type="email" class="validate">
                                        <label for="username">Username</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <input name="epassword" id="password" type="password" class="validate">
                                        <label for="password">Password</label>
                                    </div>
                                    <div class="input-field col s12">
                                        <button id="submit" class="btn btn-large waves-effect waves-light" type="submit">Sign in
                                            <i class="mdi mdi-login right"></i>
                                        </button>
                                    </div>
                                    <div class="input-field col s12 message"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col l3">&nbsp;</div>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){

                    $("#submit").click(function(){

                        var username = $("#username").val();
                        var password = $("#password").val();

                        if((username == "") || (password == "")) {
                            Materialize.toast('Please enter a username and a password', 2500)
                        }
                        else {
                            $.ajax({
                                type: "POST",
                                url: "check_login.php",
                                data: "eusername="+username+"&epassword="+password,
                                success: function(html){
                                    $('.message').empty();
                                    if(html=='true') {
                                        document.location.href="index.php";
                                    }
                                    else {
                                        Materialize.toast(html, 2500)
                                    }
                                },
                                beforeSend:function()
                                {
                                    $(".message").html("<div class='progress'><div class='indeterminate'></div></div>")
                                }
                            });
                        }
                        return false;
                    });
                });
            </script>
        </div>
    </body>
</html>