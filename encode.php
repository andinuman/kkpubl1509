<?php
/**
 * Created by PhpStorm.
 * User: sikasep
 * Date: 10/22/15
 * Time: 12:33 AM
 */
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
include('des2.php');
include('base64.php');
//use Blowfish\Blowfish as Blowfish;
if ( ! empty($_POST) OR ! empty($_FILES)) {
    $key = $_POST["secretkey"];
    if (empty($key)) {
        $msg = 'Please provide your secret key';
    }
    else {
        if ( ! empty($_FILES['file']) && ! empty($_FILES['file']['tmp_name'])) {
            $file = $_FILES['file'];
            $tf = file_get_contents($file['tmp_name']);
        }
        elseif ( ! empty($_POST["string"])) {
            $string = $_POST["string"];
            $tf = $string;
        }

        if ( ! empty($tf)) {
            $des = DES::des_encrypt($key, $tf);
            if ( ! empty($file)) {
                $filename = pathinfo($file['name'], PATHINFO_FILENAME) . '.psi';
                $content = $des.'|'.Base64::encode(pathinfo($file['name'], PATHINFO_EXTENSION));
                file_put_contents('download/'.$filename, $content);
                header('Content-type: application/x-download');
                header('Content-Disposition: attachment; filename="'.$filename.'"');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: '.strlen('download/'.$filename));
                set_time_limit(0);
                exit;
            }
        }
        else {
            $msg = 'Please input some text or upload a file';
        }
    }
}
?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Encrypter - Think Digital</title>
    <link rel="stylesheet" href="css/materialize.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col l2">&nbsp;</div>
        <div class="col l8 wrap-page">
            <div class="card-panel">
                <div class="col l12">
                    <div class="header center">
                        <a href="index.php"><img src="img/logo.png"></a>
                    </div>
                    <nav>
                        <div class="nav-wrapper">
                            <ul id="nav-mobile" class="left hide-on-med-and-down">
                                <li class="active"><a href="encode.php">Encrypter</a></li>
                                <li><a href="">Decrypter</a></li>
                            </ul>
                        </div>
                    </nav>
                    <h5 class="center-align">Encrypter</h5>
                    <div class="box-form">
                        <ul class="tabs">
                            <li class="tab col l3"><a <?php if (isset($file)) { ?>class="active"<?php } ?> href="#file">File</a></li>
                            <li class="tab col l3"><a <?php if (isset($string)) { ?>class="active"<?php } ?>href="#text">Text</a></li>
                        </ul>
                        <div id="file" class="col s12">
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                <div class="file-field input-field col s12">
                                    <div class="btn">
                                        <span>File</span>
                                        <input name="file" type="file">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" placeholder="Upload one file">
                                    </div>
                                </div>
                                <div class="input-field col s12">
                                    <input name="secretkey" placeholder="Please provide your secret key" id="first_name" type="text" class="validate">
                                    <label for="first_name">Secret Key</label>
                                </div>
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit" name="action">Encrypt
                                        <i class="material-icons right">input</i>
                                    </button>
                                    <button class="btn waves-effect waves-light" type="reset" name="action">Reset
                                        <i class="material-icons right">clear_all</i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div id="text" class="col s12">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <?php if (isset($string)) { ?>
                                    <div class="chip">
                                        Encrypted result: <?php echo $des; ?>
                                    </div>
                                <?php } ?>
                                <div class="input-field col s12">
                                    <textarea name="string" id="textarea1" class="materialize-textarea"><?php echo isset($string) ? $string : ''; ?></textarea>
                                    <label for="textarea1">
                                        <?php
                                            if (isset($string)) {
                                                echo "The original text";
                                            }
                                            else {
                                                echo "Plain text";
                                            }
                                        ?>
                                    </label>
                                </div>
                                <div class="input-field col s12">
                                    <input name="secretkey" placeholder="Please provide your secret key" id="first_name" type="text" class="validate">
                                    <label for="first_name">Secret Key</label>
                                </div>
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit" name="action">Encrypt
                                        <i class="material-icons right">input</i>
                                    </button>
                                    <button class="btn waves-effect waves-light" type="reset" name="action">Reset
                                        <i class="material-icons right">clear_all</i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col l2">&nbsp;</div>
    </div>
</div>
</body>
</html>

