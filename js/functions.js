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

var login = function () {
    var v1 = $.trim($('#userr').val());
    var v2 = $.trim($('#passs').val());
    if (!v1 || !v2){
        $('#loginOutput').html("Missing fields");
        if (!v1) {
            $('#userr').css("border-color", "#DF3D82");
        }
        if (!v2){
            $('#passs').css("border-color", "#DF3D82");
        }
        return;
    }

    $('#loginOutput').html("Trying");
    var data = new FormData();
    data.append('submit', true);
    data.append('userr', $('#userr').val());
    data.append('passs', $('#passs').val());

    var connect = new XMLHttpRequest();
    connect.onreadystatechange = function(){
        if(connect.readyState == 4 && connect.status == 200){
            $('#loginOutput').html(connect.responseText);
            if (connect.responseText == "Login Successful!"){
                setTimeout(function(){
                    window.location.href = "/index.php";
                }, 500);
                $('#userr').css("border-color", "#43df0c");
                $('#passs').css("border-color", "#43df0c");
            }else{
                $('#passs').css("border-color", "#DF3D82");
                $('#userr').css("border-color", "#DF3D82");
            }
        }
    };
    connect.open('POST', 'php-functions/login.php', true);
    connect.send(data);


};

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
$('#loginSubmit').click(login);
$("#userFile").change(function () {
    var size = _file.files[0].size;
    if (size > 262144000){
        $('#submitNow').prop('disabled', true);
        $('#uploadName').css('color', '#df0719');
    }else{
        $('#uploadName').css('color', '#14df2a');
        $('#submitNow').prop('disabled', false);
    }

    var strr = _file.files[0].name;
    strr += '[' + parseFloat(_file.files[0].size/1000000).toFixed(2)+ ' MB]';
    //_name.innerHTML = _file.files[0].name + '[' + parseFloat(_file.files[0].size/1000000).toFixed(2)+ ' MB]';
    _name.innerHTML = strr;

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
$('#loginForm').on('click', function(e){
    e.preventDefault();
});

