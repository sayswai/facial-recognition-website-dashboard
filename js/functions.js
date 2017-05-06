/**
 * Created by Wai on 5/5/2017.
 */

var _submit = document.getElementById('submitNow'),
    _file = document.getElementById('userFile'),
    _progress = document.getElementById('progressBar'),
    _output = document.getElementById('uploadResult'),
    _name = document.getElementById('uploadName'),
    _percent = document.getElementById('uploadPercent');

var upload = function(){


    if(_file.files.length === 0){
        _name.innerHTML = "Please choose a file first";
        return;
    }
    _output.innerHTML = "Upload started: ";

    var data = new FormData();
    data.append('userUpload', _file.files[0]);
    data.append('submit', true);

    var connect = new XMLHttpRequest();
    connect.onreadystatechange = function(){
        if(connect.readyState == 4 && connect.status == 200){
            $('#outputWrapper').show();
            $('#realOutput').html(connect.responseText);
        }
    };

    connect.upload.addEventListener('progress', function(e){
        _progress.style.width = Math.round((e.loaded / e.total) * 100) + '%';
        _percent.innerHTML = Math.round((e.loaded / e.total) * 100) + '%';
    }, false);

    connect.upload.addEventListener('load', function(e) {
        _output.innerHTML = "Upload complete";
        $("#newUpload").show();
    }, false);

    connect.open('POST', 'php-functions/upload.php', true);
    connect.send(data);
}


var logoff = function(){
    $('#logOff').fadeOut();
    var connect = new XMLHttpRequest();
    var data = new FormData();
    data.append('submit', true);
    connect.onreadystatechange = function(){
        if(connect.readyState == 4){
            try {
                var resp = JSON.parse(connect.response);
            } catch (e){
                var resp = {
                    status: 'error caught',
                    data: 'Unknown error occurred: ' + connect.responseText +''
                };
            }
            console.log(resp.status + ': ' + resp.data);
        }
    };
    connect.open('POST', 'php-functions/logoff.php');
    connect.send(data);
};



$('#logOff').click(logoff);
$("#submitNow").click(upload);
$("#userFile").change(function () {
    _name.innerHTML = _file.files[0].name;
});
$('#newUpload').click(function () {
    document.getElementById('userFile').value = "";
    document.getElementById('uploadName').innerHTML = "";
    document.getElementById('uploadPercent').innerHTML = "";
    document.getElementById('progressBar').style.width = '0%';
    document.getElementById('uploadResult').innerHTML = "";


    $('#realOutput').html("");
    $(this).hide();
});

