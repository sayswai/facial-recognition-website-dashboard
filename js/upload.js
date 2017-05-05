/**
 * Created by saysw on 5/3/2017.
 */
var _submit = document.getElementById('submitNow'),
    _file = document.getElementById('userFile'),
    _progress = document.getElementById('progressBar'),
    _output = document.getElementById('uploadResult'),
    _name = document.getElementById('uploadName'),
    _percent = document.getElementById('uploadPercent');

var upload = function(){
    if(_file.files.length === 0){
        return;
    }
    _output.innerHTML = "Upload started: ";

    var data = new FormData();
    data.append('userUpload', _file.files[0]);
    data.append('submit', true);

    var connect = new XMLHttpRequest();
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

    connect.upload.addEventListener('progress', function(e){
        _progress.style.width = Math.ceil(e.loaded / e.total) * 100 + '%';
        _percent.innerHTML = Math.ceil(e.loaded / e.total) * 100 + '%';
    }, false);

    connect.upload.addEventListener('load', function(e) {
        _output.innerHTML = "Upload complete";
    }, false);

    connect.open('POST', 'php-functions/upload.php');
    connect.send(data);
}

var namechange = function () {
    _name.innerHTML = _file.files[0].name;
}

_submit.addEventListener('click', upload);
_name.addEventListener('mouseout', namechange);