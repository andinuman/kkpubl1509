<?php
/**
 * Created by PhpStorm.
 * User: sikasep
 * Date: 10/22/15
 * Time: 12:33 AM
 */
include('rc4.php');
if ( ! empty($_POST) OR ! empty($_FILES)) {
    $key = $_POST["secretkey"];
    if (empty($key)) {
        $data = array('msg' => 'Please provide your secret key');
    }
    else {
        if(isset($_FILES['file'])) {
            if (!empty($_FILES['file']) && !empty($_FILES['file']['tmp_name'])) {
                $file = $_FILES['file'];
                $file_name = file_get_contents($file['tmp_name']);
                $data = array('status'=>true,'value'=>$file_name);
            }
            else {
                $err = 'Please upload a file';
                $data = array('status'=>false,'msg'=>$err);
            }
        }
        if($data['status']) {
            $ef = rc4::rc4dec($key,$data['value']);
            $filename = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME) . '.txt';
            file_put_contents('download/'.$filename, $ef);
            $units = array('B', 'KB', 'MB');
            $bytes = filesize('download/'.$filename);
            if ($bytes >= 1048576)
            {
                $bytes = number_format($bytes / 1048576, 2) . ' MB';
            }
            else
            {
                $bytes = number_format($bytes / 1024, 2) . ' KB';
            }
            $size = $bytes;
            $result = array('name' => $filename, 'size' => $size, 'link' => 'download.php?download_file='.$filename);
        }
    }
}
?>
<html>
<?php include('meta.php'); ?>
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
                                <li><a href="index.php">Home</a></li>
                                <li><a href="encode.php">Encrypter</a></li>
                                <li class="active"><a href="decode.php">Decrypter</a></li>
                                <li><a href="logout.php">Log out</a></li>
                            </ul>
                        </div>
                    </nav>
                    <h5 class="center-align">Decrypter</h5>
                    <p class="center-align">Please insert file & secret key in below: </p>
                    <div class="box-form">
                        <div id="file" class="col s12">
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                                <div class="file-field input-field col s12">
                                    <div class="btn">
                                        <span>File</span>
                                        <input name="file" type="file" id="upload-file">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text" placeholder="Upload one file">
                                    </div>
                                </div>
                                <div class="input-field col s12">
                                    <input name="secretkey" placeholder="Please provide your secret key" id="secret_key" type="password" class="validate" value="<?php if (isset($_FILES['file'])): echo $key; endif; ?>">
                                    <label for="first_name">Secret Key</label>
                                </div>
                                <div class="input-field col s12">
                                    <button class="btn waves-effect waves-light" type="submit" name="action">Decrypt
                                        <i class="mdi mdi-login right"></i>
                                    </button>
                                    <button class="btn waves-effect waves-light" type="reset" name="action">Reset
                                        <i class="mdi mdi-close right"></i>
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
<?php if($result): ?>
    <div id="download-file" class="modal modal-fixed-footer" style="width: 30% !important;">
        <div class="modal-content">
            <div class="center-align">
                <h4>Result encryption</h4>
                <p><img src="img/icon.lock.png"> </p>
                <p><?=$result['name']?></p>
                <p><?=$result['size']?></p>
                <a href="<?=$result['link']?>" class="waves-effect waves-light btn-large"><i class="mdi mdi-download right"></i>Download</a>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
    </div>
    <script type="text/javascript">
        $('#download-file').openModal();
    </script>
<?php endif; ?>
<script type="text/javascript">
    var _validFileExtensions = [".evo"];
    $("#upload-file").bind('change', function() {
        var fileName = $(this).val();
        if (fileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (fileName.substr(fileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
            if (!blnValid) {
                Materialize.toast('Sorry file is invalid, allowed extensions are: ' + _validFileExtensions.join(", "), 2500);
                $(this).val('');
                return false;
            }
        }
    });
    <?php if($data['msg']): ?>
        Materialize.toast('<?=$data['msg']?>', 2500);
    <?php endif; ?>
</script>
</body>
</html>

