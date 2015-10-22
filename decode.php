<?php
/**
 * Created by PhpStorm.
 * User: sikasep
 * Date: 10/22/15
 * Time: 12:33 AM
 */
include('des.php');
$kalimat = $_POST["plaintext"]; // method post dan get
$key = $_POST["secretkey"]; // method post dan get
//for($i=0;$i<strlen($kalimat);$i++)
//{
//    $kode[$i]=ord($kalimat[$i]); //rubah ASCII ke desimal
//    $b[$i]=($kode[$i] + $key ) % 256; //proses enkripsi
//    $c[$i]=chr($b[$i]); //rubah desimal ke ASCII
//}
////echo "kalimat ASLI : ";
//for($i=0;$i<strlen($kalimat);$i++)
//{
//    $kalimat[$i];
//}
////echo "hasil enkripsi =";
//$hsl = '';
//for ($i=0;$i<strlen($kalimat);$i++)
//{
//    $c[$i];
//    $hsl = $hsl . $c[$i];
//}
////simpan data di file
//$fp = fopen ("enc_text".$hsl.".txt","w");
//fputs ($fp,$hsl);
//fclose($fp);

?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Credential Encrypter - Erasys Consulting</title>
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
        <div class="col l8">
            <div class="header center">
                <img src="img/logo.png" title="Budi Luhur Salemba">
            </div>
            <nav>
                <div class="nav-wrapper">
                    <ul id="nav-mobile" class="left hide-on-med-and-down">
                        <li><a href="encode.php">Encrypter</a></li>
                        <li class="active"><a href="">Decrypter</a></li>
                    </ul>
                </div>
            </nav>
            <h5 class="center-align">Credential Encrypter</h5>
            <div class="box-form">
                <ul class="tabs">
                    <li class="tab col l3"><a <?php if ($kalimat == '') { ?>class="active"<?php } ?> href="#file">File</a></li>
                    <li class="tab col l3"><a <?php if ($kalimat != '') { ?>class="active"<?php } ?>href="#text">Text</a></li>
                </ul>
                <div id="file" class="col s12">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
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
                        <?php if($hsl) { ?>
                            <div class="chip">
                                hasil enkripsi : <?php echo Des::des_encrypt($key,$kalimat); ?>
                            </div>
                        <?php } ?>
                        <div class="input-field col s12">
                            <textarea name="plaintext" id="textarea1" class="materialize-textarea" leng><?php echo $kalimat; ?></textarea>
                            <label for="textarea1">
                                <?php
                                    if ($kalimat) {
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
        <div class="col l2">&nbsp;</div>
    </div>

</div>
</body>
</html>

