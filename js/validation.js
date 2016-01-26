/**
 * Created by sikasep on 1/26/16.
 */
$('#formFile').validate({
    rules: {
        secretkey: {
            required: true,
            minlength: 8
        },
        file: {
            required: true,
        }
    },
    messages: {
        secretkey: {
            required: "The secret key is required",
            minlength: "The secret key must have at least 8 characters"
        },
        file: {
            required: "The file is required",
        }
    },
    errorElement : 'div',
    errorLabelContainer: '.errorTxt'
});
var _validFileExtensions = [".txt"];
var _validEvo = [".evo"];
$("#decrypt-file").bind('change', function() {
    var fileName = $(this).val();
    if (fileName.length > 0) {
        var blnValid = false;
        for (var j = 0; j < _validEvo.length; j++) {
            var sCurExtension = _validEvo[j];
            if (fileName.substr(fileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                blnValid = true;
                break;
            }
        }
        if (!blnValid) {
            Materialize.toast('Sorry file is invalid, allowed extensions are: ' + _validEvo.join(", "), 2500);
            $(this).val('');
            return false;
        }
    }
});
$("#upload-file").bind('change', function() {
    var fileName = $(this).val();
    var fileSize = this.files[0].size;
    console.log(this.files[0].type);
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
            Materialize.toast('Sorry file is invalid, allowed extensions are: ' + _validFileExtensions.join(", "),3000);
            $(this).val('');
            return false;
        }
        else if(fileSize > 1000000) {
            Materialize.toast('Sorry file size is bigger, max file size are: 1MB ', 3000);
            $(this).val('');
        }
    }
});